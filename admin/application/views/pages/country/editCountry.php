<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
        Country Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Country</li>
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
                  <h3 class="box-title">Edit Country</h3>
               </div>
               <?php $errorMsg = $this->session->userdata('cat_error'); 
                  if(!empty($errorMsg)) { ?>
               <div class="alert alert-danger alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Fail! </strong> <?php echo $errorMsg; ?>
               </div>
               <?php } ?>
               <?php $successMsg = $this->session->userdata('cat_success'); 
                  if(!empty($successMsg)) { ?>
               <div class="alert alert-success alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Success! </strong> <?php echo $successMsg; ?>
               </div>
               <?php } ?>
               <?php echo form_open_multipart('country/updateCountry',array('role'=>'form')); ?>
               <input type="hidden" name="country_id" id="country_id" value="<?php echo $country_id; ?>">
               <div class="box-body">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="books category">Country Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="country_name" placeholder="Country Name" autocomplete="off" value="<?php echo $fetch_data['country_name']; ?>" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="books category">Contry Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="country_code" placeholder="Contry Code" autocomplete="off" value="<?php echo $fetch_data['country_code']; ?>" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="books category">Contry Image</label>
                        <input type="file" class="form-control" name="files">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="books category">Contry Image</label><br>
                        <img src="<?php echo VIEW_IMAGE_URL.'countryImage/'.$fetch_data['country_image'];?>" alt="countryImage" style="width: auto;height: 45px;">
                     </div>
                  </div>
               <!-- /.box-body -->
               <div class="box-footer">
                  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                  <button type="button" name="submit" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('country/viewCountry'); ?>'">Back</button>
               </div>

               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </section>
</div>