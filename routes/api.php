<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\ProfileAvatarController;
use Modules\User\Http\Controllers\SocialiteController;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Resources\UserResource;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function (): void {
    // Authentication routes (Socialite)
    Route::prefix('login')->name('login.')->withoutMiddleware('auth:sanctum')->middleware('web')->group(function (): void {
        Route::get('callback/{provider}', [SocialiteController::class, 'callback'])->name('callback');
        Route::get('redirect/{provider}', [SocialiteController::class, 'redirect'])->name('redirect');
    });

    // Authenticated user routes
    Route::prefix('user')->name('user.')->middleware('verified')->group(function (): void {
        Route::get('me', [UserController::class, 'me'])->withoutMiddleware('verified')->name('me');

        // Profile management
        Route::post('profile-photo', [ProfileAvatarController::class, 'update'])->name('photo.update');
        Route::delete('profile-photo', [ProfileAvatarController::class, 'delete'])->name('photo.delete');
    });
});
