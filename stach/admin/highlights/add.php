<?php
// highlights/add.php - Ajout d'un highlight
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Vérification de l'authentification
requireLogin();

$success = '';
$error = '';
$highlight = [
    'title' => '',
    'author_name' => '',
    'author_initials' => '',
    'image_url' => '',
    'clip_url' => '',
    'color_code' => '#3B82F6' // Bleu par défaut
];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $highlight['title'] = isset($_POST['title']) ? sanitizeInput($_POST['title']) : '';
    $highlight['author_name'] = isset($_POST['author_name']) ? sanitizeInput($_POST['author_name']) : '';
    $highlight['author_initials'] = isset($_POST['author_initials']) ? substr(sanitizeInput($_POST['author_initials']), 0, 2) : '';
    $highlight['clip_url'] = isset($_POST['clip_url']) ? sanitizeInput($_POST['clip_url']) : '';
    $highlight['color_code'] = isset($_POST['color_code']) ? sanitizeInput($_POST['color_code']) : '#3B82F6';
    
    // Validation
    if (empty($highlight['title'])) {
        $error = 'Le titre du highlight est obligatoire.';
    } elseif (empty($highlight['author_name'])) {
        $error = 'Le nom de l\'auteur est obligatoire.';
    } elseif (empty($highlight['author_initials']) || strlen($highlight['author_initials']) !== 2) {
        $error = 'Les initiales de l\'auteur sont obligatoires (2 caractères).';
    } elseif (empty($highlight['clip_url'])) {
        $error = 'L\'URL du clip est obligatoire.';
    } else {
        // Gestion de l'image
        if (isset($_POST['image_type']) && $_POST['image_type'] === 'upload' && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            // Upload d'une nouvelle image
            $uploadResult = uploadImage($_FILES['image'], 'highlights');
            
            if ($uploadResult['success']) {
                $highlight['image_url'] = $uploadResult['file_url'];
            } else {
                $error = $uploadResult['message'];
            }
        } elseif (isset($_POST['image_url']) && !empty($_POST['image_url'])) {
            // Utilisation d'une URL externe
            $highlight['image_url'] = sanitizeInput($_POST['image_url']);
        } else {
            $error = 'Une image est obligatoire. Veuillez soit la télécharger, soit fournir une URL.';
        }
        
        if (empty($error)) {
            // Insertion du highlight dans la base de données
            $highlightId = insertData('community_highlights', $highlight);
            
            if ($highlightId) {
                $success = 'Le highlight a été ajouté avec succès.';
                
                // Reset form
                $highlight = [
                    'title' => '',
                    'author_name' => '',
                    'author_initials' => '',
                    'image_url' => '',
                    'clip_url' => '',
                    'color_code' => '#3B82F6'
                ];
            } else {
                $error = 'Une erreur est survenue lors de l\'ajout du highlight.';
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
    <title>Ajouter un highlight - StachZer Admin</title>
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
                
                <a href="index.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
                    <i class="fas fa-film w-6"></i>
                    <span>Highlights</span>
                </a>
                
                <a href="add.php" class="flex items-center px-4 py-2 bg-gray-900 text-pink-300">
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
                    <h1 class="text-3xl font-bold">Ajouter un highlight</h1>
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
                                <h2 class="text-xl font-semibold mb-4">Informations du highlight</h2>
                                
                                <div class="mb-4">
                                    <label for="title" class="block text-gray-300 mb-2">Titre du highlight*</label>
                                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($highlight['title']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="author_name" class="block text-gray-300 mb-2">Nom de l'auteur*</label>
                                    <input type="text" id="author_name" name="author_name" value="<?php echo htmlspecialchars($highlight['author_name']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="author_initials" class="block text-gray-300 mb-2">Initiales (2 caractères)*</label>
                                    <input type="text" id="author_initials" name="author_initials" value="<?php echo htmlspecialchars($highlight['author_initials']); ?>" maxlength="2" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="clip_url" class="block text-gray-300 mb-2">URL du clip*</label>
                                    <input type="text" id="clip_url" name="clip_url" value="<?php echo htmlspecialchars($highlight['clip_url']); ?>" placeholder="https://" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" required>
                                    <p class="text-xs text-gray-400 mt-1">URL YouTube, Twitch ou tout autre service d'hébergement de clips.</p>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="color_code" class="block text-gray-300 mb-2">Couleur</label>
                                    <div class="flex items-center">
                                        <input type="color" id="color_code" name="color_code" value="<?php echo htmlspecialchars($highlight['color_code']); ?>" class="h-10 w-10 rounded mr-2">
                                        <input type="text" id="color_code_text" value="<?php echo htmlspecialchars($highlight['color_code']); ?>" class="bg-gray-700 border border-gray-600 text-white py-2 px-4 rounded w-full focus:outline-none focus:border-pink-300" readonly>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Couleur utilisée pour l'identifiant de l'auteur dans la section communauté.</p>
                                </div>
                            </div>
                            
                            <!-- Image -->
                            <div>
                                <h2 class="text-xl font-semibold mb-4">Image du highlight</h2>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-300 mb-2">Image du clip*</label>
                                    
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
                                
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold mb-3">Aperçu</h3>
                                    <div class="bg-gray-900 p-4 rounded-lg">
                                        <div class="community-card preview-card relative h-56 rounded-lg overflow-hidden border border-gray-700">
                                            <div id="preview-image" class="w-full h-full bg-gray-700 flex items-center justify-center">
                                                <i class="fas fa-image text-3xl text-gray-500"></i>
                                            </div>
                                            <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
                                                <div class="flex items-center mb-2">
                                                    <div id="preview-initials" class="w-8 h-8 rounded-full bg-blue-500 mr-2 flex items-center justify-center text-xs font-bold">
                                                        <?php echo htmlspecialchars($highlight['author_initials']); ?>
                                                    </div>
                                                    <span class="font-medium" id="preview-author">
                                                        <?php echo htmlspecialchars($highlight['author_name'] ?: 'Nom de l\'auteur'); ?>
                                                    </span>
                                                </div>
                                                <h3 class="text-lg font-bold mb-1" id="preview-title">
                                                    <?php echo htmlspecialchars($highlight['title'] ?: 'Titre du highlight'); ?>
                                                </h3>
                                            </div>
                                            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="mt-6 flex justify-end">
                            <a href="index.php" class="px-4 py-2 border border-gray-600 rounded-md mr-2 hover:bg-gray-700 transition">Annuler</a>
                            <button type="submit" class="bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 py-2 px-6 rounded-md font-semibold hover:opacity-90 transition">
                                Ajouter le highlight
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
        
        // Synchronisation de la couleur
        const colorInput = document.getElementById('color_code');
        const colorTextInput = document.getElementById('color_code_text');
        const previewInitials = document.getElementById('preview-initials');
        
        colorInput.addEventListener('input', () => {
            const colorValue = colorInput.value;
            colorTextInput.value = colorValue;
            previewInitials.style.backgroundColor = colorValue;
        });
        
        // Synchronisation du titre
        const titleInput = document.getElementById('title');
        const previewTitle = document.getElementById('preview-title');
        
        titleInput.addEventListener('input', () => {
            previewTitle.textContent = titleInput.value || 'Titre du highlight';
        });
        
        // Synchronisation du nom d'auteur
        const authorInput = document.getElementById('author_name');
        const previewAuthor = document.getElementById('preview-author');
        
        authorInput.addEventListener('input', () => {
            previewAuthor.textContent = authorInput.value || 'Nom de l\'auteur';
        });
        
        // Synchronisation des initiales
        const initialsInput = document.getElementById('author_initials');
        
        initialsInput.addEventListener('input', () => {
            previewInitials.textContent = initialsInput.value.substr(0, 2).toUpperCase();
        });
        
        // Prévisualisation de l'image
        const fileInput = document.getElementById('image');
        const urlInput = document.getElementById('image_url');
        const previewImage = document.getElementById('preview-image');
        
        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.innerHTML = '';
                    previewImage.style.backgroundImage = `url('${e.target.result}')`;
                    previewImage.style.backgroundSize = 'cover';
                    previewImage.style.backgroundPosition = 'center';
                };
                reader.readAsDataURL(file);
            }
        });
        
        urlInput.addEventListener('input', () => {
            const url = urlInput.value.trim();
            if (url) {
                previewImage.innerHTML = '';
                previewImage.style.backgroundImage = `url('${url}')`;
                previewImage.style.backgroundSize = 'cover';
                previewImage.style.backgroundPosition = 'center';
            } else {
                previewImage.style.backgroundImage = '';
                previewImage.innerHTML = '<i class="fas fa-image text-3xl text-gray-500"></i>';
            }
        });
    </script>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>
