<?php
session_start();
// Uncomment this if you want to enforce admin login
// if (!isset($_SESSION['admin_id'])) {
//     die("Error: Admin not logged in.");
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            font-weight: bold;
            background-color: #dc3545;
            color: #fff;
        }
        .card {
            border-radius: 8px;
        }
        .btn-approve {
            width: 100%;
        }
        .nav-link {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item nav-link" onclick="showSection('employeeApprovalSection')">Employee Approvals</li>
                <li class="list-group-item nav-link" onclick="showSection('loanApprovalSection')">Loan Approvals</li>
                <li class="list-group-item nav-link" onclick="showSection('claimApprovalSection')">Claim Approvals</li>
            </ul>
        </div>
        <div class="col-md-9">
            <div id="employeeApprovalSection" class="d-none">
                <div class="card">
                    <div class="card-header">Pending Employee Approvals</div>
                    <div class="card-body">
                        <div id="employeeApprovalList"></div>
                    </div>
                </div>
            </div>

            <div id="loanApprovalSection" class="d-none">
                <div class="card">
                    <div class="card-header">Pending Loan Applications</div>
                    <div class="card-body">
                        <div id="loanApprovalList"></div>
                    </div>
                </div>
            </div>

            <div id="claimApprovalSection" class="d-none">
                <div class="card">
                    <div class="card-header">Pending Claim Applications</div>
                    <div class="card-body">
                        <div id="claimApprovalList"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Show specific section based on click
    function showSection(sectionId) {
        document.querySelectorAll('.col-md-9 > div').forEach(section => {
            section.classList.add('d-none');
        });
        document.getElementById(sectionId).classList.remove('d-none');
    }

    // Function to fetch employee approvals
    function fetchEmployeeApprovals() {
        $.get('fetch_employee_approvals.php', function(data) {
            console.log('Employee data:', data);
            try {
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                if (Array.isArray(data)) {
                    let html = '<table class="table table-striped">';
                    html += '<thead><tr><th>#</th><th>Name</th><th>Email</th><th>Action</th></tr></thead><tbody>';
                    data.forEach((employee, index) => {
                        html += `<tr>
                                    <td>${index + 1}</td>
                                    <td>${employee.first_name}</td>
                                    <td>${employee.email}</td>
                                    <td>
                                        <button class="btn btn-success btn-approve" onclick="approveEmployee(${employee.user_id})">Approve</button>
                                    </td>
                                 </tr>`;
                    });
                    html += '</tbody></table>';
                    $('#employeeApprovalList').html(html);
                } else {
                    $('#employeeApprovalList').html('<div class="alert alert-warning">No employees found for approval.</div>');
                }
            } catch (error) {
                console.error('Error parsing response:', error);
                $('#employeeApprovalList').html('<div class="alert alert-danger">Invalid response from the server.</div>');
            }
        }).fail(function() {
            $('#employeeApprovalList').html('<div class="alert alert-danger">Failed to fetch employee approvals. Please try again later.</div>');
        });
    }

    // Function to fetch loan approvals
    function fetchLoanApprovals() {
        $.get('approve_loan_by_admin.php', function(data) {
            try {
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                if (Array.isArray(data)) {
                    let html = '<table class="table table-striped">';
                    html += '<thead><tr><th>#</th><th>Loan ID</th><th>Loan Amount</th><th>Verify</th></tr></thead><tbody>';
                    data.forEach((loan, index) => {
                        html += `<tr>
                            <td>${index + 1}</td>
                            <td>${loan.loan_id}</td>
                            <td>${loan.loan_amount}</td>
                            
                            <td>
                                <button class="btn btn-success btn-approve" data-action="verify" data-loan-id="${loan.loan_id}" data-user-id="${loan.user_id}">Approve</button>
                            </td>
                            
                        </tr>`;
                    });
                    html += '</tbody></table>';
                    $('#loanApprovalList').html(html);
                } else {
                    $('#loanApprovalList').html('<div class="alert alert-warning">No loan applications found.</div>');
                }
            } catch (error) {
                console.error('Error parsing response:', error);
                $('#loanApprovalList').html('<div class="alert alert-danger">Invalid response from the server.</div>');
            }
        }).fail(function() {
            $('#loanApprovalList').html('<div class="alert alert-danger">Failed to fetch loan approvals. Please try again later.</div>');
        });
    }

   

    // Function to fetch claim approvals
    function fetchClaimApprovals() {
        $.get('fetch_claim_approvals.php', function(data) {
            console.log('Claim data:', data);
            try {
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                if (Array.isArray(data)) {
                    let html = '<table class="table table-striped">';
                    html += '<thead><tr><th>#</th><th>Claim ID</th><th>User ID</th><th>Amount</th><th>Action</th></tr></thead><tbody>';
                    data.forEach((claim, index) => {
                        html += `<tr>
                                    <td>${index + 1}</td>
                                    <td>${claim.claim_id}</td>
                                    <td>${claim.user_id}</td>
                                    <td>${claim.claim_amount}</td>
                                    <td>
                                        <button class="btn btn-success btn-approve" data-action="verify" data-claim-id="${claim.claim_id}">Approve</button>
                                    </td>
                                 </tr>`;
                    });
                    html += '</tbody></table>';
                    $('#claimApprovalList').html(html);
                } else {
                    $('#claimApprovalList').html('<div class="alert alert-warning">No claims found for approval.</div>');
                }
            } catch (error) {
                console.error('Error parsing response:', error);
                $('#claimApprovalList').html('<div class="alert alert-danger">Invalid response from the server.</div>');
            }
        }).fail(function() {
            $('#claimApprovalList').html('<div class="alert alert-danger">Failed to fetch claim approvals. Please try again later.</div>');
        });
    }
    $(document).on('click', '.btn-approve', function () {
    const action = $(this).data('action');

    if (action === 'verify') {
        const claimID = $(this).data('claim-id');
        if (claimID) {
            approveClaim(claimID);
        } else {
            const loanID = $(this).data('loan-id');
            const userID = $(this).data('user-id');
            if (loanID && userID) {
                approveLoan(loanID, userID);
            }
        }
    }
});
    // Function to approve an employee
    function approveEmployee(employeeId) {
        $.post('approve_employee.php', { employee_id: employeeId }, function(response) {
            alert(response);
            fetchEmployeeApprovals(); // Refresh the employee approvals list
        }).fail(function() {
            alert('Failed to approve employee. Please try again.');
        });
    }
    
    // Function to approve a loan
    function approveLoan(loanId, userId) {
        $.post('approve_loan_admin.php', { loan_id: loanId, user_id: userId }, function(response) {
            alert(response);
            fetchLoanApprovals(); // Refresh the loan approvals list
        }).fail(function() {
            alert('Failed to approve loan. Please try again.');
        });
    }

    // Function to approve a claim
    function approveClaim(claimId) {
        $.post('approve_claim_admin.php', { claim_id: claimId }, function(response) {
            alert(response);
            fetchClaimApprovals(); // Refresh the claim approvals list
        }).fail(function() {
            alert('Failed to approve claim. Please try again.');
        });
    }

    // Initialize data fetching on page load
    $(document).ready(function() {
        fetchEmployeeApprovals();
        fetchLoanApprovals();
        fetchClaimApprovals();
    });
</script>
</body>
</html>
