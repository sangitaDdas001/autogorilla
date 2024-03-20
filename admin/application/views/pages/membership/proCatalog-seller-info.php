<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Pro Catelog Service Seller Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Pro Catelog Service Seller Information</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Pro Catelog Service Seller Information</h3>
            </div>

            <!-- /.box-header -->
            <div class="box-body">

            <?php $successMsg = $this->session->userdata('department_success'); 
                if(!empty($successMsg)) { ?>
                  <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success! </strong> <?php echo $successMsg; ?>
                  </div>
            <?php } ?>

            <?php $errorMsg = $this->session->userdata('department_error'); 
              if(!empty($errorMsg)) { ?>
                    <div class="alert alert-danger alert-dismissible">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Fail! </strong> <?php echo $errorMsg; ?>
                    </div>
            <?php } ?>
                  
              <table id="pro_seller" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                      <th>Sl No.</th>
                      <th>Company Name</th>
                      <th>Conpany Vendor Name</th>
                      <th>Email</th>
                      <th>Catelog Avg Score</th>
                      <th>AVerified</th>
                      <th>Feature</th>
                      <th>Product Count</th>
                      <th>Catelog Display Website</th>
                      <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                  <?php foreach ($proCat_member_list as $key => $value): ?>
                    <tr>
                      <td><?php echo $key+1; ?></th>
                      <td><?php echo !empty($value['company_name'])?$value['company_name']:""; ?></td>
                      <td><?php echo $value['name']; ?></td>
                      <td><?php echo $value['email']; ?></td>
                      <td><?php echo $value['cat_avscore']; ?></td>
                       <?php if($value['autogorilla_verified']== 'Y'){ ?>
                          <td><span style="color:green">Yes</span></td>
                        <?php } else { ?> 
                          <td><span style="color:red">No</span></td>
                        <?php } ?> 
                        <?php if($value['featured_company']== 1){ ?>
                          <td><span style="color:green">Featured</span></td>
                        <?php } else { ?> 
                          <td><span style="color:red">Not Featured</span></td>
                        <?php } ?> 
                        <?php if(!empty($value['product'])){ ?>
                        <td><?php echo count($value['product']);?></td>
                        <?php } else { ?>
                          <td><?php echo 0 ;?></td>
                        <?php } ?>
                        <?php if(!empty($value['product']) && $value['approved_status'] == 'Approved'){ $p_arr =[]; ?>
                            <?php foreach ($value['product'] as $key => $pvalue): 
                                array_push($p_arr,$pvalue['approved_status']); ?>
                            <?php endforeach ?>
                              <?php if(in_array(1, $p_arr)){ ?>
                                   <td><span style="color:green">Yes</span></td>
                               <?php } ?>
                        <?php } else { ?>
                          <td><span style="color:red">No</span></td>
                        <?php } ?>  
                      <?php if($value['approved_status'] == 'Approved') { ?>
                        <th><span style="color:green"><?php echo $value['approved_status']; ?></span></th>
                      <?php } else { ?>
                        <th><span style="color:red"><?php echo $value['approved_status']; ?></span></th>
                      <?php } ?>
                    </tr>
                  <?php endforeach ?>
                </tbody>
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

