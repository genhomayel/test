<?php
// login.php - Page de connexion
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Redirection si déjà connecté
if (isLoggedIn()) {
    header("Location: gestion.php");
    exit;
}

$error = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($username) || empty($password)) {
        $error = 'Veuillez entrer un nom d\'utilisateur et un mot de passe.';
    } else {
        if (login($username, $password)) {
            header("Location: gestion.php");
            exit;
        } else {
            $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - StachZer Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="bg-gray-900 text-white">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-xl shadow-lg max-w-md w-full">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold">
                    <span class="text-white">Stach</span><span class="text-pink-300">Zer</span>
                </h1>
                <p class="text-gray-400">Panneau d'administration</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-300 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="login.php">
                <div class="mb-4">
                    <label for="username" class="block text-gray-300 mb-2">Nom d'utilisateur</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-user text-gray-500"></i>
                        </span>
                        <input type="text" id="username" name="username" class="bg-gray-700 border border-gray-600 text-white pl-10 py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-300 mb-2">Mot de passe</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-lock text-gray-500"></i>
                        </span>
                        <input type="password" id="password" name="password" class="bg-gray-700 border border-gray-600 text-white pl-10 py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 py-2 px-4 rounded-md font-semibold hover:opacity-90 transition">
                    CONNEXION
                </button>
            </form>
            
            <div class="mt-4 text-center text-sm text-gray-400">
                © <?php echo date('Y'); ?> StachZer.com - Tous droits réservés
            </div>
        </div>
    </div>
    
    <script src="assets/js/admin.js"></script>
</body>
</html>