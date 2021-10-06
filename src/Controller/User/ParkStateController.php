<?php

namespace App\Controller\User;

use App\Entity\Company;
use App\Entity\OrderCartridge;
use App\Entity\Report;
use App\Form\formSearchCompany;
use App\Form\OrderCartridgeInkType;
use App\Form\ReportType;
use App\Repository\OrderCartridgeRepository;
use App\Repository\RecoveryBacRepository;
use App\Repository\CartridgeRepository;
use App\Repository\CompanyRepository;
use App\Repository\ConsumableRepository;
use App\Repository\PrinterRepository;
use App\Repository\UserRepository;
use App\Services\EmailService;
use App\Services\NotificationServices;
use App\Services\OrderCodeGenerator;
use App\Services\PrinterManager;
use App\Services\ReportCodeGenerator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ParkStateController
 * @package App\Controller
 * @Route("/parkstate")
 */
class ParkStateController extends AbstractController
{

    private $printerRepository, $userRepository, $consumableRepository, $companyRepository, $cartridgeRepository, $printerManager, $em, $averageRepository,
        $codeGenerator, $orderCartridgeRepository, $notificationServices, $translator, $emailService, $reportCodeGenerator;

    public function __construct(
        PrinterRepository $printerRepository,
        UserRepository $userRepository,
        ConsumableRepository $consumableRepository,
        CompanyRepository $companyRepository,
        CartridgeRepository $cartridgeRepository,
        PrinterManager $printerManager,
        EntityManagerInterface $em,
        RecoveryBacRepository $averageRepository,
        OrderCodeGenerator $codeGenerator,
        ReportCodeGenerator $reportCodeGenerator,
        OrderCartridgeRepository $orderCartridgeRepository,
        NotificationServices $notificationServices,
        TranslatorInterface $translator,
        EmailService $emailService
    )
    {
        $this->printerRepository = $printerRepository;
        $this->userRepository = $userRepository;
        $this->consumableRepository = $consumableRepository;
        $this->companyRepository = $companyRepository;
        $this->cartridgeRepository = $cartridgeRepository;
        $this->printerManager = $printerManager;
        $this->em = $em;
        $this->averageRepository = $averageRepository;
        $this->codeGenerator = $codeGenerator;
        $this->orderCartridgeRepository = $orderCartridgeRepository;
        $this->notificationServices = $notificationServices;
        $this->translator = $translator;
        $this->emailService = $emailService;
        $this->reportCodeGenerator = $reportCodeGenerator;
    }

    /**
     * this page gives all the latest information on all printers from all companies
     * @Route("/index", name="park_state_index", methods={"GET", "POST"}, options = {"expose" = true})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // get the administrator company
        $adminCompany = $this->companyRepository->findOneBy(['name' => "AdminCompany"]);
        // find all cartridge
        $cartridges = $this->cartridgeRepository->findAll();
        $orderCartridge = new OrderCartridge();
        // get the user connected in database
        $user = $this->userRepository->find($this->getUser());

        //if an ajax call containing the given "cartridge",
        if ($request->request->get("cartridge")) {
            // retriever the cartridge of request
            $cart = $this->cartridgeRepository->find($request->request->get("cartridge"));
            // retriever the printer of request
            $print = $cart->getPrinter();
            // and retriever the company of request
            $comp = $print->getCompany();
            // creation of an array including the recovered elements
            $result = [
                $cartridge[$cart->getId()] = $cart->getName(),
                $printer[$print->getId()] = $print->getName(),
                $company[$comp->getId()] = $comp->getName(),
            ];
            // and send the json result of this
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        // creation of a new report and new form report
        $report = new Report();
        $em = $this->getDoctrine()->getManager();
        /** @var Company $company */
        $company = $this->getUser()->getCompany();
        $formReport = $this->createForm(ReportType::class, $report, [
            "company" => $company
        ]);
        $formReport->handleRequest($request);
        if ($formReport->isSubmitted() && $formReport->isValid()) {
            $report
                ->setCreatedAt(new DateTime())
                ->setCompany($company)
                ->setReportCode($this->reportCodeGenerator->generate($report))
                ->setStatut(1);
            $em->persist($report);
            $em->flush();
            // and move to the detail of this report for begin to create the first message of this report
            return $this->redirectToRoute("user_report_show", ["id" => $report->getId()]);
        }

        // Creation of an cartridge order
        $form = $this->createForm(OrderCartridgeInkType::class, $orderCartridge);
        $form->handleRequest($request);
        $companies = [];
        if ($form->isSubmitted() && $form->isValid()) {
            //Retrieve of form request for this change in object
            $printerE = $this->printerRepository->findOneBy(['name' => $request->request->get("order_cartridge_ink")["printer"]]);
            $cartridgeE = $this->cartridgeRepository->findOneBy(['name' => $request->request->get("order_cartridge_ink")["cartridge"]]);
            $companyE = $this->companyRepository->findOneBy(['name' => $request->request->get("order_cartridge_ink")["client"]]);
            // creation of  the order cartridge with this three element
            $orderCartridge
                ->setState("order.cartridge.validateType.commanded")
                ->setPrinter($printerE)
                ->setOrderCode($this->codeGenerator->generate($orderCartridge))
                ->SetClient($companyE)
                ->setCartridge($cartridgeE)
                ->setCreatedAt(new DateTime())
                ->setUser($user);
            $this->em->persist($orderCartridge);
            $this->em->flush();

            // create an notification for dealer
            $message = $this->translator->trans(
                "order.cartridge.new", [
                "%company%" => $user->getCompany()->getName(),
                "%cartridge%" => $cartridgeE->getName(),
                "%orderCode%" => $orderCartridge->getOrderCode()
            ],
                "EpsonProjectTrans");

            // I send the notification from the ordering company to the reseller company
            $this->notificationServices->newNotification($message, $user->getCompany(), $adminCompany, ["user_order_cartridge_show", $orderCartridge->getId()]);

            // Create and send a mail for dealer
            $subject = $this->translator->trans(
                "order.cartridge.email.subject", [
                "%nbOrder%" => $orderCartridge->getQuantity(),
                "%cartridge%" => $orderCartridge->getCartridge()->getName(),
                "%company%" => $orderCartridge->getClient()
            ], "EpsonProjectTrans");
            $this->emailService->sendMail($subject, $adminCompany->getEmail(), $company->getUsers()->toArray(), [
                "order" => $orderCartridge,
                "cartridge" => $orderCartridge->getCartridge(),
                "newCartridgeOrder" => true
            ]);
        }
        $this->em->flush();
        $formSearch = $this->createForm(formSearchCompany::class);
        $formSearch->handleRequest($request);
        // Charge all company for one admin User
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            //create a formSearch
            if ($formSearch->isSubmitted() && $formSearch->isValid()) {
                if ($formSearch->get("company")->getData() != null) {
                    $company = $formSearch->get("company")->getData();
                    array_push($companies, $company);
                } else {
                    $companies = $this->companyRepository->findAllOrderByConsumableDate();
                }
            } else {
                $companies = $this->companyRepository->findAllOrderByConsumableDate();
            }
        } else {
            // Charge the company of user if it's not admin
            $companies = [$user->getCompany()];
        }

        return $this->render('User/park_state/index.html.twig', [
            "formReport" => $formReport->createView(),
            'form' => $form->createView(),
            'companies' => $companies,
            'cartridges' => $cartridges,
            'formSearch' => $formSearch->createView()
        ]);
    }
}
