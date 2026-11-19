@extends('layouts.mass')

@section('titulo', 'Usuarios - Tiendas Mass')

@section('contenido')
    <h3 class="mb-4" style="color: var(--azul)">Usuarios</h3>

    <div class="tarjeta p-4 mb-4">
        <h5 class="mb-3">Nuevo usuario</h5>
        <form method="POST" action="{{ route('usuarios.store') }}" class="row g-2">
            @csrf
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Nombre" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-3">
                <input type="email" name="email" class="form-control" placeholder="Correo" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-2">
                <input type="password" name="password" class="form-control" placeholder="Contraseña (mín. 8)" required>
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select">
                    <option value="usuario">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-amarillo w-100">Crear</button>
            </div>
        </form>
        @if ($errors->any())
            <div class="text-danger small mt-2">{{ $errors->first() }}</div>
        @endif
    </div>

    <div class="tarjeta p-4">
        <table class="table align-middle mb-0">
            <thead><tr><th>Nombre</th><th>Correo</th><th>Rol</th><th></th></tr></thead>
            <tbody>
                @foreach ($usuarios as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <form method="POST" action="{{ route('usuarios.update', $u) }}" class="d-flex gap-2">
                                @csrf @method('PATCH')
                                <select name="role" class="form-select form-select-sm" style="width:150px" @disabled($u->id === auth()->id())>
                                    <option value="usuario" @selected($u->role === 'usuario')>Usuario</option>
                                    <option value="admin" @selected($u->role === 'admin')>Administrador</option>
                                </select>
                                @if ($u->id !== auth()->id())
                                    <button class="btn btn-sm btn-mass">Guardar</button>
                                @endif
                            </form>
                        </td>
                        <td class="text-end">
                            @if ($u->id !== auth()->id())
                                <form method="POST" action="{{ route('usuarios.destroy', $u) }}" onsubmit="return confirm('¿Eliminar usuario?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection