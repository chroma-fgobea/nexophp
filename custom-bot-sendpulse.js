/**
 * Custom Bot Creator - Funcionalidad para probar bots personalizados de SendPulse
 */

class SendPulseCustomBot {
    constructor() {
        this.customScriptCode = '';
        this.initialized = false;
    }
    
    /**
     * Inicializa el creador de bot personalizado
     */
    initialize() {
        if (this.initialized) return;
        
        this.createCustomBotScenario();
        this.createScriptEditorForm();
        this.setupEventListeners();
        
        // Cargar datos guardados si existen
        this.loadSavedScriptCode();
        
        this.initialized = true;
    }
    
    /**
     * Crea el séptimo escenario para el bot personalizado
     */
    createCustomBotScenario() {
        const scenariosContainer = document.querySelector('.test-scenarios');
        if (!scenariosContainer) return;
        
        // Crear el séptimo escenario
        const customScenario = document.createElement('div');
        customScenario.className = 'test-scenario';
        customScenario.setAttribute('data-scenario', 'custom-sendpulse');
        customScenario.innerHTML = `
            <div class="test-scenario-icon">💼</div>
            <h4>Mi Bot Personalizado</h4>
            <p>Prueba tu bot personalizado de SendPulse.</p>
        `;
        
        scenariosContainer.appendChild(customScenario);
    }
    
    /**
     * Crea el formulario para editar el código del script
     */
    createScriptEditorForm() {
        const testContainer = document.getElementById('test-container');
        if (!testContainer) return;
        
        const scriptEditorContainer = document.createElement('div');
        scriptEditorContainer.id = 'script-editor-form';
        scriptEditorContainer.className = 'script-editor-container';
        scriptEditorContainer.style.display = 'none';
        
        scriptEditorContainer.innerHTML = `
            <div class="form-header">
                <h3>Código de Bot Personalizado</h3>
                <p>Pega el código de tu bot personalizado de SendPulse para probarlo en este entorno.</p>
            </div>
            
            <div class="form-content">
                <div class="form-group">
                    <label for="script-code">Código del Script:</label>
                    <textarea id="script-code" placeholder="<script src=&quot;https://cdn.pulse.is/livechat/loader.js&quot; data-live-chat-id=&quot;tu-id-aquí&quot; async></script>" rows="8"></textarea>
                    <p class="form-help">Pega aquí el código completo del script que generaste en SendPulse.</p>
                </div>
                
                <div class="form-group">
                    <label for="script-notes">Notas (opcional):</label>
                    <textarea id="script-notes" placeholder="Añade notas o recordatorios sobre este bot..." rows="4"></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <button id="apply-script" class="action-button primary">Aplicar Script</button>
                <button id="cancel-script" class="action-button secondary">Cancelar</button>
            </div>
        `;
        
        testContainer.appendChild(scriptEditorContainer);
        
        // Añadir estilos para el formulario
        this.addFormStyles();
    }
    
    /**
     * Añade estilos CSS para el formulario
     */
    addFormStyles() {
        const styleElement = document.createElement('style');
        styleElement.textContent = `
            .script-editor-container {
                background-color: white;
                border-radius: var(--border-radius);
                padding: 30px;
                margin-top: 30px;
                box-shadow: var(--shadow);
            }
            
            .form-header {
                text-align: center;
                margin-bottom: 30px;
            }
            
            .form-header h3 {
                font-size: 1.8rem;
                margin-bottom: 10px;
                font-weight: 600;
            }
            
            .form-header p {
                color: #666;
                font-size: 1rem;
            }
            
            .form-group {
                margin-bottom: 25px;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: 500;
            }
            
            .form-group textarea {
                width: 100%;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 6px;
                font-family: monospace;
                font-size: 14px;
                resize: vertical;
            }
            
            .form-help {
                margin-top: 8px;
                color: #666;
                font-size: 0.9rem;
            }
            
            .form-actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                margin-top: 30px;
            }
            
            .action-button {
                padding: 10px 20px;
                border-radius: 6px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            .action-button.primary {
                background-color: var(--accent-color);
                color: white;
                border: none;
            }
            
            .action-button.secondary {
                background-color: #f5f5f5;
                color: #333;
                border: 1px solid #ddd;
            }
            
            .action-button:hover {
                opacity: 0.9;
                transform: translateY(-1px);
            }
            
            @media (max-width: 768px) {
                .script-editor-container {
                    padding: 20px;
                }
            }
        `;
        
        document.head.appendChild(styleElement);
    }
    
