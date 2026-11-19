@extends('layouts.mass')

@section('titulo', 'Categorías - Tiendas Mass')

@section('contenido')
    <h3 class="mb-4" style="color: var(--azul)">Categorías</h3>

    <div class="tarjeta p-4 mb-4">
        <form method="POST" action="{{ route('categorias.store') }}" class="row g-2">
            @csrf
            <div class="col-md-8">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre de la categoría" value="{{ old('nombre') }}" required>
                @error('nombre') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <button class="btn btn-amarillo w-100">Agregar categoría</button>
            </div>
        </form>
    </div>

    <div class="tarjeta p-4">
        <table class="table align-middle mb-0">
            <thead>
                <tr><th>Nombre</th><th>Productos</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($categorias as $c)
                    <tr>
                        <td>{{ $c->nombre }}</td>
                        <td>{{ $c->productos_count }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('categorias.destroy', $c) }}" onsubmit="return confirm('¿Eliminar categoría?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection