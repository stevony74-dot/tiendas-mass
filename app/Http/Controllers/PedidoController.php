<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Movimiento;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Reposicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $pedidos = Pedido::with('user')
            ->when(!$request->user()->esAdmin(), fn ($q) => $q->where('user_id', $request->user()->id))
            ->latest()
            ->get();

        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        return view('pedidos.create', [
            'productos' => Producto::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_nombre' => 'required|string|max:150',
            'cliente_documento' => 'nullable|string|max:20',
            'tipo_comprobante' => 'required|in:boleta,factura',
            'items' => 'required|array',
            'items.*' => 'nullable|integer|min:0',
        ]);

        // Solo productos con cantidad mayor a 0
        $items = collect($request->items)->filter(fn ($c) => (int) $c > 0);

        if ($items->isEmpty()) {
            return back()->withInput()->with('error', 'Selecciona al menos un producto con cantidad.');
        }

        // Validar stock de todos los productos
        $faltantes = [];
        $productos = Producto::whereIn('id', $items->keys())->get()->keyBy('id');

        foreach ($items as $id => $cantidad) {
            $p = $productos[$id] ?? null;
            if ($p && $p->stock < (int) $cantidad) {
                $faltantes[] = [
                    'id' => $p->id,
                    'nombre' => $p->nombre,
                    'stock' => $p->stock,
                    'faltan' => (int) $cantidad - $p->stock,
                ];
            }
        }

        // Sin stock: alerta y opción de reposición
        if (!empty($faltantes)) {
            return back()->withInput()->with('faltantes', $faltantes);
        }

        $pedido = DB::transaction(function () use ($request, $items) {
            $pedido = Pedido::create([
                'user_id' => $request->user()->id,
                'cliente_nombre' => $request->cliente_nombre,
                'cliente_documento' => $request->cliente_documento,
                'tipo_comprobante' => $request->tipo_comprobante,
                'total' => 0,
                'estado' => 'confirmado',
            ]);

            $total = 0;

            foreach ($items as $id => $cantidad) {
                $p = Producto::lockForUpdate()->findOrFail($id);

                if ($p->stock < $cantidad) {
                    throw new \Exception("Stock insuficiente de {$p->nombre}.");
                }

                $subtotal = $p->precio * $cantidad;
                $total += $subtotal;

                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $p->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $p->precio,
                    'subtotal' => $subtotal,
                ]);

                // Actualizar inventario
                $p->decrement('stock', $cantidad);

                Movimiento::create([
                    'producto_id' => $p->id,
                    'user_id' => $request->user()->id,
                    'tipo' => 'salida',
                    'cantidad' => $cantidad,
                    'motivo' => 'Venta pedido #' . $pedido->id,
                ]);
            }

            $prefijo = $request->tipo_comprobante === 'factura' ? 'F001' : 'B001';
            $pedido->update([
                'total' => $total,
                'numero_comprobante' => $prefijo . '-' . str_pad($pedido->id, 6, '0', STR_PAD_LEFT),
            ]);

            return $pedido;
        });

        return redirect()->route('pedidos.show', $pedido)->with('ok', 'Pedido confirmado.');
    }

    public function show(Request $request, Pedido $pedido)
    {
        // Un usuario normal solo ve sus pedidos
        if (!$request->user()->esAdmin() && $pedido->user_id !== $request->user()->id) {
            abort(403);
        }

        $pedido->load(['detalles.producto', 'user']);
        return view('pedidos.show', compact('pedido'));
    }
}