<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Vérification de l'authentification
requireLogin();

$success = '';
$error = '';

// Récupérer les types d'armes
$weaponTypes = getWeaponTypes();

// Récupérer les étiquettes
$weaponTags = fetchAll("SELECT * FROM weapon_tags ORDER BY name ASC");

// Traitement du formulaire pour ajouter un type d'arme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_type') {
    $name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
    
    if (empty($name)) {
        $error = 'Le nom du type d\'arme est obligatoire.';
    } else {
        $slug = createSlug($name);
        
        // Vérifier si le type existe déjà
        $existingType = fetchOne("SELECT id FROM weapon_types WHERE slug = ?", [$slug]);
        
        if ($existingType) {
            $error = 'Ce type d\'arme existe déjà.';
        } else {
            $insertId = insertData('weapon_types', [
                'name' => $name,
                'slug' => $slug
            ]);
            
            if ($insertId) {
                $success = 'Type d\'arme ajouté avec succès.';
                $weaponTypes = getWeaponTypes(); // Rafraîchir la liste
            } else {
                $error = 'Une erreur est survenue lors de l\'ajout du type d\'arme.';
            }
        }
    }
}

// Traitement du formulaire pour ajouter une étiquette
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_tag') {
    $name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
    $color = isset($_POST['color']) ? sanitizeInput($_POST['color']) : '#3B82F6';
    
    if (empty($name)) {
        $error = 'Le nom de l\'étiquette est obligatoire.';
    } else {
        $slug = createSlug($name);
        
        // Vérifier si l'étiquette existe déjà
        $existingTag = fetchOne("SELECT id FROM weapon_tags WHERE slug = ?", [$slug]);
        
        if ($existingTag) {
            $error = 'Cette étiquette existe déjà.';
        } else {
            $insertId = insertData('weapon_tags', [
                'name' => $name,
                'slug' => $slug,
                'color' => $color
            ]);
            
            if ($insertId) {
                $success = 'Étiquette ajoutée avec succès.';
                $weaponTags = fetchAll("SELECT * FROM weapon_tags ORDER BY name ASC"); // Rafraîchir la liste
            } else {
                $error = 'Une erreur est survenue lors de l\'ajout de l\'étiquette.';
            }
        }
    }
}

// Supprimer un type d'arme
if (isset($_GET['delete_type']) && !empty($_GET['delete_type'])) {
    $id = (int)$_GET['delete_type'];
    
    // Vérifier si le type est utilisé
    $usedType = fetchOne("SELECT COUNT(*) as count FROM weapons WHERE type_id = ?", [$id])['count'];
    
    if ($usedType > 0) {
        $error = 'Ce type d\'arme est utilisé par ' . $usedType . ' arme(s) et ne peut pas être supprimé.';
    } else {
        deleteData('weapon_types', ['id' => $id]);
        $success = 'Type d\'arme supprimé avec succès.';
        $weaponTypes = getWeaponTypes(); // Rafraîchir la liste
    }
}

