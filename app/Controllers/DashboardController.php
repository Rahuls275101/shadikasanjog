<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InterestModel;
use App\Models\Commanmodel;

class DashboardController extends BaseController
{
    protected $interestModel;
    protected $commanmodel;

    public function __construct()
    {
        $this->interestModel = new InterestModel();
        $this->commanmodel = new Commanmodel();
    }

    public function dashboard_interests()
    {
        $session = session();
        $usersession = $session->get('loggedin');

        if (!$usersession) {
            return redirect()->to('login');
        }

        $userId = $usersession['user_id'];

        $data = [
            'user_id' => $userId
        ];

        return view('frontend/dashboard/interests_dashboard', $data);
    }

   public function ajax_dashboard_interests()
{
    $session = session();
    $usersession = $session->get('loggedin');
    $userId = $usersession['user_id'];

    $type = $this->request->getPost('type');
    $limit = (int)($this->request->getPost('limit') ?? 6);
    $offset = (int)($this->request->getPost('offset') ?? 0);

    $html = '';
    $hasMore = false;
    $count = 0;
    $totalCount = 0;

    try {
       if ($type === 'mutual') {

    $mutualInterests = $this->interestModel
        ->where('status', 'accepted')
        ->groupStart()
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
        ->groupEnd()
        ->orderBy('created_at', 'DESC')
        ->limit($limit, $offset)
        ->findAll();

    $totalCount = $this->interestModel
        ->where('status', 'accepted')
        ->groupStart()
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
        ->groupEnd()
        ->countAllResults();

    $count = count($mutualInterests);

    foreach ($mutualInterests as $interest) {

        $otherUserId = ($interest['sender_id'] == $userId)
            ? $interest['receiver_id']
            : $interest['sender_id'];

        $userData = $this->commanmodel
            ->get_single_query('user_account', ['account_id' => $otherUserId]);

        if ($userData) {
            $html .= $this->generateInterestCard($userData, $interest, 'mutual');
        }
    }

    if (empty($html)) {
        $html = '<li class="text-center py-3">No mutual interests found</li>';
    }
}

        elseif ($type === 'received') {
            // Received interests - Pending
            $receivedInterests = $this->interestModel
                ->where('receiver_id', $userId)
                ->where('status', 'pending')
                ->orderBy('created_at', 'DESC')
                ->limit($limit, $offset)
                ->findAll();

            $totalCount = $this->interestModel
                ->where('receiver_id', $userId)
                ->where('status', 'pending')
                ->countAllResults();

            $count = count($receivedInterests);
            
            foreach ($receivedInterests as $interest) {
                $senderId = $interest['sender_id'];
                $userData = $this->commanmodel->get_single_query('user_account', ['account_id' => $senderId]);
                
                if ($userData) {
                    $html .= $this->generateInterestCard($userData, $interest, 'received');
                }
            }
            
            if (empty($html)) {
                $html = '<li class="text-center py-3">No pending received interests</li>';
            }
        } 
        elseif ($type === 'sent') {
            // Sent interests - Pending
            $sentInterests = $this->interestModel
                ->where('sender_id', $userId)
                ->where('status', 'pending')
                ->orderBy('created_at', 'DESC')
                ->limit($limit, $offset)
                ->findAll();

            $totalCount = $this->interestModel
                ->where('sender_id', $userId)
                ->where('status', 'pending')
                ->countAllResults();

            $count = count($sentInterests);
            
            foreach ($sentInterests as $interest) {
                $receiverId = $interest['receiver_id'];
                $userData = $this->commanmodel->get_single_query('user_account', ['account_id' => $receiverId]);
                
                if ($userData) {
                    $html .= $this->generateInterestCard($userData, $interest, 'sent');
                }
            }
            
            if (empty($html)) {
                $html = '<li class="text-center py-3">No pending sent interests</li>';
            }
        }

        $hasMore = ($offset + $count) < $totalCount;

        return $this->response->setJSON([
            'status' => true,
            'html' => $html,
            'hasMore' => $hasMore,
            'count' => $count,
            'total' => $totalCount,
            'type' => $type
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Dashboard interests error: ' . $e->getMessage());
        
        return $this->response->setJSON([
            'status' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'html' => '<li class="text-center py-3 text-danger">Error loading data: ' . $e->getMessage() . '</li>',
            'hasMore' => false,
            'count' => 0,
            'total' => 0,
            'type' => $type
        ]);
    }
}

  private function generateInterestCard($userData, $interest, $type = 'mutual')
{
    $session = session();
    $usersession = $session->get('loggedin');
    $currentUserId = $usersession['user_id'];

    $age = '';
    if (!empty($userData->date_of_birth)) {
        $birthDate = new \DateTime($userData->date_of_birth);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y . ' Years';
    }

    $profession = $userData->profession ?? 'Not specified';
    $profileImage = $this->commanmodel->profile_image($userData->account_id);

    $actionButton = '';

    if ($type === 'mutual') {
        $actionButton = '<button type="button" class="btn btn-danger remove-interest" data-id="'.$interest['id'].'">Cancel</button>';
    } 
    elseif ($type === 'received' && $interest['status'] === 'pending') {
        $actionButton = '
            <button type="button" class="btn btn-success accept-interest me-2" data-id="'.$interest['id'].'">Accept</button>
            <button type="button" class="btn btn-danger reject-interest" data-id="'.$interest['id'].'">Cancel</button>
        ';
    } 
    elseif ($type === 'sent' && $interest['status'] === 'pending') {
        $actionButton = '<button type="button" class="btn btn-danger cancel-interest" data-id="'.$interest['id'].'">Cancel</button>';
    }

    $city = $userData->current_residence ?? $userData->family_home ?? 'Not specified';

    $displayId = $userData->user_id ?? 'N/A';
    $firstName = $userData->user_name ?? '';
    $lastName = $userData->user_last_name ?? '';

    $profileUrl = base_url('profile-details/'.$userData->user_id);

 $html =  '
    <li class="list-group-item">
        <div class="card p-2">

            <!-- Clickable Profile Area -->
            <a href="'.$profileUrl.'" class="text-decoration-none text-dark">
                <div class="row">
                    <div class="col-4 text-center">
                        <img src="'.$profileImage.'" class="img-fluid rounded-start" alt="profile-img">';
                        
                             if (!empty($userData->verified) && strtolower(trim($userData->verified)) === 'yes') {
                    $html .=   '<span class="verified-badge">Verified</span>';
                }
                    $html .= '</div>
                    <div class="col-8">
                        <ul class="list-group detal-list">
                            <li><strong>ID: '.$displayId.'</strong></li>
                            <li>'.$firstName.' '.$lastName.'</li>';
                            
                            
                             if (!empty($userData->verified) && strtolower(trim($userData->verified)) === 'yes') {
                    //$html .=   '<li><span class="verified-badge">Verified</span></li>';
                }
                            $html .=  '<li>'.$age.'</li>
                           
                            <li>'.$city.'</li>
                        </ul>
                    </div>
                </div>
            </a>

            <!-- Buttons -->
            <div class="text-center mt-2">
                '.$actionButton.'
            </div>

        </div>
    </li>
    ';
    
    return $html;
}

    public function removeInterest()
    {
        $session = session();
        $usersession = $session->get('loggedin');
        $userId = $usersession['user_id'];

        $interestId = $this->request->getPost('interest_id');

        $interest = $this->interestModel->find($interestId);

        if (!$interest) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Interest not found.'
            ]);
        }

