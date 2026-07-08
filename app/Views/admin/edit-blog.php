 <style>
.note-editable,card-block
{
  height:200px !important;
}
<?php 
use App\Models\Commanmodel;

 $commanmodel = new Commanmodel();
 ?>
 </style>




			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper breadcrumb-contacts">
						<div>
							<h1>Update Blogs </h1>
							<p class="breadcrumbs"><span><a href="#">Home</a></span>
								<span><i class="mdi mdi-chevron-right"></i></span>Update Blogs
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
                <form role="form" method="POST" action="<?php echo base_url('admin/edit_blog_process/'.$blogView->blog_id.''); ?>" enctype="multipart/form-data">
                <div class="card-body">
                   <div class="row">

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Title*</label>
                    <input type="text" class="form-control" name="blog_name" required value="<?php echo $blogView->blog_name; ?>">
                  </div>
                  </div>
 <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> URL slug*</label>
                    <input type="text" required class="form-control" name="url_slug" value="<?php echo $blogView->url_slug; ?>" required>
                  </div>
                  </div>

                  <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputFile"> Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="form-control" name="blog_image">
                        
                        <input type="hidden" class="custom-file-input" name="blog_image_old" value="<?php echo $blogView->blog_image; ?>">
                      </div>
                    </div>
                  </div>
                  </div>
             
                  <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" required name="blog_status">
                    <option value="Active" <?php if($blogView->blog_status=='Active') { echo "selected"; } ?>>Active</option>
                    <option value="Inactive" <?php if($blogView->blog_status=='Inactive') { echo "selected"; } ?>>Inactive</option>
                    </select> 
                  </div>
                  </div>
                  
                  
                  <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Type*</label>
                    <select class="form-control" required name="type">
                    <option value="Blog" <?php if($blogView->type=='Blog') { echo "selected"; } ?>>Blog</option>
                    <option value="Event" <?php if($blogView->type=='Event') { echo "selected"; } ?>>Event</option>
                    <option value="Story" <?php if($blogView->type=='Story') { echo "selected"; } ?>>Story</option>
                    </select> 
                  </div>
                  </div>
                  
                  <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Time*</label>
                    <input type="text" class="form-control" name="time"  value="<?php echo $blogView->time; ?>">
                  </div>
                  </div>
 
                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Small Description*</label>
                    <input type="text" class="form-control" name="blog_small_description" value="<?php echo $blogView->blog_small_description; ?>" required>
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Long Description</label>
                    <div class="mb-3">
                    <textarea class="textarea" id="ckeditor" placeholder="Enter the course description..." style="width: 100%; height: 300px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="blog_description"><?php echo $blogView->blog_description; ?></textarea>
                    </div>
                  </div>
                  </div>


                  <div class="col-md-12">
                  <h4>Meta tag for this blog</h4>
                  </div> 

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Meta Title</label>
                  <input type="text" class="form-control" value="<?php echo $blogView->meta_title; ?>" name="meta_title"> 
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Meta Keyword</label>
                  <input type="text" class="form-control" value="<?php echo $blogView->meta_keyword; ?>" name="meta_keyword" required> 
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Meta Description</label>
                  <input type="text" class="form-control" value="<?php echo $blogView->meta_description; ?>" name="meta_description" required> 
                  </div>
                  </div>
                  
                  



                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer" style="text-align: center;">
                  <input type="submit" name="CreateEditBlog" value="Submit" class="btn btn-primary">
                </div>
              </form>




            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->









