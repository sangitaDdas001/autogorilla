<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/user.js" type="text/javascript"></script>

<script type="text/javascript">
$(function($) {
    if ($('.select2').length>0) {
        $('.select2').select2();
    }
    
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>','userId':'<?php echo !empty($userId)?$userId:''; ?>'
        }
    });
    

  <?php if(!empty($userdata)) {  ?> 
  
    $('#supplier_product_details').DataTable({
        "searching": true,
        "processing": true,
        "serverSide": true,
        "ajax" : {
            "url" : localurl+"UserManagement/productUser_ajax",
            "type" : "POST",
            },
        "columns": <?php echo $userdata; ?>,
        "language": {
            infoEmpty: "No records found",
        },
        "drawCallback": function(settings) {
        // Iterate over all checkboxes with class .assignProduct
            $('#supplier_product_details .assignProduct input[type="checkbox"]').each(function() {
                let product_id = $(this).closest('tr').find('td:eq(1)').text(); // Assuming product_id is in the second column

                // Check the checkbox if its corresponding product_id is present in selectedCheckboxes object
                if (selectedCheckboxes[product_id]) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            });
        }
    });
    
    <?php } ?>
});


</script>