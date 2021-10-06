<?php

namespace App\Controller\User;

use App\Datatables\ConsumableDatatable;
use App\Datatables\PrinterDatatable;
use App\Entity\Cartridge;
use App\Entity\Printer;
use App\Entity\RecoveryBac;
use App\Form\PrinterEditType;
use App\Form\PrinterFileType;
use App\Form\PrinterStateType;
use App\Form\PrinterType;
use App\Repository\CartridgeRepository;
use App\Repository\ConsumableDeltaRepository;
use App\Repository\ConsumableRepository;
use App\Repository\NotificationRepository;
use App\Repository\PrinterRepository;
use App\Repository\RecoveryBacRepository;
use App\Repository\UserRepository;
use App\Services\ChartServices;
use App\Services\EmailService;
use App\Services\PrinterManager;
use App\Services\xmlManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PrinterController
 * @package App\Controller
 * @Route("/printer")
 */
class PrinterController extends AbstractController
{
    private $printerRepository, $chartServices, $consumableRepository, $deltaRepository, $em, $printerManager,
        $cartridgeRepository, $datatableFactory, $datatableResponse, $translator, $notificationRepository, $userRepository, $emailService, $recoveryBacRepository;

    public function __construct(
        UserRepository $userRepository,
        PrinterRepository $printerRepository,
        ChartServices $chartServices,
        ConsumableRepository $consumableRepository,
        ConsumableDeltaRepository $deltaRepository,
        EntityManagerInterface $em,
        PrinterManager $printerManager,
        CartridgeRepository $cartridgeRepository,
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse,
        TranslatorInterface $translator,
        NotificationRepository $notificationRepository,
        EmailService $emailService,
        RecoveryBacRepository $recoveryBacRepository
    )
    {
        $this->printerRepository = $printerRepository;
        $this->chartServices = $chartServices;
        $this->consumableRepository = $consumableRepository;
        $this->deltaRepository = $deltaRepository;
        $this->em = $em;
        $this->printerManager = $printerManager;
        $this->cartridgeRepository = $cartridgeRepository;
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->translator = $translator;
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
        $this->emailService = $emailService;
        $this->recoveryBacRepository = $recoveryBacRepository;
    }

    /**
     * @Route("/", name="user_printer_index")
     * @param Request $request
     * @return JsonResponse|Response|null
     * @throws Exception
     */
    public function index(Request $request)
    {
        $user = $this->userRepository->find($this->getUser());
        // Variable initialize
        $isAjax = $request->isXmlHttpRequest();
        // Datatable initialize
        $datatable = $this->datatableFactory->create(PrinterDatatable::class);
        $datatable->buildDatatable();
        if ($isAjax) {
            $responseService = $this->datatableResponse;
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            if (!$this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                //sort results in descending order
                $qb->leftJoin("printer.company", "c");
                $qb->andWhere("company = :company");
                $qb->andWhere("c.isEnabled = true");
                $qb->setParameter("company", $user->getCompany());
                $qb->AddorderBy("printer.updateAt", "DESC");
            }
            return $responseService->getResponse();
        }

        // create form new custom printer
        $formNew = $this->createForm(PrinterType::class);
        //create form printed made xml file
        $formFile = $this->createForm(PrinterFileType::class);
        $formFile->handleRequest($request);
        if ($formFile->isSubmitted() && $formFile->isValid()) {
            // retrieving data from the formFile form
            $file = $formFile['download']->getData();
            //this data is used in parserXml service, it returns an array of data
            $a_print = $this->printerManager->createPrintArrayFromXmlFile($this->getParameter("folder_import") . "" . $file->getClientOriginalName());
            $addPrintObject = $this->printerManager->addPrintWithConsumable($a_print);
            if ($addPrintObject === false) {
                $this->addFlash("danger", "flash.printer.file.exist");
            } else {
                $this->addFlash("success", "flash.printer.file.addSuccessFully");
            }
        }
        return $this->render('User/printer/index.html.twig', array(
            'datatable' => $datatable,
            'formNew' => $formNew->createView(),
            'formFile' => $formFile->createView()
        ));
    }

