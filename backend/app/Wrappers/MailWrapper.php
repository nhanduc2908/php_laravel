<?php

namespace App\Wrappers;

use PHPMailer\PHPMailer\PHPMailer;

class MailWrapper
{
    protected $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = env('MAIL_HOST');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = env('MAIL_USERNAME');
        $this->mailer->Password = env('MAIL_PASSWORD');
        $this->mailer->SMTPSecure = env('MAIL_ENCRYPTION');
        $this->mailer->Port = env('MAIL_PORT');
        $this->mailer->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    }

    public function to($email, $name = null)
    {
        $this->mailer->addAddress($email, $name);
        return $this;
    }

    public function subject($subject)
    {
        $this->mailer->Subject = $subject;
        return $this;
    }

    public function body($body)
    {
        $this->mailer->isHTML(true);
        $this->mailer->Body = $body;
        return $this;
    }

    public function send()
    {
        return $this->mailer->send();
    }
}