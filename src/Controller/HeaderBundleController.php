<?php


namespace App\Controller;


use App\Entity\Notification;
use App\Repository\CompanyRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * this class manage all system of header
 * Class HeaderBundleController
 * @package App\Controller
 */
class HeaderBundleController extends AbstractController
{
    /**
     * this function Manage the notification system of header
     * @param UserRepository $userRepository
     * @param NotificationRepository $notificationRepository
     * @return Response
     * @throws Exception
     */
    public function getNotification(UserRepository $userRepository, NotificationRepository $notificationRepository): Response
    {
        // retrieve the user connected for reference in condition
        $user = $userRepository->find($this->getUser());
        $notificationDescSorting = [];
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $notificationDescSorting = $notificationRepository->findBy(["isEnabled" => true], ["createdAt" => "ASC"]);
        } else {
           $notifications =  $user->getCompany()->getNotifications();
            $iterator = $notifications->getIterator();
            $iterator->uasort(function ($a, $b) {
                return ($a->getCreatedAt() > $b->getCreatedAt()) ? +1 : 1;
            });
            $notificationIterator = iterator_to_array($iterator);
            /** @var Notification $notification */
            foreach ($notificationIterator as $notification) {
              if ($notification->getIsEnabled() === true) {
                  array_push($notificationDescSorting,$notification);
              }
            }
        }
        return $this->render("Layout/_header.html.twig", [
            "notifications" => $notificationDescSorting
        ]);
    }
}
