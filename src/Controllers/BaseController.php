<?php

namespace Controllers;

use Services\AuthenticationService;

class BaseController {

    public $auth = null;

    public function __construct()
    {
        $authenticationService = new AuthenticationService;
        if (!$authenticationService->validate()) {
            $response = [
                'message' => 'No Authentication',
                'status' => 'error'
            ];
            $this->response($response);
        }
    }

    /**
     * @param array $response
     */
    public function response ($response) {
        $data['data'] = $response;
        exit(json_encode($data));
    }
}