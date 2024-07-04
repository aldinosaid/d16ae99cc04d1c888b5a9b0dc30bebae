<?php

namespace Cores;

class Request {
    
    public function header()
    {
        return json_decode(json_encode(apache_request_headers()));
    }

    public function body()
    {
        return json_decode(json_encode($this->__body()));
    }

    protected function __body()
    {
        $headers = $this->header();
        $contentType = $headers->{'Content-Type'} ?? null;
        if (sizeof($_POST) > 0) {
            return $_POST;
        } else {
            if ($contentType === 'application/json') {
                return file_get_contents('php://input');
            } else {
                return file_get_contents('php://input');
            }
        }
    }

    public function validate($args)
    {
        $params = (array)json_decode($this->__body());
        foreach ($args as $key => $value) {
            if ($value === "required" && !array_key_exists($key, $params)) {
                $return['data'] = [
                    'message' => "$key is $value",
                    'status' => 'error'
                ];
                die(json_encode($return));
            } else {
                continue;
            }
        }
    }
}