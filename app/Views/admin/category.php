
	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All City </h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All City > <?php echo $table_name;?>
							</p>
						</div>

						<div>
						
							<a href="javascript:void(0);" class="btn btn-primary" id="RecordCategory" data-toggle="modal" >Add City</a>
							    <?php if(!empty($back)) { ?>
						    	<a href="<?php echo base_url('admin/category/'.$back); ?>" class="btn btn-secondary" > Back</a>
						    	
						    	<?php } else if($id > 0) {
						    	?>
						    		<a href="<?php echo base_url('admin/category'); ?>" class="btn btn-secondary" > Back</a>
						    	<?php } ?>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 col-lg-12">
							<div class="ec-cat-list card card-default">
								<div class="card-body">
									<div class="table-responsive">
									    <input type="hidden" id="id" value="<?php echo $id; ?>" >
										<table  id="ajax_table" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>ID</th>
													<th>Photo</th>
													<th>City / Service</th>
											
												
													<th>Status</th>
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
<div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Menu New Category</h5>
        <button type="button" class="close" id="addCategoryclose"  data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="SavedCategory" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">

                    <div class="col-md-12" > 
               <div class="form-group">
                  <label for="exampleInputEmail1">select Parent category</label>
                  <div id="category">
                      
                  </div>
                  </div>
                  </div>

                  <div class="col-md-8">  
                  <div class="form-group">
                  <label for="exampleInputEmail1"> category name*</label>
                  <input type="text" class="form-control" name="category_name" required>
                  </div>
                  </div>

                  <div class="col-md-4"> 
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" name="category_status" required>
                     <option>Active</option>
                     <option>Inactive</option>
                    </select>  
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Menu category image*</label>
                    <input type="file" class="form-control" name="category_image" required>
                  </div>
                  </div>
                

                <div class="col-md-6"> 
                  <div class="form-group">
                 <label for="exampleInputPassword1">Home Page Show / Hide*</label>
                    <select class="form-control" name="category_order" required>
                     <option value="1">Show</option>
                     <option value="2">Hide</option>
                    </select>  
                  </div>
                  </div>
                  
                  
                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Description</label>
                  <input type="text" class="form-control" name="description">
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Meta Title</label>
                  <input type="text" class="form-control" name="category_title">
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Meta Keyword</label>
                  <input type="text" class="form-control" name="category_keyword">
                  </div>
                  </div>
                  
                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Meta Description</label>
                  <input type="text" class="form-control" name="category_description">
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
<div class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Menu Category</h5>
        <button type="button" class="close" id="editCategoryclose" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="EditedCategory" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">
