
<section>
    <div class="db">
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php include('sidebar.php'); // Aapka existing sidebar ?>
        </div>
        
        <div class="col-md-9">
            <div class="dashboard-content">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4><i class="fa fa-exclamation-triangle"></i> Delete Account Request</h4>
                    </div>
                    
                    <div class="card-body">
                        <?php if(isset($pendingRequest) && $pendingRequest): ?>
                            <!-- Pending Request Status -->
                            <div class="alert alert-warning">
                                <h5><i class="fa fa-clock-o"></i> Request Pending</h5>
                                <p>Your account deletion request is pending admin approval.</p>
                                <p><strong>Request Date:</strong> <?php echo date('d M Y H:i', strtotime($pendingRequest->created_at)); ?></p>
                                <button type="button" class="btn btn-danger" id="cancelDeleteRequest">
                                    <i class="fa fa-times"></i> Cancel Request
                                </button>
                            </div>
                            
                        <?php else: ?>
                            <!-- Delete Request Form -->
                            <div class="alert alert-danger">
                                <strong>Warning!</strong> Once your account is deleted, all your data including profile, photos, interests, and messages will be permanently removed. This action cannot be undone.
                            </div>
                            
                            <form id="deleteAccountForm">
                                <div class="form-group">
                                    <label>Why are you deleting your account? <span class="text-danger">*</span></label>
                                    <select class="form-control" name="delete_reason" id="deleteReason" required>
                                        <option value="">Select Reason</option>
                                        <option value="found_match">I found a match</option>
                                        <option value="not_interest">Not interested anymore</option>
                                        <option value="privacy">Privacy concerns</option>
                                        <option value="too_expensive">Too expensive</option>
                                        <option value="duplicate">Created duplicate account</option>
                                        <option value="technical">Technical issues</option>
                                        <option value="other">Other reason</option>
                                    </select>
                                </div>
                                
                                <div class="form-group" id="otherReasonGroup" style="display: none;">
                                    <label>Please specify reason</label>
                                    <input type="text" class="form-control" name="other_reason" id="otherReason" placeholder="Enter your reason">
                                </div>
                                
                                <div class="form-group">
                                    <label>Any feedback for us? (Optional)</label>
                                    <textarea class="form-control" name="feedback" rows="3" placeholder="Tell us how we can improve..."></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="confirm" id="confirmDelete" required>
                                        I understand that this action is irreversible and all my data will be permanently deleted.
                                    </label>
                                </div>
                                
                                <button type="submit" class="btn btn-danger" id="submitDeleteRequest">
                                    <i class="fa fa-trash"></i> Submit Delete Request
                                </button>
                                
                                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Cancel
                                </a>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</section>

<!-- jQuery -->
<script src="<?php echo base_url('assets/frontend/assets/js/jquery.min.js'); ?>"></script>
<!-- AJAX Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // Show/hide other reason field
    $('#deleteReason').on('change', function() {
        if ($(this).val() === 'other') {
            $('#otherReasonGroup').slideDown();
            $('#otherReason').prop('required', true);
        } else {
            $('#otherReasonGroup').slideUp();
            $('#otherReason').prop('required', false);
        }
    });
    
    // Submit delete request
    $('#deleteAccountForm').on('submit', function(e) {
        e.preventDefault();
        
        // Check confirmation
        if (!$('#confirmDelete').is(':checked')) {
            Swal.fire({
                icon: 'warning',
                title: 'Confirmation Required',
                text: 'Please confirm that you understand the consequences of deleting your account.'
            });
            return;
        }
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to request account deletion. This action requires admin approval.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, request deletion',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                
                $('#submitDeleteRequest').html('<i class="fa fa-spinner fa-spin"></i> Submitting...').prop('disabled', true);
                
                $.ajax({
                    url: '<?php echo base_url("dashboard/submit_delete_request"); ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Request Submitted!',
                                text: response.message,
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                            $('#submitDeleteRequest').html('<i class="fa fa-trash"></i> Submit Delete Request').prop('disabled', false);
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.'
                        });
                        $('#submitDeleteRequest').html('<i class="fa fa-trash"></i> Submit Delete Request').prop('disabled', false);
                    }
                });
            }
        });
    });
    
    // Cancel delete request
    $('#cancelDeleteRequest').on('click', function() {
        Swal.fire({
            title: 'Cancel Request?',
            text: "Are you sure you want to cancel your account deletion request?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel request',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                
                $(this).html('<i class="fa fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
                
                $.ajax({
                    url: '<?php echo base_url("dashboard/cancel_delete_request"); ?>',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cancelled!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            }
        });
    });
    
});
</script>

<style>
.dashboard-content .card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border-radius: 10px;
    overflow: hidden;
}

.dashboard-content .card-header {
    padding: 15px 20px;
}

.dashboard-content .card-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.checkbox-inline {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.checkbox-inline input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}
</style>