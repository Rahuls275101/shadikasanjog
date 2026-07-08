<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper">
						<div>
							<h1>Order details</h1>
						<p class="breadcrumbs"><span><a href="index.html">Home</a></span>
							<span><i class="mdi mdi-chevron-right"></i></span>Order details
						</p>
						</div>
					</div>
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
													 <a href="#!">View profile</a>
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
															<td><img class="product-img tbl-img" src="<?php echo $item->booking_product_image; ?>" ></td>
															<td><?php echo $item->booking_product_product_name; ?></td>
															<td><?php echo $item->booking_product_varian; ?></td>
															<td><?php echo $item->booking_product_quantity; ?></td>
															<td><?php echo $item->booking_product_price; ?></td>
															<td><?php echo $item->booking_product_sub_total; ?></td>
														</tr>
														
														<tr>
															<td colspan="4">
															</td>
															<td class="text-right"><strong>Total:</strong></td>
															<td class="text-right"><strong><?php echo $item->booking_product_sub_total; ?></strong></td>
														</tr>

														<tr>
															<td colspan="4">
															</td>
															<td class="text-right"><strong>Gst (<?php echo $item->booking_product_tax; ?>):</strong></td>
															<td class="text-right"><strong><?php echo $item->booking_product_tax_rate; ?></strong></td>
														</tr>
														<tr>
															<td colspan="4">
															</td>
															<td class="text-right"><strong>Discount:</strong></td>
															<td class="text-right"><strong><?php echo $item->discount_per_product; ?></strong></td>
														</tr>
															<tr>
															<td colspan="4">
															</td>
															<td class="text-right"><strong>Shipping:</strong></td>
															<td class="text-right"><strong><?php echo $item->booking_product_shipping; ?></strong></td>
														</tr>
														<tr>
															<td colspan="4">
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
							</div>
							<!-- Tracking Detail -->
							
							
							
								<div class="card ec-odr-dtl card card-default mt-4 trk-order">
								<div class="card-header card-header-border-bottom order_tracking_title">
									<h3>Update Order Status</h3>
								</div>
							
							<div class="card-body">
							     <form method="post" action="<?php echo base_url('admin/updateorderstatus/'.$id.''); ?>">
    <div class="row">
							     <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Order Status*</label>
                    <select class="form-control" required name="status">
                    <option value="success" <?php if('success' == $item->booking_product_status) { echo "selected"; } ?>>New</option>
                    <option value="delivered" <?php if('delivered' == $item->booking_product_status) { echo "selected"; } ?>>Delivered</option>
                    <option value="cancelled" <?php if('cancelled' == $item->booking_product_status) { echo "selected"; } ?>>Cancelled</option>
                    </select> 
                  </div>
                  </div>
                    <div class="col-md-1" style="margin-top:32px;">
    <div class="form-group">  
   
    <input type="submit" style="margin-right: 10px" class="btn btn-success" name="update_booking_status" value="Update">
    </div>
    </div>
                  </form>
                   </div>
							    </div>
							 </div>   
							    
							
							
							
							
							
							
							
							<div class="card ec-odr-dtl card card-default mt-4 trk-order">
								<div class="card-header card-header-border-bottom order_tracking_title">
									<h3>Shipping Details</h3>
								</div>
							<!--	<div
									class="d-flex flex-wrap flex-sm-nowrap justify-content-between py-3 px-2 bg-custom">
									<div class="order_details_shipment w-100 py-1 px-2">
										<h3>Shipped via: <span>DHL courier service int.</span></h3>
										</div>
									<div class="order_details_shipment w-100 py-1 px-2">
										<h3>Status: <span>Product dispatch</span></h3>
									</div>
									<div class="order_details_shipment w-100 py-1 px-2">
											<h3>Delivery date: <span>31 Mar 2022</span></h3>
										</div>
								</div>-->
								<div class="card-body">
								    
								    
								      <?php $shupnow = true;
  
        if(!empty($item->ship_shipment_id)) {
     $shupnow = false; ?>                     
          <table class="table table-bordered table-striped">
                <thead>
          
                <tr>
                  <th>Ship Order Id </th>
                  <th><?php echo $item->order_id; ?></th>
                </tr>
                <tr>
                  <th>Ship Shipment Id</th>
                  <th> <?php echo $item->ship_shipment_id; ?></th>
                </tr>
                <tr>
                  <th>Ship Status</th>
                  <th> <?php echo $item->ship_status; ?></th>
                </tr>
                    <tr>
                  <th>Ship Status</th>
                  <th>  <a class="btn btn-flat btn-success" href="https://trackcourier.io/track-and-trace/shiprocket/<?php echo $item->ship_shipment_id; ?>">Track</i></a></th>
                </tr>
              
                </thead>
                
              </table>
<?php }  ?>

    <?php if($shupnow) {  ?>
    <form method="post" action="<?php echo base_url('admin/create_shiprocket/'.$item->booking_product_id.''); ?>">
    <div class="row">
              
			  <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Length*</label>
                  <input type="text"  class="form-control" name="length" required> 
                  </div>
                  </div>
                     <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Width*</label>
                  <input type="text"  class="form-control" name="width" required> 
                  </div>
                  </div>
                     <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Height*</label>
                  <input type="text"  class="form-control" name="height" required> 
                  </div>
                  </div>
                     <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Weight  *</label>
                  <input type="text"  class="form-control" name="weight" required> 
                  </div>
                  </div>       
					   

    <div class="col-md-1" style="margin-top:32px;">
    <div class="form-group">  
    <input type="hidden" required name="shipping_weight" class="form-control" value="0">
    <input type="submit" style="margin-right: 10px" class="btn btn-success" name="update_booking_status" value="Ship">
    </div>
    </div>

    </div>
    </form>    
    <?php }  ?>
								<!--	<div
										class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">
										<div class="step completed">
											<div class="step-icon-wrap">
												<div class="step-icon"><i class="mdi mdi-cart-plus"></i></div>
											</div>
											<h4 class="step-title">Confirmed Order</h4>
										</div>
										<div class="step completed">
											<div class="step-icon-wrap">
												<div class="step-icon"><i class="mdi mdi-repeat"></i></div>
											</div>
											<h4 class="step-title">Processing Order</h4>
										</div>
										<div class="step completed">
											<div class="step-icon-wrap">
												<div class="step-icon"><i class="mdi mdi-gift"></i></div>
											</div>
											<h4 class="step-title">Product Dispatched</h4>
										</div>
										<div class="step">
											<div class="step-icon-wrap">
												<div class="step-icon"><i class="mdi mdi-truck-fast"></i></div>
											</div>
											<h4 class="step-title">On Delivery</h4>
										</div>
										<div class="step">
											<div class="step-icon-wrap">
												<div class="step-icon"><i class="mdi mdi-shopping"></i></div>
											</div>
											<h4 class="step-title">Product Delivered</h4>
										</div>
									</div>-->
								</div>
							</div>
						</div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->