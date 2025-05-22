<?php
header('Content-Type: application/json');

// Fonction pour vérifier si un streamer est en direct
function isStreamerLive($username) {
    // Permet de mettre en cache le résultat pour 2 minutes
    $cacheFile = sys_get_temp_dir() . '/twitch_' . $username . '_cache.json';
    
    // Utiliser le cache si disponible et récent (moins de 2 minutes)
    if (file_exists($cacheFile)) {
        $cacheData = json_decode(file_get_contents($cacheFile), true);
        if ($cacheData && isset($cacheData['checked_at']) && 
            $cacheData['checked_at'] > (time() - 120)) {
            return $cacheData['is_live'];
        }
    }
    
    $url = "https://www.twitch.tv/{$username}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    // Vérifier si la page contient des indicateurs que le streamer est en direct
    $isLive = strpos($response, 'isLiveBroadcast') !== false || 
              strpos($response, '"isLive":true') !== false || 
              strpos($response, 'tw-channel-status-indicator--live') !== false;
    
    // Mettre en cache le résultat
    $cacheData = [
        'is_live' => $isLive,
        'checked_at' => time()
    ];
    file_put_contents($cacheFile, json_encode($cacheData));
    
    return $isLive;
}

$username = 'stachzer';
$isLive = isStreamerLive($username);

echo json_encode([
    'is_live' => $isLive,
    'username' => $username,
    'checked_at' => date('Y-m-d H:i:s')
]);
