
    <!-- START -->
    <section style="overflow: hidden;">
        <div class="inn-ban">
            <div class="container">
                <div class="row">
                    <h1> Events</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- END -->


    <!-- START -->
    <section class="newpadding">
        <div class="blog-main">
            <div class="container">

                <div class="row ajax_list">

              

                </div>




                <div class="page-nation" id="pagination_link">
                    
                </div>
            </div>
        </div>
    </section>
    <!-- END -->



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>




<script>
$(document).ready(function() {

    // Initial ajax call
    ajax_list(1);

    // Event listeners
    $('.common_selector_change').on('change', function() {
        ajax_list(1);
    });

    $('.common_selector').click(function() {
        ajax_list(1);
    });
    
  
     
    

    // Function to fetch and filter data
    function ajax_list(page) {
        var action = 'fetch_data';
        var type = 'Event';
        
        $.ajax({
            url: "<?php echo base_url('blog_list'); ?>/" + page,
            method: "POST",
            dataType: "JSON",
            data: {action: action,type:type},
            beforeSend: function() {
                // Add loading animation or something if needed
            },
            success: function(data) {
                $('#item_total').html(data.item_total);
                $('.ajax_list').html(data.product_list);
                 $('.headoutput').html(data.headoutput);
                
                $('#pagination_link').html(data.pagination_link);
            }
        });
    }
    
    
    $('.pricecheck').click(function() {
       min = $(this).attr('data-min');
        max = $(this).attr('data-max');
        $('#min_price').val(min);
        $('#max_price').val(max);
         ajax_list(1);
    });
    
 $(document).on('click', '.pagination-link', function(e) {
    e.preventDefault();
    var page = $(this).data('page'); // This should be a number like 1, 2, 3...
    
    // If `page` is a full URL like "?page=5", extract just the number
    if (typeof page === 'string' && page.includes('page=')) {
        page = page.split('page=')[1];
    }

    ajax_list(page);
});


    // Function to get selected filters
    function get_filter_production(class_name) {
        var filter = [];
        $('.' + class_name + ':checked').each(function() {
            filter.push($(this).val());
        });
        return filter;
    }
});

</script>

