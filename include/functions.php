<?php

// echo '<pre>'; print_r($_SESSION); echo '</pre>';

//Fonction utilisateurs authentifie
//fonction permettant de savoir si l'utilisateur est authentifié sur le site
function userConnected(){

    //si l'indice user dans le fichier de sessions n'est pas définit, cela veut dire que l'internaute n'est pas passé par la page connexion et n'est pas authentifié
    return(isset($_SESSION['member']));

}


//Fonction administrateur authentifie
//fonction permettant de savoir si l'administrateur est authentifié sur le site

function memberConnected(){
    //si à l'indice 'role, la valeur est  egale a member, cela veut dire que c'est un admin, donc retourne true
    return userConnected() && isset($_SESSION['member']['role']) && $_SESSION['member']['role'] === 'member';


}

function adminConnected(){
    //si à l'indice 'role, la valeur est  egale a admin, cela veut dire que c'est un admin, donc retourne true
    return userConnected() && isset($_SESSION['member']['role']) && $_SESSION['member']['role'] === 'admin';
}



function executeRequete($requete, $params = array()){

    //on se connecte à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=annonceo', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

    //on prepare la requete
    $result = $pdo->prepare($requete);

    //on execute la requete
    $result->execute($params);

    //on retourne le resultat
    return $result;
}