
	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All Coupon </h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Coupon 
							</p>
						</div>

						<div>
						
							<a href="javascript:void(0);" class="btn btn-primary" id="Recordcoupon" data-toggle="modal " >Add Coupon</a>
							   
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
                  <th style="text-align: center;">coupon</th>
                  <th style="text-align: center;">Discount(%)</th>
                  <th style="text-align: center;">Start Date</th>
                  <th style="text-align: center;">End Date</th>
                
                  <th style="text-align: center;">Action</th>
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
<div class="modal fade" id="addcoupon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create New coupon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="Savedcoupon" method="POST">
                <div class="card-body">
                <div class=" row">
                    
                     <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Coupon Title*</label>
                    <input type="text" required class="form-control" name="coupon_title" >
                  </div>
                  </div>

                  <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Coupon Code*</label>
                    <input type="text" required class="form-control" name="coupon_code" id="coupan_code">
                  </div>
                  </div>

                  <div class="col-md-3">
                  <div class="form-group" style="margin-top: 32px;">
                    <a href="javascript:void(0);" id="getpromocode" class="btn btn-primary">Generate Coupan</a>  
                  </div>
                  </div>

                  <div class="col-md-3">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Start Date*</label>
                  <input type="date" class="form-control" name="coupon_start_date" required>
                  </div>
                  </div>

                  <div class="col-md-3">
                  <div class="form-group">
                  <label for="exampleInputEmail1">End Date*</label>
                  <input type="date" class="form-control" name="coupon_end_date" required>
                  </div>
                  </div>

                  <div class="col-md-3">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Discount Type*</label>
                  <select type="number" class="form-control" name="coupon_type" required>
                  <option value="">Choose</option>
                  <option value="1">Percent(%)</option>
                  <option value="2">Amount(Rs)</option>
                  </select>
                  </div>
                  </div>

                  <div class="col-md-3">
                  <div class="form-group">
                  <label for="exampleInputEmail1">Value*</label>
                  <input type="number" class="form-control" name="coupon_value" required>
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputFile">Primary Image*</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file"  class="form-control" id="exampleInputFile" required name="coupon_primary_image">
                      </div>
                    </div>
                  </div>
                  </div>

                  <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" required name="coupon_status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    </select> 
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Quick Overview*</label>
                    <div class="mb-3">
                    <textarea class="textarea" placeholder="Enter the product description..." style="width: 100%; height: 300px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="coupon_quick_overview"></textarea>
                    </div>
                  </div>
                  </div>
                </div>

                  

                  
                

                 
                  <div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </div>
                <!-- /.card-body -->

              </form>
      </div>
    </div>
  </div>
</div>


 <!-- Modal -->
<div class="modal fade" id="editcoupon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit coupon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form role="form" id="Editedcoupon">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">coupon Code*</label>
                    <input type="text" required class="form-control" name="edit_couponCode" id="edit_couponCode">
                  </div>
                  
                  
                  <div class="form-group">
                    <label for="exampleInputFile">Primary Image*</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file"  class="form-control" id="exampleInputFile" name="edit_coupon_primary_image">
                        <input type="hidden" name="edit_couponimages" id="edit_couponimages">
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" id="edit_couponStatus" name="edit_couponStatus">
                     <option>Active</option>
                     <option>Inactive</option>
                    </select>  
                  </div>
                  <div>
                  <input type="hidden" name="edit_couponID" id="edit_couponID" value="">  
                  <button type="submit" class="btn btn-primary">Updated</button>
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
            "url": "<?php echo base_url('admin/coupon-list'); ?>",
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

    
    
$('#Savedcoupon').submit(function(event){
    event.preventDefault(); // Prevent the default form submission
    
    var formData = new FormData($(this)[0]);
    $(":submit").attr("disabled", true);
    
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/coupon_save'); ?>", // Make sure URL is correctly enclosed in quotes
        processData: false,
        contentType: false,
        dataType: "JSON",
        data: formData,
        success: function(data){
          
            	$('#addcoupon').modal('hide');
            $("#Savedcoupon")[0].reset();
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


	$('#Editedcoupon').submit('click',function(){
  var formData = new FormData($(this)[0]);
  $(":submit").attr("disabled", true);
  
		$.ajax({
			type : "POST",
			url  : "<?php echo base_url('admin/coupon_update'); ?>",
      processData: false,
      contentType: false,
			dataType : "JSON",
			data : formData,
			success: function(data){
			$('#editcoupon').modal('hide');
				
				$("#Editedcoupon")[0].reset();
				 $(":submit").attr("disabled", false);
			 table.ajax.reload();
			}
		});
		return false;
	});

	
	$('#ajax_table').on('click','.editRecordcoupon',function(){
	   
	    
		$('#editcoupon').modal('show');
		$("#edit_couponID").val($(this).data('coupon_id'));
	

		$("#edit_couponCode").val($(this).data('coupon_code'));
		$("#edit_couponStatus").val($(this).data('coupon_status'));
    $("#edit_couponimages").val($(this).data('coupon_primary_image'));
  
    
	});
	


 

  


	$('#Recordcoupon').on('click',function(){
	   

    $('#addcoupon').modal('show');
	});

function generateCouponCode(type, length) {
    if (type === 'coupon_action') {
        const useLetters = true;
        const useNumbers = true;
        const useSymbols = false;
        const useMixedCase = false;

        const uppercase = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'];
        const lowercase = ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm'];
        const numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        const symbols = ['`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '\\', '|', '/', '[', ']', '{', '}', '"', "'", ';', ':', '<', '>', ',', '.', '?'];

        let characters = [];
        let coupon = '';

        // Include letters
        if (useLetters) {
            if (useMixedCase) {
                characters = characters.concat(lowercase, uppercase);
            } else {
                characters = characters.concat(uppercase);
            }
        }

        // Include numbers
        if (useNumbers) {
            characters = characters.concat(numbers);
        }

        // Include symbols
        if (useSymbols) {
            characters = characters.concat(symbols);
        }

        // Generate the coupon code
        for (let i = 0; i < length; i++) {
            coupon += characters[Math.floor(Math.random() * characters.length)];
        }

        // Output the coupon code
        return coupon;
    }
}


	$('#getpromocode').on('click',function(){
	   
let couponCode = generateCouponCode('coupon_action', 10);

$("#coupan_code").val(couponCode);
   
	});
	
	
// Example Usage:

console.log(couponCode);



    
});
</script>
 
