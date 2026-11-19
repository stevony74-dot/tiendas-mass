<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,usuario',
        ]);

        $datos['password'] = Hash::make($datos['password']);
        User::create($datos);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario creado.');
    }

    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'role' => 'required|in:admin,usuario',
        ]);

        if ($usuario->id === $request->user()->id) {
            return back()->with('error', 'No puedes cambiar tu propio rol.');
        }

        $usuario->update($datos);
        return back()->with('ok', 'Rol actualizado.');
    }

    public function destroy(Request $request, User $usuario)
    {
        if ($usuario->id === $request->user()->id) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        if ($usuario->pedidos()->exists() || $usuario->movimientos()->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene pedidos o movimientos.');
        }

        $usuario->delete();
        return back()->with('ok', 'Usuario eliminado.');
    }
}