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

$('#state_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#state_details").dataTable().fnGetData(row);
    console.log(data);
    let url = localurl+"state/updateStatus";
	let postData = {"state_id":btoa(data.stateId)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"state/viewState";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"state/viewState";
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

$('#state_details').on('click','.delete_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#state_details").dataTable().fnGetData(row);
    let url = localurl+"state/deleteStateDetails";
	let postData = {"state_id":btoa(data.stateId)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"state/viewState";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"state/viewState";
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

$('#state_details').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#state_details").dataTable().fnGetData(row);
	window.location.href=localurl+"state/editState/"+btoa(data.stateId);
});