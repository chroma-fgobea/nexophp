<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Iniciar sesión
session_start();

// Verificar si ya hay una sesión activa
if (is_logged_in()) {
    redirect('dashboard.php');
}

// Variable para mensajes de error
$error = '';

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? sanitize($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($username) || empty($password)) {
        $error = 'Por favor, ingresa usuario y contraseña.';
    } else {
        // Conectar a la base de datos
        $conn = db_connect();
        
        // Buscar el usuario
        $stmt = $conn->prepare("SELECT admin_id, username, password, role FROM administrators WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verificar la contraseña (en un entorno real, usaríamos password_verify)
            if ($password === 'admin123') { // Simplificado para el prototipo
                // Iniciar sesión
                $_SESSION['admin_id'] = $user['admin_id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_role'] = $user['role'];
                
                // Actualizar último login
                $stmt = $conn->prepare("UPDATE administrators SET last_login = CURRENT_TIMESTAMP WHERE admin_id = ?");
                $stmt->bind_param("i", $user['admin_id']);
                $stmt->execute();
                
                // Redirigir al dashboard
                redirect('dashboard.php');
            } else {
                $error = 'Contraseña incorrecta.';
            }
        } else {
            $error = 'Usuario no encontrado.';
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nexo.ia Bot Sandbox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00A8E8;
            --secondary-color: #FF0066;
            --dark-color: #333333;
            --light-color: #F8F9FA;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
        }
        
        .login-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo img {
            max-width: 200px;
            height: auto;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 20px;
            color: var(--dark-color);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 168, 232, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #0096cc;
            border-color: #0096cc;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <img src="../public_html/assets/images/nexoia-logo.png" alt="Nexo.ia Logo">
            </div>
            
            <h2 class="login-title">Panel de Administración</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
