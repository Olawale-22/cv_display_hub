<?php 
	require_once('config.php');

	if(isset($_GET['pseudo']) && isset($_GET['password'])  && isset($_GET['submit'])){
		if(!empty($_GET['pseudo']) && !empty($_GET['password'])){
			$username = valid_donnees($_GET['pseudo']);
			$password = sha1(valid_donnees($_GET['password']));
			
			try{
				$pdo = getPDO();
			} catch(Exception $e) {
				$_SESSION['error'] = $e->getMessage();
				header("Location: ../index.php");
			}

			$result = $pdo->prepare(
				"SELECT * 
				FROM students 
				WHERE pseudo = :pseudo AND password = :password"
			);

			$result->execute(["pseudo" => $username, "password" => $password]);
			if($result->rowCount() == 1){
				$resQuery = $result->fetch();

				setcookie('connected', true, time() + (86400 * 1));
				setcookie('id', $resQuery['id'], time() + (86400 * 1));
				setcookie('pseudo', $resQuery['pseudo'], time() + (86400 * 1));
				setcookie('prenom', $resQuery['prenom'], time() + (86400 * 1));
				
				$_SESSION['connected'] = true;
				$_SESSION['id'] =  $resQuery['id'];
				$_SESSION['pseudo'] = $resQuery['pseudo'];
				$_SESSION['prenom'] = $resQuery['prenom'];
				$_SESSION['admin'] = $resQuery['admin'];
				if($resQuery['admin']){
					header("Location: ../HTML/admin.php");
				} else {
					header("Location: ../HTML/user.php");
				}
			} else {
				$_SESSION['error'] = "Mauvais identifiant ou mauvais mot de passe";
				header("Location: ../index.php");
			}
		} else {
			$_SESSION['error'] = "Try to jump the gun ?";
			header("Location: ../index.php");
		}
	} else {
		$_SESSION['error'] = "Try to jump the gun ?";
		header("Location: ../index.php");
	}

	function valid_donnees($donnees){
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
        return $donnees;
    }
?>
