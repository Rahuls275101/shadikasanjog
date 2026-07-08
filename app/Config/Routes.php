<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.




$routes->get('/', 'Home::index');


$routes->get('dashboard-interests', 'DashboardController::dashboard_interests');
$routes->post('dashboard/ajax_dashboard_interests', 'DashboardController::ajax_dashboard_interests');
$routes->post('dashboard/removeInterest', 'DashboardController::removeInterest');
$routes->post('dashboard/cancelSentInterest', 'DashboardController::cancelSentInterest');
$routes->get('dashboard/getCounts', 'DashboardController::getCounts');

$routes->post('interest/send', 'InterestController::send');
$routes->post('interest/accept', 'InterestController::accept');
$routes->post('interest/reject', 'InterestController::reject');

$routes->get('chat', 'ChatController::index');
$routes->get('chat/getChat/(:num)', 'ChatController::getChat/$1');
$routes->post('chat/sendMessage', 'ChatController::sendMessage');
$routes->get('chat/getUnreadCount', 'ChatController::getUnreadCount');

/*$routes->group('interest', ['namespace' => 'App\Controllers'], function($routes){
    $routes->post('send', 'InterestController::send');
    $routes->post('accept', 'InterestController::accept');
    $routes->get('received', 'InterestController::received');
    $routes->get('sent', 'InterestController::sent');
});
*/
$routes->post('admin/update_all_statuses', 'Admin::update_all_statuses');
$routes->post('profile/getContactDetails', 'Dashboard::getContactDetails');

$routes->get('delete-photo/(:num)', 'Dashboard::deletePhoto/$1');

// Delete Account Routes
$routes->get('delete-account', 'Dashboard::delete_account');
$routes->post('dashboard/submit_delete_request', 'Dashboard::submit_delete_request');
$routes->post('dashboard/cancel_delete_request', 'Dashboard::cancel_delete_request');

$routes->get('auth/forgotPassword', 'Home::forgotPassword');
$routes->post('auth/sendResetLink', 'Home::sendResetLink');
$routes->get('auth/resetPassword/(:any)', 'Home::resetPassword/$1');
$routes->post('auth/updatePassword', 'Home::updatePassword');

$routes->get('/test', 'Test::index');

$routes->post('test/submit', 'Test::submit');
$routes->get('test/error', 'Test::error');

$routes->post('contact/send', 'Home::send');

$routes->get('/fix-appointment', 'Home::fix_appointment');
$routes->get('/plans', 'Home::plan');
$routes->get('/free-webinar', 'Home::free_webinar');
$routes->get('/services', 'Home::services');


$routes->get('/product/(:any)', 'Home::product_details/$1');

$routes->get('/about-us', 'Home::about_us');

$routes->get('/grievance-redressal', 'Home::grievance_redressal');
$routes->get('/report-abuse', 'Home::report_abuse');
$routes->get('/testimonial', 'Home::testimonial');
$routes->get('/advertisements', 'Home::advertisements');
$routes->get('/event', 'Home::event');
$routes->get('/story', 'Home::story');

$routes->get('/profile', 'Home::profile',['filter' => 'AuthFrontGuard']);
$routes->post('/ajax_list_profile/(:num)', 'Home::ajax_list_profile/$1');
$routes->post('/ajax_list_profile', 'Home::ajax_list_profile');

$routes->get('/blogs', 'Home::blog');
$routes->get('/contact-us', 'Home::contact_us');
$routes->post('/blog_list/(:num)', 'Home::blog_list/$1');
$routes->get('/blog-detail/(:any)', 'Home::blog_detail/$1');
$routes->get('/property/(:any)', 'Home::property/$1');
$routes->post('/ajax_list/(:num)', 'Home::ajax_list/$1');
$routes->get('/search', 'Home::search');
$routes->get('/login', 'Home::login');
$routes->get('/register', 'Home::register');
$routes->post('/register_process', 'Home::register_process');
$routes->post('/login_process', 'Home::login_process');
$routes->post('/chack-sing-in', 'Home::chack_sing_in');
$routes->post('/enquirysend', 'Home::enquirysend');
$routes->post('/quote', 'Home::quote');

$routes->get('verify-email/(:any)', 'Home::verifyEmail/$1');


$routes->post('/review-submit', 'Ajex::review_submit');
$routes->post('/ordercancel-submit', 'Ajex::ordercancel_submit');



