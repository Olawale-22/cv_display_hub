<?php
require_once("config.php");

if (isset($_GET['old_pass']) && isset($_GET['new_pass']) && isset($_GET['new_pass_conf']) && isset($_GET['submit'])) {
    if (!empty($_GET['old_pass']) && !empty($_GET['new_pass']) && !empty($_GET['new_pass_conf'])){

        $oldp = sha1(htmlspecialchars($_GET['old_pass']));
        $newp = sha1(htmlspecialchars($_GET['new_pass']));
        $newpc = sha1(htmlspecialchars($_GET['new_pass_conf']));

        $pdo = getPDO();
        $isGoodPass = $pdo->prepare(
            "SELECT * 
            FROM students 
            WHERE pseudo = ? AND password = ? AND id = ?"
        );
        $isGoodPass->execute(array($_SESSION['pseudo'], $oldp, $_SESSION['id']));
        if($newp == $newpc){
            if($res = $isGoodPass->rowCount() == 1){
                $changepass = $pdo->prepare(
                    "UPDATE students
                    SET password = ? 
                    WHERE id = ? AND pseudo = ?"
                );
                $changepass->execute(array($newp, $_SESSION['id'], $_SESSION['pseudo']));
                $_SESSION['user_info'] = "L'ancien mot de passe a été changé";
		        header("Location: ../index.php");
            } else {
                $_SESSION['user_info'] = "L'ancien mot de passe est mauvais";
		        header("Location: ../HTML/pass.php");
            }
        } else {
            $_SESSION['user_info'] = "Les deux mot de passes ne correspondent pas";
		    header("Location: ../HTML/pass.php");
        }
    } else {
        $_SESSION['error'] = "Try to jump the gun ?";
		header("Location: ../index.php");
    }
} else {
    $_SESSION['error'] = "Try to jump the gun ?";
	header("Location: ../index.php");
}
?>
