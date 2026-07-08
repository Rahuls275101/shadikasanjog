<!-- CONTENT WRAPPER -->
<div class="ec-content-wrapper">
    <div class="content">
        <div class="breadcrumb-wrapper breadcrumb-contacts">
            <div>
                <h1>Create new Attributes </h1>
                <p class="breadcrumbs"><span><a href="index.html">Home</a></span>
                    <span><i class="mdi mdi-chevron-right"></i></span>Create new Attributes
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
                        <form method="POST" action="<?php echo base_url('admin/create_attributes_process/'); ?>" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Select attributes Main</label>
                                            <select class="form-control attributes_check" name="main_attributes_id" id="main_attributes_id">
                                                <option value="">Select attributes Main</option>
                                                <?php foreach($main as $mainrow) { ?>
                                                    <option value="<?php echo $mainrow->attribute_main_id; ?>"><?php echo $mainrow->attribute_main_name; ?></option>
                                                <?php } ?>
                                                <option value="add">Add attributes Main</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="attribute_input_container" style="display:none;">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Add New Main Attribute*</label>
                                            <input type="text"  class="form-control" name="main_attributes_name" value="">
                                        </div>
                                    </div>
                                    
                                    
                                      <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"> Attribute Name*</label>
                                            <input type="text" required class="form-control" name="attributes_name" value="">
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"> Attribute Color*</label>
                                            <input type="color" required class="form-control" name="attributes_color" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Status*</label>
                                            <select class="form-control" required name="status">
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
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
        // Check the selected value of the "attributes_id" dropdown
        $('#main_attributes_id').change(function() {
            // If the value is "add", show the input field for attribute
            if ($(this).val() === 'add') {
                $('#attribute_input_container').show();
            } else {
                $('#attribute_input_container').hide();
            }
        });
    });
</script>
