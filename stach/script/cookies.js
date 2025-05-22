document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'utilisateur a déjà fait un choix
    const cookieConsent = localStorage.getItem('cookie_consent');
    
    if (!cookieConsent) {
        // Afficher la bannière après un court délai
        setTimeout(() => {
            const cookieBanner = document.getElementById('cookie-banner');
            cookieBanner.classList.remove('translate-y-full');
        }, 1500);
    }
    
    // Gérer le clic sur "Accepter"
    document.getElementById('accept-cookies').addEventListener('click', function() {
        localStorage.setItem('cookie_consent', 'all');
        hideCookieBanner();
    });
    
    // Gérer le clic sur "Essentiels uniquement"
    document.getElementById('essential-only-cookies').addEventListener('click', function() {
        localStorage.setItem('cookie_consent', 'essential');
        hideCookieBanner();
    });
    
    function hideCookieBanner() {
        const cookieBanner = document.getElementById('cookie-banner');
        cookieBanner.classList.add('translate-y-full');
        
        // Suppression de l'élément après l'animation
        setTimeout(() => {
            cookieBanner.remove();
        }, 300);
    }
});
