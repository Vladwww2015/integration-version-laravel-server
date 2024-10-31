<?php

use Illuminate\Support\Facades\Route;
Route::group([
    'prefix'     => 'v1/admin',
    'middleware' => ['sanctum.locale'],
], function () {

    Route::post('get-identities', [\IntegrationHelper\IntegrationVersionLaravelServer\Http\Controllers\Admin\IntegrationVersionController::class, 'getIdentities'])
        ->name('admin.integration.version.identities');

    Route::post('get-latest-hash', [\IntegrationHelper\IntegrationVersionLaravelServer\Http\Controllers\Admin\IntegrationVersionController::class, 'getLatestHash'])
        ->name('admin.integration.version.get-latest-hash');
});
