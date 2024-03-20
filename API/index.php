<?php 
	require_once("api.php");
try{
	if(!empty($_GET['demande'])){
		$url = explode("/", filter_var($_GET['demande'], FILTER_SANITIZE_URL));
		switch($url[0]){
			case "student":
				if(empty($url[2])){
					break;
				} else {
					getStudentDetail($url[1],$url[2],$url[3]);
					break;
				}
			default: throw new Exception("La demande n'est pas valide vérifier l'url");
		}
	} else {
		throw new Exception("Prob de recup donnée");
	}
} catch(Exception $e){
	$erreur = [
		"message" => $e->getMessage(),
		"code" => $e->getCode()
	];
	print_r($erreur);
}
?>