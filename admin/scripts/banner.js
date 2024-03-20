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



/*	Edit Banner details	*/
$('#banner_details').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#banner_details").dataTable().fnGetData(row);
	window.location.href=localurl+"banner/editBanner/"+btoa(data.id);
});

$('#banner_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#banner_details").dataTable().fnGetData(row);
    let url = localurl+"banner/updateBannerStatus";
	let postData = {"id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"banner/viewBannerList";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"banner/viewBannerList";
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

/*	Delete category details	*/
$('#banner_details').on('click','.delete_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#banner_details").dataTable().fnGetData(row);
    let url = localurl+"banner/deleteBannerDetails";
	let postData = {"banner_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"banner/viewBannerList";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"banner/viewBannerList";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to delete banner details?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#promo_banner_details').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#promo_banner_details").dataTable().fnGetData(row);
	window.location.href=localurl+"banner/editProtionBanner/"+btoa(data.id);
});

$('#promo_banner_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#promo_banner_details").dataTable().fnGetData(row);
    let url = localurl+"banner/updateBannerStatus";
	let postData = {"id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"banner/viewPromotionBanner";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"banner/viewPromotionBanner";
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

/*	Delete category details	*/
$('#promo_banner_details').on('click','.delete_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#promo_banner_details").dataTable().fnGetData(row);
    let url = localurl+"banner/deleteBannerDetails";
	let postData = {"banner_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"banner/viewPromotionBanner";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"banner/viewPromotionBanner";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to delete banner details?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});




