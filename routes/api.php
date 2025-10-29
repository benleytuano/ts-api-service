<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\TicketController;
use App\Http\Controllers\api\CategoryController;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    // Public routes
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'me');
        Route::post('refresh', 'refresh');
    });
});

// Categories routes - publicly accessible for form data
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/active', [CategoryController::class, 'active']);
    Route::get('/with-counts', [CategoryController::class, 'withTicketCounts']);
});

Route::middleware('auth:sanctum')->prefix('tickets')->group(function () {
    Route::post('/', [TicketController::class, 'store']);
    Route::get('/', [TicketController::class, 'index']);
    Route::get('/{id}', [TicketController::class, 'show']);

    // Assignment actions
    Route::post('/{id}/assign',   [TicketController::class, 'assign']);   // claim-only
    Route::post('/{id}/reassign', [TicketController::class, 'reassign']); // admin-only
    Route::post('/{id}/unassign', [TicketController::class, 'unassign']); // optional race-guard

    Route::post('/{id}/resolve', [TicketController::class, 'resolve']);

    // Ticket updates/comments
    Route::get('/{id}/updates', [TicketController::class, 'getUpdates']);
    Route::post('/{id}/updates', [TicketController::class, 'createUpdate']);

});
