<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\CompanyData\Http\Controllers'
], function () {
    Route::prefix('company-data')->group(function() {
        Route::get('/', 'Main@index')->name('company-data.index');
        Route::get('/{company_data}', 'Main@edit')->name('company-data.edit');
        Route::post('/updated/{company_data}', 'Main@update')->name('company-data.update');
        Route::post('/updateInfo', 'Main@updateFromInternet')->name('company-data.update_from_internet');
    });

    Route::prefix('chat')->group(function() {
        Route::group(['prefix' => 'zalo', 'as' => 'zalo.'], function () {
            Route::get('/', 'ZaloChat@index')->name('index');
            Route::post('chat', 'ZaloChat@sendMessage')->name('chat');
        });
    });
});
