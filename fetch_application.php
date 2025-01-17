<?php
include "db.php";

try {
    // Create a new MySQLi connection
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $applicationId = $_POST['applicationId'];
        $user_id = $_SESSION['user_id']; // Assume user_id is sent in the request

        // Prepare and execute query for loans table
        $stmt = $conn->prepare("SELECT * FROM loans WHERE loan_id = ? AND user_id = ?");
        $stmt->bind_param("si", $applicationId, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Loan found
            $loan = $result->fetch_assoc();
            echo json_encode(['success' => true, 'type' => 'loan'] + $loan);
            exit;
        }

        // Prepare and execute query for claims table
        $stmt1 = $conn->prepare("SELECT * FROM claims WHERE claim_id = ? AND user_id = ?");
        $stmt1->bind_param("si", $applicationId, $user_id);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($result1->num_rows > 0) {
            // Claim found
            $claim = $result1->fetch_assoc();
            echo json_encode(['success' => true, 'type' => 'claim'] + $claim);
            exit;
        }

        // No records found
        echo json_encode(['success' => false, 'message' => 'No records found for the provided Application ID and User ID']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
} catch (Exception $e) {
    // Handle other exceptions
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