    /**
     * This function add all xml file of xml folder in database via one button of printer index
     * @Route("/add", name="user_printer_addAllFiles")
     * @param xmlManager $managerXml
     * @return RedirectResponse
     * @throws Exception
     */
    public function addAllFiles(xmlManager $managerXml): RedirectResponse
    {
        $a_averages = [];
        /* recovery of all xml file name contained in xml folder */
        $a_printsName = $managerXml->receiptAllFilesName();
        // for each fileName
        foreach ($a_printsName as $printName) {
            // creation of array with all information of file
            $prints = $this->printerManager->createPrintArrayFromXmlFile($printName);
            $this->printerManager->addPrintWithConsumable($prints);
            $this->em->flush();
        }

        //For each printer and each consumable, creation of delta by consumables line
        $printers = $this->printerRepository->findAll();
        ## Create deltas ,  Email + notification ##
        foreach ($printers as $printer) {
//            $consumables = $this->printerManager->sortingByDesc($printer->getConsumables());
            $consumables = $this->consumableRepository->findBy(["print" => $printer], ["date_update" => "DESC"]);
            // Function of deltas calcul
            $this->printerManager->addDeltas($consumables, $printer);
            $this->em->flush();
            // Function of averages calcul for each delta
            $a_average = $this->printerManager->averageCalculationWeighted($printer);

            //retrieve the four last cartridge of this print
            $lastCartridges = $this->cartridgeRepository->findLastCartridgeByPrint($printer);
            // this array retrieve the last level listed by date for each color and for the drip tray "c.MBR", 'c.black', "c.cyan", "c.magenta", "c.yellow", "c.date_update"
            $inkslvls = $this->consumableRepository->findLastLvlByPrint($printer);
            // this function calculthe number of pages remaining for each of its properties
            $restPageOnCartridge = $this->printerManager->calculationCartridgeReplacement($printer);
            // calcul the percent of rest in retrieve bac
            $restBac = 100 - $inkslvls["MBR"];

            /** @var Cartridge $lastCartridge */
            foreach ($lastCartridges as $lastCartridge) {
                //ink remaining in the cartridge%
                $a_average["rest_ink_for_" . $lastCartridge->getColor()] = $inkslvls[$lastCartridge->getColor()];
                // ink used
                $restCartridge = 100 - $a_average["rest_ink_for_" . $lastCartridge->getColor()];
                // Number of days remaining for the cartridge
                $a_average["rest_day_for_" . $lastCartridge->getColor()] = $restCartridge / $a_average['average_by_day_for_' . $lastCartridge->getColor()];
                $a_average["rest_print_for_" . $lastCartridge->getColor()] = $restPageOnCartridge[$lastCartridge->getColor()];
                /*
                Calculate black and white and color printing average I take the printing average for an entire cartridge and I divide by the printing average per day
                and I get the remaining print count for a cartridge.
                */
                $a_average['rest_print_bw_for_' . $lastCartridge->getColor()] = $restPageOnCartridge[$lastCartridge->getColor()] / $a_average["average_by_day_for_bwPrint"];
                $a_average['rest_print_color_for_' . $lastCartridge->getColor()] = $restPageOnCartridge[$lastCartridge->getColor()] / $a_average["average_by_day_for_colorPrint"];
                // we take advantage of the loop to create the ink cartridges
                $lastCartridge
                    ->setRestPrintColor(round($a_average["rest_print_color_for_" . $lastCartridge->getColor()], 1))
                    ->setUseByDay(round($a_average["average_by_day_for_" . $lastCartridge->getColor()], 2))
                    ->setRestPrints(round($a_average["rest_print_for_" . $lastCartridge->getColor()], 1))
                    ->setRestDays(round($a_average["rest_day_for_" . $lastCartridge->getColor()], 1))
                    ->setRestPrintBw(round($a_average["rest_print_bw_for_" . $lastCartridge->getColor()], 1))
                    ->setRestInkPercent($a_average["rest_ink_for_" . $lastCartridge->getColor()]);
                $this->em->persist($lastCartridge);
            }
            // Calculates the number of days remaining before the end of the cartridge
            $a_average["rest_prints_for_bac"] = $restPageOnCartridge['bac'];
            $a_average["rest_days_for_bac"] = $restBac / $a_average['average_by_day_for_bac'];
            array_push($a_averages, $a_average);
            // Creation of RecoveryBac object
            $recoveryBac = $this->recoveryBacRepository->findOneBy(['printer' => $printer]);
            $recoveryBac
                ->setPrinter($printer)
                ->setRestBacPercent($inkslvls["MBR"])
                ->setUseByDay(round($a_average['average_by_day_for_bac'], 2))
                ->setRestDays(round($a_average['rest_days_for_bac'], 2))
                ->setRestprints(round($a_average['rest_prints_for_bac'], 2));
            $this->em->persist($recoveryBac);
            $this->em->flush();
            $this->printerManager->mailAndNotification($printer);
        }
        $this->em->flush();
        $this->addFlash("success", "flash.printer.allFile.updateSuccessfully");
        return $this->redirectToRoute("user_printer_index");
    }

