<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blockchain";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve Application ID from POST request and user_id from session
$applicationId = $_POST['applicationId'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

if ($applicationId && $user_id) {
    $stmt = $conn->prepare("SELECT * FROM loans WHERE loan_id = ? AND user_id = ?");
    $stmt->bind_param("si", $applicationId, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt1 = $conn->prepare("SELECT * FROM claims WHERE claim_id = ? AND user_id = ?");
    $stmt1->bind_param("si", $applicationId, $user_id);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    if ($result->num_rows > 0) {
        $loan = $result->fetch_assoc();
    
        // Determine current status
        $currentStatus = $loan['status'] ?? 'Submitted';
        
        // Define statuses for "Rejected" and other states
        $statuses = ($currentStatus === 'Rejected') 
            ? ['Submitted', 'Rejected'] 
            : ['Submitted', 'Verification Completed', 'Approved', 'Disbursed'];
    
        // Find the current status index
        $currentStatusIndex = array_search($currentStatus, $statuses);
    
        if ($currentStatusIndex === false) {
            echo "<div class='alert alert-danger'>Invalid status in database.</div>";
        } else {
            // Display progress tracker
            echo "<div class='progress-bar-steps'>";
            foreach ($statuses as $index => $status) {
                $activeClass = $index <= $currentStatusIndex ? 'active' : '';
                echo "
                    <div class='step $activeClass'>
                        <div class='circle'>" . ($index + 1) . "</div>
                        <div class='step-title'>$status</div>
                    </div>";
            }
            echo "</div>";
        }
    } else if($result1->num_rows > 0){
        $claim = $result1->fetch_assoc();
    
        // Determine current status
        $currentStatus1 = $claim['status'] ?? 'Submitted';
        
        // Define statuses for "Rejected" and other states
        $statuses1 = ($currentStatus1 === 'Rejected') 
            ? ['Submitted', 'Rejected'] 
            : ['Submitted', 'Verification Completed', 'Approved', 'Disbursed'];
    
        // Find the current status index
        $currentStatusIndex1 = array_search($currentStatus1, $statuses1);
    
        if ($currentStatusIndex1 === false) {
            echo "<div class='alert alert-danger'>Invalid status in database.</div>";
        } else {
            // Display progress tracker
            echo "<div class='progress-bar-steps'>";
            foreach ($statuses1 as $index => $status1) {
                $activeClass = $index <= $currentStatusIndex1 ? 'active' : '';
                echo "
                    <div class='step $activeClass'>
                        <div class='circle'>" . ($index + 1) . "</div>
                        <div class='step-title'>$status1</div>
                    </div>";
            }
            echo "</div>";
        }
    }
    
    else {
        echo "<div class='alert alert-warning'>No application found for ID: $applicationId</div>";
    }
    
    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Application ID and User ID are required.</div>";
}

$conn->close();
?>
