<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->where('user_id', auth()->id())
            ->get();

        return OrderResource::collection($orders);
    }
    public function create()
    {
        $order = Order::where('user_id', auth()->id())
                    ->where('status', 'pending')
                    ->firstOrFail();

        $insufficientStockProducts = [];
        foreach ($order->products as $product) {
            if ($product->stock < $product->pivot->quantity) {
                $insufficientStockProducts[] = [
                    'product_name' => $product->name,
                    'available_stock' => $product->stock,
                    'requested_quantity' => $product->pivot->quantity,
                ];
            }
        }

        if (!empty($insufficientStockProducts)) {
            return response()->json([
                'message' => 'Some products are out of stock.',
                'insufficient_stock' => $insufficientStockProducts,
            ], 400);
        }

        $stockUpdated = [];
        foreach ($order->products as $product) {

            if ($product->stock >= $product->pivot->quantity) {
                $product->stock -= $product->pivot->quantity;
                $stockUpdated[] = [
                    'product_id' => $product->id,
                    'previous_stock' => $product->stock + $product->pivot->quantity,
                    'current_stock' => $product->stock,
                ];
                $product->save();
            }
        }

        $order->update(['status' => 'completed']);

        return response()->json([
            'message' => 'Order placed successfully.',
            'order_id' => $order->id,
            'stock_updates' => $stockUpdated,
        ]);
    }
}
