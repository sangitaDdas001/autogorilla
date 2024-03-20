<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/jstree.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sales User Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="<?php echo base_url('supplier'); ?>">Sales User List</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Sales User List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php $successMsg = $this->session->userdata('user_success'); 
                if(!empty($successMsg)) { ?>
                  <div class="alert alert-success alert-dismissible">
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     <strong>Success! </strong> <?php echo $successMsg; ?>
                  </div>
               <?php } ?>
            <div id="managerial_tree_view">
               <div class="tree_clickable fa tree-down-arrow">
                  <span>
                  Tree View
                  </span>
               </div>
               <div id="tree_div">
                  <div id="tree" class="jstree jstree-1 jstree-default" role="tree" aria-multiselectable="true" tabindex="0" aria-activedescendant="6" aria-busy="false">
                    
                  </div>
               </div> 
               <div id="response_div"></div>
            </div> 
              <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $this->security->get_csrf_token_name(); ?>">
              <input type="hidden" name="csrf_token_value" id="csrf_token_value" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div id="tree"></div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
