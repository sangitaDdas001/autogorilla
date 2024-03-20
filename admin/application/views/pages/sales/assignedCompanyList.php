<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Assigned Company Information </h1>
 
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Company List</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Assigned Company List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <?php $successMsg = $this->session->userdata('suppliers_product_success'); 
                if(!empty($successMsg)) { ?>
                  <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success! </strong> <?php echo $successMsg; ?>
                  </div>
            <?php } ?>

            <?php $errorMsg = $this->session->userdata('suppliers_product_error'); 
              if(!empty($errorMsg)) { ?>
                    <div class="alert alert-danger alert-dismissible">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Fail! </strong> <?php echo $errorMsg; ?>
                    </div>
            <?php } ?>
            <?php $userid = $this->uri->segment(3); ?>
              <div style="margin-bottom: 4%; display: flex;">
                <form action="<?php echo base_url('SalesManagement/exportCsv/'.$userid); ?>" method="post">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="submit" class="btn btn-warning" style="float: right; margin-right: 1%;" value="Export Company Assign List Csv">
                </form>
                <button class="text-capitalize btn-primary" onclick="history.back();" style="border-radius:5px;border: none; padding: 3px 12px;"><i class="fa fa-chevron-left" aria-hidden="true" style="margin-right: 5px;"></i>back</button>
              </div>
              <table id="company_details" class="table table-bordered table-hover">
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


