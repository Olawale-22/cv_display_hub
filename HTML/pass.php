
<?php 
	require_once("../PHP/config.php");

	if(!$_SESSION['connected']){
		header("Location: ../index.php");
	}
?>
<!DOCTYPE html>

<html lang="fr">
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Changer mot de passe -ISCOM</title>
        <link rel="stylesheet" href="../CSS/sign_up.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="../JS/admin.js"></script>
		<link rel="icon" type="image/x-icon" href="../image/favicon.ico">
	</head>
	<body>
    <div class="container">
        <h2 class="headlabel"></h2>
    <form action="../PHP/p_change.php" method="get">
        <div class="box">
          <label for="pseudo" class="fl fontLabel"> Pseudo: </label>
    			<div class="new iconBox"><i class="fa fa-user" aria-hidden="true"></i></div>
    			<div class="fr">
                        <input type="text" name="pseudo" placeholder="Pseudo" class="textBox" autofocus="on" required>
    			</div>
    			<div class="clr"></div>
    		</div>
            <div class="box">
          <label for="password" class="fl fontLabel"> Ancient passe </label>
    			<div class="fl iconBox"><i class="fa fa-key" aria-hidden="true"></i></div>
    			<div class="fr">
    					<input type="Password" required name="old_pass" placeholder="Old Password" class="textBox">
    			</div>
    			<div class="clr"></div>
    		</div>
            <div class="box">
          <label for="password" class="fl fontLabel"> Nouveau passe </label>
    			<div class="fl iconBox"><i class="fa fa-key" aria-hidden="true"></i></div>
    			<div class="fr">
    					<input type="Password" required name="new_pass" placeholder="New Password" class="textBox">
    			</div>
    			<div class="clr"></div>
    		</div>
            <div class="box">
          <label for="password" class="fl fontLabel"> Confirmer </label>
    			<div class="fl iconBox"><i class="fa fa-key" aria-hidden="true"></i></div>
    			<div class="fr">
    					<input type="Password" required name="new_pass_conf" placeholder="Password" class="textBox">
    			</div>
    			<div class="clr"></div>
    		</div>
            <div class="box" style="margin-top: 8%;">
            <label class="fl fontLabel"><a href="../index.php">Retour au login</a> </label>
    				<input type="submit" name="submit" class="submit" value="SUBMIT">
    		</div>
	</form>
</div>
</div>
			<?php 
				if(isset($_SESSION['user_info'])){
					echo ("<p>" . $_SESSION['user_info'] . "</p>");
					unset($_SESSION['user_info']);
				}
			?>
	</body>
</html>
