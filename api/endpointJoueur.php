<?php
    require_once '../model/JoueurModel.php';
    require_once '../model/utils.php';

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    if(!checkToken()){
        deliver_response(401, 'Unauthorized');
        exit();
    }

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            $joueurs = getAllJoueurs();
            echo json_encode($joueurs);
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['licence']) && isset($data['nom']) && isset($data['prenom']) && isset($data['taille']) && isset($data['poids']) && isset($data['date_naissance']) && isset($data['statut']) && isset($data['commentaire']))
                if(createJoueur($data['licence'], $data['nom'], $data['prenom'], $data['taille'], $data['poids'], $data['date_naissance'], $data['statut'], $data['commentaire']))
                    deliver_response(200, "Joueur created");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(401, "Invalid request");
            break;
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['licence']) && isset($data['nom']) && isset($data['prenom']) && isset($data['taille']) && isset($data['poids']) && isset($data['date_naissance']) && isset($data['statut']) && isset($data['commentaire']) && isset($data['id']))
                if(updateJoueur($data['licence'], $data['nom'], $data['prenom'], $data['taille'], $data['poids'], $data['date_naissance'], $data['statut'], $data['commentaire'], $data['id']))
                    deliver_response(200, "Joueur updated");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(401, "Invalid request");
            break;
        case 'DELETE':  
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['id']))
                if(deleteJoueur($data['id']))
                    deliver_response(200, "Joueur deleted");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(401, "Invalid request");
            break;
    }

?>