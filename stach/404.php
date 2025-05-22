<?php
// Récupérer les fichiers nécessaires si disponibles
$includesExist = file_exists('includes/config.php');
if ($includesExist) {
    require_once 'includes/config.php';
    require_once 'includes/db.php';
    require_once 'includes/functions.php';
}

// Définir le code d'état HTTP 404
http_response_code(404);

// URL demandée
$requestedUrl = htmlspecialchars($_SERVER['REQUEST_URI']);

// Récupérer le referer si disponible
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page introuvable - StachZer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-light: #FFD6E0;
            --white: #FFFFFF;
            --blue-light: #D6E5FF;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #0f1923;
            color: var(--white);
            overflow-x: hidden;
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--pink-light), var(--blue-light));
            color: #0f1923;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .error-heading {
            font-size: 8rem;
            background: linear-gradient(to right, var(--pink-light), var(--blue-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-opacity-90 bg-gray-900 backdrop-filter backdrop-blur-lg border-b border-gray-800">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-extrabold mr-6">
                        <span class="text-white">Stach</span><span style="color: var(--pink-light)">Zer</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center pt-16">
        <div class="container mx-auto px-4 py-20 text-center">
            <div class="max-w-md mx-auto">
                <h1 class="error-heading">404</h1>
                <h2 class="text-2xl font-bold mb-6">Page introuvable</h2>
                <p class="mb-6 opacity-80">La page que vous recherchez n'existe pas ou a été déplacée.</p>
                <div class="mb-8 p-4 bg-gray-800 rounded-lg text-left">
                    <p class="text-sm opacity-70"><i class="fas fa-exclamation-triangle text-pink-300 mr-2"></i> URL demandée : <?php echo $requestedUrl; ?></p>
                </div>
                <div class="flex justify-center gap-4">
                    <a href="/" class="btn-primary"><i class="fas fa-home mr-2"></i> Accueil</a>
                    <?php if (!empty($referer)): ?>
                        <a href="<?php echo htmlspecialchars($referer); ?>" class="px-6 py-3 border border-white rounded-lg hover:bg-white hover:text-gray-900 transition duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 py-8 border-t border-gray-800">
        <div class="container mx-auto px-4 text-center">
            <div class="text-sm opacity-70">
                © <?php echo date('Y'); ?> StachZer.com. Tous droits réservés.
            </div>
        </div>
    </footer>
</body>
</html>
