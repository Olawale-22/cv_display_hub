<?php
require_once("../PHP/config.php");
require_once("../PHP/download.php");

        error_reporting(E_ALL);
        ini_set('display_errors', '1');

if (!isset($_SESSION['connected']) || !isset($_SESSION['admin']) || !$_SESSION['connected'] || !$_SESSION['admin']) {
    header("Location: ../index.php");
    exit();
}

// add students to subjects

if(isset($_POST['save_studentSub']))
{
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];

    if($student_id == "" || $subject_id == "")
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

        $pdo = getPDO();
        // Get student details
        $qry = "SELECT pseudo, prenom, nom FROM students WHERE id = :student_id";
        $stmt = $pdo->prepare($qry);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $pseudo = $row['pseudo'];
            $prenom = $row['prenom'];
            $nom = $row['nom'];

            // Select subject name from sujet
            $qrsujet = "SELECT nom_sujet FROM sujet WHERE id = :subject_id";
            $stmtsujet = $pdo->prepare($qrsujet);
            $stmtsujet->bindParam(':subject_id', $subject_id);
            $stmtsujet->execute();

            if($stmtsujet->rowCount() == 1)
            {
                $rowsujet = $stmtsujet->fetch(PDO::FETCH_ASSOC);
                $sujetName = $rowsujet['nom_sujet'];

                // Insert data into subjects
                $query = "INSERT INTO subjects (student_id, pseudo, prenom, nom, subject_id, subject_name) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$student_id, $pseudo, $prenom, $nom, $subject_id, $sujetName]);

                if($stmt->rowCount() > 0)
                {
                    $res = [
                        'status' => 200,
                        'message' => 'Student Subject Added Successfully'
                    ];
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    return;
                }
                else
                {
                    $res = [
                        'status' => 500,
                        'message' => 'Student Subject Not Added'
                    ];
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    return;
                }
            }
            else {
                $res = [
                    'status' => 500,
                    'message' => 'Subject not found'
                ];
                header('Content-Type: application/json');
                echo json_encode($res);
                return;
            }
        }
        else
        {
            $res = [
                'status' => 500,
                'message' => 'Student not found'
            ];
            header('Content-Type: application/json');
            echo json_encode($res);
            return;
        }
}

