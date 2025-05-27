<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    private PHPMailer $mail;
    public readonly array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/mail.php';
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = $this->config['mailers']['smtp']['host'];
        $this->mail->Port = $this->config['mailers']['smtp']['port'];
        $this->mail->SMTPSecure = $this->config['mailers']['smtp']['encryption'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->config['mailers']['smtp']['username'];
        $this->mail->Password = $this->config['mailers']['smtp']['password'];
    }

    public function send(string $to, string $subject, string $body): bool
    {
        $this->mail->setFrom($this->config['from']['address'], $this->config['from']['name']);
        $this->mail->addAddress($to);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        return $this->mail->send();
    }
}
