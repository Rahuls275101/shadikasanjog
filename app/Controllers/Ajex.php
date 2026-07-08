<?php

namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\Commanmodel;
use App\Models\Productmodel;
use App\Models\Usermodel;
use App\Libraries\Cart;

class Ajex extends BaseController
{
    
    
    
    public function remove_cart_product()
    {    
       $cart = new \App\Libraries\Cart();
           
           $cart->remove($this->request->getVar('product_token_id'));
           
           
      
       
    }
    
    
     public function getCastes()
    {
        $religion_id = $this->request->getPost('religion_id');
       $commanmodel = new Commanmodel();
        $castes =  $commanmodel->all_multiple_query_order_by('castes',array('religion_id'=> $religion_id),'id','ASC');
        
       

        return $this->response->setJSON($castes);
    }
    
    
   public function update_cart()
    {    
       $cart = new \App\Libraries\Cart();
       
        $id = $this->request->getVar('product_token_id');
		$qty = $this->request->getVar('qty');	
         $data = array(
                'rowid' => $id,
                'qty'   => $qty
            );
            
            $cart->update($data);
            
            echo "success";
        
       
    }
    
    public function remove_discount()
    { 
        $session = session();
         $session->remove('coupon_applied');
    }
    
    
  public function add_to_cart() {
    $cart = new \App\Libraries\Cart();
    $commanmodel = new Commanmodel();
    
    $product_id = $this->request->getVar('product_id');
    $variant = $this->request->getVar('variant');
    $addqty = $this->request->getVar('addqty');
    $variant_yes = $this->request->getVar('variant_yes');
    
    $product = $commanmodel->get_single_query('product', ['product_id' => $product_id]);
    

    $price = $product->product_price;
    $img = $product->product_thumbnail;
    $avvariant = '';
    
    if ($variant_yes === 'Yes') {
        $pro_variant = $commanmodel->get_single_query('pro_variant', ['variant_pro_id' => $product_id, 'varian' => $variant]);
        if ($pro_variant) {
            $price = $pro_variant->pro_variant_price;
            $avvariant = $pro_variant->varian;
            $img = $pro_variant->pro_variant_image ?: $product->product_thumbnail;
        }
    }
    
    $item = [
        'id'    => $product_id,
        'qty'   => $addqty,
        'price' => $price,
        'name'  => $product->product_name,
        'varian' => $avvariant,
        'image' => $img
    ];
    

  
    $output = $cart->insert($item)
        ? ['alert_class' => 'success', 'alert_title' => 'Product added', 'alert_message' => 'Product added to cart']
        : ['alert_class' => 'faild', 'alert_title' => 'Error', 'alert_message' => 'Failed to add product'];
    
    echo json_encode($output);
}


function my_cart_list()
{
    $cart = new \App\Libraries\Cart();
    $commanmodel = new Commanmodel();
    $session = session();
    $webInfo = $commanmodel->get_single_query('address', ['id' => 1]);
 
  
    // Get shipping charges
    if ($session->has('shipping_charges')) {
        $shipping_charges = $session->shipping_charges;
        $shipping = $shipping_charges['shipcharge'];
        
    } else {
        $charges = $commanmodel->get_single_query('shipcharge', ['shipcharge_id' => 1]);
        $shipping = $charges->shipcharge;
    }
   

    $output = '  <thead><tr>
              <th class="thumb-img-titl">Item</td>
              <th class="item-decription-titl"></td>
              <th class="price-column-titl">Price</td>
              <th class="price-column-titl">Total</td>
            </tr></thead>';
    $subTotalView = 0;
    $shippingtotal = 0;
    $exclusiveGST = 0;
    $no = 0;
    $total_cart_amount = 0;

    $availableCart = $cart->contents();

    if (count($availableCart)) {
        foreach ($availableCart as $items) {
            $productName = $commanmodel->get_single_query('product', ['product_id' => $items['id']]);
            $no++;
            $taxRate = 0;
            $tax = 0;

            // Check if GST is inclusive or exclusive
            if ($productName->inclusive_gst !== 'Yes') {
                $tax = $productName->gst;
                $taxRate = ($items['price'] * $items['qty'] * $tax) / 100; 
                $exclusiveGST += $taxRate;
            }

            // Calculate subtotal for each product
            $subTotal = ($items['price'] * $items['qty']); 
            $subTotalView += $subTotal;

            $productImage = base_url('assets/images/' . $items['image']);

            // Build product row output
            $output .= ' <tr>
              <td class="product-thumbnail"><img src="' . $productImage . '" alt="' . htmlspecialchars($items['name']) . '" style="width: 87px;"></td>
              <td class="product-quantity"><h3>' . htmlspecialchars($items['name']) . '</h3>
                <ul>
                  <li>Quantity: 
                    ' . $items['qty'] . ' cards </li>
             
                  
                  
                  <li class="edit-description delete_cart_value" data-reproductid="' . htmlspecialchars($items['rowid']) . '"><a href="#" onclick="#">Remove This Order</a></li>
                </ul></td>
              <td class="product-price"><div>₹' . number_format($items['price'], 2) . ' </div></td>
              <td class="product-subtotal"><div>' . 
            ($taxRate > 0 
                ? 'Exclusive GST ₹' . number_format($taxRate, 2) . ' + ' 
                : ''
            ) . '₹' . number_format($subTotal, 2) . '</div></td>
            </tr>
            
           
   ';

            // Increment shipping total for each product
            $shippingtotal += $shipping * $items['qty'];
        }

        // Apply coupon if exists
        if ($session->has('coupon_applied')) {
            $couponApplied = $session->coupon_applied;
            $discount = $commanmodel->get_coupon_discount(
                $couponApplied['coupon_type'],
                $couponApplied['coupon_value'],
                $subTotalView
            );

            $totalAfterDiscount = $subTotalView - $discount;
            $shippingall = ($webInfo->free_shipping > 0 && $totalAfterDiscount < $webInfo->free_shipping) ? $shippingtotal : 0;
            $totalWithGST = $totalAfterDiscount + $exclusiveGST + $shippingall;

            $total_cart_amount = $this->build_cart_totals($totalAfterDiscount, $shippingall, $discount, $totalWithGST, $exclusiveGST);
        } else {
            $shippingall = ($webInfo->free_shipping > 0 && $subTotalView < $webInfo->free_shipping) ? $shippingtotal : 0;
            $totalWithGST = $subTotalView + $exclusiveGST + $shippingall;

            $total_cart_amount = $this->build_cart_totals($subTotalView, $shippingall, 0, $totalWithGST, $exclusiveGST);
        }
    } else {
        $output = '<span style="font-size: 20px; color: red;">Cart Empty!</span>
            <br><div class="cart_navigation">
                <a class="continue-btn" href="' . base_url() . '">
                    <i class="fa fa-arrow-left"></i>&nbsp; Continue shopping
                </a>
            </div>';
    }

    // Prepare response data
    $data = [
        'cartSummaryList' => $output,
        'cartSummary' => $total_cart_amount ,
        'checkoutSummary' => '', 
        'totalSummary' => $no,
    ];

    echo json_encode($data);
}










function build_cart_totals($subTotal, $shipping, $discount, $totalWithGST, $exclusiveGST)
{
    $html = '';

    // Subtotal
    $html .= '<tr>
                <td>Subtotal (Incl. GST):</td>
                <td class="text-end fw-bold">AED' . number_format($subTotal, 2) . '</td>
              </tr>';

    // Exclusive GST (if any)
    if ($exclusiveGST > 0) {
        $html .= '<tr>
                    <td>Exclusive GST:</td>
                    <td class="text-end fw-bold">AED' . number_format($exclusiveGST, 2) . '</td>
                  </tr>';
    }

    // Shipping
    $html .= '<tr>
                <td>Shipping:</td>
                <td class="text-end fw-bold">AED' . number_format($shipping, 2) . '</td>
              </tr>';

    // Discount (if any)
    if ($discount > 0) {
        $html .= '<tr>
                    <td>Discount <a href="#" class="remove_discount text-danger ms-2">(Remove)</a>:</td>
                    <td class="text-end text-success fw-bold">-AED' . number_format($discount, 2) . '</td>
                  </tr>';
    }

    // Total
    $html .= '<tr class="border-top pt-3">
                <td class="fw-bold fs-5">Total (Incl. GST):</td>
                <td class="text-end fw-bold fs-5">AED' . number_format($totalWithGST, 2) . '</td>
              </tr>';


    return $html;
}





    
     public function mini_cart() { 
    $cart = new \App\Libraries\Cart();
    $commanmodel = new Commanmodel();

    $availableCart = $cart->contents();
    $totalAmount = 0;
    $output = '';
    $outputfooter = '';

    if (count($availableCart) > 0) {
        foreach ($availableCart as $items) {
            $productImage = base_url('assets/images/' . $items['image']);
            $productUrl = '#'; // Update with product link if available
            
            
            
            $output .= '   <li class="single-item">
                                    <div class="item-area">
                                        <div class="item-img">
                                            <img src="' . $productImage . '" alt="' . htmlspecialchars($items['name']) . '">
                                        </div>
                                        <div class="content-and-quantity">
                                            <div class="content">
                                                <div
                                                    class="price-and-btn d-flex align-items-center justify-content-between">
                                                    <span> <div class="new-small">AED</div>' . number_format($items['price'], 2) . ' <del></span>
                                                    <button class="close-btn">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                                <p><a href="' . $productUrl . '">' . htmlspecialchars($items['name']) . '</a></p>
                                            </div>
                                            <div class="quantity-area">
                                                <div class="quantity">
                                                    <a class="quantity__minus"><span><i
                                                                class="bi bi-dash"></i></span></a>
                                                    <input name="quantity" type="text" class="quantity__input"
                                                        value="' . htmlspecialchars($items['qty']) . '">
                                                    <a class="quantity__plus delete_cart_value" data-reproductid="' . htmlspecialchars($items['rowid']) . '" id="remove' . htmlspecialchars($items['rowid']) . '" ><span><i
                                                                class="bi bi-plus"></i></span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>';
            
        
            
            $totalAmount += $items['price'] * $items['qty'];
        }

        $outputfooter = '
            <a href="' . base_url('cart') . '" class="btn btn-dark btn-outline btn-rounded">View Cart</a>
            <a href="' . base_url('checkout') . '" class="btn btn-primary  btn-rounded">Checkout</a>';
    } else {
        $output .= '<p class="empty-cart">Your cart is empty.</p>';
    }

    $data = [
        'miniCartDetail' => $output,
        'miniCartfooter' => $outputfooter,
        'totalAmount' => 'AED'. number_format($totalAmount, 2),
        'totalCount' => $cart->totalItems()
    ];

    echo json_encode($data);
    exit;
}



