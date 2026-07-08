<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Models\Commanmodel;
use DateTime;

class Interestlistmodel extends Model
{
    protected $table = 'interests';
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * ✅ Base query that automatically detects sender/receiver role
     */
    private function _base_query($userId, $status)
    {
        $builder = $this->db->table('interests');
        
        // Select only actual fields from tables
        $builder->select("
            interests.id AS interestsid,
            interests.sender_id,
            interests.receiver_id,
            interests.status,
            interests.created_at,
             sender_user.user_id as sender_user_id,
            sender_user.account_id as sender_account_id,
            sender_user.user_name as sender_user_name,
            sender_user.user_last_name as sender_user_last_name,
            sender_user.date_of_birth as sender_dob,
            sender_user.height as sender_height,
               receiver_user.user_id as receiver_user_id,
            receiver_user.account_id as receiver_account_id,
            receiver_user.user_name as receiver_user_name,
            receiver_user.user_last_name as receiver_user_last_name,
            receiver_user.date_of_birth as receiver_dob,
            receiver_user.height as receiver_height,
            sender_edu.profession as sender_profession,
            receiver_edu.profession as receiver_profession
        ");
        
        // Join actual tables
        $builder->join('user_account as sender_user', 'sender_user.account_id = interests.sender_id', 'left');
        $builder->join('user_account as receiver_user', 'receiver_user.account_id = interests.receiver_id', 'left');
        $builder->join('user_education as sender_edu', 'sender_edu.user_id = interests.sender_id', 'left');
        $builder->join('user_education as receiver_edu', 'receiver_edu.user_id = interests.receiver_id', 'left');
        
        // Where conditions
        $builder->where("(interests.sender_id = {$userId} OR interests.receiver_id = {$userId})");
        $builder->where('interests.status', $status);
        $builder->where('(sender_user.user_status = "Active" OR receiver_user.user_status = "Active")');
            
        $builder->orderBy('interests.created_at', 'DESC');
        return $builder;
    }

    /**
     * ✅ Count total results for pagination
     */
    public function count_all_status($userId, $status)
    {
        try {
            $builder = $this->_base_query($userId, $status);
            return $builder->countAllResults(false);
        } catch (\Exception $e) {
            log_message('error', 'Count error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * ✅ Fetch paginated HTML data - automatically detects role
     */
    public function fetch_html_by_status($userId, $status, $limit, $start)
    {
        $commanmodel = new Commanmodel();
        
        try {
            $builder = $this->_base_query($userId, $status);
            $builder->limit($limit, $start);
            $query = $builder->get();

            $output = '';

            if ($query->getNumRows() > 0) {
                foreach ($query->getResult() as $row) {
                    // ✅ Determine if current user is sender or receiver
                    $isSender = ($row->sender_id == $userId);
                    
                    // ✅ Display user info based on role
                    if ($isSender) {
                        // Current user is sender, so show receiver's info
                        $displayUserIdurl = $row->receiver_user_id;
                        $displayUserId = $row->receiver_account_id;
                        $displayUserName = $row->receiver_user_name . ' ' . $row->receiver_user_last_name;
                        $displayUserDob = $row->receiver_dob;
                        $displayUserHeight = $row->receiver_height;
                        $profession = $row->receiver_profession ?? 'Not specified';
                        $relationshipText = '<small class="text-info">You sent interest</small>';
                    } else {
                        // Current user is receiver, so show sender's info
                           $displayUserIdurl = $row->sender_user_id;
                        $displayUserId = $row->sender_account_id;
                        $displayUserName = $row->sender_user_name . ' ' . $row->sender_user_last_name;
                        $displayUserDob = $row->sender_dob;
                        $displayUserHeight = $row->sender_height;
                        $profession = $row->sender_profession ?? 'Not specified';
                        $relationshipText = '<small class="text-success">Sent you interest</small>';
                    }

                    // ✅ Calculate Age for display user
                    $age = 'Not specified';
                    if (!empty($displayUserDob)) {
                        try {
                            $birthDate = new DateTime($displayUserDob);
                            $today = new DateTime();
                            $age = $today->diff($birthDate)->y;
                        } catch (\Exception $e) {
                            $age = 'Invalid date';
                        }
                    }

                    // ✅ Other profile info
                    $profile_img = $commanmodel->profile_image($displayUserId);
                    $height = !empty($displayUserHeight) ? $displayUserHeight . ' Cm' : 'Not specified';

                    // ✅ Action buttons based on status and role
                    $action_buttons = '';
                    
                    if ($isSender) {
                        // Current user SENT the interest
                        if ($status === 'pending') {
                            $action_buttons = '<span class="badge bg-warning text-dark">Waiting for response</span>';
                        } elseif ($status === 'accepted') {
                            $action_buttons = '<span class="badge bg-success">They Accepted</span>';
                        } elseif ($status === 'rejected') {
                            $action_buttons = '<span class="badge bg-danger">They Rejected</span>';
                        }
                    } else {
                        // Current user RECEIVED the interest
                        if ($status === 'pending') {
                            $action_buttons = '
                                <button type="button" class="btn btn-success btn-sm accept-interest" data-id="'.$row->interestsid.'">Accept</button>
                                <button type="button" class="btn btn-outline-danger btn-sm reject-interest" data-id="'.$row->interestsid.'">Reject</button>
                            ';
                        } elseif ($status === 'accepted') {
                            $action_buttons = '<span class="badge bg-success">You Accepted</span>';
                        } elseif ($status === 'rejected') {
                            $action_buttons = '<span class="badge bg-danger">You Rejected</span>';
                        }
                    }

                    // ✅ Final HTML output for each profile
                    $output .= '
                        <li>
                            <div class="db-int-pro-1">
                                <img src="' . $profile_img . '" alt="">
                                <span class="badge bg-primary user-pla-pat">Platinum user</span>
                            </div>
                            <div class="db-int-pro-2">
                                <h5>' . esc($displayUserName) . '</h5>
                                ' . $relationshipText . '
                                <ol class="poi">
                                    <li>Age: <strong>' . esc($age) . '</strong></li>
                                    <li>Height: <strong>' . esc($height) . '</strong></li>
                                    <li>Job: <strong>' . esc($profession) . '</strong></li>
                                </ol>
                                <a href="' . base_url('profile-details/' . $displayUserIdurl) . '" class="cta-5" target="_blank">View full profile</a>
                            </div>
                            <div class="db-int-pro-3">
                                ' . $action_buttons . '
                            </div>
                        </li>
                    ';
                }
            } else {
                $output = '<div class="col-md-12 text-center"><h3>No ' . ucfirst($status) . ' interests found!</h3></div>';
            }

            return $output;

        } catch (\Exception $e) {
            log_message('error', 'Fetch HTML error: ' . $e->getMessage());
            return '<div class="col-md-12 text-center"><h3>Error loading data: ' . $e->getMessage() . '</h3></div>';
        }
    }

    /**
     * ✅ Get counts for dashboard/tabs
     */
    public function get_interest_counts($userId)
    {
        $statuses = ['pending', 'accepted', 'rejected'];
        $counts = [];
        
        foreach ($statuses as $status) {
            $counts[$status] = $this->count_all_status($userId, $status);
        }
        
        return $counts;
    }
}