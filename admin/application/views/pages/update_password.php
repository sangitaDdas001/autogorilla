<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Change Password
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
         <li class="active">Change Password</li>
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
                  <h3 class="box-title">Change Password Details</h3>
               </div>
               <?php $errorMsg = $this->session->userdata('error_msg'); 
                  if(!empty($errorMsg)) { ?>
               <div class="alert alert-danger alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Fail! </strong> <?php echo $errorMsg; ?>
               </div>
               <?php } ?>
               <?php $successMsg = $this->session->userdata('success_msg'); 
                  if(!empty($successMsg)) { ?>
               <div class="alert alert-success alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Success! </strong> <?php echo $successMsg; ?>
               </div>
               <?php } ?>
               <?php echo form_open('dashboard/editPassword',array('role'=>'form')); ?>
               <div class="box-body">
                  <div class="form-group">
                     <label for="exampleInputEmail1">Current Password</label>
                     <input type="password" class="form-control" name="oldpassword" placeholder="Current password" autocomplete="off">
                  </div>
                  <div class="form-group">
                     <label for="exampleInputPassword1">New Password</label>
                     <input type="password" class="form-control" name="newpassword" placeholder="New password" autocomplete="off">
                  </div>
               </div>
               <!-- /.box-body -->
               <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
               </div>
               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </section>
</div>