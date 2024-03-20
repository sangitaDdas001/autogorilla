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


/*
$('#state_id').on('change', function() {
    alert('suhds');
    var stateId = $(this).val();
    if(stateId !=''){
        $.ajax({
            url : localurl+"SalesManagement/getCityList",
            type:'post',
            dataType : "json",
            data:{'stateId':stateId },
            beforeSend: function () {
              jQuery('select[multiple]#city_id').find("option:eq(0)").html("Please wait..");
            },
            complete: function () {
                // code
            },
            success: function (json) {
            	console.log(json);
                var options = '<option value="">-- Select City --</option>';
                for (var i = 0; i < json.data.length; i++) {
                    options += '<option value="' + json.data[i].cityId + '">' + json.data[i].city_name + '</option>';
                }
                $('#city_id').html(options).select2().trigger('change');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});*/

/*$('#city_id').on('change', function() {
    let cityId = this.value;
    if(cityId !=''){
        $.ajax({
            url : localurl+"SalesManagement/getAreaList",
            type:'post',
            dataType : "json",
            data:{'cityId':cityId },
            beforeSend: function () {
              jQuery('select#area_id').find("option:eq(0)").html("Please wait..");
            },
            complete: function () {
                // code
            },
            success: function (json) {
            	console.log(json);
                var options = '<option value="">-- Select Area/ZipCode --</option>';
                for (var i = 0; i < json.data.length; i++) {
                    options += '<option value="' + json.data[i].areaId + '">' + json.data[i].area_name + '</option>';
                }
                $('#area_id').html(options);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});*/



$(document).ready(function(){
    var csrf_token = $('#csrf_token').val();
    var csrf_token_value = $('#csrf_token_value').val();
    var data ="department_id=9"+"&csrf_test_name="+csrf_token_value; 
    $.ajax({
        url: localurl + "salesManagement/get_users_list_treeview_ajax",
        data: data,
        cache: false,
        method: 'POST',
        dataType: "html",
        beforeSend: function(xhr) {
            
        },
        complete: function(){
           
        },
        success: function(result) {
            var data = JSON.parse(result);
            $('#tree').jstree({     
              'core' : {
                "animation" : 300,
                'data' : data.html,
                "check_callback" : true,
              },   
          
              'plugins' : [ "types"],
              'types' : {
                  'default' : {
                      'icon' : 'fa fa-user fa-fw'
                  }
              }   
              
            });
        }
    });

});
    
$("body").on("click",".permission_update_row",function(e){
    let id= $(this).attr('data-uid');
    window.location.href = localurl+"userManagement/editUserManagement/"+btoa(id)+'/salesuser';
});


$('#tree').bind("loaded.jstree", function(event, data) {
    $(this).jstree("open_all");
    $('[data-toggle="tooltip"]').tooltip({html: true});
});

    
$("body").on("click",".edit_user_view",function(e){
    let id= $(this).attr('data-uid');
    window.location.href = localurl+"SalesManagement/editSalesUser/"+btoa(id);
});

$("body").on("click",".delete_user",function(e){
    let id= $(this).attr('data-uid');
    if (confirm('Are you want to delete this user ?') == true) {
       window.location.href = localurl+"SalesManagement/deleteSalesUser/"+btoa(id);
    } 
    
});

$("body").on("click",".assigned_company_list",function(e){
    let id = $(this).attr('data-uid');
    window.location.href = localurl+"salesManagement/assignedCompanyListForUser/"+btoa(id);
});


