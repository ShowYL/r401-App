<?php 
    require_once '../model/MatchModel.php';
    require_once '../model/utils.php';

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');


    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            $matchs = getAllMatchs();
            echo json_encode($matchs);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['date']) && isset($data['heure']) && isset($data['adversaire']) && isset($data['lieu']) && isset($data['resultat']))
               if(createMatch($data['date'], $data['heure'], $data['adversaire'], $data['lieu'], $data['resultat']))
                    deliver_response(200, "Match created");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(401, "Invalid request");
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['idMatch']) && isset($data['date']) && isset($data['heure']) && isset($data['adversaire']) && isset($data['lieu']) && isset($data['resultat']))
                if(updateMatch($data['idMatch'], $data['date'], $data['heure'], $data['adversaire'], $data['lieu'], $data['resultat']))
                    deliver_response(200, "Match updated");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(401, "Invalid request");
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['idMatch']))
                if(deleteMatch($data['idMatch']))
                    deliver_response(200, "Match deleted");
                else
                    deliver_response(500, "internal server error");
            else 
                deliver_response(401, "Invalid request");
            break;
    }
?>