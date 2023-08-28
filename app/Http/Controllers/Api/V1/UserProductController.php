<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserProduct;
use App\Responses\UserProductResponse;
use App\Services\ProductService;
use App\Services\UserProductService;
use App\Services\UserService;

class UserProductController extends Controller
{
    public function index(ProductService $service)
    {
        $userId = auth()->user()->id;
        return $service->fetchUserProducts($userId);
    }

    public function status($productId, ProductService $service)
    {
        $userId = auth()->user()->id;
        return $service->showAndCheckUserProduct($userId, $productId);
    }
}
