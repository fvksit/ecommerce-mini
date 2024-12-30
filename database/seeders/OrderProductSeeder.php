<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        if ($orders->count() > 0 && $products->count() > 0) {
            $orders->each(function ($order) use ($products) {
                $productsForOrder = $products->random(rand(1, 5));

                $productsForOrder->each(function ($product) use ($order) {
                    $quantity = rand(1, 5);

                    $totalPrice = $quantity * $product->price;

                    $order->products()->attach(
                        $product->id,
                        [
                            'quantity' => $quantity,
                            'price' => $totalPrice
                        ]
                    );
                });
            });
        }
    }
}