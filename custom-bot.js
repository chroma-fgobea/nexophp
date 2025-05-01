/**
 * Custom Bot Creator - Funcionalidad para crear un bot personalizado con información de negocio
 */

class CustomBotCreator {
    constructor() {
        this.businessInfo = {
            name: '',
            description: '',
            products: '',
            faqs: [],
            hours: '',
            location: '',
            specialties: '',
            contact: '',
            website: '',
            tone: 'profesional',
            promotions: '',
            languages: '',
            paymentMethods: ''
        };
        
        this.initialized = false;
    }
    
    /**
     * Inicializa el creador de bot personalizado
     */
    initialize() {
        if (this.initialized) return;
        
        this.createCustomBotScenario();
        this.createBusinessInfoForm();
        this.setupEventListeners();
        
        // Cargar datos guardados si existen
        this.loadSavedBusinessInfo();
        
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
        customScenario.setAttribute('data-scenario', 'custom-business');
        customScenario.innerHTML = `
            <div class="test-scenario-icon">💼</div>
            <h4>Mi Bot Personalizado</h4>
            <p>Prueba un bot con la información de tu negocio.</p>
        `;
        
        scenariosContainer.appendChild(customScenario);
    }
    
    /**
     * Crea el formulario para la información del negocio
     */
    createBusinessInfoForm() {
        const testContainer = document.getElementById('test-container');
        if (!testContainer) return;
        
        const businessFormContainer = document.createElement('div');
        businessFormContainer.id = 'business-info-form';
        businessFormContainer.className = 'business-form-container';
        businessFormContainer.style.display = 'none';
        
        businessFormContainer.innerHTML = `
            <div class="form-header">
                <h3>Información de tu Negocio</h3>
                <p>Completa este formulario para personalizar tu bot con la información de tu negocio.</p>
            </div>
            
            <div class="form-tabs">
                <button class="form-tab active" data-tab="basic-info">Información Básica</button>
                <button class="form-tab" data-tab="products-services">Productos y Servicios</button>
                <button class="form-tab" data-tab="faqs">Preguntas Frecuentes</button>
                <button class="form-tab" data-tab="additional-info">Información Adicional</button>
            </div>
            
            <div class="form-content">
                <!-- Pestaña de Información Básica -->
                <div class="form-tab-content active" id="basic-info-tab">
                    <div class="form-group">
                        <label for="business-name">Nombre del Negocio:</label>
                        <input type="text" id="business-name" placeholder="Ej: Cafetería El Aroma">
                    </div>
                    
                    <div class="form-group">
                        <label for="business-description">Descripción General:</label>
                        <textarea id="business-description" placeholder="Describe brevemente tu negocio..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="business-hours">Horarios de Atención:</label>
                        <textarea id="business-hours" placeholder="Ej: Lunes a Viernes: 9:00 - 18:00, Sábados: 10:00 - 14:00"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="business-location">Dirección y Ubicación:</label>
                        <textarea id="business-location" placeholder="Dirección completa y referencias..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="business-contact">Información de Contacto:</label>
                        <textarea id="business-contact" placeholder="Teléfono, email, redes sociales..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="business-website">Página Web:</label>
                        <input type="text" id="business-website" placeholder="https://www.tunegocio.com">
                    </div>
                </div>
                
                <!-- Pestaña de Productos y Servicios -->
                <div class="form-tab-content" id="products-services-tab">
                    <div class="form-group">
                        <label for="business-products">Productos/Servicios Ofrecidos:</label>
                        <textarea id="business-products" placeholder="Lista de productos o servicios principales..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="business-specialties">Especialidades:</label>
                        <textarea id="business-specialties" placeholder="¿En qué se especializa tu negocio?"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="business-promotions">Promociones Actuales:</label>
                        <textarea id="business-promotions" placeholder="Describe las promociones o descuentos vigentes..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="business-payments">Métodos de Pago Aceptados:</label>
                        <textarea id="business-payments" placeholder="Ej: Efectivo, tarjetas de crédito/débito, transferencia bancaria..."></textarea>
                    </div>
                </div>
                
                <!-- Pestaña de Preguntas Frecuentes -->
                <div class="form-tab-content" id="faqs-tab">
                    <div class="faq-list">
                        <div class="faq-item">
                            <div class="form-group">
                                <label>Pregunta 1:</label>
                                <input type="text" class="faq-question" placeholder="Ej: ¿Cuáles son sus horarios de atención?">
                            </div>
                            <div class="form-group">
                                <label>Respuesta:</label>
                                <textarea class="faq-answer" placeholder="Respuesta a la pregunta..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <button id="add-faq" class="action-button" style="margin-top: 20px;">Añadir Otra Pregunta</button>
                </div>
                
                <!-- Pestaña de Información Adicional -->
                <div class="form-tab-content" id="additional-info-tab">
                    <div class="form-group">
                        <label for="business-tone">Tono del Bot:</label>
                        <select id="business-tone">
                            <option value="profesional">Profesional</option>
                            <option value="cercano">Cercano y Amigable</option>
                            <option value="formal">Formal</option>
                            <option value="casual">Casual</option>
                            <option value="divertido">Divertido</option>
                            <option value="serio">Serio</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="business-languages">Idiomas Atendidos:</label>
                        <textarea id="business-languages" placeholder="Ej: Español, Inglés, Francés..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="additional-instructions">Instrucciones Adicionales para el Bot:</label>
                        <textarea id="additional-instructions" placeholder="Cualquier otra información o instrucción para el comportamiento del bot..."></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button id="save-business-info" class="action-button primary">Guardar y Aplicar</button>
                <button id="cancel-business-info" class="action-button secondary">Cancelar</button>
            </div>
        `;
        
        testContainer.appendChild(businessFormContainer);
        
        // Añadir estilos para el formulario
        this.addFormStyles();
    }
    
