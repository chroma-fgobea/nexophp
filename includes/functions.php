<?php
require_once '../includes/config.php';

// Función para conectar a la base de datos
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Función para sanitizar entradas
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para verificar si el usuario está logueado
function is_logged_in() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Función para redirigir
function redirect($url) {
    header("Location: $url");
    exit;
}

// Función para generar una clave única para URL de cliente
function generate_unique_key() {
    return md5(uniqid(rand(), true));
}

// Función para verificar permisos de administrador
function is_admin() {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin';
}

// Función para obtener todos los clientes
function get_all_clients() {
    $conn = db_connect();
    $sql = "SELECT * FROM clients ORDER BY company_name ASC";
    $result = $conn->query($sql);
    
    $clients = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
    }
    
    $conn->close();
    return $clients;
}

// Función para obtener un cliente por ID
function get_client($client_id) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $client = $result->fetch_assoc();
    } else {
        $client = null;
    }
    
    $stmt->close();
    $conn->close();
    return $client;
}

// Función para obtener todos los sandboxes de un cliente
function get_client_sandboxes($client_id) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM sandboxes WHERE client_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sandboxes = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $sandboxes[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $sandboxes;
}

// Función para obtener un sandbox por ID
function get_sandbox($sandbox_id) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT s.*, c.company_name, c.unique_url_key 
                           FROM sandboxes s 
                           JOIN clients c ON s.client_id = c.client_id 
                           WHERE s.sandbox_id = ?");
    $stmt->bind_param("i", $sandbox_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $sandbox = $result->fetch_assoc();
    } else {
        $sandbox = null;
    }
    
    $stmt->close();
    $conn->close();
    return $sandbox;
}

// Función para obtener feedback de un sandbox
function get_sandbox_feedback($sandbox_id) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM client_feedback WHERE sandbox_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $sandbox_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $feedback = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $feedback[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $feedback;
}

// Función para obtener sesiones de prueba de un sandbox
function get_sandbox_sessions($sandbox_id) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM test_sessions WHERE sandbox_id = ? ORDER BY session_start DESC");
    $stmt->bind_param("i", $sandbox_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sessions = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $sessions;
}

// Función para obtener mensajes de una sesión de prueba
function get_session_messages($session_id) {
    $conn = db_connect();
    $stmt = $conn->prepare("SELECT * FROM test_messages WHERE session_id = ? ORDER BY timestamp ASC");
    $stmt->bind_param("i", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $messages;
}

// Función para crear un nuevo cliente
function create_client($company_name, $contact_name, $email, $phone) {
    $conn = db_connect();
    $unique_key = generate_unique_key();
    
    $stmt = $conn->prepare("INSERT INTO clients (company_name, contact_name, email, phone, unique_url_key) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $company_name, $contact_name, $email, $phone, $unique_key);
    
    $result = $stmt->execute();
    $client_id = $result ? $conn->insert_id : 0;
    
    $stmt->close();
    $conn->close();
    return $client_id;
}

// Función para actualizar un cliente
function update_client($client_id, $company_name, $contact_name, $email, $phone, $status) {
    $conn = db_connect();
    
    $stmt = $conn->prepare("UPDATE clients SET company_name = ?, contact_name = ?, email = ?, phone = ?, status = ? WHERE client_id = ?");
    $stmt->bind_param("sssssi", $company_name, $contact_name, $email, $phone, $status, $client_id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    return $result;
}

// Función para crear un nuevo sandbox
function create_sandbox($client_id, $name, $description, $script_code, $admin_id) {
    $conn = db_connect();
    
    $stmt = $conn->prepare("INSERT INTO sandboxes (client_id, name, description, script_code, created_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $client_id, $name, $description, $script_code, $admin_id);
    
    $result = $stmt->execute();
    $sandbox_id = $result ? $conn->insert_id : 0;
    
    $stmt->close();
    $conn->close();
    return $sandbox_id;
}

// Función para actualizar un sandbox
function update_sandbox($sandbox_id, $name, $description, $script_code) {
    $conn = db_connect();
    
    $stmt = $conn->prepare("UPDATE sandboxes SET name = ?, description = ?, script_code = ?, updated_at = CURRENT_TIMESTAMP WHERE sandbox_id = ?");
    $stmt->bind_param("sssi", $name, $description, $script_code, $sandbox_id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    return $result;
}

// Función para añadir feedback de cliente
function add_feedback($sandbox_id, $feedback_text, $feedback_type) {
    $conn = db_connect();
    
    $stmt = $conn->prepare("INSERT INTO client_feedback (sandbox_id, feedback_text, feedback_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $sandbox_id, $feedback_text, $feedback_type);
    
    $result = $stmt->execute();
    $feedback_id = $result ? $conn->insert_id : 0;
    
    $stmt->close();
    $conn->close();
    return $feedback_id;
}

// Función para actualizar estado de feedback
function update_feedback_status($feedback_id, $status, $admin_id) {
    $conn = db_connect();
    
    $resolved_at = ($status === 'resolved') ? 'CURRENT_TIMESTAMP' : 'NULL';
    $resolved_by = ($status === 'resolved') ? $admin_id : 'NULL';
    
    $stmt = $conn->prepare("UPDATE client_feedback SET status = ?, resolved_at = $resolved_at, resolved_by = $resolved_by WHERE feedback_id = ?");
    $stmt->bind_param("si", $status, $feedback_id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    return $result;
}

// Función para iniciar sesión de prueba
function start_test_session($sandbox_id, $ip_address, $user_agent) {
    $conn = db_connect();
    
    $stmt = $conn->prepare("INSERT INTO test_sessions (sandbox_id, ip_address, user_agent) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $sandbox_id, $ip_address, $user_agent);
    
    $result = $stmt->execute();
    $session_id = $result ? $conn->insert_id : 0;
    
    $stmt->close();
    $conn->close();
    return $session_id;
}

// Función para finalizar sesión de prueba
function end_test_session($session_id) {
    $conn = db_connect();
    
    $stmt = $conn->prepare("UPDATE test_sessions SET session_end = CURRENT_TIMESTAMP WHERE session_id = ?");
    $stmt->bind_param("i", $session_id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    return $result;
}

// Función para añadir mensaje de prueba
function add_test_message($session_id, $message_text, $is_bot) {
    $conn = db_connect();
    
    $stmt = $conn->prepare("INSERT INTO test_messages (session_id, message_text, is_bot) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $session_id, $message_text, $is_bot);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    return $result;
}
