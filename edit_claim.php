<?php
include "db.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

// Get form data
$claim_amount = $_POST['claim_amount'];
$claim_reason = $_POST['claim_reason'];
$application_id = $_POST['ac'];


$user_id = $_SESSION['user_id'];
$target_dir = "uploads/";

// Ensure the directory exists
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
}

// Check if file was uploaded
if (isset($_FILES["claim_document"]) && $_FILES["claim_document"]["error"] == 0) {
    $target_file = $target_dir . basename($_FILES["claim_document"]["name"]);
    
    // Move the uploaded file to the target directory
    if (!move_uploaded_file($_FILES["claim_document"]["tmp_name"], $target_file)) {
        die("Error: Unable to upload the document.");
    }
} else {
    die("Error: No document uploaded.");
}

// Prepare the SELECT statement
$stmt = $conn->prepare("SELECT * FROM claims WHERE claim_id = ? AND user_id = ?");
$stmt->bind_param("si", $application_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $loan = $result->fetch_assoc();
    $status = "Submitted";  // Status for the loan update

    // Prepare the UPDATE statement
    $updateStmt = $conn->prepare("UPDATE claims SET status=?, claim_amount=?, claim_reason=?, document=? WHERE claim_id=?");
    $document_hash = basename($target_file);  // Store only the file name
    $updateStmt->bind_param("sdsss", $status, $claim_amount, $claim_reason, $document_hash, $application_id);

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
