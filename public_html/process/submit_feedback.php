<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Verificar si se recibieron los datos necesarios
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Método no permitido";
    exit;
}

// Obtener datos del formulario
$sandbox_id = isset($_POST['sandbox_id']) ? intval($_POST['sandbox_id']) : 0;
$session_id = isset($_POST['session_id']) ? intval($_POST['session_id']) : 0;
$feedback_type = isset($_POST['feedback_type']) ? sanitize($_POST['feedback_type']) : '';
$feedback_text = isset($_POST['feedback_text']) ? sanitize($_POST['feedback_text']) : '';

// Validar datos
if ($sandbox_id <= 0 || $session_id <= 0 || empty($feedback_type) || empty($feedback_text)) {
    http_response_code(400);
    echo "Datos incompletos o inválidos";
    exit;
}

// Validar tipo de feedback
$valid_types = ['approval', 'correction', 'additional_info'];
if (!in_array($feedback_type, $valid_types)) {
    http_response_code(400);
    echo "Tipo de feedback inválido";
    exit;
}

// Guardar feedback en la base de datos
$feedback_id = add_feedback($sandbox_id, $feedback_text, $feedback_type);

if ($feedback_id <= 0) {
    http_response_code(500);
    echo "Error al guardar el feedback";
    exit;
}

// Finalizar la sesión de prueba
end_test_session($session_id);

// Redirigir a página de agradecimiento
header("Location: ../sandbox/thank_you.php?key=" . $_POST['key']);
exit;
