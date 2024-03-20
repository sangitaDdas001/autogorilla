<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Manage Promotion Banner Information 
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Promotion Banner</li>
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
                  <h3 class="box-title">Add Promotion Banner Details</h3>
               </div>
               <?php $errorMsg = $this->session->userdata('book_error'); 
                  if(!empty($errorMsg)) { ?>
               <div class="alert alert-danger alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Fail! </strong> <?php echo $errorMsg; ?>
               </div>
               <?php } ?>
               <?php $successMsg = $this->session->userdata('book_success'); 
                  if(!empty($successMsg)) { ?>
               <div class="alert alert-success alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Success! </strong> <?php echo $successMsg; ?>
               </div>
               <?php } ?>
               <?php echo form_open_multipart('banner/addPromotionBanner',array('role'=>'form')); ?>
               <div class="box-body">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="Vendor Company Name">Vendor Company Name</label>
                          <select name="vendor_id" class="form-control select2">
                             <option value="">--- Select Company ---</option>
                              <?php if(!empty($company_list)){ 
                                 foreach ($company_list as $value) { ?>
                                    <option value="<?php echo $value['id'] ;?>"><?php echo $value['company_name'] ;?></option>
                              <?php } } ?>
                          </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Promotion Banner Image</label>
                        <input type="file" class="form-control" name="files">
                        <small class="text-danger"><b>Note:- </b>Maximum image size allowed is 5MB</small>
                     </div>
                  </div> 
                  <input type="hidden" name="banner_type" value="Promo"> 
               <!-- /.box-body -->
               <div class="box-footer">
                  <div class="col-md-12">
                     <center><button type="submit" name="submit" class="btn btn-primary">Submit</button>
                     <button type="button" name="submit" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('banner/viewBannerList'); ?>'">Back</button></center>
                  </div>
               </div>
               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </section>
</div>