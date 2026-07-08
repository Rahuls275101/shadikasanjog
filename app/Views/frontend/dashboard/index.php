<?php 
use App\Models\Commanmodel;
$commanmodel = new Commanmodel();
$session = session();
$usersession = $session->get('loggedin');
?>

<style>
    .verified-badge {
font-size: 11px;
  /*padding: 3px 8px;*/
  border-radius: 12px;
  /*margin-left: 8px;*/
  color: #fff;
  font-weight: 600;

  background-color: #1e73be;

}
</style>
 
<section>
    <div class="db">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-lg-3">
                    <?php echo view('frontend/dashboard/sidebar'); ?>
                </div>
                <div class="col-md-8 col-lg-9 bgcolor">
                    <div class="row box-showdow">
                        <!-- Mutual Interests - जहाँ दोनों ने Accept किया -->
                        <div class="col-md-12 col-lg-12 col-xl-4 db-sec-com">
                            <h2 class="db-tit font-size-26">Mutual Interests <span class="badge bg-primary" id="mutual-count">0</span></h2>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="list-view">
                                        <ul class="list-group d-xl-flex flex-wrap" id="mutual-list">
                                            <li class="text-center py-3">Loading mutual interests...</li>
                                        </ul>
                                        <div class="text-center mt-3" id="mutual-load-more" style="display: none;">
                                            <a href="#" class="view-btn see load-more" data-type="mutual">See More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Interests Received - केवल Pending (जो Accept नहीं हुए) -->
                        <div class="col-lg-12 col-xl-4 db-sec-com">
                            <h2 class="db-tit font-size-26"> Interests Received <span class="badge bg-warning" id="received-count">0</span></h2>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="list-view">
                                        <ul class="list-group d-xl-flex flex-wrap" id="received-list">
                                            <li class="text-center py-3">Loading received interests...</li>
                                        </ul>
                                        <div class="text-center mt-3" id="received-load-more" style="display: none;">
                                            <a href="#" class="view-btn see load-more" data-type="received">See More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Interests Sent - केवल Pending (जो Accept नहीं हुए) -->
                        <div class="col-lg-12 col-xl-4 db-sec-com">
                            <h2 class="db-tit font-size-26"> Interests Sent <span class="badge bg-danger" id="sent-count">0</span></h2>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="list-view">
                                        <ul class="list-group d-xl-flex flex-wrap" id="sent-list">
                                            <li class="text-center py-3">Loading sent interests...</li>
                                        </ul>
                                        <div class="text-center mt-3" id="sent-load-more" style="display: none;">
                                            <a href="#" class="view-btn see load-more" data-type="sent">See More</a>
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

