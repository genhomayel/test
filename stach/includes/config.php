<?php
// config.php - Configuration de base
session_start();

// Configuration de la base de données
define('DB_HOST', 'game-link.eu');
define('DB_PORT', 7999); // Ajout du port
define('DB_USER', 'vpn_user');
define('DB_PASS', 'YvanLea5404');
define('DB_NAME', 'stachzer');

// Configuration du site
define('SITE_URL', 'https://stachzer.game-link.eu/'); // Remplacer par votre URL réelle
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');


// Configuration de sécurité
define('HASH_SALT', '123'); // À remplacer par une valeur aléatoire

// Gestion des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
