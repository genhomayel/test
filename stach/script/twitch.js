document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si un état est déjà enregistré dans le stockage local
    const savedStatus = localStorage.getItem('twitch_status');
    if (savedStatus === 'offline') {
        updateStatusDisplay(false);
    }
    
    // Permettre de changer le statut en double-cliquant sur le conteneur (admin uniquement)
    const statusContainer = document.querySelector('.stream-status-container');
    if (statusContainer) {
        statusContainer.addEventListener('dblclick', function() {
            const isLive = statusContainer.querySelector('.bg-red-600') !== null;
            
            if (confirm(isLive ? 
                'Changer le statut en HORS LIGNE ?' : 
                'Changer le statut en EN DIRECT ?')) {
                updateStatusDisplay(!isLive);
                localStorage.setItem('twitch_status', isLive ? 'offline' : 'live');
            }
        });
    }
});

function updateStatusDisplay(isLive) {
    const statusContainer = document.querySelector('.stream-status-container');
    if (!statusContainer) return;
    
    if (isLive) {
        // Afficher "EN DIRECT"
        statusContainer.innerHTML = `
            <div class="bg-red-600 animate-pulse w-3 h-3 rounded-full mr-2"></div>
            <a href="https://www.twitch.tv/stachzer" target="_blank" class="text-sm font-medium hover:text-pink-300 transition">EN DIRECT</a>
        `;
    } else {
        // Afficher "HORS LIGNE"
        statusContainer.innerHTML = `
            <div class="bg-gray-600 w-3 h-3 rounded-full mr-2"></div>
            <a href="https://www.twitch.tv/stachzer" target="_blank" class="text-sm text-gray-400 hover:text-pink-300 transition">HORS LIGNE</a>
        `;
    }
}
