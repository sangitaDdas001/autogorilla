<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
        <?php echo ucwords($vendor_details[0]['company_name']); ?>  
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active"> Manage Vendor Service</li>
      </ol>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
               
               <div class="box-header with-border">
                  <h2 class="box-title"><?php echo ucwords($vendor_details[0]['name']); ?></h2>
               </div>
               <?php $errorMsg = $this->session->userdata('service_error'); 
               if(!empty($errorMsg)) { ?>
                  <div class="alert alert-danger alert-dismissible">
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     <strong>Fail! </strong> <?php echo $errorMsg; ?>
                  </div>

               <?php } ?>
               <?php $successMsg = $this->session->userdata('service_success'); 
               if(!empty($successMsg)) { ?>
                  <div class="alert alert-success alert-dismissible">
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     <strong>Success! </strong> <?php echo $successMsg; ?>
                  </div>
               <?php } ?>
               <?php echo form_open_multipart('supplier/vendorPackageAdd',array('role'=>'form')); ?>
               <input type="hidden" name="vendor_id" value="<?php echo $vendor_details[0]['id']; ?>" id="vendor_id">
               <div class="box-body">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="">Service Package</label><br>
                        <?php if(!empty($get_services_details)){ 
                           foreach ($get_services_details as $key => $value): ?>
                              <?php if($value['id']== $value['service_package_id'] && $value['status']== 'A'){ ?>
                                 <input type="checkbox" name="service_package_id[<?php echo $value['id'] ?>]" value="<?php echo $value['id']; ?>" class="service_package_id" data-id = "<?php echo $value['id']; ?>" checked id="pack_id_<?php echo $value['id']; ?>"> 
                              <?php } else { ?>
                                 <input type="checkbox" name="service_package_id[<?php echo $value['id'] ?>]" value="<?php echo $value['id']; ?>" class="service_package_id" data-id = "<?php echo $value['id']; ?>" id="pack_id_<?php echo $value['id']; ?>"> 
                              <?php } ?>
                              <?php echo $value['service_plan']; ?><br>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label for="">Start Date</label>
                                    <input type="date" class="form-control package_start_date_<?php echo $value['id']; ?>" name="package_start_date[<?php echo $value['id']; ?>]" placeholder="Service Start Date" autocomplete="off" value="<?php echo !empty($value['package_start_date'])?$value['package_start_date']:''; ?>">
                                 </div>
                              </div>  
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label for="">Expiry Date</label>
                                    <input type="date" class="form-control package_expiry_date_<?php echo $value['id']; ?>" name="package_expiry_date[<?php echo $value['id'] ?>]" placeholder="Service Expiry Date" autocomplete="off" value="<?php echo !empty($value['package_expiry_date'])?$value['package_expiry_date']:''; ?>" >
                                 </div>
                              </div>
                           <?php endforeach ?>
                        <?php } ?> 
                     </div>
                  </div> 
                  

                  <!-- /.box-body -->
                  <div class="box-footer">
                     <div class="col-md-12">
                        <center><button type="submit" name="submit" class="btn btn-primary">Submit</button>
                           <button type="button" name="submit" class="btn btn-primary" onclick="window.location.href = '<?php echo base_url('supplier'); ?>'">Back</button></center>
                        </div>
                     </div>
                     <?php echo form_close(); ?>
                  </div>
               </div>
               
               <!-- FOR AUTO GORILLA VERIFIED SECTION -->
               <div class="box-header with-border">
                  <h2 class="box-title">AUTO GORILLA VERIFIED SECTION</h2>
               </div>
               <?php echo form_open_multipart('supplier/updateAutogorilla_verified_status',array('role'=>'form')); ?>
               <input type="hidden" name="vendor_id" value="<?php echo $vendor_details[0]['id']; ?>" id="vendor_id">
               <input type="hidden" name="service_package_id" value= "5" id="service_package_id">
               <div class="box-body">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="">Autogorilla Verified</label><br>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="">Start Date</label>
                              <input type="date" class="form-control" name="package_start_date" placeholder="Service Start Date" autocomplete="off" value="<?php echo !empty($get_autogorilla_verified_data[0]['package_start_date'])?$get_autogorilla_verified_data[0]['package_start_date']:''; ?>">
                           </div>
                        </div>  
                        <div class="col-md-6">
                           <div class="form-group">
                           <label for="">Expiry Date</label>
                           <input type="date" class="form-control" name="package_expiry_date" placeholder="Service Expiry Date" autocomplete="off" value="<?php echo !empty($get_autogorilla_verified_data[0]['package_expiry_date'])?$get_autogorilla_verified_data[0]['package_expiry_date']:''; ?>">
                        </div>
                     </div>
                  </div>
               </div> 
               <!-- /.box-body -->
                  <div class="box-footer">
                     <div class="col-md-12">
                        <center><button type="submit" name="submit" class="btn btn-primary">Submit</button>
                           <button type="button" name="submit" class="btn btn-primary" onclick="window.location.href = '<?php echo base_url('supplier'); ?>'">Back</button></center>
                        </div>
                     </div>
                     <?php echo form_close(); ?>
                  </div>
               </div>
            </div>
         </section>
      </div>