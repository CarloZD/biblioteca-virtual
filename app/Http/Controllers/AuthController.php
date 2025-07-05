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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            // Obtener usuario por email
            $usuario = DB::connection('oracle')->select(
                'SELECT * FROM usuarios WHERE UPPER(email) = UPPER(?) AND activo = 1',
                [$request->email]
            );

            if (empty($usuario)) {
                return back()->withErrors(['email' => 'Usuario no encontrado o inactivo'])->withInput();
            }

            $user = $usuario[0];

            // Convertir objeto a array para manejo más fácil
            $userArray = (array) $user;
            
            // Buscar las propiedades correctas (pueden estar en mayúsculas o minúsculas)
            $userId = null;
            $userName = null;
            $userEmail = null;
            $userPassword = null;
            $userRole = null;

            foreach($userArray as $key => $value) {
                $keyUpper = strtoupper($key);
                switch($keyUpper) {
                    case 'ID':
                        $userId = $value;
                        break;
                    case 'NOMBRE':
                        $userName = $value;
                        break;
                    case 'EMAIL':
                        $userEmail = $value;
                        break;
                    case 'PASSWORD':
                        $userPassword = $value;
                        break;
                    case 'ROL':
                        $userRole = $value;
                        break;
                }
            }

            // Verificar que tenemos todos los datos necesarios
            if (!$userPassword) {
                return back()->withErrors(['email' => 'Error: No se pudo obtener la contraseña del usuario'])->withInput();
            }

            // Verificar contraseña
            if (!Hash::check($request->password, $userPassword)) {
                return back()->withErrors(['email' => 'Contraseña incorrecta'])->withInput();
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
            return back()->withErrors(['email' => 'Error de conexión: ' . $e->getMessage()])->withInput();
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            // Verificar si el email ya existe
            $existing = DB::connection('oracle')->select(
                'SELECT COUNT(*) as count FROM usuarios WHERE UPPER(email) = UPPER(?)',
                [$request->email]
            );

            $count = (array) $existing[0];
            $countValue = null;
            foreach($count as $key => $value) {
                if (strtoupper($key) === 'COUNT') {
                    $countValue = $value;
                    break;
                }
            }

            if ($countValue > 0) {
                return back()->with('error', 'El email ya está registrado')->withInput();
            }

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