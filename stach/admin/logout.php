<?php
// logout.php - Déconnexion
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Déconnecter l'utilisateur
logout();

// Redirection vers la page de connexion
header("Location: login.php");
exit;
?>
