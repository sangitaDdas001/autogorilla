<?php $userId_segment = $this->uri->segment(3); $userName = fetchUserName(base64_decode($userId_segment)); ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
         Company permission for <?php echo ucwords($userName[0]['name']) ;?>
        </h1>
      
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url('userManagement/user-list'); ?>">User List</li></a>
        <li class="active">Company List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <?php $successMsg = $this->session->userdata('user_success'); 
                  if(!empty($successMsg)) { ?>
                    <div class="alert alert-success alert-dismissible">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Success! </strong> <?php echo $successMsg; ?>
                    </div>
              <?php } ?>

              <?php $errorMsg = $this->session->userdata('user_error'); 
                if(!empty($errorMsg)) { ?>
                      <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Fail! </strong> <?php echo $errorMsg; ?>
                      </div>
              <?php } 
              if(!empty($exist_company_list)){
                  $company_arr = [];
                  foreach ($exist_company_list as $key => $company_value) {
                      array_push($company_arr, $company_value['company_id']);
                  } } ?>
              <?php echo form_open_multipart('userManagement/companyPermission/'.$userId_segment,array('role'=>'form')); ?>
                <div class="box-body box box-default">
                  <div class="mb-3">
                  <a href="<?php echo base_url('userManagement/activeUserList');?>"><button type="button" name="submit" class="text-capitalize btn-warning"  style="border-radius:5px;border: none; padding: 3px 12px;">back</button></a> 
                    <!-- <a href="#"><button type="button" name="submit" class="btn btn-warning" onclick="history.go(-3);">Back to user</button></a> -->
                  </div>
                  <div class="box-header">
                    <div class="row-sp align-items-center">
                      <div class="col-lg-3">
                        <h3 class="mt-25px mb-0" style="text-wrap:nowrap; font-size:23px">Assigned Comapany List</h3>
                      </div>
                      <div class="col-lg-3">
                        <div class="form-group-search text-center"> 
                          <label for="">Search by Company Name/ Sr No.</label>
                          <input type="text" name="" id="search_company" class="form-control"  placeholder="Search.." data-type="search">
                        </div> 
                      </div>
                      <div class="col-lg-6">
                        <div class="mt-25px d-flex align-items-center justify-content-end">
                           <button type="button" name="submit" data-toggle="modal" class="btn btn-warning" style="border-radius: 10px;margin-right: 1%;" data-target="#companyAssign_csv_modal" data-userId = <?php echo $userId_segment; ?>>Upload Assign File</button>
                            <button type="button" name="submit" class="btn btn-success download_company_list" style="border-radius: 10px;margin-right: 1%;"><i class="fa fa-download" aria-hidden="true" style="margin-right:5px"></i>Download Sample</button>

                            <button type="button" name="submit" class="btn btn-primary select_all"style="margin-right: 1%;border-radius: 10px;">Allow All Companies</button>

                            <div class="submit-btn" style="display:inline-flex; flex-direction: row;">
                            <button type="submit" name="submit" class="btn btn-primary" style="margin-right: 1%; float: right;border-radius: 10px;min-height: 35px;height: 100%;">Submit</button>
                            <a href="javascript:;" style="font-size: 19pt;" id="scrollToBottom">&#x25BC;</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- <h3>Comapany List
                      
                    </h3> -->
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <div class="row">
                      <input type="hidden" name="csrf" value="<?php echo $this->security->get_csrf_token_name(); ?>">
                      <input type="hidden" name="admin_user_id" id="admin_userId" value="<?php echo base64_decode($userId_segment); ?>">
                      
                      <div class="form-group col-md-5"> 
                        <label for="">Search start sl no.</label>
                          <input type="text" id="min" name="min" class="form-control"  placeholder="Search.." data-type="search">
                      </div> 
                      <div class="form-group col-md-5"> 
                        <label for="">Search end sl no.</label>
                          <input type="text" id="max" name="max" class="form-control"  placeholder="Search.." data-type="search">
                      </div> 
                      <div class="form-group col-md-2"> 

                        <button type="button" name="submit" id="clear-filter" class="btn btn-primary mt-25px" >Reset</button>
                      </div> 
                    </div>
                    <table class="table table-bordered table-hover" id="company_tbl">
                      <thead>
                       <tr>
                          <th><input type="checkbox" class="checkAll">  All Select</th>
                          <th>Sl No.</th>
                          <th>Company Name</th>
                          <th>Assigned User</th>
                          <th>Company Id</th>
                        </tr>
                        </thead>
                        <tbody >
                        <?php if(!empty($company_list)) { 
                           
                              foreach ($company_list as $key => $cvalue) { 
                                if(!empty($company_arr)){ ?>
                                  <tr>
                                    <td><input type="checkbox" name="company_id[]" value ="<?php echo $cvalue['id']; ?>" class="company_ids" <?php if(in_array($cvalue['id'], $company_arr)) { echo 'checked'; } ?> ></td>
                                    <td><?php echo $key+1; ?></td>
                                    <?php if(!empty($cvalue['state_name'])) { ?>
                                      <td><?php echo $cvalue['company_name'] ;?> (<?php echo $cvalue['state_name']; ?>)</td>
                                    <?php } else { ?>
                                      <td><?php echo $cvalue['company_name'];?></td>
                                    <?php } ?>
                                    <td><?php echo ucwords($cvalue['assigned_users']); ?></td>
                                    <td><?php echo $cvalue['id'];?></td>
                                  </tr>
                                <?php } else { ?>
                                <tr>
                                  <td><input type="checkbox" name="company_id[]" value ="<?php echo $cvalue['id']; ?>" class="company_ids"></td>
                                  <td><?php echo $key+1; ?></td>
                                  <?php if(!empty($cvalue['state_name'])) { ?>
                                     <td><?php echo $cvalue['company_name'] ;?> (<?php echo $cvalue['state_name']; ?>)</td>
                                   <?php } else { ?>
                                    <td><?php echo $cvalue['company_name'];?></td>
                                    <?php } ?>
                                   <td><?php echo $cvalue['assigned_users']; ?></td>
                                  <td><?php echo $cvalue['id'];?></td>
                                </tr>
                              <?php  }
                             } 
                           } ?>
                      </tbody>
                    </table>
                    <div class="box-footer">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        <button type="button" name="submit" class="btn btn-primary select_all">Allow All companies</button>
                        <a href="<?php echo base_url('userManagement/activeUserList');?>"><button type="button" name="submit" class="btn btn-warning">Back to user</button></a>
                    </div>
                  </div>
                  <div align = "right">
                      <a href="javascript:;" style="font-size: 19pt;" id="scrollToTop">&#x25B2;</a>
                  </div>
                  <!-- /.box-body -->
                </div>
              <?php echo form_close(); ?>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="<?php echo base_url('scripts/user.js'); ?>"></script>

  <script type = "text/javascript">
    var localurl = "<?php echo base_url(); ?>"
    $(function () {
        $('#scrollToBottom').bind("click", function () {
            $('html, body').animate({ scrollTop: $(document).height() }, 1000);
            return false;
        });
        $('#scrollToTop').bind("click", function () {
            $('html, body').animate({ scrollTop: 0 }, 1000);
            return false;
        });
    });

    $('.download_company_list').click(function(){
        window.location.href = localurl+"UserManagement/downloadAssignList";
    });

    $('')
  </script>

