<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel_IOFactory;
use App\Models\Commanmodel;
use App\Models\Answarmodel;
use App\Models\Usermodel;
use App\Models\Franchisemodel;
use App\Models\Questionsmodel;

require_once(APPPATH . "Libraries/config.php");
require_once(APPPATH . "Libraries/razorpay-php/Razorpay.php");

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;


class Admin extends BaseController
{

 
    public function index()
    {
        $session = session();
        
           
        
        
        $commanmodel = new Commanmodel();
        return view('admin/login');
    }

    public function admin_login() {
       
        $session = session();
        $commanmodel = new Commanmodel();
        $rules = [
           
            'email'         => 'required|valid_email',
            'password'      => 'required|min_length[5]|max_length[16]',
            
        ];

        if($this->validate($rules)){

            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');
           // password_hash($password, PASSWORD_DEFAULT);
            $admindetails =$commanmodel->login_valid($email);
            
            if($admindetails){
                $pass = $admindetails->password;
                $authenticatePassword = password_verify($password, $pass);
                if($authenticatePassword){
                    
                     
                    $ses_data = [
                        'id' => $admindetails->id,
                         'referral_code' => $admindetails->referral_code,
                         'name' => $admindetails->name,
                        'email' => $admindetails->email,
                         'image' => $admindetails->admin_image,
                        'name' => $admindetails->name,
                        'position' => $admindetails->position, 
                        'admin_type' => $admindetails->admin_type,
                        
                        'isLoggedIn' => TRUE
                    ];
                    $session->set($ses_data);
                    return redirect()->to('admin/dashboard');
                
                }else{
                    $session->setFlashdata('login_failed', 'Password is incorrect.');
                    return redirect()->to('/admin');
                }
            } else{
          

                $session->setFlashdata('login_failed', 'Invalid Email-Id and Password');
               
                return redirect()->to('/admin');
            }

           
        }else{
          

            $session->setFlashdata('login_failed', 'Invalid Email-Id and Password');
           
            return redirect()->to('/admin');
        }
       
      
       
    }


  public function vender_register()
    {
        $session = session();
        helper('form');
        $commanmodel = new Commanmodel();
         $data = array(
            'title' => "Vender Register : Rent House", 
            'keyword' => "Home : Rent House",
            'description' => "Home : Rent House",
            'search' => '',
           
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            'pageimage' => base_url('assets/frontend/assets/img/logo.png'),
            'validation' => \Config\Services::validation()
            );
        return view('admin/register',$data);
    }



public function vender_register_proccess()
{
    $session = session();
    helper('form');
    $commanmodel = new Commanmodel();

    $type = $this->request->getPost('type');

    // Validation rules
    $rules = [
        'type' => 'required|in_list[Vendor,Franchise,Promoter]',
        'name' => 'required|min_length[3]',
        'email' => 'required|valid_email|is_unique[admin.email]',
        'phone' => 'required|numeric|min_length[10]|max_length[15]',
        'password' => 'required|min_length[6]',
        'confirm_password' => 'required|matches[password]',
    ];

    // Conditionally require 'refer_by' if Promoter is selected
    if ($type === 'Promoter') {
        $rules['refer_by'] = 'required|min_length[4]';
    }

    if ($this->validate($rules)) {
        // Gather form data
        $email = $this->request->getPost('email');
        $name = $this->request->getPost('name');
        $phone = $this->request->getPost('phone');
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $refer_by = $this->request->getPost('refer_by'); // May be null

        $referral_code = $commanmodel->generateReferralCode(); // Assuming this returns a unique code

        // Prepare data for insertion
        $data = [
            'admin_type' => $type,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'refer_by' => $type === 'Promoter' ? $refer_by : '',
            'referral_code' => $referral_code,
            'status' => 'Inactive',
            'status_color' => 'danger',
            
        ];
        
          $insertId = $commanmodel->insert_query_get_inserid('admin', $data);
        
  

        // Insert data into the 'admin' table
    

        if ($insertId) {
            $session->setFlashdata('registration_success', 'Your account has been created successfully. Please wait for approval.');
        } else {
            $session->setFlashdata('registration_failed', 'Registration failed. Please try again.');
        }

        return redirect()->to('/vender_register');
    } else {
        // Validation failed
        $data = [
            'title' => "Vender Register : Rent House",
            'keyword' => "Home : Rent House",
            'description' => "Home : Rent House",
            'search' => '',
            'searchcategory' => 'all',
            'pageurl' => base_url(),
            'pageimage' => base_url('assets/frontend/assets/img/logo.png'),
            'validation' => $this->validator
        ];

        return view('admin/register', $data);
    }
}



