<?php 
use App\Models\Commanmodel;
$commanmodel = new Commanmodel();
$session = session();
$usersession = $session->get('loggedin');

$partner_religion_name = '';
$partner_caste_name = '';
    if (!empty($partner_preferences->religion_id)) {
        $partner_religion_data = $commanmodel->get_single_query('religions', ['id' => $partner_preferences->religion_id]);
        $partner_religion_name = $partner_religion_data->name ?? '';
    }

    if (!empty($partner_preferences->caste_id)) {
        $partner_caste_data = $commanmodel->get_single_query('castes', ['id' => $partner_preferences->caste_id]);
        $partner_caste_name = $partner_caste_data->name ?? '';
    }

// Check approval status for each section
$db = \Config\Database::connect();
$user_id = $userdata->account_id;

// Personal status
$personalStatus = $userdata->personal_status ?? 'approved';
$verificationStatus = $userdata->verification_status ?? 'approved';

// Education status - check if any education record is approved
$educationApproved = $db->table('user_education')
    ->where('user_id', $user_id)
    ->where('status', 'approved')
    ->countAllResults() > 0;

// Filter education data to show only approved records
$approvedEducation = [];
if(isset($education_data) && !empty($education_data)) {
    foreach($education_data as $edu) {
        if($edu['status'] == 'approved' ||  $usersession["user_id"] == $userdata->account_id) {
            $approvedEducation[] = $edu;
        }
    }
}

// Work status - check if any work record is approved
$workApproved = $db->table('user_work_experience')
    ->where('user_id', $user_id)
    ->where('status', 'approved' ||  $usersession["user_id"] == $userdata->account_id)
    ->countAllResults() > 0;

// Filter work experience to show only approved records
$approvedWork = [];
if(isset($work_experience) && !empty($work_experience)) {
    foreach($work_experience as $work) {
        if($work->status == 'approved' ||  $usersession["user_id"] == $userdata->account_id) {
            $approvedWork[] = $work;
        }
    }
}

// Family background status
$familyApproved = isset($family_background) && 
                  (!isset($family_background->status) || $family_background->status == 'approved');

// Family members status - check if any family member is approved
$familyMembersApproved = $db->table('user_family_members')
    ->where('user_id', $user_id)
    ->where('status', 'approved')
    ->countAllResults() > 0;

// Filter family members to show only approved records
$approvedFamilyMembers = [];
if(isset($family_members) && !empty($family_members)) {
    foreach($family_members as $member) {
        if($member->status == 'approved' ||  $usersession["user_id"] == $userdata->account_id) {
            $approvedFamilyMembers[] = $member;
        }
    }
}

// Partner preferences status
$partnerApproved = isset($partner_preferences) && 
                   (!isset($partner_preferences->status) || $partner_preferences->status == 'approved');

// Photos status - check if any photo is approved
$photosApproved = $db->table('user_photos')
    ->where('user_id', $user_id)
    ->where('status', 'approved')
    ->countAllResults() > 0;

// Filter photos to show only approved records
$approvedPhotos = [];
if(isset($user_photos) && !empty($user_photos)) {
    foreach($user_photos as $photo) {
        if($photo->status == 'approved' ||  $usersession["user_id"] == $userdata->account_id) {
            $approvedPhotos[] = $photo;
        }
    }
}
?>

