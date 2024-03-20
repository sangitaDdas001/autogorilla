<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jstree.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/sales.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function($) {
        $.ajaxSetup({
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>','admin_user_id':'<?php echo !empty($userId)?$userId:''; ?>',
            }
        });
        <?php if(!empty($companyData)) {  ?> 
            $('#company_details').DataTable({
                "processing": true,
                "serverSide": true,
                "scrollX": true,
                "ajax" : {
                    "url" : localurl+"SalesManagement/companyDetails_ajax",
                    "type" : "POST",
                },
                "columns": <?php echo $companyData; ?>,
                "language": {
                    infoEmpty: "No records found",
                }
            });
        <?php } ?>
   }); 

    
</script>