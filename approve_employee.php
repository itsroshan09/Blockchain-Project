<?php
include "db.php";

$u = $_POST["employee_id"];

$updateStmt = $conn->prepare("UPDATE users SET approve = 0 WHERE user_id = ? ");
$updateStmt->bind_param("s", $u);
$updateStmt->execute();
echo "Employee Approved";
?>