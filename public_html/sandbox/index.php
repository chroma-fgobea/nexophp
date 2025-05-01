<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Verificar si se recibió una clave válida
$key = isset($_GET['key']) ? sanitize($_GET['key']) : '';

// Conectar a la base de datos
$conn = db_connect();

// Buscar el cliente por la clave única
$stmt = $conn->prepare("SELECT client_id, company_name FROM clients WHERE unique_url_key = ?");
$stmt->bind_param("s", $key);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Cliente no encontrado
    echo "Sandbox no encontrado";
    exit;
}

$client = $result->fetch_assoc();
$client_id = $client['client_id'];

// Buscar el sandbox más reciente para este cliente
$stmt = $conn->prepare("SELECT * FROM sandboxes WHERE client_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No hay sandbox para este cliente
    echo "No hay sandbox configurado para este cliente";
    exit;
}

$sandbox = $result->fetch_assoc();
$sandbox_id = $sandbox['sandbox_id'];

// Registrar la sesión de prueba
$ip_address = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$session_id = start_test_session($sandbox_id, $ip_address, $user_agent);

// Cerrar la conexión
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bot Sandbox - <?php echo htmlspecialchars($client['company_name']); ?></title>
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
            flex-direction: column;
        }
        
        .header {
            background-color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header .logo {
            display: flex;
            align-items: center;
        }
        
        .header .logo img {
            height: 40px;
            margin-right: 10px;
        }
        
        .main-content {
            flex: 1;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        
        .sandbox-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .sandbox-header {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .sandbox-header h1 {
            font-size: 2rem;
            color: var(--dark-color);
            margin-bottom: 10px;
        }
        
        .sandbox-header p {
            font-size: 1.1rem;
            color: #6c757d;
        }
        
        .chat-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .chat-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .message {
            margin-bottom: 15px;
            display: flex;
        }
        
        .message.bot {
            justify-content: flex-start;
        }
        
        .message.user {
            justify-content: flex-end;
        }
        
        .message-content {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 18px;
            position: relative;
        }
        
        .message.bot .message-content {
            background-color: white;
            border: 1px solid #e9ecef;
            border-bottom-left-radius: 5px;
        }
        
        .message.user .message-content {
            background-color: var(--primary-color);
            color: white;
            border-bottom-right-radius: 5px;
        }
        
        .message-time {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 5px;
            text-align: right;
        }
        
        .message.user .message-time {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .chat-input {
            display: flex;
            padding: 15px;
            background-color: white;
            border-top: 1px solid #e9ecef;
        }
        
        .chat-input input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            border-radius: 30px;
            margin-right: 10px;
        }
        
        .chat-input button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .chat-input button:hover {
            background-color: #0096cc;
        }
        
        .feedback-container {
            max-width: 800px;
            margin: 30px auto 0;
        }
        
        .feedback-header {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .feedback-header h2 {
            font-size: 1.5rem;
            color: var(--dark-color);
        }
        
        .feedback-form {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }
        
        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: auto;
        }
        
        .footer p {
            margin: 0;
        }
        
        .footer a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 20px 10px;
            }
            
            .sandbox-container {
                padding: 20px;
            }
            
            .message-content {
                max-width: 85%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../assets/images/nexoia-logo.png" alt="Nexo.ia Logo">
            <span>Bot Sandbox</span>
        </div>
    </div>
    
    <div class="main-content">
        <div class="sandbox-container">
            <div class="sandbox-header">
                <h1>Prueba el Bot de <?php echo htmlspecialchars($client['company_name']); ?></h1>
                <p>Interactúa con el bot como lo haría un usuario real y proporciona tu feedback.</p>
            </div>
            
            <div class="chat-container">
                <div class="chat-header">
                    Chat con el Bot
                </div>
                <div class="chat-messages" id="chatMessages">
                    <div class="message bot">
                        <div class="message-content">
                            ¡Hola! Soy el asistente virtual de <?php echo htmlspecialchars($client['company_name']); ?>. ¿En qué puedo ayudarte hoy?
                            <div class="message-time">Ahora</div>
                        </div>
                    </div>
                </div>
                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Escribe un mensaje..." autocomplete="off">
                    <button id="sendButton">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="feedback-container">
            <div class="feedback-header">
                <h2>Tu Feedback es Importante</h2>
            </div>
            <div class="feedback-form">
                <form id="feedbackForm" action="../process/submit_feedback.php" method="post">
                    <input type="hidden" name="sandbox_id" value="<?php echo $sandbox_id; ?>">
                    <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                    
                    <div class="mb-3">
                        <label for="feedbackType" class="form-label">Tipo de Feedback</label>
                        <select class="form-select" id="feedbackType" name="feedback_type" required>
                            <option value="" selected disabled>Selecciona una opción</option>
                            <option value="approval">Aprobación - El bot funciona correctamente</option>
                            <option value="correction">Corrección - El bot necesita mejoras</option>
                            <option value="additional_info">Información Adicional - Tengo más datos para el bot</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="feedbackText" class="form-label">Tu Feedback</label>
                        <textarea class="form-control" id="feedbackText" name="feedback_text" rows="4" required placeholder="Comparte tus impresiones, sugerencias o información adicional..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Enviar Feedback</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>Powered by <a href="https://nexo.ia" target="_blank">Nexo.ia</a> - Asistentes virtuales inteligentes</p>
    </div>
    
    <script>
        // Elementos del DOM
        const chatMessages = document.getElementById('chatMessages');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const feedbackForm = document.getElementById('feedbackForm');
        
        // Variables para el chat
        const sessionId = <?php echo $session_id; ?>;
        const sandboxId = <?php echo $sandbox_id; ?>;
        
        // Función para añadir un mensaje al chat
        function addMessage(text, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const timeString = `${hours}:${minutes}`;
            
            messageDiv.innerHTML = `
                <div class="message-content">
                    ${text}
                    <div class="message-time">${timeString}</div>
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Registrar el mensaje en la base de datos
            fetch('../process/log_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `session_id=${sessionId}&message_text=${encodeURIComponent(text)}&is_bot=${isUser ? 0 : 1}`
            });
        }
        
        // Función para enviar un mensaje
        function sendMessage() {
            const text = messageInput.value.trim();
            if (text === '') return;
            
            // Añadir mensaje del usuario
            addMessage(text, true);
            messageInput.value = '';
            
            // Simular respuesta del bot (en una implementación real, esto se conectaría con SendPulse)
            setTimeout(() => {
                // Aquí se insertaría el código para procesar la respuesta real del bot
                // Por ahora, usamos una respuesta simulada
                const botResponse = "Gracias por tu mensaje. Estoy procesando tu consulta.";
                addMessage(botResponse);
            }, 1000);
        }
        
        // Event listeners
        sendButton.addEventListener('click', sendMessage);
        
        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
                e.preventDefault();
            }
        });
        
        // Registrar cuando se cierra la ventana para finalizar la sesión
        window.addEventListener('beforeunload', () => {
            fetch(`../process/end_session.php?session_id=${sessionId}`, {
                method: 'GET',
                keepalive: true
            });
        });
        
        // Insertar el script de SendPulse
        document.addEventListener('DOMContentLoaded', function() {
            const scriptCode = `<?php echo $sandbox['script_code']; ?>`;
            const scriptElement = document.createElement('div');
            scriptElement.innerHTML = scriptCode;
            document.body.appendChild(scriptElement);
        });
    </script>
</body>
</html>
