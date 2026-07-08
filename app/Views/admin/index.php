<?php
$userCount = 1;
$productCount = 1;
?>
 <!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<!-- Top Statistics -->
					<div class="row">
				
						<div class="col-xl-6 col-sm-6 p-b-15 lbl-card">
							<div class="card card-mini dash-card card-2">
								<div class="card-body">
									<h2 class="mb-1"><?php echo $user; ?></h2>
									<p>Profile</p>
									<span class="mdi mdi-account-clock"></span>
								</div>
							</div>
						</div>
				
						<div class="col-xl-6 col-sm-6 p-b-15 lbl-card">
							<div class="card card-mini dash-card card-4">
								<div class="card-body">
									<h2 class="mb-1"><?php echo $product; ?></h2>
									<p>Srvices</p>
									<span class="mdi mdi-currency-usd"></span>
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
                var fromDate =  '';
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
        "bJQueryUI": false // Disable default DataTables CSS
    });

    $('#searchname').on('keyup', function() {
        table.column(0).search(this.value).draw();
    });
    

    $('#search').on('click', function() {
       
      table.column(0).search(this.value).draw();
    });

    

	


    
});
</script>
 