$routes->post('/newsletter/signup', 'Ajex::sign_up_newsletter');


$routes->get('/tips-to-order', 'Home::tips_to_order');
$routes->get('/get-a-quote', 'Home::get_a_quote');
$routes->get('/faq', 'Home::faq');

$routes->get('/page/(:any)', 'Home::pages/$1');


/* Team */


$routes->get('/admin/users/delete/(:num)', 'Admin::user_delete/$1',['filter' => 'authGuard']);

$routes->get('/admin/team', 'Admin::team',['filter' => 'authGuard']);
$routes->post('/admin/team-save', 'Admin::team_save',['filter' => 'authGuard']);
$routes->get('/admin/team-edit/(:num)', 'Admin::team_edit/$1',['filter' => 'authGuard']);
$routes->post('/admin/team-update/(:num)', 'Admin::team_update/$1',['filter' => 'authGuard']);


$routes->post('/razorpay-callback/(:any)', 'PaymentController::razorpay_callback/$1');

$routes->get('/plan/(:any)', 'PaymentController::plan/$1');

$routes->post('/plan-razorpay-callback', 'PaymentController::plan_razorpay_callback');

/*
 * --------------------------------------------------------------------
 * Dashboard 
 * --------------------------------------------------------------------
 */
 
   $routes->post('/wishlistapply', 'Ajex::wishlistapply');
  $routes->post('/add_to_cart', 'Ajex::add_to_cart');
  $routes->post('/mini_cart', 'Ajex::mini_cart');
  $routes->post('/cart-list', 'Ajex::my_cart_list');
      $routes->post('/apply_coupon_code', 'Ajex::apply_coupon_code');
       $routes->post('/apply_pin_code', 'Ajex::apply_pin_code');
    $routes->post('/remove-cart-product', 'Ajex::remove_cart_product');
     $routes->post('/remove_discount', 'Ajex::remove_discount');
    $routes->post('/update-cart', 'Ajex::update_cart');
 $routes->get('/cart', 'Home::cart');
  $routes->get('/checkout', 'Home::checkout');
   $routes->get('/wishlist', 'Home::wishlist',['filter' => 'AuthFrontGuard']);
  $routes->get('/catalog/(:any)', 'Home::catalog/$1');
 
  $routes->get('/pool/(:any)', 'Home::collection/$1');
    $routes->get('/profile-details/(:any)', 'Home::profile_details/$1',['filter' => 'AuthFrontGuard']);
  
  
  //Order 
  $routes->post('/order-now', 'PaymentController::index');
    $routes->post('/ccavenues-response/(:any)', 'PaymentController::ccavenues_response/$1');
  $routes->get('/order-invoice/(:any)', 'PaymentController::order_invoice/$1');
 
 
 
 // Dashboard 
 
 $routes->get('/order-view/(:any)', 'Dashboard::order_details/$1',['filter' => 'AuthFrontGuard']);
 
$routes->post('profile/uploadImage', 'Dashboard::uploadImage');

$routes->get('/logout', 'Home::logout');
 $routes->get('/dashboard', 'Dashboard::index',['filter' => 'AuthFrontGuard']);
 
 
  $routes->get('/user-chat', 'Dashboard::user_chat',['filter' => 'AuthFrontGuard']);
 
 
  $routes->get('/user-interests', 'Dashboard::user_interests',['filter' => 'AuthFrontGuard']);
    $routes->post('/ajax_list_interests', 'Dashboard::ajax_list_interests',['filter' => 'AuthFrontGuard']);
  
  $routes->get('/user-profile', 'Dashboard::profile',['filter' => 'AuthFrontGuard']);
    $routes->get('/user-plans', 'Dashboard::plans',['filter' => 'AuthFrontGuard']);
   $routes->get('/update-profile', 'Dashboard::update_profile',['filter' => 'AuthFrontGuard']);
    $routes->post('/get-castes', 'Ajex::getCastes');
   
 $routes->post('/update-user', 'Dashboard::update_user',['filter' => 'AuthFrontGuard']);
 
  $routes->post('/profile-verification', 'Dashboard::profile_verification',['filter' => 'AuthFrontGuard']);
    $routes->post('/save-personal-details', 'Dashboard::save_personal_details',['filter' => 'AuthFrontGuard']);
    $routes->post('/save-education-details', 'Dashboard::save_education_details',['filter' => 'AuthFrontGuard']);
   $routes->post('/save-work-details', 'Dashboard::save_work_details',['filter' => 'AuthFrontGuard']);
    $routes->post('/save-family-details', 'Dashboard::saveFamilyDetails',['filter' => 'AuthFrontGuard']);
     $routes->post('/save-partner-preferences', 'Dashboard::savePartnerPreferences',['filter' => 'AuthFrontGuard']);
        $routes->post('/save-profile-visibility', 'Dashboard::saveProfileVisibility',['filter' => 'AuthFrontGuard']);
        $routes->post('update-profile-photo', 'Dashboard::updateProfilePhoto');
     