<!-- PROFILE -->
<section>
    <div class="profi-pg profi-ban">
        <div class="">
            <div class="">
                <!-- Profile Section with Image Gallery - Always visible -->
                <div class="profile">
                    <div class="pg-pro-big-im">
                        
                        <?php 
                              $badgeClass = '';
                if (!empty($userdata->batch)) {
                    $color = strtolower(trim($userdata->batch));
                    if ($color === 'green') $badgeClass = 'greenbage';
                    if ($color === 'blue') $badgeClass = 'bluebage';
                    if ($color === 'orange') $badgeClass = 'orangebage';
                }

                        
                        ?>
                        <!-- Main Image -->
                        <div class="s1 <?php echo $badgeClass; ?>">
                            <img src="<?php echo $commanmodel->profile_image($userdata->account_id); ?>" loading="lazy" class="pro main-img" alt="Profile Picture">
                        </div>
                        
                        <!-- Thumbnails - Show only approved photos -->
                        <?php if(!empty($approvedPhotos)): ?>
                        <div class="thumbs">
                            <?php foreach($approvedPhotos as $photo): ?>
                            <img src="<?php echo base_url('assets/uploads/' . $photo->photo_path); ?>" class="thumb" alt="Profile Photo">
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Buttons -->
                        <div class="s3">
                            <a href="#!" class="cta fol cta-chat db-chat-trig" 
                               data-partner-id="<?php echo $userdata->account_id; ?>" 
                               data-partner-name="<?php echo $userdata->user_name . ' ' . $userdata->user_last_name; ?>">Chat now</a>
                            <?php echo $commanmodel->interestButton($userdata->account_id); ?>
                            <a href="#!" id="viewContactBtn" data-user-id="<?= $userdata->account_id; ?>" class="cta fol contact">Contact Details</a>
                        </div>
                    </div>
                </div>
                
                <!-- Image Popup -->
                <div class="img-popup">
                    <span class="close">&times;</span>
                    <a class="prev">&#10094;</a>
                    <img class="popup-img">
                    <a class="next">&#10095;</a>
                </div>

                <!-- Profile Details Section -->
                <div class="profi-pg profi-bio">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="col-md-8 col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="profile-form">
                                                <div class="col-xl-12">
                                                    <form>
                                                        <!-- Profile Header - Always visible -->
                                                        <div class="name-number">
                                                            <h4><?php echo $userdata->user_name ?? ''; ?> <?php echo $userdata->user_last_name ?? ''; ?></h4>
                                                            <h4 class="user-id"><?php echo $userdata->user_id ?? '';  ?></h4>
                                                            <?php if($userdata->verified == 'Yes') {  ?>
                                                              <span class="verified-badge">Verified</span>
                                                              
                                                              <?php } ?>
                                                           
                                                        </div>

                                                        <!-- Basic Introduction - Always visible -->
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <p class="mb-0 pb-1"><strong>
                                                                    <?php if($userdata->match_for == 'self'): ?>
                                                                        I am <?php echo $userdata->user_name . ' ' . $userdata->user_last_name; ?>, looking for a suitable match.
                                                                    <?php else: ?>
                                                                        I am <?php echo $userdata->introducer_name ?? ''; ?>, looking for a suitable match for my <?php echo $userdata->match_for != 'others' ? $userdata->match_for : $userdata->match_for_other; ?>.
                                                                    <?php endif; ?>
                                                                </strong></p>
                                                            </div>
                                                        </div>

                                                        <!-- Personal Details - Show if personal status is approved -->
                                                        <?php if($personalStatus == 'approved' ||  $usersession["user_id"] == $userdata->account_id): ?>
                                                        <div class="row">
                                                            <hr />
                                                            <div class="col-xl-12">
                                                                <h4 class="headtext">Personal Details:</h4>
                                                            </div>
                                                          <div class="col-xl-12 mt-2 mb-2">
    <div class="row">
        <div class="col-xl-12 label-text">
            <ul class="list-group view-list d-xl-flex flex-wrap align-items-start flex-row">

                <?php
                function showValue($value, $default = 'Not specified') {
                    return !empty($value) ? esc($value) : $default;
                }

                $fullName = trim(($userdata->user_name ?? '') . ' ' . ($userdata->user_last_name ?? ''));

                // Height Format
                $height = '';
                if (!empty($userdata->height) && !empty($userdata->height_inch)) {
                    $height = esc($userdata->height) . ' ft ' . esc($userdata->height_inch) . ' inch';
                } else {
                    $height = 'Not specified';
                }

                // Date Format
                $dob = (!empty($userdata->date_of_birth)) 
                    ? date('d-m-Y', strtotime($userdata->date_of_birth)) 
                    : 'Not specified';
                ?>

                <li class="col-xl-6 prof-d">
                    Name :
                    <span class="text-list prof-d01">
                        <?= $fullName ?: 'Not specified'; ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Profession :
                    <span class="text-list prof-d01">
                        <?= showValue($profession_name); ?> 
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Marital Status :
                    <span class="text-list prof-d01">
                     
                        
                         <?= showValue($userdata->marital_status == 'other' ? $userdata->other_marital_status :$userdata->marital_status); ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Height :
                    <span class="text-list prof-d01">
                        <?= $height; ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Date of Birth :
                    <span class="text-list prof-d01">
                        <?= $dob; ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Time of Birth :
                    <span class="text-list prof-d01">
                        <?php
$timeOfBirth = 'Not specified';

