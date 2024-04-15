<?php
    require_once('../PHP/config.php');
	require_once('../PHP/handlers.php');
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Creé Profile</title>
  <link rel="stylesheet" type="text/css" href="../CSS/reg.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
  <script src="../PHP/JS/handlers.js"></script>
</head>

<body>
  <!-- <div class="container"> -->
    <div>
    <form action="../PHP/reg-submit.php" method="POST" enctype="multipart/form-data" onsubmit="return limitSpecialisations()">
    <div class="container">
      <h2 class="headlabel">Create personal template</h2>
      <!-- Pseudo -->
      <div class="box">
        <label for="card_heading" class="fl fontLabel">Personal Info</label>
      </div>

      <div class="box">
          <div class="new iconBox" aria-hidden="true"><i class="fa fa-user"></i></div>
          <input type="text" id="nom" name="nom" placeholder="Nom" class="textBox" autofocus="on" required>
      </div>

      <div class="box">
          <div class="fl iconBox"><i class="fa fa-user"></i></div>
          <input type="text" id="prenom" name="prenom" placeholder="Prénom" class="textBox" autofocus="on" required>
      </div>

      <div class="box">
          <div class="fl iconBox"><i class="fa fa-envelope"></i></div>
          <input type="email" id="mail" name="mail" placeholder="Email" class="textBox" autofocus="on" required>
      </div>

      <!-- <div class="box">
          <div class="fl iconBox"><i class="fa fa-phone"></i></div>
          <input type="text" id="phone" name="phone" placeholder="Téléphone" class="textBox" autofocus="on" required>
      </div> -->

      <div class="box">
          <div class="fl iconBox"><i class="fa fa-folder-open"></i></div>
          <input type="text" id="github" name="github" placeholder="Git url" class="textBox" autofocus="on">
      </div>

      <div class="box">
          <div class="fl iconBox"><i class="fa fa-folder-open"></i></div>
          <input type="text" id="portfolio" name="portfolio" placeholder="Portfolio  (project url)" class="textBox" autofocus="on">
      </div>

      <div class="box">
          <div class="fl iconBox"><i class="fa fa-key"></i></div>
          <input type="password" id="password" name="password" placeholder="Mot de passe" class="textBox" autofocus="on" required>
      </div>

      <div class="box">
          <div class="fl iconBox"><i class="fa fa-map-marker"></i></div>
          <select id="lieu" name="lieu" class="textBox" autofocus="on" required>
              <option value="" disabled selected>Localisation</option>
              <?php getLieu(); ?>
          </select>
      </div>
    </div>
    <!-- ****$$$$******NEW CONTAINER****$$$$******* -->
    <div class="container">
      <div class="box">
        <label for="teletravail" class="fl fontLabel">Disponible pour teletravail?</label>
        <div class="fr">
            <input type="checkbox" name="teletravail" value="1" class="textBox" autofocus="on" required>
          </div>
      </div>
      <br />
      <div class="box">
        <label for="teletravail" class="fl fontLabel">Disponible pour en presentiel en Europe?</label>
        <div class="fr">
            <input type="checkbox" name="europe" value="1" class="textBox" autofocus="on" required>
          </div>
      </div><br />
      <br />
      <br />
      <div class="box">
        <label for="partout" class="fl fontLabel">Peut travail en presentiel tout la France?</label>
        <div class="fr">
            <i class="fa"></i>
            <input type="checkbox" name="anywhere" value="1" class="textBox" autofocus="on" required>
          </div>
        </div><br />
        <br />
        <br>
        <div class="box">
          <div class="fl iconBox"><i class="fa fa-map-marker"></i></div>
          <select id="lieu" name="disponibility" class="textBox" autofocus="on" required>
              <option value="" disabled selected>Disponibilité</option>
              <?php getDisponibility() ?>
          </select>
      </div> 
      <br />
        <div class="box">
          <div class="fl iconBox"><i class="fa fa-map-marker"></i></div>
          <select id="lieu" name="department" class="textBox" autofocus="on" required>
              <option value="" disabled selected>Département de travail préféré</option>
              <?php getDepartment() ?>
          </select>
      </div> 
        <br />

      <!-- CV Upload -->
      <div class="box">
        <label for="file" class="fl fontLabel">Upload CV</label>
        <div class="fr">
          <div class="iconBox" onclick="document.getElementById('file').click();" aria-hidden="true">
            <i class="fa fa-upload"></i>
            <input type="file" id="file" name="file" size="50" accept="application/*" required>
          </div>
        </div>
      </div>

      <!-- Upload Image -->
      <div class="box">
        <label for="image" class="fl fontLabel">Profile Image</label>
        <div class="fr">
          <div class="iconBox" onclick="document.getElementById('image').click();" aria-hidden="true">
            <i class="fa fa-upload"></i>
            <input type="file" id="image" name="image" accept="image/*" required>
          </div>
        </div>
      </div>

      <!-- Upload Video -->
      <div class="box">
        <label for="image" class="fl fontLabel">Have a Video?</label>
        <div class="fr">
          <div class="iconBox" onclick="document.getElementById('video').click();" aria-hidden="true">
            <i class="fa fa-upload"></i>
            <input type="file" id="video" name="video" accept="video/*">
          </div>
        </div>
      </div>
  </div>

  <!-- ****$$$$*******NEW CONTAINER****$$$$******* -->
  <div class="container">
      <!-- Contract type checklist -->
      <div class="box">
          <label for="contrat" class="fl fontLabel">Type de contrat intéressé</label>
          <div class="fr">
              <input type="checkbox" name="contrats[]" value="stage"> Stage<br>
              <input type="checkbox" name="contrats[]" value="1 year apprenticeship"> Alternance 1 an<br>
              <input type="checkbox" name="contrats[]" value="2 ans alternance"> Alternance 2 an<br>
          </div>
      </div><br />
      <!-- Specializations Checklist -->
      <div class="box">
          <label for="specialisations" class="fl fontLabel">Specialisation(s) (4 Max.)</label>
          <div class="fr">
              <?php selectSignUpProfile(); ?>
          </div>
      </div><br />
      <br />
      <br />
  </div>

  <!-- ****$$$$*******NEW CONTAINER****$$$$******* -->
  <div class="container">
      <!-- Skills Checklist -->
      <div class="box">
          <label for="skills" class="fl fontLabel">Skill(s)</label>
            <div class="fr">
                <?php selectSignUpSkills(); ?>
            </div>
      </div>
  </div>
  
  <!-- ****$$$$*******NEW CONTAINER****$$$$******* -->
  <!-- <div class="container"> -->
      <!-- Submit Button -->
      <div class="box">
        <input type="submit" name="submit" class="submit" value="SAVE">
      </div>
  <!-- </div> -->
      <!-- Submit Button -->
    </form>
  </div>
</body>
</html>