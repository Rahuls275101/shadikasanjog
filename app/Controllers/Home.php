<?php

namespace App\Controllers;
use App\Models\Commanmodel;
use App\Models\Blogmodel;
use App\Models\Ajaxlist;
use App\Models\Profilemodel;
use App\Libraries\Cart;
use CodeIgniter\Email\Email;
use CodeIgniter\I18n\Time;
use App\Models\ProfileListModel;
use Config\Services;

class Home extends BaseController
{
    public function index()
    { 
    /*   $cart = new \App\Libraries\Cart();
        
       $datcart = $cart->contents();
       
       print_r($datcart);
       */
        $commanmodel = new Commanmodel();
        $newblog = $commanmodel->all_multiple_query_order_by_limit('blogs',array('blog_status' => 'Active'),'blog_id','ASC',4); 
        
        
          $productfeatured = $commanmodel->all_multiple_query_order_by_limit_with_like('product',array('product_status' => 'Active'),array('product_collections' => '1'),'product_id','DESC',10); 
         
        
         $productsuggest = $commanmodel->all_multiple_query_order_by_limit_with_like('product',array('product_status' => 'Active'),array('product_collections' => '2'),'product_id','DESC',4); 
         $producttopselling= $commanmodel->all_multiple_query_order_by_limit_with_like('product',array('product_status' => 'Active'),array('product_collections' => '3'),'product_id','DESC',10); 
         
         
        
         $data = array(
         
            'search' => '',
           'productfeatured' => $productfeatured,
            'productsuggest' => $productsuggest,
             'producttopselling' => $producttopselling,

            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
             $data['bannerView'] = $commanmodel->get_multiple_query_order_by('home_banner','banner_id','DESC'); 
         return view('frontend/header',$data).view('frontend/index').view('frontend/footer');
    }
    
     public function send()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $post = $this->request->getPost();
        
        $lable = $this->request->getPost('lable');

        // build HTML table of all fields
        $html = "<h3>$lable</h3>";
        $html .= "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse; width:100%;'>";
        foreach ($post as $key => $value) {
            if($key > 0) {
           
            $html .= "<tr><td><strong>" . esc($key) . "</strong></td><td>" . nl2br(esc($value)) . "</td></tr>";
            }
        }
        $html .= "</table>";

        // send mail
        $email = \Config\Services::email();
              $email->setTo("shadikasanjog@gmail.com");
        $email->setFrom("support@shadikasanjog.com", "Shadi ka Sanjog");
        
        $email->setSubject($lable);
        $email->setMailType("html");
        $email->setMessage($html);

        if ($email->send()) {
            return $this->response->setJSON(['status' => 'success']);
        } else {
            log_message('error', $email->printDebugger(['headers']));
            return $this->response->setJSON(['status' => 'error', 'message' => 'Mail not sent.']);
        }
    }
    
    
    
