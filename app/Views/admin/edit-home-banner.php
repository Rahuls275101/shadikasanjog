

	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>Update Banner </h1>
							<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>Update Banner 
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
  <form role="form" method="POST" action="<?php echo base_url('admin/edit_home_banner_process/'.$bannerView->banner_id.''); ?>" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">
                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputFile">Banner Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="form-control" id="exampleInputFile" name="banner_image">
                        <input type="hidden"  required name="banner_image_old" value="<?php echo $bannerView->banner_image; ?>">
                      </div>
                    </div>
                  </div>
                  </div>
				<div class="col-md-6">
                   <div class="form-group">
                        <input type="text" class="form-control" required="" name="first_title" value="<?php echo $bannerView->banner_first_title; ?>" placeholder="Enter First Titel">
                  </div>
                </div>
			<div class="col-md-6">
                   <div class="form-group">
                        <input type="text" class="form-control" required="" name="banner_first_second" value="<?php echo $bannerView->banner_first_second; ?>" placeholder="Enter Second Titel">
                  </div>
                </div>
                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" required name="banner_status">
                    <option value="Active" <?php if($bannerView->banner_status=='Active') { echo "selected"; } ?>>Active</option>
                    <option value="Inactive" <?php if($bannerView->banner_status=='Inactive') { echo "selected"; } ?>>Inactive</option>
                    </select> 
                  </div>
                  </div>
			
                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Redirect Url</label>
                    <input type="text" class="form-control" name="redirect_url" value="<?php echo $bannerView->redirect_url; ?>">
                  </div>
                  </div>
	<div class="col-md-4">
                   <div class="form-group">
                        <label for="exampleInputPassword1">Date*</label>
                        <input type="date" class="form-control" required="" name="date" placeholder="Date" value="<?php echo $bannerView->banner_date; ?>">
                  </div>
                </div>
                </div>
                  </div>
                <!-- /.card-body -->
                <div class="card-footer" style="text-align: center;">
                  <input type="submit" name="EditBanner" value="Submit" class="btn btn-primary">
                </div>
              </form>


            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>



					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->

















