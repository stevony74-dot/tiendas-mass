<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::with('categoria')
            ->when($request->buscar, fn ($q, $b) => $q->where('nombre', 'like', "%$b%"))
            ->orderBy('nombre')
            ->get();

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.form', [
            'producto' => new Producto(),
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Producto::create($this->validar($request));
        return redirect()->route('productos.index')->with('ok', 'Producto registrado.');
    }

    public function edit(Producto $producto)
    {
        return view('productos.form', [
            'producto' => $producto,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Producto $producto)
    {
        $producto->update($this->validar($request));
        return redirect()->route('productos.index')->with('ok', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->movimientos()->exists() || $producto->stock > 0) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar: tiene stock o movimientos.');
        }

        $producto->delete();
        return redirect()->route('productos.index')->with('ok', 'Producto eliminado.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:150',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);
    }
}