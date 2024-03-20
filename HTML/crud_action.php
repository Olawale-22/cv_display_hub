<?php
require_once("../PHP/config.php");
require_once("../PHP/download.php");

        error_reporting(E_ALL);
        ini_set('display_errors', '1');

if (!isset($_SESSION['connected']) || !isset($_SESSION['admin']) || !$_SESSION['connected'] || !$_SESSION['admin']) {
    header("Location: ../index.php");
    exit();
}

// Add Student / Utilisateur
if (isset($_POST['save_student'])) {
    $pseudo = $_POST['pseudo'];
    $first_name = $_POST['first_name'];
     $last_name = $_POST['last_name'];
    $email = $_POST['email'];

        $vipUser = isset($_POST['checkAsAdmin']) ? 1 : 0;
        
    if ($pseudo == NULL || $first_name == NULL || $last_name == NULL || $email == NULL) {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    function passgen1($nbChar) {
        $chaine = "mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
        srand((double) microtime() * 1000000);
        $pass = '';
        for ($i = 0; $i < $nbChar; $i++) {
            $pass .= $chaine[rand() % strlen($chaine)];
        }
        return $pass;
    }

    $password = passgen1(8);
    $hashedPass = sha1($password);

    // Assuming you have already established a PDO connection:
    $pdo = getPDO();

    if ($vipUser == 0)
    {
    $query = "INSERT INTO students (pseudo, nom, prenom, mail, password) VALUES (?, ?, ?, ?, ?)";
    }
    else
    {
        $query = "INSERT INTO students (pseudo, nom, prenom, mail, password, admin) VALUES (?, ?, ?, ?, ?, true)";
    }
    $stmt = $pdo->prepare($query);
    $stmt->execute([$pseudo, $last_name, $first_name, $email, $hashedPass]);

    if ($stmt->rowCount() > 0) {
        $email;
        $subjet = "Création compte Émargement Campus";
        $message = "
        <html>
        <head>
          <title>Votre compte a été créé!</title>
        </head>
        <body>
          <p>Cher/chère {$last_name} {$first_name},</p>
          <p>Nous avons le plaisir de vous informer que votre compte d’émergence VIP a été créé avec succès! Veuillez trouver ci-dessous vos informations de connexion :</p>
          <ul>
            <li>Nom d'utilisateur : {$pseudo}</li>
            <li>Mot de passe temporaire : {$password}</li>
          </ul>
          <a href='http://10.10.0.21/index.php'>Cliquez ici pour accéder à la plateforme de connexion. </a>
          <p>Pour des raisons de commodité, nous vous conseillons vivement de vous connecter à votre profil à partir d'un ordinateur en tant qu'utilisateur VIP. Vous serez averti lorsque votre page sera disponible pour une utilisation mobile.</p>
          <p>Veuillez penser à modifier votre mot de passe après votre première connexion.</p>
          <p>Cordialement,</p>
          <p>L'équipe CSM Rouen</p>
        </body>
        </html>
        ";
        $headers = array(
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: CSM Rouen - Émargement <csmemargement@gmail.com>',
            'Reply-To: ' . $email,
            'X-Mailer: PHP/' . phpversion()
        );

        if (mail($email, $subjet, $message, implode("\r\n", $headers))) {
            $res = [
                'status' => 200,
                'message' => 'Student Created Successfully. An email has been sent to this user. His password is<hr><span class="student-password">' . $password . '</span>'
            ];
        header('Content-Type: application/json');
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 200,
                'message' => 'Student Created Successfully. <u><b>There was an error sending the email</b></u>. His password is<hr><span class="student-password">' . $password . '</span>'
            ];
        header('Content-Type: application/json');
            echo json_encode($res);
            return;
        }
    } else {
        $res = [
            'status' => 500,
            'message' => 'Student Not Created'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

// Edit Student
if (isset($_POST['update_student'])) {
    $student_id = $_POST['student_id'];
    $pseudo = $_POST['pseudo'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $promo_id = $_POST['promo_id'];

    if ($pseudo == NULL || $first_name == NULL || $last_name == NULL || $email == NULL) {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    // Assuming you have already established a PDO connection:
        $pdo = getPDO();

    $query = "UPDATE students SET pseudo=?, prenom=?, nom=?, mail=?, id_promo=? WHERE id=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$pseudo, $first_name, $last_name, $email, $promo_id, $student_id]);

    if ($stmt->rowCount() > 0) {
        $res = [
            'status' => 200,
            'message' => 'Student n°' . $student_id . ' updated successfully'
        ];
header('Content-Type: application/json');
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Student not updated'
        ];
header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

// Fetch Student
if(isset($_GET['student_id']))
{
    $student_id = $_GET['student_id'];

        $pdo = getPDO();

    $query = "SELECT * FROM students WHERE id=:student_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();

    if($stmt->rowCount() == 1)
    {
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        $res = [
            'status' => 200,
            'message' => 'Student Fetch Successfully by id',
            'data' => $student
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'Student Id Not Found'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}


// Delete Student
if(isset($_POST['delete_student']))
{
    $student_id = $_POST['student_id'];
        $pdo = getPDO();
    $query = "DELETE FROM students WHERE id=:student_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();

    if($stmt->rowCount() > 0)
    {
        $res = [
            'status' => 200,
            'message' => 'Student ' . $_POST['student_id']  . ' Deleted Successfully'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Student Not Deleted'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}


// LOGS PART

// Add Log

if(isset($_POST['save_log']))
{
    $student_id = $_POST['studentId'];
    $currentDate = $_POST['currentDate'];
    $enterTime = $_POST['enterTime'];
    $exitTime = $_POST['exitTime'];
        $pdo = getPDO();
    // We have both values, we calculate timeIn
    if($enterTime != "" && $exitTime != ""){
        $enter = new DateTime($enterTime);
        $exit = new DateTime($exitTime);
        $interval = $enter->diff($exit);
        $timeIn = $interval->format('%h:%i:%s');
    }else{
        $timeIn = NULL;
    }

    if($enterTime == ""){
        $enterDate = NULL;
    }else{
        $enterDate = $currentDate . ' ' . $enterTime;
    }

    if($exitTime == ""){
        $exitDate = NULL;
    }else{
        $exitDate = $currentDate . ' ' . $exitTime;
    }

    if($student_id == NULL || $currentDate == NULL || $enterTime == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'Student, current date, and enter time are mandatory'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    $query = "INSERT INTO logs (idStudent, currentDate, enterDate, exitDate, timeIn) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$student_id, $currentDate, $enterDate, $exitDate, $timeIn]);

    if($stmt->rowCount() > 0)
    {
        $res = [
            'status' => 200,
            'message' => 'Log associated to ' . $student_id . ' created Successfully'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Log Not Created'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}


// Edit Log

if(isset($_POST['update_log']))
{
    $log_id = $_POST['log_id'];

    $currentDate = $_POST['date'];
        $pdo = getPDO();
    $enterDate = $_POST['enterDate'];
    if($enterDate == ""){
        $enterDate = NULL;
    }else{
        $enterDate = $currentDate . ' ' . $enterDate;
    }

    $exitDate = $_POST['exitDate'];
    if($exitDate == ""){
        $exitDate = NULL;
    }else{
        $exitDate = $currentDate . ' ' . $exitDate;
    }

    // We have both values, we calculate timeIn
    if($enterDate != "" && $exitDate != ""){
        $enterTime = new DateTime($enterDate);
        $exitTime = new DateTime($exitDate);
        $interval = $enterTime->diff($exitTime);
        $timeIn = $interval->format('%h:%i:%s');
    }else{
        $timeIn = NULL;
    }

    if($currentDate == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'Date is required'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE logs SET enterDate=?, exitDate=?, timeIn=? WHERE id=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$enterDate, $exitDate, $timeIn, $log_id]);

    if($stmt->rowCount() > 0)
    {
        $res = [
            'status' => 200,
            'message' => 'Log n°' . $log_id .' updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Log Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


// Fetch Log
if(isset($_GET['log_id']))
{
    $log_id = $_GET['log_id'];
    $pdo = getPDO();
    $query = "SELECT * FROM logs WHERE id=:log_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':log_id', $log_id);
    $stmt->execute();

    if($stmt->rowCount() == 1)
    {
        $log = $stmt->fetch(PDO::FETCH_ASSOC);

        $res = [
            'status' => 200,
            'message' => 'Log Fetch Successfully by Id',
            'data' => $log
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'Log Id Not Found: ' . $log_id
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

// Delete Log
if(isset($_POST['delete_log']))
{
    $log_id = $_POST['log_id'];

        $pdo = getPDO();
    $query = "DELETE FROM logs WHERE id=:log_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':log_id', $log_id);
    $query_run = $stmt->execute();

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'Log ' . $_POST['log_id']  . ' Deleted Successfully'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Log Not Deleted'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

// PROMO PART

// Add Promo

if(isset($_POST['save_promo']))
{
    $nom_promo = $_POST['nom_promo'];
    $pdo = getPDO();

    if($nom_promo == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'Promotion needs a name'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    $query = "INSERT INTO promo (nom_promo) VALUES (:nom_promo)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nom_promo', $nom_promo);
    $query_run = $stmt->execute();

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'Promotion "' . $nom_promo . '" created Successfully'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Promotion Not Created'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

// Edit Promo
if(isset($_POST['update_promo']))
{
    $promo_id = $_POST['promo_id'];
    $nom_promo = $_POST['nom_promo'];
    $pdo = getPDO();

    if($nom_promo == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'Promotion needs a name'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    $query = "UPDATE promo SET nom_promo=:nom_promo WHERE id=:promo_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nom_promo', $nom_promo);
    $stmt->bindParam(':promo_id', $promo_id);
    $query_run = $stmt->execute();

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'Promo "' . $nom_promo .'" updated Successfully'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Promo Not Updated'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}
// Fetch Promo
if(isset($_GET['promo_id']))
{
    $promo_id = $_GET['promo_id'];
    $pdo = getPDO();
    $query = "SELECT * FROM promo WHERE id=:promo_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':promo_id', $promo_id);
    $stmt->execute();

    if($stmt->rowCount() == 1)
    {
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);

        $res = [
            'status' => 200,
            'message' => 'Promo Fetch Successfully by Id',
            'data' => $promo
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'Promo Id Not Found: ' . $promo_id
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

// Delete Promo
if(isset($_POST['delete_promo']))
{
    $promo_id = $_POST['promo_id'];
    $pdo = getPDO();
    $query = "DELETE FROM promo WHERE id=:promo_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':promo_id', $promo_id);
    $query_run = $stmt->execute();

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'Promo ' . $promo_id . ' deleted Successfully'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Promo Not Deleted'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentId']) && isset($_POST['logsMonth']) && isset($_POST['selectorYear'])) {
    // Get the filter values from the AJAX request
    $studentId = $_POST['studentId'];
    // split get student_id from the value of function getStudents()
    $stuId = explode(' ', $studentId);
    $logsMonth = $_POST['logsMonth'];
    $selectorYear = $_POST['selectorYear'];

    // Construct the query based on the filter values
    $query = "SELECT w.id, w.currentDate, s.pseudo, w.enterDate, w.exitDate, w.timeIn
              FROM logs w
              LEFT JOIN students s ON w.idStudent = s.id";

    $conditions = array();
    $parameters = array();

    if (!empty($studentId)) {
        $conditions[] = "w.idStudent = ?";
        $parameters[] = $stuId[0];
    }

    if (!empty($logsMonth)) {
        $conditions[] = "MONTH(w.currentDate) = ?";
        $parameters[] = $logsMonth;
    }

    if (!empty($selectorYear)) {
        $conditions[] = "YEAR(w.currentDate) = ?";
        $parameters[] = $selectorYear;
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    //$query .= " GROUP BY w.currentDate ORDER BY w.currentDate DESC";
        $query .= " GROUP BY w.id, w.currentDate, s.pseudo, w.enterDate, w.exitDate, w.timeIn
                ORDER BY w.currentDate DESC";

        $pdo = getPDO();
    // Prepare the statement and bind the parameters
    $stmt = $pdo->prepare($query);
    if ($stmt) {
        $stmt->execute($parameters);
        $filteredData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the filtered data as JSON response
        header('Content-Type: application/json');
        echo json_encode($filteredData);
    } else {
        // Error in preparing the statement
        echo "Error in preparing the statement";
    }
} else {
    // Invalid request method or missing filter values
    echo "Invalid request";
}

?>