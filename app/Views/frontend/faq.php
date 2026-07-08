<?php  
use App\Models\Commanmodel; 
$commanmodel = new Commanmodel();

/* Sirf Active FAQ lao */
$faqList = $commanmodel->all_multiple_query_order_by(
    'faq',
    ['faq_status' => 'Active'],
    'faq_id',
    'ASC'
);

/* Type ke hisaab se group karo */
$groupedFaq = [];

foreach ($faqList as $row) {
    $groupedFaq[$row->type][] = $row;
}

/* Fixed Type Sequence */
$typeSequence = [
    "General",
     "Website Navigation",
    "Verified Batch",
    "Green Batch",
    "Orange Batch",
    "No Batch Holder",
    "Profile Verification",
    "Membership Plans",
    "Refund Policy",
    "Membership Termination",
    "Miscellaneous"
];
?>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<section>
    <div class="all-pro-head">
        <div class="container">
            <div class="row">
                <h1 class="text-center my-4">Frequently Asked Questions</h1>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
<div class="container">

<?php  
$sectionCount = 1;

foreach ($typeSequence as $type) :

    if (!isset($groupedFaq[$type])) continue;

    $faqs = $groupedFaq[$type];
?>

    <div class="row mb-5">
        <div class="col-md-12">
            
            <h3 class="mb-4"><?php echo $type; ?></h3>

            <div class="accordion" id="accordion_<?php echo $sectionCount; ?>">

            <?php  
            $i = 1;
            foreach ($faqs as $faq) :

                $collapseId = "collapse_" . $sectionCount . "_" . $i;
                $headingId  = "heading_" . $sectionCount . "_" . $i;
            ?>

                <div class="accordion-item">
                    
                    <h2 class="accordion-header" id="<?php echo $headingId; ?>">
                        <button class="accordion-button <?php echo ($i != 1 ? 'collapsed' : ''); ?>" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#<?php echo $collapseId; ?>" 
                                aria-expanded="<?php echo ($i == 1 ? 'true' : 'false'); ?>" 
                                aria-controls="<?php echo $collapseId; ?>">
                            <?php echo $faq->faq_question; ?>
                        </button>
                    </h2>

                    <div id="<?php echo $collapseId; ?>" 
                         class="accordion-collapse collapse <?php echo ($i == 1 ? 'show' : ''); ?>" 
                         aria-labelledby="<?php echo $headingId; ?>" 
                         data-bs-parent="#accordion_<?php echo $sectionCount; ?>">

                        <div class="accordion-body">
                            <?php echo $faq->faq_answer; ?>
                        </div>
                    </div>

                </div>

            <?php  
            $i++;
            endforeach;
            ?>

            </div>

        </div>
    </div>

<?php  
$sectionCount++;
endforeach;
?>

</div>
</section>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>