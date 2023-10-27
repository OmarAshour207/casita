<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountrySoapController;

// First
Route::get('countries/index', [App\Http\Controllers\CountryController::class, 'index']);
Route::post('countries/store', [App\Http\Controllers\CountryController::class, 'store']);
Route::post('countries/update/{id}', [App\Http\Controllers\CountryController::class, 'update']);
Route::post('countries/delete/{id}', [App\Http\Controllers\CountryController::class, 'delete']);

// Second and Third
Route::get('soap', [CountrySoapController::class, 'get'])->name('countries-soap')->middleware('auth.oauth');
