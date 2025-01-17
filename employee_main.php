<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
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
    <h1 class="text-center mb-4">Employee Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item nav-link" onclick="showSection('loanApprovalSection')">Loan Approvals</li>
                <li class="list-group-item nav-link" onclick="showSection('claimApprovalSection')">Claim Approvals</li>
            </ul>
        </div>
        <div class="col-md-9">
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
    <iframe id="documentFrame" src="" style="width: 100%; height: 400px; border: none; display: none;"></iframe>
    <button id="closeIframeBtn" style="display: none;" onclick="closeIframe()">Close</button>
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

    function fetchLoanApprovals() {
        $.get('approve_loan_by_employee.php', function(data) {
            try {
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                if (Array.isArray(data)) {
                    let html = '<table class="table table-striped">';
                    html += '<thead><tr><th>#</th><th>Loan ID</th><th>Loan Amount</th><th>View</th><th>Verify</th><th>Reject</th></tr></thead><tbody>';
                    data.forEach((loan, index) => {
                        html += `<tr>
                            <td>${index + 1}</td>
                            <td>${loan.loan_id}</td>
                            <td>${loan.loan_amount}</td>
                            <td>
                                <button class="btn btn-primary btn-approve" data-action="view" data-loan-id="${loan.loan_id}">View</button>
                            </td>
                            <td>
                                <button class="btn btn-success btn-approve" data-action="verify" data-loan-id="${loan.loan_id}">Verify</button>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-approve" data-action="reject" data-loan-id="${loan.loan_id}">Reject</button>
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

    function fetchClaimApprovals() {
        $.get('approve_claim_by_employee.php', function(data) {
            try {
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                if (Array.isArray(data)) {
                    let html = '<table class="table table-striped">';
                    html += '<thead><tr><th>#</th><th>Claim ID</th><th>Claim Amount</th><th>Claim Reason</th><th>View</th><th>Verify</th><th>Reject</th></tr></thead><tbody>';
                    data.forEach((claim, index) => {
                        html += `<tr>
                            <td>${index + 1}</td>
                            <td>${claim.claim_id}</td>
                            <td>${claim.claim_amount}</td>
                            <td>${claim.claim_reason}</td>
                            <td>
                                <button class="btn btn-primary btn-approve" data-action="view" data-claim-id="${claim.claim_id}">View</button>
                            </td>
                            <td>
                                <button class="btn btn-success btn-approve" data-action="verify" data-claim-id="${claim.claim_id}">Verify</button>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-approve" data-action="reject" data-claim-id="${claim.claim_id}">Reject</button>
                            </td>
                        </tr>`;
                    });
                    html += '</tbody></table>';
                    $('#claimApprovalList').html(html);
                } else {
                    $('#claimApprovalList').html('<div class="alert alert-warning">No claim applications found.</div>');
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
        const id1 = $(this).data('loan-id');
        const id =  $(this).data('claim-id');
        if (action === 'view') {
            if ($(this).data('loan-id')) {
                viewDocument(id1);  // Loan View
            } else {
                viewClaimDocument(id);  // Claim View
            }
        } else if (action === 'verify') {
            if ($(this).data('loan-id')) {
                approveLoan(id1);  // Loan Verify
            } else {
                approveClaim(id);  // Claim Verify
            }
        } else if (action === 'reject') {
            if ($(this).data('loan-id')) {
                rejectLoan(id1);  // Loan Reject
            } else {
                rejectClaim(id);  // Claim Reject
            }
        }
    });

    function approveClaim(claimID) {
        $.post('approve_claim.php', { claim_id: claimID }, function(response) {
            alert(response);
            fetchClaimApprovals();
        }).fail(function() {
            alert('Failed to approve claim application. Please try again.');
        });
    }

    function rejectClaim(claimID) {
        if (confirm("Are you sure you want to reject this claim?")) {
            const reason = prompt("Enter the reason for rejection:");
            if (reason) {
                $.post('reject_claim.php', { claim_id: claimID, reason: reason }, function(response) {
                    alert(response);
                    fetchClaimApprovals();
                }).fail(function() {
                    alert('Failed to reject claim application. Please try again.');
                });
            } else {
                alert("Rejection reason is required.");
            }
        }
    }

    function viewDocument(loanID) {
        const url = `fetch_document.php?loan_id=${loanID}`;
        const iframe = document.getElementById('documentFrame');
        const closeBtn = document.getElementById('closeIframeBtn');

        iframe.src = url;
        iframe.style.display = 'block';
        closeBtn.style.display = 'block';
    }

    function viewClaimDocument(claimID) {
        const url = `fetch_document_claim.php?claim_id=${claimID}`;
        const iframe = document.getElementById('documentFrame');
        const closeBtn = document.getElementById('closeIframeBtn');

        iframe.src = url;
        iframe.style.display = 'block';
        closeBtn.style.display = 'block';
    }

    function closeIframe() {
        document.getElementById('documentFrame').style.display = 'none';
        document.getElementById('closeIframeBtn').style.display = 'none';
    }

    function approveLoan(loanID) {
        $.post('approve_loan.php', { loan_id: loanID }, function(response) {
            alert(response);
            fetchLoanApprovals();
        }).fail(function() {
            alert('Failed to approve loan application. Please try again.');
        });
    }

    function rejectLoan(loanID) {
        if (confirm("Are you sure you want to reject this loan?")) {
            const reason = prompt("Enter the reason for rejection:");
            if (reason) {
                $.post('reject_loan.php', { loan_id: loanID, reason: reason }, function(response) {
                    alert(response);
                    fetchLoanApprovals();
                }).fail(function() {
                    alert('Failed to reject loan application. Please try again.');
                });
            } else {
                alert("Rejection reason is required.");
            }
        }
    }

    // Fetch data on page load
    fetchLoanApprovals();
    fetchClaimApprovals();
</script>
</body>
</html>
