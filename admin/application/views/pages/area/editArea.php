<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Edit Banner Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Banner List</li>
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
                  <h3 class="box-title">Edit Banner Information</h3>
               </div>
               <?php $errorMsg = $this->session->userdata('area_error'); 
                  if(!empty($errorMsg)) { ?>
               <div class="alert alert-danger alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Fail! </strong> <?php echo $errorMsg; ?>
               </div>
               <?php } ?>
               <?php $successMsg = $this->session->userdata('area_success'); 
                  if(!empty($successMsg)) { ?>
               <div class="alert alert-success alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Success! </strong> <?php echo $successMsg; ?>
               </div>
               <?php } ?>
               <?php echo form_open_multipart('area/updateArea',array('role'=>'form')); ?>
               <input type="hidden" name="id" id="id" value="<?php echo $area_id; ?>">
               <div class="box-body">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">City Name</label>
                        <select name="city_id" class="form-control">
                           <option>--Select City--</option>
                           <?php foreach ($city_list as $key => $c_value): ?>
                              <option <?php if($c_value['id']==$fetch_data['city_id']){ echo "selected" ;} ?> value="<?php echo $c_value['id']; ?>"><?php echo $c_value['city_name']; ?></option>
                           <?php endforeach ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Area name</label>
                        <input type="text" class="form-control" name="area_name" value="<?php echo $fetch_data['area_name']; ?>" Placeholder="Area name" autocomplete="off">
                     </div>
                  </div>
               <!-- /.box-body -->
               <div class="box-footer">
                  <div class ="col-md-12">
                     <center><button type="submit" name="submit" class="btn btn-primary">Submit</button>
                     <button type="button" name="submit" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('banner/viewBannerList'); ?>'">Back</button></center>
                  </div>
               </div>
               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </section>
</div>