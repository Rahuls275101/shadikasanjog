<?php 
use App\Models\Commanmodel;
$commanmodel = new Commanmodel();
$session = session();
$usersession = $session->get('loggedin');

$userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
$education_data = $commanmodel->all_multiple_query_order_by('user_education',array('user_id'=> $usersession['user_id']),'id','ASC');
$user_work_experience = $commanmodel->all_multiple_query_order_by('user_work_experience',array('user_id'=> $usersession['user_id']),'id','ASC');
$family_members = $commanmodel->all_multiple_query_order_by('user_family_members',array('user_id'=> $usersession['user_id']),'id','ASC');
$family_background = $commanmodel->get_single_query('user_family_background',array('user_id'=> $usersession['user_id']));
$partner_preferences = $commanmodel->get_single_query('user_partner_preferences',array('user_id'=> $usersession['user_id']));
$user_photos = $commanmodel->all_multiple_query_order_by('user_photos', array('user_id' => $usersession['user_id']), 'id', 'DESC');
?>

<?php 
$hobbies = isset($userdata->hobbies) ? explode(',', $userdata->hobbies) : [];
$hobbies = array_map('trim', $hobbies);
?> 

<style> 
.photo-actions {
  position: absolute;
  z-index: 99;
  right: 10px;
  top: 10px;
}
    .profile-img {
        width: 100%;
        height: 417px;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 15px;
    }
    .other-input {
        margin-top: 10px;
        display: none;
    }
  .carousel-item {
    position: relative;
}

.carousel-item .btn {
    pointer-events: auto;
}
 .carousel-control-next, .carousel-control-prev {
  position: absolute;
  top: 0;
  bottom: 0;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 15%;
  padding: 0;
  color: #fff;
  text-align: center;
  background: 0 0;
  border: 0;
  opacity: .5;
  transition: opacity .15s ease;
  height: 50%;
  top: 25%;
} 




.carousel-control-next-icon {
    background-color: #cc2f6b;
    border-radius: 50%;
    padding: 15px;
}



.carousel-control-prev-icon {
    background-color: #cc2f6b;
    border-radius: 50%;
    padding: 15px;
}



/* TABLET VIEW (768px – 1024px) */
@media (min-width: 768px) and (max-width: 1024px) {

    /* Carousel Full Width */
    #carouselExample,
    .carousel-inner,
    .carousel-item {
        width: 100%;
    }
    
    h2.db-tit span{
        width: 696px;
    margin-top: 20px;
    }
    
    
    
    
    
    
    
    
    /* Tablet + sab screens ke liye use kar sakte ho */
.slider-box {
    width: 696px;
    position: relative;
    left: 87%;
    /*right: 50%;*/
    margin-left: -50vw;
    margin-right: -50vw;
}

form{
    width: 696px;
}


.list .list-group{
    width: 696px;
}

.card-photo{
    width: 696px;
    position: relative;
}

.text-end{
    left: 50%;
}

/* Carousel bhi full width */
#carouselExample {
    width: 100%;
}

    /* Remove unwanted spacing */
    .carousel-inner {
        margin: 0;
        padding: 0;
    }

    /* Image Full Width */
    .profile-img {
    width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;   /* ✅ center fix */
}

    /* Image container full width */
    .carousel-item .position-relative {
        width: 100%;
        margin: 0;
        padding: 0;
        position: relative;
        overflow: hidden;
        border-radius: 0;   /* full edge to edge */
        background: #000;
    }
    
    .carousel-item {
    text-align: center;
}

    /* Edit & Delete Buttons */
    .photo-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        gap: 5px;
        z-index: 10;
    }

    .photo-actions button {
        padding: 5px 7px;
        font-size: 11px;
    }

    /* Carousel Controls */
    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
        height: 40px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        opacity: 1;
    }

    /* Proper edge alignment */
    .carousel-control-prev {
        left: 10px;
    }

    .carousel-control-next {
        right: 10px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 16px;
        height: 16px;
    }
}








/* MOBILE VIEW ONLY */
@media (max-width: 767px) {

    .para-text {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 10px;
    }

    /* Inner line (I am + input + text) */
    .para-text > div {
        flex-direction: column !important;
        align-items: flex-start !important;
        width: 100%;
        gap: 6px;
    }

    /* Input full width */
    .para-text input.form-control {
        width: 100% !important;
    }

    /* Dropdown full width */
    .para-text span,
    .para-text .input-box,
    .para-text .form-select {
        width: 100% !important;
    }

    /* Text wrap allow */
    .para-text strong {
        white-space: normal !important;
    }

    /* Other input spacing */
    .other-input {
        width: 100%;
        margin-top: 8px;
    }
}



.select2-container--default .select2-selection--multiple:after {
    content: "";
    width: 8px;
    height: 8px;
    border-right: 2px solid #555;
    border-bottom: 2px solid #555;
    transform: rotate(45deg);
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%) rotate(45deg);
}
.select2-selection--multiple {
    position: relative;
    padding-right: 25px !important;
}
</style>

<div class="modal fade" id="editPhotoModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('update-profile-photo') ?>" enctype="multipart/form-data">
            <input type="hidden" name="photo_id" id="photo_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Profile Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="file" name="photo" class="form-control" >
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addPhotoModal">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('add-profile-photo') ?>" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add Profile Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="file" name="photo" class="form-control" >
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>

<section class="mainside mt-5">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-3">
                        <?php echo view('frontend/dashboard/sidebar'); ?>
                    </div>
                    <div class="col-xl-9">
                        <div class="col-md-8 col-xl-12"> 
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 db-sec-com">
                                    <h2 class="db-tit"><span>Profile Update/Edit <a href="<?= base_url('profile-details') ?>/<?php echo $userdata->user_id ?? ''; ?>"> <button class="btn profile-priv">Profile Preview</button></a></span></h2>
                                 <div class="slider-box">
    <div id="carouselExample" class="carousel slide carousel-fade">

        <div class="carousel-inner">

            <?php foreach ($user_photos as $index => $photo): ?>

            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                
                <div class="position-relative">
                     <div class="photo-actions">
                        
                        
                       <!-- EDIT -->
<button type="button"
    class="btn btn-primary btn-sm edit-photo-btn position-absolute"
    style="top:10px; right:40px; z-index:9999;"
    data-id="<?= $photo->id ?>">
    <i class="fa fa-edit"></i>
</button>

<!-- DELETE -->
<button type="button"
    class="btn btn-danger btn-sm delete-btn position-absolute"
    style="top:10px; right:10px; z-index:9999;"
    data-id="<?= $photo->id ?>">
    <i class="fa fa-trash"></i>
</button>

                    </div>

                    <!-- IMAGE -->
                    <img src="<?= base_url('assets/uploads/' . $photo->photo_path) ?>"
                         class="d-block w-100 profile-img"
                         alt="Profile Photo">

                    <!-- ACTION BUTTONS OVERLAY -->
                   

                </div>

            </div>

            <?php endforeach; ?>

        </div>

        <button class="carousel-control-prev" type="button"
                data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button"
                data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>
