<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StatusController;

Route::get('/tickets', [TicketController::class, 'getAll']);
Route::post('/tickets', [TicketController::class, 'create']);
Route::get('/tickets/{id}', [TicketController::class, 'get']);
Route::get('/tickets/status/{status_id}', [TicketController::class, 'getByStatus']);
Route::put('/tickets/{id}', [TicketController::class, 'update']);
Route::delete('/tickets/{id}', [TicketController::class, 'delete']);
Route::get('/tickets/sla', [TicketController::class, 'getSLA']);

Route::get('/categories', [CategoryController::class, 'getAll']);
Route::post('/categories', [CategoryController::class, 'create']);
Route::get('/categories/{id}', [CategoryController::class, 'get']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'delete']);

Route::get('/statuses', [StatusController::class, 'getAll']);
