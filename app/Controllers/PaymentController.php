<?php
namespace App\Controllers;
require_once(APPPATH . "Libraries/config.php");
require_once(APPPATH . "Libraries/razorpay-php/Razorpay.php");


namespace App\Controllers;
use App\Models\Commanmodel;
use App\Models\Travelmodel;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
class PaymentController extends BaseController
{
    
    
    
    public function index()
{
    $session = session();
    $commanmodel = new Commanmodel();
    helper(['form', 'url']);
    $validation =  \Config\Services::validation();
    
    $rules = [
        'name' => [
            'label'  => 'First Name',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Please enter First Name',
            ],
        ],
        'phone' => [
            'label'  => 'Phone Number',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Please enter phone number',
            ],
        ],
        'email' => [
            'label'  => 'Email',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Please enter email',
            ],
        ],
        'city' => [
            'label'  => 'City',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Please enter city',
            ],
        ],
        'state' => [
            'label'  => 'State',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Please enter state',
            ],
        ],
        'address' => [
            'label'  => 'Address',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Please enter address',
            ],
        ],
    ];
    
    if ($this->validate($rules)) {
        if ($session->has('loggedin')) {
            $usersession = $session->get('loggedin'); 
            $loginId = $usersession['user_id'];
        } else {
            $loginId = 0;
        }

     
        $bookingInformetion = $commanmodel->calculateCartSummary();
       
        
        // Capture shipping details if provided, else use the billing details or leave them blank
        $shippingName = $this->request->getVar('shipping_name') ?: $this->request->getVar('name');
        $shippingEmail = $this->request->getVar('shipping_email') ?: $this->request->getVar('email');
        $shippingPhone = $this->request->getVar('shipping_phone') ?: $this->request->getVar('phone');
        $shippingAddress = $this->request->getVar('shipping_address') ?: $this->request->getVar('address');
        $shippingCity = $this->request->getVar('shipping_city') ?: $this->request->getVar('city');
        $shippingState = $this->request->getVar('shipping_state') ?: $this->request->getVar('state');
         $shippingCountry= $this->request->getVar('shipping_country') ?: $this->request->getVar('country');
        $shippingPin = $this->request->getVar('shipping_pin') ?: $this->request->getVar('pin_code');

            
             if ($this->request->getVar('saved_address')=='Yes') {
                 
                   $address_data = array(
            'address_user_id' => $loginId,
            'address_book_user_name' => $this->request->getVar('name'),
            'address_book_email' => $this->request->getVar('email'),
            'address_book_phone' => $this->request->getVar('phone'),
            'address_book_address' => $this->request->getVar('address'),
            'address_book_city' => $this->request->getVar('city'),
            'address_book_state' => $this->request->getVar('state'),
             
            'address_book_pin_no' => $this->request->getVar('pin_code'),
            'address_shipping_user_name' => $shippingName,
            'address_shipping_email' => $shippingEmail,
            'address_shipping_phone' => $shippingPhone,
            'address_shipping_address' => $shippingAddress,
            'address_shipping_city' => $shippingCity,
            'address_shipping_state' => $shippingState,
   
            'address_shipping_pin_no' => $shippingPin,
            );
            $AddressApplied = $commanmodel->insert_query('manage_address',$address_data);
            
            
        }


        // Preparing booking data
        $bookingData = [
           
            'order_book_user_id' => $loginId,
            'order_book_user_name' => $this->request->getVar('name'),
            'order_book_email' => $this->request->getVar('email'),
            'order_book_phone' => $this->request->getVar('phone'),
            'order_book_address' => $this->request->getVar('address'),
            'order_book_city' => $this->request->getVar('city'),
            'order_book_state' => $this->request->getVar('state'),
            'order_book_country' => $this->request->getVar('country'),
            'order_book_pin_no' => $this->request->getVar('pin_code'),
            'order_shipping_user_name' => $shippingName,
            'order_shipping_email' => $shippingEmail,
            'order_shipping_phone' => $shippingPhone,
            'order_shipping_address' => $shippingAddress,
            'order_shipping_city' => $shippingCity,
            'order_shipping_state' => $shippingState,
            'order_shipping_country' => $shippingCountry,
            'order_shipping_pin_no' => $shippingPin,
            'coupon_code' => $bookingInformetion['coupon'] ? $bookingInformetion['coupon']['coupon_code'] : '',
            'coupon_type' =>  $bookingInformetion['coupon'] ? $bookingInformetion['coupon']['coupon_type'] : '',
            'coupon_value' =>  $bookingInformetion['coupon'] ? $bookingInformetion['coupon']['coupon_value'] : '',
            'order_book_subtotal' => $bookingInformetion['subTotal'],
            'order_book_exclusive_gst' => $bookingInformetion['exclusiveGST'],
            'order_book_shipping' => $bookingInformetion['shipping'],
            'order_book_total' => $bookingInformetion['totalWithGST'],
            'order_book_status' => 'Pending',
            'order_book_pay_type' => $this->request->getVar('payment_method'),
            'order_book_date' =>  date("Y-m-d H:i:s"),
        ];
        
     

        // Insert the booking data into the database
        $Inserted = $commanmodel->insert_query_get_inserid('order_book', $bookingData);
        
    
        
            foreach($bookingInformetion['products'] as $item) {
                
               
                   $bookingNumber = $this->generateBookingNumber();
                     $product_data = array(
                'booking_product_vender' => $item['vender'],
                'booking_product_order_book_id' => $Inserted,
                'booking_product_order_id' => $bookingNumber,
                'booking_product_product_id' => $item['product_id'],
                'booking_product_product_name' =>  $item['product_name'],
                 'booking_product_varian' => $item['varian'],
                'booking_product_price' => $item['price'],
                'booking_product_shipping' => $item['shipping'],
                  'discount_per_product' => $item['discount_per_product'],
                'booking_product_quantity' => $item['quantity'],
                'booking_product_tax' =>  $item['tax'],
                'booking_product_tax_rate' =>  $item['tax_rate'],
                'booking_product_sub_total' =>  $item['sub_total'],
                'booking_product_image' =>  $item['image'],
                 'booking_product_status' => 'pending',
               
                );
                
                $commanmodel->insert_query('booking_product', $product_data);
            }
            
            
         
             return  $this->payment_confirmation($Inserted,$this->request->getVar('payment_method'),$bookingData);
           
        
    }
}



 private function payment_confirmation($Inserted, $paymentMethod, $bookingData)
{
    $commanmodel = new Commanmodel();

    if ($paymentMethod == 'COD') {
      
        $verification = $commanmodel->order_verification($Inserted);
        
        return redirect()->to('/order-invoice/'.$Inserted)->with('msg', $verification);
    } else {
         $this->razorpay($Inserted, $paymentMethod, $bookingData);
    }

    // Add logic for other payment methods
   // return redirect()->to('/payment/gateway')->with('order_id', $bookingNumber);
}

