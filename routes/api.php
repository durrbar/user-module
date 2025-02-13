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

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // Route::apiResource('user', UserController::class)->names('user');

    Route::withoutMiddleware('auth:sanctum')->middleware('web')->prefix('login')->name('login.')->group(function () {
        Route::get('/callback/{provider}', [SocialiteController::class, 'callback'])->name('callback');
        Route::get('/redirect/{provider}', [SocialiteController::class, 'redirect'])->name('redirect');
    });

    // User routes
    Route::middleware('verified')->prefix('user')->name('user.')->group(function () {

        Route::get('/me', function (Request $request) {
            $user = new UserResource($request->user());
            return response()->json(['user' => $user], Response::HTTP_OK);
        })->name('me');

        // Profile Information...
        Route::post('/profile-photo', [ProfileAvatarController::class, 'update'])->name('photo.update');
        Route::delete('/profile-photo', [ProfileAvatarController::class, 'delete'])->name('photo.delete');
    });
});