        // Check if user has permission to remove
        if ($interest['sender_id'] != $userId && $interest['receiver_id'] != $userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Unauthorized action.'
            ]);
        }

        // Delete the interest
        $this->interestModel->delete($interestId);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Interest removed successfully.'
        ]);
    }

    public function cancelSentInterest()
    {
        $session = session();
        $usersession = $session->get('loggedin');
        $userId = $usersession['user_id'];

        $interestId = $this->request->getPost('interest_id');

        $interest = $this->interestModel->find($interestId);

        if (!$interest) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Interest not found.'
            ]);
        }

        if ($interest['sender_id'] != $userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'You can only cancel interests sent by you.'
            ]);
        }

        // Only allow cancel if status is pending
        if ($interest['status'] != 'pending') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Cannot cancel accepted interest.'
            ]);
        }

        // Delete the interest
        $this->interestModel->delete($interestId);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Interest cancelled successfully.'
        ]);
    }

    public function getCounts()
    {
        $session = session();
        $usersession = $session->get('loggedin');
        $userId = $usersession['user_id'];

        try {
            // Mutual count
            
     
                
                    $mutualCount = $this->interestModel
        ->where('status', 'accepted')
        ->groupStart()
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
        ->groupEnd()
        ->countAllResults();

                
        

            // Received count
            $receivedCount = $this->interestModel
                ->where('receiver_id', $userId)
                ->where('status', 'pending')
                ->countAllResults();

            // Sent count
            $sentCount = $this->interestModel
                ->where('sender_id', $userId)
                ->where('status', 'pending')
                ->countAllResults();

            return $this->response->setJSON([
                'status' => true,
                'counts' => [
                    'mutual' => (int)$mutualCount,
                    'received' => (int)$receivedCount,
                    'sent' => (int)$sentCount
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Counts error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Error loading counts',
                'counts' => [
                    'mutual' => 0,
                    'received' => 0,
                    'sent' => 0
                ]
            ]);
        }
    }
}