     public function profile()
    { 
  
         $session = session();
        $commanmodel = new Commanmodel();
         $usersession = $session->get('loggedin');
      
  $userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
  
   $religions =$commanmodel->all_multiple_query_order_by('religions',array('status' => 'Active'),'id','ASC');
    $education_qualifications  =$commanmodel->all_multiple_query_order_by('education_qualifications',array(),'sort_order','ASC');
      $profession_categories  =$commanmodel->all_multiple_query_order_by('profession_categories',array(),'sort_order','ASC');
         $data = array(
         
            'search' => '',
          
      'userdata' => $userdata,
       'religions' => $religions,
        'education_qualifications' => $education_qualifications,
         'profession_categories' => $profession_categories,
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/profile',$data).view('frontend/footer');
    }
    
public function profile_details($id)
{ 
    $commanmodel = new Commanmodel();
    $db = \Config\Database::connect();
    $session = session();

    $usersession = $session->get('loggedin');

    // 🔐 Login check
    if (!$usersession) {
        return redirect()->to(base_url('login'));
    }

    // 🔎 Logged in user data
    $userdatalogin = $commanmodel->get_single_query(
        'user_account',
        ['account_id' => $usersession['user_id']]
    );

    // 🔎 Profile user data
    $userdata = $commanmodel->get_single_query(
        'user_account',
        ['user_id' => $id]
    );

    if (!$userdata) {
        return redirect()->back()->with('error', 'Profile not found.');
    }

    // 📌 Basic Lists
    $religions = $commanmodel->all_multiple_query_order_by('religions', [], 'id', 'ASC');

    $caste = $commanmodel->all_multiple_query_order_by(
        'castes',
        ['religion_id' => $userdata->religion_id ?? 0],
        'id',
        'ASC'
    );

    $user_photos = $commanmodel->all_multiple_query_order_by(
        'user_photos',
        ['user_id' => $userdata->account_id],
        'id',
        'ASC'
    );

    // 🎓 Education
    $education_data = $db->table('user_education ue')
        ->select('ue.*, eq.qualification_name')
        ->join('education_qualifications eq', 'eq.id = ue.education_level', 'left')
        ->where('ue.user_id', $userdata->account_id)
        ->orderBy('ue.id', 'ASC')
        ->get()
        ->getResultArray(); 

    // 👨‍👩‍👧‍👦 Family
    $family_members = $commanmodel->all_multiple_query_order_by(
        'user_family_members',
        ['user_id' => $userdata->account_id],
        'id',
        'ASC'
    );

    $family_background = $commanmodel->get_single_query(
        'user_family_background',
        ['user_id'=> $userdata->account_id]
    );


 $partner_preferences = $commanmodel->get_single_query('user_partner_preferences',array('user_id'=> $userdata->account_id));

    $work_experience = $commanmodel->all_multiple_query_order_by(
        'user_work_experience',
        ['user_id' => $userdata->account_id],
        'id',
        'ASC'
    );

    // ==============================
    // 🔹 Get Religion, Caste, Profession Names
    // ==============================

    $religion_name = '';
    $caste_name = '';
    $profession_name = '';

    if (!empty($userdata->religion_id)) {
        $religion = $commanmodel->get_single_query('religions', ['id' => $userdata->religion_id]);
        $religion_name = $religion->name ?? '';
    }

    if (!empty($userdata->caste_id)) {
        $caste_data = $commanmodel->get_single_query('castes', ['id' => $userdata->caste_id]);
        $caste_name = $caste_data->name ?? '';
    }

    if (!empty($userdata->profession)) {
        $profession_data = $commanmodel->get_single_query(
            'profession_categories',
            ['id' => $userdata->profession]
        );
        $profession_name = $profession_data->category_name ?? $userdata->other_profession;
    }

    // ==============================
    // 🔹 Meta Data
    // ==============================

    $meta = $commanmodel->get_single_query('meta', ['meta_id'=> 1]);

    // ==============================
    // 🔹 Final Data Array
    // ==============================

    $data = [
        'search' => '',
        'userdata' => $userdata,
        'membership_status' => $userdatalogin->membership_status ?? 'Inactive',
        'religions' => $religions,
        'castes' => $caste,
        'user_photos' => $user_photos,
        'education_data' => $education_data,
        'family_members' => $family_members,
        'family_background' => $family_background,
        'partner_preferences' => $partner_preferences,
        'work_experience' => $work_experience,
        'religion_name' => $religion_name,
        'caste_name' => $caste_name,
        'profession_name' => $profession_name,
        'searchcategory' => 'all',
        'pageurl' => base_url(),
        'title' => $meta->meta_title ?? '',
        'keyword' => $meta->meta_keyword ?? '',
        'description' => $meta->meta_description ?? '',
        'pageimage' => base_url('assets/meta/'.($meta->meta_image ?? 'default.png')),
    ];

    return view('frontend/header', $data)
        . view('frontend/profile_details')
        . view('frontend/footer');
}
    
     public function plan()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/plan').view('frontend/footer');
    }
    
    
       public function event()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/event').view('frontend/footer');
    }
    
         public function story()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/story').view('frontend/footer');
    }
        public function grievance_redressal()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/grievance_redressal').view('frontend/footer');
    }
    
    
       public function report_abuse()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/report_abuse').view('frontend/footer');
    }
    

    
    
     public function testimonial()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/testimonial').view('frontend/footer');
    }
    
     public function advertisements()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/advertisements').view('frontend/footer');
    }
    
        public function fix_appointment()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/fix_appointment').view('frontend/footer');
    }
    
    
     public function free_webinar()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/free_webinar').view('frontend/footer');
    }
    
    
         public function services()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/services').view('frontend/footer');
    }
    
    
  public function quote()
{
    $commanmodel  = new Commanmodel();
    $emailService = \Config\Services::email();

    // File Upload
    $file    = $this->request->getFile('file');
    $newName = null;
    if ($file && $file->isValid()) {
        $newName = $file->getRandomName();
        $file->move('assets/uploads', $newName);
    }

    // Data
    $data = [
        'name'        => $this->request->getPost('name'),
        'email'       => $this->request->getPost('email'),
        'phone'       => $this->request->getPost('phone'),
        'date'        => $this->request->getPost('date'),
        'description' => $this->request->getPost('description'),
        'create_date' => date('Y-m-d H:i:s'), 
        'file'        => $newName
    ];

    // Insert into DB
    $insertId = $commanmodel->insert_query_get_inserid('quote', $data);

    if ($insertId) {
        // Compose Email
        $emailService->setFrom('info@kapasajobs.com', 'FINE METAL CARDS');
        $emailService->setTo('shadikasanjog@gmail.com');
        $emailService->setSubject($this->request->getVar('subject') ?? 'New Quote Request');
        $emailService->setMailType('html');

        $message = '<html><body>';
        $message .= '<h3>New Quote Request</h3>';
        $message .= '<table border="1" cellpadding="5" cellspacing="0">';
        $message .= '<tr><td><b>Name</b></td><td>'.$this->request->getVar('name').'</td></tr>';
        $message .= '<tr><td><b>Email</b></td><td>'.$this->request->getVar('email').'</td></tr>';
        $message .= '<tr><td><b>Phone</b></td><td>'.$this->request->getVar('phone').'</td></tr>';
        $message .= '<tr><td><b>Date</b></td><td>'.$this->request->getVar('date').'</td></tr>';

        $message .= '<tr><td><b>Description</b></td><td>'.$this->request->getVar('description').'</td></tr>';
        $message .= '</table>';
        $message .= '</body></html>';

        $emailService->setMessage($message);

        // Attach file if uploaded
        if ($newName) {
            $emailService->attach(FCPATH . 'assets/uploads/' . $newName);
        }

        // Send email
        if (! $emailService->send()) {
            log_message('error', 'Quote Email sending failed: ' . print_r($emailService->printDebugger(['headers','subject']), true));
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Quote added successfully!'
        ]);
    } else {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Failed to save quote.'
        ]);
    }
}

            public function faq()
    { 
  
        $commanmodel = new Commanmodel();
      

         $data = array(
         
            'search' => '',
          
   
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
        
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 1));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
            
           
         return view('frontend/header',$data).view('frontend/faq').view('frontend/footer');
    }
    
      public function about_us()
    {
        
         $commanmodel = new Commanmodel();
         $data = array(
           
            'search' => '',
          
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
           
            );
            
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 2));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
         return view('frontend/header',$data).view('frontend/about_us').view('frontend/footer');
    }
    
       public function blog()
    { $commanmodel = new Commanmodel();
        $newblog = $commanmodel->all_multiple_query_order_by_limit('blogs',array('blog_status' => 'Active'),'blog_id','ASC',4); 
         $data = array(
          
            'search' => '',
          'newblog' => $newblog,
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
           
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 4));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
         return view('frontend/header',$data).view('frontend/blog').view('frontend/footer');
    }
    
      public function blog_detail($slug)
    {
        $commanmodel = new Commanmodel();
        $blogs = $commanmodel->get_single_query('blogs',array('url_slug'=> $slug));
     $newblog = $commanmodel->all_multiple_query_order_by_limit('blogs',array('blog_status' => 'Active'),'blog_id','ASC',4); 
         $data = array(
             'blogs' => $blogs, 
            'newblog' => $newblog,
            'title' => $blogs->meta_title." : Rent House", 
            'keyword' =>  $blogs->meta_keyword,
            'description' =>  $blogs->meta_description,
            'search' => '',
          
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            'pageimage' => base_url('assets/frontend/assets/img/logo.png')
            );
         return view('frontend/header',$data).view('frontend/blog_detail').view('frontend/footer');
    } 
    
       public function contact_us()
    {
         $commanmodel = new Commanmodel();
         $data = array(
           
            'search' => '',
          
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
           
            );
            
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 3));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
               
         return view('frontend/header',$data).view('frontend/contact_us').view('frontend/footer');
    }
    
      public function catalog($slug)
    {
        $commanmodel = new Commanmodel();
        
        
        if($slug == 'all') {
              $mainid = '';
                $id = '';
                $catname = '';
        } else {
                $category = $commanmodel->get_single_query('category',array('url_slug'=> $slug));
        
        $mainid = '';
        if($category) {
          
            $parent_id =  $category->parent_id; 
            
            if($parent_id == 0) {
                $mainid =  $category->category_id; 
                 $catname = $category->category_name; 
                $id = '';
            } else {
                 $mainid = '';
                $id = $category->category_id; 
                $catname = $category->category_name; 
            }
            
            
        } else {
            $id = '';
        }
        }
    
        
           $session = session();
        $commanmodel = new Commanmodel();
         $data = array(
          
            'search' => '',
             'collection' => '',
             'mainid' => $mainid,
              'catname' => $catname,
           'id' => $id,
            'url' => $slug,
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            
            );
            
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 5));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
             
         return view('frontend/header',$data).view('frontend/catalog',$data).view('frontend/footer');
    }
    
        public function search()
    {
        $commanmodel = new Commanmodel();
        
           $session = session();
        $commanmodel = new Commanmodel();
        
      $categoryget =  $this->request->getVar('category');
            $category = $commanmodel->get_single_query('category',array('url_slug'=> $categoryget));
        
        
        if($category) {
          
            $parent_id =  $category->parent_id; 
            
            if($parent_id == 0) {
                $mainid =  $category->category_id; 
                $id = '';
            } else {
                 $mainid = '';
                $id = $category->category_id; 
            }
            
            
        } else {
            $id = '';
            $mainid = '';
        }
        
        
        
        
         $data = array(
           
            'search' => $this->request->getVar('search'),
              'mainid' => '',
           'id' => '',
            'url' => 'search',
             'collection' => '',
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
           
            );
            
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 6));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
             
         return view('frontend/header',$data).view('frontend/catalog',$data).view('frontend/footer');
    }
    
      public function collection($slug)
    {
        $commanmodel = new Commanmodel();
        
       $collections = $commanmodel->get_single_query('collections',array('url_slug'=> $slug));
        
           $session = session();
        $commanmodel = new Commanmodel();
         $data = array(
           
            'search' => '',
           'id' => '',
           'mainid' => '',
           'collection' => $collections->collections_id,
            'url' => 'search',
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
          
            );
            
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 5));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
             
         return view('frontend/header',$data).view('frontend/catalog',$data).view('frontend/footer');
    }
    
    
     public function ajax_list($page)
{
    
 $commanmodel = new Commanmodel();
 $mainid=  $this->request->getVar('mainid');
    $id=  $this->request->getVar('id');
 $url=  $this->request->getVar('list');
$search=  $this->request->getVar('search');
$collection=  $this->request->getVar('collection');
$minprice=  $this->request->getVar('minprice');
$maxprice=  $this->request->getVar('maxprice');
$shortby=  $this->request->getVar('shortby');
   
$Travellmodel = new Ajaxlist($id);

 $pager = service('pager');
 
 
   $allid = array_column(
        $commanmodel->all_multiple_query_order_by('category', ['parent_id' => $mainid], 'category_id', 'ASC'),
        'category_id'
    );

$perPage = 12;
$total = $Travellmodel->count_all_frontend($mainid,$id,$search,$collection,$minprice,$maxprice,$shortby,$allid);
$segment = $this->request->uri->getSegment(2);

// Set the base URL and segment for pagination links.
$pager->setPath(base_url($url), $segment);

// Generate pagination links using the makeLinks() method.
$pager_links = $pager->makeLinks($page, $perPage, $total, 'foundation_full');

$start = ($page - 1) * $perPage;

$output = [
    'item_total' => 'Showing '.$total.' total results',
    'pagination_link' => $pager_links,
    'product_list' => $Travellmodel->fetch_data($perPage, $start,$mainid,$id,$search,$collection,$minprice,$maxprice,$shortby,$allid)['output'],
     'headoutput' => $Travellmodel->fetch_data($perPage, $start,$mainid,$id,$search,$collection,$minprice,$maxprice,$shortby,$allid)['headoutput']
];

echo json_encode($output);


}
     public function product_details($slug)
    {
       $session = session();
        $commanmodel = new Commanmodel();
         $usersession = $session->get('loggedin');
         
           $userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
        $product = $commanmodel->get_single_query('product',array('slug'=> $slug));
        
         $data = array(
             'product' => $product, 
             
            'title' => $product->product_meta_title, 
            'keyword' =>  $product->product_meta_keyword,
            'description' =>  $product->product_meta_description,
            'search' => '',
          'membership_status' => $userdata->membership_status,
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            'pageimage' => base_url('assets/frontend/assets/img/logo.png')
            );
         return view('frontend/header',$data).view('frontend/product_details').view('frontend/footer');
    } 
    
    
