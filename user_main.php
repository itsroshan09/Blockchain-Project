<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blockchain Financial Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            font-weight: bold;
            background-color: #007bff;
            color: #fff;
        }
        .card {
            border-radius: 8px;
        }
        .btn-primary, .btn-success {
            width: 100%;
        }
        .nav-link {
            cursor: pointer;
        }
        .progress-bar-steps {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .step {
            text-align: center;
            flex: 1;
            position: relative;
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 5px;
            background-color: #ddd;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }
        .step.active::after {
            background-color: #007bff;
        }
        .circle {
            width: 30px;
            height: 30px;
            background-color: #ddd;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #fff;
        }
        .step.active .circle {
            background-color: #007bff;
        }
        .step-title {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Blockchain Financial Management System</h1>
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item nav-link" onclick="showSection('recentActivitySection')">Previous Activity</li>
                <li class="list-group-item nav-link" onclick="showSection('statusSection')">Loan/Claim Status</li>
                <li class="list-group-item nav-link" onclick="showSection('applicationSection')">Submit Application</li>
                <li class="list-group-item nav-link" onclick="showSection('editSection')">Edit Application</li>
                <li class="list-group-item nav-link" onclick="showSection('notificationsSection')">Notifications</li>
            </ul>
        </div>
        <div class="col-md-9">
            <div id="recentActivitySection" class="d-none">
                <div class="card">
                    <div class="card-header">Previous Activity</div>
                    <div class="card-body">
                        <div id="recentActivity"></div>
                    </div>
                </div>
                <div id="activityDetails" class="mt-3"></div> <!-- Container for activity details -->
            </div>

            <div id="statusSection" class="d-none">
                <div class="card">
                    <div class="card-header">Loan/Claim Status</div>
                    <div class="card-body">
                        <form id="statusForm">
                            <div class="mb-3">
                                <label for="applicationId" class="form-label">Application ID</label>
                                <input type="text" id="applicationId" class="form-control" placeholder="Enter Application ID" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Check Status</button>
                        </form>
                        <div id="statusResult" class="mt-3">
                            <div id="loader" class="d-none text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p>Fetching status...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="applicationSection" class="d-none">
                <div class="card">
                    <div class="card-header">Submit Application</div>
                    <div class="card-body">
                        <form id="applicationForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="type" class="form-label">Application Type</label>
                                <select id="type" name="type" class="form-control">
                                <option >-----</option>
                                    <option value="loan">Loan</option>
                                    <option value="claim">Claim</option>
                                </select>
                            </div>
                            <div id="loanFields" class="d-none">
                                <div class="mb-3">
                                    <label for="loan_amount" class="form-label">Loan Amount</label>
                                    <input type="number" id="loan_amount" name="loan_amount" class="form-control" placeholder="Enter loan amount">
                                </div>
                                <div class="mb-3">
                                    <label for="loan_term" class="form-label">Loan Term (months)</label>
                                    <input type="number" id="loan_term" name="loan_term" class="form-control" placeholder="Enter loan term">
                                </div>
                                <div class="mb-3">
                                    <label for="loan_purpose" class="form-label">Loan Purpose</label>
                                    <textarea id="loan_purpose" name="loan_purpose" class="form-control" placeholder="Enter loan purpose"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="loan_amount" class="form-label">Account Number</label>
                                    <input type="number" id="ac1" name="ac1" class="form-control" placeholder="Enter Account Number">
                                </div>
                                <div class="mb-3">
                                    <label for="document" class="form-label">Upload Document</label>
                                    <input type="file" id="document" name="document" class="form-control">
                                </div>
                            </div>
                            <div id="claimFields" class="d-none">
                                <div class="mb-3">
                                    <label for="claim_amount" class="form-label">Claim Amount</label>
                                    <input type="number" id="claim_amount" name="claim_amount" class="form-control" placeholder="Enter claim amount">
                                </div>
                                <div class="mb-3">
                                    <label for="claim_reason" class="form-label">Claim Reason</label>
                                    <textarea id="claim_reason" name="claim_reason" class="form-control" placeholder="Enter reason for claim"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="loan_amount" class="form-label">Account Number</label>
                                    <input type="number" id="ac" name="ac" class="form-control" placeholder="Enter Account Number">
                                </div>
                                <div class="mb-3">
                                    <label for="claim_document" class="form-label">Upload Supporting Documents</label>
                                    <input type="file" id="claim_document" name="claim_document" class="form-control">
                                </div>
                            </div>

                            <!-- <div class="mb-3">
                                <label for="details" class="form-label">Details</label>
                                <textarea id="details" name="details" class="form-control" placeholder="Enter details" required></textarea>
                            </div> -->
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div id="editSection" class="d-none">
                <div class="card">
                    <div class="card-header">Edit Application</div>
                    <div class="card-body">
                        <form id="editForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="type1" class="form-label">Application Type</label>
                                <select id="type1" name="type1" class="form-control">
                                    <option value="loan">Loan</option>
                                    <option value="claim">Claim</option>
                                </select>
                            </div>
                            <div id="loanFields1" class="d-none">
                            
                                <div class="mb-3">
                                    <label for="loan_amount" class="form-label">Loan Amount</label>
                                    <input type="number" id="loan_amount" name="loan_amount" class="form-control" placeholder="Enter loan amount">
                                </div>
                                <div class="mb-3">
                                    <label for="loan_term" class="form-label">Loan Term (months)</label>
                                    <input type="number" id="loan_term" name="loan_term" class="form-control" placeholder="Enter loan term">
                                </div>
                                <div class="mb-3">
                                    <label for="loan_purpose" class="form-label">Application Id</label>
                                    <textarea id="loan_purpose" name="loan_purpose" class="form-control" placeholder="Enter loan purpose"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="document" class="form-label">Upload Document</label>
                                    <input type="file" id="document" name="document" class="form-control">
                                </div>
                            </div>
                            <div id="claimFields1" class="d-none">
                                <div class="mb-3">
                                    <label for="claim_amount" class="form-label">Claim Amount</label>
                                    <input type="number" id="claim_amount" name="claim_amount" class="form-control" placeholder="Enter claim amount">
                                </div>
                                <div class="mb-3">
                                    <label for="claim_reason" class="form-label">Claim Reason</label>
                                    <textarea id="claim_reason" name="claim_reason" class="form-control" placeholder="Enter reason for claim"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="loan_amount" class="form-label">Account Number</label>
                                    <input type="text" id="ac" name="ac" class="form-control" placeholder="Enter Account Number">
                                </div>
                                <div class="mb-3">
                                    <label for="claim_document" class="form-label">Upload Supporting Documents</label>
                                    <input type="file" id="claim_document" name="claim_document" class="form-control">
                                </div>
                            </div>

                            <!-- <div class="mb-3">
                                <label for="details" class="form-label">Details</label>
                                <textarea id="details" name="details" class="form-control" placeholder="Enter details" required></textarea>
                            </div> -->
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div id="notificationsSection" class="d-none">
                <div class="card">
                    <div class="card-header">Notifications</div>
                    <div class="card-body" id="notifications"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function showSection(sectionId) {
        document.querySelectorAll('.col-md-9 > div').forEach(section => {
            section.classList.add('d-none');
        });
        document.getElementById(sectionId).classList.remove('d-none');
    }
    $('#type').change(function() {
    if ($(this).val() === 'loan') {
        $('#loanFields').removeClass('d-none');
        $('#claimFields').addClass('d-none');
    } else if ($(this).val() === 'claim') {
        $('#claimFields').removeClass('d-none');
        $('#loanFields').addClass('d-none');
    } else {
        $('#loanFields, #claimFields').addClass('d-none');
    }
});

$('#type1').change(function() {
    if ($(this).val() === 'loan') {
        $('#loanFields1').removeClass('d-none');
        $('#claimFields1').addClass('d-none');
    } else if ($(this).val() === 'claim') {
        $('#claimFields1').removeClass('d-none');
        $('#loanFields1').addClass('d-none');
    } else {
        $('#loanFields1, #claimFields1').addClass('d-none');
    }
});
    function fetchRecentActivity() {
    $.get('fetch_recent_activity.php', function(data) {
        $('#recentActivity').html('<strong>Here are your recent activities:</strong>');
        $('#activityDetails').html(data);
    }).fail(function() {
        $('#recentActivity').html('<div class="alert alert-danger">Failed to fetch recent activities. Please try again later.</div>');
    });
}

    function fetchNotifications() {
        $.get('fetch_notifications.php', function(data) {
            $('#notifications').html(data);
        });
    }
    $('#statusForm').submit(function(e) {
        e.preventDefault();
        const applicationId = $('#applicationId').val();
        $('#loader').removeClass('d-none');
        $('#statusResult').html('');
        $.ajax({
            url: 'check_status.php',
            type: 'POST',
            data: { applicationId: applicationId },
            success: function(data) {
                $('#loader').addClass('d-none');
                $('#statusResult').html(data);
            },
            error: function() {
                $('#loader').addClass('d-none');
                $('#statusResult').html('<div class="alert alert-danger">Error retrieving status. Please try again later.</div>');
            }
        });
    });
    $('#applicationForm').submit(function(e) {
    e.preventDefault();
    
    const type = $('#type').val(); // Get the application type (loan or claim)
    const formData = new FormData(this);
    
    let submitUrl = '';

    // Decide the URL based on the selected application type
    if (type === 'loan') {
        submitUrl = 'submit_loan.php'; // For loan applications
    } else if (type === 'claim') {
        submitUrl = 'submit_claim.php'; // For claim applications
    }

    $.ajax({
        url: submitUrl, // Use the respective URL based on type
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            alert(data);
            fetchRecentActivity(); // Fetch the recent activity after submission
        },
        error: function() {
            alert('Error submitting application. Please try again.');
        }
    });
});
$('#editForm').submit(function(e) {
    e.preventDefault();
    
    const type = $('#type1').val(); // Get the application type (loan or claim)
    const formData = new FormData(this);
    
    let submitUrl = '';

    // Decide the URL based on the selected application type
    if (type === 'loan') {
        submitUrl = 'edit_loan.php'; // For loan applications
    } else if (type === 'claim') {
        submitUrl = 'edit_claim.php'; // For claim applications
    }

    $.ajax({
        url: submitUrl, // Use the respective URL based on type
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            alert(data);
            fetchRecentActivity(); // Fetch the recent activity after submission
        },
        error: function() {
            alert('Error submitting application.');
        }
    });
});
    fetchRecentActivity();
    fetchNotifications();
</script>
</body>
</html>
