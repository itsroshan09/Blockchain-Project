<?php
include "db.php";

$loan_id = $_POST["loan_id"];
$reason = $_POST['reason'];

$updateStmt = $conn->prepare("UPDATE loans SET status = 'Rejected' WHERE loan_id = ?");
$updateStmt->bind_param("s", $loan_id); 
if (!$updateStmt->execute()) {
    die("Error updating loan status: " . $updateStmt->error);
}

$stmt = $conn->prepare("SELECT * FROM loans WHERE loan_id = ?");
$stmt->bind_param("s", $loan_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Loan not found with ID: $loan_id");
}

$stmt1 = $conn->prepare("INSERT INTO rejected_loans (loan_id, user_id, loan_amount, reasons) VALUES (?, ?, ?, ?)");
$stmt1->bind_param("sids", $loan_id, $row['user_id'], $row['loan_amount'], $reason);
if (!$stmt1->execute()) {
    die("Error inserting into rejected_loans: " . $stmt1->error);
}

echo "Application Rejected";

$updateStmt->close();
$stmt->close();
$stmt1->close();
$conn->close();
?>
