<?php 
  $menuPermission = fetchParentMenu();$menu_arr =[]; $menu_id =[];
  if(!empty($menuPermission)){
        foreach ($menuPermission as $key => $menu_value) {
          if(!empty($menu_value['permissionType'])){
            if($menu_value['id'] == 24 && $menu_value['permissionType'] == 1){
              array_push($menu_arr, $menu_value['permissionType']);
          }
        }
    }
} ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        All Product Information
        </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#"> All Product Information</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"> All Product List</h3>
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
              <div style="margin-bottom: 4%;">
                <?php if( !in_array('1',$menu_arr)){ ?>
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#product_csv_modal" style="margin-right: 1%;">Upload Product CSV</button>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#download_csv_modal" style="margin-right: 1%;">Download Sample csv</button>
                <?php } ?>  
                <form action ="<?php echo base_url('product/export_all_productData'); ?>" method="post">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="submit" class="btn btn-warning" value="Export All Product Data" style="margin-right: 1%;">
                </form>
              </div>
            <div class="row">
              <input type="hidden" name="csrf" value="<?php echo $this->security->get_csrf_token_name(); ?>">
              <div class="form-group col-md-4"> 
                <label for="">Search Minimum Product Score</label>
                  <input type="text" id="min" name="min" class="form-control"  placeholder="Search.." data-type="search">
              </div> 
              <div class="form-group col-md-4"> 
                <label for="">Search Maximum Product Score</label>
                  <input type="text" id="max" name="max" class="form-control"  placeholder="Search.." data-type="search">
              </div> 
              <div class="form-group col-md-4"> 
                <button type="button" name="submit" id="clear-filter" class="btn btn-primary mt-25px" 
                >Reset</button>
              </div> 
            </div>
              <table id="all_product_list" class="table table-bordered table-hover">
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

