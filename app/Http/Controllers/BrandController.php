<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Brand::with('carModels')->get();

        return response()->json([
            "success" => true,
            "message" => "Listado de marcas obtenido con éxito.",
            "data" => BrandResource::collection($data)
        ], 200);
    }
}
