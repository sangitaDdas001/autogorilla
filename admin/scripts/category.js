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



/*	Edit Category details	*/
$('#category_details').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#category_details").dataTable().fnGetData(row);
	window.location.href=localurl+"category/editCat/"+btoa(data.id);
});

$('#subcategory_details').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#subcategory_details").dataTable().fnGetData(row);
	window.location.href=localurl+"category/editsubCat/"+btoa(data.id);
});

$('#category_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#category_details").dataTable().fnGetData(row);
    let url = localurl+"category/updateCatStatus";
	let postData = {"cat_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"category/viewCategory";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"category/viewCategory";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to change category status')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});


/*	Delete category details	*/
$('#category_details').on('click','.delete_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#category_details").dataTable().fnGetData(row);
    let url = localurl+"category/deleteCatDetails";
	let postData = {"cat_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"category/viewCategory";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"category/viewCategory";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to delete the category details?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

/*	Delete subcategory details	*/
$('#subcategory_details').on('click','.delete_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#subcategory_details").dataTable().fnGetData(row);
    let url = localurl+"category/deleteSubCatDetails";
	let postData = {"cat_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"category/viewSubCategory";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"category/viewSubCategory";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to delete the category details?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#subcategory_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#subcategory_details").dataTable().fnGetData(row);
    let url = localurl+"category/updateCatStatus";
	let postData = {"cat_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"category/viewSubCategory";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"category/viewSubCategory";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to change category status')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#microCategory_details').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#microCategory_details").dataTable().fnGetData(row);
	window.location.href=localurl+"category/editMicroCat/"+btoa(data.id);
});


/*	Delete subcategory details	*/
$('#microCategory_details').on('click','.delete_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#microCategory_details").dataTable().fnGetData(row);
    let url = localurl+"category/deleteMicroCatDetails";
	let postData = {"cat_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"category/viewMicroCategory";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"category/viewMicroCategory";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to delete the category details?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#microCategory_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#microCategory_details").dataTable().fnGetData(row);
    let url = localurl+"category/updateMicCatStatus";
	let postData = {"cat_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"category/viewMicroCategory";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"category/viewMicroCategory";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to change category status')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

/***** END *****/
$('#category_content').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#category_content").dataTable().fnGetData(row);
	window.location.href=localurl+"category/editAllCategoryContent/"+btoa(data.id);
});
