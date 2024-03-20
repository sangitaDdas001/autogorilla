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

$('#country_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#country_details").dataTable().fnGetData(row);
    let url = localurl+"country/updateStatus";
	let postData = {"country_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"country/viewCountry";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"country/viewCountry";
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

$('#country_details').on('click','.delete_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#country_details").dataTable().fnGetData(row);
    let url = localurl+"country/deleteCountryDetails";
	let postData = {"country_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"country/viewCountry";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"country/viewCountry";
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

$('#country_details').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#country_details").dataTable().fnGetData(row);
	window.location.href=localurl+"country/editCountry/"+btoa(data.id);
});