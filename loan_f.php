<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application</title>
</head>
<body>
    <h1>Loan Application Form</h1>
    <form action="submit_loan.php" method="POST" enctype="multipart/form-data">
        <label for="loan_amount">Loan Amount:</label>
        <input type="number" id="loan_amount" name="loan_amount" required><br><br>
        
        <label for="loan_term">Loan Term (in years):</label>
        <input type="number" id="loan_term" name="loan_term" required><br><br>

        <label for="loan_purpose">Purpose of Loan:</label>
        <textarea id="loan_purpose" name="loan_purpose" required></textarea><br><br>

        <label for="document">Upload Documents:</label>
        <input type="file" id="document" name="document" required><br><br>

        <button type="submit">Submit Loan Application</button>
    </form>
</body>
</html>
<?php

session_start();
$_SESSION['user_id']=1;
?>