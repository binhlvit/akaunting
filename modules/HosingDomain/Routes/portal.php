<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'portal',
    'middleware' => 'portal',
    'namespace' => 'Modules\HosingDomain\Http\Controllers'
], function () {
    // Route::get('invoices/{invoice}/hosing-domain', 'Main@show')->name('portal.invoices.hosing-domain.show');
    // Route::post('invoices/{invoice}/hosing-domain/confirm', 'Main@confirm')->name('portal.invoices.hosing-domain.confirm');
});
