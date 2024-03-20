<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/service.js" type="text/javascript"></script>

<script type="text/javascript">
$(function($) {
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });
 
 	<?php if(!empty($serviceData)) { ?> 
	    $('#service_plan_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"service/serviceDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $serviceData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

});
</script>