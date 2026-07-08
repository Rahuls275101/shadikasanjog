
<?php 
use App\Models\Commanmodel;
 $commanmodel = new Commanmodel();
 $team = $commanmodel->all_multiple_query_order_by('team',array('team_status' => 'Active'),'team_id','ASC');
        

?>


  <section>
        <div class="all-pro-head">
            <div class="container">
                <div class="row">
                    <h1>Testimonials</h1>
                    <!-- <a href="sign-up.html">Join now for Free <i class="fa fa-handshake-o" aria-hidden="true"></i></a> -->
                </div>
            </div>
        </div>
     
    </section>



    <!-- START -->
    <section class="newpadding">
        <div class="ab-wel" style="padding-bottom: 10px;">
            <div class="container">
                <div class="row">
                    
                    
                          <?php foreach($team as $teamrow) { ?>
                              <div class="col-sm-4  col-md-4">
                        <div class="cus-revi-box">

                            <p> <?php echo $teamrow->overview;?></p>
                            <h5><?php echo $teamrow->team_name;?></h5>
                            <span><?php echo $teamrow->designation;?></span>
                        </div>  
                    </div>
                            <?php } ?>

         



                </div>
            </div>
        </div>
    </section>
    <!-- END -->


