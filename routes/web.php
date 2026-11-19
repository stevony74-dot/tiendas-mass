<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Después del login: cada rol va a su panel
Route::get('/dashboard', function () {
    return Auth::user()->esAdmin()
        ? redirect()->route('admin.panel')
        : redirect()->route('usuario.panel');
})->middleware(['auth'])->name('dashboard');

// Panel del administrador
Route::get('/admin', function () {
    return view('panel', [
        'totalProductos' => \App\Models\Producto::count(),
        'stockBajo' => \App\Models\Producto::whereColumn('stock', '<=', 'stock_minimo')->count(),
        'totalPedidos' => \App\Models\Pedido::count(),
    ]);
})->middleware(['auth', 'role:admin'])->name('admin.panel');

// Panel del usuario
Route::get('/usuario', function () {
    return view('panel', [
        'totalProductos' => \App\Models\Producto::count(),
        'stockBajo' => \App\Models\Producto::whereColumn('stock', '<=', 'stock_minimo')->count(),
        'totalPedidos' => \App\Models\Pedido::count(),
    ]);
})->middleware(['auth', 'role:usuario'])->name('usuario.panel');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Inventario: ambos roles pueden ver
Route::get('/productos', [ProductoController::class, 'index'])
    ->middleware('auth')->name('productos.index');

// Gestión: solo administrador
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('productos', ProductoController::class)->except(['index', 'show']);
    Route::resource('categorias', CategoriaController::class)->only(['index', 'store', 'destroy']);
    Route::get('/movimientos', [\App\Http\Controllers\MovimientoController::class, 'index'])->name('movimientos.index');
    Route::post('/movimientos', [\App\Http\Controllers\MovimientoController::class, 'store'])->name('movimientos.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/pedidos', [\App\Http\Controllers\PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/nuevo', [\App\Http\Controllers\PedidoController::class, 'create'])->name('pedidos.create');
    Route::post('/pedidos', [\App\Http\Controllers\PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{pedido}', [\App\Http\Controllers\PedidoController::class, 'show'])->name('pedidos.show');
    Route::post('/reposiciones', [\App\Http\Controllers\ReposicionController::class, 'store'])->name('reposiciones.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/reposiciones', [\App\Http\Controllers\ReposicionController::class, 'index'])->name('reposiciones.index');
    Route::post('/reposiciones/{reposicion}/atender', [\App\Http\Controllers\ReposicionController::class, 'atender'])->name('reposiciones.atender');
});

Route::middleware('auth')->group(function () {
    Route::get('/reportes', [\App\Http\Controllers\ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/inventario', [\App\Http\Controllers\ReporteController::class, 'inventario'])->name('reportes.inventario');
    Route::get('/reportes/pedidos', [\App\Http\Controllers\ReporteController::class, 'pedidos'])->name('reportes.pedidos');
    Route::get('/reportes/exportar/{tipo}', [\App\Http\Controllers\ReporteController::class, 'exportar'])->name('reportes.exportar');
});

// Ventas: solo administrador
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/reportes/ventas', [\App\Http\Controllers\ReporteController::class, 'ventas'])->name('reportes.ventas');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/usuarios', [\App\Http\Controllers\UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [\App\Http\Controllers\UsuarioController::class, 'store'])->name('usuarios.store');
    Route::patch('/usuarios/{usuario}', [\App\Http\Controllers\UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [\App\Http\Controllers\UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});

require __DIR__.'/auth.php';