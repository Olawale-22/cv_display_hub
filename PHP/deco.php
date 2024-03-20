<?php 
	// Déconnexion d'un utilisateur
	
	require_once("config.php");

	if(isset($_SESSION['connected']) && $_SESSION['connected']){
		unset($_SESSION['connected']);
		if(isset($_SESSION['admin'])){
			unset($_SESSION['admin']);
		}
		header("Location: ../index.php");
	}
?>