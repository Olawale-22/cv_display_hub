<?php
require_once("../PHP/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['UserId']) && isset($_FILES['imageData'])) {
    // Get the image data from the uploaded file
    $imageData = $_FILES['imageData']['tmp_name'];
    $student_id = $_POST['UserId'];

    $filename = time() . "--" . $student_id . '.png';

    // Specify the file path on the server to save the image
    $filePath = './uploads/' . $filename;

    // Save the image using move_uploaded_file
    if (move_uploaded_file($imageData, $filePath)) {
        $pdo = getPDO();

        // Insert the image information into the database
        $sql = "INSERT INTO profile_pic (student_id, img_data) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id, $filename]);

        // Return a response to the client (success or error)
        if ($stmt->rowCount() > 0) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
            return;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error']);
            return;
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error']);
        return;
    }
}
?>
