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
			$subject_id = mysqli_real_escape_string($con, $_POST['subject_id']);
			$curentDate = date("Y-m-d");
			$enterDate = date("Y-m-d H:i:s");
			if(asThreeEnterAtDate($pdo, $curentDate)){
				$_SESSION['error'] = "Vous êtes déjà entré 3 fois";
				header("Location: ../HTML/user.php");
				return;
			}

			if(!isAlreadyEnter($pdo, $curentDate)){

					// insert connections into logs
					$query = $pdo->prepare(
						"INSERT INTO logs (idStudent, currentDate, enterDate, ipUser, sujet_id) 
						VALUES (?, ?, ?, ?)"
					);
					
					$query->execute(array($_SESSION['id'], $curentDate, $enterDate, $_SERVER['REMOTE_ADDR'], $subject_id));
					// check if connection is from a prof
					if ($_SESSION['admin'] && $_SESSION['pseudo'] !== "admin") {
						// check for empty input
						if(isset($_POST['subject_id'])) {
							if (!empty($_POST['subject_id'])) {
								$subjectId = $_POST['subject_id'];
								$queryevent = $pdo->prepare(
									"INSERT INTO eventlogs (idStudent, subject_id, currentDate, enterDate, ipUser) 
									VALUES (?, ?, ?, ?, ?)"
								);
								$queryevent->execute(array($_SESSION['id'], $subjectId, $curentDate, $enterDate, $_SERVER['REMOTE_ADDR']));
								header("Location: ../HTML/crudprof.php");
							} else {
								$_SESSION['error'] = "Try to jump the gun ? No promo session selected subject_id= empty";
								header("Location: ../HTML/crudprof.php");
							}
						}else {
							$_SESSION['error'] = "Try to jump the gun ? No promo session selected";
								header("Location: ../HTML/crudprof.php");
						}
					} else {
						header("Location: ../HTML/user.php");
					}
			} else {
				if ($_SESSION['admin'] && $_SESSION['pseudo'] !== "admin") {
					$_SESSION['error'] = "Vous êtes deja entré";
					header("Location: ../HTML/crudprof.php");
				} else {
					$_SESSION['error'] = "Vous êtes deja entré";
					header("Location: ../HTML/user.php");
				}
			}
		} else {
			header("Location: ../index.php");
		}
	} else {
		header("Location: ../index.php");
	}

	function asThreeEnterAtDate($db, $cdate){
		$query = $db->prepare(
			"SELECT * 
			FROM logs 
			WHERE idStudent = ?
			AND currentDate = ?"
		);
	$query->execute(array($_SESSION['id'], $cdate));
	if($query->rowCount() >= 3){
		return true;
	}
	return false;

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

