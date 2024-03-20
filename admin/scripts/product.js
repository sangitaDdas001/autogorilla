function ajaxCallJson(postData,apiurl,successCb,errorCb) {
	$.ajax({
		type: 'POST',
		dataType: 'json',
		// context:this,
		url: apiurl,
		data: postData,
		success: successCb,
		error: errorCb
	});
}
     let base_url ="https://www.autogorilla.com";

$(document).ready(function(){
	$('#supplier_product_details').on('click','.change_product_status ',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#supplier_product_details").dataTable().fnGetData(row);
	    let url = localurl+"product/updateProductStatus";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href=localurl+"product";
				} else {
					alert(response.message);
				}
			}
			window.location.href=localurl+"product"
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to change approved status')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});

	$('#supplier_product_details,#supplier_product_mapping_details,#all_product_un_mapping_details').on('click','.product_approve_status',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#supplier_product_details,#supplier_product_mapping_details,#all_product_un_mapping_details").dataTable().fnGetData(row);
	    let url = localurl+"product/updateProductApprovedStatus";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href=localurl+"product/approved_product_list";
				} else {
					alert(response.message);
				}
			}
			//window.location.href=localurl+"product";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to change approved status')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});


	$('#all_product_list').on('click','.delete_product',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#all_product_list").dataTable().fnGetData(row);
	    //alert(data.product_id);
	    let url = localurl+"product/deleteProduct";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
		if(response != '') 
		{
			if(response.status) 
			{
				window.location.href = localurl+"product/all_product_list";
			} 
			else 
			{
				alert(response.message);
			}
		}
			window.location.href = localurl+"product/all_product_list";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to permanently delete this product?')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});

	$('#approved_product_details').on('click','.delete_product',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#approved_product_details").dataTable().fnGetData(row);
	    //alert(data.product_id);
	    let url = localurl+"product/deleteProduct";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
		if(response != '') 
		{
			if(response.status) 
			{
				window.location.href = localurl+"product/approved_product_list";
			} 
			else 
			{
				alert(response.message);
			}
		}
			window.location.href = localurl+"product/approved_product_list";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to permanently delete this product?')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});

	$('#supplier_product_details').on('click','.delete_product',function(e){
		let row = $($(this)).closest('tr');
		let data = $("#supplier_product_details").dataTable().fnGetData(row);
		//alert(data.product_id);
		let url = localurl+"product/deleteProduct";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
		if(response != '') 
		{
			if(response.status) 
			{
				window.location.href = localurl+"product";
			} 
			else 
			{
				alert(response.message);
			}
		}
		window.location.href = localurl+"product";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to permanently delete this product?')) {
			ajaxCallJson(postData,url,successCb,errorCb);
		} else {
			return false;
		}
	});

	$('#reject_product_details').on('click','.delete_product',function(e){
		let row = $($(this)).closest('tr');
		let data = $("#reject_product_details").dataTable().fnGetData(row);
		//alert(data.product_id);
		let url = localurl+"product/deleteProduct";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
		if(response != '') 
		{
			if(response.status) 
			{
				window.location.href = localurl+"product/reject_list";
			} 
			else 
			{
				alert(response.message);
			}
		}
		window.location.href = localurl+"product/reject_list";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to permanently delete this product?')) {
			ajaxCallJson(postData,url,successCb,errorCb);
		} else {
			return false;
		}
	});

	$('#supplier_product_mapping_details').on('click','.delete_product',function(e){
		let row = $($(this)).closest('tr');
		let data = $("#supplier_product_mapping_details").dataTable().fnGetData(row);
		//alert(data.product_id);
		let url = localurl+"product/deleteProduct";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
		if(response != '') 
		{
			if(response.status) 
			{
				window.location.href = localurl+"product/product-mapping-information";
			} 
			else 
			{
				alert(response.message);
			}
		}
		window.location.href = localurl+"product/product-mapping-information";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to permanently delete this product?')) {
			ajaxCallJson(postData,url,successCb,errorCb);
		} else {
			return false;
		}
	});

	$('#all_product_un_mapping_details').on('click','.delete_product',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#all_product_un_mapping_details").dataTable().fnGetData(row);
	    //alert(data.product_id);
	    let url = localurl+"product/deleteProduct";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
		if(response != '') 
		{
			if(response.status) 
			{
				window.location.href = localurl+"product/all_unmapped_products";
			} 
			else 
			{
				alert(response.message);
			}
		}
			window.location.href = localurl+"product/all_unmapped_products";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to permanently delete this product?')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});



	$('#reject_product_details').on('click','.product_approve_status',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#reject_product_details").dataTable().fnGetData(row);
	    let url = localurl+"product/updateProductApprovedStatus";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href=localurl+"product/approved_product_list";
				} else {
					alert(response.message);
				}
			}
			//window.location.href=localurl+"product";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to change approved status')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});


	$('#supplier_product_details').on('click','.product_details',function(e){
		let row 		= $($(this)).closest('tr');
	    let data 		= $("#supplier_product_details").dataTable().fnGetData(row);
	    let url 		= localurl+"product/getProductetails";
		let postData 	= {"product_id":btoa(data.product_id)};
		let baseurl 	= "http://dev.autogorilla.com/";
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					var html='';
					$('#p_id').val(response.data[0].product_id);
					$('#productDetails').modal('show');
					if(response.data[0].product_code != ''){
						$('.p_name').html(response.data[0].product_name+' ('+ response.data[0].product_code+')');
					}else{
						$('.p_name').html(response.data[0].product_name);
					}
					$('.p_code').html(response.data[0].product_code);
					$('.p_description').html(response.data[0].product_description);
					if(response.data[0].approved_status == 3){
						$('#p_status').hide();
						$('#a_status').hide();
						$('#r_status').html('Rejected');
						$("#r_status").removeClass("cursor-pointer");
					}else if(response.data[0].approved_status == 1){
						$('#p_status').hide();
						$('#a_status').show();
					}else{
						$('#p_status').show();
						$('#a_status').hide();
					}
					if(response.data[0].vendor_name !=''){
						$('.v_name').html(response.data[0].vendor_name);
					}
					if(response.data[0].product_price !=''){
						$('.p_price').html(response.data[0].product_price);
					}else{
						$('.p_price').html('NO record found');
					}
					if(response.data[0].brand !=''){
						$('.b_name').html(response.data[0].brand);
					}else{
						$('.b_name').html('No record found');
					}
					if(response.data[0].product_video_1 != ''){
						$('.p_videos_data1').html('<td><a href='+response.data[0].product_video_1+' target="_blank">'+response.data[0].product_video_1+'</a></td>');
					}else{
						$('.p_videos_data1').html('No record found');
					}
					if(response.data[0].product_video_2 != ''){
						$('.p_videos_data2').html('<td><a href='+response.data[0].product_video_2+' target="_blank">'+response.data[0].product_video_2+'</a></td>');
					}else{
						$('.p_videos_data2').html('No record found');
					}
					if(response.data[0].product_video_3 != ''){
						$('.p_videos_data3').html('<td><a href='+response.data[0].product_video_3+' target="_blank">'+response.data[0].product_video_3+'</a></td>');
					}else{
						$('.p_videos_data3').html('No record found');
					}

					if(response.data[0].product_img_1 != ''){
						$('.p_img1').html('<td>Product Image1</td><td><img src='+baseurl+'uploads/product/'+response.data[0].product_img_1+' style="width:50px;height:50px;"></td>');
					}else {
						$('.p_img1').html('<td>Product Image1</td><td><img src='+baseurl+'uploads/noimage.png style="width:50px;height:50px;"></td>');
					}
					if(response.data[0].product_img_2 != ''){
						$('.p_img2').html('<td>Product Image2</td><td><img src='+localurl+'product/'+response.data[0].product_img_2+' style="width:50px;height:50px;"></td>');
					}else{
						$('.p_img2').html('<td>Product Image2</td><td><img src='+baseurl+'uploads/noimage.png style="width:50px;height:50px;"></td>');
					}
					if(response.data[0].product_img_3 != ''){
						$('.p_img3').html('<td>Product Image3</td><td><img src='+localurl+'product/'+response.data[0].product_img_3+' style="width:50px;height:50px;"></td>');
					}else{
						$('.p_img3').html('<td>Product Image3</td><td><img src='+baseurl+'uploads/noimage.png style="width:50px;height:50px;"></td>');
					}
					if(response.data[0].specification !=''){
						for(var i = 0; i < response.data[0].specification.length; i++) {
						 	html += '<tr>';
							html += '<td class="sp_td">'+response.data[0].specification[i].title+'</td>';
							html += '<td>'+response.data[0].specification[i].specification_details+'</td>';
							html += '</tr>';
						}
						$('#spcefication_tbl').html(html);
					} else {
						    html += '<tr>';
							html += '<td style="text-align: center;">' ;
							html += 'No record found';
							html += '</td>';
							html += '</tr>';
							$('#spcefication_tbl').html(html);
					}
					if(response.data[0].category_mapping){
						$('.parent_cat').html(response.data[0].category_mapping[0].category_name);
						$('.sub_cat').html(response.data[0].category_mapping[0].sub_cat);
						$('.micro_cat').html(response.data[0].category_mapping[0].micro_cat_name);
					}else{
						$('.parent_cat').hide();
						$('.sub_cat').hide();
						$('.micro_cat').hide();
						$('.data1').html('<td colspan="3" style="text-align:center;">No Record Found </td>');
					}
				} else {
					alert(response.message);
				}
			}
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};
		ajaxCallJson(postData,url,successCb,errorCb);
	    
	});

	$('#supplier_product_details,#supplier_product_mapping_details,#all_product_un_mapping_details').on('click','.product_reject_status',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#supplier_product_details,#supplier_product_mapping_details,#all_product_un_mapping_details").dataTable().fnGetData(row);
	    let url = localurl+"product/productRejected";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href=localurl+"product/reject_list";
				} else {
					alert(response.message);
				}
			}
			window.location.href=localurl+"product/reject_list";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to reject this product?')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});

	$('#approved_product_details').on('click','.product_reject_status',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#approved_product_details").dataTable().fnGetData(row);
	    let url = localurl+"product/productRejected";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href=localurl+"product/approved_product_list";
				} else {
					alert(response.message);
				}
			}
			window.location.href=localurl+"product/approved_product_list";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to reject this product?')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});

	$('#p_status').on('click',function(e){
		let product_id = $('#p_id').val();
	    let url = localurl+"product/updateProductApprovedStatus";
		let postData = {"product_id":btoa(product_id)};
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					$('#p_status').hide();
					$('#a_status').show();
				} else {
					alert(response.message);
				}
			}
			window.location.href=localurl+"product";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to change approved status')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});

	$('#r_status').on('click',function(e){
		let product_id = $('#p_id').val();
	    let url = localurl+"product/productRejected";
		let postData = {"product_id":btoa(product_id)};
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					$('#p_status').hide();
					$('#a_status').hide();
					$('#r_status').html('Rejected');
					$("#r_status").removeClass("cursor-pointer");
				} else {
					alert(response.message);
				}
			}
			window.location.href=localurl+"product";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to reject this product')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});

	$('#supplier_product_details').on('click','.product_mapping',function(e){
		let row  = $($(this)).closest('tr'); 
	    let data = $("#supplier_product_details").dataTable().fnGetData(row);
		window.location.href=localurl+"product/productMapping/"+btoa(data.product_id);
	});

	$('.parent_id').on('change', function() {
	        let parent_id = this.value;
	        let csrf      = $('#csrf').val();
	        if(parent_id !=''){
	          $.ajax({
	            url : localurl+"category/getsubCategoryById",
	            type:'post',
	            dataType : "json",
	            data:{'csrf_test_name':csrf,'parent_id':parent_id},
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

    $('#subcat-name').on('change', function() {
        let sub_catId = this.value;
        let csrf      = $('#csrf').val();
        if(sub_catId !=''){
            $.ajax({
                url : localurl+"product/getMicroCategoryNameBySubCatId",
                type:'post',
                dataType : "json",
                data:{'csrf_test_name':csrf,'sub_cat_id':sub_catId },
                beforeSend: function () {
                  jQuery('select[multiple]#micro_cat_id').find("option:eq(0)").html("Please wait..");
                },
                complete: function () {
                    // code
                },
                success: function (json) {
                    var options = '<option value="">-- Select Micro Category --</option>';
                    for (var i = 0; i < json.length; i++) {
                        options += '<option value="' + json[i].id + '">' + json[i].category_name + '</option>';
                    }
                    $('#micro_cat_id').html(options).select2().trigger('change');
                    //$('#micro_category_select').select2().trigger('change');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    });


    $('#supplier_product_details,#supplier_product_mapping_details').on('click','.product_edit',function(e){
		let row  = $($(this)).closest('tr'); 
	    let data = $("#supplier_product_details,#supplier_product_mapping_details").dataTable().fnGetData(row);
		let username = data.email;
		let password = data.password;
        let url = base_url +"/supplierLogin/loginSupplier";
        let postData = {'username':username,'password':password };
        $.ajax({
	            url 		: url,
	            type 		: 'post',
	            dataType 	: "json",
	            data: 		postData,
	            complete: function () {
	                // code
	            },
	            success: function (json) {
	                if(json.msg =='success_login'){
                        window.location.href = base_url+"/supplier/product/edit/"+btoa(data.product_id);
                    }

	            },
	            error: function (xhr, ajaxOptions, thrownError) {
	                alert("Something went wrong. Please try again later");
	            }
	    });

	});

	$('#supplier_product_details,#supplier_product_mapping_details,#all_product_list,#all_product_un_mapping_details').on('click','.catlog_info',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#supplier_product_details,#supplier_product_mapping_details,#all_product_list,#all_product_un_mapping_details").dataTable().fnGetData(row);
	    $.ajax({
            url 		: localurl+"supplier/supplier_profile_check",
            type 		: 'post',
            dataType 	: "json",
            data 		: {'id' :data.vendor_id,'profile_check':'admin'},
            complete: function () {
                // code
            },
            success: function (json) {
                let url= base_url+'/'+data.vendor_catalog_url_slug+"/products";
    			window.open(url, '_blank');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Something went wrong. Please try again later");
            }
    	});
	});

	$('#supplier_product_details,#reject_product_details,#approved_product_details,#supplier_product_mapping_details,#all_product_un_mapping_details').on('click','.product_edit_update',function(e){
		let base_url ="https://www.autogorilla.com/";
		let row = $($(this)).closest('tr');
	    let data = $("#supplier_product_details,#reject_product_details,#approved_product_details,#supplier_product_mapping_details,#all_product_un_mapping_details").dataTable().fnGetData(row);
	    let product_url = base_url+"product/productDetailsByAdmin/"+btoa(data.product_id);
	    window.open(product_url,'_blank');
	});

	$('#all_product_list').on('click','.product_reject_status',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#all_product_list").dataTable().fnGetData(row);
	    let url = localurl+"product/productRejected";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href=localurl+"product/all_product_list";
				} else {
					alert(response.message);
				}
			}
			window.location.href=localurl+"product/all_product_list";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to reject this product?')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});

	$('#all_product_list').on('click','.product_approve_status',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#all_product_list").dataTable().fnGetData(row);
	    let url = localurl+"product/updateProductApprovedStatus";
		let postData = {"product_id":btoa(data.product_id)};
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href = localurl+"product/all_product_list";
				} else {
					alert(response.message);
				}
			}
			//window.location.href=localurl+"product";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to change approved status')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});

	$('#all_product_list').on('click','.product_edit_update',function(e){
		let base_url ="https://www.autogorilla.com/";
		let row = $($(this)).closest('tr');
	    let data = $("#all_product_list").dataTable().fnGetData(row);
	    let product_url = base_url+"product/productDetailsByAdmin/"+btoa(data.product_id);
	    window.open(product_url,'_blank');
	});

	$('#min, #max').keyup(function() {
    	var min = $('#min').val();
    	var max = $('#max').val();
    	$('#all_product_list').DataTable().draw();
	});

	$('#min_ps, #max_ps').keyup(function() {
    	var min_ps = $('#min_ps').val();
    	var max_ps = $('#max_ps').val();
    	$('#approved_product_details').DataTable().draw();
	});
	
	$(document).on('click', '#clear-filter', function(){       
	    $('input[data-type="search"]').val('');
	    $('input[data-type="search"]').trigger("keyup");
	});

	$("#searchByDateRange").click(function () {
		var min_date = $('#min_date').val();
		var max_date = $('#max_date').val();
		//alert(max_date);
		$('#all_product_list,#approved_product_details,#supplier_product_details,#reject_product_details,#all_product_un_mapping_details,#supplier_product_mapping_details').DataTable().draw();
	});

	$("#min_date").datepicker({
		'format': "yyyy-mm-dd",
		changeMonth: true,
		changeYear: true,
		autoclose: true,
	     endDate: "today"
	});

	$("#max_date").datepicker({
		'format': "yyyy-mm-dd",
		changeMonth: true,
		changeYear: true,
		autoclose: true,
		endDate: "today"
	});
	
	$('#approved_product_details,#all_product_list').on('click','.add_seo_info',function(e){
		let row = $($(this)).closest('tr'); 
	    let data = $("#approved_product_details,#all_product_list").dataTable().fnGetData(row);
		window.location.href=localurl+"product/seo_info/"+btoa(data.product_id);
	});
});