    /**
     * Configura los event listeners para el formulario y escenario
     */
    setupEventListeners() {
        // Event listener para el escenario personalizado
        const customScenario = document.querySelector('.test-scenario[data-scenario="custom-sendpulse"]');
        if (customScenario) {
            customScenario.addEventListener('click', () => {
                this.showScriptEditorForm();
            });
        }
        
        // Event listener para aplicar el script
        const applyButton = document.getElementById('apply-script');
        if (applyButton) {
            applyButton.addEventListener('click', () => {
                this.applyCustomScript();
            });
        }
        
        // Event listener para cancelar
        const cancelButton = document.getElementById('cancel-script');
        if (cancelButton) {
            cancelButton.addEventListener('click', () => {
                this.hideScriptEditorForm();
            });
        }
    }
    
    /**
     * Muestra el formulario de edición de script
     */
    showScriptEditorForm() {
        const scriptEditorForm = document.getElementById('script-editor-form');
        if (scriptEditorForm) {
            scriptEditorForm.style.display = 'block';
            
            // Ocultar el simulador de chat
            const chatSimulator = document.querySelector('.test-interaction');
            if (chatSimulator) {
                chatSimulator.style.display = 'none';
            }
            
            // Ocultar las métricas
            const metrics = document.querySelector('.test-metrics');
            if (metrics) {
                metrics.style.display = 'none';
            }
            
            // Ocultar los resultados
            const results = document.querySelector('.test-results');
            if (results) {
                results.style.display = 'none';
            }
            
            // Ocultar las acciones
            const actions = document.querySelector('.test-actions');
            if (actions) {
                actions.style.display = 'none';
            }
        }
    }
    
    /**
     * Oculta el formulario de edición de script
     */
    hideScriptEditorForm() {
        const scriptEditorForm = document.getElementById('script-editor-form');
        if (scriptEditorForm) {
            scriptEditorForm.style.display = 'none';
            
            // Mostrar el simulador de chat
            const chatSimulator = document.querySelector('.test-interaction');
            if (chatSimulator) {
                chatSimulator.style.display = 'block';
            }
            
            // Mostrar las métricas
            const metrics = document.querySelector('.test-metrics');
            if (metrics) {
                metrics.style.display = 'block';
            }
            
            // Mostrar las acciones
            const actions = document.querySelector('.test-actions');
            if (actions) {
                actions.style.display = 'flex';
            }
        }
    }
    
    /**
     * Aplica el script personalizado
     */
    applyCustomScript() {
        // Obtener el código del script
        const scriptCode = document.getElementById('script-code').value.trim();
        const scriptNotes = document.getElementById('script-notes').value.trim();
        
        if (!scriptCode) {
            alert('Por favor, ingresa el código del script de tu bot personalizado.');
            return;
        }
        
        // Validar que el código parece un script de SendPulse
        if (!this.validateScriptCode(scriptCode)) {
            alert('El código ingresado no parece ser un script válido de SendPulse. Por favor, verifica que has copiado el código completo.');
            return;
        }
        
        // Guardar el código
        this.customScriptCode = scriptCode;
        localStorage.setItem('customSendPulseScript', scriptCode);
        localStorage.setItem('customSendPulseNotes', scriptNotes);
        
        // Aplicar el script al sandbox
        this.injectCustomScript();
        
        // Ocultar el formulario y mostrar el chat
        this.hideScriptEditorForm();
        
        // Mostrar mensaje de éxito
        alert('Script aplicado correctamente. Ahora puedes probar tu bot personalizado.');
    }
    