 public function apply_pin_code()
{
   
    $commanmodel = new Commanmodel();
    $session = session();
     $pin = $this->request->getVar('pin');
     $session->remove('shipcharge');

      $charges = $commanmodel->get_single_query('shipcharge', ['shipcharge_pin' => $pin]);
      
      if($charges) {
           $shipping = $charges->shipcharge;
      } else {
           $charges = $commanmodel->get_single_query('shipcharge', ['shipcharge_id' => 1]);
        $shipping = $charges->shipcharge;
      }
      
        $chargesValue = [
                                'shipcharge' => $shipping
                                
                            ];

                            $session->set('shipping_charges', $chargesValue);
                            
                          $output = [
                                'alert_class' => 'success',
                                'alert_title' => 'Pin Applied',
                                'alert_message' => 'Your Pin has been successfully applied.',
                            ];
     
      echo json_encode($output);
}

    
 public function apply_coupon_code()
{
    $cart = new \App\Libraries\Cart();
    $commanmodel = new Commanmodel();
    $session = session();

    // Initialize default output
    $output = ['alert_class' => 'danger', 'alert_title' => 'Error', 'alert_message' => 'Something went wrong.'];

    // Check if user is logged in
    if ($session->has('loggedin')) {
        $coupon_code = $this->request->getVar('coupon_code');

        // Check if coupon code is provided
        if (!empty($coupon_code)) {
            $availableCart = $cart->totalItems();

            // Ensure the cart has items
            if ($availableCart > 0) {
                $checkValid = $commanmodel->check_valid_code($coupon_code);

                // Check if coupon code is valid
                if ($checkValid) {
                    $currentDate = date('Y-m-d');
                    $couponStartDate = $checkValid->coupon_start_date;
                    $couponEndDate = $checkValid->coupon_end_date;

                    // Check if the coupon is within the valid date range
                    if ($currentDate >= $couponStartDate && $currentDate <= $couponEndDate) {
                         $usersession = $session->get('loggedin');
                        $userId = $usersession['user_id'];

                        $data = [
                            'coupon_code' => $checkValid->coupon_code,
                            'coupon_type' => $checkValid->coupon_type,
                            'coupon_value' => $checkValid->coupon_value,
                            'order_book_user_id' => $userId,
                        ];

                        // Check if the coupon has already been used
                        $checkValue = $commanmodel->check_used_coupon('order_book', $data);
                    

                        if ($checkValue) {
                            $output = [
                                'alert_class' => 'warning',
                                'alert_title' => 'Coupon Already Used',
                                'alert_message' => 'You have already used this coupon.',
                            ];
                        } else {
                            $couponValue = [
                                'coupon_code' => $checkValid->coupon_code,
                                'coupon_type' => $checkValid->coupon_type,
                                'coupon_value' => $checkValid->coupon_value,
                                'user_id' => $userId,
                            ];

                            $session->set('coupon_applied', $couponValue); // Save coupon to session
                            $output = [
                                'alert_class' => 'success',
                                'alert_title' => 'Coupon Applied',
                                'alert_message' => 'Your coupon has been successfully applied.',
                            ];
                        }
                    } else {
                        $output = [
                            'alert_class' => 'danger',
                            'alert_title' => 'Coupon Expired',
                            'alert_message' => 'This coupon has expired or is not active yet.',
                        ];
                    }
                } else {
                    $output = [
                        'alert_class' => 'danger',
                        'alert_title' => 'Invalid Coupon',
                        'alert_message' => 'The coupon code you entered is invalid.',
                    ];
                }
            } else {
                $output = [
                    'alert_class' => 'warning',
                    'alert_title' => 'Cart Empty',
                    'alert_message' => 'Please add items to your cart before applying a coupon.',
                ];
            }
        } else {
            $output = [
                'alert_class' => 'warning',
                'alert_title' => 'Coupon Code Missing',
                'alert_message' => 'Please enter a coupon code to apply.',
            ];
        }
    } else {
        // User not logged in
        $output = [
            'alert_class' => 'warning',
            'alert_title' => 'Login Required',
            'alert_message' => 'Please log in to apply a coupon.',
        ];
    }

    // Return the output as JSON
    echo json_encode($output);
}

       
    
    
  public function getCity(){
      $commanmodel = new Commanmodel();
        $state =  $this->request->getVar('state_id');
        $data = $commanmodel->getCity($state);
        echo json_encode($data);
    }
    
