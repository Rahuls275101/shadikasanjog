<?php 
namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table = 'chats';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user1_id', 'user2_id', 'last_message', 'last_message_time'];
    
    /**
     * Get or create chat thread between two users
     */
 public function getChatThread($user1_id, $user2_id)
{
    // ✅ Try to find existing chat thread in both directions
    $chat = $this->where('(user1_id = ' . $this->db->escape($user1_id) . ' AND user2_id = ' . $this->db->escape($user2_id) . ') 
                         OR (user1_id = ' . $this->db->escape($user2_id) . ' AND user2_id = ' . $this->db->escape($user1_id) . ')')
                 ->first();

    // ✅ If not found, create a new chat
    if (!$chat) {
        $data = [
            'user1_id'   => $user1_id,
            'user2_id'   => $user2_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->insert($data);
        $chatId = $this->getInsertID();

        $chat = $this->find($chatId);
    }

    return $chat;
}

    
    /**
     * Update last message in chat
     */
    public function updateLastMessage($chatId, $message)
    {
        return $this->update($chatId, [
            'last_message' => $message,
            'last_message_time' => date('Y-m-d H:i:s')
        ]);
    }
}