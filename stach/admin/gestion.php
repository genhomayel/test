<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Vérification de l'authentification
requireLogin();

// Statistiques de base
$totalWeapons = fetchOne("SELECT COUNT(*) as count FROM weapons")['count'];
$totalMetaWeapons = fetchOne("SELECT COUNT(*) as count FROM weapons WHERE is_meta = 1")['count'];
$totalHighlights = fetchOne("SELECT COUNT(*) as count FROM community_highlights")['count'];
$totalWeaponTypes = fetchOne("SELECT COUNT(*) as count FROM weapon_types")['count'];
$totalWeaponTags = fetchOne("SELECT COUNT(*) as count FROM weapon_tags")['count'];

// Statistiques de visites
$todayViews = fetchOne("SELECT COUNT(*) as count FROM page_views WHERE DATE(view_time) = CURDATE()")['count'];
$weekViews = fetchOne("SELECT COUNT(*) as count FROM page_views WHERE view_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")['count'];
$monthViews = fetchOne("SELECT COUNT(*) as count FROM page_views WHERE view_time >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)")['count'];
$totalViews = fetchOne("SELECT COUNT(*) as count FROM page_views")['count'];

// Armes récentes
$recentWeapons = fetchAll("SELECT w.*, wt.name as type_name FROM weapons w 
                          LEFT JOIN weapon_types wt ON w.type_id = wt.id 
                          ORDER BY w.updated_at DESC LIMIT 5");

// Highlights récents
$recentHighlights = fetchAll("SELECT * FROM community_highlights ORDER BY updated_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion - StachZer Admin</title>
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
            padding: 12px 24px;
            border-radius: 8px;
            text-transform: uppercase;
        }
        
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            background: linear-gradient(to right, var(--pink-light), var(--blue-light));
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
                <a href="gestion.php" class="flex items-center px-4 py-2 text-pink-300 bg-gray-900">
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
                    <h1 class="text-3xl font-bold">Tableau de bord</h1>
                    
                    <div class="flex items-center">
                        <span class="text-gray-400 mr-2">Bienvenue,</span>
                        <span class="font-semibold"><?php echo $_SESSION['username']; ?></span>
                    </div>
                </div>
                
                <!-- Stats cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gray-800 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-gray-400 text-sm">Arsenal</div>
                                <div class="text-2xl font-bold"><?php echo $totalWeapons; ?> armes</div>
                                <div class="text-green-400 text-sm mt-1"><?php echo $totalMetaWeapons; ?> armes META</div>
                            </div>
                            <div class="bg-pink-500 bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-gun text-pink-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-gray-400 text-sm">Highlights</div>
                                <div class="text-2xl font-bold"><?php echo $totalHighlights; ?> clips</div>
                                <div class="text-blue-400 text-sm mt-1">Communauté</div>
                            </div>
                            <div class="bg-blue-500 bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-film text-blue-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-gray-400 text-sm">Visites aujourd'hui</div>
                                <div class="text-2xl font-bold"><?php echo $todayViews; ?></div>
                                <div class="text-green-400 text-sm mt-1">
                                    <?php echo ($weekViews > 0) ? '+' . round(($todayViews / ($weekViews / 7)) * 100 - 100, 1) : '0'; ?>% vs moyenne
                                </div>
                            </div>
                            <div class="bg-green-500 bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-users text-green-500"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-gray-400 text-sm">Visites (30j)</div>
                                <div class="text-2xl font-bold"><?php echo $monthViews; ?></div>
                                <div class="text-gray-400 text-sm mt-1">Visiteurs</div>
                            </div>
                            <div class="bg-purple-500 bg-opacity-20 rounded-full p-3">
                                <i class="fas fa-chart-line text-purple-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent content -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent weapons -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden">
                        <div class="bg-gray-700 px-4 py-3 flex justify-between items-center">
                            <h2 class="font-semibold">Armes récentes</h2>
                            <a href="weapons/index.php" class="text-sm text-pink-300 hover:underline">Voir tout</a>
                        </div>
                        
                        <div class="p-1">
                            <?php if (count($recentWeapons) > 0): ?>
                                <?php foreach ($recentWeapons as $weapon): ?>
                                    <div class="p-3 hover:bg-gray-700 rounded-lg m-1">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 rounded bg-gray-700 mr-3 flex items-center justify-center">
                                                <i class="fas fa-gun text-gray-500"></i>
                                            </div>
                                            
                                            <div class="flex-grow">
                                                <div class="flex justify-between">
                                                    <div class="font-semibold"><?php echo htmlspecialchars($weapon['name']); ?></div>
                                                    <?php if ($weapon['is_meta']): ?>
                                                        <span class="px-2 py-1 bg-green-500 text-xs rounded-full text-black">META</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-sm text-gray-400"><?php echo htmlspecialchars($weapon['type_name']); ?></div>
                                            </div>
                                            
                                            <a href="weapons/edit.php?id=<?php echo $weapon['id']; ?>" class="text-blue-400 hover:text-blue-300">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="p-4 text-center text-gray-500">
                                    Aucune arme n'a été ajoutée.
                                    <div class="mt-2">
                                        <a href="weapons/add.php" class="text-pink-300 hover:underline">Ajouter une arme</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Recent highlights -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden">
                        <div class="bg-gray-700 px-4 py-3 flex justify-between items-center">
                            <h2 class="font-semibold">Highlights récents</h2>
                            <a href="highlights/index.php" class="text-sm text-pink-300 hover:underline">Voir tout</a>
                        </div>
                        
                        <div class="p-1">
                            <?php if (count($recentHighlights) > 0): ?>
                                <?php foreach ($recentHighlights as $highlight): ?>
                                    <div class="p-3 hover:bg-gray-700 rounded-lg m-1">
                                        <div class="flex items-center">
                                            <div class="w-16 h-12 rounded bg-gray-700 mr-3 flex items-center justify-center">
                                                <i class="fas fa-film text-gray-500"></i>
                                            </div>
                                            
                                            <div class="flex-grow">
                                                <div class="font-semibold"><?php echo htmlspecialchars($highlight['title']); ?></div>
                                                <div class="text-sm text-gray-400">
                                                    <span class="inline-flex items-center">
                                                        <div class="w-4 h-4 rounded-full mr-1" style="background-color: <?php echo htmlspecialchars($highlight['color_code']); ?>;"></div>
                                                        <?php echo htmlspecialchars($highlight['author_name']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <a href="highlights/edit.php?id=<?php echo $highlight['id']; ?>" class="text-blue-400 hover:text-blue-300">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="p-4 text-center text-gray-500">
                                    Aucun highlight n'a été ajouté.
                                    <div class="mt-2">
                                        <a href="highlights/add.php" class="text-pink-300 hover:underline">Ajouter un highlight</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="mt-6">
                    <div class="bg-gray-800 rounded-lg overflow-hidden">
                        <div class="bg-gray-700 px-4 py-3">
                            <h2 class="font-semibold">Statistiques de visites</h2>
                        </div>
                        
                        <div class="p-6 text-center">
                            <div class="mb-4">
                                <div class="inline-block bg-gray-700 rounded-lg overflow-hidden">
                                    <button class="px-4 py-2 bg-pink-300 text-gray-900">Aujourd'hui</button>
                                    <button class="px-4 py-2 text-gray-300">7 jours</button>
                                    <button class="px-4 py-2 text-gray-300">30 jours</button>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-gray-700 rounded-lg">
                                <div class="text-3xl font-bold mb-2"><?php echo $todayViews; ?></div>
                                <div class="text-sm text-gray-400">Visites aujourd'hui</div>
                                
                                <div class="mt-4 flex justify-between items-center">
                                    <div>00:00</div>
                                    <div class="h-12 flex-grow mx-2 bg-gray-800 rounded">
                                        <div class="h-2 mt-5 bg-gradient-to-r from-pink-300 to-blue-300 rounded" style="width: 75%"></div>
                                    </div>
                                    <div>23:59</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
