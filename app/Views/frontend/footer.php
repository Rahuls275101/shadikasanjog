<?php 

use App\Models\Commanmodel;
 $commanmodel = new Commanmodel();
   $addressView = $commanmodel->get_single_query('address',array('id' => 1));
     $category = $commanmodel->all_multiple_query_order_by('category',array('parent_id' => 0),'category_id','ASC');
?> 

    <!-- FOOTER -->
    <section class="wed-hom-footer">
        <div class="container">

            <div class="row wed-foot-link wed-foot-link-1">
                <div class="col-md-3">
                    <h4>Matchmaking Arena</h4>
                    <ul>
                        <li><a href="<?php echo base_url('register'); ?>">Register</a></li>
                        <li><a href="<?php echo base_url('login'); ?>">Log-in</a></li>
                        <li><a href="<?php echo base_url('about-us'); ?>">About Us</a></li>
                        <li><a href="<?php echo base_url('faq'); ?>">FAQ</a></li>
                        <li><a href="<?php echo base_url('plans'); ?>">Membership Plans</a></li>
                        <li><a href="<?php echo base_url('page/refund-policy'); ?>">Refund Policy</a></li>
                        <li><a href="<?php echo base_url('page/terms-and-conditions'); ?>">Terms &amp; Conditions</a></li>
                    </ul>
                </div>
                <!-- Your Safety First -->
                <div class="col-md-3">
                    <h4>Your Safety First</h4>
                    <ul>
                        <li><a href="<?php echo base_url('grievance-redressal'); ?>">Grievance Redressal</a></li>
                        <li><a href="<?php echo base_url('report-abuse'); ?>">Report Abuse</a></li>
                        <li><a href="<?php echo base_url('contact-us'); ?>">Contact Us</a></li>
                        <li><a href="<?php echo base_url('page/privacy-policy'); ?>">Privacy Policy</a></li>
                        <li><a href="<?php echo base_url('page/quick-safety-check'); ?>">Quick Safety Check List</a></li>
                        <li><a href="<?php echo base_url('page/safety-tips-for-on-line-matrimony'); ?>">Safety Tips for On-line Matrimony</a></li>
                    </ul>
                </div>
                <!-- Members Response -->
                <div class="col-md-3">
                    <h4>Members Response</h4>
                    <ul>
                        <li><a href="<?php echo base_url('testimonial'); ?>">Testimonials</a></li>
                        <li><a href="<?php echo base_url('story'); ?>">Success Stories</a></li>
                        <li><a href="<?php echo base_url('blogs'); ?>">Articles and Blogs</a></li>
                    </ul>
                </div>
                <!-- Beyond Matchmaking -->
                <div class="col-md-3">
                    <h4>Beyond Matchmaking</h4>
                    <ul>
                        <li><a href="<?php echo base_url('services'); ?>">Services</a></li>
                        <li><a href="<?php echo base_url('event'); ?>">Events</a></li>
                        <li><a href="<?php echo base_url('advertisements'); ?>">Advertisements</a></li>
                         <!--<li><a href="<?php echo base_url('profile'); ?>">Profile</a></li>-->
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="notebox">
                    <div class="notes">Disclaimer:</div>
                    <marquee direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                        💙 We connect hearts, you make the choices. Kindly verify details before taking the next step.
                    </marquee>

                </div>
            </div>

        </div>
    </section>
    <!-- END -->

    <!-- COPYRIGHTS -->
    <section>
        <div class="cr">
            <div class="container">
                <div class="row">
                    <p>Copyright © <span id="cry">2025</span> <a href="#!" target="_blank">Shadi ka Sanjog </a> All
                        Rights Reserved. Website Design By: <a href="#">Star Web Maker</a></p>


                </div>
            </div>
        </div>
    </section>



    
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo base_url('assets/frontend/'); ?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/frontend/'); ?>/assets/js/popper.min.js"></script>
    <script src="<?php echo base_url('assets/frontend/'); ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets/frontend/'); ?>/assets/js/select-opt.js"></script>
    <script src="<?php echo base_url('assets/frontend/'); ?>/assets/js/slick.js"></script>

    <script src="<?php echo base_url('assets/frontend/'); ?>/assets/js/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.4.6/jquery.sumoselect.min.js'></script>
