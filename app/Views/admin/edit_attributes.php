<!-- CONTENT WRAPPER -->
<div class="ec-content-wrapper">
    <div class="content">
        <div class="breadcrumb-wrapper breadcrumb-contacts">
            <div>
                <h1>Update Attributes </h1>
                <p class="breadcrumbs"><span><a href="index.html">Home</a></span>
                    <span><i class="mdi mdi-chevron-right"></i></span>Update Attributes
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="ec-cat-list card card-default">
                    <div class="card">

                        <?php if(session()->getFlashdata('failed')):?>
                            <div class="alert alert-danger alert-dismissable">
                               <?= session()->getFlashdata('failed') ?>
                            </div>
                        <?php endif;?>

                        <?php if(session()->getFlashdata('created')):?>
                            <div class="alert alert-success alert-dismissable">
                               <?= session()->getFlashdata('created') ?>
                            </div>
                        <?php endif;?>

                        <!-- /.card-header -->
                        <form method="POST" action="<?php echo base_url('admin/update_attributes_process/'.$id); ?>" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Select attributes Main</label>
                                            <select class="form-control attributes_check" name="main_attributes_id" id="main_attributes_id">
                                                <option value="">Select attributes Main</option>
                                                <?php foreach($main as $mainrow) { ?>
                                                    <option value="<?php echo $mainrow->attribute_main_id; ?>" <?php if($mainrow->attribute_main_id==$attributes->main_id) { echo "selected"; } ?>><?php echo $mainrow->attribute_main_name; ?></option>
                                                <?php } ?>
                                               
                                            </select>
                                            <br>
                                            <input type="checkbox" id="update_name" > Update Main Attributes Main
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="attribute_input_container" style="display:none;">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Add New Main Attribute*</label>
                                            <input type="text"  class="form-control" name="main_attributes_name" value="<?php echo $attributes_main->attribute_main_name;?>">
                                        </div>
                                    </div>
                                    
                                    
                                      <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"> Attribute Name*</label>
                                            <input type="text" required class="form-control" name="attributes_name" value="<?php echo $attributes->attributes_name;?>">
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"> Attribute Color*</label>
                                            <input type="color" required class="form-control" name="attributes_color" value="<?php echo $attributes->attributes_symbol	;?>">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Status*</label>
                                            <select class="form-control" required name="status">
                                                 <option value="Active" <?php if($attributes->attributes_status=='Active') { echo "selected"; } ?>>Active</option>
                    <option value="Inactive" <?php if($attributes->attributes_status=='Inactive') { echo "selected"; } ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="card-footer" style="text-align: center;">
                                        <input type="submit" name="CreateNewProduct" value="Submit" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Content -->
</div> <!-- End Content Wrapper -->

<!-- Add the following JavaScript/jQuery to handle the show/hide functionality -->
<script>
    $(document).ready(function() {
        // Initially check if the checkbox is checked, then show the input container
        if ($('#update_name').prop('checked')) {
            $('#attribute_input_container').show();
        } else {
            $('#attribute_input_container').hide();
        }

        // Listen for change in checkbox state
        $('#update_name').change(function() {
            // If checkbox is checked, show the input field for adding a new main attribute
            if ($(this).prop('checked')) {
                $('#attribute_input_container').show();
            } else {
                $('#attribute_input_container').hide();
            }
        });
    });
</script>