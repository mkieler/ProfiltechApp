<?php

use Illuminate\Support\Facades\Route;

// User module routes
Route::get('/users', function () {
    return 'User Module: List of users';
});