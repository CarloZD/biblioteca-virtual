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

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    private function getObjectProperty($obj, $property)
    {
        // Intentar con mayúsculas primero (Oracle típico)
        $upperProperty = strtoupper($property);
        if (property_exists($obj, $upperProperty)) {
            return $obj->$upperProperty;
        }
        
        // Intentar con minúsculas
        $lowerProperty = strtolower($property);
        if (property_exists($obj, $lowerProperty)) {
            return $obj->$lowerProperty;
        }
        
        // Intentar con el nombre original
        if (property_exists($obj, $property)) {
            return $obj->$property;
        }
        
        return null;
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

            // Debug: Mostrar las propiedades disponibles
            \Log::info('Propiedades del usuario:', (array)$user);

            // Obtener las propiedades de forma robusta
            $userId = $this->getObjectProperty($user, 'id');
            $userName = $this->getObjectProperty($user, 'nombre');
            $userEmail = $this->getObjectProperty($user, 'email');
            $userPassword = $this->getObjectProperty($user, 'password');
            $userRole = $this->getObjectProperty($user, 'rol');

            if (!$userPassword) {
                return back()->withErrors(['email' => 'Error en la estructura de datos del usuario'])->withInput();
            }

            // Verificar contraseña
            if (!Hash::check($request->password, $userPassword)) {
                return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
            }

            // Crear sesión
            Session::put([
                'usuario_id' => $userId,
                'usuario_nombre' => $userName,
                'usuario_email' => $userEmail,
                'usuario_rol' => $userRole,
                'usuario_logueado' => true
            ]);

            return redirect()->route('dashboard')->with('success', 'Bienvenido ' . $userName);

        } catch (\Exception $e) {
            \Log::error('Error en login: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Error de conexión: ' . $e->getMessage()])->withInput();
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:oracle.usuarios,email',
            'password' => 'required|string|min:6|confirmed',
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
            $rol = 'USUARIO'; // Por defecto
            $result = null;

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':result', $result, \PDO::PARAM_INT);

            $stmt->execute();

            if ($result == 1) {
                return redirect()->route('login')->with('success', 'Usuario registrado exitosamente. Ahora puedes iniciar sesión.');
            } elseif ($result == -2) {
                return back()->with('error', 'El email ya está registrado')->withInput();
            } else {
                return back()->with('error', 'Error al registrar usuario')->withInput();
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}