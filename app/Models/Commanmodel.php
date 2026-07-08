<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;
use Illuminate\Support\Facades\DB;
use App\Models\InterestModel;

class Commanmodel extends Model
{
  
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        // OR $this->db = db_connect();
    }
    
    

public function profile_image($userId)
{
    $userdata = $this->get_single_query('user_account', ['account_id' => $userId]);

    $base_url = base_url(); // Get base URL once

    if (isset($userdata->user_photo) && !empty($userdata->user_photo)) {
        return $base_url . '/assets/uploads/' . $userdata->user_photo;
    } elseif (isset($userdata->gender) && $userdata->gender === 'Male') {
        return  $base_url . '/assets/images/male_profile.png';
    } else {
        return $base_url . '/assets/images/female_profile.avif';
    }
}

function interestButton($receiverId)
{
    $interestModel = new InterestModel();
    $session = session();
    $usersession = $session->get('loggedin');
    $loggedUserId = $usersession['user_id'];

    // Check sent interest
    $sent = $interestModel
        ->where(['sender_id' => $loggedUserId, 'receiver_id' => $receiverId])
        ->orderBy('id', 'DESC')
        ->first();

    // Check received interest
    $received = $interestModel
        ->where(['sender_id' => $receiverId, 'receiver_id' => $loggedUserId])
        ->orderBy('id', 'DESC')
        ->first();

    $html = '';

    // ✅ Sent Case
    if ($sent) {

        switch ($sent['status']) {

            case 'pending':
                $html = '<a href="#!" class="cta fol send-interest"   data-receiver-id="'.$receiverId.'">
                            Interest Sent
                         </a>';
                break;

            case 'accepted':
                $html = '<a href="#!" class="cta fol send-interest"   data-receiver-id="'.$receiverId.'">
                             Interest Sent
                         </a>';
                break;

            case 'rejected':
                $html = '<a href="#!" class="cta fol cta-rejected disabled">
                            Rejected
                         </a>';
                break;
        }

    }
    // ✅ Received Case
    elseif ($received) {

        if ($received['status'] == 'pending') {

            $html = '
                <a href="javascript:void(0)" 
                   class="cta cta-sendint accept-interest" 
                   data-id="'.$received['id'].'">
                   Accept
                </a>

                <a href="javascript:void(0)" 
                   class="cta cta-sendint reject-interest" 
                   data-id="'.$received['id'].'">
                   Reject
                </a>
            ';

        } elseif ($received['status'] == 'accepted') {

            $html = '<a href="#!" class="cta cta-connected disabled">
                         Interest Sent
                     </a>';

        } elseif ($received['status'] == 'rejected') {

            $html = '<a href="#!" class="cta cta-rejected disabled">
                        Rejected
                     </a>';
        }

    }
    // ✅ No Record Case
    else {

        $html = '<a href="javascript:void(0)" 
                    class="cta cta-sendint send-interest" 
                    data-receiver-id="'.$receiverId.'">
                    Send Interest
                 </a>';
    }

    return $html;
}



public function generateReferralCode()
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $length = 8;

    // Get database instance
    $db = \Config\Database::connect();

    do {
        // Generate random 4-character code
        $referralCode = '';
        for ($i = 0; $i < $length; $i++) {
            $referralCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Check if referral code exists in 'admin' table
        $builder = $db->table('admin');
        $exists = $builder->where('referral_code', $referralCode)
                          ->countAllResults() > 0;

    } while ($exists);

    return $referralCode;
}


    function getCity($state_id)
        {
               $query = $this->db->table('city')->select('*')->where('state_id',$state_id)->get();
                return $query->getResult();
        }
        
        
    public function login_valid($email){

		
		
		$query = $this->db->table('admin')->select('*')->where('email', $email)->get();
			return $query->getRow();
       
        }
        
        
function calculateCartSummary()
{
    $cart = new \App\Libraries\Cart();
    $session = session();

    $webInfo = $this->get_single_query('address', ['id' => 1]);
    
     if ($session->has('shipping_charges')) {
        $shipping_charges = $session->shipping_charges;
        $shipping = $shipping_charges['shipcharge'];
        
    } else {
        $charges = $this->get_single_query('shipcharge', ['shipcharge_id' => 1]);
        $shipping = $charges->shipcharge;
    }

    $subTotalView = 0; // Total before tax
    $exclusiveGST = 0; // Total GST
    $shippingTotal = 0; // Total shipping based on number of items
    $no = 0; // Total number of items

    $availableCart = $cart->contents();

    // Initialize coupon-related variables
    $couponApplied = null;
    $discount = 0;
    $totalAfterDiscount = 0;

    $products = []; // To store all product details

    if (count($availableCart)) {
        foreach ($availableCart as $items) {
            $productName = $this->get_single_query('product', ['product_id' => $items['id']]);
            $no++;

            $taxRate = 0;
            $tax = 0;

            if ($productName->inclusive_gst !== 'Yes') {
                $tax = $productName->gst;
                $taxRate = ($items['price'] * $items['qty'] * $tax) / 100; // GST for the item
                $exclusiveGST += $taxRate; // Add GST to total
            }

            $subTotalView += ($items['price'] * $items['qty']); // Subtotal for all items

            // Calculate shipping per item
            $shippingTotal += $shipping*$items['qty'];

            // Placeholder for per-product discount
            $discountPerProduct = 0;

            // If coupon is applied, calculate discount for this product
            if ($session->has('coupon_applied')) {
                $couponApplied = $session->coupon_applied;
                $totalDiscount = $this->get_coupon_discount(
                    $couponApplied['coupon_type'],
                    $couponApplied['coupon_value'],
                    $subTotalView
                );

                // Calculate the per-product discount proportionally
                $productSubTotal = $items['price'] * $items['qty'];
                $discountPerProduct = ($totalDiscount / $subTotalView) * $productSubTotal;
            }

            // Store product details including per-product discount
            $products[] = [
                'vender' => $productName->product_create_by,
                'product_id' => $items['id'],
                'product_name' => $items['name'],
                'varian' => $items['varian'],
                'price' => $items['price'],
                'quantity' => $items['qty'],
                'tax' => $tax,
                'shipping' => $shipping*$items['qty'],
                'tax_rate' => $taxRate,
                'sub_total' => ($items['price'] * $items['qty']),
                'discount_per_product' => round($discountPerProduct, 2), // Add discount for this product
                'image' => base_url('assets/images/' . $items['image']),
            ];
        }

        // Finalize coupon calculations and total
        if ($session->has('coupon_applied')) {
            $discount = $this->get_coupon_discount(
                $couponApplied['coupon_type'],
                $couponApplied['coupon_value'],
                $subTotalView
            );

            $totalAfterDiscount = $subTotalView - $discount;
            $shippingTotal = ($webInfo->free_shipping > 0)  ? ($totalAfterDiscount >= $webInfo->free_shipping ? 0 : $shippingTotal)  : 0;
            $totalWithGST = $totalAfterDiscount + $exclusiveGST + $shippingTotal;
        } else {
           $shippingTotal = ($webInfo->free_shipping > 0)  ? ($subTotalView >= $webInfo->free_shipping ? 0 : $shippingTotal)  : 0;
            $totalWithGST = $subTotalView + $exclusiveGST + $shippingTotal;
        }

        // Return the calculated values along with coupon and product details
        return [
            'subTotal' => $subTotalView,
            'exclusiveGST' => $exclusiveGST,
            'shipping' => $shippingTotal,
            'totalWithGST' => $totalWithGST,
            'totalItems' => $no,
            'coupon' => $couponApplied, // Include coupon details if applied
            'discount' => $discount, // Include discount amount
            'products' => $products, // Include all product details with per-product discount
        ];
    } else {
        // Return empty cart data along with coupon as null and empty products
        return [
            'subTotal' => 0,
            'exclusiveGST' => 0,
            'shipping' => 0,
            'totalWithGST' => 0,
            'totalItems' => 0,
            'coupon' => null,
            'discount' => 0,
            'products' => [],
        ];
    }
}


        
     

