<?php

const DB_HOST = 'mysql-lestitansdesete.alwaysdata.net';
const DB_NAME = 'lestitansdesete_bdapp';
const DB_USER = '385432';
const DB_PASS = '$iutinfo';

function getDBCon(){
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        
        // Set error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
        
    } catch(PDOException $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
        exit();
    }
}

?>