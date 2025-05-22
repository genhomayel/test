<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Vérification de l'authentification
requireLogin();

// Suppression d'une arme
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Vérifier si l'arme existe
    $weapon = fetchOne("SELECT id FROM weapons WHERE id = ?", [$id]);
    
    if ($weapon) {
        // Supprimer les relations avec les tags
        deleteData('weapon_tag_relations', ['weapon_id' => $id]);
        
        // Supprimer l'arme
        deleteData('weapons', ['id' => $id]);
        
        // Message de succès
        $success = "L'arme a été supprimée avec succès.";
    }
}

// Récupération des armes
$weapons = fetchAll("
    SELECT w.*, wt.name as type_name 
    FROM weapons w 
    LEFT JOIN weapon_types wt ON w.type_id = wt.id 
    ORDER BY w.name ASC
");

// Récupération des types d'armes pour le filtrage
$weaponTypes = getWeaponTypes();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsenal - StachZer Admin</title>
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
                
                <a href="index.php" class="flex items-center px-4 py-2 text-pink-300 bg-gray-900">
                    <i class="fas fa-gun w-6"></i>
                    <span>Toutes les armes</span>
                </a>
                
                <a href="add.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-plus-circle w-6"></i>
                    <span>Ajouter une arme</span>
                </a>
                
                <a href="types.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-tags w-6"></i>
                    <span>Types & Étiquettes</span>
                </a>
                
                <div class="px-4 py-2 text-xs uppercase text-gray-500 mt-4">Communauté</div>
                
                <a href="../highlights/index.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-film w-6"></i>
                    <span>Highlights</span>
                </a>
                
                <a href="../highlights/add.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
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
                    <h1 class="text-3xl font-bold">Arsenal</h1>
                    <a href="add.php" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i> Ajouter une arme
                    </a>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-300 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($weapons)): ?>
                    <div class="bg-gray-800 rounded-lg p-8 text-center">
                        <i class="fas fa-gun text-4xl text-gray-600 mb-4"></i>
                        <h2 class="text-2xl font-semibold mb-2">Aucune arme dans l'arsenal</h2>
                        <p class="text-gray-400 mb-6">Commencez par ajouter des armes pour les afficher ici.</p>
                        <a href="add.php" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i> Ajouter votre première arme
                        </a>
                    </div>
                <?php else: ?>
                    <div class="bg-gray-800 rounded-lg overflow-hidden">
                        <div class="bg-gray-700 p-4">
                            <!-- Filter options -->
                            <div class="flex flex-wrap gap-2">
                                <button class="px-4 py-2 bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 rounded-full text-sm font-semibold">Toutes</button>
                                
                                <?php foreach ($weaponTypes as $type): ?>
                                    <button class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-full text-sm">
                                        <?php echo displayText($type['name']); ?>
                                    </button>
                                <?php endforeach; ?>
                                
                                <button class="px-4 py-2 bg-green-600 opacity-80 hover:opacity-100 rounded-full text-sm ml-auto">
                                    META uniquement
                                </button>
                            </div>
                        </div>
                        
                        <table class="w-full">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left">Nom</th>
                                    <th class="py-3 px-4 text-left">Type</th>
                                    <th class="py-3 px-4 text-left">Statistiques</th>
                                    <th class="py-3 px-4 text-center">META</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($weapons as $weapon): ?>
                                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                                        <td class="py-3 px-4">
                                            <?php echo displayText($weapon['name']); ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?php echo displayText($weapon['type_name'] ?? 'Non défini'); ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-16">
                                                    <div class="text-xs text-gray-400">Dégâts</div>
                                                    <div class="bg-gray-700 h-1 rounded-full">
                                                        <div class="bg-red-500 h-1 rounded-full" style="width: <?php echo $weapon['damage']; ?>%"></div>
                                                    </div>
                                                </div>
                                                <div class="w-16">
                                                    <div class="text-xs text-gray-400">Précision</div>
                                                    <div class="bg-gray-700 h-1 rounded-full">
                                                        <div class="bg-blue-500 h-1 rounded-full" style="width: <?php echo $weapon['accuracy']; ?>%"></div>
                                                    </div>
                                                </div>
                                                <div class="w-16">
                                                    <div class="text-xs text-gray-400">Mobilité</div>
                                                    <div class="bg-gray-700 h-1 rounded-full">
                                                        <div class="bg-green-500 h-1 rounded-full" style="width: <?php echo $weapon['mobility']; ?>%"></div>
                                                    </div>
                                                </div>
                                                <div class="w-16">
                                                    <div class="text-xs text-gray-400">Contrôle</div>
                                                    <div class="bg-gray-700 h-1 rounded-full">
                                                        <div class="bg-yellow-500 h-1 rounded-full" style="width: <?php echo $weapon['control']; ?>%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <?php if ($weapon['is_meta']): ?>
                                                <span class="px-2 py-1 bg-green-500 text-xs rounded-full text-black">META</span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 bg-gray-600 text-xs rounded-full">NON</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <a href="edit.php?id=<?php echo $weapon['id']; ?>" class="inline-block text-blue-400 hover:text-blue-300 mr-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?delete=<?php echo $weapon['id']; ?>" class="inline-block text-red-400 hover:text-red-300" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette arme ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
