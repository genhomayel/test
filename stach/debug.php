<?php
// Afficher les erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Informations de base
echo "<h1>Test de connexion</h1>";
echo "<p>PHP version: " . phpversion() . "</p>";

// Tester la connexion à la base de données
try {
    $mysqli = new mysqli('game-link.eu', 'vpn_user', 'YvanLea5404', 'stachzer', 7999);
    
    if ($mysqli->connect_error) {
        die("<p style='color:red'>ÉCHEC: " . $mysqli->connect_error . "</p>");
    } else {
        echo "<p style='color:green'>SUCCÈS: Connexion à la base de données établie.</p>";
        
        // Vérifier les tables
        $result = $mysqli->query("SHOW TABLES");
        
        echo "<h2>Tables dans la base de données:</h2><ul>";
        while ($row = $result->fetch_array()) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
        
        // Vérifier si la table users existe et contient des données
        $result = $mysqli->query("SELECT COUNT(*) as count FROM users");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>Nombre d'utilisateurs : " . $row['count'] . "</p>";
        } else {
            echo "<p style='color:red'>La table 'users' n'existe pas ou est vide.</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Exception: " . $e->getMessage() . "</p>";
}
?>
