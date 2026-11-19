@extends('layouts.mass')

@section('titulo', 'Movimientos - Tiendas Mass')

@section('contenido')
    <h3 class="mb-4" style="color: var(--azul)">Entradas y salidas</h3>

    <div class="tarjeta p-4 mb-4">
        <form method="POST" action="{{ route('movimientos.store') }}" class="row g-2">
            @csrf
            <div class="col-md-4">
                <select name="producto_id" class="form-select" required>
                    @foreach ($productos as $p)
                        <option value="{{ $p->id }}">{{ $p->nombre }} (stock: {{ $p->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="tipo" class="form-select" required>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="cantidad" min="1" class="form-control" placeholder="Cantidad" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="motivo" class="form-control" placeholder="Motivo">
            </div>
            <div class="col-md-2">
                <button class="btn btn-amarillo w-100">Registrar</button>
            </div>
        </form>
        @if ($errors->any())
            <div class="text-danger small mt-2">{{ $errors->first() }}</div>
        @endif
    </div>

    <div class="tarjeta p-4">
        <table class="table align-middle mb-0">
            <thead>
                <tr><th>Fecha</th><th>Producto</th><th>Tipo</th><th>Cantidad</th><th>Motivo</th><th>Usuario</th></tr>
            </thead>
            <tbody>
                @forelse ($movimientos as $m)
                    <tr>
                        <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $m->producto->nombre }}</td>
                        <td>
                            <span class="badge {{ $m->tipo === 'entrada' ? 'text-bg-success' : 'text-bg-danger' }}">{{ ucfirst($m->tipo) }}</span>
                        </td>
                        <td>{{ $m->cantidad }}</td>
                        <td>{{ $m->motivo }}</td>
                        <td>{{ $m->user->name }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Sin movimientos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection