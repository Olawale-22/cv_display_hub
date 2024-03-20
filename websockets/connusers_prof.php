<?php
require_once("../PHP/config.php");

// Check subscription status
$input = $_GET['profId'];
$getters = explode(" ", $input);
$prof_id = $getters[0];
$subject_Id = $getters[1];
$con = getPDO();
$currentDate = date("Y-m-d");

$stmtLogs = $con->prepare("SELECT timeIn FROM eventlogs WHERE idStudent = :prof_id AND subject_id = :subject_id AND currentDate = :currentDate");
$stmtLogs->bindParam(':prof_id', $prof_id);
$stmtLogs->bindParam(':subject_id', $subject_Id);
$stmtLogs->bindParam(':currentDate', $currentDate);
$stmtLogs->execute();

if ($stmtLogs->rowCount() > 0) {
    $rowLogs = $stmtLogs->fetch(PDO::FETCH_ASSOC);
    $timeIn = $rowLogs['timeIn'];

    if (!empty($timeIn)) {
        $res = [
            'status' => 200,
            'message' => 'Pas de session active 💻... on y va ? 😊'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    // The code below will be executed only if $timeIn is empty
    $query = "SELECT pseudo FROM wlogs w LEFT JOIN sujet su ON w.subject_name = su.nom_sujet WHERE su.prof_id = :prof_id AND subject_id = :subject_id AND currentDate = CURRENT_DATE()";
    $stmtWlogs = $con->prepare($query);
    $stmtWlogs->bindParam(':prof_id', $prof_id);
    $stmtWlogs->bindParam(':subject_id', $subject_Id);
    $stmtWlogs->execute();
    $numRows = $stmtWlogs->rowCount();

    if ($numRows > 0) {
        $pseudo = $stmtWlogs->fetchColumn();
        $stmtWlogs->closeCursor();

        $res = [
            'status' => 200,
            'message' => 'active subscribers',
            'data' => $pseudo
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 225,
            'message' => 'no active subscribers yet'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
} else {
    $res = [
        'status' => 422,
        'message' => 'Bienvenue'
    ];
    header('Content-Type: application/json');
    echo json_encode($res);
    return;
}

// pseudo: okay
// password: u5h5uI0M
?>
