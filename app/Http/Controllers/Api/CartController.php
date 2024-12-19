<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Order;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(CartRequest $request)
    {
        $order = Order::firstOrCreate(
            ['user_id' => auth()->id(), 'status' => 'pending'],
            ['total_price' => 0]
        );

        $order->products()->syncWithoutDetaching([
            $request->product_id => [
                'quantity' => $request->quantity,
                'price' => $request->price * $request->quantity,
            ],
        ]);

        $totalPrice = $order->products->sum(function ($product) {
            return $product->pivot->price;
        });

        $order->update(['total_price' => $totalPrice]);

        return new CartResource($order);
    }

    public function destroy(Request $request, $productId)
    {
        $order = Order::where('user_id', auth()->id())->where('status', 'pending')->firstOrFail();

        $order->products()->detach($productId);

        if ($order->products()->count() === 0) {
            $order->delete();
            return response()->json(['message' => 'Cart has been cleared and deleted.']);
        }

        $totalPrice = $order->products->sum(function ($product) {
            return $product->pivot->price;
        });

        $order->update(['total_price' => $totalPrice]);

        return response()->json(['message' => 'Product removed from cart successfully.']);
    }

    public function update(Request $request, $productId)
    {
        $order = Order::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $order->products()->updateExistingPivot($productId, [
            'quantity' => $request->quantity,
            'price' => $request->quantity * $order->products()->find($productId)->price,
        ]);

        $totalPrice = $order->products->sum(function ($product) {
            return $product->pivot->price;
        });

        $order->update(['total_price' => $totalPrice]);

        return new CartResource($order);
    }
}