$routes->post('add-profile-photo', 'Dashboard::addProfilePhoto');

 $routes->get('/checkout/(:num)', 'Dashboard::checkout/$1',['filter' => 'AuthFrontGuard']);
 $routes->post('/proceed-to-payment/(:num)', 'Dashboard::proceed_to_payment/$1',['filter' => 'AuthFrontGuard']); 

 $routes->post('/order_response/(:num)', 'Dashboard::order_response/$1');
 $routes->get('/thank_you', 'Dashboard::thank_you');


$routes->get('/vender_register', 'Admin::vender_register');
$routes->post('/vender_register', 'Admin::vender_register_proccess');
$routes->post('/vender_response/(:any)', 'Admin::vender_response/$1');

$routes->post("profile/updateBatchAdmin", "Admin::updateBatchAdmin");

$routes->get('/admin/enquiry', 'Admin::enquiry',['filter' => 'authGuard']);

$routes->post('/admin/enquirylist', 'Admin::enquirylist',['filter' => 'authGuard']);

$routes->get('/admin/order', 'Admin::order',['filter' => 'authGuard']);

$routes->post('/admin/order_details/(:any)', 'Admin::order_details/$1',['filter' => 'authGuard']);
$routes->post('/admin/orderlist', 'Admin::orderlist',['filter' => 'authGuard']);

$routes->get('/admin/customer', 'Admin::customer',['filter' => 'authGuard']);

$routes->post('/admin/customerlist', 'Admin::customerlist',['filter' => 'authGuard']);


/* Banner */
$routes->get('/admin/home_banner', 'Admin::home_banner',['filter' => 'authGuard']);

$routes->post('/admin/home_banner_process', 'Admin::home_banner_process',['filter' => 'authGuard']);
$routes->get('/admin/edit_home_banner/(:num)', 'Admin::edit_home_banner/$1',['filter' => 'authGuard']);
$routes->post('/admin/edit_home_banner_process/(:num)', 'Admin::edit_home_banner_process/$1',['filter' => 'authGuard']);
$routes->get('/admin/delete_home_banner/(:num)', 'Admin::delete_home_banner/$1',['filter' => 'authGuard']);


$routes->get('/admin/setting', 'Admin::setting',['filter' => 'authGuard']);

$routes->post('/admin/address_manage_process', 'Admin::address_manage_process',['filter' => 'authGuard']);
$routes->post('/admin/password_manage_process', 'Admin::password_manage_process',['filter' => 'authGuard']);


$routes->get('/admin/attributes', 'Admin::attributes',['filter' => 'authGuard']);
$routes->post('/admin/attributes-list', 'Admin::attributes_list',['filter' => 'authGuard']);
$routes->get('/admin/create_attributes', 'Admin::create_attributes',['filter' => 'authGuard']);
$routes->post('/admin/create_attributes_process', 'Admin::create_attributes_process',['filter' => 'authGuard']);
$routes->get('/admin/edit_attributes/(:num)', 'Admin::edit_attributes/$1',['filter' => 'authGuard']);
$routes->post('/admin/update_attributes_process/(:num)', 'Admin::update_attributes_process/$1',['filter' => 'authGuard']);



/* CMS */
$routes->get('/admin/cms_pages', 'Admin::cms_pages',['filter' => 'authGuard']);

$routes->get('/admin/edit_cms/(:num)', 'Admin::edit_cms/$1',['filter' => 'authGuard']);
$routes->post('/admin/edit_cms_process/(:num)', 'Admin::edit_cms_process/$1',['filter' => 'authGuard']);


$routes->get('/admin', 'Admin::index');
$routes->post('/admin/admin_login', 'Admin::admin_login');

$routes->get('/admin/dashboard', 'Admin::dashboard',['filter' => 'authGuard']);


$routes->get('/admin/vender', 'Admin::user',['filter' => 'authGuard']);


