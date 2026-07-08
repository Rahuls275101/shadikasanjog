
<?php 

use App\Models\Commanmodel;
 $commanmodel = new Commanmodel();
   $session = session();
  $webdetails =  $commanmodel->get_single_query('address',array('id' => 1));
?> 
<!DOCTYPE html>
<html lang="en">
	
<!-- Mirrored from andit.co/projects/html/andshop/andshop-dashboard/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 25 Dec 2024 09:12:21 GMT -->
<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="Andshop - Admin Dashboard HTML Template.">

		<title>Andshop - Admin Dashboard HTML Template.</title>
		
		<!-- GOOGLE FONTS -->
		<link rel="preconnect" href="https://fonts.googleapis.com/">
		<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&amp;family=Poppins:wght@300;400;500;600;700;800;900&amp;family=Roboto:wght@400;500;700;900&amp;display=swap" rel="stylesheet">

		<link href="<?php echo base_url('assets/admin/'); ?>/assets/css/materialdesignicons.min.css" rel="stylesheet" />
		
		<!-- custom css -->
		<link id="style.css" rel="stylesheet" href="<?php echo base_url('assets/admin/'); ?>/assets/css/style.css" />
		
		<!-- FAVICON -->
		<link href="<?php echo base_url('assets/admin/'); ?>/assets/img/favicon.png" rel="shortcut icon" />
	</head>
	
	<body class="sign-inup" id="body">
		<div class="container">
			<div class="row g-0"> 
				<div class="col-lg-10 offset-lg-1">
					<div class="row g-0">
						<div class="col-lg-6">
							<div class="login_area_left_wrapper">
								<div class="login_logo_area">
									<img src="<?php echo base_url('assets/img/'); ?>/<?php echo $webdetails->header_logo; ?>" alt=""  style="max-width: 100px;">
									<p>Nulla laborum sit voluptate anim in. Nulla ut qui ex 
										ipsum id aliqua amet exercitation. Anim ididunt
										anim anim voluptate enim.</p>
								</div>
							</div>
						</div>
					
						
<div class="col-lg-6">
							<div class="login_area_right_wrapper">
								<div class="login_area_right_heading">
									<h4>Register account</h4>
									<p>Sign up to join</p>
									 <?php if(session()->getFlashdata('registration_success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('registration_success') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('registration_failed')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('registration_failed') ?>
        </div>
    <?php endif; ?>
								</div>
								<div class="login_form_wrapper">
									<form "<?php echo base_url('vender_register'); ?>" method="Post">
									    
									 <div class="form-group">
    <label>User Type</label><br>

<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="type" id="vendor" value="Vendor" <?= set_radio('type', 'Vendor') ?>>
    <label class="form-check-label" for="vendor">Vendor</label>
</div>

<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="type" id="franchise" value="Franchise" <?= set_radio('type', 'Franchise') ?>>
    <label class="form-check-label" for="franchise">Franchise</label>
</div>

<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="type" id="promoter" value="Promoter" <?= set_radio('type', 'Promoter') ?>>
    <label class="form-check-label" for="promoter">Promoter</label>
</div>


    <?php if (isset($validation) && $validation->getError('type')): ?>
        <p class="badge badge-danger sm"><?= $validation->getError('type') ?></p>
    <?php endif; ?>
</div>

<!-- Refer By Code Input (Initially Hidden) -->
<div class="form-group" id="referByGroup" style="display: none;">
    <label>Refer By Code</label>
    <input type="text" class="form-control" name="refer_by" placeholder="Enter referral code" value="<?= old('refer_by') ?>">
    
    <?php if (isset($validation) && $validation->getError('refer_by')): ?>
        <p class="badge badge-danger sm"><?= $validation->getError('refer_by') ?></p>
    <?php endif; ?>
</div>


										
										
										       
          
                  
									    
										<div class="form-group">
											<label>User name</label>
											<input type="text" class="form-control" name="name" placeholder="Enter username" value="<?= old('name') ?>">
											 <?php if(isset($validation) && $validation->getError('name')): ?>
                      <p class="badge badge-danger sm"><?= $validation->getError('name') ?></p>
                      <?php endif; ?>
										</div>
										<div class="form-group">
											<label>Email address</label>
											<input type="email" class="form-control" name="email" placeholder="Enter email address" value="<?= old('email') ?>">
											<?php if(isset($validation) && $validation->getError('email')): ?>
                              <p class="badge badge-danger"><?= $validation->getError('email') ?></p>
                              <?php endif; ?>
										</div>
										
											<div class="form-group">
											<label>Phone Number</label>
											<input type="text" class="form-control" name="phone" placeholder="Enter Phone Number" value="<?= old('phone') ?>">
											<?php if(isset($validation) && $validation->getError('phone')): ?>
                              <p class="badge badge-danger"><?= $validation->getError('phone') ?></p>
                              <?php endif; ?>
										</div>
										<div class="form-group">
											<label>Password</label>
											<input type="password" class="form-control" name="password" placeholder="Enter password" value="<?= old('password') ?>">
											  <?php if(isset($validation) && $validation->getError('password')): ?>
                              <p class="badge badge-danger"><?= $validation->getError('password') ?></p>
                              <?php endif; ?>
										</div>
											<div class="form-group">
											<label>Confirm Password</label>
											<input type="password" class="form-control" name="confirm_password" placeholder="Enter Confirm Password" value="<?= old('confirm_password') ?>">
											  <?php if(isset($validation) && $validation->getError('confirm_password')): ?>
                              <p class="badge badge-danger"><?= $validation->getError('confirm_password') ?></p>
                              <?php endif; ?>
										</div>
										<div class="login_form_forget">
										  <p>By registering you agree to the AndShop Terms of Service</p>
										</div>
										<div class="login_form_bottm_area">
											<button type="submit" class="btn btn-primary w-100">Register</button>
											<div class="login_middel_title">
												<p>Already have account</p>
											</div>
											<a href="<?php echo base_url('admin'); ?>" class="btn custom_button w-100">Login</a>
										</div>
									</form>
								</div>
							</div>
						</div>
						
						
						
					</div>
				</div>
			</div>
		</div>
<script>
    function toggleReferBy() {
        const promoterRadio = document.getElementById('promoter');
        const referByGroup = document.getElementById('referByGroup');

        if (promoterRadio.checked) {
            referByGroup.style.display = 'block';
        } else {
            referByGroup.style.display = 'none';
        }
    }

    // On page load
    window.onload = toggleReferBy;

    // On radio change
    const radios = document.querySelectorAll('input[name="type"]');
    radios.forEach(radio => {
        radio.addEventListener('change', toggleReferBy);
    });
</script>


		<!-- Javascript -->
		<script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/jquery/jquery-3.5.1.min.js"></script>
		<script src="<?php echo base_url('assets/admin/'); ?>/assets/js/bootstrap.bundle.min.js"></script>
		<script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/jquery-zoom/jquery.zoom.min.js"></script>
		<script src="<?php echo base_url('assets/admin/'); ?>/assets/plugins/slick/slick.min.js"></script>
	
		<!-- custom js -->	
		<script src="<?php echo base_url('assets/admin/'); ?>/assets/js/custom.js"></script>
	</body>


<!-- Mirrored from andit.co/projects/html/andshop/andshop-dashboard/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 25 Dec 2024 09:12:21 GMT -->
</html>


