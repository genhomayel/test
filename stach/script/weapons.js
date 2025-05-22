document.addEventListener('DOMContentLoaded', function() {
    // Filtrage des armes par type
    const filterButtons = document.querySelectorAll('.filter-button');
    const weaponCards = document.querySelectorAll('.weapon-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Supprimer la classe active de tous les boutons
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-gradient-to-r', 'from-pink-300', 'to-blue-300');
                btn.classList.add('bg-gray-800');
            });
            
            // Ajouter la classe active au bouton cliqué
            button.classList.add('active', 'bg-gradient-to-r', 'from-pink-300', 'to-blue-300');
            button.classList.remove('bg-gray-800');
            
            const typeId = button.getAttribute('data-type');
            weaponCards.forEach(card => {
                if (!typeId || typeId === 'all' || card.getAttribute('data-type') === typeId) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Gestion des détails des armes
    const detailButtons = document.querySelectorAll('.view-details-btn');
    const detailModal = document.getElementById('weapon-detail-modal');
    const zoomModal = document.getElementById('image-zoom-modal');
    
    // Ouvrir la modal de détails
    detailButtons.forEach(button => {
        button.addEventListener('click', () => {
            const weaponId = button.getAttribute('data-weapon-id');
            openWeaponDetails(weaponId);
        });
    });
    
    // Fermer les modals
    document.querySelectorAll('.close-modal, .modal-overlay').forEach(element => {
        element.addEventListener('click', () => {
            detailModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    });
    
    document.querySelectorAll('.close-zoom, #image-zoom-modal .modal-overlay').forEach(element => {
        element.addEventListener('click', () => {
            zoomModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    });
    
    // Empêcher la propagation du clic sur le contenu des modals
    document.querySelector('.weapon-modal-content').addEventListener('click', e => {
        e.stopPropagation();
    });
    
    document.querySelector('#zoomed-image').addEventListener('click', e => {
        e.stopPropagation();
    });
    
    // Permettre le zoom sur l'image
    document.getElementById('weapon-detail-image').addEventListener('click', () => {
        const imageUrl = document.getElementById('weapon-detail-image').src;
        document.getElementById('zoomed-image').src = imageUrl;
        zoomModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    });
    
    // Fonction pour ouvrir la modal de détails
    function openWeaponDetails(weaponId) {
        const weapon = weaponsData[weaponId];
        if (!weapon) return;
        
        // Remplir les informations
        document.querySelector('.weapon-detail-name').textContent = weapon.name;
        document.getElementById('weapon-detail-image').src = weapon.image_url;
        document.getElementById('weapon-detail-image').alt = weapon.name;
        
        // Stats
        document.querySelector('.weapon-damage').style.width = weapon.damage + '%';
        document.querySelector('.weapon-accuracy').style.width = weapon.accuracy + '%';
        document.querySelector('.weapon-mobility').style.width = weapon.mobility + '%';
        document.querySelector('.weapon-control').style.width = weapon.control + '%';
        
        document.querySelector('.weapon-damage-value').textContent = weapon.damage + '%';
        document.querySelector('.weapon-accuracy-value').textContent = weapon.accuracy + '%';
        document.querySelector('.weapon-mobility-value').textContent = weapon.mobility + '%';
        document.querySelector('.weapon-control-value').textContent = weapon.control + '%';
        
        // META
        const metaContainer = document.querySelector('.weapon-detail-meta');
        metaContainer.innerHTML = '';
        if (weapon.is_meta) {
            metaContainer.innerHTML = '<span class="px-3 py-1 bg-green-500 text-xs font-bold rounded-full text-black">META</span>';
        }
        
        // Tags
        const tagsContainer = document.querySelector('.weapon-detail-tags');
        tagsContainer.innerHTML = '';
        if (weapon.tags && weapon.tags.length > 0) {
            weapon.tags.forEach(tag => {
                const tagEl = document.createElement('div');
                tagEl.className = 'weapon-tag px-2 py-1 text-xs font-bold text-white rounded-l-md';
                tagEl.style.backgroundColor = tag.color;
                tagEl.textContent = tag.name;
                tagsContainer.appendChild(tagEl);
            });
        }
        
        // Attachments
        const attachmentsContainer = document.querySelector('.weapon-attachments');
        attachmentsContainer.innerHTML = '';
        
        if (weapon.attachment1) {
            attachmentsContainer.innerHTML += `<p><span class="font-semibold">Canon:</span> ${weapon.attachment1}</p>`;
        }
        if (weapon.attachment2) {
            attachmentsContainer.innerHTML += `<p><span class="font-semibold">Lunette:</span> ${weapon.attachment2}</p>`;
        }
        if (weapon.attachment3) {
            attachmentsContainer.innerHTML += `<p><span class="font-semibold">Crosse:</span> ${weapon.attachment3}</p>`;
        }
        if (weapon.attachment4) {
            attachmentsContainer.innerHTML += `<p><span class="font-semibold">Poignée:</span> ${weapon.attachment4}</p>`;
        }
        if (weapon.attachment5) {
            attachmentsContainer.innerHTML += `<p><span class="font-semibold">Munitions:</span> ${weapon.attachment5}</p>`;
        }
        
        // Description
        const descContainer = document.querySelector('.weapon-description-container');
        const descText = document.querySelector('.weapon-description');
        
        if (weapon.description) {
            descText.textContent = weapon.description;
            descContainer.classList.remove('hidden');
        } else {
            descContainer.classList.add('hidden');
        }
        
        // Afficher la modal
        detailModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
});
