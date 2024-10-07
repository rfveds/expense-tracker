<?php

declare(strict_types=1);

namespace App\Mail;

use App\Config;
use App\Entity\PasswordReset;
use App\SignedUrl;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\BodyRendererInterface;

readonly class ForgotPasswordEmail
{
    public function __construct(
        private Config $config,
        private MailerInterface $mailer,
        private BodyRendererInterface $renderer,
        private SignedUrl $signedUrl
    ) {
    }

    public function send(PasswordReset $passwordReset): void
    {
        $email     = $passwordReset->getEmail();
        $resetLink = $this->signedUrl->fromRoute(
            'password-reset',
            ['token' => $passwordReset->getToken()],
            $passwordReset->getExpiration()
        );
        $message   = (new TemplatedEmail())
            ->from($this->config->get('mailer.from'))
            ->to($email)
            ->subject('Your Expense Tracker Password Reset Instructions')
            ->htmlTemplate('emails/password_reset.html.twig')
            ->context(
                [
                    'resetLink' => $resetLink,
                ]
            );

        $this->renderer->render($message);

        $this->mailer->send($message);
    }
}