<?php 
namespace App\Models;

use CodeIgniter\Model;

class InterestsModel extends Model
{
    protected $table = 'interests';
    protected $primaryKey = 'id';
    protected $allowedFields = ['sender_id', 'receiver_id', 'message', 'status', 'created_at'];
    
    /**
     * Get user's chat partners from interests table (mutual interests only)
     */
   public function getUserChatPartners($userId)
{
    $userId = (int)$userId; // ✅ ensure integer for safety

    $sql = "
        SELECT 
            u.account_id AS partner_account_id,
            u.user_name AS partner_name,
            u.user_last_name AS partner_last_name,
            u.user_photo AS partner_photo,
            c.id AS chat_id,
            c.last_message AS partner_message,
            c.last_message_time AS partner_time,
            (
                SELECT COUNT(*) 
                FROM messages m 
                WHERE m.chat_id = c.id 
                  AND m.receiver_id = ? 
                  AND m.is_read = 0
            ) AS unread_count
        FROM interests i1
        INNER JOIN interests i2 
            ON i1.sender_id = i2.receiver_id 
           AND i1.receiver_id = i2.sender_id 
        INNER JOIN user_account u 
            ON (u.account_id = i1.sender_id OR u.account_id = i1.receiver_id)
        LEFT JOIN chats c 
            ON (c.user1_id = ? AND c.user2_id = u.account_id) 
             OR (c.user1_id = u.account_id AND c.user2_id = ?)
        WHERE u.account_id != ?
          AND (
              i1.sender_id = ? 
              OR i1.receiver_id = ?
          )
        GROUP BY u.account_id
        ORDER BY c.last_message_time DESC
    ";

    $query = $this->db->query($sql, [$userId, $userId, $userId, $userId, $userId, $userId]);
    return $query->getResult();
}

    
    /**
     * Check if mutual interest exists between two users
     */
    public function mutualInterestExists($user1_id, $user2_id)
    {
        // Check if both users have shown interest in each other
        $interest1 = $this->where('sender_id', $user1_id)
                         ->where('receiver_id', $user2_id)
                         ->countAllResults();
                         
        $interest2 = $this->where('sender_id', $user2_id)
                         ->where('receiver_id', $user1_id)
                         ->countAllResults();
        
        
        
        if($interest1 > 0) {
            return true;
        }
         else if($interest2 > 0) {
              return true;
         } else {
              return false;
         }
    }
    
    /**
     * Get simple interested users list (for testing)
     */
    public function getInterestedUsers($userId)
    {
        $query = $this->db->query("
            SELECT DISTINCT
                u.account_id as partner_account_id,
                u.user_name as partner_name,
                u.user_last_name as partner_last_name,
                u.user_photo as partner_photo
            FROM interests i
            INNER JOIN user_account u ON (i.sender_id = u.account_id OR i.receiver_id = u.account_id)
            WHERE (i.sender_id = $userId OR i.receiver_id = $userId) 
            AND u.account_id != $userId
            GROUP BY u.account_id
        ");
        
        return $query->getResult();
    }
}