// Supprimer une étiquette
if (isset($_GET['delete_tag']) && !empty($_GET['delete_tag'])) {
    $id = (int)$_GET['delete_tag'];
    
    // Supprimer les relations avec cette étiquette
    deleteData('weapon_tag_relations', ['tag_id' => $id]);
    
    // Supprimer l'étiquette
    deleteData('weapon_tags', ['id' => $id]);
    
    $success = 'Étiquette supprimée avec succès.';
    $weaponTags = fetchAll("SELECT * FROM weapon_tags ORDER BY name ASC"); // Rafraîchir la liste
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Types et Étiquettes - StachZer Admin</title>
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
            text-transform: uppercase;
        }
        
        .btn-secondary {
            background: #4B5563;
            color: white;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-danger {
            background: #EF4444;
            color: white;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
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
                
                <a href="index.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-gun w-6"></i>
                    <span>Toutes les armes</span>
                </a>
                
                <a href="add.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-plus-circle w-6"></i>
                    <span>Ajouter une arme</span>
                </a>
                
                <a href="types.php" class="flex items-center px-4 py-2 text-pink-300 bg-gray-900">
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
                    <h1 class="text-3xl font-bold">Types et Étiquettes d'armes</h1>
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
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Types d'armes -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden">
                        <div class="bg-gray-700 px-4 py-3 flex justify-between items-center">
                            <h2 class="font-semibold">Types d'armes</h2>
                        </div>
                        
                        <div class="p-4">
                            <!-- Formulaire d'ajout -->
                            <form method="post" action="types.php" class="bg-gray-700 rounded-lg p-4 mb-4">
                                <input type="hidden" name="action" value="add_type">
                                
                                <div class="mb-4">
                                    <label for="name" class="block text-gray-300 mb-2">Nom du type d'arme</label>
                                    <input type="text" id="name" name="name" class="bg-gray-800 border border-gray-600 text-white py-2 px-4 rounded w-full" required>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 py-2 px-4 rounded-md font-semibold">
                                        Ajouter un type
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Liste des types -->
                            <div class="bg-gray-700 rounded-lg overflow-hidden">
                                <?php if (empty($weaponTypes)): ?>
                                    <div class="p-4 text-center text-gray-400">
                                        Aucun type d'arme n'a été défini.
                                    </div>
                                <?php else: ?>
                                    <table class="w-full">
                                        <thead class="bg-gray-800">
                                            <tr>
                                                <th class="py-2 px-4 text-left">Nom</th>
                                                <th class="py-2 px-4 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($weaponTypes as $type): ?>
                                                <tr class="border-t border-gray-800">
                                                    <td class="py-2 px-4"><?php echo displayText($type['name']); ?></td>
                                                    <td class="py-2 px-4 text-right">
                                                        <a href="types.php?delete_type=<?php echo $type['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type d\'arme ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Étiquettes -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden">
                        <div class="bg-gray-700 px-4 py-3 flex justify-between items-center">
                            <h2 class="font-semibold">Étiquettes</h2>
                        </div>
                        
                        <div class="p-4">
                            <!-- Formulaire d'ajout -->
                            <form method="post" action="types.php" class="bg-gray-700 rounded-lg p-4 mb-4">
                                <input type="hidden" name="action" value="add_tag">
                                
                                <div class="mb-4">
                                    <label for="tag_name" class="block text-gray-300 mb-2">Nom de l'étiquette</label>
                                    <input type="text" id="tag_name" name="name" class="bg-gray-800 border border-gray-600 text-white py-2 px-4 rounded w-full" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="color" class="block text-gray-300 mb-2">Couleur</label>
                                    <div class="flex">
                                        <input type="color" id="color" name="color" value="#3B82F6" class="h-10 w-12 rounded-l border-0">
                                        <input type="text" id="color_text" class="bg-gray-800 border border-gray-600 text-white py-2 px-4 rounded-r w-full border-l-0" value="#3B82F6" readonly>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 py-2 px-4 rounded-md font-semibold">
                                        Ajouter une étiquette
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Liste des étiquettes -->
                            <div class="bg-gray-700 rounded-lg overflow-hidden">
                                <?php if (empty($weaponTags)): ?>
                                    <div class="p-4 text-center text-gray-400">
                                        Aucune étiquette n'a été définie.
                                    </div>
                                <?php else: ?>
                                    <table class="w-full">
                                        <thead class="bg-gray-800">
                                            <tr>
                                                <th class="py-2 px-4 text-left">Étiquette</th>
                                                <th class="py-2 px-4 text-left">Couleur</th>
                                                <th class="py-2 px-4 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($weaponTags as $tag): ?>
                                                <tr class="border-t border-gray-800">
                                                    <td class="py-2 px-4"><?php echo htmlspecialchars($tag['name']); ?></td>
                                                    <td class="py-2 px-4">
                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 rounded mr-2" style="background-color: <?php echo htmlspecialchars($tag['color']); ?>"></div>
                                                            <span><?php echo htmlspecialchars($tag['color']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-4 text-right">
                                                        <a href="types.php?delete_tag=<?php echo $tag['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette étiquette ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Synchronisation de la couleur
        const colorInput = document.getElementById('color');
        const colorTextInput = document.getElementById('color_text');
        
        colorInput.addEventListener('input', () => {
            colorTextInput.value = colorInput.value;
        });
    </script>
</body>
</html>

