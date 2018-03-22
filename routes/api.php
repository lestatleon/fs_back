<?php

Route::middleware(['checkToken'])->group(function() {
    Route::prefix('invitation')->group(function() {
        $controllerName = 'Api\InvitationController';
        Route::get('/', $controllerName . '@getList');
        Route::post('/', $controllerName . '@create');
    });
});

Route::prefix('invitation')->group(function() {
    $controllerName = 'Api\InvitationController';
    Route::put('/{key}/viewed', $controllerName . '@viewed');
    Route::put('/{key}', $controllerName . '@update');
    Route::get('/{key}', $controllerName . '@get');
});

Route::post('/login', 'Api\AuthController@login');
Route::get('/logout', 'Api\AuthController@logout');

Route::get('/check', 'Api\AuthController@checkToken');


