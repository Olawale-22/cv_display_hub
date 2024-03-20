<?php
require_once("config.php");

if (isset($_GET['pseudo']) && isset($_GET['last_name']) && isset($_GET['first_name']) && isset($_GET['e_mail']) && isset($_GET['pass_']) && isset($_GET['submit'])) {
    if (!empty($_GET['pseudo']) && !empty($_GET['first_name']) && !empty($_GET['last_name']) && !empty($_GET['e_mail']) && !empty($_GET['pass_'])){

        $pseuname = htmlspecialchars($_GET['pseudo']);
        $lastN = htmlspecialchars($_GET['last_name']);
        $firstN = htmlspecialchars($_GET['first_name']);
        $eMail = htmlspecialchars($_GET['e_mail']);
        $pass_ = sha1(htmlspecialchars($_GET['pass_']));
        
        $pdo = getPDO();
        $isEmailExist = $pdo->prepare(
            "SELECT *
            FROM students 
            WHERE mail = ?"
        );
        $isEmailExist->execute(array($eMail));
        $result = $isEmailExist->rowCount();
        if($result == 0){
            $insertUser = $pdo->prepare(
                "INSERT INTO students (pseudo, nom, prenom, mail, password) 
                VALUES (?, ?, ?, ?, ?)"
            );
            $insertUser->execute(array($pseuname, $lastN, $firstN, $eMail, $pass_));
            $_SESSION['user_info'] = "New user inserted successfully";

            // lets try connected session from here... 
            setcookie('connected', true, time() + (3600 * 1));
				//setcookie('id', $resQuery['id'], time() + (86400 * 1));
				setcookie('pseudo', $pseuname, time() + (3600 * 1));

				$_SESSION['connected'] = true;
				//$_SESSION['id'] =  $resQuery['id'];
				$_SESSION['pseudo'] = $pseuname;
            header("Location: ../HTML/set_profile.php");
        } else {
            $_SESSION['user_info'] = "This email already exists";
            header("Location: ../HTML/sign_up.php");
        }
    } else {
        $_SESSION['error'] = "Please fill in all required fields";
        header("Location: ../HTML/sign_up.php");
    }
} else {
    $_SESSION['error'] = "Please fill in all required fields";
    header("Location: ../HTML/sign_up.php");
}
?>
