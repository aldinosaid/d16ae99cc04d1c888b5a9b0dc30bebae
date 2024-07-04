<?php

namespace Controllers;

use Databases\PostgreSql;
use Services\EmailService;

class QueueController extends BaseController {

    function __construct(){}

    public function index() {
        $pdo = (new PostgreSql)->Pgsql();
        $query = $pdo->prepare("SELECT * FROM queue where status='pending'");
        $query->execute();
        $queryResults = json_decode(json_encode($query->fetchAll()));

        if ($queryResults) {
            foreach ($queryResults as $queryResult) {
                $emailArgs = json_decode($queryResult->email_args);
                $emailService = new EmailService($emailArgs);
                $send = $emailService->send();
                if ($send) {
                    $status = 'success';
                } else {
                    $status = 'failed';
                }

                $updateQuery = $pdo->prepare("Update queue set status='$status'");
                $updateQuery->execute();
            }
        }
        
    }

}