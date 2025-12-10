<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Cart;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->group(function () {

        // AUTH API
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login']);
        Route::post('/logout',   [AuthController::class, 'logout'])->middleware('auth:sanctum');

        // CATEGORY API
        Route::get('/category', [CategoryController::class, 'index']);
        Route::post('/category', [CategoryController::class, 'store'])
            ->middleware(['auth:sanctum', 'role:admin']);

        Route::get('/category/{id}', [CategoryController::class, 'show']);
        Route::put('/category/{id}', [CategoryController::class, 'update'])
            ->middleware(['auth:sanctum', 'role:admin']);

        Route::delete('/category/{id}', [CategoryController::class, 'destroy'])
            ->middleware(['auth:sanctum', 'role:admin']);

        // PRODUCT API
        Route::get('/product', [ProductController::class, 'index']);
        Route::post('/product', [ProductController::class, 'store'])
            ->middleware(['auth:sanctum', 'role:admin']);

        Route::get('/product/{id}', [ProductController::class, 'show']);
        Route::put('/product/{id}', [ProductController::class, 'update'])
            ->middleware(['auth:sanctum', 'role:admin']);

        Route::delete('/product/{id}', [ProductController::class, 'destroy'])
            ->middleware(['auth:sanctum', 'role:admin']);

        // USER API
        Route::get('/user', [UserController::class, 'index'])
            ->middleware(['auth:sanctum', 'role:admin']);
    });
