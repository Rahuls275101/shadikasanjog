

	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All Vendors </h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Vendors > 
							</p>
						</div>

						<div>
		
							<a href="<?php echo base_url('admin/create_product'); ?>" class="btn btn-primary" >Add Vendors</a>
							   
						</div>
					</div>
					<div class="row">
				   <div class="col-12">
          <!-- /.card -->
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
            <!-- /.card-header -->
            <div class="card-body">
    <form id="news-filter">
    <div class="row">


    <div class="col-md-6">
    <div class="form-group">
    <label>Search with name</label>
    <input type="text" class="form-control" id="search_date">
      <input type="hidden" class="form-control" id="id" value="<?php echo $id; ?>">
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
    <button type="button" id="btn-filter-userlist" style="margin-right: 10px" class="btn btn-success search">Search</button>
    <button type="button" id="btn-reset-userlist" class="btn btn-danger reset">Reset Search</button>
    </div>
    </div>

    </div>
    </form>                            


            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>




        <div class="col-12">
          <!-- /.card -->
          <div class="card">
            <!-- /.card-header -->
            	<div class="ec-cat-list card card-default">
            <div class="card-body">
                	<div class="table-responsive">
              <table id="ajax_table" class="table table-bordered table-striped" >
                <thead>
                <tr>
                  <th>#S.No</th>
                <th>Images</th>
                  <th>Services</th>
                  <th>Category</th>
                  <th>Action</th>
                  
                </tr>
                </thead>
                <tbody>               
                </tbody>
              </table>
              </div>
            </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
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
            "url": "<?php echo base_url('admin/productlist'); ?>",
            "type": "POST",
            "data": function(data) {
                // Add custom parameters to the AJAX request data
                var fromDate =  $('#search_date').val();
                data.searchname = fromDate;
                var id =  $('#id').val();
                data.id = id;
               
                var status =  $('#status').val();
                data.status = status;
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
    

  
    $('.search').on('click', function() {
       
      table.column(0).search(this.value).draw();
    });

   $('.reset').on('click', function() {
      $('#search_date').val(''); 
       $('#status').val('');
      table.column(0).search(this.value).draw();
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
    
});
</script>
 