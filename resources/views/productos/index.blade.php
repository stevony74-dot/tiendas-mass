@extends('layouts.mass')

@section('titulo', 'Productos - Tiendas Mass')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0" style="color: var(--azul)">Inventario</h3>
        @if (auth()->user()->esAdmin())
            <a href="{{ route('productos.create') }}" class="btn btn-amarillo">+ Nuevo producto</a>
        @endif
    </div>

    <div class="tarjeta p-3 mb-3">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar producto..." value="{{ request('buscar') }}">
            <button class="btn btn-mass">Buscar</button>
        </form>
    </div>

    <div class="tarjeta p-4">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Estado</th>
                    @if (auth()->user()->esAdmin()) <th></th> @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $p)
                    <tr>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->categoria->nombre }}</td>
                        <td>S/ {{ number_format($p->precio, 2) }}</td>
                        <td>{{ $p->stock }}</td>
                        <td>
                            @if ($p->stockBajo())
                                <span class="badge text-bg-warning">Stock bajo</span>
                            @else
                                <span class="badge text-bg-success">Disponible</span>
                            @endif
                        </td>
                        @if (auth()->user()->esAdmin())
                            <td class="text-end">
                                <a href="{{ route('productos.edit', $p) }}" class="btn btn-sm btn-mass">Editar</a>
                                <form method="POST" action="{{ route('productos.destroy', $p) }}" class="d-inline" onsubmit="return confirm('¿Eliminar producto?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Sin productos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection