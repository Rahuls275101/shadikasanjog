<?php 

use App\Models\Commanmodel;
 $commanmodel = new Commanmodel();
   $session = session();
?> 

<style>
  .image-box {
    width: 150px;
    height: 150px;
    background-color: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    border: 1px dashed #ccc;
  }

  .image-box img {
    max-width: 100%;
    max-height: 100%;
    display: block;
  }
</style>


 <div class="ec-content-wrapper">
                <div class="content">
                    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
                        <div>
                            <h1>Settings</h1>
                            <p class="breadcrumbs"><span><a href="index.html">Home</a></span>
                                <span><i class="mdi mdi-chevron-right"></i></span>Settings
                            </p>
                        </div>
                    </div>
                    	<div class="ec-cat-list card card-default tab2-card">
                    
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
                            <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="top-profile-tab"
                                        data-bs-toggle="tab" href="#top-profile" role="tab" aria-controls="top-profile"
                                        aria-selected="true"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-user me-2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>Profile</a>
                                </li>
                                	<?php if($session->admin_type == 'Supar Admin') { ?>
                                <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                        href="#top-contact" role="tab" aria-controls="top-contact"
                                        aria-selected="false"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-settings me-2">
                                            <circle cx="12" cy="12" r="3"></circle>
                                            <path
                                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                            </path>
                                        </svg>Website Settings</a>
                                </li>
                                
                                <?php } ?>
                            </ul>
                            <div class="tab-content" id="top-tabContent">
                                <div class="tab-pane fade active show" id="top-profile" role="tabpanel"
                                    aria-labelledby="top-profile-tab">
                                    <h5 class="f-w-600">Profile</h5>
                                     <form role="form" method="POST" action="<?php echo base_url('admin/password_manage_process'); ?>" enctype="multipart/form-data">
                <div class="card-body">
                   <div class="row">
<?php $userdetails =  $commanmodel->get_single_query('admin',array('id' =>  $session->id));  ?>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Full Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $userdetails->name; ?>" required>
                  </div>
                  </div>
                  
                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputFile">Profile Image*</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file"  class="form-control" id="exampleInputFile" name="profile_images">
                        <input type="hidden" name="edit_profile_images" id="edit_profile_images" value="<?php echo $userdetails->admin_image; ?>">
                      </div>
                    </div>
                  </div>
                  </div>
                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email Address /Username</label>
                    <input type="text" class="form-control" name="login_email" value="<?php echo $userdetails->email; ?>" required>
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="text" class="form-control" name="password" value="" >
                  </div>
                  </div>

                  <div class="col-md-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Address</label>
                    <input type="text" class="form-control" name="admin_address" value="<?php echo $userdetails->admin_address; ?>" required>
                  </div>
                  </div>

                <div style="text-align: center;">
                  <input type="submit" name="pageUpdated" value="Submit" class="btn btn-primary">
                </div>

                </div>
                <!-- /.card-body -->
                </div>
              </form>
                                </div>
                                <div class="tab-pane fade" id="top-contact" role="tabpanel"
                                    aria-labelledby="contact-top-tab">
                                    <div class="account-setting">
                                        <h5 class="f-w-600">Website Settings</h5>
<form role="form" method="POST" action="<?php echo base_url('admin/address_manage_process'); ?>" enctype="multipart/form-data">
                <div class="card-body">
                   <div class="row">


<div class="col-md-4">
  <div class="form-group">
    <label for="exampleInputFile">Header logo *</label>
    <div class="input-group">
      <div class="custom-file">
        <!-- Hidden file input -->
        <input type="file" class="header-logo-input" name="header_logo" onchange="previewImage(event)" style="display: none;">
          <input type="hidden" name="edit_header_logo" id="edit_header_logo" value="<?php echo $addressView->header_logo; ?>">
        
        <!-- Box to display the image, which is clickable to change the image -->
        <div class="image-box cat-thumb" onclick="triggerFileInput(event)">
          <img class="header-logo-preview"  src="<?php echo base_url('assets/img/'); ?>/<?php echo $addressView->header_logo; ?>" alt="Header Logo" class="cat-thumb">
        </div>
      </div>
    </div>
  </div>
</div>


