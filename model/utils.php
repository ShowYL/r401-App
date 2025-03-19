<?php
    function deliver_response($status_code, $status_message, $data=null){

        http_response_code($status_code);
        header('Content-Type:application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');


        $response['status_code'] = $status_code;
        $response['data'] = $data;
        $response['status'] = $status_message;
        
        $json_response = json_encode($response);
        if ($json_response === false){
            die('json encode ERROR : '.json_last_error_msg());
        }

        echo $json_response;
    }

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

        error_log("Authorization Headers: " . print_r($headers, true));
            
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

    function checkToken(){
        error_log("checkToken() called");
    
        $token = get_bearer_token();
        error_log("Extracted Token: " . print_r($token, true));
    
        if ($token === null) {
            error_log("Token is null");
            return false;
        }
        
        $authUrl = "https://chatr410.alwaysdata.net/R401/r401-Auth/api/endpoint.php";
        error_log("Auth URL: " . $authUrl);
    
        // Effectuer la requête GET vers l'API d'authentification avec le token dans le header
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $authUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token
        ));
        error_log("cURL initialized with headers: Authorization: Bearer " . $token);
    
        $response = curl_exec($ch);
        if ($response === false) {
            $curlError = curl_error($ch);
            error_log("cURL Error: " . $curlError);
            curl_close($ch);
            return false;
        }
        error_log("Raw Response: " . print_r($response, true));
    
        curl_close($ch);
        
        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON Decode Error: " . json_last_error_msg());
            return false;
        }
        error_log("Decoded Response: " . print_r($result, true));
    
        // Si l'API d'authentification répond avec un code 200, le token est valide.
        if (isset($result['status_code']) && $result['status_code'] == 200) {
            error_log("Token is valid");
            return true;
        }
    
        error_log("Token is invalid or status_code is not 200");
        return false;
    }
?>