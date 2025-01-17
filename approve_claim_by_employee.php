<?php
include "db.php";
header('Content-Type: application/json');

$query = "SELECT * FROM claims WHERE status = 'Submitted'";
    
$result = mysqli_query($conn, $query);

if ($result) {
    $employees = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $employees[] = $row;
        }
        echo json_encode($employees); 
    } else {
        echo json_encode([]);
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch employee approvals.']);
}

mysqli_close($conn);
?>
