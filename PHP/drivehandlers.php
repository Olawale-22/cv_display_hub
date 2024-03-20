<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['locate_sid']) && isset($_POST['dispo_sid']) && isset($_POST['skill_id']) && isset($_POST['profile_id'])) {

        // Get the filter values from the AJAX request
        $locateSid = $_POST['locate_sid'];
        $dispoSid = $_POST['dispo_sid'];
        // split get student_id from the value of function getStudents()
        // $stuId = explode(' ', $studentId);
        $skillId = $_POST['skill_id'];
        $profileId = $_POST['profile_id'];
        $pdo = getPDO();

        // Construct the query based on the filter values
        $query = "SELECT s.nom, s.prenom, s.location, s.anywhere, s.mail, p.profile_one, p.profile_two, d.skill_one, d.skill_two, d.skill_three, d.skill_four, COUNT(s.id) AS number_of_students 
                FROM students s 
                LEFT JOIN profiles p ON s.id = p.student_id 
                LEFT JOIN skills d ON s.id = d.student_id 
                WHERE s.id = ?";

        $conditions = array();
        $parameters = array();

        if (!empty($locateSid)) {
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
        
        $query .= " GROUP BY p.id, s.nom ORDER BY p.id DESC";
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