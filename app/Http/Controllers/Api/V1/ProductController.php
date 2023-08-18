<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\UserProduct;
use App\Models\UserTransaction;
use App\Responses\ProductResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function list()
    {
        return ProductResponse::success(Product::paginate(5));
    }

    public function show($id)
    {
        if (!$product = Product::find($id)) {
            return ProductResponse::error("Product not found", 400);
        }
            return ProductResponse::successSingle($product);
    }

    public function rent($id, $time)
    {

        if (!$product = Product::find($id)) {
            return ProductResponse::error("Product not found", 400);
        }

        if (!$user = auth()->user()) {
            return ProductResponse::error("Not Authorize", 401);
        }

        $rent = ['time' => 0, 'price' => 0];

        if ($product->count == 0) {
            return ProductResponse::error("The product is out of stock", 400);
        }

        switch (intval($time)) {
            case 4:
                $rent['time'] = 4;
                $rent['price'] = $product->rent_4;
                break;
            case 8:
                $rent['time'] = 8;
                $rent['price'] = $product->rent_8;
                break;
            case 12:
                $rent['time'] = 12;
                $rent['price'] = $product->rent_12;
                break;
            case 24:
                $rent['time'] = 24;
                $rent['price'] = $product->rent_24;
                break;
            default:
                return ProductResponse::error("Invalid time", 400);
        }

        if (($balance = $user->balance - $rent['price']) < 0) {
            return ProductResponse::error("Not enough balance" . $rent['price'], 400);
        }
        $user->balance = $balance;
        $product->count--;
        $product->in_use++;
        try {
            DB::beginTransaction();
                $user->save();
                $product->save();
                UserTransaction::create([
                    "user_id" => $user->id,
                    "product_id" => $product->id,
                    "cost" => $rent['price'],
                    "type" => "rent"
                ]);
                UserProduct::create([
                    "user_id" => $user->id,
                    "product_id" => $product->id,
                    "expired_at" => date("Y-m-d H:i:s", time() + 60*60*$rent['time']),
                    "is_rent" => 1,
                ]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollback();
            return ProductResponse::error("Something went wrong", 500);
        }
    }
}