<script>$(function() {

  
    $('.testselect4').SumoSelect(
      {search: true, searchText: 'Enter here.'}
    );

    
  })</script>
    <script>
        $(document).ready(function () {
            $(".hero-slider").owlCarousel({
                items: 1,
                loop: true,
                autoplay: true,
                autoplayTimeout: 6000,
                animateOut: 'fadeOut',
                dots: false,
                nav: false
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $(".owl-carousel").owlCarousel({
                loop: true,
                margin: 20,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 2500,
                autoplayHoverPause: true,
                responsive: {
                    0: { items: 1 },
                    768: { items: 2 },
                    1024: { items: 3 }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $(".profile-slider").owlCarousel({
                loop: true,
                margin: 0,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayTimeout: 2500,
                autoplayHoverPause: true,
                responsive: {
                    0: { items: 1 },
                    768: { items: 2 },
                    1024: { items: 4 },

                }
            });
        });
    </script>

 <script>
    
$(document).ready(function(){
    
    
    function mini_cart() {
         $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('mini_cart'); ?>",
                data: {
                    action: 'action',
                  
                },
                success: function (data) {
            $(".numbers").text(data.totalCount);
		    $(".totalamoutcart").html(data.totalAmount);
            $('.mini-products-list').html(data.miniCartDetail);
        	$(".mini-products-footer").html(data.miniCartfooter);
                }
            });
    }
    
     function listCartResult() {
   
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('cart-list'); ?>",
        dataType: "json",
        data: {
            type: "listCartResult",
          
        },
        success: function(data) {
            $(".totalSummary").text(data.totalSummary);
            $('.cartSummary').html(data.cartSummary);
            $('.cartSummaryList').html(data.cartSummaryList);
            $('.checkoutSummary').html(data.checkoutSummary);
        }
    });
}


     	$(document).on('click', '.applycoupon', function(e){
  e.preventDefault();
  var coupon_code = $('#coupon_code').val();
   
     $.ajax({
      type: "POST",
       dataType: "json",
      url: "<?php echo base_url('apply_coupon_code'); ?>",
      data: {coupon_code:coupon_code},
      success: function (data) {
     
        showAlert(data.alert_class, data.alert_title, data.alert_message);
        listCartResult()
      }
    });
   
});


	$(document).on('input', '.applypin', function(e) {
    var pin;

    // Get the pin code from the appropriate field
    if ($('#shipping_pinCode').val()) {
        pin = $('#shipping_pinCode').val();
    } else {
        pin = $('#pinCode').val();
    }

    // Validate pin code before proceeding (optional)
    if (pin.length === 6) {  // assuming pin should be 6 digits, adjust as needed
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('apply_pin_code'); ?>",
            data: { pin: pin },  // sending only the pin code
            success: function(data) {
             
                // Assuming you want to update the cart after applying pin code
                listCartResult();  // This function presumably updates the cart with any changes
            },
            error: function(xhr, status, error) {
                console.log("Error applying pin code:", error);  // Handle any error cases
            }
        });
    } else {
        console.log("Invalid pin code");
    }
});



    
     mini_cart();
     listCartResult()
     
     
     $(document).on('click', '.wishlistadd', function (e) {
    e.preventDefault();

    var product_id =  $(this).attr('data-product_id');

    $.ajax({
        type: "POST",
        url: "<?php echo base_url('wishlistapply'); ?>", // CodeIgniter 4 route
        data: { product_id: product_id },
        dataType: "json", // Expect JSON response from server
        success: function (response) {
            // Show SweetAlert based on the response from the server
            Swal.fire({
                icon: response.alert_class || 'info', // Dynamic icon
                title: response.alert_title || 'Notice', // Dynamic title
                text: response.alert_message || 'Something happened.', // Dynamic message
            });

            // Update wishlist icon dynamically if provided
            if (response.icon_update) {
                $('.pro-dis-hart').html(response.icon_update);
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred. Please try again.',
            });
        }
    });
});

    
$(document).on('click', '.delete_cart_value', function () {
    
    var id = $(this).data("reproductid");
  
      
      $.ajax({
      type: "POST",
      url: "<?php echo base_url('remove-cart-product'); ?>",
      data: 'product_token_id=' + id,
      
      success: function (data) {
       mini_cart();
        listCartResult();
      }
    });
    
  });
  
  
  $(document).on('click', '.remove_discount', function () {
    
    var action = 'action';
  
      
      $.ajax({
      type: "POST",
      url: "<?php echo base_url('remove_discount'); ?>",
      data: 'action=' + action,
      
      success: function (data) {
       mini_cart();
        listCartResult();
      }
    });
    
  });
  
  
   $(document).on('change', '.quantity', function () {
     
    var id = $(this).data("reproductid");
    var qty =  $(this).val();
    
     
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('update-cart'); ?>",
        data: {product_token_id: id,qty:qty},
        success: function (data) {
           
        mini_cart();
        listCartResult();
        }
      });
    
  });
   
         $(document).on('click', '.AddToCart', function (e) {
    e.preventDefault();
        var product_id =  $(this).attr('data-product-id');
        var variant =  $(this).attr('data-variant');
        var qty =  $(this).attr('data-qty');
        var variant_yes =  $(this).attr('data-variant-yes'); 
         
        // Check for out-of-stock condition
        if (qty <= 0) {  // If quantity is 0 or less, show out of stock alert
            showAlert('warning', 'Out of stock', 'Product out of stock');
            return; // Stop further execution if out of stock
        }
        
        if($("#qty").val()) {
             var addqty =   $("#qty").val();
        } else {
             var addqty = 1;
        }
        
        

        // Check if the variant is valid
        if ((variant_yes == 'Yes' && variant !== '') || variant_yes == 'No') {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo base_url('add_to_cart'); ?>",
                data: {
                    product_id: product_id,
                    variant: variant,
                    qty: qty,
                    addqty: addqty,
                    variant_yes: variant_yes
                },
                success: function (data) {
                      mini_cart();
                   showAlert(data.alert_class, data.alert_title, data.alert_message);
                }
            });
        } else {
            // Invalid variant - Show error alert
            showAlert('error', 'Invalid variant', 'Please select a valid variant.');
        }
    });
    
    $(document).on('click', '.BuyNow', function (e) {
    e.preventDefault();
    var product_id = $(this).attr('data-product-id');
    var variant = $(this).attr('data-variant');
    var qty =  $("#qty").val();
    var variant_yes = $(this).attr('data-variant-yes');

    if (qty <= 0) {
        showAlert('warning', 'Out of stock', 'Product out of stock');
        return;
    }

    var addqty = $("#qty").val() ? $("#qty").val() : 1;

    if ((variant_yes == 'Yes' && variant !== '') || variant_yes == 'No') {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('add_to_cart'); ?>",
            data: {
                product_id: product_id,
                variant: variant,
                qty: qty,
                addqty: addqty,
                variant_yes: variant_yes
            },
            success: function (data) {
                if (data.alert_class === 'success') {
                    window.location.href = "<?php echo base_url('checkout'); ?>";
                } else {
                    showAlert(data.alert_class, data.alert_title, data.alert_message);
                }
            }
        });
    } else {
        showAlert('error', 'Invalid variant', 'Please select a valid variant.');
    }
});

});

    </script>

        <script>
       
     function showAlert(alert_class, alert_title, alert_message) {
    Swal.fire({
        icon: alert_class,
        title: alert_title,
        text: alert_message,
        timer: 2000000, // Timer in milliseconds (2 seconds)
        showConfirmButton: true // Show the "OK" button
    });
}




       </script>   
<script>$(document).ready(function(){
    $('.color li a').on('click', function(){
        $('.color li a').removeClass('active');
        $(this).addClass('active');
    })
});</script>
</body>
</html>