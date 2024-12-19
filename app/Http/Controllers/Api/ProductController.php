<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(ProductFilterRequest $request)
    {
        $categoryId = $request->category_id;
        $sortOrder = $request->sort_order ?? 'asc';

        $productsQuery = Product::query()->with('category', 'images');

        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        $productsQuery->orderBy('price', $sortOrder);

        $products = $productsQuery->paginate(10);

        return ProductResource::collection($products);
    }
}
