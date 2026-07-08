 <?php 
use App\Models\Commanmodel;
 $commanmodel = new Commanmodel();
  $session = session();
  $usersession = $session->get('loggedin');
     $userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
?>

<div class="db-nav">
                            <div class="db-nav-pro">
                                
                               
                                        <img src="<?php echo $commanmodel->profile_image($usersession['user_id']); ?>" class="img-fluid"  loading="lazy" alt="Profile Picture">
                                   
                                    <!-- Name + ID Box -->
    <div class="profile-info">
        <i class="fa fa-user"></i>
        <div>
            <h4><?php echo $userdata->user_name ?? ''; ?> <?php echo $userdata->user_last_name ?? ''; ?></h4>
            <p><?php echo $userdata->user_id ?? ''; ?></p>
        </div>
    </div>
                          
                                
                                </div>
                            <div class="db-nav-list">
                                <ul>
                                   <li><a href="<?php echo base_url('dashboard'); ?>" 
       class="<?php echo (current_url() == base_url('dashboard')) ? 'act' : ''; ?>">
       <i class="fa fa-tachometer" aria-hidden="true"></i>Dashboard</a></li>

<li><a href="<?php echo base_url('user-profile'); ?>" 
       class="<?php echo (current_url() == base_url('user-profile')) ? 'act' : ''; ?>">
       <i class="fa fa-male" aria-hidden="true"></i>Update Profile</a></li>

<li><a href="<?php echo base_url('user-plans'); ?>" 
       class="<?php echo (current_url() == base_url('user-plans')) ? 'act' : ''; ?>">
       <i class="fa fa-money" aria-hidden="true"></i>Manage Plan</a></li>
       
    <!--  <li><a href="<?php echo base_url('user-interests'); ?>" 
       class="<?php echo (current_url() == base_url('user-interests')) ? 'act' : ''; ?>">
       <i class="fa fa-money" aria-hidden="true"></i>Interests</a></li>-->
       
       
           
      <li><a href="<?php echo base_url('user-chat'); ?>" 
       class="<?php echo (current_url() == base_url('user-chat')) ? 'act' : ''; ?>">
       <i class="fa fa-money" aria-hidden="true"></i>Chat</a></li>
       
       <li><a href="<?php echo base_url('profile'); ?>" 
       class="<?php echo (current_url() == base_url('profile')) ? 'act' : ''; ?>">
       <i class="fa fa-money" aria-hidden="true"></i>Search</a></li>

<li><a href="<?php echo base_url('logout'); ?>" 
       class="<?php echo (current_url() == base_url('logout')) ? 'act' : ''; ?>">
       <i class="fa fa-sign-out" aria-hidden="true"></i>Log out</a></li>
       
       <li><a href="<?php echo base_url('delete-account'); ?>" 
       class="<?php echo (current_url() == base_url('delete-account')) ? 'act' : ''; ?>">
       <i class="fa fa-trash" aria-hidden="true"></i>Delete Account</a></li>

                                </ul>
                            </div>
                        </div>
                        
                        
                        <style>
                            /* Info Box */
.profile-info {
    background: #c92c63;
    color: #fff;
    padding: 10px 15px;
    display: flex;
    /*align-items: center;*/
    /*justify-content: center;*/
    gap: 10px;
}

/* Icon */
.profile-info i {
    font-size: 18px;
}

/* Text */
.profile-info h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
}

.profile-info p {
    margin: 0;
    font-size: 13px;
    opacity: 0.9;
    color: #fff;
}
                        </style>