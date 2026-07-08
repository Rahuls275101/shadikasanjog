<?php namespace App\Filters;
 
 use CodeIgniter\Filters\FilterInterface;
 use CodeIgniter\HTTP\RequestInterface;
 use CodeIgniter\HTTP\ResponseInterface;
 use App\Models\Commanmodel;
 class AuthGuard implements FilterInterface
 {
     public function before(RequestInterface $request, $arguments = null)
     {
         /* if user not logged in */
         if(! session()->get('isLoggedIn')){
             /* then redirct to login page */
              $session = session();
             $commanmodel = new Commanmodel();
               
         
             
                
             $session->setFlashdata('login_failed', 'Access Denied');
             return redirect()->to('/admin'); 
         } else {
                $session = session();
             $commanmodel = new Commanmodel();
               
                
                 $ses_data = [
                        'id' => $session->id,
                      'referral_code' => $session->referral_code,
                         'name' => $session->name,
                        'email' => $session->email,
                        'name' => $session->name,
                        'position' => $session->position, 
                        'admin_type' => $session->admin_type,
                        
                        'isLoggedIn' => TRUE
                    ];
                    $session->set($ses_data);
               
         }
     }
  
     /*--------------------------------------------------------------------*/
  
     public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
     {
         /* Do something here */
     }
 }