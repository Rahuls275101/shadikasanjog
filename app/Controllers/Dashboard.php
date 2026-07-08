<?php
namespace App\Controllers;
use CodeIgniter\Email\Email;
use App\Models\Commanmodel;
use App\Models\Interestlistmodel;
require_once(APPPATH . "Libraries/config.php");
require_once(APPPATH . "Libraries/razorpay-php/Razorpay.php");

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
class Dashboard extends BaseController
{
    protected $db;
 protected $session;
    public function index()
    {
        $session = session();
        $commanmodel = new Commanmodel();
         $usersession = $session->get('loggedin');
         $userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
       $religions =$commanmodel->all_multiple_query_order_by('religions',array('status' => 'Active'),'name','ASC');
            $caste =$commanmodel->all_multiple_query_order_by('castes',array('religion_id' => $userdata->religion_id),'name','ASC');
            $user_photos =$commanmodel->all_multiple_query_order_by('user_photos',array('user_id' => $usersession['user_id']),'id','ASC');
            $education_data = $commanmodel->get_single_query('user_education',array('user_id'=> $usersession['user_id']));
            $family_members =$commanmodel->all_multiple_query_order_by('user_family_members',array('user_id' => $usersession['user_id']),'id','ASC');
          $family_background = $commanmodel->get_single_query('user_family_background',array('user_id'=> $usersession['user_id']));
          $partner_preferences = $commanmodel->get_single_query('user_partner_preferences',array('user_id'=> $usersession['user_id']));
          
        $data = array(
        'title' => "Home : Event", 
        'keyword' => "Home : Event",
        'description' => "Home : Event",
        'search' => '',
        'userdata' => $userdata,
     'religions' => $religions,
          'castes' => $caste,
           'user_photos' => $user_photos,
           'education_data' => $education_data,
           'family_members' => $family_members,
            'family_background' => $family_background,
              'partner_preferences' => $partner_preferences,
        'searchcategory' => 'all',
        'pageurl' => base_url(), 
        'pageimage' => base_url('assets/images/logo.png')
		);


       
          return view('frontend/header', $data).view('frontend/dashboard/index').view('frontend/footer');
    }
    

    public function delete_account()
{
    $session = session();
    $commanmodel = new Commanmodel();
    $usersession = $session->get('loggedin');
    
    if (!$usersession) {
        return redirect()->to(base_url('login'));
    }
    
    $userdata = $commanmodel->get_single_query('user_account', array('account_id' => $usersession['user_id']));
    
    // Check if already has pending request
    $db = \Config\Database::connect();
    $pendingRequest = $db->table('account_delete_requests')
        ->where('user_id', $usersession['user_id'])
        ->where('status', 'pending')
        ->get()
        ->getRow();
    
    $religions = $commanmodel->all_multiple_query_order_by('religions', array('status' => 'Active'), 'id', 'ASC');
    $caste = $commanmodel->all_multiple_query_order_by('castes', array('religion_id' => $userdata->religion_id), 'id', 'ASC');
    $user_photos = $commanmodel->all_multiple_query_order_by('user_photos', array('user_id' => $usersession['user_id']), 'id', 'ASC');
    $education_data = $commanmodel->get_single_query('user_education', array('user_id' => $usersession['user_id']));
    $family_members = $commanmodel->all_multiple_query_order_by('user_family_members', array('user_id' => $usersession['user_id']), 'id', 'ASC');
    $family_background = $commanmodel->get_single_query('user_family_background', array('user_id' => $usersession['user_id']));
    $partner_preferences = $commanmodel->get_single_query('user_partner_preferences', array('user_id' => $usersession['user_id']));
    
    $data = array(
        'title' => "Delete Account",
        'keyword' => "Delete Account",
        'description' => "Delete Account Request",
        'userdata' => $userdata,
        'religions' => $religions,
        'castes' => $caste,
        'user_photos' => $user_photos,
        'education_data' => $education_data,
        'family_members' => $family_members,
        'family_background' => $family_background,
        'partner_preferences' => $partner_preferences,
        'pendingRequest' => $pendingRequest,
        'searchcategory' => 'all',
        'pageurl' => base_url(),
        'pageimage' => base_url('assets/images/logo.png')
    );

    return view('frontend/header', $data) . view('frontend/dashboard/delete_account') . view('frontend/footer');
}

/**
 * Submit delete account request via AJAX
 */
public function submit_delete_request()
{
    $session = session();
    $usersession = $session->get('loggedin');
    
    if (!$usersession) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Please login first.'
        ]);
    }
    
    $user_id = $usersession['user_id'];
    
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Invalid request method.'
        ]);
    }
    
    $db = \Config\Database::connect();
    
    // Check if already has pending request
    $existing = $db->table('account_delete_requests')
        ->where('user_id', $user_id)
        ->where('status', 'pending')
        ->get()
        ->getRow();
    
    if ($existing) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'You already have a pending delete request. Please wait for admin approval.'
        ]);
    }
    
    // Validate input
    $rules = [
        'delete_reason' => 'required',
    ];
    
    if (!$this->validate($rules)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => implode('<br>', $this->validator->getErrors())
        ]);
    }
    
    $reason = $this->request->getPost('delete_reason');
    $other_reason = null;
    
    if ($reason === 'other') {
        $other_reason = $this->request->getPost('other_reason');
    }
    
    $feedback = $this->request->getPost('feedback');
    
    try {
        // Insert delete request
        $db->table('account_delete_requests')->insert([
            'user_id' => $user_id,
            'reason' => $reason,
            'other_reason' => $other_reason,
            'feedback' => $feedback,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Send email notification to admin (optional)
        $this->sendDeleteRequestNotification($user_id, $reason, $other_reason);
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Your account deletion request has been submitted successfully. Admin will review and process it shortly.'
        ]);
        
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Something went wrong. Please try again.'
        ]);
    }
}

/**
 * Cancel delete request via AJAX
 */
public function cancel_delete_request()
{
    $session = session();
    $usersession = $session->get('loggedin');
    
    if (!$usersession) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Please login first.'
        ]);
    }
    
    $user_id = $usersession['user_id'];
    $db = \Config\Database::connect();
    
    $updated = $db->table('account_delete_requests')
        ->where('user_id', $user_id)
        ->where('status', 'pending')
        ->update([
            'status' => 'cancelled',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    
    if ($updated) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Delete request cancelled successfully.'
        ]);
    }
    
    return $this->response->setJSON([
        'status' => 'error',
        'message' => 'No pending request found.'
    ]);
}

