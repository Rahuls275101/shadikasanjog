<?php 

use App\Models\Commanmodel;
 $commanmodel = new Commanmodel();
   $session = session();
  $webdetails =  $commanmodel->get_single_query('address',array('id' => 1));
?> 

 <body class="ec-header-fixed ec-sidebar-fixed ec-sidebar-light ec-header-light" id="body">

	<!--  WRAPPER  -->
	<div class="wrapper">
		
		<!-- LEFT MAIN SIDEBAR -->
		<div class="ec-left-sidebar ec-bg-sidebar">
			<div id="sidebar" class="sidebar ec-sidebar-footer">

				<div class="ec-brand">
					<a href="<?php echo base_url('admin/dashboard'); ?>">
						<img class="ec-brand-icon" src="<?php echo base_url('assets/img/'); ?>/<?php echo $webdetails->header_logo; ?>" alt="" />
						<span class="ec-brand-name text-truncate"><?php echo $webdetails->web_name; ?></span>
					</a>
				</div>

				<!-- begin sidebar scrollbar -->
				<div class="ec-navigation" data-simplebar>
					<!-- sidebar menu -->
					<ul class="nav sidebar-inner" id="sidebar-menu">
						<!-- Dashboard -->
						<li class="active">
							<a class="sidenav-item-link" href="<?php echo base_url('admin/dashboard'); ?>">
								<i class="mdi mdi-view-dashboard-outline"></i>
								<span class="nav-text">Dashboard</span>
							</a>
							<hr>
						</li>


	<?php if($session->admin_type == 'Vendor' || $session->admin_type == 'Supar Admin') { ?>
					<li class="has-sub">
							<a class="sidenav-item-link" href="javascript:void(0)">
							
								<i class="mdi mdi-package-variant-closed"></i>
								<span class="nav-text">Vendors</span> <b class="caret"></b>
							</a>
							<div class="collapse">
								<ul class="sub-menu" id="products" data-parent="#sidebar-menu">
									<li class="">
										<a class="sidenav-item-link" href="<?php echo base_url('admin/create_product'); ?>">
											<span class="nav-text">Add Vendor</span>
										</a>
									</li>
									<li class="">
										<a class="sidenav-item-link" href="<?php echo base_url('admin/product'); ?>">
											<span class="nav-text">List Vendor</span>
										</a>
									</li>
									
					
								
								</ul>
							</div>
						</li>
						
					
				<?php		} ?>
				
			
						
							<?php if($session->admin_type == 'Supar Admin') { ?>
							
							
						<!-- Vendors -->
				


								<!-- Category -->
					<li class="has-sub">
							<a class="sidenav-item-link" href="javascript:void(0)">
								<i class="mdi mdi-shape"></i>
								<span class="nav-text">City</span> <b class="caret"></b>
							</a>
							<div class="collapse">
								<ul class="sub-menu" id="categorys" data-parent="#sidebar-menu">
									<li class="">
										<a class="sidenav-item-link" href="<?php echo base_url('admin/category'); ?>">
											<span class="nav-text">All City</span>
										</a>
									</li>
								
								</ul>
							</div>
						</li>
	

			
						
						
			
	<!----><!--					
						<li>
							<a class="sidenav-item-link" href="<?php echo base_url('admin/enquiry'); ?>">
								<i class="mdi mdi-tag-faces"></i>
								<span class="nav-text">Enquiry</span>
							</a>
						
						</li>-->
						
				
<li>
    <a class="sidenav-item-link" href="<?= base_url('admin/blog?type=Blog'); ?>">
        <i class="mdi mdi-post-outline"></i>
        <span class="nav-text">Blogs</span>
    </a>
</li>

<li>
    <a class="sidenav-item-link" href="<?= base_url('admin/blog?type=Story'); ?>">
        <i class="mdi mdi-book-open-page-variant"></i>
        <span class="nav-text">Story</span>
    </a>
</li>

<li>
    <a class="sidenav-item-link" href="<?= base_url('admin/blog?type=Event'); ?>">
        <i class="mdi mdi-calendar-star"></i>
        <span class="nav-text">Event</span>
    </a>
</li>
				
						
			<li>
							<a class="sidenav-item-link" href="<?php echo base_url('admin/customer'); ?>">
								<i class="mdi mdi-account"></i>
								<span class="nav-text">Profile</span>
							</a>
						
						</li>
						
				
						<li class="has-sub">
							<a class="sidenav-item-link" href="javascript:void(0)">
								<i class="mdi mdi-web"></i>
								<span class="nav-text">Website</span> <b class="caret"></b>
							</a>
							<div class="collapse">
								<ul class="sub-menu" id="website" data-parent="#sidebar-menu">
								
								<li class="">
										<a class="sidenav-item-link" href="<?php echo base_url('admin/home_banner'); ?>">
											<span class="nav-text">Banner List</span>
										</a>
									</li>
									
									<li class="">
										<a class="sidenav-item-link" href="<?php echo base_url('admin/cms_pages'); ?>">
											<span class="nav-text">CMS Pages List</span>
										</a>
									</li>
									
								
											<li class="">
										<a class="sidenav-item-link" href="<?php echo base_url('admin/team'); ?>">
											<span class="nav-text">Testimonials</span>
										</a>
									</li>
									
										<li class="">
										<a class="sidenav-item-link" href="<?php echo base_url('admin/faq'); ?>">
											<span class="nav-text">Faq</span>
										</a>
									</li>
									
									<li class="">
										<a class="sidenav-item-link" href="<?php echo base_url('admin/meta'); ?>">
											<span class="nav-text">Meta List</span>
										</a>
									</li>
								
								</ul>
							</div>
						</li>
						<?php } ?>

						<!-- Products -->
					
 
			
				

						
						<!-- Transactions -->
					
						<!-- Setting -->
						
						
						<li>
							<a class="sidenav-item-link" href="<?php echo base_url('admin/setting'); ?>">
								<i class="mdi mdi-cogs"></i>
								<span class="nav-text">Setting</span>
							</a>
						</li>
			

					
					</ul>
				</div>
			</div>
		</div>

		<!--  PAGE WRAPPER -->
		<div class="ec-page-wrapper">

			<!-- Header -->
			<header class="ec-main-header" id="header">
				<nav class="navbar navbar-static-top navbar-expand-lg">
					<!-- Sidebar toggle button -->
					<button id="sidebar-toggler">
						<img src="<?php echo base_url('assets/admin/'); ?>/assets/img/icons/clops.png" alt="">
					</button>
					<!-- search form -->
					<div class="search-form d-lg-inline-block">
						<div class="input-group">
							<input type="text" name="query" id="search-input" class="form-control"
								placeholder="search.." autofocus autocomplete="off" />
							<button type="button" name="search" id="search-btn" class="btn btn-flat">
								<i class="mdi mdi-magnify"></i>
							</button>
						</div>
						<div id="search-results-container">
							<ul id="search-results"></ul>
						</div>
					</div>

					<!-- navbar right -->
					<div class="navbar-right">
						<ul class="nav navbar-nav">
							<!-- User Account -->
							<li class="dropdown user-menu">
								<button class="dropdown-toggle nav-link ec-drop" data-bs-toggle="dropdown"
									aria-expanded="false">
								    <?php if(!empty($session->image)) { ?>
								    <img src="<?php echo base_url('assets/vender/').'/'.$session->image; ?>" class="user-image" alt="<?php echo $session->name; ?>" />
								    <?php } else { ?>
								        	<img src="<?php echo base_url('assets/admin/'); ?>/assets/img/user/user-1.png" class="user-image" alt="User Image" />
								  <?php   } ?>
								
								</button>
								<ul class="dropdown-menu dropdown-menu-right ec-dropdown-menu">
									<!-- User image -->
									<li class="dropdown-header">
										<div class="d-inline-block">
											<h5><?php echo $session->name; ?></h5>
											<p class="pt-2"><?php echo $session->email; ?></p>
										</div>
									</li>
									<li>
										<a href="<?php echo base_url('admin/setting'); ?>">
											<i class="mdi mdi-account"></i> My Profile
										</a>
									</li>
									<li class="dropdown-footer">
										<a href="<?php echo base_url('admin/logout'); ?>"> <i class="mdi mdi-logout"></i> Log Out </a>
									</li>
								</ul>
							</li>
							<li class="dropdown notifications-menu custom-dropdown">
								<button class="dropdown-toggle notify-toggler custom-dropdown-toggler">
									<i class="mdi mdi-bell-ring-outline"></i>
								</button>

								<div class="card card-default dropdown-notify dropdown-menu-right mb-0">
									<div class="card-header card-header-border-bottom px-3">
										<h2>Notifications</h2>
									</div>

									<div class="card-body px-0 py-0">
										<div class="tab-content" id="myNotifications">
											<ul class="list-unstyled" data-simplebar style="height: 360px">
												<li>
													<a href="javscript:void(0)"
														class="media media-message media-notification">
														<div class="position-relative mr-3">
															<img class="rounded-circle" src="assets/img/user/u2.jpg"
																alt="Image">
															<span class="status away"></span>
														</div>
														<div class="media-body d-flex justify-content-between">
															<div class="message-contents">
																<h4 class="title">Nitin</h4>
																<p class="last-msg">Lorem ipsum dolor sit, amet
																	consectetur adipisicing elit. Nam itaque
																	doloremque odio, eligendi delectus vitae.</p>

																<span
																	class="font-size-12 font-weight-medium text-secondary">
																	<i class="mdi mdi-clock-outline"></i> 30 min
																	ago...
																</span>
															</div>
														</div>
													</a>
												</li>

												<li>
													<a href="javscript:void(0)"
														class="media media-message media-notification media-active">
														<div class="position-relative mr-3">
															<img class="rounded-circle" src="assets/img/user/u1.jpg"
																alt="Image">
															<span class="status active"></span>
														</div>
														<div class="media-body d-flex justify-content-between">
															<div class="message-contents">
																<h4 class="title">Lovina</h4>
																<p class="last-msg">Donec mattis augue a nisl
																	consequat, nec imperdiet ex rutrum. Fusce et
																	vehicula enim. Sed in enim eu odio vehic.</p>

																<span
																	class="font-size-12 font-weight-medium text-white">
																	<i class="mdi mdi-clock-outline"></i> Just
																	now...
																</span>
															</div>
														</div>
													</a>
												</li>

												<li>
													<a href="javscript:void(0)"
														class="media media-message media-notification">
														<div class="position-relative mr-3">
															<img class="rounded-circle" src="assets/img/user/u5.jpg"
																alt="Image">
															<span class="status away"></span>
														</div>
														<div class="media-body d-flex justify-content-between">
															<div class="message-contents">
																<h4 class="title">Crinali</h4>
																<p class="last-msg">Lorem ipsum dolor sit, amet
																	consectetur adipisicing elit. Nam itaque
																	doloremque odio, eligendi delectus vitae.</p>

																<span
																	class="font-size-12 font-weight-medium text-secondary">
																	<i class="mdi mdi-clock-outline"></i> 1 hrs
																	ago...
																</span>
															</div>
														</div>
													</a>
												</li>
												<li>
													<a href="javscript:void(0)"
														class="media media-message media-notification">
														<div class="position-relative mr-3">
															<img class="rounded-circle" src="assets/img/user/u4.jpg"
																alt="Image">
															<span class="status away"></span>
														</div>
														<div class="media-body d-flex justify-content-between">
															<div class="message-contents">
																<h4 class="title">Crinali</h4>
																<p class="last-msg">Lorem ipsum dolor sit, amet
																	consectetur adipisicing elit. Nam itaque
																	doloremque odio, eligendi delectus vitae.</p>

																<span
																	class="font-size-12 font-weight-medium text-secondary">
																	<i class="mdi mdi-clock-outline"></i> 1 hrs
																	ago...
																</span>
															</div>
														</div>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>

								<ul class="dropdown-menu dropdown-menu-right d-none">
									<li class="dropdown-header">You have 5 notifications</li>
									<li>
										<a href="#">
											<i class="mdi mdi-account-plus"></i> New user registered
											<span class=" font-size-12 d-inline-block float-right"><i
													class="mdi mdi-clock-outline"></i> 10 AM</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="mdi mdi-account-remove"></i> User deleted
											<span class=" font-size-12 d-inline-block float-right"><i
													class="mdi mdi-clock-outline"></i> 07 AM</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="mdi mdi-chart-areaspline"></i> Sales report is ready
											<span class=" font-size-12 d-inline-block float-right"><i
													class="mdi mdi-clock-outline"></i> 12 PM</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="mdi mdi-account-supervisor"></i> New client
											<span class=" font-size-12 d-inline-block float-right"><i
													class="mdi mdi-clock-outline"></i> 10 AM</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="mdi mdi-server-network-off"></i> Server overloaded
											<span class=" font-size-12 d-inline-block float-right"><i
													class="mdi mdi-clock-outline"></i> 05 AM</span>
										</a>
									</li>
									<li class="dropdown-footer">
										<a class="text-center" href="#"> View All </a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</header>
			
			
		