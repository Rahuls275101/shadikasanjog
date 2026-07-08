
   <section>
        <div class="db">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-lg-3">
                 <?php echo view('frontend/dashboard/sidebar'); ?>
            </div>
                    <div class="col-md-8 col-lg-9">
                        <div class="row">
                            <div class="col-md-5 m-auto db-sec-com">
                                <h2 class="db-tit">Manage Plan</h2>
                                <div class="db-pro-stat">
                                    <h2 class="pt-4">Current Plan</h2>
                                    <div class="db-plan-card">
                                        <img src="<?php echo base_url('assets/frontend/'); ?>/assets/images/icon/plan.png" alt="">
                                    </div>
                                    <div class="db-plan-detil">
                                        <ul>
                                            <li><strong><?php echo $userdata->membership_name ?? ''; ?></strong></li>
                                            <li>Validity: <strong><?php echo $userdata->membership_month ?? ''; ?> Months</strong></li>
                                            <li>Valid Until <strong><?php echo $userdata->membership_end_date ?? ''; ?></strong></li>
                                            <li><a href="<?php if($userdata->membership_id > 1)  { echo base_url('plan/'); ?>/<?php echo $userdata->membership_id ?? ''; } ?>" class="cta-3">Renew Plan</a></li>
                                        </ul>
                                        <div class="row mt-4">
                                    <div class="col-xl-12">
                                        <ul class="list-group border-top border-bottom plan mt-0 d-flex flex-wrap flex-row">

                                            <li class="w-50"><a href="<?php echo base_url('plan/'); ?>/2"  class="btn defene_plan upgrade pt-3 pb-3">Defence Plan</a>
                                            <li class="w-50"><a href="<?php echo base_url('plan/'); ?>/3" class="btn defene_plan upgrade pt-3 pb-3">Non-Defence Plan</a>
                                        </li>
                                        </ul>
                                          <!-- <a href="#" class="btn defene_plan_ok">Ok</a> -->

                                        </div>
                                </div>
                                    </div>
                                     

                                </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->



  