/**
 * Send email notification to admin (optional)
 */
private function sendDeleteRequestNotification($user_id, $reason, $other_reason = null)
{
    $db = \Config\Database::connect();
    $user = $db->table('user_account')->where('account_id', $user_id)->get()->getRow();
    
    $email = \Config\Services::email();
    
    $reason_text = $reason;
    if ($reason === 'other' && $other_reason) {
        $reason_text = $other_reason;
    }
    
    $message = "
        <h2>New Account Delete Request</h2>
        <p><strong>User ID:</strong> {$user_id}</p>
        <p><strong>Name:</strong> {$user->user_name} {$user->user_last_name}</p>
        <p><strong>Email:</strong> {$user->user_email}</p>
        <p><strong>Phone:</strong> {$user->user_phone}</p>
        <p><strong>Reason:</strong> {$reason_text}</p>
        <p><strong>Request Date:</strong> " . date('Y-m-d H:i:s') . "</p>
        <p><a href='" . base_url('admin/delete-requests') . "'>Click here to view all requests</a></p>
    ";
    
    $email->setTo('admin@example.com'); // Change to admin email
    $email->setFrom('noreply@yourdomain.com', 'Matrimony Site');
    $email->setSubject('New Account Delete Request - User #' . $user_id);
    $email->setMessage($message);
    
    $email->send();
}
 public function getContactDetails()
{
    $commanmodel = new Commanmodel();
    $session     = session();
    $usersession = $session->get('loggedin');
        $userId = $this->request->getPost('user_id');

    // ðŸ”’ Check if user logged in
    if (!$usersession || !isset($usersession['user_id'])) {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Please login first.'
        ]);
    }

    // ðŸ”’ Get logged-in user data
    $userdata = $commanmodel->get_single_query(
        'user_account',
        ['account_id' => $usersession['user_id']]
    );

    if (!$userdata) {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'User session invalid.'
        ]);
    }


if($userId != $usersession['user_id']) {
if ((int)$userdata->membership_id === 1) {
    return $this->response->setJSON([
        'status'  => false,
        'message' => 'To use this feature you are requested to become a paid member. Visit Manage Plan and select your plan.'
    ]);
}
    // ðŸ”’ Membership Check
    if (!isset($userdata->membership_status) || $userdata->membership_status !== 'Active') {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'To use this feature you are requested to become a paid member. Visit Manage Plan and select your plan.'
        ]);
    }

}

    if (empty($userId)) {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Invalid request.'
        ]);
    }

    // ðŸ”¹ Fetch profile contact details
    $db      = \Config\Database::connect();
    $builder = $db->table('user_account');
    $builder->where('account_id', $userId);
    $user = $builder->get()->getRow();
    
    $pincode = $user->country_code ?? '91';

    if ($user) {
        return $this->response->setJSON([
            'status' => true,
            'mobile' => $pincode.$user->user_phone ?? '' ,
            'email'  => $user->user_email ?? ''
        ]);
    }

    return $this->response->setJSON([
        'status'  => false,
        'message' => 'User not found.'
    ]);
}

 
        public function user_interests()
    {
        $session = session();
        $commanmodel = new Commanmodel();
         $usersession = $session->get('loggedin');
         $userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
         
           $religions =$commanmodel->all_multiple_query_order_by('religions',array('status' => 'Active'),'id','ASC');
            $caste =$commanmodel->all_multiple_query_order_by('castes',array('religion_id' => $userdata->religion_id),'id','ASC');
            $user_photos =$commanmodel->all_multiple_query_order_by('user_photos',array('user_id' => $usersession['user_id']),'id','ASC');
            $education_data = $commanmodel->get_single_query('user_education',array('user_id'=> $usersession['user_id']));
            $family_members =$commanmodel->all_multiple_query_order_by('user_family_members',array('user_id' => $usersession['user_id']),'id','ASC');
          $family_background = $commanmodel->get_single_query('user_family_background',array('user_id'=> $usersession['user_id']));
          $partner_preferences = $commanmodel->get_single_query('user_partner_preferences',array('user_id'=> $usersession['user_id']));
          
          
        $data = array(
        'title' => "Home : Event", 
        'keyword' => "Home : Event",
        'description' => "Home : Event",
        'search' => '',
        'userdata' => $userdata,
        'religions' => $religions,
          'castes' => $caste,
           'user_photos' => $user_photos,
           'education_data' => $education_data,
           'family_members' => $family_members,
            'family_background' => $family_background,
              'partner_preferences' => $partner_preferences,
     
        'searchcategory' => 'all',
        'pageurl' => base_url(), 
        'pageimage' => base_url('assets/images/logo.png')
		);


       
          return view('frontend/header', $data).view('frontend/dashboard/user_interests').view('frontend/footer');
    }
    
        public function user_chat()
    {
        $session = session();
        $commanmodel = new Commanmodel();
         $usersession = $session->get('loggedin');
         $userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
         
           $religions =$commanmodel->all_multiple_query_order_by('religions',array('status' => 'Active'),'id','ASC');
            $caste =$commanmodel->all_multiple_query_order_by('castes',array('religion_id' => $userdata->religion_id),'id','ASC');
            $user_photos =$commanmodel->all_multiple_query_order_by('user_photos',array('user_id' => $usersession['user_id']),'id','ASC');
            $education_data = $commanmodel->get_single_query('user_education',array('user_id'=> $usersession['user_id']));
            $family_members =$commanmodel->all_multiple_query_order_by('user_family_members',array('user_id' => $usersession['user_id']),'id','ASC');
          $family_background = $commanmodel->get_single_query('user_family_background',array('user_id'=> $usersession['user_id']));
          $partner_preferences = $commanmodel->get_single_query('user_partner_preferences',array('user_id'=> $usersession['user_id']));
          
          
        $data = array(
        'title' => "Home : Event", 
        'keyword' => "Home : Event",
        'description' => "Home : Event",
        'search' => '',
        'userdata' => $userdata,
        'religions' => $religions,
          'castes' => $caste,
           'user_photos' => $user_photos,
           'education_data' => $education_data,
           'family_members' => $family_members,
            'family_background' => $family_background,
              'partner_preferences' => $partner_preferences,
     
        'searchcategory' => 'all',
        'pageurl' => base_url(), 
        'pageimage' => base_url('assets/images/logo.png')
		);


                 $userId = $usersession['user_id'];
        $interestsModel = new \App\Models\InterestsModel();
        
        // Get user's chat partners from interests (mutual interests)
        $data['chatPartners'] = $interestsModel->getUserChatPartners($userId);
        
        // If no mutual interests found, show simple interested users for testing
        if (empty($data['chatPartners'])) {
            $data['chatPartners'] = $interestsModel->getInterestedUsers($userId);
        }
        
          return view('frontend/header', $data).view('frontend/dashboard/user_chat').view('frontend/footer');
    }
    
    
