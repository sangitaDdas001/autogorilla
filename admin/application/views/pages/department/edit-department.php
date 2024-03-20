<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Department Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Department Information</li>
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
                  <h3 class="box-title">Edit department details</h3>
               </div>
               <?php $errorMsg = $this->session->userdata('department_error'); 
               if(!empty($errorMsg)) { ?>
                  <div class="alert alert-danger alert-dismissible">
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     <strong>Fail! </strong> <?php echo $errorMsg; ?>
                  </div>
               <?php } ?>
               
               <?php echo form_open_multipart('userManagement/updateDepartment',array('role'=>'form')); ?>
               <input type="hidden" name="id" value="<?php echo base64_encode($fetch_data['id']); ?>">
               <div class="box-body">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="">Department Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="department_name" placeholder="Department Name" autocomplete="off" value="<?php echo $fetch_data['department_name']; ?>">
                     </div>
                  </div>
                  
                  <!-- /.box-body -->
                  <div class="box-footer">
                     <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                     <a href="<?php echo base_url('userManagement/department'); ?>"><button type="button" name="back" class="btn btn-warning">Back</button></a>
                  </div>
                  <?php echo form_close(); ?>
               </div>
            </div>
         </div>
      </section>
   </div>