    <style>.info-grid li {
    list-style: none;
    font-weight: bold; line-height:25px;
}
h5.title_infos {
    font-size: 18px;
    font-weight: bold;
}
.info-grid li span{

    font-weight: 400;
}
.pt-100{padding-top:100px}</style>
 
 <div class="liton__wishlist-area pb-120 pt-100">
          
				<div class="container">
		
					<div class="row">
						<div class="col-12">
							<div class="ec-odr-dtl card card-default">
								<div class="card-header card-header-border-bottom d-flex justify-content-between">
									<h2 class="ec-odr">Order details</h2>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-xl-3 col-lg-6">
											<address class="info-grid">
												<div class="info-content">
												    <h5 class="title_infos"> <span class="mdi mdi-account-circle"></span> Customer info</h5> 
													 <ul>
														<li>Name: <span> <?php echo $order->order_book_user_name; ?></span></li>
														<li>Email: <span><?php echo $order->order_book_email; ?></span> </li>
														<li>Phone: <span><?php echo $order->order_book_phone; ?></span></li>
													 </ul>
													
												</div>
											</address>
										</div>
										<div class="col-xl-3 col-lg-6">
											<address class="info-grid">
												<div class="info-content">
												    <h5 class="title_infos"> <span class="mdi mdi-ship-wheel"></span> Shipping info</h5> 
													 <ul>
														<li>Name: <span> Home</span></li>
														<li>Address: <span><?php echo $order->order_shipping_user_name; ?>,
															<?php echo $order->order_shipping_address; ?>,<?php echo $order->order_shipping_city; ?>,<?php echo $order->order_shipping_state; ?>,<?php echo $order->order_shipping_pin_no; ?></span> </li>
														<li>Phone: <span><?php echo $order->order_shipping_phone; ?></span></li>
													 </ul>
												</div>
											</address>
										</div>
										<div class="col-xl-3 col-lg-6">
											<address class="info-grid">
												<div class="info-content">
												    <h5 class="title_infos"> <span class="mdi mdi-cart"></span> Order info</h5> 
													 <ul>
														<li>Vendor: <span><?php echo $vender->name; ?></span></li>
														<li>Address: <span><?php echo $vender->admin_address; ?></span> </li>
														<li>Phone: <span><?php echo $vender->phone; ?></span></li>
													 </ul>
												
												</div>
											</address>
										</div>
										<div class="col-xl-3 col-lg-6">
											<address class="info-grid">
												<div class="info-content">
												    <h5 class="title_infos"> <span class="mdi mdi-card-bulleted"></span>Payment info</h5> 
													 <ul>
													     	<li>Order Status: <span><?php echo $item->booking_product_status; ?></span> </li>
														<li>Payment method: <span><?php echo $order->order_book_pay_type; ?></span></li>
														<?php if($order->order_book_pay_type !='COD') { ?>
														<li>TXN ID: <span><?php echo $order->order_TXNID; ?></span> </li>
													
														
														<?php } ?>
													 </ul>
												</div>
											</address>
										</div>
									
									</div>
								</div>
							</div>

							<div class="ec-odr-dtl card card-default mt-5">
								<div class="card-header card-header-border-bottom d-flex justify-content-between">
									<h2 class="ec-odr">Product summary</h2>
								</div>
								<div class="card-body">
								
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table class="table table-striped o-tbl">
													<thead>
														<tr class="line">
															<td><strong>Order ID</strong></td>
															<td><strong>Photo</strong></td>
															<td><strong>Product name</strong></td>
																<td><strong>Variant</strong></td>
															<td><strong>Unit</strong></td>
															<td><strong>Price</strong></td>
															<td><strong>Sub total</strong></td>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><?php echo $item->booking_product_order_id; ?>
															<td><img class="product-img tbl-img" style="width: 100px;" src="<?php echo $item->booking_product_image; ?>" ></td>
															<td><?php echo $item->booking_product_product_name; ?></td>
																<td><?php echo $item->booking_product_varian; ?></td>
															<td><?php echo $item->booking_product_quantity; ?></td>
															<td><?php echo $item->booking_product_price; ?></td>
															<td><?php echo $item->booking_product_sub_total; ?></td>
														</tr>
														
														<tr>
															<td colspan="5">
															</td>
															<td class="text-right"><strong>Total:</strong></td>
															<td class="text-right"><strong><?php echo $item->booking_product_sub_total; ?></strong></td>
														</tr>

														<tr>
															<td colspan="5">
															</td>
															<td class="text-right"><strong>Gst (<?php echo $item->booking_product_tax; ?>):</strong></td>
															<td class="text-right"><strong><?php echo $item->booking_product_tax_rate; ?></strong></td>
														</tr>
														<tr>
															<td colspan="5">
															</td>
															<td class="text-right"><strong>Discount:</strong></td>
															<td class="text-right"><strong><?php echo $item->discount_per_product; ?></strong></td>
														</tr>
															<tr>
															<td colspan="5">
															</td>
															<td class="text-right"><strong>Shipping:</strong></td>
															<td class="text-right"><strong><?php echo $item->booking_product_shipping; ?></strong></td>
														</tr>
														<tr>
															<td colspan="5">
															</td>
															<td class="text-right"><strong>Grand total:</strong></td>
															<td class="text-right"><strong><?php echo ($item->booking_product_sub_total + $item->booking_product_tax_rate + $item->booking_product_shipping) - $item->discount_per_product; ?></strong></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								 <?php if($item->booking_product_status == 'success') { ?>
									<div class="ec-odr-dtl card card-default mt-5">
								<div class="card-header card-header-border-bottom d-flex justify-content-between">
									<h2 class="ec-odr">Cancel  Order </h2>
								</div>
								    <div class="card-body">
								<form  id="ordercancel" method="post">
									<div class="row">
									  
									       <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0 pb-1">Cancel Reason</label>
                                        <textarea class="form-control" name="cancel_reason"></textarea>
                                        <input type="hidden" name="orderid" value="<?php echo $item->booking_product_order_id; ?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                         <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit">Cancel order</button>
                                    </div>
                                </div>
                                
									    </div>
									    
									    
									    </form>
									    </div>
									    
									    </div>
									    <?php } ?>
							</div>
						
						</div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->
			
	

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>    		
	    <script>
    // Handle form submission with AJAX
    $('#ordercancel').on('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting the traditional way
        
        // Prepare the data to send via AJAX
        var formData = $(this).serialize();  // Serialize the form data

        // Perform AJAX request
        $.ajax({
            url: '<?php echo base_url("ordercancel-submit"); ?>', // Change the URL based on your actual route
            type: 'POST',
            data: formData,
            dataType: 'json',  // Expect a JSON response
            success: function(response) {
                // If the response is successful, show SweetAlert with the appropriate message
                if (response.alert_class === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: response.alert_title,
                        text: response.alert_message,
                    }).then(function() {
                        // Optionally, redirect or reload the page after the alert
                       	$("#reviewForm")[0].reset();
                    });
                }
                // Handle validation or error alerts
                else if (response.alert_class === 'warning' || response.alert_class === 'danger') {
                    
                    let errorMessages = '';
                    // Loop through the validation errors and create a message string
                    for (let field in response.validation_errors) {
                        errorMessages += `<strong>${field}:</strong> ${response.validation_errors[field]}<br>`;
                    }
                    Swal.fire({
                        icon: response.alert_class,  // This will be 'warning' or 'error' class based on the response
                        title: response.alert_title,
                        html: errorMessages
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle error in case of AJAX failure
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong! Please try again later.',
                });
            }
        });
    });
</script>