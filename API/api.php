<?php
	require_once("../PHP/config.php");

	function getStudentDetail($id, $year, $month){
		$pdo = getConnexion();
		
		$queryStudentInfo = $pdo->prepare("SELECT id, pseudo, nom, prenom FROM students WHERE pseudo = ?");
		$queryStudentInfo->execute(array($id));
		$studentInfo = $queryStudentInfo->fetch();
		
		$queryAllDatesStudent = $pdo->prepare(
			"SELECT students.id AS idStudent, logs.id AS idLogs, pseudo, nom, prenom, currentDate,enterDate,exitDate,timeIn, ipUser 
			FROM students INNER JOIN logs ON students.id = logs.idStudent 
			WHERE students.pseudo = ? AND DATE_FORMAT(currentDate ,\"%Y-%m\") = ? 
			ORDER BY logs.currentDate DESC, logs.enterDate DESC"
		);
		$queryAllDatesStudent->execute(array($id, ($year."-".$month)));
		$sum = array();
		$sumDay = 0;
		while($res = $queryAllDatesStudent->fetch()){
			$array = array(
				"logsId" => $res['idLogs'],
				"currentDate" => $res['currentDate'],
				"enterDate" => $res['enterDate'],
				"exitDate" => $res['exitDate'],
				"timeIn" => $res['timeIn'],
				"userIp" => $res['ipUser']
			);
			array_push($sum, $array);
			$sumDay++;
		}
		
		$queryAllTimeInStudent = $pdo->prepare(
			"SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timeIn))) AS hour_passed 
			FROM students INNER JOIN logs ON students.id = logs.idStudent 
			WHERE students.pseudo = ? AND DATE_FORMAT(currentDate ,\"%Y-%m\") = ? 
			ORDER BY logs.currentDate ASC"
		);
		$queryAllTimeInStudent->execute(array($id, ($year."-".$month)));
		$timeInStudent = $queryAllTimeInStudent->fetch();
		$sumTimeIn = $timeInStudent;


		$finalResult = array(
			"id" => $studentInfo['id'],
			"pseudo" => $studentInfo['pseudo'],
			"nom" => $studentInfo['nom'],
			"prenom" => $studentInfo['prenom'],
			"logs" => $sum,
			"hour_passed" => $sumTimeIn['hour_passed'],
			"day_passed" => $sumDay
		);
		sendJSON($finalResult);
	}

	function getConnexion(){
		return new PDO(
					'mysql:host='.$GLOBALS['hostname'].';dbname='.$GLOBALS['dbname'],
					$GLOBALS['root'],
					$GLOBALS['password_bdd'],
				);
	}
	
	function sendJSON($info){
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json");

		echo json_encode($info, JSON_UNESCAPED_UNICODE);
	}
?>
