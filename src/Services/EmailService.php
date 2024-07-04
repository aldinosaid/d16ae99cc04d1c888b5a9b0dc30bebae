<?php

namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {

    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function send()
    {

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURITY;
            $mail->Port       = SMTP_PORT;
        
            $mail->setFrom($this->request->from, $this->request->name);
            $mail->addAddress($this->request->to, $this->request->address_name);
            $mail->addReplyTo($this->request->reply_to, $this->request->reply_name);
        
            $mail->isHTML(true);
            $mail->Subject = $this->request->subject;
            $mail->Body    = $this->request->html_content;
        
            $mail->send();
            echo 'Message has been sent';
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
}