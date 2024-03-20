<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
        Category Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Category</li>
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
                  <h3 class="box-title">Edit Category</h3>
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
               <?php echo form_open_multipart('category/updateAllCategoryContent',array('role'=>'form')); ?>
               <input type="hidden" name="id" id="id" value="<?php echo $fetch_data['id']; ?>">
               <div class="box-body">
                  <div class="col-md-12"> 
                     <div class="form-group">
                        <label for="">Category Content</label>
                        <textarea name="header_content"  id="category_content" class="form-control category_content" placeholder="Category Description"><?php echo $fetch_data['header_content']; ?></textarea>
                     </div>
                     <div id="character_count">
                     </div>
                  </div>
                  <div class="col-md-12"> 
                     <div class="form-group">
                        <label for="">Footer Content</label>
                        <textarea name="footer_content" class="form-control category_content"  placeholder="Footer Content" id="footer_content"><?php echo $fetch_data['footer_content']; ?></textarea>
                     </div>
                  </div>
               <!-- /.box-body -->
               <div class="box-footer">
                  <button type="submit" name="submit" class="btn btn-primary ValidateCharacterLength">Submit</button>
                  <button type="button" name="submit" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('category/allCategoryContent'); ?>'">Back</button>
               </div>

               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </section>
</div>