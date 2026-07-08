<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Commanmodel;
class Blogmodel extends Model{




    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        // OR $this->db = db_connect();
    }
    var $table = 'blogs';
    var $column_order = array(null); //set column field database for datatable orderable
    var $column_search = array(); //set column field database for datatable searchable 
    var $order = array('blog_id' => 'DESC'); // default order 



 function _get_datatables_query()
 {
     
     
       $builder = $this->db->table($this->table);  
  

      
     $commanmodel = new Commanmodel();
    
     
     
      $builder->where('type', $_POST['type']);
     
     
      
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

 function count_all_frontend()
 {
  
    $query = $this->_get_datatables_query();
        $query = $query->get();
        return $query->getNumRows();
 }

      
 function fetch_data($limit, $start)
 { $commanmodel = new Commanmodel();
       $query = $this->_get_datatables_query();

        if($limit != -1)
        $query->limit($limit, $start);
        $query = $query->get();
        $query->getResult();
     
     

  $output = '';
  if($query->getNumRows() > 0)
  {
   foreach($query->getResult() as $resultsrow)
   {
       
    $commanmodel = new Commanmodel();
  
 if( $_POST['type'] =='Event') {
     $output .= '           <div class="col-md-4">
                      
                        <div class="event-card shadow-sm rounded-3 p-3 mb-4">

                           
                            <div class="event-date mb-2">
                                <i class="fa fa-calendar me-2"></i>  '.$resultsrow->time.'
                            </div>

                           
                            <h4 class="event-title fw-bold mb-2">
                              '.$resultsrow->blog_name.'
                            </h4>

                            
                            <p class="event-desc">
                              '.$resultsrow->blog_small_description.'
                            </p>

                          
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="'.base_url('blog-detail/').'/'.$resultsrow->url_slug.'" class="btn btn-sm event-btn">
                                Read More
                                </a>
                                <div class="event-author text-end">

                                </div>
                            </div>
                        </div>
                        <!-- UPCOMING EVENT END -->
                    </div>'; 
} else if( $_POST['type'] =='Story') {
     $output .= '              <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="save-im">
                            <div class="inn">
                                <img src="'.base_url('assets/blog/'.$resultsrow->blog_image.'').'" alt="">
                                <div class="desc">

                                    <h4>'.$resultsrow->blog_name.'</h4>
                                    <a href="'.base_url('blog-detail/').'/'.$resultsrow->url_slug.'">Read More.</a>

                                </div>
                            </div>

                        </div>
                    </div>'; 
} else {
     $output .= '      <div class="col-md-4">
                       
                        <div class="blog-home-box">

                            <div class="txt">
                                <span class="blog-date">'.date('D M Y', strtotime($resultsrow->date_time)).'</span>
                                <span class="blog-cate">Wedding</span><span class="blog-cate">Events</span><span
                                    class="blog-cate">Decoration</span>
                                <h2>'.$resultsrow->blog_name.'</h2>
                                <p>'.$resultsrow->blog_small_description.'</p>
                                <a href="'.base_url('blog-detail/').'/'.$resultsrow->url_slug.'">Read More</a></a>
                                <div class="blog-info">
                                    <div class="blog-pro-info">
                                        
                                        <h5>Admin <span>Author</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </div> '; 
}

   
   }
  }
  else
  {
   $output = '<div class="col-md-12 col-xl-12 mb-3 mb-md-4 pb-1" style="text-align: center;"> <h3>No result found!</h3></div>';
  }

  
  return $output;
 }




}