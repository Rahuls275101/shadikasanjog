
	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All meta </h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All meta 
							</p>
						</div>

						<div>
						
						<!--	<a href="javascript:void(0);" class="btn btn-primary" id="Recordmeta" data-toggle="modal" >Add meta</a>-->
							   
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
													<th>Page</th>
													<th>Photo</th>
													
													<th>Meta Title</th>
											
												
												
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
<div class="modal fade" id="addmeta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create New meta</h5>
        <button type="button" class="close" id="addmetaclose"  data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="Savedmeta" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">

                   

                  <div class="col-md-8">  
                  <div class="form-group">
                  <label for="exampleInputEmail1"> meta name*</label>
                  <input type="text" class="form-control" name="meta_name" required>
                  </div>
                  </div>

                  <div class="col-md-4"> 
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" name="meta_status" required>
                     <option>Active</option>
                     <option>Inactive</option>
                    </select>  
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Menu meta image*</label>
                    <input type="file" class="form-control" name="meta_image" required>
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
<div class="modal fade" id="editmeta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update  meta</h5>
        <button type="button" class="close" id="editmetaclose" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="Editedmeta" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Meta Title*</label>
                    <input type="text" class="form-control" name="meta_title" id="meta_title" required>
                  </div>
                  </div>


                    <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Meta Keyword*</label>
                    <input type="text" class="form-control" name="meta_keyword" id="meta_keyword" required>
                  </div>
                  </div>
                  
                  
                   <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Meta Description*</label>
                    <input type="text" class="form-control" name="meta_description" id="meta_description" required>
                  </div>
                  </div>
                

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Meta Image</label>
                    <input type="file" class="form-control" name="edit_meta_image">
                    <input type="hidden" class="form-control" name="edit_meta_image_old" id="edit_metaImage">
                  </div>
                  </div>
            
             
                 

                  <div>
                  <input type="hidden" name="edit_meta_id" id="edit_metaID" value="">  
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
            "url": "<?php echo base_url('admin/meta-list'); ?>",
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

    
    
$('#Savedmeta').submit(function(event){
    event.preventDefault(); // Prevent the default form submission
    
    var formData = new FormData($(this)[0]);
    $(":submit").attr("disabled", true);
    
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/meta_save'); ?>", // Make sure URL is correctly enclosed in quotes
        processData: false,
        contentType: false,
        dataType: "JSON",
        data: formData,
        success: function(data){
            $('#addmetaclose').click();
            $("#Savedmeta")[0].reset();
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


	$('#Editedmeta').submit('click',function(){
  var formData = new FormData($(this)[0]);
  $(":submit").attr("disabled", true);
  
		$.ajax({
			type : "POST",
			url  : "<?php echo base_url('admin/meta_update'); ?>",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
			$('#editmeta').modal('hide');
				
				$("#Editedmeta")[0].reset();
				 $(":submit").attr("disabled", false);
			 table.ajax.reload();
			}
		});
		return false;
	});

	
	$('#ajax_table').on('click','.editRecordmeta',function(){
	   
	    
		$('#editmeta').modal('show');
		$("#edit_metaID").val($(this).data('meta_id'));
	

		$("#meta_title").val($(this).data('meta_title'));
		$("#meta_keyword").val($(this).data('meta_keyword'));
    $("#meta_description").val($(this).data('meta_description'));
    $("#edit_metaImage").val($(this).data('meta_image'));
  
    
	});
	


 

	$('#Recordmeta').on('click',function(){
	   

   	$this.modal('hide');
	});
  


	$('#Recordmeta').on('click',function(){
	   

    $('#addmeta').modal('show');
	});




    
});
</script>
 