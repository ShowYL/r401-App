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
?>