if (!empty($userdata->time_of_birth)) {
    $timeOfBirth = date('h:i A', strtotime($userdata->time_of_birth));
}
?>
                        <?= $timeOfBirth; ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Place of Birth :
                    <span class="text-list prof-d01">
                        <?= showValue($userdata->place_of_birth ?? null); ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Religion :
                    <span class="text-list prof-d01">
                    
                          <?= showValue($religion_name == 'other' ? $userdata->other_religion :$religion_name); ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Caste :
                    <span class="text-list prof-d01">
                   
                         <?= showValue($caste_name == 'other' ? $userdata->other_caste :$caste_name); ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Mother Tongue :
                    <span class="text-list prof-d01">
                        
                    
                        
                          <?= showValue($userdata->mother_tongue == 'other' ? $userdata->other_mother_tongue :$userdata->mother_tongue); ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Other Languages Spoken :
                    <span class="text-list prof-d01">
                       <?= showValue($userdata->other_languages ?? null, 'None'); ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Family Home :
                    <span class="text-list prof-d01">
                        <?= showValue($userdata->family_home ?? null); ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Current Place of Residence :
                    <span class="text-list prof-d01">
                        <?= showValue($userdata->current_residence ?? null); ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Manglik Status :
                    <span class="text-list prof-d01">
                        <?= showValue($userdata->manglik_status == 'other' ? $userdata->other_manglik_status :$userdata->manglik_status); ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Need for Kundali Matching :
                    <span class="text-list prof-d01">
                        <?= showValue($userdata->kundali_matching ?? null); ?>
                    </span>
                </li>

                <li class="col-xl-6 prof-d">
                    Living in :
                    <span class="text-list prof-d01">
                       
                          <?= showValue($userdata->living_in == 'other' ? $userdata->other_living_in :$userdata->living_in); ?>
                    </span>
                </li>
                
                     

            </ul>
        </div>
    </div>
</div>
                                                        </div>
                                                        <?php endif; ?>

                                                        <!-- Educational Qualifications - Show only approved education records -->
                                                        <?php if(!empty($approvedEducation) ||  $usersession["user_id"] == $userdata->account_id): ?>
                                                        <div class="row">
                                                            <hr />
                                                            <div class="col-xl-12">
                                                                <h4 class="headtext">Educational Qualifications:</h4>
                                                            </div>
                                                            <div class="col-xl-12 mt-2 mb-2">
                                                                <div class="row">
                                                                    <div class="col-xl-12 label-text">
                                                                        <ul class="list-group view-list d-xl-flex flex-wrap align-items-start flex-row">
                                                                            <?php foreach($approvedEducation as $key => $edu): ?> 
                                                                            <li class="col-xl-4 prof-d">
                                                                                Education Level  (<?php  $key++;
                                                                                echo '#'.$key;
                                                                                ?>) :
                                                                                <span class="text-list prof-d01">
                                                                                    <?= !empty($edu['education_level']) ? $edu['qualification_name'] : 'Not specified'; ?>
                                                                                </span>
                                                                            </li>
                                                                            <li class="col-xl-4 prof-d">
                                                                                Institution :
                                                                                <span class="text-list prof-d01">
                                                                                    <?= !empty($edu['institution']) ? $edu['institution'] : 'Not specified'; ?>
                                                                                </span>
                                                                            </li>
                                                                            <li class="col-xl-4 prof-d">
                                                                                Qualification :
                                                                                <span class="text-list prof-d01">
                                                                                    <?= !empty($edu['degree']) ? $edu['degree'] : 'Not specified'; ?>
                                                                                </span>
                                                                            </li>
                                                                            <?php endforeach; ?>  
                                                                            
                                                                            
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>

                                                        <!-- Work Experience - Show only approved work records -->
                                                        <?php if(!empty($approvedWork) ||  $usersession["user_id"] == $userdata->account_id): ?>
                                                        <div class="row">
                                                            <hr />
                                                            <div class="col-xl-12">
                                                                <h4 class="headtext">Work Experience :</h4>
                                                            </div>
                                                            <div class="col-xl-12 mt-2 mb-2">
                                                                <div class="row">
                                                                    <?php foreach($approvedWork as $key => $work): ?>
                                                                    <div class="col-xl-12 label-text">
                                                                        <ul class="list-group view-list d-xl-flex flex-wrap align-items-start flex-row">
                                                                            <li class="col-xl-6 prof-d">Designation   (<?php  $key++;
                                                                                echo '#'.$key;
                                                                                ?>):<span class="text-list prof-d01"><?php echo $work->designation ?? 'Not specified'; ?></span></li>
                                                                            <li class="col-xl-6 prof-d">Company/Firm :<span class="text-list prof-d01"><?php echo $work->company ?? 'Not specified'; ?></span></li>
                                                                            <li class="col-xl-6 prof-d">Years Worked :<span class="text-list prof-d01"><?php echo $work->years_worked ?? 'Not specified'; ?></span></li>
                                                                           <?php if(!empty($work->ctc)) { ?> <li class="col-xl-6 prof-d">Current CTC :<span class="text-list prof-d01"><?php echo $work->ctc ?? 'Not specified'; ?></span></li> <?php } ?>
                                                                        </ul>
                                                                    </div>
                                                                    <?php endforeach; ?>
                                                                 
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-12 label-text">
                                                                    <ul class="list-group view-list d-xl-flex flex-wrap align-items-start flex-row">
                                                                          <?php if(isset($userdata->about_self) && !empty($userdata->about_self)): ?>
                                                                            <li class="col-xl-12 prof-d"><strong>About Yourself :</strong> <br>
                                                                                <p class="text-list pt-1 m-0"><?php echo nl2br(htmlspecialchars($userdata->about_self)); ?></p>
                                                                            </li>
                                                                            <?php endif; ?>
                                                                            </ul>
                                                                              </div>
                                                        </div>
                                                        <?php endif; ?>
                                                        
                                      

                                                        <!-- Family Members - Show only approved family members -->
                                                        <?php if(!empty($approvedFamilyMembers) ||  $usersession["user_id"] == $userdata->account_id): ?>
                                                        <div class="row">
                                                            <hr />
                                                            <div class="col-xl-12">
                                                                <h4 class="headtext">Family Members :</h4>
                                                            </div>
                                                            <div class="col-xl-12 mt-2 mb-2">
                                                                <div class="row">
                                                                    <?php foreach($approvedFamilyMembers as $key => $member): ?>
                                                                    <div class="col-xl-12 label-text">
                                                                        <ul class="list-group view-list d-xl-flex flex-wrap align-items-start flex-row">
                                                                            <li class="col-xl-6 prof-d">Relationship  (<?php  $key++;
                                                                                echo '#'.$key;
                                                                                ?>):<span class="text-list prof-d01"><?php echo $member->relationship == 'other' ? $member->other_relationship : $member->relationship; ?></span></li>
                                                                            <li class="col-xl-6 prof-d">Occupation :<span class="text-list prof-d01"><?php echo $member->occupation == 'other' ? $member->other_occupation : $member->occupation; ?></span></li>
                                                                        </ul>
                                                                    </div>
                                                                    <?php endforeach; ?>
                                                                            <div class="col-xl-12 label-text">
                                                                        <ul class="list-group view-list d-xl-flex flex-wrap align-items-start flex-row">
                                                                                   <?php if(isset($family_background->family_income) && !empty($family_background->family_income)): ?>
                                                                            <li class="col-xl-6 prof-d">Family Income :<span class="text-list prof-d01">₹<?php echo $family_background->family_income; ?></span></li>
                                                                            <?php endif; ?>
                                                                         <?php if(isset($family_background->about_family) && !empty($family_background->about_family)): ?>
