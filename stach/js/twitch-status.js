/**
 * Solution ultra simple pour vérifier si un streamer Twitch est en ligne
 * Cette version utilise une iframe cachée pour tester si la vidéo en direct existe
 */
document.addEventListener('DOMContentLoaded', function() {
    // Attendre un peu avant de vérifier
    setTimeout(checkStreamWithIframe, 2000);
});

function checkStreamWithIframe() {
    const username = 'stachzer';
    
    // Créer une iframe invisible qui pointe vers le flux en direct
    const iframe = document.createElement('iframe');
    iframe.style.width = '1px';
    iframe.style.height = '1px';
    iframe.style.position = 'absolute';
    iframe.style.opacity = '0';
    iframe.style.visibility = 'hidden';
    iframe.style.pointerEvents = 'none';
    
    // URL qui redirige vers le stream en direct si disponible
    iframe.src = `https://player.twitch.tv/?channel=${username}&parent=${window.location.hostname}&muted=true`;
    
    document.body.appendChild(iframe);
    
    // Vérifier l'état après chargement de l'iframe
    iframe.onload = function() {
        // Attendre un moment pour le chargement
        setTimeout(function() {
            try {
                // Si l'iframe est redirigée vers une page d'erreur, le stream est hors ligne
                const iframeLocation = iframe.contentWindow.location.href;
                
                // Si l'iframe reste sur twitch et contient "offline_embed", le stream est hors ligne
                if (iframeLocation.includes('twitch.tv') && 
                    (iframe.contentDocument && 
                     iframe.contentDocument.body && 
                     iframe.contentDocument.body.innerHTML.includes('offline_embed'))) {
                    updateStreamStatusToOffline();
                }
            } catch (e) {
                // Erreur d'accès à l'iframe, probablement à cause de restrictions CORS
                // Ne pas changer le statut pour conserver "EN DIRECT"
            } finally {
                // Supprimer l'iframe dans tous les cas
                document.body.removeChild(iframe);
            }
        }, 1500);
    };
    
    // Faire une vérification alternative en se basant sur le contenu de la page Twitch
    checkPageExists(`https://www.twitch.tv/${username}/videos?filter=archives&sort=time`);
    
    // Vérifier à nouveau après 5 minutes
    setTimeout(checkStreamWithIframe, 5 * 60 * 1000);
}

// Fonction auxiliaire pour vérifier si un stream récent existe
function checkPageExists(url) {
    // Créer un élément image avec une URL bidon basée sur l'URL réelle
    const img = new Image();
    const timestamp = new Date().getTime();
    
    img.onload = function() {
        // Si l'image se charge, la page existe donc le streamer est probablement en ligne
    };
    
    img.onerror = function() {
        // Si l'image ne se charge pas, vérifions si c'est à cause d'une erreur 404
        const xhr = new XMLHttpRequest();
        xhr.open('HEAD', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 404) {
                    // La page n'existe pas, le streamer est probablement hors ligne
                    updateStreamStatusToOffline();
                }
            }
        };
        xhr.send();
    };
    
    // Lancez le chargement de l'image
    img.src = url + '?_=' + timestamp;
}

function updateStreamStatusToOffline() {
    const statusContainer = document.querySelector('.stream-status-container');
    if (!statusContainer) return;
    
    statusContainer.innerHTML = `
        <div class="bg-gray-600 w-3 h-3 rounded-full mr-2"></div>
        <a href="https://www.twitch.tv/stachzer" target="_blank" class="text-sm text-gray-400 hover:text-pink-300 transition">HORS LIGNE</a>
    `;
}
