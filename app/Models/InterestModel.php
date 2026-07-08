<?php namespace App\Models;

use CodeIgniter\Model;

class InterestModel extends Model
{
    protected $table = 'interests';
    protected $primaryKey = 'id';
    protected $allowedFields = ['sender_id','receiver_id','message','status','created_at','updated_at'];

    // optional: use timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

