<?php
// weapons/add.php - Ajout d'une arme
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Vérification de l'authentification
requireLogin();

$success = '';
$error = '';
$weapon = [
    'name' => '',
    'type_id' => '',
    'image_url' => '',
    'damage' => 50,
    'accuracy' => 50,
    'mobility' => 50,
    'control' => 50,
    'attachment1' => '',
    'attachment2' => '',
    'attachment3' => '',
    'attachment4' => '',
    'attachment5' => '',
    'description' => '',
    'is_meta' => 0
];

// Récupération des types d'armes
$weaponTypes = getWeaponTypes();

// Récupération des étiquettes
$weaponTags = getWeaponTags();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $weapon['name'] = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
    $weapon['type_id'] = isset($_POST['type_id']) ? (int)$_POST['type_id'] : 0;
    $weapon['damage'] = isset($_POST['damage']) ? (int)$_POST['damage'] : 50;
    $weapon['accuracy'] = isset($_POST['accuracy']) ? (int)$_POST['accuracy'] : 50;
    $weapon['mobility'] = isset($_POST['mobility']) ? (int)$_POST['mobility'] : 50;
    $weapon['control'] = isset($_POST['control']) ? (int)$_POST['control'] : 50;
    $weapon['attachment1'] = isset($_POST['attachment1']) ? sanitizeInput($_POST['attachment1']) : '';
    $weapon['attachment2'] = isset($_POST['attachment2']) ? sanitizeInput($_POST['attachment2']) : '';
    $weapon['attachment3'] = isset($_POST['attachment3']) ? sanitizeInput($_POST['attachment3']) : '';
    $weapon['attachment4'] = isset($_POST['attachment4']) ? sanitizeInput($_POST['attachment4']) : '';
    $weapon['attachment5'] = isset($_POST['attachment5']) ? sanitizeInput($_POST['attachment5']) : '';
    $weapon['description'] = isset($_POST['description']) ? sanitizeInput($_POST['description']) : '';
    $weapon['is_meta'] = isset($_POST['is_meta']) ? 1 : 0;
    
    // Validation
