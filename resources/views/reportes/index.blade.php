@extends('layouts.mass')

@section('titulo', 'Reportes - Tiendas Mass')

@section('contenido')
    <h3 class="mb-4" style="color: var(--azul)">Reportes y análisis</h3>

    <div class="row g-3">
        @if (auth()->user()->esAdmin())
            <div class="col-md-4">
                <div class="tarjeta p-4 h-100">
                    <h5>Ventas</h5>
                    <p class="text-muted">Ventas por fecha, total por día y productos más vendidos.</p>
                    <a href="{{ route('reportes.ventas') }}" class="btn btn-amarillo">Ver reporte</a>
                </div>
            </div>
        @endif
        <div class="col-md-4">
            <div class="tarjeta p-4 h-100">
                <h5>Inventario</h5>
                <p class="text-muted">Existencias actuales y productos con stock bajo.</p>
                <a href="{{ route('reportes.inventario') }}" class="btn btn-amarillo">Ver reporte</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="tarjeta p-4 h-100">
                <h5>Pedidos</h5>
                <p class="text-muted">Listado de pedidos registrados.</p>
                <a href="{{ route('reportes.pedidos') }}" class="btn btn-amarillo">Ver reporte</a>
            </div>
        </div>
    </div>
@endsection