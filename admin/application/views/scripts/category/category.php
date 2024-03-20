<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/category.js" type="text/javascript"></script>
 
<script type="text/javascript">
$(function($) {
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });
 
 	<?php if(!empty($catData)) { ?> 
	    $('#category_details').DataTable({
			// "searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"category/categoryNameDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $catData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
    <?php } ?>

    <?php if(!empty($subCatData)) { ?>
    	$('#subcategory_details').DataTable({
			//"searching": true,
			"processing": true,
			"serverSide": true,
			"ajax" : {
				"url" : localurl+"category/subCategoryDetails_ajax",
				"type" : "POST",
			},
			"columns": <?php echo $subCatData; ?>,
			"language": {
	        	infoEmpty: "No records found",
	    	}
	    });
	<?php } ?>

    <?php if(!empty($microCatData)) { ?>
        $('#microCategory_details').DataTable({
            //"searching": true,
            "processing": true,
            "serverSide": true,
            "ajax" : {
                "url" : localurl+"category/microCategoryDetails_ajax",
                "type" : "POST",
            },
            "columns": <?php echo $microCatData; ?>,
            "language": {
                infoEmpty: "No records found",
            }
        });
    <?php } ?>

	if ($('.select2').length>0) {
		$('.select2').select2({
		  placeholder: '-- Select Related category --',
		  allowClear: true
		});
	}

	if ($('.minorSelect2').length>0) {
		$('.minorSelect2').select2({
		  placeholder: '-- Select Sub Parent category --',
		  allowClear: true
		});
	}


	if ($('.category_content').length>0) {
	   tinymce.init({
            selector: '.category_content',
            height : "230",
            toolbar: "forecolor backcolor",
            color_cols: "1",
            fontsize_formats: "8pt 9pt 10pt 11pt 12pt 14pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt 96pt",
            plugins: 'charmap code lists image paste fullscreen imagetools preview table link',
            toolbar: ['code preview image removeformat link h2 h3 h4 h5 forecolor| bold italic underline fontsizeselect | subscript superscript charmap | alignleft aligncenter alignright outdent indent | bullist numlist  selectall fullscreen',],
            images_upload_url: localurl+'aboutUs/uploadImg',
            file_picker_types: 'image',
            branding: false,
            contextmenu: "link image imagetools table spellchecker",
            quickbars_selection_toolbar: 'bold italic | quicklink h1 h2 h3',
            toolbar_drawer: 'sliding',
            menubar : false,
            relative_urls :false,
            remote_scripts_host:false,
            convert_urls: true,
            setup: function (ed) {
                ed.on('keyup', function (e) { 
                    var count = CountCharacters();
                    document.getElementById("character_count").innerHTML = "Characters: " + count;
                });
            },

           file_picker_callback: function (callback, value, meta) {
            if (meta.filetype == 'image') {
                var input = document.getElementById('my-file');
                input.click();
                input.onchange = function () {
                    var file = input.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        callback(e.target.result, {
                            alt: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };
            }

            if (meta.filetype == 'media') {
                var input = document.getElementById('my-file');
                input.click();
                input.onchange = function () {
                    var file = input.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {

                        callback(e.target.result, {
                            alt: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };
            }
        },

          automatic_uploads: false,
          media_alt_source: false
    	});
	}

	function CountCharacters() {
        var body = tinymce.get("category_content").getBody();
        var content = tinymce.trim(body.innerText || body.textContent);
        return content.length;
    }

    $('.ValidateCharacterLength').click(function(){
	  	var max = 500;
        var count = CountCharacters();
        if (count > max) {
            alert("Maximum " + max + " characters allowed in category content.")
            return false;
        }
	});

    function createUrl(text) {
  // Replace special characters with a hyphen
  text = text.replace(/[^a-zA-Z0-9]+/g, '-');

  // Trim any leading or trailing hyphens
  text = text.replace(/^-+|-+$/g, '');

  // Lowercase
  text = text.toLowerCase();

  if (text === "") {
    return 'n-a';
  }

  return text;
}

    $(".category_name").blur(function(){
        var str = $('.category_name').val();
        var slug = createUrl(str);
        $('.url_slug').val(slug);
    });

	////////// FOR FOOTER CONTENT////////////////

	if ($('#footer_content').length>0) {
	   tinymce.init({
            selector: '#footer_content',
            height : "230",
            toolbar: "forecolor backcolor",
            color_cols: "1",
            fontsize_formats: "8pt 9pt 10pt 11pt 12pt 14pt 18pt 24pt 30pt 36pt 48pt 60pt 72pt 96pt",
            plugins: 'charmap code lists image paste fullscreen imagetools preview table link',
            toolbar: ['code preview image removeformat link h2 h3 h4 h5 forecolor| bold italic underline fontsizeselect | subscript superscript charmap | alignleft aligncenter alignright outdent indent | bullist numlist  selectall fullscreen',],
            images_upload_url: localurl+'aboutUs/uploadImg',
            file_picker_types: 'image',
            branding: false,
            contextmenu: "link image imagetools table spellchecker",
            quickbars_selection_toolbar: 'bold italic | quicklink h1 h2 h3',
            toolbar_drawer: 'sliding',
            menubar : false,
            relative_urls :false,
            remote_scripts_host:false,
            convert_urls: true,

           file_picker_callback: function (callback, value, meta) {
            if (meta.filetype == 'image') {
                var input = document.getElementById('my-file');
                input.click();
                input.onchange = function () {
                    var file = input.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        callback(e.target.result, {
                            alt: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };
            }

            if (meta.filetype == 'media') {
                var input = document.getElementById('my-file');
                input.click();
                input.onchange = function () {
                    var file = input.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {

                        callback(e.target.result, {
                            alt: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };
            }
        },

          automatic_uploads: false,
          media_alt_source: false
    	});
	}

    /******* get Sub Category [Micro Cat Page ] ********/
    /**** FOR ADD MICRO CAT*/
    
    $('.parent_id').on('change', function() {
        let parent_id = this.value;
        if(parent_id !=''){
          $.ajax({
            url : localurl+"category/getsubCategoryById",
            type:'post',
            dataType : "json",
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','parent_id':parent_id},
            beforeSend: function () {
              jQuery('select#subcat-name').find("option:eq(0)").html("Please wait..");
          },
          complete: function () {
                // code
            },
            success: function (json) {
                var options = '';
                 options +='<option value="">--Select Sub Category--</option>';
                for (var i = 0; i < json.length; i++) {
                    options += '<option value="' + json[i].cat_id + '">' + json[i].category_name + '</option>';
                }
                jQuery("select#subcat-name").html(options);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
      }
  });

    var parent_id = $('.parent_cat_id').val();
    var related_cat_id = $('#related_cat_id').val();

        $('.parent_cat_id').change(function(){
            let parent_id = this.value;
            if(parent_id !=''){
              $.ajax({
                url : localurl+"category/getParentCategoryById",
                type:'post',
                dataType : "json",
                data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','parent_id':parent_id},
                beforeSend: function () {
                  jQuery('select#related_cat_id').find("option:eq(0)").html("Please wait..");
                },
                complete: function () {
                    // code
                },
                success: function (json) {
                var options = '';
                options +='<option value="">Related Category Name</option>';
                for (var i = 0; i < json.length; i++) {
                    options += '<option value="' + json[i].cat_id + '">' + json[i].category_name + '</option>';
                }
                jQuery("select#related_cat_id").html(options);

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
             });
            }
        });

        if(related_cat_id == ''){
          $.ajax({
            url : localurl+"category/getParentCategoryById",
            type:'post',
            dataType : "json",
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','parent_id':parent_id},
            beforeSend: function () {
              jQuery('select#related_cat_id').find("option:eq(0)").html("Please wait..");
          },
          complete: function () {
                    // code
                },
                success: function (json) {
                    var options = '';
                    options +='<option value="">Related Category Name</option>';
                    for (var i = 0; i < json.length; i++) {
                        options += '<option value="' + json[i].cat_id + '">' + json[i].category_name + '</option>';
                    }
                    jQuery("select#related_cat_id").html(options);

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
        
    
   /* Get Related parent Cat*/
    var sub_categoryId = $('#subcat-name').val();
    if(sub_categoryId !=''){
        $('#subcat-name').change(function(){
            var parent_id = $('.parent_id').val();
            let subcat_Id = this.value;
            if(subcat_Id !=''){
              $.ajax({
                url : localurl+"category/getParentRelatedCategoryById",
                type:'post',
                dataType : "json",
                data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','subCat_id':subcat_Id ,'parent_cat':parent_id},
                beforeSend: function () {
                  jQuery('select#related_p_category').find("option:eq(0)").html("Please wait..");
                },
                complete: function () {
                    // code
                },
                success: function (json) {
                var options = '';
                options +='<option value="">--- Related Parent Category Name ---</option>';
                for (var i = 0; i < json.length; i++) {
                    options += '<option value="' + json[i].cat_id + '">' + json[i].category_name + '</option>';
                }
                jQuery("select#related_p_category").html(options);

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
             });
            }
        });
    }

    // get related sub parent category
    $('#related_p_category').change(function(){
        var sub_related_categoryId = $('#related_p_category').val();
        if(sub_related_categoryId !=''){
            $.ajax({
                url : localurl+"category/getSubRelatedCatById",
                type:'post',
                dataType : "json",
                data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','cat_id':sub_related_categoryId },
                beforeSend: function () {
                  jQuery('select#related_sub_category').find("option:eq(0)").html("Please wait..");
                },
                complete: function () {
                    // code
                },
                success: function (json) {
                var options = '';
                options +='<option value="">--- Related Sub Category Name ---</option>';
                for (var i = 0; i < json.length; i++) {
                    options += '<option value="' + json[i].cat_id + '">' + json[i].category_name + '</option>';
                }
                jQuery("select#related_sub_category").html(options);

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });

    <?php if(!empty($categoryContentData)) { ?>
        $('#category_content').DataTable({
            //"searching": true,
            "processing": true,
            "serverSide": true,
            "ajax" : {
                "url" : localurl+"category/categoryContentFormat_ajax",
                "type" : "POST",
            },
            "columns": <?php echo $categoryContentData; ?>,
            "language": {
                infoEmpty: "No records found",
            }
        });
    <?php } ?>

});
</script>