      public function getattributes(){
      $commanmodel = new Commanmodel();
        $id =  $this->request->getVar('id');
        
        
        $main = $commanmodel->get_single_query('attribute_main',array('attribute_main_id'=> $id));
$attributes = $commanmodel->all_multiple_query_order_by('attributes',array('main_id' => $main->attribute_main_id),'attributes_id','ASC');

      $data = [
         'main'=> $main,
         'attributes'=> $attributes,
      
      ];
        
        
        echo json_encode($data);
    }
    
public function getCategory() {
    ?>
    <style>
       .tree-container {
    font-family: 'Arial', sans-serif;
    font-size: 16px;
    color: #333;
    margin-top: 10px;
}

.search-box {
    width: 100%;
    padding: 8px 12px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

ul {
    list-style: none;
    padding-left: 20px;
    margin: 0;
}

.category-item {
    position: relative;
    padding-left: 20px;
    margin-left: 10px;
    line-height: 1.8;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.category-item:hover {
    background-color: #f5f5f5;
    border-radius: 5px;
}

.category-item:before {
    content: "└";
    position: absolute;
    left: -20px;
    top: 0;
    font-size: 18px;
    color: #6c757d;
}

.category-item:not(:last-child):before {
    content: "├";
    
}

.toggle-icon {
    margin-left: -18px;
    cursor: pointer;
    color: #5e5e5e;
    transition: transform 0.3s ease;
}

.toggle-icon:before {
    content: "\25B6"; /* Right-pointing triangle */
    font-size: 18px;
}

.toggle-icon.open:before {
    content: "\25BC"; /* Downward-pointing triangle */
}

input[type="checkbox"] {
    margin-right: 8px;
}

.selected-category {
    background-color: #cce5ff;
    border-radius: 4px;
    font-weight: bold;
}

.inner_ul {
    display: none;
    padding-left: 20px;
    margin-top: 5px;
}

.expanded > .inner_ul {
    display: block;
}

input[type="checkbox"]:checked + span {
    font-weight: bold;
    color: #007bff;
}

    </style>

    <?php
    // Recursive function to build the category tree
    function buildCategoryTree($categories, $parentId = 0) {
        $tree = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = buildCategoryTree($categories, $category->category_id);
                if ($children) {
                    $category->children = $children;
                }
                $tree[] = $category;
            }
        }
        return $tree;
    }

    // Fetch categories and build the category tree
    $commanmodel = new Commanmodel();
    $parentId = $this->request->getVar('id');
    $selected = $this->request->getVar('select');
    $categories = $commanmodel->all_multiple_query_order_by('category', ['category_status' => 'Active'], 'category_id', 'ASC');
    $categoryTree = buildCategoryTree($categories, $parentId);

    // Determine which categories need to be expanded
    $expandedCategories = [];
    if ($selected) {
        $expandedCategories[] = $selected;
        $currentCategory = $commanmodel->get_single_query('category', ['category_id' => $selected]);
        if($currentCategory) {
        while ($currentCategory->parent_id > 0) {
            $expandedCategories[] = $currentCategory->parent_id;
            $currentCategory = $commanmodel->get_single_query('category', ['category_id' => $currentCategory->parent_id]);
        }
        }
    }
    ?>

    <div class="tree-container">
        <input type="text" id="category-search" placeholder="Search categories..." class="search-box">
        <div class="tree_main">
            <ul id="bs_main">
                <?php
                // Render the category tree as HTML
                function renderCategoryTree($tree, $selected, $expandedCategories) {
                    $output = '';
                    foreach ($tree as $category) {
                        $isChecked = ($selected == $category->category_id) ? 'checked' : '';
                        $isExpanded = in_array($category->category_id, $expandedCategories) ? 'open' : '';
                        $isSelected = ($selected == $category->category_id) ? 'selected-category' : '';
                        $isExpandeds = in_array($category->category_id, $expandedCategories) ? 'display: block;' : 'display: none;';

                   $output .= '<li id="bs_' . $category->category_id . '" class="category-item ' . $isSelected . '">
                        <span class="toggle-icon ' . $isExpanded . '"></span>
                        <input type="checkbox" class="category-checkbox" id="c_bs_' . $category->category_id . '" ' . $isChecked . ' name="parent_id" value="' . $category->category_id . '" />
                        <span>' . $category->category_name . '</span>';
        
        if (isset($category->children)) {
            $output .= '<ul id="bs_l_' . $category->category_id . '" class="inner_ul ' . $isExpanded . '" style="' . $isExpandeds . '">';
            $output .= renderCategoryTree($category->children, $selected, $expandedCategories);
            $output .= '</ul>';
        }
        
        $output .= '</li>';

                    }
                    return $output;
                }

                echo renderCategoryTree($categoryTree, $selected, $expandedCategories);
                ?>
            </ul>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Toggle categories
            $("body").on("click", ".toggle-icon", function () {
                const $subTree = $(this).siblings("ul");
                $subTree.slideToggle();
                $(this).toggleClass("open");
            });

            // Checkbox selection
            $("body").on("change", "input[type=checkbox]", function () {
                $("input[type=checkbox]").not(this).prop('checked', false); // Allow only one selection
            });

            // Search functionality
            $("#category-search").on("input", function () {
                const query = $(this).val().toLowerCase();
                $("ul#bs_main li").each(function () {
                    const categoryName = $(this).text().toLowerCase();
                    $(this).toggle(categoryName.includes(query));
                });
            });
        });
    </script>

    <?php
}


