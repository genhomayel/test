document.addEventListener('DOMContentLoaded', function() {
    // Code de recherche d'armes
    const searchInput = document.getElementById('weapon-search');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const weaponCards = document.querySelectorAll('.weapon-card');
            
            weaponCards.forEach(card => {
                // Récupérer le nom de l'arme dans la carte
                const weaponName = card.querySelector('h3').textContent.toLowerCase();
                
                // Récupérer le type d'arme (si disponible)
                const weaponType = card.getAttribute('data-type');
                
                // Récupérer les descriptions et accessoires si nécessaire
                const descriptions = Array.from(card.querySelectorAll('.text-sm p')).map(p => p.textContent.toLowerCase());
                
                // Vérifier si la description de l'arme existe
                const descriptionElement = card.querySelector('.text-sm.bg-gray-800 p');
                const description = descriptionElement ? descriptionElement.textContent.toLowerCase() : '';
                
                // Ajouter la description au tableau descriptions
                if (description) {
                    descriptions.push(description);
                }
                
                // Vérifier si le terme de recherche correspond à l'un des éléments
                const matchesSearch = 
                    weaponName.includes(searchTerm) || 
                    descriptions.some(desc => desc.includes(searchTerm));
                
                // Afficher ou masquer la carte en fonction du résultat de la recherche
                if (matchesSearch) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Afficher un message si aucune arme ne correspond à la recherche
            const visibleCards = document.querySelectorAll('.weapon-card[style="display: none;"]').length;
            const totalCards = document.querySelectorAll('.weapon-card').length;
            
            // Si un élément de message existe déjà, on le supprime pour éviter les doublons
            const existingMessage = document.getElementById('no-weapons-message');
            if (existingMessage) {
                existingMessage.remove();
            }
            
            // Si aucune arme ne correspond, afficher un message
            if (visibleCards === totalCards && searchTerm !== '') {
                const weaponsContainer = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
                const message = document.createElement('div');
                message.id = 'no-weapons-message';
                message.className = 'col-span-3 text-center p-10';
                message.innerHTML = `<p>Aucune arme ne correspond à votre recherche "${searchTerm}"</p>`;
                weaponsContainer.appendChild(message);
            }
        });
        
        // Réinitialiser la recherche lorsque l'utilisateur clique sur le X (bouton de réinitialisation)
        searchInput.addEventListener('search', function() {
            if (this.value === '') {
                const weaponCards = document.querySelectorAll('.weapon-card');
                
                weaponCards.forEach(card => {
                    card.style.display = '';
                });
                
                // Supprimer le message s'il existe
                const message = document.getElementById('no-weapons-message');
                if (message) {
                    message.remove();
                }
            }
        });
    }
});
