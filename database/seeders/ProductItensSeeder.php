<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductItensSeeder extends Seeder
{
    public function run(): void
    {
        $colors = ['Preto', 'Branco', 'Azul', 'Vermelho', 'Cinza'];

        Product::all()->each(function (Product $product) use ($colors) {
            foreach ($colors as $color) {
                $product->productItens()->create([
                    'quantity' => fake()->numberBetween(5, 50),
                    'color' => $color,
                    'value' => $product->price,
                ]);
            }
        });
    }
}
