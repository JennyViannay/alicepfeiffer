<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Contact;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailAfterContact(Contact $contact): void
    {
        $email = (new TemplatedEmail())
            ->from('jenny.viannay75@gmail.com')
            //TODO: Change ->to('alicepfeiffer@gmail.com')
            ->to('jennyngaby@gmail.com')
            ->subject('New message from alicepfeiffer.com')
            ->html(
                '<p>' .$contact->getEmail() .'</h4> vous a envoyé un message:</p>'.
                '<p>Sujet: '.$contact->getSubject().'</p>'.
                '<p>'.$contact->getMessage().'</p>'
            );

        $this->mailer->send($email);
    }
}
