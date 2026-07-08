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
		<meta name="description" content="Admin Dashboard">

		<title>Admin Dashboard</title>
		
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
								
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="login_area_right_wrapper">
								<div class="login_area_right_heading">
									<h4>Welcome Back!</h4>
									<p>Sign in to continue to <a href="#!">Admin</a></p>
									<?php if(session()->getFlashdata('registration_success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('registration_success') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('login_failed')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('login_failed') ?>
        </div>
    <?php endif; ?>
							
								</div>
								<div class="login_form_wrapper">
									<form action="<?php echo base_url('admin/admin_login'); ?>" method="post">
										<div class="form-group">
											<label>User name</label>
											<input type="email" class="form-control" name="email" placeholder="Enter username">
										</div>
										<div class="form-group">
											<label>Password</label>
											<input type="password" class="form-control" name="password" placeholder="Enter password">
										</div>
										<div class="login_form_forget">
										
										</div>
										<div class="login_form_bottm_area">
											<button type="submit" class="btn btn-primary w-100">Login</button>
										<!--	<div class="login_middel_title">
												<p>New to Vender</p>
											</div>
											<a href="<?php echo base_url('vender_register'); ?>" class="btn custom_button w-100">Register</a>-->
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
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


