@extends('layouts.mass')

@section('titulo', 'Producto - Tiendas Mass')

@section('contenido')
    <h3 class="mb-4" style="color: var(--azul)">{{ $producto->exists ? 'Editar producto' : 'Nuevo producto' }}</h3>

    <div class="tarjeta p-4" style="max-width: 600px">
        <form method="POST" action="{{ $producto->exists ? route('productos.update', $producto) : route('productos.store') }}">
            @csrf
            @if ($producto->exists) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
                @error('nombre') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select name="categoria_id" class="form-select" required>
                    @foreach ($categorias as $c)
                        <option value="{{ $c->id }}" @selected(old('categoria_id', $producto->categoria_id) == $c->id)>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Precio (S/)</label>
                    <input type="number" step="0.01" min="0" name="precio" class="form-control" value="{{ old('precio', $producto->precio) }}" required>
                    @error('precio') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" min="0" name="stock" class="form-control" value="{{ old('stock', $producto->stock ?? 0) }}" required>
                    @error('stock') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Stock mínimo</label>
                    <input type="number" min="0" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', $producto->stock_minimo ?? 5) }}" required>
                    @error('stock_minimo') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>

            <button class="btn btn-amarillo">Guardar</button>
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
@endsection