    /**
     * Añade estilos CSS para el formulario
     */
    addFormStyles() {
        const styleElement = document.createElement('style');
        styleElement.textContent = `
            .business-form-container {
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
            
            .form-tabs {
                display: flex;
                border-bottom: 1px solid #eee;
                margin-bottom: 20px;
                overflow-x: auto;
            }
            
            .form-tab {
                padding: 10px 20px;
                background: none;
                border: none;
                cursor: pointer;
                font-size: 1rem;
                font-weight: 500;
                color: #888;
                position: relative;
                white-space: nowrap;
            }
            
            .form-tab.active {
                color: var(--accent-color);
            }
            
            .form-tab.active::after {
                content: '';
                position: absolute;
                bottom: -1px;
                left: 0;
                width: 100%;
                height: 2px;
                background-color: var(--accent-color);
            }
            
            .form-tab-content {
                display: none;
            }
            
            .form-tab-content.active {
                display: block;
            }
            
            .faq-item {
                background-color: #f9f9f9;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 15px;
            }
            
            .form-actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                margin-top: 30px;
            }
            
            @media (max-width: 768px) {
                .form-tabs {
                    flex-wrap: wrap;
                }
                
                .form-tab {
                    padding: 8px 15px;
                    font-size: 0.9rem;
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
        const customScenario = document.querySelector('.test-scenario[data-scenario="custom-business"]');
        if (customScenario) {
            customScenario.addEventListener('click', () => {
                this.showBusinessForm();
            });
        }
        
        // Event listeners para las pestañas del formulario
        const formTabs = document.querySelectorAll('.form-tab');
        formTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabName = tab.getAttribute('data-tab');
                
                // Desactivar todas las pestañas
                formTabs.forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.form-tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Activar la pestaña seleccionada
                tab.classList.add('active');
                document.getElementById(`${tabName}-tab`).classList.add('active');
            });
        });
        
        // Event listener para añadir más preguntas frecuentes
        const addFaqButton = document.getElementById('add-faq');
        if (addFaqButton) {
            addFaqButton.addEventListener('click', () => {
                this.addNewFaqItem();
            });
        }
        
        // Event listener para guardar la información
        const saveButton = document.getElementById('save-business-info');
        if (saveButton) {
            saveButton.addEventListener('click', () => {
                this.saveBusinessInfo();
            });
        }
        
        // Event listener para cancelar
        const cancelButton = document.getElementById('cancel-business-info');
        if (cancelButton) {
            cancelButton.addEventListener('click', () => {
                this.hideBusinessForm();
            });
        }
    }
    
    /**
     * Añade un nuevo elemento de pregunta frecuente
     */
    addNewFaqItem() {
        const faqList = document.querySelector('.faq-list');
        if (!faqList) return;
        
        const faqCount = faqList.querySelectorAll('.faq-item').length + 1;
        
        const faqItem = document.createElement('div');
        faqItem.className = 'faq-item';
        faqItem.innerHTML = `
            <div class="form-group">
                <label>Pregunta ${faqCount}:</label>
                <input type="text" class="faq-question" placeholder="Ej: ¿Cuáles son sus horarios de atención?">
            </div>
            <div class="form-group">
                <label>Respuesta:</label>
                <textarea class="faq-answer" placeholder="Respuesta a la pregunta..."></textarea>
            </div>
            <button class="remove-faq" style="background: none; border: none; color: #dc3545; cursor: pointer; float: right;">Eliminar</button>
        `;
        
        faqList.appendChild(faqItem);
        
        // Event listener para eliminar esta pregunta
        const removeButton = faqItem.querySelector('.remove-faq');
        if (removeButton) {
            removeButton.addEventListener('click', () => {
                faqItem.remove();
            });
        }
    }
    
    /**
     * Muestra el formulario de información de negocio
     */
    showBusinessForm() {
        const businessForm = document.getElementById('business-info-form');
        if (businessForm) {
            businessForm.style.display = 'block';
            
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
     * Oculta el formulario de información de negocio
     */
    hideBusinessForm() {
        const businessForm = document.getElementById('business-info-form');
        if (businessForm) {
            businessForm.style.display = 'none';
            
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
     * Guarda la información del negocio
     */
    saveBusinessInfo() {
        // Recopilar información básica
        this.businessInfo.name = document.getElementById('business-name').value;
        this.businessInfo.description = document.getElementById('business-description').value;
        this.businessInfo.hours = document.getElementById('business-hours').value;
        this.businessInfo.location = document.getElementById('business-location').value;
        this.businessInfo.contact = document.getElementById('business-contact').value;
        this.businessInfo.website = document.getElementById('business-website').value;
        
        // Recopilar información de productos y servicios
        this.businessInfo.products = document.getElementById('business-products').value;
        this.businessInfo.specialties = document.getElementById('business-specialties').value;
        this.businessInfo.promotions = document.getElementById('business-promotions').value;
        this.businessInfo.paymentMethods = document.getElementById('business-payments').value;
        
        // Recopilar preguntas frecuentes
        this.businessInfo.faqs = [];
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question').value;
            const answer = item.querySelector('.faq-answer').value;
            
            if (question && answer) {
                this.businessInfo.faqs.push({ question, answer });
            }
        });
        
        // Recopilar información adicional
        this.businessInfo.tone = document.getElementById('business-tone').value;
        this.businessInfo.languages = document.getElementById('business-languages').value;
        
        // Guardar en localStorage
        localStorage.setItem('customBusinessInfo', JSON.stringify(this.businessInfo));
        
        // Ocultar el formulario y mostrar el chat
        this.hideBusinessForm();
        
        // Inicializar el chat con la información del negocio
        this.initializeCustomChat();
        
        // Mostrar mensaje de éxito
        alert('Información guardada correctamente. Ahora puedes probar tu bot personalizado.');
    }
    
    /**
     * Carga la información guardada del negocio
     */
    loadSavedBusinessInfo() {
        const savedInfo = localStorage.getItem('customBusinessInfo');
        if (savedInfo) {
            try {
                this.businessInfo = JSON.parse(savedInfo);
                
                // Rellenar el formulario con la información guardada
                document.getElementById('business-name').value = this.businessInfo.name || '';
                document.getElementById('business-description').value = this.businessInfo.description || '';
                document.getElementById('business-hours').value = this.businessInfo.hours || '';
                document.getElementById('business-location').value = this.businessInfo.location || '';
                document.getElementById('business-contact').value = this.businessInfo.contact || '';
                document.getElementById('business-website').value = this.businessInfo.website || '';
                
                document.getElementById('business-products').value = this.businessInfo.products || '';
                document.getElementById('business-specialties').value = this.businessInfo.specialties || '';
                document.getElementById('business-promotions').value = this.businessInfo.promotions || '';
                document.getElementById('business-payments').value = this.businessInfo.paymentMethods || '';
                
                document.getElementById('business-tone').value = this.businessInfo.tone || 'profesional';
                document.getElementById('business-languages').value = this.businessInfo.languages || '';
                
                // Cargar preguntas frecuentes
                const faqList = document.querySelector('.faq-list');
                if (faqList) {
                    // Limpiar la lista actual
                    faqList.innerHTML = '';
                    
                    // Añadir las preguntas guardadas
                    if (this.businessInfo.faqs && this.businessInfo.faqs.length > 0) {
                        this.businessInfo.faqs.forEach((faq, index) => {
                            const faqItem = document.createElement('div');
                            faqItem.className = 'faq-item';
                            faqItem.innerHTML = `
                                <div class="form-group">
                                    <label>Pregunta ${index + 1}:</label>
                                    <input type="text" class="faq-question" value="${faq.question}" placeholder="Ej: ¿Cuáles son sus horarios de atención?">
                                </div>
                                <div class="form-group">
                                    <label>Respuesta:</label>
                                    <textarea class="faq-answer" placeholder="Respuesta a la pregunta...">${faq.answer}</textarea>
                                </div>
                                ${index > 0 ? '<button class="remove-faq" style="background: none; border: none; color: #dc3545; cursor: pointer; float: right;">Eliminar</button>' : ''}
                            `;
                            
                            faqList.appendChild(faqItem);
                            
                            // Event listener para eliminar esta pregunta
                            const removeButton = faqItem.querySelector('.remove-faq');
                            if (removeButton) {
                                removeButton.addEventListener('click', () => {
                                    faqItem.remove();
                                });
                            }
                        });
                    } else {
                        // Si no hay preguntas guardadas, añadir una en blanco
                        const faqItem = document.createElement('div');
                        faqItem.className = 'faq-item';
                        faqItem.innerHTML = `
                            <div class="form-group">
                                <label>Pregunta 1:</label>
                                <input type="text" class="faq-question" placeholder="Ej: ¿Cuáles son sus horarios de atención?">
                            </div>
                            <div class="form-group">
                                <label>Respuesta:</label>
                                <textarea class="faq-answer" placeholder="Respuesta a la pregunta..."></textarea>
                            </div>
                        `;
                        
                        faqList.appendChild(faqItem);
                    }
                }
            } catch (error) {
                console.error('Error al cargar la información guardada:', error);
            }
        }
    }
    
    /**
     * Inicializa el chat con la información del negocio
     */
    initializeCustomChat() {
        // Reiniciar el chat
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.innerHTML = '';
            
            // Añadir mensaje de bienvenida personalizado
            this.addBotMessage(this.generateWelcomeMessage());
        }
    }
    
    /**
     * Genera un mensaje de bienvenida personalizado
     */
    generateWelcomeMessage() {
        let welcomeMessage = '';
        
        switch (this.businessInfo.tone) {
            case 'cercano':
                welcomeMessage = `¡Hola! Soy el asistente virtual de ${this.businessInfo.name}. ¿En qué puedo ayudarte hoy? 😊`;
                break;
            case 'formal':
                welcomeMessage = `Bienvenido/a a ${this.businessInfo.name}. Soy su asistente virtual. ¿En qué puedo asistirle?`;
                break;
            case 'casual':
                welcomeMessage = `¡Hey! Soy el bot de ${this.businessInfo.name}. ¿Qué necesitas saber?`;
                break;
            case 'divertido':
                welcomeMessage = `¡Hola! 👋 Soy el bot más simpático de ${this.businessInfo.name}. ¿En qué puedo ayudarte hoy? 🎉`;
                break;
            case 'serio':
                welcomeMessage = `Bienvenido a ${this.businessInfo.name}. Soy el asistente virtual. ¿Cómo puedo ayudarle?`;
                break;
            case 'profesional':
            default:
                welcomeMessage = `Bienvenido/a a ${this.businessInfo.name}. Soy su asistente virtual. ¿Cómo puedo ayudarle hoy?`;
                break;
        }
        
        return welcomeMessage;
    }
    
    /**
     * Añade un mensaje del bot al chat
     */
    addBotMessage(message) {
        const chatMessages = document.getElementById('chat-messages');
        if (!chatMessages) return;
        
        const messageElement = document.createElement('div');
        messageElement.className = 'chat-message bot';
        
        const now = new Date();
        const timeString = now.getHours().toString().padStart(2, '0') + ':' + 
                          now.getMinutes().toString().padStart(2, '0');
        
        messageElement.innerHTML = `
            ${message}
            <div class="chat-message-time">${timeString}</div>
        `;
        
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    /**
     * Procesa un mensaje del usuario y genera una respuesta
     */
    processUserMessage(message) {
        // Normalizar el mensaje para búsqueda
        const normalizedMessage = message.toLowerCase().trim();
        
        // Buscar en las FAQs
        for (const faq of this.businessInfo.faqs) {
            const normalizedQuestion = faq.question.toLowerCase().trim();
            
            // Si la pregunta contiene palabras clave similares
            if (this.hasCommonKeywords(normalizedMessage, normalizedQuestion)) {
                return faq.answer;
            }
        }
        
        // Buscar información sobre horarios
        if (normalizedMessage.includes('horario') || normalizedMessage.includes('hora') || 
            normalizedMessage.includes('abierto') || normalizedMessage.includes('cerrado') ||
            normalizedMessage.includes('atienden')) {
            return `Nuestros horarios de atención son: ${this.businessInfo.hours}`;
        }
        
        // Buscar información sobre ubicación
        if (normalizedMessage.includes('ubicación') || normalizedMessage.includes('dirección') || 
            normalizedMessage.includes('donde') || normalizedMessage.includes('llegar') ||
            normalizedMessage.includes('encuentran')) {
            return `Nos encontramos en: ${this.businessInfo.location}`;
        }
        
        // Buscar información sobre contacto
        if (normalizedMessage.includes('contacto') || normalizedMessage.includes('teléfono') || 
            normalizedMessage.includes('email') || normalizedMessage.includes('correo') ||
            normalizedMessage.includes('llamar')) {
            return `Puedes contactarnos a través de: ${this.businessInfo.contact}`;
        }
        
        // Buscar información sobre productos/servicios
        if (normalizedMessage.includes('producto') || normalizedMessage.includes('servicio') || 
            normalizedMessage.includes('ofrecen') || normalizedMessage.includes('venden')) {
            return `Ofrecemos los siguientes productos/servicios: ${this.businessInfo.products}`;
        }
        
        // Buscar información sobre especialidades
        if (normalizedMessage.includes('especialidad') || normalizedMessage.includes('especializan') || 
            normalizedMessage.includes('mejor') || normalizedMessage.includes('destacan')) {
            return `Nos especializamos en: ${this.businessInfo.specialties}`;
        }
        
        // Buscar información sobre promociones
        if (normalizedMessage.includes('promoción') || normalizedMessage.includes('descuento') || 
            normalizedMessage.includes('oferta') || normalizedMessage.includes('especial')) {
            return `Actualmente tenemos las siguientes promociones: ${this.businessInfo.promotions}`;
        }
        
        // Buscar información sobre métodos de pago
        if (normalizedMessage.includes('pago') || normalizedMessage.includes('pagar') || 
            normalizedMessage.includes('efectivo') || normalizedMessage.includes('tarjeta') ||
            normalizedMessage.includes('transferencia')) {
            return `Aceptamos los siguientes métodos de pago: ${this.businessInfo.paymentMethods}`;
        }
        
        // Buscar información sobre idiomas
        if (normalizedMessage.includes('idioma') || normalizedMessage.includes('hablan') || 
            normalizedMessage.includes('inglés') || normalizedMessage.includes('español')) {
            return `Atendemos en los siguientes idiomas: ${this.businessInfo.languages}`;
        }
        
        // Respuesta genérica si no se encuentra información específica
        const genericResponses = [
            `Gracias por tu mensaje. ¿Hay algo específico sobre ${this.businessInfo.name} que te gustaría saber?`,
            `Estoy aquí para ayudarte con cualquier información sobre ${this.businessInfo.name}. ¿Qué más te gustaría saber?`,
            `Si necesitas información específica sobre nuestros productos, horarios o ubicación, no dudes en preguntar.`,
            `¿Hay algo más en lo que pueda ayudarte sobre ${this.businessInfo.name}?`
        ];
        
        return genericResponses[Math.floor(Math.random() * genericResponses.length)];
    }
    
    /**
     * Verifica si dos textos comparten palabras clave comunes
     */
    hasCommonKeywords(text1, text2) {
        // Eliminar palabras comunes (stop words)
        const stopWords = ['el', 'la', 'los', 'las', 'un', 'una', 'unos', 'unas', 'y', 'o', 'a', 'ante', 'bajo', 'con', 'de', 'desde', 'en', 'entre', 'hacia', 'hasta', 'para', 'por', 'según', 'sin', 'sobre', 'tras', 'que', 'como', 'cuando', 'donde', 'si', 'no', 'al', 'del', 'lo', 'su', 'sus', 'mi', 'mis', 'tu', 'tus', 'se', 'es', 'son', 'está', 'están', 'hay', 'ser', 'tener', 'hacer', 'me', 'te', 'nos', 'os', 'le', 'les', 'la', 'las', 'lo', 'los'];
        
        // Obtener palabras clave
        const keywords1 = text1.split(/\s+/).filter(word => !stopWords.includes(word) && word.length > 2);
        const keywords2 = text2.split(/\s+/).filter(word => !stopWords.includes(word) && word.length > 2);
        
        // Contar coincidencias
        let matches = 0;
        for (const word1 of keywords1) {
            for (const word2 of keywords2) {
                if (word1 === word2 || word2.includes(word1) || word1.includes(word2)) {
                    matches++;
                }
            }
        }
        
        // Determinar si hay suficientes coincidencias
        const threshold = Math.min(keywords1.length, keywords2.length) * 0.3; // 30% de coincidencia
        return matches >= threshold;
    }
}

// Inicializar el creador de bot personalizado cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que el contenedor de pruebas esté disponible
    const waitForTestContainer = setInterval(function() {
        if (document.getElementById('test-container')) {
            clearInterval(waitForTestContainer);
            
            // Inicializar el creador de bot personalizado
            const customBotCreator = new CustomBotCreator();
            customBotCreator.initialize();
            
            // Guardar la instancia en window para acceso global
            window.customBotCreator = customBotCreator;
            
            // Modificar la función de envío de mensajes para usar el bot personalizado
            const originalSendUserMessage = window.sendUserMessage;
            if (originalSendUserMessage) {
                window.sendUserMessage = function() {
                    const userMessageInput = document.getElementById('user-message');
                    const message = userMessageInput.value.trim();
                    
                    if (message) {
                        // Añadir mensaje del usuario
                        addUserMessage(message);
                        userMessageInput.value = '';
                        
                        // Incrementar contador de mensajes
                        const messagesCount = document.getElementById('messages-count');
                        messagesCount.textContent = parseInt(messagesCount.textContent) + 1;
                        
                        // Verificar si estamos en el escenario de bot personalizado
                        const activeScenario = document.querySelector('.test-scenario.active');
                        if (activeScenario && activeScenario.getAttribute('data-scenario') === 'custom-business') {
                            // Usar el bot personalizado
                            setTimeout(() => {
                                // Generar tiempo de respuesta aleatorio
                                const responseTime = (Math.random() * 0.5 + 0.5).toFixed(1);
                                document.getElementById('response-time').textContent = responseTime + 's';
                                
                                // Obtener respuesta del bot personalizado
                                const botResponse = window.customBotCreator.processUserMessage(message);
                                addBotMessage(botResponse);
                                
                                // Incrementar contador de mensajes
                                messagesCount.textContent = parseInt(messagesCount.textContent) + 1;
                            }, Math.random() * 1000 + 500);
                        } else {
                            // Usar el comportamiento original para otros escenarios
                            setTimeout(() => {
                                // Generar tiempo de respuesta aleatorio
                                const responseTime = (Math.random() * 0.5 + 0.5).toFixed(1);
                                document.getElementById('response-time').textContent = responseTime + 's';
                                
                                // Respuesta genérica del bot
                                const botResponses = [
                                    'Gracias por tu mensaje. ¿Puedes proporcionar más detalles?',
                                    'Entiendo lo que necesitas. ¿Hay algo más en lo que pueda ayudarte?',
                                    'Estoy procesando tu solicitud. ¿Podrías darme más información?',
                                    'Voy a ayudarte con eso. ¿Tienes alguna preferencia específica?',
                                    'He recibido tu mensaje. ¿Hay algo más que deba saber?'
                                ];
                                
                                const randomResponse = botResponses[Math.floor(Math.random() * botResponses.length)];
                                addBotMessage(randomResponse);
                                
                                // Incrementar contador de mensajes
                                messagesCount.textContent = parseInt(messagesCount.textContent) + 1;
                            }, Math.random() * 1000 + 500);
                        }
                    }
                };
            }
            
            // Modificar la función de carga de escenario para manejar el escenario personalizado
            const originalLoadTestScenario = window.loadTestScenario;
            if (originalLoadTestScenario) {
                window.loadTestScenario = function(scenarioType) {
                    if (scenarioType === 'custom-business') {
                        // Mostrar el formulario de información de negocio
                        window.customBotCreator.showBusinessForm();
                    } else {
                        // Usar el comportamiento original para otros escenarios
                        originalLoadTestScenario(scenarioType);
                    }
                };
            }
        }
    }, 100);
});
