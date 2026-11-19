@extends('layouts.mass')

@section('titulo', 'Pedidos - Tiendas Mass')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0" style="color: var(--azul)">Pedidos</h3>
        <a href="{{ route('pedidos.create') }}" class="btn btn-amarillo">+ Nuevo pedido</a>
    </div>

    <div class="tarjeta p-4">
        <table class="table align-middle mb-0">
            <thead>
                <tr><th>Comprobante</th><th>Fecha</th><th>Cliente</th><th>Total</th><th>Registrado por</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($pedidos as $p)
                    <tr>
                        <td>{{ $p->numero_comprobante }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->cliente_nombre }}</td>
                        <td>S/ {{ number_format($p->total, 2) }}</td>
                        <td>{{ $p->user->name }}</td>
                        <td class="text-end"><a href="{{ route('pedidos.show', $p) }}" class="btn btn-sm btn-mass">Ver</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Sin pedidos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection