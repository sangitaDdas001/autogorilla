<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo (isset($page_title)?$page_title:''); ?></title>
	__CSSLINKS__
  	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">
</head>
<body class="hold-transition login-page">
	<?php  echo (isset($layout_content)?$layout_content:''); ?>
__FOOTER__
<?php  echo (isset($script_files)?$script_files:''); ?>
</body>
</html>