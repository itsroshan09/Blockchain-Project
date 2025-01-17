<?php
include "db.php";

$claim_id = $_POST["claim_id"];
$reason = $_POST['reason'];

$updateStmt = $conn->prepare("UPDATE claims SET status = 'Rejected' WHERE claim_id = ?");
$updateStmt->bind_param("s", $claim_id); 
if (!$updateStmt->execute()) {
    die("Error updating loan status: " . $updateStmt->error);
}

$stmt = $conn->prepare("SELECT * FROM claims WHERE claim_id = ?");
$stmt->bind_param("s", $claim_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Loan not found with ID: $claim_id");
}

$stmt1 = $conn->prepare("INSERT INTO rejected_claims (claim_id, user_id, claim_amount, reasons) VALUES (?, ?, ?, ?)");
$stmt1->bind_param("sids", $claim_id, $row['user_id'], $row['claim_amount'], $reason);
if (!$stmt1->execute()) {
    die("Error inserting into rejected_loans: " . $stmt1->error);
}

echo "Application Rejected";

$updateStmt->close();
$stmt->close();
$stmt1->close();
$conn->close();
?>
