<?php 
  $aboutId = $this->uri->segment(3);
  if(empty($aboutId)) {
    redirect('viewAboutUs');
  }
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        About Us
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="<?php echo base_url('aboutUs'); ?>">About Us</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            
            <div class="col-md-12">
              <div class="form-group">
                <label>Overview Heading</label>
                <input type="text" class="form-control" value="<?php echo !empty($fetch_data['content_for'])?$fetch_data['content_for']:''?>" readonly>
              </div>
              <div class="form-group">
                <label>Overview Content</label>
                  <textarea class="form-control" readonly><?php echo !empty($fetch_data['content'])?$fetch_data['content']:'';?></textarea>
              </div>
            
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <div class="box-footer">
          <div class="row">
            <div class="col-md-12">
              <center>
                <button type="button" class="btn btn-warning" onclick="history.back();">Back</button>
              </center>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->