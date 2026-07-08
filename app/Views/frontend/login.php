

   <!-- LOGIN -->
    <section>
        <div class="login">
            <div class="container">
                <div class="row">

                    <div class="inn">
                        <div class="lhs">
                            <div class="tit">
                                <h2>‘It’s <b>never been easier to find your</b>perfect life partner’</h2>
                            </div>
                            <div class="im">
                                <img src="<?php echo base_url('assets/frontend/'); ?>/assets/images/login-couple.png" alt="">
                            </div>
                            <div class="log-bg">&nbsp;</div>
                        </div>
                        <div class="rhs">
                            <div>
                                <div class="form-tit">
                                    <h4>Start for free</h4>
                                    <h1>Sign in to Matrimony</h1>
                                    <p>Not a member? <a href="<?php echo base_url('register'); ?>">Sign up now</a></p>
                                                            <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message'); ?>
    </div>
<?php endif; ?>
 
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>
                                </div>
                                <div class="form-login">
                                      <form id="user_login" class="login">
                                        <div class="form-inner mb-3">
                                             <label class="lb">Email:</label>
                                            <input type="text" class="form-control"
                                                placeholder="Email Address" name="login_email" required>
                                            <p id="login_email_error" class="errors text-danger"></p>
                                        </div>
                                        <div class="form-inner mb-3 position-relative">
                                             <label class="lb">Password:</label>
                                            <input id="login_password" type="password" class="form-control"
                                                placeholder="Password" name="login_password" required>
                                            <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3 cursor-pointer toggle-password"
                                               data-target="login_password"></i>
                                            <p id="login_password_error" class="errors text-danger"></p>
                                        </div>
                                  
                                        <input type="hidden" name="user_login" value="Login">
                                        <div class="form-group form-check justify-content-between d-flex">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="agree"> Remember
                                                me
                                            </label>
                                            <label class="form-check-label">
                                              <a href="<?= base_url('auth/forgotPassword'); ?>">Forgot Password</a>
                                            </label>
                                        </div>
                                     
                                        <button type="submit" class="btn btn-primary">Sign in</button>
                                    </form>
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- END -->






      
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
var BaseUrl = '<?php echo base_url(''); ?>/';

$(document).ready(function(){

    // Check login area
    login_area();

    // LOGIN AJAX
    $('#user_login').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: BaseUrl+'login_process',
            method:"POST",
            data: $(this).serialize(),
            dataType:"json",
            beforeSend: function(){
                $('#login_submit').attr('disabled', true);
            },
            success: function(data) {
                $('#login_submit').attr('disabled', false);

                if(data.error_user){
                    $('#login_submit').attr('disabled', false); 
                    if(data.phone_login_error != '') { $('#login_emailerror').html(data.login_email_error); } else { $('#login_email_error').html(''); } if(data.pin_error != '') { $('#login_password_error').html(data.login_password_error); } else { $('#login_password_error').html(''); } //$('#register_form').attr('disabled', false);
                }
                else if(data.success == false){
                    Swal.fire('Warning', data.message, 'error');
                }
                else if(data.success){
                    Swal.fire({
                        title: data.title,
                        text: data.message,
                        icon: data.class,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    $('#user_login')[0].reset();
                    login_area();
                }
            }
        });
    });

    // REGISTER AJAX
    $('#form_user_register').on('submit', function(event){
        event.preventDefault();

        $.ajax({
            url: BaseUrl+'register_process',
            method:"POST",
            data: $(this).serialize(),
            dataType:"json",
            beforeSend: function(){
                $('#register_submit').attr('disabled', true);
            },
            success: function(data){
                $('#register_submit').attr('disabled', false);

                if(data.error_user){
                    // Show all validation errors
                    if(data.name_register_error) Swal.fire('Error', data.name_register_error, 'error');
                    if(data.name_last_register_error) Swal.fire('Error', data.name_last_register_error, 'error');
                    if(data.gander_register_error) Swal.fire('Error', data.gander_register_error, 'error');
                    if(data.dob_register_error) Swal.fire('Error', data.dob_register_error, 'error');
                    if(data.phone_register_error) Swal.fire('Error', data.phone_register_error, 'error');
                    if(data.email_register_error) Swal.fire('Error', data.email_register_error, 'error');
                    if(data.password_error) Swal.fire('Error', data.password_error, 'error');
                    if(data.confirm_password_error) Swal.fire('Error', data.confirm_password_error, 'error');
                }
                else if(data.failed){
                    Swal.fire('Warning', data.failed, 'warning');
                }
                else if(data.success){
                    Swal.fire({
                        title: data.title,
                        text: data.message,
                        icon: data.class,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    $('#form_user_register')[0].reset();
                    login_area();
                }
            }
        });
    });

    // Fetch login area or redirect
    function login_area() {
        $.ajax({
            url: BaseUrl + 'chack-sing-in',
            method: "POST",
            dataType: "JSON",
            data: { action: 'fetch_data' },
            success: function(data) {
                $('#login_area').html(data.output);
                if(data.success){
                    window.location.href = data.url;
                }
                if(document.querySelector('#bookingButton')){
                    $('#bookingButton').html(data.bookingbutton);
                }
            }
        });
    }

});
</script>
