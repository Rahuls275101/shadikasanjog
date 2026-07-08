

<?php
use App\Models\Commanmodel;

$commanmodel = new Commanmodel();
 $team = $commanmodel->all_multiple_query_order_by('team',array('team_status' => 'Active'),'team_id','ASC');
?>


 <section>
        <div class="all-pro-head">
            <div class="container">
                <div class="row">
                    <h1>About Us</h1>
                    <!-- <a href="sign-up.html">Join now for Free <i class="fa fa-handshake-o" aria-hidden="true"></i></a> -->
                </div>
            </div>
        </div>
        <!--FILTER ON MOBILE VIEW-->
        <div class="fil-mob fil-mob-act">
            <h4>Profile filters <i class="fa fa-filter" aria-hidden="true"></i> </h4>
        </div>
    </section>

 <?php $about= $commanmodel->get_single_query('cms_pages',array('cms_id' => 1)); ?>

    <!-- START -->
    <section class="newpadding">
        <div class="ab-wel" style="padding-bottom: 10px;">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8">
                        <div class="ab-wel-rhs">
                            <div class="ab-wel-tit">
                                <!-- <h2>About Us</em></h2> -->
                                <?php echo $about->cms_page_description; ?>

                                <br>
                                
                                <?php $about5 = $commanmodel->get_single_query('cms_pages',array('cms_id' => 5)); ?>
                                <h4><?php echo $about5->cms_page_heading; ?></h4>
                              <?php echo $about5->cms_page_description; ?>
                            </div>
                            <!-- <div class="ab-wel-tit-1">
                                <p>There are many variations of passages of Lorem Ipsum available, but the majority have
                                    suffered alteration in some form, by injected humour, or randomised words which
                                    don't look even slightly believable.</p>
                            </div> -->

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="newimges">
                            <img
                                src="<?php echo base_url('assets/images/'); ?>/<?php echo $about5->cms_image; ?>">
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>
    <!-- END -->

    <!-- START -->
    <section>
        <div class="ab-sec2">
            <div class="container">
                <h2 class="section-title"> <span>Our Unique Features</span></h2>
                <div class="row mt-5">
                    <ul>
                        <li>
                            <div class="green">
                                <img src="https://www.freeiconspng.com/uploads/badge-icon-png-22.png" alt="">
                                <h4>Green Badge</h4>
                                <p>For
                                    Defence Members.</p>
                            </div>
                        </li>
                        <li>
                            <div class="Orange">
                                <img src="https://www.freeiconspng.com/uploads/badge-icon-png-22.png" alt="">
                                <h4>Orange Badge</h4>
                                <p>For
                                    Select Individuals.</p>
                            </div>
                        </li>
                        <li>
    <div class="Blue">
        <img src="https://www.freeiconspng.com/uploads/badge-icon-png-22.png" alt="">
        <!-- Verified Button -->
        <a href="#" class="verified-btn">Verified</a>
        <!--<h4>Blue Badge</h4>-->
        <p>For all verified profiles.</p>

        
    </div>
</li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="feturetex">
                             <?php $about6 = $commanmodel->get_single_query('cms_pages',array('cms_id' => 6)); ?>
                            
                 <?php echo $about6->cms_page_description; ?>

                        </div>

                    </div>
                </div>

  
            </div>
        </div>
    </section>
    <!-- END -->
<style>
    .verified-btn {
    display: inline-block;
    margin-top: 0px;
    margin-bottom: 8px;
    padding: 3px 28px;
    background-color: #1e73be; /* Blue theme */
    color: #fff;
    font-size: 14px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 600;
}

.verified-btn:hover {
    background-color: #155a96;
    color: #fff;
}
</style>