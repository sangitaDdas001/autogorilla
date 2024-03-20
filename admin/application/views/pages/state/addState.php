<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         State Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">State Information</li>
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
                  <h3 class="box-title">Add State details</h3>
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
               <?php echo form_open_multipart('state/addState',array('role'=>'form')); ?>
               <div class="box-body">
                  <?php if(!empty($country_list)){ ?>
                     <div class="form-group">
                        <label for="">Country Name <span class="text-danger">*</span></label>
                        <select name="country_id" class="form-control" required="required">
                          <option value="">--Select Country--</option>
                           <?php foreach ($country_list as $conVal) { ?>
                                 <option value="<?php echo $conVal['id'];?>"><?php echo !empty($conVal['country_name'])?ucwords($conVal['country_name']):'No record found';?></option>
                           <?php } ?>
                       </select>
                     </div>
                  <?php } ?>
                  <div class="form-group field_wrapper">
                     <label for="">State Name <span class="text-danger">*</span></label>
                    <div class="d-flex" style="display: flex; align-items: center;">
                        <input type="text" class="form-control" name="state_name[]" placeholder="State Name" autocomplete="off" style="width: 80%" required="required">
                     <a href="javascript:void(0);" class="add_button" title="Add field" ><img src="<?php echo VIEW_IMAGE_URL;?>add-icon.png"/ style="width:60px"></a>
                    </div>
                  </div>
                  <div class="form-group">
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
