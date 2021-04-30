<?php

use PHPMailer\PHPMailer\PHPMailer;

class Mail extends PHPMailer
{
    function  __construct()
    {
        $this->isSMTP();
        $this->SMTPDebug = 0;
        $this->CharSet  = 'UTF-8';
        $this->Host = '';
        $this->Port = 25;
        $this->SMTPSecure = 'tls';
        $this->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $this->SMTPAuth = true;
        $this->Username = '';
        $this->Password = '';
    }
}
