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
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register'); 
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren login)
Route::middleware(['auth.custom'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de libros - Todos los usuarios pueden VER libros
    Route::get('/libros', [LibroController::class, 'index'])->name('libros.index');
    Route::get('/libros/buscar', [LibroController::class, 'buscar'])->name('libros.buscar');
    Route::get('/libros/{id}', [LibroController::class, 'show'])->name('libros.show');
    
    // Rutas SOLO para BIBLIOTECARIOS
    Route::middleware(['role:BIBLIOTECARIO'])->group(function () {
        // Gestión completa de libros (crear, editar, eliminar)
        Route::get('/libros/create', [LibroController::class, 'create'])->name('libros.create');
        Route::post('/libros', [LibroController::class, 'store'])->name('libros.store');
        Route::get('/libros/{id}/edit', [LibroController::class, 'edit'])->name('libros.edit');
        Route::put('/libros/{id}', [LibroController::class, 'update'])->name('libros.update');
        Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->name('libros.destroy');
        
        // Gestión completa de usuarios
        Route::resource('usuarios', UsuarioController::class);
        /*
        Esto crea automáticamente las siguientes rutas:
        GET /usuarios                -> usuarios.index    (lista de usuarios)
        GET /usuarios/create         -> usuarios.create   (formulario crear)
        POST /usuarios               -> usuarios.store    (guardar nuevo)
        GET /usuarios/{id}           -> usuarios.show     (ver usuario)
        GET /usuarios/{id}/edit      -> usuarios.edit     (formulario editar)
        PUT /usuarios/{id}           -> usuarios.update   (actualizar)
        DELETE /usuarios/{id}        -> usuarios.destroy  (eliminar/desactivar)
        */
    });
});

// Ruta de prueba de conexión Oracle (opcional)
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

// Ruta de debug temporal (eliminar en producción)
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
        
        // 3. Buscar usuario
        echo "<p><strong>3. Buscando usuario...</strong></p>";
        $usuario = DB::connection('oracle')->select(
            'SELECT id, nombre, email, password, rol FROM usuarios WHERE UPPER(email) = UPPER(?)',
            [$email]
        );
        
        if (empty($usuario)) {
            echo "❌ Usuario no encontrado<br>";
            return;
        }
        
        $user = $usuario[0];
        echo "✅ Usuario encontrado: " . $user->nombre . "<br>";
        echo "Rol: " . $user->rol . "<br>";
        
        // 4. Verificar password
        echo "<p><strong>4. Verificando contraseña:</strong></p>";
        $check = Hash::check($password, $user->password);
        echo "Password check: " . ($check ? "✅ CORRECTO" : "❌ INCORRECTO") . "<br>";
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
});
// Reemplaza la ruta /debug-oracle en routes/web.php con esta versión más simple:

Route::get('/debug-oracle', function () {
    try {
        echo "<h2>🔍 Debug Oracle - Biblioteca Virtual</h2>";
        
        // 1. Verificar conexión
        echo "<h3>1. Conexión Oracle</h3>";
        $test = DB::connection('oracle')->select('SELECT 1 as test FROM DUAL');
        echo "✅ Conexión OK<br><br>";
        
        // 2. Buscar usuario directamente
        echo "<h3>2. Buscar usuario usuario@biblioteca.com</h3>";
        $email = 'usuario@biblioteca.com';
        $usuario = DB::connection('oracle')->select(
            'SELECT * FROM usuarios WHERE UPPER(email) = UPPER(?)',
            [$email]
        );
        
        if (empty($usuario)) {
            echo "❌ Usuario '$email' no encontrado<br><br>";
            
            // 3. Mostrar todos los usuarios
            echo "<h3>3. Todos los usuarios en la base de datos:</h3>";
            $todos = DB::connection('oracle')->select('SELECT * FROM usuarios LIMIT 5');
            
            if (empty($todos)) {
                echo "❌ No hay usuarios en la base de datos<br>";
            } else {
                foreach($todos as $i => $u) {
                    echo "<h4>Usuario #" . ($i + 1) . ":</h4>";
                    echo "<pre>";
                    var_dump($u);
                    echo "</pre><hr>";
                }
            }
        } else {
            $user = $usuario[0];
            echo "✅ Usuario encontrado<br>";
            
            // 4. Mostrar todas las propiedades del usuario
            echo "<h3>4. Propiedades del objeto usuario:</h3>";
            echo "<pre>";
            var_dump($user);
            echo "</pre>";
            
            // 5. Probar acceso a propiedades de diferentes formas
            echo "<h3>5. Intentar acceder a diferentes propiedades:</h3>";
            
            $properties = ['id', 'ID', 'nombre', 'NOMBRE', 'email', 'EMAIL', 'password', 'PASSWORD', 'rol', 'ROL'];
            
            foreach($properties as $prop) {
                if (property_exists($user, $prop)) {
                    if (strtolower($prop) === 'password') {
                        echo "✅ Propiedad '$prop' existe = " . substr($user->$prop, 0, 20) . "...<br>";
                    } else {
                        echo "✅ Propiedad '$prop' existe = '{$user->$prop}'<br>";
                    }
                } else {
                    echo "❌ Propiedad '$prop' NO existe<br>";
                }
            }
            
            // 6. Listar todas las propiedades disponibles
            echo "<h3>6. Todas las propiedades disponibles:</h3>";
            $reflection = new ReflectionObject($user);
            $props = $reflection->getProperties();
            if (empty($props)) {
                // Si no hay propiedades públicas, usar get_object_vars
                $vars = get_object_vars($user);
                echo "<pre>";
                foreach($vars as $key => $value) {
                    if (strtolower($key) === 'password') {
                        echo "Clave: '$key' = " . substr($value, 0, 20) . "...\n";
                    } else {
                        echo "Clave: '$key' = '$value'\n";
                    }
                }
                echo "</pre>";
            } else {
                foreach($props as $prop) {
                    echo "Propiedad: " . $prop->getName() . "<br>";
                }
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
        echo "Línea: " . $e->getLine() . "<br>";
        echo "Archivo: " . $e->getFile() . "<br>";
    }
});