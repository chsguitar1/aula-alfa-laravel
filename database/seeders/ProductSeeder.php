<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Camiseta Básica', 'price' => 39.90, 'unitOfMeasurement' => 'un'],
            ['name' => 'Calça Jeans', 'price' => 129.90, 'unitOfMeasurement' => 'un'],
            ['name' => 'Tênis Esportivo', 'price' => 199.90, 'unitOfMeasurement' => 'un'],
            ['name' => 'Boné', 'price' => 49.90, 'unitOfMeasurement' => 'un'],
            ['name' => 'Jaqueta Corta-Vento', 'price' => 159.90, 'unitOfMeasurement' => 'un'],
        ];

        foreach ($products as $product) {
            Product::query()->create($product);
        }
    }
}
