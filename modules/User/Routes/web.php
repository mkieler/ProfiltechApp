<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// User module routes
Route::get('/users', fn(): string => 'User Module: List of users');
