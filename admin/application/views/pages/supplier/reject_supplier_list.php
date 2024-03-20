<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Rejected Supplier Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="<?php echo base_url('supplier/reject_supplier'); ?>">Reject Supplier List</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Rejected Supplier List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <?php $successMsg = $this->session->userdata('suppliers_success'); 
                if(!empty($successMsg)) { ?>
                  <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success! </strong> <?php echo $successMsg; ?>
                  </div>
            <?php } ?>

            <?php $errorMsg = $this->session->userdata('suppliers_error'); 
              if(!empty($errorMsg)) { ?>
                    <div class="alert alert-danger alert-dismissible">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Fail! </strong> <?php echo $errorMsg; ?>
                    </div>
            <?php } ?>
            <!-- <?php if(!empty($check_reject_data)){ ?>
              <div style="margin-bottom: 4%;">
                <input type="submit" class="btn btn-success approved_all_rejectedSupplier" style="float: right;margin-right: 1%;" value="Approve All Suppliers">
              </div>
            <?php } ?> -->
            <div style="margin-bottom: 4%;">
                <form action="<?php echo base_url('supplier/rejectedDataCsvDownload'); ?>" method="post">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="submit" class="btn btn-success" value="Export Rejected Csv">
                </form>
              </div>
              
              <table id="reject_supplier_details" class="table table-bordered table-hover">
                <thead>
                  <tr>
                  <?php if(!empty($columns)) {
                         foreach($columns as $value)  { 
                           echo '<th>'.$value.'</th>';
                         }
                  } ?>
                  </tr>
                </thead>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>