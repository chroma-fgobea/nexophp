<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Nexo.ia Bot Sandbox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #00A8E8;
            --secondary-color: #FF0066;
            --dark-color: #333333;
            --light-color: #F8F9FA;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f7fa;
        }
        
        .sidebar {
            background-color: var(--dark-color);
            color: white;
            min-height: 100vh;
            position: fixed;
            width: 250px;
        }
        
        .sidebar .logo {
            padding: 20px;
            text-align: center;
        }
        
        .sidebar .logo img {
            max-width: 180px;
            height: auto;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 0;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .header {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 1.5rem;
            margin: 0;
            color: var(--dark-color);
        }
        
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #0096cc;
            border-color: #0096cc;
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-secondary:hover {
            background-color: #e6005c;
            border-color: #e6005c;
        }
        
        .table th {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .badge {
            padding: 6px 10px;
            font-weight: 500;
        }
        
        .badge-active {
            background-color: var(--success-color);
        }
        
        .badge-inactive {
            background-color: var(--danger-color);
        }
        
        .badge-pending {
            background-color: var(--warning-color);
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-info .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
        }
        
        .user-info .user-name {
            font-weight: 600;
        }
        
        .user-info .user-role {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .feedback-item {
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            border-radius: 0 8px 8px 0;
        }
        
        .feedback-item.approval {
            border-left-color: var(--success-color);
        }
        
        .feedback-item.correction {
            border-left-color: var(--danger-color);
        }
        
        .feedback-item.additional_info {
            border-left-color: var(--warning-color);
        }
        
        .feedback-item .feedback-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .feedback-item .feedback-type {
            font-weight: 600;
        }
        
        .feedback-item .feedback-date {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .feedback-item .feedback-text {
            margin-bottom: 10px;
        }
        
        .feedback-item .feedback-actions {
            text-align: right;
        }
        
        .code-editor {
            font-family: monospace;
            height: 300px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 10px;
            background-color: #f8f9fa;
        }
        
        .preview-frame {
            width: 100%;
            height: 500px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar .logo img {
                max-width: 40px;
            }
            
            .sidebar .nav-link span {
                display: none;
            }
            
            .sidebar .nav-link i {
                margin-right: 0;
                font-size: 1.2rem;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <img src="../public_html/assets/images/nexoia-logo.png" alt="Nexo.ia Logo">
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="clients.php">
                        <i class="bi bi-people"></i>
                        <span>Clientes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sandboxes.php">
                        <i class="bi bi-box"></i>
                        <span>Sandboxes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="feedback.php">
                        <i class="bi bi-chat-dots"></i>
                        <span>Feedback</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">
                        <i class="bi bi-gear"></i>
                        <span>Configuración</span>
                    </a>
                </li>
                <li class="nav-item mt-auto">
                    <a class="nav-link" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Cerrar sesión</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <div class="avatar">A</div>
                    <div>
                        <div class="user-name">Admin</div>
                        <div class="user-role">Administrador</div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="display-4 text-primary">12</h3>
                            <p class="text-muted mb-0">Clientes Totales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="display-4 text-success">8</h3>
                            <p class="text-muted mb-0">Clientes Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="display-4 text-warning">3</h3>
                            <p class="text-muted mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="display-4 text-info">15</h3>
                            <p class="text-muted mb-0">Sandboxes</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Clientes Recientes</span>
                            <a href="clients.php" class="btn btn-sm btn-primary">Ver todos</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Empresa</th>
                                            <th>Contacto</th>
                                            <th>Estado</th>
                                            <th>Sandboxes</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Empresa A</td>
                                            <td>Juan Pérez</td>
                                            <td><span class="badge badge-active bg-success">Activo</span></td>
                                            <td>2</td>
                                            <td>
                                                <a href="client_detail.php?id=1" class="btn btn-sm btn-outline-primary">Ver</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Empresa B</td>
                                            <td>María López</td>
                                            <td><span class="badge badge-pending bg-warning">Pendiente</span></td>
                                            <td>1</td>
                                            <td>
                                                <a href="client_detail.php?id=2" class="btn btn-sm btn-outline-primary">Ver</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Empresa C</td>
                                            <td>Carlos Rodríguez</td>
                                            <td><span class="badge badge-active bg-success">Activo</span></td>
                                            <td>3</td>
                                            <td>
                                                <a href="client_detail.php?id=3" class="btn btn-sm btn-outline-primary">Ver</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Empresa D</td>
                                            <td>Ana Martínez</td>
                                            <td><span class="badge badge-inactive bg-danger">Inactivo</span></td>
                                            <td>0</td>
                                            <td>
                                                <a href="client_detail.php?id=4" class="btn btn-sm btn-outline-primary">Ver</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Feedback Reciente</span>
                            <a href="feedback.php" class="btn btn-sm btn-primary">Ver todos</a>
                        </div>
                        <div class="card-body">
                            <div class="feedback-item approval">
                                <div class="feedback-header">
                                    <div class="feedback-type">Aprobación</div>
                                    <div class="feedback-date">Hoy, 14:30</div>
                                </div>
                                <div class="feedback-text">
                                    El bot funciona perfectamente. Estamos listos para implementarlo en nuestra web.
                                </div>
                                <div class="feedback-client">
                                    <small class="text-muted">Empresa A - Bot de Atención al Cliente</small>
                                </div>
                            </div>
                            
                            <div class="feedback-item correction">
                                <div class="feedback-header">
                                    <div class="feedback-type">Corrección</div>
                                    <div class="feedback-date">Ayer, 16:45</div>
                                </div>
                                <div class="feedback-text">
                                    El bot no está respondiendo correctamente a preguntas sobre horarios de atención.
                                </div>
                                <div class="feedback-client">
                                    <small class="text-muted">Empresa B - Bot de Reservas</small>
                                </div>
                            </div>
                            
                            <div class="feedback-item additional_info">
                                <div class="feedback-header">
                                    <div class="feedback-type">Información Adicional</div>
                                    <div class="feedback-date">12/04/2025</div>
                                </div>
                                <div class="feedback-text">
                                    Hemos actualizado nuestro catálogo de productos. Adjuntamos el nuevo listado para que el bot pueda responder sobre ellos.
                                </div>
                                <div class="feedback-client">
                                    <small class="text-muted">Empresa C - Bot de Ventas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
