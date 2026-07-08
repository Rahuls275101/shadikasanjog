<section>
        <div class="db">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-lg-3">
                   <?php echo view('frontend/dashboard/sidebar'); ?>
                    </div>
                    <div class="col-md-8 col-lg-9">
                        <div class="row">
                            <div class="col-md-12 db-sec-com">
                                <h2 class="db-tit">Interest request</h2>
                                <div class="db-pro-stat">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?php echo base_url('update-profile'); ?>">Edid profile</a></li>
                                            <li><a class="dropdown-item" href="<?php echo base_url('profile'); ?>">View profile</a></li>
                                            <li><a class="dropdown-item" href="<?php echo base_url('user-plans'); ?>">Plan change</a></li>
                                  
                                        </ul>
                                    </div>
                                    <div class="db-inte-main">

                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#home" data-status="pending">
                                                    New requests <span class="pending-count badge bg-danger">0</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#menu1" data-status="accepted">
                                                    Accept request <span class="accepted-count badge bg-success">0</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#menu2" data-status="rejected">
                                                    Denay request <span class="rejected-count badge bg-warning">0</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div id="home" class="container tab-pane active"><br>
                                                <div class="db-inte-prof-list">
                                                    <ul id="pending_list">
                                                        <li class="text-center">Loading...</li>
                                                    </ul>
                                                    <div id="pending_pagination" class="mt-3"></div>
                                                </div>
                                            </div>
                                            <div id="menu1" class="container tab-pane fade"><br>
                                                <div class="db-inte-prof-list">
                                                    <ul id="accepted_list">
                                                        <li class="text-center">Loading...</li>
                                                    </ul>
                                                    <div id="accepted_pagination" class="mt-3"></div>
                                                </div>
                                            </div>
                                            <div id="menu2" class="container tab-pane fade"><br>
                                                <div class="db-inte-prof-list">
                                                    <ul id="rejected_list">
                                                        <li class="text-center">Loading...</li>
                                                    </ul>
                                                    <div id="rejected_pagination" class="mt-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->



<script>
$(document).ready(function() {

    // Load data on page load
    ajax_list(1);

    // Filter or search triggers
    $('.chosen-select').on('change', function() {
        ajax_list(1);
    });

    $('#btnSearch').on('click', function() {
        ajax_list(1);
    });

    // ✅ Tab click handler
    $('.nav-tabs a').on('click', function(e) {
        e.preventDefault();
        const status = $(this).attr('href').replace('#', '');
        if(status === 'home') {
            ajax_list(1, 'pending');
        } else if(status === 'menu1') {
            ajax_list(1, 'accepted');
        } else if(status === 'menu2') {
            ajax_list(1, 'rejected');
        }
    });

    // ✅ Pagination click for all lists (pending/accepted/rejected)
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('/').pop();
        const currentTab = $('.tab-pane.active').attr('id');
        
        let status = 'pending';
        if(currentTab === 'menu1') status = 'accepted';
        else if(currentTab === 'menu2') status = 'rejected';
        
        ajax_list(page, status);
    });

    // ✅ AJAX function - POST method with correct URL
    function ajax_list(page = 1, status = 'pending') {
        const data = {
            status: status,
            page: page
        };

        $.ajax({
            url: "<?php echo base_url('ajax_list_interests'); ?>",
            method: "POST",
            dataType: "JSON",
            data: data,
            beforeSend: function() {
                if(status === 'pending') {
                    $('#pending_list').html('<p style="text-align:center;">Loading profiles...</p>');
                } else if(status === 'accepted') {
                    $('#accepted_list').html('<p style="text-align:center;">Loading profiles...</p>');
                } else if(status === 'rejected') {
                    $('#rejected_list').html('<p style="text-align:center;">Loading profiles...</p>');
                }
            },
            success: function(res) {
                if (res.status) {
                    // ✅ Inject HTML for each list based on current status
                    if(status === 'pending') {
                        $('#pending_list').html(res.data.list);
                        $('#pending_pagination').html(res.data.pagination);
                    } else if(status === 'accepted') {
                        $('#accepted_list').html(res.data.list);
                        $('#accepted_pagination').html(res.data.pagination);
                    } else if(status === 'rejected') {
                        $('#rejected_list').html(res.data.list);
                        $('#rejected_pagination').html(res.data.pagination);
                    }
                    
                    // ✅ Update counts in tabs
                    if(res.counts) {
                        $('.pending-count').text(res.counts.pending || 0);
                        $('.accepted-count').text(res.counts.accepted || 0);
                        $('.rejected-count').text(res.counts.rejected || 0);
                    }
                } else {
                    const errorMsg = '<p class="text-center">No data found</p>';
                    if(status === 'pending') {
                        $('#pending_list').html(errorMsg);
                    } else if(status === 'accepted') {
                        $('#accepted_list').html(errorMsg);
                    } else if(status === 'rejected') {
                        $('#rejected_list').html(errorMsg);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                const errorMsg = '<p class="text-center text-danger">Error loading data</p>';
                if(status === 'pending') {
                    $('#pending_list').html(errorMsg);
                } else if(status === 'accepted') {
                    $('#accepted_list').html(errorMsg);
                } else if(status === 'rejected') {
                    $('#rejected_list').html(errorMsg);
                }
            }
        });
    }
    
    
    // ---- Send Interest ----
    $(document).on('click', '.send-interest', function() {
        let card = $(this).closest('.profile-card');
        let receiverId = $(this).data('receiver-id');

        $.ajax({
            url: "<?php echo base_url('interest/send'); ?>",
            type: "POST",
            data: {
                receiver_id: receiverId,
                message: 'Hi, I am interested in your profile.'
            },
            dataType: "json",
            success: function(response) {
                // ✅ Show same message returned from PHP
                if (response.status) {
                    // Reload current tab
                    const currentTab = $('.tab-pane.active').attr('id');
                    let status = 'pending';
                    if(currentTab === 'menu1') status = 'accepted';
                    else if(currentTab === 'menu2') status = 'rejected';
                    
                    ajax_list(1, status);
                    
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Disable button and show status
                    card.find('.status-message').text(response.message);
                    card.find('.send-interest')
                        .prop('disabled', true)
                        .text('Interest Sent');
                } else {
                    Swal.fire({
                        icon: 'warning',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong. Please try again later.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // ---- Accept Interest ----
    $(document).on('click', '.accept-interest', function() {
        let card = $(this).closest('li');
        let interestId = $(this).data('id');
        
        $.ajax({
            url: "<?php echo base_url('interest/accept'); ?>",
            type: "POST",
            data: {
                interest_id: interestId,
                action: 'accept'
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    // Reload pending tab
                    ajax_list(1, 'pending');
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong. Please try again later.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // ---- Reject Interest ----
    $(document).on('click', '.reject-interest', function() {
        let card = $(this).closest('li');
        let interestId = $(this).data('id');

        $.ajax({
            url: "<?php echo base_url('interest/accept'); ?>",
            type: "POST",
            data: {
                interest_id: interestId,
                action: 'reject'
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    // Reload pending tab
                    ajax_list(1, 'pending');
                    Swal.fire({
                        icon: 'info',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong. Please try again later.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

});
</script>