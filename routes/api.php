<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LoginLogController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// TODO: Sử dụng https://github.com/spatie/laravel-route-attributes

// Tối đa nhập sai 5 lần trong 1 phút
// Nếu vi phạm thì hạn chế trong 2 phút
Route::post('/login', [LoginController::class, 'login'])->middleware('customThrottle:5,1,2,login');

Route::post('/logout', [LoginController::class, 'logout']);

Route::post('/check-login-ticket', [LoginController::class, 'checkLoginTicket']);

Route::get('/me', [LoginController::class, 'getUserInfo']);

Route::post('/change-password', [ChangePasswordController::class, 'changePassword']);
Route::post('/user', [AccountController::class, 'updateInfo']);

Route::get('/apps', [AppController::class, 'getAppsOfUser']);
Route::get('/login-logs', [LoginLogController::class, 'getLoginLogsOfUser']);
Route::post('/send-reset-password-token', [ForgotPasswordController::class, 'sendResetToken']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/verify-email', [VerifyEmailController::class, 'verifyEmail']);
