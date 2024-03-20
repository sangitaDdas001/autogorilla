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

$('#supplier_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/updateSupplierStatus";
	let postData = {"vendor_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier/";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to change status')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#supplier_details').on('click','.approve_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/updateSupplierApprovedStatus";
	let postData = {"vendor_id":btoa(data.id),'approved_by':'admin','email':data.email};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier/approved_supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier/approved_supplier";
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

$('#reject_supplier_details').on('click','.approve_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#reject_supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/updateSupplierApprovedStatus";
	let postData = {"vendor_id":btoa(data.id),'approved_by':'admin','email':data.email};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier/approved_supplier";
			} else {
				alert(response.message);
			}
		}
		
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

$('#pending_supplier_details').on('click','.approve_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#pending_supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/updateSupplierApprovedStatus";
	let postData = {"vendor_id":btoa(data.id),'approved_by':'admin','email':data.email};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier/approved_supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier/approved_supplier";
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

$('#supplier_details,#approved_supplier_details,#reject_supplier_details,#pending_supplier_details').on('click','.product_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_details,#approved_supplier_details,#reject_supplier_details,#pending_supplier_details").dataTable().fnGetData(row);
    window.location.href=localurl+"supplier/productInfo/"+btoa(data.id);
});



$('#supplier_details,#approved_supplier_details,#reject_supplier_details,#pending_supplier_details').on('click','.catlog_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_details,#approved_supplier_details,#reject_supplier_details,#pending_supplier_details").dataTable().fnGetData(row);
   
    $.ajax({
            url 		: localurl+"supplier/supplier_profile_check",
            type 		: 'post',
            dataType 	: "json",
            data 		: {'id' :data.id,'profile_check':'admin'},
            complete: function () {
                // code
            },
            success: function (json) {
                let url= "http://www.autogorilla.com/"+data.vendor_catalog_url_slug;
    			window.open(url, '_blank');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Something went wrong. Please try again later");
            }
    });
});

$('#supplier_details,#approved_supplier_details,#reject_supplier_details,#pending_supplier_details').on('click','.profile_info',function(e){
	let base_url = "http://www.autogorilla.com";
	let row = $($(this)).closest('tr');
    let data = $("#supplier_details,#approved_supplier_details,#reject_supplier_details,#pending_supplier_details").dataTable().fnGetData(row);

    let username = data.email;
	let password = data.password;
	let vendor_id = data.id;
	let url 	 = base_url +"/supplierLogin/loginSupplier";
    let postData = {'username':username,'password':password ,'login_form':'admin'};
    
    $.ajax({
            url 		: url,
            type 		: 'post',
            dataType 	: "json",
            data 		: postData,
            complete: function () {
                // code
            },
            success: function (json) {
                if(json.msg =='success_login'){
                    let locationUrl = base_url+"/supplier/company/update-vendor-details";
                    let extraWindow = window.open(locationUrl, '_blank');
                    if(extraWindow){
                    	setTimeout(function() {
                    		extraWindow.location.reload();
                    	}, 1000);
					}
                }

            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Something went wrong. Please try again later");
            }
    });
});



