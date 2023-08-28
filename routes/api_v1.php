<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->controller(AuthController::class)->group(function () {
    Route::post('/registration', 'registration')->name('registration');
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware("auth:api")->group(function () {
    Route::controller(ProductController::class)->prefix("/product")->group(function () {
        Route::get("/", "list")->name("ProductList");
        Route::get("/{id}", "show")->name("Product");
        Route::get("/{id}/rent", "rent")->name("ProductRent");
        Route::get("/{id}/buy", "buy")->name("ProductRent");
    });

    Route::controller(UserController::class)->group(function () {
        Route::get("/me", "me")->name("UserMe");
        Route::get("transaction", "transactions")->name("UserTransactions");
        Route::get("transaction/{id}", "transaction")->name("UserTransaction");
    });

    Route::controller(UserProductController::class)->prefix("user-product")->group(function () {
        Route::get("/", "index")->name("UserProducts");
        Route::get("/{productId}", "status")->name("UserProductStatus");
    });
});
