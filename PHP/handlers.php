<?php

require_once('config.php');

function getProfile(){
    $pdo = getPDO();
    $q = $pdo->prepare("SELECT id, specialisation FROM specialisation ORDER BY specialisation ASC");
    $q->execute();
    while($res = $q->fetch()){
        echo("<option value=". $res['specialisation'] . ">". $res['specialisation']."</option>");
    }
}

function getSkills(){
    $pdo = getPDO();
    $q = $pdo->prepare("SELECT id, skill FROM skill ORDER BY skill ASC");
    $q->execute();
    while($res = $q->fetch()){
        // echo("<option value=". $res['id'] . ">". $res['skill']."</option>");
        echo("<option value=". $res['skill'] . ">". $res['skill']."</option>");
    }
}

function getLieu(){
    $pdo = getPDO();
    $q = $pdo->prepare("SELECT id, ville From villes ORDER BY ville ASC");
    $q->execute();
    while($res = $q->fetch()){
        echo("<option value=". $res['ville'] . ">". $res['ville']."</option>");
    }
}

?>