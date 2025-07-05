<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            // Obtener usuario por email
            $usuario = DB::connection('oracle')->select(
                'SELECT id, nombre, email, password, rol, activo FROM usuarios WHERE UPPER(email) = UPPER(?) AND activo = 1',
                [$request->email]
            );

            if (empty($usuario)) {
                return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
            }

            $user = $usuario[0];

            // CORREGIDO: usar minúsculas (como muestra el debug)
            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
            }

            // Crear sesión - CORREGIDO: usar minúsculas
            Session::put([
                'usuario_id' => $user->id,
                'usuario_nombre' => $user->nombre,
                'usuario_email' => $user->email,
                'usuario_rol' => $user->rol,
                'usuario_logueado' => true
            ]);

            return redirect()->route('dashboard')->with('success', 'Bienvenido ' . $user->nombre);

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Error de conexión: ' . $e->getMessage()])->withInput();
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}