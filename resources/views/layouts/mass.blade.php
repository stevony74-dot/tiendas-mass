<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Tiendas Mass')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --amarillo: #FFD500; --azul: #0B3D91; --gris: #F3F4F6; }
        body { background: var(--gris); }
        .barra { background: var(--amarillo); border-bottom: 4px solid var(--azul); }
        .barra .marca { color: var(--azul); font-weight: 800; font-size: 1.4rem; text-decoration: none; }
        .lateral { background: var(--azul); min-height: calc(100vh - 68px); padding-top: 10px; }
        .lateral a { color: #fff; display: block; padding: 12px 18px; text-decoration: none; border-radius: 8px; margin: 4px 8px; }
        .lateral a:hover, .lateral a.activo { background: var(--amarillo); color: var(--azul); font-weight: 600; }
        .tarjeta { background: #fff; border-radius: 14px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .btn-mass { background: var(--azul); color: #fff; border: none; }
        .btn-mass:hover { background: #082c69; color: #fff; }
        .btn-amarillo { background: var(--amarillo); color: var(--azul); font-weight: 600; border: none; }
        .btn-amarillo:hover { background: #e6c000; color: var(--azul); }
    </style>
</head>
<body>
    <nav class="barra d-flex justify-content-between align-items-center px-4 py-2">
        <a href="{{ route('dashboard') }}">
    <img src="{{ asset('images/mass-logo.png') }}" alt="Tiendas Mass" style="height: 48px;"></a>
        <div class="d-flex align-items-center gap-3">
            <span class="fw-semibold" style="color: var(--azul)">
                {{ auth()->user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-mass">Cerrar sesion</button>
            </form>
        </div>
    </nav>

    @php
        $menu = auth()->user()->esAdmin()
            ? [
                ['Inicio', 'admin.panel'],
                ['Productos', 'productos.index'],
                ['Categorías', 'categorias.index'],
                ['Movimientos', 'movimientos.index'],
                ['Pedidos', 'pedidos.index'],
                ['Reposiciones', 'reposiciones.index'],
                ['Reportes', 'reportes.index'],
                ['Usuarios', 'usuarios.index'],
            ]
            : [
                ['Inicio', 'usuario.panel'],
                ['Nuevo pedido', 'pedidos.create'],
                ['Inventario', 'productos.index'],
                ['Reportes', 'reportes.index'],
            ];
    @endphp

    <div class="container-fluid">
        <div class="row">
            <aside class="col-12 col-md-2 lateral">
                @foreach ($menu as [$texto, $ruta])
                    @if (Route::has($ruta))
                        <a href="{{ route($ruta) }}" class="{{ request()->routeIs($ruta) ? 'activo' : '' }}">{{ $texto }}</a>
                    @endif
                @endforeach
            </aside>
            <main class="col-12 col-md-10 p-4">
                @if (session('ok'))
                    <div class="alert alert-success">{{ session('ok') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @yield('contenido')
            </main>
        </div>
    </div>
</body>
</html>