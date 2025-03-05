<?php 
    require_once '../model/MatchModel.php';
    require_once '../model/utils/responsedata.php';

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            $matchModel = new MatchModel();
            $matchs = $matchModel->getAllMatchs();
            echo json_encode($matchs);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $matchModel = new MatchModel();
            if(isset($data['date']) && isset($data['heure']) && isset($data['adversaire']) && isset($data['lieu']) && isset($data['resultat']))
               if(createMatch($data['date'], $data['heure'], $data['adversaire'], $data['lieu'], $data['resultat']))
                    deliver_response(200, "Match created");
                else
                    deliver_response(500, "internal server errore");
            else
                deliver_response(400, "Invalid request");
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $matchModel = new MatchModel();
            if(isset($data['idMatch']) && isset($data['date']) && isset($data['heure']) && isset($data['adversaire']) && isset($data['lieu']) && isset($data['resultat']))
                if($matchModel->updateMatch($data['idMatch'], $data['date'], $data['heure'], $data['adversaire'], $data['lieu'], $data['resultat']))
                    deliver_response(200, "Match updated");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(400, "Invalid request");
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $matchModel = new MatchModel();
            if(isset($data['idMatch']))
                if($matchModel->deleteMatch($data['idMatch']))
                    deliver_response(200, "Match deleted");
                else
                    deliver_response(500, "internal server error");
            else 
                deliver_response(400, "Invalid request");
            break;
        
    }
?>