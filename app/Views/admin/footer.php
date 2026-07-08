

			<!-- Footer -->
			<footer class="footer mt-auto">
				<div class="copyright bg-white">
					<p>
						Copyright © 2023. All right reserved.
					</p>
				</div>
			</footer>

		</div> <!-- End Page Wrapper -->
	</div> <!-- End Wrapper -->


<script src="<?php echo base_url('assets/admin/'); ?>/plugins/select2/js/select2.full.min.js"></script>
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/simplebar/simplebar.min.js"></script>
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/jquery-zoom/jquery.zoom.min.js"></script>
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/slick/slick.min.js"></script>

	<!-- Chart -->
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/charts/Chart.min.js"></script>
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/js/chart.js"></script>

	<!-- Google map chart -->
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/charts/google-map-loader.js"></script>
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/charts/google-map.js"></script>
	
	

	<!-- Date Range Picker -->
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/js/date-range.js"></script>
	<!-- custom js -->
	<script src="<?php echo base_url('assets/admin/'); ?>/assets/js/custom.js"></script>






<script>
$(document).ready(function() {
    // Close modal when 'close' button (in header or footer) is clicked
    $('.close').on('click', function() {
        // Automatically hide the modal when close button is clicked
        $(this).closest('.modal').modal('hide');
    });

  
});
      function GetRegisterCity(stateId)
  {
      if(stateId >= 1)
      {
      $.ajax({
          type:'POST',
          url:'<?php echo base_url('getCity'); ?>',
          data:'state_id='+stateId,
          success:function(data){
              $('.cityList').html('<option value="">Select City</option>'); 
              var dataObj = jQuery.parseJSON(data);
              //console.log(data);
              if(dataObj){
                  $(dataObj).each(function(){
                      var option = $('<option />');
                      option.attr('value', this.city_id).text(this.city_name);
                      $('.cityList').append(option);
                  });
              }else{
                  $('.cityList').html('<option value="">City not available</option>');
              }
          }
      });
      }
      else
      {
         $('.cityList').html('<option value="">City</option>'); 
      }


  }
</script>





<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script>
    'use strict';
$(function () {
    //CKEditor
    CKEDITOR.replace('ckeditor');
    CKEDITOR.config.height = 300;

});
</script>

<script>
$(".datepicker").datepicker( {
    format: "mm-yyyy",
    startView: "months", 
    minViewMode: "months"
});




  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    })

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

  })
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template")
  previewNode.id = ""
  var previewTemplate = previewNode.parentNode.innerHTML
  previewNode.parentNode.removeChild(previewNode)

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  })

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
  })

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  })

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1"
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
  })

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  })

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  }
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
  }
  // DropzoneJS Demo Code End
  
  
  $('#select-id').select2('destroy').find('option').prop('selected', 'selected').end().select2();
</script>
<script>
$(document).ready(function() {
    $("#checkboxfacilities").click(function(){
      if($("#checkboxfacilities").is(':checked') ){ //select all
        $("#facilities").find('option').prop("selected",true);
        $("#facilities").trigger('change');
      } else { //deselect all
        $("#facilities").find('option').prop("selected",false);
        $("#facilities").trigger('change');
      }
  });
});
$(document).ready(function() {
    $("#checkboxsafety").click(function(){
      if($("#checkboxsafety").is(':checked') ){ //select all
        $("#safety").find('option').prop("selected",true);
        $("#safety").trigger('change');
      } else { //deselect all
        $("#safety").find('option').prop("selected",false);
        $("#safety").trigger('change');
      }
  });
});

$(document).ready(function() {
    $("#checkboxinclued").click(function(){
      if($("#checkboxinclued").is(':checked') ){ //select all
        $("#inclued").find('option').prop("selected",true);
        $("#inclued").trigger('change');
      } else { //deselect all
        $("#inclued").find('option').prop("selected",false);
        $("#inclued").trigger('change');
      }
  });
});

$(document).ready(function() {
    $("#checkbox").click(function(){
      if($("#checkbox").is(':checked') ){ //select all
        $("#e1").find('option').prop("selected",true);
        $("#e1").trigger('change');
      } else { //deselect all
        $("#e1").find('option').prop("selected",false);
        $("#e1").trigger('change');
      }
  });
});



function GetRegisterState(countryId)
  {
      if(countryId >= 1)
      {
      $.ajax({
          type:'POST',
          url:'<?php echo base_url('Ajax/getState'); ?>',
          data:'country_id='+countryId,
          success:function(data){
              $('.stateList').html('<option value="">Select State</option>'); 
              var dataObj = jQuery.parseJSON(data);
              //console.log(data);
              if(dataObj){
                  $(dataObj).each(function(){
                      var option = $('<option />');
                      option.attr('value', this.id).text(this.name);
                      $('.stateList').append(option);
                  });
              }else{
                  $('.stateList').html('<option value="">State not available</option>');
              }
          }
      });
      }
      else
      {
         $('.stateList').html('<option value="">State</option>'); 
      }


    



  }

</script>



<script>
  $(function () {
    $("#course_category").DataTable();
  });
  $(function () {
    $("#main_category").DataTable();
  });
    $(function () {
    $("#sub_category").DataTable();
  });
  $(function () {
    $("#blog_list").DataTable();
  });
  $(function () {
    $("#course_coupan").DataTable();
  });
    $(function () {
$('#brand').DataTable( {
        "order": [[ 1, "desc" ]]
    } );
  });
        $(function () {
    $("#production_category").DataTable();
  });
</script>




</body>
</html>