    /**
     * Valida que el código parece un script de SendPulse
     */
    validateScriptCode(code) {
        // Verificar que contiene las partes esenciales de un script de SendPulse
        return code.includes('<script') && 
               code.includes('</script>') && 
               (code.includes('pulse.is') || code.includes('livechat') || code.includes('data-live-chat-id'));
    }
    
    /**
     * Inyecta el script personalizado en el sandbox
     */
    injectCustomScript() {
        // Primero, eliminar cualquier script personalizado anterior
        const existingScripts = document.querySelectorAll('script[data-custom-bot="true"]');
        existingScripts.forEach(script => script.remove());
        
        // Extraer el src y los atributos del script
        const scriptDetails = this.extractScriptDetails(this.customScriptCode);
        
        if (scriptDetails) {
            // Crear un nuevo elemento script
            const scriptElement = document.createElement('script');
            scriptElement.src = scriptDetails.src;
            scriptElement.async = true;
            scriptElement.setAttribute('data-custom-bot', 'true');
            
            // Añadir los atributos data-*
            for (const [key, value] of Object.entries(scriptDetails.attributes)) {
                scriptElement.setAttribute(key, value);
            }
            
            // Añadir el script al documento
            document.body.appendChild(scriptElement);
            
            console.log('Script personalizado inyectado:', scriptDetails);
        }
    }
    
    /**
     * Extrae los detalles del script (src y atributos)
     */
    extractScriptDetails(code) {
        // Crear un elemento temporal para parsear el HTML
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = code;
        
        // Obtener el elemento script
        const scriptElement = tempDiv.querySelector('script');
        
        if (!scriptElement) {
            console.error('No se pudo encontrar un elemento script en el código proporcionado.');
            return null;
        }
        
        // Obtener el src
        const src = scriptElement.src;
        
        if (!src) {
            console.error('El script no tiene un atributo src.');
            return null;
        }
        
        // Obtener todos los atributos data-*
        const attributes = {};
        for (const attr of scriptElement.attributes) {
            if (attr.name.startsWith('data-')) {
                attributes[attr.name] = attr.value;
            }
        }
        
        return {
            src,
            attributes
        };
    }
    
    /**
     * Carga el código de script guardado
     */
    loadSavedScriptCode() {
        const savedScript = localStorage.getItem('customSendPulseScript');
        const savedNotes = localStorage.getItem('customSendPulseNotes');
        
        if (savedScript) {
            document.getElementById('script-code').value = savedScript;
            this.customScriptCode = savedScript;
        }
        
        if (savedNotes) {
            document.getElementById('script-notes').value = savedNotes;
        }
    }
}

// Inicializar el creador de bot personalizado cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que el contenedor de pruebas esté disponible
    const waitForTestContainer = setInterval(function() {
        if (document.getElementById('test-container')) {
            clearInterval(waitForTestContainer);
            
            // Inicializar el creador de bot personalizado
            const sendPulseCustomBot = new SendPulseCustomBot();
            sendPulseCustomBot.initialize();
            
            // Guardar la instancia en window para acceso global
            window.sendPulseCustomBot = sendPulseCustomBot;
            
            // Modificar la función de carga de escenario para manejar el escenario personalizado
            const originalLoadTestScenario = window.loadTestScenario;
            if (originalLoadTestScenario) {
                window.loadTestScenario = function(scenarioType) {
                    if (scenarioType === 'custom-sendpulse') {
                        // Mostrar el formulario de edición de script
                        window.sendPulseCustomBot.showScriptEditorForm();
                    } else {
                        // Usar el comportamiento original para otros escenarios
                        originalLoadTestScenario(scenarioType);
                    }
                };
            }
        }
    }, 100);
});
