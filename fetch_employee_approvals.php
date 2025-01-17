<?php
include "db.php"; // Make sure the database connection file exists and is correctly configured
header('Content-Type: application/json');

// Correct SQL query with proper conditions
$query = "SELECT user_id, first_name, email FROM users WHERE approve = '1' AND type = 'Employee'";
    
$result = mysqli_query($conn, $query);

if ($result) {
    $employees = [];
    
    // Check if the result is not empty
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $employees[] = $row;
        }
        echo json_encode($employees); // Return the data as JSON if employees exist
    } else {
        echo json_encode([]); // Return an empty array if no employees found
    }
} else {
    // Return a JSON response with a 500 error code for server-side failure
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch employee approvals.']);
}

// Close the database connection (optional, depending on your setup)
mysqli_close($conn);
?>
