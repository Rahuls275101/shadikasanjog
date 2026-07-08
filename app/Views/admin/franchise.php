


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><a href="<?php echo base_url('admin/create_franchise'); ?>" class="btn btn-primary">Add new franchise</a></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    

    <section class="content">
      <div class="row">
        <div class="col-12">
          <!-- /.card -->
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
            <div class="card-body">
    <form id="news-filter">
    <div class="row">


    <div class="col-md-6">
    <div class="form-group">
    <label>Search with name</label>
    <input type="text" class="form-control" id="name_search">
    </div>
    </div>

    <div class="col-md-3">
    <div class="form-group">
    <label>Status</label>
    <select class="form-control" id="status">
    <option value="">Select</option>
    <option value="Active">Active</option>
    <option value="Inactive">Inactive</option>
         </select> 
    </div>
    </div>


 

    <div class="col-md-3" style="margin-top: 31px;">
    <div class="form-group">
    <button type="button" id="btn-filter-userlist" style="margin-right: 10px" class="btn btn-success">Search</button>
    <button type="button" id="btn-reset-userlist" class="btn btn-danger">Reset Search</button>
    </div>
    </div>

    </div>
    </form>                            


            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>




        <div class="col-12">
          <!-- /.card -->
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <table id="franchiselist" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#S.No</th>
                
                  <th>Name</th>
                  <th>Address</th>
                  <th>Action</th>
                  
                </tr>
                </thead>
                <tbody>               
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


 

 