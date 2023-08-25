<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\ProductAction;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Responses\ProductResponse;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function list(): JsonResponse
    {
        return ProductResponse::success(Product::all());
    }

    public function show($id): JsonResponse
    {
        if (!$product = Product::find($id)) {
            return ProductResponse::error("Product not found", 400);
        }
            return ProductResponse::successSingle($product);
    }

    public function rent($id, Request $request, ProductService $service): JsonResponse
    {
        return $service->rentOrBuy($id, "rent", $request->query("time"));
    }

    public function buy($id, ProductService $service): JsonResponse
    {
        return $service->rentOrBuy($id, "purchase");
    }
}
