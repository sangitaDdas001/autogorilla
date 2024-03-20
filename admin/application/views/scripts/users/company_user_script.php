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
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
        }
    });

    
    if( ('#all_company_list').length ) {
        $('#all_company_list').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax" : {
                "url" : localurl+"UserManagement/allcompanyInfo_ajax",
                "type" : "POST",
                },
            "columns": <?php echo $companyData; ?>,
            "language": {
                infoEmpty: "No records found",
            }
        });    
    }

});
</script>
