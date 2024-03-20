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
                  <h3 class="box-title">Edit User Information</h3>
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
               <?php echo form_open_multipart('userManagement/editUserDetails',array('role'=>'form')); ?>
               <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
               <div class="box-body">

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo !empty($fetch_data['first_name'])?$fetch_data['first_name']:''; ?>" class="form-control" required="required" placeholder="First Name" autocomplete="off">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" value="<?php echo !empty($fetch_data['middle_name'])?$fetch_data['middle_name']:''; ?>" class="form-control"  placeholder="Middle Name" autocomplete="off">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo !empty($fetch_data['last_name'])?$fetch_data['last_name']:''; ?>" class="form-control" required="required" placeholder="Last Name" autocomplete="off">
                     </div>
                  </div>
                  
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" value="<?php echo !empty($fetch_data['email'])?$fetch_data['email']:''; ?>" required="required"  placeholder="Email" autocomplete="off">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="Latitude">Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="mobile" value="<?php echo !empty($fetch_data['mobile'])?$fetch_data['mobile']:''; ?>" autocomplete="off" placeholder="Mobile" onkeypress="return isNumber(event)" min=10 maxlength="10" required="required">
                     </div>
                  </div>

                  <div class="col-md-6 d-flex">
                     <div class="form-group" style="width: 100%; position: relative;">
                        <label for="">Password <span class="text-danger">*</span></label>
                        <input id="password-field" type="password" style="padding-right: 30px;" class="form-control" name="password" value="<?php echo !empty($fetch_data['password_hint'])?$fetch_data['password_hint']:''; ?>">
                        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password" style="position: absolute;
                        right: 10px; top: 35px;"></span>
                     </div>
                     
                  </div>
                  
                    <?php $departmentArr = explode(",", $fetch_data['department_id']); ?>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Department <span class="text-danger">*</span></label>
                        <select name="department_id[]" class="form-control select2" multiple>
                           <option value="">--Select Department--</option>
                             <?php if(!empty($department_list)) { 
                              foreach ($department_list as $key => $dep) { ?>
                                 <option <?php if(in_array($dep['id'], $departmentArr)) echo "selected"; ?> value="<?php echo $dep['id']; ?>"><?php echo $dep['department_name']; ?></option>
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
                     <button class="btn btn-primary" onclick="window.location.href='<?php echo base_url('userManagement/user-list'); ?>';">Back</button>
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

    $(".toggle-password").click(function() {
   $(this).toggleClass("fa-eye fa-eye-slash");
   var input = $($(this).attr("toggle"));
   if (input.attr("type") == "password") {
   input.attr("type", "text");
   } else {
   input.attr("type", "password");
   }
});  
  </script>