<div class="col-md-4">
  <div class="form-group">
    <label for="exampleInputFile">Footer logo  *</label>
    <div class="input-group">
      <div class="custom-file">
        <!-- Hidden file input -->
        <input type="file" class="header-logo-input" name="footer_logo" onchange="previewImage(event)" style="display: none;">
         <input type="hidden" name="edit_footer_logo" id="edit_footer_logo" value="<?php echo $addressView->footer_logo; ?>">
        
        <!-- Box to display the image, which is clickable to change the image -->
        <div class="image-box cat-thumb" onclick="triggerFileInput(event)">
          <img class="header-logo-preview"  src="<?php echo base_url('assets/img/'); ?>/<?php echo $addressView->footer_logo; ?>" alt="Header Logo" class="cat-thumb">
          
        </div>
      </div>
    </div>
  </div>
</div>
        
                  
                  
               
                  
                   <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Web Name</label>
                    <input type="text" class="form-control" name="web_name" value="<?php echo $addressView->web_name; ?>">
                  </div>
                  </div>
                  
                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email Address</label>
                    <input type="text" class="form-control" name="email" value="<?php echo $addressView->email; ?>">
                  </div>
                  </div>

                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Phone One</label>
                    <input type="text" class="form-control" name="phone_one" value="<?php echo $addressView->phone_one; ?>">
                  </div>
                  </div>

                  <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Phone Two</label>
                    <input type="text" class="form-control" name="phone_two" value="<?php echo $addressView->phone_two; ?>">
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Address</label>
                    <input type="text" class="form-control" name="address" value="<?php echo $addressView->address; ?>">
                  </div>
                  </div>
                      <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Address Two</label>
                    <input type="text" class="form-control" name="address_tow" value="<?php echo $addressView->address_tow; ?>">
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Copy Right</label>
                    <input type="text" class="form-control" name="copyright" value="<?php echo $addressView->copyright; ?>">
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Facebook Page</label>
                    <input type="text" class="form-control" name="facebook" value="<?php echo $addressView->facebook; ?>">
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Twitter Page</label>
                    <input type="text" class="form-control" name="twitter" value="<?php echo $addressView->twitter; ?>">
                  </div>
                  </div>

                   <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">LinkedIn Page</label>
                    <input type="text" class="form-control" name="linkedin" value="<?php echo $addressView->linkedin; ?>">
                  </div>
                  </div>

                   <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Instagram Page</label>
                    <input type="text" class="form-control" name="instagram" value="<?php echo $addressView->instagram; ?>">
                  </div>
                  </div>

           
                  
                  
                  </div>

             

                </div>
                <!-- /.card-body -->
                <h5 class="f-w-600">Commission</h5>
                    <div class="card-body">
                   <div class="row">
                        <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Franchise Direct Commission (%)</label>
                    <input type="text" class="form-control" name="franchise_direct_commission" value="<?php echo $addressView->franchise_direct_commission; ?>">
                  </div>
                  </div>

                  <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Franchise Indirect Commission (%)</label>
                    <input type="text" class="form-control" name="franchise_indirect_commission" value="<?php echo $addressView->franchise_indirect_commission; ?>">
                  </div>
                  </div>

                   <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Promoter Direct commission (%)</label>
                    <input type="text" class="form-control" name="promoter_direct_commission" value="<?php echo $addressView->promoter_direct_commission; ?>">
                  </div>
                  </div>

                   <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Promoter Indirect Commission (%)</label>
                    <input type="text" class="form-control" name="promoter_indirect_commission" value="<?php echo $addressView->promoter_indirect_commission; ?>">
                  </div>
                  </div>

                         </div>

                <div style="text-align: center;">
                  <input type="submit" name="pageUpdated" value="Submit" class="btn btn-primary">
                </div>

                </div>
              </form>
                                    </div>
                                    
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End Content -->
            </div> <!-- End Content Wrapper -->
            
            
           <script>
  // Function to trigger the file input when the image box is clicked
  function triggerFileInput(event) {
    // Trigger the file input click event
    var input = event.target.closest('.input-group').querySelector('.header-logo-input');
    input.click();
  }

  // Function to preview the selected image
  function previewImage(event) {
    var input = event.target;
    var preview = input.closest('.input-group').querySelector('.header-logo-preview');
    
    var reader = new FileReader();
    reader.onload = function() {
      preview.src = reader.result; // Set the preview image
      preview.style.display = 'block'; // Make sure the image is displayed
    };
    reader.readAsDataURL(input.files[0]);
  }
</script>
