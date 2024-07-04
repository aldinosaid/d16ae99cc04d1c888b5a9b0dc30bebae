<?php

namespace Controllers;

use Cores\Request;
use Databases\PostgreSql;

class EmailController extends BaseController {

    protected $request;

    function __construct()
    {
        parent::__construct();

        $request = new Request;
        $this->request = $request;
    }

    public function send() {
        $requestBody = $this->request->body();

        $this->request->validate([
            "from" => "required",
            "name" => "requied",
            "reply_to" => "required",
            "reply_name" => "required",
            "to" => "required",
            "address_name" => "required",
            "subject" => "required",
            "html_content" => "required"
        ]);
        
        $now = date('Y-m-d H:i:s');
        $uuid = sha1($now);
        $pdo = (new PostgreSql)->Pgsql();
        $query = $pdo->prepare("INSERT INTO queue (uuid, created_at, email_args) VALUES('$uuid', '$now', '$requestBody')");
        if ($query->execute()) {
            $response = [
                "message" => "Your message has been queued",
                "uuid" => $uuid,
                "status" => "success"
            ];
        } else {
            $response = [
                "message" => "Your message failed to enter the queue",
                "status" => "error"
            ];
        }

        $this->response($response);
    }

    public function status()
    {
        $requestBody = json_decode($this->request->body());
        $this->request->validate([
            "uuid" => "required"
        ]);

        $pdo = (new PostgreSql)->Pgsql();
        $query = $pdo->prepare("SELECT * FROM queue where uuid='$requestBody->uuid'");
        $query->execute();
        $queryResult = json_decode(json_encode($query->fetch()));

        if ($queryResult) {
            $email = json_decode($queryResult->email_args);
            $result = [
                "uuid" => $queryResult->uuid,
                "email" => [
                    "from" => $email->from,
                    "nama" => $email->name,
                    "reply_to" => $email->reply_to,
                    "reply_name" => $email->reply_name,
                    "to" => $email->to,
                    "address_name" => $email->address_name,
                    "subject" => $email->subject,
                    "html_content" => $email->html_content,
                ],
                "created_at" => $queryResult->created_at,
                "status" => $queryResult->status
            ];
        } else {
            $result = [
                "message" => "Your uuid {$requestBody->uuid} not found",
                "status" => "error"
            ];
        }

        $this->response($result);
    }
}