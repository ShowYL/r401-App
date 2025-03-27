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
            if (isset($_GET['ID_Selection'])) {
                $Selection = getSelection($_GET['ID_Selection']);
                deliver_response(200, "Selection found", $Selection);
            } else if (isset($_GET['Id_Joueur']) && isset($_GET['Id_Match'])) {
                $Selection = getSelectionByPlayerAndMatch($_GET['Id_Joueur'], $_GET['Id_Match']);
                deliver_response(200, "Selection found", $Selection);
            }else if (isset($_GET['Id_Match'])) {
                $Selection = getPlayersByMatch($_GET['Id_Match']);
                deliver_response(200, "Player in Selection found", $Selection);
            } else {
                $Selection = getAllSelection();
                deliver_response(200, "Selection found", $Selection);
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['Id_Joueur']) && isset($data['Id_Match']) && isset($data['Titulaire']) && isset($data['Poste']))
                if(createSelection($data['Id_Joueur'], $data['Id_Match'], $data['Titulaire'], $data['Poste']))
                    deliver_response(200, "Selection created", $data);
                else
                    deliver_response(500, "internal server error", $data);
            else
                deliver_response(401, "Invalid request", $data);
            break;
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['Id_Joueur']) && isset($data['Id_Match']) && isset($data['Titulaire']) && isset($data['Poste']) && isset($data['Note']))
                if(updateSelection($data['Id_Joueur'], $data['Id_Match'], $data['Titulaire'], $data['Poste'], $data['Note']))
                    deliver_response(200, "Selection updated", $data);
                else
                    deliver_response(500, "internal server error",  $data);
            else
                deliver_response(401, "Invalid request",   $data);
            break;
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['ID_Selection']))
                if(deleteSelection($data['ID_Selection']))
                    deliver_response(200, "Selection deleted", $data);
                else
                    deliver_response(500, "internal server error", $data);
            if(isset($data['Id_Joueur']) && isset($data['Id_Match']))
                if(deleteSelectionByPlayerAndMatch($data['Id_Joueur'], $data['Id_Match']))
                    deliver_response(200, "Selection deleted", $data);
                else
                    deliver_response(500, "internal server error", $data);
            else
                deliver_response(401, "Invalid request",   $data);
            break;
    }
?>