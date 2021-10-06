<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ParameterController
 * @package App\Controller
 * @Route("/parameters")
 */
class ParameterController extends AbstractController
{
    /**
     * Page of parameters
     * @Route("/", name="user_parameter_index")
     */
    public function index(): Response
    {
        return $this->render('User/parameter/index.html.twig', [
            'controller_name' => 'ParameterController',
        ]);
    }
}