// Edit Student
if(isset($_POST['update_student']))
{
    $student_id = $_POST['student_id'];
    $pseudo = $_POST['pseudo'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $promo_id = $_POST['promo_id'];

    if($pseudo == NULL || $first_name == NULL || $last_name == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
        $pdo = getPDO();
        
        // Select sujet name from promo
        $qry = "SELECT nom_sujet FROM sujet WHERE id = :promo_id";
        $stmt = $pdo->prepare($qry);
        $stmt->bindParam(':promo_id', $promo_id);
        $stmt->execute();

        if($stmt->rowCount() == 1)
        {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $subName = $row['nom_sujet'];

            $query = "UPDATE subjects SET pseudo=:pseudo, prenom=:first_name, nom=:last_name, subject_id=:promo_id, subject_name=:subName WHERE id=:student_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':promo_id', $promo_id);
            $stmt->bindParam(':subName', $subName);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->execute();

            if($stmt->rowCount() > 0)
            {
                $res = [
                    'status' => 200,
                    'message' => 'Student n°' . $student_id .' updated Successfully'
                ];
                header('Content-Type: application/json');
                echo json_encode($res);
                return;
            }
            else
            {
                $res = [
                    'status' => 500,
                    'message' => 'Student Not Updated'
                ];
                header('Content-Type: application/json');
                echo json_encode($res);
                return;
            }
        }
        else
        {
            $res = [
                'status' => 500,
                'message' => 'Student Not Updated - qry_run edit student'
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
    $subject_id = $_POST['subject_id'];
        $pdo = getPDO();

        $query = "DELETE FROM subjects WHERE student_id=:student_id AND subject_id=:subject_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $res = [
                'status' => 200,
                'message' => 'Student ' . $student_id . ' Deleted Successfully'
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
if (isset($_POST['save_log'])) {
    $student_id = $_POST['studentId'];
    $currentDate = $_POST['currentDate'];
    $enterTime = $_POST['enterTime'];
    $PromoHyd = $_POST['promo_id'];
    $pdo = getPDO();

    $promo_name = "";
    $student_pseudo = "";

    if ($enterTime != "") {
        $enter = new DateTime($enterTime);
        $enterTime = $enter->format('Y-m-d H:i:s');
    } else {
        $enterTime = NULL;
    }

    if ($PromoHyd != "") {
        $query = "SELECT nom_sujet FROM sujet WHERE id=:PromoHyd";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':PromoHyd', $PromoHyd);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $promo_name = $row['nom_sujet'];
        } else {
            $res = [
                'status' => 500,
                'message' => 'nom_sujet not fetched successfully'
            ];
                header('Content-Type: application/json');
            echo json_encode($res);
            return;
        }
    }

    if ($student_id != "") {
        $query = "SELECT pseudo FROM students WHERE id=:student_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $student_pseudo = $row['pseudo'];
        } else {
            $res = [
                'status' => 500,
                'message' => 'pseudo not fetched successfully'
            ];
                header('Content-Type: application/json');
            echo json_encode($res);
            return;
        }
    }

    if ($student_id == NULL || $currentDate == NULL || $enterTime == NULL || $PromoHyd == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Student, current date, and enter time are mandatory'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    $query = "INSERT INTO wlogs (pseudo, student_id, subject_id, subject_name, currentDate, enterTime) VALUES (:pseudo, :student_id, :PromoHyd, :promo_name, :currentDate, :enterTime)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':pseudo', $student_pseudo);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':PromoHyd', $PromoHyd);
    $stmt->bindParam(':promo_name', $promo_name);
    $stmt->bindParam(':currentDate', $currentDate);
    $stmt->bindParam(':enterTime', $enterTime);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $res = [
            'status' => 200,
            'message' => 'Log associated with ' . $student_id . ' created successfully'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Log not created'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

// Edit Log
if (isset($_POST['update_log'])) {
    $log_id = $_POST['log_id'];
    $currentDate = $_POST['date'];
    $enterDate = $_POST['enterDate'];
    $exitDate = $_POST['exitDate'];
        $pdo = getPDO();
    if ($enterDate == "") {
        $enterDate = NULL;
    } else {
        $enterDate = $currentDate . ' ' . $enterDate;
    }

    if ($exitDate == "") {
        $exitDate = NULL;
    } else {
        $exitDate = $currentDate . ' ' . $exitDate;
    }

    if ($enterDate != "" && $exitDate != "") {
        $enterTime = new DateTime($enterDate);
        $exitTime = new DateTime($exitDate);
        $interval = $enterTime->diff($exitTime);
        $timeIn = $interval->format('%h:%i:%s');
    } else {
        $timeIn = NULL;
    }

    if ($currentDate == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Date is required'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    $query = "UPDATE logs SET enterDate=:enterDate, exitDate=:exitDate, timeIn=:timeIn WHERE id=:log_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':enterDate', $enterDate);
    $stmt->bindParam(':exitDate', $exitDate);
    $stmt->bindParam(':timeIn', $timeIn);
    $stmt->bindParam(':log_id', $log_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $res = [
            'status' => 200,
            'message' => 'Log n°' . $log_id . ' updated Successfully'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Log Not Updated'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}
if (isset($_POST['update_log'])) {
    $log_id = $_POST['log_id'];
    $currentDate = $_POST['date'];
    $enterDate = $_POST['enterDate'];
    $exitDate = $_POST['exitDate'];

    if ($enterDate == "") {
        $enterDate = NULL;
    } else {
        $enterDate = $currentDate . ' ' . $enterDate;
    }

    if ($exitDate == "") {
        $exitDate = NULL;
    } else {
        $exitDate = $currentDate . ' ' . $exitDate;
    }

    if ($enterDate != "" && $exitDate != "") {
        $enterTime = new DateTime($enterDate);
        $exitTime = new DateTime($exitDate);
        $interval = $enterTime->diff($exitTime);
        $timeIn = $interval->format('%h:%i:%s');
    } else {
        $timeIn = NULL;
    }

    if ($currentDate == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Date is required'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    $query = "UPDATE logs SET enterDate=:enterDate, exitDate=:exitDate, timeIn=:timeIn WHERE id=:log_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':enterDate', $enterDate);
    $stmt->bindParam(':exitDate', $exitDate);
    $stmt->bindParam(':timeIn', $timeIn);
    $stmt->bindParam(':log_id', $log_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $res = [
            'status' => 200,
            'message' => 'Log n°' . $log_id . ' updated Successfully'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Log Not Updated'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}


// Fetch Log

if (isset($_GET['log_id'])) {
    $log_id = $_GET['log_id'];
        $pdo = getPDO();
    $query = "SELECT * FROM logs WHERE id=:log_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':log_id', $log_id);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $log = $stmt->fetch(PDO::FETCH_ASSOC);

        $res = [
            'status' => 200,
            'message' => 'Log Fetch Successfully by Id',
            'data' => $log
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    } else {
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
if (isset($_POST['delete_log'])) {
    $log_id = $_POST['log_id'];
    $pdo = getPDO();
    $query = "DELETE FROM wlogs WHERE id=:log_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':log_id', $log_id);
    $query_run = $stmt->execute();

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Log ' . $_POST['log_id'] . ' Deleted Successfully'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Log Not Deleted'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

// Add Promo

if(isset($_POST['save_sujet'])) {
    $nom_sujet = $_POST['nom_sujet'];
    $prof_id = $_POST['session_id'];

    if($nom_sujet == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Promotion needs a name'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

        $db = getPDO();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO sujet (nom_sujet, prof_id) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $nom_sujet);
        $stmt->bindParam(2, $prof_id);
        $qry_rn = $stmt->execute();

         if($qry_rn) {
            $res = [
                'status' => 200,
                'message' => 'Sujet "' . $nom_sujet .'" created successfully'
            ];
            header('Content-Type: application/json');
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 500,
                'message' => 'Sujet "' .  $nom_sujet .'" not created'
            ];
            header('Content-Type: application/json');
            echo json_encode($res);
            return;
        }

}


// Edit Promo
if(isset($_POST['update_sujet'])) {
    $promo_id = $_POST['promo_id'];
    $nom_sujet = $_POST['nom_sujet'];

    if($nom_sujet == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Promotion needs a name'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
        $db = getPDO(); // Replace with your implementation of the getPDO() function
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "UPDATE sujet SET nom_sujet=? WHERE id=?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $nom_sujet);
        $stmt->bindParam(2, $promo_id);
        $stmt->execute();

        $qryupd = "UPDATE subjects SET subject_name=? WHERE subject_id=?";
        $statmt = $db->prepare($qryupd);
        $statmt->bindParam(1, $nom_sujet);
        $statmt->bindParam(2, $promo_id);
        $qry_run = $statmt->execute();

        if($qry_run) {
            $res = [
                'status' => 200,
                'message' => 'Sujet "' . $nom_sujet .'" updated successfully'
            ];
            header('Content-Type: application/json');
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 500,
                'message' => 'Subject Sujet not updated'
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
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM sujet WHERE id=:promo_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':promo_id', $promo_id);
    $stmt->execute();

    if($stmt->rowCount() == 1)
    {
        $log = $stmt->fetch(PDO::FETCH_ASSOC);

        $res = [
            'status' => 200,
            'message' => 'Promo Fetch Successfully by Id',
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
            'message' => 'Promo Id Not Found: ' . $promo_id
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

// Delete Promo
if(isset($_POST['delete_promo'])) {
    $promo_id = $_POST['promo_id'];

        $db = getPDO(); // Replace with your implementation of the getPDO() function
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "DELETE FROM sujet WHERE id=?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $promo_id);
        $query_run = $stmt->execute();

        $querydel = "DELETE FROM subjects WHERE subject_id=?";
        $statmt = $db->prepare($querydel);
        $statmt->bindParam(1, $promo_id);
        $qry_run = $statmt->execute();

        if($qry_run) {
            $res = [
                'status' => 200,
                'message' => 'Sujet ' . $promo_id . ' deleted successfully'
            ];
            header('Content-Type: application/json');
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 500,
                'message' => 'Sujet subscribers not cleared'
            ];
            header('Content-Type: application/json');
            echo json_encode($res);
            return;
        }
}

    // Retrieve the selected filter values from the query string
        // Check if the request method is AJAX from LOGS

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promoId']) && isset($_POST['studentId']) && isset($_POST['logsMonth']) && isset($_POST['selectorYear']) && isset($_POST['Uid'])) {
        // Get the filter values from the AJAX request
        $promoId = $_POST['promoId'];
        $studentId = $_POST['studentId'];
        // split get student_id from the value of function getStudents()
        $stuId = explode(' ', $studentId);
        $logsMonth = $_POST['logsMonth'];
        $selectorYear = $_POST['selectorYear'];
        $pro_Id = $_POST['Uid'];
        $pdo = getPDO();

        // Construct the query based on the filter values
        $query = "SELECT w.id, w.currentDate, w.pseudo, w.subject_name, w.enterTime FROM wlogs w
                  LEFT JOIN students s ON w.student_id = s.id
                  LEFT JOIN sujet su ON w.subject_name = su.nom_sujet
                  WHERE su.prof_id = ?";
                  
        $conditions = array();
        $parameters = array();

        if (!empty($promoId)) {
            $conditions[] = "w.subject_id = ?";
            $parameters[] = $promoId;
        }

        if (!empty($studentId) && $studentId != "0") {
            $conditions[] = "w.student_id = ?";
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
            $query .= " AND " . implode(" AND ", $conditions);
        }

        $query .= " GROUP BY w.id, w.subject_name ORDER BY w.currentDate DESC";
        //$query .= " GROUP BY w.id, w.currentDate, s.pseudo, w.subject_name, w.enterTime
           // ORDER BY w.currentDate DESC";

        // Prepare the statement
        $stmt = $pdo->prepare($query);

        // Bind the parameters
        $stmt->bindValue(1, $pro_Id, PDO::PARAM_INT);

        // Bind additional parameters if any
        $paramCount = 2;
        foreach ($parameters as $parameter) {
        $stmt->bindValue($paramCount++, $parameter, PDO::PARAM_INT);
        }

        // Execute the statement
        $stmt->execute();

        // Fetch the filtered data
        $filteredData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the filtered data as JSON response
        header('Content-Type: application/json');
        echo json_encode($filteredData);
    } else {
        // Invalid request method or missing filter values
        echo "Invalid request";
    }

?>