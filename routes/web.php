<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RabbitMQController;

Route::get('/send', [RabbitMQController::class, 'send']);
Route::get('/consumer', [RabbitMQController::class, 'consumer']);