$routes->post('/admin/em_userlist', 'Admin::em_userlist',['filter' => 'authGuard']);
$routes->get('/admin/create-user', 'Admin::create_user',['filter' => 'authGuard']);
$routes->post('/admin/create-user-process', 'Admin::create_user_process',['filter' => 'authGuard']);
$routes->get('/admin/edit-user/(:num)', 'Admin::edit_user/$1',['filter' => 'authGuard']);
$routes->post('/admin/edit-user-process/(:num)', 'Admin::edit_user_process/$1',['filter' => 'authGuard']);
$routes->get('/admin/user-delete/(:num)', 'Admin::user_delete/$1',['filter' => 'authGuard']);


$routes->get('/admin/property', 'Admin::property',['filter' => 'authGuard']);
$routes->get('/admin/create_property', 'Admin::create_property',['filter' => 'authGuard']);

$routes->post('/admin/create_property_process', 'Admin::create_property_process',['filter' => 'authGuard']);

$routes->post('/admin/propertylist', 'Admin::propertylist',['filter' => 'authGuard']);
$routes->get('/admin/edit_property/(:any)', 'Admin::edit_property/$1',['filter' => 'authGuard']);
$routes->post('/admin/update_property_process/(:any)', 'Admin::update_property_process/$1',['filter' => 'authGuard']);


$routes->get('/admin/questions/(:any)', 'Admin::questions/$1',['filter' => 'authGuard']);
$routes->post('/admin/questionslist', 'Admin::questionslist',['filter' => 'authGuard']);
$routes->get('/admin/create_questions/(:any)', 'Admin::create_questions/$1',['filter' => 'authGuard']);

$routes->post('/admin/create_questions_process/(:any)', 'Admin::create_questions_process/$1',['filter' => 'authGuard']);

$routes->get('/admin/edit_questions/(:any)/(:any)', 'Admin::edit_questions/$1/$2',['filter' => 'authGuard']);
$routes->post('/admin/update_questions_process/(:any)/(:any)', 'Admin::update_questions_process/$1/$2',['filter' => 'authGuard']);

/* category */
$routes->get('/admin/category', 'Admin::category',['filter' => 'authGuard']);
$routes->post('/admin/category-list', 'Admin::category_list',['filter' => 'authGuard']);
$routes->post('/admin/category_save', 'Admin::category_save',['filter' => 'authGuard']);
$routes->post('/admin/category_update', 'Admin::category_update',['filter' => 'authGuard']);
$routes->get('/admin/subcategory/(:num)', 'Admin::subcategory/$1',['filter' => 'authGuard']);
$routes->get('/admin/childcategory/(:num)', 'Admin::childcategory/$1',['filter' => 'authGuard']);

/* reviews */
$routes->get('/admin/transactions', 'Admin::transactions',['filter' => 'authGuard']);
$routes->post('/admin/transactions-list', 'Admin::transactions_list',['filter' => 'authGuard']);


$routes->get('/admin/wallet', 'Admin::wallet',['filter' => 'authGuard']);
$routes->post('/admin/wallet-list', 'Admin::wallet_list',['filter' => 'authGuard']);



/* Shipping Charges */
$routes->get('/admin/shipcharge', 'Admin::shipcharge',['filter' => 'authGuard']);
$routes->post('/admin/shipcharge-list', 'Admin::shipcharge_list',['filter' => 'authGuard']);
$routes->post('/admin/shipcharge_save', 'Admin::shipcharge_save',['filter' => 'authGuard']);
$routes->post('/admin/shipcharge_update', 'Admin::shipcharge_update',['filter' => 'authGuard']);


/* reviews */
$routes->get('/admin/reviews', 'Admin::reviews',['filter' => 'authGuard']);
$routes->post('/admin/reviews-list', 'Admin::reviews_list',['filter' => 'authGuard']);
$routes->post('/admin/reviews_save', 'Admin::reviews_save',['filter' => 'authGuard']);
$routes->post('/admin/reviews_update', 'Admin::reviews_update',['filter' => 'authGuard']);

/* bulk_product_upload */
$routes->get('/admin/bulk_product_upload', 'Admin::bulk_product_upload',['filter' => 'authGuard']);
$routes->post('admin/uploadCSV', 'Admin::uploadCSV');

