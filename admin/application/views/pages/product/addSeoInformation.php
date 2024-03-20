<?php 
   $seo_meta_info       = seo_meta_information($fetch_data['product_url_slug'],$fetch_data['product_url_slug'].'/'.base64_encode($fetch_data['product_id'])); 
   $seo_multi_meta_info = seo_multi_meta_information($fetch_data['product_url_slug'],$fetch_data['product_url_slug'].'/'.base64_encode($fetch_data['product_id'])); 
   $seo_social_og_info  = seo_social_ogInfo($fetch_data['product_url_slug'],$fetch_data['product_url_slug'].'/'.base64_encode($fetch_data['product_id'])); 

   //call helper
?>

<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Add Seo details for product <?php echo ucwords($fetch_data['product_name']); ?>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Add Seo Details</li>
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
                  <h3 class="box-title">Add Seo details</h3>
               </div>
               <?php $errorMsg = $this->session->userdata('seo_error'); 
               if(!empty($errorMsg)) { ?>
                  <div class="alert alert-danger alert-dismissible">
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     <strong>Fail! </strong> <?php echo $errorMsg; ?>
                  </div>
               <?php } ?>
               <?php $successMsg = $this->session->userdata('seo_success'); 
               if(!empty($successMsg)) { ?>
                  <div class="alert alert-success alert-dismissible">
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     <strong>Success! </strong> <?php echo $successMsg; ?>
                  </div>
               <?php } ?>
               
               <?php echo form_open_multipart('product/addSeoinfo',array('role'=>'form')); ?>
               <input type="hidden" name="page_name" value="<?php echo $fetch_data['product_url_slug'];  ?>">
               <input type="hidden" name="page_url" value="<?php echo $fetch_data['product_url_slug'].'/'.base64_encode($fetch_data['product_id']);  ?>">
              
               <input type="hidden" name="product_id" value="<?php echo base64_encode($fetch_data['product_id']);  ?>">
               <div class="box-body">
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Meta Title</label>
                           <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" autocomplete="off"  value="<?php echo $seo_meta_info[0]['meta_title']; ?>">
                        </div>
                     </div>
                     <div class="col-md-6">
                       <div class="form-group">
                           <label for="">Canonical Url</label>
                           <input type="text" class="form-control" name="canonical_url" placeholder="Canonical Url" autocomplete="off" required="" value="<?php echo $seo_meta_info[0]['canonical_url']; ?>">
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="form-group">
                           <label for="">Meta Description</label>
                           <textarea name="meta_description" class="form-control" placeholder="Meta Description" id="meta_description"><?php echo $seo_meta_info[0]['meta_description']; ?></textarea>
                        </div>
                     </div>
                  </div>
                  <?php if(!empty($seo_multi_meta_info)) { ?>
                     <div class="col-md-12" id="newinput">
                        <?php foreach ($seo_multi_meta_info as $key => $m_value) { ?>
                           <div id ="row_m_<?php echo $m_value['id']; ?>">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="">Meta name</label>
                                    <input type="text" class="form-control" name="meta_name[]" placeholder="Meta name" autocomplete="off" value="<?php echo $m_value['meta_name']; ?>">
                                 </div>
                              </div>
                              <div class="col-md-7">
                                 <div class="form-group">
                                    <label for="">Meta content</label>
                                    <textarea name="meta_content[]" class="form-control" placeholder="Meta content" id="meta_description"><?php echo $m_value['meta_content']; ?></textarea>
                                 </div>
                              </div>
                              <?php if($key !==0 ){ ?>
                                 <div class="col-md-1">
                                    <div class="form-group editDeleteRow" data-id="<?php echo $m_value['id']; ?>" style="margin-top: 25px; margin-left: 48px;">
                                       <span><i class="fa fa-minus-circle" style="font-size:19px;color:#ff5200"></i></span>
                                    </div>
                                 </div>
                           
                              <?php } if($key == 0){ ?>
                                 <div class="col-md-1">
                                    <div class="form-group rowAdder" style="margin-top: 25px; margin-left: 48px;" >
                                       <span><i class="fa fa-plus-circle" style="font-size:19px;"></i></span>
                                    </div>
                                 </div>
                              <?php } ?>   
                           </div>
                        <?php } ?>
                        
                     </div>
                  <?php } else { ?>
                     <div class="col-md-12" id="newinput">
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="">Meta name</label>
                           <input type="text" class="form-control" name="meta_name[]" placeholder="Meta name" autocomplete="off">
                        </div>
                     </div>
                     <div class="col-md-7">
                        <div class="form-group">
                           <label for="">Meta content</label>
                           <textarea name="meta_content[]" class="form-control" placeholder="Meta content" id="meta_description"></textarea>
                        </div>
                     </div>
                     <div class="col-md-1">
                        <div class="form-group rowAdder" style="margin-top: 25px; margin-left: 48px;" >
                           <span><i class="fa fa-plus-circle" style="font-size:19px;"></i></span>
                        </div>
                     </div>
                     </div> 
                  <?php } ?>

                  <?php if(!empty($seo_social_og_info)){ ?>
                     <div class="col-md-12" id="oginput">
                        <?php foreach ($seo_social_og_info as $key => $s_value) { ?>
                           <div id="row_og_<?php echo $s_value['id']; ?>">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="">Meta og property</label>
                                    <input type="text" class="form-control" name="social_meta_og_property[]" placeholder="Meta og property" autocomplete="off" value="<?php echo $s_value['social_meta_og_property']; ?>">
                                 </div>
                              </div>
                              <div class="col-md-7">
                                 <div class="form-group">
                                    <label for="">Meta og-property content</label>
                                    <textarea name="social_meta_og_content[]" class="form-control" placeholder="Meta og-property content" id="social_meta_og_content"><?php echo $s_value['social_meta_og_content']; ?></textarea>
                                 </div>
                              </div>
                              <?php if($key !==0 ){ ?>
                                 <div class="col-md-1">
                                    <div class="form-group og_deleterow" style="margin-top: 25px; margin-left: 48px;" data-socialid="<?php echo $s_value['id']; ?>">
                                       <span><i class="fa fa-minus-circle" style="font-size:19px; color:#c72f17"></i></span>
                                    </div>
                                 </div>
                              <?php } if($key ==0) { ?>  
                                 <div class="col-md-1">
                                    <div class="form-group new_row_add" style="margin-top: 25px; margin-left: 48px;" >
                                       <span><i class="fa fa-plus-circle" style="font-size:20px;color:#2665af"></i></span>
                                    </div>
                                 </div> 
                              <?php } ?>   
                           </div>
                        <?php } ?>
                        
                     </div>
                  <?php } else { ?>
                     <div class="col-md-12" id="oginput">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="">Meta og property</label>
                              <input type="text" class="form-control" name="social_meta_og_property[]" placeholder="Meta og property" autocomplete="off">
                           </div>
                        </div>
                        <div class="col-md-7">
                           <div class="form-group">
                              <label for="">Meta og-property content</label>
                              <textarea name="social_meta_og_content[]" class="form-control" placeholder="Meta og-property content" id="social_meta_og_content"></textarea>
                           </div>
                        </div>
                        <div class="col-md-1">
                           <div class="form-group new_row_add" style="margin-top: 25px; margin-left: 48px;" >
                              <span><i class="fa fa-plus-circle" style="font-size:19px;"></i></span>
                           </div>
                        </div>
                     </div>
                  <?php } ?>

                  <!-- /.box-body -->
                  <div class="box-footer">
                     <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                     <button type="button" class="btn btn-warning" onclick="history.back();">Back</button>
                  </div>
                  <?php echo form_close(); ?>
               </div>
            </div>
         </div>
      </section>
   </div>

   <script type="text/javascript">
         $(".rowAdder").click(function () {
            newRowAdd =
               '<div id ="row">'+
               '<div class="col-md-4">'+
               '<div class="form-group">' +
               '<input type="text" class="form-control" name="meta_name[]" placeholder="Meta name" autocomplete="off" required="">' +
               '</div></div>' +
               '<div class="col-md-7"><div class="form-group">' +
               '<textarea name="meta_content[]" class="form-control" placeholder="Meta content" id="meta_description"></textarea></div></div>' +
               '<div class="col-md-1">'+
               '<div class="form-group" style="margin-top: 25px; margin-left: 48px;" id="DeleteRow">'+
               '<span><i class="fa fa-minus-circle" style="font-size:19px;color:#ff5200"></i></span>'+
               '</div>'+
               '</div>'+
               '</div>';
 
            $('#newinput').append(newRowAdd);
         });

            $(".new_row_add").click(function () {
               newRowAdd =
                  '<div id ="row_og">'+
                  '<div class="col-md-4">'+
                  '<div class="form-group">' +
                  '<input type="text" class="form-control" name="social_meta_og_property[]" placeholder="Meta og property" autocomplete="off" required="">' +
                  '</div></div>' +
                  '<div class="col-md-7"><div class="form-group">' +
                  '<textarea name="social_meta_og_content[]" class="form-control" placeholder="Meta og-property content" id="social_meta_og_content"></textarea></div></div>' +
                  '<div class="col-md-1">'+
                  '<div class="form-group" style="margin-top: 25px; margin-left: 48px;" id="DeleteogRow">'+
                  '<span><i class="fa fa-minus-circle" style="font-size:19px;color:#c72f17"></i></span>'+
                  '</div>'+
                  '</div>'+
                  '</div>';
    
               $('#oginput').append(newRowAdd);
           });

         $("body").on("click", "#DeleteRow", function () {
            $(this).parents("#row").remove();
         });

         $("body").on("click", "#DeleteogRow", function () {
            $(this).parents("#row_og").remove();
         });

         $("body").on('click','.editDeleteRow',function(event){
            var id_val = $(this).attr('data-id');
            $("#row_m_"+id_val).remove()
         });

         $("body").on('click','.og_deleterow',function(event){
            var id_val = $(this).attr('data-socialid');
            $("#row_og_"+id_val).remove()
         });

    </script>