 public function vender_response($orderid=NULL)
      {
           $session = session();
    $commanmodel = new Commanmodel();
      $current_order_id = $orderid; 
      $OrderDetail = $commanmodel->order_detail_get_by_id_validate($current_order_id);
      if($OrderDetail)
      {
       
        
        
        		$success = true;

        $error = "Payment Failed";
        
        if (empty($_POST['razorpay_payment_id']) === false)
        {
           $api = new Api('rzp_test_qThjo54DWyFKHa', 'zRAGwRhizipHqH6wDpeSvNCU');
        
            try
            {
                // Please note that the razorpay order ID must
                // come from a trusted source (session here, but
                // could be database or something else)
                $attributes = array(
                    'razorpay_order_id' => $_SESSION['razorpay_order_id'],
                    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                    'razorpay_signature' => $_POST['razorpay_signature']
                );
        
                $api->utility->verifyPaymentSignature($attributes);
            }
            catch(SignatureVerificationError $e)
            {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        
        
        }
                
        
         if ($success === true)
{
                         		
                         	date_default_timezone_set("Asia/Kolkata");
                      
                         		
                                        
                            		    $cofirmrray['order_TXNID'] = $_POST["razorpay_payment_id"];
                            		    $cofirmrray['order_payment_status'] = 'success';
                            		    $cofirmrray['order_TXNDATE'] = date("Y-m-d h:i:s");
                            		    $cofirmrray['order_TXN_signature'] = $_POST['razorpay_signature'];
                            		     $cofirmrray['status'] ='Active';
                            		      $cofirmrray['status_color'] = 'success';
                            		    
                            		    
                                       
                                       $update = $commanmodel->update_query('admin', $cofirmrray, array('orderid' =>$_SESSION['razorpay_order_id'])); 
                                       
                                           $order =$commanmodel->get_single_query('admin',array('orderid' => $current_order_id));
                                       
                                                         $to = $order->email;
$subject = 'Thank You for Your Vender Register .';
$from = 'info@ase-electrical.co.uk';
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 
// Create email headers
$headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
// Compose a simple HTML email message


// Compose the HTML content for the email
$htmldata = "   <p>Dear ".$order->name.",</p>
    <p>Thank you for Vender Registeration</p>
    <p>Thank you again for choosing us!</p>
    <p>Best Regards,</p>
    <p>Team Rent House</p>";


// Path to the email template file
// $template_file = FCPATH . 'assets/frontend/mysendmail.php';


//     $body = file_get_contents($template_file);
    
//     // Replace the placeholders in the template file
//     $body = str_replace("{news}", $htmldata, $body);
    
    
    
// Sending email
mail($to, $subject, $htmldata, $headers);
                                       
                        				
                        					
                        				if($update) {
                        				    $session->setFlashdata('success', 'Registration successful. You can now log in.');
            return redirect()->to('/vender_register');
                        				}
			                         	
	     	
			                         	


	
           
                         		
                         		
                         		
}
else
{
    $html = "<p>Your payment failed</p>
             <p>{$error}</p>";
             	$this->session->set_flashdata('square', 'Your payment failed');
			 
			                            redirect(base_url().'home');
}
        
       
      }
      else
      {
        return redirect('404');
      }
      }
    public function dashboard()
    {
         $session = session();
       $commanmodel = new Commanmodel();
       $table_header = [
                
            ['data' => 'id'],
             ['data' => 'img'],
              ['data' => 'item'],
              ['data' => 'category'],
               ['data' => 'price'],
                ['data' => 'seller'],
                 ['data' => 'payment_type'],
                ['data' => 'shipping_address'],
                 ['data' => 'stauts'],
                  ['data' => 'action'],
          
      
           
        
        ];
        
        $data['table_column'] = json_encode($table_header);
        
         $data['vender'] = $commanmodel->get_single_query_count('admin',array()) - 1;
         $data['user'] = $commanmodel->get_single_query_count('user_account',array());
         $data['product'] = $commanmodel->get_single_query_count('product',array());
          $data['order'] = $commanmodel->get_single_query_count('order_book',array('order_book_status'=>'Success'));
         
     
        return view('admin/head').view('admin/sidebar').view('admin/index',$data).view('admin/footer');
       
    }
    
    
    
    
      public function logout() {
        $session = session();
        $session->destroy();
        
        $session->setFlashdata('login_failed', 'Successfully logged out!');
        return redirect()->to('/admin'); // Adjust the redirect URL as per your application's routes
    }


 public function setting()
    { 
        
        $session = session();
       $commanmodel = new Commanmodel();
        $data['addressView'] = $commanmodel->get_single_query('address',array('id' => 1));  
        
       return view('admin/head').view('admin/sidebar').view('admin/setting',$data).view('admin/footer');
       
    }

public function address_manage_process()
{
    $session = session();
    $commanmodel = new Commanmodel();

    // Check if page is being updated
    if ($this->request->getVar('pageUpdated')) {
        
        // Validate header logo only if a new file is uploaded
        if ($this->request->getFile('header_logo')->isValid()) {
            $validatedheaderlogo = $this->validate([
                'header_logo' => [
                    'label' => 'Image File',
                    'rules' => 'uploaded[header_logo]'
                        . '|is_image[header_logo]'
                        . '|mime_in[header_logo,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                ],
            ]);
            
            if ($validatedheaderlogo) {
                $fileheader = $this->request->getFile('header_logo');
                $header_logo = $fileheader->getRandomName();
                $fileheader->move('assets/img', $header_logo);
            } else {
                $header_logo = $this->request->getVar('edit_header_logo'); // Use existing header logo if no new file
            }
        } else {
            $header_logo = $this->request->getVar('edit_header_logo'); // Use existing header logo if no file uploaded
        }

        // Validate footer logo only if a new file is uploaded
        if ($this->request->getFile('footer_logo')->isValid()) {
            $validatedfooterlogo = $this->validate([
                'footer_logo' => [
                    'label' => 'Image File',
                    'rules' => 'uploaded[footer_logo]'
                        . '|is_image[footer_logo]'
                        . '|mime_in[footer_logo,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                ],
            ]);
            
            if ($validatedfooterlogo) {
                $filefooter = $this->request->getFile('footer_logo');
                $footer_logo = $filefooter->getRandomName();
                $filefooter->move('assets/img', $footer_logo);
            } else {
                $footer_logo = $this->request->getVar('edit_footer_logo'); // Use existing footer logo if no new file
            }
        } else {
            $footer_logo = $this->request->getVar('edit_footer_logo'); // Use existing footer logo if no file uploaded
        }

        // Prepare the data for insertion
        $post_data = [
            'header_logo' => $header_logo,
            'footer_logo' => $footer_logo,
            'web_name' => $this->request->getVar('web_name'),
            'email' => $this->request->getVar('email'),
            'phone_one' => $this->request->getVar('phone_one'),
            'phone_two' => $this->request->getVar('phone_two'),
         
            'address' => $this->request->getVar('address'),
            'address_tow' => $this->request->getVar('address_tow'),
            'copyright' => $this->request->getVar('copyright'),
            'facebook' => $this->request->getVar('facebook'),
            'twitter' => $this->request->getVar('twitter'),
            'linkedin' => $this->request->getVar('linkedin'),
            'instagram' => $this->request->getVar('instagram'),
            
            'franchise_direct_commission' => $this->request->getVar('franchise_direct_commission'),
            'franchise_indirect_commission' => $this->request->getVar('franchise_indirect_commission'),
            'promoter_direct_commission' => $this->request->getVar('promoter_direct_commission'),
            'promoter_indirect_commission' => $this->request->getVar('promoter_indirect_commission'),
        ];

        // Attempt to update the data in the database
        $inserted = $commanmodel->update_query('address', $post_data, ['id' => 1]);

        // Handle success or failure
        if ($inserted) {
            $session->setFlashdata('created', 'This Website Settings has been updated.');
            return redirect()->to(base_url('admin/setting'));
        } else {
            $session->setFlashdata('failed', 'Sorry, this address has not been updated. Please try again.');
            $data['addressView'] = $commanmodel->get_single_query('address', ['id' => 1]);
            return view('admin/head') . view('admin/sidebar') . view('admin/setting', $data) . view('admin/footer');
        }

    } else {
        // Handle if 'pageUpdated' is not set
        $session->setFlashdata('failed', 'Submit process is not working!');
        $data['addressView'] = $commanmodel->get_single_query('address', ['id' => 1]);
        return view('admin/head') . view('admin/sidebar') . view('admin/setting', $data) . view('admin/footer');
    }
}





 public function password_manage_process()
{
    $session = session();
    $commanmodel = new Commanmodel();

    if($this->request->getVar('pageUpdated')) {
        // Handle password update
        if (!empty($this->request->getVar('password'))) {
            // Password validation rules
            $validatedpassword = $this->validate([
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[6]|max_length[20]',
                ],
            ]);

            // Check if password validation is successful
            if ($validatedpassword) {
                // Hash the password
                $password = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
            } else {
                $session->setFlashdata('failed', 'Please enter a password with a minimum length of 6 characters.');
                $data['addressView'] = $commanmodel->get_single_query('address', array('id' => 1));
                return view('admin/head') . view('admin/sidebar') . view('admin/setting', $data) . view('admin/footer');
            }
        }

        // Handle profile image upload
        $validatedImage = $this->validate([
            'profile_images' => [
                'label' => 'Image File',
                'rules' => 'uploaded[profile_images]'
                    . '|is_image[profile_images]'
                    . '|mime_in[profile_images,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
            ],
        ]);

        // If image is uploaded, process it, otherwise use the existing one
        if ($validatedImage) {
            $file = $this->request->getFile('profile_images');
            $profile_images = $file->getRandomName();
            $file->move('assets/vender', $profile_images);
            
            $ses_data['image'] = $profile_images; // example update

            // Set the updated session data
            $session->set($ses_data);
        } else {
            $profile_images = $this->request->getVar('edit_profile_images');
        }

        // Prepare the post data for updating the admin info
        $post_data = [
            'name' => $this->request->getVar('name'),
            'admin_address' => $this->request->getVar('admin_address'),
            'admin_image' => $profile_images,
            'email' => $this->request->getVar('login_email'),
        ];

        // If password was validated, add it to the post data
        if (!empty($password)) {
            $post_data['password'] = $password;
        }

        // Update the admin record
        $updated = $commanmodel->update_query('admin', $post_data, array('id' => $session->id));

        // Check if update was successful
        if ($updated) {
          $session->setFlashdata('created', 'Your profile has been successfully updated.');

            return redirect()->to(base_url('admin/setting'));
        } else {
            $session->setFlashdata('failed', 'Sorry, the username, email, and password have not been updated. Please try again.');
            $data['addressView'] = $commanmodel->get_single_query('address', array('id' => 1));
            return view('admin/head') . view('admin/sidebar') . view('admin/setting', $data) . view('admin/footer');
        }
    } else {
        // If pageUpdated is not set
        $session->setFlashdata('failed', 'Submit process is not working!');
        $data['addressView'] = $commanmodel->get_single_query('address', array('id' => 1));
        return view('admin/head') . view('admin/sidebar') . view('admin/setting', $data) . view('admin/footer');
    }
}


     public function home_banner()
    {    $session = session();
        $commanmodel = new Commanmodel();
        $data['bannerView'] = $commanmodel->get_multiple_query_order_by('home_banner','banner_id','DESC');    

        
        return view('admin/head').view('admin/sidebar').view('admin/banner',$data).view('admin/footer');
     
    }
    
    
    
    
    
    
     public function home_banner_process()
    {
        $session = session();
        $commanmodel = new Commanmodel();
        $data['bannerView'] = $commanmodel->get_multiple_query_order_by('home_banner','banner_id','DESC');
        if($this->request->getVar('upload_banner'))
        {
            if($_FILES['home_banner']['name']!=""){
          
                
                $file = $this->request->getFile('home_banner');

        // Generate a new secure name
        $home_banner = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/images', $home_banner);
            }
            else{
            $session->setFlashdata('failed', 'Please choose banner image!');    
          
            
            return view('admin/head').view('admin/sidebar').view('admin/banner',$data).view('admin/footer');
            }
			 
        $post_data = array(
        'banner_image' => $home_banner,
		'banner_first_title' => $this->request->getVar('first_title'),
			'banner_first_second' => $this->request->getVar('banner_first_second'),
	'banner_date' => $this->request->getVar('date'),
        'redirect_url' => $this->request->getVar('redirect_url')
	
        );
        $inserted = $commanmodel->insert_query('home_banner',$post_data); 
                   if($inserted)
                   {
                    $session->setFlashdata('created', 'This Home Banner has been uploaded successfully.');
                     return redirect()->to('/admin/home_banner');
                   }
                   else
                   {
            $session->setFlashdata('failed', 'Sorry, This Home Banner has not been uploaded.');      
   
        return view('admin/head').view('admin/sidebar').view('admin/banner',$data).view('admin/footer');
                   }


            }
        else
        {
        $session->setFlashdata('failed', 'Submit process is not working!');      
      return view('admin/head').view('admin/sidebar').view('admin/banner',$data).view('admin/footer');
        }  

       
    }


    public function edit_home_banner($banner_id)
    {
        $session = session();
        $commanmodel = new Commanmodel();
        $data['bannerView'] = $commanmodel->get_single_query('home_banner',array('banner_id' => $banner_id));    
    
        return view('admin/head').view('admin/sidebar').view('admin/edit-home-banner',$data).view('admin/footer');
       
    }

    public function edit_home_banner_process($banner_id)
    {
        $session = session();
        $commanmodel = new Commanmodel();
        $data['bannerView'] = $commanmodel->get_single_query('home_banner',array('banner_id' => $banner_id)); 
        if($this->request->getVar('EditBanner'))
        {
            if($_FILES['banner_image']['name']!=""){
                
                $file = $this->request->getFile('banner_image');

        // Generate a new secure name
        $home_banner = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/images', $home_banner);
            }
            else{
              $home_banner = $this->request->getVar('banner_image_old');
            }
        if($this->request->getVar('banner_status')=='Active')
        {
          $banner_status_color = 'success';
        }
        if($this->request->getVar('banner_status')=='Inactive')
        {
          $banner_status_color = 'danger';
        }
        $post_data = array(
     'banner_image' => $home_banner,
		'banner_first_title' => $this->request->getVar('first_title'),
		'banner_first_second' => $this->request->getVar('banner_first_second'),
	'banner_date' => $this->request->getVar('date'),
        'redirect_url' => $this->request->getVar('redirect_url'),
        'banner_status' => $this->request->getVar('banner_status'),
        'banner_status_color' => $banner_status_color 
        );
        $updated = $commanmodel->update_query('home_banner',$post_data,array('banner_id' => $banner_id)); 
        if($updated)
        {
        $session->setFlashdata('created', 'This banner has been updated.');
         return redirect()->to('/admin/home_banner');
        }
        else
        {
        $session->setFlashdata('failed', 'Sorry, This banner has not been uploaded.');     
      return view('admin/head').view('admin/sidebar').view('admin/edit-home-banner',$data).view('admin/footer');
        }
            }
        else
        {
        $session->setFlashdata('failed', 'Submit process is not working!');  
        
     return view('admin/head').view('admin/sidebar').view('admin/edit-home-banner',$data).view('admin/footer');
        }  
       
    }




    public function delete_home_banner($banner_id)
    {
        $session = session();
        $commanmodel = new Commanmodel();
     $deleteClient = $commanmodel->delete_query('home_banner',array('banner_id' =>$banner_id));
     if($deleteClient)
     {
      $session->setFlashdata('created', 'This Home Banner is delete.');
       return redirect()->to('/admin/home_banner');
     }
     else
     {
      $session->setFlashdata('failed', 'This Home Banner is not delete!');
       return redirect()->to('/admin/home_banner'); 
     }
    
    }





public function cms_pages()
    {
       
             $commanmodel = new Commanmodel();
        $data['cmsView'] = $commanmodel->get_multiple_query_order_by('cms_pages','cms_id','ASC');    
       
        
          return view('admin/head').view('admin/sidebar').view('admin/cms-pages',$data).view('admin/footer');
       
    }


    public function edit_cms($cms_id)
    {
          $commanmodel = new Commanmodel();
        $data['cmsView'] = $commanmodel->get_single_query('cms_pages',array('cms_id' => $cms_id));    
        
        return view('admin/head').view('admin/sidebar').view('admin/edit-cms',$data).view('admin/footer');
       
    }




 public function edit_cms_process($cms_id)
    {
        
             $session = session();
        $commanmodel = new Commanmodel();
         if($this->request->getVar('pageUpdated'))
        {
              if($_FILES['product_image']['name']!=""){
         
                
                       $file = $this->request->getFile('product_image');

        // Generate a new secure name
        $image = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/images', $image);
            }
            else{
                  $image=$this->request->getVar('product_image_old');
            }
            
            
    $post_data = array(
        'cms_image' =>  $image,
    'cms_page_heading' =>$this->request->getVar('cms_page_heading'),
    'cms_page_small_description' =>$this->request->getVar('cms_page_small_description'),
    'cms_page_description' =>$this->request->getVar('cms_page_description')
    );
                   $inserted = $commanmodel->update_query('cms_pages',$post_data,array('cms_id' => $cms_id)); 
                   if($inserted)
                   {
                     $session->setFlashdata('created', 'This Page contant has been updated.');
           
                    return redirect()->to('admin/cms_pages');
                   }
                   else
                   {
             $session->setFlashdata('failed', 'Sorry, This blog has not been updated. Please try again?');    
        $data['cmsView'] = $commanmodel->get_single_query('cms_pages',array('cms_id' => $cms_id));    
      return view('admin/head').view('admin/sidebar').view('admin/edit-cms',$data).view('admin/footer');
                   }


                
        }
        else
        {
             $session->setFlashdata('failed', 'Submit process is not working!');    
        $data['cmsView'] = $commanmodel->get_single_query('cms_pages',array('cms_id' => $cms_id));    
       return view('admin/head').view('admin/sidebar').view('admin/edit-cms',$data).view('admin/footer');
        }



       
    }
    
    
private function getnamecategory($id) {
    $commanmodel = new Commanmodel();
    $name = '';
    $currentCategory = $commanmodel->get_single_query('category', ['category_id' => $id]);
    if ($currentCategory) {
        // Recursively fetch the parent category first
        $name .= $this->getnamecategory($currentCategory->parent_id);
        
        // Then append the current category name
        $name .= $currentCategory->category_name . ' >';
    }
    return $name;
}

    
    
 public function category($id=0)
    {$commanmodel = new Commanmodel();
        $session = session();
       if(session()->get('admin_type')=='Supar Admin') {
           
             $data['table_name'] = $this->getnamecategory($id);
             
             
             
              $data['id'] = $id;
                $currentCategory = $commanmodel->get_single_query('category', ['category_id' => $id]);
                
                if($currentCategory) {
                      $data['back'] = $currentCategory->parent_id;
                } else {
                      $data['back'] = '';
                }
    
            $table_header = [
                
            ['data' => 'id'],
            ['data' => 'images'],
            ['data' => 'category'],
            ['data' => 'status'],
            ['data' => 'action'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
       
       return view('admin/head').view('admin/sidebar').view('admin/category',$data).view('admin/footer');
       } else {
            
            return redirect()->back()->withInput();
       }
       
    }
    
    
        public function category_list()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input
$id = $_POST['id']; 

// Define filters based on your requirements
$filters = [];

// Add search filter if a search term is provided
// if (!empty($searchname)) {
// $filters[] = [
//     'column' => 'product_title',
//     'value' => $searchname,
//     'type' => 'like',
// ];
// }


$filters[] = [
    'column' => 'parent_id',
    'value' => $id,
    'type' => 'where',
];



// if (session()->get('admin_type')=='Admin') { 
//     $filters[] = [
//     'column' => 'product_create_by',
//     'value' => session()->get('id'),
//     'type' => 'where',
// ];
// }

// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'category_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('category', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {

$name = '<a  href="'.base_url('admin/category/'.$alldata_view->category_id).'">'.$alldata_view->category_name.'</a>';

$images = '<img class="cat-thumb" src="'.base_url().'/assets/category/'.$alldata_view->category_image.'" >'; 



$action = '<div class="btn-group">
															<button type="button"
																class="btn btn-outline-success">Info</button>
															<button type="button"
																class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
																data-bs-toggle="dropdown" aria-haspopup="true"
																aria-expanded="false" data-display="static">
																<span class="sr-only">Info</span>
															</button>
															<div class="dropdown-menu">
																<a class="dropdown-item editRecordCategory" href="javascript:void(0);" data-menu_order="'.$alldata_view->menu_order.'" data-parent_id="'.$alldata_view->parent_id.'" data-category_id="'.$alldata_view->category_id.'" data-category_name="'.$alldata_view->category_name.'" data-description="'.$alldata_view->description.'" data-category_status="'.$alldata_view->category_status.'" data-category_image="'.$alldata_view->category_image.'" data-category_title="'.$alldata_view->metaTitle.'" data-category_keyword="'.$alldata_view->metaKeyword.'" data-category_description="'.$alldata_view->metaDescription.'">Edit</a>
																<a class="dropdown-item" href="#">Delete</a>
															</div>
														</div>';


        $status = '<span class="badge badge-success">'.$alldata_view->category_status.'</span>';
      
      

$data[] = [
    "id" => $alldata_view->category_id,
    "images" => $images,
    "category" => $name,
    
    "status" => $status,
    "action" => $action 
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}
    
    
    
        function category_save(){
           $session = session();
      
             $commanmodel = new Commanmodel();
         $status = $this->request->getVar('category_status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         }
         
        
         
         
               $validated = $this->validate([
            'category_image' => [
                'label' => 'Image File',
                'rules' => 'uploaded[category_image]'
                    . '|is_image[category_image]'
                    . '|mime_in[category_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                  
            ],
        ]);
 
       
  
        if ($validated) {
             $file = $this->request->getFile('category_image');

        // Generate a new secure name
        $category_image = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/category', $category_image);
        } else {
             $category_image = '';
        }
         
         
      
         

        $title = strip_tags($this->request->getVar('category_name'));
        $titleURL = strtolower(url_title($title));
        
   

        $data = array( 
        'parent_id' => $parentId = $this->request->getVar('parent_id') ? $this->request->getVar('parent_id') : 0, 
       
        'category_name' => $this->request->getVar('category_name'), 
            'description' => $this->request->getVar('description'), 
         'menu_order' => $this->request->getVar('category_order'),
        'category_status' => $this->request->getVar('category_status'), 
        'category_status_color' => $status_color,
        'category_image' => $category_image,
        'url_slug' => $titleURL,
        'metaTitle' => $this->request->getVar('category_title'),
        'metaKeyword' => $this->request->getVar('category_keyword'),
        'metaDescription' => $this->request->getVar('category_description')
            );
        $Inserted=$commanmodel->insert_query('category',$data);
        echo json_encode($Inserted);
     
    }
        function category_update(){
             $session = session();
       
             helper(['form', 'url']);
               $commanmodel = new Commanmodel();
         $status = $this->request->getVar('edit_category_status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         } 
         
         
              $validated = $this->validate([
            'edit_category_image' => [
                'label' => 'Image File',
                'rules' => 'uploaded[edit_category_image]'
                    . '|is_image[edit_category_image]'
                    . '|mime_in[edit_category_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
               
            ],
        ]);
        
 
       
  
        if ($validated) {
             $file = $this->request->getFile('edit_category_image');

        // Generate a new secure name
        $category_image = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/category', $category_image);
        } else {
             $category_image = $this->request->getVar('edit_category_image_old');
        }

        $title = strip_tags($this->request->getVar('edit_category_name'));
        $titleURL = strtolower(url_title($title));
        if($commanmodel->get_url_slug_update('category',$titleURL,array('category_id'=> $this->request->getVar('edit_category_id')))){
        $titleURL = $titleURL.'-'.time(); 
        }
        $data = array(  
              'parent_id' => $parentId = $this->request->getVar('parent_id') ? $this->request->getVar('parent_id') : 0, 
        'category_name' => $this->request->getVar('edit_category_name'), 
          'description' => $this->request->getVar('edit_description'), 
        'menu_order' => $this->request->getVar('edit_category_order'), 
        'category_status' => $this->request->getVar('edit_category_status'), 
        'category_status_color' => $status_color,
        'category_image' => $category_image,
        'url_slug' => $titleURL,
        'metaTitle' => $this->request->getVar('edit_category_title'),
        'metaKeyword' => $this->request->getVar('edit_category_keyword'),
        'metaDescription' => $this->request->getVar('edit_category_description') 
        );
        $where = array(             
        'category_id' => $this->request->getVar('edit_category_id')
            );
        $updated=$commanmodel->update_query('category',$data,$where);
        echo json_encode($updated);
     
    }
    
 
  
   public function bulk_product_upload()
    {
        $commanmodel = new Commanmodel();
        $session = session();
         $data['table_name'] = '';
         
         return view('admin/head').view('admin/sidebar').view('admin/bulk_product_upload',$data).view('admin/footer');
    }
    
    
    
   public function uploadCSV()
{
    $commanmodel = new Commanmodel();
    $session = session();

    helper(['form', 'url']);

    // Define the file validation rules
    $validation = \Config\Services::validation();
    $validation->setRules([
        'file' => 'uploaded[file]|max_size[file,10240]|ext_in[file,csv]',
    ]);

    // Check if file passes validation
    if (!$this->validate($validation->getRules())) {
        return redirect()->to('/bulk-upload')->with('errors', $validation->getErrors());
    }

    // Get the uploaded file
    $file = $this->request->getFile('file');

    // Process the CSV file
    if ($file->isValid() && !$file->hasMoved()) {
   
$csvData = array_slice(array_map('str_getcsv', file($file->getTempName())), 1);

 $insertid = 0;
        // Loop through the CSV data and insert into the database
        foreach ($csvData as $row) {
           
            
            $price = '';
             $qty = '';
              $img = '';

            if (!empty($row[1])) { // Assuming row[1] is product name, adjust accordingly
                // Prepare product data
                $postData = [
                    'product_name' => $row[1],  
                    'product_category' => $row[5],
                    'product_brand' => $row[7],
                    'product_collections' => $row[12],
                    'slug' => $row[0],
                    'product_thumbnail' => $row[13],
                    'product_max_price' => $row[8],
                    'product_price' => $row[21],
                    'inclusive_gst' => $row[9],
                    'gst' => $row[11],
                    'sku' => $row[10],
                    'product_overview' => $row[2],
                    'product_description' => $row[3],
                    'additional_information' => $row[4],
                    'product_meta_title' => $row[24],
                    'product_meta_keyword' => $row[25],
                    'product_meta_description' => $row[26],
                    'product_status' => $row[14], 
                    'product_status_color' => ($row[14] == 'Active') ? 'success' : 'danger',
                    'product_create_by' => $row[6],
                    'product_date' => date('Y-m-d')
                ];

                // Insert product data into the 'product' table
                $insertid = $commanmodel->insert_query_get_inserid('product', $postData);

                // Insert group data into the 'pro_group' table
               if(!empty($row[15])) { $group_data1 = ['pro_group_pro_id' => $insertid, 'pro_group_name' => $row[15]];
                $groupinserted1 = $commanmodel->insert_query_get_inserid('pro_group', $group_data1); }

                if(!empty($row[17])) { $group_data2 = ['pro_group_pro_id' => $insertid, 'pro_group_name' => $row[17]];
                $groupinserted2 = $commanmodel->insert_query_get_inserid('pro_group', $group_data2); }

               if(!empty($row[19])) {  $group_data3 = ['pro_group_pro_id' => $insertid, 'pro_group_name' => $row[19]];
                $groupinserted3 = $commanmodel->insert_query_get_inserid('pro_group', $group_data3); }

              
            }
            
            
              // Insert item data into the 'pro_item' table for each group
               if(!empty($row[16])) {  $item_data1 = ['pro_item_group_id' => $groupinserted1, 'pro_item_name' => $row[16]];
                $commanmodel->insert_query_get_inserid('pro_item', $item_data1);  }

              if(!empty($row[18])) {  $item_data2 = ['pro_item_group_id' => $groupinserted2, 'pro_item_name' => $row[18]];
                $commanmodel->insert_query_get_inserid('pro_item', $item_data2); }

               if(!empty($row[20])) {  $item_data3 = ['pro_item_group_id' => $groupinserted3, 'pro_item_name' => $row[20]];
                $commanmodel->insert_query_get_inserid('pro_item', $item_data3);  }

              // Check if row[16], row[18], or row[20] are empty, and create the variant string accordingly
            $varian = trim($row[16] . (empty($row[18]) ? '' : '-' . $row[18]) . (empty($row[20]) ? '' : '-' . $row[20]));

            $price = $row[21];
             $qty = $row[22];
              $img = $row[23];
                // Insert variant data into the 'pro_variant' table
                $variant_data = [
                    'variant_pro_id' => $insertid,
                    'varian' => $varian, 
                    'pro_variant_price' => $price, 
                    'pro_variant_available' => $qty, 
                    'pro_variant_image' => $img,
                ];
                $commanmodel->insert_query_get_inserid('pro_variant', $variant_data);  
        }

        return redirect()->to('/admin/bulk_product_upload')->with('success', 'CSV file successfully uploaded!');
    } else {
        return redirect()->to('/admin/bulk_product_upload')->with('error', 'There was an issue with the file upload.');
    }
}

    
  
 public function files()
    {$commanmodel = new Commanmodel();
        $session = session();
       if(session()->get('admin_type')=='Supar Admin') {
           
             $data['table_name'] = '';
             
             
    
    
           $table_header = [
    [
        "data" => "checkbox", // This will be the checkbox column for selecting rows
        "orderable" => false,  // Disable sorting for the checkbox column
        "searchable" => false, // Disable searching for the checkbox column
       
    ],
    ['data' => 'id'],
    ['data' => 'images'],
    ['data' => 'files_images'],
    ['data' => 'date_added'],
    ['data' => 'size'],
    ['data' => 'references']
];


        $data['table_column'] = json_encode($table_header);
       
       return view('admin/head').view('admin/sidebar').view('admin/files',$data).view('admin/footer');
       } else {
            
            return redirect()->back()->withInput();
       }
       
    }
public function files_list()
{
    // Request parameter (asc or desc)
    $order = $_POST['order']; 
    $draw = $_POST['draw'];
    $start = $_POST['start']; // Start index
    $length = $_POST['length']; // Number of records per page
    
    $folderPath = 'assets/images'; // Folder path
    $files = scandir($folderPath); // Get all files in the folder

    // Filter out '.' and '..'
    $fileList = array_diff($files, array('.', '..'));

    // Sort files by last modified date using filemtime
    usort($fileList, function($file1, $file2) use ($folderPath, $order) {
        $file1Date = filemtime($folderPath . DIRECTORY_SEPARATOR . $file1);
        $file2Date = filemtime($folderPath . DIRECTORY_SEPARATOR . $file2);

        // Sort based on order (ascending or descending)
        if ($order === 'asc') {
            return $file1Date - $file2Date; // Ascending order
        } else {
            return $file2Date - $file1Date; // Descending order
        }
    });

    // Paginate the files (slice array based on start and length)
    $paginatedFiles = array_slice($fileList, $start, $length);

    // Initialize the data array for response
    $data = [];
    $sn = $start + 1; // Serial number starting from 'start'

    // Loop through the paginated files and add their details
    foreach ($paginatedFiles as $file) {
        $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
        
        // Generate image HTML for displaying image preview
        $images = '<img class="cat-thumb" src="' . base_url($filePath) . '" >'; 
        
        $copy = '<button class="btn btn-border btn-sm copy-btn" data-link="'.$file.'"><i class="mdi mdi-content-copy"></i> </button>';
        
        // Add file details to data array
       $data[] = [
    'checkbox' => '<input type="checkbox" class="selectRow" value="' . htmlspecialchars($filePath) . '">', // Ensure file path is safe
    'id' => $sn, // Serial number or file ID (You can replace with actual ID if needed)
    'images' => '<img src="' . base_url($filePath) . '" class="cat-thumb">'.' '.$copy, // Image HTML with base_url() for correct URL
    'files_images' => pathinfo($file, PATHINFO_FILENAME), // Full file path (if you need it in the response)
    'date_added' => date('Y-m-d H:i:s', filemtime($filePath)), // File modification date
    'size' => filesize($filePath), // File size
    'references' => '' // Placeholder for references, you can populate this if needed
];
        $sn++; // Increment serial number
    }

    // Prepare response
    $response = [
        'draw' => intval($draw),
        'recordsTotal' => count($fileList), // Total number of records (before pagination)
        'recordsFiltered' => count($fileList), // Total number of filtered records (same as total in this case)
        'data' => $data // File data
    ];

    // Return response in JSON format
    echo json_encode($response);
}

public function upload_image() {
    $validated = $this->validate([
        'file' => [
            'label' => 'Image File',
            'rules' => 'uploaded[file]'
                . '|is_image[file]'
                . '|mime_in[file,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
        ],
    ]);

    if ($validated) {
        $file = $this->request->getFile('file');

        // Get the original name of the file
        $reviews_image = $file->getName();

        // Replace spaces with underscores
        $brand_image = str_replace(' ', '_', $brand_image);

        // Define the target directory
        $targetDirectory = 'assets/images/';

        // Check if a file with the same name exists
        if (file_exists($targetDirectory . $brand_image)) {
            // Generate a unique name by appending a timestamp
            $fileInfo = pathinfo($brand_image);
            $brand_image = $fileInfo['filename'] . '_' . time() . '.' . $fileInfo['extension'];
        }

        // Move the file to the directory with the unique or updated name
        $file->move($targetDirectory, $brand_image);
        echo $brand_image; // Output the uploaded file name
    } else {
        $brand_image = '';
    }
}



   

public function delete_files() {
    // Get the selected file paths from the POST request
    $filePaths = $this->request->getVar('ids');
    
    if (empty($filePaths)) {
        // If no files are selected, return an error message
        return $this->response->setJSON(['status' => 'error', 'message' => 'No files selected']);
    }

    // Folder path where the files are stored
     $folderPath = FCPATH . 'assets/images/'; // Use WRITEPATH to ensure safe folder location

    $deletedFiles = 0;

    // Loop through each file path and attempt to delete the file
    foreach ($filePaths as $filePath) {
        // Sanitize and ensure the file name is safe (avoid directory traversal)
        $fileName = basename($filePath);

        // Ensure the file exists before trying to delete it
        $fullPath = $folderPath . $fileName;

        if (file_exists($fullPath)) {
             if (unlink($fullPath)) {
                $deletedFiles++; // Increment if the file is successfully deleted
            }
           
        }
    }

    // Send back a response
    if ($deletedFiles > 0) {
        echo json_encode(['status' => 'success', 'message' => "$deletedFiles files deleted successfully"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $fullPath]);
    }
}



    
     public function brand()
    {$commanmodel = new Commanmodel();
        $session = session();
       if(session()->get('admin_type')=='Supar Admin') {
           
             $data['table_name'] = '';
             
             
         
    
            $table_header = [
                
            ['data' => 'id'],
            ['data' => 'images'],
            ['data' => 'brand'],
           
            ['data' => 'action'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
       
       return view('admin/head').view('admin/sidebar').view('admin/brand',$data).view('admin/footer');
       } else {
            
            return redirect()->back()->withInput();
       }
       
    } 
        public function brand_list()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input


// Define filters based on your requirements
$filters = [];

// Add search filter if a search term is provided
// if (!empty($searchname)) {
// $filters[] = [
//     'column' => 'product_title',
//     'value' => $searchname,
//     'type' => 'like',
// ];
// }






// if (session()->get('admin_type')=='Admin') { 
//     $filters[] = [
//     'column' => 'product_create_by',
//     'value' => session()->get('id'),
//     'type' => 'where',
// ];
// }

// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'brand_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('brand', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {

$name = '<a  href="'.base_url('admin/brand/'.$alldata_view->brand_id).'">'.$alldata_view->brand_name.'</a>';

$images = '<img class="cat-thumb" src="'.base_url().'/assets/brand/'.$alldata_view->brand_image.'" >'; 



$action = '<div class="btn-group">
															<button type="button"
																class="btn btn-outline-'.$alldata_view->brand_status_color.'">'.$alldata_view->brand_status.'</button>
															<button type="button"
																class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
																data-bs-toggle="dropdown" aria-haspopup="true"
																aria-expanded="false" data-display="static">
																<span class="sr-only">Info</span>
															</button>
															<div class="dropdown-menu">
																<a class="dropdown-item editRecordbrand" href="javascript:void(0);"  data-brand_id="'.$alldata_view->brand_id.'" data-brand_image="'.$alldata_view->brand_image.'" data-brand_name="'.$alldata_view->brand_name.'" data-brand_status="'.$alldata_view->brand_status.'" >Edit</a>
																<a class="dropdown-item" href="#">Delete</a>
															</div>
														</div>';


      

$data[] = [
    "id" => $sn,
    "images" => $images,
    "brand" => $name,
    
   
    "action" => $action 
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}
    
    
    
        function brand_save(){
           $session = session();
      
             $commanmodel = new Commanmodel();
         $status = $this->request->getVar('brand_status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         }
         
        
         
         
               $validated = $this->validate([
            'brand_image' => [
                'label' => 'Image File',
                'rules' => 'uploaded[brand_image]'
                    . '|is_image[brand_image]'
                    . '|mime_in[brand_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                    
            ],
        ]);
 
       
  
        if ($validated) {
             $file = $this->request->getFile('brand_image');

        // Generate a new secure name
        $brand_image = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/brand', $brand_image);
        } else {
             $brand_image = '';
        }
         
         
      
         

        $title = strip_tags($this->request->getVar('brand_name'));
        $titleURL = strtolower(url_title($title));
        
   

        $data = array( 
        
        'brand_name' => $this->request->getVar('brand_name'), 
         
        'brand_status' => $this->request->getVar('brand_status'), 
        'brand_status_color' => $status_color,
        'brand_image' => $brand_image,
        'url_slug' => $titleURL,
       
            );
        $Inserted=$commanmodel->insert_query('brand',$data);
        echo json_encode($Inserted);
     
    }
        function brand_update(){
             $session = session();
       
             helper(['form', 'url']);
               $commanmodel = new Commanmodel();
         $status = $this->request->getVar('edit_brand_status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         } 
         
         
              $validated = $this->validate([
            'edit_brand_image' => [
                'label' => 'Image File',
                'rules' => 'uploaded[edit_brand_image]'
                    . '|is_image[edit_brand_image]'
                    . '|mime_in[edit_brand_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                   
            ],
        ]);
 
       
  
        if ($validated) {
             $file = $this->request->getFile('edit_brand_image');

        // Generate a new secure name
        $brand_image = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/brand', $brand_image);
        } else {
             $brand_image = $this->request->getVar('edit_brand_image_old');
        }

        $title = strip_tags($this->request->getVar('edit_brand_name'));
        $titleURL = strtolower(url_title($title));
        if($commanmodel->get_url_slug_update('brand',$titleURL,array('brand_id'=> $this->request->getVar('edit_brand_id')))){
        $titleURL = $titleURL.'-'.time(); 
        }
        $data = array(  
             
        'brand_name' => $this->request->getVar('edit_brand_name'), 
      
        'brand_status' => $this->request->getVar('edit_brand_status'), 
        'brand_status_color' => $status_color,
        'brand_image' => $brand_image,
        'url_slug' => $titleURL,
       
        );
        $where = array(             
        'brand_id' => $this->request->getVar('edit_brand_id')
            );
        $updated=$commanmodel->update_query('brand',$data,$where);
        echo json_encode($updated);
     
    }
    
    
    
    
      public function collections()
    {$commanmodel = new Commanmodel();
        $session = session();
       if(session()->get('admin_type')=='Supar Admin') {
           
             $data['table_name'] = '';
             
             
         
    
            $table_header = [
                
            ['data' => 'id'],
        
            ['data' => 'collections'],
           
            ['data' => 'action'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
       
       return view('admin/head').view('admin/sidebar').view('admin/collections',$data).view('admin/footer');
       } else {
            
            return redirect()->back()->withInput();
       }
       
    } 
        public function collections_list()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input


// Define filters based on your requirements
$filters = [];

// Add search filter if a search term is provided
// if (!empty($searchname)) {
// $filters[] = [
//     'column' => 'product_title',
//     'value' => $searchname,
//     'type' => 'like',
// ];
// }






// if (session()->get('admin_type')=='Admin') { 
//     $filters[] = [
//     'column' => 'product_create_by',
//     'value' => session()->get('id'),
//     'type' => 'where',
// ];
// }

// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'collections_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('collections', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {

$name = '<a  href="'.base_url('admin/collections/'.$alldata_view->collections_id).'">'.$alldata_view->collections_name.'</a>';

//$images = '<img class="cat-thumb" src="'.base_url().'/assets/collections/'.$alldata_view->collections_image.'" >'; 



$action = '<div class="btn-group">
															<button type="button"
																class="btn btn-outline-'.$alldata_view->collections_status_color.'">'.$alldata_view->collections_status.'</button>
															<button type="button"
																class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
																data-bs-toggle="dropdown" aria-haspopup="true"
																aria-expanded="false" data-display="static">
																<span class="sr-only">Info</span>
															</button>
															<div class="dropdown-menu">
																<a class="dropdown-item editRecordcollections" href="javascript:void(0);"  data-collections_id="'.$alldata_view->collections_id.'" data-collections_name="'.$alldata_view->collections_name.'" data-collections_status="'.$alldata_view->collections_status.'" >Edit</a>
																<a class="dropdown-item" href="#">Delete</a>
															</div>
														</div>';


      

$data[] = [
    "id" => $sn,
   
    "collections" => $name,
    
   
    "action" => $action 
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}
    
    
    
        function collections_save(){
           $session = session();
      
             $commanmodel = new Commanmodel();
         $status = $this->request->getVar('collections_status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         }
         
        
        
      
         

        $title = strip_tags($this->request->getVar('collections_name'));
        $titleURL = strtolower(url_title($title));
        
   

        $data = array( 
        
        'collections_name' => $this->request->getVar('collections_name'), 
         
        'collections_status' => $this->request->getVar('collections_status'), 
        'collections_status_color' => $status_color,
      
        'url_slug' => $titleURL,
       
            );
        $Inserted=$commanmodel->insert_query('collections',$data);
        echo json_encode($Inserted);
     
    }
        function collections_update(){
             $session = session();
       
             helper(['form', 'url']);
               $commanmodel = new Commanmodel();
         $status = $this->request->getVar('edit_collections_status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         } 
         
         
             

        $title = strip_tags($this->request->getVar('edit_collections_name'));
        $titleURL = strtolower(url_title($title));
        if($commanmodel->get_url_slug_update('collections',$titleURL,array('collections_id'=> $this->request->getVar('edit_collections_id')))){
        $titleURL = $titleURL.'-'.time(); 
        }
        $data = array(  
             
        'collections_name' => $this->request->getVar('edit_collections_name'), 
      
        'collections_status' => $this->request->getVar('edit_collections_status'), 
        'collections_status_color' => $status_color,
        
        'url_slug' => $titleURL,
       
        );
        $where = array(             
        'collections_id' => $this->request->getVar('edit_collections_id')
            );
        $updated=$commanmodel->update_query('collections',$data,$where);
        echo json_encode($updated);
     
    }
    
    
    public function meta()
    {$commanmodel = new Commanmodel();
        $session = session();
       if(session()->get('admin_type')=='Supar Admin') {
           
             $data['table_name'] = '';
             
             
         
    
            $table_header = [
                
            ['data' => 'id'],
             ['data' => 'page'],
            ['data' => 'images'],
            ['data' => 'meta'],
           
            ['data' => 'action'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
       
       return view('admin/head').view('admin/sidebar').view('admin/meta',$data).view('admin/footer');
       } else {
            
            return redirect()->back()->withInput();
       }
       
    }
    
    
        public function meta_list()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input


// Define filters based on your requirements
$filters = [];

// Add search filter if a search term is provided
// if (!empty($searchname)) {
// $filters[] = [
//     'column' => 'product_title',
//     'value' => $searchname,
//     'type' => 'like',
// ];
// }






// if (session()->get('admin_type')=='Admin') { 
//     $filters[] = [
//     'column' => 'product_create_by',
//     'value' => session()->get('id'),
//     'type' => 'where',
// ];
// }

// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'meta_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('meta', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {

$name = $alldata_view->meta_title;

$images = '<img class="cat-thumb" src="'.base_url().'/assets/meta/'.$alldata_view->meta_image.'" >'; 



$action = '<div class="btn-group">
			<button type="button"
				class="btn btn-outline-success"></button>
			<button type="button"
				class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
				data-bs-toggle="dropdown" aria-haspopup="true"
				aria-expanded="false" data-display="static">
				<span class="sr-only">Info</span>
			</button>
			<div class="dropdown-menu">
				<a class="dropdown-item editRecordmeta" href="javascript:void(0);"  data-meta_id="'.$alldata_view->meta_id.'" data-meta_title="'.$alldata_view->meta_title.'"   data-meta_keyword="'.$alldata_view->meta_keyword.'" data-meta_description="'.$alldata_view->meta_description.'" data-meta_image="'.$alldata_view->meta_image.'" >Edit</a>
				
			</div>
		</div>';


      

$data[] = [
    "id" => $sn,
     "page" => $alldata_view->meta_page,
    "images" => $images,
    "meta" => $name,
    
   
    "action" => $action 
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}
    
    
    
        function meta_save(){
           $session = session();
      
             $commanmodel = new Commanmodel();
         $status = $this->request->getVar('meta_status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         }
         
        
         
         
               $validated = $this->validate([
            'meta_image' => [
                'label' => 'Image File',
                'rules' => 'uploaded[meta_image]'
                    . '|is_image[meta_image]'
                    . '|mime_in[meta_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                    
            ],
        ]);
 
       
  
        if ($validated) {
             $file = $this->request->getFile('meta_image');

        // Generate a new secure name
        $meta_image = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/meta', $meta_image);
        } else {
             $meta_image = '';
        }
         
         
      
         

        $title = strip_tags($this->request->getVar('meta_name'));
        $titleURL = strtolower(url_title($title));
        
   

        $data = array( 
        
        'meta_name' => $this->request->getVar('meta_name'), 
         
        'meta_status' => $this->request->getVar('meta_status'), 
        'meta_status_color' => $status_color,
        'meta_image' => $meta_image,
        'url_slug' => $titleURL,
       
            );
        $Inserted=$commanmodel->insert_query('meta',$data);
        echo json_encode($Inserted);
     
    }
        function meta_update(){
             $session = session();
       
             helper(['form', 'url']);
               $commanmodel = new Commanmodel();
        
         
         
              $validated = $this->validate([
            'edit_meta_image' => [
                'label' => 'Image File',
                'rules' => 'uploaded[edit_meta_image]'
                    . '|is_image[edit_meta_image]'
                    . '|mime_in[edit_meta_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                   
            ],
        ]);
 
       
  
        if ($validated) {
             $file = $this->request->getFile('edit_meta_image');

        // Generate a new secure name
        $meta_image = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/meta', $meta_image);
        } else {
             $meta_image = $this->request->getVar('edit_meta_image_old');
        }

        
        $data = array(  
             
       
        'meta_title' => $this->request->getVar('meta_title'), 
        'meta_keyword' => $this->request->getVar('meta_keyword'), 
        'meta_description' => $this->request->getVar('meta_description'), 
        

        'meta_image' => $meta_image,
    
       
        );
        $where = array(             
        'meta_id' => $this->request->getVar('edit_meta_id')
            );
        $updated=$commanmodel->update_query('meta',$data,$where);
        echo json_encode($updated);
     
    }
    
    
     public function coupon()
    {$commanmodel = new Commanmodel();
        $session = session();
       if(session()->get('admin_type')=='Supar Admin') {
           
             $data['table_name'] = '';
             
             
         
    
            $table_header = [
                
            ['data' => 'coupon'],
            ['data' => 'discount'],
            ['data' => 'startdate'],
            ['data' => 'enddate'],
         
            ['data' => 'action'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
       
       return view('admin/head').view('admin/sidebar').view('admin/coupon',$data).view('admin/footer');
       } else {
            
            return redirect()->back()->withInput();
       }
       
    }
    
    
        public function coupon_list()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input


// Define filters based on your requirements
$filters = [];

// Add search filter if a search term is provided
// if (!empty($searchname)) {
// $filters[] = [
//     'column' => 'product_title',
//     'value' => $searchname,
//     'type' => 'like',
// ];
// }






// if (session()->get('admin_type')=='Admin') { 
//     $filters[] = [
//     'column' => 'product_create_by',
//     'value' => session()->get('id'),
//     'type' => 'where',
// ];
// }

// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'coupon_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('coupon', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {

$name = $alldata_view->coupon_title;

$images = '<img class="cat-thumb" src="'.base_url().'/assets/coupon/'.$alldata_view->coupon_primary_image.'" >'; 



$action = '<div class="btn-group">
															<button type="button"
																class="btn btn-outline-'.$alldata_view->coupon_status_color.'">'.$alldata_view->coupon_status.'</button>
															<button type="button"
																class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
																data-bs-toggle="dropdown" aria-haspopup="true"
																aria-expanded="false" data-display="static">
																<span class="sr-only">Info</span>
															</button>
															<div class="dropdown-menu">
																<a class="dropdown-item editRecordcoupon" href="javascript:void(0);"  data-coupon_id="'.$alldata_view->coupon_id.'" data-coupon_code="'.$alldata_view->coupon_code.'" data-coupon_status="'.$alldata_view->coupon_status.'" data-coupon_primary_image="'.$alldata_view->coupon_primary_image.'" >Edit</a>
																<a class="dropdown-item" href="#">Delete</a>
															</div>
														</div>';



$data[] = [
    "coupon" => $name = $alldata_view->coupon_title,
    "discount" =>  $alldata_view->coupon_value,
    "startdate" => $alldata_view->coupon_start_date	,
    "enddate" => $alldata_view->coupon_end_date,
   
   
    "action" => $action 
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}
    
    
    
        function coupon_save() {
    $session = session();
    $commanmodel = new Commanmodel();

    // Get the coupon status and assign the color accordingly
    $status = $this->request->getVar('coupon_status');
    if ($status == 'Active') {
        $status_color = 'success';
    }  
    if ($status == 'Inactive') {
        $status_color = 'danger';
    }

    // Validate the uploaded image file
    $validated = $this->validate([
        'coupon_primary_image' => [
            'label' => 'Image File',
            'rules' => 'uploaded[coupon_primary_image]'
                . '|is_image[coupon_primary_image]'
                . '|mime_in[coupon_primary_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
        ],
    ]);

    // If the file is validated, upload it
    if ($validated) {
        $file = $this->request->getFile('coupon_primary_image');

        // Generate a random name for the uploaded file
        $primary_image = $file->getRandomName();

        // Move the file to the desired directory
        $file->move('assets/coupon', $primary_image);
    } else {
        // If no file is uploaded, set the image to an empty string
        $primary_image = '';
    }

    // Prepare the data for inserting into the database
    $data = [
        'coupon_title' => $this->request->getVar('coupon_title'),
        'coupon_code' => $this->request->getVar('coupon_code'),
        'coupon_start_date' => $this->request->getVar('coupon_start_date'),
        'coupon_end_date' => $this->request->getVar('coupon_end_date'),
        'coupon_type' => $this->request->getVar('coupon_type'),
        'coupon_value' => $this->request->getVar('coupon_value'),
        'coupon_primary_image' => $primary_image,
        'coupon_status' => $status,
        'coupon_status_color' => $status_color,
        'coupon_quick_overview' => $this->request->getVar('coupon_quick_overview'),
        'saved_date' => date('Y-m-d')
    ];

 

    // Insert the coupon into the database and return the result
    $inserted = $commanmodel->insert_query('coupon', $data);

    // Return the insertion result as a JSON response
    echo json_encode($inserted);
}

        function coupon_update(){
    $session = session();
    helper(['form', 'url']);
    $commanmodel = new Commanmodel();

    // Get coupon status and set the status color
    $status = $this->request->getVar('edit_couponStatus');
    if($status == 'Active') {
        $status_color = 'success';
    }  
    if($status == 'Inactive') {
        $status_color = 'danger';
    }

    // Validate the uploaded image file
    $validated = $this->validate([
        'edit_coupon_primary_image' => [
            'label' => 'Image File',
            'rules' => 'uploaded[edit_coupon_primary_image]'
                . '|is_image[edit_coupon_primary_image]'
                . '|mime_in[edit_coupon_primary_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
        ],
    ]);

    // If a new image is validated, upload it
    if ($validated) {
        $file = $this->request->getFile('edit_coupon_primary_image');

        // Generate a random name for the image
        $primary_image = $file->getRandomName();

        // Move the file to the desired directory
        $file->move('assets/coupon', $primary_image);
    } else {
        // If no new image is provided, use the existing one
        $primary_image = $this->request->getVar('edit_couponimages');
    }

 
    // Prepare the data for updating the coupon
    $data = [
        'coupon_code' => $this->request->getVar('edit_couponCode'),
        'coupon_primary_image' => $primary_image,
        'coupon_status' => $status,
        'coupon_status_color' => $status_color,
    ];

    // Define the where condition for updating the coupon
    $where = [
        'coupon_id' => $this->request->getVar('edit_couponID')
    ];

    // Perform the update query
    $updated = $commanmodel->update_query('coupon', $data, $where);

    // Return the result of the update as a JSON response
    echo json_encode($updated);
}


    public function user()
    {
        $session = session();
       if(session()->get('admin_type')=='Supar Admin' or session()->get('admin_type')=='Promoter' or session()->get('admin_type')=='Franchise') {
           
           
            $data['table_name'] = 'product';
    
            $table_header = [
                
            ['data' => 'id'],
            ['data' => 'name'],
            
            ['data' => 'email'],
              ['data' => 'type'],
            ['data' => 'action'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
     
        return view('admin/head').view('admin/sidebar').view('admin/user',$data).view('admin/footer');
       } else {
            $session->setFlashdata('failed', 'Sorry, You are not authorized to access this page?');
            return redirect()->back()->withInput();
       }
    }
   
    public function em_userlist()
    {

       $session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input
$id = $_POST['id']; 
$status = $_POST['status']; 
// Define filters based on your requirements
$filters = [];



// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements



if (!empty($searchname)) {
$filters[] = [
    'column' => 'name',
    'value' => $searchname,
    'type' => 'like',
];
}

if($session->admin_type == 'Franchise' || $session->admin_type == 'Promoter') {
   $filters[] = [
    'column' => 'refer_by',
    'value' => $session->referral_code,
    'type' => 'where',
]; 
}




$filters[] = [
    'column' => 'id !=',
    'value' => 1,
    'type' => 'where',
];

if (!empty($status)) {
$filters[] = [
    'column' => 'status',
    'value' => $status,
    'type' => 'like',
];
}

if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('admin', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alluser_view) {
        


            $name = 'Name : '.$alluser_view->name.'<br>Phone : '.$alluser_view->phone.'<br>Date : '.$alluser_view->date_time;
            $email = $alluser_view->email;
         
         if($alluser_view->admin_type != 'Vendor') {
        $name .= '<br>Referral Code : '.$alluser_view->referral_code;
         }
             $no++;
            
            
            $action = '<div class="btn-group">
															<button type="button"
																class="btn btn-outline-'.$alluser_view->status_color.'">'.$alluser_view->status.'</button>
															<button type="button"
																class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
																data-bs-toggle="dropdown" aria-haspopup="true"
																aria-expanded="false" data-display="static">
																<span class="sr-only">Info</span>
															</button>
															<div class="dropdown-menu">';
															
															if($alluser_view->admin_type == 'Vendor') { 
																  $action .= '<a class="dropdown-item"  href="'.base_url('admin/product/'.$alluser_view->id.'').'" >Product</a>';
															}
																  
																  
																  $action .= '<a class="dropdown-item" href="'.base_url('admin/edit-user/'.$alluser_view->id.'').'" >Edit</a>
																<a class="dropdown-item" href="'.base_url('admin/user-delete/'.$alluser_view->id.'').'" class="btn btn-danger btn-xs" onclick="return confirm(\'Are you sure you want to delete?\')">Delete</a>
															</div>
														</div>';
             
       $data[] = [
    "id" => $sn,
    "name" => $name,
    "email" => $email,
    
     "type" =>  $alluser_view->admin_type,
    "action" => $action
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
    }
       public function user_delete($id)
    {
         $session = session();
       if(session()->get('admin_type')=='Supar Admin' or session()->get('admin_type')=='Admin') {
        $db = \Config\Database::connect();

 $user =  $db->table('user_account')
            ->where('account_id', $id)
            ->delete();
     
       
         
    if($user) {
         $session->setFlashdata('created', 'This User has been Delete successfully!');
       
    } else {
        
        $session->setFlashdata('failed', 'Cannot delete. User is associated with category ?');
        
    }

       
        return redirect()->to('/admin/customer');
    } else {
            $session->setFlashdata('failed', 'Sorry, You are not authorized to access this page?');
            return redirect()->to('/admin/customer');
       }
    }



    public function create_user()
    {
       $session = session();
       if(session()->get('admin_type')=='Supar Admin' or session()->get('user')=='Yes') {
        helper(['form', 'url']);
         $commanmodel = new Commanmodel();
         
         $employee =$commanmodel->all_multiple_query_order_by('employee',array('employee_status'=> 'Active'),'employee_name','ASC');
        $company = $commanmodel->all_multiple_query_order_by('company',array('status'=> 'Active'),'name','ASC');
        $department=$commanmodel->all_multiple_query_order_by('position',array('position_status'=> 'Active'),'position_id','ASC');
           $data = [
            'employee' => $employee,
            'department' => $department,
            'company' => $company
            
        ];
            return view('admin/head').view('admin/sidebar').view('admin/create_user',$data).view('admin/footer');
    
       } else {
            $session->setFlashdata('failed', 'Sorry, You are not authorized to access this page?');
            return redirect()->back()->withInput();
       }
      
       
    }

    public function create_user_process()
    {
       $session = session();
       if(session()->get('admin_type')=='Supar Admin' or session()->get('user')=='Yes') {
        $commanmodel = new Commanmodel();
        
        $employee =$commanmodel->all_multiple_query_order_by('employee',array('employee_status'=> 'Active'),'employee_name','ASC');
        $company = $commanmodel->all_multiple_query_order_by('company',array('status'=> 'Active'),'name','ASC');
        
        
           $data = [
            'employee' => $employee,
            'company' => $company
            
        ];
        $session = session();
        helper(['form', 'url']);
        $validation =  \Config\Services::validation();
        $rules = [
            'user' => [
                'label'  => 'Name',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please Select user',
                ],
            ],
            'company' => [
                'label'  => 'company',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please select company',
                    'is_unique'  =>  'This email address is already exists!' 
                ],
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required|min_length[6]|max_length[20]',
                'errors' => [
                    'required'  =>  'Please enter password!',
                    'min_length'  =>  'Password min length 6!',
                    'max_length'  =>  'Password max length 20!'
                ],
            ],
            'confirm_password' => [
                'label'  => 'Confirm Password',
                'rules'  => 'required|min_length[6]|max_length[20]|matches[password]',
                'errors' => [
                    'required'  =>  'Please enter password!',
                    'min_length'  =>  'Password min length 6!',
                    'max_length'  =>  'Password max length 20!',
                    'matches' => 'Confirm password should be match password!'
                ],
            ],
        ];

        if($this->validate($rules))
        {
                  $employees = $commanmodel->get_single_query('employee',array('employee_id '=> $this->request->getVar('user')));
            
            
            $postData = array(
                'position' => $this->request->getVar('role'),  
                'employee_id' => $this->request->getVar('user'),
                'company_id' => $this->request->getVar('company'),
                
                'name' => $employees->employee_name,
                'email' => $employees->employee_email,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'password_show' => $this->request->getVar('password'),
                'status' => $this->request->getVar('status'), 
                'date_time' => date('Y-m-d')
                );
             $insertid = $commanmodel->insert_query_get_inserid('admin',$postData);
       


            $data = $this->request->getVar('data'); // Assuming you're using a framework for request handling
            
            $postRole = array(
                'role_user_id' => $insertid,
                'company' => isset($data['company']) ? 'Yes' : 'No',
                'customer_compliant' => isset($data['customer_compliant']) ? 'Yes' : 'No',
                'vendor_creation' => isset($data['vendor_creation']) ? 'Yes' : 'No',
                'user' => isset($data['user']) ? 'Yes' : 'No',
                'department' => isset($data['department']) ? 'Yes' : 'No',
                'employee' => isset($data['employee']) ? 'Yes' : 'No',
                'attendance' => isset($data['attendance']) ? 'Yes' : 'No',
                'category' => isset($data['category']) ? 'Yes' : 'No',
                'tender' => isset($data['tender']) ? 'Yes' : 'No',
                'tender_approval' => isset($data['tender_approval']) ? 'Yes' : 'No',
                'tender_participated' => isset($data['tender_participated']) ? 'Yes' : 'No',
                'tender_result' => isset($data['tender_result']) ? 'Yes' : 'No',
                'order_status' => isset($data['order_status']) ? 'Yes' : 'No',
                'order' => isset($data['order']) ? 'Yes' : 'No',
                'dispatch_status' => isset($data['dispatch_status']) ? 'Yes' : 'No',
                'customer' => isset($data['customer']) ? 'Yes' : 'No',
                'purchase_order' => isset($data['purchase_order']) ? 'Yes' : 'No',
                'quotation' => isset($data['quotation']) ? 'Yes' : 'No',
            );

                 $insert = $commanmodel->insert_query('role',$postRole);
              

                
                if($insert) {
                    $session->setFlashdata('created', 'This User has been saved successfully!');
                    return redirect()->to('/admin/user');
                } else {
                    $session->setFlashdata('failed', 'Sorry, This User has not been saved. Please try again?');
                    return redirect()->to('/admin/user');
                }

        } else {

            $data["validation"] = $validation->getErrors();

            return view('admin/head').view('admin/sidebar').view('admin/create_user',$data).view('admin/footer');
        }
       } else {
            $session->setFlashdata('failed', 'Sorry, You are not authorized to access this page?');
             return redirect()->back()->withInput();
       }
    }


 public function edit_user($id)
{
    $session = session();

    // Check user authorization
  if(session()->get('admin_type')=='Supar Admin' or session()->get('admin_type')=='Promoter' or session()->get('admin_type')=='Franchise') {
        $commanmodel = new Commanmodel();
        $validation = \Config\Services::validation();

        // Fetch user data
        $admin = $commanmodel->get_single_query('admin', ['id' => $id]);
       

        // Data to be passed to the view
        $data = [
            'admin' => $admin,
            'id' => $id,
            
            'validation' => $validation, // Add validation object to data
        ];

        helper(['form', 'url']);

        // Load views and pass data
        return view('admin/head') . view('admin/sidebar') . view('admin/edit_user', $data) . view('admin/footer');
    } else {
        // User is not authorized
        $session->setFlashdata('failed', 'Sorry, You are not authorized to access this page.');
        return redirect()->to('/admin/dashboard');
    }
}
    public function edit_user_process($id)
    {
        $session = session();
        if(session()->get('admin_type')=='Supar Admin' or session()->get('admin_type')=='Promoter' or session()->get('admin_type')=='Franchise') {
        $commanmodel = new Commanmodel();
        $session = session();
        helper(['form', 'url']);
        $validation =  \Config\Services::validation();
    $admin = $commanmodel->get_single_query('admin',array('id '=> $id));
      
        $data = [
            'admin' => $admin,
            'id' => $id,
            
        ];
     
             $rules = [
           'name' => [
                'label'  => 'Name',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please Select user',
                ],
            ],
            
           
           
        ]; 
        
      
        
       
        if($this->validate($rules))
        {
            
                $status = $this->request->getVar('status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         } 
            
            $postData = array(
                
                
                'name' =>$this->request->getVar('name'),
                'email' =>$this->request->getVar('email'),
                
                'status' => $this->request->getVar('status'), 
                'status_color' => $status_color, 
              
                );
                $where_data = array(
                    'id' => $id
                    );
             $insertid = $commanmodel->update_query('admin',$postData,$where_data);
       
  

                
                if($insertid) {
                    $session->setFlashdata('created', 'This Vender has been saved successfully!');
                    return redirect()->to('/admin/user');
                } else {
                    $session->setFlashdata('failed', 'Sorry, This Vender has not been saved. Please try again?');
                    return redirect()->to('/admin/user');
                }

        } else {

            $data["validation"] = $this->validator;
 
            return view('admin/head').view('admin/sidebar').view('admin/edit_user', $data).view('admin/footer');
       
    }
       } else {
            $session->setFlashdata('failed', 'Sorry, You are not authorized to access this page?');
              return redirect()->back()->withInput();
       }
}


    

   public function product_best_deal_action()
  {
       $commanmodel = new Commanmodel();
  $product_id =$this->request->getVar('product_id');
  $best_deal_product = $this->request->getVar('best_deal_product');  
  $fild = $this->request->getVar('fild');
  
  $commanmodel->update_query('answered',array('winner'=> 'No'),array());
  
  $data=$commanmodel->update_query('answered',array($fild=> $best_deal_product),array('answered_id'=> $product_id));
    echo json_encode($data);

  }
  
  
  
  public function product($id=null)
{
     $session = session();
 
      $commanmodel = new Commanmodel();

$data['id'] = $id;
        $data['table_name'] = 'product';
    
            $table_header = [
                
            ['data' => 'id'],
            ['data' => 'images'],
            ['data' => 'product'],
            ['data' => 'category'],
            ['data' => 'action'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
    
 
   return view('admin/head').view('admin/sidebar').view('admin/product', $data).view('admin/footer');

}
  
     public function productlist()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input
$id = $_POST['id']; 
$status = $_POST['status']; 
// Define filters based on your requirements
$filters = [];

// Add search filter if a search term is provided
if (!empty($searchname)) {
$filters[] = [
    'column' => 'product_name',
    'value' => $searchname,
    'type' => 'like',
];
}


if (!empty($status)) {
$filters[] = [
    'column' => 'product_status',
    'value' => $status,
    'type' => 'like',
];
}


if (!empty($id)) {
$filters[] = [
    'column' => 'product_create_by',
    'value' => $id,
    'type' => 'where',
];
}


if (session()->get('admin_type')=='Vendor') { 
    $filters[] = [
    'column' => 'product_create_by',
    'value' => session()->get('id'),
    'type' => 'where',
];
}

// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'product_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('product', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {


$name= $alldata_view->product_name;

$images = '<img class="cat-thumb" src="'.base_url().'/assets/images/'.$alldata_view->product_thumbnail.'" >'; 
  $category = '';
          
             

$action = '<a href="'.base_url().'/admin/edit_product/'.$alldata_view->product_id .'"  class="btn btn-primary btn-sm " >Edit </a>';
//$action .= '<br><a href="'.base_url().'/admin/faq/'.$alldata_view->product_id .'"  class="btn btn-primary btn-sm " >FAQ </a>';

        $status = '';
      
      

$data[] = [
    "id" => $sn,
    "images" => $images,
    "product" => $name,
    
    "category" => $category,
    "action" => $action 
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}
   public function create_product()
    {
        $session = session();
       $commanmodel = new Commanmodel();
          $main = $commanmodel->all_multiple_query_order_by('attribute_main',array(),'attribute_main_id','ASC');
         $brand = $commanmodel->all_multiple_query_order_by('brand',array(),'brand_name','ASC');
          $collections = $commanmodel->all_multiple_query_order_by('collections',array(),'collections_name','ASC');
        
        
        $category = $commanmodel->all_multiple_query_order_by('category',array('category_status'=> 'Active'),'category_id','ASC');
          $attributes = $commanmodel->all_multiple_query_order_by('attributes',array('attributes_status'=> 'Active'),'attributes_id','ASC');
         $data = [
       'main' => $main,
        'collections' => $collections,
        'brand' => $brand,
            'category' => $category,
             'attributes' => $attributes
        ];
        return view('admin/head').view('admin/sidebar').view('admin/create_product',$data).view('admin/footer');
       
    }
  
  
    public function create_product_process()
    {
       $session = session();
      
        $commanmodel = new Commanmodel();
        
       $main = $commanmodel->all_multiple_query_order_by('attribute_main',array(),'attribute_main_id','ASC');
        $collections = $commanmodel->all_multiple_query_order_by('collections',array(),'collections_name','ASC');
        $brand = $commanmodel->all_multiple_query_order_by('brand',array(),'brand_name','ASC');
        $category = $commanmodel->all_multiple_query_order_by('category',array('category_status'=> 'Active'),'category_id','ASC');
          $attributes = $commanmodel->all_multiple_query_order_by('attributes',array('attributes_status'=> 'Active'),'attributes_id','ASC');
         $data = [
       'main' => $main,
               'collections' => $collections,
       'brand' => $brand,
            'category' => $category,
             'attributes' => $attributes
        ];
        $session = session();
        helper(['form', 'url']);
        $validation =  \Config\Services::validation();
        $rules = [
            'name' => [
                'label'  => 'Title',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter title',
                ],
            ],
            'parent_id' => [
                'label'  => 'category',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please select category',
                  
                ],
            ],
           'overview' => [
                'label'  => 'overview',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter overview',
                ],
            ],
         
        ];

        if($this->validate($rules))
        {
                  
            
            $status = $this->request->getVar('status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         } 
         
         
  
  
                   
             $validated = $this->validate([
            'defaultimage' => [
                'label' => 'Image File',
                'rules' => 'uploaded[defaultimage]'
              
            ],
        ]);
       
  
        if ($validated) {
           
             $file = $this->request->getFile('defaultimage');

        // Generate a new secure name
         $primary_image = $this->getUniqueFileName($file->getName());

        // Move the file to the directory
        $file->move('assets/images', $primary_image);
        } else {
             $primary_image =  '';
        }
         
         
         
         
              $title = strip_tags($this->request->getVar('name'));
        $titleURL = strtolower(url_title($title));
        if($commanmodel->get_url_slug('product',$titleURL)){
        $titleURL = $titleURL.'-'.time(); 
        }
     
         
            $postData = array(
                'product_name' => $this->request->getVar('name'),  
                'product_category' => $this->request->getVar('parent_id'),
              
                'slug' => $titleURL,
            'product_thumbnail' => $primary_image,
                   'product_overview' => $this->request->getVar('overview'),

                'product_date' => date('Y-m-d')
                );
                 $insertid = $commanmodel->insert_query_get_inserid('product',$postData);
              
                
            
           
        
         

       
    

                
                if($insertid) {
                    $session->setFlashdata('created', 'This Product has been saved successfully!');
                    return redirect()->to('/admin/product');
                } else {
                    $session->setFlashdata('failed', 'Sorry, This Product has not been saved. Please try again?');
                    return redirect()->to('/admin/product');
                }

        } else {

            $data["validation"] = $validation->getErrors();

            return view('admin/head').view('admin/sidebar').view('admin/create_product',$data).view('admin/footer');
        }
      
    }
  
    public function edit_product($id)
    {
        $session = session();
       $commanmodel = new Commanmodel();

      $product = $commanmodel->get_single_query('product',array('product_id'=> $id));
     
         $data = [
              'id' => $id,
     
       'product' => $product,
      
        ];
        return view('admin/head').view('admin/sidebar').view('admin/edit_product',$data).view('admin/footer');
       
    }
    
   public function update_product_process($id)
    {
       $session = session();
      
        $commanmodel = new Commanmodel();
        $request = service('request');
    
          $product = $commanmodel->get_single_query('product',array('product_id'=> $id));

         $data = [
              'id' => $id,
   
       'product' => $product,
     
           
        ];
        
        $session = session();
        helper(['form', 'url']);
        $validation =  \Config\Services::validation();
        $rules = [
            'name' => [
                'label'  => 'Title',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter title',
                ],
            ],
            'parent_id' => [
                'label'  => 'category',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please select category',
                  
                ],
            ],
           'overview' => [
                'label'  => 'overview',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter overview',
                ],
            ],
         
        ];

        if($this->validate($rules))
        {
                  
            
            $status = $this->request->getVar('status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         } 
         
         


			
          
             $validated = $this->validate([
            'defaultimage' => [
                'label' => 'Image File',
                'rules' => 'uploaded[defaultimage]'
                  
            ],
        ]);
       
  
        if ($validated) {
           
             $file = $this->request->getFile('defaultimage');

        // Generate a new secure name
         $primary_image = $this->getUniqueFileName($file->getName());

        // Move the file to the directory
        $file->move('assets/images', $primary_image);
        } else {
             $primary_image =  $this->request->getVar('primary_image_old');
        }
         
         


         
           $title = strip_tags($this->request->getVar('name'));
        $titleURL = strtolower(url_title($title));
      
      
       $postData = array(
                'product_name' => $this->request->getVar('name'),  
                'product_category' => $this->request->getVar('parent_id'),
              
                'slug' => $titleURL,
             'product_thumbnail' => $primary_image,
                   'product_overview' => $this->request->getVar('overview'),

                'product_date' => date('Y-m-d')
                );
   
          
                
            
                
                
             $insertid =  $commanmodel->update_query('product',$postData,array('product_id' => $id)); 
             
   

                
                if($insertid) {
                    $session->setFlashdata('created', 'This Product has been saved successfully!');
                    return redirect()->to('/admin/product');
                } else {
                    $session->setFlashdata('failed', 'Sorry, This Product has not been saved. Please try again?');
                    return redirect()->to('/admin/product');
                }

        } else {

            $data["validation"] = $validation->getErrors();

            return view('admin/head').view('admin/sidebar').view('admin/create_user',$data).view('admin/footer');
        }
      
    }
  private function getUniqueFileName($fileName)
{
    $filePath = 'assets/images/' . $fileName;
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $baseName = pathinfo($fileName, PATHINFO_FILENAME);
    $counter = 1;

    // If file with the same name exists, append a counter to the file name
    while (file_exists($filePath)) {
        $filePath = 'assets/images/' . $baseName . '-' . $counter . '.' . $ext;
        $counter++;
    }

    return basename($filePath);  // Return the unique file name
}
  public function our_blogs(){
         $commanmodel = new Commanmodel();
         
              $data['type']  = $type = $this->request->getVar('type');
              
              if($type) {
                 $data['blogView'] =   $commanmodel->all_multiple_query_order_by('blogs',array('type'=> $type),'blog_id','DESC');
              } else {
        $data['blogView'] = $commanmodel->get_multiple_query_order_by('blogs','blog_id','DESC');    
    
              }
        return view('admin/head').view('admin/sidebar').view('admin/our-blogs', $data).view('admin/footer');
       
    }


    public function create_blog()
    {
     
         $session = session();
         return view('admin/head').view('admin/sidebar').view('admin/create-blog').view('admin/footer');
       
    }

 public function create_blog_process()
    {
        
        $commanmodel = new Commanmodel();
        $session = session();
        helper(['form', 'url']);
        $validation =  \Config\Services::validation();
        
        
        if($this->request->getVar('CreateNewBlog'))
        {
      
        
                $rules = [
            'blog_name' => [
                'label'  => 'Blog name',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please blog name',
                ],
            ],
            'blog_status' => [
                'label'  => 'Blog small description',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please select status',
                    
                ],
            ],
            
           'blog_small_description' => [
                'label'  => 'Blog status',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter small descriptio',
                    
                ],
            ],
        ];
        
                if($this->validate($rules) == FALSE)
                {    
      
            $data["validation"] = $validation->getErrors();
             return view('admin/head').view('admin/sidebar').view('admin/create-blog', $data).view('admin/footer');
                }
                else
                {


    $status = $this->request->getVar('blog_status');
    if($status=='Active')
    {
      $status_color = 'success';
    }
    if($status=='Inactive')
    {
      $status_color = 'danger';
    }
            if($_FILES['blog_image']['name']!=""){
                
                
                $file = $this->request->getFile('blog_image');

        // Generate a new secure name
        $blog_image = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/blog', $blog_image);
                
          
            }
            else{
           $blog_image = '';
            }












        $title = strip_tags($this->request->getVar('url_slug'));
        $titleURL = strtolower(url_title($title));
   


    $post_data = array(
    'blog_name' => $this->request->getVar('blog_name'),
    'url_slug' => $titleURL,
    
    'blog_status' => $this->request->getVar('blog_status'),
'type' => $this->request->getVar('type'),
    'blog_status_color' => $status_color,
    'blog_image' => $blog_image,
 'time' => $this->request->getVar('time'),
    'blog_small_description' => $this->request->getVar('blog_small_description'),
    'blog_description' => $this->request->getVar('blog_description'),
    'meta_title' => $this->request->getVar('meta_title'),
    'meta_keyword' => $this->request->getVar('meta_keyword'),
    'meta_description' => $this->request->getVar('meta_description')
    );
                   $inserted = $commanmodel->insert_query('blogs',$post_data); 
                   if($inserted)
                   {
                 
                    $session->setFlashdata('created', 'This blog has been created!');
                
                    return redirect()->to('/admin/blog?type='.$this->request->getVar('type'));
                    
                   }
                   else
                   {
         
            
             $session->setFlashdata('failed', 'Sorry, This course has not been created. Please try again?');  
        return view('admin/head').view('admin/sidebar').view('admin/create-blog', $data).view('admin/footer');
                   }


                }
        }
        else
        {
   
             $session->setFlashdata('failed', 'Submit process is not working!'); 
        return view('admin/head').view('admin/sidebar').view('admin/create-blog', $data).view('admin/footer');
        }



       
    }
    
    public function delete_our_blog($blog_id)
    {
         $session = session();
         $commanmodel = new Commanmodel();
     $deleteBlog = $commanmodel->delete_query('blogs',array('blog_id' =>$blog_id));
     if($deleteBlog)
     {
     
      $session->setFlashdata('created', 'This blog is delete.');
       return redirect()->to('/admin/blog');
     }
     else
     {
          $session->setFlashdata('failed', 'This is not delete!');

       return redirect()->to('/admin/blog');
     }
    
    }


    public function edit_our_blog($blog_id)
    {
      $commanmodel = new Commanmodel();
        $data['blogView'] = $commanmodel->get_single_query('blogs',array('blog_id' => $blog_id));    
 
        
         return view('admin/head').view('admin/sidebar').view('admin/edit-blog', $data).view('admin/footer');
       
    }


 public function edit_blog_process($blog_id)
    {
       $commanmodel = new Commanmodel();
       $session = session();
  helper(['form', 'url']);
        $validation =  \Config\Services::validation();
        if($this->request->getVar('CreateEditBlog'))
        {
         $rules = [
            'blog_name' => [
                'label'  => 'Blog name',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please blog name',
                ],
            ],
            'blog_status' => [
                'label'  => 'Blog small description',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please select status',
                    
                ],
            ],
            
           'blog_small_description' => [
                'label'  => 'Blog status',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter small descriptio',
                    
                ],
            ],
        ];
        
                if($this->validate($rules) == FALSE)
                {   
                    $data["validation"] = $validation->getErrors();
        $data['blogView'] = $commanmodel->get_single_query('blogs',array('blog_id' => $blog_id));    
        
        return view('admin/head').view('admin/sidebar').view('admin/edit-blog', $data).view('admin/footer');
                }
                else
                {


    $status = $this->request->getVar('blog_status');
    if($status=='Active')
    {
      $status_color = 'success';
    }
    if($status=='Inactive')
    {
      $status_color = 'danger';
    }
            if($_FILES['blog_image']['name']!=""){
                    $file = $this->request->getFile('blog_image');

        // Generate a new secure name
        $blog_image = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/blog', $blog_image);
            }
            else{
                  $blog_image= $this->request->getVar('blog_image_old');
            }


 $title = strip_tags($this->request->getVar('url_slug'));
        $titleURL = strtolower(url_title($title));
 
    $post_data = array(
    'blog_name' => $this->request->getVar('blog_name'),
    'blog_status' => $this->request->getVar('blog_status'),
  'url_slug' => $titleURL,
  
    'blog_status_color' => $status_color,
    'blog_image' => $blog_image,
  'type' => $this->request->getVar('type'),
  'time' => $this->request->getVar('time'),
    'blog_small_description' => $this->request->getVar('blog_small_description'),
    'blog_description' => $this->request->getVar('blog_description'),
    'meta_title' => $this->request->getVar('meta_title'),
    'meta_keyword' => $this->request->getVar('meta_keyword'),
    'meta_description' => $this->request->getVar('meta_description')
    );
                   $inserted = $commanmodel->update_query('blogs',$post_data,array('blog_id' => $blog_id)); 
                   if($inserted)
                   {
                    $session->setFlashdata('created', 'This blog has been updated.');
               
                    
                     return redirect()->to('/admin/blog?type='.$this->request->getVar('type'));
                   }
                   else
                   {
            $session->setFlashdata('failed', 'Sorry, This blog has not been updated. Please try again?');    
        $data['blogView'] = $commanmodel->get_single_query('blogs',array('blog_id' => $blog_id));    
    
     return view('admin/head').view('admin/sidebar').view('admin/edit-blog', $data).view('admin/footer');
                   }


                }
        }
        else
        {
            $session->setFlashdata('failed', 'Submit process is not working!');    
        $data['blogView'] = $commanmodel->get_single_query('blogs',array('blog_id' => $blog_id));    
       
        return view('admin/head').view('admin/sidebar').view('admin/edit-blog', $data).view('admin/footer');
        }



      
    }
         public function team()
    {
        $session = session();
        $commanmodel = new Commanmodel();
      $category = $commanmodel->all_multiple_query_order_by('category',array('parent_id'=> '0','subparent_id'=> '0'),'menu_order','ASC');
         $team= $commanmodel->all_multiple_query_order_by('team',array(),'team_id','ASC');
        
          $data = array(
       'team' => $team,
        'category' => $category,
      
		);

        
        
         return view('admin/head',$data).view('admin/sidebar').view('admin/team').view('admin/footer');
    }
     public function team_save()
{
    $session = session();
    $commanmodel = new Commanmodel();

    $status = $this->request->getVar('status');

    // Default status color
    $status_color = 'secondary';

    if ($status == 'Active') {
        $status_color = 'success';
    } elseif ($status == 'Inactive') {
        $status_color = 'danger';
    }

    // File Upload Handling
    $file = $this->request->getFile('logo');
    $logo = '';

    if ($file && $file->isValid() && !$file->hasMoved()) {

        // Optional: Validate file type
        $allowedTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/webp'];

        if (in_array($file->getMimeType(), $allowedTypes)) {

            $logo = $file->getRandomName();
            $file->move(FCPATH . 'assets/team', $logo);

        } else {
            $session->setFlashdata('error', 'Invalid image format.');
            return redirect()->back();
        }
    }

    $postData = [
        'team_name'          => $this->request->getVar('name'),
        'team_logo'          => $logo,
        'overview'           => $this->request->getVar('overview'),
        'designation'        => $this->request->getVar('designation'),
        'team_status'        => $status,
        'team_status_color'  => $status_color,
        'created_at'         => date('Y-m-d H:i:s')
    ];

    $insertid = $commanmodel->insert_query_get_inserid('team', $postData);

    if ($insertid) {
        $session->setFlashdata('created', 'Team has been created successfully!');
    } else {
        $session->setFlashdata('error', 'Something went wrong.');
    }

    return redirect()->to('/admin/team');
}

    
         public function team_edit($id)
    {
        $session = session();
        $commanmodel = new Commanmodel();
      $category = $commanmodel->all_multiple_query_order_by('category',array('parent_id'=> '0','subparent_id'=> '0'),'menu_order','ASC');
         $team= $commanmodel->get_single_query('team',array('team_id' =>$id));
        
          $data = array(
              'id' => $id,
       'team' => $team,
        'category' => $category,
      
		);

        
        
         return view('admin/head',$data).view('admin/sidebar').view('admin/team_edit').view('admin/footer');
    }
    
          public function team_update($id)
    {
        $session = session();
        $commanmodel = new Commanmodel();
      $category = $commanmodel->all_multiple_query_order_by('category',array('parent_id'=> '0','subparent_id'=> '0'),'menu_order','ASC');
        
        
              $status = $this->request->getVar('status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         }
         
         
         
         
         
                $validated = $this->validate([
            'logo' => [
                'label' => 'Image File',
                'rules' => 'uploaded[logo]'
                    . '|is_image[logo]'
                    . '|mime_in[logo,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                    . '|max_size[logo,100]'
                    . '|max_dims[logo,1024,768]',
            ],
        ]);
 
       
  
        if ($validated) {
          $file = $this->request->getFile('logo');

        // Generate a new secure name
        $logo = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/team', $logo);
        } else {
             $logo = $this->request->getVar('logo_old');
        }
         
         
         
         
         
     
        
          $postData = array(
                    
                'team_name' => $this->request->getVar('name'),
                'team_logo' => $logo,
               'overview' => $this->request->getVar('overview'),
                'designation' => $this->request->getVar('designation'),
                'team_status' => $status, 
                'team_status_color' => $status_color
                );
      
          $where_data = array(
                    'team_id' => $id
                    );
             $insertid = $commanmodel->update_query('team',$postData,$where_data);
        
            $session->setFlashdata('created', 'This team has been Updated successfully!');
                
        return redirect()->to('/admin/team');
    }
    
     public function faq($id=0)
    {
         $session = session();
        $commanmodel = new Commanmodel();
       
        $data['id'] = $id;
    $data['faqView'] =  $commanmodel->all_multiple_query_order_by('faq',array(),'faq_id','ASC');
        
         return view('admin/head',$data).view('admin/sidebar').view('admin/faq',$data).view('admin/footer');
       
    }


    public function faq_process()
    {
         $session = session();
         $commanmodel = new Commanmodel();
        $data['faqView'] = $commanmodel->get_multiple_query_order_by('faq','faq_id','DESC');
        
        if($this->request->getVar('CreateFaq'))
        {
         $status = $this->request->getVar('faq_status'); 
         if($status=='Active')
         {
           $status_color = 'success';
         }
         if($status=='Inactive')
         {
           $status_color = 'danger';
         }

        $post_data = array(
             'type' => $this->request->getVar('type'),
        'faq_question' => $this->request->getVar('faq_question'),
        'faq_answer' => $this->request->getVar('faq_answer'),
        'faq_status' => $this->request->getVar('faq_status'),
        'faq_status_color' => $status_color
        );
        $inserted = $commanmodel->insert_query('faq',$post_data); 
                   if($inserted)
                   {
                   $session->setFlashdata('created', 'This Faq has been saved.');
                    return redirect()->to(base_url('admin/faq/'.$this->request->getVar('type')));
                    
                   }
                   else
                   {
   $session->setFlashdata('failed', 'Sorry, This faq has not been saved.');  
     return view('admin/head',$data).view('admin/sidebar').view('admin/faq',$data).view('admin/footer');
                   }


            }
        else
        {
       $session->setFlashdata('failed', 'Submit process is not working!');   
        return view('admin/head',$data).view('admin/sidebar').view('admin/faq',$data).view('admin/footer');
        }  

       
    }



    public function delete_faq($faq_id,$id)
    {
         $session = session();
         $commanmodel = new Commanmodel();
     $deleteClient = $commanmodel->delete_query('faq',array('faq_id' =>$faq_id));
     if($deleteClient)
     { 
     $session->setFlashdata('created', 'This Faq is delete.');
      return redirect()->to(base_url('admin/faq/'.$id));
     }
     else
     {
     $session->setFlashdata('failed', 'This faq is not delete!');
      return redirect()->to(base_url('admin/faq/'.$id)); 
     }
    
    }


    public function edit_faq($faq_id)
    {
          $commanmodel = new Commanmodel();
        $data['faqView'] = $commanmodel->get_single_query('faq',array('faq_id' => $faq_id));   
        
       
       return view('admin/head',$data).view('admin/sidebar').view('admin/edit-faq',$data).view('admin/footer');
       
    }

    public function edit_faq_process($faq_id)
    {
         $session = session();
          $commanmodel = new Commanmodel();
$data['faqView'] = $commanmodel->get_single_query('faq',array('faq_id' => $faq_id)); 
        if($this->request->getVar('EditFaq'))
        {
         $status = $this->request->getVar('faq_status'); 
         if($status=='Active')
         {
           $status_color = 'success';
         }
         if($status=='Inactive')
         {
           $status_color = 'danger';
         }
        $post_data = array(
              'type' => $this->request->getVar('type'),
        'faq_question' => $this->request->getVar('faq_question'),
        'faq_answer' => $this->request->getVar('faq_answer'),
        'faq_status' => $this->request->getVar('faq_status'),
        'faq_status_color' => $status_color
        );
        $inserted = $commanmodel->update_query('faq',$post_data,array('faq_id' => $faq_id)); 
                   if($inserted)
                   {
                   $session->setFlashdata('created', 'This Faq has been Updated.');
                    return redirect()->to(base_url('admin/faq/'.$this->request->getVar('type')));
                   }
                   else
                   {
           $session->setFlashdata('failed', 'Sorry, This faq has not been updated.');   
   return view('admin/head',$data).view('admin/sidebar').view('admin/edit-faq',$data).view('admin/footer');
                   }


            }
        else
        {
       $session->setFlashdata('failed', 'Submit process is not working!');   
       return view('admin/head',$data).view('admin/sidebar').view('admin/edit-faq',$data).view('admin/footer');
        }  

       
    }
     public function our_gallery()
    {
         $session = session();
         $commanmodel = new Commanmodel();
        $data['clientView'] = $commanmodel->get_multiple_query_order_by('clients','client_id','DESC');    
   
       
       return view('admin/head',$data).view('admin/sidebar').view('admin/our-gallery').view('admin/footer');
    }

    public function our_gallery_process()
    {
        
 $session = session();
         $commanmodel = new Commanmodel();
        if($this->request->getVar('upload_logo'))
        {

            if($_FILES['client_logo']['name']!=""){
            
                         
                $file = $this->request->getFile('client_logo');

        // Generate a new secure name
        $client_logo = $file->getRandomName();

        // Move the file to the directory
        $file->move('assets/client', $client_logo);
            }
            else{
            $session->setFlashdata('failed', 'Please choose Image');
            $data['clientView'] = $commanmodel->get_multiple_query_order_by('clients','client_id','DESC');    
           return view('admin/head',$data).view('admin/sidebar').view('admin/our-gallery').view('admin/footer');
            }

        $post_data = array(
            'name' => $this->request->getVar('name'),
            'location' => $this->request->getVar('location'),
             'type' => $this->request->getVar('type'),
        'client_image' => $client_logo
        );
        $inserted = $commanmodel->insert_query('clients',$post_data); 
                   if($inserted)
                   {
                    $session->setFlashdata('created', 'This gallery  has been uploaded.');
                
                    
                     return redirect()->to('/admin/our_gallery');
                   }
                   else
                   {
            $session->setFlashdata('failed', 'Sorry, This gallery  has not been uploaded.');
        $data['clientView'] = $commanmodel->get_multiple_query_order_by('clients','client_id','DESC');    

         return view('admin/head',$data).view('admin/sidebar').view('admin/our-gallery').view('admin/footer');
                   }


            }
        else
        {
            $session->setFlashdata('failed', 'Submit process is not working!');
        $data['clientView'] = $commanmodel->get_multiple_query_order_by('clients','client_id','DESC');    
 return view('admin/head',$data).view('admin/sidebar').view('admin/our-gallery').view('admin/footer');
        }  

       
    }



    public function delete_gallery($client_id)
    {
        $session = session();
         $commanmodel = new Commanmodel();
     $deleteClient = $commanmodel->delete_query('clients',array('client_id' =>$client_id));
     if($deleteClient)
     {
      $session->setFlashdata('created', 'This gallery is delete.');
      return redirect()->to('/admin/our_gallery');
     }
     else
     {
      $session->setFlashdata('failed', 'This is not delete!');
       return redirect()->to('/admin/our_gallery');
     }
    
    }
    
     public function attributes()
{
     $session = session();
 
      $commanmodel = new Commanmodel();


        $data['table_name'] = 'attributes';
    
            $table_header = [
                
            ['data' => 'id'],
          ['data' => 'main_attributes'],
            ['data' => 'attributes'],
      
            ['data' => 'action'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
    
 
   return view('admin/head').view('admin/sidebar').view('admin/attributes', $data).view('admin/footer');

}
  
     public function attributes_list()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input

// Define filters based on your requirements
$filters = [];

// Add search filter if a search term is provided
if (!empty($searchname)) {
$filters[] = [
    'column' => 'attributes_name',
    'value' => $searchname,
    'type' => 'like',
];
}

// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'attributes_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('attributes', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {


$attributes = $commanmodel->get_single_query('attribute_main',array('attribute_main_id'=> $alldata_view->main_id));
$name = $alldata_view->attributes_name;

$action = '<a href="'.base_url().'/admin/edit_attributes/'.$alldata_view->attributes_id .'"  class="btn btn-primary btn-sm " >Edit </a>';


        $status = '';
      
      

$data[] = [
    "id" => $sn,
  'main_attributes' => $attributes->attribute_main_name, 
    "attributes" => $name,
    
 
    "action" => $action 
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}
  public function create_attributes()
    {
        $session = session();
       $commanmodel = new Commanmodel();
        $main = $commanmodel->all_multiple_query_order_by('attribute_main',array(),'attribute_main_id','ASC');
         $data = [
      
         'main'=>$main
        ];
        return view('admin/head').view('admin/sidebar').view('admin/create_attributes',$data).view('admin/footer');
       
    }
  
  
    public function create_attributes_process()
    {
       $session = session();
      
        $commanmodel = new Commanmodel();
        
         $category = $commanmodel->all_multiple_query_order_by('category',array('category_status'=> 'Active'),'category_id','ASC');
         $data = [
      
            'category' => $category
        ];
        
        
        $session = session();
        helper(['form', 'url']);
        $validation =  \Config\Services::validation();
        $rules = [
            'attributes_name' => [
                'label'  => 'Title',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter title',
                ],
            ],
           
         
        ];

        if($this->validate($rules))
        {
                  
            
            $status = $this->request->getVar('status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         } 
         
         
     
                if($this->request->getVar('main_attributes_id') =='add') { 
                
                 $postDatamain = array(
                   
               
              'attribute_main_name' => $this->request->getVar('main_attributes_name'),
         
                );
                
                  $insertid_main = $commanmodel->insert_query_get_inserid('attribute_main',$postDatamain);
                } else {
                    $insertid_main = $this->request->getVar('main_attributes_id');
                }
     
       
            $postData = array(
                    'main_id' => $insertid_main,  
               
              'attributes_name' => $this->request->getVar('attributes_name'),
               'attributes_symbol' => ($insertid_main ==1)? $this->request->getVar('attributes_color') : '',
            
                     
                
                
                'attributes_status' => $this->request->getVar('status'), 
                'attributes_status_color' => $status_color,
                
                );
                
                  $insertid = $commanmodel->insert_query_get_inserid('attributes',$postData);
                
            
           
             
         


                
                if($insertid) {
                    $session->setFlashdata('created', 'This attributes has been saved successfully!');
                    return redirect()->to('/admin/attributes');
                } else {
                    $session->setFlashdata('failed', 'Sorry, This attributes has not been saved. Please try again?');
                    return redirect()->to('/admin/attributes');
                }

        } else {

            $data["validation"] = $validation->getErrors();

            return view('admin/head').view('admin/sidebar').view('admin/create_attributes',$data).view('admin/footer');
        }
      
    }
  
    public function edit_attributes($id)
    {
        $session = session();
       $commanmodel = new Commanmodel();
       
        $main = $commanmodel->all_multiple_query_order_by('attribute_main',array(),'attribute_main_id','ASC');
     
       
       $attributes = $commanmodel->get_single_query('attributes',array('attributes_id'=> $id));
       $attributes_main = $commanmodel->get_single_query('attribute_main',array('attribute_main_id'=> $attributes->main_id));
       
         $data = [
              'main'=>$main,
      'attributes' => $attributes,
      'attributes_main' => $attributes_main,
           'id' => $id,  
        ];
        return view('admin/head').view('admin/sidebar').view('admin/edit_attributes',$data).view('admin/footer');
       
    }
    
    public function update_attributes_process($id)
    {
       $session = session();
      
        $commanmodel = new Commanmodel();
        
     $main = $commanmodel->all_multiple_query_order_by('attribute_main',array(),'attribute_main_id','ASC');
     
       
       $attributes = $commanmodel->get_single_query('attributes',array('attributes_id'=> $id));
       $attributes_main = $commanmodel->get_single_query('attribute_main',array('attribute_main_id'=> $attributes->main_id));
       
         $data = [
              'main'=>$main,
      'attributes' => $attributes,
      'attributes_main' => $attributes_main,
           'id' => $id,  
        ];
        
        $session = session();
        helper(['form', 'url']);
        $validation =  \Config\Services::validation();
        $rules = [
            'attributes_name' => [
                'label'  => 'Title',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter title',
                ],
            ],
          
         
        ];

        if($this->validate($rules))
        {
                  
            
            $status = $this->request->getVar('status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         } 
         
         
          
          
          $postData_main = array(
               'attribute_main_name' => $this->request->getVar('main_attributes_name'),
               
                );
                
                $commanmodel->update_query('attribute_main',$postData_main,array('attribute_main_id' => $this->request->getVar('main_attributes_id')));

      
            $postData = array(
               'main_id' => $this->request->getVar('main_attributes_id'),  
               
              'attributes_name' => $this->request->getVar('attributes_name'),
               'attributes_symbol' => ($insertid_main ==1)? $this->request->getVar('attributes_color') : '',
            
                     
                
                
                'attributes_status' => $this->request->getVar('status'), 
                'attributes_status_color' => $status_color,
               
                );
             $insertid =  $commanmodel->update_query('attributes',$postData,array('attributes_id' => $id)); 
             
         

              

                
                if($insertid) {
                    $session->setFlashdata('created', 'This Course has been saved successfully!');
                    return redirect()->to('/admin/attributes');
                } else {
                    $session->setFlashdata('failed', 'Sorry, This Course has not been saved. Please try again?');
                    return redirect()->to('/admin/attributes');
                }

        } else {

            $data["validation"] = $validation->getErrors();

            return view('admin/head').view('admin/sidebar').view('admin/create_attributes',$data).view('admin/footer');
        }
      
    }
    
    
    
      public function enquiry()
{
     $session = session();
 
      $commanmodel = new Commanmodel();


        $data['table_name'] = 'attributes';
    
            $table_header = [
                
            ['data' => 'id'],
          
            ['data' => 'info'],
            ['data' => 'pro'],
            ['data' => 'message'],
      
           
        
        ];
        
        $data['table_column'] = json_encode($table_header);
    
 
   return view('admin/head').view('admin/sidebar').view('admin/enquiry', $data).view('admin/footer');

}
  
     public function enquirylist()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input

// Define filters based on your requirements
$filters = [];



if (session()->get('admin_type')=='Admin') { 
    $filters[] = [
    'column' => 'enquiry_vender',
    'value' => session()->get('id'),
    'type' => 'where',
];
}

// Add search filter if a search term is provided
if (!empty($searchname)) {
$filters[] = [
    'column' => 'enquiry_name',
    'value' => $searchname,
    'type' => 'like',
];
}

// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'enquiry_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('enquiry', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {



$info = 'Name : '.$alldata_view->enquiry_name.'<br>Email : '.$alldata_view->enquiry_email.'<br>Phone : '.$alldata_view->enquiry_phone;



      
      $product = $commanmodel->get_single_query('product',array('product_id'=> $alldata_view->enquiry_pro_id));

$data[] = [
    "id" => $sn,
  
    "info" => $info,
     "pro" => $product->product_name,
     "message" => $alldata_view->enquiry_message,
   
 
  
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}



  public function order()
{
     $session = session();
 
      $commanmodel = new Commanmodel();


        $data['table_name'] = 'attributes';
    
            $table_header = [
                
            ['data' => 'id'],
             ['data' => 'img'],
              ['data' => 'item'],
              ['data' => 'category'],
               ['data' => 'price'],
                ['data' => 'refer_by'],
                ['data' => 'seller'],
                 ['data' => 'payment_type'],
                ['data' => 'shipping_address'],
                 ['data' => 'stauts'],
                  ['data' => 'action'],
          
      
           
        
        ];
        
        $data['table_column'] = json_encode($table_header);
    
 
   return view('admin/head').view('admin/sidebar').view('admin/order', $data).view('admin/footer');

}



  public function updateorderstatus($id)
{
      $session = session();
 
      $commanmodel = new Commanmodel();
      
        $item = $commanmodel->get_single_query('booking_product',array('booking_product_order_id'=> $id));
        
        
        if($item->commission == 'Unpaid' and $this->request->getVar('status')=='delivered') {
            
          
             $this->transfercommition($item->booking_product_referral_code, $item->booking_product_sub_total, $item->booking_product_order_id);
            
        }
      
      
      
      
      if($this->request->getVar('status') == 'delivered') {
          $data = array(  
    
      
        'booking_product_status' => $this->request->getVar('status'), 
         'commission' => 'Paid', 

        );
      } else {
          $data = array(  
    
      
        'booking_product_status' => $this->request->getVar('status'), 
        

        );
      }
      
       
        $where = array(             
        'booking_product_order_id' => $id
            );
        $updated=$commanmodel->update_query('booking_product',$data,$where);
        
         return redirect()->to('admin/order_details/'.$id);
}


  public function order_details($id)
{
     $session = session();
 
      $commanmodel = new Commanmodel();
   
       $item = $commanmodel->get_single_query('booking_product',array('booking_product_order_id'=> $id));
        $vender = $commanmodel->get_single_query('admin',array('id'=> $item->booking_product_vender));
     
          $order = $commanmodel->get_single_query('order_book',array('order_book_id'=> $item->booking_product_order_book_id));
       
    
       
         $data = [
              'order'=>$order,
      'item' => $item,
      'vender' => $vender,
     
           'id' => $id,  
        ];
 return view('admin/head').view('admin/sidebar').view('admin/order_details', $data).view('admin/footer');
}
  
   public function orderlist()
{
    $session = session();
    $commanmodel = new Commanmodel();

    $draw   = $_POST['draw'] ?? 1;
    $start  = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;

    $searchname        = $_POST['searchname'] ?? '';
    $membership_id     = $_POST['membership_id'] ?? '';
    $status_filter     = $_POST['status_filter'] ?? '';
    $pending_filter    = $_POST['pending_filter'] ?? '';

    $filters = [];
    $arrayfilte = [];

    /* ----------------------------------------------------
       ROLE BASED FILTER
    -----------------------------------------------------*/
    if ($session->admin_type == 'Franchise' || $session->admin_type == 'Promoter') {

        $filtersrefer = [$session->referral_code];

        $mypromoters = $commanmodel->all_multiple_query_order_by(
            'admin',
            ['refer_by' => $session->referral_code],
            'id',
            'ASC'
        );

        $filtersrefer = array_merge($filtersrefer, array_map(function ($p) {
            return $p->referral_code;
        }, $mypromoters));

    } else {
        $filtersrefer = [];
    }

    if ($session->admin_type == 'Vendor') {
        $filters[] = [
            'column' => 'booking_product_vender',
            'value'  => $session->id,
            'type'   => 'where'
        ];
    }

    /* ----------------------------------------------------
       SEARCH FILTERS
    -----------------------------------------------------*/

    // Product Name Search
    if (!empty($searchname)) {
        $filters[] = [
            'column' => 'booking_product_product_name',
            'value'  => $searchname,
            'type'   => 'like'
        ];
    }

    // Membership ID Search
    if (!empty($membership_id)) {
        $filters[] = [
            'column' => 'user_account.membership_id',
            'value'  => $membership_id,
            'type'   => 'like'
        ];
    }

    /* ----------------------------------------------------
       STATUS FILTER (Pending / Unapproved / etc)
    -----------------------------------------------------*/
    if (!empty($status_filter)) {
        $filters[] = [
            'column' => 'booking_product_status',
            'value'  => $status_filter,
            'type'   => 'where'
        ];
    }

    /* ----------------------------------------------------
       ONLY ADMIN ACTION REQUIRED (Pending Approval)
    -----------------------------------------------------*/
    if ($pending_filter == 'yes') {
        $filters[] = [
            'column' => 'booking_product_admin_action',
            'value'  => 'pending',
            'type'   => 'where'
        ];
    }

    /* ----------------------------------------------------
       DEFAULT STATUS ARRAY
    -----------------------------------------------------*/
    $arrayfilte[] = [
        'column' => 'booking_product_status',
        'value'  => ['success','delivered','cancelled','pending','unapproved'],
        'type'   => 'where'
    ];

    /* ----------------------------------------------------
       ORDERING (Newest Change First)
    -----------------------------------------------------*/
    $order = [
        'column' => 'booking_product_updated_at',
        'order'  => 'DESC'
    ];

    /* ----------------------------------------------------
       GET DATA
    -----------------------------------------------------*/
    $result = $commanmodel->getDataFromTableorder(
        'booking_product',
        $filters,
        $order,
        $length,
        $start,
        $filtersrefer,
        $arrayfilte
    );

    $alldata = $result['filteredRecords'];
    $data = [];

    foreach ($alldata as $row) {

        $order_book = $commanmodel->get_single_query(
            'order_book',
            ['order_book_id'=> $row->booking_product_order_book_id]
        );

        $user = null;
        $membership_id = '-';
        $subscription_status = '<span class="badge bg-danger">Inactive</span>';

        if ($order_book) {
            $user = $commanmodel->get_single_query(
                'user_account',
                ['account_id'=> $order_book->order_book_user_id]
            );
        }

        if ($user) {
            $membership_id = $user->membership_id;

            // Subscription Check
            if (!empty($user->subscription_valid_till) &&
                strtotime($user->subscription_valid_till) >= strtotime(date('Y-m-d'))) {

                $subscription_status =
                    '<span class="badge bg-success">Active</span>';
            }
        }

        /* -------------------------------
           STATUS BADGE LOGIC
        --------------------------------*/
        $status_badge = '<span class="badge bg-secondary">'.$row->booking_product_status.'</span>';

        if ($row->booking_product_status == 'pending') {
            $status_badge = '<span class="badge bg-warning">Pending</span>';
        }

        if ($row->booking_product_status == 'unapproved') {
            $status_badge = '<span class="badge bg-danger">Unapproved</span>';
        }

        if ($row->booking_product_status == 'success') {
            $status_badge = '<span class="badge bg-success">Approved</span>';
        }

        /* -------------------------------
           ACTION REQUIRED STICKER
        --------------------------------*/
        $admin_action = '';
        if ($row->booking_product_admin_action == 'pending') {
            $admin_action =
                '<span class="badge bg-danger">Action Required</span>';
        }

        $action = '
        <div class="btn-group mb-1">
            <a href="'.base_url().'/admin/order_details/'.$row->booking_product_order_id.'" 
               class="btn btn-outline-primary btn-sm">Details</a>
        </div>';

        $data[] = [
            "membership_id" => $membership_id,
            "item" => $row->booking_product_product_name,
            "price" => $row->booking_product_price,
            "payment_type" => $order_book->order_book_pay_type ?? '',
            "status" => $status_badge,
            "subscription" => $subscription_status,
            "admin_action" => $admin_action,
            "action" => $action
        ];
    }

    $response = [
        "draw" => intval($draw),
        "recordsTotal" => $result['filteredRecordCount'],
        "recordsFiltered" => $result['totalRecords'],
        "data" => $data
    ];

    return $this->response->setJSON($response);
}

      public function customer()
{
     $session = session();
 
      $commanmodel = new Commanmodel();


        $data['table_name'] = 'attributes';
    
            $table_header = [
                
            ['data' => 'id'],
           ['data' => 'image'],
            ['data' => 'info'],
        
              ['data' => 'status'],
          
             ['data' => 'action'],
           
        
        ];
        
        $data['table_column'] = json_encode($table_header);
    
 
   return view('admin/head').view('admin/sidebar').view('admin/customer', $data).view('admin/footer');

}
public function delete_user_photo()
{
    $db = \Config\Database::connect();
    $photo_id = $this->request->getPost('id');

    if(!$photo_id){
        return;
    }

    // Get photo record
    $photo = $db->table('user_photos')
                ->where('id', $photo_id)
                ->get()
                ->getRow();

    if($photo){

        // Delete file from folder
        $filePath = FCPATH . 'assets/uploads/' . $photo->photo_path;

        if(file_exists($filePath)){
            unlink($filePath);
        }

        // Delete DB record
        $db->table('user_photos')
           ->where('id', $photo_id)
           ->delete();
    }

    return $this->response->setJSON(['status' => 'success']);
}
public function change_status()
{
    $commanmodel = new Commanmodel();

    $user_id = $this->request->getPost('user_id');
    $status  = $this->request->getPost('status');   // new status
    $type    = $this->request->getPost('type') ?? 'approval';     // approval | verified

    if (empty($user_id) || empty($status) || empty($type)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid data'
        ]);
    }

    // ✅ Decide column based on type
    $column = '';
    if ($type === 'approval') {
        $column = 'approval';
        if (!in_array($status, ['Approved', 'Unapproved'], true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid approval value']);
        }
    } elseif ($type === 'verified') {
        $column = 'verified';
        if (!in_array($status, ['Yes', 'No'], true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid verified value']);
        }
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid type'
        ]);
    }

    $update = $commanmodel->update_query(
        'user_account',
        [$column => $status],
        ['account_id' => $user_id]
    );

    if ($update) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status updated',
            'newStatus' => $status,
            'type' => $type
        ]);
    }

    return $this->response->setJSON([
        'success' => false,
        'message' => 'Update failed'
    ]);
}




public function profile_view($id)
{
    $session = session();
    $commanmodel = new Commanmodel();
    
    // User basic data
    $userdata = $commanmodel->get_single_query('user_account', array('account_id' => $id));
    
    // All religions and castes for dropdowns
    $religions = $commanmodel->all_multiple_query_order_by('religions', array(), 'id', 'ASC');
    
    // Get religion name for display
    $religion_data = $commanmodel->get_single_query('religions', array('id' => $userdata->religion_id));
    $religion_name = $religion_data->religion_name ?? 'Not specified';
    
    // Get caste name for display
    $caste_data = $commanmodel->get_single_query('castes', array('id' => $userdata->caste_id));
    $caste_name = $caste_data->caste_name ?? 'Not specified';
    
    // Partner preference religion and caste names
    $partner_religion_data = $commanmodel->get_single_query('religions', array('id' => $partner_preferences->religion_id ?? 0));
    $partner_religion_name = $partner_religion_data->religion_name ?? 'Not specified';
    
    $partner_caste_data = $commanmodel->get_single_query('castes', array('id' => $partner_preferences->caste_id ?? 0));
    $partner_caste_name = $partner_caste_data->caste_name ?? 'Not specified';
    
    // All related data
    $caste = $commanmodel->all_multiple_query_order_by('castes', array('religion_id' => $userdata->religion_id), 'id', 'ASC');
    $user_photos = $commanmodel->all_multiple_query_order_by('user_photos', array('user_id' => $id), 'id', 'ASC');
    $education_data = $commanmodel->get_single_query('user_education', array('user_id' => $id));
    $family_members = $commanmodel->all_multiple_query_order_by('user_family_members', array('user_id' => $id), 'id', 'ASC');
    $family_background = $commanmodel->get_single_query('user_family_background', array('user_id' => $id));
    $partner_preferences = $commanmodel->get_single_query('user_partner_preferences', array('user_id' => $id));
    $work_experience = $commanmodel->all_multiple_query_order_by('user_work_experience', array('user_id' => $id), 'id', 'ASC');
    
    $data = [
        'userdata' => $userdata,
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
        'partner_religion_name' => $partner_religion_name,
        'partner_caste_name' => $partner_caste_name,
        'id' => $id,
    ];
    
    return view('admin/head').view('admin/sidebar').view('admin/profile_view', $data).view('admin/footer');
}


// In your Admin Controller
public function update_membership()
{
    $session = session();
    
    // Get POST data
    $user_id = $this->request->getPost('user_id');
    $membership_plan = $this->request->getPost('membership_plan');
    $membership_price = $this->request->getPost('membership_price');
    $membership_start_date = $this->request->getPost('membership_start_date');
    $membership_end_date = $this->request->getPost('membership_end_date');
    $membership_status = $this->request->getPost('membership_status');
    $membership_duration = $this->request->getPost('membership_duration');
    $membership_notes = $this->request->getPost('membership_notes');
    
    // Validate input
    if (empty($user_id) || empty($membership_plan) || empty($membership_start_date) || empty($membership_end_date)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Missing required parameters'
        ]);
    }
    
    $db = \Config\Database::connect();
    
    // Get plan details
    $plan = $db->table('membership')
        ->where('membership_id', $membership_plan)
        ->get()
        ->getRow();
    
    $plan_name = $plan ? $plan->membership_name : 'Custom Plan';
   
    // Update user membership
    $builder = $db->table('user_account');
    $success = $builder->where('account_id', $user_id)
        ->update([
            'membership_id' => $membership_plan,
            'membership_name' => $plan_name,
            'membership_price' => $membership_price,
            'membership_month' => $membership_duration,
            'membership_start_date' => $membership_start_date,
            'membership_end_date' => $membership_end_date,
            'membership_status' => $membership_status,
           
        ]);
    
    if ($success) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Membership updated successfully'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update membership'
        ]);
    }
}
 public function updateBatchAdmin()
{
    $db = \Config\Database::connect();

    $accountId = $this->request->getPost("account_id");
    $batch     = $this->request->getPost("batch");

    $allowed = ["","Green", "Orange", "Blue"];

    if (!in_array($batch, $allowed)) {
        return $this->response->setJSON([
            "status" => "error",
            "message" => "Invalid Batch"
        ]);
    }

    $update = $db->table("user_account")
                 ->where("account_id", $accountId)
                 ->update([
                     "batch" => $batch,
                    
                 ]);

    if ($update) {
        return $this->response->setJSON([
            "status" => "success"
        ]);
    }

    return $this->response->setJSON([
        "status" => "error"
    ]);
}

  
public function customerlist()
{
    $session = session();
    $commanmodel = new Commanmodel();
    $db = \Config\Database::connect();

    $draw   = $this->request->getPost('draw');
    $start  = $this->request->getPost('start');
    $length = $this->request->getPost('length');
    
    // Simple 4 search parameters
    $search_type = $this->request->getPost('search_type'); // name, email, phone, id
    $search_keyword = $this->request->getPost('search_keyword');
    $status = $this->request->getPost('status');
    $approval = $this->request->getPost('approval');
    $membership = $this->request->getPost('membership');

    $builder = $db->table('user_account ua');

    // ================= SIMPLE SEARCH =================
    if (!empty($search_type) && !empty($search_keyword)) {
        switch($search_type) {
            case 'name':
                $builder->groupStart()
                    ->like('ua.user_name', $search_keyword)
                    ->orLike('ua.user_last_name', $search_keyword)
                    ->groupEnd();
                break;
            case 'email':
                $builder->like('ua.user_email', $search_keyword);
                break;
            case 'phone':
                $builder->like('ua.user_phone', $search_keyword);
                break;
            case 'id':
                $builder->where('ua.user_id', $search_keyword);
                break;
        }
    }

    // Status filter
    if (!empty($status)) {
        $builder->where('ua.user_status', $status);
    }

    // Approval filter
    if (!empty($approval)) {
        $builder->where('ua.approval', $approval);
    }
    
     if (!empty($membership)) {
        $builder->where('ua.membership_id', $membership);
    }



    // ================= TOTAL PENDING CALCULATION =================
    $builder->select("
        ua.*,
        (
            IFNULL((SELECT COUNT(*) FROM user_education ue WHERE ue.user_id = ua.account_id AND ue.status='pending'),0)
            +
            IFNULL((SELECT COUNT(*) FROM user_work_experience uw WHERE uw.user_id = ua.account_id AND uw.status='pending'),0)
            +
            IFNULL((SELECT COUNT(*) FROM user_family_members uf WHERE uf.user_id = ua.account_id AND uf.status='pending'),0)
            +
            IFNULL((SELECT COUNT(*) FROM user_partner_preferences up WHERE up.user_id = ua.account_id AND up.status='pending'),0)
            +
            IFNULL((SELECT COUNT(*) FROM user_photos ph WHERE ph.user_id = ua.account_id AND ph.status='pending'),0)
            +
            IF(ua.personal_status='pending',1,0)
            +
            IF(ua.verification_status='pending',1,0)
        ) as total_pending
    ");

    // Order by
    $builder->orderBy('total_pending', 'DESC');
    $builder->orderBy('ua.account_id', 'DESC');

    $totalRecords = $builder->countAllResults(false);

    $builder->limit($length, $start);
    $alldata = $builder->get()->getResult();

    $data = [];
    $sn = $start + 1;

    foreach ($alldata as $row) {
        $user_id = $row->account_id;

        // Image
        $image = '<img src="'.$commanmodel->profile_image($user_id).'" class="cat-thumb" style="width:50px; height:50px; border-radius:50%; object-fit:cover;" loading="lazy">';

        // Main Approval Status
        $statusColor = ($row->approval == 'Unapproved') ? 'danger' : 'success';
        $mainStatus = '<button class="btn btn-'.$statusColor.' btn-sm changeMainStatus"
            data-id="'.$user_id.'"
            data-status="'.($row->approval == 'Unapproved' ? 'Approved':'Unapproved').'">
            '.esc($row->approval).'
        </button>';
        
        
        
        
           $mainStatus .= '<br><button class="btn btn-success btn-sm changeMainStatus"
           >Membership :  '.esc($row->membership_name).' <br>
            '.esc($row->membership_status).'
        </button>';

        // Trending Status
        $trendingStatus = $row->trending_status ?? 'No';
        $trendingColor = ($trendingStatus == 'Yes') ? 'warning' : 'secondary';
        $trendingBtn = '<button class="btn btn-'.$trendingColor.' btn-sm changeTrendingStatus"
            data-id="'.$user_id.'"
            data-status="'.$trendingStatus.'">
            '.($trendingStatus == 'Yes' ? 'Trending':'Not Trending').'
        </button>';

        // Section Status
        $updateStatus = '<div class="status-badges">';

        // PERSONAL
        $personalStatus = $row->personal_status ?? 'approved';
        $personalClass = ($personalStatus == 'pending') ? 'bg-warning' :
                        (($personalStatus == 'rejected') ? 'bg-danger':'bg-success');
        $updateStatus .= '<span class="badge '.$personalClass.' status-badge"
            data-id="'.$user_id.'"
            data-type="personal"
            data-current-status="'.$personalStatus.'"
            style="cursor:pointer; margin:2px;">
            Personal: '.ucfirst($personalStatus).'
        </span>';

        // VERIFICATION
        $verificationStatus = $row->verification_status ?? 'approved';
        $verificationClass = ($verificationStatus == 'pending') ? 'bg-warning' :
                            (($verificationStatus == 'rejected') ? 'bg-danger':'bg-success');
        $updateStatus .= '<span class="badge '.$verificationClass.' status-badge"
            data-id="'.$user_id.'"
            data-type="verification"
            data-current-status="'.$verificationStatus.'"
            style="cursor:pointer; margin:2px;">
            Verification: '.ucfirst($verificationStatus).'
        </span>';

        // Check pending counts
        $educationPending = $db->table('user_education')->where('user_id',$user_id)->where('status','pending')->countAllResults();
        if ($educationPending > 0) {
            $updateStatus .= '<span class="badge bg-info status-badge" data-id="'.$user_id.'" data-type="education" data-current-status="pending">Education: '.$educationPending.' Pending</span>';
        }

        $workPending = $db->table('user_work_experience')->where('user_id',$user_id)->where('status','pending')->countAllResults();
        if ($workPending > 0) {
            $updateStatus .= '<span class="badge bg-info status-badge" data-id="'.$user_id.'" data-type="work" data-current-status="pending">Work: '.$workPending.' Pending</span>';
        }

        $familyPending = $db->table('user_family_members')->where('user_id',$user_id)->where('status','pending')->countAllResults();
        if ($familyPending > 0) {
            $updateStatus .= '<span class="badge bg-info status-badge" data-id="'.$user_id.'" data-type="family" data-current-status="pending">Family: '.$familyPending.' Pending</span>';
        }

        $partnerPending = $db->table('user_partner_preferences')->where('user_id',$user_id)->where('status','pending')->countAllResults();
        if ($partnerPending > 0) {
            $updateStatus .= '<span class="badge bg-info status-badge" data-id="'.$user_id.'" data-type="partner" data-current-status="pending">Partner: '.$partnerPending.' Pending</span>';
        }

        $photoPending = $db->table('user_photos')->where('user_id',$user_id)->where('status','pending')->countAllResults();
        if ($photoPending > 0) {
            $updateStatus .= '<span class="badge bg-danger status-badge" data-id="'.$user_id.'" data-type="photo" data-current-status="pending">Photo: '.$photoPending.' Pending</span>';
        }

        $updateStatus .= '</div>';

        if ($row->total_pending == 0) {
            $updateStatus = '<span class="badge bg-success">All Approved</span>';
        }

        // User Info
        $info = '<strong>'.esc($row->user_name).' '.esc($row->user_last_name).'</strong><br>
            <i class="mdi mdi-email"></i> '.esc($row->user_email).'<br>
            <i class="mdi mdi-phone"></i> '.esc($row->user_phone).'<br>
            <i class="mdi mdi-cake"></i> '.esc($row->date_of_birth).'<br>
            <i class="mdi mdi-account"></i> Gender: '.esc($row->gender).'<br>
            <small class="text-muted">ID: '.esc($row->user_id).'</small>';

        $data[] = [
            "id"     => $sn,
            "image"  => $image,
            "info"   => $info,
            "status" => $mainStatus.'<br><div class="mt-2">'.$trendingBtn.'</div>',
            "action" => '<a href="'.base_url('admin/profile_view/'.$user_id).'" class="btn btn-primary btn-sm">View</a>
                        <br><div class="mt-2">'.$updateStatus.'</div>',
        ];

        $sn++;
    }

    return $this->response->setJSON([
        "draw"            => intval($draw),
        "recordsTotal"    => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data"            => $data,
    ]);
}
public function change_trending_status()
{
    $db = \Config\Database::connect();
    $user_id = $this->request->getPost('user_id');
    $currentStatus = $this->request->getPost('status');

    $newStatus = ($currentStatus == 'Yes') ? 'No' : 'Yes';

    $db->table('user_account')
        ->where('account_id', $user_id)
        ->update(['trending_status' => $newStatus]);

    return $this->response->setJSON([
        'success' => true,
        'newStatus' => $newStatus
    ]);
}
 public function approve_delete_request()
{
    
    $db = \Config\Database::connect();
    
    
    try {

        $request_id = $this->request->getPost('request_id');

        if (empty($request_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Request ID missing'
            ]);
        }

        $db->transBegin();

        // Get delete request
        $deleteRequest = $db->table('account_delete_requests')
            ->where('id', $request_id)
            ->get()
            ->getRow();

        if (!$deleteRequest) {
            throw new \Exception('Delete request not found');
        }

        // Get user
        $user = $db->table('user_account')
            ->where('account_id', $deleteRequest->user_id)
            ->get()
            ->getRow();

        if (!$user) {
            throw new \Exception('User not found');
        }

        $accountId = $user->account_id;

        // Update request status
        $db->table('account_delete_requests')
            ->where('id', $request_id)
            ->update([
                'status' => 'approved',
                'processed_at' => date('Y-m-d H:i:s')
            ]);

        // Delete profile photo
        if (!empty($user->user_photo)) {

            $path = FCPATH . 'assets/uploads/' . $user->user_photo;

            if (is_file($path)) {
                unlink($path);
            }
        }

        // Delete document
        if (!empty($user->documents)) {

            $path = FCPATH . 'assets/uploads/' . $user->documents;

            if (is_file($path)) {
                unlink($path);
            }
        }

        // Delete gallery photos
        
        
        
        $photos = $db->table('user_photos')
            ->where('user_id', $accountId)
            ->get()
            ->getResult();

        foreach ($photos as $photo) {

            if (!empty($photo->photo_path)) {

                $path = FCPATH . 'assets/uploads/' . $photo->photo_path;

                if (is_file($path)) {
                    unlink($path);
                }
            }
        }

        // Delete child tables
        $db->table('user_photos')
            ->where('user_id', $accountId)
            ->delete();

        $db->table('user_education')
            ->where('user_id', $accountId)
            ->delete();

        $db->table('user_partner_preferences')
            ->where('user_id', $accountId)
            ->delete();

        // Delete delete-request record also
        $db->table('account_delete_requests')
            ->where('user_id', $accountId)
            ->delete();

        // Delete main account
        $db->table('user_account')
            ->where('account_id', $accountId)
            ->delete();

        if ($db->transStatus() === false) {

            $error = $db->error();

            throw new \Exception(
                'DB Error : ' .
                ($error['message'] ?? 'Unknown')
            );
        }

        $db->transCommit();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Account deleted successfully'
        ]);

    } catch (\Throwable $e) {

        $db->transRollback();

        log_message('error', $e->getMessage());

        return $this->response->setJSON([
            'success' => false,
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
}

    /**
     * Reject delete request
     */
    public function reject_delete_request()
    {
        // Check if admin is logged in
        if (!$this->session->get('isAdminLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $request_id = $this->request->getPost('request_id');
        $user_id = $this->request->getPost('user_id');

        if (!$request_id || !$user_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request parameters'
            ]);
        }

        try {
            // Update delete request status to rejected
            $db->table('account_delete_requests')
                ->where('id', $request_id)
                ->update([
                    'status' => 'rejected',
                    'processed_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            // Log the rejection
            $logData = [
                'admin_id' => $this->session->get('admin_id'),
                'user_id' => $user_id,
                'action' => 'reject_delete_request',
                'ip_address' => $this->request->getIPAddress(),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $db->table('admin_activity_logs')->insert($logData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Delete request has been rejected successfully.'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Reject delete request error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error rejecting request: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Direct permanent delete (without request)
     */
    public function permanent_delete_user($user_id = null)
    {
        // Check if admin is logged in
        if (!$this->session->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/login')->with('error', 'Please login first');
        }

        if (!$user_id) {
            return redirect()->back()->with('error', 'Invalid user ID');
        }

        // Begin transaction
        $db->transStart();

        try {
            // Get user details
            $user = $db->table('accounts')
                ->where('account_id', $user_id)
                ->get()
                ->getRow();

            if (!$user) {
                throw new \Exception('User not found');
            }

            // Check if there's a pending delete request
            $pendingRequest = $db->table('account_delete_requests')
                ->where('user_id', $user_id)
                ->where('status', 'pending')
                ->get()
                ->getRow();

            if ($pendingRequest) {
                // Update the pending request to approved
                $db->table('account_delete_requests')
                    ->where('id', $pendingRequest->id)
                    ->update([
                        'status' => 'approved',
                        'processed_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }

            // Delete user photos from server
            $photos = $db->table('user_photos')
                ->where('user_id', $user_id)
                ->get()
                ->getResult();

            foreach ($photos as $photo) {
                if (!empty($photo->photo_path)) {
                    $filePath = FCPATH . 'assets/uploads/' . $photo->photo_path;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // Delete user document
            if (!empty($user->documents)) {
                $docPath = FCPATH . 'assets/uploads/' . $user->documents;
                if (file_exists($docPath)) {
                    unlink($docPath);
                }
            }

            // Define all related tables to delete from
            $tables = [
                'user_photos',
                'user_education',
                'user_work_experience',
                'user_family_members',
                'user_family_background',
                'user_partner_preferences',
                'user_interests' => ['user_id', 'interested_user_id'],
                'user_messages' => ['sender_id', 'receiver_id'],
                'user_notifications',
                'user_login_history',
                'user_activity_logs',
                'user_membership_history',
                'payment_transactions',
                'blocked_users' => ['user_id', 'blocked_user_id'],
                'reported_users' => ['reporter_id', 'reported_user_id'],
                'account_delete_requests',
                'accounts' // Main table last
            ];

            // Delete from all tables
            foreach ($tables as $key => $value) {
                if (is_array($value)) {
                    // Table with multiple possible user fields
                    $table = $key;
                    $fields = $value;
                    foreach ($fields as $field) {
                        $db->table($table)->where($field, $user_id)->delete();
                    }
                } else {
                    // Simple table with user_id field
                    $table = $value;
                    $db->table($table)->where('user_id', $user_id)->delete();
                }
            }

            // Log the deletion
            $logData = [
                'admin_id' => $this->session->get('admin_id'),
                'user_id' => $user_id,
                'user_email' => $user->user_email ?? '',
                'user_name' => $user->user_name . ' ' . ($user->user_last_name ?? ''),
                'action' => 'permanent_delete',
                'reason' => 'Admin initiated permanent deletion',
                'ip_address' => $this->request->getIPAddress(),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $db->table('admin_activity_logs')->insert($logData);

            // Commit transaction
            $db->transCommit();

            return redirect()->to('/admin/customer')
                ->with('success', 'User account has been permanently deleted successfully.');

        } catch (\Exception $e) {
            // Rollback transaction
            $db->transRollback();
            
            log_message('error', 'Permanent delete error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

public function update_all_statuses()
{
    $session = session();
    $commanmodel = new Commanmodel();
    
    // Get POST data
    $user_id = $this->request->getPost('user_id');
    $status_type = $this->request->getPost('status_type'); // personal, verification, education, work, family, partner, photo
    $new_status = $this->request->getPost('new_status'); // approved, rejected, pending
    
    // Validate input
    if (empty($user_id) || empty($status_type) || empty($new_status)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Missing required parameters'
        ]);
    }
    
    $db = \Config\Database::connect();
    $success = false;
    $message = '';
    
    // Update based on status type
    switch ($status_type) {
        case 'personal':
            // Update personal status in user_account table
            $builder = $db->table('user_account');
            $success = $builder->where('account_id', $user_id)
                              ->update(['personal_status' => $new_status]);
            $message = 'Personal status updated successfully';
            break;
        case 'verified':
            // Update verification status in user_account table
            $builder = $db->table('user_account');
            $success = $builder->where('account_id', $user_id)
                              ->update(['verified' => $new_status]);
            $message = 'Verification status updated successfully';
            break;
            
        case 'verification_status':
            // Update verification status in user_account table
            $builder = $db->table('user_account');
            $success = $builder->where('account_id', $user_id)
                              ->update(['verification_status' => $new_status]);
            $message = 'Verification status updated successfully';
            break;
            
        case 'education':
            // Update all pending education records for this user
            $builder = $db->table('user_education');
            $success = $builder->where('user_id', $user_id)
                              ->where('status', 'pending')
                              ->update(['status' => $new_status]);
            $message = 'Education status updated successfully';
            break;
            
        case 'work':
            // Update all pending work records for this user
            $builder = $db->table('user_work_experience');
            $success = $builder->where('user_id', $user_id)
                              ->where('status', 'pending')
                              ->update(['status' => $new_status]);
            $message = 'Work status updated successfully';
            break;
            
        case 'family':
            // Update all pending family records for this user
            $builder = $db->table('user_family_members');
            $success = $builder->where('user_id', $user_id)
                              ->where('status', 'pending')
                              ->update(['status' => $new_status]);
            $message = 'Family status updated successfully';
            break;
            
        case 'partner':
            // Update all pending partner preferences for this user
            $builder = $db->table('user_partner_preferences');
            $success = $builder->where('user_id', $user_id)
                              ->where('status', 'pending')
                              ->update(['status' => $new_status]);
            $message = 'Partner preferences status updated successfully';
            break;
            
        case 'photo':
            // Update all pending photo records for this user
            $builder = $db->table('user_photos');
            $success = $builder->where('user_id', $user_id)
                              ->where('status', 'pending')
                              ->update(['status' => $new_status]);
            $message = 'Photo status updated successfully';
            break;
            
        default:
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid status type'
            ]);
    }
    
    if ($success) {
        return $this->response->setJSON([
            'success' => true,
            'message' => $message,
            'new_status' => $new_status,
            'status_type' => $status_type
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update status'
        ]);
    }
}



 public function reviews()
    {$commanmodel = new Commanmodel();
        $session = session();
       if(session()->get('admin_type')=='Supar Admin') {
           
             $data['table_name'] = '';
             
             
         
    
            $table_header = [
                
            ['data' => 'id'],
            ['data' => 'info'],
            ['data' => 'product'],
            ['data' => 'rating'],
            ['data' => 'messages'],
           
            ['data' => 'action'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
       
       return view('admin/head').view('admin/sidebar').view('admin/reviews',$data).view('admin/footer');
       } else {
            
            return redirect()->back()->withInput();
       }
       
    } 
        public function reviews_list()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input


// Define filters based on your requirements
$filters = [];



// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'reviews_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('reviews', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {

$products = $commanmodel->get_single_query('product',array('product_id'=> $alldata_view->product_id));

if($products) {
   $product = $products->product_name; 
} else {
    $product = '';
}





$action = '<div class="btn-group">
															<button type="button"
																class="btn btn-outline-'.$alldata_view->reviews_status_color.'">'.$alldata_view->reviews_status.'</button>
															<button type="button"
																class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
																data-bs-toggle="dropdown" aria-haspopup="true"
																aria-expanded="false" data-display="static">
																<span class="sr-only">Info</span>
															</button>
															<div class="dropdown-menu">';
															
													if($alldata_view->reviews_status =='Active') {		
										$action .= '<a class="dropdown-item editRecordreviews" href="javascript:void(0);"  data-reviews_id="'.$alldata_view->reviews_id.'" data-status="Inactive" >Inactive</a>';
													} else {
													    $action .= '<a class="dropdown-item editRecordreviews" href="javascript:void(0);"  data-reviews_id="'.$alldata_view->reviews_id.'" data-status="Active"  >Active</a>';
													}
															
														$action .= '	</div>
														</div>';


     

$data[] = [
    "id" => $sn,
    "info" => 'Name : '.$alldata_view->user_name.'<br>Email : '.$alldata_view->user_email.'<br>Date : '.$alldata_view->review_date,
    "product" => $product,
    "rating" => $alldata_view->rating,
    "messages" => $alldata_view->message,
   
    "action" => $action 
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}
    
   function reviews_update(){
             $session = session();
       
             helper(['form', 'url']);
               $commanmodel = new Commanmodel();
         $status = $this->request->getVar('status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         } 
         
       
        $data = array(  
    
      
        'reviews_status' => $this->request->getVar('status'), 
        'reviews_status_color' => $status_color,
    
       
        );
        $where = array(             
        'reviews_id' => $this->request->getVar('reviews_id')
            );
        $updated=$commanmodel->update_query('reviews',$data,$where);
        echo json_encode($updated);
     
    }  
    
    
     public function transactions()
    { 
        $commanmodel = new Commanmodel();
        $session = session();
       if(session()->get('admin_type')=='Supar Admin') {
           
             $data['table_name'] = '';
             
             
    
    
            $table_header = [
                
            ['data' => 'id'],
            ['data' => 'customer_name'],
            ['data' => 'seller_name'],
            ['data' => 'date'],
            ['data' => 'amount'],
           
            ['data' => 'method'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
       
       return view('admin/head').view('admin/sidebar').view('admin/transactions',$data).view('admin/footer');
       } else {
            
            return redirect()->back()->withInput();
       }
       
    } 
        public function transactions_list()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input


// Define filters based on your requirements
$filters = [];



// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'booking_product_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('booking_product', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {


$order = $commanmodel->get_single_query('order_book',array('order_book_id'=> $alldata_view->booking_product_order_book_id));


$admin = $commanmodel->get_single_query('admin',array('id'=> $alldata_view->booking_product_vender));






$data[] = [
    "id" => $sn,
    "customer_name" =>$order->order_book_user_name,
    "seller_name" => $admin->name,
    "date" => $order->order_book_date,
     "amount" => $alldata_view->booking_product_sub_total,
    "method" => $order->order_book_pay_type,
   
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}


   public function shipcharge()
    {$commanmodel = new Commanmodel();
        $session = session();
       if(session()->get('admin_type')=='Supar Admin') {
           
             $data['table_name'] = '';
             
             
         
    
            $table_header = [
                
            ['data' => 'id'],
        
            ['data' => 'shipcharge'],
            ['data' => 'pin'],
           
            ['data' => 'action'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
       
       return view('admin/head').view('admin/sidebar').view('admin/shipcharge',$data).view('admin/footer');
       } else {
            
            return redirect()->back()->withInput();
       }
       
    } 
        public function shipcharge_list()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input


// Define filters based on your requirements
$filters = [];

// Add search filter if a search term is provided
// if (!empty($searchname)) {
// $filters[] = [
//     'column' => 'product_title',
//     'value' => $searchname,
//     'type' => 'like',
// ];
// }






// if (session()->get('admin_type')=='Admin') { 
//     $filters[] = [
//     'column' => 'product_create_by',
//     'value' => session()->get('id'),
//     'type' => 'where',
// ];
// }

// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'shipcharge_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('shipcharge', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {




$action = '<div class="btn-group">
															<button type="button"
																class="btn btn-outline-'.$alldata_view->shipcharge_status_color.'">'.$alldata_view->shipcharge_status.'</button>
															<button type="button"
																class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
																data-bs-toggle="dropdown" aria-haspopup="true"
																aria-expanded="false" data-display="static">
																<span class="sr-only">Info</span>
															</button>
															<div class="dropdown-menu">
																<a class="dropdown-item editRecordshipcharge" href="javascript:void(0);"  data-shipcharge_id="'.$alldata_view->shipcharge_id.'" data-shipcharge="'.$alldata_view->shipcharge.'" data-shipcharge_pin="'.$alldata_view->shipcharge_pin.'" data-shipcharge_status="'.$alldata_view->shipcharge_status.'" >Edit</a>
																<a class="dropdown-item" href="#">Delete</a>
															</div>
														</div>';


      

$data[] = [
    "id" => $sn,
   
    "shipcharge" => $alldata_view->shipcharge,
    "pin" => $alldata_view->shipcharge_pin,
   
    "action" => $action 
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}
    
    
    
        function shipcharge_save(){
           $session = session();
      
             $commanmodel = new Commanmodel();
         $status = $this->request->getVar('shipcharge_status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         }
         
        
        
      
    
        $data = array( 
        
        'shipcharge' => $this->request->getVar('shipcharge'), 
         'shipcharge_pin' => $this->request->getVar('shipcharge_pin'), 
         
        'shipcharge_status' => $this->request->getVar('shipcharge_status'), 
        'shipcharge_status_color' => $status_color,
      
       
            );
        $Inserted=$commanmodel->insert_query('shipcharge',$data);
        echo json_encode($Inserted);
     
    }
        function shipcharge_update(){
             $session = session();
       
             helper(['form', 'url']);
               $commanmodel = new Commanmodel();
         $status = $this->request->getVar('edit_shipcharge_status');
         if($status=='Active')
         {
           $status_color = 'success';
         }  
         if($status=='Inactive')
         {
           $status_color = 'danger';
         } 
         
         
             


        $data = array(  
             
        'shipcharge' => $this->request->getVar('edit_shipcharge'), 
       'shipcharge_pin' => $this->request->getVar('edit_shipcharge_pin'), 
        'shipcharge_status' => $this->request->getVar('edit_shipcharge_status'), 
        'shipcharge_status_color' => $status_color,
     
       
        );
        $where = array(             
        'shipcharge_id' => $this->request->getVar('edit_shipcharge_id')
            );
        $updated=$commanmodel->update_query('shipcharge',$data,$where);
        echo json_encode($updated);
     
    }
    
    
      public function commission()
{
     $session = session();
 
      $commanmodel = new Commanmodel();


        $data['table_name'] = 'attributes';
    
            $table_header = [
                
            ['data' => 'id'],
             ['data' => 'img'],
              ['data' => 'item'],
             
               ['data' => 'price'],
               
                ['data' => 'refer_by'],
               ['data' => 'commission'],
          
      
           
        
        ];
        
        $data['table_column'] = json_encode($table_header);
    
 
   return view('admin/head').view('admin/sidebar').view('admin/commission', $data).view('admin/footer');

}


  public function commissionlist()
{
    
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input

// Define filters based on your requirements
$filters = [];
$arrayfilte = [];



if (session()->get('admin_type')=='Vendor') { 
    $filters[] = [
    'column' => 'booking_product_vender',
    'value' => session()->get('id'),
    'type' => 'where',
];
}

if($session->admin_type == 'Franchise' || $session->admin_type == 'Promoter') {
   $filters[] = [
    'column' => 'booking_product_referral_code',
    'value' => $session->referral_code,
    'type' => 'where',
]; 
}


// Add search filter if a search term is provided
if (!empty($searchname)) {
$filters[] = [
    'column' => 'booking_product_product_name',
    'value' => $searchname,
    'type' => 'like',
];
}



// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'booking_product_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}



$arrayfilte[] = [
    'column' => 'booking_product_status',
    'value' => ['success','delivered','cancelled'],
    'type' => 'where',
];


// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('booking_product', $filters, $order, $length, $start,$arrayfilte);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {


$vender = $commanmodel->get_single_query('admin',array('id'=> $alldata_view->booking_product_vender));

$referby = $commanmodel->get_single_query('admin',array('referral_code'=> $alldata_view->booking_product_referral_code));

$refername = '';
$refercode = '';
if($referby) {
    $refername = $referby->name;
$refercode =  $referby->referral_code;
}

$category = '';
 $order = $commanmodel->get_single_query('order_book',array('order_book_id'=> $alldata_view->booking_product_order_book_id));
  $product = $commanmodel->get_single_query('product',array('product_id'=> $alldata_view->booking_product_product_id));
   $seller = $commanmodel->get_single_query('admin',array('id'=> $alldata_view->booking_product_vender));
  
  if($product) {
       $category = $this->getnamecategory($product->product_category);
  }
  
$user = $commanmodel->get_single_query('user_account',array('account_id'=> $order->order_book_user_id));
$images = '<img class="product-img tbl-img" src="'.$alldata_view->booking_product_image.'" >'; 

if($user) {
  $order_type = 'Verifide Order';  
} else {
   $order_type = 'Guest Order';  
}



$action = '<div class="btn-group mb-1">
															<button type="button" class="btn btn-outline-success">'.$order_type.'</button>
															<button type="button" class="btn btn-outline-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
																<span class="sr-only">Info</span>
															</button>

															<div class="dropdown-menu" style="">
																<a class="dropdown-item" href="'.base_url().'/admin/order_details/'.$alldata_view->booking_product_order_id .'">Order detail</a>
																<a class="dropdown-item" href="#">Order Cancel</a>
														</div>
														</div>';
														
													$calculatecommition =	$this->calculatecommition($refercode,$alldata_view->booking_product_price);
                                                    $getnamebyreferralcode =	$this->getnamebyreferralcode($refercode);
$data[] = [
    "id" => $alldata_view->booking_product_order_id,
   "img" => $images,
    "item" => $alldata_view->booking_product_product_name,
    "category" => $category,
    "price" =>  $alldata_view->booking_product_price,
    'refer_by' => 'Franchise : '.$getnamebyreferralcode['franchise_name'].' <br> Promoter : '.$getnamebyreferralcode['promoter_name'],
    "commission" => 'Franchise : '.$calculatecommition['franchise_amount'].' <br> Promoter : '.$calculatecommition['promoter_amount'],
  
   
   
 
  
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}


private function getnamebyreferralcode($referralcode) {
    $commanmodel = new Commanmodel();
    
     $franchisenamet = '';
    $promotername = '';
     $referralby = $commanmodel->get_single_query('admin', array('referral_code' => $referralcode));
     
     if($referralby) {
        if ($referralby->admin_type == 'Franchise') {
             $franchisenamet = $referralby->name.' Code: '.$referralby->referral_code;
             
         } else {
              $referralbymain = $commanmodel->get_single_query('admin', array('referral_code' => $referralby->refer_by));
               $franchisenamet = $referralbymain->name.' Code: '.$referralbymain->referral_code;
               $promotername = $referralby->name.' Code: '.$referralby->referral_code;
               
         }
     }
    
     return [
        'franchise_name' => $franchisenamet,
        'promoter_name' => $promotername
    ];
}
    
    



private function calculatecommition($referralcode, $price) {
    $commanmodel = new Commanmodel();
    
    // Get settings that contain commission percentages
    $setting = $commanmodel->get_single_query('address', array('id' => 1)); 
    
    // Get the admin who referred using the referral code
    $referralby = $commanmodel->get_single_query('admin', array('referral_code' => $referralcode));
    
    // Initialize commission values
    $franchiseamount = 0;
    $promoteramount = 0;
    
    if ($referralby) {
        if ($referralby->admin_type == 'Franchise') {
            // Direct franchise commission
            $franchisepercentage = $setting->franchise_direct_commission;
            $franchiseamount = ($price * $franchisepercentage) / 100;
        } else {
            // Check if promoter was referred by a franchise
            $referralbymain = $commanmodel->get_single_query('admin', array('referral_code' => $referralby->refer_by));
            
            if ($referralbymain && $referralbymain->admin_type == 'Franchise') {
                // Indirect franchise + indirect promoter
                $franchisepercentage = $setting->franchise_indirect_commission;
                $promoterpercentage = $setting->promoter_indirect_commission;
                $franchiseamount = ($price * $franchisepercentage) / 100;
                $promoteramount = ($price * $promoterpercentage) / 100;
            } else {
                // Direct promoter commission only
                $promoterpercentage = $setting->promoter_direct_commission;
                $promoteramount = ($price * $promoterpercentage) / 100;
            }
        }
    }
    
    // Return computed amounts in an array
    return [
        'franchise_amount' => $franchiseamount,
        'promoter_amount' => $promoteramount
    ];
}
private function transfercommition($referralcode, $price, $orderId) {
    $commanmodel = new Commanmodel();

    // Get settings
    $setting = $commanmodel->get_single_query('address', ['id' => 1]);

    // Get admin by referral code
    $referralby = $commanmodel->get_single_query('admin', ['referral_code' => $referralcode]);

    if (!$referralby) {
        return; // Stop if referral code is invalid
    }

    $franchiseId = null;
    $promoterId = null;
    $franchiseamount = 0;
    $promoteramount = 0;

    // Commission distribution logic
    if ($referralby->admin_type == 'Franchise') {
        $franchiseId = $referralby->id;
        $franchiseamount = ($price * $setting->franchise_direct_commission) / 100;
    } else {
        $referralbymain = $commanmodel->get_single_query('admin', ['referral_code' => $referralby->refer_by]);

        if ($referralbymain && $referralbymain->admin_type == 'Franchise') {
            $franchiseId = $referralbymain->id;
            $promoterId = $referralby->id;
            $franchiseamount = ($price * $setting->franchise_indirect_commission) / 100;
            $promoteramount = ($price * $setting->promoter_indirect_commission) / 100;
        } else {
            $promoterId = $referralby->id;
            $promoteramount = ($price * $setting->promoter_direct_commission) / 100;
        }
    }

    // Credit to Franchise
    if ($franchiseId) {
        $franchise = $commanmodel->get_single_query('admin', ['id' => $franchiseId]);

        $commanmodel->insert_query('commission_txn', [
            'commission_txn_amount' => $franchiseamount,
            'commission_txn_order_id' => $orderId,
            'commission_txn_date' => date('Y-m-d H:i:s'),
            'commission_txn_userid' => $franchiseId,
        ]);

        $commanmodel->update_query('admin', [
            'wallet' => $franchise->wallet + $franchiseamount,
        ], ['id' => $franchiseId]);
    }

    // Credit to Promoter
    if ($promoterId) {
        $promoter = $commanmodel->get_single_query('admin', ['id' => $promoterId]);

        $commanmodel->insert_query('commission_txn', [
            'commission_txn_amount' => $promoteramount,
            'commission_txn_order_id' => $orderId,
            'commission_txn_date' => date('Y-m-d H:i:s'),
            'commission_txn_userid' => $promoterId,
        ]);

        $commanmodel->update_query('admin', [
            'wallet' => $promoter->wallet + $promoteramount,
        ], ['id' => $promoterId]);
    }
}


   public function wallet()
    { 
        $commanmodel = new Commanmodel();
        $session = session();
       if($session->admin_type == 'Franchise' || $session->admin_type == 'Promoter' || $session->admin_type == 'Supar Admin') {
            $data['userdetails'] =  $commanmodel->get_single_query('admin',array('id' =>  $session->id));
             $data['table_name'] = '';
             
             
    
    
            $table_header = [
                
            ['data' => 'id'],
            ['data' => 'pro_name'],
            ['data' => 'amount'],
            ['data' => 'date'],
            ['data' => 'users'],
        
        ];
        
        $data['table_column'] = json_encode($table_header);
       
       return view('admin/head').view('admin/sidebar').view('admin/wallet',$data).view('admin/footer');
       } else {
            
            return redirect()->back()->withInput();
       }
       
    } 
        public function wallet_list()
{
$session = session();
$commanmodel = new Commanmodel();


// Define your DataTables parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchname = $_POST['searchname']; // Change this to your search input


// Define filters based on your requirements
$filters = [];

if($session->admin_type == 'Franchise' || $session->admin_type == 'Promoter') {
   $filters[] = [
    'column' => 'commission_txn_userid',
    'value' => $session->id,
    'type' => 'where',
]; 
}

// Define ordering parameters
$order = null; // Set this based on your DataTables ordering requirements
if (!empty($_POST['order'])) {
$orderColumn = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
// Define the column name you want to order by based on $orderColumn
$order = [
    'column' => 'commission_txn_id', // Change this to your desired default order column
    'order' => 'DESC', // Change this to your desired default order direction
];
}

// Retrieve data using the getDataFromTable function
$result = $commanmodel->getDataFromTable('commission_txn', $filters, $order, $length, $start);

// Process the retrieved data
$alldata = $result['filteredRecords'];
$data = [];
$no = $start + 1;
$sn = 1;
foreach ($alldata as $alldata_view) {


$order = $commanmodel->get_single_query('booking_product',array('booking_product_order_id'=> $alldata_view->commission_txn_order_id));


$product = $commanmodel->get_single_query('product',array('product_id'=> $order->booking_product_product_id));



$admin = $commanmodel->get_single_query('admin',array('id' =>  $alldata_view->commission_txn_userid));


$data[] = [
    "id" =>  $alldata_view->commission_txn_order_id,
    "pro_name" =>$product->product_name,

   
     "amount" => $alldata_view->commission_txn_amount,
      "date" => $alldata_view->commission_txn_date,
  "users" => $admin->name.'<br> Code '.$admin->referral_code,
   
];

$no++;

$sn++;
}

// Prepare the DataTables response
$response = [
"draw" => intval($draw),
"recordsTotal" => $result['filteredRecordCount'] ,
"recordsFiltered" => $result['totalRecords'],
"data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

            


}


}