public function order_verification($order_no)
{
    $session = session();
     $cart = new \App\Libraries\Cart();

    // Fetch order details
    $order = $this->get_single_query('order_book', ['order_book_id' => $order_no]);
    if (!$order) {
        return [
            'status' => 'danger',
            'message' => '<strong>Oh snap!</strong> Order not found',
        ];
    }

    // Fetch all products associated with the order
    $products = $this->all_multiple_query_order_by('booking_product', ['booking_product_order_book_id' => $order_no], 'booking_product_id', 'ASC');

    foreach ($products as $productRow) {
        $product_id = $productRow->booking_product_product_id;
        $qty = $productRow->booking_product_quantity;
        $variant = $productRow->booking_product_varian;

        // Handle stock reduction for variants
        if (!empty($variant)) {
            $currentStockVariant = $this->get_single_query('pro_variant', ['variant_pro_id' => $product_id,'varian' => $variant]);
            if ($currentStockVariant && $currentStockVariant->pro_variant_available >= $qty) {
                $newStockVariant = $currentStockVariant->pro_variant_available - $qty;
                $this->update_query('pro_variant', ['pro_variant_available' => $newStockVariant], ['variant_pro_id' => $product_id,'varian' => $variant]);
            }
        }

        // Handle stock reduction for main product
        $currentStock = $this->get_single_query('product', ['product_id' => $product_id]);
        if ($currentStock && $currentStock->quantity >= $qty) {
            $newStock = $currentStock->quantity - $qty;
            $this->update_query('product', ['quantity' => $newStock], ['product_id' => $product_id,]);
        }
    }

    // Update order status to 'Success'
    $this->update_query('order_book', ['order_book_status' => 'Success'], ['order_book_id' => $order_no]);
     $this->update_query('booking_product', ['booking_product_status' => 'success'], ['booking_product_order_book_id' => $order_no]);
    
   
    $cart->destroy();
    $session->remove('coupon_applied');

    return [
        'status' => 'success',
        'message' => '<strong>Well done!</strong> Your order has been successfully placed!',
    ];
}



     
        
       public function check_used_coupon($table, $data)
{
    $query = $this->db->table($table)
        ->select('*')
        ->where($data)
        ->get();

    if ($query->getRow()) {
        return true;
    } else {
        return false;
    }
}

        public function check_valid_code($coupon_code)
{
    $query = $this->db->table('coupon')
        ->select('*')
        ->where('coupon_code', $coupon_code)
        ->where('coupon_status', 'Active')
        ->get();

    if ($query->getNumRows() > 0) {
        return $query->getRow(); // Fetch a single row as an object
    } else {
        return false;
    }
}
  
        
  public function get_coupon_discount($coutonType,$couponValue,$subTotalShow)
    {
      if($coutonType==1)
      {
        $discount = ($couponValue/100);
        $discountAmount = ($discount*$subTotalShow);
        return $discountAmount; 
      }
      if($coutonType==2)
      {
         $discountAmount = $couponValue;
         return $discountAmount;
      }
      else
      {
       return 0;
      }
    }

    public function result_query($table)
    {
        $hasil=$this->db->table($table)->get();
        return $hasil->getResult();
    }

    public function delete_query($table,$data)
    {
        $result=$this->db->table($table)->where($data)->delete();
        return $result;
        }

        public function insert_query($table,$data){
            $result=$this->db->table($table)->insert($data);
            return $result;
        }

    public function insert_query_get_inserid($table,$data)
    {
            $result=$this->db->table($table)->insert($data);
            return $this->db->insertID();
            
    }
      
    public function update_query($table,$data,$where){
        $result=$this->db->table($table)->where($where)->update($data);
        return $result;
}


