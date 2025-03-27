<?php 
    require_once '../model/MatchModel.php';
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
            if (isset($_GET['Id_Match'])) {
                $match = getMatch($_GET['Id_Match']);
                deliver_response(200, "Match found", $match);
            } else {
                $matchs = getAllMatchs();
                deliver_response(200, "Matchs found", $matchs);
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['Date_Match']) && isset($data['Heure_Match']) && isset($data['Equipe_Adverse']) && isset($data['Lieu']) && isset($data['Résultat']))
               if(createMatch($data['Date_Match'], $data['Heure_Match'], $data['Equipe_Adverse'], $data['Lieu'], $data['Résultat']))
                    deliver_response(200, "Match created");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(401, "Invalid request", $data);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['Id_Match']) && isset($data['Date_Match']) && isset($data['Heure_Match']) && isset($data['Equipe_Adverse']) && isset($data['Lieu']) && isset($data['Résultat']))
                if(upDate_MatchMatch($data['Id_Match'], $data['Date_Match'], $data['Heure_Match'], $data['Equipe_Adverse'], $data['Lieu'], $data['Résultat']))
                    deliver_response(200, "Match upDate_Matchd");
                else
                    deliver_response(500, "internal server error");
            else
                deliver_response(401, "Invalid request", $data);
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['Id_Match']))
                if(deleteMatch($data['Id_Match']))
                    deliver_response(200, "Match deleted");
                else
                    deliver_response(500, "internal server error");
            else 
                deliver_response(401, "Invalid request", $data);
            break;
    }
?>