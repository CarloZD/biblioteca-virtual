<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Verificar que el usuario esté logueado
        if (!session('usuario_logueado')) {
            return redirect()->route('login');
        }

        try {
            // Obtener estadísticas de la base de datos
            $estadisticas = [
                'total_usuarios' => 0,
                'total_libros' => 0,
                'libros_disponibles' => 0,
                'usuario_nombre' => session('usuario_nombre'),
                'usuario_rol' => session('usuario_rol')
            ];

            // Contar usuarios activos
            $usuarios = DB::connection('oracle')->select('SELECT COUNT(*) as total FROM usuarios WHERE activo = 1');
            $estadisticas['total_usuarios'] = $usuarios[0]->total;

            // Contar libros totales
            $libros = DB::connection('oracle')->select('SELECT COUNT(*) as total FROM libros');
            $estadisticas['total_libros'] = $libros[0]->total;

            // Contar libros disponibles
            $disponibles = DB::connection('oracle')->select('SELECT COUNT(*) as total FROM libros WHERE disponible = 1');
            $estadisticas['libros_disponibles'] = $disponibles[0]->total;

            return view('dashboard', $estadisticas);

        } catch (\Exception $e) {
            return view('dashboard')->with('error', 'Error al cargar estadísticas: ' . $e->getMessage());
        }
    }
}