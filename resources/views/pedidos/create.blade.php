@extends('layouts.mass')

@section('titulo', 'Nuevo pedido - Tiendas Mass')

@section('contenido')
    <h3 class="mb-4" style="color: var(--azul)">Registrar pedido</h3>

        @if (session('faltantes'))
        <div class="alert alert-warning">
            <strong>Falta de producto:</strong> no hay stock suficiente.
            @foreach (session('faltantes') as $f)
                <form method="POST" action="{{ route('reposiciones.store') }}" class="d-flex align-items-center gap-2 mt-2">
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $f['id'] }}">
                    <input type="hidden" name="cantidad" value="{{ $f['faltan'] }}">
                    <span>{{ $f['nombre'] }}: stock {{ $f['stock'] }}, faltan {{ $f['faltan'] }}.</span>
                    <button class="btn btn-sm btn-amarillo">Generar pedido de reposición</button>
                </form>
            @endforeach
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('pedidos.store') }}">
        @csrf

        <div class="tarjeta p-4 mb-4">
            <h5 class="mb-3">Datos del cliente</h5>
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Nombre del cliente</label>
                    <input type="text" name="cliente_nombre" class="form-control" value="{{ old('cliente_nombre') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">DNI / RUC</label>
                    <input type="text" name="cliente_documento" class="form-control" value="{{ old('cliente_documento') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Comprobante</label>
                    <select name="tipo_comprobante" class="form-select">
                        <option value="boleta" @selected(old('tipo_comprobante') === 'boleta')>Boleta</option>
                        <option value="factura" @selected(old('tipo_comprobante') === 'factura')>Factura</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="tarjeta p-4 mb-4">
            <h5 class="mb-3">Productos</h5>
            <table class="table align-middle mb-0">
                <thead>
                    <tr><th>Producto</th><th>Precio</th><th>Stock</th><th style="width:140px">Cantidad</th></tr>
                </thead>
                <tbody>
                    @foreach ($productos as $p)
                        <tr>
                            <td>{{ $p->nombre }}</td>
                            <td>S/ {{ number_format($p->precio, 2) }}</td>
                            <td>{{ $p->stock }}</td>
                            <td>
                                <input type="number" min="0" name="items[{{ $p->id }}]" class="form-control" value="{{ old('items.' . $p->id, 0) }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button class="btn btn-amarillo btn-lg">Confirmar pedido</button>
    </form>
@endsection