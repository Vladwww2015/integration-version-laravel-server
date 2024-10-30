<?php

use Illuminate\Support\Facades\Route;
Route::group([
    'prefix' => config('app.admin_url') . '/integration-version',
    'middleware' => ['web', 'admin']
], function () {

    Route::post('get-identities', [\IntegrationHelper\IntegrationVersionLaravelServer\Http\Controllers\Admin\IntegrationVersionController::class, 'getIdentities'])
        ->name('admin.integration.version.identities');
});
