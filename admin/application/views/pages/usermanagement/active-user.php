<style>
/* Popup container - can be anything you want */
/* Popup container - can be anything you want */
.popup {
  position: relative;
  display: inline-block;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* The actual popup */
.popuptext {
  visibility: hidden;
  width: 219px;
  background-color: #066609b8;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 11px 0;
  position: absolute;
  z-index: 1;
  bottom: 128%;
  left: 50%;
  margin-left: -73px;
}

/* Popup arrow */
.popup .popuptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

/* Toggle this class - hide and show the popup */
.popup .show {
  visibility: visible;
  -webkit-animation: fadeIn 1s;
  animation: fadeIn 1s;
}

/* Add animation (fade in the popup) */
@-webkit-keyframes fadeIn {
  from {opacity: 0;} 
  to {opacity: 1;}
}

@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity:1 ;}
}
</style>
<?php 
    //echo '<pre>';print_r($_SESSION['supplier']);
    unset($_SESSION["suppliers_product_error"]); 
    unset($_SESSION["suppliers_product_success"]); 
    unset($_SESSION["menu_error"]); 
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Active User Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Active User List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Active User List</h3>
              <div class="box-footer" style="float: right;">
                <a href="#" class="download_sample_csv"><button type="button" name="submit" class="btn btn-primary ">Download Assign Product sample CSV</button></a>
                <button type="button" name="submit" class="btn btn-success" data-toggle="modal" data-target="#assign_product_csv_modal">Upload csv assign product user</button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div id ="alert-msgs"></div>
              <?php $successMsg = $this->session->userdata('user_success'); 
                  if(!empty($successMsg)) { ?>
                    <div class="alert alert-success alert-dismissible">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Success! </strong> <?php echo $successMsg; ?>
                    </div>
              <?php } ?>

            <?php $errorMsg = $this->session->userdata('user_error'); 
              if(!empty($errorMsg)) { ?>
                    <div class="alert alert-danger alert-dismissible">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Fail! </strong> <?php echo $errorMsg; ?>
                    </div>
            <?php } ?>

              <table id="active_user_list" class="table table-bordered table-hover">
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

<!--##### modal #######-->
<div class="modal fade" id="assign_product_csv_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?php echo base_url('UserManagement/uploadAssignProductCsv'); ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Assign product to employee</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Upload csv</label>
            <input type="file" name="upload_assign_csv" class="form-control" style="width: 80%;">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- /.modal -->

<div class="modal fade assigned_product_list">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Assigned Product List For <span class="user_name"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <center>
          <div class="popup" >
            <span class="popuptext" id="deleted_popup">Product deleted successfully</span>
          </div>
        </center>
        
        <div class="modal-body">
          <div class="box-header" style=" margin-top: -21px; float: right;">
            <button type="button" class="btn btn-danger" id="deleteAllButton">Delete All</button>
        </div>
          <table id="dynamicTable" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th style="color:#194e7e">Check All &nbsp;<span><input type ="checkbox" name="product_id[]" id="selected_product_ids"  style="color:#194e7e"></span></th>
                    <th style="color:#194e7e">Product Id</th>
                    <th style="color:#194e7e">Product Name</th>
                    <th style="color:#194e7e">Product Comapny Name</th>
                    <th style="color:#194e7e">Product Assign Date</th>
                    <th style="color:#194e7e">Action</th>
                </tr>
            </thead>
          <tbody>
          </tbody>
        </table> 
        </div>
      </div>
      <!-- /.modal-content -->
 </div>
    <!-- /.modal-dialog -->
 </div> 
<!-- /.modal -->
