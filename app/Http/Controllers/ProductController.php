<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('productItens')->get();

        return view('products.products', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('productItens');

        return view('products.show', compact('product'));
    }
}
