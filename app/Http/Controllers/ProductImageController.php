<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductImageRequest $request)
    {
        $productId = $request->input('product_id');
        $images = $request->file('images');

        $storedImages = [];

        foreach ($images as $image) {
            $path = $image->store('products', 'public');
            $storedImage = ProductImage::create([
                'product_id' => $productId,
                'image_path' => $path,
            ]);
            $storedImages[] = new ProductImageResource($storedImage);
        }

        return response()->json([
            'message' => 'Images uploaded successfully.',
            'images' => $storedImages,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductImage $productImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductImage $productImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductImage $productImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($productId, $imageId)
    {
        $image = ProductImage::where('id', $imageId)
                ->where('product_id', $productId)
                ->first();
        if (!$image) {
            return response()->json(['message' => 'Image not found.'], 404);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully.'], 200);
    }
}