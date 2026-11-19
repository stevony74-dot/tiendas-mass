@extends('layouts.mass')

@section('titulo', 'Comprobante - Tiendas Mass')

@section('contenido')
    <div class="tarjeta p-4" style="max-width: 700px">
        <div class="d-flex justify-content-between">
            <h4 style="color: var(--azul)">{{ strtoupper($pedido->tipo_comprobante) }} {{ $pedido->numero_comprobante }}</h4>
            <span class="text-muted">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <hr>
        <p class="mb-1"><strong>Cliente:</strong> {{ $pedido->cliente_nombre }}</p>
        <p class="mb-3"><strong>DNI/RUC:</strong> {{ $pedido->cliente_documento ?? '-' }}</p>

        <table class="table">
            <thead><tr><th>Producto</th><th>Cant.</th><th>P. unit.</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach ($pedido->detalles as $d)
                    <tr>
                        <td>{{ $d->producto->nombre }}</td>
                        <td>{{ $d->cantidad }}</td>
                        <td>S/ {{ number_format($d->precio_unitario, 2) }}</td>
                        <td>S/ {{ number_format($d->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><th colspan="3" class="text-end">Total</th><th>S/ {{ number_format($pedido->total, 2) }}</th></tr>
            </tfoot>
        </table>

        <p class="text-muted small mb-3">Atendido por: {{ $pedido->user->name }}</p>
        <button onclick="window.print()" class="btn btn-mass">Imprimir</button>
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
@endsection