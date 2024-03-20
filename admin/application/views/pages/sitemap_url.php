<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          Sitemap for product section
        </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">All Url Sitemap Url Info</a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Site Map Url</h3>
            </div>
            <div class="box-body">
            <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Url Name</th>
                    <th>Created Date</th>
                  </tr>
                </thead>
                <?php if(!empty($xmlFiles)){ 
                    foreach($xmlFiles as $key=>$urlinfo){ ?>
                        <tr>
                            <td><a href="<?php echo 'http://dev.autogorilla.com/'.$urlinfo['file'];?>"><?php echo $_SERVER['REQUEST_SCHEME'].'//'.$_SERVER['HTTP_HOST'].'/'.$urlinfo['file'];?></a></td>
                            <td><?php echo $urlinfo['creation_time'];?></td>
                        </tr>
                <?php } } ?>
              </table>
            </div>
          </div>
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