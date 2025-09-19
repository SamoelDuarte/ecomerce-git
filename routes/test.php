<?php

use Illuminate\Support\Facades\Route;

// Rota de teste simples
Route::get('/teste', function () {
    return 'Laravel funcionando com URL bonita! Versão: ' . app()->version();
});

// Rota principal
Route::get('/', function () {
    return view('welcome');
});