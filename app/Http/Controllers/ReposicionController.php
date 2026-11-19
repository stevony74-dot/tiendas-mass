<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Reposicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReposicionController extends Controller
{
    public function index()
    {
        $reposiciones = Reposicion::with(['producto', 'user'])->latest()->get();
        return view('reposiciones.index', compact('reposiciones'));
    }

    // Cualquier usuario registra una necesidad de reposición
    public function store(Request $request)
    {
        $datos = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        Reposicion::create($datos + ['user_id' => $request->user()->id]);

        return redirect()->route('pedidos.create')
            ->with('ok', 'Necesidad de reposición registrada.');
    }

    // El admin la marca como atendida y el stock sube
    public function atender(Reposicion $reposicion, Request $request)
    {
        if ($reposicion->estado === 'atendida') {
            return back()->with('error', 'Ya fue atendida.');
        }

        DB::transaction(function () use ($reposicion, $request) {
            $producto = Producto::lockForUpdate()->findOrFail($reposicion->producto_id);
            $producto->increment('stock', $reposicion->cantidad);

            Movimiento::create([
                'producto_id' => $producto->id,
                'user_id' => $request->user()->id,
                'tipo' => 'entrada',
                'cantidad' => $reposicion->cantidad,
                'motivo' => 'Reposición #' . $reposicion->id,
            ]);

            $reposicion->update(['estado' => 'atendida']);
        });

        return back()->with('ok', 'Reposición atendida y stock actualizado.');
    }
}