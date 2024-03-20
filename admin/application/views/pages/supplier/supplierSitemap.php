<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
       All Suppliers Site map
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">All Suppliers Site map</li>
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
                  <h3 class="box-title">Suppliers Site Map</h3>
                  <?php $errorMsg = $this->session->userdata('supplier_error'); 
                  if(!empty($errorMsg)) { ?>
                     <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Fail! </strong> <?php echo $errorMsg; ?>
                     </div>
                  <?php } ?>
                  <?php $successMsg = $this->session->userdata('supplier_success'); 
                  if(!empty($successMsg)) { ?>
                     <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success! </strong> <?php echo $successMsg; ?>
                     </div>
                  <?php } ?>

                  <div class="box-body">
                    <button type="button" name="submit" class="btn btn-primary" onclick="generateSupplierSitemap();" style="margin-right: 6px; margin-top:6px">Generate Site Map</button> 
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<input type="hidden" name="base_url" id="localurl" value="<?php echo base_url(); ?>">
<script type="text/javascript">
   let base_url         =  $('#localurl').val();
   function generateSupplierSitemap(){
      let base_url         =  $('#localurl').val();
      window.location.href = base_url+"supplier/generateCompanySiteMap";
   }
</script>