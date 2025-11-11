<?php

use Modules\Order\Http\Controllers\OrderController;

Route::controller(OrderController::class)
    ->prefix('/orders')
    ->name('orders.')
    ->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{id}', 'details')->name('details');
    });
