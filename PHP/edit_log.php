<?php
require_once("config.php");

if(!$_SESSION['admin']){
    header("Location:../index.php");
    return;
}

$logIdStudent = htmlspecialchars($_POST['idStudent']);
$logDate = htmlspecialchars($_POST['oldDate']);

$actualHrEnter = htmlspecialchars($_POST['actHourEnterEdit']);
$actualHrExit = htmlspecialchars($_POST['actHourExitEdit']);
$logHourEnter = htmlspecialchars($_POST['oldHourEnter']);
$logHourExit = htmlspecialchars($_POST['oldHourExit']);

if($actualHrEnter != "" && $logHourEnter != ""){
    $actualHrEnter = $logHourEnter;
}

if($actualHrExit != "" && $logHourExit != ""){
    $actualHrExit = $logHourExit;
}

function logDateFormat($hour, $date){
    if($hour != '' && $date != ''){
        $hour = $date . ' ' . $hour;
    }
    return $hour;
}

$logHourEnterFormatted = logDateFormat($logHourEnter, $logDate);
$logHourExitFormatted = logDateFormat($logHourExit, $logDate);
$actHourEnterFormatted = logDateFormat($actualHrEnter, $logDate);
if($actHourEnterFormatted == ""){
    $actHourEnterFormatted = logDateFormat($logHourEnter, $logDate);
}

$pdo = getPDO();


// UPDATING Students info 

$query = $pdo->prepare("UPDATE logs SET enterDate = :enterDate, exitDate = :exitDate, timeIn = DATE_FORMAT(TIMEDIFF(logs.exitDate, logs.enterDate), \"%H:%i\") WHERE idStudent = :idStudent AND currentDate = :currentDate AND enterDate = :actualEnterDate");

$datas = ['enterDate' => $logHourEnterFormatted, 'exitDate' => $logHourExitFormatted,'idStudent' => $logIdStudent,'currentDate' => $logDate, 'actualEnterDate' => $actualHrEnter];

$query->bindValue('idStudent', $logIdStudent, PDO::PARAM_INT);
$query->bindValue('enterDate', $logHourEnterFormatted, PDO::PARAM_STR);

//If exitDate is empty
if($logHourExitFormatted != ""){
    $query->bindValue('exitDate', $logHourExitFormatted, PDO::PARAM_STR);
}else{
    $query->bindValue('exitDate', null, PDO::PARAM_NULL);
}


$query->bindValue('actualEnterDate', $actHourEnterFormatted, PDO::PARAM_STR);
$query->bindValue('currentDate', $logDate, PDO::PARAM_STR);

$query->execute();

$_SESSION['user_info'] = "Log de l'utilisateur ".$logIdStudent . " modifié.";

//Adding back current selected values
$_SESSION['selectedIndexStudent'] = $_POST['selectedIndexStudent'];
$_SESSION['selectedIndexMonth'] = $_POST['selectedIndexMonth'];
$_SESSION['selectedIndexYear'] = $_POST['selectedIndexYear'];


// Going back to admin panel
header("Location: ../HTML/admin.php");

?>
