<?php

use App\Http\Controllers\EnergyReadingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function (Request $request) {
    return response()->json(['status' => 'ok', 'db' => 'ok']);
});

Route::controller(EnergyReadingController::class)->group(function () {
    Route::post('/import/json', 'jsonImport');
    Route::post('/sync/prices', 'syncPrices');
    Route::get('/readings', 'allReadings');
    Route::delete('/readings', 'deleteReadings');
});
