<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/product.js" type="text/javascript"></script>

<script type="text/javascript">
	$(function($) {
	    $.ajaxSetup({
	        data: {
	            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>','uniq_code':'<?php echo !empty($uniq_code)?$uniq_code:''; ?>'
	        }
	    });
	});

	<?php if(!empty($productUnMappingData)){ ?>
		$('#supplier_product_un_mapping_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
			"url" : localurl+"product/getProductUnMapping_ajax",
			"type" : "POST",
			'data': function(data) {
	            // Read values
				var min_date = $('#min_date').val();
				var max_date = $('#max_date').val();
				// Append to data
	            data.searchByFromMinDate = min_date;
	            data.searchByToMaxDate   = max_date;
	            return data;
	            }
			},
			"columns": <?php echo $productUnMappingData; ?>,
			"language": {
			infoEmpty: "No records found",
			}
		});
	<?php } ?>
	
</script>