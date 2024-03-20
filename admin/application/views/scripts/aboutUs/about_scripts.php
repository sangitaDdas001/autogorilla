<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>assets/tinymce/tinymce.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/about.js" type="text/javascript"></script>

<script type="text/javascript">
$(function($) {
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });
<?php if(!empty($aboutData)){?>
	if ($('#about_details').length) {
		$('#about_details').DataTable({
			// "searching": false,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"aboutUs/aboutDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $aboutData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
	}

<?php }?>
   
});
</script>