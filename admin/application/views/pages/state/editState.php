<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
        State Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">State</li>
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
                  <h3 class="box-title">Edit State</h3>
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
               <?php echo form_open_multipart('state/updateState',array('role'=>'form')); ?>
               <?php $countryArray = $fetch_data['country_id']; ?> 
               <input type="hidden" name="state_id" id="state_id" value="<?php echo $state_id; ?>">
               <div class="box-body">
                  <div class="form-group">
                     <label for="books category">Country Name <span class="text-danger">*</span></label>
                        <select name="country_id" class="form-control" required="required">
                           <option value="">--Select Country Name</option>
                           <?php foreach ($country_list as $conVal) { ?>
                              <option <?php if($conVal['id'] == $countryArray) echo "selected"; ?> value="<?php echo $conVal['id']; ?>"><?php echo !empty($conVal['country_name'])?ucwords($conVal['country_name']):'No record found';?></option>
                           <?php } ?>
                        </select>
                  </div>
                  <div class="form-group">
                     <label for="book image">State</label>
                     <input type="text" name="state_name" class="form-control" value="<?php echo !empty($fetch_data['state_name'])?$fetch_data['state_name']:''; ?>">
                  </div>
                  
               <!-- /.box-body -->
               <div class="box-footer">
                  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                  <button type="button" name="submit" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('state/viewState'); ?>'">Back</button>
               </div>

               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </section>
</div>