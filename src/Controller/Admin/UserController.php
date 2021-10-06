<?php

namespace App\Controller\Admin;

use App\Datatables\UserDatatable;
use App\Entity\User;
use App\Form\UserEditForm;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sg\DatatablesBundle\Datatable\DatatableFactory;
use Sg\DatatablesBundle\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("admin/parameters/user")
 */
class UserController extends AbstractController
{
    /**
     * @var DatatableFactory
     */
    private $datatableFactory,$datatableResponse,$encoder;

    public function __construct(
        DatatableFactory $datatableFactory,
        DatatableResponse $datatableResponse,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->datatableFactory = $datatableFactory;
        $this->datatableResponse = $datatableResponse;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(UserRepository $userRepository, Request $request): Response
    {
        // Variable initialize
        $isAjax = $request->isXmlHttpRequest();
        // Datatable initialize
        $datatable = $this->datatableFactory->create(UserDatatable::class);
        $datatable->buildDatatable();
        if ($isAjax) {
            $responseService = $this->datatableResponse;
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();
            return $responseService->getResponse();
        }
        return $this->render('Admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
            "datatable" => $datatable
        ]);
    }

    /**
     * @Route("/enabled/{id}", name="admin_user_isEmailRecipient", requirements={"id"="\d+"})
     * @param User $user
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function enabled(User $user, EntityManagerInterface $em,TranslatorInterface $translator): RedirectResponse
    {
        // check if the user can receive alert emails with isEmailRecipient boolean
        if ($user->getIsEmailRecipient() === true) {
            $user->setIsEmailRecipient(false);
            $message = $translator->trans("flash.user.isNotEmailRecipient", ["%userName%" => $user->getEmail()], 'FlashesMessages');
            $this->addFlash("warning", $message);
        } else {
            $user->setIsEmailRecipient(true);
            $message = $translator->trans("flash.user.isEmailRecipient", ["%userName%" => $user->getEmail()], 'FlashesMessages');
            $this->addFlash("success", $message);
        }
        $em->persist($user);
        $em->flush();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="admin_user_new")
     */
    public function new(Request $request): Response
    {
        //User creation and hash password
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->encoder->encodePassword($user, $form->getData()->getPassword());
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $user->setRoles($form->getData()->getRoles());
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }
        return $this->render('Admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
