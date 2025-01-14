<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category', 'images')->get();
        return ProductResource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {

    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        if ($request->has('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'size' => $request->input("images_size.{$index}"),
                ]);
            }
        }

        return ProductResource::make($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product->load(
            'category',
            'images'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Product $product)
    // {

    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        if ($request->has('deleted_image_ids')) {
            ProductImage::whereIn('id', $request->deleted_image_ids)
            ->where('product_id', $product->id)
            ->delete();
        }

        if ($request->has('images')) {

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'size' => $request->input("images_size.{$index}"),
                ]);
            }

        }

        return new ProductResource($product->load('category', 'images'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return response()->noContent();
        } catch (\Exception $e) {
            Log::error('Error soft deleting product: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to delete product',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}