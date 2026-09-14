<?php

namespace App\Http\Controllers;
use App\Models\ProductItens;
use Illuminate\Http\Request;

class ProductItensController extends Controller
{
    public function index()
    {
        $itens = ProductItens::with('product')->get();

        return view('product_itens.index', compact('itens'));
    }
}