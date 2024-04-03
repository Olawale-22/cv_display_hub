<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Form</title>
  <link rel="stylesheet" type="text/css" href="../CSS/sign_up.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
</head>
<body>
  <div class="container">
    <h2 class="headlabel">Sign Up / Upload</h2>
    <form action="../PHP/reg-submit.php" method="POST" enctype="multipart/form-data">
      <!-- Pseudo -->
      <div class="box">
        <label for="username" class="fl fontLabel">Pseudo:</label>
        <div class="new iconBox" aria-hidden="true"><i class="fa fa-user"></i></div>
        <div class="fr">
          <input type="text" id="username" name="username" placeholder="Pseudo" class="textBox" autofocus="on" required>
        </div>
        <div class="clr"></div>
      </div>
      <!-- Pseudo -->

      <!-- Upload Image -->
      <div class="box">
        <label for="image" class="fl fontLabel">Upload Image:</label>
        <div class="new iconBox" aria-hidden="true"><i class="fa fa-upload"></i></div>
        <div class="fr">
          <input type="file" id="image" name="image" accept="image/*" required>
        </div>
        <div class="clr"></div>
      </div>
      <!-- Upload Video -->
      <div class="box">
        <label for="video" class="fl fontLabel">Upload Video:</label>
        <div class="new iconBox" aria-hidden="true"><i class="fa fa-upload"></i></div>
        <div class="fr">
          <input type="file" id="video" name="video" accept="video/*">
        </div>
        <div class="clr"></div>
      </div>

      <!-- Submit Button -->
      <div class="box" style="margin-top: 8%;">
        <input type="submit" name="submit" class="submit" value="SUBMIT">
      </div>
      <!-- Submit Button -->
    </form>
  </div>

</body>
</html>
