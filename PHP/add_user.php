<?php
require_once("config.php");

if(!$_SESSION['admin']){
    header("Location:../index.php");
    return;
}

/*$auth_username = "zone01emargement@gmail.com";
$auth_password = "jroqdxsiuzttvnrf";*/

$pseudo = htmlspecialchars($_POST['pseudo']);
$email = htmlspecialchars($_POST['email']);
$nom = htmlspecialchars($_POST['nom']);
$prenom = htmlspecialchars($_POST['prenom']);

$passgen = passgen1(8);
$mdp = sha1($passgen);

$pdo = getPDO();

$query = $pdo->prepare(
    "INSERT INTO students(pseudo, nom, prenom, mail, password, admin)
    VALUES (?, ?, ?, ?, ?, ?)"
);

$query->execute(array($pseudo, $nom, $prenom, $email, sha1($passgen), '0'));

$_SESSION['user_info'] = "Utilisateur ajouté, pseudo : ".$pseudo." et son mot de passe :".$passgen;

$to = $email;
$subjet = "Mot de passe émargement ISCOM";
$message = "Votre compte a été créer pour l'émargement en ligne votre pseudo est : " .$pseudo.", et votre mot de passe provisoire est : ".$passgen ;

$headers = "Content-Type: text/plain; charset=utf-8\r\n";
// $headers .= "From: zone01emargement@gmail.com\r\n";
$headers .= "From: csmemargement@gmail.com\r\n";

if(mail($to, $subjet, $message, $headers)){
    echo "envoyé";
} else {
    echo "erreur";
}

header("Location: ../HTML/admin.php");




function passgen1($nbChar) {
    $chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
    srand((double)microtime()*1000000);
    $pass = '';
    for($i=0; $i<$nbChar; $i++){
        $pass .= $chaine[rand()%strlen($chaine)];
        }
    return $pass;
    }
?>
