<?php
	require_once('config.php');

	if(isset($_SESSION['connected'])){
		if($_SESSION['connected']){

			try{
				$pdo = getPDO();
			} catch(Exception $e) {
				$_SESSION['error'] = $e->getMessage();
				header("Location: ../index.php");
			}

			$curentDate = date("Y-m-d");
			$exitDate = date("Y-m-d H:i:s");
			
			if(isAlreadyEnter($pdo, $curentDate)){
				if(!isAlreadyExit($pdo, $curentDate)){
					$lasteEnterdate = getLastEnterDate($pdo, $curentDate);
						//$lasteEnterdate = getLastEnterDate($pdo, $curentDate);
					$query = $pdo->prepare(
						"UPDATE logs
						SET exitDate = ?, timeIn = DATE_FORMAT(TIMEDIFF(logs.exitDate, logs.enterDate), \"%H:%i\"), ipUser = CONCAT(ipUser, ?)
						WHERE idStudent = ? AND currentDate = ? AND enterDate = ?"
					);
					$query->execute(array($exitDate, "/" . $_SERVER['REMOTE_ADDR'], $_SESSION['id'], $curentDate, $lasteEnterdate));
					// check if connection is from a prof
					if ($_SESSION['admin'] && $_SESSION['pseudo'] !== "admin") {
								$queryevent = $pdo->prepare(
									"UPDATE eventlogs SET exitDate = ?, timeIn = DATE_FORMAT(TIMEDIFF(eventlogs.exitDate, eventlogs.enterDate), \"%H:%i\"), ipUser = CONCAT(ipUser, ?) WHERE idStudent = ? AND currentDate = ? AND enterDate = ?"
								);
								$queryevent->execute(array($exitDate, "/" . $_SERVER['REMOTE_ADDR'], $_SESSION['id'], $curentDate, $lasteEnterdate));
								header("Location: ../HTML/crudprof.php");
					} else {
						header("Location: ../HTML/user.php");
					}
				} else {
					$_SESSION['error'] = "Vous êtes déjà sortie";
					header("Location: ../HTML/crudprof.php");
				}
			} else {
				$_SESSION['error'] = "Vous n'êtes pas encore entré";
				header("Location: ../HTML/crudprof.php");
			}
		} else {
			header("Location: ../index.php");
		}
	} else {
		header("Location: ../index.php");
	}


	function getLastEnterDate($db, $cdate){
		$query = $db->prepare(
			"SELECT * 
			FROM logs 
			WHERE idStudent = ? AND currentDate = ?
			ORDER BY enterDate DESC"
		);
		$query->execute(array($_SESSION['id'], $cdate));
		$result = $query->fetch();
	return $result['enterDate'];
	}
	function isAlreadyExit($db, $cdate){
		$query = $db->prepare(
			"SELECT * 
			FROM logs 
			WHERE idStudent = ? AND currentDate = ?
			ORDER BY enterDate DESC"
		);
		$query->execute(array($_SESSION['id'], $cdate));
		$result = $query->fetch();
		if(empty($result['exitDate'])){
			return false;
		}
		return true;
	}

	function isAlreadyEnter($db, $cdate){
		$query = $db->prepare(
			"SELECT * 
			FROM logs 
			WHERE idStudent = ?"
		);
		$query->execute(array($_SESSION['id']));
		while($result = $query->fetch()){
			if($result['currentDate'] == $cdate && $result['exitDate'] == null){
				return true;
			}
		}
		return false;
	}
?>