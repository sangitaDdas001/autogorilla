<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>assets/tinymce/tinymce.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/area.js" type="text/javascript"></script>

<script type="text/javascript">
$(function($) {
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });
<?php if(!empty($areaData)){?>
	if ($('#area_details').length) {
		$('#area_details').DataTable({
			// "searching": false,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"area/areaNameDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $areaData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
	}

<?php }?>
   
});
</script>