<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function index()
    {
        $movimientos = Movimiento::with(['producto', 'user'])->latest()->take(100)->get();
        $productos = Producto::orderBy('nombre')->get();
        return view('movimientos.index', compact('movimientos', 'productos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:150',
        ]);

        try {
            DB::transaction(function () use ($datos, $request) {
                $producto = Producto::lockForUpdate()->findOrFail($datos['producto_id']);

                if ($datos['tipo'] === 'salida') {
                    if ($producto->stock < $datos['cantidad']) {
                        throw new \Exception('Stock insuficiente para la salida.');
                    }
                    $producto->decrement('stock', $datos['cantidad']);
                } else {
                    $producto->increment('stock', $datos['cantidad']);
                }

                Movimiento::create($datos + ['user_id' => $request->user()->id]);
            });
        } catch (\Exception $e) {
            return redirect()->route('movimientos.index')->with('error', $e->getMessage());
        }

        return redirect()->route('movimientos.index')->with('ok', 'Movimiento registrado.');
    }
}