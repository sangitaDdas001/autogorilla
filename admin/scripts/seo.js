function ajaxCallJson(postData,apiurl,successCb,errorCb) {
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: apiurl,
		data: postData,
		success: successCb,
		error: errorCb
	});
}

$('#seo_details').on('click','.edit_info',function(e){
	let row 				= $($(this)).closest('tr'); 
    let data 				= $("#seo_details").dataTable().fnGetData(row);
	window.location.href 	= localurl+"seo/edit_seo/"+btoa(data.id);
});

$("#seo_details").on('click','.seo_content_delete',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#seo_details").dataTable().fnGetData(row);
    let url = localurl+"seo/deleteSeoContent";
	let postData = {"id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"seo/viewseo";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"seo/viewseo";
	};
	let errorCb = function(error) {
		alert("Something went wrong. Please try again later");
	};

	if(confirm('Are you sure, you want to delete the seo details?')) {
    	ajaxCallJson(postData,url,successCb,errorCb);
    } else {
    	return false;
    }
});