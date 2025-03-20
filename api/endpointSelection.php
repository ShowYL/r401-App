<?php

    require_once '../model/SelectionModel.php';
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
        deliver_response(401, 'Unauthorized');
        exit();
    }

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            if (isset($_GET['idMatch'])) {
                $Selection = getSelection($_GET['idMatch']);
                echo json_encode($Selection);
            } else {
                $Selection = getAllSelection();
                echo json_encode($Selection);
            }
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['idJoueur']) && isset($data['idMatch']) && isset($data['titulaire']) && isset($data['poste']))
                if(createSelection($data['idJoueur'], $data['idMatch'], $data['titulaire'], $data['poste']))
                    deliver_response(200, "Selection created");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(401, "Invalid request");
            break;
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['idJoueur']) && isset($data['idMatch']) && isset($data['titulaire']) && isset($data['poste']))
                if(updateSelection($data['idJoueur'], $data['idMatch'], $data['titulaire'], $data['poste']))
                    deliver_response(200, "Selection updated");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(401, "Invalid request");
            break;
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['idSelection']))
                if(deleteSelection($data['idSelection']))
                    deliver_response(200, "Selection deleted");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(401, "Invalid request");
            break;
    }
?>