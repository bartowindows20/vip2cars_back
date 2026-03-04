<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::apiResource('clients', ClientController::class);

Route::get('document-types', function () {
    $types = \App\Enums\DocumentTypeEnum::values();
    return response()->json([
        "success" => true,
        "message" => "Tipos de documento encontrados con éxito.",
        "data" => $types
    ], 200);
});
