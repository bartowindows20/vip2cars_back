<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ClientCarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UtilityController;
use Illuminate\Support\Facades\Route;

Route::apiResource('clients', ClientController::class);
Route::apiResource('clients.cars', ClientCarController::class)->scoped(['car' => 'id'])->shallow();

Route::get('document-types', [UtilityController::class, 'getDocumentTypes']);
Route::get('brands', [BrandController::class, 'index']);
