@extends('layouts.mass')

@section('titulo', 'Reposiciones - Tiendas Mass')

@section('contenido')
    <h3 class="mb-4" style="color: var(--azul)">Pedidos de reposición</h3>

    <div class="tarjeta p-4">
        <table class="table align-middle mb-0">
            <thead>
                <tr><th>Fecha</th><th>Producto</th><th>Cantidad</th><th>Solicitó</th><th>Estado</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($reposiciones as $r)
                    <tr>
                        <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $r->producto->nombre }}</td>
                        <td>{{ $r->cantidad }}</td>
                        <td>{{ $r->user->name }}</td>
                        <td>
                            <span class="badge {{ $r->estado === 'pendiente' ? 'text-bg-warning' : 'text-bg-success' }}">{{ ucfirst($r->estado) }}</span>
                        </td>
                        <td class="text-end">
                            @if ($r->estado === 'pendiente')
                                <form method="POST" action="{{ route('reposiciones.atender', $r) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-mass">Marcar atendida</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Sin reposiciones.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection