<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Virtual - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        
        .login-left {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-30px, -30px) rotate(360deg); }
        }
        
        .login-right {
            padding: 60px 40px;
        }
        
        .logo {
            font-size: 3rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }
        
        .welcome-text {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }
        
        .subtitle {
            opacity: 0.9;
            font-size: 1rem;
            position: relative;
            z-index: 2;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 3;
        }
        
        .input-group .form-control {
            padding-left: 45px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.4);
        }
        
        .btn-register {
            background: transparent;
            border: 2px solid #3498db;
            color: #3498db;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            background: #3498db;
            color: white;
            transform: translateY(-2px);
        }
        
        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }
        
        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 20px;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .demo-accounts {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-top: 2rem;
        }
        
        .demo-accounts h6 {
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .demo-account {
            background: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid #3498db;
        }
        
        .demo-account:last-child {
            border-left-color: #e74c3c;
            margin-bottom: 0;
        }
        
        .demo-account small {
            display: block;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .demo-account .text-muted {
            font-size: 0.85rem;
            font-weight: normal;
        }
        
        @media (max-width: 768px) {
            .login-left {
                padding: 40px 20px;
            }
            
            .login-right {
                padding: 40px 20px;
            }
            
            .logo {
                font-size: 2rem;
            }
            
            .welcome-text {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="row g-0 h-100">
                <div class="col-lg-5">
                    <div class="login-left h-100">
                        <div>
                            <i class="fas fa-book-open logo"></i>
                            <h2 class="welcome-text">Biblioteca Virtual</h2>
                            <p class="subtitle">Tu biblioteca digital al alcance de un clic</p>
                            <div class="mt-4">
                                <i class="fas fa-search me-3"></i>
                                <i class="fas fa-bookmark me-3"></i>
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-7">
                    <div class="login-right">
                        <h3 class="mb-4 text-center">Bienvenido de vuelta</h3>
                        
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="input-group">
                                <i class="fas fa-envelope"></i>
                                <input type="email" class="form-control" name="email" 
                                       placeholder="Correo electrónico" value="{{ old('email') }}" required>
                            </div>
                            
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" class="form-control" name="password" 
                                       placeholder="Contraseña" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Iniciar Sesión
                            </button>
                        </form>
                        
                        <div class="divider">
                            <span>¿No tienes cuenta?</span>
                        </div>
                        
                        <a href="{{ route('register') }}" class="btn btn-register w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            Crear cuenta nueva
                        </a>
                        
                        <div class="demo-accounts">
                            <h6><i class="fas fa-info-circle me-2"></i>Cuentas de prueba</h6>
                            <div class="demo-account">
                                <small>Administrador</small>
                                <span class="text-muted">admin@biblioteca.com / password</span>
                            </div>
                            <div class="demo-account">
                                <small>Usuario</small>
                                <span class="text-muted">usuario@biblioteca.com / password</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>