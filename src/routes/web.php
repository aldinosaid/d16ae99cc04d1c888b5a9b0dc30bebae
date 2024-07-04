<?php
include_once "CustomRoute.php";

use Controllers\AuthController;
use Controllers\EmailController;
use Controllers\QueueController;
use Controllers\ClientController;

CustomRoute::post("register", [ClientController::class, 'register']);
CustomRoute::post('authorize', [AuthController::class, 'index']);
CustomRoute::post('validate', [AuthController::class, 'validation']);
CustomRoute::post('send', [EmailController::class, 'send']);
CustomRoute::post('status', [EmailController::class, 'status']);
CustomRoute::get('execute_queue', [QueueController::class, 'index']);