<?php
// Inclure les fichiers nécessaires
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Enregistrer la visite
recordPageView('accueil');

// Récupérer les armes META
$metaWeapons = fetchAll("
    SELECT w.*, wt.name as type_name 
    FROM weapons w 
    LEFT JOIN weapon_types wt ON w.type_id = wt.id 
    WHERE w.is_meta = 1 
    ORDER BY w.updated_at DESC 
    LIMIT 3
");

// Récupérer les highlights
$highlights = fetchAll("
    SELECT * 
    FROM community_highlights 
    ORDER BY created_at DESC 
    LIMIT 3
");

// Récupérer les types d'armes
$weaponTypes = getWeaponTypes();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">	
    <title>StachZer.com | Streamer</title>
	<meta name="description" content="StachZer : Découvrez les meilleures classes d'armes et stratégies pour Warzone. Guides, astuces et optimisations pour améliorer votre gameplay et dominer dans Call of Duty.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <!-- Custom Cursor (Desktop only) -->
    <div class="custom-cursor hidden md:block"></div>
    
    <!-- Social Media Floating Icons -->
    <div class="social-float">
        <a href="https://www.twitch.tv/stachzer" target="_blank"><i class="fab fa-twitch"></i></a>
        <a href="https://www.tiktok.com/@stachzer" target="_blank"><i class="fab fa-tiktok"></i></a>
        <a href="https://x.com/StachZer" target="_blank"><i class="fa-solid fa-x"></i></a>
        <a href="https://www.youtube.com/channel/UCNMVzYvUYag8ehEmlcr4xNg" target="_blank"><i class="fab fa-youtube"></i></a>
        <a href="https://discord.com/invite/2YPHegkhN5" target="_blank"><i class="fab fa-discord"></i></a>
    </div>

    <!-- Navigation Header -->
<header class="fixed top-0 left-0 right-0 z-50 bg-opacity-90 backdrop-filter backdrop-blur-lg border-b" style="background-color: rgba(26, 14, 26, 0.9); border-color: rgba(255, 45, 85, 0.1);">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <!-- Partie gauche: Logo -->
            <div class="text-2xl font-extrabold">
                <span class="text-white">Stach</span><span style="color: var(--pink-light)">Zer</span>
            </div>

            <!-- Partie centrale: Navigation (desktop uniquement) -->
            <div class="hidden md:flex items-center space-x-4 flex-1 justify-center">
                <a href="/accueil" data-section="accueil" class="nav-link nav-item active">Accueil</a>
                <a href="/arsenal" data-section="arsenal" class="nav-link nav-item">Arsenal</a>
                <a href="/strategies" data-section="strategies" class="nav-link nav-item">Stratégies</a>
                <a href="/communaute" data-section="communaute" class="nav-link nav-item">Communauté</a>
            </div>

            <!-- Partie droite: Statut live et menu mobile -->
            <div class="flex items-center">
                <div class="hidden md:flex items-center mr-4 stream-status-container">
                    <div class="bg-red-600 animate-pulse w-3 h-3 rounded-full mr-2"></div>
                    <a href="https://www.twitch.tv/stachzer" target="_blank" class="text-sm font-medium hover:text-pink-300 transition">EN DIRECT</a>
                </div>
                
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Menu mobile -->
        <div id="mobile-menu" class="md:hidden hidden pt-4 pb-2">
            <div class="flex flex-col space-y-2">
                <a href="/accueil" data-section="accueil" class="nav-link nav-item active">Accueil</a>
                <a href="/arsenal" data-section="arsenal" class="nav-link nav-item">Arsenal</a>
                <a href="/strategies" data-section="strategies" class="nav-link nav-item">Stratégies</a>
                <a href="/communaute" data-section="communaute" class="nav-link nav-item">Communauté</a>
            </div>
        </div>
    </div>
</header>


    <!-- Main Content -->
    <main class="pt-16">
        <!-- Accueil (Home) Page -->
        <section id="accueil" class="page active min-h-screen py-20">
            <div class="hero-section h-screen flex items-center justify-center">
                <div class="container mx-auto px-4 text-center hero-text">
                    <h1 class="text-5xl md:text-7xl font-extrabold mb-6 animate-fadeInUp" style="text-shadow: 0 0 15px rgba(255, 214, 224, 0.7);">LES CLASSES ET <br>ACTUALITES DE <span style="color: var(--pink-light)">STACH</span><span style="color: var(--blue-light)">ZER</span></h1>
                    <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto opacity-90 animate-fadeInUp delay-1">Streameur Warzone, créateur de contenu et passionné de FPS. Rejoins une communauté de <span class="font-bold">72K</span> joueurs!</p>
                    <button class="btn-primary animate-fadeInUp delay-2" onclick="changePage('arsenal')">DÉCOUVRIR MES CLASSES</button>
                    
                    <div class="absolute bottom-10 left-0 right-0 animate-bounce">
                        <svg class="mx-auto w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="content-section py-20 px-4">
                <div class="container mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold mb-2">Qui est <span style="color: var(--pink-light)">StachZer</span>?</h2>
                        <div class="w-24 h-1 bg-gradient-to-r from-pink-300 to-blue-300 mx-auto"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                        <div class="card p-6 text-center animate-fadeInUp">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-pink-300 to-blue-300 flex items-center justify-center">
                                <i class="fas fa-trophy text-2xl text-gray-900"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Pro Player</h3>
                            <p class="opacity-80">Joueur professionnel avec des compétences exceptionnelles et une expérience compétitive.</p>
                        </div>
                        
                        <div class="card p-6 text-center animate-fadeInUp delay-1">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-pink-300 to-blue-300 flex items-center justify-center">
                                <i class="fas fa-desktop text-2xl text-gray-900"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Streameur</h3>
                            <p class="opacity-80">Créateur de contenu Twitch avec une communauté de 72K followers passionnés.</p>
                        </div>
                        
                        <div class="card p-6 text-center animate-fadeInUp delay-2">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-pink-300 to-blue-300 flex items-center justify-center">
                                <i class="fas fa-users text-2xl text-gray-900"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Communauté</h3>
                            <p class="opacity-80">Une communauté active et passionnée qui partage la même passion pour Warzone.</p>
                        </div>
                    </div>

        </section>
     
	 
	 <!-- Arsenal (Loadouts) Page -->
<section id="arsenal" class="page min-h-screen py-20">
    <div class="container mx-auto px-4 pt-10">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">ARSENAL <span style="color: var(--pink-light)">META</span></h1>
            <p class="text-lg opacity-80 max-w-3xl mx-auto">Découvre les meilleures classes d'armes pour dominer sur Warzone. Mises à jour après chaque patch pour toujours rester compétitif.</p>
        </div>

        <!-- Ajout du code récupérant les étiquettes en début de section -->
        <?php
        // Récupérer les étiquettes pour chaque arme
        $weaponIds = array_column($metaWeapons, 'id');
        $weaponTags = [];

        if (!empty($weaponIds)) {
            $placeholders = implode(',', array_fill(0, count($weaponIds), '?'));
            $tagsResult = fetchAll("
                SELECT wtr.weapon_id, wt.name, wt.color 
                FROM weapon_tag_relations wtr 
                JOIN weapon_tags wt ON wtr.tag_id = wt.id 
                WHERE wtr.weapon_id IN ($placeholders)
            ", $weaponIds);
            
            // Regrouper les étiquettes par arme
            foreach ($tagsResult as $tag) {
                if (!isset($weaponTags[$tag['weapon_id']])) {
                    $weaponTags[$tag['weapon_id']] = [];
                }
                $weaponTags[$tag['weapon_id']][] = $tag;
            }
        }
        ?>

        <div class="flex flex-wrap items-center justify-between mb-8">
            <div class="flex flex-wrap justify-center gap-4 md:flex-1">
                <button id="filter-all" class="filter-button px-6 py-2 rounded-full bg-gradient-to-r from-pink-primary to-violet-accent text-white font-medium active">Toutes</button>
                <?php foreach ($weaponTypes as $type): ?>
                    <button id="filter-<?php echo $type['id']; ?>" class="filter-button px-6 py-2 rounded-full bg-gray-800 hover:bg-gray-700 transition" data-type="<?php echo $type['id']; ?>">
                        <?php echo displayText($type['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <div class="relative mt-4 md:mt-0 w-full md:w-64">
                <input type="text" id="weapon-search" placeholder="Rechercher une arme..." class="bg-gray-800 border border-gray-700 focus:border-pink-primary text-white py-2 px-4 pl-10 rounded-lg w-full focus:outline-none">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <?php 
            // Si pas d'armes META, récupérer toutes les armes (limité à 3)
            if (count($metaWeapons) == 0) {
                $metaWeapons = fetchAll("
                    SELECT w.*, wt.name as type_name 
                    FROM weapons w 
                    LEFT JOIN weapon_types wt ON w.type_id = wt.id 
                    ORDER BY w.updated_at DESC 
                    LIMIT 3
                ");
                
                // Récupérer à nouveau les étiquettes si nécessaire
                $weaponIds = array_column($metaWeapons, 'id');
                $weaponTags = [];

                if (!empty($weaponIds)) {
                    $placeholders = implode(',', array_fill(0, count($weaponIds), '?'));
                    $tagsResult = fetchAll("
                        SELECT wtr.weapon_id, wt.name, wt.color 
                        FROM weapon_tag_relations wtr 
                        JOIN weapon_tags wt ON wtr.tag_id = wt.id 
                        WHERE wtr.weapon_id IN ($placeholders)
                    ", $weaponIds);
                    
                    foreach ($tagsResult as $tag) {
                        if (!isset($weaponTags[$tag['weapon_id']])) {
                            $weaponTags[$tag['weapon_id']] = [];
                        }
                        $weaponTags[$tag['weapon_id']][] = $tag;
                    }
                }
            }
            
            if (count($metaWeapons) > 0): 
            ?>
                <?php foreach ($metaWeapons as $index => $weapon): ?>
                    <!-- Loadout Card -->
                    <div class="card animate-fadeInUp weapon-card <?php echo $index > 0 ? 'delay-'. $index : ''; ?>" data-type="<?php echo $weapon['type_id']; ?>">
                        <div class="relative">
                            <img src="<?php echo htmlspecialchars($weapon['image_url']); ?>" alt="<?php echo displayText($weapon['name']); ?>" class="w-full h-48 object-cover">
                            
                            <!-- Étiquettes de l'arme -->
                            <?php if (isset($weaponTags[$weapon['id']]) && !empty($weaponTags[$weapon['id']])): ?>
                                <div class="absolute top-0 right-0 flex flex-col gap-1 p-2">
                                    <?php foreach ($weaponTags[$weapon['id']] as $tag): ?>
                                        <div class="weapon-tag px-2 py-1 text-xs font-bold text-white rounded-l-md" 
                                             style="background-color: <?php echo htmlspecialchars($tag['color']); ?>">
                                            <?php echo displayText($tag['name']); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($weapon['is_meta']): ?>
                                <div class="absolute bottom-0 left-0 m-2">
                                    <span class="px-3 py-1 bg-green-500 text-xs font-bold rounded-full text-black">META</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold"><?php echo displayText($weapon['name']); ?></h3>
                            </div>
                            
                            <div class="mb-4">
                                <div class="grid grid-cols-4 gap-1 text-xs mb-1">
                                    <div>Dégâts</div>
                                    <div>Précision</div>
                                    <div>Mobilité</div>
                                    <div>Contrôle</div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="bg-gray-700 rounded-full h-2 overflow-hidden">
                                        <div class="progress-bar" style="width: <?php echo $weapon['damage']; ?>%"></div>
                                    </div>
                                    <div class="bg-gray-700 rounded-full h-2 overflow-hidden">
                                        <div class="progress-bar" style="width: <?php echo $weapon['accuracy']; ?>%"></div>
                                    </div>
                                    <div class="bg-gray-700 rounded-full h-2 overflow-hidden">
                                        <div class="progress-bar" style="width: <?php echo $weapon['mobility']; ?>%"></div>
                                    </div>
                                    <div class="bg-gray-700 rounded-full h-2 overflow-hidden">
                                        <div class="progress-bar" style="width: <?php echo $weapon['control']; ?>%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-sm mb-4">
                                <?php if (!empty($weapon['attachment1'])): ?>
                                    <p class="mb-2"><span class="font-semibold">Canon:</span> <?php echo displayText($weapon['attachment1']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($weapon['attachment2'])): ?>
                                    <p class="mb-2"><span class="font-semibold">Lunette:</span> <?php echo displayText($weapon['attachment2']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($weapon['attachment3'])): ?>
                                    <p class="mb-2"><span class="font-semibold">Crosse:</span> <?php echo displayText($weapon['attachment3']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($weapon['attachment4'])): ?>
                                    <p class="mb-2"><span class="font-semibold">Poignée:</span> <?php echo displayText($weapon['attachment4']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($weapon['attachment5'])): ?>
                                    <p><span class="font-semibold">Munitions:</span> <?php echo displayText($weapon['attachment5']); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($weapon['description'])): ?>
                                <div class="text-sm bg-gray-800 p-3 rounded-lg mb-4">
                                    <p class="italic"><?php echo displayText($weapon['description']); ?></p>
                                </div>
                            <?php endif; ?>
                           <button class="view-details-btn w-full py-3 bg-gradient-to-r from-pink-300 to-blue-300 rounded-lg font-semibold text-gray-900 hover:opacity-90 transition" data-weapon-id="<?php echo $weapon['id']; ?>">AFFICHER L'ARME</button>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center p-10">
                    <p>Aucune arme n'a été ajoutée pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
	 <!-- Modal pour les détails de l'arme -->
<div id="weapon-detail-modal" class="weapon-modal fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="modal-overlay absolute inset-0 bg-black bg-opacity-75"></div>
    
    <div class="weapon-modal-content relative bg-gray-900 w-full max-w-4xl mx-auto rounded-lg shadow-lg z-50 overflow-y-auto max-h-screen">
        <!-- En-tête avec bouton de fermeture -->
        <div class="modal-header flex justify-between items-center p-4 border-b border-gray-700">
            <h2 class="text-2xl font-bold weapon-detail-name">Détails de l'arme</h2>
            <button class="close-modal text-gray-400 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Contenu -->
        <div class="modal-body p-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Image section -->
                <div class="relative">
                    <div class="weapon-image-container relative overflow-hidden rounded-lg cursor-zoom-in">
                        <img src="" alt="" id="weapon-detail-image" class="w-full h-auto object-cover">
                        <div class="absolute top-0 right-0 flex flex-col gap-1 p-2 weapon-detail-tags"></div>
                        <div class="absolute bottom-0 left-0 m-2 weapon-detail-meta"></div>
                    </div>
                    <div class="mt-2 text-sm text-gray-400"><i class="fas fa-search-plus mr-1"></i> Cliquez sur l'image pour l'agrandir</div>
                </div>
                
                <!-- Info section -->
                <div>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Statistiques</h3>
                        <div class="grid grid-cols-4 gap-4 mb-2">
                            <div>
                                <div class="text-xs text-gray-400 mb-1">Dégâts</div>
                                <div class="bg-gray-700 rounded-full h-2 overflow-hidden">
                                    <div class="weapon-damage progress-bar" style="width: 0%"></div>
                                </div>
                                <div class="text-right text-xs mt-1 weapon-damage-value">0%</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 mb-1">Précision</div>
                                <div class="bg-gray-700 rounded-full h-2 overflow-hidden">
                                    <div class="weapon-accuracy progress-bar" style="width: 0%"></div>
                                </div>
                                <div class="text-right text-xs mt-1 weapon-accuracy-value">0%</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 mb-1">Mobilité</div>
                                <div class="bg-gray-700 rounded-full h-2 overflow-hidden">
                                    <div class="weapon-mobility progress-bar" style="width: 0%"></div>
                                </div>
                                <div class="text-right text-xs mt-1 weapon-mobility-value">0%</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 mb-1">Contrôle</div>
                                <div class="bg-gray-700 rounded-full h-2 overflow-hidden">
                                    <div class="weapon-control progress-bar" style="width: 0%"></div>
                                </div>
                                <div class="text-right text-xs mt-1 weapon-control-value">0%</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Configuration</h3>
                        <div class="space-y-2 weapon-attachments"></div>
                    </div>
                    
                    <div class="weapon-description-container hidden">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <p class="italic weapon-description"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour l'image agrandie -->
<div id="image-zoom-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="modal-overlay absolute inset-0 bg-black bg-opacity-90"></div>
    
    <div class="relative z-50">
        <button class="close-zoom absolute top-4 right-4 text-white hover:text-gray-300 focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="zoomed-image" src="" alt="" class="max-w-full max-h-90vh object-contain">
    </div>
</div>

	 
	 </section>
	 
	 
        
        <!-- Stratégies (Strategies) Page -->
        <section id="strategies" class="page min-h-screen py-20">
            <div class="container mx-auto px-4 pt-10">
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Optimisation <span style="color: var(--pink-light)">PRO</span></h1>
					                    <p class="text-lg opacity-80 max-w-3xl mx-auto">Boost de FPS ou meilleur audio ? c'est ici !</p>
                </div>
                
                <!-- Featured Strategy -->
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 mb-16">
                    <div class="lg:col-span-3 bg-gray-900 bg-opacity-70 rounded-2xl overflow-hidden">
                        <img src="" alt="Rotation Strategy" class="w-full h-64 object-cover">
                        <div class="p-8">
                            <h2 class="text-2xl font-bold mb-4">Ma vidéo youtube pour profitez de tes fps !</h2>
                            <p class="mb-6 opacity-90">Les FPS, c'est crucial pour dominer tes parties ! Regarde mon guide opti dans cette vidéo et booste tes performances en un rien de temps !</p>
                            <div class="flex flex-wrap gap-3 mb-6">
                                <span class="px-3 py-1 bg-blue-900 bg-opacity-50 rounded-full text-xs">Graphisme</span>
                                <span class="px-3 py-1 bg-blue-900 bg-opacity-50 rounded-full text-xs">FPS</span>
                                <span class="px-3 py-1 bg-blue-900 bg-opacity-50 rounded-full text-xs">Optimisation</span>
                                <span class="px-3 py-1 bg-blue-900 bg-opacity-50 rounded-full text-xs">Warzone</span>
                            </div>
                            <button class="btn-primary">VOIR LA VIDEO</button>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-2 grid grid-cols-1 gap-6">
                        <!-- Quick Tip 1 -->
                        <div class="strategy-card p-6">
       
                            <h3 class="text-xl font-bold mb-3">Optimisation graphique</h3>
                            <p class="opacity-80 mb-4">Dans cette vidéo je te montre les meilleurs paramètres graphiques pour Warzone .</p>
                            <div class="flex">
                                <span class="px-3 py-1 bg-pink-900 bg-opacity-30 rounded-full text-xs mr-2">BOOST FPS</span>
                                <span class="px-3 py-1 bg-pink-900 bg-opacity-30 rounded-full text-xs">Graphisme</span>
                            </div>
                        </div>
                        
                        <!-- Quick Tip 2 -->
                        <div class="strategy-card p-6">
                            <h3 class="text-xl font-bold mb-3">test</h3>
                            <p class="opacity-80 mb-4">test</p>
                            <div class="flex">
                                <span class="px-3 py-1 bg-pink-900 bg-opacity-30 rounded-full text-xs mr-2">Combat</span>
                                <span class="px-3 py-1 bg-pink-900 bg-opacity-30 rounded-full text-xs">Tactique</span>
                            </div>
                        </div>
                        
                        <!-- Quick Tip 3 -->
                        <div class="strategy-card p-6">
                            <h3 class="text-xl font-bold mb-3">test</h3>
                            <p class="opacity-80 mb-4">test</p>
                            <div class="flex">
                                <span class="px-3 py-1 bg-pink-900 bg-opacity-30 rounded-full text-xs mr-2">Équipement</span>
                                <span class="px-3 py-1 bg-pink-900 bg-opacity-30 rounded-full text-xs">Stratégie</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Communauté (Community) Page -->
        <section id="communaute" class="page min-h-screen py-20">
            <div class="container mx-auto px-4 pt-10">
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">MA <span style="color: var(--blue-light)">COMMUNAUTÉ</span></h1>
                    <p class="text-lg opacity-80 max-w-3xl mx-auto">Rejoins une communauté passionnée de joueurs Warzone et participe à des événements exclusifs.</p>
                </div>
                
                <!-- Community Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="card p-8 text-center animate-fadeInUp">
                        <div class="text-5xl font-bold mb-4" style="color: var(--pink-light)">72K+</div>
                        <h3 class="text-xl font-medium mb-2">Followers Twitch</h3>
                        <p class="opacity-70">Une communauté grandissante qui nous rejoint chaque jour sur Twitch.</p>
                    </div>
                    
                    <div class="card p-8 text-center animate-fadeInUp delay-1">
                        <div class="text-5xl font-bold mb-4" style="color: var(--blue-light)">300+</div>
                        <h3 class="text-xl font-medium mb-2">Membres Discord</h3>
                        <p class="opacity-70">Notre serveur Discord est l'un des plus actifs de la communauté francophone Warzone.</p>
                    </div>
                    
                    <div class="card p-8 text-center animate-fadeInUp delay-2">
                        <div class="text-5xl font-bold mb-4" style="color: var(--pink-light)">100+</div>
                        <h3 class="text-xl font-medium mb-2">Armes Méta</h3>
                        <p class="opacity-70">Plus de 100 armes dédié à ma communauté disponible sur le discord !</p>
                    </div>
                </div>
                
                <!-- Discord Section -->
                <div class="bg-gray-900 bg-opacity-70 rounded-2xl overflow-hidden mb-16">
                    <div class="grid grid-cols-1 lg:grid-cols-2">
                        <div class="p-8 lg:p-12 flex flex-col justify-center">
                            <h2 class="text-2xl lg:text-3xl font-bold mb-4">Rejoins notre serveur Discord</h2>
                           
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="bg-gray-800 bg-opacity-60 rounded-lg p-4">
                                    <div class="font-bold mb-1">Annonces</div>
                                    <p class="text-sm opacity-70">Toutes mes annonces sont disponible sur le discord .</p>
                                </div>
                                
                                <div class="bg-gray-800 bg-opacity-60 rounded-lg p-4">
                                    <div class="font-bold mb-1">Canaux</div>
                                    <p class="text-sm opacity-70">Ton canaux , ta décision , profite du système de canaux automatique.</p>
                                </div>
                                
                                <div class="bg-gray-800 bg-opacity-60 rounded-lg p-4">
                                    <div class="font-bold mb-1">Balance ton twitch </div>
                                    <p class="text-sm opacity-70">Tu stream toi aussi , vien partager ton twitch sur notre serveur pour attiré plus de viewers !</p>
                                </div>
                                
                                <div class="bg-gray-800 bg-opacity-60 rounded-lg p-4">
                                    <div class="font-bold mb-1">Armes Meta </div>
                                    <p class="text-sm opacity-70">Sur mon discord tu trouveras aussi toutes mes classes meta ainsi que celle des joueurs.</p>
                                </div>
                            </div>
                            
                            <a href="https://discord.com/invite/2YPHegkhN5" target="_blank" class="btn-primary self-start">
                                <i class="fab fa-discord mr-2"></i> REJOINDRE LE DISCORD
                            </a>
                        </div>
                        <div class="relative overflow-hidden">
                            <img src="https://contabo.game-link.eu/stachban.png" alt="Discord Community" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-r from-gray-900 to-transparent"></div>
                        </div>
                    </div>
                </div>
                
<!-- Community Highlights -->
<div class="mb-16">
    <div class="text-center mb-10">
        <h2 class="text-2xl lg:text-3xl font-bold mb-3">Highlights de la communauté</h2>
        <p class="opacity-80 max-w-2xl mx-auto">Les meilleurs clips et moments partagés par notre communauté. Soumets ton clip pour apparaître ici!</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php if (count($highlights) > 0): ?>
            <?php foreach ($highlights as $highlight): ?>
                <!-- Highlight -->
                <div class="community-card">
                    <img src="<?php echo htmlspecialchars($highlight['image_url']); ?>" alt="<?php echo htmlspecialchars($highlight['title']); ?>" class="w-full h-56 object-cover">
                    <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 rounded-full mr-2 flex items-center justify-center text-xs font-bold" style="background-color: <?php echo htmlspecialchars($highlight['color_code']); ?>;">
                                <?php echo htmlspecialchars($highlight['author_initials']); ?>
                            </div>
                            <span class="font-medium"><?php echo htmlspecialchars($highlight['author_name']); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold"><?php echo htmlspecialchars($highlight['title']); ?></h3>
                            <!-- Nouveau bouton "Regarder" -->
                            <a href="<?php echo htmlspecialchars($highlight['video_url']); ?>" target="_blank" class="px-3 py-1 bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 rounded-md text-xs font-bold flex items-center">
                                <i class="fas fa-play mr-1"></i> Regarder
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Highlight 1 (exemples par défaut si pas de highlights) -->
            <div class="community-card">
                <img src="https://i.ytimg.com/vi/KlCzROTAos4/maxresdefault.jpg" alt="Community Highlight" class="w-full h-56 object-cover">
                <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 rounded-full bg-blue-500 mr-2 flex items-center justify-center text-xs font-bold">MK</div>
                        <span class="font-medium">Qui à clip</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold">Sujet du clip</h3>
                        <!-- Exemple de bouton "Regarder" pour les exemples par défaut -->
                        <a href="#" class="px-3 py-1 bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 rounded-md text-xs font-bold flex items-center">
                            <i class="fas fa-play mr-1"></i> Regarder
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Highlight 2 -->
            <div class="community-card">
                <img src="https://i.ytimg.com/vi/pqPCFUUbMiU/maxresdefault.jpg" alt="Community Highlight" class="w-full h-56 object-cover">
                <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 rounded-full bg-pink-500 mr-2 flex items-center justify-center text-xs font-bold">LN</div>
                        <span class="font-medium">Qui à clip</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold">Sujet du clip</h3>
                        <!-- Exemple de bouton "Regarder" pour les exemples par défaut -->
                        <a href="#" class="px-3 py-1 bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 rounded-md text-xs font-bold flex items-center">
                            <i class="fas fa-play mr-1"></i> Regarder
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Highlight 3 -->
            <div class="community-card">
                <img src="https://i.ytimg.com/vi/6vkpYReIXIc/maxresdefault.jpg" alt="Community Highlight" class="w-full h-56 object-cover">
                <div class="absolute bottom-0 left-0 right-0 p-4 z-10">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 rounded-full bg-green-500 mr-2 flex items-center justify-center text-xs font-bold">VK</div>
                        <span class="font-medium">Qui à clip</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold">Sujet du clip</h3>
                        <!-- Exemple de bouton "Regarder" pour les exemples par défaut -->
                        <a href="#" class="px-3 py-1 bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 rounded-md text-xs font-bold flex items-center">
                            <i class="fas fa-play mr-1"></i> Regarder
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="text-center mt-8">
        <a href="highlights.php" class="px-6 py-3 border border-white rounded-lg hover:bg-white hover:text-gray-900 transition">VOIR PLUS DE CLIPS</a>
    </div>
</div>

            </div>
        </section>
        
       
              
    <!-- Footer -->
    <footer class="py-12 border-t border-gray-800" style="background: rgba(25, 18, 23, 0.98);">

        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <div>
                    <div class="text-2xl font-extrabold mb-4">
                        <span class="text-white">Stach</span><span style="color: var(--pink-light)">Zer</span>
                    </div>
                    <p class="opacity-70 mb-4">Streameur Warzone, créateur de contenu et passionné de FPS. Une communauté de 72K+ joueurs!</p>
                    <div class="flex space-x-3">
                        <a href="https://www.twitch.tv/stachzer" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-300 hover:text-gray-900 transition">
                            <i class="fab fa-twitch"></i>
                        </a>
                        <a href="https://www.tiktok.com/@stachzer" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-300 hover:text-gray-900 transition">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="https://x.com/StachZer" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-300 hover:text-gray-900 transition">
                            <i class="fa-solid fa-x"></i>
                        </a>
                        <a href="https://www.youtube.com/channel/UCNMVzYvUYag8ehEmlcr4xNg" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-300 hover:text-gray-900 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="https://discord.com/invite/2YPHegkhN5" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-300 hover:text-gray-900 transition">
                            <i class="fab fa-discord"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4">Navigation</h3>
                    <ul class="space-y-2">
                        <li><a href="javascript:void(0)" onclick="changePage('accueil')" class="opacity-70 hover:opacity-100 transition">Accueil</a></li>
                        <li><a href="javascript:void(0)" onclick="changePage('arsenal')" class="opacity-70 hover:opacity-100 transition">Arsenal</a></li>
                        <li><a href="javascript:void(0)" onclick="changePage('strategies')" class="opacity-70 hover:opacity-100 transition">Stratégies</a></li>
                        <li><a href="javascript:void(0)" onclick="changePage('communaute')" class="opacity-70 hover:opacity-100 transition">Communauté</a></li>
                          </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4">Resources</h3>
                    <ul class="space-y-2">                    
                        <li><a href="cgu.php" class="opacity-70 hover:opacity-100 transition">Mentions légales</a></li>
                        <li><a href="confidentialite.php" class="opacity-70 hover:opacity-100 transition">Politique de confidentialité</a></li>
                        
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4">Mise à jour</h3>
                    <p class="opacity-70 mb-4">Site mis à jour sur la V1.0.4</p>

                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0 opacity-70 text-sm">
                    © <?php echo date('Y'); ?> StachZer.com. Tous droits réservés.

                </div>
                <div class="opacity-70 text-sm">
                    Call of Duty® et Warzone™ sont des marques déposées d'Activision Publishing, Inc.
                </div>
            </div>
        </div>
    </footer>
<!-- Bannière de cookies -->
<div id="cookie-banner" class="fixed bottom-0 left-0 right-0 bg-gray-800 bg-opacity-95 backdrop-filter backdrop-blur-md border-t border-gray-700 transform transition-transform duration-300 translate-y-full z-50">
    <div class="container mx-auto px-4 py-3">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="text-sm text-gray-300 mb-4 md:mb-0 md:mr-4">
                <p>Nous utilisons des cookies pour améliorer votre expérience sur notre site. En continuant à naviguer, vous acceptez notre <a href="confidentialite.php" class="text-pink-300 hover:underline">politique de confidentialité</a>.</p>
            </div>
            <div class="flex space-x-2">
                <button id="accept-cookies" class="px-4 py-2 bg-gradient-to-r from-pink-300 to-blue-300 text-gray-900 rounded text-sm font-medium hover:opacity-90 transition">Accepter</button>
                <button id="essential-only-cookies" class="px-4 py-2 bg-gray-700 text-gray-300 rounded text-sm font-medium hover:bg-gray-600 transition">Essentiels uniquement</button>
            </div>
        </div>
    </div>
</div>
<!-- Scripts JavaScript -->
<script>
    // Variables globales utilisées par les scripts
    const weaponsData = <?php 
        $weaponsForJS = [];
        foreach ($metaWeapons as $weapon) {
            $weaponData = [
                'id' => $weapon['id'],
                'name' => displayText($weapon['name']),
                'image_url' => $weapon['image_url'],
                'is_meta' => $weapon['is_meta'],
                'damage' => $weapon['damage'],
                'accuracy' => $weapon['accuracy'],
                'mobility' => $weapon['mobility'],
                'control' => $weapon['control'],
                'attachment1' => displayText($weapon['attachment1']),
                'attachment2' => displayText($weapon['attachment2']),
                'attachment3' => displayText($weapon['attachment3']),
                'attachment4' => displayText($weapon['attachment4']),
                'attachment5' => displayText($weapon['attachment5']),
                'description' => displayText($weapon['description']),
                'tags' => isset($weaponTags[$weapon['id']]) ? $weaponTags[$weapon['id']] : []
            ];
            $weaponsForJS[$weapon['id']] = $weaponData;
        }
        echo json_encode($weaponsForJS);
    ?>;
</script>
<script src="script/navigation.js"></script>
<script src="script/cursor.js"></script>
<script src="script/weapons.js"></script>
<script src="script/cookies.js"></script>
<script src="script/twitch.js"></script>
<script src="script/weapon-search.js"></script>


</body>
</html>
