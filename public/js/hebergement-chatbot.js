/**
 * Hebergement Chatbot
 * A simple chatbot that answers questions about accommodations
 */
(function() {
    'use strict';

    // Chatbot state
    let isChatbotOpen = false;
    let conversationHistory = [];

    // DOM Elements
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSend = document.getElementById('chatbot-send');

    // Page URLs with correct routes
    const PAGES = {
        cliniques: '/app_clinique_front_index',
        hebergements: '/app_hebergement_front_index',
        reservations: '/mesReservation',
        transport: '/app_transport_front_index'
    };

    // Initialize chatbot
    function initChatbot() {
        // Add welcome message
        addMessage("Bonjour! Je suis votre assistant virtuel. Comment puis-je vous aider aujourd'hui?", 'bot', false);
        
        // Event listeners
        chatbotToggle.addEventListener('click', toggleChatbot);
        chatbotSend.addEventListener('click', handleSendMessage);
        chatbotInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                handleSendMessage();
            }
        });

        // Add click event listener for links in messages
        chatbotMessages.addEventListener('click', (e) => {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                const href = e.target.getAttribute('href');
                // Remove the leading slash if present for proper routing
                const route = href.startsWith('/') ? href.substring(1) : href;
                window.location.href = route;
            }
        });
    }

    // Toggle chatbot visibility
    function toggleChatbot() {
        isChatbotOpen = !isChatbotOpen;
        chatbotContainer.classList.toggle('chatbot-hidden');
        if (isChatbotOpen) {
            chatbotInput.focus();
        }
    }

    // Add message to chat
    function addMessage(text, sender, showTyping = true) {
        if (showTyping && sender === 'bot') {
            // Add typing indicator
            const typingIndicator = document.createElement('div');
            typingIndicator.className = 'typing-indicator';
            typingIndicator.innerHTML = '<span></span><span></span><span></span>';
            chatbotMessages.appendChild(typingIndicator);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            
            // Remove typing indicator and show message after delay
            setTimeout(() => {
                if (typingIndicator.parentNode === chatbotMessages) {
                    chatbotMessages.removeChild(typingIndicator);
                }
                appendMessageToChat(text, sender);
            }, 1000);
        } else {
            appendMessageToChat(text, sender);
        }
    }

    // Append message to chat
    function appendMessageToChat(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${sender}-message`;
        messageDiv.innerHTML = `<p>${text}</p>`;
        chatbotMessages.appendChild(messageDiv);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    // Handle user messages
    function handleSendMessage() {
        const message = chatbotInput.value.trim();
        if (!message) return;

        // Add user message to chat
        addMessage(message, 'user', false);
        chatbotInput.value = '';

        // Process message and generate response
        const response = generateResponse(message);
        addMessage(response, 'bot');
    }

    // Create a link in the message
    function createLink(text, page) {
        return `<a href="${page}" class="chatbot-link" style="color: #4a6cf7; text-decoration: underline; cursor: pointer;">${text}</a>`;
    }

    // Generate response based on user message
    function generateResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        // Simple response patterns
        if (lowerMessage.includes('bonjour') || lowerMessage.includes('salut') || lowerMessage.includes('hello')) {
            return "Bonjour! Comment puis-je vous aider aujourd'hui?";
        } 
        // Clinique related queries
        else if (lowerMessage.includes('clinique') || lowerMessage.includes('hopital') || lowerMessage.includes('médical') || lowerMessage.includes('medical') || lowerMessage.includes('soins')) {
            if (lowerMessage.includes('adresse') || lowerMessage.includes('où') || lowerMessage.includes('ou')) {
                return `Nos cliniques sont situées dans différentes villes. Vous pouvez consulter leurs adresses exactes sur ${createLink('notre page des cliniques', PAGES.cliniques)}. Chaque clinique dispose d'une fiche détaillée avec son emplacement.`;
            } else if (lowerMessage.includes('docteur') || lowerMessage.includes('médecin') || lowerMessage.includes('specialiste')) {
                return `Nos cliniques disposent d'une équipe de médecins spécialisés. Vous pouvez consulter leurs profils, spécialités et disponibilités sur ${createLink('la page de nos cliniques', PAGES.cliniques)}.`;
            } else if (lowerMessage.includes('prix') || lowerMessage.includes('tarif') || lowerMessage.includes('coût')) {
                return `Les tarifs des cliniques varient selon les soins et services requis. Consultez ${createLink('notre page des cliniques', PAGES.cliniques)} pour voir les tarifs de base. Pour un devis précis, contactez directement la clinique de votre choix.`;
            } else {
                return `Nos cliniques partenaires offrent des soins médicaux de haute qualité. Découvrez tous nos établissements sur ${createLink('la page des cliniques', PAGES.cliniques)}. Que souhaitez-vous savoir plus précisément?`;
            }
        }
        // Reservation related queries
        else if (lowerMessage.includes('réserv') || lowerMessage.includes('reserv') || lowerMessage.includes('rendez-vous') || lowerMessage.includes('rdv')) {
            if (lowerMessage.includes('annul')) {
                return `Pour annuler une réservation, accédez à ${createLink('la section Mes Réservations', PAGES.reservations)} de votre compte. Vous pouvez y gérer toutes vos réservations.`;
            } else if (lowerMessage.includes('modif')) {
                return `Pour modifier une réservation, rendez-vous dans ${createLink('la section Mes Réservations', PAGES.reservations)} de votre compte. Attention, les modifications sont soumises à la disponibilité des services.`;
            } else if (lowerMessage.includes('date')) {
                return `Lors de votre réservation sur ${createLink('notre plateforme', PAGES.reservations)}, vous pourrez choisir vos dates de séjour, la date de départ et les horaires qui vous conviennent. Tout est personnalisable selon vos besoins.`;
            } else {
                return `Pour effectuer une réservation, commencez par choisir une clinique sur ${createLink('notre page des cliniques', PAGES.cliniques)}, un hébergement sur ${createLink('notre page des hébergements', PAGES.hebergements)}, et un transport sur ${createLink('notre page des transports', PAGES.transport)}. Vous pourrez ensuite sélectionner vos dates et finaliser votre réservation en ligne.`;
            }
        }
        // Accompaniment related queries
        else if (lowerMessage.includes('accompagn') || lowerMessage.includes('assistance') || lowerMessage.includes('aide') || lowerMessage.includes('support')) {
            if (lowerMessage.includes('service')) {
                return `Nous proposons un service d'accompagnement complet. Consultez ${createLink('nos services de transport', PAGES.transport)} et autres prestations d'assistance pour votre séjour médical.`;
            } else if (lowerMessage.includes('langue') || lowerMessage.includes('traduction')) {
                return "Nos accompagnateurs sont multilingues et peuvent vous assister pour la traduction lors de vos rendez-vous médicaux et pendant votre séjour.";
            } else if (lowerMessage.includes('disponible') || lowerMessage.includes('horaire')) {
                return `Consultez les disponibilités de nos accompagnateurs sur ${createLink('la page des services', PAGES.transport)}. Ils sont disponibles selon vos besoins, que ce soit pour des rendez-vous ponctuels ou un accompagnement continu.`;
            } else {
                return `Notre service d'accompagnement est là pour faciliter votre séjour médical. Découvrez nos prestations sur ${createLink('la page des services', PAGES.transport)}.`;
            }
        }
        // Existing responses with links
        else if (lowerMessage.includes('capacité') || lowerMessage.includes('personnes') || lowerMessage.includes('places')) {
            return `La capacité des hébergements varie selon le type de logement. Consultez ${createLink('notre page des hébergements', PAGES.hebergements)} où vous pourrez filtrer les options selon vos besoins.`;
        } else if (lowerMessage.includes('prix') || lowerMessage.includes('tarif') || lowerMessage.includes('coût')) {
            return `Les prix varient selon le type d'hébergement, la saison et la durée du séjour. Consultez ${createLink('notre page des hébergements', PAGES.hebergements)} où vous pourrez filtrer selon votre budget.`;
        } else if (lowerMessage.includes('disponibilité') || lowerMessage.includes('disponible')) {
            return `Pour vérifier la disponibilité d'un hébergement, consultez ${createLink('notre page des hébergements', PAGES.hebergements)} et utilisez le calendrier sur la page de l'hébergement qui vous intéresse.`;
        } else if (lowerMessage.includes('transport') || lowerMessage.includes('déplacement') || lowerMessage.includes('navette')) {
            return `Découvrez nos services de transport sur ${createLink('la page dédiée', PAGES.transport)}. Nos horaires sont flexibles et adaptés à vos rendez-vous médicaux.`;
        } else if (lowerMessage.includes('merci') || lowerMessage.includes('remercie')) {
            return "Je vous en prie! N'hésitez pas si vous avez d'autres questions.";
        } else if (lowerMessage.includes('au revoir') || lowerMessage.includes('bye')) {
            return "Au revoir! Merci d'avoir utilisé notre service. À bientôt!";
        } else {
            return `Je peux vous renseigner sur ${createLink('nos cliniques', PAGES.cliniques)}, ${createLink('vos réservations', PAGES.reservations)}, ${createLink('les hébergements', PAGES.hebergements)}, et ${createLink('les services de transport', PAGES.transport)}. N'hésitez pas à me poser des questions plus précises.`;
        }
    }

    // Initialize chatbot when DOM is loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChatbot);
    } else {
        initChatbot();
    }
})(); 