public function plan($id)
{
    $session = session();
    $commanmodel = new Commanmodel();
    helper('sweet_alert');

    $usersession = $session->get('loggedin');

    if (!$usersession || empty($usersession['user_id'])) {
        return redirect()->to(base_url('login'));
    }

    $userdata = $commanmodel->get_single_query(
        'user_account',
        ['account_id' => $usersession['user_id']]
    );

    if (!$userdata) {
        echo showSweetAlert('Error', 'User not found.', 'error', base_url('login'));
        exit;
    }

    if (($userdata->approval ?? '') !== 'Approved') {
        echo showSweetAlert(
            'Approval Required',
            'Please get approved before purchasing a plan.',
            'warning',
            base_url('user-plans')
        );
        exit;
    }

    $membership = $commanmodel->get_single_query(
        'membership',
        ['membership_id' => (int)$id]
    );

    if (!$membership) {
        echo showSweetAlert(
            'Invalid Membership',
            'The selected membership plan is not available.',
            'error',
            base_url('user-plans')
        );
        exit;
    }

    /* ===========================================
       ✅ FINAL RENEWAL LOGIC
    =========================================== */

    $today = date('Y-m-d');
    $renewalType = 'Fresh';
    $durationMonths = 6;
    $renewal_count = (int)($userdata->renewal_count ?? 0);

    if (!empty($userdata->membership_end_date)) {

        $expiryDate = date('Y-m-d', strtotime($userdata->membership_end_date));
        $graceDate  = date('Y-m-d', strtotime($expiryDate . ' +1 month'));

        /* 1️⃣ PLAN ACTIVE */
        if ($today <= $expiryDate) {

            echo showSweetAlert(
                'Plan Already Active',
                'Your plan is active till ' . date('d-m-Y', strtotime($expiryDate)) . '. You cannot purchase again.',
                'info',
                base_url('dashboard')
            );
            exit;
        }

        /* 2️⃣ WITHIN 1 MONTH GRACE → RENEWAL */
        elseif ($today > $expiryDate && $today <= $graceDate) {

            if ($renewal_count == 0) {
                $renewalType = 'First Renewal';
                $durationMonths = 12;
            } else {
                $renewalType = 'Renewal';
                $durationMonths = 12;
            }
        }

        /* 3️⃣ GRACE EXPIRED → FRESH */
        else {

            $renewalType = 'Fresh';
            $durationMonths = 6;
            $renewal_count = 0;
        }
    }

    /* ===========================================
       Razorpay Setup
    =========================================== */

    $keyId     = 'rzp_live_SxUXhv5AhYOWKS';
    $keySecret = 'JWAfIrqbUowxh9fiFK3utb9w';

    $bookingNumber = 'ORD' . time();
    $amountPaise   = (int)round(((float)$membership->membership_price) * 100);

    try {

        $api = new \Razorpay\Api\Api($keyId, $keySecret);

        $razorpayOrder = $api->order->create([
            'receipt'  => $bookingNumber,
            'amount'   => $amountPaise,
            'currency' => 'INR'
        ]);

        $razorpayOrderId = $razorpayOrder['id'];

    } catch (\Exception $e) {

        echo showSweetAlert(
            'Payment Gateway Error',
            $e->getMessage(),
            'error',
            base_url('user-plans')
        );
        exit;
    }

    $session->set('razorpay_order_id', $razorpayOrderId);

    $db = \Config\Database::connect();
    $db->table('payment_history')->insert([
        'user_id'       => $userdata->account_id,
        'membership_id' => $membership->membership_id,
        'order_id'      => $razorpayOrderId,
        'amount'        => (float)$membership->membership_price,
        'renewal_type'  => $renewalType,
        'duration'      => $durationMonths,
        'status'        => 'Pending',
        'created_at'    => date('Y-m-d H:i:s')
    ]);

    $data = [
        "key" => $keyId,
        "amount" => $amountPaise,
        "name" => "Shadi ka Sanjog",
        "description" => $membership->membership_name . " ({$renewalType})",
        "order_id" => $razorpayOrderId,
        "prefill" => [
            "name" => $userdata->user_name ?? '',
            "email" => $userdata->user_email ?? '',
            "contact" => $userdata->user_phone ?? '',
        ],
        "notes"             => [
    "user_id"           => $userdata->user_id,
    "address"           => $userdata->user_id,
     "member_id" => $userdata->user_id,
  
    ],
        "theme" => [
            "color" => "#F37254"
        ]
    ];

    echo view('razorpay_checkout_page', [
        'data' => $data,
        'membership' => $membership,
        'renewalType' => $renewalType,
        'durationMonths' => $durationMonths,
        'razorpayOrderId' => $razorpayOrderId
    ]);

    exit;
}

