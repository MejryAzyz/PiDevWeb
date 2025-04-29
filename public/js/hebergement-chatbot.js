/**
 * Hebergement Chatbot
 * A simple chatbot that answers questions about accommodations
 */
(function() {
    'use strict';

    // Get current accommodation data if we're on a details page
    function getCurrentHebergementData() {
        // Check if we're on a details page
        const isDetailsPage = window.location.pathname.includes('/hebergement/') && !window.location.pathname.endsWith('/index');
        
        if (isDetailsPage) {
            // Extract data from the page
            return {
                nom: document.querySelector('.hotel-name h4') ? document.querySelector('.hotel-name h4').textContent.trim() : null,
                adresse: document.querySelector('.hotel-name .f-14') ? document.querySelector('.hotel-name .f-14').textContent.trim() : null,
                tarifNuit: document.querySelector('.price-badge h4') ? 
                    parseInt(document.querySelector('.price-badge h4').textContent.trim().replace('€', '')) : null,
                capacite: document.querySelector('.property-details li:first-child .property-value') ? 
                    parseInt(document.querySelector('.property-details li:first-child .property-value').textContent.trim()) : null,
                telephone: document.querySelector('.media-body a[href^="tel:"]') ? 
                    document.querySelector('.media-body a[href^="tel:"]').textContent.trim() : null,
                email: document.querySelector('.media-body a[href^="mailto:"]') ? 
                    document.querySelector('.media-body a[href^="mailto:"]').textContent.trim() : null
            };
        }
        
        return null;
    }

    // AI Personality and Response Patterns
    const AI_PERSONALITY = {
        name: 'Alex',
        traits: {
            friendly: true,
            helpful: true,
            professional: true,
            enthusiastic: true
        },
        responsePatterns: {
            greeting: [
                "Bonjour! Je suis Alex, votre assistant virtuel pour les hébergements. Comment puis-je vous aider aujourd'hui?",
                "Salut! Je suis ravi de vous aider avec vos questions sur nos hébergements. Que souhaitez-vous savoir?",
                "Bonjour! Je suis là pour vous guider dans votre recherche d'hébergement. Que puis-je faire pour vous?"
            ],
            thinking: [
                "Je réfléchis à votre question...",
                "Je consulte mes informations...",
                "Je cherche la meilleure réponse pour vous..."
            ],
            error: [
                "Je m'excuse, j'ai rencontré une petite difficulté. Pouvez-vous reformuler votre question?",
                "Je n'ai pas bien compris. Pourriez-vous être plus précis?",
                "Je vais avoir besoin d'un peu plus de détails pour vous aider au mieux."
            ],
            farewell: [
                "Au revoir! N'hésitez pas à revenir si vous avez d'autres questions.",
                "À bientôt! J'espère avoir pu vous aider.",
                "Merci de votre visite! Revenez quand vous voulez."
            ]
        }
    };

    // Chatbot configuration
    const CHATBOT_CONFIG = {
        apiKey: 'AIzaSyAj23EhnddEwWhX9-bJs9rJUnsiZn9Fv1g',
        model: 'gemini-pro',
        endpoint: 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent'
    };

    // Chatbot state
    let isChatbotOpen = false;
    let conversationHistory = [];

    // DOM Elements
        const chatbotToggle = document.getElementById('chatbot-toggle');
        const chatbotContainer = document.getElementById('chatbot-container');
        const chatbotMessages = document.getElementById('chatbot-messages');
        const chatbotInput = document.getElementById('chatbot-input');
        const chatbotSend = document.getElementById('chatbot-send');
        
    // Initialize chatbot with personality
    function initChatbot() {
        // Add welcome message with personality
        setTimeout(() => {
            const randomGreeting = AI_PERSONALITY.responsePatterns.greeting[
                Math.floor(Math.random() * AI_PERSONALITY.responsePatterns.greeting.length)
            ];
            addMessage(randomGreeting, 'bot');
        }, 500);
        
        // Event listeners
        chatbotToggle.addEventListener('click', toggleChatbot);
        chatbotSend.addEventListener('click', handleSendMessage);
        chatbotInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                handleSendMessage();
                }
            });
        }
        
    // Toggle chatbot visibility with animation
    function toggleChatbot() {
        isChatbotOpen = !isChatbotOpen;
        chatbotContainer.classList.toggle('chatbot-hidden');
        if (isChatbotOpen) {
            setTimeout(() => {
                chatbotInput.focus();
            }, 300);
        }
    }

    // Enhanced message handling with typing simulation
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${sender}-message`;
        
        if (sender === 'bot') {
            // Add typing indicator
            const typingIndicator = document.createElement('div');
            typingIndicator.className = 'typing-indicator';
            typingIndicator.innerHTML = '<span></span><span></span><span></span>';
            chatbotMessages.appendChild(typingIndicator);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            
            // Simulate typing delay based on message length
            const typingDelay = Math.min(1000 + (text.length * 20), 3000);
            
            setTimeout(() => {
                chatbotMessages.removeChild(typingIndicator);
                
                // Add message with typing effect
                let currentText = '';
                const words = text.split(' ');
                let wordIndex = 0;
                
                const typeNextWord = () => {
                    if (wordIndex < words.length) {
                        currentText += (wordIndex > 0 ? ' ' : '') + words[wordIndex];
                        messageDiv.innerHTML = `<p>${currentText}</p>`;
                        chatbotMessages.appendChild(messageDiv);
                    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
                        wordIndex++;
                        setTimeout(typeNextWord, Math.random() * 100 + 50);
                    }
                };
                
                typeNextWord();
            }, typingDelay);
        } else {
            messageDiv.innerHTML = `<p>${text}</p>`;
            chatbotMessages.appendChild(messageDiv);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }
    }

    // Enhanced message handling with context awareness
    async function handleSendMessage() {
        const message = chatbotInput.value.trim();
        if (!message) return;

        // Add user message to chat
        addMessage(message, 'user');
        chatbotInput.value = '';

        try {
            // Get current accommodation data
            const hebergementData = getCurrentHebergementData();
            
            // Show thinking indicator
            const thinkingMessage = AI_PERSONALITY.responsePatterns.thinking[
                Math.floor(Math.random() * AI_PERSONALITY.responsePatterns.thinking.length)
            ];
            const thinkingIndicator = document.createElement('div');
            thinkingIndicator.className = 'typing-indicator';
            thinkingIndicator.innerHTML = `<span></span><span></span><span></span><p class="thinking-text">${thinkingMessage}</p>`;
            chatbotMessages.appendChild(thinkingIndicator);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;

            // Prepare context for Gemini
            let context = '';
            if (hebergementData) {
                context = `Je suis sur la page de l'hébergement "${hebergementData.nom}" avec les détails suivants:
