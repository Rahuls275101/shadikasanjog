
	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All Collections </h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Collections 
							</p>
						</div>

						<div>
						
							<a href="javascript:void(0);" class="btn btn-primary" id="Recordcollections" data-toggle="modal" >Add Collections</a>
							   
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
												
													<th>Collections</th>
											
												
												
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
<div class="modal fade" id="addcollections" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create New collections</h5>
        <button type="button" class="close" id="addcollectionsclose"  data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="Savedcollections" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">

                   

                  <div class="col-md-8">  
                  <div class="form-group">
                  <label for="exampleInputEmail1"> collections name*</label>
                  <input type="text" class="form-control" name="collections_name" required>
                  </div>
                  </div>

                  <div class="col-md-4"> 
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" name="collections_status" required>
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
<div class="modal fade" id="editcollections" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update  collections</h5>
        <button type="button" class="close" id="editcollectionsclose" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="Editedcollections" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">

                  <div class="col-md-8">
                  <div class="form-group">
                    <label for="exampleInputEmail1">collections Name*</label>
                    <input type="text" class="form-control" name="edit_collections_name" id="edit_collectionsName" required>
                  </div>
                  </div>

                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status</label>
                    <select class="form-control" id="edit_collectionsStatus" name="edit_collections_status" required>
                     <option>Active</option>
                     <option>Inactive</option>
                    </select>  
                  </div>
                  </div>

               
             
                 

                  <div>
                  <input type="hidden" name="edit_collections_id" id="edit_collectionsID" value="">  
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
            "url": "<?php echo base_url('admin/collections-list'); ?>",
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

    
    
$('#Savedcollections').submit(function(event){
    event.preventDefault(); // Prevent the default form submission
    
    var formData = new FormData($(this)[0]);
    $(":submit").attr("disabled", true);
    
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/collections_save'); ?>", // Make sure URL is correctly enclosed in quotes
        processData: false,
        contentType: false,
        dataType: "JSON",
        data: formData,
        success: function(data){
            $('#addcollectionsclose').click();
            $("#Savedcollections")[0].reset();
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


	$('#Editedcollections').submit('click',function(){
  var formData = new FormData($(this)[0]);
  $(":submit").attr("disabled", true);
  
		$.ajax({
			type : "POST",
			url  : "<?php echo base_url('admin/collections_update'); ?>",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
			$('#editcollections').modal('hide');
				
				$("#Editedcollections")[0].reset();
				 $(":submit").attr("disabled", false);
			 table.ajax.reload();
			}
		});
		return false;
	});

	
	$('#ajax_table').on('click','.editRecordcollections',function(){
	   
	    
		$('#editcollections').modal('show');
		$("#edit_collectionsID").val($(this).data('collections_id'));
	

		$("#edit_collectionsName").val($(this).data('collections_name'));
		$("#edit_collectionsStatus").val($(this).data('collections_status'));
  
  
    
	});
	


 

  


	$('#Recordcollections').on('click',function(){
	   

    $('#addcollections').modal('show');
	});




    
});
</script>
 