</div>
<div class="text-end mt-3 mb-2 position-relative">
                                                <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addPhotoModal">
                                                    <i class="fa fa-plus me-1"></i> Add Photo
                                                </button>
                                            </div>


                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-2">
                                        <div class="card-photo">
                                            <div class="card rounded-0">
                                                <div class="card-body profile-photo p-0 border-0">
                                                    <div class="bg-transparent">
                                                        <div class="d-flex justify-content-center img-p">
                                                            <img id="selectedImage" src="<?php echo $commanmodel->profile_image($usersession['user_id']); ?>" alt="example placeholder" />
                                                        </div>
                                                        <h1><?php echo $userdata->user_name ?? ''; ?> <?php echo $userdata->user_last_name ?? ''; ?></h1>
                                                        <div class="d-flex justify-content-center">
                                                            <div data-mdb-ripple-init class="btn upload">
                                                                <label class="form-label text-white m-1" for="customFile1">Add/Upload</label>
                                                                <input type="file" class="form-control d-none" id="customFile1" onchange="uploadProfileImage(event)" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-10">
                                        <div class="profile-form">
                                            <div class="col-xl-12">
                                                <div class="row">
                                                    <div class="col-xl-12 mt-2 mb-2">
                                                        <?php if (session()->getFlashdata('success')): ?>
                                                            <div class="alert alert-success">
                                                                <?= session()->getFlashdata('success'); ?>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (session()->getFlashdata('error')): ?>
                                                            <div class="alert alert-danger">
                                                                <?= session()->getFlashdata('error'); ?>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- ==================== PROFILE VERIFICATION FORM ==================== -->
                                                        
                                              
                                                        <form method="post" action="<?php echo base_url('profile-verification'); ?>" enctype="multipart/form-data">
                                                            <div class="row">
                                                                <div class="para-text d-flex align-items-center justify-content-between">
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <div><strong style="white-space: nowrap;">I am</strong></div>
                                                                        <input type="text" class="form-control" name="introducer_name" placeholder="Name of person managing profile" aria-label="Username" aria-describedby="basic-addon1" value="<?php echo $userdata->introducer_name ?? ''; ?>" style="width:300px;" />
                                                                        <div><strong style="white-space: nowrap;">looking for a match for</strong></div>
                                                                    
                                                                    <span>
                                                                        <div class="col-xl-12 input-box">
                                                                            <select class="form-select other-enabled" name="match_for" id="match_for" data-other-div="match_for_other_div" data-other-name="match_for_other" onchange="toggleOther(this, 'match_for_other_div', 'match_for_other')">
                                                                                <option value="myself" <?php echo ($userdata->match_for ?? '') == 'myself' ? 'selected' : ''; ?>>Myself</option>
                                                                                <option value="son" <?php echo ($userdata->match_for ?? '') == 'son' ? 'selected' : ''; ?>>My Son</option>
                                                                                <option value="daughter" <?php echo ($userdata->match_for ?? '') == 'daughter' ? 'selected' : ''; ?>>My Daughter</option>
                                                                                <option value="brother" <?php echo ($userdata->match_for ?? '') == 'brother' ? 'selected' : ''; ?>>My Brother</option>
                                                                                <option value="sister" <?php echo ($userdata->match_for ?? '') == 'sister' ? 'selected' : ''; ?>>My Sister</option>
                                                                                <option value="nephew" <?php echo ($userdata->match_for ?? '') == 'nephew' ? 'selected' : ''; ?>>My Nephew</option>
                                                                                <option value="niece" <?php echo ($userdata->match_for ?? '') == 'niece' ? 'selected' : ''; ?>>My Niece</option>
                                                                                <option value="others" <?php echo ($userdata->match_for ?? '') == 'others' ? 'selected' : ''; ?>>Other</option>
                                                                            </select>
                                                                            <div id="match_for_other_div" class="other-input" style="display: <?php echo ($userdata->match_for ?? '') == 'others' ? 'block' : 'none'; ?>; margin-top:10px;">
                                                                                <input type="text" class="form-control" name="match_for_other" value="<?php echo $userdata->match_for_other ?? ''; ?>" placeholder="Please specify">
                                                                            </div>
                                                                        </div>
                                                                    </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                          
                                                            <div class="row">
                                                                <div class="res">
                                                                <div class="col-xl-12">
                                                                    <h3 class="headtext">Profile Verification  <?php if(!empty($userdata->documents)) { echo "(Verification already submitted successfully)"; } ?></h3>
                                                                </div>

                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <label class="lb" for="first_name">Full Name:</label>
                                                                    <input type="text" class="form-control" id="verification_user_name" name="verification_user_name" value="<?php echo $userdata->verification_user_name ?? ''; ?>" placeholder="Enter your full name" required>
                                                                </div>

                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label for="I am Rank" class="form-label">Relationship :</label>
                                                                        </div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="verification_relationship" id="verification_relationship" data-other-div="verification_rel_other_div" data-other-name="verification_relationship_other" onchange="toggleOther(this, 'verification_rel_other_div', 'verification_relationship_other')">
                                                                   
                                                                                
                                                                                
                                                                                          <option value="myself" <?php echo ($userdata->verification_relationship ?? '') == 'myself' ? 'selected' : ''; ?>>Myself</option>
                                                                                <option value="Father" <?php echo ($userdata->verification_relationship ?? '') == 'Father' ? 'selected' : ''; ?>>Father</option>
                                                                                <option value="Mother" <?php echo ($userdata->verification_relationship ?? '') == 'Mother' ? 'selected' : ''; ?>>Mother</option>
                                                                                <option value="Uncle" <?php echo ($userdata->verification_relationship ?? '') == 'Uncle' ? 'selected' : ''; ?>>Uncle</option>
                                                                                <option value="Aunty" <?php echo ($userdata->verification_relationship ?? '') == 'Aunty' ? 'selected' : ''; ?>>Aunty</option>
                                                                                <option value="Brother" <?php echo ($userdata->verification_relationship ?? '') == 'Brother' ? 'selected' : ''; ?>>Brother</option>
                                                                                <option value="Sister" <?php echo ($userdata->verification_relationship ?? '') == 'Sister' ? 'selected' : ''; ?>>Sister</option>
                                                                            
                                                                                <option value="others" <?php echo ($userdata->verification_relationship ?? '') == 'others' ? 'selected' : ''; ?>>Other</option>
                                                                                
                                                                            </select>
                                                                            <div id="verification_rel_other_div" class="other-input" style="display: <?php echo ($userdata->verification_relationship ?? '') == 'others' ? 'block' : 'none'; ?>; margin-top:10px;">
                                                                                <input type="text" class="form-control" name="verification_relationship_other" value="<?php echo $userdata->verification_relationship_other ?? ''; ?>" placeholder="Please specify relationship">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-xl-12 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label for="I am Rank" class="form-label">Document Upload :</label>
                                                                        </div>
                                                                        <div class="col-xl-6 select-upload select-input">
                                                                            <select class="form-select mb-2 other-enabled" name="document_type" id="document_type" data-other-div="document_type_other_div" data-other-name="document_type_other" onchange="toggleOther(this, 'document_type_other_div', 'document_type_other')">
                                                                                <option value="">Select Document Type</option>
                                                                                <option value="aadhar" <?php echo ($userdata->document_type ?? '') == 'aadhar' ? 'selected' : ''; ?>>Aadhar</option>
                                                                                <option value="electricity_bill" <?php echo ($userdata->document_type ?? '') == 'electricity_bill' ? 'selected' : ''; ?>>Electricity Bill</option>
                                                                                <option value="driving_license" <?php echo ($userdata->document_type ?? '') == 'driving_license' ? 'selected' : ''; ?>>Driving License</option>
                                                                                <option value="passport" <?php echo ($userdata->document_type ?? '') == 'passport' ? 'selected' : ''; ?>>Passport</option>
                                                                                <option value="others" <?php echo ($userdata->document_type ?? '') == 'others' ? 'selected' : ''; ?>>Other Govt ID</option>
                                                                            </select>
                                                                            <div id="document_type_other_div" class="other-input" style="display: <?php echo ($userdata->document_type ?? '') == 'others' ? 'block' : 'none'; ?>; margin-bottom:10px;">
                                                                                <input type="text" class="form-control mb-2" name="document_type_other" value="<?php echo $userdata->document_type_other ?? ''; ?>" placeholder="Please specify document type">
                                                                            </div>
                                                                            <input class="form-control" type="file" name="verification_documents[]" multiple>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-xl-12 d-flex justify-content-end">
                                                                   
                                                                    <div class="bttn-all">
                                                                        <button type="submit" class="btn-priv btn btn-success">Save and Submit</button>
                                                                    </div>
                                                                  
                                                                </div>
                                                                
                                                                </div>
                                                            </div>
                                                        </form>
                                                       

                                                        <div class="row">
                                                            <hr />
                                                            <div class="col-xl-12">
                                                                <h4 class="headtext">Note:-</h4>
                                                            </div>
                                                            <div class="list">
                                                                <ul class="list-group border-0 list-group-flush">
                                                                    <li class="list-group-item border-0 bg-transparent list-circle">
                                                                        Only verified profiles will be considered for
                                                                        <strong>Green and Orange Batch</strong>
                                                                        (learn more in About Us). The name must match with the name in the uploaded ID.
                                                                    </li>
                                                                    <li class="list-group-item border-0 bg-transparent list-circle">
                                                                        Verification details should be of the to be bride/ bride groom or the relative above initially
                                                                        introducing the profile. For those seeking the
                                                                        <strong>Green Batch</strong> verification details should be of the person who qualifies for the said batch.
                                                                    </li>
                                                                    <li class="list-group-item border-0 bg-transparent list-circle">
                                                                        The details of verification will not appear on your profile page seen by others.
                                                                    </li>
                                                                    <li class="list-group-item border-0 bg-transparent list-circle">
                                                                        Verification will be carried out within 48 hours and Blue Verified sticker will appear in the profile.
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>

                                                        <!-- ==================== PERSONAL DETAILS FORM ==================== -->
                                                        <form method="post" action="<?= base_url('save-personal-details'); ?>" enctype="multipart/form-data">
                                                            <div class="row">
                                                                <hr />
                                                                <div class="col-xl-12"><h4 class="headtext">Personal Details:</h4></div>

                                                                <!-- First Name -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>First Name *</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="user_name" value="<?= $userdata->user_name ?? '' ?>" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Last Name -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Last Name </label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="user_last_name" value="<?= $userdata->user_last_name ?? '' ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <style>
                                                                    .input-group .form-select {
    border-radius: 6px 0 0 6px;
}

.input-group .form-control {
    border-radius: 0 6px 6px 0;
}