$('#supplier_product_details').on('click','.change_product_status ',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_product_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/updateProductStatus";
	let postData = {"product_id":btoa(data.product_id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier/productInfo/"+btoa(data.vendor_id);
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier/productInfo/"+btoa(data.vendor_id);
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



$('#supplier_product_details').on('click','.product_approve_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_product_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/updateSupplierProductApprovedStatus";
	let postData = {"product_id":btoa(data.product_id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier/productInfo/"+btoa(data.vendor_id);
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier/productInfo/"+btoa(data.vendor_id);
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

/*	Delete Subject details	*/
$('#supplier_details').on('click','.delete_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/deleteSupplierDetails";
	let postData = {"vendor_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to delete the details?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#supplier_details,#approved_supplier_details,#reject_supplier_details,#pending_supplier_details').on('click','.package_mapping',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_details,#approved_supplier_details,#reject_supplier_details,#pending_supplier_details").dataTable().fnGetData(row);
    window.location.href = localurl+"supplier/vendor_packageInfo/"+btoa(data.id);
});

$('.service_package_id').click(function(){
	if($(this).prop('checked')){
		if($(this).attr('data-id')==1){
			$("#pack_id_1").prop("checked", true);
			$("#pack_id_2,#pack_id_3,#pack_id_4,#pack_id_5,#pack_id_6").prop("checked", false);
			$(".package_start_date_2,.package_start_date_3,.package_start_date_4,.package_start_date_5,.package_start_date_6").val('');
			$(".package_expiry_date_2,.package_expiry_date_3,.package_expiry_date_4,.package_expiry_date_5,.package_expiry_date_6").val('');
		}else if($(this).attr('data-id')==2){
			$("#pack_id_2").prop("checked", true);
			$("#pack_id_1,#pack_id_3,#pack_id_4").prop("checked", false);
			$(".package_start_date_1,.package_start_date_3,.package_start_date_4").val('');
			$(".package_expiry_date_1,.package_expiry_date_3,.package_expiry_date_4").val('');
		}else if($(this).attr('data-id')==3){
			$("#pack_id_3").prop("checked", true);
			$("#pack_id_1,#pack_id_2,#pack_id_4").prop("checked", false);
			$(".package_start_date_1,.package_start_date_2,.package_start_date_4").val('');
			$(".package_expiry_date_1,.package_expiry_date_2,.package_expiry_date_4").val('');
		}
		else if($(this).attr('data-id')==4){
			$("#pack_id_4").prop("checked", true);
			$("#pack_id_1,#pack_id_2,#pack_id_3").prop("checked", false);
			$(".package_start_date_1,.package_start_date_2,.package_start_date_3").val('');
			$(".package_expiry_date_1,.package_expiry_date_2,.package_expiry_date_3").val('');
		}

	}
       
});



$('#supplier_details,#approved_supplier_details,#pending_supplier_details').on('click','.reject_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_details,#approved_supplier_details,#pending_supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/supplierRejected";
	let postData = {"vendor_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier/reject_supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier/reject_supplier";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to rejected this supplier ?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#supplier_product_details').on('click','.product_edit',function(e){
	let base_url ="http://www.autogorilla.com/";
	let row = $($(this)).closest('tr');
    let data = $("#supplier_product_details").dataTable().fnGetData(row);
    let product_url = base_url+"product/productDetailsByAdmin/"+btoa(data.product_id);
    window.open(product_url,'_blank');
});

$('#approved_supplier_details').on('click','.change_feature_company',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#approved_supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/updateCompanyFeatureStatus";
	let postData = {"vendor_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier/approved_supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier/approved_supplier";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you want to mark this comany as a featured company ?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#supplier_details').on('click','.change_feature_company',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/updateCompanyFeatureStatus";
	let postData = {"vendor_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you want to mark this comany as a featured company ?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#pending_supplier_details').on('click','.change_feature_company',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#pending_supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/updateCompanyFeatureStatus";
	let postData = {"vendor_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier/pending_supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier/pending_supplier";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you want to mark this comany as a featured company ?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#reject_supplier_details').on('click','.change_feature_company',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#reject_supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/updateCompanyFeatureStatus";
	let postData = {"vendor_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier/reject_supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier/reject_supplier";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you want to mark this comany as a featured company ?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('.approve_all_suppler').on('click',function(e){
	if(confirm('Are you want to approve all suppliers ?')) {
	    $.ajax({
	        url : localurl+"supplier/approved_all_supplierInfo",
	        type:'post',
	        dataType : "json",
	        data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
	        
	        complete: function () {
	            // code
	        },
	        success: function (json) {
	        	console.log(json);
	            if(json.status == true){
	            	window.location.href = localurl+"supplier";
	            }

	        },
	        error: function (xhr, ajaxOptions, thrownError) {
	            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
	}
        
});

$('.rejected_all_suppler').on('click',function(e){
	if(confirm('Are you want to reject all suppliers ?')) {
	    $.ajax({
	        url : localurl+"supplier/rejected_all_Supplier",
	        type:'post',
	        dataType : "json",
	        data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
	        
	        complete: function () {
	            // code
	        },
	        success: function (json) {
	            if(json.status == true){
	            	window.location.href = localurl+"supplier";
	            }

	        },
	        error: function (xhr, ajaxOptions, thrownError) {
	            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
	}
        
});

$('.rejected_all_ApprovedSupplier').on('click',function(e){
	if(confirm('Are you want to reject all suppliers ?')) {
	    $.ajax({
	        url : localurl+"supplier/rejected_all_ApprovedSupplier",
	        type:'post',
	        dataType : "json",
	        data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
	        
	        complete: function () {
	            // code
	        },
	        success: function (json) {
	            if(json.status == true){
	            	window.location.href = localurl+"supplier/reject_supplier";
	            }

	        },
	        error: function (xhr, ajaxOptions, thrownError) {
	            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
	}
        
});


$('.approved_all_rejectedSupplier').on('click',function(e){
	if(confirm('Are you want to approve all suppliers ?')) {
	    $.ajax({
	        url : localurl+"supplier/approved_all_rejectedSupplier",
	        type:'post',
	        dataType : "json",
	        data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
	        
	        complete: function () {
	            // code
	        },
	        success: function (json) {
	            if(json.status == true){
	            	window.location.href = localurl+"supplier/approved_supplier";
	            }else{
	            	window.location.href = localurl+"supplier/reject_supplier";
	            }

	        },
	        error: function (xhr, ajaxOptions, thrownError) {
	            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
	}
        
});

$('.rejected_all_pendingSupplier').on('click',function(e){
	if(confirm('Are you want to reject all suppliers ?')) {
	    $.ajax({
	        url : localurl+"supplier/rejected_all_pendingSupplier",
	        type:'post',
	        dataType : "json",
	        data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
	        
	        complete: function () {
	            // code
	        },
	        success: function (json) {
	            if(json.status == true){
	            	window.location.href = localurl+"supplier/reject_supplier";
	            }else{
	            	window.location.href = localurl+"supplier/pending_supplier";
	            }

	        },
	        error: function (xhr, ajaxOptions, thrownError) {
	            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
	}
        
});

$('.approved_all_supplier').on('click',function(e){
	if(confirm('Are you want to approve all suppliers ?')) {
	    $.ajax({
	        url : localurl+"supplier/approved_all_supplier",
	        type:'post',
	        dataType : "json",
	        data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
	        
	        complete: function () {
	            // code
	        },
	        success: function (json) {
	            if(json.status == true){
	            	window.location.href = localurl+"supplier/approved_supplier";
	            }else{
	            	window.location.href = localurl+"supplier/pending_supplier";
	            }

	        },
	        error: function (xhr, ajaxOptions, thrownError) {
	            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
	    });
	}
        
});


$('#supplier_details,#approved_supplier_details,#pending_supplier_details').on('click','.membership_listing',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#supplier_details,#approved_supplier_details,#pending_supplier_details").dataTable().fnGetData(row);
    let url = localurl+"supplier/subscriptionList";
	let postData = {"vendor_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				$('#membership_details').modal('show');
				$('.company_name').html(data.company_name);
				var html = '';
                for (var i = 0; i < response.data.length; i++) {
                    var sr_no = i+1;
                    html += '<tr>';
                        html+= '<td>'+sr_no+'</td>';
                            html+= '<td>'+response.data[i].service_plan+'</td>';
                                html += '<td class="align-middle">'+response.data[i].package_start_date+'</td>';
                                html += '<td class="align-middle">'+response.data[i].package_expiry_date+'</td>';
                                html += '<td class="align-middle">'+response.data[i].total_date_calculate+'</td>';
                                html += '<td class="align-middle">'+response.data[i].total_calculate_days+'</td>';
                                if(response.permission_access == 0){
                                    html += '<td class="align-middle"><a href="javascript:void(0);" data-subscriptionId ="'+response.data[i].service_package_id+'" data-vendorId = "'+btoa(data.id)+'" class="delete_subscription"><i class="fa fa-trash" style="font-size: 16px; color:red;" aria-hidden="true"></i></a></td>';
                                } else {
                                    html += '<td class="align-middle">Only View Access</td>';
                                }
                                //html += '<td class="align-middle"><a href="javascript:void(0);" data-subscriptionId ="'+response.data[i].service_package_id+'" data-vendorId = "'+btoa(data.id)+'" class="delete_subscription"><i class="fa fa-trash" style="font-size: 16px; color:red;" aria-hidden="true"></i></a></td>';
                    html += '</tr>';
                }
                $('.dynamicSubscriptionData').html(html);
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

$(document.body).on('click','.delete_subscription',function(e){
	var subscriptionId  = $(this).attr('data-subscriptionId');
	var vendorId 		= $(this).attr('data-vendorId');
	if(subscriptionId !='' && vendorId !=''){
		let url = localurl+"supplier/subscriptionDelete";
		let postData = {"vendor_id": vendorId ,'subscriptionId':subscriptionId };
		let successCb = function(response) {
			if(response != '') {
				if(response.status==true) {
					alert(response.message);
					window.location.href = localurl+"supplier";
				} else {
					alert(response.message);
				}
			}
		}

	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

		if(confirm('Are you sure, you want to delete a subscription?')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	}
});


$('#min, #max').keyup(function() {
    var min = $('#min').val();
    var max = $('#max').val();
    $('#supplier_details_all').DataTable().draw();
});

$('#min_catlog, #max_catlog').keyup(function() {
    var min = $('#max_catlog').val();
    var max = $('#max_catlog').val();
    $('#approved_supplier_details').DataTable().draw();
});

$(document).on('click', '#clear-filter', function(){       
    $('input[data-type="search"]').val('');
    $('input[data-type="search"]').trigger("keyup");
});

$(document.body).on('click','.membership_listing_sup',function(e){
	var id = $(this).attr('data-id');
	var companyname = $(this).attr('data-companyname');
	let url = localurl+"supplier/subscriptionList";
	let postData = {"vendor_id":btoa(id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				$('#membership_details').modal('show');
				$('.company_name').html(companyname);
				var html = '';
                for (var i = 0; i < response.data.length; i++) {
                    var sr_no = i+1;
                    html += '<tr>';
                        html+= '<td>'+sr_no+'</td>';
                            html+= '<td>'+response.data[i].service_plan+'</td>';
                                html += '<td class="align-middle">'+response.data[i].package_start_date+'</td>';
                                html += '<td class="align-middle">'+response.data[i].package_expiry_date+'</td>';
                                html += '<td class="align-middle">'+response.data[i].total_date_calculate+'</td>';
                                html += '<td class="align-middle">'+response.data[i].total_calculate_days+'</td>';
                                if(response.permission_access == 0){
                                    html += '<td class="align-middle"><a href="javascript:void(0);" data-subscriptionId ="'+response.data[i].service_package_id+'" data-vendorId = "'+btoa(id)+'" class="delete_subscription"><i class="fa fa-trash" style="font-size: 16px; color:red;" aria-hidden="true"></i></a></td>';
                                } else {
                                    html += '<td class="align-middle">Only View Access</td>';
                                }
                                //html += '<td class="align-middle"><a href="javascript:void(0);" data-subscriptionId ="'+response.data[i].service_package_id+'" data-vendorId = "'+btoa(id)+'" class="delete_subscription"><i class="fa fa-trash" style="font-size: 16px; color:red;" aria-hidden="true"></i></a></td>';
                    html += '</tr>';
                }
                $('.dynamicSubscriptionData').html(html);
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

$(document.body).on('click','.change_feature_companys',function(e){
	var id = $(this).attr('data-id');
	let url = localurl+"supplier/updateCompanyFeatureStatus";
	let postData = {"vendor_id":btoa(id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you want to mark this comany as a featured company ?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$(document.body).on('click','.rejectStatus',function(e){
	var id = $(this).attr('data-id');
	let url = localurl+"supplier/supplierRejected";
	let postData = {"vendor_id":btoa(id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to rejected this supplier ?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$(document.body).on('click','.approveStatus',function(e){
	var id 		= $(this).attr('data-id');
	var email   = $(this).attr('data-email');
	let url = localurl+"supplier/updateSupplierApprovedStatus";
	let postData = {"vendor_id":btoa(id),'email':email};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"supplier";
			} else {
				alert(response.message);
			}
		}
		window.location.href = localurl+"supplier";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to change approved status ?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$(document.body).on('click','.profileInfo',function(e){
	var vendor_id 	= $(this).attr('data-id');
	var username   	= $(this).attr('data-email');
	var password   	= $(this).attr('data-password');
	let base_url    = "https://www.autogorilla.com";
	let url 	   = base_url +"/supplierLogin/loginSupplier";
	let postData = {'username':username,'password':password ,'login_form':'admin'};
	$.ajax({
            url 		: url,
            type 		: 'post',
            dataType 	: "json",
            data 		: postData,
            complete: function () {
                // code
            },
            success: function (json) {
                if(json.msg =='success_login'){
                    let locationUrl = base_url+"/supplier/company/update-vendor-details";
                    let extraWindow = window.open(locationUrl, '_blank');
                    if(extraWindow){
                    	setTimeout(function() {
                    		extraWindow.location.reload();
                    	}, 1000);
					}
                }

            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert("Something went wrong. Please try again later");
            }
    });
});

$(document.body).on('click','.productInfo',function(e){
	var id= $(this).attr('data-id');
	window.location.href=localurl+"supplier/productInfo/"+btoa(id);
});

$(document.body).on('click','.packageMapping',function(e){
	var id= $(this).attr('data-id');
	window.location.href=localurl+"supplier/vendor_packageInfo/"+btoa(id);
});

$(document.body).on('click','.category_info',function(e){
	var id= $(this).attr('data-id');
	window.location.href=localurl+"supplier/supplierOwnCategory_info/"+btoa(id);
});

$('#duplicate_supplier_details').on('click','.delete_duplicate_data',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#duplicate_supplier_details").dataTable().fnGetData(row);
	let url = localurl+"supplier/deleteSupplierRecord";
	let postData = {"vendor_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href =localurl+"supplier/viewDuplicateSuppliers";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"supplier/viewDuplicateSuppliers";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure , you want to delete this supplier ?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});