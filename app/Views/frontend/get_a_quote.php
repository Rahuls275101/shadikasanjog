<div class="menu-overlay"></div>
<div class=" pt-1201">
  <div class="container ">
    <div class="row">
    
      <div class="col-md-6 sect1">
        <div class="corp-block-left" style="width:100%">
          <h1 style="font-weight: 500; margin-bottom: 19px; letter-spacing: -1px; font-size: 38px;">Custom Quote </h1>
            <div class="corporate-orders-hint">If you need a metal card with special effects that are not listed in our online catalogue, you will
need to request a custom quote. Fill out the form on the side of this page and submit. One of our
expert team members will get back to you shortly on your specific requirements and will offer
you options to choose from. Even if you don’t know all the details for your project at this stage,
just submit everything you can, and our team will contact you and guide you through the rest of
the information required. Our talented, professional team is here to guide you through your
project from start to finish. No job is too big or small for our team.</div>
          
          </div>


        

      </div>
   

    <div class="col-md-6">
        <div class="corp-block-left" style=" width: 100%; background: #e6e6e6; padding: 30px;     border-radius: 10px;">
             <form  accept-charset="UTF-8" id="corporate-orders-form" >
         
       <div class="row">             
              <div class="col-md-6"><input required name="name" type="text" placeholder="Name" style=" font-size: 14px; padding: 13px;"></div>
              <div class="col-md-6"><input required name="email" type="text" placeholder="Email" style=" font-size: 14px; padding: 13px;"></div>
              <div class="col-md-6"><input required name="phone" type="text" placeholder="Phone" style=" font-size: 14px; padding: 13px;"></div>
              <div class="col-md-6"><input required name="date" type="date" placeholder="Date Required (MM-DD-YYYY)" style=" font-size: 14px; padding: 13px;"></div>
              <div class="col-md-12"><textarea name="description" cols="50" rows="5" placeholder="Describe your project to us. (Size of product, colours, indoor/outdoor etc)"style=" font-size: 14px; padding: 13px; margin-bottom: 13px; min-height: 145px; width: 100%;"></textarea></div>
              <div class="col-md-12"><input name="file" type="file"style=" font-size: 14px; padding: 13px; background: #fff;">
              <p style="font-size:12px">Accepted file types: ai, jpg, psd, pdf, png, tif, tiff, eps, cdr, Max. file size: 100 MB, Max. files: 3.</p></div>

</div>







              
              


       
             
              <button type="submit" class="get-quatotion-but">REQUEST QUOTE</button>
            </form>
          </div>
      </div>
  </div> </div>
</div>
      <script>
$(document).ready(function(){
    $("#corporate-orders-form").on("submit", function(e){
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: "<?= base_url('quote') ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response){
                if(response.status === "success"){
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message
                    });
                    $("#quoteFome")[0].reset();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message
                    });
                }
            },
            error: function(){
                Swal.fire("Error", "Something went wrong!", "error");
            }
        });
    });
});
</script>