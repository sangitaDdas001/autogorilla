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

$(document).ready(function(){
	/*	Delete department details	*/
	$('#department_details').on('click','.delete_info',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#department_details").dataTable().fnGetData(row);
	    let url = localurl+"userManagement/deleteDepartment";
		let postData = {"id":btoa(data.id)};
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href = localurl+"userManagement/department";
				} else {
					alert(response.message);
				}
			}
			window.location.href = localurl+"userManagement/department";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		if(confirm('Are you sure, you want to delete department details ?')) {
	    	ajaxCallJson(postData,url,successCb,errorCb);
	    } else {
	    	return false;
	    }
	});

	$('#department_details').on('click','.edit_info',function(e){
		let row 				= $($(this)).closest('tr'); 
	    let data 				= $("#department_details").dataTable().fnGetData(row);
		window.location.href 	= localurl+"userManagement/editDepartment/"+btoa(data.id);
	});

	$('#department_details').on('click','.change_status',function(e){
    let row = $($(this)).closest('tr');
    let data = $("#department_details").dataTable().fnGetData(row);
    let url = localurl+"UserManagement/updateDepartmentStatus";
    let postData = {"id":btoa(data.id)};
    
	    let successCb = function(response) {
	        if(response != '') {
	            if(response.status) {
	                window.location.href=localurl+"userManagement/department";
	            } else {
	                alert(response.message);
	            }
	        }
	        window.location.href=localurl+"userManagement/department";
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
});