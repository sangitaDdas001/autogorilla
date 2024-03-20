<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         City Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">City Information</li>
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
                  <h3 class="box-title">Add City details</h3>
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
               <?php echo form_open_multipart('city/addCity',array('role'=>'form')); ?>
               <div class="box-body">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Country Name <span class="text-danger">*</span></label>
                        <select name="country_id" class="form-control country" required="required">
                          <option value="">--Select Country--</option>
                           <?php foreach ($country_list as $cval) { ?>
                                 <option value="<?php echo $cval['id'];?>"><?php echo !empty($cval['country_name'])?ucwords($cval['country_name']):'No record found';?></option>
                           <?php } ?>
                       </select>
                     </div>
                  </div>
                   <div class="col-md-6">
                     <div class="form-group">
                        <label for="">State Name <span class="text-danger">*</span></label>
                        <select name="state_id" class="form-control" required="required" id="state-name">
                          <option value="">--Select State--</option>
                           
                       </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">City Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="files" autocomplete="off" required="required">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">City Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="city_name" placeholder="City Name" autocomplete="off" required="required">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="Latitude">Latitude</label>
                        <input type="text" class="form-control" name="latitude" autocomplete="off" placeholder="Latitude">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Longitude</label>
                        <input type="text" class="form-control" name="longitude" autocomplete="off" placeholder="Longitude">
                     </div>
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                     <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                  </div>
                  <?php echo form_close(); ?>
               </div>
            </div>
         </div>
      </section>
   </div>
  