<style>
.note-editable,card-block
{
  height:200px !important;
}
</style>
<?php 


?> 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h5 class="m-0 text-dark">Update Franchise  </h5>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    

    <section class="content">
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
                <form method="POST" action="<?php echo base_url('admin/update_franchise_process/'.$id); ?>" enctype="multipart/form-data">
                <div class="card-body">
                   <div class="row">

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Name*</label>
                    <input type="text" required class="form-control" name="name" value="<?php echo $franchise->franchise_name; ?>">
                  </div>
                  </div>
               
                  
                
                     <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Contact Number *</label>
                    <input type="text" required class="form-control" name="number" value="<?php echo $franchise->franchise_phone; ?>">
                  </div>
                  </div>
                     <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Email Id *</label>
                    <input type="text" required class="form-control" name="email" value="<?php echo $franchise->franchise_email; ?>">
                  </div>
                  </div>
              
            
                  
                     <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Time (Min) *</label>
                    <input type="text" required class="form-control" name="time" value="<?php echo $franchise->franchise_time; ?>">
                  </div>
                  </div>
                   <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Category*</label>
                    <select class="form-control" required name="category">
                    <?php foreach($category as $categoryrow) { ?>
                    <option value="<?php echo $categoryrow->category_id; ?>" <?php if($categoryrow->category_id==$franchise->franchise_category) { echo "selected"; } ?>><?php echo $categoryrow->category_name; ?></option>
                        <?php } ?>
                    </select> 
                  </div>
                  </div>
                     <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" required name="status">
                    <option value="Active" <?php if($franchise->franchise_status=='Active') { echo "selected"; } ?>>Active</option>
                    <option value="Inactive" <?php if($franchise->franchise_status=='Inactive') { echo "selected"; } ?>>Inactive</option>
                    </select> 
                  </div>
                  </div>
                     <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Discount *</label>
                    <input type="text" required class="form-control" name="franchise_discount" value="<?php echo $franchise->franchise_discount; ?>">
                  </div>
                  </div>
			  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Address *</label>
                    <input type="text" required class="form-control" name="address" value="<?php echo $franchise->franchise_address; ?>">
                  </div>
                  </div>
		
             <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1"> Title</label>
                    <input type="text" required class="form-control" name="title" value="<?php echo $franchise->title; ?>">
                  </div>
                  </div>
               
               <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1"> Description</label>
                    <div class="mb-3">
                    <textarea id="ckeditor" placeholder="Enter Description" style="width: 100%; height: 300px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="description" id="description">
<?php echo $franchise->description; ?>
</textarea>
                    </div>
                  </div>
                  </div>
               
               
             <div class="card-footer" style="text-align: center;">
                  <input type="submit" name="CreateNewProduct" value="Submit" class="btn btn-primary">
                </div>
              </form>


                  </div>
                </div>
                <!-- /.card-body -->
               
            <!-- /.card-body -->
            
            
          </div>
          <!-- /.card -->

     

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

