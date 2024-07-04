<?php
include_once "config.php";
include_once "helpers.php";
include_once "vendor/autoload.php";

spl_autoload_register( function($nameSpace) {
    require str_ireplace("\\", "/", $nameSpace). ".php";
});

include_once "routes/web.php";

