<?php 
   $seo_meta_info       = seo_meta_information('micro category',$fetch_data['url_slug']); 
   $seo_multi_meta_info = seo_multi_meta_information('micro category',$fetch_data['url_slug']); 
   $seo_social_og_info  = seo_social_ogInfo('micro category',$fetch_data['url_slug']); 

   //call helper
?>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Micro Category Information
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('dashboard') ?>"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Micro Category Information</li>
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
                  <h3 class="box-title">Edit Micro category details</h3>
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
               <?php echo form_open_multipart('category/updateMicroCategory',array('role'=>'form')); ?>
               <div class="box-body">
                  <input type="hidden" name="cat_id" value="<?php echo $cat_id ?>">
                  <div class="row">
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Parent Category Name <span class="text-danger">*</span></label>
                           <select name="parent_cat_id" class="form-control parent_id" required id="parent_cat_id">
                              <option value="">--Select Parent category--</option>
                             <?php foreach($master_category as $value){ ?>
                                 <option <?php if($value['id'] == $fetch_data['parent_cat_id']) echo "selected"; ?>  value="<?php echo $value['id']; ?>"><?php echo ucwords($value['category_name']); ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Sub Category Name <span class="text-danger">*</span></label>
                           <select name="sub_cat_id"  class="form-control" id="subcat-name" required>
                              <option value="">--Select Sub Category --</option>
                             <?php foreach($subCat_category as $value){ ?>
                                 <option <?php if($value['cat_id'] == $fetch_data['sub_cat_id']) echo "selected"; ?>  value="<?php echo $value['cat_id']; ?>"><?php echo ucwords($value['category_name']); ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Related Parent Category Name</label>
                           <select name="related_parent_id" class="form-control" id="related_p_category" >
                              <?php foreach($related_parent_category as $value){ ?>
                                 <option <?php if($value['cat_id'] == $fetch_data['related_parent_id']) echo "selected"; ?>  value="<?php echo $value['cat_id']; ?>"><?php echo ucwords($value['category_name']); ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                      <?php $categoryArr = explode(",", $fetch_data['related_category']); ?>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Related Sub-parent Category Name </label>
                           <select name="related_category[]" multiple class="form-control minorSelect2" id="related_sub_category">
                              <?php foreach($related_sub_parent_category as $value){ ?>
                                 <option <?php if(in_array($value['cat_id'], $categoryArr)) echo "selected"; ?> value="<?php echo $value['cat_id']; ?>"><?php echo ucwords($value['category_name']); ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Micro Category Name <span class="text-danger">*</span></label>
                           <input type="text" class="form-control category_name" name="category_name" placeholder="Micro category Name" autocomplete="off" required value="<?php echo $fetch_data['category_name']; ?>">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Url Slug<span class="text-danger">*</span></label>
                           <input type="text" class="form-control url_slug" name="url_slug" placeholder="Url Slug" autocomplete="off" required value="<?php echo $fetch_data['url_slug']; ?>" readonly>
                           <input type="hidden" name="url_slug_prev" class="form-control" value="<?php echo $fetch_data['url_slug']; ?>" >
                        </div>
                     </div>
                  </div> 
                  
                  <div class="col-md-12">
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="">Image</label>
                           <input type="file" class="form-control" name="files" autocomplete="off" >
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="">Image</label>
                           <?php if(!empty($fetch_data['category_image'])){ ?>
                              <img src="<?php echo VIEW_IMAGE_URL.'categories/'.$fetch_data['category_image']; ?>" class='img-thumbnail img-rounded' alt="categories image" style="width:70px;height:70px">
                           <?php } else { ?>
                              <img src="<?php echo VIEW_IMAGE_URL.'noimage.png' ?>" class='img-thumbnail img-rounded'  alt="categories image" style="width:90px;height:70px">
                           <?php } ?>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label for="">Catetgory Content</label>
                           <textarea name="category_content"  id="category_content" class="form-control category_content" placeholder="Category Description"><?php echo $fetch_data['category_content']; ?></textarea>
                        </div>
                     </div>
                     <div id="character_count">
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label for="">Footer Content</label>
                           <textarea name="footer_content" class="form-control"  placeholder="Footer Content" id="footer_content"><?php echo $fetch_data['footer_content']; ?></textarea>
                        </div>
                     </div>
                  </div>

                  <h3 class="box-title">SEO Details</h3>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="">Meta Title</label>
                        <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" autocomplete="off" value="<?php echo !empty($seo_meta_info[0]['meta_title'])?$seo_meta_info[0]['meta_title']:''; ?>">
                     </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Canonical Url</label>
                        <input type="text" class="form-control" name="canonical_url" placeholder="Canonical Url" autocomplete="off" value="<?php echo !empty($seo_meta_info[0]['canonical_url'])?$seo_meta_info[0]['canonical_url']:''; ?>">
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="">Meta Description</label>
                        <textarea name="meta_description" class="form-control" placeholder="Meta Description" id="meta_description"><?php echo !empty($seo_meta_info[0]['meta_description'])?$seo_meta_info[0]['meta_description']:''; ?></textarea>
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
                           <input type="text" class="form-control" name="meta_name[]" placeholder="Meta name" autocomplete="off" >
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