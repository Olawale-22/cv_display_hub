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
    $q = $pdo->prepare("SELECT id, skill FROM skills ORDER BY skill ASC");
    $q->execute();
    while($res = $q->fetch()){
        // echo("<option value=". $res['id'] . ">". $res['skill']."</option>");
        echo("<option value=". $res['skill'] . ">". $res['skill']."</option>");
    }
}

function getDisponibility() {
    $pdo = getPDO();
    $q = $pdo->prepare("SELECT id, disponibility FROM availability ORDER BY id ASC");
    $q->execute();
    while ($res = $q->fetch()) {
        $output = '"' . $res['id'] . ',' . $res['disponibility'] . '"';
        echo("<option value=". $output . ">". $res['disponibility']."</option>");
    }
}

function getDepartment() {
    $pdo = getPDO();
    $q = $pdo->prepare("SELECT id, dep_id, department FROM departments ORDER BY id ASC");
    $q->execute();
    while ($res = $q->fetch()) {
        $output = '' . $res['dep_id'] . ' - ' . $res['department'] . '';
        echo("<option value=". $res['dep_id'] . ">". $output ."</option>");
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

function selectSignUpProfile(){
    $pdo = getPDO();
    $q = $pdo->prepare("SELECT id, specialisation FROM specialisation ORDER BY specialisation ASC");
    $q->execute();
    while($res = $q->fetch()){
        echo('<input type="checkbox" name="specialisations[]" value="'. $res['id'] . '"> '. $res['specialisation'].'<br>');
    }
}

function selectSignUpSkills(){
    $pdo = getPDO();
    $q = $pdo->prepare("SELECT id, skill FROM skills ORDER BY skill ASC");
    $q->execute();
    while($res = $q->fetch()){
        echo('<input type="checkbox" name="skills[]" value="'. $res['id'] . '"> '. $res['skill'].'<br>');
    }
}

?>