public function ajax_list_profile($page = 1)
    {
        $Profilemodel = new Profilemodel();
        $pager = service('pager');
        $request = service('request');

        $perPage = 12;
        $start = ($page - 1) * $perPage;

        // Count total
        $total = $Profilemodel->count_all_frontend($request);

        // Get paginated data
        $data = $Profilemodel->fetch_data($perPage, $start, $request);

        // Pagination links
        $pager_links = $pager->makeLinks($page, $perPage, $total, 'foundation_full');

        $output = [
            'item_total' => 'Showing ' . $total . ' total results',
            'pagination_link' => $pager_links,
            'product_list' => $data['output'],
            'headoutput' => $data['headoutput']
        ];

        return $this->response->setJSON($output);
    }

    
    
     public function register()
    {
        $commanmodel = new Commanmodel();
       
        
         $data = array(
             
             
           
            'search' => '',
          
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            
            );
            
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 7));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
         return view('frontend/header',$data).view('frontend/register').view('frontend/footer');
    }
    
       public function login()
    {
        $commanmodel = new Commanmodel();
       
        
         $data = array(
         
             
          
            'search' => '',
          
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            
            );
            
               $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 8));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
               
         return view('frontend/header',$data).view('frontend/login').view('frontend/footer');
    }
    
  public function register_process()
{
    $session = session();
    $commanmodel = new Commanmodel();
    helper(['form', 'url']);
    $validation = \Config\Services::validation();

    if ($this->request->getVar('new_register') == "Newregister") {

        $rules = [
            'name_register' => [
                'label' => 'First Name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter First Name',
                ],
            ],
            'name_last_register' => [
                'label' => 'Last Name',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter Last Name',
                ],
            ],
            'gander_register' => [
                'label' => 'Gender',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select Gender',
                ],
            ],
            'dob_register' => [
                'label' => 'Date of Birth',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter Date of Birth',
                ],
            ],
            'phone_register' => [
                'label' => 'Phone Number',
                'rules' => 'required|min_length[10]|max_length[10]|is_unique[user_account.user_phone]',
                'errors' => [
                    'required' => 'Please enter phone number',
                    'min_length' => 'Phone number must be 10 digits!',
                    'max_length' => 'Phone number must be 10 digits!',
                    'is_unique' => 'This phone number already exists!',
                ],
            ],
            'email_register' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[user_account.user_email]',
                'errors' => [
                    'required' => 'Please enter email',
                    'valid_email' => 'Please enter a valid email address',
                    'is_unique' => 'This email address already exists!',
                ],
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]|max_length[16]|matches[confirm_password]',
                'errors' => [
                    'required' => 'Please enter password',
                    'min_length' => 'Password must be at least 8 characters!',
                    'max_length' => 'Password must not exceed 16 characters!',
                    'matches' => 'Password and confirm password do not match!',
                ],
            ],
            'confirm_password' => [
                'label' => 'Confirm Password',
                'rules' => 'required|min_length[8]|max_length[16]',
                'errors' => [
                    'required' => 'Please enter confirm password',
                    'min_length' => 'Confirm password must be at least 8 characters!',
                    'max_length' => 'Confirm password must not exceed 16 characters!',
                ],
            ],
        ];

        if ($this->validate($rules)) {
            $password = $this->request->getVar('password');
            $email = $this->request->getVar('email_register');
            $name = $this->request->getVar('name_register');
            $lastName = $this->request->getVar('name_last_register');
            $gender = $this->request->getVar('gander_register');
            $dob = $this->request->getVar('dob_register');
            $phone = $this->request->getVar('phone_register');

            // ✅ Generate verification token
            $token = bin2hex(random_bytes(32)); 

            $registrationData = [
                'user_name'       => $name,
                'user_last_name'  => $lastName,
                'user_phone'      => $phone,
                'user_email'      => $email,
                'gender'          => $gender,
                'date_of_birth'   => $dob,
                'user_type'       => 1,
                'user_status'     => 'Inactive', // user inactive until verification
                'verification_token' => $token,
                'user_password'   => password_hash($password, PASSWORD_DEFAULT),
            ];

            $inserted = $commanmodel->insert_query_get_inserid('user_account', $registrationData);

           if ($inserted) {
    // ✅ Get first 3 alphabets of first name (uppercase)
    $prefix = strtoupper(substr($name, 0, 3));

    // ✅ Generate Unique ID (Example: RAH0001)
    $user_unique_id = $prefix . str_pad($inserted, 3, '0', STR_PAD_LEFT);

    // ✅ Update the user_account table with user_unique_id
    $commanmodel->update_query('user_account', [
        'user_id' => $user_unique_id
    ], [
        'account_id' => $inserted
    ]);

    // ✅ Send verification email
    $verificationLink = base_url("verify-email/" . $token);

    $to = $email;
    $subject = 'Verify Your Email Address';
    $from = 'no-reply@shadikasanjog.com';

    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

$htmldata = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email Address</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <p>Dear Member,</p>

    <p>
        Welcome to <strong>Shadi Ka Sanjog</strong> — a trusted platform that connects
        defence families and like-minded individuals with respect, privacy, and purpose.
    </p>

    <p>
        To activate your account and begin your journey with us, please verify your email
        address by clicking the button below:
    </p>

    <p style="margin: 30px 0;">
        <a href="' . $verificationLink . '"
           target="_blank"
           style="
                background-color: #d32f2f;
                color: #ffffff;
                padding: 12px 25px;
                text-decoration: none;
                border-radius: 5px;
                display: inline-block;
                font-weight: bold;
           ">
            Verify My Email
        </a>
    </p>

    <p>
        If the button above doesn’t work, you can also copy and paste the following link
        into your browser:
    </p>

    <p>
        <a href="' . $verificationLink . '" target="_blank">
            ' . $verificationLink . '
        </a>
    </p>

    <p>
        Once verified, you’ll be able to log in, complete your profile and start exploring
        genuine connections on our platform.
    </p>

    <p>
        Your User ID: <strong>' . $user_unique_id . '</strong>
    </p>

    <p>
        If you did not create an account on Shadi Ka Sanjog, please ignore this email —
        no further action is needed.
    </p>

    <br>

    <p>
        Best Regards,<br>
        <strong>Team Shadi Ka Sanjog</strong>
    </p>

</body>
</html>
';


 // send mail
        $email = \Config\Services::email();
              $email->setTo($to);
        $email->setFrom("support@shadikasanjog.com", "Shadi ka Sanjog");
        
        $email->setSubject($subject);
        $email->setMailType("html");
        $email->setMessage($htmldata);

       $email->send();

 

    $array = [
        'success' => true,
        'title'   => 'Registration Successful',
        'class'   => 'success',
        'message' => 'A confirmation email has been sent to your inbox. Please check
your email and click the link to complete your registration. Once
done, you’ll be able to log in.',
        'user_id' => $user_unique_id,
    ];
}
 else {
                $array = [
                    'success' => false,
                    'title'   => 'Error',
                    'class'   => 'danger',
                    'message' => 'Oops! Something went wrong, please try again.'
                ];
            }
        } else {
            $array = [
                'error_user'              => true,
                'name_register_error'     => $validation->getError('name_register'),
                'name_last_register_error'=> $validation->getError('name_last_register'),
                'phone_register_error'    => $validation->getError('phone_register'),
                'email_register_error'    => $validation->getError('email_register'),
                'password_error'          => $validation->getError('password'),
                'confirm_password_error'  => $validation->getError('confirm_password'),
                'gander_register_error'   => $validation->getError('gander_register'),
                'dob_register_error'      => $validation->getError('dob_register'),
            ];
        }
    } else {
        $array = [
            'failed' => '<div class="alert alert-danger">Please fill all mandatory fields (*)</div>'
        ];
    }

    return $this->response->setJSON($array);
}

