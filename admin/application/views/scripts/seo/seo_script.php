<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/seo.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function($) {
	    $.ajaxSetup({
	        data: {
	            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
	        }
	    });

	    <?php if(!empty($seoData)) { ?> 
		    $('#seo_details').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax" : {
					"url" : localurl+"seo/seoDetails_ajax",
					"type" : "POST",
				},
				"columns": <?php echo $seoData; ?>,
				"language": {
		        	infoEmpty: "No records found",
		    	}
		    });
    	<?php } ?>
	});
</script>