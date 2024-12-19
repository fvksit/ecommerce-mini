<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function create()
    {
        $order = Order::where('user_id', auth()->id())->where('status', 'pending')->firstOrFail();

        foreach ($order->products as $product) {
            if ($product->stock < $product->pivot->quantity) {
                return response()->json([
                    'message' => "Product '{$product->name}' is out of stock."
                ], 400);
            }
        }

        foreach ($order->products as $product) {
            $product->update(['stock' => $product->stock - $product->pivot->quantity]);
        }

        $order->update(['status' => 'completed']);

        return response()->json(['message' => 'Order placed successfully.']);
    }
}
