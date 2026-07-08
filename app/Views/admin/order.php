	<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All Order </h1>
							<p class="breadcrumbs"><span><a href="#">Order</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Order 
							</p>
						</div>

						<div>
						
						
						</div>
					</div>
					<div class="row">
					    	<div class="col-xl-12 col-lg-12">
					    <div class="ec-cat-list card card-default">
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
            <!-- /.card-header -->
            <div class="card-body">
    <form id="news-filter">
    <div class="row">


    <div class="col-md-6">
    <div class="form-group">
    <label>Search with name</label>
    <input type="text" class="form-control" id="search_date">
    </div>
    </div>

    <div class="col-md-3">
    <div class="form-group">
    <label>Status</label>
    <select class="form-control" id="status">
    <option value="">Select</option>
    <option value="Active">Active</option>
    <option value="Inactive">Inactive</option>
         </select> 
    </div>
    </div>


 

    <div class="col-md-3" style="margin-top: 31px;">
    <div class="form-group">
    <button type="button" id="btn-filter-userlist" style="margin-right: 10px" class="btn btn-success">Search</button>
    <button type="button" id="btn-reset-userlist" class="btn btn-danger">Reset Search</button>
    </div>
    </div>

    </div>
    </form>                            


            </div>
            <!-- /.card-body -->
          </div>
           </div>
						<div class="col-xl-12 col-lg-12">
							<div class="ec-cat-list card card-default">
								<div class="card-body">
									<div class="table-responsive">
									   
				    <table id="ajax_table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#Order Id</th>
                <th>Image</th>
                  <th>Item Name</th>
             
                  <th>Category</th>
                  <th>Price</th>
                  <th>Refer By</th>
                  <th>Seller</th>
                  <th>Payment Type</th>
                  <th>Shipping Address</th>
      
                  <th>Status</th>
                  <th>Action</th>
                  
                </tr>
                </thead>
                <tbody>               
                </tbody>
              </table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->





<script>
    $(document).ready(function() {
    var table = $('#ajax_table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo base_url('admin/orderlist'); ?>",
            "type": "POST",
            "data": function(data) {
                // Add custom parameters to the AJAX request data
                var fromDate =  $('#search_date').val();
                data.searchname = fromDate;
            }
        },
        "columns": <?= $table_column ?>,
        "pagingType": "simple_numbers_no_ellipses",
        "oLanguage": {
            "oPaginate": {
                "sFirst": "First",
                "sPrevious": "Previous",
                "sNext": "Next"
            }
        },
        "language": {
            "processing": '<div class="custom-spinner"></div> ' 
        },
        "initComplete": function () {
            // Apply your custom CSS styles to the table
            $('#ajax_table').addClass('custom-datatable');
        },
       "bJQueryUI": false,
        "dom": '<"top"lB>frtip', 
    "buttons": [
        {
            extend: 'excelHtml5',  // Excel export button
            text: 'Export to Excel', // Button text
            className: 'btn btn-primary',  // Button CSS class
            title: 'Order_' + new Date().toLocaleDateString(), // Custom file name (e.g., "Custom_Filename_02/06/2025.xlsx")
            exportOptions: {
                // You can add options here to export only certain columns if needed
                // columns: [0, 1, 2]  // Example: only export the first 3 columns
            }
            
        }
    ]
    });

    $('#searchname').on('keyup', function() {
        table.column(0).search(this.value).draw();
    });
    

    $('#search').on('click', function() {
       
      table.column(0).search(this.value).draw();
    });

    

	


    
});
</script>
 