    /**
     * This function retrieve all information for this printer
     * @Route("/show/{id}", name="user_printer_show")
     * @param Printer $printer
     * @param Request $request
     * @return Response|null
     * @throws Exception
     */
    public function show(Printer $printer, Request $request): ?Response
    {
        $date = new DateTime();
        $dateLeastMonth = $date->modify("-3 month");
        // Variable initialize
        // retrieve the cartridges of this printer
        $cartridges = $this->cartridgeRepository->findBy(["printer" => $printer]);
        // retrieve the consumable of today for this printer
        $consumationToday = $this->deltaRepository->findLastConsumableDeltaByPrint($printer);
        // retrieve the consumable located between today's date and this modified date 3 months
        $consumables = $this->consumableRepository->findConsumableByPrinter($printer, $dateLeastMonth);
        // retrieve the deltas located between today's date and this modified date 3 months
        $deltas = $this->deltaRepository->findThreeMonthLast($printer, $dateLeastMonth);
        // retrieve all last lvls of each color and retrieve bac for this printer
        $lvls = $this->consumableRepository->findLastLvlByPrint($printer);
        // Retrieve the recovery average of this print
        $a_average = $this->getDoctrine()->getRepository(RecoveryBac::class)->findOneBy(["printer" => $printer]);
        // creation of chart with printer data
        $printHistoric = $this->chartServices->printByDate($deltas);
        $inkHistoric = $this->chartServices->inkByDate($consumables);
        // retrieves of rest page for each cartridge for this print
        $restPageOnCartridge = $this->printerManager->calculationCartridgeReplacement($printer);
        $this->em->flush();

        $isAjax = $request->isXmlHttpRequest();
        // DatatableConsumable initialize
        $datatableConsumable = $this->datatableFactory->create(ConsumableDatatable::class);
        $datatableConsumable->buildDatatable();
        if ($isAjax) {
            $responseService = $this->datatableResponse;
            $responseService->setDatatable($datatableConsumable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            //sort results in descending order
            $qb->where("consumable.print = :printer")
                ->setParameter(':printer', $printer);
            return $responseService->getResponse();
        }
        return $this->render("User/printer/show.html.twig", [
            'bacLvl' => $lvls['MBR'],
            'printHistoric' => $printHistoric,
            'inkHistoric' => $inkHistoric,
            "printer" => $printer,
            "deltas" => $deltas,
            "cartridges" => $cartridges,
            "consumableDatatables" => $datatableConsumable,
            "consumableToday" => $consumationToday,
            "average" => $a_average,
            "restPageOnCartridge" => $restPageOnCartridge
        ]);
    }

    /**
     * @Route("/changeState/{id}", name="user_printer_change_state")
     * @param Printer $printer
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function changeState(Printer $printer, EntityManagerInterface $em, Request $request): Response
    {
        $formPrinterState = $this->createForm(PrinterStateType::class, $printer);
        $formPrinterState->handleRequest($request);
        if ($formPrinterState->isSubmitted() && $formPrinterState->isValid()) {
            $em->persist($printer);
            $em->flush();
            return $this->redirectToRoute("park_state_index");
        }
        return $this->render("User/printer/changeState.html.twig", [
            "printer" => $printer,
            "form" => $formPrinterState->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="user_printer_edit")
     * @param Printer $printer
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Printer $printer, Request $request)
    {
        $form = $this->createForm(PrinterEditType::class, $printer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $printer->setSubname($form->get("subname")->getData());
            $this->em->persist($printer);
            $this->em->flush();
            return $this->redirectToRoute("park_state_index");
        }
        return $this->render("User/printer/edit.html.twig", [
            "printer" => $printer,
            "form" => $form->createView()
        ]);
    }
}
