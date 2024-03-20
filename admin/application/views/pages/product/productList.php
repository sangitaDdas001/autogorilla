<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <?php if(!empty($vendor_info[0]['company_name'])){ ?>
        <h1>
          <?php echo $vendor_info[0]['company_name'] ?> Product Information
        </h1>
      <?php }else {?>
        <h1>
         Pending Product Information
        </h1>
      <?php }?>
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
              <h3 class="box-title">Pending Product List</h3>
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

<!-- Modal -->
<div class="modal fade" id="productDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Product Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <html>
<head>
<style>
      table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
      }

      td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        width: 10px;
      }

      tr:nth-child(even) {
        background-color: #dddddd;
      }

      .sp_td {
        width: 42%
      }
</style>
</head>
<body>

<h3 class="p_name" style="margin-block: auto;"></h3>
<div style="margin-top: 9px;
    margin-bottom: 10px;">
  <span class="badge badge-pending  product_approve_status cursor-pointer" id="p_status" style="width: 17%; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px;">Pending</span>
  <span class="badge badge-success" id="a_status" style="width: 17%; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px;">Approve</span>
  <span class="badge badge-warning cursor-pointer" id="r_status" style="width: 17%; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px;">Reject</span>
</div>

<input type="hidden" name="productId" value="" id="p_id">
<p class="p_description"></p>

<table>
  <tr>
    <td>Vendor Name</td>
    <td class="v_name"></td>
  </tr>
  <tr class="price">
    <td>Price</td>
    <td class="p_price"></td>
  </tr>
  <tr class="brand">
    <td>Brand</td>
    <td class="b_name"></td>
  </tr>
  <tr class="p_video">
    <td>Product Videos1</td>
    <td class="p_videos_data1"></td>
  </tr>
  <tr class="p_video2">
    <td>Product Videos2</td>
    <td class="p_videos_data2"></td>
  </tr>
  <tr class="p_video3">
    <td>Product Videos3</td>
    <td class="p_videos_data3"></td>
  </tr>
  <tr class="p_img1"></tr>
  <tr class="p_img2"></tr>
  <tr class="p_img3"></tr>
</table>
  <h3 style="margin-top:30px;font-size: 22px;">Product Specication</h3>
  <table id="spcefication_tbl">
  </table>

  <h3 style="margin-top:30px;font-size: 22px;">Map Product with Auto Gorilla Category</h3>
  <table id="mappingCat_tbl">
    <tr>
      <th>Parent Category</th>
      <th>Sub Parent Category</th>
      <th>Micro Category</th>
    </tr>
    <tr class="data1">
      <td class="parent_cat"></td>
      <td class="sub_cat"></td>
      <td class="micro_cat"></td>
    </tr>
  </table>

</body>
</html>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>