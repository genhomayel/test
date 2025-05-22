<?php
// record-view.php - Enregistrement asynchrone des vues de page
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

// Définir cet appel comme AJAX pour éviter les boucles infinies
$_SESSION['is_ajax'] = true;

// Récupérer la page demandée
$page = isset($_GET['page']) ? sanitizeInput($_GET['page']) : 'unknown';

// Enregistrer la visite
recordPageView($page);

// Répondre avec un code de statut 200
http_response_code(200);
?>
