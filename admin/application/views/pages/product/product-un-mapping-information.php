<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
         All Unmapped Products
        </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="<?php echo base_url('product/all_unmapped_products'); ?>"> All Unmapped Products</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"> All Unmapped Product List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <?php $successMsg = $this->session->userdata('product_mapping_success'); 
                if(!empty($successMsg)) { ?>
                  <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success! </strong> <?php echo $successMsg; ?>
                  </div>
            <?php } ?>

            <?php $errorMsg = $this->session->userdata('product_mapping_error'); 
              if(!empty($errorMsg)) { ?>
                    <div class="alert alert-danger alert-dismissible">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Fail! </strong> <?php echo $errorMsg; ?>
                    </div>
            <?php } ?>

            <!-- <div class="row">
              <input type="hidden" name="csrf" value="<?php echo $this->security->get_csrf_token_name(); ?>">
              <div class="form-group col-md-4"> 
                <label for="">Search Minimum Date</label>
                  <input type="text" id="min_date" name="min_date" class="form-control" placeholder="0000-00-00" data-type="search" readonly>
              </div> 
              <div class="form-group col-md-4"> 
                <label for="">Search Maximum Date</label>
                  <input type="text" id="max_date" name="max_date" class="form-control" placeholder="0000-00-00" data-type="search" readonly>
              </div> 
              <div class="form-group col-md-4">
              <button type="button" name="submit" id="searchByDateRange" class="btn btn-success mt-25px" 
                >Submit</button> 
              <a class="btn btn-primary mt-25px" href="<?php echo base_url('product/all_unmapped_products'); ?>">Reset</a>
              </div> 
            </div> -->

              <table id="all_product_un_mapping_details" class="table table-bordered table-hover">
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