<!-- jQuery -->
<script src="<?php echo base_url('assets/frontend/assets/js/jquery.min.js'); ?>"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    let offsets = {
        mutual: 0,
        received: 0,
        sent: 0
    };
    const limit = 6;

    // Initialize
    loadCounts();
    loadInterests('mutual', 0);
    loadInterests('received', 0);
    loadInterests('sent', 0);

    function loadCounts() {
        $.ajax({
            url: "<?php echo base_url('dashboard/getCounts'); ?>",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status && response.counts) {
                    $('#mutual-count').text(response.counts.mutual);
                    $('#received-count').text(response.counts.received);
                    $('#sent-count').text(response.counts.sent);
                }
            },
            error: function() {
                console.log('Error loading counts');
            }
        });
    }

    function loadInterests(type, offset, append = false) {
        const listId = type + '-list';
        const loadMoreId = type + '-load-more';

        if (!append) {
            $('#' + listId).html('<li class="text-center py-3">Loading...</li>');
        }

        $.ajax({
            url: "<?php echo base_url('dashboard/ajax_dashboard_interests'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                type: type,
                limit: limit,
                offset: offset
            },
            success: function(response) {
                if (response.status) {
                    if (response.html) {
                        if (append) {
                            $('#' + listId).append(response.html);
                        } else {
                            $('#' + listId).html(response.html);
                        }
                        
                        if (response.hasMore) {
                            $('#' + loadMoreId).show();
                        } else {
                            $('#' + loadMoreId).hide();
                        }
                        
                        offsets[type] = offset + response.count;
                    } else {
                        $('#' + listId).html('<li class="text-center py-3">No interests found</li>');
                        $('#' + loadMoreId).hide();
                    }
                } else {
                    $('#' + listId).html('<li class="text-center py-3">No interests found</li>');
                    $('#' + loadMoreId).hide();
                }
            },
            error: function() {
                $('#' + listId).html('<li class="text-center py-3 text-danger">Error loading data</li>');
                $('#' + loadMoreId).hide();
            }
        });
    }
    
        $(document).on('click', '.accept-interest', function(e) {
        
        alert();
        e.preventDefault();
        const interestId = $(this).data('id');
        
        Swal.fire({
            title: 'Accept Interest?',
            text: 'Do you want to accept this interest request?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, accept it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo base_url('interest/accept'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        interest_id: interestId,
                        action: 'accept'
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Accepted!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            // Reload all sections
                            setTimeout(function() {
                                offsets = { mutual: 0, received: 0, sent: 0 };
                                loadInterests('mutual', 0);
                                loadInterests('received', 0);
                                loadInterests('sent', 0);
                                loadCounts();
                            }, 500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '.reject-interest', function(e) {
        e.preventDefault();
        const interestId = $(this).data('id');
        
        Swal.fire({
            title: 'Reject Interest?',
            text: 'Do you want to reject this interest request?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo base_url('interest/accept'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        interest_id: interestId,
                        action: 'reject'
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            // Reload received section only
                            setTimeout(function() {
                                offsets.received = 0;
                                loadInterests('received', 0);
                                loadCounts();
                            }, 500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '.remove-interest, .cancel-interest', function(e) {
        e.preventDefault();
        const interestId = $(this).data('id');
        const button = $(this);
        const action = button.hasClass('cancel-interest') ? 'cancel' : 'remove';
        
        const actionText = action === 'cancel' ? 'Cancel' : 'Remove';
        
        Swal.fire({
            title: actionText + ' Interest?',
            text: 'Are you sure you want to ' + action + ' this interest?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, ' + action + ' it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = action === 'cancel' 
                    ? "<?php echo base_url('dashboard/cancelSentInterest'); ?>"
                    : "<?php echo base_url('dashboard/removeInterest'); ?>";
                
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        interest_id: interestId
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            // Reload all sections
                            setTimeout(function() {
                                offsets = { mutual: 0, received: 0, sent: 0 };
                                loadInterests('mutual', 0);
                                loadInterests('received', 0);
                                loadInterests('sent', 0);
                                loadCounts();
                            }, 500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '.load-more', function(e) {
        e.preventDefault();
        const type = $(this).data('type');
        loadInterests(type, offsets[type], true);
    });



    setInterval(function() {
        loadCounts();
    }, 30000);
});
</script>

<style>
.list-view {
    min-height: 300px;
}
.list-group-item {
    border: none;
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 10px !important;
}
.list-group-item.blue {
    background-color: #e3f2fd; /* Mutual - Light Blue */
}
.list-group-item.yellow {
    background-color: #fff3cd; /* Received - Light Yellow */
}
.list-group-item.red {
    background-color: #f8d7da; /* Sent - Light Red */
}
.detal-list {
    list-style: none;
    padding-left: 0;
}
.detal-list li {
    padding: 2px 0;
    font-size: 14px;
}
.view-btn {
    display: inline-block;
    background: #007bff;
    color: white;
    padding: 5px 15px;
    border-radius: 5px;
    text-decoration: none;
    margin-top: 10px;
    text-align: center;
    font-size: 14px;
}
.view-btn:hover {
    background: #0056b3;
    color: white;
}
.view-btn.see {
    background: #6c757d;
}
.view-btn.see:hover {
    background: #545b62;
}
.btn-group .view-btn {
    margin: 0 2px;
    padding: 3px 10px;
    font-size: 12px;
}
.btn-group .view-btn:first-child {
    background: #28a745;
}
.btn-group .view-btn:last-child {
    background: #dc3545;
}
.badge {
    font-size: 12px;
    margin-left: 5px;
}
.text-danger {
    color: #dc3545;
    font-size: 12px;
    font-weight: bold;
}
</style>