<?php

namespace App\Controller\User;

use App\Repository\CompanyRepository;
use App\Repository\ConsumableDeltaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller\User
 */
class DashboardController extends AbstractController
{
    private $deltaRepository,$userRepository;

    public function __construct(
        ConsumableDeltaRepository $deltaRepository,
        UserRepository $userRepository
    )
    {
        $this->deltaRepository = $deltaRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * This page displays the companies for the reseller and the printers for the customer for the customer
     * @Route("/user", name="dashboard")
     * @param CompanyRepository $companyRepository
     * @return Response
     */
    public function index(CompanyRepository $companyRepository): Response
    {
        $user = $this->userRepository->find($this->getUser());
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $companies = $companyRepository->findAll();
        } else {
            $companies = $user->getCompany();
        }
        return $this->render('User/dashboard.html.twig', [
            "companies" => $companies
        ]);
    }
}
