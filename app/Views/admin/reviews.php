
	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All Reviews </h1>
							<p class="breadcrumbs"><span><a href="#">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Reviews 
							</p>
						</div>

						<div>
						
						
							   
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 col-lg-12">
							<div class="ec-cat-list card card-default">
								<div class="card-body">
									<div class="table-responsive">
									   
										<table  id="ajax_table" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>ID</th>
													<th>Info</th>
													<th>Product</th>
													<th>Rating</th>
													<th>Messages</th>
											
												
												
													<th>Action</th>
												</tr>
											</thead>

										
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->



<script>


     $(document).ready(function () {
    var table = $('#ajax_table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo base_url('admin/reviews-list'); ?>",
            "type": "POST",
            "data": function(data) {
                // Add custom parameters to the AJAX request data
                var fromDate =  '';
                data.searchname = fromDate;
            
                data.id = '';
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
        "bJQueryUI": false // Disable default DataTables CSS
    });

    $('#searchname').on('keyup', function() {
        table.column(0).search(this.value).draw();
    });
    

    $('#search').on('click', function() {
       
      table.column(0).search(this.value).draw();
    });

    
    




	
$('#ajax_table').on('click', '.editRecordreviews', function() {
    var reviews_id = $(this).data('reviews_id');
    var status = $(this).data('status');

    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/reviews_update'); ?>",
        dataType: "JSON",
        data: {
            reviews_id: reviews_id,
            status: status
        },
        success: function(data) {
            // Reload the table after successful update
            table.ajax.reload();
        },
        error: function(xhr, status, error) {
            console.log('Error:', error);
        }
    });
});



 

  


	$('#Recordbrand').on('click',function(){
	   

    $('#addbrand').modal('show');
	});




    
});
</script>
 