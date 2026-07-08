

			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>All Projects </h1>
							<p class="breadcrumbs"><span><a href="#">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>All Projects
							</p>
						</div>

					
					</div>
					<div class="row">
		     

        <div class="col-12">
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
    <form class="form-inline" method="POST" action="<?php echo base_url('admin/our_gallery_process'); ?>" enctype="multipart/form-data">
 <div class="col-12">
        <div class="row">
              <div class="col-4">
                    <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Name*</label>
                  <input type="test" class="form-control" required="" name="name" value="null">
                  </div>
                  </div>
            </div>
                <div class="col-4">
               <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Location*</label>
                  <input type="test" class="form-control" required="" name="location" value="null">
                  </div>
                  </div>
            </div>
              <div class="col-4">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Type*</label>
                    <select class="form-control" required name="type">
                    <option value="OUR CLIENTS">OUR CLIENTS</option>
                  
                 
                    </select> 
                  </div>
                  </div>
            </div>
             <div class="col-4">
                    <div class="form-group">
                    <div class="input-group">
                      <div class="custom-file">
                              <label for="exampleInputPassword1">Image*</label>
                        <input type="file" class="form-control" required="" name="client_logo">
                      
                      </div>
                    </div>
                  </div>
            </div>
             <div class="col-4">
                  <div class="form-group mx-sm-3 mb-2">
    <input type="submit" name="upload_logo" value="Upload" class="btn btn-success">
    </div>
            </div>
            
        </div>

</div>

                

   
    </form>                            


            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <div class="card">
          
            <!-- /.card-header -->
            <div class="card-body">
              <table  class="table table-bordered table-striped">
     
                <thead>
                <tr>
                  <th>#S.No</th>
                    <th>Name</th>
                      <th>Location</th>
                    <th>Type</th>
                  <th>Gallery</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody> 
                <?php
                $clientSeId = 1;
                foreach ($clientView as $clientView) {
                ?>
                <tr>
                <th><?php echo $clientSeId; ?></th>
                 <th><?php echo $clientView->name; ?></th>
                  <th><?php echo $clientView->location; ?></th>
                  <th><?php echo $clientView->type; ?></th>
                <th><img src="<?php echo base_url('assets/client/'.$clientView->client_image.''); ?>" style="width: 200px; height:100px;"></th>
                <th><a href="<?php echo base_url('admin/delete_gallery/'.$clientView->client_id.''); ?>" class="btn btn-block btn-danger btn-sm">Delete</a></th>
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