public function get_single_query_count_no_param($table)
{
        $query = $this->db->table($table)->select('*')->get();
        return $query->getNumRows();
}


    
    
      public function order_detail_get_by_id_validate($current_order_id) {
        $query = $this->db->table('admin')
                      ->select('*')
                      ->where('orderid', $current_order_id)
                      ->get();

        if ($query->getRow()) {
            return true; // Return true if row exists
        } else {
            return false; // Return false if row does not exist
        }
    }
    
        public function order_detail_get_by_id_validate_order($current_order_id) {
        $query = $this->db->table('order_book')
                      ->select('*')
                      ->where('order_book_id', $current_order_id)
                      ->get();

        if ($query->getRow()) {
            return true; // Return true if row exists
        } else {
            return false; // Return false if row does not exist
        }
    }
        public function generate_order_id()
        {
       $length = 8;
        $useLetters = false;
        $useNumbers = true;
        $useSymbols = false;
        $useMixedCase = false;
        
        $uppercase = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'];
        $lowercase = ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm'];
        $numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $symbols = ['`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '\\', '|', '/', '[', ']', '{', '}', '"', "'", ';', ':', '<', '>', ',', '.', '?'];

        $characters = [];

        if ($useLetters) {
            if ($useMixedCase) {
                $characters = array_merge($characters, $lowercase, $uppercase);
            } else {
                $characters = array_merge($characters, $uppercase);
            }
        }

        if ($useNumbers) {
            $characters = array_merge($characters, $numbers);
        }

        if ($useSymbols) {
            $characters = array_merge($characters, $symbols);
        }

        $orderGenerate = '';

        for ($i = 0; $i < $length; $i++) {
            $orderGenerate .= $characters[array_rand($characters)];
        }

        $generateId = Time::now()->format('Ymd'); // Using CodeIgniter's Time class to get current date in 'Ymd' format
        return $generateId . $orderGenerate;
        }



public function check_select_input($id)
{
    $builder = $this->db->table('attributes')->select('attributes_id');

    // Ensure $id is an array and perform exact match with whereIn
  $builder->whereIn('attributes_id', $id);

    // Get the result
    $query = $builder->get();

    return $query->getNumRows(); // Correct method to count rows
}






public function all_multiple_query_order_by_filters($table, $filters, $field_param, $value)
{
    $builder = $this->db->table($table)->select('*');

    // Loop through each filter and apply where conditions
    foreach ($filters as $key => $filterValue) {
        // Check if the value is an array, and use whereIn for '!=' condition
        if (is_array($filterValue)) {
            $builder->whereNotIn($key, $filterValue);
        } else {
            $builder->where($key, $filterValue);
        }
    }

    // You can add more logic here if needed

    // Order by the specified field and value
    $builder->orderBy($field_param, $value);

    // Get the result
    $query = $builder->get();

    return $query->getResult();
}

public function all_multiple_query_order_by_filtersnot($table,$filtersnot, $filters, $field_param, $value)
{
    $builder = $this->db->table($table)->select('*');

    // Loop through each filter and apply where conditions
    foreach ($filtersnot as $key => $filterValue) {
        // Check if the value is an array, and use whereIn for '!=' condition
        
            $builder->whereNotIn($key, $filterValue);
       
         
        
    }
    
    
    
      foreach ($filters as $key => $filterValue) {
        // Check if the value is an array, and use whereIn for '!=' condition
        
          
       
            $builder->where($key, $filterValue);
        
    }

    // You can add more logic here if needed

    // Order by the specified field and value
    $builder->orderBy($field_param, $value);

    // Get the result
    $query = $builder->get();

    return $query->getResult();
}


public function all_multiple_query_order_by_filters_join($table, $jointable, $joinCondition, $filters, $field_param, $value)
{
$builder = $this->db->table($table)->select('branch.branch_name,admin.id');

    // Loop through each filter and apply where conditions
    foreach ($filters as $key => $filterValue) {
        // Check if the value is an array, and use whereIn for '!=' condition
        if (is_array($filterValue)) {
            $builder->whereNotIn($key, $filterValue);
        } else {
            $builder->where($key, $filterValue);
        }
    }


  $builder->join($jointable, $joinCondition, 'LEFT'); // Adjust 'LEFT' to the appropriate join type
    // You can add more logic here if needed

    // Order by the specified field and value
    $builder->orderBy($field_param, $value);

    // Get the result
    $query = $builder->get();

    return $query->getResult();
}
    public function all_multiple_query_order_by($table,$data,$field_param,$value)
        {
           
            $builder  = $this->db->table($table)->select('*')->where($data)->orderBy($field_param,$value);
            $query    = $builder->get();
           
            return $query->getResult();
            
        }


        public function all_multiple_query_order_by_join($table, $jointable, $joinCondition, $data, $field_param, $value)
        {
            $builder = $this->db->table($table);
            $builder->select('*');
            $builder->where($data);
        
            // Joining the cancellation_policy table
            $builder->join($jointable, $joinCondition, 'LEFT'); // Adjust 'LEFT' to the appropriate join type
        
            $builder->orderBy($field_param, $value);
            $query = $builder->get();
        
            return $query->getResult();
        }
        public function all_multiple_query_order_by_date($table, $data, $field_param, $value, $start_date, $end_date, $type)
        {
            $builder = $this->db->table($table)
                ->select('*')
                ->where($data);
                
       if ($start_date === $end_date) {
    // If start_date and end_date are the same, include records for that specific day and the next day
    $nextDay = date('Y-m-d', strtotime($end_date . ' + 1 day'));
    $builder->where("$type >= '$start_date' AND $type < '$nextDay'");
} else {
    // Otherwise, apply the date range filter
    $builder->where("$type BETWEEN '$start_date' AND '$end_date'");
}     
                
               
                $builder->orderBy($field_param, $value);
        
            $query = $builder->get();
        
            return $query->getResult();
        }


        public function all_multiple_query_order_by_limit_with_like($table,$data,$dataarray,$field_param,$value,$limit)
        {
              $query = $this->db->table($table)
            ->select('*')
            ->where($data) // Apply the where conditions from $data
            ->like($dataarray) // Apply whereIn with the correct syntax
            ->orderBy($field_param, $value) // Order by the specified field
            ->limit($limit) // Limit the results
            ->get();
                return $query->getResult();
        }

 public function all_multiple_query_order_by_limit($table,$data,$field_param,$value,$limit)
        {
                $query = $this->db->table($table)->select('*')->where($data)->orderBy($field_param,$value)->limit($limit)->get();
                return $query->getResult();
        }


        
        public function get_multiple_query_order_by($table,$field_param,$value)
        {
                $query = $this->db->table($table)->select('*')->orderBy($field_param,$value)->get();
                return $query->getResult();
        }

        public function topFiveBooking()
        {
            try {
                $query = $this->db->table('booking')
                    ->select('bus.bus_name, COUNT(booking.booking_id) as booking_count')
                    ->join('bus_tour', 'booking.booking_ture_id = bus_tour.bus_tour_id')
                    ->join('bus', 'bus_tour.bus_tour_bus_id = bus.bus_id')
                    ->where('booking.booking_status', 'Success')
                    ->groupBy('bus.bus_name')
                    ->orderBy('booking_count', 'DESC')
                    ->limit(5);
        
                $result = $query->get();
        
                if (!$result) {
                    $error = $this->db->error();
                    throw new \Exception('Database error: ' . $error['message']);
                }
        
                return $result->getResult();
            } catch (\Exception $e) {
                log_message('error', 'Error fetching top 5 bookings: ' . $e->getMessage());
                return []; // Return an empty array or handle the error as needed
            }
        }
        
       public function topFiveBusTourCodes()
{
    try {
        $query = $this->db->table('booking')
            ->select('bus.bus_tour_code, COUNT(booking.booking_id) as booking_count')
            ->join('bus_tour', 'booking.booking_ture_id = bus_tour.bus_tour_id')
            ->join('bus', 'bus_tour.bus_tour_bus_id = bus.bus_id')
            ->where('booking.booking_status', 'Success')
            ->groupBy('bus.bus_tour_code')
            ->orderBy('booking_count', 'DESC')
            ->limit(5);

        $result = $query->get();

        if (!$result) {
            $error = $this->db->error();
            throw new \Exception('Database error: ' . $error['message']);
        }

        return $result->getResult();
    } catch (\Exception $e) {
        log_message('error', 'Error fetching top 5 bookings: ' . $e->getMessage());
        return []; // Return an empty array or handle the error as needed
    }
}

public function topFiveBranches()
{
    try {
        $query = $this->db->table('booking')
            ->select('branch.branch_name, COUNT(booking.booking_id) as booking_count')
            ->join('bus_tour', 'booking.booking_ture_id = bus_tour.bus_tour_id')
            ->join('bus', 'bus_tour.bus_tour_bus_id = bus.bus_id')
            ->join('admin', 'booking.booking_agent = admin.id')
            ->join('branch', 'admin.branch_name = branch.branch_id')
            ->where('booking.booking_status', 'Success')
            ->groupBy('branch.branch_name')
            ->orderBy('booking_count', 'DESC')
            ->limit(5);

        $result = $query->get();

        if (!$result) {
            $error = $this->db->error();
            throw new \Exception('Database error: ' . $error['message']);
        }

        return $result->getResult(); // Use getResultArray() instead of getResult()
    } catch (\Exception $e) {
        log_message('error', 'Error fetching top 5 branches: ' . $e->getMessage());
        return []; // Return an empty array or handle the error as needed
    }
}

public function topFiveAget()
{
    try {
        $query = $this->db->table('booking')
            ->select('admin.id, admin.first_name, COUNT(booking.booking_id) as booking_count, DATE(booking.booking_date) as booking_date')
            ->join('bus_tour', 'booking.booking_ture_id = bus_tour.bus_tour_id')
            ->join('bus', 'bus_tour.bus_tour_bus_id = bus.bus_id')
            ->join('admin', 'booking.booking_agent = admin.id')
            ->where('admin.user_type', '4')
            ->where('booking.booking_status', 'Success')
            ->groupBy('admin.id, booking_date')
         
            ->orderBy('booking_date', 'ASC') // Then order by date in descending order
            ->limit(5);
        $result = $query->get();

        if (!$result) {
            $error = $this->db->error();
            throw new \Exception('Database error: ' . $error['message']);
        }

        // Transform the query result into the required data structure
        $data = [];
        foreach ($result->getResultArray() as $row) {
            // Format the date in the "j M" (e.g., "9 Nov") format
            $formattedDate = date('j m Y', strtotime($row['booking_date']));
            $data[] = [
                'date' => $formattedDate,
                'booking_count' => $row['booking_count'],
                'admin_name' => $row['first_name']
            ];
        }

        return $data;
    } catch (\Exception $e) {
        log_message('error', 'Error fetching top 5 branches: ' . $e->getMessage());
        // You may want to handle the error differently here
        return [];
    }
}



public function topFiveUser()
{
    try {
        $query = $this->db->table('booking')
            ->select('admin.id, COUNT(booking.booking_id) as booking_count, DATE(booking.booking_date) as booking_date')
            ->join('bus_tour', 'booking.booking_ture_id = bus_tour.bus_tour_id')
            ->join('bus', 'bus_tour.bus_tour_bus_id = bus.bus_id')
            ->join('admin', 'booking.booking_agent = admin.id')
            ->join('branch', 'admin.branch_name = branch.branch_id')
            ->where('booking.booking_status', 'Success')
            ->where('admin.user_type', '3')
            ->groupBy('booking.booking_date')
            ->orderBy('booking_date', 'DESC')
            ->limit(5);

        $result = $query->get();

        if (!$result) {
            $error = $this->db->error();
            throw new \Exception('Database error: ' . $error['message']);
        }
        $data = [];
        foreach ($result->getResultArray() as $row) {
            // Format the date in the "j M" (e.g., "9 Nov") format
            $formattedDate = date('j M Y', strtotime($row['booking_date']));
            $data[] = [
                'date' => $formattedDate,
                'booking_count' => $row['booking_count'],
               
            ];
        }

        return $data;
    } catch (\Exception $e) {
        log_message('error', 'Error fetching top 5 branches: ' . $e->getMessage());
        return []; // Return an empty array or handle the error as needed
    }
}

public function topFiveWebsite()
{
    try {
        $query = $this->db->table('booking')
            ->select('COUNT(booking.booking_id) as booking_count, DATE(booking.booking_date) as booking_date')
            ->where('booking.booking_agent', 0)
            ->where('booking.booking_status', 'Success')
            ->groupBy('booking.booking_date')
            ->orderBy('booking_date', 'DESC')
            ->limit(5);

        $result = $query->get();

        if ($result->getNumRows() === 0) {
            // No data found, return an empty array
            return [];
        }

        $data = [];
        foreach ($result->getResultArray() as $row) {
            // Format the date in the "j M" (e.g., "9 Nov") format
            $formattedDate = date('j M Y', strtotime($row['booking_date']));
            $data[] = [
                'date' => $formattedDate,
                'booking_count' => $row['booking_count'],
            ];
        }

        return $data;
    } catch (\Exception $e) {
        log_message('error', 'Error fetching top 5 branches: ' . $e->getMessage());
        return []; // Return an empty array or handle the error as needed
    }
}

        public function get_single_query($table,$data)
        { 
                $query = $this->db->table($table)->select('*')->where($data)->get();
                return  $query->getRow();
        }
        
        public function getSingleQueryCalculate($tableName, $filter, $fieldName)
        {
            $grandTotal = 0;
        
            if (!empty($tableName) && is_string($tableName)) {
                $query = $this->db->table($tableName)
                    ->select("SUM($fieldName) as total")
                    ->where($filter);
        
                $result = $query->get()->getRow();  // Use get()->getRow() instead of get()->first()
        
                if ($result) {
                    // Add the current iteration's total to the grand total
                    $grandTotal = $result->total;
                } else {
                    // Handle the case where no result is found
                    \Config\Services::logger()->error("No result found for the provided filter.");
                    $grandTotal = 0;
                }
            } else {
                // Handle the case where the table name is invalid
                \Config\Services::logger()->error("Invalid table name provided.");
            }
        
            return intval($grandTotal); // Return the calculated grand total
        }
        public function get_single_query_count($table,$data)
        {
                $query = $this->db->table($table)->select('*')->where($data)->get();
                return $query->getNumRows();
        }

        public function get_query_count($table,$data)
        {
                $query = $this->db->table($table)->select('*')->where($data)->get();
              if($query->getNumRows())
              {
                 return $query->getRow();
              }
              else
              {
                return false;
              }
        }
        
         public function get_url_slug($table,$title)
        {
             $query = $this->db->table($table)->select('*')->where('slug', $title)->get();
               $rowNum = $query->getNumRows();
                return ($rowNum>0)?true:false;
             
        }
         public function get_url_slug_update($table,$title,$value)
        {
             $query = $this->db->table($table)->select('*')->where($value)->where('url_slug', $title)->get();
               $rowNum = $query->getNumRows();
                return ($rowNum>0)?true:false;
             
        }
        
        
           public function match_common($table,$data)
        {
                $query = $this->db->table($table)->select('*')->where($data)->get();
              if($query->getNumRows())
              {
                 return $query->getRow();
              }
              else
              {
                return false;
              }
        }
        
        
public function salary_calculator($month, $year, $employee_id, $monthly_salary)
{
    $queryabsent = $this->db->table('attendance')
        ->selectCount('attendance_id')
        ->where('attendance_employee_id', $employee_id)
        ->where('DAYNAME(check_in_date) !=', 'Sunday') // Exclude Sundays
        ->where('MONTH(check_in_date)', $month)
        ->where('YEAR(check_in_date)', $year)
        ->where('attendance_type', 2)
        ->get();
    
    $absentCount = $queryabsent->getRow()->attendance_id;
     
    $querypresent = $this->db->table('attendance')
        ->selectCount('attendance_id')
        ->where('attendance_employee_id', $employee_id)
        ->where('DAYNAME(check_in_date) !=', 'Sunday') // Exclude Sundays
        ->where('MONTH(check_in_date)', $month)
        ->where('YEAR(check_in_date)', $year)
        ->where('attendance_type', 1)
        ->get();
    
    $presentCount = $querypresent->getRow()->attendance_id;
    
    // Calculate total days in the given month
    $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    
    // Calculate deductions
    $deductionPerDay = $monthly_salary / $totalDaysInMonth;
    $totalDeduction = ($absentCount * $deductionPerDay);
    
    // Calculate final salary
    $finalSalary = $monthly_salary - $totalDeduction;
    
    $output = '<tr>
                  <td> Present </td>
                  <td>'.$presentCount.'</td>
                </tr> 
                <tr>
                  <td> Absence </td>
                  <td>'.$absentCount.'</td>
                </tr>
                <tr>
                  <td> Total Deduction </td>
                  <td>'.$totalDeduction.'</td>
                </tr>
                <tr>
                  <td> Final Salary </td>
                  <td>'.$finalSalary.'</td>
                </tr>';
     
    return $output;
}

  public function getAjaxData($table, $searchQuery, $postData)
    {
           $this->db = \Config\Database::connect();
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $postData['columns'][$columnIndex]['data'];
        $columnSortOrder = $postData['order'][0]['dir'] == 'asc' ? 'DESC' : 'ASC';

        $builder = $this->db->table($table);

        // Total number of records without filtering
        $totalRecords = $builder->countAll();

        if ($searchQuery != '') {
            $builder->where($searchQuery);
        }

        $totalRecordwithFilter = $builder->countAllResults();

        // Fetch records
        if ($searchQuery != '') {
            $builder->where($searchQuery);
        }

        $builder->orderBy($columnName, $columnSortOrder);
        $builder->limit($rowperpage, $start);

        $query = $builder->get(); // Execute the query
        $records = $query->getResultArray();

        return ['records' => $records, 'totalRecords' => $totalRecords, 'totalRecordwithFilter' => $totalRecordwithFilter];
    }



 public function getDataFromTableJoin($tableName, $joinTable, $joinCondition, $filters = [], $dateFilter = [], $orderBy = null, $limit = null, $offset = 0)
{
    $builder = $this->db->table($tableName);

    // Create a copy of the builder for counting total records
    $countBuilder = clone $builder;

    // Apply join operation
    $builder->join($joinTable, $joinCondition);

    // Apply filters
    foreach ($filters as $filter) {
        if (isset($filter['column']) && isset($filter['value'])) {
            if (isset($filter['type']) && $filter['type'] === 'like') {
                $builder->like($filter['column'], $filter['value']);
            } else {
                $builder->where($filter['column'], $filter['value']);
            }
        }
    }

    if (!empty($dateFilter) && isset($dateFilter['column']) && isset($dateFilter['from']) && isset($dateFilter['to'])) {
        $column = $dateFilter['column'];
        $from = $dateFilter['from'];
        $to = $dateFilter['to'];

      
            $nextDay = date('Y-m-d', strtotime($to . ' + 1 day'));
            $builder->where("$column >= '$from' AND $column < '$nextDay'");
       
    }

    // Order By
    if ($orderBy) {
        $builder->orderBy($orderBy['column'], $orderBy['order']);
    }

    // Pagination
    if ($limit) {
        $builder->limit($limit, $offset);
    }

    // Get the filtered records
    $filteredRecords = $builder->get()->getResult();

    // Get the total record count (without filtering)
    $totalRecords = $countBuilder->countAllResults();

    // Get the filtered record count
    $filteredRecordCount = count($filteredRecords);

    return [
        'totalRecords' => $totalRecords,
        'filteredRecordCount' => $filteredRecordCount,
        'filteredRecords' => $filteredRecords,
    ];
}


public function getDataFromTablejointhird($tableName, $joinTable, $secondJoinTable, $thirdJoinTable, $joinCondition, $secondJoinCondition, $thirdJoinCondition, $filters = [], $dateFilterDate = [], $orderBy = null, $limit = null, $offset = 0)
{
    $builder = $this->db->table($tableName);

    // Create a copy of the builder for counting total records
    $countBuilder = clone $builder;

    // Apply join operation
    $builder->join($joinTable, $joinCondition);
    $builder->join($secondJoinTable, $secondJoinCondition);
    $builder->join($thirdJoinTable, $secondJoinCondition);

    // Apply filters
    foreach ($filters as $filter) {
        if (isset($filter['column']) && isset($filter['value'])) {
            if (isset($filter['type']) && $filter['type'] === 'like') {
                $builder->like($filter['column'], $filter['value']);
            } else {
                $builder->where($filter['column'], $filter['value']);
            }
        }
    }

    if (!empty($dateFilterDate) && isset($dateFilterDate['column']) && isset($dateFilterDate['from']) && isset($dateFilterDate['to'])) {
        $column = $dateFilterDate['column'];
        $from = $dateFilterDate['from'];
        $to = $dateFilterDate['to'];

        if ($from === $to) {
              $nextDay = date('Y-m-d', strtotime($to . ' + 1 day'));
             $builder->where("$column BETWEEN '$from' AND '$nextDay'");
        } else {
            // Otherwise, apply the date range filter
            $builder->where("$column BETWEEN '$from' AND '$to'");
        }
    }

    // Order By
    if ($orderBy) {
        $builder->orderBy($orderBy['column'], $orderBy['order']);
    }

    // Pagination
    if ($limit) {
        $builder->limit($limit, $offset);
    }
    $builder->groupBy($tableName . '.cancel_info_id');

    // Get the filtered records with DISTINCT applied to prevent duplicates
    $filteredRecords = $builder->distinct()->get()->getResult();

    // Get the total record count (without filtering)
    $totalRecords = $countBuilder->countAllResults();

    // Get the filtered record count
    $filteredRecordCount = count($filteredRecords);

    return [
        'totalRecords' => $totalRecords,
        'filteredRecordCount' => $filteredRecordCount,
        'filteredRecords' => $filteredRecords,
    ];
}

public function getDataFromTablejoinSecond($tableName, $joinTable,$secondJoinTable, $joinCondition, $secondJoinCondition, $filters = [], $dateFilterDate =[], $orderBy = null, $limit = null, $offset = 0)
{
    $builder = $this->db->table($tableName);

    // Create a copy of the builder for counting total records
    $countBuilder = clone $builder;

    // Apply join operation
    $builder->join($joinTable, $joinCondition);
    $builder->join($secondJoinTable, $secondJoinCondition);

    // Apply filters
    foreach ($filters as $filter) {
        if (isset($filter['column']) && isset($filter['value'])) {
            if (isset($filter['type']) && $filter['type'] === 'like') {
                $builder->like($filter['column'], $filter['value']);
            } else {
                $builder->where($filter['column'], $filter['value']);
            }
        }
    }



    if (!empty($dateFilterDate) && isset($dateFilterDate['column']) && isset($dateFilterDate['from']) && isset($dateFilterDate['to'])) {
        $builder->where("$dateFilterDate[column] BETWEEN '$dateFilterDate[from]' AND '$dateFilterDate[to]'");
    }
    // Order By
    if ($orderBy) {
        $builder->orderBy($orderBy['column'], $orderBy['order']);
    }

    // Pagination
    if ($limit) {
        $builder->limit($limit, $offset);
    }

    // Get the filtered records
    $filteredRecords = $builder->get()->getResult();

    // Get the total record count (without filtering)
    $totalRecords = $countBuilder->countAllResults();

    // Get the filtered record count
    $filteredRecordCount = count($filteredRecords);

    return [
        'totalRecords' => $totalRecords,
        'filteredRecordCount' => $filteredRecordCount,
        'filteredRecords' => $filteredRecords,
    ];
}



public function getDataFromTableorder($tableName, $filters = [], $orderBy = null, $limit = null, $offset = 0,$filtersrefer= [],$arrayfilte= [])
{
      
    
    
    $builder = $this->db->table($tableName);

    // Create a copy of the builder for counting total records
    $countBuilder = clone $builder;

    // Apply filters
    foreach ($filters as $filter) {
        if (isset($filter['column']) && isset($filter['value'])) {
            if (isset($filter['type']) && $filter['type'] === 'like') {
                $builder->like($filter['column'], $filter['value']);
            } else {
                $builder->where($filter['column'], $filter['value']);
            }
        }
    }
    
      foreach ($arrayfilte as $filter) {
             if (isset($filter['column']) && isset($filter['value'])) {
                $builder->whereIn($filter['column'], $filter['value']);
             }
      }
    
    if(!empty($filtersrefer)) {
        $builder->whereIn('booking_product_referral_code', $filtersrefer);
    }

    // Order By
    if ($orderBy) {
        $builder->orderBy($orderBy['column'], $orderBy['order']);
    }

    // Pagination
    if ($limit) {
        $builder->limit($limit, $offset);
    }

    // Get the filtered records
    $filteredRecords = $builder->get()->getResult();

    // Get the total record count (without filtering)
    $totalRecords = $countBuilder->countAllResults();

    // Get the filtered record count
    $filteredRecordCount = count($filteredRecords);

    return [
        'totalRecords' => $totalRecords,
        'filteredRecordCount' => $filteredRecordCount,
        'filteredRecords' => $filteredRecords,
    ];
}

public function getDataFromTable($tableName, $filters = [], $orderBy = null, $limit = null, $offset = 0 ,$arrayfilte= [])
{
      
    
    
    $builder = $this->db->table($tableName);

    // Create a copy of the builder for counting total records
    $countBuilder = clone $builder;

    // Apply filters
    foreach ($filters as $filter) {
        if (isset($filter['column']) && isset($filter['value'])) {
            if (isset($filter['type']) && $filter['type'] === 'like') {
                $builder->like($filter['column'], $filter['value']);
            } else {
                $builder->where($filter['column'], $filter['value']);
            }
        }
    }
 foreach ($arrayfilte as $filter) {
             if (isset($filter['column']) && isset($filter['value'])) {
                $builder->whereIn($filter['column'], $filter['value']);
             }
      }
    // Order By
    if ($orderBy) {
        $builder->orderBy($orderBy['column'], $orderBy['order']);
    }

    // Pagination
    if ($limit) {
        $builder->limit($limit, $offset);
    }

    // Get the filtered records
    $filteredRecords = $builder->get()->getResult();

    // Get the total record count (without filtering)
    $totalRecords = $countBuilder->countAllResults();

    // Get the filtered record count
    $filteredRecordCount = count($filteredRecords);

    return [
        'totalRecords' => $totalRecords,
        'filteredRecordCount' => $filteredRecordCount,
        'filteredRecords' => $filteredRecords,
    ];
}


 public function seatChartMaker($rows, $columns, $selectedSeats)
    {
$html = '<div id="list-bus-container">';

for ($row = 1; $row <= $rows; $row++) {
    $html .= '<div class="colsview">';
    for ($col = 1; $col <= $columns; $col++) {
        $seatNumber = ($row - 1) * $columns + $col;
        $seatClass = in_array($seatNumber, $selectedSeats) ? 'selected' : '';

        $html .= '<div class="seatview ' . $seatClass . '"></div>';
    }
    $html .= '</div>';
}

$html .= '</div>';








        return $html;
    }
    
    
    
    
       public function vehicleseatChartMaker($rows, $columns, $selectedSeats,$class,$seattypeId)
    {
        
        
        
        ?>
   
        <?php
        
               if ($class === 'Website Available') {
    $variableValue = 'view-selected-skyblue';
} elseif ($class === 'Available') {
    $variableValue = 'view-selected-blue';
} elseif ($class === 'Blocked') {
    $variableValue = 'view-selected-yellow';
} elseif ($class === 'Branch Blocked') {
    $variableValue = 'view-selected-red';
} else {
   $variableValue = 'selected';
}
  
        


$html = '<div id="vehicle-list-bus-container">';
$seatno = 1;

for ($row = 1; $row <= $rows; $row++) {
    $html .= '<div class="colsview">';
    for ($col = 1; $col <= $columns; $col++) {
        $boxNumber = ($row - 1) * $columns + $col;
        $seatClass = in_array($boxNumber, $selectedSeats) ? $variableValue : '';
        
        // Check if the seat is selected and assign the appropriate number, otherwise keep it blank
        $seatNumber= in_array($boxNumber, $selectedSeats) ? $seatno++ : '';
        
     if ($seattypeId == 'R' and $seatNumber!='') {
    $seatview = $seatNumber;
} else if ($seattypeId == 'S' and $seatNumber!='') {
    $seatview = 'S';
} else {
    $seatview = '';
}
        $html .= '<div class="seatview ' . $seatClass . '" data-boxnumber="' . $boxNumber . '" data-seatNumber="' . $seatNumber . '">' . $seatview . '</div>';
    }
    $html .= '</div>';
}

$html .= '</div>';






        return $html;
    }




    public function getbusTourSeatChart($bus_tour_id)
    {
        
        
       
         $bus = $this->get_single_query('bus_tour',array('bus_tour_id'=> $bus_tour_id));

         $chart = $this->get_single_query('vehicle',array('vehicle_id'=> $bus->bus_tour_vehicle_id));
        $rows = $chart->vehicle_rows;
        $columns = $chart->vehicle_columns;
        
         $vehicle_seat_type = $chart->vehicle_seat_type;
        $selectedSeats = explode(', ', $chart->vehicle_selected);
        
   
             $website_available = explode(', ', $bus->bus_tour_website_available);
        $available = explode(', ', $bus->bus_tour_available);
        $blocked = explode(', ', $bus->bus_tour_blocked);
        $branch_blocked = explode(', ', $bus->bus_tour_branch_blocked);
        $tour_confirm = explode(', ', $bus->bus_tour_confirm);
    $bus_tour_confirm_femail = explode(', ', $bus->bus_tour_confirm_femail);
        

$html = '
 <div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <img src="'.base_url('assets/img/green1.png').'" alt="logo">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="selected-skyblue" data-value="selected-skyblue"> Website Available <i class="input-helper"></i>
                </label>
            </div>
            <input type="hidden" id="selectedSkyblueSeatNumber" name="selectedSkyBlueSeatNumber" value="'.$bus->bus_tour_website_available.'"  readonly>
        </div>
    </div>

    <!-- Radio buttons and input boxes for "Available" class -->
    <div class="col-md-3">
        <div class="form-group">
            <img src="'.base_url('assets/img/blue1.png').'" alt="logo">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="selected-blue" data-value="selected-blue"> Available <i class="input-helper"></i>
                </label>
            </div>
            <input type="hidden" id="selectedBlueSeatNumber" name="selectedBlueSeatNumber" value="'.$bus->bus_tour_available.'"  readonly>
        </div>
    </div>

    <!-- Radio buttons and input boxes for "Blocked" class -->
    <div class="col-md-3">
        <div class="form-group">
            <img src="'.base_url('assets/img/yelow2.png').'" alt="logo">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios3" value="selected-yellow" data-value="selected-yellow"> Blocked <i class="input-helper"></i>
                </label>
            </div>
            <input type="hidden" id="selectedYellowSeatNumber" name="selectedYellowSeatNumber" value="'.$bus->bus_tour_blocked.'"  readonly>
        </div>
    </div>

    <!-- Radio buttons and input boxes for "Branch Blocked" class -->
    <div class="col-md-3">
        <div class="form-group">
            <img src="'.base_url('assets/img/red2.png').'" alt="logo">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios4" value="selected-red" data-value="selected-red"> Branch Blocked <i class="input-helper"></i>
                </label>
            </div>
            <input type="hidden" id="selectedRedSeatNumber" name="selectedRedSeatNumber" value="'.$bus->bus_tour_branch_blocked.'" readonly>
        </div>
    </div>




    <!-- Radio buttons and input boxes for "Branch Blocked" class -->
<div class="col-md-3">
    <div class="form-group">
        <img src="'.base_url('assets/img/booked_seat_img.gif').'" alt="logo">
        <div class="form-check">
        <label class="form-check-label">
        Confirmed Seat
        </label>
    </div>

    </div>
</div>


<!-- Radio buttons and input boxes for "Branch Blocked" class -->
<div class="col-md-3">
    <div class="form-group">
        <img src="'.base_url('assets/img/pink_seats.png').'" alt="logo">
        <div class="form-check">
        <label class="form-check-label">
       Female Occupied
        </label>
    </div>

    </div>
</div>

    <div class="col-md-3">
        <div class="form-group">
<input type="checkbox" id="selectAllSeats" checked> All Select
</div>
</div>
</div>


<div id="list-bus-container">';
$seatno = 1;

for ($row = 1; $row <= $rows; $row++) {
    $html .= '<div class="colsview">';
    for ($col = 1; $col <= $columns; $col++) {
        $boxNumber = ($row - 1) * $columns + $col;
        
          $seatNumber= in_array($boxNumber, $selectedSeats) ? $seatno++ : '';
          
          if($seatNumber ==''){
             $seatClass = ''; 
          }  else if(in_array($seatNumber, $bus_tour_confirm_femail)) {
            $seatClass = 'selected-femail'; 
        } else if(in_array($seatNumber, $tour_confirm)) {
            $seatClass = 'selected-booked'; 
        }  else if(in_array($seatNumber, $website_available)){
           $seatClass = 'selected-skyblue'; 
        } else if(in_array($seatNumber, $available)) {
            $seatClass = 'selected-blue'; 
        } else if(in_array($seatNumber, $blocked)) {
            $seatClass = 'selected-yellow'; 
        } else if(in_array($seatNumber, $branch_blocked)) {
            $seatClass = 'selected-red'; 
        } else {
             $seatClass = in_array($boxNumber, $selectedSeats) ? 'selected' : '';
        }
       
        
                    if ($vehicle_seat_type == 'R' and $seatNumber!='') {
    $seatview = $seatNumber;
} else if ($vehicle_seat_type == 'S' and $seatNumber!='') {
    $seatview = 'S';
} else {
    $seatview = '';
}
       
        
        // Check if the seat is selected and assign the appropriate number, otherwise keep it blank
      

        $html .= '<div class="seatview ' . $seatClass . '" data-boxnumber="' . $boxNumber . '" data-seatNumber="' . $seatNumber . '">' . $seatview . '</div>';
    }
    $html .= '</div>';
}

$html .= '</div>';




       ?>


<script>
var seatBoxes = document.querySelectorAll('.seatview');
var radioButtons = document.querySelectorAll('input[name="optionsRadios"]');
var selectedRadioButton = null;
var selectedClass = null;

var selectAllCheckbox = document.getElementById('selectAllSeats');

selectAllCheckbox.addEventListener('change', function () {
    var isChecked = this.checked;

    seatBoxes.forEach(function (seatBox) {
        var seatNumber = seatBox.getAttribute('data-seatnumber');
        var isSelected = seatBox.classList.contains(selectedClass);
        var hasSelectedClass = seatBox.classList.contains('selected');

if (seatNumber !== '') {
     
          var oldClassElement = document.querySelector('.seatview[data-seatnumber="' + seatNumber + '"]');
var classListWithoutSeatview = Array.from(oldClassElement.classList).filter(className => className !== 'seatview');
  var oldClass = classListWithoutSeatview.join(' ');
          
            if(oldClass !== 'selected-booked' && oldClass !== 'selected-femail') {
           
              seatBox.classList.replace(oldClass, 'selected');
              
               updateInputBox(oldClass);
              
              seatBox.classList.replace('selected', selectedClass);
              updateInputBox(selectedClass);
            }
}

        
    });
});

radioButtons.forEach(function (radioButton) {
    radioButton.addEventListener('change', function () {
        selectedRadioButton = radioButton;
        selectedClass = selectedRadioButton.getAttribute('data-value');

        // Update the selected class attribute of the "Select All" checkbox
        selectAllCheckbox.setAttribute('data-selected-class', selectedClass);

        // Update the corresponding input box
        updateInputBox(selectedClass);
    });
});

seatBoxes.forEach(function (seatBox) {
    seatBox.addEventListener('click', function () {
        if (selectedClass) {
            var seatNumber = this.getAttribute('data-seatnumber');
            var isSelected = this.classList.contains(selectedClass);
            var hasSelectedClass = seatBox.classList.contains('selected');
            
          var oldClassElement = document.querySelector('.seatview[data-seatnumber="' + seatNumber + '"]');
var classListWithoutSeatview = Array.from(oldClassElement.classList).filter(className => className !== 'seatview');
  var oldClass = classListWithoutSeatview.join(' ');
          
          
         
             if(oldClass != 'selected-booked' &&  oldClass != 'selected-femail') {
            
              seatBox.classList.replace(oldClass, 'selected');
               updateInputBox(oldClass);
              
              seatBox.classList.replace('selected', selectedClass);
            
         

            updateInputBox(selectedClass);
             }
        }
    });
});

function updateInputBox(selectedClass) {
    var inputBoxId = selectedClass.split('-').map(function (word, index) {
        return (index === 0) ? word : word.charAt(0).toUpperCase() + word.slice(1);
    }).join('') + 'SeatNumber';

    var inputBox = document.getElementById(inputBoxId);

    if (inputBox) {
        var selectedSkyBlueSeats = document.querySelectorAll('.' + selectedClass);
        var seatNumbers = [];

        selectedSkyBlueSeats.forEach(function (seat) {
            var seatNumber = seat.getAttribute('data-seatnumber');
            if (seatNumber !== null) {
                seatNumbers.push(seatNumber);
            }
        });

        inputBox.value = seatNumbers.join(', ');
    }
}



</script>



<?php 



        return $html;
    }




    public function future_booking_date($bus_id)
    {

        $currentDate = date('Y-m-d');
        $querypresent = $this->db->table('bus_tour')
        ->where('bus_tour_bus_id', $bus_id)
        ->where('DATE(bus_tour_date) >', date('Y-m-d')) // Use the current date in "Y-m-d" format
        ->where('bus_tour_available >=', 0)
        ->where('bus_tour_status', 'Active')
        ->get();
    
    if ($querypresent->getNumRows() > 0) {
        return $querypresent->getRow()->bus_tour_date;
    } else {
        return null; // Handle the case when no results are found.
    }
    }




   
       public function send_sms($mobile, $message_text)
{
    $mobilenumber = trim($mobile);
    $url = "https://sms.tsence.com/app/smsapi/index.php";
    $message = urlencode($message_text);
    
    $data = array(
        'key' => '36613CA59DF5C3',
        'campaign' => '14327',
        'routeid' => '7',
        'type' => 'text',
        'contacts' => $mobilenumber,
        'senderid' => 'PANICR',
        'msg' => $message,
        'template_id' => '1707161175173071100',
        'pe_id' => '1701160014818575349'
    );

    $ch = curl_init();
    if (!$ch) {
        die("Couldn't initialize a cURL handle");
    }
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);          
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    $curlresponse = curl_exec($ch); // execute
    
    if ($curlresponse === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return "Error: $error";
    } else {
        curl_close($ch);
        return "SuccessDoneOTP";
    }
}




