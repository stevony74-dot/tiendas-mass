@extends('layouts.mass')

@section('titulo', 'Reporte de ventas - Tiendas Mass')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0" style="color: var(--azul)">Reporte de ventas</h3>
        <a href="{{ route('reportes.exportar', ['tipo' => 'ventas', 'desde' => $desde, 'hasta' => $hasta]) }}" class="btn btn-mass">Exportar CSV</a>
    </div>

    <div class="tarjeta p-3 mb-3">
        <form method="GET" class="row g-2">
            <div class="col-md-4"><input type="date" name="desde" class="form-control" value="{{ $desde }}"></div>
            <div class="col-md-4"><input type="date" name="hasta" class="form-control" value="{{ $hasta }}"></div>
            <div class="col-md-4"><button class="btn btn-amarillo w-100">Filtrar</button></div>
        </form>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="tarjeta p-4">
                <div class="text-muted">Total vendido</div>
                <div class="fs-2 fw-bold" style="color: var(--azul)">S/ {{ number_format($pedidos->sum('total'), 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="tarjeta p-4">
                <div class="text-muted">Pedidos</div>
                <div class="fs-2 fw-bold" style="color: var(--azul)">{{ $pedidos->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="tarjeta p-4">
                <div class="text-muted">Ticket promedio</div>
                <div class="fs-2 fw-bold" style="color: var(--azul)">S/ {{ number_format($pedidos->avg('total') ?? 0, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-7">
            <div class="tarjeta p-4">
                <h6>Ventas por día</h6>
                <canvas id="grafico"></canvas>
            </div>
        </div>
        <div class="col-md-5">
            <div class="tarjeta p-4">
                <h6>Productos más vendidos</h6>
                <table class="table mb-0">
                    @forelse ($topProductos as $t)
                        <tr><td>{{ $t->nombre }}</td><td class="text-end">{{ $t->cantidad }}</td></tr>
                    @empty
                        <tr><td class="text-muted">Sin datos.</td></tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>

    <div class="tarjeta p-4">
        <table class="table align-middle mb-0">
            <thead><tr><th>Comprobante</th><th>Fecha</th><th>Cliente</th><th>Total</th></tr></thead>
            <tbody>
                @forelse ($pedidos as $p)
                    <tr>
                        <td>{{ $p->numero_comprobante }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->cliente_nombre }}</td>
                        <td>S/ {{ number_format($p->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Sin ventas en este rango.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        new Chart(document.getElementById('grafico'), {
            type: 'bar',
            data: {
                labels: @json($porDia->pluck('dia')),
                datasets: [{ label: 'Ventas (S/)', data: @json($porDia->pluck('total')), backgroundColor: '#FFD500', borderColor: '#0B3D91', borderWidth: 2 }]
            }
        });
    </script>
@endsection