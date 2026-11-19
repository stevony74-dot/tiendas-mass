@extends('layouts.mass')

@section('titulo', 'Reporte de pedidos - Tiendas Mass')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0" style="color: var(--azul)">Reporte de pedidos</h3>
        <a href="{{ route('reportes.exportar', 'pedidos') }}" class="btn btn-mass">Exportar CSV</a>
    </div>

    <div class="tarjeta p-4">
        <table class="table align-middle mb-0">
            <thead><tr><th>Comprobante</th><th>Fecha</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Registrado por</th></tr></thead>
            <tbody>
                @forelse ($pedidos as $p)
                    <tr>
                        <td>{{ $p->numero_comprobante }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->cliente_nombre }}</td>
                        <td>S/ {{ number_format($p->total, 2) }}</td>
                        <td><span class="badge text-bg-success">{{ ucfirst($p->estado) }}</span></td>
                        <td>{{ $p->user->name }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Sin pedidos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection