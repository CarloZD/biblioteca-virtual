<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        // Solo bibliotecarios pueden ver la lista de usuarios
        if (session('usuario_rol') !== 'BIBLIOTECARIO') {
            return redirect()->route('dashboard')->with('error', 'No tiene permisos para acceder a esta sección');
        }

        try {
            // Obtener todos los usuarios (Oracle devuelve propiedades en minúsculas)
            $usuarios = DB::connection('oracle')->select('
                SELECT id, nombre, email, rol, activo, created_at 
                FROM usuarios 
                ORDER BY nombre
            ');

            return view('usuarios.index', compact('usuarios'));

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error al cargar usuarios: ' . $e->getMessage());
        }
    }

    public function create()
    {
        if (session('usuario_rol') !== 'BIBLIOTECARIO') {
            return redirect()->route('dashboard')->with('error', 'No tiene permisos para esta acción');
        }

        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        if (session('usuario_rol') !== 'BIBLIOTECARIO') {
            return redirect()->route('dashboard')->with('error', 'No tiene permisos para esta acción');
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:BIBLIOTECARIO,USUARIO'
        ]);

        try {
            // Usar procedimiento almacenado para crear usuario
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->prepare('
                BEGIN 
                    pkg_usuarios.crear_usuario(:nombre, :email, :password, :rol, :result); 
                END;
            ');

            $nombre = $request->nombre;
            $email = $request->email;
            $password = Hash::make($request->password);
            $rol = $request->rol;
            $result = null;

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':result', $result, \PDO::PARAM_INT);

            $stmt->execute();

            if ($result == 1) {
                return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
            } elseif ($result == -2) {
                return back()->with('error', 'El email ya está registrado')->withInput();
            } else {
                return back()->with('error', 'Error al crear usuario')->withInput();
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        if (session('usuario_rol') !== 'BIBLIOTECARIO') {
            return redirect()->route('dashboard')->with('error', 'No tiene permisos para esta acción');
        }

        try {
            $usuario = DB::connection('oracle')->select('
                SELECT id, nombre, email, rol, activo, created_at 
                FROM usuarios 
                WHERE id = ?
            ', [$id]);

            if (empty($usuario)) {
                return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
            }

            return view('usuarios.show', ['usuario' => $usuario[0]]);

        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (session('usuario_rol') !== 'BIBLIOTECARIO') {
            return redirect()->route('dashboard')->with('error', 'No tiene permisos para esta acción');
        }

        try {
            $usuario = DB::connection('oracle')->select('
                SELECT id, nombre, email, rol, activo 
                FROM usuarios 
                WHERE id = ?
            ', [$id]);

            if (empty($usuario)) {
                return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
            }

            return view('usuarios.edit', ['usuario' => $usuario[0]]);

        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (session('usuario_rol') !== 'BIBLIOTECARIO') {
            return redirect()->route('dashboard')->with('error', 'No tiene permisos para esta acción');
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'rol' => 'required|in:BIBLIOTECARIO,USUARIO',
            'activo' => 'boolean',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        try {
            // Construir query de actualización usando nombres en minúsculas
            $updateFields = [
                'nombre = ?',
                'email = ?', 
                'rol = ?',
                'activo = ?',
                'updated_at = SYSDATE'
            ];
            
            $params = [
                $request->nombre,
                $request->email,
                $request->rol,
                $request->has('activo') ? 1 : 0
            ];

            // Si se proporciona nueva contraseña
            if ($request->filled('password')) {
                $updateFields[] = 'password = ?';
                $params[] = Hash::make($request->password);
            }

            $params[] = $id; // Para el WHERE

            $sql = 'UPDATE usuarios SET ' . implode(', ', $updateFields) . ' WHERE id = ?';
            
            $affected = DB::connection('oracle')->update($sql, $params);

            if ($affected > 0) {
                return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
            } else {
                return back()->with('error', 'Usuario no encontrado')->withInput();
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        if (session('usuario_rol') !== 'BIBLIOTECARIO') {
            return redirect()->route('dashboard')->with('error', 'No tiene permisos para esta acción');
        }

        try {
            // No eliminar físicamente, solo desactivar
            $affected = DB::connection('oracle')->update(
                'UPDATE usuarios SET activo = 0, updated_at = SYSDATE WHERE id = ?', 
                [$id]
            );
            
            if ($affected > 0) {
                return redirect()->route('usuarios.index')->with('success', 'Usuario desactivado exitosamente');
            } else {
                return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
            }

        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al desactivar: ' . $e->getMessage());
        }
    }
}