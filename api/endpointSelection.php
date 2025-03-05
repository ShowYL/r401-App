<?php

    require_once '../model/SelectionModel.php';

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header('Access-Control-Allow-Headers: Content-Type');

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            $SelectionModel = new SelectionModel();
            $Selection = $SelectionModel->getAllSelection();
            echo json_encode($Selection);
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $SelectionModel = new SelectionModel();
            $result = $SelectionModel->createSelection($data['idJoueur'], $data['idMatch'], $data['titulaire'], $data['poste']);
            echo json_encode(['success' => $result]);
            break;
    }
?>