<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../CSS/reg.css">
  <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
  <title>Video Page</title>
  <style>
    body {
        background-color: black;
        color: white;
    }
  </style>
</head>
<body>
  <h2 class="centered_text">Video Page</h2>

  <?php
  // Include the database connection function
  require_once('../PHP/config.php');
  // Check if the student ID is provided via GET parameter
  if(isset($_GET['student_id'])) {
      $student_id = $_GET['student_id'];

      // Retrieve the video path for the given student ID from the database
      $pdo = getPDO();
      $query = "SELECT video_path FROM uploads WHERE student_id = ?";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$student_id]);
      $video_path = $stmt->fetchColumn();

      // Check if a video path is found
      if($video_path) {
  ?>
  <div class="centered">
      <video controls>
      <source src="<?php echo $video_path; ?>" type="video/mp4">
        <!-- <source src="/ibukun/<?php echo $video_path; ?>" type="video/mp4"> -->
          Your browser does not support the video tag.
      </video>
      </div>
  <?php
      } else {
        ?>
        <h3 style="color: white;">No CV found</h3>
        <?php
          echo "No video found for the selected student.";
      }
  } else {
      echo "Student ID is missing.";
  }
  ?>
</body>
</html>