public function verifyEmail($token)
{
    $db = \Config\Database::connect();
    $builder = $db->table('user_account');

    $query = $builder->getWhere(['verification_token' => $token]);
    $user = $query->getRow();

    $style = "
    <style>
        body {
            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .verify-box {
            background: #fff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.15);
            text-align: center;
            width: 450px;
            max-width: 90%;
        }
        .verify-box h1 {
            font-size: 26px;
            margin-bottom: 10px;
            color: #2193b0;
        }
        .verify-box p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .verify-box .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2193b0;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
        }
    </style>
    ";

    if ($user) {

        if ($user->user_status == 'Active') {

            echo $style . "
            <div class='verify-box'>
                <h1>✅ Account Already Verified</h1>
                <p>Your account is already active. You can log in anytime!</p>
                <a href='" . base_url('login') . "' class='btn'>Go to Login</a>
            </div>";

        } else {

            // Update account status
            $builder->where('verification_token', $token)
                    ->update([
                        'user_status' => 'Active',
                        'verification_token' => null
                    ]);

            /*
            |----------------------------------------
            | Send Welcome Email
            |----------------------------------------
            */

            $to = $user->user_email;
            $subject = "Welcome to Shadi Ka Sanjog!";

        $htmldata = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Shadi Ka Sanjog</title>
</head>
<body style="font-family: Arial, sans-serif; line-height:1.8; color:#333;">

    <p>Dear <strong>' . $user->user_id . '</strong>,</p>

    <p>
        Welcome to the <strong>Shadi Ka Sanjog</strong> family.
        We’re thrilled to have you on board and excited to help you find meaningful connections.
    </p>

    <h3>Getting Started:</h3>

    <p>
        <strong>1. Complete Your Profile:</strong><br>
        To get the most out of Shadi Ka Sanjog, it’s essential to fill out your profile thoroughly.
        Please provide all the requested details, as a complete profile builds trust and increases
        your chances of meaningful matches. Remember, your profile is your first impression.
        Go to <strong>Update Profile</strong> and complete your profile.
    </p>

    <p>
        <strong>2. Verify Your Profile:</strong><br>
        Verification is key. A verified profile not only boosts your credibility but also attracts
        genuine interests. Please complete the verification process as this is the first step for
        award of the <strong>‘Green Batch’</strong> or <strong>‘Orange Batch’</strong>.
        Learn more about them in <strong>About Us</strong> and <strong>FAQ</strong>.
        Once your profile is verified you will be awarded a blue verified badge.
        Details of your documents submitted will not be visible to other members but will be
        safely kept with us.
    </p>

    <p>
        <strong>3. Privacy Controls:</strong><br>
        We value your privacy. Utilize our privacy settings in <strong>Update Profile</strong>
        to control who can view your profile and update your information as needed.
    </p>

    <p>
        <strong>4. Category Badges:</strong><br>
        Seek the <strong>‘Green Batch’</strong> or the <strong>‘Orange Batch’</strong> as per your category.
        Remember <strong>‘Green Batch’</strong> is for the Defence Forces Officers (uniformed soldiers)
        both serving and retired and their wards and the <strong>‘Orange Batch’</strong> for select
        individuals (as decided by our Select Panel) from all walks of life.
    </p>

    <p>
        <strong>5. Need Assistance?</strong><br>
        FAQ will help clarify a number of your queries. Feel free to reach out to us via email or WhatsApp at any time.
    </p>

    <p>
        <strong>Ready to Begin?</strong><br>
        Once your profile is complete and verified, you can manage your subscription plan under the
        <strong>Manage Plan</strong> section. To realise the full potential of the platform you need to
        pay your subscription so as to access our features such as view contact details, chat, send interest
        and advance search. If you have any questions, don’t hesitate to contact us.
    </p>

    <p>
        We wish you the very best in your journey with Shadi Ka Sanjog.
        We look forward to helping you find your perfect match.
    </p>

    <br>

    <p>
        Regards,<br>
        <strong>Team Shadi Ka Sanjog</strong>
    </p>

</body>
</html>';

            $email = \Config\Services::email();

            $email->setTo($to);
            $email->setFrom("support@shadikasanjog.com", "Shadi Ka Sanjog");

            $email->setSubject($subject);
            $email->setMailType("html");
            $email->setMessage($htmldata);

            $email->send();

            echo $style . "
            <div class='verify-box'>
                <h1>🎉 Email Verified Successfully!</h1>
                <p>
                    Thank you for verifying your email.
                    Your account is now active and ready to use.
                </p>
                <a href='" . base_url('login') . "' class='btn'>Login Now</a>
            </div>";
        }

    } else {

        echo $style . "
        <div class='verify-box'>
            <h1>❌ Invalid Link</h1>
            <p>Sorry! This verification link is invalid or expired.</p>
            <a href='" . base_url() . "' class='btn'>Go to Homepage</a>
        </div>";
    }
}




  public function login_process()
{
    $session = session();
    $commanmodel = new Commanmodel();
    helper(['form', 'url']);
    $validation = \Config\Services::validation();

    if ($this->request->getVar('user_login') == "Login") {

        $rules = [
            'login_email' => [
                'label' => 'Email or Phone',
                'rules' => 'required',
                'errors' => ['required' => 'Please enter your email or phone number'],
            ],
            'login_password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]|max_length[16]',
                'errors' => [
                    'required' => 'Please enter password',
                    'min_length' => 'Password must be at least 8 characters',
                    'max_length' => 'Password must not exceed 16 characters',
                ],
            ],
        ];

        if ($this->validate($rules)) {
            $loginValue = $this->request->getVar('login_email');
            $password = $this->request->getVar('login_password');

            // Detect email or phone
            if (is_numeric($loginValue)) {
                $user = $commanmodel->get_single_query('user_account', ['user_phone' => $loginValue]);
            } else {
                $user = $commanmodel->get_single_query('user_account', ['user_email' => $loginValue]);
            }

            if ($user) {
                   if ($user->user_status == 'Inactive') {

        $array = [
            'success' => false,
            'title' => 'Account Pending',
            'class' => 'warning',
            'message' => 'A verification link has already been sent to your registered email
address. Please verify your email and log in.',
        ];

    } elseif (password_verify($password, $user->user_password)) {
                    $sessionData = [
                        'user_id' => $user->account_id,
                        'user_name' => $user->user_name,
                        'user_last_name' => $user->user_last_name,
                        'user_phone' => $user->user_phone,
                        'user_email' => $user->user_email,
                        'user_gender' => $user->gender,
                        'user_dob' => $user->date_of_birth,
                        'user_type' => $user->user_type,
                    ];

                    $session->set('loggedin', $sessionData);

                    $array = [
                        'success' => true,
                        'title' => 'Success',
                        'class' => 'success',
                        'message' => 'Welcome back! You are now logged in.',
                    ];
                } else {
                    $array = [
                        'success' => false,
                        'title' => 'Warning',
                        'class' => 'warning',
                        'message' => 'The email/phone and password do not match.',
                    ];
                }
            } else {
                $array = [
                    'success' => false,
                    'title' => 'Warning',
                    'class' => 'warning',
                    'message' => 'No account found with this email or phone number.',
                ];
            }
        } else {
            $array = [
                'error_user' => true,
                'login_email_error' => $validation->getError('login_email'),
                'login_password_error' => $validation->getError('login_password'),
            ];
        }
    } else {
        $array = [
            'success' => false,
            'title' => 'Warning',
            'class' => 'warning',
            'message' => 'Please fill all mandatory fields.',
        ];
    }

    echo json_encode($array);
}

    
      public function chack_sing_in()
    {
         $session = session();
        if ($session->has('loggedin')) {
            
               
                    
                     $redirectUrl = session()->get('redirect_url');
        
        if ($redirectUrl) {
            $url = $redirectUrl;
        } else {
           $url = base_url('dashboard');
        }
            
               $array = array(
            
         
             'success' => true,
              'url' => $url,
            );
        } else {
                $array = array(
            
         
             'success' => false,
            );
        }
        echo json_encode($array); 
    }
    
        public function logout()
    {
        $session = session();
         $session->remove('loggedin');
          $session->setFlashdata('login_failed', 'Logout successful. You have been successfully logged out. Thank you for using our services. Have a great day!');
             return redirect()->to('/'); 
    }
        public function blog_list($page)
{
    

    
 

   
    $Blogmodel = new Blogmodel();
    
     $pager = service('pager');
    
    $perPage = 10;
    $total = $Blogmodel->count_all_frontend();
    $segment = $this->request->uri->getSegment(2);
    
    // Set the base URL and segment for pagination links.
    $pager->setPath(base_url('blog'), $segment);
   
    // Generate pagination links using the makeLinks() method.
    $pager_links = $pager->makeLinks($page, $perPage, $total, 'foundation_full');

    $start = ($page - 1) * $perPage;
    
    $output = [
        'item_total' =>$total.' tours found',
        'pagination_link' => $pager_links,
        'product_list' => $Blogmodel->fetch_data($perPage, $start)
    ];
    
    echo json_encode($output);


}

