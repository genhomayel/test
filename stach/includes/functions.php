<?php
// functions.php - Fonctions utilitaires
require_once 'db.php';
function displayText($text) {
    return html_entity_decode(htmlspecialchars_decode($text));
}
// Fonction pour sécuriser les entrées
function sanitizeInput($input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = sanitizeInput($value);
        }
    } else {
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
    return $input;
}

// Fonction pour générer un slug
function createSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Fonction pour télécharger une image
function uploadImage($file, $directory = 'weapons') {
    $targetDir = UPLOAD_DIR . $directory . '/';
    
    // Création du répertoire si nécessaire
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    // Génération d'un nom de fichier unique
    $filename = uniqid() . '_' . basename($file['name']);
    $targetFile = $targetDir . $filename;
    $uploadUrl = UPLOAD_URL . $directory . '/' . $filename;
    
    // Vérification du type de fichier
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp") {
        return [
            'success' => false,
            'message' => 'Seuls les fichiers JPG, JPEG, PNG, WEBP et GIF sont autorisés.'
        ];
    }
    
    // Vérification de la taille du fichier (5MB max)
    if ($file['size'] > 5000000) {
        return [
            'success' => false,
            'message' => 'Le fichier est trop volumineux (max 5MB).'
        ];
    }
    
    // Tentative de téléchargement
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return [
            'success' => true,
            'message' => 'Image téléchargée avec succès.',
            'file_url' => $uploadUrl,
            'file_path' => $targetFile
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Une erreur est survenue lors du téléchargement de l\'image.'
        ];
    }
}

// Fonction pour gérer les étiquettes (tags)
function getWeaponTags() {
    return fetchAll("SELECT * FROM weapon_tags ORDER BY name ASC");
}

function createWeaponTag($name, $color) {
    $slug = createSlug($name);
    
    return insertData('weapon_tags', [
        'name' => $name,
        'slug' => $slug,
        'color' => $color
    ]);
}

// Fonction pour les types d'armes
function getWeaponTypes() {
    return fetchAll("SELECT * FROM weapon_types ORDER BY name ASC");
}

// Fonctions pour les statistiques de visites
function recordPageView($page) {
    insertData('page_views', [
        'page' => $page,
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        'view_time' => date('Y-m-d H:i:s')
    ]);
}

function getPageViews($period = 'all') {
    $sql = "SELECT page, COUNT(*) as count FROM page_views";
    
    if ($period == 'today') {
        $sql .= " WHERE DATE(view_time) = CURDATE()";
    } elseif ($period == 'week') {
        $sql .= " WHERE view_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
    } elseif ($period == 'month') {
        $sql .= " WHERE view_time >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
    }
    
    $sql .= " GROUP BY page ORDER BY count DESC";
    
    return fetchAll($sql);
}

// Fonction pour formater la date
function formatDate($date) {
    $timestamp = strtotime($date);
    return date('d/m/Y à H:i', $timestamp);
}
?>