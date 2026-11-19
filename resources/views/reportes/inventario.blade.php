@extends('layouts.mass')

@section('titulo', 'Reporte de inventario - Tiendas Mass')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0" style="color: var(--azul)">Reporte de inventario</h3>
        <a href="{{ route('reportes.exportar', 'inventario') }}" class="btn btn-mass">Exportar CSV</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="tarjeta p-4">
                <div class="text-muted">Productos</div>
                <div class="fs-2 fw-bold" style="color: var(--azul)">{{ $productos->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="tarjeta p-4">
                <div class="text-muted">Stock bajo</div>
                <div class="fs-2 fw-bold text-warning">{{ $productos->filter->stockBajo()->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="tarjeta p-4">
                <div class="text-muted">Valor del inventario</div>
                <div class="fs-2 fw-bold" style="color: var(--azul)">S/ {{ number_format($productos->sum(fn ($p) => $p->precio * $p->stock), 2) }}</div>
            </div>
        </div>
    </div>

    <div class="tarjeta p-4">
        <table class="table align-middle mb-0">
            <thead><tr><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Mínimo</th><th>Estado</th></tr></thead>
            <tbody>
                @foreach ($productos as $p)
                    <tr>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->categoria->nombre }}</td>
                        <td>S/ {{ number_format($p->precio, 2) }}</td>
                        <td>{{ $p->stock }}</td>
                        <td>{{ $p->stock_minimo }}</td>
                        <td>
                            @if ($p->stockBajo())
                                <span class="badge text-bg-warning">Stock bajo</span>
                            @else
                                <span class="badge text-bg-success">Disponible</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection