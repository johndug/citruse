<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\API\Admin\VendorController;
use App\Http\Controllers\API\PurchaseManager\PurchaseOrderController;
use App\Http\Controllers\API\Admin\ProductController;
use App\Http\Controllers\API\Admin\UserController;

// Public authentication routes
Route::post('/login', [ApiAuthController::class, 'login']);

// Protected auth routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/users/me', [ApiAuthController::class, 'me'])->name('users.me');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Admin routes
    Route::group(['prefix' => 'vendors'], function () {
        Route::get('/', [VendorController::class, 'index']);
        Route::post('/', [VendorController::class, 'store']);
        Route::put('/{vendor}', [VendorController::class, 'update']);
        Route::delete('/{vendor}', [VendorController::class, 'destroy']);
    });

    // Admin routes
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });

    // Purchase Manager / Admin routes
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [PurchaseOrderController::class, 'index']);
        Route::post('/pod', [PurchaseOrderController::class, 'createNewPOD']);
        Route::put('/{purchaseOrder}', [PurchaseOrderController::class, 'update']);
        Route::delete('/{purchaseOrder}', [PurchaseOrderController::class, 'delete']);
    });
});

