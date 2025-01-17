<?php
include "db.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

// Get form data
$loan_amount = $_POST['loan_amount'];
$loan_term = $_POST['loan_term'];
$application_id = $_POST['loan_purpose'];


$user_id = $_SESSION['user_id'];
$target_dir = "uploads/";

// Ensure the directory exists
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
}

// Check if file was uploaded
if (isset($_FILES["document"]) && $_FILES["document"]["error"] == 0) {
    $target_file = $target_dir . basename($_FILES["document"]["name"]);
    
    // Move the uploaded file to the target directory
    if (!move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
        die("Error: Unable to upload the document.");
    }
} else {
    die("Error: No document uploaded.");
}

// Prepare the SELECT statement
$stmt = $conn->prepare("SELECT * FROM loans WHERE loan_id = ? AND user_id = ?");
$stmt->bind_param("si", $application_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $loan = $result->fetch_assoc();
    $status = "Submitted";  // Status for the loan update

    // Prepare the UPDATE statement
    $updateStmt = $conn->prepare("UPDATE loans SET status=?, loan_amount=?, loan_purpose=?, document_hash=? WHERE loan_id=?");
    $document_hash = basename($target_file);  // Store only the file name
    $updateStmt->bind_param("sdsss", $status, $loan_amount, $loan_purpose, $document_hash, $application_id);

    // Execute the update
    if (!$updateStmt->execute()) {
        die("Error updating loan status: " . $updateStmt->error);
    } else {
        echo "Edit Application Successfully";
    }
} else {
    echo "Error: Loan not found or user not authorized.";
}

?>
