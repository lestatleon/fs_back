<?php

Route::middleware(['checkToken'])->group(function() {
    Route::prefix('invitation')->group(function() {
        $controllerName = 'Api\InvitationController';
        Route::get('/', $controllerName . '@getList');
        Route::post('/', $controllerName . '@create');
    });

    Route::get('/me', 'Api\AuthController@me');
});

Route::prefix('invitation')->group(function() {
    $controllerName = 'Api\InvitationController';
    Route::put('/{key}', $controllerName . '@update');
    Route::get('/{key}', $controllerName . '@get');
});

Route::post('/login', 'Api\AuthController@login');
Route::get('/logout/{id}', 'Api\AuthController@logout');
