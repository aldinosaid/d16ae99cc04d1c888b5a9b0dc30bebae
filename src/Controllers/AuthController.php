<?php

namespace Controllers;

use Cores\Request;
use Databases\PostgreSql;

class AuthController extends BaseController {

    public $request;

    public function __construct()
    {
        $request = new Request;
        $this->request = $request;
    }

    public function index() {
        $body = json_decode($this->request->body());
        $username = $body->username ?? null;
        $password = sha1($body->password) ?? null;

        $pdo = (new PostgreSql)->Pgsql();
        $query = $pdo->prepare("SELECT * FROM clients where username='$username' and pass='$password'");
        $query->execute();
        $queryResult = json_decode(json_encode($query->fetch()));
        if ($queryResult) {
            $secretKey = $this->_generateToken(32);
            $token = base64_encode($secretKey."-".$username."-".$password);
            $response = [
                "message" => "Authentication Successful",
                "status" => "success",
                "token" => $token
            ];
            $updateQuery = $pdo->prepare("Update clients set token='$token', secret_key='$secretKey' where id=:id");
            $updateQuery->execute(['id' => $queryResult->id]);
        } else {
            $response = [
                "message" => "Authentication failed",
                "status" => "error"
            ];
        }

        $this->response($response);
    }

    public function validation()
    {
        $body = json_decode($this->request->body());
        $token = $body->token;
        $tokenExplode = explode("-", base64_decode($token));
        $secretKey = $tokenExplode[0]; 
        $username = $tokenExplode[1];
        $password = $tokenExplode[2];

        $pdo = (new PostgreSql)->Pgsql();
        $query = $pdo->prepare("SELECT * FROM clients where username='$username' and pass='$password' and secret_key='$secretKey'");
        $query->execute();
        $queryResult = json_decode(json_encode($query->fetch()));

        if ($queryResult) {
            if ($token === $queryResult->token) {
                $response = [
                    "message" => "Your token has been validated",
                    "status" => "success",
                    "token" => $token
                ];
                
                $updateQuery = $pdo->prepare("Update clients set status='validated' where id=:id");
                $updateQuery->execute(['id' => $queryResult->id]);
            
            } else {
                $response = [
                    "message" => "Your token doesn't match",
                    "status" => "error"
                ];
            }

        } else {
            $response = [
                "message" => "Your token invalid",
                "status" => "error"
            ];
        }

        $this->response($response);
    }

    protected function _generateToken($length = 32)
    {
        $stringSpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $stringLength = strlen($stringSpace);
        $randomString = '';
        for ($i = 0; $i < $length; $i ++) {
            $randomString = $randomString . $stringSpace[rand(0, $stringLength - 1)];
        }
        return $randomString;
    }
}