public function get_variant_tab($id)
{
    // Check if the ID contains non-numeric characters (e.g., '1k', '1p')
    if (preg_match('/\D/', $id)) { // \D matches any non-digit character
        // Handle the case where the ID contains a string
        return '<a href="javascript:;" class="d-flex align-items-center justify-content-center">'.$id.'</a>';
    }

    // Fetch attributes from the database
    $attributes = $this->get_single_query('attributes', array('attributes_id' => $id)); 

    if ($attributes) {
        if (!empty($attributes->attributes_symbol)) {
            return '<a href="javascript:;" class="filter-color border-0" style="background-color: '.$attributes->attributes_symbol.';"></a>';
        } else if (!empty($attributes->attributes_name)) {
            return '<a href="javascript:;" class="d-flex align-items-center justify-content-center">'.$attributes->attributes_name.'</a>';
        } else {
            return '<a href="javascript:;" class="d-flex align-items-center justify-content-center">'.$id.'</a>';
        }
    } else {
        return '<a href="javascript:;" class="d-flex align-items-center justify-content-center">'.$id.'</a>';
    }
}


public function get_variant_name($id)
{
     if (preg_match('/\D/', $id)) { // \D matches any non-digit character
        // Handle the case where the ID contains a string
        return $id;
    }
    
    $attributes = $this->get_single_query('attributes', ['attributes_id' => $id]);
    return $attributes->attributes_name ?? $id;
}

