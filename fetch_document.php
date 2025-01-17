<?php
session_start();
include 'db.php'; 

$loan_id = intval($_GET['loan_id']);

// Query to fetch the document path from the database
$sql = "SELECT document_hash FROM loans WHERE loan_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loan_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $document_path = $row['document_hash']; // This should be the actual path to the file
    
    if (file_exists($document_path)) {
        // Determine the MIME type of the file
        $mime_type = mime_content_type($document_path);

        // Send the appropriate headers to display the document in the browser
        header("Content-Type: $mime_type");
        header('Content-Disposition: inline; filename="' . basename($document_path) . '"');
        header('Content-Length: ' . filesize($document_path));

        // Read and output the file content
        readfile($document_path);
    } else {
        die("Error: Document not found.");
    }
} else {
    die("Error: Loan ID not found.");
}

$stmt->close();
$conn->close();
?>
