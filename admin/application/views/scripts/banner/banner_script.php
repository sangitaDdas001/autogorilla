<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/banner.js" type="text/javascript"></script>
 

<script type="text/javascript">
$(function($) {
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });
<?php if(!empty($bannerData)){?>
    $('#banner_details').DataTable({
		// "searching": false,
		"processing": true,
		"serverSide": true,
		"ajax" : {
			"url" : localurl+"banner/bannerDetails_ajax",
			"type" : "POST",
		},
		"columns": <?php echo $bannerData; ?>,
		"language": {
        	infoEmpty: "No records found",
    	}
    });
<?php }?>

<?php if(!empty($promoBannerData)){?>
    $('#promo_banner_details').DataTable({
		// "searching": false,
		"processing": true,
		"serverSide": true,
		"ajax" : {
			"url" : localurl+"banner/promotionBannerDetails_ajax",
			"type" : "POST",
		},
		"columns": <?php echo $promoBannerData; ?>,
		"language": {
        	infoEmpty: "No records found",
    	}
    });
<?php }?>

    if ($('.select2').length>0) {
			$('.select2').select2();
	}
});
</script>