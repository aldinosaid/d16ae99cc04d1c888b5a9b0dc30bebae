<?php

namespace Controllers;

use Controllers\BaseController;
use Cores\Request;
use Databases\PostgreSql;

class ClientController extends BaseController {
    
    protected $body;
    protected $request;

    function __construct()
    {
        $this->request = new Request;
    }

    public function register() {
        $this->request->validate([
            "username" => "required",
            "password" => "requied"
        ]);

        $body = json_decode($this->request->body());

        $username = $body->username ?? null;
        $password = sha1($body->password) ?? null;
        $now = date('Y-m-d H:i:s');

        $pdo = (new PostgreSql)->Pgsql();
        $queryExisting = $pdo->prepare("SELECT * FROM clients where username='$username' and pass='$password'");
        $queryExisting->execute();
        $isExisting = $queryExisting->fetch();

        if ($isExisting) {
            $response = [
                "message" => "Your account already exists",
                "status" => "success"
            ];
        } else {
            $query = $pdo->prepare("INSERT INTO clients (username, pass, created_at) VALUES('$username', '$password', '$now')");
            if ($query->execute()) {
                $response = [
                    "message" => "Your account created successful",
                    "username" => $username,
                    "password" => $body->password,
                    "status" => "success"
                ];
            } else {
                $response = [
                    "message" => "Your account creating failed",
                    "status" => "error"
                ];
            }
        }

        $this->response($response);
    }
}