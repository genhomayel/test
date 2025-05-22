<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Vérification de l'authentification
requireLogin();

$success = '';
$error = '';
$user = [];

// Récupérer les informations de l'utilisateur
$userId = $_SESSION['user_id'];
$user = fetchOne("SELECT id, username, email FROM users WHERE id = ?", [$userId]);

// Traitement du formulaire pour changer le mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Vérifier le mot de passe actuel
    $userAuth = fetchOne("SELECT password FROM users WHERE id = ?", [$userId]);
    
    if (!$userAuth || !password_verify($currentPassword, $userAuth['password'])) {
        $error = 'Le mot de passe actuel est incorrect.';
    } elseif (strlen($newPassword) < 8) {
        $error = 'Le nouveau mot de passe doit contenir au moins 8 caractères.';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'La confirmation du mot de passe ne correspond pas.';
    } else {
        // Hacher le nouveau mot de passe
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Mettre à jour le mot de passe
        $updateResult = updateData('users', ['password' => $hashedPassword], ['id' => $userId]);
        
        if ($updateResult) {
            $success = 'Votre mot de passe a été mis à jour avec succès.';
        } else {
            $error = 'Une erreur est survenue lors de la mise à jour du mot de passe.';
        }
    }
}

// Traitement du formulaire pour mettre à jour le profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    
    // Validation
    if (empty($email)) {
        $error = 'L\'adresse email est obligatoire.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'L\'adresse email n\'est pas valide.';
    } else {
        // Vérifier si l'email existe déjà (pour un autre utilisateur)
        $existingEmail = fetchOne("SELECT id FROM users WHERE email = ? AND id != ?", [$email, $userId]);
        
        if ($existingEmail) {
            $error = 'Cette adresse email est déjà utilisée par un autre compte.';
        } else {
            // Mettre à jour l'email
            $updateResult = updateData('users', ['email' => $email], ['id' => $userId]);
            
            if ($updateResult) {
                $success = 'Votre profil a été mis à jour avec succès.';
                
                // Récupérer les informations mises à jour
                $user = fetchOne("SELECT id, username, email FROM users WHERE id = ?", [$userId]);
            } else {
                $error = 'Une erreur est survenue lors de la mise à jour du profil.';
            }
        }
    }
}

// Vérifier la configuration du site
$uploadsDir = dirname(dirname(__FILE__)) . '/uploads';
$uploadsDirExists = file_exists($uploadsDir);
$uploadsDirWritable = is_writable($uploadsDir);

// Vérifier l'extension GD
$gdEnabled = extension_loaded('gd');

