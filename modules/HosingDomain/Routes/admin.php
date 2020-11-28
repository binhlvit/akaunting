<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\HosingDomain\Http\Controllers'
], function () {
    Route::prefix('hosing-domain')->group(function() {
        Route::get('/', 'Main@index');
        Route::get('/check-domain', 'Main@checkDomain')->name('hosing-domain.check-domain');
    });
});
