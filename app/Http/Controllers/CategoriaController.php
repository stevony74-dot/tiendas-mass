<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
        ]);

        Categoria::create($datos);
        return redirect()->route('categorias.index')->with('ok', 'Categoría creada.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->exists()) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar: la categoría tiene productos.');
        }

        $categoria->delete();
        return redirect()->route('categorias.index')->with('ok', 'Categoría eliminada.');
    }
}