<li class="col-xl-12 prof-d about-family-row">

    <strong>About Family :</strong>

    <div class="text-list prof-d01 about-family-text">
        <?php echo nl2br(htmlspecialchars($family_background->about_family)); ?>
    </div>

</li>
<?php endif; ?>
                                                                                </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>

                                                        <!-- Partner Preferences - Show if approved -->
                                                        <?php if($partnerApproved && isset($partner_preferences) && !empty($partner_preferences) ||  $usersession["user_id"] == $userdata->account_id): ?>
                                                        <div class="row">
                                                            <hr />
                                                            <div class="col-xl-12">
                                                                <h4 class="headtext">Partner Preferences :</h4>
                                                            </div>
                                                            <div class="col-xl-12 mt-2 mb-2">
                                                                <div class="row">
                                                                    <div class="col-xl-12 label-text">
                                                                        <ul class="list-group view-list d-xl-flex flex-wrap align-items-start flex-row">
                                                                            <li class="col-xl-6 prof-d">Age Range :<span class="text-list prof-d01"><?php echo $partner_preferences->age_from ?? ''; ?> to <?php echo $partner_preferences->age_to ?? ''; ?> years</span></li>
                                                                            <li class="col-xl-6 prof-d">Height Range :<span class="text-list prof-d01"><?php echo $partner_preferences->height_from ?? ''; ?> </span></li>
                                                                            <li class="col-xl-6 prof-d">Religion :<span class="text-list prof-d01"><?php echo $partner_religion_name; ?></span></li>
                                                                            <li class="col-xl-6 prof-d">Caste :<span class="text-list prof-d01"><?php echo $partner_caste_name; ?></span></li>
                                                                            <li class="col-xl-6 prof-d">Dietary Preference :<span class="text-list prof-d01"><?php echo $partner_preferences->dietary_preference ?? 'Not specified'; ?></span></li>
                                                                            <li class="col-xl-6 prof-d">Smoker :<span class="text-list prof-d01"><?php echo $partner_preferences->smoker ?? 'Not specified'; ?></span></li>
                                                                            <li class="col-xl-6 prof-d">Drinking Habit :<span class="text-list prof-d01"><?php echo $partner_preferences->drinking_habit ?? 'Not specified'; ?></span></li>
                                                                            <?php if(isset($partner_preferences->about_partner) && !empty($partner_preferences->about_partner)): ?>
