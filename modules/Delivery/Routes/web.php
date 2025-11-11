<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Delivery\Http\Controllers\RouteController;

Route::prefix('deliveries')->name('deliveries.')->group(function () {
    Route::controller(RouteController::class)
        ->prefix('/routes')
        ->name('routes.')
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('/{id}', 'details')->name('details');
            Route::post('/', 'create')->name('create');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'delete')->name('delete');
            Route::get('/{id}/stops', 'stops')->name('stops');
            Route::post('/{id}/stops', 'addStopToRoute')->name('addStop');
            Route::delete('/{id}/stops/{stopId}', 'removeStopFromRoute')->name('removeStop');
        });

    // Route::controller(ShippingController::class)
    //     ->prefix('/shipping')
    //     ->name('shipping.')
    //     ->group(function () {
    //         Route::get('/', 'list')->name('list');
    //         Route::get('/{id}', 'details')->name('details');
    //     });
});
