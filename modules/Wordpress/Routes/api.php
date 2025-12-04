<?php

use Illuminate\Support\Facades\Route;

// Wordpress module routes

Route::prefix('wordpress')->name('wordpress.')->group(function () {
    Route::get('/posts', function () {
        return response()->json(['posts' => []]);
    })->name('posts.index');
});