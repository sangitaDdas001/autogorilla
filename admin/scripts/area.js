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


$('#area_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#area_details").dataTable().fnGetData(row);
    let url = localurl+"area/updateAreaStatus";
	let postData = {"id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"area";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"area";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to change area status')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#area_details').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#area_details").dataTable().fnGetData(row);
	window.location.href=localurl+"area/editArea/"+btoa(data.id);
});

/*	Delete category details	*/
$('#area_details').on('click','.delete_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#area_details").dataTable().fnGetData(row);
    let url = localurl+"area/deleteArea";
	let postData = {"id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"area";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"area";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to delete area details?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});
