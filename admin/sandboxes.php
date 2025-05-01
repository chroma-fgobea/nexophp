<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!is_logged_in()) {
    redirect('login.php');
}

// Obtener lista de sandboxes
$conn = db_connect();
$sql = "SELECT s.*, c.company_name, c.unique_url_key 
        FROM sandboxes s 
        JOIN clients c ON s.client_id = c.client_id 
        ORDER BY s.created_at DESC";
$result = $conn->query($sql);

$sandboxes = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sandboxes[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandboxes - Nexo.ia Bot Sandbox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Gestión de Sandboxes</h1>
                <div class="user-info">
                    <div class="avatar"><?php echo substr($_SESSION['admin_username'], 0, 1); ?></div>
                    <div>
                        <div class="user-name"><?php echo $_SESSION['admin_username']; ?></div>
                        <div class="user-role"><?php echo ucfirst($_SESSION['admin_role']); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Lista de Sandboxes</span>
                    <a href="clients.php" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Nuevo Sandbox
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Cliente</th>
                                    <th>Creado</th>
                                    <th>Actualizado</th>
                                    <th>URL</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sandboxes as $sandbox): ?>
                                <tr>
                                    <td><?php echo $sandbox['sandbox_id']; ?></td>
                                    <td><?php echo $sandbox['name']; ?></td>
                                    <td>
                                        <a href="client_detail.php?id=<?php echo $sandbox['client_id']; ?>">
                                            <?php echo $sandbox['company_name']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($sandbox['created_at'])); ?></td>
                                    <td>
                                        <?php echo $sandbox['updated_at'] ? date('d/m/Y H:i', strtotime($sandbox['updated_at'])) : 'Sin actualizar'; ?>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm" value="<?php echo SITE_URL; ?>/sandbox/<?php echo $sandbox['unique_url_key']; ?>" readonly>
                                            <button class="btn btn-outline-secondary btn-copy" type="button" data-clipboard-text="<?php echo SITE_URL; ?>/sandbox/<?php echo $sandbox['unique_url_key']; ?>">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="sandbox_detail.php?id=<?php echo $sandbox['sandbox_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="edit_sandbox.php?id=<?php echo $sandbox['sandbox_id']; ?>" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>
    <script>
        // Inicializar clipboard.js
        new ClipboardJS('.btn-copy');
    </script>
</body>
</html>
