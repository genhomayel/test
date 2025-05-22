/**
 * Script principal pour le panneau d'administration StachZer
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des notifications toast
    initToasts();
    
    // Initialisation des modals
    initModals();
    
    // Gestion des confirmations de suppression
    initDeleteConfirmations();
    
    // Gestion de la pagination des tableaux
    initTablePagination();
    
    // Gestion des aperçus d'images
    initImagePreviews();
});

/**
 * Initialisation des notifications toast
 */
function initToasts() {
    // Fermeture automatique des notifications
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        // Fermeture automatique après 5 secondes
        if (!toast.classList.contains('persistent')) {
            setTimeout(() => {
                closeToast(toast);
            }, 5000);
        }
        
        // Bouton de fermeture
        const closeButton = toast.querySelector('.toast-close');
        if (closeButton) {
            closeButton.addEventListener('click', () => {
                closeToast(toast);
            });
        }
    });
}

/**
 * Ferme une notification toast
 */
function closeToast(toast) {
    toast.style.transform = 'translateX(110%)';
    setTimeout(() => {
        toast.remove();
    }, 300);
}

/**
 * Affiche une notification toast
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type} slide-in-right`;
    toast.innerHTML = `
        <div class="flex justify-between items-center">
            <div>${message}</div>
            <button class="toast-close ml-4 text-white">&times;</button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Affichage avec animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Initialisation du bouton de fermeture
    const closeButton = toast.querySelector('.toast-close');
    closeButton.addEventListener('click', () => {
        closeToast(toast);
    });
    
    // Fermeture automatique après 5 secondes
    setTimeout(() => {
        closeToast(toast);
    }, 5000);
}

/**
 * Initialisation des modals
 */
function initModals() {
    // Boutons d'ouverture
    const openButtons = document.querySelectorAll('[data-modal-target]');
    openButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-modal-target');
            const modal = document.getElementById(targetId);
            openModal(modal);
        });
    });
    
    // Boutons de fermeture
    const closeButtons = document.querySelectorAll('[data-modal-close]');
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalOverlay = button.closest('.modal-overlay');
            closeModal(modalOverlay);
        });
    });
    
    // Fermeture en cliquant en dehors de la modal
    const modalOverlays = document.querySelectorAll('.modal-overlay');
    modalOverlays.forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeModal(overlay);
            }
        });
    });
}

/**
 * Ouvre une modal
 */
function openModal(modal) {
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Ferme une modal
 */
function closeModal(modalOverlay) {
    if (modalOverlay) {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
}

/**
 * Initialisation des confirmations de suppression
 */
function initDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll('.delete-confirm');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            
            const confirmText = button.getAttribute('data-confirm-text') || 'Êtes-vous sûr de vouloir supprimer cet élément ?';
            const url = button.getAttribute('href');
            
            if (confirm(confirmText)) {
                window.location.href = url;
            }
        });
    });
}

/**
 * Initialisation de la pagination des tableaux
 */
function initTablePagination() {
    const tables = document.querySelectorAll('.paginated-table');
    
    tables.forEach(table => {
        const itemsPerPage = parseInt(table.getAttribute('data-items-per-page')) || 10;
        const rows = table.querySelectorAll('tbody tr');
        const totalPages = Math.ceil(rows.length / itemsPerPage);
        
        if (totalPages <= 1) {
            return; // Pas besoin de pagination
        }
        
        let currentPage = 1;
        
        // Créer la pagination
        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'pagination mt-4';
        
        // Bouton précédent
        const prevButton = document.createElement('div');
        prevButton.className = 'pagination-item';
        prevButton.textContent = '«';
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                goToPage(currentPage - 1);
            }
        });
        paginationContainer.appendChild(prevButton);
        
        // Pages numérotées
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('div');
            pageButton.className = 'pagination-item';
            if (i === 1) {
                pageButton.classList.add('active');
            }
            pageButton.textContent = i;
            pageButton.addEventListener('click', () => {
                goToPage(i);
            });
            paginationContainer.appendChild(pageButton);
        }
        
        // Bouton suivant
        const nextButton = document.createElement('div');
        nextButton.className = 'pagination-item';
        nextButton.textContent = '»';
        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                goToPage(currentPage + 1);
            }
        });
        paginationContainer.appendChild(nextButton);
        
        // Ajouter la pagination après le tableau
        table.parentNode.insertBefore(paginationContainer, table.nextSibling);
        
        // Afficher la première page
        goToPage(1);
        
        // Fonction pour aller à une page spécifique
        function goToPage(page) {
            currentPage = page;
            
            // Cacher toutes les lignes
            rows.forEach(row => {
                row.style.display = 'none';
            });
            
            // Afficher les lignes de la page actuelle
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            
            for (let i = startIndex; i < endIndex && i < rows.length; i++) {
                rows[i].style.display = '';
            }
            
            // Mettre à jour les boutons de pagination
            const pageButtons = paginationContainer.querySelectorAll('.pagination-item');
            pageButtons.forEach((button, index) => {
                if (index === 0) {
                    // Bouton précédent
                    button.classList.toggle('text-gray-500', currentPage === 1);
                } else if (index === pageButtons.length - 1) {
                    // Bouton suivant
                    button.classList.toggle('text-gray-500', currentPage === totalPages);
                } else {
                    // Boutons numérotés
                    if (index === currentPage) {
                        button.classList.add('active');
                    } else {
                        button.classList.remove('active');
                    }
                }
            });
        }
    });
}

/**
 * Initialisation des aperçus d'images
 */
function initImagePreviews() {
    const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');
    
    fileInputs.forEach(input => {
        const previewId = input.getAttribute('data-preview');
        const previewElement = document.getElementById(previewId);
        
        if (previewElement) {
            input.addEventListener('change', () => {
                const file = input.files[0];
                
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = (e) => {
                        if (previewElement.tagName === 'IMG') {
                            previewElement.src = e.target.result;
                        } else {
                            previewElement.style.backgroundImage = `url('${e.target.result}')`;
                        }
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        }
    });
}

/**
 * Fonction pour formater un nombre
 */
function formatNumber(number) {
    return new Intl.NumberFormat().format(number);
}
