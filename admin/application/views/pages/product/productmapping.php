<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
        Country Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Country</li>
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
                  <h3 class="box-title">Edit Country</h3>
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
               <?php echo form_open_multipart('Product/categoryProductMapping',array('role'=>'form')); ?>
               <input type="hidden" name="product_id" id="product_id" value="<?php echo base64_decode($product_id); ?>">
               <input type="hidden" name="vendor_id" id="vendor_id" value="<?php echo $fetch_data['vendor_id']; ?>">
               <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" id="csrf">
               <div class="box-body">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="books category">Product Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="product_name" placeholder="Product Name" autocomplete="off" value="<?php echo $fetch_data['product_name']; ?>" readonly>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="books category">Parent Category <span class="text-danger">*</span></label>
                        <select class="form-control parent_id" name="autogorila_parent_cat_id">
                           <option>--Select Parent Category--</option>
                           <?php if(!empty($parent_categories)) { 
                              foreach ($parent_categories as $key => $pa_value) { ?> 
                                 <option value="<?php echo $pa_value['id']; ?>"><?php echo $pa_value['category_name']; ?></option>
                              <?php } ?>
                           <?php } ?>  
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="books category">Sub Category <span class="text-danger">*</span></label>
                        <select class="form-control subcat-parent" id="subcat-name" name="autogorila_sub_cat_id">
                           <option>--Select Sub Category--</option>
                        </select>
                     </div>
                  </div>

                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="books category">Sub Category <span class="text-danger">*</span></label>
                        <select class="form-control microcat-parent" multiple id="micro_cat_id" name="autogorila_micro_cat_id[]">
                           <option>--Select Micro Cat Category--</option>
                        </select>
                     </div>
                  </div>
                  

                  <!-- /.box-body -->
                  <div class="box-footer">
                     <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                     <button type="button" name="submit" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('admin/product'); ?>'">Back</button>
                  </div>
               </div>
               <?php echo form_close(); ?>
            </div>
         </div>
      </section>
   </div>