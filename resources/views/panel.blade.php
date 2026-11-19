@extends('layouts.mass')

@section('titulo', 'Inicio - Tiendas Mass')

@section('contenido')
    <h3 class="mb-4" style="color: var(--azul)">Bienvenido, {{ auth()->user()->name }}</h3>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="tarjeta p-4">
                <div class="text-muted">Productos</div>
                <div class="fs-1 fw-bold" style="color: var(--azul)">{{ $totalProductos }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="tarjeta p-4">
                <div class="text-muted">Productos con stock bajo</div>
                <div class="fs-1 fw-bold text-warning">{{ $stockBajo }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="tarjeta p-4">
                <div class="text-muted">Pedidos registrados</div>
                <div class="fs-1 fw-bold" style="color: var(--azul)">{{ $totalPedidos }}</div>
            </div>
        </div>
    </div>
@endsection