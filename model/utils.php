<?php
    function deliver_response($status_code, $status_message, $data=null){

        http_response_code($status_code);
        header('Content-Type:application/json; charset=utf-8');

        $response['status_code'] = $status_code;
        $response['data'] = $data;
        $response['status'] = $status_message;
        
        $json_response = json_encode($response);
        if ($json_response === false){
            die('json encode ERROR : '.json_last_error_msg());
        }

        echo $json_response;
    }

    // function checkToken(){
    //     // Récupération des headers HTTP
    //     $headers = getallheaders();
    //     if(!isset($headers['Authorization'])){
    //         return false;
    //     }
        
    //     $authHeader = $headers['Authorization'];
    //     // Vérifier que le header est bien au format "Bearer <token>"
    //     if(strpos($authHeader, "Bearer ") !== 0){
    //         return false;
    //     }
        
    //     $token = trim(substr($authHeader, 7));
        
    //     // URL de votre endpoint d'authentification (adaptée à votre environnement)
    //     $authUrl = "http://localhost:3001/api/endpoint.php?data=" . urlencode($token);
        
    //     // Effectuer la requête GET vers l'API d'authentification
    //     $response = @file_get_contents($authUrl);
    //     if($response === false){
    //         return false;
    //     }
        
    //     $result = json_decode($response, true);
    //     // Si l'API d'authentification répond avec un code 200, le token est valide.
    //     if(isset($result['status_code']) && $result['status_code'] == 200){
    //         return true;
    //     }
        
    //     return false;
    // }
    

    function get_authorization_header(){
        $headers = null;
        
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } else if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
             //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
            }
        }
        
        return $headers;
    }
        
    function get_bearer_token() {
        $headers = get_authorization_header();
            
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                if($matches[1]=='null') //$matches[1] est de type string et peut contenir 'null'
                    return null;
                else
                    return $matches[1];
            }
        }            
        return null;
    }
?>