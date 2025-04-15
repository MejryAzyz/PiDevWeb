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

    // Knowledge base for chatbot responses
    const knowledgeBase = {
        // General questions
        'bonjour': (data) => 'Bonjour ! Je suis votre assistant virtuel pour les hébergements. Comment puis-je vous aider aujourd\'hui ?',
        'salut': (data) => 'Salut ! Je suis là pour répondre à vos questions sur nos hébergements. Que voulez-vous savoir ?',
        'hello': (data) => 'Bonjour ! Comment puis-je vous aider avec votre recherche d\'hébergement ?',
        'aide': (data) => 'Je peux vous aider avec les tarifs, les disponibilités, les services inclus, et plus encore. Posez-moi une question !',
        'merci': (data) => 'De rien ! N\'hésitez pas si vous avez d\'autres questions.',
        'au revoir': (data) => 'Au revoir ! N\'hésitez pas à revenir si vous avez d\'autres questions sur nos hébergements.',

        // Accommodation questions
        'prix': (data) => {
            if (data && data.tarifNuit) {
                return `Le tarif de ${data.nom} est de ${data.tarifNuit}€ par nuit. Ce prix peut varier selon la saison. N'hésitez pas à nous contacter pour vérifier si des promotions sont disponibles pour vos dates.`;
            }
            return 'Nos prix varient selon la saison et le type d\'hébergement. Vous pouvez voir les tarifs directement sur chaque fiche d\'hébergement. Nous proposons des tarifs à partir de 40€ par nuit.';
        },
        'tarif': (data) => {
            if (data && data.tarifNuit) {
                return `Le tarif de ${data.nom} est de ${data.tarifNuit}€ par nuit, taxes incluses. La taxe de séjour est de 2€ par nuit et par personne, ce qui fait un total de ${data.tarifNuit + 2}€ par nuit pour une personne.`;
            }
            return 'Nos tarifs commencent à 40€ par nuit et peuvent varier selon la saison, la capacité et les services inclus. Consultez la fiche détaillée de l\'hébergement pour voir le prix exact.';
        },
        'réservation': (data) => {
            if (data && data.telephone && data.email) {
                return `Pour réserver ${data.nom}, vous pouvez nous contacter directement par téléphone au ${data.telephone} ou par email à ${data.email}. Nous confirmerons votre réservation dans les 24 heures.`;
            }
            return 'Pour réserver, vous pouvez nous contacter directement par téléphone ou par email depuis la fiche de l\'hébergement. Nous confirmerons votre réservation dans les 24 heures.';
        },
        'réserver': (data) => {
            if (data && data.telephone && data.email) {
                return `La réservation est simple ! Pour ${data.nom}, contactez-nous au ${data.telephone} ou par email à ${data.email}. Un acompte de 30% sera demandé pour confirmer la réservation.`;
            }
            return 'La réservation est simple ! Contactez-nous par téléphone ou email depuis la page de l\'hébergement qui vous intéresse. Un acompte de 30% sera demandé pour confirmer la réservation.';
        },
        'disponibilité': (data) => {
            if (data && data.nom) {
                return `Pour connaître les disponibilités exactes de ${data.nom}, veuillez nous contacter directement par téléphone ou email. Les disponibilités évoluent rapidement, mais nous pourrons vous donner une réponse immédiate.`;
            }
            return 'Pour connaître les disponibilités exactes d\'un hébergement, veuillez nous contacter directement. Nous mettons à jour notre calendrier régulièrement.';
        },
        'disponible': (data) => {
            if (data && data.nom) {
                return `Les disponibilités pour ${data.nom} sont mises à jour quotidiennement. Actuellement, certaines dates sont encore libres pour les prochaines semaines. Contactez-nous pour vérifier la disponibilité aux dates qui vous intéressent.`;
            }
            return 'Les disponibilités sont mises à jour quotidiennement. Si vous êtes intéressé par un hébergement spécifique, contactez-nous pour vérifier sa disponibilité aux dates souhaitées.';
        },
        'capacité': (data) => {
            if (data && data.capacite) {
                return `${data.nom} peut accueillir jusqu'à ${data.capacite} personnes. C'est idéal ${data.capacite > 4 ? 'pour les groupes ou les grandes familles' : 'pour un couple ou une petite famille'}.`;
            }
            return 'Nos hébergements ont des capacités variées, allant de 1 à 10 personnes. Vous pouvez filtrer les hébergements par capacité dans notre page de recherche.';
        },

        // Services & amenities
        'service': (data) => {
            if (data && data.nom) {
                return `${data.nom} inclut les services standards : Wi-Fi gratuit, linge de lit, ménage de fin de séjour, et parking. Il dispose également de services premium comme la climatisation, l'accès à une cuisine équipée et un service de conciergerie disponible 24/7.`;
            }
            return 'La plupart de nos hébergements incluent : Wi-Fi gratuit, linge de lit, ménage de fin de séjour, et parking. Certains proposent des services supplémentaires comme le petit-déjeuner ou la climatisation.';
        },
        'services': (data) => {
            if (data && data.nom) {
                return `${data.nom} offre un ensemble de services premium : Wi-Fi haut débit, linge de lit de qualité hôtelière, ménage quotidien sur demande, parking sécurisé, climatisation, et service de conciergerie. Certains services complémentaires peuvent être arrangés moyennant un supplément.`;
            }
            return 'Nos services standard comprennent le Wi-Fi, le linge de lit et le ménage de fin de séjour. Des services premium comme le petit-déjeuner ou le transfert depuis l\'aéroport sont disponibles dans certains établissements.';
        },
        'wifi': (data) => {
            if (data && data.nom) {
                return `Oui, ${data.nom} dispose d'une connexion Wi-Fi gratuite et illimitée avec une vitesse moyenne de 100 Mbps, parfaite pour le streaming ou les vidéoconférences.`;
            }
            return 'Oui, tous nos hébergements disposent d\'une connexion Wi-Fi gratuite et illimitée.';
        },
        'parking': (data) => {
            if (data && data.nom) {
                return `${data.nom} dispose d'un parking privé gratuit sur place. Les places sont attribuées selon l'ordre d'arrivée, mais il y a généralement suffisamment d'espace pour tous nos clients.`;
            }
            return 'La plupart de nos hébergements offrent des options de stationnement gratuit. Vérifiez les détails sur la page de l\'hébergement qui vous intéresse.';
        },
        'petit-déjeuner': (data) => {
            if (data && data.nom) {
                return `${data.nom} propose un petit-déjeuner continental en option pour 12€ par personne. Il peut être commandé la veille jusqu'à 20h et vous sera livré à l'heure de votre choix entre 7h et 10h.`;
            }
            return 'Certains de nos hébergements proposent le petit-déjeuner inclus ou en option. Cette information est précisée sur la fiche de chaque hébergement.';
        },
        'climatisation': (data) => {
            if (data && data.nom) {
                return `Oui, ${data.nom} est entièrement équipé de climatisation réversible, vous permettant de régler la température idéale quelle que soit la saison.`;
            }
            return 'La majorité de nos hébergements sont équipés de climatisation. Consultez la fiche détaillée pour confirmer.';
        },

        // Location & transport
        'emplacement': (data) => {
            if (data && data.adresse) {
                return `${data.nom} est situé à ${data.adresse}. C'est un emplacement idéal, proche des transports en commun et à distance de marche de nombreux points d'intérêt. Vous pouvez consulter la carte dans l'onglet "Localisation" pour voir exactement où il se trouve.`;
            }
            return 'Nos hébergements sont situés dans différents quartiers. Vous pouvez voir l\'emplacement exact sur la carte disponible sur chaque fiche d\'hébergement.';
        },
        'localisation': (data) => {
            if (data && data.adresse) {
                return `${data.nom} est situé à ${data.adresse}. C'est un quartier ${Math.random() > 0.5 ? 'calme et résidentiel' : 'animé et central'}, avec de nombreux restaurants et commerces à proximité. L'arrêt de bus le plus proche est à 3 minutes à pied.`;
            }
            return 'Chaque hébergement dispose d\'une carte dans sa fiche détaillée qui vous montre sa localisation exacte et les points d\'intérêt à proximité.';
        },
        'transport': (data) => {
            if (data && data.adresse) {
                return `${data.nom} est bien desservi par les transports en commun. La station de métro la plus proche est à environ 500m et plusieurs lignes de bus passent à proximité. Si vous venez en voiture, un parking gratuit est disponible sur place.`;
            }
            return 'La plupart de nos hébergements sont bien desservis par les transports en commun. Les détails spécifiques sont disponibles sur chaque fiche d\'hébergement.';
        },
        'distance': (data) => {
            if (data && data.adresse) {
                return `Depuis ${data.nom}, il faut environ 15 minutes en transports en commun pour rejoindre le centre-ville, 20 minutes pour la gare principale et 45 minutes pour l'aéroport. La plage la plus proche est à 30 minutes en bus.`;
            }
            return 'Pour connaître la distance entre un hébergement et un lieu spécifique, consultez la carte sur la fiche de l\'hébergement ou contactez-nous directement.';
        },

        // Policies
        'annulation': (data) => {
            if (data && data.nom) {
                return `Pour ${data.nom}, notre politique d'annulation permet une annulation gratuite jusqu'à 7 jours avant l'arrivée. En cas d'annulation entre 7 jours et 48h avant l'arrivée, 50% du séjour sera facturé. Moins de 48h avant l'arrivée, le séjour sera facturé intégralement.`;
            }
            return 'Notre politique d\'annulation standard permet une annulation gratuite jusqu\'à 7 jours avant l\'arrivée. Des politiques spécifiques peuvent s\'appliquer selon l\'hébergement.';
        },
        'animaux': (data) => {
            if (data && data.nom) {
                return `${data.nom} accepte les petits animaux de compagnie (chiens de moins de 10kg et chats) moyennant un supplément de 15€ par nuit. Merci de nous informer à l'avance si vous prévoyez de venir avec votre animal.`;
            }
            return 'Certains de nos hébergements acceptent les animaux de compagnie, généralement avec un supplément. Cette information est indiquée sur la fiche de chaque hébergement.';
        },
        'enfants': (data) => {
            if (data && data.nom) {
                return `${data.nom} est parfaitement adapté aux familles avec enfants. Nous pouvons fournir gratuitement un lit bébé, une chaise haute et une baignoire pour bébé sur demande. L'hébergement dispose également d'un espace de jeux sécurisé.`;
            }
            return 'La plupart de nos hébergements sont adaptés aux familles. Certains proposent même des équipements spécifiques pour les enfants comme des lits bébé ou des chaises hautes.';
        },
        'heure d\'arrivée': (data) => {
            if (data && data.nom) {
                return `Pour ${data.nom}, l'heure d'arrivée standard est entre 15h et 19h, et l'heure de départ est fixée à 11h. Une arrivée tardive est possible jusqu'à 22h, mais merci de nous prévenir à l'avance.`;
            }
            return 'L\'heure d\'arrivée standard est généralement entre 14h et 18h, mais cela peut varier. L\'heure de départ est habituellement fixée à 11h.';
        },
        'check-in': (data) => {
            if (data && data.nom) {
                return `Le check-in à ${data.nom} se fait entre 15h et 19h. Pour une arrivée en dehors de ces horaires, merci de nous contacter au ${data.telephone || 'numéro indiqué sur la fiche'} afin que nous puissions organiser votre accueil.`;
            }
            return 'Le check-in se fait généralement entre 14h et 18h. Si vous prévoyez d\'arriver en dehors de ces horaires, veuillez nous en informer à l\'avance.';
        },
        'check-out': (data) => {
            if (data && data.nom) {
                return `Le check-out de ${data.nom} doit être effectué avant 11h. Un départ tardif peut être arrangé jusqu'à 14h moyennant un supplément de 20€, sous réserve de disponibilité.`;
            }
            return 'Le check-out est généralement prévu avant 11h. Un départ tardif peut être organisé sur demande, selon disponibilité.';
        },

        // Default response
        'default': (data) => 'Je n\'ai pas complètement compris votre question. Pouvez-vous la reformuler ou me demander quelque chose sur nos tarifs, services, disponibilités ou localisation ?'
    };

    // Function to find the best match in the knowledge base
    function findBestMatch(query) {
        query = query.toLowerCase();
        const hebergementData = getCurrentHebergementData();
        
        // Direct match
        for (const key in knowledgeBase) {
            if (query.includes(key)) {
                return knowledgeBase[key](hebergementData);
            }
        }
        
        // Check for related keywords
        if (query.includes('combien') || query.includes('coûte') || query.includes('euros') || query.includes('€')) {
            return knowledgeBase['tarif'](hebergementData);
        }
        
        if (query.includes('réserv')) {
            return knowledgeBase['réservation'](hebergementData);
        }
        
        if (query.includes('place') || query.includes('personne')) {
            return knowledgeBase['capacité'](hebergementData);
        }
        
        if (query.includes('petit dej') || query.includes('manger') || query.includes('repas')) {
            return knowledgeBase['petit-déjeuner'](hebergementData);
        }
        
        if (query.includes('inclus') || query.includes('propose') || query.includes('offre')) {
            return knowledgeBase['services'](hebergementData);
        }
        
        if (query.includes('où') || query.includes('quartier') || query.includes('près') || query.includes('situé')) {
            return knowledgeBase['localisation'](hebergementData);
        }
        
        if (query.includes('annul') || query.includes('rembourse')) {
            return knowledgeBase['annulation'](hebergementData);
        }
        
        if (query.includes('chien') || query.includes('chat') || query.includes('pet')) {
            return knowledgeBase['animaux'](hebergementData);
        }
        
        if (query.includes('arrivée') || query.includes('arriver')) {
            return knowledgeBase['check-in'](hebergementData);
        }
        
        if (query.includes('départ') || query.includes('partir')) {
            return knowledgeBase['check-out'](hebergementData);
        }
        
        // Additional keywords
        if (query.includes('wifi') || query.includes('internet') || query.includes('connexion')) {
            return knowledgeBase['wifi'](hebergementData);
        }
        
        if (query.includes('park') || query.includes('stationn') || query.includes('voiture')) {
            return knowledgeBase['parking'](hebergementData);
        }
        
        if (query.includes('clim') || query.includes('température') || query.includes('chauffage')) {
            return knowledgeBase['climatisation'](hebergementData);
        }
        
        if (query.includes('enfant') || query.includes('bébé') || query.includes('famille')) {
            return knowledgeBase['enfants'](hebergementData);
        }
        
        if (query.includes('transport') || query.includes('bus') || query.includes('métro') || query.includes('train')) {
            return knowledgeBase['transport'](hebergementData);
        }
        
        if (query.includes('distance') || query.includes('loin') || query.includes('proche') || query.includes('km')) {
            return knowledgeBase['distance'](hebergementData);
        }
        
        // Default response
        return knowledgeBase['default'](hebergementData);
    }

    // Initialize chatbot
    function initChatbot() {
        // Elements
        const chatbotToggle = document.getElementById('chatbot-toggle');
        const chatbotContainer = document.getElementById('chatbot-container');
        const chatbotMessages = document.getElementById('chatbot-messages');
        const chatbotInput = document.getElementById('chatbot-input');
        const chatbotSend = document.getElementById('chatbot-send');
        
        // Get current page data
        const hebergementData = getCurrentHebergementData();
        
        // Show initial message
        let welcomeMessage = "Bonjour ! Je suis votre assistant virtuel. Comment puis-je vous aider avec votre recherche d'hébergement ?";
        
        if (hebergementData && hebergementData.nom) {
            welcomeMessage = `Bonjour ! Je suis votre assistant virtuel pour ${hebergementData.nom}. Posez-moi des questions sur cet hébergement, ses services ou comment le réserver !`;
        }
        
        addBotMessage(welcomeMessage);
        
        // Toggle chatbot visibility
        if (chatbotToggle) {
            chatbotToggle.addEventListener('click', function() {
                if (chatbotContainer.classList.contains('chatbot-hidden')) {
                    chatbotContainer.classList.remove('chatbot-hidden');
                    chatbotToggle.innerHTML = '<i class="icofont-close-line"></i>';
                } else {
                    chatbotContainer.classList.add('chatbot-hidden');
                    chatbotToggle.innerHTML = '<i class="icofont-robot"></i>';
                }
            });
        }
        
        // Send message on button click
        if (chatbotSend) {
            chatbotSend.addEventListener('click', sendMessage);
        }
        
        // Send message on Enter key
        if (chatbotInput) {
            chatbotInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        }
        
        function sendMessage() {
            const message = chatbotInput.value.trim();
            if (message) {
                // Add user message
                addUserMessage(message);
                
                // Clear input
                chatbotInput.value = '';
                
                // Add bot response after a short delay
                setTimeout(function() {
                    const response = findBestMatch(message);
                    addBotMessage(response);
                    
                    // Scroll to bottom
                    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
                }, 500);
            }
        }
        
        function addUserMessage(message) {
            const messageElement = document.createElement('div');
            messageElement.className = 'chatbot-message user-message';
            messageElement.textContent = message;
            chatbotMessages.appendChild(messageElement);
            
            // Scroll to bottom
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }
        
        function addBotMessage(message) {
            const messageElement = document.createElement('div');
            messageElement.className = 'chatbot-message bot-message';
            messageElement.textContent = message;
            chatbotMessages.appendChild(messageElement);
            
            // Scroll to bottom
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initChatbot();
    });

})(); 