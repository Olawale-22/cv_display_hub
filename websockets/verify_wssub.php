<!-- THIS CHECKS IF FOR LIVE SESSIONS AND RETURN A BOOLEAN TO USERS... THIS BOOLEAN HELPS DETERMINE WHAT COURSE CALLBACK SHOULD BE DISPLAYED TO NESLY CONNECTED USERS -->
<!-- AUTHOR: One And Only Me... BABATUNDE SULAIMAN OLAWALE -->
<!-- APPRECIATION: THANK YOU -->
<?php
require_once("../PHP/config.php");

// Check subscription status
$subject = $_GET['subject'];
$getters = explode(" ", $subject);
$currentDate = date("Y-m-d");

$db = getPDO(); // Replace with your implementation of the getPDO() function
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmtLogs = $con->prepare("SELECT timeIn FROM eventlogs WHERE currentDate = :currentDate");
$stmtLogs->bindParam(':currentDate', $currentDate);
$stmtLogs->execute();

// Prepare the response
$response = array();

if ($stmtLogs->rowCount() > 0) {
    $rowLogs = $stmtLogs->fetch(PDO::FETCH_ASSOC);
    $timeIn = $rowLogs['timeIn'];

    if (!empty($timeIn)) {
        $response['subscribed'] = "unknown";
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
    }

    // Prepare the query using a parameterized statement
    $query = "SELECT pseudo FROM wlogs WHERE subject_name = :sub_name AND student_id = :student_id AND currentDate = CURRENT_DATE()";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':sub_name', $getters[0]);
    $stmt->bindParam(':student_id', $getters[1]);
    $stmt->execute();

    // Get the count of matching rows
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $response['subscribed'] = true;
    } else {
        $response['subscribed'] = false;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    $response['subscribed'] = "unknown";
    header('Content-Type: application/json');
    echo json_encode($response);
    return;
}
?>
