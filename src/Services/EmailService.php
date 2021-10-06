<?php

namespace App\Services;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Class EmailService
 * @package App\Services
 */
class EmailService
{

    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(MailerInterface $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    /**
     * @param String $subject
     * @param String $sender
     * @param User[] $receivers
     * @param array $context []
     */
    public function sendMail(string $subject, string $sender, array $receivers, $context = [])
    {
        // for each user of an company
        foreach ($receivers as $user) {
            //creation of an email
            $email = (new TemplatedEmail())
                ->from($sender)
                ->to($user->getEmail())
                ->replyTo("sl0ders@gmail.com")
                ->subject($subject)
                ->htmlTemplate("Emails/alertAverageInk.html.twig")
                ->context($context);
            try {
                $this->mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                echo 'Exception reçue : ', $e->getMessage(), "\n";
            }
        }
    }
}
