<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Edit Banner Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Banner List</li>
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
                  <h3 class="box-title">Edit Banner Information</h3>
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
               <?php echo form_open_multipart('banner/updatePromoBannerDetails',array('role'=>'form')); ?>
               <?php 
                  $CompanyArray = $fetch_data['vendor_id']; 
               ?>
               <input type="hidden" name="banner_id" id="banner_id" value="<?php echo $banner_id; ?>">
               <div class="box-body">
                  <div class="col-md-5">
                     <div class="form-group">
                        <label for="">Company Name</label>
                        <select name="vendor_id" class="form-control" required="required">
                           <option value="">--Select Company --</option>
                           <?php foreach ($company_list as $val) { ?>
                              <option <?php if($val['id'] == $CompanyArray) echo "selected"; ?> value="<?php echo $val['id']; ?>"><?php echo !empty($val['company_name'])?ucwords($val['company_name']):'No record found';?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-5">
                     <div class="form-group">
                        <label for="">Banner Image</label>
                        <input type="file" class="form-control" name="files">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label for="">Banner Image</label>
                         <img src="<?php echo VIEW_IMAGE_URL.'promotion_banner_Image/'.$fetch_data['bannerImage']; ?>" class='img-thumbnail img-rounded' style="width:auto;height:65px" alt="Promo banner">
                     </div>
                  </div>
               <!-- /.box-body -->
               <div class="box-footer">
                  <div class ="col-md-12">
                     <center><button type="submit" name="submit" class="btn btn-primary">Submit</button>
                     <button type="button" name="submit" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('banner/viewPromotionBanner'); ?>'">Back</button></center>
                  </div>
               </div>
               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </section>
</div>