<li class="col-xl-12 prof-d about-family-row">

    <strong>About Partner Preference :</strong>

    <div class="text-list prof-d01 about-family-text">
        <?php echo nl2br(htmlspecialchars($partner_preferences->about_partner)); ?>
    </div>

</li>
<?php endif; ?>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>

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
</section>
<!-- END PROFILE -->

<!-- INTEREST POPUP -->
<div class="modal fade" id="sendInter">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title seninter-tit">Send interest to <span class="intename"><?php echo $userdata->user_name ?? ''; ?></span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body seninter">
                <div class="lhs">
                    <img src="<?php echo $commanmodel->profile_image($userdata->account_id); ?>" alt="" class="intephoto1" />
                </div>
                <div class="rhs">
                    <h4><span class="intename1"><?php echo $userdata->user_name ?? ''; ?></span> Can able to view the below details</h4>
                    <ul>
                        <li>
                            <div class="chbox">
                                <input type="checkbox" id="pro_about" checked="" />
                                <label for="pro_about">About section</label>
                            </div>
                        </li>
                        <li>
                            <div class="chbox">
                                <input type="checkbox" id="pro_photo" />
                                <label for="pro_photo">Photo gallery</label>
                            </div>
                        </li>
                        <li>
                            <div class="chbox">
                                <input type="checkbox" id="pro_contact" />
                                <label for="pro_contact">Contact info</label>
                            </div>
                        </li>
                        <li>
                            <div class="chbox">
                                <input type="checkbox" id="pro_person" />
                                <label for="pro_person">Personal info</label>
                            </div>
                        </li>
                        <li>
                            <div class="chbox">
                                <input type="checkbox" id="pro_hobbi" />
                                <label for="pro_hobbi">Hobbies</label>
                            </div>
                        </li>
                        <li>
                            <div class="chbox">
                                <input type="checkbox" id="pro_social" />
                                <label for="pro_social">Social media</label>
                            </div>
                        </li>
                    </ul>
                    <div class="form-floating">
                        <textarea class="form-control" id="comment" name="text" placeholder="Comment goes here"></textarea>
                        <label for="comment">Write some message to <span class="intename"><?php echo $userdata->user_name ?? ''; ?></span></label>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Send interest</button>
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- END INTEREST POPUP -->

<!-- CHAT CONVERSATION BOX START -->
<div class="chatbox">
    <span class="comm-msg-pop-clo"><i class="fa fa-times" aria-hidden="true"></i></span>

    <div class="inn">
        <form name="new_chat_form" method="post" id="chatForm">
            <input type="hidden" name="receiver_id" id="receiver_id" value="">
            <div class="s1">
                <img src="<?php echo $commanmodel->profile_image($userdata->account_id); ?>" class="intephoto2" alt="">
                <h4><b><?php echo $userdata->user_name ?? ''; ?></b>,</h4>
                <span class="avlsta avilyes">Available online</span>
            </div>
            <div class="s2 chat-box-messages" id="chatMessages">
                <div class="chat-loading">Loading messages...</div>
            </div>
            <div class="s3">
                <input type="text" name="chat_message" id="chat_message" placeholder="Type a message here.." required>
                <button id="chat_send" name="chat_send" type="submit">Send <i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
            </div>
        </form>
    </div>
</div>
<!-- END -->

<!-- CONTACT DETAILS MODAL -->
<div class="modal fade" id="contactModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Contact Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center" id="contactModalBody">
        Loading...
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Close
        </button>
      </div>

    </div>
  </div>
</div>

<script src="<?php echo base_url('assets/frontend/'); ?>/assets/js/Chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
window.membershipStatus = "<?= isset($membership_status) && $membership_status === 'Active' ? 'Active' : 'Inactive'; ?>";
</script>

