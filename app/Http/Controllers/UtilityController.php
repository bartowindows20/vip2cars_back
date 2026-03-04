<?php

namespace App\Http\Controllers;

use App\Enums\DocumentTypeEnum;
use Illuminate\Http\JsonResponse;

class UtilityController extends Controller
{
    public function getDocumentTypes(): JsonResponse
    {
        return response()->json([
            "success" => true,
            "message" => "Listado de tipos de documentos obtenido con éxito.",
            "data" => DocumentTypeEnum::cases()
        ]);
    }
}
