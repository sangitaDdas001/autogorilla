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
	$('#leads_details,#reject_leads_details,#active_leads_details,#buy_leads_details,#active_buy_leads_details,#all_direct_leads_details,#all_active_direct_leads_details').on('click','.leads_details ',function(e){
		let row = $($(this)).closest('tr');
	    let data = $("#leads_details,#reject_leads_details,#active_leads_details,#buy_leads_details,#active_buy_leads_details,#all_direct_leads_details,#all_active_direct_leads_details").dataTable().fnGetData(row);
	    let id   = data.id;
		window.location.href =localurl+'leads/leads_details/'+btoa(id);
	});
});

$('#leads_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
	let data = $("#leads_details").dataTable().fnGetData(row);
	if(data.status == 'Active'){
		if(confirm('Are you sure, you want to reject this lead ?')) {
    		$('#leadRejectionModal').modal('show');
    		$("#lead_id").val(data.id);
	    } else {
	    	return false;
	    }
	} else {
		let url 			= localurl+"leads/activeLeads";
		let postData 		= {"id" :data.id };
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href = localurl+"leads/all_leads";
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
	}
	
});


$('#active_leads_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
	let data = $("#active_leads_details").dataTable().fnGetData(row);
	//alert(data.status);
	if(data.status == 'Active'){
		if(confirm('Are you sure, you want to reject this lead ?')) {
    		$('#activeleadRejectionModal').modal('show');
    		$("#lead_id").val(data.id);
	    } else {
	    	return false;
	    }
	} else {
		let url 			= localurl+"leads/activeLeads";
		let postData 		= {"id" :data.id };
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href = localurl+"leads/reject_leads";
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
	}
});

$('#reject_leads_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
	let data = $("#reject_leads_details").dataTable().fnGetData(row);
	//alert(data.status);
 	if(data.status == 'Reject'){
		let url 			= localurl+"leads/activeLeads";
		let postData 		= {"id" :data.id };
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					//window.location.href = localurl+"leads/active_leads";
					history.back();
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
	}
});

$('#active_to_reject_lead').click(function(e){
	var rejected_reason = $('#reject_reason').val();
	var lead_id 		= $('#lead_id').val();
	let url 			= localurl+"leads/rejectedReasonUpdate";
	let postData 		= {"id" :lead_id,"rejected_reason":rejected_reason };

	//alert(rejected_reason);

	if(lead_id == ''){
		alert('Rejected id not found');
		return false;
	} else if(rejected_reason == ''){
		alert('Please enter the reason');
		return false;
	} else {
		let successCb = function(response) {
			if(response != '') {
				if(response.msg == 'rejected_reason_updated') {
					window.location.href = localurl+"leads/reject_leads";
				} else if(response.msg == 'not_updated_reason'){
					alert('Data not updated');
				} else if(response.msg == 'id_not_found'){
					alert('Rejected id not found');
				} else if(response.msg == 'rejected_reason_blank'){
					alert('Rejected reason is blank');
				} else {
					alert('Something went wrong');
				}
			}
			window.location.href = localurl+"leads/reject_leads";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		ajaxCallJson(postData,url,successCb,errorCb);
	}
});


$('#reject_lead').click(function(e){
	var rejected_reason = $('#reason').val();
	var lead_id 		= $('#lead_id').val();
	let url 			= localurl+"leads/rejectedReasonUpdate";
	let postData 		= {"id" :lead_id,"rejected_reason":rejected_reason };

	if(lead_id == ''){
		alert('Rejected id not found');
		return false;
	} else if(rejected_reason == ''){
		alert('Please enter the reason');
		return false;
	} else {
		let successCb = function(response) {
			if(response != '') {
				if(response.msg == 'rejected_reason_updated') {
					window.location.href = localurl+"leads/all_leads";
				} else if(response.msg == 'not_updated_reason'){
					alert('Data not updated');
				} else if(response.msg == 'id_not_found'){
					alert('Rejected id not found');
				} else if(response.msg == 'rejected_reason_blank'){
					alert('Rejected reason is blank');
				} else {
					alert('Something went wrong');
				}
			}
			window.location.href = localurl+"leads/reject_leads";
		};
		let errorCb = function(error) {
			alert("Something went wrong. Please try again later");
		};

		ajaxCallJson(postData,url,successCb,errorCb);
	}
});


$("#searchByDateRange").click(function () {
	var min_date = $('#min_date').val();
	var max_date = $('#max_date').val();
    $('#leads_details,#active_leads_details,#reject_leads_details,#buy_leads_details,#active_buy_leads_details,#all_direct_leads_details,#all_active_direct_leads_details').DataTable().draw();
});

$(document).on('click', '#clear-filter', function(){       
    $('input[data-type="search"]').val('');
    $('input[data-type="search"]').trigger("click");
});


$(document).ready(function(){
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
});


$('#buy_leads_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
	let data = $("#buy_leads_details").dataTable().fnGetData(row);
	if(data.status == 'Active'){
		if(confirm('Are you sure, you want to reject this lead ?')) {
    		$('#leadRejectionModal').modal('show');
    		$("#lead_id").val(data.id);
	    } else {
	    	return false;
	    }
	} else {
		let url 			= localurl+"leads/activeLeads";
		let postData 		= {"id" :data.id };
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href = localurl+"leads/buy_leads";
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
	}
	
});

$('#active_buy_leads_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
	let data = $("#active_buy_leads_details").dataTable().fnGetData(row);
	if(data.status == 'Active'){
		if(confirm('Are you sure, you want to reject this lead ?')) {
    		$('#leadRejectionModal').modal('show');
    		$("#lead_id").val(data.id);
	    } else {
	    	return false;
	    }
	} else {
		let url 			= localurl+"leads/activeLeads";
		let postData 		= {"id" :data.id };
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href = localurl+"leads/active_buy_leads";
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
	}
	
});

$('#all_direct_leads_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
	let data = $("#all_direct_leads_details").dataTable().fnGetData(row);
	if(data.status == 'Active'){
		if(confirm('Are you sure, you want to reject this lead ?')) {
    		$('#leadRejectionModal').modal('show');
    		$("#lead_id").val(data.id);
	    } else {
	    	return false;
	    }
	} else {
		let url 			= localurl+"leads/activeLeads";
		let postData 		= {"id" :data.id };
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href = localurl+"leads/direct_leads";
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
	}
	
});

$('##all_active_direct_leads_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
	let data = $("##all_active_direct_leads_details").dataTable().fnGetData(row);
	if(data.status == 'Active'){
		if(confirm('Are you sure, you want to reject this lead ?')) {
    		$('#leadRejectionModal').modal('show');
    		$("#lead_id").val(data.id);
	    } else {
	    	return false;
	    }
	} else {
		let url 			= localurl+"leads/activeLeads";
		let postData 		= {"id" :data.id };
		let successCb = function(response) {
			if(response != '') {
				if(response.status) {
					window.location.href = localurl+"leads/active_direct_leads";
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
	}
	
});