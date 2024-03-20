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

$('#city_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#city_details").dataTable().fnGetData(row);
    let url = localurl+"city/updateStatus";
	let postData = {"city_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"city/viewCity";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"city/viewCity";
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

$('#city_details').on('click','.delete_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#city_details").dataTable().fnGetData(row);
    let url = localurl+"city/deleteCityDetails";
	let postData = {"city_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"city/viewCity";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"city/viewCity";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to delete the city details?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});

$('#city_details').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#city_details").dataTable().fnGetData(row);
	window.location.href=localurl+"city/editCity/"+btoa(data.id);
});