<?php

namespace App\Controller\User;

use App\Datatables\NotificationDatatable;
use App\Datatables\ReportDatatable;
use App\Entity\MessageReport;
use App\Entity\Report;
use App\Form\MessageReportType;
use App\Form\ReportType;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use App\Services\EmailService;
use App\Services\NotificationServices;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ReportController
 * @package App\Controller\User
 * @Route("/report")
 */
class ReportController extends AbstractController
{
    private $userRepository, $datatableFactory, $datatableResponse, $companyRepository;

    public function __construct(UserRepository $userRepository, DatatableFactory $datatableFactory, DatatableResponse $datatableResponse, CompanyRepository $companyRepository)
    {
        $this->userRepository = $userRepository;
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @Route("/", name="user_report_index")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function index(Request $request): Response
    {
        $user = $this->userRepository->find($this->getUser());
        // Variable initialize
        $isAjax = $request->isXmlHttpRequest();
        // Datatable initialize
        $datatable = $this->datatableFactory->create(ReportDatatable::class);
        $datatable->buildDatatable();
        if ($isAjax) {
            $responseService = $this->datatableResponse;
            $responseService->setDatatable($datatable);
            // if the logged user is an administratror, it retrieve all report
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $responseService->getDatatableQueryBuilder();
            } else {
                // else it retrieve the report related to his company
                $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
                $qb = $datatableQueryBuilder->getQb();
                $qb
                    ->andWhere("company = :userCompany")
                    ->setParameter("userCompany", $user->getCompany());
            }
            return $responseService->getResponse();
        }
        return $this->render('User/report/index.html.twig', [
            'datatable' => $datatable
        ]);
    }

    /**
     * @Route("/show/{id}", name="user_report_show")
     * @param Report $report
     * @param Request $request
     * @param NotificationServices $notificationServices
     * @param EmailService $emailService
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function show(Report $report, Request $request, NotificationServices $notificationServices, EmailService $emailService, TranslatorInterface $translator): Response
    {
        // retrieve the admin company
        $adminCompany = $this->companyRepository->findOneBy(['name' => "AdminCompany"]);
        $em = $this->getDoctrine()->getManager();
        // create a now report message and the form of this
        $reportMessage = new MessageReport();
        $form = $this->createForm(MessageReportType::class, $reportMessage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reportMessage
                ->setCreatedAt(new DateTime())
                // a set the sender with the company of the logged user
                ->setSender($this->getUser()->getCompany());
            $em->persist($reportMessage);
            // add this message report in the report passed in parameter
            $report->addMessageReport($reportMessage);
            $em->persist($report);
            // creation of an email subject
            $subject = $translator->trans("report.notification.subject", ["%company%" => $report->getCompany(), "%code%" => $report->getReportCode()], "EpsonProjectTrans");
            // creation of an message notification
            $message = $translator->trans("report.notification.message", ["%company%" => $report->getCompany(), "%code%" => $report->getReportCode()], "EpsonProjectTrans");
            // if the sender of this message report is the company admin
            if ($reportMessage->getSender() === $adminCompany) {
                // i change the order of sender and receiver of notification and the success message same for the mail
                $notificationServices->newNotification($message, $adminCompany, $report->getCompany(), ["user_report_show", $report->getId()]);
                $this->addFlash("success", "flash.report.messageReport.createdSuccessfullyAtClient");
                $emailService->sendMail($subject, $adminCompany->getEmail(), $report->getCompany()->getUsers()->toArray(), [
                    "reportSendMail" => true,
                    "report" => $report,
                    "message" => $reportMessage
                ]);
            } else {
                $notificationServices->newNotification($message, $report->getCompany(), $adminCompany, ["user_report_show", $report->getId()]);
                $this->addFlash("success", "flash.report.messageReport.createdSuccessfullyAtAdmin");
                $emailService->sendMail($subject, $reportMessage->getSender()->getEmail() , $adminCompany->getUsers()->toArray(), [
                    "reportSendMail" => true,
                    "report" => $report,
                    "message" => $reportMessage
                ]);
            }
            $em->flush();
            return $this->redirectToRoute("user_report_show", ['id' => $report->getId()]);
        }

        return $this->render("User/report/show.html.twig", [
            "report" => $report,
            "form" => $form->createView()
        ]);
    }

    /**
     * each report can be archived
     * @Route("/enabled/{id}", name="user_report_enabled")
     * @param Report $report
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function enabled(Report $report, EntityManagerInterface $em, TranslatorInterface $translator): RedirectResponse
    {
        if ($report->getStatut() === true) {
            $report->setStatut(false);
            $message = $translator->trans("flash.report.disabled", [], 'FlashesMessages');
        } else {
            $report->setStatut(true);
            $message = $translator->trans("flash.report.enabled", [], 'FlashesMessages');
        }
        $em->persist($report);
        $em->flush();
        $this->addFlash("success", $message);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
