<?php 

use App\Models\Commanmodel;
 $commanmodel = new Commanmodel();
   $session = session();
  $webdetails =  $commanmodel->get_single_query('address',array('id' => 1));
  
  
  


?> 

			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All  <?php echo $type ? $type : 'Blog'; ?> </h1>
							<p class="breadcrumbs"><span><a href="#">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Available <?php echo $type ? $type : 'Blog'; ?> 
							</p>
						</div>

						<div>
	
					
							    <a href="<?php echo base_url('admin/create_blog'); ?>" class="btn btn-primary">Add new <?php echo $type ? $type : 'Blog'; ?> </a>
							   
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
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>




        <div class="col-12">
          <!-- /.card -->
          <div class="card">
          
            <!-- /.card-header -->
            <div class="card-body">
              <table id="blog_list" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#S.No</th>
                  <th> heading</th>
         
                  <th>Image</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody> 
                <?php
                $clientSeId = 1;
                foreach ($blogView as $blogView) {
                ?>
                <tr>
                <th><?php echo $clientSeId; ?></th>
                <th><?php echo $blogView->blog_name; ?></th>
               
                <th><img src="<?php echo base_url('assets/blog/'.$blogView->blog_image.''); ?>" style="width: 100px;height: 100px;"></th>
                <th><a href="javascript:void(0);" class="btn btn-block btn-<?php echo $blogView->blog_status_color; ?> btn-sm"><?php echo $blogView->blog_status; ?></a>
                  
                </th>
                <th>
                  <a href="<?php echo base_url('admin/edit_our_blog/'.$blogView->blog_id.''); ?>" class="btn btn-block btn-primary btn-sm">Edit</a>
                <br> 
                  <a href="<?php echo base_url('admin/delete_our_blog/'.$blogView->blog_id.''); ?>" class="btn btn-block btn-danger btn-sm">Delete</a></th> 
                </tr> 
                <?php
                $clientSeId++;
                }
                ?>             
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->