public function cart()
    {
         $commanmodel = new Commanmodel();
         $data = array(
           
            'search' => '',
          
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
           
            );
            
            
               $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 9));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
         return view('frontend/header',$data).view('frontend/cart').view('frontend/footer');
    }
    
    public function checkout()
    {
         $commanmodel = new Commanmodel();
         $data = array(
            'title' => "contact us : Rent House", 
            'keyword' => "Home : Rent House",
            'description' => "Home : Rent House",
            'search' => '',
          
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            'pageimage' => base_url('assets/frontend/assets/img/logo.png')
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 10));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
         return view('frontend/header',$data).view('frontend/checkout').view('frontend/footer');
    }
    
    
     public function wishlist()
    {   $session = session();
          $commanmodel = new Commanmodel();
           $usersession = $session->get('loggedin');
                        $userId = $usersession['user_id'];
              
        $wishlist = $commanmodel->all_multiple_query_order_by('wishlist',array('wishlist_user_id' =>$userId),'wishlist_id','ASC'); 
         $data = array(
        
            'search' => '',
             'wishlist' => $wishlist,
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            
            );
            
             $meta = $commanmodel->get_single_query('meta',array('meta_id'=> 11));
            $data['title'] = $meta->meta_title;
             $data['keyword'] = $meta->meta_keyword;
              $data['description'] = $meta->meta_description;
               $data['pageimage'] = base_url('assets/meta/'.$meta->meta_title);
            
         return view('frontend/header',$data).view('frontend/wishlist').view('frontend/footer');
    }
    
    
