document.addEventListener('DOMContentLoaded', function() {
    // Intercepter les clics sur les liens de navigation
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Empêcher la navigation par défaut
            const section = this.getAttribute('data-section');
            changePage(section);
            
            // Mettre à jour l'URL sans recharger la page (pour les moteurs de recherche et l'historique)
            history.pushState({section: section}, "", this.getAttribute('href'));
        });
    });
    
    // Gérer la navigation avec les boutons précédent/suivant du navigateur
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.section) {
            changePage(e.state.section);
        }
    });
    
    // Vérifier l'URL à l'initialisation pour charger la bonne section
    const path = window.location.pathname;
    const section = path.substring(1) || 'accueil'; // Enlever le slash initial ou utiliser 'accueil' par défaut
    
    if (document.getElementById(section)) {
        changePage(section);
    }
});

function changePage(pageId) {
    // Masquer toutes les pages
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });
    
    // Afficher la page sélectionnée
    document.getElementById(pageId).classList.add('active');
    
    // Mettre à jour l'élément de navigation actif
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Trouver tous les éléments de navigation qui correspondent à l'identifiant de la page
    document.querySelectorAll('.nav-item').forEach(item => {
        if (item.textContent.toLowerCase().includes(pageId) || 
            (pageId === 'accueil' && item.textContent.includes('Accueil'))) {
            item.classList.add('active');
        }
    });
    
    // Remonter en haut de page
    window.scrollTo(0, 0);
    
    // Fermer le menu mobile s'il est ouvert
    document.getElementById('mobile-menu').classList.add('hidden');
    
    // Enregistrer la vue de la page
    fetch('includes/record-view.php?page=' + pageId, {
        method: 'GET',
        credentials: 'same-origin'
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Gestion du menu mobile
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
    
    // Animation au scroll pour les éléments
    window.addEventListener('scroll', () => {
        const animatedElements = document.querySelectorAll('.card, .strategy-card');
        
        animatedElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementTop < windowHeight * 0.9) {
                element.classList.add('animate-fadeInUp');
            }
        });
    });
});