if (empty($weapon['name'])) {
    $error = 'Le nom de l\'arme est obligatoire.';
} elseif ($weapon['type_id'] <= 0) {
    $error = 'Veuillez sélectionner un type d\'arme.';
} else {
        // Gestion de l'image
        if (isset($_POST['image_type']) && $_POST['image_type'] === 'upload' && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            // Upload d'une nouvelle image
            $uploadResult = uploadImage($_FILES['image']);
            
            if ($uploadResult['success']) {
                $weapon['image_url'] = $uploadResult['file_url'];
            } else {
                $error = $uploadResult['message'];
            }
        } elseif (isset($_POST['image_url']) && !empty($_POST['image_url'])) {
            // Utilisation d'une URL externe
            $weapon['image_url'] = sanitizeInput($_POST['image_url']);
        } else {
            $error = 'Une image est obligatoire. Veuillez soit la télécharger, soit fournir une URL.';
        }
        
        if (empty($error)) {
            // Insertion de l'arme dans la base de données
            $weaponId = insertData('weapons', $weapon);
            
            if ($weaponId) {
                // Gestion des étiquettes (tags)
                if (isset($_POST['tags']) && is_array($_POST['tags'])) {
                    foreach ($_POST['tags'] as $tagId) {
                        insertData('weapon_tag_relations', [
                            'weapon_id' => $weaponId,
                            'tag_id' => (int)$tagId
                        ]);
                    }
                }
                
                $success = 'L\'arme a été ajoutée avec succès.';
                
                // Reset form
                $weapon = [
                    'name' => '',
                    'type_id' => '',
                    'image_url' => '',
                    'damage' => 50,
                    'accuracy' => 50,
                    'mobility' => 50,
                    'control' => 50,
                    'attachment1' => '',
                    'attachment2' => '',
                    'attachment3' => '',
                    'attachment4' => '',
                    'attachment5' => '',
                    'description' => '',
                    'is_meta' => 0
                ];
            } else {
                $error = 'Une erreur est survenue lors de l\'ajout de l\'arme.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une arme - StachZer Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="bg-gray-900 text-white">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 h-full flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <div class="text-2xl font-bold">
                    <span class="text-white">Stach</span><span class="text-pink-300">Zer</span>
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
                
                <a href="add.php" class="flex items-center px-4 py-2 bg-gray-900 text-pink-300">
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
                
                <div class="px-4 py-2 text-xs uppercase text-gray-500 mt-4">Système</div>
                
                <a href="../stats.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-chart-line w-6"></i>
                    <span>Statistiques</span>
                </a>
                
                <a href="../settings.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-cog w-6"></i>
                    <span>Paramètres</span>
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
                    <h1 class="text-3xl font-bold">Ajouter une arme</h1>
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
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <form method="post" action="add.php" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informations de base -->
                            <div>
                                <h2 class="text-xl font-semibold mb-4">Informations de base</h2>
                                
                                <div class="mb-4">
                                    <label for="name" class="block text-gray-300 mb-2">Nom de l'arme*</label>
                                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($weapon['name']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="type_id" class="block text-gray-300 mb-2">Type d'arme*</label>
                                    <select id="type_id" name="type_id" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                                        <option value="">Sélectionner un type</option>
                                        <?php foreach ($weaponTypes as $type): ?>
                                            <option value="<?php echo $type['id']; ?>" <?php echo ($weapon['type_id'] == $type['id']) ? 'selected' : ''; ?>>
                                                <?php echo displayText($type['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-300 mb-2">Image de l'arme*</label>
                                    
                                    <div class="mb-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="image_type" value="upload" class="form-radio text-pink-300" checked>
                                            <span class="ml-2">Télécharger une image</span>
                                        </label>
                                    </div>
                                    
                                    <div class="mb-4 image-option" id="upload-option">
                                        <input type="file" id="image" name="image" accept="image/*" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300">
                                        <p class="text-xs text-gray-400 mt-1">Formats acceptés : JPG, PNG, WEBP, GIF. Max 5MB.</p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="image_type" value="url" class="form-radio text-pink-300">
                                            <span class="ml-2">Utiliser une URL</span>
                                        </label>
                                    </div>
                                    
                                    <div class="mb-4 image-option hidden" id="url-option">
                                        <input type="text" id="image_url" name="image_url" placeholder="https://" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-300 mb-2">Étiquettes</label>
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <?php foreach ($weaponTags as $tag): ?>
                                            <label class="inline-flex items-center bg-gray-700 p-2 rounded">
                                                <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>" class="form-checkbox text-pink-300">
                                                <span class="ml-2" style="color: <?php echo htmlspecialchars($tag['color']); ?>;">
                                                    <?php echo htmlspecialchars($tag['name']); ?>
                                                </span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_meta" value="1" <?php echo $weapon['is_meta'] ? 'checked' : ''; ?> class="form-checkbox text-pink-300">
                                        <span class="ml-2">Arme META (mise en avant)</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Statistiques et Accessoires -->
                            <div>
                                <h2 class="text-xl font-semibold mb-4">Statistiques</h2>
                                
                                <div class="mb-4">
                                    <label for="damage" class="block text-gray-300 mb-2">Dégâts (<?php echo $weapon['damage']; ?>%)</label>
                                    <input type="range" id="damage" name="damage" min="0" max="100" value="<?php echo $weapon['damage']; ?>" class="w-full" oninput="document.querySelector('label[for=\'damage\']').textContent = 'Dégâts (' + this.value + '%)'">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="accuracy" class="block text-gray-300 mb-2">Précision (<?php echo $weapon['accuracy']; ?>%)</label>
                                    <input type="range" id="accuracy" name="accuracy" min="0" max="100" value="<?php echo $weapon['accuracy']; ?>" class="w-full" oninput="document.querySelector('label[for=\'accuracy\']').textContent = 'Précision (' + this.value + '%)'">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="mobility" class="block text-gray-300 mb-2">Mobilité (<?php echo $weapon['mobility']; ?>%)</label>
                                    <input type="range" id="mobility" name="mobility" min="0" max="100" value="<?php echo $weapon['mobility']; ?>" class="w-full" oninput="document.querySelector('label[for=\'mobility\']').textContent                                    <input type="range" id="mobility" name="mobility" min="0" max="100" value="<?php echo $weapon['mobility']; ?>" class="w-full" oninput="document.querySelector('label[for=\'mobility\']').textContent = 'Mobilité (' + this.value + '%)'">
                                </div>
                                
                                <div class="mb-6">
                                    <label for="control" class="block text-gray-300 mb-2">Contrôle (<?php echo $weapon['control']; ?>%)</label>
                                    <input type="range" id="control" name="control" min="0" max="100" value="<?php echo $weapon['control']; ?>" class="w-full" oninput="document.querySelector('label[for=\'control\']').textContent = 'Contrôle (' + this.value + '%)'">
                                </div>
                                
                                <h2 class="text-xl font-semibold mb-4">Accessoires</h2>
                                
                                <div class="mb-3">
                                    <label for="attachment1" class="block text-gray-300 mb-1">Canon</label>
                                    <input type="text" id="attachment1" name="attachment1" value="<?php echo htmlspecialchars($weapon['attachment1']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="attachment2" class="block text-gray-300 mb-1">Lunette/Laser</label>
                                    <input type="text" id="attachment2" name="attachment2" value="<?php echo htmlspecialchars($weapon['attachment2']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="attachment3" class="block text-gray-300 mb-1">Crosse</label>
                                    <input type="text" id="attachment3" name="attachment3" value="<?php echo htmlspecialchars($weapon['attachment3']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="attachment4" class="block text-gray-300 mb-1">Poignée</label>
                                    <input type="text" id="attachment4" name="attachment4" value="<?php echo htmlspecialchars($weapon['attachment4']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="attachment5" class="block text-gray-300 mb-1">Munitions/Accessoire</label>
                                    <input type="text" id="attachment5" name="attachment5" value="<?php echo htmlspecialchars($weapon['attachment5']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mt-4">
                            <label for="description" class="block text-gray-300 mb-2">Description*</label>
<textarea id="description" name="description" rows="4" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300"><?php echo htmlspecialchars($weapon['description']); ?></textarea>
                           <p class="text-xs text-gray-400 mt-1">Expliquez les avantages et les inconvénients de cette arme, ainsi que les situations dans lesquelles elle excelle.</p>
                        </div>
                        
                        <!-- Actions -->
                        <div class="mt-6 flex justify-end">
                            <a href="index.php" class="px-4 py-2 border border-gray-600 rounded-md mr-2 hover:bg-gray-700 transition">Annuler</a>
                            <button type="submit" class="bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 py-2 px-6 rounded-md font-semibold hover:opacity-90 transition">
                                Ajouter l'arme
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle des options d'image
        const imageTypeRadios = document.querySelectorAll('input[name="image_type"]');
        const imageOptions = document.querySelectorAll('.image-option');
        
        imageTypeRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                const selectedValue = radio.value;
                
                imageOptions.forEach(option => {
                    option.classList.add('hidden');
                });
                
                document.getElementById(selectedValue + '-option').classList.remove('hidden');
            });
        });
    </script>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>
