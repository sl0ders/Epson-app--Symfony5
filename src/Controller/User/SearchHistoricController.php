<?php

namespace App\Controller\User;

use App\Entity\Printer;
use App\Form\SearchFormType;
use App\Repository\CompanyRepository;
use App\Repository\ConsumableDeltaRepository;
use App\Repository\ConsumableRepository;
use App\Repository\PrinterRepository;
use App\Repository\UserRepository;
use App\Services\ChartServices;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchHistoricController extends AbstractController
{
    private
        $consumableRepository, $paginator, $entity, $chartServices, $deltaRepository, $printerRepository,
        $companyRepository, $userRepository;

    public function __construct(
        UserRepository $userRepository,
        ConsumableRepository $consumableRepository,
        CompanyRepository $companyRepository,
        PrinterRepository $printerRepository,
        ConsumableDeltaRepository $deltaRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $entity,
        ChartServices $chartServices
    )
    {
        $this->consumableRepository = $consumableRepository;
        $this->paginator = $paginator;
        $this->entity = $entity;
        $this->chartServices = $chartServices;
        $this->deltaRepository = $deltaRepository;
        $this->printerRepository = $printerRepository;
        $this->companyRepository = $companyRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * TODO A optimiser !!
     * @Route("/consumable", name="user_search_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = $this->userRepository->find($this->getUser());
        $resultsConsumable = [];
        $resultsDeltas = [];
        // create form search printer
        $formSearch = $this->createForm(SearchFormType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->get('printer')->getData()) {
            $resultsConsumable = $this->consumableRepository->findAllSixtyCompanyEnabled();
            $resultsDeltas = $this->deltaRepository->findAllSixtyCompanyEnabled();
        }
        $params['printer'] = $formSearch->get('printer')->getData();
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $params['company'] = $formSearch->get('company')->getData();
        } else {
            $params["company"] = $user->getCompany();
        }
        $params['start'] = $formSearch->get('dateStart')->getData();
        $params['end'] = $formSearch->get('dateEnd')->getData();

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            if ($formSearch->get("all")->getData() == true) {
                $params['printer'] = $this->printerRepository->findBy(['company' => $params["company"]]);
            } else {
                $params['printer'] = $formSearch->get('printer')->getData();
            }

            $printer = $params['printer'];
            if ($params['printer'] instanceof Printer) {
                $printer = [$params['printer']];
            }
            $resultsConsumable = $this->consumableRepository->findConsumableForSearchPage($params['company'], $printer, $params['start'], $params['end']);
            $resultsDeltas = $this->deltaRepository->findDeltasForSearchPage($params['company'], $printer, $params['start'], $params['end']);
        }

        $printHistoric = $this->chartServices->printByDate($resultsDeltas);
        $inkHistoric = $this->chartServices->inkByDate($resultsConsumable);

        return $this->render('User/consumable/index.html.twig', [
            'formSearch' => $formSearch->createView(),
            'pagination' => $resultsDeltas,
            'printHistoric' => $printHistoric,
            'inkHistoric' => $inkHistoric,
        ]);
    }
}
