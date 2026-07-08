


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><a href="#" class="btn btn-primary">Testimonials Upadte</a></h1>
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
            <div class="card-body">
    <form action="<?php echo base_url('admin/team-update').'/'.$id; ?>" method="post"  enctype="multipart/form-data">
    <div class="row">




    <div class="col-md-3">
    <div class="form-group">
    <label> name</label>
    <input type="text" class="form-control" name="name" value="<?php echo $team->team_name; ?>">
    </div>
    </div>
    
     <div class="col-md-3">
     <div class="form-group">
                    <label for="exampleInputEmail1">Image*</label>
                    <input type="file" class="form-control" name="logo" >
                    <input type="hidden" class="form-control" name="logo_old"  value="<?php echo $team->team_logo; ?>">
                  </div>
                  </div>


        
    <div class="col-md-3">
    <div class="form-group">
    <label>Status</label>
    <select class="form-control" name="status">
    <option value="">Select</option>
    <option value="Active" <?php if($team->team_status=='Active') { echo "selected"; }?>>Active</option>
    <option value="Inactive" <?php if($team->team_status=='Inactive') { echo "selected"; }?>>Inactive</option>
         </select> 
    </div>
    </div>
    
    
     <div class="col-md-3">
    <div class="form-group">
    <label> Designation</label>
    <input type="text" class="form-control" name="designation" value="<?php echo $team->designation; ?>">
    </div>
    </div>


<div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Overview*</label>
                    <div class="mb-3">
                    <textarea class="ckeditor" placeholder="Enter product quick overview..." style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="overview" id="overview"><?php echo $team->overview; ?></textarea>
                    </div>
                  </div>
                  </div>
 

    <div class="col-md-3" style="margin-top: 31px;">
    <div class="form-group">
    <button type="submit"  style="margin-right: 10px" class="btn btn-success">Submit</button>

    </div>
    </div>

    </div>
    </form>                            


            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>



      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


 

 