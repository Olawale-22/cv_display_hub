<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../CSS/reg.css">
  <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
  <title>Voir CV</title>
  <style>
    body {
        background-color: black;
    }

  </style>
</head>
<body>
  <h2 class="centered_text">Corriculum Vitae</h2>
  <?php
  // Include the database connection function
  require_once('../PHP/config.php');
  // Check if the student ID is provided via GET parameter
  if(isset($_GET['student_id'])) {
      $student_id = $_GET['student_id'];

      // Retrieve the video path for the given student ID from the database
      $pdo = getPDO();
      $query = "SELECT cv_path FROM uploads WHERE student_id = ?";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$student_id]);
      $cv_path = $stmt->fetchColumn();

      // Check if a video path is found
      if($cv_path) {
  ?>
    <div class="centered_cv">
        <object
            type="application/pdf"
            data="<?php echo $cv_path; ?>"
            width="600"
            height="800">
        </object>
    </div>
  <?php
      } else {
          echo "No CV found for the selected student.";
      }
  } else {
      echo "Student ID is missing.";
  }
  ?>
</body>
</html>
