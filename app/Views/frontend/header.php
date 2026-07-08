<?php 

use App\Models\Commanmodel;
 $commanmodel = new Commanmodel();
   $addressView = $commanmodel->get_single_query('address',array('id' => 1)); 
    $request = service('request');
     $session = session();
     
      $category = $commanmodel->all_multiple_query_order_by_limit('category',array('parent_id' => 0,'category_status' => 'Active'),'category_id','ASC',7);
      $allcategory = $commanmodel->all_multiple_query_order_by_limit('category',array('parent_id' => 5),'category_id','ASC',12);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
 
<meta property="og:url" content="<?php echo $pageurl; ?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:description" content="<?php echo $description; ?>" />
<meta property="og:image" content="<?php echo $pageimage;?>" />
<meta name="twitter:card" content="summary_large_image">
<title><?php echo $title; ?></title>
<meta name="description" content="<?php echo $description; ?>"/>
<meta name="keywords" content="<?php echo $keyword; ?>" />
<meta name="copyright" content=""/>
<meta name="author" content=" " />
<meta name="email" content="" />
<meta name="Distribution" content="Global" />
<meta name="page-topic" content=" " />
<meta name="page-type" content="Rich Internet Media" />
<meta name="Rating" content="General" />
<meta name="Robots" content="INDEX,FOLLOW" />
<meta name="Revisit-after" content="7 Days" />
<link rel="canonical" href="<?= current_url(); ?>" />
<link rel="shortcut icon" type="image/ico" href="https://www.shadikasanjog.com/assets/frontend/assets/images/favicon.png" />
<meta name="site" content="www.shadikasanjog.com/" />


   <!--== CSS FILES ==-->
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/'); ?>/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/'); ?>/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/'); ?>/assets/css/animate.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/'); ?>/assets/css/style.css">
    <!-- CSS files -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
        
        <link rel="shortcut icon" href="favicon.jpg" type="image/x-icon">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.4.6/sumoselect.min.css'>
<style>.SumoSelect {
    width: 100%;
}</style>
</head>

