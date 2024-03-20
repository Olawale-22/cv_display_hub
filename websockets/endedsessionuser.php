<?php
require_once("../PHP/config.php");

// Check subscription status
$prof_id = $_GET['profId'];
$con = getPDO();
$currentDate = date("Y-m-d");

$stmtLogs = $con->prepare("SELECT timeIn FROM eventlogs WHERE currentDate = :currentDate");
$stmtLogs->bindParam(':currentDate', $currentDate);
$stmtLogs->execute();

if ($stmtLogs->rowCount() > 0) {
    $rowLogs = $stmtLogs->fetch(PDO::FETCH_ASSOC);
    $timeIn = $rowLogs['timeIn'];
    echo "prof_id: $prof_id\n";

    if (!empty($timeIn)) {
        $res = [
            'status' => 200,
            'message' => 'Pas de session active 💻... on y va ? 😊'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 220,
            'message' => 'Catch up on active sessions 😊'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
} else {
    $res = [
        'status' => 240,
        'message' => 'Happy new day 😊 Pas de session active 💻...'
    ];
    header('Content-Type: application/json');
    echo json_encode($res);
    return;
}

?>