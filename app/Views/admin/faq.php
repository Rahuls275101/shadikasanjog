

  
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Faq</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <!-- Filter Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Filter FAQs</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Search by Question</label>
                                    <input type="text" class="form-control" id="searchQuestion" placeholder="Enter question...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Filter by Type</label>
                                    <select class="form-control" id="filterType">
                                        <option value="all">All Types</option>
                                        <option value="General">General</option>
                                                 <option value="Website Navigation" >Website Navigation</option>
                                        <option value="Verified Batch">Verified Batch</option>
                                        <option value="Green Batch">Green Batch</option>
                                        <option value="Orange Batch">Orange Batch</option>
                                        <option value="No Batch Holder">No Batch Holder</option>
                                        <option value="Profile Verification">Profile Verification</option>
                                        <option value="Membership Plans">Membership Plans</option>
                                        <option value="Refund Policy">Refund Policy</option>
                                        <option value="Membership Termination">Membership Termination</option>
                                        <option value="Miscellaneous">Miscellaneous</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Filter by Status</label>
                                    <select class="form-control" id="filterStatus">
                                        <option value="all">All Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button class="btn btn-secondary btn-block" id="resetFilters">
                                            <i class="fas fa-undo"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="badge badge-info" id="filterStats">
                                    Showing all records
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add FAQ Card -->
                <div class="card">
                    <?php if(session()->getFlashdata('failed')):?>
                        <div class="alert alert-danger alert-dismissable">
                            <?= session()->getFlashdata('failed') ?>
                        </div>
                    <?php endif;?>

                    <?php if(session()->getFlashdata('created')):?>
                        <div class="alert alert-success alert-dismissable">
                            <?= session()->getFlashdata('created') ?>
                        </div>
                    <?php endif;?>
                    
                    <div class="card-header">
                        <h3 class="card-title">Add New FAQ</h3>
                    </div>

                    <form role="form" method="POST" action="<?php echo base_url('admin/faq_process'); ?>" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Question *</label>
                                        <input type="text" required class="form-control" name="faq_question" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Status*</label>
                                        <select class="form-control" required name="faq_status">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select> 
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="type">Type*</label>
                                        <select class="form-control" required name="type" id="type">
                                            <option value="">-- Select Type --</option>
                                            <option value="General">General</option>
                                               <option value="Website Navigation" >Website Navigation</option>
                                            <option value="Verified Batch">Verified Batch</option>
                                            <option value="Green Batch">Green Batch</option>
                                            <option value="Orange Batch">Orange Batch</option>
                                            <option value="No Batch Holder">No Batch Holder</option>
                                            <option value="Profile Verification">Profile Verification</option>
                                            <option value="Membership Plans">Membership Plans</option>
                                            <option value="Refund Policy">Refund Policy</option>
                                            <option value="Membership Termination">Membership Termination</option>
                                            <option value="Miscellaneous">Miscellaneous</option>
                                        </select> 
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Answer*</label>
                                        <textarea class="form-control" name="faq_answer"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6" style="margin-top: 31px;">
                                    <div class="form-group">
                                        <input type="submit" name="CreateFaq" value="Submit" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- FAQ List Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">FAQ List</h3>
                        <div class="card-tools">
                            <?php 
                            // Fix the count issue - check if it's an object or array
                            $totalCount = 0;
                            if(is_array($faqView) || is_object($faqView)) {
                                $totalCount = count((array)$faqView);
                            }
                            ?>
                            <span class="badge badge-primary" id="totalRecords">Total: <?php echo $totalCount; ?></span>
                            <span class="badge badge-success" id="visibleRecords">Visible: <?php echo $totalCount; ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="faqTable">
                            <thead>
                                <tr>
                                    <th>#S.No</th>
                                    <th>Question</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php
                                if(!empty($faqView)):
                                    $clientSeId = 1;
                                    foreach ($faqView as $faq) {
                                ?>
                                <tr data-type="<?php echo isset($faq->type) ? htmlspecialchars($faq->type) : 'General'; ?>" 
                                    data-status=""
                                    data-question="<?php echo strtolower(htmlspecialchars($faq->faq_question)); ?>">
                                    <td><?php echo $clientSeId; ?></td>
                                    <td class="question-text"><?php echo htmlspecialchars($faq->faq_question); ?></td>
                                    <td>
                                        <span class="badge badge-info type-badge"><?php echo isset($faq->type) ? htmlspecialchars($faq->type) : 'General'; ?></span>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-block btn-<?php echo $faq->faq_status_color ?? 'secondary'; ?> btn-sm status-badge">
                                            <?php echo $faq->faq_status; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('admin/edit_faq/'.$faq->faq_id.''); ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="<?php echo base_url('admin/delete_faq/'.$faq->faq_id.'/'.$id ?? ''); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this FAQ?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr> 
                                <?php
                                        $clientSeId++;
                                    }
                                else:
                                ?>
                                <tr>
                                    <td colspan="5" class="text-center">No FAQs found</td>
                                </tr>
                                <?php endif; ?>             
                            </tbody>
                        </table>
                        
                        <!-- No results message -->
                        <div id="noResults" class="alert alert-info text-center" style="display: none;">
                            <i class="fas fa-info-circle"></i> No FAQs found matching your criteria.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript for Filtering -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const searchInput = document.getElementById('searchQuestion');
    const filterType = document.getElementById('filterType');
    const filterStatus = document.getElementById('filterStatus');
    const resetBtn = document.getElementById('resetFilters');
    const table = document.getElementById('faqTable');
    const tbody = table ? table.getElementsByTagName('tbody')[0] : null;
    const rows = tbody ? tbody.getElementsByTagName('tr') : [];
    const noResultsDiv = document.getElementById('noResults');
    const visibleRecordsSpan = document.getElementById('visibleRecords');
    const totalRecordsSpan = document.getElementById('totalRecords');
    
    // Store total records count (excluding "No FAQs found" row)
    let totalRecords = 0;
    for (let i = 0; i < rows.length; i++) {
        if (rows[i].cells.length > 1 && rows[i].cells[0].textContent !== 'No FAQs found') {
            totalRecords++;
        }
    }
    
    // Update total records display
    if (totalRecordsSpan) {
        totalRecordsSpan.textContent = 'Total: ' + totalRecords;
    }
    
    // Filter function
    function filterTable() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const typeValue = filterType ? filterType.value : 'all';
        const statusValue = filterStatus ? filterStatus.value : 'all';
        
        let visibleCount = 0;
        
        // Loop through all rows
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            
            // Skip the "No FAQs found" row
            if (row.cells.length > 1 && row.cells[0].textContent === 'No FAQs found') {
                continue;
            }
            
            const question = row.getAttribute('data-question') || '';
            const type = row.getAttribute('data-type') || 'General';
            const status = row.getAttribute('data-status') || '';
            
            // Check all conditions
            const matchesSearch = searchTerm === '' || question.includes(searchTerm);
            const matchesType = typeValue === 'all' || type === typeValue;
            const matchesStatus = statusValue === 'all' || status === statusValue;
            
            // Show/hide row based on conditions
            if (matchesSearch && matchesType && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }
        
        // Update visible records count
        if (visibleRecordsSpan) {
            visibleRecordsSpan.textContent = 'Visible: ' + visibleCount;
        }
        
        // Show/hide no results message
        if (noResultsDiv) {
            if (visibleCount === 0 && totalRecords > 0) {
                noResultsDiv.style.display = 'block';
            } else {
                noResultsDiv.style.display = 'none';
            }
        }
        
        // Update filter stats
        updateFilterStats(searchTerm, typeValue, statusValue, visibleCount);
    }
    
    // Update filter statistics
    function updateFilterStats(searchTerm, typeValue, statusValue, visibleCount) {
        const filterStats = document.getElementById('filterStats');
        if (!filterStats) return;
        
        let statsText = [];
        
        if (searchTerm) {
            statsText.push(`Search: "${searchTerm}"`);
        }
        if (typeValue !== 'all') {
            statsText.push(`Type: ${typeValue}`);
        }
        if (statusValue !== 'all') {
            statsText.push(`Status: ${statusValue}`);
        }
        
        if (statsText.length > 0) {
            filterStats.textContent = `Filtered: ${statsText.join(' | ')} | Showing ${visibleCount} of ${totalRecords} records`;
            filterStats.className = 'badge badge-warning';
        } else {
            filterStats.textContent = 'Showing all records';
            filterStats.className = 'badge badge-info';
        }
    }
    
    // Reset all filters
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (filterType) filterType.value = 'all';
        if (filterStatus) filterStatus.value = 'all';
        
        // Show all rows
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            if (row.cells.length > 1 && row.cells[0].textContent !== 'No FAQs found') {
                row.style.display = '';
            }
        }
        
        // Update counts and stats
        if (visibleRecordsSpan) {
            visibleRecordsSpan.textContent = 'Visible: ' + totalRecords;
        }
        if (noResultsDiv) {
            noResultsDiv.style.display = 'none';
        }
        
        const filterStats = document.getElementById('filterStats');
        if (filterStats) {
            filterStats.textContent = 'Showing all records';
            filterStats.className = 'badge badge-info';
        }
        
        // Remove highlights
        removeHighlights();
    }
    
    // Remove all highlights
    function removeHighlights() {
        document.querySelectorAll('.highlight').forEach(el => {
            const parent = el.parentNode;
            if (parent) {
                parent.replaceChild(document.createTextNode(el.textContent), el);
                parent.normalize();
            }
        });
    }
    
    // Add highlight effect for search matches
    function highlightSearch() {
        if (!searchInput) return;
        
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        // Remove previous highlights
        removeHighlights();
        
        // Add new highlights if search term exists
        if (searchTerm) {
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                if (row.style.display !== 'none') {
                    const questionCell = row.querySelector('.question-text');
                    if (questionCell) {
                        const originalText = questionCell.textContent;
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        if (regex.test(originalText)) {
                            questionCell.innerHTML = originalText.replace(
                                regex, 
                                '<span class="highlight" style="background-color: #ffeb3b; font-weight: bold; padding: 2px; border-radius: 2px;">$1</span>'
                            );
                        }
                    }
                }
            }
        }
    }
    
    // Add event listeners if elements exist
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            filterTable();
            highlightSearch();
        });
    }
    
    if (filterType) {
        filterType.addEventListener('change', function() {
            filterTable();
            highlightSearch();
        });
    }
    
    if (filterStatus) {
        filterStatus.addEventListener('change', function() {
            filterTable();
            highlightSearch();
        });
    }
    
    if (resetBtn) {
        resetBtn.addEventListener('click', resetFilters);
    }
    
    // Initialize - make sure all rows are visible
    if (totalRecords > 0) {
        resetFilters();
    }
});

// Add some CSS for better styling
const style = document.createElement('style');
style.textContent = `
    .highlight {
        background-color: #ffeb3b;
        font-weight: bold;
        padding: 2px;
        border-radius: 2px;
    }
    #filterStats {
        font-size: 14px;
        padding: 8px 12px;
    }
    .badge-info {
        background-color: #17a2b8;
        color: #fff;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #000;
    }
    .card-tools .badge {
        margin-left: 5px;
        font-size: 12px;
        padding: 5px 8px;
    }
    #searchQuestion, #filterType, #filterStatus {
        border-radius: 4px;
    }
    .btn-sm {
        margin: 2px;
    }
    .table td {
        vertical-align: middle;
    }
`;
document.head.appendChild(style);
</script>

