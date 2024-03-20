<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>

<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Zip Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Zip Information</li>
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
                  <h3 class="box-title">Add City details</h3>
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
               <?php echo form_open_multipart('zipupload/uploadZipfile',array('role'=>'form')); ?>
               <div class="box-body">
                  <div class="col-md-12">
                     <input type="file" name="imagezip" class="form-control">
                  </div>
                  <!-- /.box-body -->
                  <div class="col-md-12">
                     <div class="box-footer">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                     </div>
                  </div>   
                  <?php echo form_close(); ?>
               </div>
            </div>
         </div>
      

      <table id="ziplist">
         <thead>
            <tr>
              <th>Zip</th>
              <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php if(!empty($zipFiles)){ 
               foreach ($zipFiles as $key => $value) { ?>
                  <tr>
                     <td><?php echo $value; ?></td>
                     <td>
                           <?php echo form_open_multipart('zipupload/extract_zip',array('role'=>'form')); ?>
                           <button type="submit" name="submit" class="btn btn-warning">Extract</button>
                        </form>
                     </td>
                  </tr>
               <?php } ?>
               
            <?php } else { ?>
               <tr colspan="3">
                 <td colspan="3"> no record found </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </section>
</div>

