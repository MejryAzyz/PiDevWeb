<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class MailerService
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger
    ) {
    }

    public function sendEmail(
        string $to,
        string $subject,
        string $content
    ): void {
        try {
            $email = (new Email())
                ->from(new Address('beyaabid876@gmail.com', 'Job Application Support'))
                ->to($to)
                ->subject($subject)
                ->text('This is a plain text version of the email.')
                ->html($content);

            $this->mailer->send($email);
            $this->logger->info('Email sent to ' . $to);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Failed to send email to ' . $to . ': ' . $e->getMessage());
            throw new \RuntimeException('Failed to send email: ' . $e->getMessage());
        }
    }
}
