<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$perPage = intval($request->query('per_page', 5));

		if ($perPage === 0) {
			$perPage = 5;
		}

		$query = Product::orderBy('created_at', 'desc');

		if ($name = $request->query('name')) {
			$query->where('name', 'LIKE', "%{$name}%");
		}

		if ($details = $request->query('details')) {
			$query->orWhere('details', 'LIKE', "%{$details}%");
		}

		$products = $query->paginate($perPage);

		return ProductResource::collection($products);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreProductRequest $request)
	{
		$product = Product::create($request->validated());

		return response(new ProductResource($product), Response::HTTP_CREATED);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Product $product)
	{
		return response(new ProductResource($product));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateProductRequest $request, Product $product)
	{
		$product->update($request->validated());

		return response([], Response::HTTP_NO_CONTENT);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Product $product)
	{
		$product->delete();

		return response([], Response::HTTP_NO_CONTENT);
	}
}
