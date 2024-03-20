<?php
require_once('config.php');
require_once('../API/api.php');

if (!isset($_SESSION['connected']) || !isset($_SESSION['admin']) || !$_SESSION['connected'] || !$_SESSION['admin']) {
    header("Location: ../index.php");
    exit();
}


function createCSV(){
    $promotion = "";
    if(isset($_POST['promotion']) && ($_POST['promotion']) != ""){
        $promotion = " AND id_promo = " . $_POST['promotion'] . " ";
    }

    $filename = "data.csv";
    $pdo = getPDO();
    $header = createHeader($pdo);
    $rows = printRows($pdo, $promotion);
    $output = fopen("php://temp", "w+");
    fputcsv($output, $header, ';');
    foreach($rows as $row){
        fputcsv($output, $row, ';');
    }
    rewind($output);
    $csv = stream_get_contents($output);
    fclose($output);
    return $csv;
}

// --- Handling Download ---
if(isset($_POST['action']) && $_POST['action'] == 'download') {
    $csv = createCSV();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="data.csv"');
    echo $csv;
    exit();
}

function createHeader($pdo){
    $ym = getTabYearMonth($pdo);
    $header = ['EMAIL', 'NOM', 'PRENOM'];
    foreach($ym as $y){
        array_push($header, ($y." heures"));
        array_push($header, ($y." jours"));
    }
    return $header;
}

function printRows($pdo, $promotion){
    $AllYearMonth = getTabYearMonth($pdo);
    $AllStudents = getTabPseudo($pdo, $promotion);

    $request = $pdo->prepare("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
    $request->execute();

    $rows = array();
    foreach ($AllStudents as $st) {
        $row = [];
        $query = $pdo->prepare(
            "SELECT mail, nom, prenom
                FROM students
                WHERE pseudo = ?" . $promotion
        );
        $query->execute(array($st));
        $infoStudent = $query->fetch();

        $row[] = $infoStudent['mail'];
        $row[] = $infoStudent['nom'];
        $row[] = $infoStudent['prenom'];

        foreach ($AllYearMonth as $ym) {
            $request = $pdo->prepare(
                "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timeIn))) AS hour
                    FROM logs
                    INNER JOIN students ON students.id = logs.idStudent
                    WHERE pseudo = ? AND DATE_FORMAT(currentDate, \"%Y-%m\") = ?" . $promotion
            );
            $request->execute(array($st, $ym));
            $resultHour = $request->fetch();
            if ($resultHour['hour']) {
                $row[] = $resultHour['hour'];
            } else {
                $row[] = "0";
            }

            $request = $pdo->prepare(
                "SELECT idStudent, currentDate, enterDate, exitDate, timeIn, pseudo, nom, prenom, mail, password FROM logs
                    INNER JOIN students ON students.id = logs.idStudent
                    WHERE pseudo = ?
                    AND DATE_FORMAT(currentDate, \"%Y-%m\") = ?" . $promotion . "
                    GROUP BY currentDate"
            );
            $request->execute(array($st, $ym));

            $row[] = $request->rowCount();
        }
        $rows[] = $row;
    }
    return $rows;
}

function getTabYearMonth($pdo){
    $result = [];
    $request = $pdo->prepare(
        "SELECT DATE_FORMAT(currentDate, \"%Y-%m\") AS date
            FROM logs INNER JOIN students ON logs.idStudent = students.id 
            WHERE admin = false 
            GROUP BY date
            ORDER BY date;"
    );
    $request->execute();
    while($date = $request->fetch()){
        array_push($result, $date[0]);
    }
    return $result;
}

function getTabPseudo($pdo, $promotion){
    $result = [];
    $request = $pdo->prepare(
        "SELECT pseudo
            FROM students
            WHERE admin = false" . $promotion);
    $request->execute();
    while($student = $request->fetch()){
        array_push($result, $student['pseudo']);
    }
    return $result;
}

?>
