<?php
    require_once '../model/JoueurModel.php';

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            $joueurModel = new JoueurModel();
            $joueurs = $joueurModel->getAllJoueurs();
            echo json_encode($joueurs);
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $joueurModel = new JoueurModel();
            $result = $joueurModel->createJoueur($data['licence'], $data['nom'], $data['prenom'], $data['taille'], $data['poids'], $data['date_naissance'], $data['statut'], $data['commentaire']);
            echo json_encode(['success' => $result]);
            break;
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $joueurModel = new JoueurModel();
            $result = $joueurModel->updateJoueur($data['licence'], $data['nom'], $data['prenom'], $data['taille'], $data['poids'], $data['date_naissance'], $data['statut'], $data['commentaire']);
            echo json_encode(['success' => $result]);
            break;
        case 'DELETE':  
            $data = json_decode(file_get_contents('php://input'), true);
            $joueurModel = new JoueurModel();
            $result = $joueurModel->deleteJoueur($data['licence']);
            echo json_encode(['success' => $result]);
            break;
    }

?>