<script>
$(document).ready(function () {
    // CONTACT DETAILS CLICK
  $(document).on('click', '#viewContactBtn', function (e) {
    e.preventDefault();

    let userId = $(this).data('user-id');
    profile = <?php echo $userdata->account_id;?>


    // Make sure membershipStatus exists
    if (membershipStatus === 'Inactive' && profile != userId) {

        Swal.fire({
            icon: 'warning',
            title: 'Membership Required',
            text: 'To use this feature you are requested to become a paid member. Visit Manage Plan and select your plan.',
            confirmButtonText: 'View Plans',
            confirmButtonColor: '#F37254'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('plans'); ?>";
            }
        });

        return false;
    }

    // Membership Active → AJAX Call
    $.ajax({
        url: "<?= base_url('profile/getContactDetails'); ?>",
        type: "POST",
        dataType: "json",
        data: { user_id: userId },

        beforeSend: function () {
            $('#contactModalBody').html("Loading...");
            $('#contactModal').modal('show');
        },

        success: function (response) {

            if (response.status === true) {

                let mobile = response.mobile ? response.mobile : 'Not Available';
                let email  = response.email ? response.email : 'Not Available';

                $('#contactModalBody').html(`
                    <div style="font-size:16px;">
                        <p><strong>Mobile:</strong> ${mobile}</p>
                        <p><strong>Email:</strong> ${email}</p>
                    </div>
                `);

            } else {

                $('#contactModalBody').html(`
                    <p style="color:red;">${response.message ? response.message : 'Unable to fetch details.'}</p>
                `);
            }
        },

        error: function (xhr, status, error) {
            console.log(error);
            $('#contactModalBody').html(`
                <p style="color:red;">Error loading contact details. Please try again.</p>
            `);
        }
    });
});

    // Image Gallery Functionality
    const mainImg = document.querySelector('.main-img');
    const thumbs = document.querySelectorAll('.thumb');
    const popup = document.querySelector('.img-popup');
    const popupImg = document.querySelector('.popup-img');
    const closeBtn = document.querySelector('.close');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    
    let currentImageIndex = 0;
    let images = [];
    
    // Collect all images
    if (mainImg) images.push(mainImg.src);
    thumbs.forEach(thumb => {
        images.push(thumb.src);
    });
    
    // Thumbnail click
    thumbs.forEach((thumb, index) => {
        thumb.addEventListener('click', () => {
            if (mainImg) mainImg.src = thumb.src;
            currentImageIndex = index + 1;
        });
    });
    
    // Main image click
    if (mainImg) {
        mainImg.addEventListener('click', () => {
            popupImg.src = mainImg.src;
            currentImageIndex = 0;
            popup.style.display = 'flex';
        });
    }
    
    // Thumbnail click for popup
    thumbs.forEach((thumb, index) => {
        thumb.addEventListener('click', () => {
            popupImg.src = thumb.src;
            currentImageIndex = index + 1;
            popup.style.display = 'flex';
        });
    });
    
    // Close popup
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            popup.style.display = 'none';
        });
    }
    
    // Previous image
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            popupImg.src = images[currentImageIndex];
        });
    }
    
    // Next image
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            popupImg.src = images[currentImageIndex];
        });
    }

    // Send Interest
    $(document).on('click', '.send-interest', function() {
        let card = $(this).closest('.profile-card');
        let receiverId = $(this).data('receiver-id');

        $.ajax({
            url: "<?php echo base_url('interest/send'); ?>",
            type: "POST",
            data: {
                receiver_id: receiverId,
                message: 'Hi, I am interested in your profile.'
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    card.find('.status-message').text(response.message);
                    card.find('.send-interest')
                        .prop('disabled', true)
                        .text('Interest Sent');
                } else {
                    Swal.fire({
                        icon: 'warning',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong. Please try again later.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Accept Interest
    $(document).on('click', '.accept-interest', function() {
        let card = $(this).closest('.profile-card');
        let interestId = $(this).data('id');
        
        $.ajax({
            url: "<?php echo base_url('interest/accept'); ?>",
            type: "POST",
            data: {
                interest_id: interestId,
                action: 'accept'
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    card.find('.status-message').text(response.message);
                    card.find('.interest-actions').hide();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong. Please try again later.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Reject Interest
    $(document).on('click', '.reject-interest', function() {
        let card = $(this).closest('.profile-card');
        let interestId = $(this).data('id');

        $.ajax({
            url: "<?php echo base_url('interest/accept'); ?>",
            type: "POST",
            data: {
                interest_id: interestId,
                action: 'reject'
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'info',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    card.find('.status-message').text(response.message);
                    card.find('.interest-actions').hide();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong. Please try again later.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Chat Functionality
    var currentPartnerId = '';
    var currentPartnerName = '';

    // CHAT WINDOW OPEN
  $(".db-chat-trig").on('click', function () {

    currentPartnerId = $(this).data('partner-id');
    currentPartnerName = $(this).data('partner-name');

    $("#partner_name").text(currentPartnerName);
    $("#receiver_id").val(currentPartnerId);
    
    var partnerPhoto = $(this).find('img').attr('src');
    $("#partner_photo").attr('src', partnerPhoto);

    // ❌ Yahan se hata diya
    // $(".chatbox").addClass("open");

    loadChatMessages(currentPartnerId);
});

    // CLOSE CHAT WINDOW
    $(".comm-msg-pop-clo").on('click', function () {
        $(".chatbox").removeClass("open");
        currentPartnerId = '';
        currentPartnerName = '';
    });

    // SEND MESSAGE
    $("#chatForm").on('submit', function(e) {
        e.preventDefault();
        
        var message = $("#chat_message").val().trim();
        var receiverId = $("#receiver_id").val();
        
        if (message === '') {
            alert('Please enter a message');
            return;
        }
        
        if (receiverId === '') {
            alert('Please select a user to chat with');
            return;
        }
        
        sendMessage(receiverId, message);
    });

    // LOAD CHAT MESSAGES
  function loadChatMessages(partnerId) {

    $.ajax({
        url: '<?php echo base_url("chat/getChat/"); ?>/' + partnerId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {

            if (response.code == 'YOURSELF') {
 // ❌ Chatbox band hi rahe
                $(".chatbox").removeClass("open");
                
                  $("#receiver_id").val('');
                  currentPartnerId = '';

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });

                return;
                
               

            } else {
                                $(".chatbox").addClass("open");
 if (response.status) {
                    displayMessages(response.messages);
                    updateUnreadCount();
                } else {
                    $("#chatMessages").html('<div class="chat-error">' + response.message + '</div>');
                }

                // ✅ Chatbox yahin open karo

               
            }
        },
        error: function() {

            $(".chatbox").removeClass("open");

            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Error loading messages'
            });

            return;
        }
    });
}

    // DISPLAY MESSAGES
    function displayMessages(messages) {
        var messagesHtml = '';
        var currentUserId = '<?php echo $usersession["user_id"]; ?>';
        
        if (messages.length === 0) {
            messagesHtml = '<span class="chat-wel">Start a new conversation with this interested user!</span>';
        } else {
            messages.forEach(function(message) {
                var messageClass = message.sender_id == currentUserId ? 'chat-rhs' : 'chat-lhs';
                var messageTime = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                messagesHtml += '<div class="' + messageClass + '">' + 
                               '<div class="message-text">' + message.message + '</div>' +
                               '<div class="message-time">' + messageTime + '</div>' +
                               '</div>';
            });
        }
        
        $("#chatMessages").html(messagesHtml);
        $("#chatMessages").scrollTop($("#chatMessages")[0].scrollHeight);
    }

    // SEND MESSAGE
    function sendMessage(receiverId, message) {
        $.ajax({
            url: '<?php echo base_url("chat/sendMessage"); ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                receiver_id: receiverId,
                message: message
            },
            success: function(response) {
                if (response.status) {
                    $("#chat_message").val('');
                    loadChatMessages(receiverId);
                } else {
                    alert('Failed to send message: ' + response.message);
                }
            },
            error: function() {
                alert('Error sending message');
            }
        });
    }

    // UPDATE UNREAD COUNT
    function updateUnreadCount() {
        $.ajax({
            url: '<?php echo base_url("chat/getUnreadCount"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    console.log('Unread messages:', response.unread_count);
                }
            }
        });
    }

    // AUTO REFRESH MESSAGES EVERY 5 SECONDS
    setInterval(function() {
        if (currentPartnerId !== '') {
            loadChatMessages(currentPartnerId);
        }
    }, 5000);
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Sirf 'Other Languages Spoken' waale section ko target kar rahe hain
    const profItems = document.querySelectorAll('.prof-d');
    
    profItems.forEach(item => {
        if (item.innerText.includes('Other Languages Spoken')) {
            const span = item.querySelector('.prof-d01');
            if (span) {
                // Text se comma dhoond kar uske baad space add kar raha hai
                // Taki "Malayalam,Oriya" ban jaye "Malayalam, Oriya"
                let originalText = span.innerText;
                let formattedText = originalText.split(',').join(', ');
                span.innerText = formattedText;
                
                // CSS fix via JS taaki text word ke beech se na kate
                span.style.whiteSpace = "normal";
                span.style.wordBreak = "keep-all";
                span.style.display = "inline-block";
            }
        }
    });
});
</script>

<script id="c3v7pk">
document.addEventListener("DOMContentLoaded", function () {

    /* sirf Educational Qualifications wala section */
    let headings = document.querySelectorAll(".headtext");

    headings.forEach(function(head){

        if(head.innerText.trim() === "Educational Qualifications:"){

            let eduList = head.closest(".row").querySelector(".view-list");

            if(eduList){

                let items = eduList.querySelectorAll("li");

                items.forEach((item, index) => {

                    /* Education Level #2, #3, #4 niche start */
                    if(index > 0 && index % 3 === 0){

                        let breaker = document.createElement("div");
                        breaker.style.width = "100%";
                        breaker.style.flexBasis = "100%";

                        item.parentNode.insertBefore(breaker, item);
                    }

                });

            }

        }

    });

});
</script>


<style>
/* Additional styles for approved sections */

.verified-badge {
font-size: 12px;
  padding: 3px 8px;
  border-radius: 12px;
  margin-left: 8px;
  color: #fff;
  font-weight: 600;

  background-color: #1e73be;

}
/*.verified-badge {*/
/*  display: inline-block;*/
/*  margin-top: 0px;*/
/*  margin-bottom: 8px;*/
/*  padding: 3px 28px;*/
/*  background-color: #1e73be;*/
/*  color: #fff;*/
/*  font-size: 14px;*/
/*  border-radius: 20px;*/
/*  text-decoration: none;*/
/*  font-weight: 600;*/
/*}*/

/* Section visibility */
/*.profile-section {*/
/*    transition: all 0.3s ease;*/
/*}*/




</style>

<style>
.chat-loading, .chat-error {
    text-align: center;
    padding: 20px;
    color: #666;
}

.chat-lhs {
    background: #f1f1f1;
    padding: 10px;
    margin: 5px;
    border-radius: 10px;
    max-width: 70%;
    float: left;
    clear: both;
}

.chat-rhs {
    background: #007bff;
    color: white;
    padding: 10px;
    margin: 5px;
    border-radius: 10px;
    max-width: 70%;
    float: right;
    clear: both;
}

.chat-wel {
    text-align: center;
    display: block;
    padding: 20px;
    color: #666;
    font-style: italic;
}

.last-message {
    color: #666;
    font-size: 12px;
}

.message-time {
    font-size: 10px;
    text-align: right;
    margin-top: 5px;
    opacity: 0.7;
}

.no-chats {
    text-align: center;
    padding: 20px;
    color: #666;
}


.name-number h4.user-id {
    display: inline-block;
    font-size: 22px;
    font-weight: 700;
    color: #2c2c2c;
    border-radius: 2px;
    letter-spacing: 1px;
    font-family: "Times New Roman", serif !important;
}


.name-input {
    width: 348px;
}


@media screen and (max-width: 1400px) {
    .profi-bio {
        padding: 50px 20px 50px 20px;
    }
}

/* Mobile */
@media screen and (max-width: 768px) {
    .profi-bio {
        padding: 30px 0px 30px 0px;
    }
}






/* Desktop */
.view-list {
    padding: 0;
    margin: 0;
    list-style: none;
}

.view-list li.prof-d {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 8px;
    padding: 10px 15px;
    width: 50%;
    box-sizing: border-box;
}

.view-list li.prof-d span.prof-d01 {
    flex: 1;
    min-width: 0;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal;
}

/* Large Desktop */
@media screen and (min-width: 992px) {
    .view-list li.prof-d {
        width: 50%;
    }
}

/* Mobile */
@media screen and (max-width: 991px) {
    .view-list li.prof-d {
        width: 100%;
        padding: 8px 10px;
    }

    .view-list li.prof-d span.prof-d01 {
        display: block;
        width: 100%;
        /*margin-top: 4px;*/
        margin: 0px;
    }
}



/* Force Full Width */
.about-family-row{
    width:100% !important;
    max-width:100% !important;
    flex:0 0 100% !important;
    display:block !important;
}

.about-family-row strong{
    display:block;
    margin-bottom:8px;
}

.about-family-text{
    width:100% !important;
    display:block !important;
    white-space:normal;
    word-break:break-word;
    overflow-wrap:break-word;
    line-height:1.8;
}

/* Mobile */
@media screen and (max-width:768px){
    .about-family-row{
        padding:10px;
    }
}



.prof-d01 {
    word-break: keep-all !important; /* Word ko beech se nahi katne dega */
    overflow-wrap: anywhere !important; 
    white-space: normal !important;
}

.view-list .prof-d p {
    word-break: keep-all !important; /* Word ko beech se nahi katne dega */
    overflow-wrap: anywhere !important; 
    white-space: normal !important;
}


/* Sirf About Yourself section ko target karega */
.label-text .view-list > li.col-xl-12.prof-d{
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    flex: 0 0 100% !important;
}

.label-text .view-list > li.col-xl-12.prof-d strong{
    display: block !important;
    width: 100% !important;
    /*margin-bottom: 8px !important;*/
}

.label-text .view-list > li.col-xl-12.prof-d p.text-list{
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    white-space: normal !important;
    word-break: keep-all !important;
    overflow-wrap: anywhere !important;
}









</style>