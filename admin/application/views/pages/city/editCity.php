<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
        City Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">City</li>
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
                  <h3 class="box-title">Edit City</h3>
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
               <?php echo form_open_multipart('city/updateCity',array('role'=>'form')); ?>
               <input type="hidden" name="city_id" id="city_id" value="<?php echo $city_id; ?>">
               <?php 
                  $countryArray = $fetch_data['country_id']; 
                  $stateArray   = $fetch_data['state_id']; 
               ?> 
               <div class="box-body">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="books category">Country Name <span class="text-danger">*</span></label>
                        <select name="country_id" class="form-control country" required="required">
                           <option value="">--Select Country Name</option>
                           <?php foreach ($country_list as $conVal) { ?>
                              <option <?php if($conVal['id'] == $countryArray) echo "selected"; ?> value="<?php echo $conVal['id']; ?>"><?php echo !empty($conVal['country_name'])?ucwords($conVal['country_name']):'No record found';?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="books category">State name<span class="text-danger">*</span></label>
                        <select name="state_id" class="form-control" required="required" id="state-name">
                          <?php foreach ($state_list as $stateVal) { ?>
                              <option <?php if($stateVal['state_id'] == $stateArray) echo "selected"; ?> value="<?php echo $stateVal['state_id']; ?>"><?php echo !empty($stateVal['state_name'])?ucwords($stateVal['state_name']):'No record found';?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-5">
                     <div class="form-group">
                        <label for="books category">City Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="city_name" placeholder="Category Name" autocomplete="off" value="<?php echo $fetch_data['city_name']; ?>" required>
                     </div>
                  </div>
                  <div class="col-md-5">
                     <div class="form-group">
                        <label for="book image">Image</label>
                        <input type="file" class="form-control" name="files" autocomplete="off">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <img src="<?php echo VIEW_IMAGE_URL.'cityImage/'.$fetch_data['city_image']; ?>" class='img-thumbnail img-rounded' style="width:auto;height:65px" alt="city image">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="Latitude">Latitude</label>
                        <input type="text" class="form-control" name="latitude" autocomplete="off" placeholder="Latitude" value="<?php echo $fetch_data['latitude']; ?>">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Longitude</label>
                        <input type="text" class="form-control" name="longitude" autocomplete="off" placeholder="Longitude" value="<?php echo $fetch_data['longitude']; ?>">
                     </div>
                  </div>
               <!-- /.box-body -->
               <div class="box-footer">
                  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                  <button type="button" name="submit" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('city/viewCity'); ?>'">Back</button>
               </div>

               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </section>
</div>