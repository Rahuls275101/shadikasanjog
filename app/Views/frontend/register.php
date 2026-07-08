<section>
    <div class="login">
        <div class="container">
            <div class="row">
                <div class="inn">
                    <div class="lhs">
                        <div class="tit">
                            <h2>‘It’s <b>Never been easier to find your</b> Perfect life partner’</h2>
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
                                <h1>Sign up to Shadi Ka Sanjog</h1>
                                <p>Already a member? <a href="<?php echo base_url('login'); ?>">Login</a></p>
                            </div>
                            <div class="form-login ">
                                <form id="contactForm">
                                    <input type="hidden" required autocomplete="off" name="new_register" value="Newregister" />
                                    <div class="row">
                                        <!-- Personal Details Section -->
                                        <div class="col-lg-12">
                                            <h5 class="section-title">Personal Details</h5>
                                        </div>
                                        
                                        <!-- First Name (Bride/Bridegroom) -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <i class='bx bx-user'></i>
                                                <input type="text" name="name_register" id="namGendere_register" class="form-control" required data-error="Please enter first name" placeholder="First Name (To be bride/ bride groom)">
                                                <div class="help-block">
                                                    <p id="user_name_error" class="errors"></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Last Name (Bride/Bridegroom) -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <i class='bx bx-user'></i>
                                                <input type="text" name="name_last_register" id="name_last_register" class="form-control"  data-error="Please enter last name" placeholder="Last Name (To be bride/ bride groom)">
                                                <div class="help-block">
                                                    <p id="user_last_name_error" class="errors"></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Email -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <i class='bx bx-envelope-open'></i>
                                                <input type="email" name="email_register" id="email_register" class="form-control" required data-error="Please enter email" placeholder="Email">
                                                <div class="help-block">
                                                    <p id="email_register_error" class="errors"></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Phone with Country Code Dropdown -->
                                        <div class="col-lg-12">
                                            <div class="form-group phone-group">
                                                <i class='bx bx-phone'></i>
                                                <div class="phone-input-wrapper">
                                                    <select name="country_code" id="country_code" class="country-code-select" required>
                                                        <option value="+91" selected>India (+91)</option>
                                                        <option value="+1">USA (+1)</option>
                                                        <option value="+44">UK (+44)</option>
                                                        <option value="+61">Australia (+61)</option>
                                                        <option value="+1">Canada (+1)</option>
                                                        <option value="+65">Singapore (+65)</option>
                                                        <option value="+971">UAE (+971)</option>
                                                        <option value="+966">Saudi Arabia (+966)</option>
                                                        <option value="+974">Qatar (+974)</option>
                                                        <option value="+968">Oman (+968)</option>
                                                        <option value="+973">Bahrain (+973)</option>
                                                        <option value="+965">Kuwait (+965)</option>
                                                        <option value="+92">Pakistan (+92)</option>
                                                        <option value="+94">Sri Lanka (+94)</option>
                                                        <option value="+880">Bangladesh (+880)</option>
                                                        <option value="+977">Nepal (+977)</option>
                                                        <option value="+60">Malaysia (+60)</option>
                                                        <option value="+64">New Zealand (+64)</option>
                                                        <option value="+27">South Africa (+27)</option>
                                                        <option value="+33">France (+33)</option>
                                                        <option value="+49">Germany (+49)</option>
                                                        <option value="+39">Italy (+39)</option>
                                                        <option value="+34">Spain (+34)</option>
                                                    </select>
                                                    <input type="tel" name="phone_register" id="phone_register" class="form-control phone-number" required data-error="Please enter your phone number" placeholder="Phone Number">
                                                </div>
                                                <div class="help-block">
                                                    <p id="phone_register_error" class="errors"></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Gender -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <i class='bx bx-user'></i>
                                                <select class="form-select chosen-select" name="gander_register" id="gender_register" data-placeholder="Select your Gender" required>
                                                    <option value="">Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                                <div class="help-block">
                                                    <p id="gander_register_error" class="errors"></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Date of Birth (DD-MM-YYYY format) -->
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <i class='bx bx-calendar'></i>
                                                <input type="date" name="dob_register" id="dob_register" class="form-control datepicker-dmy" required data-error="Please enter your date of birth" placeholder="Date of Birth (DD-MM-YYYY)" pattern="\d{2}-\d{2}-\d{4}" title="Please enter date in DD-MM-YYYY format">
                                                <div class="help-block">
                                                    <p id="dob_register_error" class="errors"></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Password -->
                                        <div class="col-12">
                                            <div class="form-group">
                                                <i class='bx bx-lock-alt'></i>
                                                <input class="form-control" type="password" name="password" placeholder="Password">
                                                <div class="help-block">
                                                    <p id="password_error" class="errors"></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Confirm Password -->
                                        <div class="col-12">
                                            <div class="form-group">
                                                <i class='bx bx-lock-alt'></i>
                                                <input class="form-control" type="password" name="confirm_password" placeholder="Re-enter Password">
                                                <div class="help-block">
                                                    <p id="confirm_password_error" class="errors"></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Terms & Conditions and Privacy Policy -->
                                        <div class="form-step">
                                            <h4 class="mb-3">Accept Terms & Conditions and Privacy Policy</h4>
                                            <div class="form-group form-check mb-3">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                                    I agree to the
                                                    <a href="<?php echo base_url('page/terms-and-conditions'); ?>" style="color: #000;" target="_blank">Terms & Conditions</a>
                                                     and 
                                                    <a href="<?php echo base_url('page/privacy-policy'); ?>" style="color: #000; font-weight: bold" target="_blank">Privacy Policy</a>
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-success">Create Account</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add this CSS for phone input styling -->
<style>
    .phone-group .phone-input-wrapper {
        display: flex;
        gap: 10px;
        width: 100%;
    }
    
    .phone-group .country-code-select {
        width: 35%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .phone-group .phone-number {
        width: 65%;
    }
    
    .phone-group i.bx-phone {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1;
    }
    
    .phone-group {
        position: relative;
    }
    
    .section-title {
        margin-bottom: 15px;
        color: #333;
        font-weight: 600;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }
    
    /* Date picker styling hint */
    .datepicker-dmy {
        background: white;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .phone-group .phone-input-wrapper {
            flex-direction: column;
            gap: 5px;
        }
        
        .phone-group .country-code-select,
        .phone-group .phone-number {
            width: 100%;
        }
    }
</style>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
var BaseUrl = '<?php echo base_url(''); ?>/';	

$(document).ready(function() {
    // Load login area initially
    login_area();

    // ==========================
    // Register Form Submission
    // ==========================
    $('#contactForm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: BaseUrl + 'register_process',
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('#register_submit').attr('disabled', 'disabled');
            },
            success: function(data) {
                $('#register_submit').attr('disabled', false);

                // ========================
                // Validation Error Display
                // ========================
                $('#user_name_error').html(data.name_register_error || '');
                $('#user_last_name_error').html(data.name_last_register_error || '');
                $('#phone_register_error').html(data.phone_register_error || '');
                $('#email_register_error').html(data.email_register_error || '');
                $('#password_error').html(data.password_error || '');
                $('#confirm_password_error').html(data.confirm_password_error || '');
                $('#gander_register_error').html(data.gander_register_error || '');
                $('#dob_register_error').html(data.dob_register_error || '');

                // ========================
                // Failed Case
                // ========================
                if (data.failed) {
                    $('#success_message').html(data.failed);
                }

                // ========================
                // Success Case
                // ========================
                if (data.success) {
                    showAlert(data.class, data.title, data.message);
                    $('#myModal').modal('hide');
                    $('.errors').html('');
                    $('#contactForm')[0].reset();
                    login_area();
                }
            }
        });
    });

    // ==========================
    // Login Form Submission
    // ==========================
    $('#user_login').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: BaseUrl + 'login_process',
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('#login_submit').attr('disabled', 'disabled');
            },
            success: function(data) {
                $('#login_submit').attr('disabled', false);

                // Handle login validation
                $('#login_email_error').html(data.login_email_error || '');
                $('#login_password_error').html(data.login_password_error || '');

                if (data.failed) {
                    $('#success_message_login').html(data.failed);
                }

                if (data.success) {
                    showAlert(data.class, data.title, data.message);
                    $('.errors').html('');
                    $('#user_login')[0].reset();
                    $('#myModal').modal('hide');
                    login_area();

                    setTimeout(function() {
                        $('#myModal').modal('hide');
                    }, 3000);
                }
            }
        });
    });

    // ==========================
    // Login Area Function
    // ==========================
    function login_area() {
        $.ajax({
            url: BaseUrl + 'chack-sing-in',
            method: "POST",
            dataType: "JSON",
            data: { action: 'fetch_data' },
            success: function(data) {
                $('#login_area').html(data.output);

                if (data.success && data.url) {
                    window.location.href = data.url;
                }

                if ($('#bookingButton').length) {
                    $('#bookingButton').html(data.bookingbutton);
                }
            }
        });
    }
});
</script>