function enquirysend(){
       $session = session();
  
         $commanmodel = new Commanmodel();
         $request = service('request');
     

 
    $data = array( 
    'enquiry_name' => $this->request->getVar('name'),
    'enquiry_phone' => $this->request->getVar('phone'),
     'enquiry_email' => $this->request->getVar('email'),
      'enquiry_pro_id' => $this->request->getVar('pro_id'),

    'enquiry_message' => $this->request->getVar('message')

    
    
  
        );
    $Inserted=$commanmodel->insert_query('enquiry',$data);
        $response = [
"title" => 'Enquiry Sent',
"class" => 'success',
"message" => 'Your enquiry has been Sent successfully'

];
    
    echo json_encode($response);
  
   }
   
   
   public function pages($sulg)
    {
         $commanmodel = new Commanmodel();
         $pages =$commanmodel->get_single_query('cms_pages',array('cms_slug'=> $sulg));
         $data = array(
            'title' => $pages->cms_page_name, 
            'keyword' => "",
            'description' => "",
            'search' => '',
          'pages' => $pages,
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            'pageimage' => base_url('assets/frontend/assets/img/logo.png')
            );
         return view('frontend/header',$data).view('frontend/pages').view('frontend/footer');
    }
    
 
 public function forgotPassword()
    { $data = array(
            'title' => "contact us : Rent House", 
            'keyword' => "Home : Rent House",
            'description' => "Home : Rent House",
            'search' => '',
          
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            'pageimage' => base_url('assets/frontend/assets/img/logo.png')
            );
      
         return view('frontend/header',$data).view('frontend/forgot_password').view('frontend/footer');
    }

    public function sendResetLink()
    {
        
         $commanmodel = new Commanmodel();
        // Form se email address lene ka process
        $email = $this->request->getPost('email');
        
        // Validate email address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Invalid email address!');
        }
        
       
     
