<?php
session_start();

//--Connexion BDD

$connect_db = new PDO('mysql:host=localhost;dbname=annonceo', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
]);



// echo '<pre>';print_r($connect_db); echo'</pre>';



require_once('functions.php');