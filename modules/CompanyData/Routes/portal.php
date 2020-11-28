<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'portal',
    'middleware' => 'portal',
    'namespace' => 'Modules\CompanyData\Http\Controllers'
], function () {
    // Route::get('invoices/{invoice}/company-data', 'Main@show')->name('portal.invoices.company-data.show');
    // Route::post('invoices/{invoice}/company-data/confirm', 'Main@confirm')->name('portal.invoices.company-data.confirm');
});
