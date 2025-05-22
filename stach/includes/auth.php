<?php
// auth.php - Fonctions d'authentification
require_once 'db.php';

// Vérification de l'authentification
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Redirection si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// Fonction de connexion
function login($username, $password) {
    $user = fetchOne("SELECT id, username, password FROM users WHERE username = ?", [$username]);
    
    if (!$user) {
        return false;
    }
    
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Enregistrement de la connexion
        insertData('login_logs', [
            'user_id' => $user['id'],
            'login_time' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ]);
        
        return true;
    }
    
    return false;
}

// Fonction de déconnexion
function logout() {
    if (isLoggedIn()) {
        // Enregistrement de la déconnexion
        insertData('login_logs', [
            'user_id' => $_SESSION['user_id'],
            'logout_time' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ]);
        
        // Suppression des variables de session
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
    }
    
    // Destruction complète de la session
    session_destroy();
}

// Fonction d'inscription (pour créer un utilisateur admin)
function registerUser($username, $password, $email) {
    // Vérification de l'unicité du nom d'utilisateur
    $existingUser = fetchOne("SELECT id FROM users WHERE username = ?", [$username]);
    
    if ($existingUser) {
        return [
            'success' => false,
            'message' => 'Ce nom d\'utilisateur est déjà utilisé.'
        ];
    }
    
    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertion de l'utilisateur
    $userId = insertData('users', [
        'username' => $username,
        'password' => $hashedPassword,
        'email' => $email
    ]);
    
    if ($userId) {
        return [
            'success' => true,
            'message' => 'Utilisateur créé avec succès.',
            'user_id' => $userId
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Erreur lors de la création de l\'utilisateur.'
        ];
    }
}
?>