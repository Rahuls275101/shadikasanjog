<?php

namespace App\Models;

use CodeIgniter\Model;

class Answarmodel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        // OR $this->db = db_connect();
    }
    var $table = 'answered';
    var $column_order = array(null, 'answered_user_name'); //set column field database for datatable orderable
    var $column_search = array('answered_user_name'); //set column field database for datatable searchable 
    var $order = array('answered_id' => 'ASC'); // default order 
  
    private function _get_datatables_query()
    {

        $builder = $this->db->table($this->table);  


   

     


        $i = 0;
foreach ($this->column_search as $item) // loop column 
{
    if($_POST['search']['value']) // if datatable send POST for search
    {
        if($i===0) // first loop
        {
            $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
            $builder->like($item, $_POST['search']['value']);
        }
        else
        {
            $builder->or_like($item, $_POST['search']['value']);
        }

        if(count($this->column_search) - 1 == $i) //last loop
            $builder->groupEnd(); //close bracket
    }
    $i++;
}

    if(isset($_POST['order'])) // here order processing
    {
        $builder->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
        $order = $this->order;
        $builder->orderBy(key($order), $order[key($order)]);
    }

       return $builder;
    }    

     function get_datatables()
    {

      

        $query = $this->_get_datatables_query();

        if($_POST['length'] != -1)
        $query->limit($_POST['length'], $_POST['start']);
        $query = $query->get();
        return $query->getResult();
    }
    function count_filtered()
    {
        $query = $this->_get_datatables_query();
        $query = $query->get();
        return $query->getNumRows();
    }
    
    public function count_all()
    {
        $query = $this->db->table($this->table);
        return $query->countAll();
    }

}