<?php 
use App\Models\Commanmodel;
$commanmodel = new Commanmodel();
$session = session();



    if (!empty($userdata->profession)) {
        $profession_data = $commanmodel->get_single_query(
            'profession_categories',
            ['id' => $userdata->profession]
        );
        $profession_name = $profession_data->category_name ?? '';
    }
    
    
// Get religion name
$religion_data = $commanmodel->get_single_query('religions', array('id' => $userdata->religion_id ?? 0));
$religion_name = $religion_data->name ?? 'Not specified';

// Get caste name
$caste_data = $commanmodel->get_single_query('castes', array('id' => $userdata->caste_id ?? 0));
$caste_name = $caste_data->name ?? 'Not specified';

// Get partner preference religion and caste names
$partner_religion_data = $commanmodel->get_single_query('religions', array('id' => $partner_preferences->religion_id ?? 0));
$partner_religion_name = $partner_religion_data->name ?? 'Not specified';

$partner_caste_data = $commanmodel->get_single_query('castes', array('id' => $partner_preferences->caste_id ?? 0));
$partner_caste_name = $partner_caste_data->name ?? 'Not specified';

// Calculate pending counts
$db = \Config\Database::connect();
$user_id = $userdata->account_id;

// Education pending check
$educationPending = $db->table('user_education')
    ->where('user_id', $user_id)
    ->where('status', 'pending')
    ->countAllResults();

// Work pending check
$workPending = $db->table('user_work_experience')
    ->where('user_id', $user_id)
    ->where('status', 'pending')
    ->countAllResults();

// Family pending check
$familyPending = $db->table('user_family_members')
    ->where('user_id', $user_id)
    ->where('status', 'pending')
    ->countAllResults();

// Partner pending check
$partnerPending = $db->table('user_partner_preferences')
    ->where('user_id', $user_id)
    ->where('status', 'pending')
    ->countAllResults();

// Photo pending check
$photoPending = $db->table('user_photos')
    ->where('user_id', $user_id)
    ->where('status', 'pending')
    ->countAllResults();

// Personal status
$personalStatus = $userdata->personal_status ?? 'approved';
$verificationStatus = $userdata->verification_status ?? 'approved';

// Get all membership plans for dropdown with error handling
$membership_plans = [];
try {
    // Check if table exists
    $tableExists = $db->tableExists('membership');
    
    if ($tableExists) {
        $query = $db->table('membership')
            ->where('status', 'active')
            ->get();
        
        if ($query !== false) {
            $membership_plans = $query->getResult();
        }
    }
} catch (\Exception $e) {
    // Log error if needed
    log_message('error', 'Error fetching membership plans: ' . $e->getMessage());
    $membership_plans = [];
}

// If no plans found, provide default options
if (empty($membership_plans)) {
    // Create default plan objects for the dropdown
    $default_plan1 = new stdClass();
    $default_plan1->id = 1;
    $default_plan1->name = 'Basic Plan';
    $default_plan1->price = 999;
    $default_plan1->duration_months = 3;
    
    $default_plan2 = new stdClass();
    $default_plan2->id = 2;
    $default_plan2->name = 'Defence Plan';
    $default_plan2->price = 1995;
    $default_plan2->duration_months = 6;
    
    $default_plan3 = new stdClass();
    $default_plan3->id = 3;
    $default_plan3->name = 'Non-Defence Plan';
    $default_plan3->price = 3990 ;
    $default_plan3->duration_months = 6;
    
    $membership_plans = [$default_plan2, $default_plan3];
}

// Get account delete requests for this user
$deleteRequests = [];
try {
    $deleteRequests = $db->table('account_delete_requests')
        ->where('user_id', $user_id)
        ->orderBy('requested_at', 'DESC')
        ->get()
        ->getResult();
} catch (\Exception $e) {
    log_message('error', 'Error fetching delete requests: ' . $e->getMessage());
    $deleteRequests = [];
}

// Check if there's a pending delete request
$pendingDeleteRequest = null;
foreach ($deleteRequests as $request) {
    if ($request->status == 'pending') {
        $pendingDeleteRequest = $request;
        break;
    }
}
?> 

