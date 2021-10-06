<?php

namespace App\Controller\User;

use App\Datatables\OrderCartridgeDatatable;
use App\Entity\OrderCartridge;
use App\Form\OrderCartridgeType;
use App\Repository\CompanyRepository;
use App\Repository\OrderCartridgeRepository;
use App\Repository\UserRepository;
use App\Services\EmailService;
use App\Services\NotificationServices;
use Doctrine\ORM\EntityManagerInterface;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class OrderCartridgeController
 * @package App\Controller\User
 * @Route("/orderCartridge")
 */
class OrderCartridgeController extends AbstractController
{

    private $userRepository, $orderCartridgeRepository, $datatableFactory, $datatableResponse, $translator,
        $notificationServices, $em, $companyRepository, $emailService;

    public function __construct(
        EmailService $emailService,
        CompanyRepository $companyRepository,
        EntityManagerInterface $em,
        NotificationServices $notificationServices,
        TranslatorInterface $translator,
        UserRepository $userRepository,
        OrderCartridgeRepository $orderCartridgeRepository,
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse
    )
    {
        $this->userRepository = $userRepository;
        $this->orderCartridgeRepository = $orderCartridgeRepository;
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->translator = $translator;
        $this->notificationServices = $notificationServices;
        $this->em = $em;
        $this->companyRepository = $companyRepository;
        $this->emailService = $emailService;
    }

    /**
     * @Route("/", name="user_order_cartridge_index")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request): Response
    {
        // Retrieve the connected user
        $user = $this->userRepository->find($this->getUser());
        $isAjax = $request->isXmlHttpRequest();
        // Datatable initialize
        $datatable = $this->datatableFactory->create(OrderCartridgeDatatable::class);
        $datatable->buildDatatable();
        if ($isAjax) {
            $responseService = $this->datatableResponse;
            $responseService->setDatatable($datatable);
            // if the connected user is administrator, he will see all the orders of all the companies
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $responseService->getDatatableQueryBuilder();
            } else {
                // Otherwise he will only see the orders created by his company
                $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
                $qb = $datatableQueryBuilder->getQb();
                $qb->andWhere('client = :company');
                $qb->setParameter('company', $user->getCompany());
            }
            return $responseService->getResponse();
        }
        return $this->render('User/order_cartridge/index.html.twig', [
            'datatable' => $datatable,
        ]);
    }

    /**
     * @route("/show/{id}", name="user_order_cartridge_show")
     * @param OrderCartridge $orderCartridge
     * @return Response
     */
    public function show(OrderCartridge $orderCartridge): Response
    {
        return $this->render("User/order_cartridge/show.html.twig", [
            "order_cartridge" => $orderCartridge,
        ]);
    }

    /**
     * @route("/edit/{id}", name="user_order_cartridge_edit")
     * @param OrderCartridge $orderCartridge
     * @param Request $request
     * @return Response
     */
    public function edit(OrderCartridge $orderCartridge, Request $request): Response
    {
        // Creation of order cartridge form
        $form = $this->createForm(OrderCartridgeType::class, $orderCartridge);
        // Retrieve of administrator company
        $adminCompany = $this->companyRepository->findOneBy(['name' => "AdminCompany"]);
        // Retrieve the state of order before the submit
        $orderCartridgeBefore = $orderCartridge->getState();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve the state of order after the submit
            $orderCartridgeAfter = $orderCartridge->getState();
            //compare the two values
            if ($orderCartridgeBefore !== $orderCartridgeAfter) {
                //if they are different creation of an notification and email
                // translation of database state
                $state = $this->translator->trans($orderCartridgeAfter, [], "EpsonProjectTrans");
                // creation of the email subject
                $subject = $this->translator->trans(
                    "order.cartridge.state", [
                    "%orderCode%" => $orderCartridge->getOrderCode(),
                    "%cartridge%" => $orderCartridge->getCartridge()->getName()
                ], "EpsonProjectTrans");
                // creatation of notification message
                $message = $this->translator->trans("order.cartridge.changeState.notification", [
                    "%cartridge%" => $orderCartridge->getCartridge()->getName(),
                    "%printer%" => $orderCartridge->getCartridge()->getPrinter()->getName(),
                    "%orderCode%" => $orderCartridge->getOrderCode(),
                    "%nbCartridge%" => $orderCartridge->getQuantity(),
                    "%state%" => $state
                ], "EpsonProjectTrans");
                // Send this notification at the administrator from this user, and go from the path that the notification will give to the click
                $this->notificationServices->newNotification($message, $orderCartridge->getClient(), $adminCompany, ["user_order_cartridge_show", $orderCartridge->getId()]);
                // Email creation send at administrator from client and pass all parameter for this message
                $this->emailService->sendMail($subject, $adminCompany->getEmail(), $orderCartridge->getClient()->getUsers(), [
                    "order" => $orderCartridge,
                    "state" => $state,
                    "orderStatutChange" => true
                ]);
            }
            $this->em->persist($orderCartridge);
            $this->em->flush();
            $this->addFlash('success', "flash.order.editSuccessfully");
            return $this->redirectToRoute("park_state_index");
        }
        return $this->render("User/order_cartridge/edit.html.twig", [
            "order_cartridge" => $orderCartridge,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="user_order_cartridge_delete", methods={"DELETE"})
     * @param Request $request
     * @param OrderCartridge $orderCartridge
     * @return Response
     */
    public function delete(Request $request, OrderCartridge $orderCartridge): Response
    {
        if ($this->isCsrfTokenValid('delete' . $orderCartridge->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orderCartridge);
            $entityManager->flush();
        }
        return $this->redirectToRoute('user_order_cartridge_index');
    }
}