public function getnamecategory($id)
{
    try {

        $commanmodel = new Commanmodel();

        $name = '';

        // Empty check
        if (empty($id)) {
            return '';
        }

        $currentCategory = $commanmodel->get_single_query(
            'category',
            ['category_id' => $id]
        );

        // If category not found
        if (!$currentCategory) {
            return '';
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent infinite loop
        |--------------------------------------------------------------------------
        */

        if (
            isset($currentCategory->parent_id) &&
            !empty($currentCategory->parent_id) &&
            $currentCategory->parent_id != $currentCategory->category_id
        ) {

            $name .= $this->getnamecategory($currentCategory->parent_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Add current category
        |--------------------------------------------------------------------------
        */

        $name .= ($currentCategory->category_name ?? 'Unknown') . ' > ';

        return $name;

    } catch (\Throwable $e) {

        // Error aaye tab bhi system na ruke
        log_message('error', 'Category Error: ' . $e->getMessage());

        return '';
    }
}

public function product_rating($course_id)
{
    $db = \Config\Database::connect();
    $builder = $db->table('reviews');
    $query = $builder->select('rating, user_name, message, review_date')
                     ->where('product_id', $course_id)
                     ->where('reviews_status', 'Active')
                     ->get();

    $output = '';
    $reviews_output = '';
    $review_count = $query->getNumRows();

    if ($review_count > 0) {
        $total_rating = 0;

        foreach ($query->getResult() as $row) {
            $total_rating += $row->rating;

            $review_date = date("F j, Y", strtotime($row->review_date));
            $user_name = htmlspecialchars($row->user_name);
            $message = htmlspecialchars($row->message);
            $rating = $row->rating;

            // Generate dynamic stars for individual rating
            $stars_html = '';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    $stars_html .= '<li><a href="#"><i class="bi bi-star-fill"></i></a></li>';
                } elseif ($i - 0.5 == $rating) {
                    $stars_html .= '<li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>';
                } else {
                    $stars_html .= '<li><a href="#"><i class="far fa-star"></i></a></li>';
                }
            }

            $reviews_output .= '    <li>
                                                        <div class="author-img">
                                                            
                                                        </div>
                                                        <div class="comment-content">
                                                            <div class="author-post style-2">
                                                                <div class="author-info">
                                                                    <h5>' . $user_name . ', <span> ' . $review_date . '</span></h5>
                                                                </div>
                                                                <ul class="rating">
                                                                ' . $stars_html . '
                                                                    
                                                                </ul>
                                                            </div>
                                                            <p>' . $message . '</p>
                                                       
                                                        </div>
                                                    </li>';
        }

        $average_rating = $total_rating / $review_count;
        $rating_round = round($average_rating * 2) / 2;
        $rating_percentage = ($average_rating / 5) * 100;

        // Generate average rating stars
        $output = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating_round) {
                $output .= '<li><a href="#"><i class="fas fa-star"></i></a></li>';
            } elseif ($i - 0.5 == $rating_round) {
                $output .= '<li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>';
            } else {
                $output .= '<li><a href="#"><i class="far fa-star"></i></a></li>';
            }
        }

        $outputnum = number_format($average_rating, 1, '.', '');
    } else {
        $outputnum = 0.00;
        $rating_percentage = 0;
        $output = '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
        $reviews_output = '<p>No reviews yet.</p>';
    }

    return [
        'rat' => $outputnum,
        'review' => $output,
        'reviews_list' => $reviews_output,
        'rating_percentage' => $rating_percentage,
        'review_count' => $review_count
    ];
}





