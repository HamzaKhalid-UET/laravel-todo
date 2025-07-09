<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\jwtMiddleware;
use Illuminate\Support\Facades\Route;



// User Routes
Route::post('/user', [UserController::class, 'storeUser']);
Route::post('/login', [UserController::class, 'loginUser']);

// Todo Routes
Route::post('/todo', [TodoController::class, 'storeTodo']);

// Middleware for Todo Routes
Route::middleware([jwtMiddleware::class])->group(function () {
    Route::get('/getusertodo', [TodoController::class, 'getUserTodos']);
});