public function plan_razorpay_callback()
{
    $db = \Config\Database::connect();
    helper('sweet_alert');
$commanmodel = new Commanmodel();
    $order_id   = $this->request->getPost('razorpay_order_id');
    $payment_id = $this->request->getPost('razorpay_payment_id');
    $signature  = $this->request->getPost('razorpay_signature');

    try {

        $payment = $db->table('payment_history')
            ->where('order_id', $order_id)
            ->get()
            ->getRow();

        if (!$payment) {
            throw new \Exception("Payment record not found.");
        }

        if ($payment->status === 'Success') {
            echo showSweetAlert(
                'Already Processed',
                'This payment is already processed.',
                'info',
                base_url('dashboard')
            );
            exit;
        }

        $user = $db->table('user_account')
            ->where('account_id', $payment->user_id)
            ->get()
            ->getRow();

        $today = date('Y-m-d');
        $durationMonths = (int)$payment->duration;

        $startDate = (!empty($user->membership_end_date) &&
            strtotime($user->membership_end_date) >= strtotime($today))
            ? $user->membership_end_date
            : $today;

        $endDate = date('Y-m-d', strtotime("+{$durationMonths} months", strtotime($startDate)));

        $renewal_count = ($payment->renewal_type == 'Fresh')
            ? 0
            : ((int)($user->renewal_count ?? 0) + 1);

        $db->table('payment_history')
            ->where('order_id', $order_id)
            ->update([
                'payment_id' => $payment_id,
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'status'     => 'Success',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $membership = $commanmodel->get_single_query(
        'membership',
        ['membership_id' => $payment->membership_id]
    );

        $db->table('user_account')
            ->where('account_id', $payment->user_id)
            ->update([
                'membership_id'        => $payment->membership_id,
                 'membership_name'        => $membership->membership_name,
                  'membership_price'        => $payment->amount,
                   'membership_month'        => $payment->duration,
                'membership_start_date'=> $startDate,
                'membership_end_date'  => $endDate,
                'membership_status'    => 'Active',
                'renewal_count'        => $renewal_count
            ]);
            
            
            
            // Send Payment Confirmation Email

$to = $user->user_email; 
$subject = "Payment Confirmation";

$validUpto = date('d M Y', strtotime($endDate));

$htmldata = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height:1.8; color:#333;">

    <p>Dear <strong>' . $user->user_id . '</strong>,</p>

    <p>
        Thank you for subscribing to our membership plan.
        Your plan is now valid upto <strong>' . $validUpto . '</strong>.
    </p>

    <p>
        We are committed to making your experience valuable and rewarding.
        As a paid member, you’ll now have access to exclusive benefits and
        features designed to help you get the most out of our platform.
    </p>

    <p>
        We truly appreciate your trust in us and look forward to serving you.
    </p>

    <p>
        If you have any questions or need assistance at any time,
        feel free to connect with us. We’re always happy to help.
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

$email->setFrom(
    "support@shadikasanjog.com",
    "Shadi Ka Sanjog"
);

$email->setSubject($subject);

$email->setMailType("html");

$email->setMessage($htmldata);

$email->send();

        echo showSweetAlert(
            'Payment Successful',
            'Your membership has been activated successfully!',
            'success',
            base_url('dashboard')
        );

    } catch (\Exception $e) {

        $db->table('payment_history')
            ->where('order_id', $order_id)
            ->update([
                'status' => 'Failed',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        echo showSweetAlert(
            'Payment Failed',
            $e->getMessage(),
            'error',
            base_url('plans')
        );
    }
}


    
   private function razorpay($inserid,$paymentMethod,$bookingData) {
     
      $session = session();
    $commanmodel = new Commanmodel();
    
    $usersession = $session->get('loggedin');

    $userdata = $commanmodel->get_single_query('user_account', array('account_id' => $usersession['user_id']));
    
     $infofomedetails = $session->infofomedetails;
     $order_book = $commanmodel->get_single_query('order_book', ['order_book_id' => $inserid]);
       if ($inserid) {
           	  $api = new Api('rzp_live_SxUXhv5AhYOWKS', 'JWAfIrqbUowxh9fiFK3utb9w');

//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders


//
$orderData = [
    'receipt'         => 'order'.$inserid,
    'amount'          => $bookingData['order_book_total'] * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];	
			   
$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount']/100;  
			     
			     $checkout = 'automatic';

if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true))
{
    $checkout = $_GET['checkout'];
}

$data = [
    "key"               => 'rzp_live_SxUXhv5AhYOWKS',
    "amount"            => $amount,
    "name"              => "Shadi ka Sanjog",
    "description"       => "",
    "image"             =>  base_url()."assets/frontend/images/logo.png",
    "prefill"           => [
    "name"              => $order_book->order_book_user_name,
    "email"             => $order_book->order_book_email,
    "contact"           => $order_book->order_book_phone,
    ],
    "notes"             => [
    "user_id"           => $userdata->user_id,
    "address"           => $userdata->user_id,
    "merchant_order_id" => 'order'.$inserid,
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];


    $data['display_currency']  = 'INR';
    $data['display_amount']    = $amount;


$json = json_encode($data);
		?>
       
      
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form name='razorpayform' action="<?php echo base_url()?>/razorpay-callback/<?php echo $inserid; ?>" method="POST">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
</form>
<script>
// Checkout details as a json
var options = <?php echo $json?>;

/**
 * The entire list of Checkout fields is available at
 * https://docs.razorpay.com/docs/checkout-form#checkout-fields
 */
options.handler = function (response){
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    document.getElementById('razorpay_signature').value = response.razorpay_signature;
   // alert(response.razorpay_signature);
    document.razorpayform.submit();
};

// Boolean whether to show image inside a white frame. (default: true)
options.theme.image_padding = false;

options.modal = {
    ondismiss: function() {
        console.log("This code runs when the popup is closed");
    },
    // Boolean indicating whether pressing escape key 
    // should close the checkout form. (default: true)
    escape: true,
    // Boolean indicating whether clicking translucent blank
    // space outside checkout form should close the form. (default: false)
    backdropclose: false
};

var rzp = new Razorpay(options);


    rzp.open();
    e.preventDefault();

</script> 
<?php	     
        }
     
 }
 
 public function razorpay_callback($Inserted){
         
           $session = session();
    $commanmodel = new Commanmodel();
      $current_order_id = $Inserted; 
      $OrderDetail = $commanmodel->order_detail_get_by_id_validate_order($current_order_id);
      if($OrderDetail)
      {
       
        
        
        		$success = true;

        $error = "Payment Failed";
        
        if (empty($_POST['razorpay_payment_id']) === false)
        {
           $api = new Api('rzp_live_SxUXhv5AhYOWKS', 'JWAfIrqbUowxh9fiFK3utb9w');
        
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
                            		    
                            		    
                                         $verification = $commanmodel->order_verification($Inserted);
                                        $update =   $commanmodel->update_query('order_book',$cofirmrray, ['order_book_id' => $Inserted]);
                                       
                                       
                                          
                        				
                        					
                        				if($update) {
                        				    return redirect()->to('/order-invoice/'.$Inserted)->with('msg', $verification);
                                        
                        				}
			                         	
	     	
			                         	


	
           
                         		
                         		
                         		
}
else
{
    $html = "<p>Your payment failed</p>
             <p>{$error}</p>";
             //	$this->session->set_flashdata('square', 'Your payment failed');
			
			                         //   redirect(base_url().'home');
}
        
       
      }
      else
      {
       // return redirect('404');
      }
            
      }

    
  

private function ccavenues($Inserted, $paymentMethod, $bookingData)
{
    $commanmodel = new Commanmodel();
    
    			     			 
	function encrypt($plainText,$key)
{
	$key = hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	$encryptedText = bin2hex($openMode);
	return $encryptedText;
}

/*
* @param1 : Encrypted String
* @param2 : Working key provided by CCAvenue
* @return : Plain String
*/
function decrypt($encryptedText,$key)
{
	$key = hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$encryptedText = hextobin($encryptedText);
	$decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	return $decryptedText;
}

function hextobin($hexString) 
 { 
	$length = strlen($hexString); 
	$binString="";   
	$count=0; 
	while($count<$length) 
	{       
	    $subString =substr($hexString,$count,2);           
	    $packedString = pack("H*",$subString); 
	    if ($count==0)
	    {
			$binString=$packedString;
	    } 
	    
	    else 
	    {
			$binString.=$packedString;
	    } 
	    
	    $count+=2; 
	} 
        return $binString; 
  }		     			 
			 
			 
		$merchant_data='';
	$working_key='2B22A1CD2B40A8555E922593845B938A';//Shared by CCAVENUES
	$access_code='AVQS82KF90CE31SQEC';//Shared by CCAVENUES
	$paydta = [];
	
	$paydta['tid'] = '';
	$paydta['merchant_id'] = '2581359';
	$paydta['order_id'] = $Inserted;
	$paydta['amount'] = $bookingData['order_book_total'];
	$paydta['currency'] = 'INR';
	$paydta['redirect_url'] = base_url('ccavenues-response/'.$Inserted);
	$paydta['cancel_url'] = base_url('ccavenues-response/'.$Inserted);;
	$paydta['language'] = 'EN';
	$paydta['billing_name'] =$bookingData['order_book_user_name'];
	$paydta['billing_address'] = $bookingData['order_book_address'];
	$paydta['billing_city'] = $bookingData['order_book_city'];
	$paydta['billing_state'] =$bookingData['order_book_state'];
	$paydta['billing_zip'] = $bookingData['order_book_pin_no'];
	$paydta['billing_country'] = 'India';
	$paydta['billing_tel'] = $bookingData['order_book_phone'];
	$paydta['billing_email'] =$bookingData['order_book_email'];
	$paydta['promo_code'] = '';
	$paydta['customer_identifier'] = '';
	
	

	
	foreach ($paydta as $key => $value){
		$merchant_data.=$key.'='.$value.'&';
	}

	$encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.		     			 
			   ?>
			 <form method="post" name="redirect" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo "<input type=hidden name=encRequest value=$encrypted_data>";
echo "<input type=hidden name=access_code value=$access_code>";
?>
</form>
</center>
<script language='javascript'>document.redirect.submit();</script>  
<?php	    
}



 public function ccavenues_response($orderid=NULL)
      {
      $current_order_id = $orderid; 
      $OrderDetail = $this->Commanmodel->order_detail_get_by_id_validate($current_order_id);
      if($OrderDetail)
      {
       
        
   	function encrypt($plainText,$key)
{
	$key = hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	$encryptedText = bin2hex($openMode);
	return $encryptedText;
}

/*
* @param1 : Encrypted String
* @param2 : Working key provided by CCAvenue
* @return : Plain String
*/
function decrypt($encryptedText,$key)
{
	$key = hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$encryptedText = hextobin($encryptedText);
	$decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	return $decryptedText;
}

function hextobin($hexString) 
 { 
	$length = strlen($hexString); 
	$binString="";   
	$count=0; 
	while($count<$length) 
	{       
	    $subString =substr($hexString,$count,2);           
	    $packedString = pack("H*",$subString); 
	    if ($count==0)
	    {
			$binString=$packedString;
	    } 
	    
	    else 
	    {
			$binString.=$packedString;
	    } 
	    
	    $count+=2; 
	} 
        return $binString; 
  }	     
        
$workingKey='2B22A1CD2B40A8555E922593845B938A';	
	//Working Key should be provided here.
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status="";
	$order_TXNID = '';
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);
 
                for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==3)	$order_status=$information[1];
		
			if($i==1)	$order_TXNID=$information[1];
	}
        
        
         if ($order_status==="Success")
{
                         		
                         	date_default_timezone_set("Asia/Kolkata");
                      
                         		
                           		    $cofirmrray['order_TXNID'] = $order_TXNID;
                            		    $cofirmrray['order_payment_status'] = 'success';
                            		    $cofirmrray['order_TXNDATE'] = date("Y-m-d h:i:s");
                            	
                            		    
                            		      $verification = $commanmodel->order_verification($orderid);
                                       
                                       $update = $this->Commanmodel->update_query('order_book', $cofirmrray, array('order_book_id' =>$orderid)); 
                                       
                        				
                        					
                        				if($update) {
                        				    redirect(base_url('thank-you'));
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

    public function order_invoice($Inserted)
    {
          $commanmodel = new Commanmodel();
         $session = session();
 $msg = $session->getFlashdata('msg');
    $order = $commanmodel->get_single_query('order_book',array('order_book_id'=> $Inserted));
       
       $data = array(
            'title' => "contact us : Rent House", 
            'keyword' => "Home : Rent House",
            'description' => "Home : Rent House",
            'search' => '',
          'order' => '',
          'msg' => $msg,
            'searchcategory' => 'all',
            'pageurl' => base_url(), 
            'pageimage' => base_url('assets/frontend/assets/img/logo.png')
            );
        return view('frontend/header',$data).view('frontend/invoice').view('frontend/footer');
    }
    
  

    public function generateBookingNumber() {
        $prefix = 'ORDER'; // You can customize the prefix
        $timestamp = time(); // Get the current timestamp
        $randomPart = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6); // Generate a random alphanumeric string
    
        $bookingNumber = $prefix . $timestamp . $randomPart;
        return $bookingNumber;
    }

}
