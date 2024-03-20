<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/leads.js" type="text/javascript"></script>

<script type="text/javascript">
$(function($) {
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });
 
 	<?php if(!empty($catData)) { ?> 
	    $('#leads_details').DataTable({
			//"searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"leads/leadsDetails_ajax",
				"type" : "POST",
				'data': function(data) {
	              //Read values
	              var min_date = $('#min_date').val();
	              var max_date = $('#max_date').val();

	              //Append to data
	              data.searchByFromMin = min_date;
	              data.searchByToMax   = max_date;
	              return data;
	            }
			},
			"columns": <?php echo $catData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });

	   	$('#active_leads_details').DataTable({
			//"searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"leads/active_leadsDetails_ajax",
				"type" : "POST",
				'data': function(data) {
	              //Read values
	              var min_date = $('#min_date').val();
	              var max_date = $('#max_date').val();

	              //Append to data
	              data.searchByFromMin = min_date;
	              data.searchByToMax   = max_date;
	              return data;
	            }
			},
			"columns": <?php echo $catData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });

	   	$('#reject_leads_details').DataTable({
			//"searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"leads/reject_leadsDetails_ajax",
				"type" : "POST",
				'data': function(data) {
	              //Read values
	              var min_date = $('#min_date').val();
	              var max_date = $('#max_date').val();

	              //Append to data
	              data.searchByFromMin = min_date;
	              data.searchByToMax   = max_date;
	              return data;
	            }
			},
			"columns": <?php echo $catData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

    <?php if(!empty($buyleadData)){ ?>
    	  $('#buy_leads_details').DataTable({
			//"searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"leads/buyleadsDetails_ajax",
				"type" : "POST",
				'data': function(data) {
	              //Read values
	              var min_date = $('#min_date').val();
	              var max_date = $('#max_date').val();

	              //Append to data
	              data.searchByFromMin = min_date;
	              data.searchByToMax   = max_date;
	              return data;
	            }
			},
			"columns": <?php echo $buyleadData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>
     
    <?php if(!empty($activebuyleadData)){ ?>
    	  $('#active_buy_leads_details').DataTable({
			//"searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"leads/activeBuyleadsDetails_ajax",
				"type" : "POST",
				'data': function(data) {
	              //Read values
	              var min_date = $('#min_date').val();
	              var max_date = $('#max_date').val();

	              //Append to data
	              data.searchByFromMin = min_date;
	              data.searchByToMax   = max_date;
	              return data;
	            }
			},
			"columns": <?php echo $activebuyleadData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

    <?php if(!empty($directleadData)){ ?>
    	  $('#all_direct_leads_details').DataTable({
			//"searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"leads/directLeadsDetails_ajax",
				"type" : "POST",
				'data': function(data) {
	              //Read values
	              var min_date = $('#min_date').val();
	              var max_date = $('#max_date').val();

	              //Append to data
	              data.searchByFromMin = min_date;
	              data.searchByToMax   = max_date;
	              return data;
	            }
			},
			"columns": <?php echo $directleadData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

    <?php if(!empty($activedirectleadData)){ ?>
    	$('#all_active_direct_leads_details').DataTable({
			//"searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"leads/activeDirectLeadsDetails_ajax",
				"type" : "POST",
				'data': function(data) {
	              //Read values
	              var min_date = $('#min_date').val();
	              var max_date = $('#max_date').val();

	              //Append to data
	              data.searchByFromMin = min_date;
	              data.searchByToMax   = max_date;
	              return data;
	            }
			},
			"columns": <?php echo $activedirectleadData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

});
</script>