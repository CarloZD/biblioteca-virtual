<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Virtual - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        
        .register-left {
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
        
        .register-left::before {
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
        
        .register-right {
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
        
        .btn-register {
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
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.4);
        }
        
        .btn-login {
            background: transparent;
            border: 2px solid #3498db;
            color: #3498db;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
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
        
        .password-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        
        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .password-requirements li {
            margin-bottom: 5px;
            color: #6c757d;
        }
        
        .strength-indicator {
            height: 4px;
            border-radius: 2px;
            margin-top: 5px;
            background: #e9ecef;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background: #dc3545; width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: #28a745; width: 100%; }
        
        @media (max-width: 768px) {
            .register-left {
                padding: 40px 20px;
            }
            
            .register-right {
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
    <div class="register-container">
        <div class="register-card">
            <div class="row g-0 h-100">
                <div class="col-lg-5">
                    <div class="register-left h-100">
                        <div>
                            <i class="fas fa-user-plus logo"></i>
                            <h2 class="welcome-text">Únete a nosotros</h2>
                            <p class="subtitle">Crea tu cuenta y accede a miles de libros digitales</p>
                            <div class="mt-4">
                                <i class="fas fa-book-reader me-3"></i>
                                <i class="fas fa-download me-3"></i>
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-7">
                    <div class="register-right">
                        <h3 class="mb-4 text-center">Crear Nueva Cuenta</h3>
                        
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       name="nombre" placeholder="Nombre completo" 
                                       value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="input-group">
                                <i class="fas fa-envelope"></i>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" placeholder="Correo electrónico" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" placeholder="Contraseña" 
                                       id="password" required>
                                <div class="strength-indicator" id="strengthIndicator"></div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       name="password_confirmation" placeholder="Confirmar contraseña" 
                                       id="passwordConfirm" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="password-requirements">
                                <h6><i class="fas fa-shield-alt me-2"></i>Requisitos de contraseña</h6>
                                <ul>
                                    <li>Mínimo 6 caracteres</li>
                                    <li>Al menos una letra mayúscula</li>
                                    <li>Al menos un número</li>
                                </ul>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-register w-100 text-white mt-3">
                                <i class="fas fa-user-plus me-2"></i>
                                Crear Cuenta
                            </button>
                        </form>
                        
                        <div class="divider">
                            <span>¿Ya tienes cuenta?</span>
                        </div>
                        
                        <a href="{{ route('login') }}" class="btn btn-login w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación de fortaleza de contraseña
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const indicator = document.getElementById('strengthIndicator');
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^A-Za-z0-9]/)) strength++;
            
            indicator.className = 'strength-indicator';
            if (strength === 1 || strength === 2) {
                indicator.classList.add('strength-weak');
            } else if (strength === 3) {
                indicator.classList.add('strength-medium');
            } else if (strength >= 4) {
                indicator.classList.add('strength-strong');
            }
        });
        
        // Validación de confirmación de contraseña
        document.getElementById('passwordConfirm').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            
            if (password !== confirm && confirm.length > 0) {
                this.setCustomValidity('Las contraseñas no coinciden');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>