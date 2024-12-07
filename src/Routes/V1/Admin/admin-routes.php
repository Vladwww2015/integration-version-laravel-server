<?php

use IntegrationHelper\IntegrationVersionLaravelServer\Http\Controllers\V1\Admin\IntegrationVersion\IntegrationVersionController;
use Illuminate\Support\Facades\Route;
Route::group([
    'prefix' => 'integration-version',
    'middleware' => ['auth:sanctum', 'sanctum.admin'],
], function () {
    Route::controller(IntegrationVersionController::class)->group(function () {
        Route::post('get-identities', 'getIdentities');
        Route::post('get-latest-hash', 'getLatestHash');
    });
});
