<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Vérification de l'authentification
requireLogin();

// Récupérer les statistiques des pages les plus visitées
$pageViews = fetchAll("
    SELECT page, COUNT(*) as count 
    FROM page_views 
    GROUP BY page 
    ORDER BY count DESC 
    LIMIT 10
");

// Statistiques par jour (30 derniers jours)
$dailyStats = fetchAll("
    SELECT 
        DATE(view_time) as date, 
        COUNT(*) as count 
    FROM page_views 
    WHERE view_time >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(view_time) 
    ORDER BY date ASC
");

// Statistiques des connexions
$loginStats = fetchAll("
    SELECT 
        u.username, 
        COUNT(l.id) as login_count,
        MAX(l.login_time) as last_login
    FROM login_logs l
    JOIN users u ON l.user_id = u.id
    WHERE l.login_time IS NOT NULL
    GROUP BY u.username
    ORDER BY login_count DESC
");

// Total des visites
$totalViews = fetchOne("SELECT COUNT(*) as count FROM page_views")['count'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - StachZer Admin</title>
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
        
        .stats-tab.active {
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
                
                <a href="stats.php" class="flex items-center px-4 py-2 bg-gray-900 text-pink-300">
                    <i class="fas fa-chart-line w-6"></i>
                    <span>Statistiques</span>
                </a>
                
                <a href="settings.php" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700">
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
                    <h1 class="text-3xl font-bold">Statistiques</h1>
                    
                    <div class="flex items-center">
                        <span class="text-gray-400 mr-2">Total des visites:</span>
                        <span class="font-semibold"><?php echo number_format($totalViews); ?></span>
                    </div>
                </div>
                
                <!-- Stats cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Page views -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden">
                        <div class="bg-gray-700 px-4 py-3">
                            <h2 class="font-semibold">Pages les plus visitées</h2>
                        </div>
                        <div class="p-4">
                            <?php if (empty($pageViews)): ?>
                                <p class="text-gray-400 text-center py-6">Aucune donnée de visite disponible.</p>
                            <?php else: ?>
                                <div class="space-y-3">
                                    <?php foreach ($pageViews as $index => $view): ?>
                                        <div class="bg-gray-700 bg-opacity-50 p-3 rounded flex items-center justify-between">
                                            <div>
                                                <span class="font-medium"><?php echo htmlspecialchars($view['page']); ?></span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-sm"><?php echo number_format($view['count']); ?> visites</span>
                                                <div class="ml-3 w-16 bg-gray-600 h-2 rounded-full">
                                                    <?php
                                                    // Calculer le pourcentage par rapport au plus grand nombre
                                                    $maxCount = $pageViews[0]['count'];
                                                    $percentage = ($view['count'] / $maxCount) * 100;
                                                    ?>
                                                    <div class="h-full bg-gradient-to-r from-pink-300 to-blue-300 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Login stats -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden">
                        <div class="bg-gray-700 px-4 py-3">
                            <h2 class="font-semibold">Statistiques de connexion</h2>
                        </div>
                        <div class="p-4">
                            <?php if (empty($loginStats)): ?>
                                <p class="text-gray-400 text-center py-6">Aucune donnée de connexion disponible.</p>
                            <?php else: ?>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-gray-700 bg-opacity-50">
                                            <tr>
                                                <th class="py-2 px-4 text-left">Utilisateur</th>
                                                <th class="py-2 px-4 text-center">Connexions</th>
                                                <th class="py-2 px-4 text-right">Dernière connexion</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-700">
                                            <?php foreach ($loginStats as $stat): ?>
                                                <tr>
                                                    <td class="py-2 px-4"><?php echo htmlspecialchars($stat['username']); ?></td>
                                                    <td class="py-2 px-4 text-center"><?php echo number_format($stat['login_count']); ?></td>
                                                    <td class="py-2 px-4 text-right"><?php echo date('d/m/Y H:i', strtotime($stat['last_login'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Daily chart -->
                <div class="bg-gray-800 rounded-lg overflow-hidden">
                    <div class="bg-gray-700 px-4 py-3">
                        <h2 class="font-semibold">Visites quotidiennes (30 derniers jours)</h2>
                    </div>
                    <div class="p-4">
                        <?php if (empty($dailyStats)): ?>
                            <p class="text-gray-400 text-center py-6">Aucune donnée quotidienne disponible.</p>
                        <?php else: ?>
                            <div class="h-64 relative">
                                <canvas id="dailyChart"></canvas>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!empty($dailyStats)): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Préparer les données pour le graphique
        const dailyData = <?php 
            $labels = [];
            $values = [];
            
            foreach ($dailyStats as $stat) {
                $labels[] = date('d/m', strtotime($stat['date']));
                $values[] = $stat['count'];
            }
            
            echo json_encode([
                'labels' => $labels,
                'values' => $values
            ]);
        ?>;
        
        // Créer le graphique
        const ctx = document.getElementById('dailyChart').getContext('2d');
        const dailyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyData.labels,
                datasets: [{
                    label: 'Visites',
                    data: dailyData.values,
                    backgroundColor: 'rgba(255, 214, 224, 0.2)',
                    borderColor: 'rgba(255, 214, 224, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#a0aec0'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#a0aec0'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
