<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LibroController;

// Ruta principal - redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren login)
Route::middleware(['auth.custom'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de libros (todos los usuarios pueden ver)
    Route::resource('libros', LibroController::class);
    Route::get('/libros/buscar', [LibroController::class, 'buscar'])->name('libros.buscar');
    
    // Rutas de usuarios (solo bibliotecarios)
    Route::middleware(['role:BIBLIOTECARIO'])->group(function () {
        Route::resource('usuarios', UsuarioController::class);
    });
});

// Ruta de prueba (opcional)
Route::get('/test-db', function () {
    try {
        $usuarios = DB::connection('oracle')->select('SELECT COUNT(*) as total FROM usuarios');
        return response()->json([
            'status' => 'success',
            'usuarios' => $usuarios[0]->total,
            'message' => 'Oracle conectado correctamente'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error', 
            'message' => $e->getMessage()
        ], 500);
    }
});

// Ruta de debug temporal CORREGIDA
Route::get('/debug-login', function () {
    try {
        $email = 'admin@biblioteca.com';
        $password = 'password';
        
        echo "<h3>Debug Login - Biblioteca Virtual</h3>";
        
        // 1. Verificar conexión
        echo "<p><strong>1. Probando conexión...</strong></p>";
        $test = DB::connection('oracle')->select('SELECT 1 as test FROM DUAL');
        echo "✅ Conexión Oracle OK<br>";
        
        // 2. Contar usuarios
        echo "<p><strong>2. Contando usuarios...</strong></p>";
        $count = DB::connection('oracle')->select('SELECT COUNT(*) as total FROM usuarios');
        echo "Total usuarios: " . $count[0]->total . "<br>";
        
        // 3. Buscar usuario SIN ALIAS (Oracle en mayúsculas)
        echo "<p><strong>3. Buscando usuario sin alias...</strong></p>";
        $usuario = DB::connection('oracle')->select(
            'SELECT id, nombre, email, password, rol FROM usuarios WHERE UPPER(email) = UPPER(?)',
            [$email]
        );
        
        if (empty($usuario)) {
            echo "❌ Usuario no encontrado<br>";
            return;
        }
        
        $user = $usuario[0];
        echo "✅ Usuario encontrado<br>";
        
        // 4. Mostrar estructura del objeto
        echo "<p><strong>4. Estructura del objeto usuario:</strong></p>";
        echo "<pre>";
        var_dump($user);
        echo "</pre>";
        
        // 5. Intentar acceder a propiedades
        echo "<p><strong>5. Intentando acceder a propiedades:</strong></p>";
        foreach($user as $key => $value) {
            if ($key === 'PASSWORD') {
                echo "Clave: '$key' = " . substr($value, 0, 20) . "...<br>";
            } else {
                echo "Clave: '$key' = '$value'<br>";
            }
        }
        
        // 6. Verificar password
        echo "<p><strong>6. Verificando contraseña:</strong></p>";
        $passwordFromDB = '';
        foreach($user as $key => $value) {
            if (strtoupper($key) === 'PASSWORD') {
                $passwordFromDB = $value;
                break;
            }
        }
        
        if ($passwordFromDB) {
            $check = Hash::check($password, $passwordFromDB);
            echo "Password check: " . ($check ? "✅ CORRECTO" : "❌ INCORRECTO") . "<br>";
        } else {
            echo "❌ No se encontró el campo password<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
});