<?php

namespace Databases;

use PDO;

class PostgreSql {

    protected $conn;

    protected function __connect($host, $port, $dbName, $dbUser, $dbPass)
    {
        return new PDO("pgsql:host=$host;port=$port;dbname=$dbName;", $dbUser, $dbPass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) );
    }

    public function Pgsql() {

        try {
            $host = 'postgres';
            $dbUser = 'admin';
            $dbPass = 'aldinosaid!23';
            $port = '5432';
            $dbName = 'email_service';

            return $this->__connect($host, $port, $dbName, $dbUser, $dbPass);
        } catch (\Throwable $th) {
            $response['data'] = [
                "message" => $th->getMessage(),
                "status"  => "error"
            ];
            debug($response);
        }
    }
}