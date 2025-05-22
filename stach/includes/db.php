<?php
// db.php - Gestion de la connexion à la base de données
require_once 'config.php';

function connectDB() {
    try {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        
        if ($mysqli->connect_error) {
            // Afficher l'erreur au lieu de la journaliser
            die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
        }
        
        $mysqli->set_charset("utf8mb4");
        return $mysqli;
    } catch (Exception $e) {
        die("Exception lors de la connexion : " . $e->getMessage());
    }
}

// Fonction pour exécuter une requête
function executeQuery($sql, $params = [], $types = "") {
    $db = connectDB();
    $stmt = $db->prepare($sql);
    
    if (!$stmt) {
        error_log("Erreur de préparation de la requête: " . $db->error);
        die("Une erreur est survenue lors de la préparation de la requête.");
    }
    
    if (!empty($params)) {
        if (empty($types)) {
            // Détermination automatique des types
            $types = "";
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= "i";
                } elseif (is_double($param)) {
                    $types .= "d";
                } elseif (is_string($param)) {
                    $types .= "s";
                } else {
                    $types .= "b";
                }
            }
        }
        
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    $stmt->close();
    $db->close();
    
    return $result;
}

// Fonction pour obtenir une seule ligne
function fetchOne($sql, $params = [], $types = "") {
    $result = executeQuery($sql, $params, $types);
    return $result ? $result->fetch_assoc() : null;
}

// Fonction pour obtenir toutes les lignes
function fetchAll($sql, $params = [], $types = "") {
    $result = executeQuery($sql, $params, $types);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Fonction pour insérer des données
function insertData($table, $data) {
    $db = connectDB();
    
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), "?"));
    
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $db->prepare($sql);
    
    if (!$stmt) {
        error_log("Erreur de préparation de l'insertion: " . $db->error);
        die("Une erreur est survenue lors de la préparation de l'insertion.");
    }
    
    $types = "";
    $values = [];
    
    foreach ($data as $value) {
        if (is_int($value)) {
            $types .= "i";
        } elseif (is_double($value)) {
            $types .= "d";
        } elseif (is_string($value)) {
            $types .= "s";
        } else {
            $types .= "b";
        }
        $values[] = $value;
    }
    
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    
    $insertId = $db->insert_id;
    
    $stmt->close();
    $db->close();
    
    return $insertId;
}

// Fonction pour mettre à jour des données
function updateData($table, $data, $conditions) {
    $db = connectDB();
    
    $setClause = [];
    foreach (array_keys($data) as $column) {
        $setClause[] = "$column = ?";
    }
    
    $whereClause = [];
    foreach (array_keys($conditions) as $column) {
        $whereClause[] = "$column = ?";
    }
    
    $sql = "UPDATE $table SET " . implode(", ", $setClause) . " WHERE " . implode(" AND ", $whereClause);
    $stmt = $db->prepare($sql);
    
    if (!$stmt) {
        error_log("Erreur de préparation de la mise à jour: " . $db->error);
        die("Une erreur est survenue lors de la préparation de la mise à jour.");
    }
    
    $types = "";
    $values = [];
    
    foreach ($data as $value) {
        if (is_int($value)) {
            $types .= "i";
        } elseif (is_double($value)) {
            $types .= "d";
        } elseif (is_string($value)) {
            $types .= "s";
        } else {
            $types .= "b";
        }
        $values[] = $value;
    }
    
    foreach ($conditions as $value) {
        if (is_int($value)) {
            $types .= "i";
        } elseif (is_double($value)) {
            $types .= "d";
        } elseif (is_string($value)) {
            $types .= "s";
        } else {
            $types .= "b";
        }
        $values[] = $value;
    }
    
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    
    $affectedRows = $stmt->affected_rows;
    
    $stmt->close();
    $db->close();
    
    return $affectedRows;
}

// Fonction pour supprimer des données
function deleteData($table, $conditions) {
    $db = connectDB();
    
    $whereClause = [];
    foreach (array_keys($conditions) as $column) {
        $whereClause[] = "$column = ?";
    }
    
    $sql = "DELETE FROM $table WHERE " . implode(" AND ", $whereClause);
    $stmt = $db->prepare($sql);
    
    if (!$stmt) {
        error_log("Erreur de préparation de la suppression: " . $db->error);
        die("Une erreur est survenue lors de la préparation de la suppression.");
    }
    
    $types = "";
    $values = [];
    
    foreach ($conditions as $value) {
        if (is_int($value)) {
            $types .= "i";
        } elseif (is_double($value)) {
            $types .= "d";
        } elseif (is_string($value)) {
            $types .= "s";
        } else {
            $types .= "b";
        }
        $values[] = $value;
    }
    
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    
    $affectedRows = $stmt->affected_rows;
    
    $stmt->close();
    $db->close();
    
    return $affectedRows;
}
?>