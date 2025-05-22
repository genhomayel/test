<?php
echo "<h1>Vérification des extensions PHP</h1>";

$required_extensions = [
    'mysqli',
    'session',
    'json',
    'mbstring',
    'gd'  // Pour la manipulation d'images
];

foreach ($required_extensions as $ext) {
    echo "<p>Extension '$ext': " . (extension_loaded($ext) ? "✅ Installée" : "❌ Non installée") . "</p>";
}

// Vérifier les limites de PHP
echo "<h2>Paramètres importants</h2>";
echo "<p>memory_limit: " . ini_get('memory_limit') . "</p>";
echo "<p>max_execution_time: " . ini_get('max_execution_time') . " secondes</p>";
echo "<p>upload_max_filesize: " . ini_get('upload_max_filesize') . "</p>";
echo "<p>post_max_size: " . ini_get('post_max_size') . "</p>";
?>
