<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8">
    <title>Form</title>
    <link rel="stylesheet" type="text/css" href="../CSS/sign_up.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
  </head>
  <body>

        <div class="container">
        <h2 class="headlabel">Sign Up</h2>
      <form action="../PHP/sign_up_action.php" method="get" autocomplete="on">
        <!-- pseudo -->
        <div class="box">
          <label for="pseudo" class="fl fontLabel"> Pseudo: </label>
                        <div class="new iconBox"><i class="fa fa-user" aria-hidden="true"></i></div>
                        <div class="fr">
                        <input type="text" name="pseudo" placeholder="Pseudo" class="textBox" autofocus="on" required>
                        </div>
                        <div class="clr"></div>
                </div>
            <!-- pseudo -->
        <!--Last name-->
                <div class="box">
          <label for="firstName" class="fl fontLabel"> Nom: </label>
                        <div class="new iconBox">
            <i class="fa fa-user" aria-hidden="true"></i>
          </div>
                        <div class="fr">
                                        <input type="text" name="last_name" placeholder="Last Name"
              class="textBox" autofocus="on" required>
                        </div>
                        <div class="clr"></div>
                </div>
                <!--Last name-->


        <!--First name-->
                <div class="box">
          <label for="secondName" class="fl fontLabel"> Prenom: </label>
                        <div class="fl iconBox"><i class="fa fa-user" aria-hidden="true"></i></div>
                        <div class="fr">
                                        <input type="text" required name="first_name"
              placeholder="First Name" class="textBox">
                        </div>
                        <div class="clr"></div>
                </div>
            <!--First name-->

                <!---Email---->
                <div class="box">
          <label for="email" class="fl fontLabel"> Email: </label>
                        <div class="fl iconBox"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                        <div class="fr">
                                        <input type="email" required name="e_mail" placeholder="Email add..." class="textBox">
                        </div>
                        <div class="clr"></div>
                </div>
                <!--Email----->

                <!---Password------>
                <div class="box">
          <label for="password" class="fl fontLabel"> Mot de Passe </label>
                        <div class="fl iconBox"><i class="fa fa-key" aria-hidden="true"></i></div>
                        <div class="fr">
                                        <input type="Password" required name="pass_" placeholder="Password" class="textBox">
                        </div>
                        <div class="clr"></div>
                </div>
                <!---Password---->

                <!---Submit Button------>
                <div class="box" style="margin-top: 8%;">
            <label class="fl fontLabel"><a href="../index.php">Retour au login</a> </label>
                                <input type="submit" name="submit" class="submit" value="SUBMIT">
                </div>
                <!---Submit Button----->
      </form>
  </div>
  </body>