function shiprockt_api_token(){
             
               	$url = 'https://apiv2.shiprocket.in/v1/external/auth/login';
        $data = array(  "email"   => "sevaykaceramics@gmail.com",
                        "password" => "Ship@rocket123");
            
        $postdata = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode( $result );
        
		return $token = $response->token; 
		
        }

          public function shippingrate($product_id,$pay,$pincode,$price)
    {
       
      $productName = $this->Commanmodel->get_single_query('product',array('product_id' => $product_id));
      

     
       $shipping_length = $productName->length;
      $shipping_width = $productName->width;
      $shipping_height = $productName->height;
      $shipping_weight = $productName->weight;
       $mrp =$price;
       

     
      
     if($pay == 'COD') {
         $methid = '1';
     } elseif($pay == 'Online') {
          $methid = '0';
     } else {
          $methid = '1';
     }

  
  if(!empty($pincode)) { 
      $pincodes = $pincode;
  } else {
       $pincodes = '110024';
  }
  

                        $shipaarya = array(
    "pickup_postcode" => '110024',
	"delivery_postcode"=> $pincodes,
	"cod"=> $methid,
    "weight" => $shipping_weight,
	"length"=> $shipping_length,
	"breadth"=> $shipping_width,
	"height"=> $shipping_height
	
  
);


            $token=$this->Commanmodel->shiprockt_api_token();
 	$urls = "https://apiv2.shiprocket.in/v1/external/courier/serviceability?token=$token";	    
           
		 $postdata = json_encode($shipaarya);
		

		
        $ch = curl_init($urls);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode( $result );  

  $responses = json_decode($result, TRUE);
 

   
    
           $data = array(             
        'category_name' =>$responses['data']['available_courier_companies']['0']['courier_name'],
     
        'rate' =>$responses['data']['available_courier_companies']['0']['rate'] 
      
       
            );
         
    
    
    
         return $data;


    }

}
