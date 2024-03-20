<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Parent Category Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Parent Category Information</li>
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
                  <h3 class="box-title">Add parent category details</h3>
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
               <?php echo form_open_multipart('category/uploadData',array('role'=>'form')); ?>
               <div class="box-body">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Import <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="userfile" id="userfile"  align="center"/>
                     </div>
                  </div> 
                  
                  
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" name="submit" class="btn btn-info">Save</button>
                  </div>
                  <?php echo form_close(); ?>
               </div>
            </div>
         </div>
      </section>
   </div>