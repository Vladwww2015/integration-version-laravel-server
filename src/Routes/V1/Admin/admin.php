<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'v1/admin',
    'middleware' => ['sanctum.locale'],
], function () {
    /**
     * Authentication routes.
     */
    require 'admin-routes.php';
});
