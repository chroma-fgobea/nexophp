<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!is_logged_in()) {
    redirect('login.php');
}

// Obtener ID del sandbox
$sandbox_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verificar que el sandbox existe
$sandbox = get_sandbox($sandbox_id);
if (!$sandbox) {
    // Sandbox no encontrado, redirigir a la lista de sandboxes
    redirect('sandboxes.php');
}

// Obtener cliente
$client = get_client($sandbox['client_id']);

// Obtener feedback del sandbox
$feedback = get_sandbox_feedback($sandbox_id);

// Obtener sesiones de prueba
$sessions = get_sandbox_sessions($sandbox_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Sandbox - Nexo.ia Bot Sandbox</title>
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
                <h1><?php echo $sandbox['name']; ?></h1>
                <div class="user-info">
                    <div class="avatar"><?php echo substr($_SESSION['admin_username'], 0, 1); ?></div>
                    <div>
                        <div class="user-name"><?php echo $_SESSION['admin_username']; ?></div>
                        <div class="user-role"><?php echo ucfirst($_SESSION['admin_role']); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Información del Sandbox</span>
                            <a href="edit_sandbox.php?id=<?php echo $sandbox_id; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Cliente:</strong> 
                                <a href="client_detail.php?id=<?php echo $client['client_id']; ?>"><?php echo $client['company_name']; ?></a>
                            </div>
                            <div class="mb-3">
                                <strong>Descripción:</strong> 
                                <p><?php echo $sandbox['description'] ? $sandbox['description'] : 'Sin descripción'; ?></p>
                            </div>
                            <div class="mb-3">
                                <strong>URL del Sandbox:</strong>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?php echo SITE_URL; ?>/sandbox/<?php echo $client['unique_url_key']; ?>" readonly>
                                    <button class="btn btn-outline-secondary btn-copy" type="button" data-clipboard-text="<?php echo SITE_URL; ?>/sandbox/<?php echo $client['unique_url_key']; ?>">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <strong>Creado:</strong> <?php echo date('d/m/Y H:i', strtotime($sandbox['created_at'])); ?>
                            </div>
                            <div class="mb-3">
                                <strong>Última actualización:</strong> 
                                <?php echo $sandbox['updated_at'] ? date('d/m/Y H:i', strtotime($sandbox['updated_at'])) : 'Sin actualizar'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <span>Código del Script</span>
                        </div>
                        <div class="card-body">
                            <textarea id="script_code" class="form-control" rows="10" readonly><?php echo htmlspecialchars($sandbox['script_code']); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <span>Feedback del Cliente</span>
                        </div>
                        <div class="card-body">
                            <?php if (empty($feedback)): ?>
                                <p class="text-muted">No hay feedback disponible para este sandbox.</p>
                            <?php else: ?>
                                <?php foreach ($feedback as $item): ?>
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
                                    <div class="feedback-item <?php echo $feedback_class; ?>">
                                        <div class="feedback-header">
                                            <div class="feedback-type"><?php echo $type_text; ?></div>
                                            <div class="feedback-date"><?php echo date('d/m/Y H:i', strtotime($item['created_at'])); ?></div>
                                        </div>
                                        <div class="feedback-text">
                                            <?php echo nl2br(htmlspecialchars($item['feedback_text'])); ?>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($item['status']); ?></span>
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
                
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <span>Sesiones de Prueba</span>
                        </div>
                        <div class="card-body">
                            <?php if (empty($sessions)): ?>
                                <p class="text-muted">No hay sesiones de prueba registradas para este sandbox.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha</th>
                                                <th>Duración</th>
                                                <th>Mensajes</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($sessions as $session): ?>
                                                <?php 
                                                    $messages = get_session_messages($session['session_id']);
                                                    $message_count = count($messages);
                                                    
                                                    $duration = 'En curso';
                                                    if ($session['session_end']) {
                                                        $start = new DateTime($session['session_start']);
                                                        $end = new DateTime($session['session_end']);
                                                        $diff = $start->diff($end);
                                                        $duration = $diff->format('%i min %s seg');
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?php echo $session['session_id']; ?></td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($session['session_start'])); ?></td>
                                                    <td><?php echo $duration; ?></td>
                                                    <td><?php echo $message_count; ?></td>
                                                    <td>
                                                        <a href="session_detail.php?id=<?php echo $session['session_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i> Ver
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <span>Vista Previa del Sandbox</span>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-16x9">
                        <iframe src="<?php echo SITE_URL; ?>/sandbox/preview.php?key=<?php echo $client['unique_url_key']; ?>" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
    <script>
        // Inicializar clipboard.js
        new ClipboardJS('.btn-copy');
        
        // Inicializar CodeMirror para el editor de script
        var editor = CodeMirror.fromTextArea(document.getElementById("script_code"), {
            mode: "htmlmixed",
            lineNumbers: true,
            theme: "default",
            lineWrapping: true,
            readOnly: true
        });
    </script>
</body>
</html>
