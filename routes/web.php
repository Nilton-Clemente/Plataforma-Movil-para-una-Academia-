<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminBlogPostController;
use App\Http\Controllers\AdminBookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MobileCheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/nosotros', 'pages.about')->name('about');
Route::view('/galeria', 'pages.gallery')->name('gallery');
Route::view('/contactos', 'pages.contact')->name('contact');
Route::get('/mobile/checkout/{user}', [MobileCheckoutController::class, 'show'])
    ->middleware('signed')
    ->name('mobile.checkout');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/tienda', [StoreController::class, 'index'])->name('store.index');
    Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrito/libros/{book}', [CartController::class, 'store'])->name('cart.store');
    Route::put('/carrito', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/carrito/libros/{bookId}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/carrito', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/admin/libros', [AdminBookController::class, 'index'])->name('admin.books.index');
    Route::get('/admin/libros/crear', [AdminBookController::class, 'create'])->name('admin.books.create');
    Route::post('/admin/libros', [AdminBookController::class, 'store'])->name('admin.books.store');
    Route::get('/admin/libros/{book}/editar', [AdminBookController::class, 'edit'])->name('admin.books.edit');
    Route::put('/admin/libros/{book}', [AdminBookController::class, 'update'])->name('admin.books.update');
    Route::delete('/admin/libros/{book}', [AdminBookController::class, 'destroy'])->name('admin.books.destroy');
    Route::get('/admin/blog', [AdminBlogPostController::class, 'index'])->name('admin.blog.index');
    Route::get('/admin/blog/crear', [AdminBlogPostController::class, 'create'])->name('admin.blog.create');
    Route::post('/admin/blog', [AdminBlogPostController::class, 'store'])->name('admin.blog.store');
    Route::get('/admin/blog/{blogPost}/editar', [AdminBlogPostController::class, 'edit'])->name('admin.blog.edit');
    Route::put('/admin/blog/{blogPost}', [AdminBlogPostController::class, 'update'])->name('admin.blog.update');
    Route::delete('/admin/blog/{blogPost}', [AdminBlogPostController::class, 'destroy'])->name('admin.blog.destroy');
    Route::get('/admin/usuarios', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/usuarios/{user}/editar', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/usuarios/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/usuarios/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});
