
	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All Ship Charges </h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Ship Charges 
							</p>
						</div>

						<div>
						
							<a href="javascript:void(0);" class="btn btn-primary" id="Recordshipcharge" data-toggle="modal" >Add shipcharge</a>
							   
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
												
													<th>Ship Charges</th>
											        <th>Ship Pin</th>
												
												
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





  <!-- Modal -->
<div class="modal fade" id="addshipcharge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create New Ship Charges </h5>
        <button type="button" class="close" id="addshipchargeclose"  data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="Savedshipcharge" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">

                   

                  <div class="col-md-4">  
                  <div class="form-group">
                  <label for="exampleInputEmail1"> Ship Charges*</label>
                  <input type="text" class="form-control" name="shipcharge" required>
                  </div>
                  </div>
                  
                  <div class="col-md-4">  
                  <div class="form-group">
                  <label for="exampleInputEmail1"> Ship Pin*</label>
                  <input type="text" class="form-control" name="shipcharge_pin" required>
                  </div>
                  </div>

                  <div class="col-md-4"> 
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" name="shipcharge_status" required>
                     <option>Active</option>
                     <option>Inactive</option>
                    </select>  
                  </div>
                  </div>

               



               


                  <div>
                     
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>

              </div>
                </div>
                <!-- /.card-body -->

              </form>
      </div>
    </div>
  </div>
</div>


 <!-- Modal -->
<div class="modal fade" id="editshipcharge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Ship Charges </h5>
        <button type="button" class="close" id="editshipchargeclose" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="Editedshipcharge" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">

                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Ship Charges*</label>
                    <input type="text" class="form-control" name="edit_shipcharge" id="edit_shipcharge" required>
                  </div>
                  </div>
                  
                  
                   <div class="col-md-4">  
                  <div class="form-group">
                  <label for="exampleInputEmail1"> Ship Pin*</label>
                  <input type="text" class="form-control" name="edit_shipcharge_pin" id="edit_shipchargePin" required>
                  </div>
                  </div>

                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status</label>
                    <select class="form-control" id="edit_shipchargeStatus" name="edit_shipcharge_status" required>
                     <option>Active</option>
                     <option>Inactive</option>
                    </select>  
                  </div>
                  </div>

               
             
                 

                  <div>
                  <input type="hidden" name="edit_shipcharge_id" id="edit_shipchargeID" value="">  
                  <button type="submit" class="btn btn-primary">Submit</button>
                  </div>


                  </div>
                </div>
                <!-- /.card-body -->

              </form>
      </div>
    </div>
  </div>
</div>



<script>


     $(document).ready(function () {
    var table = $('#ajax_table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo base_url('admin/shipcharge-list'); ?>",
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

    
    
$('#Savedshipcharge').submit(function(event){
    event.preventDefault(); // Prevent the default form submission
    
    var formData = new FormData($(this)[0]);
    $(":submit").attr("disabled", true);
    
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/shipcharge_save'); ?>", // Make sure URL is correctly enclosed in quotes
        processData: false,
        contentType: false,
        dataType: "JSON",
        data: formData,
        success: function(data){
            $('#addshipchargeclose').click();
            $("#Savedshipcharge")[0].reset();
            $(":submit").attr("disabled", false);
            table.ajax.reload();
        },
        error: function() {
            // Optionally handle AJAX errors here
            $(":submit").attr("disabled", false);
        }
    });
    return false; // Prevent default form submission
});


	$('#Editedshipcharge').submit('click',function(){
  var formData = new FormData($(this)[0]);
  $(":submit").attr("disabled", true);
  
		$.ajax({
			type : "POST",
			url  : "<?php echo base_url('admin/shipcharge_update'); ?>",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
			$('#editshipcharge').modal('hide');
				
				$("#Editedshipcharge")[0].reset();
				 $(":submit").attr("disabled", false);
			 table.ajax.reload();
			}
		});
		return false;
	});

	
	$('#ajax_table').on('click','.editRecordshipcharge',function(){
	   
	    
		$('#editshipcharge').modal('show');
		$("#edit_shipchargeID").val($(this).data('shipcharge_id'));
	

		$("#edit_shipcharge").val($(this).data('shipcharge'));
		$("#edit_shipchargePin").val($(this).data('shipcharge_pin'));
		$("#edit_shipchargeStatus").val($(this).data('shipcharge_status'));
  
  
    
	});
	


 

  


	$('#Recordshipcharge').on('click',function(){
	   

    $('#addshipcharge').modal('show');
	});




    
});
</script>
 