/* Brand */
$routes->get('/admin/brand', 'Admin::brand',['filter' => 'authGuard']);
$routes->post('/admin/brand-list', 'Admin::brand_list',['filter' => 'authGuard']);
$routes->post('/admin/brand_save', 'Admin::brand_save',['filter' => 'authGuard']);
$routes->post('/admin/brand_update', 'Admin::brand_update',['filter' => 'authGuard']);


/* collections */
$routes->get('/admin/collections', 'Admin::collections',['filter' => 'authGuard']);
$routes->post('/admin/collections-list', 'Admin::collections_list',['filter' => 'authGuard']);
$routes->post('/admin/collections_save', 'Admin::collections_save',['filter' => 'authGuard']);
$routes->post('/admin/collections_update', 'Admin::collections_update',['filter' => 'authGuard']);

/* Files */
$routes->get('/admin/files', 'Admin::files',['filter' => 'authGuard']);
$routes->post('/admin/files-list', 'Admin::files_list',['filter' => 'authGuard']);
$routes->post('/admin/delete-files', 'Admin::delete_files',['filter' => 'authGuard']);
$routes->post('/admin/upload-image', 'Admin::upload_image',['filter' => 'authGuard']);

/* meta */
$routes->get('/admin/meta', 'Admin::meta',['filter' => 'authGuard']);
$routes->post('/admin/meta-list', 'Admin::meta_list',['filter' => 'authGuard']);
$routes->post('/admin/meta_save', 'Admin::meta_save',['filter' => 'authGuard']);
$routes->post('/admin/meta_update', 'Admin::meta_update',['filter' => 'authGuard']);

// coupon
$routes->get('/admin/coupon', 'Admin::coupon',['filter' => 'authGuard']);
$routes->post('/admin/coupon-list', 'Admin::coupon_list',['filter' => 'authGuard']);
$routes->post('/admin/coupon_save', 'Admin::coupon_save',['filter' => 'authGuard']);
$routes->post('/admin/coupon_update', 'Admin::coupon_update',['filter' => 'authGuard']);

/* News */
$routes->get('/admin/blog', 'Admin::our_blogs',['filter' => 'authGuard']);
$routes->get('/admin/create_blog', 'Admin::create_blog',['filter' => 'authGuard']);
$routes->post('/admin/create_blog_process', 'Admin::create_blog_process',['filter' => 'authGuard']);
$routes->get('/admin/edit_our_blog/(:num)', 'Admin::edit_our_blog/$1',['filter' => 'authGuard']);
$routes->post('/admin/edit_blog_process/(:num)', 'Admin::edit_blog_process/$1',['filter' => 'authGuard']);
$routes->get('/admin/delete_our_blog/(:num)', 'Admin::delete_our_blog/$1',['filter' => 'authGuard']);

$routes->get('/admin/blog', 'Admin::our_blogs',['filter' => 'authGuard']);
$routes->get('/admin/create_blog', 'Admin::create_blog',['filter' => 'authGuard']);
$routes->post('/admin/create_blog_process', 'Admin::create_blog_process',['filter' => 'authGuard']);
$routes->get('/admin/edit_our_blog/(:num)', 'Admin::edit_our_blog/$1',['filter' => 'authGuard']);
$routes->post('/admin/edit_blog_process/(:num)', 'Admin::edit_blog_process/$1',['filter' => 'authGuard']);
$routes->get('/admin/delete_our_blog/(:num)', 'Admin::delete_our_blog/$1',['filter' => 'authGuard']);


$routes->get('/admin/updateorderstatus/(:num)', 'Admin::updateorderstatus/$1',['filter' => 'authGuard']);


$routes->get('/admin/answarsave', 'Admin::answarsave',['filter' => 'authGuard']);
$routes->post('/admin/answarsavelist', 'Admin::answarsavelist',['filter' => 'authGuard']);


$routes->get('/admin/logout', 'Admin::logout');

$routes->post('/getCity', 'Ajex::getCity');

$routes->post('/getcategory', 'Ajex::getcategory');
$routes->post('/getattributes', 'Ajex::getattributes');

$routes->get('/admin/(:any)', 'Admin::$1');

$routes->get('/admin/(:any)/(:any)', 'Admin::$1/$2');
$routes->get('/admin/(:any)/(:any)/(:any)', 'Admin::$1/$2/$');

$routes->post('/admin/(:any)', 'Admin::$1');

$routes->post('/admin/(:any)/(:any)', 'Admin::$1/$2');
$routes->post('/admin/(:any)/(:any)/(:any)', 'Admin::$1/$2/$');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
