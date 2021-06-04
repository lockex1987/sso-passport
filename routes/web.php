<?php

use Illuminate\Support\Facades\Route;

// Chuyển tất cả về trang index
Route::get('{path}', function () {
    return view('index');
})->where('path', '.*');
