<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Category Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="<?php echo base_url('category/viewCategory'); ?>">Category</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Category Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <?php $successMsg = $this->session->userdata('cat_success'); 
                if(!empty($successMsg)) { ?>
                  <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success! </strong> <?php echo $successMsg; ?>
                  </div>
            <?php } ?>

            <?php $errorMsg = $this->session->userdata('cat_error'); 
              if(!empty($errorMsg)) { ?>
                    <div class="alert alert-danger alert-dismissible">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Fail! </strong> <?php echo $errorMsg; ?>
                    </div>
            <?php } ?>
            <div style="margin-bottom: 4%;">
              <form action="<?php echo base_url('Category/csvDownload'); ?>" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="submit" class="btn btn-success" style="float: right;" value="Download Parent Category">
              </form>
            </div>

              <table id="category_details" class="table table-bordered table-hover">
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