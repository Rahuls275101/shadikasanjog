<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InterestModel;
use App\Models\Commanmodel;

class InterestController extends BaseController
{
    protected $interestModel;

    public function __construct()
    {
        $this->interestModel = new InterestModel();
    }

public function send()
{
    $response = service('response');
    $session = session();
    $usersession = $session->get('loggedin');
  $commanmodel = new Commanmodel();
    if (!$usersession) {
        return $response->setJSON([
            'status' => false,
            'message' => 'Please login to send interest.'
        ]);
    }
    
    
    
    
        $userdata = $commanmodel->get_single_query(
        'user_account',
        ['account_id' => $usersession['user_id']]
    );


if ((int)$userdata->membership_id === 1) {
    return $this->response->setJSON([
        'status'  => false,
        'message' => 'To use this feature you are requested to become a paid member. Visit Manage Plan and select your plan.'
    ]);
}
    // 🔒 Membership Check
    if (!isset($userdata->membership_status) || $userdata->membership_status !== 'Active') {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'To use this feature you are requested to become a paid member. Visit Manage Plan and select your plan.'
        ]);
    }

    $userId = $usersession['user_id'];
    $receiver_id = $this->request->getPost('receiver_id');
    $message = $this->request->getPost('message');

    if (!$receiver_id) {
        return $response->setJSON([
            'status' => false,
            'message' => 'Receiver is required.'
        ]);
    }

    if ($userId == (int)$receiver_id) {
        return $response->setJSON([
            'status' => false,
            'message' => 'You cannot send interest to yourself.'
        ]);
    }

    // 🔎 Check existing interest (both sides)
    $existing = $this->interestModel
        ->groupStart()
            ->where([
                'sender_id' => $userId,
                'receiver_id' => $receiver_id,
            ])
            ->orGroupStart()
                ->where([
                    'sender_id' => $receiver_id,
                    'receiver_id' => $userId,
                ])
            ->groupEnd()
        ->groupEnd()
        ->whereIn('status', ['pending', 'accepted'])
        ->first();

    if ($existing) {
        return $response->setJSON([
            'status' => false,
            'message' => 'Interest already sent! Please wait for response.'
        ]);
    }

    // ✅ Insert new interest
    $data = [
        'sender_id' => $userId,
        'receiver_id' => $receiver_id,
        'message' => $message,
        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s')
    ];

    $this->interestModel->insert($data);

    return $response->setJSON([
        'status' => true,
        'message' => 'Interest sent successfully!'
    ]);
}

    public function accept()
    {
        $response = service('response');
        $session = session();

        $usersession = $session->get('loggedin');

        if (!$usersession) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Please login to continue.'
            ])->setStatusCode(200);
        }

        $userId = $usersession['user_id'];
        $interestId = $this->request->getPost('interest_id');
        $action = $this->request->getPost('action') ?? 'accept';

        if (!$interestId) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Interest ID is required.'
            ])->setStatusCode(200);
        }

        $interest = $this->interestModel->find($interestId);
        if (!$interest) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Interest not found.'
            ])->setStatusCode(200);
        }

        if ((int)$interest['receiver_id'] !== (int)$userId) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Unauthorized action.'
            ])->setStatusCode(200);
        }

        if ($action === 'accept') {
            $this->interestModel->update($interestId, ['status' => 'accepted']);
            return $response->setJSON([
                'status' => true,
                'message' => 'Interest accepted successfully.'
            ])->setStatusCode(200);
        } else {
            $this->interestModel->update($interestId, ['status' => 'rejected']);
            return $response->setJSON([
                'status' => true,
                'message' => 'Interest rejected successfully.'
            ])->setStatusCode(200);
        }
    }
 public function reject()
    {
        $response = service('response');
        $session = session();
        $usersession = $session->get('loggedin');

        if (!$usersession) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Please login to continue.'
            ])->setStatusCode(200);
        }

        $userId = $usersession['user_id'];
        $interestId = $this->request->getPost('interest_id');

        if (!$interestId) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Interest ID is required.'
            ])->setStatusCode(200);
        }

        $interest = $this->interestModel->find($interestId);
        if (!$interest) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Interest not found.'
            ])->setStatusCode(200);
        }

        if ((int)$interest['receiver_id'] !== (int)$userId) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Unauthorized action.'
            ])->setStatusCode(200);
        }

        $this->interestModel->update($interestId, ['status' => 'rejected']);

        return $response->setJSON([
            'status' => true,
            'message' => 'Interest rejected successfully.'
        ])->setStatusCode(200);
    }
    public function received()
    {
        $response = service('response');
        $session = session();

        $usersession = $session->get('loggedin');

        if (!$usersession) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Please login to view received interests.'
            ])->setStatusCode(200);
        }

        $userId = $usersession['user_id'];

        $list = $this->interestModel
            ->where('receiver_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return $response->setJSON([
            'status' => true,
            'data' => $list
        ])->setStatusCode(200);
    }

    public function sent()
    {
        $response = service('response');
        $session = session();

        $usersession = $session->get('loggedin');

        if (!$usersession) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Please login to view sent interests.'
            ])->setStatusCode(200);
        }

        $userId = $usersession['user_id'];

        $list = $this->interestModel
            ->where('sender_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return $response->setJSON([
            'status' => true,
            'data' => $list
        ])->setStatusCode(200);
    }
}
