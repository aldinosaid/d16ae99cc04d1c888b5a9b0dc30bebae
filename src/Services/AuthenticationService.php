<?php

namespace Services;

use Cores\Request;
use Databases\PostgreSql;

class AuthenticationService {

    protected static $headers;

    protected static $body;

    function __construct()
    {
        $request = new Request;
        self::$headers = $request->header();
        self::$body = $request->body();

    }

    public function validate()
    {
        $tokenRequest = self::__getBearerToken();
        if ($tokenRequest) {
            $pdo = (new PostgreSql)->Pgsql();
            $query = $pdo->prepare("SELECT * FROM clients where token='$tokenRequest' and status='validated'");
            $query->execute();
            $queryResult = json_decode(json_encode($query->fetch()));
            if ($queryResult) {
                $token = $queryResult->token;
                if ($token <> $tokenRequest) {
                    $result['data'] = [
                        'message' => "Token doesn't match",
                        "status" => "error"
                    ];
                    die(json_encode($result));
                }

                return true;
            } else {
                $result['data'] = [
                    'message' => "Client not found",
                    "status" => "error"
                ];
                die(json_encode($result));
            }
        } else {
            $result['data'] = [
                'message' => "Token is required!",
                "status" => "error"
            ];
            die(json_encode($result));
        }
    }

    protected static function __getBearerToken() {
        $headers = self::$headers;
        $authorization = $headers->Authorization ?? null;
        if ($authorization) {
            if (preg_match('/Bearer\s(\S+)/', $authorization, $matches)) {
                return $matches[1];
            }
        }
        return false;
    }
}