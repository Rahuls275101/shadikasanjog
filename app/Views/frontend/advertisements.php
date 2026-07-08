
    <section>
        <div class="all-pro-head">
            <div class="container">
                <div class="row">
                    <h1>Advertisers (Services-Vendors)</h1>
                    <!-- <a href="sign-up.html">Join now for Free <i class="fa fa-handshake-o" aria-hidden="true"></i></a> -->
                </div>
            </div>
        </div>

    </section>



    <!-- REGISTER -->
    <section style="padding: 40px 0px;">
        <div class="login pg-cont">
            <div class="container">
                <div class="row justify-content-center">

                    <div class="col-md-8 shadow-sm py-3">
                        <div class="form-tit">

                            <h1>Get Listed With Us</h1>
                        </div>
                        <div class="form-login">
                            <form class="cform fvali" method="post" id="contactForms">
                                   <input type="hidden" id="lable" name="lable" value="Advertisers">
                                <div class="alert alert-success cmessage" style="display: none" role="alert">
                                    Your message was sent successfully.
                                </div>
                                <div class="form-group">
                                    <label class="lb">Name:</label>
                                    <input type="text" id="name" class="form-control"
                                        placeholder="Enter your full name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label class="lb">Email:</label>
                                    <input type="email" class="form-control" id="email"
                                        placeholder="Enter email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label class="lb">Phone:</label>
                                    <input type="number" class="form-control" id="phone"
                                        placeholder="Enter phone number" name="phone" required>
                                </div>
                                <div class="form-group">
                                    <label class="lb">Message:</label>
                                    <textarea name="message" class="form-control" id="message"
                                        placeholder="Enter message" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- END -->

   <script src="<?php echo base_url('assets/frontend/'); ?>/assets/js/jquery.min.js"></script>
 <script>
  $(document).ready(function(){
      $('#contactForms').on('submit', function(e){
          e.preventDefault();

          var formData = $(this).serialize(); // all form fields auto-collected

          $.ajax({
              url: "<?= base_url('contact/send') ?>", // controller function
              type: "POST",
              data: formData,
              dataType: "json",
              success: function(response){
                  if(response.status == 'success'){
                      Swal.fire({
                          icon: 'success',
                          title: 'Success!',
                          text: 'Your message has been sent successfully.',
                          confirmButtonText: 'OK'
                      });
                      $('#contactForms')[0].reset();
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'Error!',
                          text: response.message,
                          confirmButtonText: 'OK'
                      });
                  }
              },
              error: function(){
                  Swal.fire({
                      icon: 'error',
                      title: 'Oops!',
                      text: 'Something went wrong. Please try again.',
                      confirmButtonText: 'OK'
                  });
              }
          });
      });
  });
  </script>