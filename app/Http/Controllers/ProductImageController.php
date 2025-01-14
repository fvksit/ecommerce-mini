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

        $uploadTotalSize = array_sum(array_map(function ($image) {
            return $image->getSize();
        }, $images));

        $existingImages = ProductImage::where('product_id', $productId)->get();
        $existingTotalSize = 0;

        foreach ($existingImages as $existingImage) {
            $path = storage_path('app/public/' . $existingImage->image_path);
            if (file_exists($path)) {
                $existingTotalSize += filesize($path);
            }
        }

        $totalSize = $existingTotalSize + $uploadTotalSize;
        $maxAllowedSize = 2 * 1024 * 1024;

        if ($totalSize > $maxAllowedSize) {
            return response()->json(['error' => 'Total size of images exceeds the limit of 2MB.'], 400);
        }

        $storedImages = [];

        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');

            $storedImage = ProductImage::create([
                'product_id' => $productId,
                'image_path' => $path,
                'size' => $request->input("images_size.{$index}"),
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
    public function show($productId)
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