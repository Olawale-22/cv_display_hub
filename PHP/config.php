<?php
	// Fichier contenant toutes les config possible de l'application web

	//If php version > 7.0.0, we put duration of SESSIONS to 1 day.
	if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
    session_start(['cookie_lifetime' => 86400,]);
	} else {
	    session_start();
	}

	//$dbname = 'emergement_csm';
	$dbname = 'rencontrer';
	$hostname = 'localhost';
	$password_bdd = 'mac_user_pass';
	$root = 'mac_user';
	date_default_timezone_set("Europe/Paris");
	function getPDO(){
		try{
				$pdo = new PDO(
					'mysql:host='.$GLOBALS['hostname'].';dbname='.$GLOBALS['dbname'],
					$GLOBALS['root'],
					$GLOBALS['password_bdd'],
				);
			} catch(Exception $e) {
				$_SESSION['error'] = $e->getMessage();
				header("Location: ../index.php");
			}
			return $pdo;
	}
	
	// Get MySQLi
	// $con = mysqli_connect("localhost","mac_user","mac_user_pass","emergement_csm");

	// if(!$con){
	// 	die('Connection Failed'. mysqli_connect_error());
	// }
?>
