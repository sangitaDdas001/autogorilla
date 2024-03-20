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



/*	Edit Book details	*/
$('#about_details').on('click','.edit_info',function(e){
	let row = $($(this)).closest('tr'); 
    let data = $("#about_details").dataTable().fnGetData(row);
	window.location.href=localurl+"aboutUs/editAboutUs/"+btoa(data.id);
});
	/*** More Info bookDetails ****/
$('#about_details').on('click','.more_info',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#about_details").dataTable().fnGetData(row);
    window.location.href=localurl+"aboutUs/aboutInfo/"+btoa(data.id);
});

$('#about_details').on('click','.change_status',function(e){
	let row = $($(this)).closest('tr');
    let data = $("#about_details").dataTable().fnGetData(row);
    let url = localurl+"aboutUs/updateAboutStatus";
	let postData = {"about_id":btoa(data.id)};
	let successCb = function(response) {
		if(response != '') {
			if(response.status) {
				window.location.href=localurl+"aboutUs/viewAboutUs";
			} else {
				alert(response.message);
			}
		}
		window.location.href=localurl+"aboutUs/viewAboutUs";
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

if ($('#content_edit').length>0) {
	   tinymce.init({
            selector: '#content_2,#content_edit,#our_vision_details,#our_miission_details',
            height : "230",
            toolbar: "forecolor backcolor",
            color_cols: "1",
            plugins: 'charmap code lists image paste fullscreen imagetools preview table',
            toolbar: ['code preview image removeformat | bold italic underline | subscript superscript charmap | alignleft aligncenter alignright outdent indent | bullist numlist  selectall fullscreen',],
            images_upload_url: localurl+'aboutUs/uploadImg',
            file_picker_types: 'image',
            branding: false,
            contextmenu: "link image imagetools table spellchecker",
            quickbars_selection_toolbar: 'bold italic | quicklink h1 h2 h3',
            toolbar_drawer: 'sliding',
            menubar : false,
            relative_urls :false,
            remote_scripts_host:false,
            convert_urls: true,

           file_picker_callback: function (callback, value, meta) {
            if (meta.filetype == 'image') {
                var input = document.getElementById('my-file');
                input.click();
                input.onchange = function () {
                    var file = input.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        callback(e.target.result, {
                            alt: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };
            }

            if (meta.filetype == 'media') {
                var input = document.getElementById('my-file');
                input.click();
                input.onchange = function () {
                    var file = input.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {

                        callback(e.target.result, {
                            alt: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };
            }
        },

          automatic_uploads: false,
          media_alt_source: false
    });
}

