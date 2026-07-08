<div class="ec-content-wrapper">
    <div class="content">
        <div class="breadcrumb-wrapper breadcrumb-contacts">
            <div>
                <h1>All Users</h1>
                <p class="breadcrumbs">
                    <span><a href="#">User</a></span>
                    <span><i class="mdi mdi-chevron-right"></i></span>All Users
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="ec-cat-list card card-default">
                    
                    <?php if(session()->getFlashdata('failed')): ?>
                        <div class="alert alert-danger alert-dismissable">
                            <?= session()->getFlashdata('failed') ?>
                        </div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('created')): ?>
                        <div class="alert alert-success alert-dismissable">
                            <?= session()->getFlashdata('created') ?>
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <!-- Simple Search Form - Sirf 4 Options -->
                        <form id="simple-search-form">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Search By</label>
                                        <select class="form-control" id="search_type">
                                            <option value="name">Name</option>
                                            <option value="email">Email</option>
                                            <option value="phone">Phone</option>
                                            <option value="id">User ID</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Keyword</label>
                                        <input type="text" class="form-control" id="search_keyword" placeholder="Enter search keyword...">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" id="filter_status">
                                            <option value="">All</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Approval</label>
                                        <select class="form-control" id="filter_approval">
                                            <option value="">All</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Unapproved">Unapproved</option>
                                        </select>
                                    </div>
                                </div>
                                
                                
                                   <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Membership </label>
                                        <select class="form-control" id="filter_membership">
                                        <option value="">Select Plan</option>
                                            <option value="1" data-price="1995" data-months="6">Free Plan</option>
                                        <option value="2" data-price="1995" data-months="6">Defence Plan</option>
                                        <option value="3" data-price="6990" data-months="6">Non-Defence Plan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 text-center mt-3">
                                    <button type="button" id="btn-search" class="btn btn-success">
                                        <i class="mdi mdi-magnify"></i> Search
                                    </button>
                                    <button type="button" id="btn-reset" class="btn btn-danger">
                                        <i class="mdi mdi-refresh"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12">
                <div class="ec-cat-list card card-default">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="ajax_table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#S.No</th>
                                        <th>Image</th>
                                        <th>Info</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    var table = $('#ajax_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('admin/customerlist'); ?>",
            type: "POST",
            data: function(data) {
                // Sirf 4 search parameters
                data.search_type = $('#search_type').val();
                data.search_keyword = $('#search_keyword').val();
                data.status = $('#filter_status').val();
                data.approval = $('#filter_approval').val();
                 data.membership = $('#filter_membership').val();
                
                
            }
        },
        columns: <?= $table_column ?>,
        pageLength: 25,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'
        }
    });

    // Search button click
    $('#btn-search').on('click', function() {
        table.ajax.reload();
    });

    // Enter key press in keyword field
    $('#search_keyword').on('keypress', function(e) {
        if (e.which == 13) {
            table.ajax.reload();
        }
    });

    // Reset button click
    $('#btn-reset').on('click', function() {
        $('#search_type').val('name');
        $('#search_keyword').val('');
        $('#filter_status').val('');
        $('#filter_approval').val('');
        table.ajax.reload();
    });

    // ========== TRENDING STATUS CHANGE ==========
    $(document).on('click', '.changeTrendingStatus', function() {
        var button = $(this);
        var user_id = button.data('id');
        var currentStatus = button.data('status');
        var newStatus = (currentStatus === 'Yes') ? 'No' : 'Yes';

        Swal.fire({
            title: 'Are you sure?',
            text: 'Change trending status to ' + newStatus + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('admin/change_trending_status'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: { user_id: user_id, status: currentStatus },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: 'Trending status changed',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            button.text(response.newStatus === 'Yes' ? 'Trending' : 'Not Trending');
                            button.data('status', response.newStatus);

                            if (response.newStatus === 'Yes') {
                                button.removeClass('btn-secondary').addClass('btn-warning');
                            } else {
                                button.removeClass('btn-warning').addClass('btn-secondary');
                            }

                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });

    // ========== MAIN STATUS CHANGE ==========
    $(document).on('click', '.changeMainStatus', function() {
        var button = $(this);
        var user_id = button.data('id');
        var currentStatus = button.data('status');
        var newStatus = (currentStatus === 'Approved') ? 'Unapproved' : 'Approved';

        Swal.fire({
            title: 'Are you sure?',
            text: 'Change main status to ' + newStatus + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('admin/change_status'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: { user_id: user_id, status: currentStatus },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: 'Main status changed to ' + response.newStatus,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            button.text(response.newStatus);
                            button.data('status', response.newStatus);
                            if (response.newStatus === 'Approved') {
                                button.removeClass('btn-danger').addClass('btn-success');
                            } else {
                                button.removeClass('btn-success').addClass('btn-danger');
                            }

                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });

    // ========== SECTION STATUS UPDATE ==========
    $(document).on('click', '.status-badge', function() {
        var badge = $(this);
        var user_id = badge.data('id');
        var status_type = badge.data('type');
        var currentStatus = badge.data('current-status');

        Swal.fire({
            title: 'Update ' + status_type.charAt(0).toUpperCase() + status_type.slice(1) + ' Status',
            text: 'Current status: ' + currentStatus,
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'Approved',
            denyButtonText: 'Rejected',
            cancelButtonText: 'Pending',
            confirmButtonColor: '#28a745',
            denyButtonColor: '#dc3545',
            cancelButtonColor: '#ffc107',
        }).then((result) => {
            var newStatus = '';
            if (result.isConfirmed) {
                newStatus = 'approved';
            } else if (result.isDenied) {
                newStatus = 'rejected';
            } else if (result.isDismissed) {
                newStatus = 'pending';
            } else {
                return;
            }

            Swal.fire({
                title: 'Updating...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "<?= base_url('admin/update_all_statuses'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    user_id: user_id,
                    status_type: status_type,
                    new_status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Something went wrong!', 'error');
                }
            });
        });
    });

});
</script>

<style>
.status-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 3px;
    max-height: 200px;
    overflow-y: auto;
    padding: 5px;
}

.status-badge {
    transition: all 0.3s;
    font-size: 10px;
    padding: 4px 6px;
    border-radius: 12px;
    cursor: pointer;
    display: inline-block;
    margin: 2px;
}

.status-badge:hover {
    opacity: 0.8;
    transform: scale(1.05);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.cat-thumb {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
}

#ajax_table td {
    vertical-align: middle;
}

.btn-sm {
    margin: 2px;
}

.mdi {
    font-size: 14px;
}
</style>