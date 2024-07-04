<?php

if (!function_exists('debug')) {
    function debug($context)
    {
        echo "<pre>";
        var_dump($context);
        echo "</pre>";

        exit;
    }
}