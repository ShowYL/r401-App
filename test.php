<?php

require_once 'model/utils.php';

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


    if (isset($responseData['data'])) {
        return $responseData['data'];
    } else {
        throw new Exception('Authentication failed: ' . $responseData['message']);
    }
}

function useDatabase($token) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'http://localhost:3001/api/endpointJoueur.php'); // Set the URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if (isset($responseData['data'])) {
        echo "Données récupérées : \n";
        print_r($responseData['data']);
    } else {
        throw new Exception('Failed to retrieve data: ' . $responseData['message']);
    }
}


// test 
try {
    $authUrl = 'http://localhost:3001/api/endpoint.php';
    $username = 'arthur';
    $password = 'caca';

    echo "Tentative de récupération du token...\n";
    $token = getAuthToken($authUrl, $username, $password);
    
    // Afficher le token récupéré
    $bearer_token = '';
    $bearer_token = get_bearer_token();

    if(is_jwt_valid($token)){
        echo "Token valide\n";
    } else {
        echo "Token invalide\n";
    }
    
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}

?>