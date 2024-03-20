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
                  <h3 class="box-title">Add Micro category details</h3>
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
               <?php echo form_open_multipart('category/addMicroCategory',array('role'=>'form')); ?>
               <div class="box-body">
                  <input type="hidden" class="type_check" value="add">
              
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Parent Category Name <span class="text-danger">*</span></label>
                           <select name="parent_cat_id" class="form-control parent_id" required id="parent_cat_id">
                              <option value="">--Select Parent category--</option>
                              <?php foreach ($fetch_parent_cat as $pvalue) { ?>
                                 <option value="<?php echo $pvalue['id']; ?>"><?php echo ucwords($pvalue['category_name']); ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Sub Category Name <span class="text-danger">*</span></label>
                           <select name="sub_cat_id"  class="form-control" id="subcat-name">
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Related Parent Category Name </label>
                           <select name="related_parent_id" class="form-control" id="related_p_category" >
                           </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Related Sub-parent Category Name </label>
                           <select name="related_category[]" multiple class="form-control minorSelect2" id="related_sub_category" >
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Minor Category Name </label>
                           <input type="text" class="form-control category_name" name="category_name" placeholder="Minor category Name" autocomplete="off" >
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Url Slug<span class="text-danger">*</span></label>
                           <input type="text" class="form-control url_slug" name="url_slug" placeholder="Minor category Name" autocomplete="off" required>
                        </div>
                     </div>
                  </div> 
                  
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Image</label>
                           <input type="file" class="form-control" name="files" autocomplete="off" required>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label for="">Catetgory Content</label>
                           <textarea name="category_content"  id="category_content" class="form-control category_content" placeholder="Category Description"></textarea>
                        </div>
                     </div>
                     <div id="character_count">
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label for="">Footer Content</label>
                           <textarea name="footer_content" class="form-control"  placeholder="Footer Content" id="footer_content"></textarea>
                        </div>
                     </div>
                  </div>

                  <h3 class="box-title">SEO Details</h3>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">Meta Title</label>
                           <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" autocomplete="off" >
                        </div>
                     </div>
                     <div class="col-md-6">
                       <div class="form-group">
                           <label for="">Canonical Url</label>
                           <input type="text" class="form-control" name="canonical_url" placeholder="Canonical Url" autocomplete="off">
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="form-group">
                           <label for="">Meta Description</label>
                           <textarea name="meta_description" class="form-control" placeholder="Meta Description" id="meta_description"></textarea>
                        </div>
                     </div>

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

                     <div class="col-md-12" id="oginput">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="">Meta og property</label>
                              <input type="text" class="form-control" name="social_meta_og_property[]" placeholder="Meta og property" autocomplete="off" >
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
                  <!-- /.box-body -->
                  <div class="box-footer">
                     <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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
               '<span><i class="fa fa-minus-circle" style="font-size:19px;color:#ff5200"></i></span>'+
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
    </script>