
<style>
 .copy-btn {
    position: relative;
  
    border-radius: 5px;
    cursor: pointer;
   
}

/* Hover effect for the button */
.copy-btn:hover {
    background-color: #0056b3;
}

/* Tooltip-style message that appears on "active" state */
.copy-btn.active::before {
    content: "Copied!";
    position: absolute;
    top: -45px; /* Adjust to position above the button */
    right: 0px; /* Adjust to align with the button */
    background: #5c81dc;
    color: white;
    padding: 8px 10px;
    border-radius: 20px;
    font-size: 15px;
    display: block; /* Show when active */
}
</style>
	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All Files </h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Files 
							</p>
						</div>

						<div>
						
					
						<a href="javascript:void(0);" class="btn btn-primary" id="files">Add Files</a>

<!-- File Input for Image Upload -->
<input type="file" id="imageInput" style="display: none;" accept="image/*">

<!-- Add an Image Preview Area -->
<img id="imagePreview" style="display: none; max-width: 100px; max-height: 100px;" />	   
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 col-lg-12">
							<div class="ec-cat-list card card-default">
								<div class="card-body">
								    <button id="deleteSelected" type="button" class="btn btn-danger btn-sm" style="margin-bottom: 10px;">Delete Files</button>
									<div class="table-responsive">
									   
										<table  id="ajax_table" class="table table-bordered table-striped">
											<thead>
												<tr>
												     <th><input type="checkbox" id="selectAll"></th>
													<th>ID</th>
													<th>Images</th>
													<th>Files name</th>
											        <th>Date added</th>
												    <th>Size</th>
												
													<th>References</th>
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
        "url": "<?php echo base_url('admin/files-list'); ?>",
        "type": "POST",
        "data": function(data) {
            // Add any custom data if needed
            data.searchname = '';
            data.id = '';
        }
    },
    "columns": <?= $table_column ?>, // Dynamically add columns (adjust to your needs)
    "pagingType": "simple_numbers_no_ellipses",
    "language": {
        "processing": '<div class="custom-spinner"></div>',
        "oPaginate": {
            "sFirst": "First",
            "sPrevious": "Previous",
            "sNext": "Next"
        }
    },
    "select": {
        style: 'multi', // Enable multi-row selection
        selector: 'td:first-child' // Allow row selection via first column checkbox
    },
    "initComplete": function () {
        // Custom DataTable styling
        $('#ajax_table').addClass('custom-datatable');
    }
});

// "Select All" functionality for the header checkbox
$('#selectAll').on('click', function() {
    var isChecked = $(this).prop('checked');
    $('#ajax_table tbody input[type="checkbox"]').prop('checked', isChecked);
});



$(document).ready(function() {
    // Trigger for delete button
    $('#deleteSelected').on('click', function() {
        var selectedFiles = []; // Array to store selected file paths

        // Loop through all the checkboxes with class 'selectRow' and check if they are checked
        $('.selectRow:checked').each(function() {
            selectedFiles.push($(this).val()); // Get the value (file path) of the checked checkbox
        });

        // If no files are selected, show an alert
        if (selectedFiles.length === 0) {
            alert('Please select at least one file to delete.');
            return;
        }

        // Send AJAX request to the server to delete selected files
        $.ajax({
            url: "<?php echo base_url('admin/delete-files'); ?>",
            method: "POST",
            data: { ids: selectedFiles }, // Sending selected file paths as ids
            success: function(response) {
                var res = JSON.parse(response);
                alert(res.message); // Show response message
                if (res.status == 'success') {
                    table.ajax.reload(); // Reload the DataTable to reflect changes
                }
            },
            error: function() {
                alert('An error occurred while deleting the files.');
            }
        });
    });
});







    $('#searchname').on('keyup', function() {
        table.column(0).search(this.value).draw();
    });
    

    $('#search').on('click', function() {
       
      table.column(0).search(this.value).draw();
    });

    
   // Trigger file input when "Add Files" button is clicked
    $('#files').on('click', function() {
        $('#imageInput').click(); // Trigger file input click
    });

    // Preview the image when the user selects a file
    $('#imageInput').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result).show(); // Show the image preview
            }
            reader.readAsDataURL(file);
        }
    });

    // Handle the image upload to the server
    $('#imageInput').on('change', function() {
        var formData = new FormData();
        var file = $('#imageInput')[0].files[0]; // Get the selected file

        if (file) {
            formData.append('file', file); // Append the file to FormData

            // Perform the AJAX upload
            $.ajax({
                url: "<?php echo base_url('admin/upload-image'); ?>", // Your server-side upload URL
                type: "POST",
                data: formData,
                processData: false, // Don't process data
                contentType: false, // Don't set content type
                success: function(response) {
                    
                     table.ajax.reload();
                      $('#imagePreview').attr('src', '').show(); 
                    // Handle response from server
                    // Optionally reload the table or take any other action
                },
                error: function() {
                    alert('Error uploading the image.');
                }
            });
        } else {
            alert('Please select an image to upload.');
        }
    });


$('#ajax_table').on('click', '.copy-btn', function () {
    const link = $(this).data('link'); // Get the data-link attribute value

    navigator.clipboard.writeText(link)
        .then(() => {
            // Add the "active" class to the button
            $(this).addClass('active');

            // Remove the "active" class after 2 seconds
            setTimeout(() => {
                $(this).removeClass('active');
            }, 2000);
        })
        .catch(() => {
            alert('Failed to copy the link.');
        });
});


    
});
</script>
 