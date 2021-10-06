<?php

namespace App\Controller\Admin;

use App\Datatables\CompanyDatatable;
use App\Entity\Company;
use App\Form\CompanyType;
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
 * @Route("/admin/company")
 */
class CompanyController extends AbstractController
{
    private $translator,$datatableFactory,$datatableResponse;

    public function __construct(
        TranslatorInterface $translator,
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse
    )
    {
        $this->translator = $translator;
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
    }

    /**
     * @Route("/", name="admin_company_index", methods={"GET"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function index(Request $request): Response
    {
        // Variable initialize
        $isAjax = $request->isXmlHttpRequest();
        // Datatable initialize
        $datatable = $this->datatableFactory->create(CompanyDatatable::class);
        $datatable->buildDatatable();
        if ($isAjax) {
            $responseService = $this->datatableResponse;
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();
            return $responseService->getResponse();
        }
        return $this->render('admin/company/index.html.twig', [
            'datatable' => $datatable
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_company_show",methods={"GET"})
     * @param Company $company
     * @return Response
     */
    public function show(Company $company): Response
    {
        return $this->render('Admin/company/show.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_company_edit",methods={"GET","POST"})
     * @param Request $request
     * @param Company $company
     * @return Response
     */
    public function edit(Request $request, Company $company): Response
    {
        $em = $this->getDoctrine()->getManager();
        // create a form company for modify this
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($company);
            $em->flush();
            // display of a success message
            $this->addFlash("success", "flash.company.updatedSuccessfully");
            return $this->redirectToRoute("admin_company_index");
        }
        return $this->render('Admin/company/edit.html.twig', [
            'company' => $company,
            "form" => $form->createView()
        ]);
    }

    /**
     * This function allows to deactivate or activate companies
     * @Route("/enabled/{id}", name="admin_company_enabled", methods={"PUT","GET"})
     * @param Company $company
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function enabled(Company $company, EntityManagerInterface $em, TranslatorInterface $translator): RedirectResponse
    {
        if ($company->getIsEnabled() === true) {
            $company->setIsEnabled(false);
            $message = $translator->trans("flash.company.text.disabled", [], 'FlashesMessages');
        } else {
            $company->setIsEnabled(true);
            $message = $translator->trans("flash.company.text.enabled", [], 'FlashesMessages');
        }
        $em->persist($company);
        $em->flush();
        $this->addFlash("success", $message);
        // Return at the lasted page
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
