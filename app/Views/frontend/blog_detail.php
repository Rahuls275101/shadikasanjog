 <section>
        <div class="inn-ban">
            <div class="container">
                <div class="row">
                    <h1><?php echo $blogs->blog_name; ?></h1>
                    <ul class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('blogs'); ?>">All Post</a></li>
          
                        <li class="breadcrumb-item active"><?php echo $blogs->blog_name; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->

    <div class="blog-main blog-detail">
        <div class="container">
            <div class="row">
                <!--BIG POST START-->
                <div class="blog-home-box">
                 
                    <div class="txt">
                        <span class="blog-date mb-5"><?php echo date('Y-m-d', strtotime($blogs->date_time)); ?></span>
                        <h1><?php echo $blogs->blog_name; ?></h1>
               <?php echo $blogs->blog_description; ?>

                    </div>
                </div>
                <!--END BIG POST START-->
            </div>
        </div>
    </div>



