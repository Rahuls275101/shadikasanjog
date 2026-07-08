
<?php 
use App\Models\Commanmodel;

$commanmodel = new Commanmodel();

?> 
<!DOCTYPE html>
<html>
<head>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>"/>
  <title>Dashboard Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->

  
  


	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
	<link href="<?php echo base_url('assets/admin/'); ?>/assets/css/materialdesignicons.min.css" rel="stylesheet" />

	<!-- PLUGINS CSS STYLE -->
	<link href="<?php echo base_url('assets/admin/'); ?>/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
	<link href="<?php echo base_url('assets/admin/'); ?>/assets/plugins/simplebar/simplebar.css" rel="stylesheet" />

	<!-- custom css -->
	<link id="style.css" href="<?php echo base_url('assets/admin/'); ?>/assets/css/style.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- Additional DataTables Button JS if needed -->
<script type="text/javascript" src="<?php echo base_url('assets/admin/'); ?>/cdn/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/admin/'); ?>/cdn/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/admin/'); ?>/cdn/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/admin/'); ?>/cdn/buttons.print.min.js"></script>
<script src="<?php echo base_url('assets/admin/'); ?>/cdn/simple_numbers_no_ellipses.js"></script>

<!-- DataTables Select Extension CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">

<!-- DataTables Select Extension JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>


<link rel="stylesheet" href="<?php echo base_url('assets/admin/'); ?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url('assets/admin/'); ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


<script src="<?php echo base_url('assets/admin/'); ?>/ckeditor/ckeditor.js"></script>

</head>