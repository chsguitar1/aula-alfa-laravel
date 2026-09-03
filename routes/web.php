<?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Models\ProductItens;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'productsCount' => Product::count(),
        'itemsCount' => ProductItens::count(),
    ]);
});

Route::get('/products', [ProductController::class, 'index']);
