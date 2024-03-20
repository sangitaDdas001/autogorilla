<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          Sitemap for product section
        </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#"> Sitemap for product section</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"> Products site map</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php $successMsg = $this->session->userdata('product_success'); 
                  if(!empty($successMsg)) { ?>
                    <div class="alert alert-success alert-dismissible">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Success! </strong> <?php echo $successMsg; ?>
                    </div>
              <?php } ?>

              <?php $errorMsg = $this->session->userdata('product_error'); 
                if(!empty($errorMsg)) { ?>
                    <div class="alert alert-danger alert-dismissible">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Fail! </strong> <?php echo $errorMsg; ?>
                    </div>
              <?php } ?>
              <button type="button" name="submit" class="btn btn-warning" onclick="downloadProductSiteMapSitemap();" style="margin-right: 6px; margin-top:6px">Generate Map</button> 
            </div>
            
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
<input type="hidden" name="base_url" id="localurl" value="<?php echo base_url(); ?>">
<script type="text/javascript">
let local_url         =  $('#localurl').val();
  function downloadProductSiteMapSitemap(){
    window.location.href = local_url+"product/generateProductSiteMapSitemap";
  }
 
</script>