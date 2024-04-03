<?php
// Start session to store user info
require_once('config.php');

if(isset($_POST['submit'])) {
    // Check if all necessary fields are set
    if(isset($_POST['username']) && isset($_FILES['image'])) {
        $image_name = $_FILES['image']['name'];
        $username = htmlspecialchars($_POST['username']);

        // Replace special characters with underscores for image filename
        $image_mod = preg_replace('/[^\w\-\.]/', '_', $_FILES['image']['name']);
        $image_filename = time() . "--" . $image_mod;
        $image_target = "../SQL/uploads/images/" . $image_filename;
        $image_tempname = $_FILES['image']['tmp_name'];

        $pdo = getPDO();

        // Move uploaded image to directory
        if (move_uploaded_file($image_tempname, $image_target)) {
            // Insert image path into database
            $insertUser = $pdo->prepare(
                "INSERT INTO uploads (student_name, image_path)
                VALUES (?, ?)"
            );
            $insertUser->execute(array($username, $image_target));

            // Check if video file is provided
            if(isset($_FILES['video']) && !empty($_FILES['video']['tmp_name'])) {
                $video_name = $_FILES['video']['name'];

                // Replace special characters with underscores for video filename
                $video_mod = preg_replace('/[^\w\-\.]/', '_', $_FILES['video']['name']);
                $video_filename = time() . "--" . $video_mod;
                $video_target = "../SQL/uploads/videos/" . $video_filename;
                $video_tempname = $_FILES['video']['tmp_name'];

                // Move uploaded video to directory
                if (move_uploaded_file($video_tempname, $video_target)) {
                    // Update the database with the video path
                    $updateVideo = $pdo->prepare(
                        "UPDATE uploads SET video_path = ? WHERE student_name = ?"
                    );
                    $updateVideo->execute(array($video_target, $username));
                } else {
                    $error = error_get_last();
                    $_SESSION['user_info'] = "Failed to upload video. Error: " . $error['message'];
                }
            }

            $_SESSION['user_info'] = "Image uploaded successfully.";
            header("Location: ../index.php"); // Redirect after successful upload
            exit(); // Ensure no further code execution after redirection
        } else {
            $error = error_get_last();
            $_SESSION['user_info'] = "Failed to upload image. Error: " . $error['message'];
            header("Location: ../route123/reg.php"); // Redirect if upload fails
            exit(); // Ensure no further code execution after redirection
        }
    } else {
        $_SESSION['user_info'] = "Please fill out all required fields.";
        header("Location: ../route123/reg.php"); // Redirect if fields are missing
        exit(); // Ensure no further code execution after redirection
    }
}
?>
