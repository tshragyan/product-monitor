<?php


namespace App\Services;


use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionService
{
    public function create(User $user, Product $product): void
    {
        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->is_active = $user->is_active;
        $subscription->product_id = $product->id;
        $subscription->save();
    }

    public function findByUserAndProduct($userId, $productId)
    {
        return Subscription::where('product_id', $productId)->where('user_id', $userId)->first();
    }
}
