<?php


namespace App\Services;


use App\Entity\Notification;
use App\Repository\ConsumableRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificationServices
{
    private $notificationRepository, $userRepository, $tokenStorage, $em, $consumableRepository;

    public function __construct(
        NotificationRepository $notificationRepository,
        UserRepository $userRepository,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $em,
        ConsumableRepository $consumableRepository
    )
    {
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->consumableRepository = $consumableRepository;
    }

    /*We determine the message to be notified, the user who sends and the user who will receive the notification, the fourth
     parameter is optional, it determines the path to which we will be directed by clicking on this notification*/
    public function newNotification($message, $sender, $receivers, $path = []): ?bool
    {
        $date = new DateTime();
        // This date is the time limit before filing the notification
        $dateM = $date->modify("+6 month");
        $datesync = new DateTime();
        $datesync = $datesync->format("d-m-Y H:i:s");
        $dateFinal = new DateTime($datesync);

        // we check if this notification exists, and if it does not exist we create a new one
        $notification = $this->notificationRepository->findOneBy(['message' => $message]);
        if (!isset($notification)) {
            $notification = new Notification();
            $notification->setIsEnabled(true);
            $notification
                ->setCreatedAt($dateFinal)
                ->setMessage($message)
                ->setSender($sender)
                ->setReceiver($receivers)
                ->setExpirationDate($dateM);
            if ($path) {
                //the first parameter of option is the path name and the second is the id
                $notification->setPath($path[0]);
                $notification->setIdPath($path[1]);
            }
            $this->em->persist($notification);
        }
        return true;
    }


    /**
     * This function determines if there has been printer activity for the last ten days, if not then a notification is sent
     * @param $message
     * @param $printer
     * @param $sixLastConsumable
     * @param $sender
     * @return bool
     */
    public function notificationOfPrintAnomaly($message, $printer, $sixLastConsumable, $sender): bool
    {
        $dateM = new DateTime();
        $dateM = $dateM->modify("+6 month");
        $date = $sixLastConsumable[0]->getDateUpdate();
        $date = $date->format("d/m/Y H:i:s");
        $printer->setState(0);
        $notification = $this->notificationRepository->findBy(['message' => $message]);
        if (!$notification) {
            $notification = new Notification();
            $notification
                ->setIsEnabled(true)
                ->setExpirationDate($dateM)
                ->setSender($printer->getCompany())
                ->setReceiver($sender)
                ->setCreatedAt($date)
                ->setMessage($message);
            $this->em->persist($notification);
            return true;
        }
    }
}
