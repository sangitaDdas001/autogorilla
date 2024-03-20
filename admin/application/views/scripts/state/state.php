<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/state.js" type="text/javascript"></script>

<script type="text/javascript">
$(function($) {
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });
 
 	<?php if(!empty($stateData)) { ?> 
	    $('#state_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"state/stateDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $stateData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

});
</script>