<?php
include "db.php";

$u = $_POST["claim_id"];

$updateStmt = $conn->prepare("UPDATE claims SET status = 'Approve' WHERE claim_id = ? ");
$updateStmt->bind_param("s", $u);
$updateStmt->execute();
echo "Employee Approved";
?>