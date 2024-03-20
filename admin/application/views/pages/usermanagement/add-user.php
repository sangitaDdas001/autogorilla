<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         User Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">User Information</li>
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
                  <h3 class="box-title">User Registration</h3>
               </div>
               <?php $errorMsg = $this->session->userdata('user_error'); 
               if(!empty($errorMsg)) { ?>
                  <div class="alert alert-danger alert-dismissible">
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     <strong>Fail! </strong> <?php echo $errorMsg; ?>
                  </div>
               <?php } ?>
               <?php $successMsg = $this->session->userdata('user_success'); 
               if(!empty($successMsg)) { ?>
                  <div class="alert alert-success alert-dismissible">
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     <strong>Success! </strong> <?php echo $successMsg; ?>
                  </div>
               <?php } ?>
               <?php echo form_open_multipart('userManagement/addUser',array('role'=>'form')); ?>
               <div class="box-body">

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required="required" placeholder="First Name" autocomplete="off">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" class="form-control"  placeholder="Middle Name" autocomplete="off">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required="required" placeholder="Last Name" autocomplete="off">
                     </div>
                  </div>
                  
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" required="required"  placeholder="Email" autocomplete="off">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="Latitude">Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="mobile" autocomplete="off" placeholder="Mobile" onkeypress="return isNumber(event)" min=10 maxlength="10" required="required">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Password <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="password" autocomplete="off" placeholder="Password">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Department <span class="text-danger">*</span></label>
                        <select name="department_id[]" class="form-control select2" multiple>
                           <option value="">--Select Department--</option>
                             <?php if(!empty($department_list)) { 
                              foreach ($department_list as $key => $dep) { ?>
                                 <option value="<?php echo $dep['id']; ?>"><?php echo $dep['department_name']; ?></option>
                              <?php } }
                             else { ?>
                                 <option value="">No record found</option>
                             <?php } ?>
                        </select>
                     </div>
                  </div>
               </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                     <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                     <button class="btn btn-warning" onclick="window.location.href='<?php echo base_url('userManagement/user-list'); ?>';">Back</button>
                  </div>
               
               <?php echo form_close(); ?>
            </div>
         </div>
      </section>
   </div>
   <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
   <script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
  <script type="text/javascript">
     if ($('.select2').length>0) {
         $('.select2').select2({
         placeholder: '-- Select Departments --',
         allowClear: true
      });
    }
  </script>