public function ajax_list_interests($page = 1)
{
    $session = session();
    $usersession = $session->get('loggedin');
    if (!$usersession) {
        return $this->response->setJSON([
            'status' => false,
            'message' => 'Not authenticated'
        ]);
    }

    $userId = $usersession['user_id'];
    $Profilemodel = new \App\Models\Interestlistmodel();
    $pager = service('pager');

    $perPage = 12;
    $start = ($page - 1) * $perPage;

    // âœ… Get status from request (default to 'pending')
    $status = $this->request->getPost('status') ?? 'pending';

    // âœ… Get data for current status only
    $total = $Profilemodel->count_all_status($userId, $status);
    $html  = $Profilemodel->fetch_html_by_status($userId, $status, $perPage, $start);
    $links = $pager->makeLinks($page, $perPage, $total, 'foundation_full');

    // âœ… Get counts for all statuses (for tabs)
    $counts = $Profilemodel->get_interest_counts($userId);

    // âœ… Final JSON Response
    return $this->response->setJSON([
        'status' => true,
        'current_status' => $status,
        'counts' => $counts,
        'data' => [
            'list' => $html,
            'pagination' => $links,
            'total' => $total
        ]
    ]);
}
    
      public function profile()
    {
        $session = session();
        $commanmodel = new Commanmodel();
         $usersession = $session->get('loggedin');
         $userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
         
          $profession_categories  =$commanmodel->all_multiple_query_order_by('profession_categories',array(),'sort_order','ASC');
           $education_qualifications  =$commanmodel->all_multiple_query_order_by('education_qualifications',array(),'id','DESC');
         
           $religions =$commanmodel->all_multiple_query_order_by('religions',array('status' => 'Active'),'id','ASC');
            $caste =$commanmodel->all_multiple_query_order_by('castes',array('religion_id' => $userdata->religion_id),'id','ASC');
            $user_photos =$commanmodel->all_multiple_query_order_by('user_photos',array('user_id' => $usersession['user_id']),'id','ASC');
            $education_data = $commanmodel->get_single_query('user_education',array('user_id'=> $usersession['user_id']));
            $family_members =$commanmodel->all_multiple_query_order_by('user_family_members',array('user_id' => $usersession['user_id']),'id','ASC');
          $family_background = $commanmodel->get_single_query('user_family_background',array('user_id'=> $usersession['user_id']));
          $partner_preferences = $commanmodel->get_single_query('user_partner_preferences',array('user_id'=> $usersession['user_id']));
          
          
        $data = array(
        'title' => "Home : Event", 
        'keyword' => "Home : Event",
        'description' => "Home : Event",
        'search' => '',
        'userdata' => $userdata,
         'profession_categories' => $profession_categories,
          'education_qualifications' => $education_qualifications,
        'religions' => $religions,
          'castes' => $caste,
           'user_photos' => $user_photos,
           'education_data' => $education_data,
           'family_members' => $family_members,
            'family_background' => $family_background,
              'partner_preferences' => $partner_preferences,
     
        'searchcategory' => 'all',
        'pageurl' => base_url(), 
        'pageimage' => base_url('assets/images/logo.png')
		);


       
          return view('frontend/header', $data).view('frontend/dashboard/profile').view('frontend/footer');
    }
    
      public function update_profile()
    {
        $session = session();
        $commanmodel = new Commanmodel();
         $usersession = $session->get('loggedin');
         $userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
         
          $religions =$commanmodel->all_multiple_query_order_by('religions',array(),'id','ASC');
            $caste =$commanmodel->all_multiple_query_order_by('castes',array('religion_id' => $userdata->religion_id),'id','ASC');
            $user_photos =$commanmodel->all_multiple_query_order_by('user_photos',array('user_id' => $usersession['user_id']),'id','ASC');
            $education_data = $commanmodel->get_single_query('user_education',array('user_id'=> $usersession['user_id']));
            
            
            
            $family_members =$commanmodel->all_multiple_query_order_by('user_family_members',array('user_id' => $usersession['user_id']),'id','ASC');
          $family_background = $commanmodel->get_single_query('user_family_background',array('user_id'=> $usersession['user_id']));
          $partner_preferences = $commanmodel->get_single_query('user_partner_preferences',array('user_id'=> $usersession['user_id']));
          
            
        $data = array(
        'title' => "Home : Event", 
        'keyword' => "Home : Event",
        'description' => "Home : Event",
        'search' => '',
        'userdata' => $userdata,
        'religions' => $religions,
          'castes' => $caste,
           'user_photos' => $user_photos,
           'education_data' => $education_data,
           'family_members' => $family_members,
            'family_background' => $family_background,
              'partner_preferences' => $partner_preferences,
        'searchcategory' => 'all',
        'pageurl' => base_url(), 
        'pageimage' => base_url('assets/images/logo.png')
		);


       
          return view('frontend/header', $data).view('frontend/dashboard/update_profile').view('frontend/footer');
    }
    
    
      public function plans()
    {
        $session = session();
        $commanmodel = new Commanmodel();
         $usersession = $session->get('loggedin');
         $userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
         
          $religions =$commanmodel->all_multiple_query_order_by('religions',array(),'id','ASC');
            $caste =$commanmodel->all_multiple_query_order_by('castes',array('religion_id' => $userdata->religion_id),'id','ASC');
            $user_photos =$commanmodel->all_multiple_query_order_by('user_photos',array('user_id' => $usersession['user_id']),'id','ASC');
            $education_data = $commanmodel->get_single_query('user_education',array('user_id'=> $usersession['user_id']));
            $family_members =$commanmodel->all_multiple_query_order_by('user_family_members',array('user_id' => $usersession['user_id']),'id','ASC');
          $family_background = $commanmodel->get_single_query('user_family_background',array('user_id'=> $usersession['user_id']));
          $partner_preferences = $commanmodel->get_single_query('user_partner_preferences',array('user_id'=> $usersession['user_id']));
           $plan =$commanmodel->all_multiple_query_order_by('payment_history',array('user_id' => $usersession['user_id'],'status' => 'Success'),'id','DESC');
            
        $data = array(
        'title' => "Home : Event", 
        'keyword' => "Home : Event",
        'description' => "Home : Event",
        'search' => '',
        'userdata' => $userdata,
        'religions' => $religions,
          'castes' => $caste,
           'user_photos' => $user_photos,
           'education_data' => $education_data,
           'family_members' => $family_members,
            'family_background' => $family_background,
              'partner_preferences' => $partner_preferences,
              'plan' => $plan,
        'searchcategory' => 'all',
        'pageurl' => base_url(), 
        'pageimage' => base_url('assets/images/logo.png')
		);


       
          return view('frontend/header', $data).view('frontend/dashboard/plans').view('frontend/footer');
    }
    
      
