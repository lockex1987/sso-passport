<?php

use Illuminate\Support\Facades\Route;


// Tối đa nhập sai 5 lần trong 1 phút
// Nếu vi phạm thì hạn chế trong 2 phút
Route::post('/login', 'LoginController@login')->middleware('customThrottle:5,1,2,login');

Route::post('/logout', 'LoginController@logout');

Route::post('/check-login-ticket', 'LoginController@checkLoginTicket');

Route::get('/me', 'LoginController@getUserInfo');

Route::post('/change-password', 'ChangePasswordController@changePassword');
Route::post('/user', 'AccountController@updateInfo');

Route::get('/apps', 'AppController@getAppsOfUser');
Route::get('/login-logs', 'LoginLogController@getLoginLogsOfUser');
Route::post('/send-reset-password-token', 'ForgotPasswordController@sendResetToken');
Route::post('/reset-password', 'ResetPasswordController@resetPassword');

Route::post('/register', 'RegisterController@register');
Route::post('/verify-email', 'VerifyEmailController@verifyEmail');