<!-- CONTENT WRAPPER -->
<div class="ec-content-wrapper">
    <div class="content">
        <div class="breadcrumb-wrapper breadcrumb-contacts">
            <div>
                <h1>User Profile - Complete Details</h1>
                <p class="breadcrumbs">
                    <span><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></span>
                    <span><i class="mdi mdi-chevron-right"></i></span>
                    <span><a href="<?php echo base_url('admin/customer'); ?>">Users</a></span>
                    <span><i class="mdi mdi-chevron-right"></i></span>
                    Profile Details
                </p>
            </div>
            <div>
                <a href="<?php echo base_url('admin/customer'); ?>" class="btn btn-outline-primary">
                    <i class="mdi mdi-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="ec-cat-list card card-default">
                    <div class="card-body">
                        <!-- User Profile Header -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <img style="margin-right: 15px;" src="<?php echo $commanmodel->profile_image($userdata->account_id); ?>" 
                                             class="rounded-circle bg-light" 
                                             width="100" height="100" alt="Profile Picture">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3 class="mb-1"><?php echo $userdata->user_name ?? ''; ?> <?php echo $userdata->user_last_name ?? ''; ?></h3>
                                        <p class="text-muted mb-1">
                                            Member ID: #<?php echo $userdata->user_id ?? ''; ?> | 
                                            Profile For: <?php echo $userdata->match_for ?? 'Self'; ?>
                                        </p>
                                        <p class="text-muted mb-0">
                                            <i class="mdi mdi-email-outline"></i> <?php echo $userdata->user_email ?? ''; ?> | 
                                            <i class="mdi mdi-phone"></i> <?php echo $userdata->user_phone ?? 'Not provided'; ?> |
                                            <i class="mdi mdi-phone-classic"></i> <?php echo $userdata->home_phone ?? 'Not provided'; ?>
                                        </p>
                                        <div class="mt-2">
                                            <!-- ✅ Approval Button -->
                                            <?php
                                            // Approval Button
                                            $approvalClass = ($userdata->approval == 'Approved') ? 'btn-success' : 'btn-danger';
                                            $approvalNext  = ($userdata->approval == 'Approved') ? 'Unapproved' : 'Approved';

                                            echo '<button class="btn '.$approvalClass.' btn-sm changeMainStatus"  data-id="'.$userdata->account_id.'"   data-status="'.($userdata->approval == 'Unapproved' ? 'Approved':'Unapproved').'">
            '.$userdata->approval.'
        </button>
                                            
                                            
                                            ';

                                            // Verified Button
                                            $verifiedClass = ($userdata->verified == 'Yes') ? 'btn-success' : 'btn-danger';
                                            $verifiedNext  = ($userdata->verified == 'Yes') ? 'No' : 'Yes';

                                            echo '<button type="button"
                                                    class="btn btn-sm '.$verifiedClass.' change-status me-1"
                                                    data-id="'.$userdata->account_id.'"
                                                    data-type="verified"
                                                    data-current="'.$userdata->verified.'"
                                                    data-next="'.$verifiedNext.'">
                                                    Verified: '.$userdata->verified.'
                                                  </button>';
                                            ?>

                                            <!-- ✅ Batch Dropdown -->
                                            <select id="batchSelect_<?php echo $userdata->account_id; ?>" 
                                                    class="form-select form-select-sm d-inline-block w-auto ms-2">
                                                <option value="">Select Batch</option>
                                                <option value="Green" <?php if($userdata->batch=="Green") echo "selected"; ?>>Green</option>
                                                <option value="Orange" <?php if($userdata->batch=="Orange") echo "selected"; ?>>Orange</option>
                                                <option value="Blue" <?php if($userdata->batch=="Blue") echo "selected"; ?>>Blue</option>
                                            </select>

                                            <!-- ✅ Update Batch Button -->
                                            <button class="btn btn-sm btn-primary ms-2 update-batch-btn"
                                                    data-id="<?php echo $userdata->account_id; ?>">
                                                Update Batch
                                            </button>

                                            <!-- ✅ Message -->
                                            <span id="batchMsg_<?php echo $userdata->account_id; ?>" class="ms-2"></span>

                                            <!-- ✅ Registered Badge -->
                                            <span class="badge bg-info ms-2">
                                                Registered: <?php echo date('M d, Y', strtotime($userdata->created_at ?? '')); ?>
                                            </span>
                                            
                                            <!-- ✅ Delete Request Status -->
                                            <?php if ($pendingDeleteRequest): ?>
                                            <span class="badge bg-danger ms-2">
                                                <i class="mdi mdi-alert"></i> Delete Request Pending
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Badges Section -->
                       <div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="card-title mb-0">Status Management (Click to Update)</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">

                    <!-- Personal Status Badge -->
                    <?php
                    $personalBadgeClass = ($personalStatus == 'pending') 
                        ? 'bg-danger' 
                        : (($personalStatus == 'rejected') ? 'bg-danger' : 'bg-success');
                    ?>
                    <span class="badge <?php echo $personalBadgeClass; ?> status-badge" 
                        data-id="<?php echo $userdata->account_id; ?>" 
                        data-type="personal" 
                        data-current-status="<?php echo $personalStatus; ?>"
                        style="cursor:pointer; padding:8px 12px; font-size:14px;">
                        Personal: <?php echo ucfirst($personalStatus); ?>
                    </span>

                    <!-- Verification Status Badge -->
                    <?php
                    $verificationBadgeClass = ($verificationStatus == 'pending') 
                        ? 'bg-danger' 
                        : (($verificationStatus == 'rejected') ? 'bg-danger' : 'bg-success');
                    ?>
                    <span class="badge <?php echo $verificationBadgeClass; ?> status-badge" 
                        data-id="<?php echo $userdata->account_id; ?>" 
                        data-type="verification_status" 
                        data-current-status="<?php echo $verificationStatus; ?>"
                        style="cursor:pointer; padding:8px 12px; font-size:14px;">
                        Verification: <?php echo ucfirst($verificationStatus); ?>
                    </span>

                    <!-- Education Status Badge -->
                    <?php if ($educationPending > 0): ?>
                        <span class="badge bg-danger status-badge" 
                            data-id="<?php echo $userdata->account_id; ?>" 
                            data-type="education" 
                            data-current-status="pending"
                            style="cursor:pointer; padding:8px 12px; font-size:14px;">
                            Education: <?php echo $educationPending; ?> Pending
                        </span>
                    <?php else: ?>
                        <span class="badge bg-success" style="padding:8px 12px; font-size:14px;">
                            Education: Approved
                        </span>
                    <?php endif; ?>

                    <!-- Work Status Badge -->
                    <?php if ($workPending > 0): ?>
                        <span class="badge bg-danger status-badge" 
                            data-id="<?php echo $userdata->account_id; ?>" 
                            data-type="work" 
                            data-current-status="pending"
                            style="cursor:pointer; padding:8px 12px; font-size:14px;">
                            Work: <?php echo $workPending; ?> Pending
                        </span>
                    <?php else: ?>
                        <span class="badge bg-success" style="padding:8px 12px; font-size:14px;">
                            Work: Approved
                        </span>
                    <?php endif; ?>

                    <!-- Family Status Badge -->
                    <?php if ($familyPending > 0): ?>
                        <span class="badge bg-danger status-badge" 
                            data-id="<?php echo $userdata->account_id; ?>" 
                            data-type="family" 
                            data-current-status="pending"
                            style="cursor:pointer; padding:8px 12px; font-size:14px;">
                            Family: <?php echo $familyPending; ?> Pending
                        </span>
                    <?php else: ?>
                        <span class="badge bg-success" style="padding:8px 12px; font-size:14px;">
                            Family: Approved
                        </span>
                    <?php endif; ?>

                    <!-- Partner Status Badge -->
                    <?php if ($partnerPending > 0): ?>
                        <span class="badge bg-danger status-badge" 
                            data-id="<?php echo $userdata->account_id; ?>" 
                            data-type="partner" 
                            data-current-status="pending"
                            style="cursor:pointer; padding:8px 12px; font-size:14px;">
                            Partner: <?php echo $partnerPending; ?> Pending
                        </span>
                    <?php else: ?>
                        <span class="badge bg-success" style="padding:8px 12px; font-size:14px;">
                            Partner: Approved
                        </span>
                    <?php endif; ?>

                    <!-- Photo Status Badge -->
                    <?php if ($photoPending > 0): ?>
                        <span class="badge bg-danger status-badge" 
                            data-id="<?php echo $userdata->account_id; ?>" 
                            data-type="photo" 
                            data-current-status="pending"
                            style="cursor:pointer; padding:8px 12px; font-size:14px;">
                            Photo: <?php echo $photoPending; ?> Pending
                        </span>
                    <?php else: ?>
                        <span class="badge bg-success" style="padding:8px 12px; font-size:14px;">
                            Photo: Approved
                        </span>
                    <?php endif; ?>

                    <!-- Bulk Update Button -->
                    <button class="btn btn-sm btn-warning bulk-update-all" 
                            data-id="<?php echo $userdata->account_id; ?>">
                        <i class="mdi mdi-check-all"></i> Approve All
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                                    <i class="mdi mdi-account-circle"></i> Basic Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button" role="tab">
                                    <i class="mdi mdi-school"></i> Education & Career
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="family-tab" data-bs-toggle="tab" data-bs-target="#family" type="button" role="tab">
                                    <i class="mdi mdi-account-group"></i> Family Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="partner-tab" data-bs-toggle="tab" data-bs-target="#partner" type="button" role="tab">
                                    <i class="mdi mdi-heart"></i> Partner Preferences
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos" type="button" role="tab">
                                    <i class="mdi mdi-image-multiple"></i> Photos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                                    <i class="mdi mdi-account-cog"></i> Account Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="membership-tab" data-bs-toggle="tab" data-bs-target="#membership" type="button" role="tab">
                                    <i class="mdi mdi-crown"></i> Membership Management
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete" type="button" role="tab">
                                    <i class="mdi mdi-delete"></i> Delete Requests
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="profileTabContent">
                            
                            <!-- Basic Information Tab -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="card-title mb-0">Personal Details</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td width="40%"><strong>Full Name:</strong></td>
                                                        <td><?php echo $userdata->user_name ?? ''; ?> <?php echo $userdata->user_last_name ?? ''; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Gender:</strong></td>
                                                        <td><?php echo ucfirst($userdata->gender ?? 'Not specified'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Marital Status:</strong></td>
                                                        <td><?php echo $userdata->marital_status ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Date of Birth:</strong></td>
                                                        <td>
                                                            <?php 
                                                            if(isset($userdata->date_of_birth) && !empty($userdata->date_of_birth)) {
                                                                echo date('d M Y', strtotime($userdata->date_of_birth));
                                                            } else {
                                                                echo 'Not specified';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Age:</strong></td>
                                                        <td>
                                                            <?php 
                                                            if(isset($userdata->date_of_birth) && !empty($userdata->date_of_birth)) {
                                                                $birthDate = new DateTime($userdata->date_of_birth);
                                                                $today = new DateTime();
                                                                echo $today->diff($birthDate)->y . ' years';
                                                            } else {
                                                                echo 'Not specified';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    
                                                     <tr>
                                                        <td><strong>Profession:</strong></td>
                                                        <td><?php echo $userdata->profession != 'other' ? $profession_name ?? '' :  $userdata->other_profession; ?></td>
                                                    </tr>
                                                     <tr>
                                                        <td><strong>Living:</strong></td>
                                                        <td><?php echo $userdata->living_in ?? $userdata->other_living_in; ?> </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td><strong>Height:</strong></td>
                                                        <td><?php echo $userdata->height ?? 'Not specified'; ?> ft <?php echo $userdata->height_inch ?? 'Not specified'; ?> inch</td>
                                                    </tr>
                                                   
                                                    <tr>
                                                        <td><strong>About Self:</strong></td>
                                                        <td><?php echo nl2br(htmlspecialchars($userdata->about_self ?? '')); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="card mb-3">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="card-title mb-0">Contact Details</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td width="40%"><strong>Email:</strong></td>
                                                        <td><?php echo $userdata->user_email ?? ''; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Mobile:</strong></td>
                                                        <td><?php echo $userdata->user_phone ?? 'Not provided'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Home Phone:</strong></td>
                                                        <td><?php echo $userdata->home_phone ?? 'Not provided'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Family Home:</strong></td>
                                                        <td><?php echo $userdata->family_home ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Current Residence:</strong></td>
                                                        <td><?php echo $userdata->current_residence ?? 'Not specified'; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="card-title mb-0">Cultural & Religious Details</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td width="40%"><strong>Religion:</strong></td>
                                                        <td><?php echo $religion_name; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Caste:</strong></td>
                                                        <td><?php echo $caste_name ?? $userdata->other_caste; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Mother Tongue:</strong></td>
                                                        <td><?php echo $userdata->mother_tongue ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Other Languages:</strong></td>
                                                        <td><?php echo $userdata->other_languages ?? 'None'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Dietary Preference:</strong></td>
                                                        <td><?php echo $userdata->dietary_preference ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Manglik Status:</strong></td>
                                                        <td><?php echo $userdata->manglik_status ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Kundali Matching:</strong></td>
                                                        <td><?php echo $userdata->kundali_matching ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Place of Birth:</strong></td>
                                                        <td><?php echo $userdata->place_of_birth ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Time of Birth:</strong></td>
                                                        <td><?php echo $userdata->time_of_birth ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    
                                                    
                                                    
                                                </table>
                                            </div>
                                        </div>

                                        <div class="card mb-3">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="card-title mb-0">Verification Details</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td width="40%"><strong>Introducer Name:</strong></td>
                                                        <td><?php echo $userdata->introducer_name ?? 'Not provided'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Verification Relationship:</strong></td>
                                                        <td><?php echo $userdata->verification_relationship ?? 'Not provided'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Document Type:</strong></td>
                                                        <td><?php echo $userdata->document_type ?? 'Not provided'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Documents:</strong></td>
                                                        <td>
<?php if(!empty($userdata->documents)): ?>

    <?php 
        $docs = json_decode($userdata->documents, true);
        $docPath = is_array($docs) ? $docs[0] : $userdata->documents;
    ?>

    <a href="<?php echo base_url('assets/'.$docPath); ?>" 
       target="_blank" 
       class="btn btn-sm btn-outline-primary">
        <i class="mdi mdi-file-document"></i> View Document
    </a>

<?php else: ?>
    Not uploaded
<?php endif; ?>
</td>

                                                    </tr>
                                          
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Education & Career Tab -->
                            <div class="tab-pane fade" id="education" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="card-title mb-0">Education Details</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td width="40%"><strong>Highest Education:</strong></td>
                                                        <td><?php echo $education_data->highest_education ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Education Level:</strong></td>
                                                        <td><?php echo $education_data->education_level ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Institution:</strong></td>
                                                        <td><?php echo $education_data->institution ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Degree/Certificate:</strong></td>
                                                        <td><?php echo $education_data->degree ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Profession:</strong></td>
                                                        <td><?php echo $education_data->profession ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Work Experience:</strong></td>
                                                        <td><?php echo $education_data->work_experience ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Annual Income:</strong></td>
                                                        <td>
                                                            <?php 
                                                            if(isset($education_data->income) && !empty($education_data->income)) {
                                                                echo '₹' . $education_data->income;
                                                            } else {
                                                                echo 'Not specified';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>About Self (Career):</strong></td>
                                                        <td><?php echo nl2br(htmlspecialchars($education_data->about_self ?? '')); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="card-title mb-0">Work Experience</h6>
                                            </div>
                                            <div class="card-body">
                                                <?php if(isset($work_experience) && !empty($work_experience)): ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-bordered">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Designation</th>
                                                                    <th>Company</th>
                                                                    <th>Years Worked</th>
                                                                    <th>CTC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach($work_experience as $work): ?>
                                                                    <tr>
                                                                        <td><?php echo $work->designation; ?></td>
                                                                        <td><?php echo $work->company; ?></td>
                                                                        <td><?php echo $work->years_worked ?? 'N/A'; ?></td>
                                                                        <td><?php echo $work->ctc ?? 'N/A'; ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <p class="text-muted">No work experience added.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="card mb-3">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="card-title mb-0">Additional Professional Info</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td width="40%"><strong>Profession (from account):</strong></td>
                                                        <td><?php echo $userdata->profession ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Profile For:</strong></td>
                                                        <td><?php echo $userdata->match_for ?? 'Self'; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Family Details Tab -->
                            <div class="tab-pane fade" id="family" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="card-title mb-0">Family Background</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                               
                                                    <tr>
                                                        <td><strong>Monthly Family Income:</strong></td>
                                                        <td>
                                                            <?php 
                                                            if(isset($family_background->family_income) && !empty($family_background->family_income)) {
                                                                echo '₹' . $family_background->family_income;
                                                            } else {
                                                                echo 'Not specified';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td><strong>About Family:</strong></td>
                                                        <td><?php echo nl2br(htmlspecialchars($family_background->about_family ?? '')); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="card-title mb-0">Family Members</h6>
                                            </div>
                                            <div class="card-body">
                                                <?php if(isset($family_members) && !empty($family_members)): ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-bordered">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Relationship</th>
                                                                    <th>Occupation</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach($family_members as $member): ?>
                                                                    <tr>
                                                                        <td><?php echo ucfirst($member->relationship); ?></td>
                                                                        <td><?php echo $member->occupation ?? 'N/A'; ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <p class="text-muted">No family members added.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Partner Preferences Tab -->
                            <div class="tab-pane fade" id="partner" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="card-title mb-0">Basic Preferences</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td width="40%"><strong>Age Range:</strong></td>
                                                        <td>
                                                            <?php 
                                                            $age_from = $partner_preferences->age_from ?? '';
                                                            $age_to = $partner_preferences->age_to ?? '';
                                                            if(!empty($age_from) && !empty($age_to)) {
                                                                echo $age_from . ' - ' . $age_to . ' years';
                                                            } else {
                                                                echo 'Not specified';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Height Range:</strong></td>
                                                        <td>
                                                            <?php 
                                                            $height_from = $partner_preferences->height_from ?? '';
                                                            $height_to = $partner_preferences->height_to ?? '';
                                                            if(!empty($height_from) && !empty($height_to)) {
                                                                echo $height_from;
                                                            } else {
                                                                echo 'Not specified';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Religion:</strong></td>
                                                        <td><?php echo $partner_religion_name; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Caste:</strong></td>
                                                        <td><?php echo $partner_caste_name; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Dietary Preference:</strong></td>
                                                        <td><?php echo $partner_preferences->dietary_preference ?? 'Not specified'; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="card-title mb-0">Lifestyle Preferences</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td width="40%"><strong>Smoking:</strong></td>
                                                        <td><?php echo $partner_preferences->smoker ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Drinking Habit:</strong></td>
                                                        <td><?php echo $partner_preferences->drinking_habit ?? 'Not specified'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>About Partner Preference:</strong><br>
                                                            <?php echo nl2br(htmlspecialchars($partner_preferences->about_partner ?? '')); ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Photos Tab -->
                            <div class="tab-pane fade" id="photos" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                       <?php if(isset($user_photos) && !empty($user_photos)): ?>
<div class="card">
    <div class="card-header bg-dark text-white">
        <h6 class="card-title mb-0">Photo Gallery</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <?php foreach($user_photos as $photo): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <a href="<?php echo base_url('assets/uploads/' . $photo->photo_path); ?>" data-lightbox="user-photos">
                            <img src="<?php echo base_url('assets/uploads/' . $photo->photo_path); ?>" 
                                 class="card-img-top img-thumbnail" 
                                 style="height: 200px; object-fit: cover;" 
                                 alt="User Photo">
                        </a>

                        <div class="card-footer text-center">

                            <small class="text-muted d-block mb-2">
                                Added: <?php echo date('d M Y', strtotime($photo->created_at)); ?>
                            </small>

                            <!-- DELETE BUTTON -->
                            <button class="btn btn-danger btn-sm deletePhotoBtn"
                                data-id="<?php echo $photo->id; ?>">
                                <i class="mdi mdi-delete"></i> Delete
                            </button>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-info">
    <i class="mdi mdi-information"></i> No photos uploaded yet.
</div>
<?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Info Tab -->
                            <div class="tab-pane fade" id="account" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="card-title mb-0">Account Status</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td><strong>Approval Status:</strong></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo ($userdata->approval ?? '') == 'Approved' ? 'success' : 'danger'; ?>">
                                                                <?php echo $userdata->approval ?? 'Unapproved'; ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>User Type:</strong></td>
                                                        <td><?php echo ucfirst($userdata->user_type ?? 'regular'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Registration Date:</strong></td>
                                                        <td><?php echo date('d M Y, h:i A', strtotime($userdata->created_at ?? '')); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Last Updated:</strong></td>
                                                        <td><?php echo date('d M Y, h:i A', strtotime($userdata->updated_at ?? '')); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Last Login:</strong></td>
                                                        <td>
                                                            <?php 
                                                            if(!empty($userdata->last_login)) {
                                                                echo date('d M Y, h:i A', strtotime($userdata->last_login));
                                                            } else {
                                                                echo 'Never logged in';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card mb-3">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="card-title mb-0">Activity Stats</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-md-4">
                                                        <div class="border rounded p-3">
                                                            <h4 class="text-primary"><?php echo $userdata->profile_views ?? '0'; ?></h4>
                                                            <small class="text-muted">Profile Views</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="border rounded p-3">
                                                            <h4 class="text-success"><?php echo $userdata->contacts_made ?? '0'; ?></h4>
                                                            <small class="text-muted">Contacts Made</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="border rounded p-3">
                                                            <h4 class="text-warning"><?php echo $userdata->interest_shown ?? '0'; ?></h4>
                                                            <small class="text-muted">Interests Shown</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Membership Management Tab -->
                            <div class="tab-pane fade" id="membership" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card mb-3">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="card-title mb-0">Manual Membership Management</h6>
                                            </div>
                                            <div class="card-body">
                                                <form id="membershipUpdateForm">
                                                    <input type="hidden" name="user_id" value="<?php echo $userdata->account_id; ?>">
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="membership_plan" class="form-label">Membership Plan</label>
                                                            <select class="form-control" id="membership_plan" name="membership_plan">
                                                                <option value="">Select Plan</option>
                                                                <?php if(!empty($membership_plans)): ?>
                                                                    <?php foreach($membership_plans as $plan): ?>
                                                                    <option value="<?php echo $plan->id; ?>" 
                                                                            data-price="<?php echo $plan->price; ?>"
                                                                            data-months="<?php echo $plan->duration_months; ?>"
                                                                            <?php echo (isset($userdata->membership_plan_id) && $userdata->membership_plan_id == $plan->id) ? 'selected' : ''; ?>>
                                                                        <?php echo $plan->name; ?> - ₹<?php echo $plan->price; ?> (<?php echo $plan->duration_months; ?> months)
                                                                    </option>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <option value="1" data-price="999" data-months="3">Basic Plan - ₹999 (3 months)</option>
                                                                    <option value="2" data-price="1999" data-months="6">Standard Plan - ₹1999 (6 months)</option>
                                                                    <option value="3" data-price="2999" data-months="12">Premium Plan - ₹2999 (12 months)</option>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="membership_price" class="form-label">Price (₹)</label>
                                                            <input type="number" class="form-control" id="membership_price" name="membership_price" 
                                                                   value="<?php echo $userdata->membership_price ?? ''; ?>" placeholder="Enter price">
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="membership_start_date" class="form-label">Start Date</label>
                                                            <input type="date" class="form-control" id="membership_start_date" name="membership_start_date" 
                                                                   value="<?php echo !empty($userdata->membership_start_date) ? date('Y-m-d', strtotime($userdata->membership_start_date)) : ''; ?>">
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="membership_end_date" class="form-label">End Date</label>
                                                            <input type="date" class="form-control" id="membership_end_date" name="membership_end_date" 
                                                                   value="<?php echo !empty($userdata->membership_end_date) ? date('Y-m-d', strtotime($userdata->membership_end_date)) : ''; ?>">
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="membership_status" class="form-label">Membership Status</label>
                                                            <select class="form-control" id="membership_status" name="membership_status">
                                                                <option value="Active" <?php echo (isset($userdata->membership_status) && $userdata->membership_status == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                                <option value="Expired" <?php echo (isset($userdata->membership_status) && $userdata->membership_status == 'Expired') ? 'selected' : ''; ?>>Expired</option>
                                                                <option value="Pending" <?php echo (isset($userdata->membership_status) && $userdata->membership_status == 'Pending') ? 'selected' : ''; ?>>Inactive</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="membership_duration" class="form-label">Duration (Months)</label>
                                                            <input type="number" class="form-control" id="membership_duration" name="membership_duration" 
                                                                   value="<?php echo $userdata->membership_month ?? ''; ?>" placeholder="Enter duration in months">
                                                        </div>
                                                        
                                                        <div class="col-md-12 mb-3">
                                                            <label for="membership_notes" class="form-label">Notes (Optional)</label>
                                                            <textarea class="form-control" id="membership_notes" name="membership_notes" rows="3" placeholder="Add any notes about this membership"><?php echo $userdata->membership_notes ?? ''; ?></textarea>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <button type="button" class="btn btn-success" id="updateMembershipBtn">
                                                                <i class="mdi mdi-content-save"></i> Update Membership
                                                            </button>
                                                            <button type="button" class="btn btn-info" id="calculateEndDateBtn">
                                                                <i class="mdi mdi-calculator"></i> Calculate End Date
                                                            </button>
                                                            <button type="button" class="btn btn-danger" id="resetMembershipBtn">
                                                                <i class="mdi mdi-cancel"></i> Reset Membership
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        
                                        <!-- Current Membership Summary -->
                                        <div class="card mb-3">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="card-title mb-0">Current Membership Summary</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th width="30%">Plan Name:</th>
                                                        <td><?php echo $userdata->membership_name ?? 'No Plan'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Price:</th>
                                                        <td>₹<?php echo $userdata->membership_price ?? '0'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Start Date:</th>
                                                        <td><?php echo !empty($userdata->membership_start_date) ? date('d M Y', strtotime($userdata->membership_start_date)) : 'N/A'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>End Date:</th>
                                                        <td><?php echo !empty($userdata->membership_end_date) ? date('d M Y', strtotime($userdata->membership_end_date)) : 'N/A'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Duration:</th>
                                                        <td><?php echo $userdata->membership_month ?? '0'; ?> months</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status:</th>
                                                        <td>
                                                            <span class="badge bg-<?php 
                                                                echo (isset($userdata->membership_status) && $userdata->membership_status == 'Active') ? 'success' : 
                                                                    ((isset($userdata->membership_status) && $userdata->membership_status == 'Expired') ? 'danger' : 
                                                                    ((isset($userdata->membership_status) && $userdata->membership_status == 'Pending') ? 'warning' : 'warning')); 
                                                            ?>">
                                                                <?php echo ucfirst($userdata->membership_status ?? 'inactive'); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <?php if(!empty($userdata->membership_notes)): ?>
                                                    <tr>
                                                        <th>Notes:</th>
                                                        <td><?php echo nl2br(htmlspecialchars($userdata->membership_notes)); ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Requests Tab -->
                            <div class="tab-pane fade" id="delete" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card mb-3">
                                            <div class="card-header bg-danger text-white">
                                                <h6 class="card-title mb-0">Account Delete Requests</h6>
                                            </div>
                                            <div class="card-body">
                                                <?php if(!empty($deleteRequests)): ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Request Date</th>
                                                                    <th>Reason</th>
                                                                    <th>Status</th>
                                                                    <th>Processed At</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach($deleteRequests as $request): ?>
                                                                    <tr>
                                                                        <td>#<?php echo $request->id; ?></td>
                                                                        <td><?php echo date('d M Y, h:i A', strtotime($request->requested_at)); ?></td>
                                                                        <td>
                                                                            <?php 
                                                                            if(!empty($request->reason)) {
                                                                                echo nl2br(htmlspecialchars($request->reason));
                                                                            } else {
                                                                                echo '<em>No reason provided</em>';
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php
                                                                            $statusClass = '';
                                                                            $statusText = '';
                                                                            if($request->status == 'pending') {
                                                                                $statusClass = 'bg-warning';
                                                                                $statusText = 'Pending';
                                                                            } elseif($request->status == 'approved') {
                                                                                $statusClass = 'bg-success';
                                                                                $statusText = 'Approved';
                                                                            } elseif($request->status == 'rejected') {
                                                                                $statusClass = 'bg-danger';
                                                                                $statusText = 'Rejected';
                                                                            } else {
                                                                                $statusClass = 'bg-secondary';
                                                                                $statusText = ucfirst($request->status);
                                                                            }
                                                                            ?>
                                                                            <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                                                        </td>
                                                                        <td>
                                                                            <?php 
                                                                            if(!empty($request->processed_at)) {
                                                                                echo date('d M Y, h:i A', strtotime($request->processed_at));
                                                                            } else {
                                                                                echo '-';
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if($request->status == 'pending'): ?>
                                                                                <button class="btn btn-sm btn-success approve-delete" 
                                                                                        data-id="<?php echo $request->id; ?>"
                                                                                        data-user-id="<?php echo $userdata->account_id; ?>"
                                                                                        data-user-name="<?php echo $userdata->user_name . ' ' . $userdata->user_last_name; ?>">
                                                                                    <i class="mdi mdi-check"></i> Approve & Delete
                                                                                </button>
                                                                                <button class="btn btn-sm btn-danger reject-delete" 
                                                                                        data-id="<?php echo $request->id; ?>"
                                                                                        data-user-id="<?php echo $userdata->account_id; ?>">
                                                                                    <i class="mdi mdi-close"></i> Reject
                                                                                </button>
                                                                            <?php else: ?>
                                                                                <button class="btn btn-sm btn-secondary" disabled>
                                                                                    <i class="mdi mdi-lock"></i> Processed
                                                                                </button>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="alert alert-info">
                                                        <i class="mdi mdi-information"></i> No account delete requests found for this user.
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                       <button class="print-trigger" onclick="window.print();">
    <i class="mdi mdi-printer"></i> Print Profile
</button>
                                    </div>
                                    <div>
                                        <?php if(!$pendingDeleteRequest): ?>
                                        <a href="<?php echo base_url('admin/users/delete/' . $userdata->account_id); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')">
                                            <i class="mdi mdi-delete"></i> Permanently Delete User
                                        </a>
                                        <?php else: ?>
                                        <button class="btn btn-danger" disabled title="Cannot delete user with pending delete request">
                                            <i class="mdi mdi-delete"></i> Delete Disabled (Pending Request)
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Content -->
</div> <!-- End Content Wrapper -->

<!-- Include required CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).on('click', '.deletePhotoBtn', function(){

    var photo_id = $(this).data('id');

    if(confirm('Are you sure you want to delete this photo?')){

        $.ajax({
            url: "<?= base_url('admin/delete_user_photo'); ?>",
            type: "POST",
            data: { id: photo_id },
            success: function(response){
                location.reload();
            }
        });

    }
});
</script>
<script>
$(document).ready(function () {

    // Initialize lightbox
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': "Image %1 of %2"
    });

    // ========== STATUS CHANGE (Approval/Verified) ==========
    $(document).on('click', '.change-status', function () {
        var button = $(this);
        var user_id = button.data('id');
        var type = button.data('type');
        var currentStatus = button.data('current');
        var newStatus = button.data('next');

        var label = (type === 'approval') ? 'Approval' : 'Verification';

        Swal.fire({
            title: 'Change ' + label + '?',
            text: 'Do you want to change to ' + newStatus + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false, // Prevent closing when clicking outside
            allowEscapeKey: false     // Prevent closing with escape key
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('admin/update_all_statuses'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        user_id: user_id,
                        status_type: type,
                        new_status: newStatus
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Updating...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: label + ' changed to ' + response.new_status,
                                timer: 1500,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });

                            // Update button UI
                            button.data('current', response.new_status);
                            if (type === 'approval') {
                                button.data('next', (response.new_status === 'Approved') ? 'Unapproved' : 'Approved');
                                button.text(response.new_status);
                                if (response.new_status === 'Approved') {
                                    button.removeClass('btn-danger').addClass('btn-success');
                                } else {
                                    button.removeClass('btn-success').addClass('btn-danger');
                                }
                            } else if (type === 'verified') {
                                button.data('next', (response.new_status === 'Yes') ? 'No' : 'Yes');
                                button.text('Verified: ' + response.new_status);
                                if (response.new_status === 'Yes') {
                                    button.removeClass('btn-danger').addClass('btn-success');
                                } else {
                                    button.removeClass('btn-success').addClass('btn-danger');
                                }
                            }
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Something went wrong!',
                            icon: 'error',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                });
            }
        });
    });

    // ========== STATUS BADGE CLICK (Update individual statuses) ==========
    $(document).on('click', '.status-badge', function() {
        var badge = $(this);
        var user_id = badge.data('id');
        var status_type = badge.data('type');
        var currentStatus = badge.data('current-status');
        
        Swal.fire({
            title: 'Update ' + status_type.charAt(0).toUpperCase() + status_type.slice(1) + ' Status',
            text: 'Current status: ' + currentStatus,
            icon: 'question',
            showCancelButton: true,
       
            confirmButtonText: 'Approved',
          
            cancelButtonText: 'Pending',
            confirmButtonColor: '#28a745',
            denyButtonColor: '#dc3545',
            cancelButtonColor: '#ffc107',
            allowOutsideClick: false, // Prevent closing when clicking outside
            allowEscapeKey: false     // Prevent closing with escape key
        }).then((result) => {
            var newStatus = '';
            if (result.isConfirmed) {
                newStatus = 'approved';
            } else if (result.isDenied) {
                newStatus = 'rejected';
            } else if (result.isDismissed) {
                newStatus = 'pending';
            } else {
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // AJAX call to update status
            $.ajax({
                url: "<?= base_url('admin/update_all_statuses'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    user_id: user_id,
                    status_type: status_type,
                    new_status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                        
                        // Reload the page after 1.5 seconds to reflect changes
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    Swal.fire({
                        title: 'Error',
                        text: 'Something went wrong while updating status!',
                        icon: 'error',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
                }
            });
        });
    });

    // ========== BATCH UPDATE ==========
    $(document).on("click", ".update-batch-btn", function () {
        let accountId = $(this).data("id");
        let batch     = $("#batchSelect_" + accountId).val();



        $.ajax({
            url: "<?= base_url('profile/updateBatchAdmin') ?>",
            type: "POST",
            dataType: "json",
            data: {
                account_id: accountId,
                batch: batch
            },
            beforeSend: function () {
                $("#batchMsg_" + accountId).html("<span class='text-info'>Updating...</span>");
            },
            success: function (res) {
                if (res.status === "success") {
                    $("#batchMsg_" + accountId).html("<span class='text-success'>✅ Updated</span>");
                    Swal.fire({
                        title: 'Success',
                        text: 'Batch updated successfully',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
                } else {
                    $("#batchMsg_" + accountId).html("<span class='text-danger'>❌ Failed</span>");
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to update batch',
                        icon: 'error',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
                }
            },
            error: function () {
                $("#batchMsg_" + accountId).html("<span class='text-danger'>Server Error</span>");
                Swal.fire({
                    title: 'Error',
                    text: 'Server error occurred',
                    icon: 'error',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            }
        });
    });
    
    $(document).on('click', '.changeMainStatus', function() {
        var button = $(this);
        var user_id = button.data('id');
        var currentStatus = button.data('status');
        var newStatus = (currentStatus === 'Approved') ? 'Unapproved' : 'Approved';

        Swal.fire({
            title: 'Are you sure?',
            text: 'Change main status to ' + newStatus + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('admin/change_status'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: { user_id: user_id, status: currentStatus },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Updating...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: 'Main status changed to ' + response.newStatus,
                                timer: 1500,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });

                            button.text(response.newStatus);
                            button.data('status', response.newStatus);
                            if (response.newStatus === 'Approved') {
                                button.removeClass('btn-danger').addClass('btn-success');
                            } else {
                                button.removeClass('btn-success').addClass('btn-danger');
                            }

                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Something went wrong!',
                            icon: 'error',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                });
            }
        });
    });

    // ========== BULK UPDATE ALL STATUSES ==========
    $(document).on('click', '.bulk-update-all', function() {
        var user_id = $(this).data('id');
        
        Swal.fire({
            title: 'Bulk Update All Statuses',
            text: 'Set all pending statuses to approved?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve all!',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                var statusTypes = ['personal', 'verification', 'education', 'work', 'family', 'partner', 'photo'];
                var promises = [];
                var successCount = 0;
                var failCount = 0;
                
                Swal.fire({
                    title: 'Updating...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                statusTypes.forEach(function(type) {
                    promises.push(
                        $.ajax({
                            url: "<?= base_url('admin/update_all_statuses'); ?>",
                            type: "POST",
                            dataType: "json",
                            data: {
                                user_id: user_id,
                                status_type: type,
                                new_status: 'approved'
                            }
                        }).then(function(response) {
                            if (response.success) {
                                successCount++;
                            } else {
                                failCount++;
                            }
                        }).catch(function() {
                            failCount++;
                        })
                    );
                });
                
                Promise.all(promises)
                    .then(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Complete!',
                            text: successCount + ' statuses updated successfully. ' + (failCount > 0 ? failCount + ' failed.' : ''),
                            timer: 2000,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                        
                        // Reload the page to reflect changes
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    })
                    .catch(function(error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Some updates failed',
                            icon: 'error',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    });
            }
        });
    });

    // ========== MEMBERSHIP MANAGEMENT ==========
    
    // Auto-fill price and duration when plan is selected
    $('#membership_plan').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var price = selectedOption.data('price');
        var months = selectedOption.data('months');
        
        if (price) {
            $('#membership_price').val(price);
        }
        
        if (months) {
            $('#membership_duration').val(months);
        }
    });
    
    // Calculate end date based on start date and duration
    $('#calculateEndDateBtn').on('click', function() {
        var startDate = $('#membership_start_date').val();
        var duration = $('#membership_duration').val();
        
        if (!startDate) {
            Swal.fire({
                title: 'Error',
                text: 'Please select a start date first',
                icon: 'error',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            return;
        }
        
        if (!duration || duration <= 0) {
            Swal.fire({
                title: 'Error',
                text: 'Please enter a valid duration in months',
                icon: 'error',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            return;
        }
        
        var start = new Date(startDate);
        start.setMonth(start.getMonth() + parseInt(duration));
        
        var year = start.getFullYear();
        var month = (start.getMonth() + 1).toString().padStart(2, '0');
        var day = start.getDate().toString().padStart(2, '0');
        
        $('#membership_end_date').val(year + '-' + month + '-' + day);
        
        Swal.fire({
            title: 'Success',
            text: 'End date calculated successfully',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });
    
    // Update membership
    $('#updateMembershipBtn').on('click', function() {
        var formData = {
            user_id: $('input[name="user_id"]').val(),
            membership_plan: $('#membership_plan').val(),
            membership_price: $('#membership_price').val(),
            membership_start_date: $('#membership_start_date').val(),
            membership_end_date: $('#membership_end_date').val(),
            membership_status: $('#membership_status').val(),
            membership_duration: $('#membership_duration').val(),
            membership_notes: $('#membership_notes').val()
        };
        
        // Validate required fields
        if (!formData.membership_plan) {
            Swal.fire({
                title: 'Error',
                text: 'Please select a membership plan',
                icon: 'error',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            return;
        }
        
        if (!formData.membership_price) {
            Swal.fire({
                title: 'Error',
                text: 'Please enter membership price',
                icon: 'error',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            return;
        }
        
        if (!formData.membership_start_date) {
            Swal.fire({
                title: 'Error',
                text: 'Please select start date',
                icon: 'error',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            return;
        }
        
        if (!formData.membership_end_date) {
            Swal.fire({
                title: 'Error',
                text: 'Please select end date',
                icon: 'error',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            return;
        }
        
        Swal.fire({
            title: 'Update Membership?',
            text: 'Are you sure you want to update this user\'s membership?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('admin/update_membership'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Updating...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                            
                            // Reload the page after 1.5 seconds
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Something went wrong!',
                            icon: 'error',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                });
            }
        });
    });
    
    // Reset membership form to current values
    $('#resetMembershipBtn').on('click', function() {
        location.reload();
    });

    // ========== DELETE REQUEST MANAGEMENT ==========
    
    // Approve delete request and delete user
    $(document).on('click', '.approve-delete', function() {
        var requestId = $(this).data('id');
        var userId = $(this).data('user-id');
        var userName = $(this).data('user-name');
        
        Swal.fire({
            title: 'Approve Account Deletion?',
            html: 'You are about to <strong>permanently delete</strong> the account of <strong>' + userName + '</strong>.<br><br>This action cannot be undone. All user data including profile, photos, preferences, and related records will be permanently removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete permanently',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting Account...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // AJAX call to approve delete and delete user
                $.ajax({
                    url: "<?= base_url('admin/approve_delete_request'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        request_id: requestId,
                        user_id: userId
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Account Deleted',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                            
                            // Redirect to users list after deletion
                            setTimeout(function() {
                                window.location.href = "<?= base_url('admin/customer'); ?>";
                            }, 2000);
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Something went wrong while deleting the account!',
                            icon: 'error',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                });
            }
        });
    });
    
    // Reject delete request
    $(document).on('click', '.reject-delete', function() {
        var requestId = $(this).data('id');
        var userId = $(this).data('user-id');
        
        Swal.fire({
            title: 'Reject Delete Request?',
            text: 'Are you sure you want to reject this account deletion request? The user account will be preserved.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject request',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // AJAX call to reject delete request
                $.ajax({
                    url: "<?= base_url('admin/reject_delete_request'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        request_id: requestId,
                        user_id: userId
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Request Rejected',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                            
                            // Reload the page to reflect changes
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Something went wrong while rejecting the request!',
                            icon: 'error',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                });
            }
        });
    });

    // Tab functionality
    $('#profileTab button').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

});

// Print functionality
$(document).ready(function() {
    $('button[onclick="window.print()"], .btn-secondary:contains("Print")').off('click').on('click', function(e) {
        e.preventDefault();
        
        $('body').addClass('printing');
        
        setTimeout(function() {
            window.print();
            
            setTimeout(function() {
                $('body').removeClass('printing');
            }, 100);
        }, 100);
    });
});
</script>

<!-- Add these routes in Routes.php -->
<!-- $routes->post('admin/update_all_statuses', 'Admin::update_all_statuses'); -->
<!-- $routes->post('admin/update_membership', 'Admin::update_membership'); -->
<!-- $routes->post('admin/approve_delete_request', 'Admin::approve_delete_request'); -->
<!-- $routes->post('admin/reject_delete_request', 'Admin::reject_delete_request'); -->

<style>
.nav-tabs .nav-link {
    color: #495057;
    border: 1px solid transparent;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
    padding: 10px 15px;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}

.nav-tabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #dee2e6;
}

.table-borderless td, .table-borderless th {
    border: 0;
    padding: 8px 0;
}

.img-thumbnail {
    transition: transform 0.3s ease;
}

.img-thumbnail:hover {
    transform: scale(1.05);
}

.card-header {
    font-weight: 600;
}

.status-badge {
    transition: opacity 0.3s, transform 0.3s;
}

.status-badge:hover {
    opacity: 0.8;
    transform: scale(1.02);
    cursor: pointer;
}

.gap-2 {
    gap: 0.5rem;
}

/* PRINT STYLES - Copy this in your existing CSS file or inside <style> tag */
@media print {
    /* Hide all buttons, tabs, navigation, and interactive elements */
    .nav-tabs,
    .btn,
    .breadcrumb-wrapper,
    .status-badge,
    .update-batch-btn,
    .bulk-update-all,
    .change-status,
    .deletePhotoBtn,
    .approve-delete,
    .reject-delete,
    #updateMembershipBtn,
    #calculateEndDateBtn,
    #resetMembershipBtn,
    .btn-outline-primary,
    .btn-danger,
    .btn-warning,
    .btn-primary,
    .btn-info,
    .btn-secondary,
    select,
    button:not(.no-print),
    .tab-pane button,
    .card-footer button,
    .gap-2 .btn,
    .ms-2 .btn,
    .mt-2 .btn,
    .mt-2 select,
    .mt-2 button,
    .card-header .btn,
    .card-body .btn,
    .bg-danger.status-badge,
    .bg-success.status-badge,
    .print-trigger {
        display: none !important;
    }

    /* ✅ HIDE Manual Membership Management Tab Completely */
    #membership,
    #membership-tab,
    .nav-link#membership-tab,
    [href="#membership"],
    [data-bs-target="#membership"],
    a[href*="membership"],
    button[data-bs-target*="membership"],
    .tab-pane#membership,
    .card:has(.card-header:contains("Manual Membership")),
    .card:has(.card-header:contains("Current Membership")) {
        display: none !important;
    }
    
    /* Hide Membership section by header text */
    .card-header:contains("Manual Membership Management"),
    .card-header:contains("Current Membership Summary"),
    .card-header:contains("Membership"),
    .card:has(.card-header.bg-warning),
    .card:has(.card-header.bg-info) {
        display: none !important;
    }

    /* Remove backgrounds and shadows */
    body {
        background: white !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .ec-content-wrapper {
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Remove extra gaps */
    .row {
        margin: 0 !important;
        padding: 0 !important;
    }

    .col-md-6, 
    .col-md-12,
    .col-xl-12,
    .col-lg-12 {
        padding: 0 !important;
        margin: 0 !important;
    }

    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        break-inside: avoid;
        page-break-inside: avoid;
        margin-bottom: 10px !important;
        margin-top: 0 !important;
        border-radius: 0 !important;
    }

    .card-body {
        padding: 10px 12px !important;
    }

    .card-header {
        background: #f8f9fa !important;
        color: #000 !important;
        border-bottom: 1px solid #ddd !important;
        padding: 8px 12px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .card-header h6, 
    .card-header .card-title {
        margin: 0 !important;
        font-size: 14px !important;
        font-weight: 600 !important;
    }

    /* Show ALL tab content when printing */
    .tab-pane {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        page-break-after: avoid !important;
        break-inside: avoid !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Hide the tab navigation but keep content visible */
    .nav-tabs {
        display: none !important;
    }

    /* Force all tab panes to be visible except membership */
    .tab-content > .tab-pane:not(#membership) {
        display: block !important;
    }

    /* Table styling for print - remove gaps */
    .table {
        border-collapse: collapse !important;
        width: 100% !important;
        margin: 0 !important;
    }

    .table td, 
    .table th {
        border: 1px solid #ddd !important;
        padding: 6px 8px !important;
        font-size: 12px !important;
    }

    .table-borderless td,
    .table-borderless th {
        border: none !important;
        padding: 4px 0 !important;
    }

    /* Profile image */
    .rounded-circle {
        border: 1px solid #ccc !important;
        width: 80px !important;
        height: 80px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .flex-shrink-0 {
        margin-right: 10px !important;
    }

    /* Badges to plain text */
    .badge {
        border: 1px solid #ccc !important;
        background: transparent !important;
        color: #000 !important;
        padding: 2px 6px !important;
        font-size: 11px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* Links */
    a {
        text-decoration: none !important;
        color: #000 !important;
    }

    /* Grid layout - full width */
    .row {
        display: block !important;
    }

    .col-md-6, 
    .col-md-12,
    .col-xl-12,
    .col-lg-12 {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }

    /* Hide action buttons section */
    .d-flex.justify-content-between,
    .mt-4 .d-flex,
    .row.mt-4 .col-12 .d-flex,
    .d-flex.justify-content-between {
        display: none !important;
    }

    .mt-4, .mb-4, .mb-3 {
        margin-top: 5px !important;
        margin-bottom: 5px !important;
    }

    /* Hide select dropdowns */
    select, 
    .form-select {
        display: none !important;
    }

    /* Hide batch message spans */
    [id^="batchMsg_"] {
        display: none !important;
    }

    /* Image styling */
    img {
        max-width: 100% !important;
        page-break-inside: avoid !important;
    }

    /* Photo gallery in print */
    #photos .row {
        display: block !important;
    }
    
    #photos .row .col-md-3 {
        display: inline-block !important;
        width: 180px !important;
        margin: 5px !important;
        padding: 0 !important;
    }
    
    #photos .card {
        margin: 0 !important;
    }
    
    #photos img {
        height: 150px !important;
        object-fit: cover !important;
    }

    /* Page breaks */
    .card {
        page-break-inside: avoid;
    }

    h1, h2, h3, h4, h5, h6 {
        page-break-after: avoid;
    }
    
    h1 {
        font-size: 18px !important;
        margin-bottom: 10px !important;
    }
    
    h3 {
        font-size: 16px !important;
    }
    
    p, .text-muted {
        font-size: 11px !important;
        margin-bottom: 4px !important;
    }

    /* Status cards - show as plain text in print */
    .card.bg-primary,
    .card.bg-success,
    .card.bg-info,
    .card.bg-warning,
    .card.bg-danger {
        background: white !important;
        color: black !important;
        border: 1px solid #ccc !important;
    }

    /* Status management card - hide completely in print */
    .row.mb-4 .card:first-child,
    .row.mb-4 .card,
    .status-badge,
    .bulk-update-all {
        display: none !important;
    }

    /* ✅ Hide Status Management section */
    .row.mb-4 {
        display: none !important;
    }

    /* Membership table */
    .table-bordered td, 
    .table-bordered th {
        border: 1px solid #ddd !important;
    }

    /* Delete requests table */
    #delete .table {
        border: 1px solid #ddd !important;
    }

    /* Remove extra spacing between sections */
    .mb-3, .mb-4 {
        margin-bottom: 8px !important;
    }
    
    .mt-2 {
        margin-top: 5px !important;
    }
    
    .flex-grow-1.ms-3 {
        margin-left: 10px !important;
    }
    
    /* User header section */
    .row.mb-4:first-child {
        margin-bottom: 10px !important;
    }
    
    /* Two column layout for print - make them full width but with less gap */
    .row > [class*="col-"] {
        width: 100% !important;
        margin-bottom: 10px !important;
    }
    
    /* Remove gap between cards */
    .card + .card {
        margin-top: 5px !important;
    }
    
    /* Table inside cards */
    .card .table {
        margin-bottom: 0 !important;
    }
    
    /* Profile header name and details */
    .flex-grow-1 h3 {
        margin-bottom: 2px !important;
    }
    
    /* Remove extra padding from card-body */
    .card-body {
        padding: 8px 10px !important;
    }
    
    /* Compact table rows */
    .table-sm td, .table-sm th {
        padding: 3px 5px !important;
    }
    
    /* Breadcrumb hide */
    .breadcrumb-wrapper {
        display: none !important;
    }
    
    /* Photo gallery footer hide delete button area */
    #photos .card-footer {
        display: none !important;
    }
    
    /* Remove any remaining gaps */
    .gap-2 {
        gap: 0 !important;
    }
    
    /* Ensure no odd margins */
    .app-header, .app-footer, header, footer {
        display: none !important;
    }
}

/* Page size for print */
@page {
    size: A4;
    margin: 1.2cm;
}

/* Print button style - only visible on screen */
.print-trigger {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    background: #2a5298;
    color: white;
    border: none;
    border-radius: 50px;
    padding: 12px 24px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transition: all 0.3s;
}

.print-trigger:hover {
    background: #1e3c72;
    transform: scale(1.05);
}

/* Screen styles */
@media screen {
    .print-trigger {
        display: block;
    }
}
</style>