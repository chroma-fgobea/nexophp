<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!is_logged_in()) {
    redirect('login.php');
}

// Obtener ID del cliente
$client_id = isset($_GET['client_id']) ? intval($_GET['client_id']) : 0;

// Verificar que el cliente existe
$client = get_client($client_id);
if (!$client) {
    // Cliente no encontrado, redirigir a la lista de clientes
    redirect('clients.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Sandbox - Nexo.ia Bot Sandbox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Crear Sandbox para <?php echo $client['company_name']; ?></h1>
                <div class="user-info">
                    <div class="avatar"><?php echo substr($_SESSION['admin_username'], 0, 1); ?></div>
                    <div>
                        <div class="user-name"><?php echo $_SESSION['admin_username']; ?></div>
                        <div class="user-role"><?php echo ucfirst($_SESSION['admin_role']); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <span>Información del Sandbox</span>
                </div>
                <div class="card-body">
                    <form action="process/create_sandbox.php" method="post">
                        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Sandbox</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="form-text">Un nombre descriptivo para identificar este sandbox.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            <div class="form-text">Una breve descripción del propósito de este sandbox.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="script_code" class="form-label">Código del Script de SendPulse</label>
                            <textarea class="form-control" id="script_code" name="script_code" rows="5" required></textarea>
                            <div class="form-text">Pega aquí el código del script generado en SendPulse.</div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="client_detail.php?id=<?php echo $client_id; ?>" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Crear Sandbox</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
    <script>
        // Inicializar CodeMirror para el editor de script
        var editor = CodeMirror.fromTextArea(document.getElementById("script_code"), {
            mode: "htmlmixed",
            lineNumbers: true,
            theme: "default",
            lineWrapping: true
        });
    </script>
</body>
</html>
