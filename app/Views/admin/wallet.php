

	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1> Wallet </h1>
							<p class="breadcrumbs"><span><a href="#">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Wallet 
							</p>
						</div>

						<div>
						
						

							   
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 col-lg-12">
						    
						  <?php   if($userdetails->id > 1) { ?>
						  	<div class="ec-cat-list card card-default">
								<div class="card-body">
								    	<h2> Wallet Amount :  <?php echo $userdetails->wallet; ?> </h2>
								</div>
							</div>
						  <?php } ?>
						    
						    
						    
						    
						    
						    
						    
						    
						    
						    
						    
							<div class="ec-cat-list card card-default">
								<div class="card-body">
									<div class="table-responsive">
									   
										<table  id="ajax_table" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>ID</th>
													<th>Product Name</th>
													<th>Amount</th>
												
													<th>Date</th>
											     <th>Users</th>
											
											
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
            "url": "<?php echo base_url('admin/wallet-list'); ?>",
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
        "bJQueryUI": false,
        "dom": '<"top"lB>frtip', 
    "buttons": [
        {
            extend: 'excelHtml5',  // Excel export button
            text: 'Export to Excel', // Button text
            className: 'btn btn-primary',  // Button CSS class
            title: 'wallet_' + new Date().toLocaleDateString(), // Custom file name (e.g., "Custom_Filename_02/06/2025.xlsx")
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

    
    




	
$('#ajax_table').on('click', '.editRecordwallet', function() {
    var wallet_id = $(this).data('wallet_id');
    var status = $(this).data('status');

    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/wallet_update'); ?>",
        dataType: "JSON",
        data: {
            wallet_id: wallet_id,
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
 