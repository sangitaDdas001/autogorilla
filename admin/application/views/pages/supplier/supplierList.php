<?php 
  $menuPermission = fetchParentMenu();$menu_arr =[];$menu_id =[];
  if(!empty($menuPermission)){
          foreach ($menuPermission as $key => $menu_value) {
            if(!empty($menu_value['permissionType'])){
              if($menu_value['id'] == 19 && $menu_value['permissionType'] == 1){
                array_push($menu_arr, $menu_value['permissionType']);
              }
            }
          }
      } ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Supplier Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="<?php echo base_url('supplier'); ?>">Supplier List</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Supplier List</h3>
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
            <?php if( !in_array('1',$menu_arr)){ ?>
            <div style="margin-bottom: 4%; display: flex;">
              <form action="<?php echo base_url('supplier/csvDownload'); ?>" method="post" style="margin-right: 1%;">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="submit" class="btn btn-success" value="Download Sample Csv">
              </form>
              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#company_csv_modal" style="margin-right: 1%;">Upload Company CSV</button>
                <form action="<?php echo base_url('supplier/exportAllDataByCsv'); ?>" method="post">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="submit" class="btn btn-warning" style="float: right; margin-right: 1%;" value="Export Company Csv">
                </form>
              <!-- <input type="submit" class="btn btn-danger rejected_all_suppler" style="float: right;margin-right: 1%;" value="Reject All Suppliers">
              <input type="button" class="btn btn-success approve_all_suppler" style="float: right;margin-right: 1%;" value="Approve All Suppliers"> -->
             
            </div>
          <?php } ?>
            <div class="responsive-table">
              <div class="row">
                      <input type="hidden" name="csrf" value="<?php echo $this->security->get_csrf_token_name(); ?>">
                      <!-- <input type="hidden" name="admin_user_id" id="admin_userId" value="<?php echo base64_decode($userId_segment); ?>"> -->
                      
                      <div class="form-group col-md-4"> 
                        <label for="">Search Minimum Catelog Score</label>
                          <input type="text" id="min" name="min" class="form-control"  placeholder="Search.." data-type="search">
                      </div> 
                      <div class="form-group col-md-4"> 
                        <label for="">Search Maximum Catelog Score</label>
                          <input type="text" id="max" name="max" class="form-control"  placeholder="Search.." data-type="search">
                      </div> 
                      <div class="form-group col-md-4"> 
                        <button type="button" name="submit" id="clear-filter" class="btn btn-primary mt-25px" >Reset</button>
                      </div> 
                  </div>
                  <div class="table-responsive">
                    <table id="supplier_details_all" class="table table-bordered table-hover" >
                      <thead>
                        <tr>
                          <th>Company Id</th>
                          <th>Company Name</th>
                          <th>Company Vendor Name</th>
                          <th>Email</th>
                          <th>Catlog Avg Score</th>
                          <th>Membership</th>
                          <th>Verified</th>
                          <th>Featured</th>
                          <th>Created Date</th>
                          <th>Status</th>
                          <th>Update Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
              </div>
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

<!-- start modal -->
<!-- Modal -->
<div class="modal fade" id="membership_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <style>
table {
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="font-size: 20px;">Subscription Information</h5>
        <b><span class="company_name"></span></b>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table>
            <tr>
              <th>Sl No.</th>
              <th>Subscription Plan</th>
              <th>Subscription Start Date</th>
              <th>Subscription End Date</th>
              <th>Total Calculates Day's</th> 
              <th>Number of Day's</th>
              <th>Action</th>
            </tr>
            <tbody class="dynamicSubscriptionData">
                            
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>