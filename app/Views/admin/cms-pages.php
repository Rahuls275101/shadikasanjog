
	<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>Content Management System</h1>
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
									<div class="table-responsive">
									   
									  <table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#S.No</th>
                  <th>Image</th>
                  <th>Page Name</th>
                   <th>Title</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody> 
                <?php
                $clientSeId = 1;
                foreach ($cmsView as $cmsView) {
                ?>
                <tr>
                <th><?php echo $clientSeId; ?></th>
                
                 <th> <?php if($cmsView->cms_image !="" && file_exists('./assets/images/'.$cmsView->cms_image))
                          { ?>
                      <img class="cat-thumb"  src="<?php echo base_url().'/assets/images/'.$cmsView->cms_image; ?>" >
                      <?php } ?>
                     </th>
                <th><?php echo $cmsView->cms_page_name; ?></th>
                  <th><?php echo $cmsView->cms_page_heading; ?></th>
                <th><a href="<?php echo base_url('admin/edit_cms/'.$cmsView->cms_id.''); ?>" class="btn btn-block btn-primary btn-sm">Edit</a></th>
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

