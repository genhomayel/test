<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Vérification de l'authentification
requireLogin();

// Suppression d'un highlight
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Vérifier si le highlight existe
    $highlight = fetchOne("SELECT id FROM community_highlights WHERE id = ?", [$id]);
    
    if ($highlight) {
        // Supprimer le highlight
        deleteData('community_highlights', ['id' => $id]);
        
        // Message de succès
        $success = 'Le highlight a été supprimé avec succès.';
    }
}

// Récupération des highlights
$highlights = fetchAll("SELECT * FROM community_highlights ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Highlights - StachZer Admin</title>
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
            display: inline-block;
        }
        
        .btn-secondary {
            background: #4B5563;
            color: white;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            display: inline-block;
        }
        
        .btn-danger {
            background: #EF4444;
            color: white;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            display: inline-block;
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
                <a href="../gestion.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>Tableau de bord</span>
                </a>
                
                <div class="px-4 py-2 text-xs uppercase text-gray-500 mt-4">Arsenal</div>
                
                <a href="../weapons/index.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-gun w-6"></i>
                    <span>Toutes les armes</span>
                </a>
                
                <a href="../weapons/add.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-plus-circle w-6"></i>
                    <span>Ajouter une arme</span>
                </a>
                
                <a href="../weapons/types.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-tags w-6"></i>
                    <span>Types & Étiquettes</span>
                </a>
                
                <div class="px-4 py-2 text-xs uppercase text-gray-500 mt-4">Communauté</div>
                
                <a href="index.php" class="flex items-center px-4 py-2 text-pink-300 bg-gray-900">
                    <i class="fas fa-film w-6"></i>
                    <span>Highlights</span>
                </a>
                
                <a href="add.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-plus-circle w-6"></i>
                    <span>Ajouter un highlight</span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-gray-700">
                <a href="../logout.php" class="flex items-center text-red-400 hover:text-red-300">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </div>
        
        <!-- Main content -->
        <div class="flex-grow overflow-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Highlights communautaires</h1>
                    <a href="add.php" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i> Ajouter un highlight
                    </a>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-300 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($highlights)): ?>
                    <div class="bg-gray-800 rounded-lg p-8 text-center">
                        <i class="fas fa-film text-4xl text-gray-600 mb-4"></i>
                        <h2 class="text-2xl font-semibold mb-2">Aucun highlight</h2>
                        <p class="text-gray-400 mb-6">Commencez par ajouter des highlights pour les afficher ici.</p>
                        <a href="add.php" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i> Ajouter votre premier highlight
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($highlights as $highlight): ?>
                            <div class="bg-gray-800 rounded-lg overflow-hidden">
                                <div class="relative">
                                    <?php if (!empty($highlight['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($highlight['image_url']); ?>" alt="<?php echo htmlspecialchars($highlight['title']); ?>" class="w-full h-48 object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-48 bg-gray-700 flex items-center justify-center">
                                            <i class="fas fa-film text-4xl text-gray-600"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-gray-900 to-transparent">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full mr-2 flex items-center justify-center text-xs font-bold" style="background-color: <?php echo htmlspecialchars($highlight['color_code']); ?>;">
                                                <?php echo htmlspecialchars($highlight['author_initials']); ?>
                                            </div>
                                            <span class="font-medium text-white"><?php echo htmlspecialchars($highlight['author_name']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($highlight['title']); ?></h3>
                                    
                                    <div class="flex justify-between items-center">
                                        <div class="text-gray-400 text-sm">
                                            <?php echo date('d/m/Y', strtotime($highlight['created_at'])); ?>
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <a href="edit.php?id=<?php echo $highlight['id']; ?>" class="text-blue-400 hover:text-blue-300">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <a href="index.php?delete=<?php echo $highlight['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce highlight ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
