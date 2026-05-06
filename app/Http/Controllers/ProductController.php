<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    /**
 * @OA\Get(
 *     path="/api/products",
 *     tags={"Products"},
 *     summary="Get products list",
 *     @OA\Response(
 *         response=200,
 *         description="Success"
 *     )
 * )
 */



    // GET /api/products
    public function index(Request $request)
    {
        $query = Product::with('category');

        // 🔍 Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // 💰 Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 📦 Stock filter
        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                $query->where('stock', '<', 5);
            }

            if ($request->stock === 'out') {
                $query->where('stock', 0);
            }
        }

        $products = $query->paginate(10);

        return ProductResource::collection($products);
    }

    // POST /api/products
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return new ProductResource($product);
    }

    // GET /api/products/{id}
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    // PUT /api/products/{id}
    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return new ProductResource($product);
    }

    // DELETE /api/products/{id}
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted'
        ]);
    }
}

