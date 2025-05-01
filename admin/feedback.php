<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!is_logged_in()) {
    redirect('login.php');
}

// Obtener lista de feedback reciente
$conn = db_connect();
$sql = "SELECT f.*, s.name as sandbox_name, c.company_name 
        FROM client_feedback f 
        JOIN sandboxes s ON f.sandbox_id = s.sandbox_id 
        JOIN clients c ON s.client_id = c.client_id 
        ORDER BY f.created_at DESC";
$result = $conn->query($sql);

$feedback_items = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $feedback_items[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Nexo.ia Bot Sandbox</title>
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
                <h1>Feedback de Clientes</h1>
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
                    <span>Feedback Reciente</span>
                    <div>
                        <select id="filterStatus" class="form-select form-select-sm d-inline-block w-auto me-2">
                            <option value="all">Todos los estados</option>
                            <option value="new">Nuevos</option>
                            <option value="in_progress">En progreso</option>
                            <option value="resolved">Resueltos</option>
                        </select>
                        <select id="filterType" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="all">Todos los tipos</option>
                            <option value="approval">Aprobación</option>
                            <option value="correction">Corrección</option>
                            <option value="additional_info">Información Adicional</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($feedback_items)): ?>
                        <p class="text-muted">No hay feedback disponible.</p>
                    <?php else: ?>
                        <?php foreach ($feedback_items as $item): ?>
                            <?php 
                                $feedback_class = '';
                                switch ($item['feedback_type']) {
                                    case 'approval':
                                        $feedback_class = 'approval';
                                        $type_text = 'Aprobación';
                                        break;
                                    case 'correction':
                                        $feedback_class = 'correction';
                                        $type_text = 'Corrección';
                                        break;
                                    case 'additional_info':
                                        $feedback_class = 'additional_info';
                                        $type_text = 'Información Adicional';
                                        break;
                                }
                                
                                $status_class = '';
                                switch ($item['status']) {
                                    case 'new':
                                        $status_class = 'bg-danger';
                                        break;
                                    case 'in_progress':
                                        $status_class = 'bg-warning';
                                        break;
                                    case 'resolved':
                                        $status_class = 'bg-success';
                                        break;
                                }
                            ?>
                            <div class="feedback-item <?php echo $feedback_class; ?>" data-status="<?php echo $item['status']; ?>" data-type="<?php echo $item['feedback_type']; ?>">
                                <div class="feedback-header">
                                    <div class="feedback-type"><?php echo $type_text; ?></div>
                                    <div class="feedback-date"><?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?></div>
                                </div>
                                <div class="feedback-text">
                                    <?php echo nl2br(htmlspecialchars($item['feedback_text'])); ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($item['status']); ?></span>
                                        <small class="ms-2">
                                            <a href="sandbox_detail.php?id=<?php echo $item['sandbox_id']; ?>"><?php echo $item['sandbox_name']; ?></a> - 
                                            <a href="client_detail.php?id=<?php echo $item['client_id']; ?>"><?php echo $item['company_name']; ?></a>
                                        </small>
                                    </div>
                                    <?php if ($item['status'] !== 'resolved'): ?>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Actualizar estado
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php if ($item['status'] !== 'in_progress'): ?>
                                                    <li><a class="dropdown-item" href="process/update_feedback.php?id=<?php echo $item['feedback_id']; ?>&status=in_progress">En progreso</a></li>
                                                <?php endif; ?>
                                                <li><a class="dropdown-item" href="process/update_feedback.php?id=<?php echo $item['feedback_id']; ?>&status=resolved">Resuelto</a></li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtrar feedback por estado y tipo
        document.getElementById('filterStatus').addEventListener('change', filterFeedback);
        document.getElementById('filterType').addEventListener('change', filterFeedback);
        
        function filterFeedback() {
            const statusFilter = document.getElementById('filterStatus').value;
            const typeFilter = document.getElementById('filterType').value;
            
            const feedbackItems = document.querySelectorAll('.feedback-item');
            
            feedbackItems.forEach(item => {
                const status = item.getAttribute('data-status');
                const type = item.getAttribute('data-type');
                
                const statusMatch = statusFilter === 'all' || status === statusFilter;
                const typeMatch = typeFilter === 'all' || type === typeFilter;
                
                if (statusMatch && typeMatch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
