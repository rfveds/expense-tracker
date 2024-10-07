<?php

declare(strict_types = 1);

namespace App\Mail;

use App\Config;
use App\Entity\User;
use App\SignedUrl;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\BodyRendererInterface;

readonly class SignupEmail
{
    public function __construct(
        private Config $config,
        private MailerInterface $mailer,
        private BodyRendererInterface $renderer,
        private SignedUrl $signedUrl
    ) {
    }

    public function send(User $user): void
    {
        $email          = $user->getEmail();
        $expirationDate = new \DateTime('+30 minutes');
        $activationLink = $this->signedUrl->fromRoute(
            'verify',
            ['id' => $user->getId(), 'hash' => sha1($email)],
            $expirationDate
        );

        $message = (new TemplatedEmail())
            ->from($this->config->get('mailer.from'))
            ->to($email)
            ->subject('Welcome to Expense Tracker')
            ->htmlTemplate('emails/signup.twig')
            ->context(
                [
                    'activationLink' => $activationLink,
                    'expirationDate' => $expirationDate,
                ]
            );

        $this->renderer->render($message);

        $this->mailer->send($message);
    }
}