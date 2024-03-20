<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
        All Category Site map
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">All Category Site map</li>
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
                  <h3 class="box-title">Parent Category Site map</h3>
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
                 
                  <div class="box-body">
                  <button type="button" name="submit" class="btn btn-primary" onclick="categorySitemap();"  style="margin-right: 6px; margin-top:6px">Generate Site Map</button> 
                  </div>
               </div>
            </div>
         </div>
      </div>

     <!--  SUB CATEGORY SITE MAP -->

      <div class="row">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Sub Category Site map</h3>
                  <?php $errorMsg = $this->session->userdata('sub_cat_error'); 
                  if(!empty($errorMsg)) { ?>
                     <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Fail! </strong> <?php echo $errorMsg; ?>
                     </div>
                  <?php } ?>
                  <?php $successMsg = $this->session->userdata('sub_cat_success'); 
                  if(!empty($successMsg)) { ?>
                     <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success! </strong> <?php echo $successMsg; ?>
                     </div>
                  <?php } ?>
                 
                  <div class="box-body">
                     <button type="button" name="submit" class="btn btn-primary" onclick="generateSubcategorySitemap();" style="margin-right: 6px; margin-top:6px">Generate Site Map</button> 
                  </div>
               </div>
            </div>
         </div>
      </div>
     <!--  END -->

     <div class="row">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Micro Category Site map</h3>
                  <?php $errorMsg = $this->session->userdata('micro_cat_error'); 
                  if(!empty($errorMsg)) { ?>
                     <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Fail! </strong> <?php echo $errorMsg; ?>
                     </div>
                  <?php } ?>
                  <?php $successMsg = $this->session->userdata('micro_cat_success'); 
                  if(!empty($successMsg)) { ?>
                     <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success! </strong> <?php echo $successMsg; ?>
                     </div>
                  <?php } ?>
                 
                  <div class="box-body">
                  <button type="button" name="submit" class="btn btn-primary" onclick="microcategorySitemapGenerate();"  style="margin-right: 6px; margin-top:6px">Generate Site Map</button> 
                  </div>
               </div>
            </div>
         </div>
      </div>

   </section>

  

</div>
<input type="hidden" name="base_url" id="localurl" value="<?php echo base_url(); ?>">
<input type="hidden" name="parentcat_level" id="parentcat_level" value="1">
<input type="hidden" name="subcat_level" id="subcat_level" value="2">
<input type="hidden" name="microcat_level" id="microcat_level" value="3">

<script type="text/javascript">
   /** PARENT CATEGORY ***/

      function categorySitemap(){
         let base_url   =  $('#localurl').val();
         window.location.href = base_url+"category/generateParentSiteMap";
      }

   /*** END OF THE SECTION ****/

   /*** SUB CATEGORY ***/
      function generateSubcategorySitemap(){
         let base_url   =  $('#localurl').val();
         window.location.href = base_url+"category/generate_SubMenuSiteMap";
      }

   /*** END ***/

   /***  MICRO CATEGORY ***/ 
      function microcategorySitemapGenerate(){
         let base_url   =  $('#localurl').val();
         window.location.href = base_url+"category/generate_MicroMenuSiteMap";
      }
   /**** END ****/
</script>