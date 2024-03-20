<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo (isset($page_title)?$page_title:''); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  __CSSLINKS__
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
</head>

<body class="hold-transition skin-blue sidebar-mini">
	<!-- Main div -->
	<div class="wrapper">
	__HEADER__
	__SIDEBAR__
		<?php  echo (isset($layout_content)?$layout_content:''); ?>

	  <footer class="main-footer">
	    <center><strong>Copyright &copy; <?php echo date('Y'); ?></strong></center>
	  </footer>

	</div>
	__FOOTER__
	<?php  echo (isset($script_files)?$script_files:''); ?>
</body>
</html>