<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
        How It Works
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">How It Works</li>
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
                  <h3 class="box-title">Edit How It Works</h3>
               </div>
               <?php $errorMsg = $this->session->userdata('error'); 
                  if(!empty($errorMsg)) { ?>
               <div class="alert alert-danger alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Fail! </strong> <?php echo $errorMsg; ?>
               </div>
               <?php } ?>
               <?php $successMsg = $this->session->userdata('success'); 
                  if(!empty($successMsg)) { ?>
               <div class="alert alert-success alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Success! </strong> <?php echo $successMsg; ?>
               </div>
               <?php } ?>
               <?php echo form_open_multipart('aboutUs/updateAboutUs',array('role'=>'form')); ?>
               <input type="hidden" name="about_id" id="about_id" value="<?php echo $about_id; ?>">
               <div class="box-body">
                  <div class="form-group">
                     <label for="">Content For</label>
                     <input type="text" name="content_for" value="<?php echo ucwords($fetch_data['content_for']); ?>" class="form-control" readonly>
                  </div>
                  <div class="form-group">
                     <label for="">Overview Content</label>
                     <textarea class="form-control" name="content" id="content_edit" placeholder="Overview Content" autocomplete="off"><?php echo $fetch_data['content']; ?></textarea>
                  </div>
                  
                 
                  <div class="form-group">
               </div>
               <!-- /.box-body -->
               <div class="box-footer">
                  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                  <button type="submit" name="back" class="btn btn-primary" onclick="widow.location.href='<?php echo base_url('aboutUs/viewAboutUs');?>'">Back</button>
               </div>
               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </section>
</div>