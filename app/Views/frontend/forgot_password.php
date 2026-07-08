 <div class="ltn__login-area pb-65 pt-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">Forgot Password</h1>
                       
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                
                <!--Form Column-->
                <div class="form-column column col-lg-12 col-md-12 col-sm-12">
                
                    <div class="sec-title">
                        <!--<h2>Forgot Password</h2>-->
						<div class="separate"></div>
                    </div>
                    
                    <!--Login Form-->
                    <div class="styled-form login-form">
                        <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message'); ?>
    </div>
<?php endif; ?>
 
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>
                        
                           <form action="<?= site_url('auth/sendResetLink') ?>" method="post">
                        
                      
                        
                        
                        <div class="form-group">
                            <input type="text" placeholder="Email Address"  name="email" required="">
                              <p id="email_error" class="errors"></p>
                        </div>
                   
                     
                        <div class="clearfix">
                                <div class="form-group pull-left">
                                     
                                    <button type="submit" class="theme-btn btn-style-three"><span class="txt">Send Reset Link</span></button>
                                </div>
                               
                            </div>
                        
                    </form>
                       
                    </div>
                    
                </div>
                
              
                
            </div>
      </div>
   </div>