<div class="col-md-12" > 
               <div class="form-group">
                  <label for="exampleInputEmail1">select Parent category</label>
                  <div id="updatecategory">
                      
                  </div>
                  </div>
                  </div>
                  <div class="col-md-8">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Category Name*</label>
                    <input type="text" class="form-control" name="edit_category_name" id="edit_categoryName" required>
                  </div>
                  </div>

                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status</label>
                    <select class="form-control" id="edit_categoryStatus" name="edit_category_status" required>
                     <option>Active</option>
                     <option>Inactive</option>
                    </select>  
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Menu Category Image</label>
                    <input type="file" class="form-control" name="edit_category_image">
                    <input type="hidden" class="form-control" name="edit_category_image_old" id="edit_categoryImage">
                  </div>
                  </div>
            
                  
                  <div class="col-md-6"> 
                  <div class="form-group">
                    <label for="exampleInputPassword1">Home Page Show / Hide*</label>
                    <select class="form-control" name="edit_category_order" id="edit_categoryOrder" required>
                     <option value="1">Show</option>
                     <option value="2">Hide</option>
                    </select>  
                  </div>
                  </div>
                  
                    <div class="col-md-12">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Description</label>
                  <input type="text" class="form-control" name="edit_description" id="edit_description">
                  </div>
                  </div>

                  
                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Meta Title</label>
                  <input type="text" class="form-control" name="edit_category_title" id="edit_categoryTitle">
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Meta Keyword</label>
                  <input type="text" class="form-control" name="edit_category_keyword" id="edit_categoryKeyword">
                  </div>
                  </div>
                  
                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Meta Description</label>
                  <input type="text" class="form-control" name="edit_category_description" id="edit_categoryDescription">
                  </div>
                  </div>

                  <div>
                  <input type="hidden" name="edit_category_id" id="edit_categoryID" value="">  
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
            "url": "<?php echo base_url('admin/category-list'); ?>",
            "type": "POST",
            "data": function(data) {
                // Add custom parameters to the AJAX request data
                var fromDate =  '';
                data.searchname = fromDate;
                var id =  	$("#id").val();;
                data.id = id;
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

    
    
$('#SavedCategory').submit(function(event){
    event.preventDefault(); // Prevent the default form submission
    
    var formData = new FormData($(this)[0]);
    $(":submit").attr("disabled", true);
    
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/category_save'); ?>", // Make sure URL is correctly enclosed in quotes
        processData: false,
        contentType: false,
        dataType: "JSON",
        data: formData,
        success: function(data){
           	$('#addCategory').modal('hide');
            $("#SavedCategory")[0].reset();
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


	$('#EditedCategory').submit('click',function(){
  var formData = new FormData($(this)[0]);
  $(":submit").attr("disabled", true);
  
		$.ajax({
			type : "POST",
			url  : "<?php echo base_url('admin/category_update'); ?>",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
			$('#editCategory').modal('hide');
				
				$("#EditedCategory")[0].reset();
				 $(":submit").attr("disabled", false);
			 table.ajax.reload();
			}
		});
		return false;
	});

	
	$('#ajax_table').on('click','.editRecordCategory',function(){
	   
	   getcat(0, $(this).data('parent_id'), 'updatecategory');
	   
		$('#editCategory').modal('show');
		$("#edit_categoryID").val($(this).data('category_id'));
	    $("#edit_description").val($(this).data('description'));
			$("#edit_categoryOrder").val($(this).data('menu_order'));
		$("#edit_categoryName").val($(this).data('category_name'));
		$("#edit_categoryStatus").val($(this).data('category_status'));
    $("#edit_categoryImage").val($(this).data('category_image'));
    $("#edit_categoryTitle").val($(this).data('category_title'));
    $("#edit_categoryKeyword").val($(this).data('category_keyword'));
    $("#edit_categoryDescription").val($(this).data('category_description'));
    
    
	});
	


  $('#ajax_table').on('click','.activeInactive',function(){
	
   
    
  var id = $(this).data('update_id');

   $.ajax({
         type:'POST',
         url:'<?php echo base_url('admin/travel_group_activeInactive'); ?>',
         dataType : "JSON",
        data: {
           id: id
           
       },
         success:function(data){
      
     table.ajax.reload();
      showAlert(data.class,data.title,data.message);
         }
     });

});

$('#ajax_table').on('click','.checkbox',function(){
	
   
    
    var id = $(this).val();
    var type = $(this).data('checkbox_type');
  
     $.ajax({
           type:'POST',
           url:'<?php echo base_url('admin/travel_checkbox'); ?>',
           dataType : "JSON",
          data: {
             id: id, type: type
             
         },
           success:function(data){
        
       table.ajax.reload();
        showAlert(data.class,data.title,data.message);
           }
       });
  
  });
  
  
  
function getcat(id, select, htmlid){
   
     $('#' + htmlid).html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('getcategory'); ?>',
        data: {
            id: id,
            select: select
        },
        success: function(data){
            if (data != '') {
                $('#' + htmlid).html(data); 
            }
        }
    });
}

	$('#RecordCategory').on('click',function(){
	   
getcat(0, <?php echo $id; ?>, 'category');
    $('#addCategory').modal('show');
	});




    
});
</script>
 