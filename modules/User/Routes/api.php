<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// User module routes
Route::get('/user', function () {
    return Auth::user();
});