.label-text label {
    font-weight: 600;
}
                                                                </style>
                                                                
                                                                   <!-- Last Name -->
                                                      <div class="col-xl-6 mt-2 mb-2">
    <div class="row align-items-center">
        <div class="col-md-4 label-text">
            <label for="user_phone" class="mb-0">Phone Number</label>
        </div>

        <div class="col-md-8 input-box">
            <div class="input-group">
               <select name="country_code" id="country_code" class="form-select" style="max-width:82px;" required>
    <option value="+91"  <?= ($userdata->country_code ?? '') == '+91'  ? 'selected' : '' ?>>+91 India</option>
    <option value="+1"   <?= ($userdata->country_code ?? '') == '+1'   ? 'selected' : '' ?>>+1 USA</option>
    <option value="+44"  <?= ($userdata->country_code ?? '') == '+44'  ? 'selected' : '' ?>>+44 UK</option>
    <option value="+61"  <?= ($userdata->country_code ?? '') == '+61'  ? 'selected' : '' ?>>+61 Australia</option>
    <option value="+65"  <?= ($userdata->country_code ?? '') == '+65'  ? 'selected' : '' ?>>+65 Singapore</option>
    <option value="+971" <?= ($userdata->country_code ?? '') == '+971' ? 'selected' : '' ?>>+971 UAE</option>
    <option value="+966" <?= ($userdata->country_code ?? '') == '+966' ? 'selected' : '' ?>>+966 Saudi Arabia</option>
    <option value="+974" <?= ($userdata->country_code ?? '') == '+974' ? 'selected' : '' ?>>+974 Qatar</option>
    <option value="+968" <?= ($userdata->country_code ?? '') == '+968' ? 'selected' : '' ?>>+968 Oman</option>
    <option value="+973" <?= ($userdata->country_code ?? '') == '+973' ? 'selected' : '' ?>>+973 Bahrain</option>
    <option value="+965" <?= ($userdata->country_code ?? '') == '+965' ? 'selected' : '' ?>>+965 Kuwait</option>
    <option value="+92"  <?= ($userdata->country_code ?? '') == '+92'  ? 'selected' : '' ?>>+92 Pakistan</option>
    <option value="+94"  <?= ($userdata->country_code ?? '') == '+94'  ? 'selected' : '' ?>>+94 Sri Lanka</option>
    <option value="+880" <?= ($userdata->country_code ?? '') == '+880' ? 'selected' : '' ?>>+880 Bangladesh</option>
    <option value="+977" <?= ($userdata->country_code ?? '') == '+977' ? 'selected' : '' ?>>+977 Nepal</option>
    <option value="+60"  <?= ($userdata->country_code ?? '') == '+60'  ? 'selected' : '' ?>>+60 Malaysia</option>
    <option value="+64"  <?= ($userdata->country_code ?? '') == '+64'  ? 'selected' : '' ?>>+64 New Zealand</option>
    <option value="+27"  <?= ($userdata->country_code ?? '') == '+27'  ? 'selected' : '' ?>>+27 South Africa</option>
    <option value="+33"  <?= ($userdata->country_code ?? '') == '+33'  ? 'selected' : '' ?>>+33 France</option>
    <option value="+49"  <?= ($userdata->country_code ?? '') == '+49'  ? 'selected' : '' ?>>+49 Germany</option>
    <option value="+39"  <?= ($userdata->country_code ?? '') == '+39'  ? 'selected' : '' ?>>+39 Italy</option>
    <option value="+34"  <?= ($userdata->country_code ?? '') == '+34'  ? 'selected' : '' ?>>+34 Spain</option>
</select>

                <input type="text"
                       class="form-control"
                       id="user_phone"
                       name="user_phone"
                       placeholder="Enter Phone Number"
                       value="<?= $userdata->user_phone ?? '' ?>">
            </div>
        </div>
    </div>
