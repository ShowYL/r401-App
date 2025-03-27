<?php
    require_once '../model/gestionStats.php';
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
            if (isset($_GET['Id_Joueur'])) {
                $playerId = $_GET['Id_Joueur'];
                $playerStats = getPlayerStats($playerId);
                $consecutiveSelections = getConsecutiveSelections($playerId);
                $preferredPosition = getPreferredPosition($playerId);
                $stats = [
                    'player_stats' => $playerStats,
                    'consecutive_selections' => $consecutiveSelections,
                    'preferred_position' => $preferredPosition
                ];
                echo json_encode($stats);
            } else {
                $stats = getMatchStats();
                echo json_encode($stats);
            }
            break;
        default:
            deliver_response(405, 'Method Not Allowed');
            break;
    }
?>