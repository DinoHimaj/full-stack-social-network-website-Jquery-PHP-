<?php

$hostDetails = 'mysql:host=localhost;dbname=facebook; charset=utf8mb4';
$userAdmin = 'root';
$pass = '';

try{
    $pdo = new PDO($hostDetails, $userAdmin, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'Connection failed: ' . $e->getMessage();
}



?>