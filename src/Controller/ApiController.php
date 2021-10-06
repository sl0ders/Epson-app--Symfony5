<?php


namespace App\Controller;


use App\Datatables\DeltaDatatable;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Repository\PrinterRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/addPrintCompany", name="addPrintByCompany", methods={"GET"}, options = {"expose" = true})
     * @param Request $request
     * @param PrinterRepository $printerRepository
     * @return Response
     */
    public function addPrintByCompany(Request $request, PrinterRepository $printerRepository): Response
    {
        // find the request company from ajax
        $companyId = $request->get('company');
        $companyId = intval($companyId);
        $prints =[];
        // retrieve the printers from the companyId retrieve in ajax request
        $printers = $printerRepository->findBy(['company' => $companyId]);
        // for each printer i initialize an php array for convert to json array
        foreach ($printers as $printer) {
            $prints[$printer->getId()] = $printer->getName();
        }
        // send json response
        $response = new Response(json_encode($prints));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/changeReadAt", name="changeReadAt", methods={"GET"}, options={"explose" = true})
     * @param NotificationRepository $notificationRepository
     * @param Request $request
     * @return Response
     */
    public function changeReadAt(NotificationRepository $notificationRepository, Request $request): Response
    {
        $notificationId = $request->query->get("notification");
        $notification = $notificationRepository->find($notificationId);
        $notification->setReadAt(new DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($notification);
        $em->flush();
        $notif[$notification->getId()] = $notification->getid();
        $response = new Response(json_encode($notif));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
