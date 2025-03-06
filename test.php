<?php

function getAuthToken($url, $username, $password) {
    $ch = curl_init();

    $data = json_encode([
        'username' => $username,
        'password' => $password
    ]);

    curl_setopt($ch, CURLOPT_URL, $url); // Set the URL
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if (isset($responseData['token'])) {
        return $responseData['token'];
    } else {
        throw new Exception('Authentication failed: ' . $responseData['message']);
    }
}

function useDatabase($token) {
    echo "Utilisation du token pour accéder à la base de données : " . $token;
}


// test 
try {
    $authUrl = 'http://localhost:3001/api/endpoint.php';
    $username = 'arthur';
    $password = 'caca';

    $token = getAuthToken($authUrl, $username, $password);
    
    // Afficher le token récupéré
    echo "Token récupéré : " . $token . "\n";
    
    useDatabase($token);
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}

?>