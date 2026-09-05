<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogPostController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\MobileContentController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/mobile/home', [MobileContentController::class, 'home']);
Route::get('/mobile/gallery', [MobileContentController::class, 'gallery']);
Route::get('/mobile/contact', [MobileContentController::class, 'contact']);
Route::get('/blog-posts', [BlogPostController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [ProfileController::class, 'show']);
    Route::put('/me', [ProfileController::class, 'update']);
    Route::post('/blog-posts', [BlogPostController::class, 'store']);
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/books/{book}', [CartController::class, 'store']);
    Route::put('/cart', [CartController::class, 'update']);
    Route::delete('/cart/books/{book}', [CartController::class, 'destroy']);
    Route::delete('/cart', [CartController::class, 'clear']);
    Route::post('/cart/checkout-link', [CartController::class, 'checkoutLink']);
});
