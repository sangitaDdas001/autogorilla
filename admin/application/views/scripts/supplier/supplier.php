<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/supplier.js" type="text/javascript"></script>

<script type="text/javascript">

$(function($) {
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>','vendor_id':'<?php echo !empty($vendorId)?$vendorId:''; ?>','uniq_code':'<?php echo !empty($uniq_code)?$uniq_code:''; ?>'
        }
    });
 
 	<?php if(!empty($supplierData)) {  ?> 
	    $('#supplier_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"scrollX": true,
			"ajax" : {
				"url" : localurl+"supplier/supplierDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $supplierData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

		$('#supplier_details_all').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          "dataSrc": "mydata",
           
           'ajax': {
	           	'url':localurl+"supplier/supplierDetails_ajax",
	           	'data': function(data) {
	              // Read values
	              var min_avg = $('#min').val();
	              var max_avg = $('#max').val();

	              // Append to data
	              data.searchByFromMin = min_avg;
	              data.searchByToMax   = max_avg;

	              return data;
	            }
	        },
           'columns': [
           		{data : 'id'},
            	{ data: 'company_name' },
            	{ data: 'name' },
            	{ data: 'email' },
            	{ data: 'cat_av_score' },
            	{ data: 'service_plan' },
            	{ data: 'verified' },
            	{ data: 'featured_company' },
            	{ data: 'created_at' },
            	{ data: 'status' },
            	{ data: 'update_status' },
            	{ data: 'action_status' },
            ]
        });
  

    <?php if(!empty($productData)) { ?> 
	    $('#supplier_product_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"scrollX": true,
			"ajax" : {
				"url" : localurl+"supplier/productDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $productData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

    <?php if(!empty($supplierData)) { ?> 
	    $('#approved_supplier_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"scrollX": true,
			"ajax" : {
				"url" : localurl+"supplier/approved_supplierDetails_ajax",
				"type" : "POST",
				'data': function(data) {
	              // Read values
	              var min_avg = $('#min_catlog').val();
	              var max_avg = $('#max_catlog').val();

	              // Append to data
	              data.searchByFromMin = min_avg;
	              data.searchByToMax   = max_avg;

	              return data;
	            }

			},
			"columns": <?php echo $supplierData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>
    <?php if(!empty($supplierRejectData)) { ?> 
	    $('#reject_supplier_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"scrollX": true,
			"ajax" : {
				"url" : localurl+"supplier/reject_supplierDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $supplierRejectData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

    <?php if(!empty($supplierData)) { ?> 
	    $('#pending_supplier_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"scrollX": true,
			"ajax" : {
				"url" : localurl+"supplier/pending_supplierDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $supplierData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

    <?php if(!empty($companyData)) { ?> 
	    $('#company_rejected_list').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"supplier/company_rejected_listAjax",
				"type" : "POST",
			},
			"columns": <?php echo $companyData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

    $('#suppliers_own_category').DataTable({
          'processing'		: true,
          'serverSide'		: true,
          'serverMethod'	: 'post',
         
           'ajax' : {
	           	'url':localurl+"supplier/supplierOwnCategoryDetails_ajax",
	        },
           'columns': [
           		{ data: 'id' },
            	{ data: 'category_name' },
            	{ data: 'category_image' },
            	{ data: 'status' },
            ]
            	
        });

    <?php if(!empty($duplicateSupData)) { ?> 
	    $('#duplicate_supplier_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"supplier/getDuplicateSuppliers",
				"type" : "POST",
			},
			"columns": <?php echo $duplicateSupData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>
});
</script>