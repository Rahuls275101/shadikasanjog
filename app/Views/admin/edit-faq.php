


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Edit Faq</h1>
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

<form role="form" method="POST" action="<?php echo base_url('admin/edit_faq_process/'.$faqView->faq_id.''); ?>" enctype="multipart/form-data">
                <div class="card-body">
                   <div class="row">
        
                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Faq Question?*</label>
                    <input type="text" class="form-control" name="faq_question" required value="<?php echo $faqView->faq_question; ?>">
                     
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Faq Answer*</label>
           
                    <textarea class="form-control" name="faq_answer"><?php echo $faqView->faq_answer; ?></textarea>
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status*</label>
                    <select class="form-control" required name="faq_status">
                    <option value="Active" <?php if($faqView->faq_status=='Active') { echo "selected"; } ?>>Active</option>
                    <option value="Inactive" <?php if($faqView->faq_status=='Inactive') { echo "selected"; } ?>>Inactive</option>
                    </select> 
                  </div>
                  </div>
<div class="col-md-3">
    <div class="form-group">
        <label for="type">Type*</label>
        <select class="form-control" required name="type" id="type">

            <option value="General" 
            <?php if($faqView->faq_status == 'General') echo "selected"; ?>>
            General</option>
            
             <option value="Website Navigation" 
            <?php if($faqView->faq_status == 'Website Navigation') echo "selected"; ?>>
            Website Navigation</option>

            <option value="Verified Batch" 
            <?php if($faqView->faq_status == 'Verified Batch') echo "selected"; ?>>
            Verified Batch</option>

            <option value="Green Batch" 
            <?php if($faqView->faq_status == 'Green Batch') echo "selected"; ?>>
            Green Batch</option>

            <option value="Orange Batch" 
            <?php if($faqView->faq_status == 'Orange Batch') echo "selected"; ?>>
            Orange Batch</option>

            <option value="No Batch Holder" 
            <?php if($faqView->faq_status == 'No Batch Holder') echo "selected"; ?>>
            No Batch Holder</option>

            <option value="Profile Verification" 
            <?php if($faqView->faq_status == 'Profile Verification') echo "selected"; ?>>
            Profile Verification</option>

            <option value="Membership Plans" 
            <?php if($faqView->faq_status == 'Membership Plans') echo "selected"; ?>>
            Membership Plans</option>

            <option value="Refund Policy" 
            <?php if($faqView->faq_status == 'Refund Policy') echo "selected"; ?>>
            Refund Policy</option>

            <option value="Membership Termination" 
            <?php if($faqView->faq_status == 'Membership Termination') echo "selected"; ?>>
            Membership Termination</option>

            <option value="Miscellaneous" 
            <?php if($faqView->faq_status == 'Miscellaneous') echo "selected"; ?>>
            Miscellaneous</option>

        </select> 
    </div>
</div>
                  <div class="col-md-6" style="margin-top: 31px;">
                  <div class="form-group">
                    <input type="submit" name="EditFaq" value="Submit" class="btn btn-primary">
                  </div>
                  </div>

                  </div>
                </div>
                <!-- /.card-body -->
              </form>


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

