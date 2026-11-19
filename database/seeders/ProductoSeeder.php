<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $datos = [
            'Abarrotes' => [
                ['Arroz Costeño 1kg', 4.50, 50],
                ['Aceite Primor 1L', 9.90, 30],
                ['Azúcar Rubia 1kg', 3.80, 40],
                ['Fideos Don Vittorio 500g', 2.90, 3],
            ],
            'Bebidas' => [
                ['Inca Kola 1.5L', 7.50, 60],
                ['Agua San Luis 625ml', 1.50, 100],
                ['Gaseosa Coca Cola 1L', 5.00, 4],
            ],
            'Limpieza' => [
                ['Detergente Ariel 800g', 12.50, 25],
                ['Lejía Clorox 1L', 4.20, 2],
            ],
            'Lácteos' => [
                ['Leche Gloria 400g', 4.10, 45],
                ['Yogurt Gloria 1L', 7.80, 20],
            ],
        ];

        foreach ($datos as $categoria => $productos) {
            $cat = Categoria::create(['nombre' => $categoria]);
            foreach ($productos as [$nombre, $precio, $stock]) {
                Producto::create([
                    'categoria_id' => $cat->id,
                    'nombre' => $nombre,
                    'precio' => $precio,
                    'stock' => $stock,
                    'stock_minimo' => 5,
                ]);
            }
        }
    }
}