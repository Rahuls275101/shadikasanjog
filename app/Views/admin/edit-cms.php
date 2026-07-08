
	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>Update Content</h1>
							<p class="breadcrumbs"><span><a href="#">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>Content Management System
							</p>
						</div>

						<div>
						
						
							   
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 col-lg-12">
							<div class="ec-cat-list card card-default">
								<div class="card-body">
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
                <form role="form" method="POST" action="<?php echo base_url('admin/edit_cms_process/'.$cmsView->cms_id.''); ?>" enctype="multipart/form-data">
                <div class="card-body">
                   <div class="row">

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Page Name</label>
                    <input type="text" class="form-control" disabled value="<?php echo $cmsView->cms_page_name; ?>">
                  </div>
                  </div>

                 <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputFile"> Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile" name="product_image">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        <input type="hidden" class="custom-file-input" name="product_image_old" value="<?php echo $cmsView->cms_image; ?>">
                      </div>
                    </div>
                  </div>
                  </div>


                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Page Heading*</label>
                    <input type="text" class="form-control" name="cms_page_heading" value="<?php echo $cmsView->cms_page_heading; ?>" required>
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                  <label for="exampleInputPassword1">Small Description*</label>
                    <input type="text" class="form-control" name="cms_page_small_description" value="<?php echo $cmsView->cms_page_small_description; ?>" required>
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Long Description</label>
                    <div class="mb-3">
                    <textarea class="ckeditor" placeholder="Enter the course description..." style="width: 100%; height: 300px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="cms_page_description"><?php echo $cmsView->cms_page_description; ?></textarea>
                    </div>
                  </div>
                  </div>

                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer" style="text-align: center;">
                  <input type="submit" name="pageUpdated" value="Submit" class="btn btn-primary">
                </div>
              </form>



								</div>
							</div>
						</div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->


