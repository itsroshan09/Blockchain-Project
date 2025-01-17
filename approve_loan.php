<?php
include "db.php";

$u = $_POST["loan_id"];


$updateStmt = $conn->prepare("UPDATE loans SET status = 'Verification Completed' WHERE loan_id = ? ");
$updateStmt->bind_param("s", $u);
$updateStmt->execute();
echo "Employee Approved";
?>