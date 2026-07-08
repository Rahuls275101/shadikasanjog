<?php 
use App\Models\Commanmodel;
$commanmodel = new Commanmodel();

$request = service('request');

$category = $commanmodel->all_multiple_query_order_by(
    'category', 
    ['parent_id' => 0], 
    'category_id', 
    'ASC'
);
?>

<section>
    <div class="all-pro-head">
        <div class="container">
            <div class="row">
                <h1>Services</h1>
            </div>
        </div>
    </div>
</section>

<section class="vendors-section py-5">
    <div class="container">
        <div class="row">

            <div class="col-md-12 mb-4">
                <p>
                    <b>Disclaimer:-</b> We hope to not only help you find your perfect partner but seek to provide
                    solutions beyond. In order to provide this assistance, we have created our ‘Services’ section.
                    Do bear in mind that the service providers have their respective Terms and Conditions.
                </p>
            </div>

            <!-- ================= SIDEBAR ================= -->
            <div class="col-md-3 mb-4">
                <div class="vendors-sidebar p-3 shadow-sm" style="position: sticky; top: 20px;">
                    <h5 class="mb-3">Select City</h5>

                    <ul class="list-unstyled">
                        <?php foreach($category as $categoryrow) { 
                            $categorysub = $commanmodel->all_multiple_query_order_by(
                                'category',
                                ['parent_id' => $categoryrow->category_id],
                                'category_id',
                                'ASC'
                            ); 
                        ?>
                        <li class="mb-2 parent-item">

                            <a class="d-flex justify-content-between align-items-center parent-link"
                               data-bs-toggle="collapse"
                               href="#<?php echo $categoryrow->url_slug;?>"
                               role="button">
                                <?php echo $categoryrow->category_name;?>
                                <span>▼</span>
                            </a>

                            <ul class="collapse list-unstyled ps-3"
                                id="<?php echo $categoryrow->url_slug;?>">

                                <?php foreach($categorysub as $categorysubrow) { ?>
                                <li>
                                    <a href="javascript:void(0);" 
                                       class="subcategory-link"
                                       data-id="<?php echo $categorysubrow->category_id; ?>">
                                       <?php echo $categorysubrow->category_name; ?>
                                    </a>
                                </li>
                                <?php } ?>

                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <!-- ================= CONTENT AREA ================= -->
            <div class="col-md-9">
                <div class="vendors-content">
                    <div class="service-card mb-4 p-4 shadow-sm rounded">
                        <h5 class="fw-bold text-dark mb-3">Service Providers</h5>
                        <div class="row g-3 ajax_list"></div>
                        <div id="pagination_link" class="mt-3"></div>
                        <div id="item_total" class="mt-2"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
.vendors-sidebar a.active {
    color: #fff !important;
    background-color: #0d6efd;
    padding: 6px 10px;
    border-radius: 4px;
    display: block;
}

.row.ajax_list {
    width: 100%;
    max-width: 798px;
    margin: 0 auto; /* center align */
}
</style>

<script src="<?php echo base_url('assets/frontend/assets/js/jquery.min.js'); ?>"></script>

<script>
$(document).ready(function() {

    /* ================= DEFAULT LOAD ================= */
    var firstSub = $('.subcategory-link:first');

    if (firstSub.length) {
        var defaultId = firstSub.data('id');

        firstSub.addClass('active');
        firstSub.closest('.collapse').addClass('show');
        firstSub.closest('.parent-item').find('.parent-link').addClass('active');

        ajax_list(1, defaultId);
    }

    /* ================= SUBCATEGORY CLICK ================= */
    $(document).on('click', '.subcategory-link', function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        $('.subcategory-link').removeClass('active');
        $('.parent-link').removeClass('active');

        $(this).addClass('active');
        $(this).closest('.parent-item').find('.parent-link').addClass('active');

        ajax_list(1, id);
    });

    /* ================= AJAX FUNCTION ================= */
    function ajax_list(page, id = '') {

        var mainid = '';
        var search = $('#search').val();
        var list = '<?php echo $url ?? ''; ?>';
        var collection = '<?php echo $collection ?? ''; ?>';
        var minprice = $('#min_price').val();
        var maxprice = $('#max_price').val();
        var shortby = $('#orderby').val();

        $.ajax({
            url: "<?php echo base_url('ajax_list'); ?>/" + page,
            method: "POST",
            dataType: "JSON",
            data: {
                action: 'fetch_data',
                mainid: mainid,
                id: id,
                list: list,
                search: search,
                collection: collection,
                minprice: minprice,
                maxprice: maxprice,
                shortby: shortby
            },
            beforeSend: function() {
                $('.ajax_list').html('<p>Loading...</p>');
            },
            success: function(data) {
                if (data.product_list) {
                    $('.ajax_list').html(data.product_list);
                }
                if (data.pagination_link) {
                    $('#pagination_link').html(data.pagination_link);
                }
                if (data.item_total) {
                    $('#item_total').html(data.item_total);
                }
            },
            error: function(xhr) {
                console.log("AJAX ERROR:", xhr.responseText);
            }
        });
    }

    /* ================= PAGINATION ================= */
    $(document).on('click', '.pagination-link', function(e) {
        e.preventDefault();

        var page = $(this).data('page');
        var activeId = $('.subcategory-link.active').data('id');

        ajax_list(page, activeId);
    });

});
</script>