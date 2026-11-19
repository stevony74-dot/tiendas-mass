<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function ventas(Request $request)
    {
        $desde = $request->desde ?? now()->startOfMonth()->toDateString();
        $hasta = $request->hasta ?? now()->toDateString();

        $pedidos = $this->consultaVentas($desde, $hasta)->get();

        $porDia = Pedido::whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->select(DB::raw('DATE(created_at) as dia'), DB::raw('SUM(total) as total'))
            ->groupBy('dia')->orderBy('dia')->get();

        $topProductos = DB::table('detalle_pedidos')
            ->join('productos', 'productos.id', '=', 'detalle_pedidos.producto_id')
            ->whereBetween('detalle_pedidos.created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->select('productos.nombre', DB::raw('SUM(detalle_pedidos.cantidad) as cantidad'))
            ->groupBy('productos.nombre')->orderByDesc('cantidad')->limit(5)->get();

        return view('reportes.ventas', compact('pedidos', 'desde', 'hasta', 'porDia', 'topProductos'));
    }

    public function inventario()
    {
        $productos = Producto::with('categoria')->orderBy('nombre')->get();
        return view('reportes.inventario', compact('productos'));
    }

    public function pedidos(Request $request)
    {
        $pedidos = Pedido::with('user')
            ->when(!$request->user()->esAdmin(), fn ($q) => $q->where('user_id', $request->user()->id))
            ->latest()->get();

        return view('reportes.pedidos', compact('pedidos'));
    }

    // Exportar a CSV (se abre en Excel)
    public function exportar(Request $request, string $tipo)
    {
        $filas = [];

        if ($tipo === 'ventas') {
            $desde = $request->desde ?? now()->startOfMonth()->toDateString();
            $hasta = $request->hasta ?? now()->toDateString();
            $filas[] = ['Comprobante', 'Fecha', 'Cliente', 'Total'];
            foreach ($this->consultaVentas($desde, $hasta)->get() as $p) {
                $filas[] = [$p->numero_comprobante, $p->created_at->format('d/m/Y H:i'), $p->cliente_nombre, $p->total];
            }
        } elseif ($tipo === 'inventario') {
            $filas[] = ['Producto', 'Categoria', 'Precio', 'Stock', 'Stock minimo'];
            foreach (Producto::with('categoria')->orderBy('nombre')->get() as $p) {
                $filas[] = [$p->nombre, $p->categoria->nombre, $p->precio, $p->stock, $p->stock_minimo];
            }
        } elseif ($tipo === 'pedidos') {
            $filas[] = ['Comprobante', 'Fecha', 'Cliente', 'Total', 'Estado'];
            $pedidos = Pedido::when(!$request->user()->esAdmin(), fn ($q) => $q->where('user_id', $request->user()->id))->latest()->get();
            foreach ($pedidos as $p) {
                $filas[] = [$p->numero_comprobante, $p->created_at->format('d/m/Y H:i'), $p->cliente_nombre, $p->total, $p->estado];
            }
        } else {
            abort(404);
        }

        return response()->streamDownload(function () use ($filas) {
            $f = fopen('php://output', 'w');
            fwrite($f, "\xEF\xBB\xBF"); // para que Excel lea las tildes
            foreach ($filas as $fila) {
                fputcsv($f, $fila, ';');
            }
            fclose($f);
        }, "reporte_{$tipo}_" . now()->format('Ymd') . '.csv');
    }

    private function consultaVentas(string $desde, string $hasta)
    {
        return Pedido::with('user')
            ->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->latest();
    }
}