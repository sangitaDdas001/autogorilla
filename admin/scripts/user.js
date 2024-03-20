function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

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
    $('.all_1_vendor').hide(); // ul hide when page load
    $('.sp_vendor_1').hide(); // ul hide when page load
    $('.sp_view_19').hide();
    $('.default_view').hide();

    $('#all_user_list,#inactive_user_list,#active_user_list').on('click','.user_management_info',function(e){
        let row = $($(this)).closest('tr'); 
        let data = $("#all_user_list,#inactive_user_list,#active_user_list").dataTable().fnGetData(row);
        window.location.href = localurl+"userManagement/editUserManagement/"+btoa(data.id);
    });

    $('.parent_menu').on('click', function(e){  
        if(($('#parent_19').is(':checked')) && $('#parent_24').is(':checked')){
            $('.option_19').show();
            $('.all_1_vendor').show(); 
            $('.sp_vendor_1').show(); 
            $('.sp_view_19').show(); 
            $('.default_view_24').show();
        }else if($('#parent_24').is(':checked')){   
            $('.option_19').hide();
            $('.all_1_vendor').hide(); 
            $('.sp_vendor_1').hide(); 
            $('.sp_view_19').hide();
            $('.default_view_24').show();
            $("#sp_10").removeAttr("checked"); 
            $('#all_1').removeAttr("checked"); 
        }else if($('#parent_19').is(':checked')){ 
            $('.option_19').show();
            $('.all_1_vendor').show(); 
            $('.sp_vendor_1').show(); 
            $('.sp_view_19').show(); 
            $('.default_view_24').hide();
        }else{
            $('.option_19').hide();
            $('.all_1_vendor').hide(); 
            $('.sp_vendor_1').hide(); 
            $('.sp_view_19').hide(); 
            $('.default_view_24').hide();
            $("#sp_10").removeAttr("checked"); 
            $('#all_1').removeAttr("checked"); 
	        $('#sp_view_24').removeAttr("checked"); 
        }
    });

    $('#parent_19').on('change', function() {
        var csrf_test_name = $('#csrf_token_name').val();
        var csrf_token_hash = $('#csrf_token_hash').val();
        var admin_user_id = $('#admin_user_id').val();
       
        if (!$(this).is(':checked')) {
            let url = localurl+"UserManagement/menuWiseCompanyPermission";
            let postData = {"type_check":"company",csrf_test_name : csrf_token_hash,'admin_user_id' :admin_user_id };
            let successCb = function(response) {
                if(response != '') {
                    if(response.msg == "fetched_success") {
                        if (confirm('Your assigned product should be deleted. Are you sure you want to proceed ?')) {
                            $.ajax({
                                url      :localurl+'UserManagement/updateCompanyandProductPermission',
                                type     : "post",
                                data     :{'user_id':admin_user_id, csrf_test_name : csrf_token_hash,'status':"0" },
                                dataType :"json",
                                success :function(response){
                                    console.log(response.msg); 
                                    if(response.msg == 'not_updated'){
                                        location.reload();
                                    }
                                },
                            });
                        } else {
                            $.ajax({
                                url      :localurl+'UserManagement/updateCompanyandProductPermission',
                                type     : "post",
                                data     :{'user_id':admin_user_id, csrf_test_name : csrf_token_hash,'status':"1"  },
                                dataType :"json",
                                success :function(response){
                                    console.log(response.msg); 
                                    if(response.msg == 'not_updated'){
                                        location.reload();
                                    }
                                },
                            });
                        }
                    } else {
                        alert('You have specific supplier permissions, but not assigned any company.');
                    }
                }
            };
            let errorCb = function(error) {
                alert("Something went wrong. Please try again later");
            };

            ajaxCallJson(postData,url,successCb,errorCb);
        }
    });

     $('#parent_24').on('change', function() {
        var csrf_test_name = $('#csrf_token_name').val();
        var csrf_token_hash = $('#csrf_token_hash').val();
        var admin_user_id = $('#admin_user_id').val();

        if (!$(this).is(':checked')) {
             let url = localurl+"UserManagement/menuWiseCompanyPermission";
            let postData = {"type_check":"product",csrf_test_name : csrf_token_hash,'admin_user_id' :admin_user_id };
            let successCb = function(response) {
                if(response != '') {
                    if(response.msg == "fetched_success") {
                       // alert('Your assigned product should be deleted. Are you sure you want to proceed?');
                        if (confirm('Your assigned product should be deleted. Are you sure you want to proceed?')) {
                            $.ajax({
                                url      :localurl+'UserManagement/updateProductPermission',
                                type     : "post",
                                data     :{'user_id':admin_user_id, csrf_test_name : csrf_token_hash,'status':"0" },
                                dataType :"json",
                                success :function(response){
                                    console.log(response.msg); 
                                    if(response.msg == 'not_updated'){
                                        location.reload();
                                    }
                                },
                            });
                        }else{
                            $.ajax({
                                url      :localurl+'UserManagement/menuWiseCompanyPermission',
                                type     : "post",
                                data     :{'user_id':admin_user_id, csrf_test_name : csrf_token_hash,'status':"1"  },
                                dataType :"json",
                                success :function(response){
                                    console.log(response.msg); 
                                    if(response.msg == 'not_updated'){
                                        location.reload();
                                    }
                                },
                            });
                        }
                    } else {
                        alert('You have product permissions, but not assigned any product.');
                    }
                }
            };
            let errorCb = function(error) {
                alert("Something went wrong. Please try again later");
            };

            ajaxCallJson(postData,url,successCb,errorCb);
        }
    });

    if(($('#parent_19').is(':checked')) && $('#parent_24').is(':checked')){ 
            $('.option_19').show();
            $('.all_1_vendor').show(); 
            $('.sp_vendor_1').show(); 
            $('.sp_view_19').show(); 
            $('.default_view_24').show();
        }else if($('#parent_24').is(':checked')){ 
            $('.option_19').hide();
            $('.all_1_vendor').hide(); 
            $('.sp_vendor_1').hide(); 
            $('.sp_view_19').hide();
            $('.default_view_24').show();
        }else if($('#parent_19').is(':checked')){ 
            $('.option_19').show();
            $('.all_1_vendor').show(); 
            $('.sp_vendor_1').show(); 
            $('.sp_view_19').show(); 
            $('.default_view_24').hide();
        }else{
            $('.option_19').hide();
            $('.all_1_vendor').hide(); 
            $('.sp_vendor_1').hide(); 
            $('.sp_view_19').hide(); 
            $('.default_view_24').hide();
 	    $('#sp_10').prop("checked", false); 
            $('#all_1').prop("checked", false);  
        }


    $('.slectOne').on('change', function() {
       $('.slectOne').not(this).prop('checked', false);
    });

    $('.select_all').on('click',function(e){
        var userId  = $('#admin_userId').val();
        if(confirm("Are you sure, you want to give permission all companies? ")){
            window.location.href = localurl+"userManagement/allCompanyPermission/"+btoa(userId);
        }
    });


    $("#search_company").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#company_tbl tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    var allChecked = true;
    $("input.company_ids").each(function(index, element){
      if(!element.checked){
        allChecked = false;
        return false;
      } else{
        $('.checkAll').prop('checked', this.checked);
      }
    });

   /* $(".checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
*/

    $('.checkAll').on('click',function(){
        var doCheck = this.checked;
        $('.company_ids:visible').each(function(){
             this.checked = doCheck;
        });
    });

    $('#min, #max').keyup(function() {
        var min = parseInt( $('#min').val(), 10 );
        var max = parseInt( $('#max').val(), 10 );
       $('#company_tbl tbody tr').each(function() {
        var sl_no_id = parseFloat( $('td:eq(1)', this).text() ) || 0; 
            if (( isNaN( min ) && isNaN( max )) ||
                 ( isNaN( min ) && age <= max ) ||
                 ( min <= sl_no_id   && isNaN( max )) ||
                 ( min <= sl_no_id   && sl_no_id <= max )) {
                $(this).show()
            } else {
                $(this).hide()
            }   
        })
    });

});

