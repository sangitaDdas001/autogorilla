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
    <?php if(!empty($productData)) { 
    	?> 
	    $('#supplier_product_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"product/productDetails_ajax",
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
			"columns": <?php echo $productData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

    <?php if(!empty($rejectProductData)) { 
    	?> 
	    $('#reject_product_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"product/rejectedProductDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $rejectProductData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

    <?php if(!empty($approvedProductData)) { 
    	?> 
	    $('#approved_product_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"product/approvedProductDetails_ajax",
				"type" : "POST",
				'data': function(data) {
	            		// Read values
	            	var min = $('#min_ps').val();
	            	var max = $('#max_ps').val();

	              		// Append to data
	            	data.searchByFromMin = min;
	            	data.searchByToMax   = max;
	            	return data;
	            }
			},
			"columns": <?php echo $approvedProductData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

 	if ($('.microcat-parent').length>0) {
		$('.microcat-parent').select2({
		  placeholder: '-- Select Micro category --',
		  allowClear: true
		});
	}

	<?php if(!empty($productMappingData)){ ?>
		 $('#supplier_product_mapping_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"product/fetchProductMapping_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $productMappingData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
	<?php } ?>

	<?php if(!empty($allProductData)) { 
    	?> 
	    $('#all_product_list').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"product/allProductDetails_ajax",
				"type" : "POST",
				'data': function(data) {
	              // Read values
	              var min = $('#min').val();
	              var max = $('#max').val();

	              // Append to data
	              data.searchByFromMin = min;
	              data.searchByToMax   = max;
	              return data;
	            }
			},
			"columns": <?php echo $allProductData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>
    
<?php if(!empty($rejectedProductData)) { ?> 
	    $('#product_rejected_list').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"product/product_rejected_listAjax",
				"type" : "POST",
			},
			"columns": <?php echo $rejectedProductData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>
    
    <?php if(!empty($UPData)) { ?> 
	    $('#all_product_un_mapping_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"product/getProductUnMapping_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $UPData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

});





</script>