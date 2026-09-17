<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductItens;

class ProductItensController extends Controller
{

    public function index()
    {
        $productItens = ProductItens::with('product')->paginate(5);

        return view('product.itens', [
            'productItens' => $productItens,
            'title' => 'Lista de Itens de Produtos'
        ]);
    }

}
