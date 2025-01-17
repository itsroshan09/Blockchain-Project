<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}



$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM loans WHERE user_id = ? ORDER BY created_at DESC"); // Adjust table name and fields
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$stmt1 = $conn->prepare("SELECT * FROM claims WHERE user_id = ? ORDER BY created_at DESC"); // Adjust table name and fields
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Activity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .recent-activity-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .activity-item {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
        }
        .activity-item:nth-child(1) {
            animation-delay: 0.2s;
        }
        .activity-item:nth-child(2) {
            animation-delay: 0.4s;
        }
        .activity-item:nth-child(3) {
            animation-delay: 0.6s;
        }
        /* Add delays as needed for more items */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center mb-4">Previous Activity</h1>
    <div class="card">
        <div class="card-header">Your Loan Activities</div>
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <ul class="list-group recent-activity-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="list-group-item activity-item">
                            <strong>Application ID:</strong> <?= htmlspecialchars($row['loan_id']) ?><br>
                            <strong>Amount:</strong> <?= htmlspecialchars($row['loan_amount']) ?><br>
                            <strong>Purpose:</strong> <?= htmlspecialchars($row['loan_purpose']) ?><br>
                            <strong>Status:</strong> <?= htmlspecialchars($row['status']) ?><br>
                            <small class="text-muted">Date: <?= htmlspecialchars($row['created_at']) ?></small>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info">No recent activities found.</div>
            <?php endif; ?>
        </div>
        
    </div>
    <div class="card">
        <div class="card-header">Your claim Activities</div>
        <div class="card-body">
            <?php if ($result1->num_rows > 0): ?>
                <ul class="list-group recent-activity-list">
                    <?php while ($row1 = $result1->fetch_assoc()): ?>
                        <li class="list-group-item activity-item">
                            <strong>Application ID:</strong> <?= htmlspecialchars($row1['claim_id']) ?><br>
                            <strong>Amount:</strong> <?= htmlspecialchars($row1['claim_amount']) ?><br>
                            <strong>Reason:</strong> <?= htmlspecialchars($row1['claim_reason']) ?><br>
                            <strong>Status:</strong> <?= htmlspecialchars($row1['status']) ?><br>
                            <small class="text-muted">Apply Date: <?= htmlspecialchars($row1['created_at']) ?></small>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info">No recent activities found.</div>
            <?php endif; ?>
        </div>
        
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const items = document.querySelectorAll(".activity-item");
        items.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.2}s`;
        });
    });
</script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