</div>

                                                                <!-- Profession with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Profession *</label></div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="profession" id="profession_select" data-other-div="profession_other_div" data-other-name="other_profession" onchange="toggleOther(this, 'profession_other_div', 'other_profession')" required>
                                                                                <option value="">Select</option>
                                                                                <?php foreach($profession_categories as $p){ ?>
                                                                                    <option value="<?= $p->id ?>" <?= ($userdata->profession ?? '')==$p->id?'selected':'' ?>><?= $p->category_name ?></option>
                                                                                <?php } ?>
                                                                                <option value="other" <?= ($userdata->profession ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="profession_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($userdata->profession ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Profession</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_profession" value="<?= $userdata->other_profession ?? '' ?>" placeholder="Enter your profession">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Marital Status with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Marital Status *</label></div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="marital_status" id="marital_status_select" data-other-div="marital_status_other_div" data-other-name="other_marital_status" onchange="toggleOther(this, 'marital_status_other_div', 'other_marital_status')" required>
                                                                                <?php $marr=['Never Married','Divorced','Widow','Widower','Awaiting Divorce Finalisation','Marriage Annulled']; ?>
                                                                                <option value="">Select</option>
                                                                                <?php foreach($marr as $m){ ?>
                                                                                    <option value="<?= $m ?>" <?= ($userdata->marital_status??'')==$m?'selected':'' ?>><?= $m ?></option>
                                                                                <?php } ?>
                                                                                <option value="other" <?= ($userdata->marital_status ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="marital_status_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($userdata->marital_status ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Marital Status</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_marital_status" value="<?= $userdata->other_marital_status ?? '' ?>" placeholder="Enter marital status">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Height -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Height *</label></div>
                                                                        <div class="col-xl-3">
                                                                            <select class="form-select" name="height_feet">
                                                                                <?php for($f=4;$f<=7;$f++){ ?>
                                                                                    <option value="<?= $f ?>" <?= ($userdata->height??'')==$f?'selected':'' ?>><?= $f ?> ft</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-xl-3">
                                                                            <select class="form-select" name="height_inch">
                                                                                <?php for($i=0;$i<=11;$i++){ ?>
                                                                                    <option value="<?= $i ?>" <?= ($userdata->height_inch??'')==$i?'selected':'' ?>><?= $i ?> in</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- DOB, Time, Place -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Date of Birth *</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="date" class="form-control" name="date_of_birth" value="<?= $userdata->date_of_birth ?? '' ?>" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Time of Birth</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="time" class="form-control" name="time_of_birth" value="<?= $userdata->time_of_birth ?? '' ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Place of Birth</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="place_of_birth" value="<?= $userdata->place_of_birth ?? '' ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Religion with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Religion *</label></div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="religions" id="religions" data-other-div="religion_other_div" data-other-name="other_religion" onchange="toggleOther(this, 'religion_other_div', 'other_religion')" required>
                                                                                <option value="">Select</option>
                                                                                <?php foreach($religions as $r){ ?>
                                                                                    <option value="<?= $r->id ?>" <?= ($userdata->religion_id??'')==$r->id?'selected':'' ?>><?= $r->name ?></option>
                                                                                <?php } ?>
                                                                                <option value="other" <?= ($userdata->religion_id ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="religion_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($userdata->religion_id ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Religion</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_religion" value="<?= $userdata->other_religion ?? '' ?>" placeholder="Enter your religion">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Caste with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Caste</label></div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="castes" id="castes" data-other-div="caste_other_div" data-other-name="other_caste" onchange="toggleOther(this, 'caste_other_div', 'other_caste')">
                                                                                <option value="">Select</option>
                                                                                <?php foreach($castes as $c){ ?>
                                                                                    <option value="<?= $c->id ?>" <?= ($userdata->caste_id??'')==$c->id?'selected':'' ?>><?= $c->name ?></option>
                                                                                <?php } ?>
                                                                                <option value="other" <?= ($userdata->caste_id ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="caste_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($userdata->caste_id ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Caste</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_caste" value="<?= $userdata->other_caste ?? '' ?>" placeholder="Enter your caste">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Mother Tongue with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Mother Tongue</label></div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="mother_tongue" id="mother_tongue_select" data-other-div="mother_tongue_other_div" data-other-name="other_mother_tongue" onchange="toggleOther(this, 'mother_tongue_other_div', 'other_mother_tongue')">
                                                                                <option value="">Select</option>
                                                                                <?php $langs=['Hindi','English','Punjabi','Kumaoni','Garhwali','Gujarati','Marathi','Kannada','Tamil','Malayalam','Telugu','Oriya','Bengali','Assamese']; ?>
                                                                                <?php foreach($langs as $l){ ?>
                                                                                    <option value="<?= $l ?>" <?= ($userdata->mother_tongue??'')==$l?'selected':'' ?>><?= $l ?></option>
                                                                                <?php } ?>
                                                                                <option value="other" <?= ($userdata->mother_tongue ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="mother_tongue_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($userdata->mother_tongue ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Mother Tongue</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_mother_tongue" value="<?= $userdata->other_mother_tongue ?? '' ?>" placeholder="Enter mother tongue">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Other Languages --> 
                                                                
                                                               <div class="col-xl-6 mt-2 mb-2">
  <div class="row">
    
    <div class="col-xl-6 label-text">
      <label>Other Languages Spoken</label>
    </div>

    <div class="col-xl-6 select-input">
      <?php 
        $selected_languages = !empty($userdata->other_languages) 
          ? explode(',', $userdata->other_languages) 
          : []; 
      ?>
      
      <select class="form-select select2" name="other_languages[]" multiple>
        <?php foreach($langs as $l){ ?>
          <option value="<?= $l ?>" <?= in_array($l, $selected_languages) ? 'selected' : '' ?>>
            <?= $l ?>
          </option>
        <?php } ?>
        
        <option value="other" <?= in_array('other', $selected_languages) ? 'selected' : '' ?>>
          Other
        </option>
      </select>
    </div>

  </div>
</div>


                                                                <!-- Family Home & Current Residence -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Family Home</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="family_home" value="<?= $userdata->family_home ?? '' ?>" placeholder="Mention Place">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Current Home *</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="current_residence" value="<?= $userdata->current_residence ?? '' ?>" required placeholder="Mention Place">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Manglik Status with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Manglik Status</label></div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="manglik_status" id="manglik_status_select" data-other-div="manglik_status_other_div" data-other-name="other_manglik_status" onchange="toggleOther(this, 'manglik_status_other_div', 'other_manglik_status')">
                                                                                <option value="Manglik" <?= ($userdata->manglik_status??'')=='Manglik'?'selected':'' ?>>Manglik</option>
                                                                                <option value="Non Manglik" <?= ($userdata->manglik_status??'')=='Non Manglik'?'selected':'' ?>>Non Manglik</option>
                                                                                <option value="Anshik" <?= ($userdata->manglik_status??'')=='Anshik'?'selected':'' ?>>Anshik</option>
                                                                                <option value="Non Believer" <?= ($userdata->manglik_status??'')=='Non Believer'?'selected':'' ?>>Non Believer</option>
                                                                               
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="manglik_status_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($userdata->manglik_status ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Manglik Status</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_manglik_status" value="<?= $userdata->other_manglik_status ?? '' ?>" placeholder="Enter manglik status">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Living in with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Living in :</label></div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="living_in" id="living_in_select" data-other-div="living_in_other_div" data-other-name="other_living_in" onchange="toggleOther(this, 'living_in_other_div', 'other_living_in')">
                                                                                <option value="India" <?= ($userdata->living_in ?? '') == 'India' ? 'selected' : '' ?>>India</option>
                                                                                <option value="Overseas" <?= ($userdata->living_in ?? '') == 'Overseas' ? 'selected' : '' ?>>Overseas</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="living_in_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($userdata->living_in ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Location</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_living_in" value="<?= $userdata->other_living_in ?? '' ?>" placeholder="Enter location">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Kundali Matching -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Kundali Matching</label></div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select" name="kundali_matching">
                                                                                <option value="Must" <?= ($userdata->kundali_matching??'')=='Must'?'selected':'' ?>>Must</option>
                                                                                <option value="Optional" <?= ($userdata->kundali_matching??'')=='Optional'?'selected':'' ?>>Optional</option>
                                                                                <option value="Not Required" <?= ($userdata->kundali_matching??'')=='Not Required'?'selected':'' ?>>Not Required</option>
                                                                                <option value="Non Believer" <?= ($userdata->kundali_matching??'')=='Non Believer'?'selected':'' ?>>Non Believer</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                             


                                                                <div class="col-md-xl-12 d-flex justify-content-end">
                                                                
                                                                    <div class="bttn-all">
                                                                        <button type="submit" class="btn-priv btn btn-success">Save and Submit</button>
                                                                    </div>
                                                                   
                                                                </div>
                                                            </div>
                                                        </form>

                                                        <!-- ==================== EDUCATION DETAILS FORM ==================== -->
                                                        <form method="post" action="<?= base_url('save-education-details'); ?>">
                                                            <div class="row">
                                                                <hr />
                                                                <div class="col-xl-12">
                                                                    <h4 class="headtext">Educational Qualifications: Highest/ Most important first</h4>
                                                                </div>

                                                                <div id="education-container">
                                                                    <?php if(!empty($education_data)): ?>
                                                                        <?php foreach($education_data as $index => $edu): ?>
                                                                            <div class="education-entry card mb-3" data-edu-index="<?= $index ?>">
                                                                                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                                                                    <h6 class="mb-0">Education #<?= $index + 1 ?></h6>
                                                                                    <?php if($index > 0): ?>
                                                                                        <button type="button" class="btn btn-sm btn-danger remove-education">
                                                                                            <i class="fas fa-times"></i> Remove
                                                                                        </button>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <input type="hidden" name="education_id[]" value="<?= $edu->id ?>">
                                                                                    <div class="row">
                                                                                        <!-- Level with Other -->
                                                                                        <div class="col-xl-6 mt-2 mb-2">
                                                                                            <div class="row">
                                                                                                <div class="col-xl-6 label-text">
                                                                                                    <label class="form-label">Level :</label>
                                                                                                </div>
                                                                                                <div class="col-xl-6 select-input">
                                                                                                    
                                                                                                    <select class="form-select other-enabled" name="education_level[]" id="edu_level_<?= $index ?>" data-other-div="other_education_level_div_<?= $index ?>" data-other-name="other_education_level[]" onchange="toggleOtherDynamic(this, <?= $index ?>, 'education_level', 'other')" required>
                                                                                                        <option value="">Select Level</option>
                                                                                                        <?php foreach($education_qualifications as $education): ?>
                                                                                                            <option value="<?= $education->id; ?>" <?= ($edu->education_level??'')==$education->id?'selected':'' ?>><?= $education->qualification_name; ?></option>
                                                                                                        <?php endforeach; ?>
                                                                                                        <option value="other" <?= ($edu->education_level??'') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div id="other_education_level_div_<?= $index ?>" class="other-input col-xl-12 mt-2" style="display: <?= ($edu->education_level??'') == 'other' ? 'block' : 'none' ?>;">
                                                                                            <div class="row">
                                                                                                <div class="col-xl-6 label-text"><label>Specify Level</label></div>
                                                                                                <div class="col-xl-6 input-box">
                                                                                                    <input type="text" class="form-control" name="other_education_level[]" value="<?= $edu->other_education_level ?? '' ?>" placeholder="Enter education level">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Institution -->
                                                                                        <div class="col-xl-6 mt-2 mb-2">
                                                                                            <div class="row">
                                                                                                <div class="col-xl-6 label-text">
                                                                                                    <label class="form-label">Institution :</label>
                                                                                                </div>
                                                                                                <div class="col-xl-6 input-box">
                                                                                                    <input type="text" class="form-control" name="institution[]" value="<?= $edu->institution ?>"  placeholder="Name of Institution">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Qualification Obtained with Other -->
                                                                                        <div class="col-xl-12 mt-2 mb-2">
                                                                                            <div class="row">
                                                                                                <div class="col-xl-2 label-text">
                                                                                                    <label class="form-label">Qualification Obtained :</label>
                                                                                                </div>
                                                                                                <div class="col-xl-10 input-box">
                                                                                                    
                                                                                                      <input type="text" class="form-control" name="qualification_obtained[]" placeholder="Degree/Diploma/Certificate" value="<?= $edu->degree ?>" >
                                                                                                      
                                                                                       
                                                                                                    <div id="other_degree_div_<?= $index ?>" class="other-input" style="display: <?= ($edu->degree??'') == 'other' ? 'block' : 'none' ?>; margin-top:10px;">
                                                                                                        <input type="text" class="form-control" name="other_degree[]" value="<?= $edu->other_degree ?? '' ?>" placeholder="Enter your degree">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <!-- First education entry -->
                                                                        <div class="education-entry card mb-3" data-edu-index="0">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="mb-0">Education #1</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <!-- Level with Other -->
                                                                                    <div class="col-xl-6 mt-2 mb-2">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-6 label-text">
                                                                                                <label class="form-label">Level :</label>
                                                                                            </div>
                                                                                            <div class="col-xl-6 select-input">
                                                                                                
                                                                                               
                                                                                                
                                                                                                
                                                                                                <select class="form-select other-enabled" name="education_level[]" id="edu_level_0" data-other-div="other_education_level_div_0" data-other-name="other_education_level[]" onchange="toggleOtherDynamic(this, 0, 'education_level', 'other')" required>
                                                                                                    <option value="">Select Level</option>
                                                                                                    <?php foreach($education_qualifications as $education): ?>
                                                                                                        <option value="<?= $education->id; ?>"><?= $education->qualification_name; ?></option>
                                                                                                    <?php endforeach; ?>
                                                                                                    <option value="other">Other</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div id="other_education_level_div_0" class="other-input col-xl-12 mt-2" style="display: none;">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-6 label-text"><label>Specify Level</label></div>
                                                                                            <div class="col-xl-6 input-box">
                                                                                                <input type="text" class="form-control" name="other_education_level[]" placeholder="Enter education level">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Institution -->
                                                                                    <div class="col-xl-6 mt-2 mb-2">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-6 label-text">
                                                                                                <label class="form-label">Institution :</label>
                                                                                            </div>
                                                                                            <div class="col-xl-6 input-box">
                                                                                                <input type="text" class="form-control" name="institution[]" placeholder="Name of Institution" >
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Qualification Obtained with Other -->
                                                                                    <div class="col-xl-12 mt-2 mb-2">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-2 label-text">
                                                                                                <label class="form-label">Qualification Obtained :</label>
                                                                                            </div>
                                                                                            <div class="col-xl-10 input-box">
                                                                                                
                                                                                                <input type="text" class="form-control" name="qualification_obtained[]" placeholder="Degree/Diploma/Certificate">
                                                                                                
                                                                                                <div id="other_degree_div_0" class="other-input" style="display: none; margin-top:10px;">
                                                                                                    <input type="text" class="form-control" name="other_degree[]" placeholder="Enter your degree">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>

                                                                <!-- Add More Button -->
                                                                <div class="col-md-xl-12 d-xl-flex justify-content-xl-between align-items-center">
                                                                    <button type="button" class="btn more-btn add-more-education">Add More Education</button>
                                                                    <div class="col-md-xl-12 d-flex justify-content-end">
                                                                      
                                                                        <div class="bttn-all"><button class="btn-priv btn btn-success">Save and Submit</button></div>
                                                                    
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>

                                                        <!-- ==================== WORK EXPERIENCE FORM ==================== -->
                                                        <form method="post" action="<?= base_url('save-work-details'); ?>">
                                                            <div class="row">
                                                                <hr />
                                                                <div class="col-xl-12">
                                                                    <h4 class="headtext">Work Experience :</h4>
                                                                </div>

                                                                <div id="work-experience-container">
                                                                    <?php if (!empty($user_work_experience)): ?>
                                                                        <?php foreach ($user_work_experience as $index => $experience): ?>
                                                                            <div class="work-exp-entry card mb-3" data-work-index="<?= $index ?>">
                                                                                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                                                                    <h6 class="mb-0">Work Experience #<?= $index + 1 ?></h6>
                                                                                    <?php if($index > 0): ?>
                                                                                        <button type="button" class="btn btn-sm btn-danger remove-work-exp">
                                                                                            <i class="fas fa-times"></i> Remove
                                                                                        </button>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <!-- Designation with Other -->
                                                                                        <div class="col-xl-6 mt-2 mb-2">
                                                                                            <div class="row">
                                                                                                <div class="col-xl-6 label-text">
                                                                                                    <label class="form-label">Designation:</label>
                                                                                                </div>
                                                                                                <div class="col-xl-6 input-box">
                                                                                                    
                                                                                                    <input type="text" class="form-control"  name="designation[]"  value="<?= $experience->designation; ?>"> 
                                                                                                    
                                                                                                   
                                                                                                    <div id="other_designation_div_<?= $index ?>" class="other-input" style="display: <?= ($experience->designation??'') == 'other' ? 'block' : 'none' ?>; margin-top:10px;">
                                                                                                        <input type="text" class="form-control" name="other_designation[]" value="<?= $experience->other_designation ?? '' ?>" placeholder="Enter your designation">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Company -->
                                                                                        <div class="col-xl-6 mt-2 mb-2">
                                                                                            <div class="row">
                                                                                                <div class="col-xl-6 label-text">
                                                                                                    <label class="form-label">Company / Firm:</label>
                                                                                                </div>
                                                                                                <div class="col-xl-6 input-box">
                                                                                                    <input type="text" class="form-control" name="company[]" value="<?= esc($experience->company); ?>" placeholder="Enter company name" >
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Years Worked -->
                                                                                        <div class="col-xl-6 mt-2 mb-2">
                                                                                            <div class="row">
                                                                                                <div class="col-xl-6 label-text">
                                                                                                    <label class="form-label">Years Worked:</label>
                                                                                                </div>
                                                                                                <div class="col-xl-6 input-box">
                                                                                                    <input type="text" class="form-control" name="years_worked[]" value="<?= esc($experience->years_worked); ?>" placeholder="e.g., 2015-2020" >
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                       <?php if($index == 0) { ?>
                                                                                            <div class="col-xl-6 mt-2 mb-2">
                                                                                                <div class="row">
                                                                                                    <div class="col-xl-6 label-text">
                                                                                                        <label class="form-label">Current CTC:</label>
                                                                                                    </div>
                                                                                                    <div class="col-xl-6 input-box">
                                                                                                        <input type="text" class="form-control" name="ctc[]" value="<?= esc($experience->ctc); ?>" placeholder="e.g., ₹10 LPA">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <?php } ?>
                                                                                       
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <!-- First work experience entry -->
                                                                        <div class="work-exp-entry card mb-3" data-work-index="0">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="mb-0">Work Experience #1</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <!-- Designation with Other -->
                                                                                    <div class="col-xl-6 mt-2 mb-2">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-6 label-text">
                                                                                                <label class="form-label">Designation:</label>
                                                                                            </div>
                                                                                            <div class="col-xl-6 input-box">
                                                                                               <input type="text" class="form-control"  name="designation[]" > 
                                                                                                <div id="other_designation_div_0" class="other-input" style="display: none; margin-top:10px;">
                                                                                                    <input type="text" class="form-control" name="other_designation[]" placeholder="Enter your designation">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Company -->
                                                                                    <div class="col-xl-6 mt-2 mb-2">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-6 label-text">
                                                                                                <label class="form-label">Company / Firm:</label>
                                                                                            </div>
                                                                                            <div class="col-xl-6 input-box">
                                                                                                <input type="text" class="form-control" name="company[]" placeholder="Enter company name" >
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Years Worked -->
                                                                                    <div class="col-xl-6 mt-2 mb-2">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-6 label-text">
                                                                                                <label class="form-label">Years Worked:</label>
                                                                                            </div>
                                                                                            <div class="col-xl-6 input-box">
                                                                                                <input type="text" class="form-control" name="years_worked[]" placeholder="e.g., 2015-2020" >
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- CTC -->
                                                                                    <div class="col-xl-6 mt-2 mb-2">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-6 label-text">
                                                                                                <label class="form-label">Current CTC:</label>
                                                                                            </div>
                                                                                            <div class="col-xl-6 input-box">
                                                                                                <input type="text" class="form-control" name="ctc[]" placeholder="e.g., ₹10 LPA">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>


          
                                                                <!-- Add More Button -->
                                                                <div class="col-md-xl-12 d-xl-flex justify-content-xl-between align-items-center">
                                                                    <button type="button" class="btn more-btn add-more-work">Add More Work Experience</button>
                                                                </div>
                                                                
                                                                <div class="col-xl-12 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label for="email" class="form-label"><strong>Say a Few Words About Yourself</strong></label>
                                                                        </div>
                                                                        <div class="col-xl-6 input-box">
                                                                         <div class="form-group">
                                                                        <textarea class="form-control charCountTextarea" maxlength="500" name="about_self" rows="2"><?= esc($userdata->about_self ?? '') ?></textarea>
                                                                        
                                                                       <small class="text-muted">
        <span class="charCount">0</span>/500 characters
    </small>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Buttons -->
                                                                <div class="col-md-xl-12 d-flex justify-content-end mt-3">
                                                                    <div class="bttn-all">
                                                                        <button type="submit" class="btn-priv btn btn-success">Save and Submit</button>
                                                                    </div>
                                                                   
                                                                </div>
                                                            </div>
                                                        </form>

                                                        <!-- ==================== FAMILY DETAILS FORM ==================== -->
                                                        <form method="post" action="<?= base_url('save-family-details'); ?>">
                                                            <div class="row">
                                                                <div class="col-xl-12">
                                                                    <h4 class="headtext">Family Details :</h4>
                                                                </div>
                                                                <hr />

                                                                <div class="family-block" id="family-container">
                                                                    <?php if (!empty($family_members)) { ?>
                                                                        <?php foreach ($family_members as $key => $member) { ?>
                                                                            <div class="family-member-entry card mb-3" data-family-index="<?= $key ?>">
                                                                                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                                                                    <h6 class="mb-0">Family Member #<?= $key + 1 ?></h6>
                                                                                    <?php if($key > 0): ?>
                                                                                        <button type="button" class="btn btn-sm btn-danger remove-family-member">
                                                                                            <i class="fas fa-times"></i> Remove
                                                                                        </button>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <!-- Relationship with Other -->
                                                                                        <div class="col-xl-6 mt-2 mb-2">
                                                                                            <div class="row">
                                                                                                <div class="col-xl-6 label-text">
                                                                                                    <label class="form-label">Relationship :</label>
                                                                                                </div>
                                                                                                <div class="col-xl-6 select-input">
                                                                                                    <select class="form-select other-enabled" name="relationship[]" id="relationship_<?= $key ?>" data-other-div="other_relationship_div_<?= $key ?>" data-other-name="other_relationship[]" onchange="toggleOtherDynamic(this, <?= $key ?>, 'relationship', 'other')">
                                                                                                        <option value="">Select</option>
                                                                                                        <option value="Father" <?= ($member->relationship??'') == 'Father' ? 'selected' : '' ?>>Father</option>
                                                                                                        <option value="Mother" <?= ($member->relationship??'') == 'Mother' ? 'selected' : '' ?>>Mother</option>
                                                                                                        <option value="Brother" <?= ($member->relationship??'') == 'Brother' ? 'selected' : '' ?>>Brother</option>
                                                                                                        <option value="Sister" <?= ($member->relationship??'') == 'Sister' ? 'selected' : '' ?>>Sister</option>
                                                                                                        <option value="other" <?= ($member->relationship??'') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                                                    </select>
                                                                                                    <div id="other_relationship_div_<?= $key ?>" class="other-input" style="display: <?= ($member->relationship??'') == 'other' ? 'block' : 'none' ?>; margin-top:10px;">
                                                                                                        <input type="text" class="form-control" name="other_relationship[]" value="<?= $member->other_relationship ?? '' ?>" placeholder="Please specify relationship">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Occupation with Other -->
                                                                                        <div class="col-xl-6 mt-2 mb-2">
                                                                                            <div class="row">
                                                                                                <div class="col-xl-6 label-text">
                                                                                                    <label class="form-label">Occupation:</label>
                                                                                                </div>
                                                                                                <div class="col-xl-6 input-box">
                                                                                                    
                                                                                                         <input type="text" class="form-control" name="occupation[]" value="<?= $member->occupation ?? '' ?>" placeholder="Present occupation">
                                                                                                   
                          
                                                                                                 
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <!-- First time empty row -->
                                                                        <div class="family-member-entry card mb-3" data-family-index="0">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="mb-0">Family Member #1</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <!-- Relationship with Other -->
                                                                                    <div class="col-xl-6 mt-2 mb-2">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-6 label-text">
                                                                                                <label class="form-label">Relationship :</label>
                                                                                            </div>
                                                                                            <div class="col-xl-6 select-input">
                                                                                                <select class="form-select other-enabled" name="relationship[]" id="relationship_0" data-other-div="other_relationship_div_0" data-other-name="other_relationship[]" onchange="toggleOtherDynamic(this, 0, 'relationship', 'other')">
                                                                                                    <option value="">Select</option>
                                                                                                    <option value="Father">Father</option>
                                                                                                    <option value="Mother">Mother</option>
                                                                                                    <option value="Brother">Brother</option>
                                                                                                    <option value="Sister">Sister</option>
                                                                                                    <option value="other">Other</option>
                                                                                                </select>
                                                                                                <div id="other_relationship_div_0" class="other-input" style="display: none; margin-top:10px;">
                                                                                                    <input type="text" class="form-control" name="other_relationship[]" placeholder="Please specify relationship">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Occupation with Other -->
                                                                                    <div class="col-xl-6 mt-2 mb-2">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-6 label-text">
                                                                                                <label class="form-label">Occupation:</label>
                                                                                            </div>
                                                                                            <div class="col-xl-6 input-box">
                                                                                                 <input type="text" class="form-control" name="occupation[]" value="" placeholder="Present occupation">
                                                                                                <div id="other_occupation_div_0" class="other-input" style="display: none; margin-top:10px;">
                                                                                                    <input type="text" class="form-control" name="other_occupation[]" placeholder="Please specify occupation">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>

                                                                <!-- Add More -->
                                                                <div class="col-md-xl-12">
                                                                    <button type="button" class="btn more-btn add-more-family">Add More Family Member</button>
                                                                </div>

                                                                <!-- Family Income -->
                                                                <div class="col-xl-12 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label for="email" class="form-label"><strong>Family Income (PA):</strong></label>
                                                                        </div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select" name="family_income">
                                                                                <option value="">-- Select Income -- </option>
                                                                                <option value="Less than 10 Lakhs" <?= (!empty($family_background) && $family_background->family_income == 'Less than 10 Lakhs') ? 'selected' : '' ?>>Less than 10 Lakhs</option>
                                                                                <option value="10 Lakhs to 20 Lakhs" <?= (!empty($family_background) && $family_background->family_income == '10 Lakhs to 20 Lakhs') ? 'selected' : '' ?>>10 Lakhs to 20 Lakhs</option>
                                                                                <option value="20 Lakhs to 30 Lakhs" <?= (!empty($family_background) && $family_background->family_income == '20 Lakhs to 30 Lakhs') ? 'selected' : '' ?>>20 Lakhs to 30 Lakhs</option>
                                                                                <option value="30 Lakhs to 50 Lakhs" <?= (!empty($family_background) && $family_background->family_income == '30 Lakhs to 50 Lakhs') ? 'selected' : '' ?>>30 Lakhs to 50 Lakhs</option>
                                                                                <option value="50 Lakhs to 1 Crore" <?= (!empty($family_background) && $family_background->family_income == '50 Lakhs to 1 Crore') ? 'selected' : '' ?>>50 Lakhs to 1 Crore</option>
                                                                                <option value="1 Crore and Above" <?= (!empty($family_background) && $family_background->family_income == '1 Crore and Above') ? 'selected' : '' ?>>1 Crore and Above</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- About Family -->
                                                                <div class="col-xl-12 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label for="email" class="form-label"><strong>Say a Few Words About Your Family</strong></label>
                                                                        </div>
                                                                        <div class="col-xl-6 input-box">
                                                                             <div class="form-group">
                                                                            <textarea class="form-control charCountTextarea" id="textarea2" maxlength="500" name="about_family" rows="2"><?= esc($family_background->about_family ?? '') ?></textarea>
                                                                         <small class="text-muted">
        <span class="charCount">0</span>/500 characters
    </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Buttons -->
                                                                <div class="col-md-xl-12 d-flex justify-content-end">
                                                                    <div class="bttn-all">
                                                                        <button type="submit" class="btn-priv btn btn-success">Save and Submit</button>
                                                                    </div>
                                                                  
                                                                </div>
                                                            </div>
                                                        </form>

                                                        <!-- ==================== PARTNER PREFERENCES FORM ==================== -->
                                                        <form method="post" action="<?= base_url('save-partner-preferences'); ?>">
                                                            <div class="row">
                                                                <div class="col-xl-12">
                                                                    <h4 class="headtext">Partner Preferences :</h4>
                                                                </div>
                                                                <hr />

                                                                <!-- AGE -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label class="form-label">Age:</label>
                                                                        </div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <div class="row">
                                                                                <div class="col-xl-6">
                                                                                    <label>From</label>
                                                                                    <input type="number" class="form-control" name="age_from" value="<?= $partner_preferences->age_from ?? '' ?>">
                                                                                </div>
                                                                                <div class="col-xl-6">
                                                                                    <label>To</label>
                                                                                    <input type="number" class="form-control" name="age_to" value="<?= $partner_preferences->age_to ?? '' ?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- HEIGHT -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label class="form-label">Height :</label>
                                                                        </div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <select class="form-select" name="height_from">
                                                                                <option value="">Select Height</option>
                                                                                <option value="4'0 - 4'5" <?= (isset($partner_preferences->height_from) && $partner_preferences->height_from == "4'0 - 4'5") ? 'selected' : '' ?>>4'0" - 4'5"</option>
                                                                                <option value="4'6 - 5'0" <?= (isset($partner_preferences->height_from) && $partner_preferences->height_from == "4'6 - 5'0") ? 'selected' : '' ?>>4'6" - 5'0"</option>
                                                                                <option value="5'1 - 5'5" <?= (isset($partner_preferences->height_from) && $partner_preferences->height_from == "5'1 - 5'5") ? 'selected' : '' ?>>5'1" - 5'5"</option>
                                                                                <option value="5'6 - 6'0" <?= (isset($partner_preferences->height_from) && $partner_preferences->height_from == "5'6 - 6'0") ? 'selected' : '' ?>>5'6" - 6'0"</option>
                                                                                <option value="6'1+" <?= (isset($partner_preferences->height_from) && $partner_preferences->height_from == "6'1+") ? 'selected' : '' ?>>6'1"+</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- RELIGION with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label class="form-label">Religion:</label>
                                                                        </div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="religion_id" id="partner_religion" data-other-div="partner_religion_other_div" data-other-name="other_partner_religion" onchange="toggleOther(this, 'partner_religion_other_div', 'other_partner_religion')">
                                                                                <option value="">Any</option>
                                                                                <?php foreach($religions as $r){ ?>
                                                                                    <option value="<?= $r->id ?>" <?= ($partner_preferences->religion_id ?? '')==$r->id?'selected':'' ?>><?= $r->name ?></option>
                                                                                <?php } ?>
                                                                                <option value="other" <?= ($partner_preferences->religion_id ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="partner_religion_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($partner_preferences->religion_id ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Religion</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_partner_religion" value="<?= $partner_preferences->other_religion ?? '' ?>" placeholder="Enter religion">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- CASTE with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label class="form-label">Caste:</label>
                                                                        </div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="caste_id" id="partner_caste" data-other-div="partner_caste_other_div" data-other-name="other_partner_caste" onchange="toggleOther(this, 'partner_caste_other_div', 'other_partner_caste')">
                                                                                <option value="">Any</option>
                                                                                <?php foreach($castes as $c){ ?>
                                                                                    <option value="<?= $c->id ?>" <?= ($partner_preferences->caste_id ?? '')==$c->id?'selected':'' ?>><?= $c->name ?></option>
                                                                                <?php } ?>
                                                                                <option value="other" <?= ($partner_preferences->caste_id ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="partner_caste_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($partner_preferences->caste_id ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Caste</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_partner_caste" value="<?= $partner_preferences->other_caste ?? '' ?>" placeholder="Enter caste">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- DIET with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label class="form-label">Dietary Preference:</label>
                                                                        </div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="dietary_preference" id="dietary_preference" data-other-div="dietary_preference_other_div" data-other-name="other_dietary_preference" onchange="toggleOther(this, 'dietary_preference_other_div', 'other_dietary_preference')">
                                                                                <?php $diets=['Veg','Non Veg','Vegan','Doesn’t Matter']; ?>
                                                                                <option value="">Select</option>
                                                                                <?php foreach($diets as $d){ ?>
                                                                                    <option value="<?= $d ?>" <?= ($partner_preferences->dietary_preference ?? '')==$d?'selected':'' ?>><?= $d ?></option>
                                                                                <?php } ?>
                                                                                <option value="other" <?= ($partner_preferences->dietary_preference ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="dietary_preference_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($partner_preferences->dietary_preference ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Diet</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_dietary_preference" value="<?= $partner_preferences->other_dietary_preference ?? '' ?>" placeholder="Enter dietary preference">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- SMOKER with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label class="form-label">Smoking Habit :</label>
                                                                        </div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="smoker" id="smoker" data-other-div="smoker_other_div" data-other-name="other_smoker" onchange="toggleOther(this, 'smoker_other_div', 'other_smoker')">
                                                                                <?php $smoke=['Strictly No','Occasionally','Doesn’t Matter']; ?>
                                                                                <option value="">Select</option>
                                                                                <?php foreach($smoke as $s){ ?>
                                                                                    <option value="<?= $s ?>" <?= ($partner_preferences->smoker ?? '')==$s?'selected':'' ?>><?= $s ?></option>
                                                                                <?php } ?>
                                                                                <option value="other" <?= ($partner_preferences->smoker ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="smoker_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($partner_preferences->smoker ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Smoking Habit</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_smoker" value="<?= $partner_preferences->other_smoker ?? '' ?>" placeholder="Enter smoking habit">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- DRINKING with Other -->
                                                                <div class="col-xl-6 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label class="form-label">Drinking Habit:</label>
                                                                        </div>
                                                                        <div class="col-xl-6 select-input">
                                                                            <select class="form-select other-enabled" name="drinking_habit" id="drinking_habit" data-other-div="drinking_habit_other_div" data-other-name="other_drinking_habit" onchange="toggleOther(this, 'drinking_habit_other_div', 'other_drinking_habit')">
                                                                                <?php $drink=['No','Occasionally','Doesn’t Matter']; ?>
                                                                                <option value="">Select</option>
                                                                                <?php foreach($drink as $d){ ?>
                                                                                    <option value="<?= $d ?>" <?= ($partner_preferences->drinking_habit ?? '')==$d?'selected':'' ?>><?= $d ?></option>
                                                                                <?php } ?>
                                                                                <option value="other" <?= ($partner_preferences->drinking_habit ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="drinking_habit_other_div" class="other-input col-xl-12 mt-2" style="display: <?= ($partner_preferences->drinking_habit ?? '') == 'other' ? 'block' : 'none' ?>;">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text"><label>Specify Drinking Habit</label></div>
                                                                        <div class="col-xl-6 input-box">
                                                                            <input type="text" class="form-control" name="other_drinking_habit" value="<?= $partner_preferences->other_drinking_habit ?? '' ?>" placeholder="Enter drinking habit">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- ABOUT PARTNER -->
                                                                <div class="col-xl-12 mt-2 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 label-text">
                                                                            <label class="form-label"><strong>Say a Few Words About Your Partner Preference :</strong></label>
                                                                        </div>
                                                                        <div class="col-xl-6 input-box">
                                                                             <div class="form-group">
                                                                            <textarea class="form-control charCountTextarea" name="about_partner" maxlength="500" rows="2"><?= esc($partner_preferences->about_partner ?? '') ?></textarea>
                                                                            <small class="text-muted">
        <span class="charCount">0</span>/500 characters
    </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- BUTTONS -->
                                                                <div class="col-md-xl-12 d-flex justify-content-end">
                                                                    <div class="bttn-all">
                                                                        <button type="submit" class="btn-priv btn btn-success">Save and Submit</button>
                                                                    </div>
                                                                   
                                                                </div>
                                                            </div>
                                                        </form>

                                                        <!-- ==================== PROFILE VISIBILITY FORM ==================== -->
                                                        <form method="post" action="<?= base_url('save-profile-visibility'); ?>">
                                                            <div class="row">
                                                                <div class="col-xl-12">
                                                                    <h4 class="headtext">Profile Visibility :</h4>
                                                                </div>

                                                                <div class="col-xl-12 mt-2 mb-2">
                                                                    <ul class="list-group view-list d-xl-flex flex-wrap flex-row">
                                                                        <li class="col-xl-12">
                                                                            <strong>Make my profile visible to</strong>
                                                                        </li>

                                                                        <!-- CHECKBOXES -->
                                                                        <li class="col-xl-6 icon-r">
                                                                            <input type="checkbox" name="visibility_all" value="1" <?= ($userdata->visibility_all ?? 0) ? 'checked' : '' ?>>
                                                                            <label>All</label>
                                                                        </li>

                                                                        <li class="col-xl-6 icon-r">
                                                                            <input type="checkbox" name="visibility_verified" value="1" <?= ($userdata->visibility_verified ?? 0) ? 'checked' : '' ?>>
                                                                            <label>All Verified (Blue Batch)</label>
                                                                        </li>

                                                                        <li class="col-xl-6 icon-r">
                                                                            <input type="checkbox" name="visibility_defence" value="1" <?= ($userdata->visibility_defence ?? 0) ? 'checked' : '' ?>>
                                                                            <label>Defence Category (Green Batch)</label>
                                                                        </li>

                                                                        <li class="col-xl-6 icon-r">
                                                                            <input type="checkbox" name="visibility_orange" value="1" <?= ($userdata->visibility_orange ?? 0) ? 'checked' : '' ?>>
                                                                            <label>Select Category (Orange Batch)</label>
                                                                        </li>

                                                                        <!-- RELIGION -->
                                                                        <li class="col-xl-6">
                                                                            <label>By Religion</label>
                                                                            <select class="form-select" name="visibility_religion">
                                                                                <option value="">Any</option>
                                                                                <?php foreach($religions as $r){ ?>
                                                                                    <option value="<?= $r->id ?>" <?= ($userdata->visibility_religion ?? '')==$r->id?'selected':'' ?>><?= $r->name ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </li>

                                                                        <!-- CASTE -->
                                                                        <li class="col-xl-6">
                                                                            <label>By Caste</label>
                                                                            <select class="form-select" name="visibility_caste">
                                                                                <option value="">Any</option>
                                                                                <?php foreach($castes as $c){ ?>
                                                                                    <option value="<?= $c->id ?>" <?= ($userdata->visibility_caste ?? '')==$c->id?'selected':'' ?>><?= $c->name ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </li>

                                                                        <!-- INCOME -->
                                                                        <li class="col-xl-6">
                                                                            <label>By Income</label>
                                                                            <select class="form-select" name="visibility_income">
                                                                                <option value="">Select Income</option>
                                                                                <?php $incomes=['Less than 10 Lakhs','10 Lakhs to 20 Lakhs','20 Lakhs to 30 Lakhs','30 Lakhs to 50 Lakhs','50 Lakhs to 1 Crore','1 Crore and Above']; ?>
                                                                                <?php foreach($incomes as $i){ ?>
                                                                                    <option value="<?= $i ?>" <?= ($userdata->visibility_income ?? '')==$i?'selected':'' ?>><?= $i ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </li>

                                                                        <!-- FOLLOWING -->
                                                                        <li class="col-xl-6">
                                                                            <label>Only Following Profiles</label>
                                                                            <input type="text" class="form-control" name="visibility_following_profiles" value="<?= esc($userdata->visibility_following_profiles ?? '') ?>" placeholder="Profile IDs comma separated">
                                                                        </li>

                                                                        <!-- HIDE PROFILE -->
                                                                        <li class="col-xl-6 icon-r">
                                                                            <input type="checkbox" name="hide_profile" value="1" <?= ($userdata->hide_profile ?? 0) ? 'checked' : '' ?>>
                                                                            <label>Hide Profile</label>
                                                                        </li>
                                                                    </ul>
                                                                </div>

                                                                <div class="col-md-xl-12 d-flex justify-content-end">
                                                                    <button type="submit" class="btn-priv btn btn-success">Save and Submit Details</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(document).ready(function(){

    // Page load pe initial count set karo
    $('.charCountTextarea').each(function(){
        let length = $(this).val().length;
        $(this).next('small').find('.charCount').text(length);
    });

    // Typing event
    $('.charCountTextarea').on('input', function(){
        let length = $(this).val().length;
        $(this).next('small').find('.charCount').text(length);
    });

});





jQuery(document).ready(function($){
    $('.select2').select2({
        width: '100%',
          placeholder: "Select languages",
    });
});
</script>

<script>



document.addEventListener("DOMContentLoaded", function(){

    // DELETE BUTTON
    document.querySelectorAll(".delete-btn").forEach(function(button){
        button.addEventListener("click", function(e){

            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            let id = this.getAttribute("data-id");

            if(confirm("Are you sure you want to delete this photo?")){
                window.location.href = "<?= base_url('delete-photo/') ?>/" + id;
            }

        }, true); // <-- CAPTURE MODE (very important)
    });


  

});

</script>
<script>
$(document).ready(function() {
    const selectedHobbies = <?php echo json_encode($hobbies); ?>;
    console.log('Initializing TomSelect with:', selectedHobbies);
    
    const tomSelect = new TomSelect('#hobbies', {
        plugins: ['remove_button'],
        maxItems: null,
        create: false,
        placeholder: 'Choose your hobbies...'
    });
    
    tomSelect.setValue(selectedHobbies);
    console.log('TomSelect initialized successfully');
});
</script>

<!-- Main JavaScript for Dynamic Rows and Other Dropdowns -->
<script>
// ==================== COMPLETE OTHER DROPDOWN HANDLING ====================
function toggleOther(selectElement, otherDivId, inputName) {
    var otherDiv = document.getElementById(otherDivId);
    var otherInput = otherDiv ? otherDiv.querySelector('input[type="text"]') : null;
    
    if (selectElement.value === 'other' || selectElement.value === 'Others' || selectElement.value === 'others') {
        otherDiv.style.display = 'block';
        if (otherInput) {
            otherInput.name = inputName;
            otherInput.required = false;
        }
    } else {
        otherDiv.style.display = 'none';
        if (otherInput) {
            otherInput.value = '';
            otherInput.name = '';
        }
    }
}

function toggleOtherDynamic(selectElement, rowIndex, fieldType, prefix) {
    var otherDiv = document.getElementById(prefix + '_' + fieldType + '_div_' + rowIndex);
    var otherInput = otherDiv ? otherDiv.querySelector('input[type="text"]') : null;
    
    if (selectElement.value === 'other' || selectElement.value === 'Others' || selectElement.value === 'others') {
        otherDiv.style.display = 'block';
        if (otherInput) {
            otherInput.name = prefix + '_' + fieldType + '[]';
        }
    } else {
        otherDiv.style.display = 'none';
        if (otherInput) {
            otherInput.value = '';
            otherInput.name = '';
        }
    }
}

// Initialize all "other" fields on page load
document.addEventListener('DOMContentLoaded', function() {
    var otherSelects = document.querySelectorAll('select.other-enabled');
    otherSelects.forEach(function(select) {
        var otherDivId = select.getAttribute('data-other-div');
        if (otherDivId) {
            var otherDiv = document.getElementById(otherDivId);
            if (otherDiv && (select.value === 'other' || select.value === 'Others' || select.value === 'others')) {
                otherDiv.style.display = 'block';
                var otherInput = otherDiv.querySelector('input[type="text"]');
                if (otherInput) {
                    otherInput.name = select.getAttribute('data-other-name');
                }
            }
        }
    });
});

// ==================== DYNAMIC ROW HANDLING ====================
$(document).ready(function() {
    // Education Section
    let educationCount = <?= !empty($education_data) ? count($education_data) : 1 ?>;
    const maxEducations = 5;
    let educationProcessing = false;
    
    $(document).on('click', '.add-more-education', function(e) {
        e.preventDefault();
        if (educationProcessing) return;
        educationProcessing = true;
        
        if (educationCount >= maxEducations) {
            alert(`You can add maximum ${maxEducations} education entries.`);
            educationProcessing = false;
            return;
        }
        
        const newIndex = educationCount;
        const newEducationHtml = `
            <div class="education-entry card mb-3" data-edu-index="${newIndex}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Education #${educationCount + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-education">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 mt-2 mb-2">
                            <div class="row">
                                <div class="col-xl-6 label-text">
                                    <label class="form-label">Level :</label>
                                </div>
                                <div class="col-xl-6 select-input">
                                    <select class="form-select other-enabled" name="education_level[]" id="edu_level_${newIndex}" data-other-div="other_education_level_div_${newIndex}" data-other-name="other_education_level[]" onchange="toggleOtherDynamic(this, ${newIndex}, 'education_level', 'other')" >
                                        <option value="">Select Level</option>
                                        <?php foreach($education_qualifications as $education) { ?>
                                            <option value="<?php echo $education->id;?>"><?php echo $education->qualification_name;?></option>
                                        <?php } ?>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="other_education_level_div_${newIndex}" class="other-input col-xl-12 mt-2" style="display: none;">
                            <div class="row">
                                <div class="col-xl-6 label-text"><label>Specify Level</label></div>
                                <div class="col-xl-6 input-box">
                                    <input type="text" class="form-control" name="other_education_level[]" placeholder="Enter education level">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 mt-2 mb-2">
                            <div class="row">
                                <div class="col-xl-6 label-text">
                                    <label class="form-label">Institution :</label>
                                </div>
                                <div class="col-xl-6 input-box">
                                    <input type="text" class="form-control" name="institution[]" placeholder="Name of Institution" >
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 mt-2 mb-2">
                            <div class="row">
                                <div class="col-xl-2 label-text">
                                    <label class="form-label">Qualification Obtained :</label>
                                </div>
                                <div class="col-xl-10 input-box">
                                <input type="text" class="form-control" name="qualification_obtained[]" placeholder="Degree/Diploma/Certificate" >
                                   
                                    <div id="other_degree_div_${newIndex}" class="other-input" style="display: none; margin-top:10px;">
                                        <input type="text" class="form-control" name="other_degree[]" placeholder="Enter your degree">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $(this).closest('.col-md-xl-12').before(newEducationHtml);
        educationCount++;
        setTimeout(() => { educationProcessing = false; }, 300);
    });
    
    $(document).on('click', '.remove-education', function(e) {
        e.preventDefault();
        if (educationCount > 1) {
            $(this).closest('.education-entry').remove();
            educationCount--;
            renumberEducationEntries();
        }
    });
    
    function renumberEducationEntries() {
        $('.education-entry').each(function(index) {
            $(this).find('.card-header h6').text(`Education #${index + 1}`);
            $(this).attr('data-edu-index', index);
        });
    }
    
    // Work Experience Section
    let workExpCount = <?= !empty($user_work_experience) ? count($user_work_experience) : 1 ?>;
    const maxWorkExps = 5;
    let workProcessing = false;
    
    $(document).on('click', '.add-more-work', function(e) {
        e.preventDefault();
        if (workProcessing) return;
        workProcessing = true;
        
        if (workExpCount >= maxWorkExps) {
            alert(`You can add maximum ${maxWorkExps} work experience entries.`);
            workProcessing = false;
            return;
        }
        
        const newIndex = workExpCount;
        const newWorkExpHtml = `
            <div class="work-exp-entry card mb-3" data-work-index="${newIndex}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Work Experience #${workExpCount + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-work-exp">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 mt-2 mb-2">
                            <div class="row">
                                <div class="col-xl-6 label-text">
                                    <label class="form-label">Designation:</label>
                                </div>
                                <div class="col-xl-6 input-box">
                                   <input type="text" class="form-control"  name="designation[]"  > 
                                    <div id="other_designation_div_${newIndex}" class="other-input" style="display: none; margin-top:10px;">
                                        <input type="text" class="form-control" name="other_designation[]" placeholder="Enter your designation">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 mt-2 mb-2">
                            <div class="row">
                                <div class="col-xl-6 label-text">
                                    <label class="form-label">Company/ Firm:</label>
                                </div>
                                <div class="col-xl-6 input-box">
                                    <input type="text" class="form-control" name="company[]" placeholder="Enter company name">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 mt-2 mb-2">
                            <div class="row">
                                <div class="col-xl-6 label-text">
                                    <label class="form-label">Years Worked:</label>
                                </div>
                                <div class="col-xl-6 input-box">
                                    <input type="text" class="form-control" name="years_worked[]" placeholder="e.g., 2015-2020">
                                </div>
                            </div>
                        </div>
                 
                 
                    </div>
                </div>
            </div>
        `;
        
        $(this).closest('.col-md-xl-12').before(newWorkExpHtml);
        workExpCount++;
        setTimeout(() => { workProcessing = false; }, 300);
    });
    
    $(document).on('click', '.remove-work-exp', function(e) {
        e.preventDefault();
        if (workExpCount > 1) {
            $(this).closest('.work-exp-entry').remove();
            workExpCount--;
            renumberWorkExpEntries();
        }
    });
    
    function renumberWorkExpEntries() {
        $('.work-exp-entry').each(function(index) {
            $(this).find('.card-header h6').text(`Work Experience #${index + 1}`);
            $(this).attr('data-work-index', index);
        });
    }
    
    // Family Section
    let familyCount = <?= !empty($family_members) ? count($family_members) : 1 ?>;
    const maxFamily = 10;
    let familyProcessing = false;
    
    $(document).on('click', '.add-more-family', function(e) {
        e.preventDefault();
        if (familyProcessing) return;
        familyProcessing = true;
        
        if (familyCount >= maxFamily) {
            alert(`You can add maximum ${maxFamily} family members.`);
            familyProcessing = false;
            return;
        }
        
        const newIndex = familyCount;
        const newFamilyHtml = `
            <div class="family-member-entry card mb-3" data-family-index="${newIndex}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Family Member #${familyCount + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-family-member">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 mt-2 mb-2">
                            <div class="row">
                                <div class="col-xl-6 label-text">
                                    <label class="form-label">Relationship :</label>
                                </div>
                                <div class="col-xl-6 select-input">
                                    <select class="form-select other-enabled" name="relationship[]" id="relationship_${newIndex}" data-other-div="other_relationship_div_${newIndex}" data-other-name="other_relationship[]" onchange="toggleOtherDynamic(this, ${newIndex}, 'relationship', 'other')">
                                        <option value="">Select</option>
                                        <option value="Father">Father</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Brother">Brother</option>
                                        <option value="Sister">Sister</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div id="other_relationship_div_${newIndex}" class="other-input" style="display: none; margin-top:10px;">
                                        <input type="text" class="form-control" name="other_relationship[]" placeholder="Please specify relationship">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 mt-2 mb-2">
                            <div class="row">
                                <div class="col-xl-6 label-text">
                                    <label class="form-label">Occupation:</label>
                                </div>
                                <div class="col-xl-6 input-box">
                                   <input type="text" class="form-control" name="occupation[]" value="" placeholder="Present occupation">
                                    <div id="other_occupation_div_${newIndex}" class="other-input" style="display: none; margin-top:10px;">
                                        <input type="text" class="form-control" name="other_occupation[]" placeholder="Please specify occupation">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $(this).closest('.col-md-xl-12').before(newFamilyHtml);
        familyCount++;
        setTimeout(() => { familyProcessing = false; }, 300);
    });
    
    $(document).on('click', '.remove-family-member', function(e) {
        e.preventDefault();
        if (familyCount > 1) {
            $(this).closest('.family-member-entry').remove();
            familyCount--;
            renumberFamilyEntries();
        }
    });
    
    function renumberFamilyEntries() {
        $('.family-member-entry').each(function(index) {
            $(this).find('.card-header h6').text(`Family Member #${index + 1}`);
            $(this).attr('data-family-index', index);
        });
    }
    
    // Religion to Caste dynamic dropdown
    $('#religions').on('change', function() {
        var religion_id = $(this).val();
        if(religion_id && religion_id !== 'other'){
            $.ajax({
                url: "<?php echo base_url('get-castes'); ?>",
                type: "POST",
                data: {religion_id: religion_id},
                dataType: "json",
                success: function(data){
                    $('#castes').empty();
                    $('#castes').append('<option value="">Select Caste</option>');
                    $.each(data, function(key, value){
                        $('#castes').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                    $('#castes').append('<option value="other">Other</option>');
                }
            });
        } else if(religion_id === 'other') {
            $('#castes').empty();
            $('#castes').append('<option value="">Any</option>');
            $('#castes').append('<option value="other">Other</option>');
        } else {
            $('#castes').html('<option value="">Select Caste</option><option value="other">Other</option>');
        }
    });
    
    $('#partner_religion').on('change', function() {
        var religion_id = $(this).val();
        if(religion_id && religion_id !== 'other'){
            $.ajax({
                url: "<?php echo base_url('get-castes'); ?>",
                type: "POST",
                data: {religion_id: religion_id},
                dataType: "json",
                success: function(data){
                    $('#partner_caste').empty();
                    $('#partner_caste').append('<option value="">Any</option>');
                    $.each(data, function(key, value){
                        $('#partner_caste').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                    $('#partner_caste').append('<option value="other">Other</option>');
                }
            });
        } else if(religion_id === 'other') {
            $('#partner_caste').empty();
            $('#partner_caste').append('<option value="">Any</option>');
            $('#partner_caste').append('<option value="other">Other</option>');
        } else {
            $('#partner_caste').html('<option value="">Select Caste</option><option value="other">Other</option>');
        }
    });
});
</script>

<script>
$(document).on('click', '.edit-photo-btn', function () {
    $('#photo_id').val($(this).data('id'));
    $('#editPhotoModal').modal('show');
});

function uploadProfileImage(event) {
    let file = event.target.files[0];
    if (!file) return;

    let reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById("selectedImage").src = e.target.result;
    }
    reader.readAsDataURL(file);

    let formData = new FormData();
    formData.append("profile_image", file);

    $.ajax({
        url: "<?= base_url('profile/uploadImage') ?>",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log("Upload Success:", response);
            if(response.status == "success"){
                alert("✅ Profile image updated successfully!");
            } else {
                alert("❌ Upload failed!");
            }
        },
        error: function(xhr) {
            console.log("Error:", xhr.responseText);
            alert("Something went wrong!");
        }
    });
}
</script>