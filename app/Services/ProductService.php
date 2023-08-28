<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Models\UserProduct;
use App\Models\UserTransaction;
use App\Responses\ProductResponse;
use App\Responses\UserProductResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    private $id;
    private $time;
    private $type;
    public function rentOrBuy($id, $type, $time = 0): JsonResponse
    {
        $this->id = $id;
        $this->type = $type;
        $this->time = $time;
        return $this->rentOrBuyHandler();
    }

    public function fetchUserProducts($userId): JsonResponse
    {
        $userProducts = UserProduct::where("user_id", $userId)->get();
        return UserProductResponse::success($userProducts);
    }

    public function showAndCheckUserProduct($userId, $productId): JsonResponse
    {
        if (!$user = User::find($userId)) {
            return UserProductResponse::error("User is not found", 400);
        }

        if (!$product = Product::find($productId)) {
            return UserProductResponse::error("Product is not found", 400);
        }

        if (!$userProduct = UserProduct::where("user_id", $user->id)->where("product_id", $productId)->first()) {
            return UserProductResponse::error("You haven't access to this product", 403);
        }

        if ($userProduct->is_rent && $userProduct->expired_at < date("Y-m-d H:i:s")) {
            $userProduct->delete();
            return UserProductResponse::error("Product is expired", 403);
        }

        if (!$userProduct->uuid) {
            $userProduct->uuid = Str::uuid();
            $userProduct->save();
        }

        return UserProductResponse::successSingle($userProduct);
    }

    private function rentOrBuyHandler(): JsonResponse
    {
        if (!$user = auth()->user()) {
            return ProductResponse::error("Not Authorize", 401);
        }

        if (!$product = Product::find($this->id)) {
            return ProductResponse::error("Product not found", 400);
        }

        if ($product->count == 0) {
            return ProductResponse::error("The product is out of stock", 400);
        }

        if ($this->type == 'rent') {
            $data = ['time' => 0, 'price' => 0];
            switch (intval($this->time)) {
                case 4:
                    $data['time'] = 4;
                    $data['price'] = $product->rent_4;
                    break;
                case 8:
                    $data['time'] = 8;
                    $data['price'] = $product->rent_8;
                    break;
                case 12:
                    $data['time'] = 12;
                    $data['price'] = $product->rent_12;
                    break;
                case 24:
                    $data['time'] = 24;
                    $data['price'] = $product->rent_24;
                    break;
                default:
                    return ProductResponse::error("Invalid time", 400);
            }
        } else {
            $data['price'] = $product->price;
        }

        if ($this->type == "rent" && ($balance = $user->balance - $data['price']) < 0 ||
            $this->type == "purchase" && ( $balance = $user->balance - $product->price) < 0
        ) {
            return ProductResponse::error("Not enough balance", 400);
        }

        return $this->storeTransaction($user, $product, $data);
    }

    private function decrementProduct(Authenticatable $user, Product $product, $balance, $isNew = false)
    {
        $user->balance = $balance;
        if ($isNew) {
            $product->count--;
            $product->in_use++;
        }
    }

    private function updateUserProduct(UserProduct $userProduct, $newExpTime = null)
    {
        if ($this->type == 'purchase') {
            $userProduct->expired_at = null;
            $userProduct->is_rent = 0;
            $userProduct->save();
            return;
        }
        $userProduct->expired_at = $newExpTime;
        $userProduct->save();
    }

    private function saveOrUpdateUserProduct(
        Authenticatable $user,
        Product $product,
        $data,
        $userProduct = null,
        $newExpTime = null
    ): JsonResponse {
        $expTime = $this->type == "rent" ? date("Y-m-d H:i:s", time() + 60 * 60 * $data['time']) : null;

        try {
            DB::beginTransaction();
            UserTransaction::create([
                "user_id" => $user->id,
                "product_id" => $product->id,
                "cost" => $data['price'],
                "rent_time" => $data['time'] ?? null,
                "expired_at" => $expTime,
                "type" => $this->type
            ]);
            if (isset($userProduct)) {
                if ($this->type == 'purchase' && $userProduct->is_rent != 1) {
                    return ProductResponse::error("You already have this product", 400);
                }
                $this->updateUserProduct($userProduct, $newExpTime ?? null);
                $this->decrementProduct($user, $product, $user->balance - $data['price']);
            } else {
                UserProduct::create([
                    "user_id" => $user->id,
                    "product_id" => $product->id,
                    "expired_at" => $expTime,
                    "is_rent" => $this->type == "rent" ?? 1,
                ]);
                $this->decrementProduct($user, $product, $user->balance - $data['price'], true);
            }
            $user->save();
            $product->save();
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollback();
            return ProductResponse::error("Something went wrong: " . $e, 500);
        }

        return ProductResponse::successWithMessage("Product added is successfully");
    }

    private function storeTransaction(Authenticatable $user, Product $product, $data): JsonResponse
    {
        if (($userProduct = $user->products()->where("product_id", $product->id)->first()) && isset($data['time'])) {
            $expTime =  $userProduct->expired_at;
            $maxExpTime = date("Y-m-d H:i:s", strtotime($userProduct->created_at) + 60 * 60 * 24);
            $newExpTime = date("Y-m-d H:i:s", strtotime($expTime) + 60 * 60 * $data['time']);

            if ($newExpTime > $maxExpTime) {
                return ProductResponse::error("Max rent time: 24 hours", 400);
            }
        }
        return $this->saveOrUpdateUserProduct(
            $user,
            $product,
            $data,
            $userProduct ?? null,
            $newExpTime ?? null
        );
    }
}
