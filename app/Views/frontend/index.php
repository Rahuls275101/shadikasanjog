
<?php 
use App\Models\Commanmodel;
 $commanmodel = new Commanmodel();
  $category = $commanmodel->all_multiple_query_order_by('category',array('category_status' => 'Active'),'category_id','ASC');
   $banner = $commanmodel->all_multiple_query_order_by('home_banner',array('banner_status' => 'Active'),'banner_id','ASC');
   
   $gallery = $commanmodel->all_multiple_query_order_by('clients',array('client_status' => 'Active'),'client_id','ASC');
   
   $team = $commanmodel->all_multiple_query_order_by('team',array('team_status' => 'Active'),'team_id','ASC');
        $newstory = $commanmodel->all_multiple_query_order_by_limit('blogs',array('blog_status' => 'Active','type' => 'Story'),'blog_id','ASC',100); 
        
        
          $profile = $commanmodel->all_multiple_query_order_by_limit('user_account ',array('trending_status ' => 'Yes'),'account_id','DESC',100); 
        

?>
<style>
/* Force Blue Verified Badge Everywhere */
.verified-badge {
    background-color: #0d6efd !important;   /* Bootstrap Primary Blue */
    color: #ffffff !important;
    padding: 4px 8px;
    font-size: 12px;
    border-radius: 4px;
    margin-left: 6px;
    display: inline-block;
    font-weight: 500;
}

/* Agar kahin .verified class bhi use ho rahi ho */
.verified-badge.verified {
    background-color: #0d6efd !important;
}
</style>
      <section class="hero-area">
        <!-- Background Slider -->
        <div class="hero-slider owl-carousel owl-theme">
            <?php foreach($banner as $bannerrow) { ?>
            <div class="slide" style="background-image:url('<?php echo base_url('assets/images/'); ?>/<?php echo $bannerrow->banner_image;?>');"></div>
            <?php } ?>

        </div>

        <!-- Overlay Content (fixed) -->
        <div class="hero-content">
            <div class="container">
                <div class="ban-tit">
                    <!-- <span><i class="no1">#1</i> Matrimony</span> -->
                    <h1>Take The Advantage Of</h1>
                    <div class="newmd">
                        <img src="<?php echo base_url('assets/frontend/'); ?>/assets/images/white-2.png">
                    </div>
                    <p>Personalised Matchmaking | Verified Profiles | Responsive Staff | 100% Privacy

                    </p>
                    <div class="newbox">
                        <a href="<?php echo base_url('page/quick-safety-check'); ?>" class="btn btn-pink">Quick Safety <br> Check list</a>
                        <a href="<?php echo base_url('page/safety-tips-for-on-line-matrimony'); ?>" class="btn btn-gold">Safety Tips for <br>
                            Online Matchmaking</a>
                    </div>
                </div>

      
            </div>
        </div>
    </section>



    <!-- ABOUT START -->
    <section style="
       background: linear-gradient(rgb(254 251 243 / 15%), rgb(255 242 242 / 63%)), url(./images/bannars/bg.jpg);
