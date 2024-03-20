<?php
require_once("config.php");

if(!$_SESSION['admin']){
    header("Location:../index.php");
    return;
}

$logIdStudent = htmlspecialchars($_POST['studentId']);
$logDate = htmlspecialchars($_POST['currentDate']);
$hrEnter = htmlspecialchars($_POST['hourEnter']);
$hrExit = htmlspecialchars($_POST['hourExit']);

function logDateFormat($hour, $date){
    if($hour != '' && $date != ''){
        $hour = $date . ' ' . $hour;
    }
    return $hour;
}

$logHourEnterFormatted = logDateFormat($hrEnter, $logDate);
$logHourExitFormatted = logDateFormat($hrExit, $logDate);

$pdo = getPDO();


// ADDING Students logs 
// INSERT into logs (idStudent, currentDate, enterDate, exitDate) VALUES ("61", "2023-01-09", "2023-01-09 12:51:00", "2023-01-09 17:40:00")
$query = $pdo->prepare("INSERT INTO logs (idStudent, currentDate, enterDate, exitDate, timeIn) VALUES (:idStudent, :currentDate, :hourEnter, :hourExit, timeIn = DATE_FORMAT(TIMEDIFF(:hourExit, :hourEnter), \"%H:%i\"))");
// $query = $pdo->prepare("UPDATE logs SET enterDate = :enterDate, exitDate = :exitDate, timeIn = DATE_FORMAT(TIMEDIFF(logs.exitDate, logs.enterDate), \"%H:%i\") WHERE idStudent = :idStudent AND currentDate = :currentDate AND enterDate = :enterDate");

$query->bindValue('idStudent', $logIdStudent, PDO::PARAM_INT);
$query->bindValue('currentDate', $logDate, PDO::PARAM_STR);
$query->bindValue('hourEnter', $logHourEnterFormatted, PDO::PARAM_STR);

//If exitDate is empty
if($logHourExitFormatted != ""){
    $query->bindValue('hourExit', $logHourExitFormatted, PDO::PARAM_STR);
}else{
    $query->bindValue('hourExit', null, PDO::PARAM_NULL);
}

$query->execute();

//Then we update TIME IN value if we have hourEnter and hourExit
if($logHourExitFormatted && $logHourEnterFormatted){
    $query2 = $pdo->prepare("UPDATE logs SET timeIn = DATE_FORMAT(TIMEDIFF(logs.exitDate, logs.enterDate), \"%H:%i\") WHERE idStudent = :idStudent AND currentDate = :currentDate AND enterDate = :hourEnter");
    $query2->bindValue('idStudent', $logIdStudent, PDO::PARAM_INT);
    $query2->bindValue('currentDate', $logDate, PDO::PARAM_STR);
    $query2->bindValue('hourEnter', $logHourEnterFormatted, PDO::PARAM_STR);
    $query2->execute();
}

$_SESSION['user_info'] = "Log de l'utilisateur ".$logIdStudent . " a été ajouté.";

//Adding back current selected values
$_SESSION['selectedIndexStudent'] = $_POST['selectedIndexStudent'];
$_SESSION['selectedIndexMonth'] = $_POST['selectedIndexMonth'];
$_SESSION['selectedIndexYear'] = $_POST['selectedIndexYear'];

// Going back to admin panel
header("Location: ../HTML/admin.php");

?>