$user = $commanmodel->get_single_query('user_account',array('user_email'=> $email));
        if ($user) {
            // Token generate karein
            $token = bin2hex(random_bytes(50));
            
            // User record mein token aur expiry time save karein
            $expiryTime = new Time('now');
            $expiryTime = $expiryTime->addHours(1); // Token 1 ghante ke liye valid hoga
            
       
$updated=$commanmodel->update_query('user_account',[
                'tokenVerify' => $token,
                'reset_token_expiry' => $expiryTime->toDateTimeString()
            ],array('account_id'=>$user->account_id));
            // Reset link create karein
            $resetLink = site_url('auth/resetPassword/' . $token);

            // Email send karein
            $emailService = \Config\Services::email();
               $emailService->setFrom('support@shadikasanjog.com', ' Shadi ka Sanjog');
   
            $emailService->setTo($email);
            $emailService->setSubject('Password Reset Link');
            $emailService->setMessage('Click on this link to reset your password: ' . $resetLink);
            $emailService->send();

            return redirect()->back()->with('message', 'Password reset link sent to your email.');
        } else {
            return redirect()->back()->with('error', 'No user found with that email.');
        }
    }

    public function resetPassword($token)
    {
          $commanmodel = new Commanmodel();
        $data = array(
            'title' => "contact us : Rent House", 
            'keyword' => "Home : Rent House",
            'description' => "Home : Rent House",
            'search' => '',
          
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            'pageimage' => base_url('assets/frontend/assets/img/logo.png')
            );
        // Token ko validate karein
   

        
        $user = $commanmodel->get_single_query('user_account',array('tokenVerify'=> $token));

        if ($user && new Time('now') < new Time($user->reset_token_expiry)) {
      
             return view('frontend/header',$data).view('frontend/reset_password', ['token' => $token]).view('frontend/footer');
        } else {
            return redirect()->to('/login')->with('error', 'Invalid or expired token.');
        }
    }

    public function updatePassword()
    {
          $commanmodel = new Commanmodel();
        $token = $this->request->getPost('token');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        $user = $commanmodel->get_single_query('user_account',array('tokenVerify'=> $token));

        if ($user && new Time('now') < new Time($user->reset_token_expiry)) {
            // Password update karein
        
            $updated=$commanmodel->update_query('user_account',[
                'user_password' => password_hash($newPassword, PASSWORD_BCRYPT),
                'tokenVerify' => null,
                'reset_token_expiry' => null
            ],array('account_id'=>$user->account_id));

            return redirect()->to('/login')->with('message', 'Password successfully updated.');
        } else {
            return redirect()->to('/login')->with('error', 'Invalid or expired token.');
        }
    }
   
}