">
        <div class="ab-wel">
            <div class="container">
                <div class="row align-items-center align-items-stretch ">
                    <div class="col-lg-5 align-items-stretch">
                          <?php $about= $commanmodel->get_single_query('cms_pages',array('cms_id' => 15)); ?>
                        <div class="abouths">
                            <img src="<?php echo base_url('assets/images/'); ?>/<?php echo $about->cms_image; ?>" style="border-top-left-radius: 40px;">
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="ab-wel-rhs">
                            <div class="ab-wel-tit">
                                
                               
                                <h2><?php echo $about->cms_page_heading; ?> <em> <?php echo $about->cms_page_small_description; ?></em></h2>
                               <?php echo $about->cms_page_description; ?>

                               

                            </div>

                            <a class="register-btn" href="<?php echo base_url('page/who-we-are'); ?>">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- END -->


    <section class="process-section">
        <div class="container">
            <h2 class="section-title"> <span> Find Your Special Someone</span></h2>
            <div class="process-steps">

                <div class="step-box">
                    <div class="step-icon">
                        <img src="https://img.icons8.com/dotty/80/form.png" alt="Sign Up">
                        <span class="step-count">1</span>
                    </div>
                    <h4>Step 1</h4>
                    <p>Register for free and create your matrimonial profile</p>
                </div>

                <div class="step-box">
                    <div class="step-icon">
                        <img src="https://img.icons8.com/ios/50/chat.png" alt="Connect">
                        <span class="step-count">2</span>
                    </div>
                    <h4>Step 2</h4>
                    <p>Pay your subscription and start a conversation</p>
                </div>

                <div class="step-box">
                    <div class="step-icon">
                        <img src="https://img.icons8.com/wired/64/couple-man-woman.png" alt="Interact">
                        <span class="step-count">3</span>
                    </div>
                    <h4>Step 3</h4>

                    <p>Select & connect with matches you like</p>
                </div>

            </div>
        </div>
    </section>


    <section class="why-choose">
        <div class="container">
            <h2 class="section-title"> <span> Why Choose Shadi Ka Sanjog ?</span></h2>
            <div class="row">
                <ul class="choose-list">
                    <li>
                        <div class="choose-box " data-dely="0.1">
                            <img src="<?php echo base_url('assets/frontend/'); ?>/assets/images/icon/prize.png" alt="Genuine Profiles" loading="lazy">
                            <h4>Genuine Profiles</h4>
                            <p>Contact genuine profiles with 100% verified mobile</p>
                        </div>
                    </li>
                    <li>
                        <div class="choose-box " data-dely="0.3">
                            <img src="<?php echo base_url('assets/frontend/'); ?>/assets/images/icon/trust.png" alt="Most Trusted" loading="lazy">
                            <h4>Most Trusted</h4>
                            <p>The most trusted wedding matrimony brand</p>
                        </div>
                    </li>
                    <li>
                        <div class="choose-box " data-dely="0.6">
                            <img src="<?php echo base_url('assets/frontend/'); ?>/assets/images/icon/rings.png" alt="2000+ Weddings" loading="lazy">
                            <h4>Privacy Assured</h4>
                            <!-- <p>Lakhs of people have found their life partner</p> -->
                            <p>Privacy Assured- Privacy isn't a feature, it's a promise</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>


    <section class="webinar-section">
        <div class="container text-center">
            <div class="webstex">
                <h2>Join Free Webinar</h2>
                <p>
                    Join free webinars conducted regularly to help you discover the special features of our website.

                </p>
                <a class="register-btn" href="<?php echo base_url('free-webinar'); ?>">Register for Webinar
                </a>
            </div>
        </div>
    </section>
    <section class="shadi-services py-5">
        <div class="container">
            <div class="row align-items-center">

                <!-- Left content -->
                <div class="col-md-8">
                    <div class="shadi-services-content pe-md-4">
                          <?php $about16 = $commanmodel->get_single_query('cms_pages',array('cms_id' => 16)); ?>
                        <h3 class="shadi-services-title"><?php echo $about16->cms_page_heading; ?></h3>
                        <p class="shadi-services-text">
                         <?php echo $about16->cms_page_description; ?>

                        </p>
                        <a href="<?php echo base_url('services'); ?>" class="shadi-services-btn">Explore Services</a>
                    </div>
                </div>

                <!-- Right image -->
                <div class="col-md-4 text-center">
                    <div class="shadi-services-image">
                        <img src="<?php echo base_url('assets/images/'); ?>/<?php echo $about16->cms_image; ?>"
                            alt="Our Services" class="img-fluid ">
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="profiles-section">
        <div class="container">
            <h2 class="section-title"> Latest Profiles</h2>
            <div class="row align-items-center">
                <!-- Left Side -->
                <!-- <div class="col-md-4 text-content mb-5">
                    <h1>Latest Profiles</h1>
                    <h2>100000+ Profiles of Brides & Grooms</h2>
                    <button class="register-btn">View All</button>
                </div> -->

                <!-- Right Side (Slider) -->
                <div class="col-md-12">
                    <div class="owl-carousel owl-theme profile-slider">



                        <?php foreach($profile as $profilerow) {
                            
                               $badgeClass = '';
                if (!empty($profilerow->batch)) {
                    $color = strtolower(trim($profilerow->batch));
                    if ($color === 'green') $badgeClass = 'greenbage';
                    if ($color === 'blue') $badgeClass = 'bluebage';
                    if ($color === 'orange') $badgeClass = 'orangebage';
                }
                        
                         $age = '';
    if (!empty($profilerow->date_of_birth)) {
        $birthDate = new \DateTime($profilerow->date_of_birth);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y . ' Years';
    }
$city =  $profilerow->current_residence ?? $userData->family_home ?? 'Not specified';

 $verifiedBadge = (strtolower(trim($profilerow->verified ?? '')) === 'yes')
                    ? '<span class="verified-badge verified">Verified</span>'
                    : '';
                        ?>
                        <!-- Profile Card 1 -->
                        <div class="profile-card <?php echo  esc($badgeClass); ?>">
                            <div class="profile-img">
                                <img src="<?php echo $commanmodel->profile_image($profilerow->account_id) ?>" alt="<?php echo $profilerow->user_name; ?> ">
                            </div>
                            <h3>
                                <?php echo $profilerow->user_name; ?> 
                                <?php echo $verifiedBadge; ?> 
                                <!-- OR -->
                                <!-- <span class="verified-badge not-verified">Not Verified</span> -->
                            </h3>
                            <ul>
                                <li><strong>Gender :</strong> <?php echo $profilerow->user_name; ?></li>
                                <li><strong>Age :</strong> <?php echo $age; ?></li>
                                <li><strong>Location :</strong> <?php echo $city; ?></li>
                              
                                <li><?php echo $profilerow->about_self; ?></li>
                            </ul>
                        </div>
                        
                        <?php } ?>

                       
                     

                     

                    </div>
                </div>
            </div>
        </div>
    </section>






    <!-- END -->
    <section class="success-stories">
        <div class="container">
            <h2 class="title">Shadi Ka Sanjog Success Stories</h2>

            <div class="owl-carousel owl-theme">
                
                <?php foreach($newstory as $newstoryrow) { ?>
                <div class="save-im">
                    <div class="inn">
                        <img src="<?php echo base_url('assets/blog/'.$newstoryrow->blog_image.''); ?>" alt="">
                        <div class="desc">

                            <h4><?php echo $newstoryrow->blog_name; ?></h4>

                            <a href="<?php echo base_url('blog-detail/').'/'.$newstoryrow->url_slug; ?>">Read More.</a>
                        </div>
                    </div>

                </div>
                <?php } ?>

     
      
            </div>
        </div>
    </section>




    <!-- TRUST BRANDS -->
    <section>
        <div class="hom-cus-revi">
            <div class="container">
                <div class="row">
                    <div class="sec text-center">
                        <h2 class="section-title">Testimonials</h2>

                    </div>
                    <div class="slid-inn cus-revi">
                        <ul class="slider3">
                            <?php foreach($team as $teamrow) { ?>
                            <li>
                                <div class="cus-revi-box">

                        <?php echo $teamrow->overview;?>
                                    <h5><?php echo $teamrow->team_name;?></h5>
                                    <span><?php echo $teamrow->designation;?></span>
                                </div>
                            </li>
                            <?php } ?>
                           
                        </ul>
                    </div>
                    <!--<div class="cta-full-wid">
                        <a href="#!" class="cta-dark"> View More Testimonials</a>
                    </div>-->
                </div>
            </div>
        </div>
    </section>
    <!-- END -->












    <!-- FIND YOUR MATCH BANNER -->
    <section>
        <div class="str count">
            <div class="container">
                <div class="row">
                    <div class="fot-ban-inn">
                        <div class="lhs">
                            <h2>Find Your Perfect Match Now</h2>
                            <p>Why wait, when love and trust are just a click away. Begin your journey today and take
                                your first
                                step towards your soulmate.</p>
                            <a href="<?php echo base_url('free-webinar'); ?>" class="cta-3"><i class="fa fa-group" aria-hidden="true"></i>
                                Join Free Webinar</a>
                            <a href="<?php echo base_url('fix-appointment'); ?>" class="cta-3"><i class="fa fa-calendar"
                                    aria-hidden="true"></i>
                                Fix an Appointment</a>
                            <a href="<?php echo base_url('register'); ?>" class="cta-3"><i class="fa fa-user-plus"
                                    aria-hidden="true"></i>Register Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->