public function profile_verification()
{
    $db = \Config\Database::connect();
    $this->session = session();

    /* ================= SESSION CHECK ================= */
    $usersession = $this->session->get('loggedin');

    if (!$usersession) {
        return redirect()->to(base_url('login'))
            ->with('error', 'Session expired. Please login again.');
    }

    $user_id = $usersession['user_id'];

    /* ================= POST CHECK ================= */
    if ($this->request->getMethod() !== 'post') {
        return redirect()->back();
    }

    /* ================= VALIDATION ================= */
    $rules = [
        'verification_user_name' => 'required',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
            ->withInput()
            ->with('error', implode('<br>', $this->validator->getErrors()));
    }

    /* ================= FILE UPLOAD ================= */
    $uploadedFiles = $this->request->getFiles();
    $documentPaths = [];

    if (isset($uploadedFiles['verification_documents'])) {

        foreach ($uploadedFiles['verification_documents'] as $file) {

            if ($file->isValid() && !$file->hasMoved()) {

                $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    continue;
                }

                $newName = $file->getRandomName();
                $uploadPath = FCPATH . 'assets/uploads/verification/';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $newName);

                $documentPaths[] = 'uploads/verification/' . $newName;
            }
        }
    }

    /* ================= HANDLE OTHER VALUES ================= */

    $match_for = $this->request->getPost('match_for');
    $match_for_other = null;

    if ($match_for === 'others') {
        $match_for_other = $this->request->getPost('match_for_other');
    }

    $verification_relationship = $this->request->getPost('verification_relationship');
    $verification_relationship_other = null;

    if ($verification_relationship === 'others') {
        $verification_relationship_other = $this->request->getPost('verification_relationship_other');
    }

    $document_type = $this->request->getPost('document_type');
    $document_type_other = null;

    if ($document_type === 'others') {
        $document_type_other = $this->request->getPost('document_type_other');
    }

    /* ================= DATA SAVE ================= */

    $basic_info_data = [
        'introducer_name' => $this->request->getPost('introducer_name'),
        'match_for' => $match_for,
        'match_for_other' => $match_for_other,
        'verification_user_name' => $this->request->getPost('verification_user_name'),
         
        'verification_relationship' => $verification_relationship,
        'verification_relationship_other' => $verification_relationship_other,
        'document_type' => $document_type,
        'document_type_other' => $document_type_other,
        'documents' => !empty($documentPaths) ? json_encode($documentPaths) : null,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $db->table('user_account')
        ->where('account_id', $user_id)
        ->update($basic_info_data);

    return redirect()->back()
        ->with('success', 'Profile verification details submitted successfully.');
}

    public function uploadImage()
    {
        $session = session();
        $usersession = $session->get('loggedin');

        if (!$usersession) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "User not logged in"
            ]);
        }

        $userId = $usersession['user_id'];
        $file = $this->request->getFile('profile_image');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "Invalid File"
            ]);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "Only JPG, PNG, WEBP allowed"
            ]);
        }

        $uploadPath = FCPATH . "assets/uploads/";
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        $db = \Config\Database::connect();

        $oldImg = $db->table("user_account")
            ->select("user_photo")
            ->where("account_id", $userId)
            ->get()
            ->getRow();

        if ($oldImg && !empty($oldImg->user_photo)) {
            $oldFile = $uploadPath . $oldImg->user_photo;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $update = $db->table("user_account")
            ->where("account_id", $userId)
            ->update(["user_photo" => $newName]);

        if ($update) {
            return $this->response->setJSON([
                "status" => "success",
                "message" => "Profile Image Updated Successfully",
                "image" => base_url("assets/uploads/" . $newName)
            ]);
        }

        return $this->response->setJSON([
            "status" => "error",
            "message" => "Database Update Failed"
        ]);
    }

    public function save_personal_details()
    {
        $db = \Config\Database::connect();
        $session = session();

        $usersession = $session->get('loggedin');
        if (!$usersession) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Session expired. Please login again.');
        }

        $user_id = $usersession['user_id'];

        if ($this->request->getMethod() !== 'post') {
            return redirect()->back();
        }

        /* ================= VALIDATION ================= */
        $rules = [
            'user_name' => 'required',
             'user_phone' => 'required',
            'profession' => 'required',
            'marital_status' => 'required',
            'date_of_birth' => 'required',
            'religions' => 'required',
           
            'current_residence' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        /* ================= HEIGHT ================= */
        $height = $this->request->getPost('height_feet');

        /* ================= HANDLE "OTHER" OPTIONS ================= */
        // Profession handling
        $profession = $this->request->getPost('profession');
        $other_profession = null;
        if ($profession === 'other') {
            $other_profession = $this->request->getPost('other_profession');
           // $profession = null;
        }

        // Marital status handling
        $marital_status = $this->request->getPost('marital_status');
        $other_marital_status = null;
        if ($marital_status === 'other') {
            $other_marital_status = $this->request->getPost('other_marital_status');
          //  $marital_status = null;
        }

        // Religion handling
        $religion_id = $this->request->getPost('religions');
        $other_religion = null;
        if ($religion_id === 'other') {
            $other_religion = $this->request->getPost('other_religion');
          //  $religion_id = null;
        }

        // Caste handling
        $caste_id = $this->request->getPost('castes');
        $other_caste = null;
        if ($caste_id === 'other') {
            $other_caste = $this->request->getPost('other_caste');
          //  $caste_id = null;
        }

        // Mother tongue handling
        $mother_tongue = $this->request->getPost('mother_tongue');
        $other_mother_tongue = null;
        if ($mother_tongue === 'other') {
            $other_mother_tongue = $this->request->getPost('other_mother_tongue');
          //  $mother_tongue = null;
        }

        // Manglik status handling
        $manglik_status = $this->request->getPost('manglik_status');
        $other_manglik_status = null;
        if ($manglik_status === 'other') {
            $other_manglik_status = $this->request->getPost('other_manglik_status');
          //  $manglik_status = null;
        }

        // Living in handling
        $living_in = $this->request->getPost('living_in');
        $other_living_in = null;
        if ($living_in === 'other') {
            $other_living_in = $this->request->getPost('other_living_in');
           // $living_in = null;
        }



        /* ================= DATA ARRAY ================= */
        $data = [
            'user_name' => $this->request->getPost('user_name'),
            'user_last_name' => $this->request->getPost('user_last_name'),
             'user_phone' => $this->request->getPost('user_phone'),
            'profession' => $profession,
            'other_profession' => $other_profession,
            'marital_status' => $marital_status,
            'other_marital_status' => $other_marital_status,
            'height' => $height,
            'country_code' => $this->request->getPost('country_code'),
            'height_inch' => $this->request->getPost('height_inch') ,
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'time_of_birth' => $this->request->getPost('time_of_birth'),
            'place_of_birth' => $this->request->getPost('place_of_birth'),
            'religion_id' => $religion_id,
            'other_religion' => $other_religion,
            'caste_id' => $caste_id,
            'other_caste' => $other_caste,
            'mother_tongue' => $mother_tongue,
            'other_mother_tongue' => $other_mother_tongue,
         
            'family_home' => $this->request->getPost('family_home'),
            'current_residence' => $this->request->getPost('current_residence'),
            'manglik_status' => $manglik_status,
            'other_manglik_status' => $other_manglik_status,
            'kundali_matching' => $this->request->getPost('kundali_matching'),
            'living_in' => $living_in,
            'other_living_in' => $other_living_in,
            'updated_at' => date('Y-m-d H:i:s'),
            'personal_status' => 'pending' 
        ];
        
        
        $other_languages = $this->request->getPost('other_languages');

$data['other_languages'] = !empty($other_languages) 
    ? implode(',', $other_languages) 
    : null;

        $db->table('user_account')
            ->where('account_id', $user_id)
            ->update($data);

        return redirect()->back()
            ->with('success', 'Personal details saved and submitted successfully.');
    }

    public function save_education_details()
    {
        $db = \Config\Database::connect();
        $session = session();

        $usersession = $session->get('loggedin');
        if (!$usersession) {
            return redirect()->to(base_url('login'))->with('error', 'Session expired. Please login again.');
        }

        $user_id = $usersession['user_id'];

        if ($this->request->getMethod() !== 'post') {
            return redirect()->back();
        }

        $levels = $this->request->getPost('education_level') ?? [];
        $institutions = $this->request->getPost('institution') ?? [];
        $degrees = $this->request->getPost('qualification_obtained') ?? [];
        $other_levels = $this->request->getPost('other_education_level') ?? [];
        $other_degrees = $this->request->getPost('other_degree') ?? [];

        // delete old data
        $db->table('user_education')
            ->where('user_id', $user_id)
            ->delete();

        foreach ($levels as $key => $level) {
            // SAFETY CHECK
            if (empty($level) || !isset($institutions[$key]) || !isset($degrees[$key])) {
                continue;
            }

            // Handle other education level
            $other_education_level = null;
            $final_level = $level;
            if ($level === 'other' && isset($other_levels[$key])) {
                $other_education_level = $other_levels[$key];
                //$final_level = null;
            }

            // Handle other degree
            $other_degree = null;
            $final_degree = $degrees[$key];
            if ($degrees[$key] === 'other' && isset($other_degrees[$key])) {
                $other_degree = $other_degrees[$key];
                //$final_degree = null;
            }

            $data = [
                'user_id' => $user_id,
                'education_level' => $final_level,
                'other_education_level' => $other_education_level,
                'institution' => $institutions[$key],
                'degree' => $final_degree,
                'other_degree' => $other_degree,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => 'pending',
            ];

            $db->table('user_education')->insert($data);
        }

        return redirect()->back()->with('success', 'Educational qualifications saved and submitted successfully.');
    }

    public function save_work_details()
    {
        $db = \Config\Database::connect();
        $session = session();

        $usersession = $session->get('loggedin');
        if (!$usersession) {
            return redirect()->to(base_url('login'));
        }

        $user_id = $usersession['user_id'];

        $designations = $this->request->getPost('designation') ?? [];
        $companies = $this->request->getPost('company') ?? [];
        $years = $this->request->getPost('years_worked') ?? [];
        $ctcs = $this->request->getPost('ctc') ?? [];
        $other_designations = $this->request->getPost('other_designation') ?? [];

        $about_self = $this->request->getPost('about_self');

        // delete old work records
        $db->table('user_work_experience')->where('user_id', $user_id)->delete();

        $basic_info_data = [
            'about_self' => $about_self,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update user basic info
        $db->table('user_account')->where('account_id', $user_id)->update($basic_info_data);

        foreach ($designations as $key => $designation) {
            if (empty($designation) || !isset($companies[$key]) || !isset($years[$key])) {
                continue;
            }

            // Handle other designation
            $other_designation = null;
            $final_designation = $designation;
            if ($designation === 'other' && isset($other_designations[$key])) {
                $other_designation = $other_designations[$key];
                //$final_designation = null;
            }

            $data = [
                'user_id' => $user_id,
                'designation' => $final_designation,
                'other_designation' => $other_designation,
                'years_worked' => $years[$key],
                'ctc' => $ctcs[$key] ?? '',
                'company' => $companies[$key],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => 'pending',
            ];

            $db->table('user_work_experience')->insert($data);
        }

        return redirect()->back()->with('success', 'Work experience saved and submitted successfully');
    }

    public function saveFamilyDetails()
    {
        $db = \Config\Database::connect();
        $session = session();
        $usersession = $session->get('loggedin');
        if (!$usersession) {
            return redirect()->to(base_url('login'));
        }

        $user_id = $usersession['user_id'];

        $relationships = $this->request->getPost('relationship') ?? [];
        $occupations = $this->request->getPost('occupation') ?? [];
        $other_relationships = $this->request->getPost('other_relationship') ?? [];
        $other_occupations = $this->request->getPost('other_occupation') ?? [];

        // Delete old members
        $db->table('user_family_members')->where('user_id', $user_id)->delete();

        foreach ($relationships as $key => $rel) {
            if (!empty($rel)) {
                // Handle other relationship
                $other_relationship = null;
                $final_relationship = $rel;
                if ($rel === 'other' && isset($other_relationships[$key])) {
                    $other_relationship = $other_relationships[$key];
                  //  $final_relationship = null;
                }

                // Handle other occupation
                $occupation = $occupations[$key] ?? '';
                $other_occupation = null;
                $final_occupation = $occupation;
                if ($occupation === 'other' && isset($other_occupations[$key])) {
                    $other_occupation = $other_occupations[$key];
                  //  $final_occupation = null;
                }

                $db->table('user_family_members')->insert([
                    'user_id' => $user_id,
                    'relationship' => $final_relationship,
                    'other_relationship' => $other_relationship,
                    'occupation' => $final_occupation,
                    'status' => 'pending',
                    'other_occupation' => $other_occupation
                ]);
            }
        }

        // Family background data
        $data = [
            'user_id' => $user_id,
            'family_income' => $this->request->getPost('family_income'),
            'about_family' => $this->request->getPost('about_family'),
        ];

        $exists = $db->table('user_family_background')
            ->where('user_id', $user_id)
            ->get()
            ->getRow();

        if ($exists) {
            $db->table('user_family_background')
                ->where('user_id', $user_id)
                ->update($data);
        } else {
            $db->table('user_family_background')->insert($data);
        }

        return redirect()->back()->with('success', 'Family details saved and submitted successfully');
    }

    public function savePartnerPreferences()
    {
        $db = \Config\Database::connect();
        $session = session();
        $usersession = $session->get('loggedin');
        if (!$usersession) {
            return redirect()->to(base_url('login'));
        }

        $user_id = $usersession['user_id'];

        /* ================= HANDLE "OTHER" OPTIONS ================= */
        // Religion handling
        $religion_id = $this->request->getPost('religion_id');
        $other_religion = null;
        if ($religion_id === 'other') {
            $other_religion = $this->request->getPost('other_partner_religion');
           // $religion_id = null;
        }

        // Caste handling
        $caste_id = $this->request->getPost('caste_id');
        $other_caste = null;
        if ($caste_id === 'other') {
            $other_caste = $this->request->getPost('other_partner_caste');
          //  $caste_id = null;
        }

        // Dietary preference handling
        $dietary_preference = $this->request->getPost('dietary_preference');
        $other_dietary_preference = null;
        if ($dietary_preference === 'other') {
            $other_dietary_preference = $this->request->getPost('other_dietary_preference');
         //   $dietary_preference = null;
        }

        // Smoker handling
        $smoker = $this->request->getPost('smoker');
        $other_smoker = null;
        if ($smoker === 'other') {
            $other_smoker = $this->request->getPost('other_smoker');
          //  $smoker = null;
        }

        // Drinking habit handling
        $drinking_habit = $this->request->getPost('drinking_habit');
        $other_drinking_habit = null;
        if ($drinking_habit === 'other') {
            $other_drinking_habit = $this->request->getPost('other_drinking_habit');
         //   $drinking_habit = null;
        }

        $data = [
            'user_id' => $user_id,
            'age_from' => $this->request->getPost('age_from'),
            'age_to' => $this->request->getPost('age_to'),
            'height_from' => $this->request->getPost('height_from'),
            'religion_id' => $religion_id,
            'other_religion' => $other_religion,
            'caste_id' => $caste_id,
            'other_caste' => $other_caste,
            'dietary_preference' => $dietary_preference,
            'other_dietary_preference' => $other_dietary_preference,
            'smoker' => $smoker,
            'other_smoker' => $other_smoker,
            'drinking_habit' => $drinking_habit,
            'other_drinking_habit' => $other_drinking_habit,
            'about_partner' => $this->request->getPost('about_partner'),
            'status' => 'pending',
        ];

        $exists = $db->table('user_partner_preferences')
            ->where('user_id', $user_id)
            ->get()
            ->getRow();

        if ($exists) {
            $db->table('user_partner_preferences')
                ->where('user_id', $user_id)
                ->update($data);
        } else {
            $db->table('user_partner_preferences')->insert($data);
        }

        return redirect()->back()->with('success', 'Partner preferences saved and submitted successfully');
    }

    public function saveProfileVisibility()
    {
        $db = \Config\Database::connect();
        $session = session();
        $usersession = $session->get('loggedin');
        if (!$usersession) {
            return redirect()->to(base_url('login'));
        }

        $user_id = $usersession['user_id'];

        $data = [
            'visibility_all' => $this->request->getPost('visibility_all') ? 1 : 0,
            'visibility_verified' => $this->request->getPost('visibility_verified') ? 1 : 0,
            'visibility_defence' => $this->request->getPost('visibility_defence') ? 1 : 0,
            'visibility_orange' => $this->request->getPost('visibility_orange') ? 1 : 0,
            'visibility_religion' => $this->request->getPost('visibility_religion'),
            'visibility_caste' => $this->request->getPost('visibility_caste'),
            'visibility_income' => $this->request->getPost('visibility_income'),
            'visibility_following_profiles' => $this->request->getPost('visibility_following_profiles'),
            'hide_profile' => $this->request->getPost('hide_profile') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
            
        ];

        $db->table('user_account')
            ->where('account_id', $user_id)
            ->update($data);

        return redirect()->back()->with('success', 'Profile visibility updated');
    }
    
    public function deletePhoto($id)
{
    $session = session();
    $userId = $session->get('user_id');

    $db = \Config\Database::connect();

    // Get photo (ONLY current user ki photo)
    $photo = $db->table('user_photos')
                ->where('id', $id)
                ->get()
                ->getRow();

    if (!$photo) {
        return redirect()->back()->with('error', 'Photo not found.');
    }

    // File path
    $filePath =  'assets/uploads/' . $photo->photo_path;

    // Delete file from folder
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Delete from database
    $db->table('user_photos')
       ->where('id', $id)
       ->delete();

    return redirect()->back()->with('success', 'Photo deleted successfully.');
}

    // Helper function to get castes via AJAX
    public function get_castes()
    {
        $religion_id = $this->request->getPost('religion_id');
        $db = \Config\Database::connect();
        
        $castes = $db->table('castes')
            ->where('religion_id', $religion_id)
            ->get()
            ->getResult();
            
        return $this->response->setJSON($castes);
    }

    // Helper function to update profile photo
    public function update_profile_photo()
    {
        $session = session();
        $usersession = $session->get('loggedin');
        
        if (!$usersession) {
            return redirect()->to(base_url('login'));
        }
        
        $photo_id = $this->request->getPost('photo_id');
        $file = $this->request->getFile('photo');
        
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Invalid file');
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return redirect()->back()->with('error', 'Only JPG, PNG, WEBP allowed');
        }
        
        $uploadPath = FCPATH . 'assets/uploads/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);
        
        $db = \Config\Database::connect();
        
        // Get old photo to delete
        $oldPhoto = $db->table('user_photos')
            ->where('id', $photo_id)
            ->where('user_id', $usersession['user_id'])
            ->get()
            ->getRow();
            
        if ($oldPhoto) {
            $oldFile = $uploadPath . $oldPhoto->photo_path;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
            
            $db->table('user_photos')
                ->where('id', $photo_id)
                ->update(['photo_path' => $newName, 'status' => 'pending']);
        }
        
        return redirect()->back()->with('success', 'Photo updated successfully');
    }

    // Helper function to add profile photo
   public function add_profile_photo()
{
    $session = session();
    $usersession = $session->get('loggedin');
    
    if (!$usersession) {
        return redirect()->to(base_url('login'));
    }

    $userId = $usersession['user_id'];
    $db = \Config\Database::connect();

    $file = $this->request->getFile('photo');

    if (!$file || !$file->isValid()) {
        return redirect()->back()->with('error', 'Invalid file');
    }

    // File type check
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    if (!in_array($file->getMimeType(), $allowedTypes)) {
        return redirect()->back()->with('error', 'Only JPG, PNG, WEBP allowed');
    }

    $uploadPath = FCPATH . 'assets/uploads/';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // 🔹 Get total existing size
    $existingPhotos = $db->table('user_photos')
                         ->select('photo_path')
                         ->where('user_id', $userId)
                         ->get()
                         ->getResult();

    $totalSize = 0;

    foreach ($existingPhotos as $photo) {
        $filePath = $uploadPath . $photo->photo_path;
        if (file_exists($filePath)) {
            $totalSize += filesize($filePath);
        }
    }

    // Add current file size
    $newFileSize = $file->getSize(); // bytes
    $totalSize += $newFileSize;

    // 10MB = 10 * 1024 * 1024 bytes
    if ($totalSize > (10 * 1024 * 1024)) {
        return redirect()->back()->with('error', 'Total uploaded images size cannot exceed 10MB.');
    }

    // Upload file
    $newName = $file->getRandomName();
    $file->move($uploadPath, $newName);

    // Save to DB
    $db->table('user_photos')->insert([
        'user_id'   => $userId,
        'photo_path'=> $newName,
        'created_at'=> date('Y-m-d H:i:s'),
        'status'    => 'pending',
    ]);

    return redirect()->back()->with('success', 'Photo added successfully');
}

      
public function update_user()
{
    $this->db = \Config\Database::connect();
    $this->session = session();
    $validation = \Config\Services::validation();

    // Check if user is logged in
    $usersession = $this->session->get('loggedin');
    if (!$usersession) {
        return $this->response->setJSON([
            'status' => 'error',
            'title' => 'Session Expired!',
            'message' => 'Please login again to continue.',
            'icon' => 'error'
        ]);
    }

    $user_id = $usersession['user_id'];

    if ($this->request->getMethod() === 'post') {
        $rules = [
            'first_name' => [
                'label' => 'First Name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter First Name',
                ],
            ],
            'last_name' => [
                'label' => 'Last Name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter Last Name',
                ],
            ],
            'marital_status' => 'required',
            'dob' => 'required',
            'religions' => 'required',
            'castes' => 'required',
            'mother_tongue' => 'required',
            'dietary_preference' => 'required',
            'manglik_status' => 'required',
            'kundali_matching' => 'required',
            'education' => 'required',
            'education_level' => 'required',
            'profession' => 'required'
        ];

        // Validate input
        if (!$this->validate($rules)) {
            // Validation failed - return JSON error for SweetAlert
            $errorMessages = implode('<br>', $this->validator->getErrors());
            return $this->response->setJSON([
                'status' => 'error',
                'title' => 'Validation Error!',
                'message' => $errorMessages,
                'icon' => 'warning'
            ]);
        } else {
          
            try {
                $basic_info_data = [
                    'user_name'          => $this->request->getPost('first_name'),
                    'user_last_name'     => $this->request->getPost('last_name'),
                    'marital_status'     => $this->request->getPost('marital_status'),
                    'height'             => $this->request->getPost('height'),
                    'date_of_birth'      => $this->request->getPost('dob'),
                    'time_of_birth'      => $this->request->getPost('tob'),
                    'place_of_birth'     => $this->request->getPost('pob'),
                    'religion_id'        => $this->request->getPost('religions'),
                    'caste_id'           => $this->request->getPost('castes'),
                    'mother_tongue'      => $this->request->getPost('mother_tongue'),
                    'other_languages'    => $this->request->getPost('other_languages'),
                    'dietary_preference' => $this->request->getPost('dietary_preference'),
                    'family_home'        => $this->request->getPost('family_home'),
                    'current_residence'  => $this->request->getPost('current_residence'),
                    'manglik_status'     => $this->request->getPost('manglik_status'),
                    'kundali_matching'   => $this->request->getPost('kundali_matching'),
                    'hobbies' => $this->request->getVar('hobbies')? implode(', ', $this->request->getVar('hobbies')) :'',
                    'updated_at'         => date('Y-m-d H:i:s')
                ];

                // Update user basic info
                $this->db->table('user_account')->where('account_id', $user_id)->update($basic_info_data);

                // Handle file uploads
                $fileUploadResult = $this->handle_file_uploads($user_id);
               

                // Update Education & Profession
                $educationResult = $this->update_education_profession($user_id);
             

                // Update Family Details
                $familyResult = $this->update_family_details($user_id);
              

                // Update Partner Preferences
                $partnerResult = $this->update_partner_preferences($user_id);
              

              

              

                // Success response for SweetAlert
                return $this->response->setJSON([
                    'status' => 'success',
                    'title' => 'Success!',
                    'message' => 'Profile updated successfully!',
                    'icon' => 'success',
                    'redirect' => base_url('profile') // Optional: redirect after success
                ]);

            } catch (\Exception $e) {
                $this->db->transRollback();
                
                // Error response for SweetAlert
                return $this->response->setJSON([
                    'status' => 'error',
                    'title' => 'Update Failed!',
                    'message' => 'Error updating profile: ' . $e->getMessage(),
                    'icon' => 'error'
                ]);
            }
        }
    }
}


