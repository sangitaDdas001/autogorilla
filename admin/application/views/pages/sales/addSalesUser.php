<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Sales User Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Saleas User Information</li>
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
                  <h3 class="box-title">Sales User Registration</h3>
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
               <?php echo form_open_multipart('salesManagement/add-sales-user',array('role'=>'form')); ?>
               <div class="box-body">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Manager<span class="text-danger">*</span></label>
                        <select name="manager_id" class="form-control" id="manager_id" >
                           <option value="">--Select Sales User---</option>
                           <?php if(empty($get_all_sales_user)){ ?>
                              <option value="1">Admin</option>
                           <?php } else { ?>
                              <option value="1">Admin</option>
                              <?php foreach ($get_all_sales_user as $key => $sales_user) { ?>
                                 <option value="<?php echo $sales_user['id'] ?>"><?php echo ucwords($sales_user['name']).' ('.$sales_user['department_name'].')'; ?></option>
                           <?php } } ?>
                        </select>
                     </div>
                  </div>
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
                        <select name="department_id" class="form-control" id="department_id">
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

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Designation <span class="text-danger">*</span></label>
                        <select name="post_id" class="form-control" id="post">
                           <option value="">-- Select Designation --</option>
                             
                        </select>
                     </div>
                  </div>

                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="">Sales Upload image </label>
                        <input type="file" name="image" id="file_img" class="form-control">
                     </div>
                  </div>

                  <div class="col-md-2">
                     <div class="form-group">
                        <label for="">Image Preview</label>
                        <img src="<?php echo NO_IMAGE_URL.'no-image_1.png'; ?>" style="width: 40%;height: 40%;" id="imgPreview">
                     </div>
                  </div>

                  <div class="col-md-12">
                     <div class="col-md-4" style="margin-left: -14px;">
                        <div class="form-group">
                           <label for="">State <span class="text-danger">*</span></label>
                           <select name="state_id[]" class="form-control select3 state_id" id="states" multiple="multiple" required>
                              <option value="">--Select State--</option>
                                <?php if(!empty($state_list)) { 
                                 foreach ($state_list as $key => $state) { ?>
                                    <option value="<?php echo $state['id']; ?>"><?php echo $state['state_name']; ?></option>
                                 <?php } }
                                else { ?>
                                    <option value="">No record found</option>
                                <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="">City</label>
                           <select name="city_id[]" class="form-control select4" id="cities" multiple="multiple">
                               
                           </select>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="">Area/Zip Code </label>
                           <select name="area_id[]" class="form-control select5" id="area_id" multiple="multiple">
                              <option value="">--Select Area/Zip Code --</option>
                                
                           </select>
                        </div>
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
   if ($('.select3').length>0) {
         $('.select3').select2({
         placeholder: '-- Select State --',
         allowClear: true
      });
   } 
   if ($('.select4').length>0) {
         $('.select4').select2({
         placeholder: '-- Select City --',
         allowClear: true
      });
   }
   if ($('.select5').length>0) {
         $('.select5').select2({
         placeholder: '-- Select Area/Zip Code --',
         allowClear: true
      });
   }

   $('#states').on('change', function() {
      var states = $(this).val();
      $('#cities').empty();
      $('#area_id').empty();
         states.forEach(function(state) {
         // Fetch the cities for each state using AJAX
         $.ajax({
            url: localurl+"SalesManagement/getCityList",
            type:'post',
            data: {
              state: state
            },
            success: function(res) {
               var json = JSON.parse(res);
               var options = '';
               for (var i = 0; i < json.data.length; i++) {
                  options += '<option value="' + json.data[i].cityId + '">' + json.data[i].city_name + '</option>';
               }
               $('#cities').append(options);
            }
           
         });
      });
   });


   $('#cities').on('change', function() {
      var cities = $(this).val();
      $('#area_id').empty();
         cities.forEach(function(cities) {
         // Fetch the cities for each state using AJAX
         $.ajax({
            url: localurl+"SalesManagement/getAreaList",
            type:'post',
            data: {
              cities: cities
            },
            success: function(res) {
               var json = JSON.parse(res);
               var options = '';
               for (var i = 0; i < json.data.length; i++) {
                  options += '<option value="' + json.data[i].areaId + '">' + json.data[i].area_name + '</option>';
               }
               $('#area_id').append(options);
            }
           
         });
      });
   });

   $('#department_id').on('change', function() {
      var departments = $('#department_id').val();
      if(departments == 9){
         $('#post').empty();
            
            // Fetch the cities for each state using AJAX
            $.ajax({
               url: localurl+"SalesManagement/getPostList",
               type:'post',
               data: {
                 'department_id': departments
               },
               success: function(res) {
                  var json = JSON.parse(res);
                  var options = '';
                  for (var i = 0; i < json.data.length; i++) {
                     options += '<option value="' + json.data[i].id + '">' + json.data[i].post_name + '</option>';
                  }
                  $('#post').append(options);
               }
              
            });
         
      }
      
   });
   $(document).ready(()=>{
      $('#file_img').change(function(){
        const file = this.files[0];
        console.log(file);
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('#imgPreview').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });
    });
  </script>