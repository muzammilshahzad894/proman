<?php

use App\Http\Controllers\Api\PropertyController;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api'], function () {
    Route::get('/properties', [PropertyController::class, 'index']);
});