<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->controller(AuthController::class)->group(function () {
    Route::post('/registration', 'registration')->name('registration');
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware("auth:api")->group(function () {
    Route::controller(ProductController::class)->prefix("/products")->group(function () {
        Route::get("/", "list")->name("ProductList");
        Route::get("/{id}", "show")->name("Product");
        Route::get("/{id}/rent/{time}", "rent")->name("ProductRent");
    });
});
