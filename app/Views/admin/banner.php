

	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All Banner </h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Banner 
							</p>
						</div>

						<div>
						
							   
						</div>
					</div>
					<div class="row">
					    
					          <div class="col-12">
          <!-- /.card -->
          <div class="card">
              <?php if(session()->getFlashdata('failed')):?>
                    <div class="alert alert-danger alert-dismissable">
                       <?= session()->getFlashdata('failed') ?>
                    </div>
                <?php endif;?>

                <?php if(session()->getFlashdata('created')):?>
                    <div class="alert alert-success alert-dismissable">
                       <?= session()->getFlashdata('created') ?>
                    </div>
                <?php endif;?>
            <!-- /.card-header -->
            <div class="card-body">
    <form method="POST" action="<?php echo base_url('admin/home_banner_process'); ?>" enctype="multipart/form-data">
      <div class="row">

                 
				<div class="col-md-6">
                   <div class="form-group">
                        <input type="text" class="form-control" required="" name="first_title" placeholder="Enter Titel">
                  </div>
                </div>
                	<div class="col-md-6">
                   <div class="form-group">
                        <input type="text" class="form-control" required="" name="banner_first_second" placeholder="Enter Titel">
                  </div>
                </div>
			
				
                
                 <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputFile">Banner*</label>
                    <div class="input-group">
                      <div class="custom-file">
                       
                     <input type="file"  class="form-control" required="" name="home_banner">
                      </div>
                    </div>
                  </div>
                  </div>
                <div class="col-md-4">
                   <div class="form-group">
				    <label for="exampleInputPassword1">redirect url*</label>
                        <input type="text" class="form-control" required="" name="redirect_url" placeholder="Enter redirect url">
                  </div>
                </div>
			
				<div class="col-md-4">
                   <div class="form-group">
                        <label for="exampleInputPassword1">Date*</label>
                        <input type="date" class="form-control" required="" name="date" placeholder="Date">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                  <input type="submit" name="upload_banner" value="Submit" class="btn btn-primary">
                  </div>
                </div>

                </div>
    </form>                            


            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>


						<div class="col-xl-12 col-lg-12">
							<div class="ec-cat-list card card-default">
								<div class="card-body">
									<div class="table-responsive">
									   
									 <table class="table table-bordered table-striped" >
                <thead>
                <tr>
                  <th>#S.No</th>
                  <th>Banner Image</th>
                  <th>Title</th>
                    <th>Redirect url</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody> 
                <?php
                $clientSeId = 1;
                foreach ($bannerView as $bannerView) {
                ?>
                <tr>
                <th><?php echo $clientSeId; ?></th>
                <th><img class="cat-thumb" src="<?php echo base_url('assets/images/'.$bannerView->banner_image.''); ?>" ></th>
                <th><?php echo $bannerView->banner_first_title; ?></th>
                  <th><?php echo $bannerView->redirect_url; ?></th>
                <th>

              
             <div class="btn-group">
															<button type="button"
																class="btn btn-outline-<?php echo $bannerView->banner_status_color; ?>"><?php echo $bannerView->banner_status; ?></button>
															<button type="button"
																class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
																data-bs-toggle="dropdown" aria-haspopup="true"
																aria-expanded="false" data-display="static">
																<span class="sr-only">Info</span>
															</button>
															<div class="dropdown-menu">
																<a class="dropdown-item " href="<?php echo base_url('admin/edit_home_banner/'.$bannerView->banner_id.''); ?>" >Edit</a>
																<a class="dropdown-item" href="<?php echo base_url('admin/delete_home_banner/'.$bannerView->banner_id.''); ?>">Delete</a>
															</div>
														</div> 
              
              
                </tr> 
                <?php
                $clientSeId++;
                }
                ?>             
                </tbody>
                </table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->




