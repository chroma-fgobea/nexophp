<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!is_logged_in()) {
    redirect('login.php');
}

// Obtener lista de clientes
$clients = get_all_clients();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Nexo.ia Bot Sandbox</title>
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
                <h1>Gestión de Clientes</h1>
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
                    <span>Lista de Clientes</span>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">
                        <i class="bi bi-plus"></i> Nuevo Cliente
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Empresa</th>
                                    <th>Contacto</th>
                                    <th>Email</th>
                                    <th>Estado</th>
                                    <th>URL Única</th>
                                    <th>Sandboxes</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $client): ?>
                                <?php 
                                    $sandboxes = get_client_sandboxes($client['client_id']);
                                    $sandbox_count = count($sandboxes);
                                    
                                    $status_class = '';
                                    switch ($client['status']) {
                                        case 'active':
                                            $status_class = 'bg-success';
                                            break;
                                        case 'inactive':
                                            $status_class = 'bg-danger';
                                            break;
                                        case 'pending':
                                            $status_class = 'bg-warning';
                                            break;
                                    }
                                ?>
                                <tr>
                                    <td><?php echo $client['client_id']; ?></td>
                                    <td><?php echo $client['company_name']; ?></td>
                                    <td><?php echo $client['contact_name']; ?></td>
                                    <td><?php echo $client['email']; ?></td>
                                    <td><span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($client['status']); ?></span></td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm" value="<?php echo SITE_URL; ?>/sandbox/<?php echo $client['unique_url_key']; ?>" readonly>
                                            <button class="btn btn-outline-secondary btn-copy" type="button" data-clipboard-text="<?php echo SITE_URL; ?>/sandbox/<?php echo $client['unique_url_key']; ?>">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td><?php echo $sandbox_count; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="client_detail.php?id=<?php echo $client['client_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editClientModal" 
                                                    data-client-id="<?php echo $client['client_id']; ?>"
                                                    data-company="<?php echo $client['company_name']; ?>"
                                                    data-contact="<?php echo $client['contact_name']; ?>"
                                                    data-email="<?php echo $client['email']; ?>"
                                                    data-phone="<?php echo $client['phone']; ?>"
                                                    data-status="<?php echo $client['status']; ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="create_sandbox.php?client_id=<?php echo $client['client_id']; ?>" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-plus-circle"></i> Sandbox
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
    
    <!-- Add Client Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="process/add_client.php" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nombre de la Empresa</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_name" class="form-label">Nombre de Contacto</label>
                            <input type="text" class="form-control" id="contact_name" name="contact_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Client Modal -->
    <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClientModalLabel">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="process/update_client.php" method="post">
                    <input type="hidden" id="edit_client_id" name="client_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_company_name" class="form-label">Nombre de la Empresa</label>
                            <input type="text" class="form-control" id="edit_company_name" name="company_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_contact_name" class="form-label">Nombre de Contacto</label>
                            <input type="text" class="form-control" id="edit_contact_name" name="contact_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Estado</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                                <option value="pending">Pendiente</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>
    <script>
        // Inicializar clipboard.js
        new ClipboardJS('.btn-copy');
        
        // Manejar el modal de edición
        document.getElementById('editClientModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const clientId = button.getAttribute('data-client-id');
            const company = button.getAttribute('data-company');
            const contact = button.getAttribute('data-contact');
            const email = button.getAttribute('data-email');
            const phone = button.getAttribute('data-phone');
            const status = button.getAttribute('data-status');
            
            document.getElementById('edit_client_id').value = clientId;
            document.getElementById('edit_company_name').value = company;
            document.getElementById('edit_contact_name').value = contact;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_status').value = status;
        });
    </script>
</body>
</html>
