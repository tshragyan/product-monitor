<?php


namespace App\Services;


use App\Models\Product;
use Carbon\Carbon;
use GuzzleHttp\Client;

class ProductService
{
    public function findByUrl(string $url): Product|null
    {
        return Product::where('url', $url)->first();
    }

    public function create(string $url): Product
    {
        $product = new Product();
        $product->url = $url;
        $product->save();

        return $product;
    }

    public function getProductsForMonitoring()
    {
        $now = Carbon::now()->subMinutes(5)->timestamp;
        $data = [];

        Product::whereNull('monitored_at')
            ->orWhere('monitored_at', '<', $now)
            ->chunk(200, function($products) use (&$data) {
                $data = array_merge($data, $products->toArray());
            });

        return $data;
    }
}
