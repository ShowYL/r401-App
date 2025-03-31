<?php

    require_once '../model/JoueurModel.php';
    require_once '../model/utils.php';

    header('Content-Type:application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
    
    if(!checkToken()){
        deliver_response(402, 'Token false');
        exit();
    }

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            if (isset($_GET['Id_Joueur'])) {
                $joueur = getJoueur($_GET['Id_Joueur']);
                deliver_response(200, "Joueur found", $joueur);
            } else {
                $joueurs = getAllJoueurs();
                deliver_response(200, "Joueur found", $joueurs);
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['licence']) && isset($data['nom']) && isset($data['prenom']) && isset($data['taille']) && isset($data['poids']) && isset($data['date_naissance']) && isset($data['statut']) && isset($data['commentaire']))
                if(createJoueur($data['licence'], $data['nom'], $data['prenom'], $data['taille'], $data['poids'], $data['date_naissance'], $data['statut'], $data['commentaire']))
                    deliver_response(200, "Joueur created", $data);
                else
                    deliver_response(500, "internal server error", $data);
            else
                deliver_response(401, "Invalid request", $data);
            break;
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['Id_Joueur']) && isset($data['licence']) && isset($data['nom']) && isset($data['prenom']) && isset($data['taille']) && isset($data['poids']) && isset($data['date_naissance']) && isset($data['statut']) && isset($data['commentaire']))
                if(updateJoueur($data['licence'], $data['nom'], $data['prenom'], $data['taille'], $data['poids'], $data['date_naissance'], $data['statut'], $data['commentaire'], $data['Id_Joueur']))
                    deliver_response(200, "Joueur updated", $data);
                else
                    deliver_response(500, "internal server error", $data);
            else
                deliver_response(401, "Invalid request", $data);
            break;
        case 'DELETE':  
            $data = json_decode(file_get_contents('php://input'), true);
            if(array_key_exists('Id_Joueur', $data))
                if(deleteJoueur($data['Id_Joueur']))
                    deliver_response(200, "Joueur deleted", $data);
                else
                    deliver_response(500, "internal server error", $data);
            else
                deliver_response(401, "Invalid request", $data);
            break;
    }

?>