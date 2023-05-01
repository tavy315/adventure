<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailerSender
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function send(string $email): void
    {
        $message = (new Email())
            ->to(new Address($email))
            ->subject('Welcome')
            ->html('<p>emails.welcome</p>');

        $this->mailer->send($message);
    }
}