public function updateProfilePhoto()
{
    $db = \Config\Database::connect();
    $session = session();
    $usersession = $session->get('loggedin');

    if (!$usersession) {
        return redirect()->to(base_url('login'));
    }

    $user_id = $usersession['user_id'];
    $photo_id = $this->request->getPost('photo_id');
    $photo = $this->request->getFile('photo');

    if (!$photo || !$photo->isValid()) {
        return redirect()->back()->with('error', 'Invalid photo');
    }

    // âœ… Count existing photos
    $photoCount = $db->table('user_photos')
                     ->where('user_id', $user_id)
                     ->countAllResults();

    if ($photoCount >= 10) {
        return redirect()->back()->with('error', 'You can upload maximum 10 photos only');
    }

    // âœ… 10MB File Size Limit
    $maxSize = 10 * 1024 * 1024;

    if ($photo->getSize() > $maxSize) {
        return redirect()->back()->with('error', 'Photo size must be less than 10MB');
    }

    // âœ… Allowed Types
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

    if (!in_array($photo->getMimeType(), $allowedTypes)) {
        return redirect()->back()->with('error', 'Only JPG, PNG, WEBP allowed');
    }

    // OLD PHOTO DELETE (only if belongs to user)
    $old = $db->table('user_photos')
              ->where('id', $photo_id)
              ->where('user_id', $user_id)
              ->get()
              ->getRow();

    if ($old && !empty($old->photo_path)) {
        $oldPath = FCPATH . 'assets/uploads/' . $old->photo_path;
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }

    // UPLOAD NEW PHOTO
    $newName = time() . '_' . $photo->getRandomName();
    $photo->move(FCPATH . 'assets/uploads', $newName);

    // UPDATE DB
    $db->table('user_photos')
        ->where('id', $photo_id)
        ->where('user_id', $user_id)
        ->update([
            'photo_path' => $newName,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    return redirect()->back()->with('success', 'Photo updated successfully');
}

public function addProfilePhoto()
{
    $db = \Config\Database::connect();
    $session = session();

    if (!$session->get('loggedin')) {
        return redirect()->to(base_url('login'));
    }

    $user_id = $session->get('loggedin')['user_id'];

    // ✅ CI4 Validation (per image validation only)
    $validationRule = [
        'photo' => [
            'label' => 'Photo',
            'rules' => 'uploaded[photo]'
                . '|is_image[photo]'
                . '|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]'
                . '|max_size[photo,5120]', // optional: 5MB per image limit
        ],
    ];

    if (!$this->validate($validationRule)) {
        return redirect()->back()->with('error', $this->validator->getError('photo'));
    }

    $photo = $this->request->getFile('photo');

    if (!$photo || !$photo->isValid() || $photo->hasMoved()) {
        return redirect()->back()->with('error', 'Invalid file');
    }

    // ✅ Upload Path
    $uploadPath = FCPATH . 'assets/uploads/';

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // ✅ Calculate Existing Total Size
    $existingPhotos = $db->table('user_photos')
                        ->select('photo_path')
                        ->where('user_id', $user_id)
                        ->get()
                        ->getResult();

    $totalSize = 0;

    foreach ($existingPhotos as $row) {
        $filePath = $uploadPath . $row->photo_path;
        if (file_exists($filePath)) {
            $totalSize += filesize($filePath);
        }
    }

    // Add new file size
    $newFileSize = $photo->getSize(); // bytes
    $totalSize += $newFileSize;

    // ✅ 10MB total limit check
    if ($totalSize > (10 * 1024 * 1024)) {
        return redirect()->back()->with('error', 'Total uploaded images size cannot exceed 10MB.');
    }

    // ✅ Upload
    $newName = time() . '_' . $photo->getRandomName();
    $photo->move($uploadPath, $newName);

    // Count photos for first active image logic
    $count = $db->table('user_photos')
                ->where('user_id', $user_id)
                ->countAllResults();

    // ✅ Insert DB
    $db->table('user_photos')->insert([
        'user_id'     => $user_id,
        'photo_path'  => $newName,
        'is_active'   => ($count == 0 ? 1 : 0),
        'created_at'  => date('Y-m-d H:i:s'),
          'status'    => 'pending',
    ]);

    return redirect()->back()->with('success', 'Photo added successfully');
}


    
      public function order_details($id)
{
     $session = session();
 
      $commanmodel = new Commanmodel();
   
       $item = $commanmodel->get_single_query('booking_product',array('booking_product_order_id'=> $id));
        $vender = $commanmodel->get_single_query('admin',array('id'=> $item->booking_product_vender));
     
          $order = $commanmodel->get_single_query('order_book',array('order_book_id'=> $item->booking_product_order_book_id));
          
                $data = array(
        'title' => "Thank you : Event", 
        'keyword' => "Home : Event",
        'description' => "Home : Event",
        'search' => '',
        'order'=>$order,
      'item' => $item,
      'vender' => $vender,
     
           'id' => $id,  
        'searchcategory' => 'all',
        'pageurl' => base_url(), 
        'pageimage' => base_url('assets/images/logo.png')
		);


       
          return view('frontend/header', $data).view('frontend/dashboard/order_details').view('frontend/footer');
}
}
