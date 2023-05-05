<?php

use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\PasswordResetLinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('prerender',[\App\Http\Controllers\PrerenderingController::class, 'prerender']);

Route::prefix('user')->group( function () {
    Route::post('forgot-password',[PasswordResetLinkController::class,'store']);
    Route::get('reset-password/{token}', function () {
        return '449重設密碼畫面';
    })->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store']);

    //Email驗證
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/home');
    })->middleware(['auth', 'signed'])->name('verification.verify');


    Route::post('register', [\App\Http\Controllers\RegisterController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
        Route::apiResource('books', \App\Http\Controllers\BookController::class) //在程式裡books => book ->model binding
            // apiResource 預設有 index, show, store, update, destroy
            ->only('index','show', 'store', 'destroy'); //book/1 Book::findOrfail(1)
    });
});



