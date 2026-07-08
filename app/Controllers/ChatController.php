<?php 
namespace App\Controllers;

use App\Models\Commanmodel;

class ChatController extends BaseController
{
    /**
     * ✅ Membership check helper (controller ke andar)
     */
    private function isMembershipActive($userdata): bool
    {
        $status = isset($userdata->membership_status) ? strtolower(trim((string)$userdata->membership_status)) : '';
        $end    = isset($userdata->membership_end_date) ? trim((string)$userdata->membership_end_date) : '';

        if ($status !== 'active') return false;
        if (empty($end)) return false;

        $endTs = strtotime($end);
        if ($endTs === false) return false;

        return ($endTs >= time());
    }

    public function index()
    {
        $session = session();
        $usersession = $session->get('loggedin');
        
        if (!$usersession) {
            return redirect()->to('/login');
        }

        // ✅ Load SweetAlert helper
        helper('sweet_alert');

        $userId = $usersession['user_id'];

        // ✅ Fetch user membership status from user_account
        $commanmodel = new Commanmodel();
        $userdata = $commanmodel->get_single_query('user_account', ['account_id' => $userId]);

        if (!$userdata) {
            echo showSweetAlert('Error', 'User not found.', 'error', base_url('login'));
            exit;
        }

        // ✅ Membership check
        if (!$this->isMembershipActive($userdata)) {
            echo showSweetAlert(
                'Membership Required',
                'Chat feature is available only for users with an <b>Active Membership</b>.<br><br>Please purchase/renew your plan to continue.',
                'warning',
                base_url('plans'),
                [
                    'confirmButtonText' => 'Buy / Renew Plan',
                    'timer' => 4000,
                    'timerProgressBar' => true
                ]
            );
            exit;
        }

        $interestsModel = new \App\Models\InterestsModel();
        
        // Get user's chat partners from interests (mutual interests)
        $data['chatPartners'] = $interestsModel->getUserChatPartners($userId);
        
        // If no mutual interests found, show simple interested users for testing
        if (empty($data['chatPartners'])) {
            $data['chatPartners'] = $interestsModel->getInterestedUsers($userId);
        }
        
        return view('frontend/chat/list', $data);
    }
    
  public function getChat($partnerId)
{
    $session = session();
    $usersession = $session->get('loggedin');
    
    if (!$usersession) {
        return $this->response->setJSON(['status' => false, 'message' => 'Not authenticated']);
    }

    $userId = $usersession['user_id'];

    // ❌ Prevent self chat
    if ($userId == $partnerId) {
        return $this->response->setJSON([
            'status' => false,
            'code' => 'YOURSELF',
            'message' => 'You cannot chat with yourself.'
        ]);
    }

    // ✅ Membership check
    $commanmodel = new Commanmodel();
    $userdata = $commanmodel->get_single_query('user_account', ['account_id' => $userId]);

    if (!$userdata || !$this->isMembershipActive($userdata)) {
        return $this->response->setJSON([
            'status' => false,
            'code' => 'MEMBERSHIP_REQUIRED',
            'message' => 'To use this feature you are requested to become a paid member. Visit Manage Plan and select your plan.',
            'redirect' => base_url('plans')
        ]);
    }
    
    $interestsModel = new \App\Models\InterestsModel();
    $chatModel = new \App\Models\ChatModel();
    $messageModel = new \App\Models\MessageModel();
    
    // Check mutual interest
    if (!$interestsModel->mutualInterestExists($userId, $partnerId)) {
        return $this->response->setJSON([
            'status' => false, 
            'message' => 'You can only chat with users who have mutual interest'
        ]);
    }
   
    // Get or create chat thread
    $chat = $chatModel->getChatThread($userId, $partnerId);

    $messages = $messageModel->getChatMessages($chat['id']);
    
    $messageModel->markAsRead($chat['id'], $userId);
    
    return $this->response->setJSON([
        'status' => true,
        'chat_id' => $chat['id'],
        'messages' => $messages
    ]);
}
    
    public function sendMessage()
    {
        $session = session();
        $usersession = $session->get('loggedin');
        
        if (!$usersession) {
            return $this->response->setJSON(['status' => false, 'message' => 'Not authenticated']);
        }
        
        $userId = $usersession['user_id'];
        $receiverId = $this->request->getPost('receiver_id');
        $message = $this->request->getPost('message');

        // ✅ Membership check (API)
        $commanmodel = new Commanmodel();
        $userdata = $commanmodel->get_single_query('user_account', ['account_id' => $userId]);

        if (!$userdata || !$this->isMembershipActive($userdata)) {
            return $this->response->setJSON([
                'status' => false,
                'code' => 'MEMBERSHIP_REQUIRED',
                'message' => 'Chat is available only for active membership users.',
                'redirect' => base_url('plans')
            ]);
        }
        
        if (empty($message)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Message cannot be empty']);
        }
        
        $interestsModel = new \App\Models\InterestsModel();
        $chatModel = new \App\Models\ChatModel();
        $messageModel = new \App\Models\MessageModel();
        
        // Check if mutual interest exists
        if (!$interestsModel->mutualInterestExists($userId, $receiverId)) {
            return $this->response->setJSON([
                'status' => false, 
                'message' => 'You can only chat with users who have mutual interest'
            ]);
        }
      
        // Get or create chat thread
        $chat = $chatModel->getChatThread($userId, $receiverId);
        
        // Send message to messages table
        $messageId = $messageModel->sendMessage($chat['id'], $userId, $receiverId, $message);
        
        if ($messageId) {
            // Update last message in chat
            $chatModel->updateLastMessage($chat['id'], $message);
            
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Message sent successfully',
                'message_id' => $messageId
            ]);
        } else {
            return $this->response->setJSON(['status' => false, 'message' => 'Failed to send message']);
        }
    }
    
    public function getUnreadCount()
    {
        $session = session();
        $usersession = $session->get('loggedin');
        
        if (!$usersession) {
            return $this->response->setJSON(['status' => false, 'message' => 'Not authenticated']);
        }

        $userId = $usersession['user_id'];

        // ✅ Membership check (API)
        $commanmodel = new Commanmodel();
        $userdata = $commanmodel->get_single_query('user_account', ['account_id' => $userId]);

        if (!$userdata || !$this->isMembershipActive($userdata)) {
            return $this->response->setJSON([
                'status' => false,
                'code' => 'MEMBERSHIP_REQUIRED',
                'message' => 'Chat is available only for active membership users.',
                'redirect' => base_url('plans'),
                'unread_count' => 0
            ]);
        }
        
        $messageModel = new \App\Models\MessageModel();
        $unreadCount = $messageModel->getUnreadCount($userId);
        
        return $this->response->setJSON([
            'status' => true,
            'unread_count' => $unreadCount
        ]);
    }
}
