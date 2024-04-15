
<!-- $$$$$$$**********************$$$$$$$$$$*************************$$$$$$$$$$$$$$$$***********************$$$$$$$$$$$$$$$$$$$$$$$$$$ -->

<?php
// Start session to store user info
require_once('config.php');

if(isset($_POST['submit'])) {
    // Check if all compulsory fields are set
    if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['password']) && isset($_FILES['image']) && isset($_POST['mail']) && isset($_POST['lieu']) && isset($_POST['disponibility'])  && isset($_POST['specialisations']) && isset($_POST['skills']) && (isset($_POST['github']))) {

        // Retrieve PDO object
        $pdo = getPDO();

        // Retrieve form data
        $image_name = $_FILES['image']['name'];
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $mail = htmlspecialchars($_POST['mail']);
        $password = htmlspecialchars($_POST['password']);
        $dispo = htmlspecialchars($_POST['disponibility']);
        // split disponibility to take id
        $disponibility = explode(',', $dispo);
        $lieu = htmlspecialchars($_POST['lieu']);
        $skills = $_POST['skills'];
        // $portfolio = htmlspecialchars($_POST['portfolio']);
        $contrats = $_POST['contrats'];
        $specialisations = $_POST['specialisations'];
        $github = htmlspecialchars($_POST['github']);

        $sql = "INSERT INTO students (nom, prenom, location, contrats, disponibility_id, department_id, anywhere, europe, teletravail, portfolio, github, skills, specialisation, mail, password) VALUES (:nom, :prenom, :lieu, :contrat, :disponibility, :department, :anywhere, :europe, :teletravail, :portfolio, :github, :skills, :specialisation, :mail, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':lieu', $lieu);
        $stmt->bindParam(':disponibility', $disponibility[0]);
        $stmt->bindParam(':github', $github);
        
        // confirm optional infos before insertion
        if (isset($_POST['anywhere'])) {
            $someplace = htmlspecialchars($_POST['anywhere']);
            $stmt->bindParam(':anywhere', $someplace);
        } else {
            $value = 0;
            $stmt->bindParam(':anywhere', $value);
        }

        if (isset($_POST['europe'])) {
            $europework = htmlspecialchars($_POST['europe']);
            $stmt->bindParam(':europe', $europework);
        }

        if (isset($_POST['teletravail'])) {
            $someteletravail = htmlspecialchars($_POST['teletravail']);
            $stmt->bindParam(':teletravail', $someteletravail);
        }

        if (isset($_POST['department'])) {
            $somedepartment = htmlspecialchars($_POST['department']);
            $stmt->bindParam(':department', $somedepartment);
        }

        if (isset($_POST['portfolio'])) {
            $portfolio = htmlspecialchars($_POST['portfolio']);
            $stmt->bindParam(':portfolio', $portfolio);
        }

        $stmt->bindParam(':contrat', implode(',', $contrats));
        $stmt->bindParam(':skills', implode(',', $skills));
        $stmt->bindParam(':specialisation', implode(',', $specialisations));

        // Execute the query
        if ($stmt->execute()) {
            // Handle file uploads (image and video) here
            // Retrieve the last inserted ID (student_id)
            $studentId = $pdo->lastInsertId();
            // Replace special characters with underscores for image filename
            $image_mod = preg_replace('/[^\w\-\.]/', '_', $_FILES['image']['name']);
            $image_filename = time() . "--" . $image_mod;
            $image_target = "../SQL/uploads/images/" . $image_filename;
            $image_tempname = $_FILES['image']['tmp_name'];

            // Move uploaded image to directory
            if (move_uploaded_file($image_tempname, $image_target)) {
                // Insert image path into database
                $insertUser = $pdo->prepare(
                    "INSERT INTO uploads (student_id, image_path)
                    VALUES (?, ?)"
                );
                $insertUser->execute(array($studentId, $image_target));

                //$$$$$$$$$$$$$$$$$$$$$$$$$$$***************************************$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
                //$$$$$$$$$$$$$$$$$$$$$$$$$$$***************************************$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
                // handle cv upload.
                $cv_mod = preg_replace('/[^\w\-\.]/', '_', $_FILES['file']['name']);
                $cv_filename = time() . "--" . $cv_mod;
                $cv_target = "../SQL/uploads/cvitae/" . $cv_filename;
                $cv_tempname = $_FILES['file']['tmp_name'];

                if (move_uploaded_file($cv_tempname, $cv_target)) {

                    $insertCV = $pdo->prepare(
                        "UPDATE uploads SET cv_path = ? WHERE student_id = ?"
                    );
                    $insertCV->execute(array($cv_target, $studentId));
                //$$$$$$$$$$$$$$$$$$$$$$$$$$$***************************************$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
                //$$$$$$$$$$$$$$$$$$$$$$$$$$$***************************************$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
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
                                "UPDATE uploads SET video_path = ? WHERE student_id = ?"
                            );
                            $updateVideo->execute(array($video_target, $studentId));
                        } else {
                            $error = error_get_last();
                            $_SESSION['user_info'] = "Failed to upload video. Error: " . $error['message'];
                        }
                    }

                    $_SESSION['user_info'] = "Image && CV uploaded successfully.";
                    header("Location: ../index.php"); // Redirect after successful upload
                    exit(); // Ensure no further code execution after redirection
                }
            } else {
                $error = error_get_last();
                $_SESSION['user_info'] = "Failed to upload image. Error: " . $error['message'];
                header("Location: ../route123/reg.php"); // Redirect if upload fails
                exit(); // Ensure no further code execution after redirection
            }
            // Redirect user after successful submission
            $_SESSION['user_info'] = "User registration successful.";
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['user_info'] = "Error: Unable to register user.";
        }
    } else {
        $_SESSION['user_info'] = "Please fill out all required fields.";
    }
    $_SESSION['user_info'] = "Please fill out all required fields.";
    // Redirect user back to registration page if any error occurred
    header("Location: ../route123/reg.php");
    exit();
}
?>
