<?php
require_once('../PHP/config.php');

if (isset($_POST['save_pro_sujets'])) {
    // Access the student ID from the formData
    $student_pseudo = $_POST['pseudo'];
    if ($student_pseudo == "") {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    $pdo = getPDO();

    $query = "SELECT id, nom, prenom FROM students WHERE pseudo=:pseudo";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':pseudo', $student_pseudo);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        $studentID = $student['id'];
        $studentNom = $student['nom'];
        $studentPrenom = $student['prenom'];

        // Access the clicked checkboxes from the formData
        $checkboxes = $_POST['promo_id'];

        // Insert student and subject info into the subjects table
        $stmtInsert = $pdo->prepare("INSERT INTO subjects (student_id, pseudo, prenom, nom, subject_id, subject_name) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($checkboxes as $checkbox) {
            // Select subject name from sujet for each subject checked in registration
            $stmtsujet = $pdo->prepare("SELECT nom_sujet FROM sujet WHERE id = :subject_id");
            $stmtsujet->bindParam(':subject_id', $checkbox);
            $stmtsujet->execute();

            if ($stmtsujet->rowCount() == 1) {
                $rowsujet = $stmtsujet->fetch(PDO::FETCH_ASSOC);
                $sujetName = $rowsujet['nom_sujet'];

                $stmtInsert->execute([$studentID, $student_pseudo, $studentPrenom, $studentNom, $checkbox, $sujetName]);
            } else {
                $res = [
                    'status' => 500,
                    'message' => 'Subject not found'
                ];
                header('Content-Type: application/json');
                echo json_encode($res);
                return;
            }
        }

        // Check if any rows were inserted successfully
        $insertedRows = $stmtInsert->rowCount();
        if ($insertedRows > 0) {
            $res = [
                'status' => 200,
                'message' => 'Student Added To Subject Successfully'
            ];
            header('Content-Type: application/json');
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 202,
                'message' => 'Student Not Added To Subjects'
            ];
            header('Content-Type: application/json');
            echo json_encode($res);
            return;
        }
    } else {
        $res = [
            'status' => 404,
            'message' => 'Student Id Not Found'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }
}

?>
