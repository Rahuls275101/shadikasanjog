
	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All Brand </h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Brand 
							</p>
						</div>

						<div>
						
							<a href="javascript:void(0);" class="btn btn-primary" id="Recordbrand" data-toggle="modal" >Add brand</a>
							   
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
													<th>Photo</th>
													<th>Brand</th>
											
												
												
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
<div class="modal fade" id="addbrand" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create New brand</h5>
        <button type="button" class="close" id="addbrandclose"  data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="Savedbrand" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">

                   

                  <div class="col-md-8">  
                  <div class="form-group">
                  <label for="exampleInputEmail1"> brand name*</label>
                  <input type="text" class="form-control" name="brand_name" required>
                  </div>
                  </div>

                  <div class="col-md-4"> 
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" name="brand_status" required>
                     <option>Active</option>
                     <option>Inactive</option>
                    </select>  
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Menu brand image*</label>
                    <input type="file" class="form-control" name="brand_image" required>
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
<div class="modal fade" id="editbrand" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update  brand</h5>
        <button type="button" class="close" id="editbrandclose" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="Editedbrand" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">

                  <div class="col-md-8">
                  <div class="form-group">
                    <label for="exampleInputEmail1">brand Name*</label>
                    <input type="text" class="form-control" name="edit_brand_name" id="edit_brandName" required>
                  </div>
                  </div>

                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status</label>
                    <select class="form-control" id="edit_brandStatus" name="edit_brand_status" required>
                     <option>Active</option>
                     <option>Inactive</option>
                    </select>  
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Menu brand Image</label>
                    <input type="file" class="form-control" name="edit_brand_image">
                    <input type="hidden" class="form-control" name="edit_brand_image_old" id="edit_brandImage">
                  </div>
                  </div>
            
             
                 

                  <div>
                  <input type="hidden" name="edit_brand_id" id="edit_brandID" value="">  
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
            "url": "<?php echo base_url('admin/brand-list'); ?>",
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

    
    
$('#Savedbrand').submit(function(event){
    event.preventDefault(); // Prevent the default form submission
    
    var formData = new FormData($(this)[0]);
    $(":submit").attr("disabled", true);
    
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/brand_save'); ?>", // Make sure URL is correctly enclosed in quotes
        processData: false,
        contentType: false,
        dataType: "JSON",
        data: formData,
        success: function(data){
            $('#addbrandclose').click();
            $("#Savedbrand")[0].reset();
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


	$('#Editedbrand').submit('click',function(){
  var formData = new FormData($(this)[0]);
  $(":submit").attr("disabled", true);
  
		$.ajax({
			type : "POST",
			url  : "<?php echo base_url('admin/brand_update'); ?>",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
			$('#editbrand').modal('hide');
				
				$("#Editedbrand")[0].reset();
				 $(":submit").attr("disabled", false);
			 table.ajax.reload();
			}
		});
		return false;
	});

	
	$('#ajax_table').on('click','.editRecordbrand',function(){
	   
	    
		$('#editbrand').modal('show');
		$("#edit_brandID").val($(this).data('brand_id'));
	

		$("#edit_brandName").val($(this).data('brand_name'));
		$("#edit_brandStatus").val($(this).data('brand_status'));
    $("#edit_brandImage").val($(this).data('brand_image'));
  
    
	});
	


 

  


	$('#Recordbrand').on('click',function(){
	   

    $('#addbrand').modal('show');
	});




    
});
</script>
 