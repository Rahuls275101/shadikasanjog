<?php 
namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['chat_id', 'sender_id', 'receiver_id', 'message', 'message_type', 'file_path', 'is_read', 'read_at', 'created_at'];
    
    /**
     * Send new message
     */
    public function sendMessage($chatId, $senderId, $receiverId, $message, $type = 'text', $filePath = null)
    {
        $messageId = $this->insert([
            'chat_id' => $chatId,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'message_type' => $type,
            'file_path' => $filePath,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $messageId;
    }
    
    /**
     * Get chat messages
     */
    public function getChatMessages($chatId, $limit = 50, $offset = 0)
    {
        return $this->where('chat_id', $chatId)
                   ->orderBy('created_at', 'ASC')
                   ->limit($limit, $offset)
                   ->get()
                   ->getResult();
    }
    
    /**
     * Mark messages as read
     */
    public function markAsRead($chatId, $receiverId)
    {
        return $this->where('chat_id', $chatId)
                   ->where('receiver_id', $receiverId)
                   ->where('is_read', 0)
                   ->set([
                       'is_read' => 1,
                       'read_at' => date('Y-m-d H:i:s')
                   ])
                   ->update();
    }
    
    /**
     * Get unread message count for user
     */
    public function getUnreadCount($userId)
    {
        return $this->where('receiver_id', $userId)
                   ->where('is_read', 0)
                   ->countAllResults();
    }
}