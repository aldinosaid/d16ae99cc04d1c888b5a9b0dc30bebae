<?php

class CustomRoute {

    public static $method;

    public static $request;

    public static $route = [];

    public static $controller;

    public static function post($routeName, $customFunction) {
        $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_segments = explode('/', $uri_path);

        if ($routeName === $uri_segments[2]) {
            $params = json_decode(json_encode($_GET)) ?? json_decode(json_encode([]));

            self::$method = $_SERVER['REQUEST_METHOD'];

            if (self::$method <> 'POST') {
                self::error("Failed method request");
            }

            if (is_array($customFunction)) {
                if (!class_exists($customFunction[0])) static::error('Method not exists');

                $class = new $customFunction[0];
                $function = $customFunction[1];

                return $class->$function($params);
            } else {
                return $customFunction();
            }
        }
    }

    public static function get($routeName, $customFunction)
    {
        $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_segments = explode('/', $uri_path);

        if ($routeName === $uri_segments[2]) {
            $params = json_decode(json_encode($_GET)) ?? json_decode(json_encode([]));

            self::$method = $_SERVER['REQUEST_METHOD'];

            if (self::$method <> 'GET') {
                self::error("Failed method request");
            }

            if (is_array($customFunction)) {
                if (!class_exists($customFunction[0])) static::error('Method not exists');

                $class = new $customFunction[0];
                $function = $customFunction[1];

                return $class->$function($params);
            } else {
                return $customFunction();
            }
        }
    }

    public static function error($message) {
        $data = [
            'status' => 'error',
            'message' => $message
        ];
        exit(json_encode($data));
    }
}