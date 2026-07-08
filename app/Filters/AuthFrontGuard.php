<?php namespace App\Filters;
 
 use CodeIgniter\Filters\FilterInterface;
 use CodeIgniter\HTTP\RequestInterface;
 use CodeIgniter\HTTP\ResponseInterface;
 use App\Models\Commanmodel;
 class AuthFrontGuard implements FilterInterface
 {
     public function before(RequestInterface $request, $arguments = null)
     {
         /* if user not logged in */
         if(! session()->get('loggedin')){
             /* then redirct to login page */
              $session = session();
             $commanmodel = new Commanmodel();
               
         
              session()->set('redirect_url', current_url());
                
             $session->setFlashdata('login_failed', 'Access Denied');
             return redirect()->to('/login'); 
         } else {
              
               
         }
     }
  
     /*--------------------------------------------------------------------*/
  
     public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
     {
         /* Do something here */
     }
 }