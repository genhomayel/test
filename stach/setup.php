<?php
// setup.php - Configuration initiale du système
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Vérification si le script a déjà été exécuté
$userCount = fetchOne("SELECT COUNT(*) as count FROM users")['count'];

if ($userCount > 0) {
    die("La configuration a déjà été effectuée. Pour des raisons de sécurité, ce script a été désactivé.");
}

$success = '';
$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    // Validation
    if (empty($username) || empty($password) || empty($confirmPassword) || empty($email)) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($password) < 8) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'L\'adresse email n\'est pas valide.';
    } else {
        // Créer l'utilisateur
        $result = registerUser($username, $password, $email);
        
        if ($result['success']) {
            $success = 'Configuration terminée avec succès ! Vous pouvez maintenant vous connecter.';
            
            // Créer des tables supplémentaires si nécessaires
            executeQuery("
                CREATE TABLE IF NOT EXISTS weapon_tags (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(50) NOT NULL,
                    slug VARCHAR(50) NOT NULL,
                    color VARCHAR(7) NOT NULL DEFAULT '#3B82F6',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            executeQuery("
                CREATE TABLE IF NOT EXISTS weapon_tag_relations (
                    weapon_id INT NOT NULL,
                    tag_id INT NOT NULL,
                    PRIMARY KEY (weapon_id, tag_id),
                    FOREIGN KEY (weapon_id) REFERENCES weapons(id) ON DELETE CASCADE,
                    FOREIGN KEY (tag_id) REFERENCES weapon_tags(id) ON DELETE CASCADE
                )
            ");
            
            executeQuery("
                CREATE TABLE IF NOT EXISTS page_views (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    page VARCHAR(255) NOT NULL,
                    ip_address VARCHAR(45) NOT NULL,
                    user_agent TEXT,
                    view_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            executeQuery("
                CREATE TABLE IF NOT EXISTS login_logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    login_time TIMESTAMP NULL,
                    logout_time TIMESTAMP NULL,
                    ip_address VARCHAR(45) NOT NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id)
                )
            ");
            
            // Ajouter des étiquettes par défaut
            executeQuery("INSERT INTO weapon_tags (name, slug, color) VALUES ('META', 'meta', '#10B981')");
            executeQuery("INSERT INTO weapon_tags (name, slug, color) VALUES ('ZERO RECUL', 'zero-recul', '#F59E0B')");
            executeQuery("INSERT INTO weapon_tags (name, slug, color) VALUES ('RECOMMANDÉ', 'recommande', '#3B82F6')");
            executeQuery("INSERT INTO weapon_tags (name, slug, color) VALUES ('NOUVEAU', 'nouveau', '#EC4899')");
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration - StachZer Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #0f1923;
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-xl shadow-lg max-w-md w-full">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold">
                    <span class="text-white">Stach</span><span class="text-pink-300">Zer</span>
                </h1>
                <p class="text-gray-400">Configuration initiale</p>
            </div>
            
            <?php if (!empty($success)): ?>
                <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-300 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                    <p class="mt-2">
                        <a href="admin/login.php" class="text-green-300 underline">Aller à la page de connexion</a>
                    </p>
                </div>
            <?php else: ?>
                <?php if (!empty($error)): ?>
                    <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-300 px-4 py-3 rounded mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <p class="mb-4 text-gray-300">
                    Ce script va créer un compte administrateur pour gérer votre site StachZer.com. Après l'exécution, ce script sera automatiquement désactivé pour des raisons de sécurité.
                </p>
                
                <form method="post" action="setup.php">
                    <div class="mb-4">
                        <label for="username" class="block text-gray-300 mb-2">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-300 mb-2">Adresse email</label>
                        <input type="email" id="email" name="email" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-gray-300 mb-2">Mot de passe</label>
                        <input type="password" id="password" name="password" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="confirm_password" class="block text-gray-300 mb-2">Confirmer le mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                    </div>
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 py-2 px-4 rounded-md font-semibold hover:opacity-90 transition">
                        CRÉER LE COMPTE ADMINISTRATEUR
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