// Vérifier la version PHP
$phpVersion = phpversion();
$phpVersionOk = version_compare($phpVersion, '7.2.0', '>=');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - StachZer Admin</title>
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
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--pink-light), var(--blue-light));
            color: #0f1923;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .settings-tab.active {
            background-color: var(--pink-light) !important;
            color: #1F2937 !important;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 h-full flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <div class="text-2xl font-bold">
                    <span class="text-white">Stach</span><span style="color: var(--pink-light)">Zer</span>
                </div>
                <div class="text-sm text-gray-400">Panneau d'administration</div>
            </div>
            
            <nav class="flex-grow py-4">
                <a href="gestion.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>Tableau de bord</span>
                </a>
                
                <div class="px-4 py-2 text-xs uppercase text-gray-500 mt-4">Arsenal</div>
                
                <a href="weapons/index.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-gun w-6"></i>
                    <span>Toutes les armes</span>
                </a>
                
                <a href="weapons/add.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-plus-circle w-6"></i>
                    <span>Ajouter une arme</span>
                </a>
                
                <a href="weapons/types.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-tags w-6"></i>
                    <span>Types & Étiquettes</span>
                </a>
                
                <div class="px-4 py-2 text-xs uppercase text-gray-500 mt-4">Communauté</div>
                
                <a href="highlights/index.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-film w-6"></i>
                    <span>Highlights</span>
                </a>
                
                <a href="highlights/add.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-plus-circle w-6"></i>
                    <span>Ajouter un highlight</span>
                </a>
                
                <div class="px-4 py-2 text-xs uppercase text-gray-500 mt-4">Système</div>
                
                <a href="stats.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-chart-line w-6"></i>
                    <span>Statistiques</span>
                </a>
                
                <a href="settings.php" class="flex items-center px-4 py-2 bg-gray-900 text-pink-300">
                    <i class="fas fa-cog w-6"></i>
                    <span>Paramètres</span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-gray-700">
                <a href="logout.php" class="flex items-center text-red-400 hover:text-red-300">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </div>
        
        <!-- Main content -->
        <div class="flex-grow overflow-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Paramètres</h1>
                </div>
                
                <?php if (!empty($success)): ?>
                    <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-300 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-300 px-4 py-3 rounded mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Tabs -->
                <div class="mb-6 flex border-b border-gray-700">
                    <button id="tab-profile" class="settings-tab px-4 py-2 active">Profil</button>
                    <button id="tab-password" class="settings-tab px-4 py-2">Mot de passe</button>
                    <button id="tab-system" class="settings-tab px-4 py-2">Système</button>
                </div>
                
                <!-- Tab content - Profile -->
                <div id="content-profile" class="tab-content">
                    <div class="bg-gray-800 rounded-lg p-6">
                        <form method="post" action="settings.php">
                            <input type="hidden" name="action" value="update_profile">
                            
                            <div class="mb-4">
                                <label class="block text-gray-300 mb-2">Nom d'utilisateur</label>
                                <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full" readonly>
                                <p class="text-xs text-gray-400 mt-1">Le nom d'utilisateur ne peut pas être modifié.</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="block text-gray-300 mb-2">Adresse email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="submit" class="bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 py-2 px-6 rounded-md font-semibold hover:opacity-90 transition">
                                    Mettre à jour le profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Tab content - Password -->
                <div id="content-password" class="tab-content hidden">
                    <div class="bg-gray-800 rounded-lg p-6">
                        <form method="post" action="settings.php">
                            <input type="hidden" name="action" value="change_password">
                            
                            <div class="mb-4">
                                <label for="current_password" class="block text-gray-300 mb-2">Mot de passe actuel</label>
                                <input type="password" id="current_password" name="current_password" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="new_password" class="block text-gray-300 mb-2">Nouveau mot de passe</label>
                                <input type="password" id="new_password" name="new_password" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                                <p class="text-xs text-gray-400 mt-1">Minimum 8 caractères.</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirm_password" class="block text-gray-300 mb-2">Confirmer le mot de passe</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="submit" class="bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 py-2 px-6 rounded-md font-semibold hover:opacity-90 transition">
                                    Changer le mot de passe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Tab content - System -->
                <div id="content-system" class="tab-content hidden">
                    <div class="bg-gray-800 rounded-lg overflow-hidden">
                        <div class="bg-gray-700 px-4 py-3">
                            <h2 class="font-semibold">Information système</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between border-b border-gray-700 pb-2">
                                    <div class="font-medium">Version PHP</div>
                                    <div class="flex items-center">
                                        <span class="mr-2"><?php echo $phpVersion; ?></span>
                                        <?php if ($phpVersionOk): ?>
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        <?php else: ?>
                                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                                                <div class="flex items-center justify-between border-b border-gray-700 pb-2">
                                    <div class="font-medium">Extension GD</div>
                                    <div class="flex items-center">
                                        <?php if ($gdEnabled): ?>
                                            <span class="mr-2">Activée</span>
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        <?php else: ?>
                                            <span class="mr-2">Désactivée</span>
                                            <i class="fas fa-times-circle text-red-500"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between border-b border-gray-700 pb-2">
                                    <div class="font-medium">Dossier uploads</div>
                                    <div class="flex items-center">
                                        <?php if ($uploadsDirExists): ?>
                                            <?php if ($uploadsDirWritable): ?>
                                                <span class="mr-2">Existant et accessible</span>
                                                <i class="fas fa-check-circle text-green-500"></i>
                                            <?php else: ?>
                                                <span class="mr-2">Existe mais n'est pas accessible en écriture</span>
                                                <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="mr-2">N'existe pas</span>
                                            <i class="fas fa-times-circle text-red-500"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between border-b border-gray-700 pb-2">
                                    <div class="font-medium">Limite de téléchargement</div>
                                    <div>
                                        <?php echo ini_get('upload_max_filesize'); ?>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between border-b border-gray-700 pb-2">
                                    <div class="font-medium">Limite post_max_size</div>
                                    <div>
                                        <?php echo ini_get('post_max_size'); ?>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between border-b border-gray-700 pb-2">
                                    <div class="font-medium">Limite memory_limit</div>
                                    <div>
                                        <?php echo ini_get('memory_limit'); ?>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between pb-2">
                                    <div class="font-medium">Version du panel</div>
                                    <div>
                                        <span class="bg-gradient-to-r from-pink-300 to-blue-300 text-transparent bg-clip-text font-bold">1.0.0</span>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!$uploadsDirExists): ?>
                                <div class="mt-4 bg-yellow-500 bg-opacity-20 border border-yellow-500 text-yellow-300 px-4 py-3 rounded">
                                    <p class="font-medium">Le dossier uploads n'existe pas.</p>
                                    <p class="mt-2">Créez le dossier "uploads" à la racine de votre site et assurez-vous qu'il dispose des permissions en écriture (chmod 775).</p>
                                    <form method="post" action="settings.php" class="mt-3">
                                        <input type="hidden" name="action" value="create_uploads_dir">
                                        <button type="submit" class="bg-yellow-500 text-gray-900 py-1 px-3 rounded-md text-sm font-semibold">
                                            Créer le dossier uploads
                                        </button>
                                    </form>
                                </div>
                            <?php elseif (!$uploadsDirWritable): ?>
                                <div class="mt-4 bg-yellow-500 bg-opacity-20 border border-yellow-500 text-yellow-300 px-4 py-3 rounded">
                                    <p class="font-medium">Le dossier uploads existe mais n'est pas accessible en écriture.</p>
                                    <p class="mt-2">Modifiez les permissions du dossier "uploads" pour lui donner les droits en écriture (chmod 775).</p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!$gdEnabled): ?>
                                <div class="mt-4 bg-yellow-500 bg-opacity-20 border border-yellow-500 text-yellow-300 px-4 py-3 rounded">
                                    <p class="font-medium">L'extension GD n'est pas activée.</p>
                                    <p class="mt-2">Cette extension est nécessaire pour le traitement des images. Demandez à votre hébergeur d'activer l'extension GD sur votre serveur.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 rounded-lg overflow-hidden mt-6">
                        <div class="bg-gray-700 px-4 py-3">
                            <h2 class="font-semibold">Maintenance</h2>
                        </div>
                        <div class="p-6">
                            <div class="mb-6">
                                <h3 class="font-medium text-lg mb-2">Nettoyage des statistiques</h3>
                                <p class="text-gray-400 mb-4">Supprime les statistiques de visites anciennes pour optimiser la base de données.</p>
                                <form method="post" action="settings.php" class="flex flex-wrap gap-2">
                                    <input type="hidden" name="action" value="clean_stats">
                                    <button type="submit" name="clean_period" value="month" class="bg-gray-700 hover:bg-gray-600 text-white py-1 px-3 rounded-md text-sm">
                                        Supprimer > 1 mois
                                    </button>
                                    <button type="submit" name="clean_period" value="year" class="bg-gray-700 hover:bg-gray-600 text-white py-1 px-3 rounded-md text-sm">
                                        Supprimer > 1 an
                                    </button>
                                </form>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-lg mb-2">Sauvegarder la base de données</h3>
                                <p class="text-gray-400 mb-4">Créez une sauvegarde de votre base de données pour éviter la perte de données.</p>
                                <form method="post" action="settings.php">
                                    <input type="hidden" name="action" value="backup_database">
                                    <button type="submit" class="bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 py-2 px-4 rounded-md font-semibold">
                                        <i class="fas fa-download mr-2"></i> Télécharger la sauvegarde
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Tabs functionality
        const tabs = document.querySelectorAll('.settings-tab');
        const contents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active', 'bg-pink-300', 'text-gray-900'));
                
                // Add active class to clicked tab
                tab.classList.add('active', 'bg-pink-300', 'text-gray-900');
                
                // Hide all content
                contents.forEach(c => c.classList.add('hidden'));
                
                // Show corresponding content
                const contentId = tab.id.replace('tab', 'content');
                document.getElementById(contentId).classList.remove('hidden');
            });
        });
    </script>
</body>
</html>
