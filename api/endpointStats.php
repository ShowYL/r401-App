<?php
    require_once '../model/gestionStats.php';
    require_once '../model/utils.php';

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header('Access-Control-Allow-Headers: Content-Type');

    if(!checkToken()){
        deliver_response(401, 'Unauthorized');
        exit();
    }
    
    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            if (isset($_GET['player_id'])) {
                $playerId = $_GET['player_id'];
                $stats = getPlayerStats($playerId);
                echo json_encode($stats);
            } else {
                $stats = getMatchStats();
                echo json_encode($stats);
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['player_id'])) {
                $playerId = $data['player_id'];
                $consecutiveSelections = getConsecutiveSelections($playerId);
                $preferredPosition = getPreferredPosition($playerId);
                $response = [
                    'consecutive_selections' => $consecutiveSelections,
                    'preferred_position' => $preferredPosition
                ];
                echo json_encode($response);
            } else {
                deliver_response(400, "Invalid request");
            }
            break;
    }
?>