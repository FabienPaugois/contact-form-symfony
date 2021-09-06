<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use App\Entity\Mail;

class MailManager
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(Mail $mail): bool
    {

        $email = (new Email())
            ->from($mail->getEmail())
            ->to($mail->getService()->getEmail())
            ->subject('Test mail')
            ->text($mail->getContent() . "\n\nEnvoyé a partir de " . $mail->getEmail() . "\n\n" . $mail->getfirstName()  . " " . $mail->getLastName());

            $mail->SetSentAt(new \Datetime());

        $this->mailer->send($email);

        return true;
    }
}