public function wishlistapply()
    {
        
         $commanmodel = new Commanmodel();
         
         
         
    $session = session();
        if ($session->has('loggedin')) {
            $product_id = $this->request->getVar('product_id');

            if (!empty($product_id)) {
              $usersession = $session->get('loggedin');
                        $userId = $usersession['user_id'];
              
  $wishlistCount=$commanmodel->get_single_query_count('wishlist',array('wishlist_product_id' => $product_id,'wishlist_user_id' => $userId));
              

                if ($wishlistCount) {
                    // Remove from wishlist
                    
                    $wishlistdelete =$commanmodel->delete_query('wishlist',array('wishlist_product_id' => $product_id,'wishlist_user_id' => $userId));
                    if ($wishlistdelete) {
                        return $this->response->setJSON([
                            'alert_class' => 'info',
                            'alert_title' => 'Removed from Wishlist',
                            'alert_message' => 'This product has been removed from your wishlist.',
                            'icon_update' => '<i class="fa fa-heart-o"></i><span>Add to Wishlist</span>'
                        ]);
                    }
                } else {
                    
                 
                    // Add to wishlist
                      $wishlistInserted = $commanmodel->insert_query('wishlist',array('wishlist_product_id' => $product_id,'wishlist_user_id' => $userId,'wishlist_date' => date('Y-m-d')));
                 
                    if ($wishlistInserted) {
                        return $this->response->setJSON([
                            'alert_class' => 'success',
                            'alert_title' => 'Added to Wishlist',
                            'alert_message' => 'This product has been added to your wishlist.',
                            'icon_update' => '<i class="fa fa-heart" style="color:#f7ed4d;font-size:14px;"></i>'
                        ]);
                    }
                }

                // Failure response
                return $this->response->setJSON([
                    'alert_class' => 'error',
                    'alert_title' => 'Failed',
                    'alert_message' => 'Something went wrong. Please try again.'
                ]);
            }

            // Invalid product ID
            return $this->response->setJSON([
                'alert_class' => 'error',
                'alert_title' => 'Invalid Request',
                'alert_message' => 'Product ID is missing.'
            ]);
        }

        // User not logged in
        return $this->response->setJSON([
            'alert_class' => 'warning',
            'alert_title' => 'Please Login',
            'alert_message' => 'You need to log in to manage your wishlist.'
        ]);
    }
    
    
        public function review_submit()
    {
        
        
           $commanmodel = new Commanmodel();
         
    $session = session();
        if ($session->has('loggedin')) {
            
              
         $validation =  \Config\Services::validation();

      


               $rules = [
             'review_user_name' => [
                'label'  => 'Name',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter Name',
                    
                ],
            ],
            'review_user_email' => [
                'label'  => 'Email',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter email',
                   
                ],
            ],
      
          
           
           
        ];
             
            
        
       
                if ($this->validate($rules))
                
                {
                       $usersession = $session->get('loggedin');
             
          
                $ValidUserId =  $usersession['user_id'];
                
                $post_data = [
                'user_id' => $ValidUserId,
                'product_id' => $this->request->getVar('review_product_id'),
             
                'rating' => $this->request->getVar('rating3'),
                'user_name' => $this->request->getVar('review_user_name'),
                'user_email' => $this->request->getVar('review_user_email'),
                'message' => $this->request->getVar('review_message'),
                'review_date' => date('Y-m-d')
            ];


         
                   $createdAccount = $commanmodel->insert_query('reviews',$post_data); 
                   
                   if($createdAccount)
                   {
                        return $this->response->setJSON([
                            'alert_class' => 'success',
                            'alert_title' => 'Thank You!',
                            'alert_message' => 'Your review has been submitted successfully.',
                        ]);
                   }
                   else
                   {
                        return $this->response->setJSON([
                        'alert_class' => 'danger',
                        'alert_title' => 'Submission Failed',
                        'alert_message' => 'There was an error submitting your review. Please try again later.',
                    ]);
                   }
                } else {
                    
                         return $this->response->setJSON([
                    'alert_class' => 'warning',
                    'alert_title' => 'Validation Failed',
                    'alert_message' => 'Please fill out all required fields.',
                    'validation_errors' => $validation->getErrors() // Return validation errors
                ]);
               
                }
                
        } else {
             return $this->response->setJSON([
            'alert_class' => 'warning',
            'alert_title' => 'Please Login',
            'alert_message' => 'You need to log in to manage your wishlist.'
        ]);
        }
    }
    
    
    
    
    
      public function ordercancel_submit()
    {
        
        
           $commanmodel = new Commanmodel();
         
    $session = session();
        if ($session->has('loggedin')) {
            
              
         $validation =  \Config\Services::validation();

      


               $rules = [
             'cancel_reason' => [
                'label'  => 'cancel reason',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please enter cancel reason',
                    
                ],
            ],
    
      
          
           
           
        ];
             
            
        
       
                if ($this->validate($rules))
                
                {
                       $usersession = $session->get('loggedin');
             
          
                $ValidUserId =  $usersession['user_id'];
                
                $post_data = [
                'cancel_reason' => $this->request->getVar('cancel_reason'),
                'booking_product_status' => 'cancelled',
                'concel_date' => date('Y-m-d')
            ];


         $createdAccount =  $commanmodel->update_query('booking_product', $post_data, array('booking_product_order_id' =>$this->request->getVar('orderid'))); 
                  
                   
                   if($createdAccount)
                   {
                        return $this->response->setJSON([
                            'alert_class' => 'success',
                            'alert_title' => 'Order Cancel!',
                            'alert_message' => 'Your Order has been canceled successfully.',
                        ]);
                   }
                   else
                   {
                        return $this->response->setJSON([
                        'alert_class' => 'danger',
                        'alert_title' => 'Submission Failed',
                        'alert_message' => 'There was an error submitting your review. Please try again later.',
                    ]);
                   }
                } else {
                    
                         return $this->response->setJSON([
                    'alert_class' => 'warning',
                    'alert_title' => 'Validation Failed',
                    'alert_message' => 'Please enter cancel reason',
                    'validation_errors' => $validation->getErrors() // Return validation errors
                ]);
               
                }
                
        } else {
             return $this->response->setJSON([
            'alert_class' => 'warning',
            'alert_title' => 'Please Login',
            'alert_message' => 'You need to log in to manage your wishlist.'
        ]);
        }
    }
    
 public function sign_up_newsletter()
    {
        // Get the email from the post data
        $email = $this->request->getVar('email');

        // Check if the email is not empty
        if (!empty($email)) {
            // Load the Commanmodel to interact with the database
            $commanModel = new Commanmodel();

            // Check if the email already exists in the 'newsletter_subscription' table
            $emailCount = $commanModel->get_single_query_count('newsletter_subscription',array('newsletter_email' => $email));

            if ($emailCount > 0) {
                // If the email is already subscribed
                $response = [
                    'failed' => '<div class="alert alert-danger">You are already subscribed!</div>'
                ];
            } else {
                // Insert the email into the 'newsletter_subscription' table
                $subscribed = $commanModel->insert_query('newsletter_subscription',array('newsletter_email' => $email,'newsletter_date' => date('Y-m-d')));

                if ($subscribed) {
                    // If the insertion was successful
                    $response = [
                        'success' => 'You have successfully subscribed to the newsletter'
                    ];
                } else {
                    // If insertion failed
                    $response = [
                        'failed' => '<div class="alert alert-danger">Sorry, Please try again later!</div>'
                    ];
                }
            }
        } else {
            // If the email is empty
            $response = [
                'failed' => '<div class="alert alert-danger">Please fill all mandatory fields(*)</div>'
            ];
        }

        // Return the response as JSON
        return $this->response->setJSON($response);
    }

 }