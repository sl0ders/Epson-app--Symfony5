<?php

namespace App\Controller\Admin;

use App\Datatables\OrderCartridgeDatatable;
use App\Entity\Cartridge;
use App\Entity\OrderCartridge;
use App\Form\OrderCartridgeType;
use App\Repository\CartridgeRepository;
use App\Repository\OrderCartridgeRepository;
use Exception;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/ordercartridge")
 */
class OrderCartridgeController extends AbstractController
{
    private $cartridgeRepository,$orderCartridgeRepository,$datatableFactory,$datatableResponse;

    public function __construct(
        CartridgeRepository $cartridgeRepository,
        OrderCartridgeRepository $orderCartridgeRepository,
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse
    )
{
    $this->cartridgeRepository = $cartridgeRepository;
    $this->orderCartridgeRepository = $orderCartridgeRepository;
    $this->datatableFactory = $datatableFactory;
    $this->datatableResponse = $datatableResponse;
}

    /**
     * @Route("/new", name="admin_order_cartridge_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        // Creation of a new cartridge order for a form new
        $orderCartridge = new OrderCartridge();
        $form = $this->createForm(OrderCartridgeType::class, $orderCartridge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orderCartridge);
            $entityManager->flush();
            return $this->redirectToRoute('user_order_cartridge_index');
        }
        return $this->render('Admin/order_cartridge/new.html.twig', [
            'order_cartridge' => $orderCartridge,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_order_cartridge_show", methods={"GET"})
     * @param OrderCartridge $orderCartridge
     * @return Response
     */
    public function show(OrderCartridge $orderCartridge): Response
    {
        return $this->render('Admin/order_cartridge/show.html.twig', [
            'order_cartridge' => $orderCartridge,
        ]);
    }

    /**
     * this function retrieve all cartridge order by cartridge
     * @Route("cartridge/{id}", name="admin_order_cartridge_orders", methods={"GET"})
     * @param Cartridge $cartridge
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function showByCartridge(Cartridge $cartridge, Request $request): Response
    {
        // Variable initialize
        $isAjax = $request->isXmlHttpRequest();
        // Datatable initialize
        $datatable = $this->datatableFactory->create(OrderCartridgeDatatable::class);
        $datatable->buildDatatable();
        if ($isAjax) {
            $responseService = $this->datatableResponse;
            $responseService->setDatatable($datatable);
            // change of datatable result with an custom querybuilder
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('cartridge = :cartridge');
            $qb->setParameter('cartridge', $cartridge);
            return $responseService->getResponse();
        }
        return $this->render("Admin/order_cartridge/showOrders.html.twig", [
            "datatable" => $datatable,
            "cartridge" => $cartridge
        ]);
    }

    /**
     * @Route("/{id}", name="admin_order_cartridge_delete", methods={"DELETE"})
     * @param Request $request
     * @param OrderCartridge $orderCartridge
     * @return Response
     */
    public function delete(Request $request, OrderCartridge $orderCartridge): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderCartridge->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orderCartridge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_order_cartridge_index');
    }
}
