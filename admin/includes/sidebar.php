<?php
// Archivo de inclusión para el sidebar del panel de administración
?>
<div class="sidebar">
    <div class="logo">
        <img src="../public_html/assets/images/nexoia-logo.png" alt="Nexo.ia Logo">
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'clients.php' ? 'active' : ''; ?>" href="clients.php">
                <i class="bi bi-people"></i>
                <span>Clientes</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'sandboxes.php' ? 'active' : ''; ?>" href="sandboxes.php">
                <i class="bi bi-box"></i>
                <span>Sandboxes</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'feedback.php' ? 'active' : ''; ?>" href="feedback.php">
                <i class="bi bi-chat-dots"></i>
                <span>Feedback</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>" href="settings.php">
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