$(document).on('click', '#clear-filter', function(){       
    $('input[data-type="search"]').val('');
    $('input[data-type="search"]').trigger("keyup");
});

$('#all_user_list,#inactive_user_list,#active_user_list').on('click','.change_status',function(e){
    let row = $($(this)).closest('tr');
    let data = $("#all_user_list,#inactive_user_list,#active_user_list").dataTable().fnGetData(row);
    let url = localurl+"UserManagement/updateStatus";
    let postData = {"id":btoa(data.id)};
    
    let successCb = function(response) {
        if(response != '') {
            if(response.status) {
                window.location.href=localurl+"userManagement/user-list";
            } else {
                alert(response.message);
            }
        }
        window.location.href=localurl+"userManagement/user-list";
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

$('#all_user_list,#inactive_user_list,#active_user_list').on('click','.edit_info',function(e){
    let row = $($(this)).closest('tr'); 
    let data = $("#all_user_list,#inactive_user_list,#active_user_list").dataTable().fnGetData(row);
    window.location.href=localurl+"UserManagement/editUser/"+btoa(data.id);
});

$('#all_user_list,#inactive_user_list,#active_user_list').on('click','.delete_info',function(e){
    let row = $($(this)).closest('tr');
    let data = $("#all_user_list,#inactive_user_list,#active_user_list").dataTable().fnGetData(row);
    let url = localurl+"UserManagement/deleteUserDetails";
    let postData = {"id":btoa(data.id)};
    let successCb = function(response) {
        if(response != '') {
            if(response.status) {
                window.location.href=localurl+"userManagement/user-list";
            } else {
                alert(response.message);
            }
        }
        window.location.href=localurl+"userManagement/user-list";
    };
    let errorCb = function(error) {
        console.log(error);
        alert("Something went wrong. Please try again later");
    };

    if(confirm('Are you sure, you want to delete the details?')) {
        ajaxCallJson(postData,url,successCb,errorCb);
    } else {
        return false;
    }
});



$('#all_user_list,#inactive_user_list,#active_user_list').on('click','.assign_company',function(e){
    let row = $($(this)).closest('tr');
    let data = $("#all_user_list,#inactive_user_list,#active_user_list").dataTable().fnGetData(row);
    let url = localurl+"UserManagement/getCompanyListByUserId";
    let postData = {"id":btoa(data.id)};
    let successCb = function(response) {
        if(response != '') {
            if(response.status) {
                $('#assignedCompany_modal').modal("show");
                var html = '';
                for (var i = 0; i < response.data.length; i++) {
                    var sr_no = i+1;
                    html += '<tr>';
                        html+= '<td>'+sr_no+'</td>';
                            html+= '<td>'+response.data[i].company_id+'</td>';
                                html += '<td class="align-middle">'+response.data[i].company_name+'</td>';
                    html += '</tr>';
                }
                $('.dynamicCompanyData').html(html);
            } else {
                alert(response.message);
            }
        }
    };
    let errorCb = function(error) {
        alert("Something went wrong. Please try again later");
    };
    ajaxCallJson(postData,url,successCb,errorCb);

});

$('.sp_view_19').click(function(){
    if ($('#sp_view_19').is(':checked')){
        $('#sp_view_19').val(1);
    }else{
       $('#sp_view_19').val(0);
    }
});

$('#sp_view_24').click(function(){
    if ($('#sp_view_24').is(':checked')){
        $('#sp_view_24').val(1);
    }else{
       $('#sp_view_24').val(0);
    }
});

if ($('#sp_view_19').is(':checked')){
        $('#sp_view_19').val(1);
    }else{
       $('#sp_view_19').val(0);
    }

if ($('#sp_view_24').is(':checked')){
        $('#sp_view_24').val(1);
    }else{
       $('#sp_view_24').val(0);
    }

$(':checkbox').change(function () {
    var option = 'content_option_' + $(this).attr('id');
    if ($('.' + option).css('display') == 'none') {
        $('.' + option).show();
    } else {
        $('.' + option).hide();
    }
});


$(".closeCard").click(function () {
    $(this).parent().hide();
});

if($('.parent_menu_19').is(':checked')){
    $('.content_option_19').show();
} else {
    $('.content_option_19').hide();
}

if($('.parent_menu_64').is(':checked')){
    $('.content_option_64').show();
} else {
    $('.content_option_64').hide();
}

if($('.parent_menu_24').is(':checked')){
    $('.content_option_24').show();
} else {
    $('.content_option_24').hide();
}

if($('#view_19').is(':checked')){
    $('#view_19').val(1);
} else {
   $('#view_19').val(0);
}

$('#view_19').click(function(){
    if($('#view_19').is(':checked')){
        $('#view_19').val(1);
    } else {
       $('#view_19').val(0);
    }
});

if($('#view_24').is(':checked')){
    $('#view_24').val(1);
} else {
   $('#view_24').val(0);
}

$('#view_24').click(function(){
    if($('#view_24').is(':checked')){
        $('#view_24').val(1);
    } else {
       $('#view_24').val(0);
    }
});


if($('#view_64').is(':checked')){
    $('#view_64').val(1);
} else {
   $('#view_64').val(0);
}

$('#view_64').click(function(){
    if($('#view_64').is(':checked')){
        $('#view_64').val(1);
    } else {
       $('#view_64').val(0);
    }
});

$('#active_user_list').on('click','.product_info',function(e){
    let row = $($(this)).closest('tr'); 
    let data = $("#active_user_list").dataTable().fnGetData(row);
    let url = localurl+"UserManagement/checkMenuPermission";
    let postData = {"user_id":data.id };
        let successCb = function(response) {
            if(response != '') {
              if(response.status == 'success'){
                    window.location.href = localurl+"userManagement/assignProductlist/"+btoa(data.id);
                }else{
                    alert('You do not have permissions to the product menu.');
                }
            }
        };
    let errorCb = function(error) {
        alert("Something went wrong. Please try again later");
    };
    ajaxCallJson(postData, url, successCb, errorCb);
});


// Object to store checked checkboxes
var selectedCheckboxes = {};

// Event handler for checkboxes
$(document).on('change', '#supplier_product_details .assignProduct input[type="checkbox"]', function(e) {
    let product_id = $(this).closest('tr').find('td:eq(1)').text(); // Assuming product_id is in the second column

    if ($(this).is(":checked")) {
        // If checkbox is checked, add it to selectedCheckboxes object
        selectedCheckboxes[product_id] = true;
    } else {
        // If checkbox is unchecked, remove it from selectedCheckboxes object
        delete selectedCheckboxes[product_id];
    }

    // Update the input field with the selected checkboxes
    updateSelectedIdsInput();
});

// Function to update the input field with the selected checkboxes
function updateSelectedIdsInput() {
    // Get an array of keys from selectedCheckboxes object
    let selectedIdsArray = Object.keys(selectedCheckboxes);
    // Set the value of the input field to the joined string
    $('#selectedIds').val(selectedIdsArray.join(','));
    console.log(selectedIdsArray); // Output the array of selected product_id values
}


$(document).on('click', '.submit_productData', function(e) {
    let product_id  = $('#selectedIds').val();
    if(product_id != ''){
        let url = localurl+"UserManagement/assignProductToUser";
        let postData = {"product_id":product_id };
        let successCb = function(response) {
            if(response != '') {
               if(response.msg == 'added_success'){
                    myFunction(); // Call the function to show the popup
                    setTimeout(() => {
                        hidePopup(); // Hide the popup after 2 seconds
                    }, 1500);

                }
            }
        };
        let errorCb = function(error) {
            alert("Something went wrong. Please try again later");
        };
        ajaxCallJson(postData, url, successCb, errorCb);
    }else{
        alert("Something went wrong.Please Select atleast one.");
    }
});

function myFunction() {
    var popup = document.getElementById("myPopup");
    popup.classList.toggle("show");
} 

function hidePopup() {
    var popup = document.getElementById("myPopup");
    popup.classList.remove("show"); // Hide the popup
    setTimeout(() => {
        location.reload(); // Reload the page after hiding the popup
    }, 500); // You may adjust the delay before reloading the page (in milliseco
}

/*################## SHOWING DATA IN PRODUCT ########################*/

$(document).ready(function() {
    // Event listener for "Check All" checkbox
    $(document).on('change', '#selected_product_ids', function() {
        var isChecked = $(this).prop('checked');
        $('#dynamicTable').DataTable().$('input[type="checkbox"]').prop('checked', isChecked).trigger('change');
    });

    // Event listener for individual checkboxes
    $(document).on('change', '.assigned_Product input[type="checkbox"]', function() {
        updateCheckAllCheckbox();
    });

    // Function to update the state of the "Check All" checkbox
    function updateCheckAllCheckbox() {
        var allChecked = true;
        $('.assigned_Product input[type="checkbox"]').each(function() {
            if (!$(this).prop('checked')) {
                allChecked = false;
                return false; // Exit the loop early if any checkbox is unchecked
            }
        });
        $('#selected_product_ids').prop('checked', allChecked);
    }
});


$(document).on('click','#user_assigned_product_list', function(e) {
    var userId = $('.user_id').val();
    let url = localurl+"UserManagement/assignedProductListByUser";
    let postData = {"user_id":userId };
    let successCb = function(response) {
        populateDataTable(response.data); // Populate DataTable with dynamic data
        $('.assigned_product_list').modal('show'); // Show the modal
    };
    let errorCb = function(error) {
        alert("Something went wrong. Please try again later");
    };
    ajaxCallJson(postData, url, successCb, errorCb);
});

$(document).on('click','.user_assigned_product_list', function(e) {
    let row = $($(this)).closest('tr');
    let data = $("#active_user_list").dataTable().fnGetData(row);
    let url = localurl+"UserManagement/assignedProductListByUser";
    let postData = {"user_id":btoa(data.id) };
    let successCb = function(response) {
        populateDataTable(response.data); // Populate DataTable with dynamic data
        $('.user_name').html('<b>'+data.name+'(Id: '+data.id+')</b>');
        $('.assigned_product_list').modal('show'); // Show the modal
    };
    let errorCb = function(error) {
        alert("Something went wrong. Please try again later");
    };
    ajaxCallJson(postData, url, successCb, errorCb);
});


function populateDataTable(data) {
  var table = $('#dynamicTable').DataTable();
  table.clear().draw();
  $.each(data, function(index, item) {
    var deleteIcon = '<a class=\"assign_pro_delete\" href=\"javascript:void(0)\" title=\"Delete Assigned Product\" data-toggle=\"modal\" style=\"color: red;\" data-productId ="'+item.product_id+'" ><i class=\"fa fa-trash\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a>';
    var checkbox = '<span class="assigned_Product"><input type="checkbox" id="product_' + item.product_id + '" name="product_id[]" value="' + item.product_id + '"/></span>';
      table.row.add([
      checkbox,
      item.product_id,
      item.product_name,
      item.company_name,
      item.created_at,
      deleteIcon,
    ]).draw(false);
  });
}

/*####################### END ##########################*/

  // Add click event handler for delete icon  DELETE DATA IN MODAL//////
$('#dynamicTable').on('click', '.assign_pro_delete', function() {
    var row = $(this).closest('tr');
    var productId = $(this).attr('data-productId');
    let url = localurl+"UserManagement/deleteAssignedProductByuser";
    let postData = {"product_id":productId };
    let successCb = function(response) {
       if(response.msg == "delete_data"){
            showdelete_popup(); // Call the function to show the popup
                setTimeout(() => {
                    hidedelete_Popup(); // Hide the popup after 2 seconds
            }, 2000);

            row.fadeOut(400, function() {
              $(this).remove();
            });
       }
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

function showdelete_popup() {
    var popup = document.getElementById("deleted_popup");
    popup.classList.toggle("show");
} 

function hidedelete_Popup() {
    var popup = document.getElementById("deleted_popup");
    popup.classList.remove("show"); // Hide the popup
}

// ======= End Of Section=====///

// Click event handler for "Delete All" button

$('#deleteAllButton').click(function() {
    var selectedID = [];
    $('#dynamicTable').DataTable().$('input:checkbox:checked').each(function () {
        selectedID.push($(this).val());
    });
    
    
    if(selectedID.length > 0){
        let url = localurl+"UserManagement/deleteAllData";
        let postData = {"product_ids":selectedID };
        let successCb = function(response) {
            showdelete_popup(); // Call the function to show the popup
                setTimeout(() => {
                    hidedelete_Popup(); // Hide the popup after 2 seconds
            }, 2000);
            $('.assigned_product_list').modal('hide');

        };
        let errorCb = function(error) {
            alert("Something went wrong. Please try again later");
        };
     
        if(confirm('Are you sure, you want to delete all the data?')) {
            ajaxCallJson(postData,url,successCb,errorCb);
        } else {
            return false;
        }
    }else{
         alert("Something went wrong. Please select atleast one data");
    }
    location.reload();

});


/* ************** START CSV SECTION *****************/
$(document).on('click','.download_sample_csv', function(e) {
    event.preventDefault(); // Prevent the default behavior of the anchor tag

    let url = localurl + "UserManagement/sampleCsvDownload";
    downloadFile(url);
});

function downloadFile(url) {
    $.ajax({
        url: url,
        method: 'GET',
        xhrFields: {
            responseType: 'blob' // Set the response type to blob
        },
        success: function(data) {
            // Create a blob URL for the downloaded file
            var blob = new Blob([data]);
            var blobUrl = window.URL.createObjectURL(blob);

            // Create a temporary anchor element and trigger the download
            var a = document.createElement('a');
            a.href = blobUrl;
            a.download = 'assign_product_to_user_sample_file.csv';
            document.body.appendChild(a);
            a.click();

            // Clean up
            window.URL.revokeObjectURL(blobUrl);
            $(a).remove();
        },
        error: function(xhr, status, error) {
            if (xhr.status == 404) {
                var text = "Something went wrong . Sample file not exist our system";
                $('#alert-msgs').html("<small class='alert alert-danger' style='margin-left: 270px;margin-top: 15px;font-weight: 500;'>"+text+"</small>");
                setTimeout(() => {
                    hide_erro_msg();
                }, 1500);// Display an alert if the file is not found
            } else {
                console.error(error); // Log any other errors to the console for debugging
            }
        }
    });
}


function hide_erro_msg() {
    var popup = document.getElementById("alert-msgs");
    $('#alert-msgs').hide(); // Hide the popup
}





  