<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user/shipping')->middleware(['auth', 'userstatus', 'Demo', 'userLanguage'])->group(function () {
    Route::get('/', 'User\ShopSettingController@index')->name('user.shipping.index');
    Route::post('/store', 'User\ShopSettingController@store')->name('user.shipping.store');
    Route::get('/consulta-cep', 'User\ShopSettingController@consultaCep')->name('user.shipping.consulta-cep');
});
