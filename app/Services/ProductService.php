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

    public function IsProductPage($url)
    {

    }

    public function getProductsForMonitoring()
    {
        $now = Carbon::now()->subMinutes(5)->timestamp;

        return Product::whereNull('monitored_at')
            ->orWhere('monitored_at', '<', $now)
            ->get();
    }
}