<body>



    <div class="mobile-social-bar">
     
        
        
           <?php  if ($session->has('loggedin')) {
                                             $usersession = $session->get('loggedin'); ?>
                                     <a href="<?php echo base_url('dashboard'); ?>" class="btn login-btn">
                            <i class="fa fa-user"></i> Hi  <?php echo $usersession['user_name']; ?>
                        </a>
                                                     
                                        <?php } else { ?>
           <a href="<?php echo base_url('login'); ?>" class="btn login-btn">
                            <i class="fa fa-sign-in"></i> Login
                        </a>
                        <a href="<?php echo base_url('register'); ?>" class="btn reg-btn">
                            <i class="fa fa-user-plus"></i>Free Registration
                        </a>
                                        <?php } ?>
    </div>

    <!-- TOP MENU -->
    <div class="head-top">
        <div class="container">
            <div class="row">

                <div class="satybhxonf">
                    <div class="lhs">
                        <ul>
                            <li><a href="<?php echo base_url('about-us'); ?>">About</a></li>
                            <li><a href="<?php echo base_url('faq'); ?>">FAQ</a></li>
                            <li><a href="<?php echo base_url('contact-us'); ?>">Contact</a></li>
                        </ul>
                    </div>
                    <div class="rhs">
                        <ul>
                            <!-- <li><a href="tel:+9704462944"><i class="fa fa-phone" aria-hidden="true"></i>+91 8882826172</a>
                            </li>
                            <li><a href="mailto:info@example.com"><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;
                                    pavsirohi73@gmail.com</a></li> -->
                            <li><a href="#!"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <li><a href="#!"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="#!"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END -->
    <!-- MAIN MENU -->
    <div class="hom-top">
        <div class="container-fluid">
            <div class="row">
                <div class="hom-nav">
                    <!-- LOGO -->
                    <div class="logo">
                        <!-- <span class="menu desk-menu">
                            <i></i><i></i><i></i>
                        </span> -->
                        <a href="<?php echo base_url(''); ?>" class="logo-brand"><img src="<?php echo base_url('assets/frontend/'); ?>/assets/images/logo.png" alt="" loading="lazy"
                                class="ic-logo"></a>
                    </div>

                    <!-- EXPLORE MENU -->
                    <div class="bl">
                        <ul>

                            <li><a href="<?php echo base_url(''); ?>">Home </a></li>
                            <li><a href="<?php echo base_url('about-us'); ?>">About Us </a></li>
                            <li><a href="<?php echo base_url('plans'); ?>">Membership Plans </a></li>

                            <li>
                            <li><a href="<?php echo base_url('free-webinar'); ?>">Free Webinar </a></li>
                            </li>
                            <li><a href="<?php echo base_url('services'); ?>">Services </a></li>
                            <li><a href="<?php echo base_url('contact-us'); ?>">Contact us </a></li>

                            </a></li>


                        </ul>
                    </div>

                    <!-- USER PROFILE -->
                    <div class="al">
                   
                        
                        
                              <?php  if ($session->has('loggedin')) {
                                             $usersession = $session->get('loggedin'); ?>
                                     <a href="<?php echo base_url('dashboard'); ?>" class="btn login-btn">
                            <i class="fa fa-user"></i> Hi  <?php echo $usersession['user_name']; ?>
                        </a>
                                                     
                                        <?php } else { ?>
           <a href="<?php echo base_url('login'); ?>" class="btn login-btn">
                            <i class="fa fa-sign-in"></i> Login
                        </a>
                        <a href="<?php echo base_url('register'); ?>" class="btn reg-btn">
                            <i class="fa fa-user-plus"></i>Free Registration
                        </a>
                                        <?php } ?>
                    </div>

                    <!--MOBILE MENU-->
                    <div class="mob-menu">
                        <div class="mob-me-ic">

                            <span class="mobile-menu" data-mob="mobile">
                                <img src="<?php echo base_url('assets/frontend/'); ?>/assets/images/icon/menu.svg" alt="">
                            </span>
                        </div>
                    </div>
                    <!--END MOBILE MENU-->
                </div>
            </div>
        </div>
    </div>
    <!-- END -->
    <!-- EXPLORE MENU POPUP -->
    <div class="mob-me-all mobile_menu">
    <div class="mob-me-clo">
        <img src="<?php echo base_url('assets/frontend/'); ?>/assets/images/icon/close.svg" alt="">
    </div>

    <div class="mv-bus">
        <div class="sldie-log">
            <img src="<?php echo base_url('assets/frontend/'); ?>/assets/images/logo.png" alt="Logo">
        </div>

        <h4><i class="fa fa-align-center" aria-hidden="true"></i> All Pages</h4>

        <ul>
            <li><a href="<?php echo base_url(); ?>">Home</a></li>
            <li><a href="<?php echo base_url('about-us'); ?>">About Us</a></li>
            <li><a href="<?php echo base_url('plans'); ?>">Membership Plans</a></li>
            <li><a href="<?php echo base_url('free-webinar'); ?>">Free Webinar</a></li>
            <li><a href="<?php echo base_url('services'); ?>">Services</a></li>
            <li><a href="<?php echo base_url('contact-us'); ?>">Contact Us</a></li>
        </ul>

        <div class="menu-pop-help al pt-2">
            <?php if ($session->has('loggedin')) { 
                $usersession = $session->get('loggedin'); ?>
                
                <a href="<?php echo base_url('dashboard'); ?>" class="btn login-btn">
                    <i class="fa fa-user"></i> Hi <?php echo $usersession['user_name']; ?>
                </a>

            <?php } else { ?>

                <a href="<?php echo base_url('login'); ?>" class="btn login-btn">
                    <i class="fa fa-sign-in"></i> Login
                </a>

                <a href="<?php echo base_url('register'); ?>" class="btn reg-btn">
                    <i class="fa fa-user-plus"></i> Free Registration
                </a>

            <?php } ?>
        </div>

        <div class="menu-pop-soci">
            <ul>
                <li><a href="#!"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                <li><a href="#!"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                <li><a href="#!"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                <li><a href="#!"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                <li><a href="#!"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
                <li><a href="#!"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </div>
</div>