- Adresse: ${hebergementData.adresse}
- Tarif par nuit: ${hebergementData.tarifNuit}€
- Capacité: ${hebergementData.capacite} personnes
- Contact: ${hebergementData.telephone}, ${hebergementData.email}

`;
            } else {
                context = `Je suis sur la page de liste des hébergements.`;
            }

            // Call Gemini API with enhanced context
            const response = await fetch(`${CHATBOT_CONFIG.endpoint}?key=${CHATBOT_CONFIG.apiKey}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    contents: [
                        {
                            role: 'user',
                            parts: [{ text: `${context}Tu es un assistant virtuel nommé ${AI_PERSONALITY.name} spécialisé dans l'aide aux clients pour les hébergements. Tu es ${AI_PERSONALITY.traits.friendly ? 'amical' : ''} ${AI_PERSONALITY.traits.helpful ? 'et serviable' : ''}. 

Voici les informations importantes à savoir:
1. Les prix sont en euros (€)
2. La capacité indique le nombre maximum de personnes
3. Les réservations se font par téléphone ou email
4. Les disponibilités sont à vérifier directement avec l'hébergement

Réponds à la question suivante de manière naturelle et conversationnelle, en utilisant les informations disponibles: ${message}` }]
                        }
                    ],
                    generationConfig: {
                        temperature: 0.7,
                        topK: 40,
                        topP: 0.95,
                        maxOutputTokens: 1024,
                    }
                })
            });

            if (!response.ok) {
                throw new Error('API request failed');
            }

            const data = await response.json();
            let botResponse = data.candidates[0].content.parts[0].text;

            // Remove thinking indicator
            chatbotMessages.removeChild(thinkingIndicator);

            // Add bot response to chat and conversation history
            addMessage(botResponse, 'bot');
            conversationHistory.push({
                role: 'model',
                parts: [{ text: botResponse }]
            });

            // Keep conversation history manageable
            if (conversationHistory.length > 10) {
                conversationHistory = conversationHistory.slice(-10);
            }

        } catch (error) {
            console.error('Error:', error);
            chatbotMessages.removeChild(thinkingIndicator);
            const errorMessage = AI_PERSONALITY.responsePatterns.error[
                Math.floor(Math.random() * AI_PERSONALITY.responsePatterns.error.length)
            ];
            addMessage(errorMessage, 'bot');
        }
    }

    // Initialize chatbot when DOM is loaded
    document.addEventListener('DOMContentLoaded', initChatbot);

})(); 