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

<?php $userId_segment = $this->uri->segment(3); 
  $userName = fetchUserName(base64_decode($userId_segment)); 
  $count_product_data = count($c_assign_product);
?>
<div class="content-wrapper">
  
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <?php if(!empty($userName[0]['name'])){ ?>
        <h1>
           Unassigned All Active Products For <?php echo $userName[0]['name'] ?>
        </h1>
      <?php } ?>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="<?php echo base_url('product'); ?>">Pending Product List</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Unassigned Product List</h3>
                <div class="box-footer" style="float: right;">
                  <button type="submit" name="submit" class="btn btn-primary submit_productData">Check Data Submit</button>
                  <?php if($count_product_data > 0) { ?>
                    <button type="submit" name="submit" class="btn btn-success" id="user_assigned_product_list">Assigned Product List</button>
                  <?php } ?>
                  <a href="<?php echo base_url('userManagement/activeUserList'); ?>"><button type="submit" name="submit" class="btn btn-warning" id="back">Back</button></a>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <center>
                <div class="popup" >
                  <span class="popuptext" id="myPopup">Product assigned successfully</span>
                </div>
              </center>

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

              <table id="supplier_product_details" class="table table-bordered table-hover">
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
              <input type="hidden" name ="product_id[]" value ="" id ="selectedIds">
              <input type="hidden" name ="user_id" class="user_id" value="<?php echo $userId_segment; ?>">
              <div class="box-footer" style="float: left;">
                  <button type="submit" name="submit" class="btn btn-primary submit_productData">Check Data Submit</button>
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

<!-- /.modal -->

<div class="modal fade assigned_product_list">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Assigned Product List For <b><?php echo $userName[0]['name'].'( ID:'.base64_decode($userId_segment).')' ?> </b></h4>
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

