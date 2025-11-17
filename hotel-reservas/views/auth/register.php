<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\auth\register.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Registro' ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            width: 100%;
            padding: 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5568d3;
        }
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
        .text-center {
            text-align: center;
            margin-top: 20px;
        }
        a {
            color: #667eea;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>🏨 Registro</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <form action="/hotel-reservas/public/register" method="POST">
            <div class="form-group">
                <label>👤 Nombre Completo</label>
                <input type="text" name="nombre" required autocomplete="name">
            </div>
            
            <div class="form-group">
                <label>📧 Email</label>
                <input type="email" name="email" required autocomplete="email">
            </div>
            
            <div class="form-group">
                <label>🔒 Contraseña</label>
                <input type="password" name="password" required minlength="6" autocomplete="new-password">
            </div>
            
            <div class="form-group">
                <label>🔒 Confirmar Contraseña</label>
                <input type="password" name="password_confirm" required minlength="6" autocomplete="new-password">
            </div>
            
            <button type="submit" class="btn">Registrarse</button>
        </form>
        
        <div class="text-center">
            <p>¿Ya tienes cuenta? <a href="/hotel-reservas/public/login">Inicia sesión aquí</a></p>
            <p style="margin-top: 10px;"><a href="/hotel-reservas/public/">← Volver al inicio</a></p>
        </div>
    </div>
</body>
</html>