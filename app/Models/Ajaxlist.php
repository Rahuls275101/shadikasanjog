<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Commanmodel;

class Ajaxlist extends Model
{
    protected $table = 'product';
    protected $column_order = array(null); // set column field database for datatable orderable
    protected $column_search = array('product_name'); // set column field database for datatable searchable
    protected $order = array('product_id' => 'DESC'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        // OR $this->db = db_connect();
    }

private function _get_datatables_query($mainid, $id, $search, $collection, $minprice, $maxprice, $shortby, $allid)
{
    $builder = $this->db->table($this->table);
    $builder->where('product_status', 'Active');

    // Filters
    if (!empty($id)) {
        $builder->where('product_category', $id);
    }
    if (!empty($mainid)) {
        $builder->where('product_category', $mainid);
    }
    if (!empty($allid)) {
        $builder->whereIn('product_category', $allid);
    }
    if (!empty($collection)) {
        $builder->like('product_collections', $collection);
    }
    if (!empty($minprice) && !empty($maxprice)) {
        $builder->where('product_price >=', $minprice);
        $builder->where('product_price <=', $maxprice);
    }

    // Search
    if (!empty($search) && is_array($this->column_search)) {
        $builder->groupStart();
        foreach ($this->column_search as $i => $item) {
            if ($i === 0) {
                $builder->like($item, $search);
            } else {
                $builder->orLike($item, $search);
            }
        }
        $builder->groupEnd();
    }

    // Sorting logic
    if ($shortby === 'lowtohigh') {
        $builder->orderBy('product_price', 'ASC');
    } elseif ($shortby === 'hightolow') {
        $builder->orderBy('product_price', 'DESC');
    } elseif ($shortby === 'newness') {
        $builder->orderBy('product_id', 'DESC');
    } else {
        // Default order
        $builder->orderBy('product_id', 'ASC');
    }

    return $builder;
}





    public function count_all_frontend($mainid,$id, $search, $collection, $minprice, $maxprice,$shortby,$allid)
    {
        $query = $this->_get_datatables_query($mainid, $id, $search, $collection, $minprice, $maxprice,$shortby,$allid);
        $query = $query->get();
        return $query->getNumRows();
    }

   public function fetch_data($limit, $start, $mainid, $id, $search, $collection, $minprice, $maxprice, $shortby, $allid)
{
    $commanmodel = new Commanmodel();
    $query = $this->_get_datatables_query($mainid, $id, $search, $collection, $minprice, $maxprice, $shortby, $allid);

    if ($limit != -1) {
        $query->limit($limit, $start);
    }
    $query = $query->get();

    $output = '';
    $headoutput = '';

    if ($query->getNumRows() > 0) {
        foreach ($query->getResult() as $resultsrow) {
 $pro_variant = $commanmodel->all_multiple_query_order_by('pro_variant',array('variant_pro_id' => $resultsrow->product_id),'pro_variant_id','ASC');
            $output .= '   <div class="col-md-6">
                                    <div class="sub-card p-3 rounded shadow-sm">
                                        <h6 class="fw-bold text-dark">'.$resultsrow->product_name.'</h6>
                                       '.$resultsrow->product_overview.'
                                        <a href="'.base_url('/assets/images/'.$resultsrow->product_thumbnail).'" class="btn enquire-btn">
                                         Read More
                                        </a>
                                    </div>
                                </div>

            
            
            
            
            
            ';
        }
    } else {
        $output = '<div class="col-md-12 col-xl-12 mb-3 mb-md-4 pb-1" style="text-align: center;">
                      <h3>No result found!</h3>
                   </div>';
    }

    return array('output' => $output, 'headoutput' => $headoutput);
}

}
