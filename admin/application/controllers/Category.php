<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model('CategoryModel');
	}

	public function csvDownload() {
		$filename = 'parentCategory_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 

		// get data
		$usersData = $this->CategoryModel->exportPCategoryDetails();

		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","Category Name");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;

	}

	public function SubcsvDownload() {
		$filename = 'subCategory_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 

		// get data
		$usersData = $this->CategoryModel->exportSubCategoryDetails();

		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","Category Name");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;

	}

	public function microCsvDownload() {
		$filename = 'microCategory_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 

		// get data
		$usersData = $this->CategoryModel->exportMicroCategoryDetails();

		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","Category Name");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;
	}



	public function addCategory() {
		$submit = $this->input->post();
		if(!empty($submit)) {
			$data = array();
			$data = $submit;
			/*try {
				$data = validatePostData($submit);
			} catch (Exception $e) {
				$this->session->set_flashdata('cat_error','Invalid data');
				redirect('category/addCategory');
			}*/

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('category_name','Category name', 'trim|required|callback_checkCategoryName',array('checkCategoryName' => 'Category name is already exist,Please try again.'));
			$this->form_validation->set_rules('url_slug', 'Url', 'trim|required|callback_checkUrlSlug',array('checkUrlSlug' => 'This url is already exist,Please try again.'));
			$this->form_validation->set_rules('category_content', 'Category content', 'trim|required');
			$this->form_validation->set_rules('footer_content', 'Footer content', 'trim');
			$this->form_validation->set_rules('related_category', 'Related category', 'trim');
			$this->form_validation->set_rules('parent_cat_id', 'Parent Category', 'trim');

			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('cat_error',validation_errors());
				redirect('category/addCategory');
	        }
	        if(!empty($_FILES['files']['name'])) {
				$upload_folder = 'categories';
		        if (!file_exists(UPLOAD_IMAGE_URL) && !is_dir(UPLOAD_IMAGE_URL)) { 
		            mkdir(UPLOAD_IMAGE_URL, 0777, true);
		        }

		        // check uploaded path folder is present or not other wise create it 
		        if (!empty($upload_folder) && (!file_exists(UPLOAD_IMAGE_URL.$upload_folder))) {
					mkdir(UPLOAD_IMAGE_URL.$upload_folder, 0777, true);
				}

				$config['upload_path'] 	= 	UPLOAD_IMAGE_URL.$upload_folder;
				$config['allowed_types']= 	'jpg|png|jpeg';
				$config['max_size']		=	'5120';
				$config['file_name']	=	strtolower($upload_folder).'_'.rand(10,99999).time();
				
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('files')){
					
					$errors = $this->upload->display_errors();
					$this->session->set_flashdata('cat_error',$errors);
					redirect('category/addCategory');
				}
				$data['category_image'] = $this->upload->data('orig_name');	
	        }
	        $data['related_category'] = 0;
	        $data['parent_cat_id']    = 0;
	        
          	unset($data['meta_title']);
          	unset($data['canonical_url']);
          	unset($data['meta_description']);
          	unset($data['meta_name']);
          	unset($data['meta_content']);
          	unset($data['social_meta_og_property']);
          	unset($data['social_meta_og_content']);
          	unset($data['submit']);
          	
			$insert =	$this->CategoryModel->insert('auto_category',$data);
			if($insert){
			    $num = str_pad(mt_rand($insert,99999999),8,'0',STR_PAD_LEFT);
				$new_url_slug = $submit['url_slug'].'-'.$num;
				$updateArr = 
					array('url_slug' => $new_url_slug, 
				);
				$update= $this->CategoryModel->update('auto_category',$updateArr,array('id'=>$insert));
				
				$seo_arr = array(
                        'meta_title' 			=> $submit['meta_title'],
                        'meta_description' 		=> $submit['meta_description'],
                        'canonical_url' 		=> $submit['canonical_url'].'-'.$num,
                        'page_name' 			=> 'parent category',
                        'page_url' 				=> $new_url_slug,
	            	); 

				$insert_seo_m =	$this->CategoryModel->insert('seo_meta_info',$seo_arr);
				if(isset($submit['meta_name'][0]) && trim($submit['meta_name'][0]) !=''){ 
					foreach ($submit['meta_name'] as $key => $value) {
						$finalArr[] = array(
	                        'meta_name' 	=> $value,
	                        'meta_content' 	=> $submit['meta_content'][$key],
	                        'page_name' 	=> 'parent category',
	                        'page_url' 		=> $new_url_slug,
	                        'seo_meta_id' 	=> $insert_seo_m,
		            	); 
					}

					$insert_meta = $this->db->insert_batch('seo_multiple_meta_info', $finalArr);
				}
				if(isset($submit['social_meta_og_property'][0]) && trim($submit['social_meta_og_property'][0]) !=''){
					foreach ($submit['social_meta_og_property'] as $key1 => $og_value) {
						$finalArr2[] = array(
							'social_meta_og_property' 	=> $og_value,
	                    	'social_meta_og_content' 	=> $submit['social_meta_og_content'][$key1],
	                    	'page_name' 				=> 'parent category',
	                        'page_url' 					=> $new_url_slug,
	                        'seo_meta_id' 				=> $insert_seo_m,
	                    );    
					}
					
					$insert_meta_og = $this->db->insert_batch('seo_social_og_info', $finalArr2);
				}
			}
			if($insert) {
				$this->session->set_flashdata('cat_success','Category added successfully');
				redirect('category/viewCategory');
			} else {
				$this->session->set_flashdata('cat_error','Failed to add data');
				redirect('category/addCategory');
			}
		}
		$data["page_title"] 	= "Add Category Information";
        $page["layout_content"] = $this->load->view('pages/category/addCategory', $data, true);
        $page["script_files"]	= $this->load->view('scripts/category/category',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function viewCategory(){
		$catFormat 		= 	$this->catNameDetailsFormat();
		$data['columns']	=	$catFormat['cat_column'];
		$data['catData']	=	$catFormat['category_name'];
		$data["page_title"] 	= "View Category Information";
        $page["layout_content"] = $this->load->view('pages/category/viewCategory', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/category/category',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function catNameDetailsFormat() {
		$retData['cat_column'] = array("Category Name","Image","Created Date", "Status","Action");
		$path = VIEW_IMAGE_URL.'categories/';
		$no_image = VIEW_IMAGE_URL.'noimage.png';
        $retData['category_name'] = "[
        	{ 'data' : 'category_name' },
        	{
        		'render': function (data, type, row, meta) {
        			let imgPath = row.category_image;
        			let html = '';
        			if(imgPath) {
        				html += '<img src=';
	        			html += '".$path."';
	        			html += imgPath;
	        			html += ' class=\"img-thumbnail\" width=\"100\" height=\"100\" />';
        			} else {
        				html += '<img src=';
	        			html += '".$no_image."';
	        			html += ' class=\"img-thumbnail\" width=\"100\" height=\"100\" />';
        			}
                    return html;
                }
        	},
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\">Active</span>';
        			} else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Inactive</span>';
        			}
        		}
        	},
        	{ 'defaultContent' : '<div class=\"dropdown\"><a class=\"btn btn-secondary dropdown-toggle\" href=\"#\" role=\"button\" id=\"dropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fs-14 fa fa-bars\"></i></a><ul class=\"dropdown-menu dropdown-menu-right\" aria-labelledby=\"about-us\"><li style=\"text-align: center;\"><a class=\"dropdown-item edit_info\" href=\"javascript:void(0)\" data-toggle=\"modal\" style=\"color: blue;\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;\" aria-hidden=\"true\"></i>Edit</a></li><li style=\"text-align: center;\"><a class=\"dropdown-item delete_info\" href=\"javascript:void(0)\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;\" aria-hidden=\"true\"></i>Delete</a></li></ul></div>'
        	}
    	]";

        return $retData;
	}

	public function categoryNameDetails_ajax() {
		$response = array();
		$response['draw'] = 1;
		$response['recordsTotal'] = 0;
		$response['recordsFiltered'] = 0;
		$response['data'] = array();
		try {
			$data = $this->input->post();
			if(empty($data)) {
				echo json_encode($response); die;
			} else {
				$search = !empty($this->input->post('search'))? $this->input->post('search')['value'] : '';
				$limit  = (!empty($data['length'])?$data['length']:10);
				$offset = (!empty($data['start'])?$data['start']:0);
   				$result = $this->CategoryModel->getAllcatDetails($limit,$offset,$search);
   				$response['draw'] = (!empty($data['draw'])?$data['draw']:1);
				$response['recordsTotal'] = (!empty($result['total_count'])?$result['total_count']:0);
				$response['recordsFiltered'] = (!empty($result['total_count'])?$result['total_count']:0);
				$response['data'] = (!empty($result['fetch_data'])?$result['fetch_data']:array());
				echo json_encode($response); die;
			}
		} catch (Exception $e) {
			echo json_encode($response); die;
		}
	}

	public function editCat() {
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('Category/viewCategory');
		}
		$res = $this->CategoryModel->getCatDetailsById($id);
		$data['fetch_data'] 	= 	$res['fetch_data'][0];
		$data['cat_id'] 		= 	$id;
		$data["page_title"]		=	"Edit Category Details";
        $page["layout_content"]	=	$this->load->view('pages/category/editCat', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/category/category',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function updateCategory() {
		$postdata = $this->input->post();
		$data     = $postdata;
		//$data = validatePostData($data);
		$id = !empty($data['cat_id'])?$data['cat_id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('cat_error',"Failed to update");	
			redirect('Category/editCat/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('user_error',"Failed to update");	
			redirect('Category/editCat/'.$id);
		}			
		$this->form_validation->set_data($data);
        $this->form_validation->set_rules('category_name', 'Category Name','trim|required');
      
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('cat_error',validation_errors());
            redirect('Category/editCat/'.$id);
        }

    	if(!empty($_FILES['files']['name'])) {

			$upload_folder = 'categories';
	        if (!file_exists(UPLOAD_IMAGE_URL) && !is_dir(UPLOAD_IMAGE_URL)) { 
	            mkdir(UPLOAD_IMAGE_URL, 0777, true);
	        }

	        // check uploaded path folder is present or not other wise create it 
	        if (!empty($upload_folder) && (!file_exists(UPLOAD_IMAGE_URL.$upload_folder))) {
				mkdir(UPLOAD_IMAGE_URL.$upload_folder, 0777, true);
			}

			$config['upload_path'] 	= 	UPLOAD_IMAGE_URL.$upload_folder;
			$config['allowed_types']= 	'jpg|png|jpeg';
			$config['max_size']		=	'5120';
			$config['file_name']	=	strtolower($upload_folder).'_'.rand(10,99999).time();
			
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('files')){
				
				$errors = $this->upload->display_errors();
				$this->session->set_flashdata('book_error',$errors);
				redirect('bookList/editBookList/'.$id);
			}
			$data['category_image'] = $this->upload->data('orig_name');	
        }
      
        unset($data['meta_name']);
      	unset($data['meta_content']);
      	unset($data['social_meta_og_property']);
      	unset($data['social_meta_og_content']);
      	unset($data['meta_title']);
      	unset($data['canonical_url']);
      	unset($data['meta_description']);
        unset($data['cat_id']);
        unset($data['submit']);
        $data['modified_at'] = date('Y-m-d H:i:s');
        
     	$response               = $this->CategoryModel->updateCatDetails($data,$id);
     	
     	$check_seo_data 		= seo_meta_information('parent category',$postdata['url_slug']);
     	$seo_multi_meta_info 	= seo_multi_meta_information('parent category',$postdata['url_slug']); 
   		$seo_social_og_info  	= seo_social_ogInfo('parent category',$postdata['url_slug']); 


     	if(!empty($check_seo_data)){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_meta_info',array('page_name'=>'parent category','page_url'=>$postdata['url_slug']));
     	}

     	if(!empty($seo_multi_meta_info) && empty($postdata['meta_name'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_multiple_meta_info',array('page_name'=>'parent category','page_url'=>$postdata['url_slug']));

     	}else if(!empty($seo_multi_meta_info)){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_multiple_meta_info',array('page_name'=>'parent category','page_url'=>$postdata['url_slug']));
     	}else if(empty($postdata['meta_name'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_multiple_meta_info',array('page_name'=>'parent category','page_url'=>$postdata['url_slug']));
     	}

     	if(!empty($seo_social_og_info) && empty($postdata['social_meta_og_property'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_social_og_info',array('page_name'=>'parent category','page_url'=>$postdata['url_slug']));
     	}else if(!empty($seo_social_og_info)){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_social_og_info',array('page_name'=>'parent category','page_url'=>$postdata['url_slug']));
     	}else if(empty($postdata['social_meta_og_property'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_social_og_info',array('page_name'=>'parent category','page_url'=>$postdata['url_slug']));
     	}

 			$seo_arr = array(
                'meta_title' 			=> $postdata['meta_title'],
                'meta_description' 		=> $postdata['meta_description'],
                'canonical_url' 		=> $postdata['canonical_url'],
                'page_name' 			=> 'parent category',
                'page_url' 				=> $postdata['url_slug'],
            ); 

		$insert =	$this->CategoryModel->insert('seo_meta_info',$seo_arr);

		if(isset($postdata['meta_name'][0]) && trim($postdata['meta_name'][0]) !=''){
	     	foreach ($postdata['meta_name'] as $key => $value) {
				$finalArr[] = array(
	                'meta_name' 	=> $value,
	                'meta_content' 	=> $postdata['meta_content'][$key],
	                'page_name' 	=> 'parent category',
	                'page_url' 		=> $postdata['url_slug'],
	                'seo_meta_id' 	=> $insert,
	        	); 
			}
			$insert_meta = $this->db->insert_batch('seo_multiple_meta_info', $finalArr);
		}

		if(isset($postdata['social_meta_og_property'][0]) && $postdata['social_meta_og_property'][0] !=''){
			foreach ($postdata['social_meta_og_property'] as $key1 => $og_value) {
				$finalArr2[] = array(
					'social_meta_og_property' 	=> $og_value,
	            	'social_meta_og_content' 	=> $postdata['social_meta_og_content'][$key1],
	            	'page_name' 				=> 'parent category',
	                'page_url' 					=> $postdata['url_slug'],
	                'seo_meta_id' 				=> $insert,
	            );    
			}
			$insert_meta_og = $this->db->insert_batch('seo_social_og_info', $finalArr2);	
     	}

		if($response) {
			$this->session->set_flashdata('cat_success','Update Successfull');
			redirect('category/viewCategory');
		} else {
			$this->session->set_flashdata('cat_error','Failed to update. Please try again');
			redirect('category/editCat/'.$id);
		}
	}

	/*	delete Category Details 	*/
	public function deleteCatDetails() {

		$postData = $this->input->post();
		$id = !empty($postData['cat_id'])?$postData['cat_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->CategoryModel->deleteCatDetails($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
		
	}

	/*	delete mCategory Details 	*/
	public function deleteMicroCatDetails() {

		$postData = $this->input->post();
		$id = !empty($postData['cat_id'])?$postData['cat_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->CategoryModel->deletemCatDetails($postData);
		//echo $this->db->last_query();
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
		
	}

	/*	Change Category Status 	*/
	public function updateCatStatus() {
		$postData = $this->input->post();
		$id = !empty($postData['cat_id'])?$postData['cat_id']:'';
		$get_url = $this->CategoryModel->getCatDetailsById($id);
		$check_data_exist = $this->CategoryModel->fetchSeoDataByUrl($get_url['fetch_data'][0]['url_slug']);
		

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->CategoryModel->changeCatStatus($postData);
			if ($response) {
				if($check_data_exist['fetch_data'][0]['page_name']){
					$response = $this->CategoryModel->changeSeoCatStatus($get_url['fetch_data'][0]['url_slug']);
				}
				$this->session->set_flashdata('cat_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function updateMicCatStatus() {
		$postData = $this->input->post();
		$id = !empty($postData['cat_id'])?$postData['cat_id']:'';
		$get_url = $this->CategoryModel->getCatDetailsById($id);
		$check_data_exist = $this->CategoryModel->fetchSeoDataByUrl($get_url['fetch_data'][0]['url_slug']);
		

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->CategoryModel->change_m_CatStatus($postData);
			if ($response) {
				if($check_data_exist['fetch_data'][0]['page_name']){
					$response = $this->CategoryModel->changeSeoCatStatus($get_url['fetch_data'][0]['url_slug']);
				}
				$this->session->set_flashdata('cat_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function checkCategoryName($categoryName){
		$checkData = $this->CategoryModel->check_Category_name(trim($categoryName));
		if(!empty($checkData)){
			return false;die;
		}else{
			return true;
		}
	}

	public function checkUrlSlug($url){
		$checkData = $this->CategoryModel->checkUrlSlug(trim($url));
		if(!empty($checkData)){
			return false;die;
		}else{
			return true;
		}
	}

	/***************************  SUB CATEGORY *****************************/

	public function addSubCategory(){
		$data = array();
		$data['fetch_parent_cat'] = $this->CategoryModel->getCategoryName();
		$submit = $this->input->post();
		
		if(!empty($submit)) {
			$data =  $submit;
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('parent_cat_id', 'Parent Category', 'trim|required');
			$this->form_validation->set_rules('related_category[]', 'Related Category name', 'trim');
			$this->form_validation->set_rules('category_name', 'Category name', 'trim|required|callback_checkCategoryName',array('checkCategoryName' => 'Category name is already exist,Please try again.'));
			$this->form_validation->set_rules('url_slug', 'Url Name', 'trim|required|callback_checkUrlSlug',array('checkUrlSlug' => 'This url is already exist,Please try again.'));
			$this->form_validation->set_rules('category_content', 'Category Content', 'trim');
			$this->form_validation->set_rules('footer_content', 'Footer Content', 'trim');

			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('cat_error',validation_errors());
				redirect('category/addSubCategory');
	        }
	        
	        if(!empty($_FILES['files']['name'])) {
				$upload_folder = 'categories';
		        if (!file_exists(UPLOAD_IMAGE_URL) && !is_dir(UPLOAD_IMAGE_URL)) { 
		            mkdir(UPLOAD_IMAGE_URL, 0777, true);
		        }

		        // check uploaded path folder is present or not other wise create it 
		        if (!empty($upload_folder) && (!file_exists(UPLOAD_IMAGE_URL.$upload_folder))) {
					mkdir(UPLOAD_IMAGE_URL.$upload_folder, 0777, true);
				}

				$config['upload_path'] 	= 	UPLOAD_IMAGE_URL.$upload_folder;
				$config['allowed_types']= 	'jpg|png|jpeg';
				$config['max_size']		=	'5120';
				$config['file_name']	=	strtolower($upload_folder).'_'.rand(10,99999).time();
				
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('files')){
					
					$errors = $this->upload->display_errors();
					$this->session->set_flashdata('cat_error',$errors);
					redirect('category/addSubCategory');
				}
				$data['category_image'] = $this->upload->data('orig_name');	
	        }

	       
	        if(!empty($data['related_category'])){
	        	
	        		$related_category = implode(',', $data['related_category']);
	        	}

				$finalArr = array(
					'parent_cat_id' 	=> $data['parent_cat_id'],
					'category_name' 	=> $data['category_name'],
					'related_category' 	=> $related_category,
					'url_host' 			=> SERVERNAME.'/auto_gorilla/',
					'url_slug' 			=> $new_url_slug,
					'cat_level'			=> 2,
					'category_content'	=> $data['category_content'],
					'footer_content'	=> $data['footer_content'],
					'category_image' 	=> $this->upload->data('orig_name')
				);
					
		        $insert =	$this->CategoryModel->insert('auto_category',$finalArr);
				if($insert) {
				    $num = str_pad(mt_rand($insert,99999999),8,'0',STR_PAD_LEFT);
					$new_url_slug = $data['url_slug'].'-'.$num;
					$updateArr = 
						array('url_slug' => $new_url_slug, 
					);
				$update= $this->CategoryModel->update('auto_category',$updateArr,array('id'=>$insert));
    				$seo_arr = array(
                            'meta_title' 			=> $data['meta_title'],
                            'meta_description' 		=> $data['meta_description'],
                            'canonical_url' 		=> $submit['canonical_url'].'-'.$num,
                            'page_name' 			=> 'sub category',
                            'page_url' 				=> $new_url_slug,
    	            	); 
    
    				$insert_seo =	$this->CategoryModel->insert('seo_meta_info',$seo_arr);
    				if(isset($data['meta_name'][0]) && trim($data['meta_name'][0]) !=''){
	    				foreach ($data['meta_name'] as $key => $value) {
	    					$finalArr_seo[] = array(
	                            'meta_name' 	=> $value,
	                            'meta_content' 	=> $data['meta_content'][$key],
	                            'page_name' 	=> 'sub category',
	                            'page_url' 		=> $new_url_slug,
	                            'seo_meta_id' 	=> $insert_seo,
	    	            	); 
	    				}
					    $insert_meta = $this->db->insert_batch('seo_multiple_meta_info', $finalArr_seo);
					}

					if(isset($data['social_meta_og_property'][0]) && trim($data['social_meta_og_property'][0]) !=''){
	    				foreach ($submit['social_meta_og_property'] as $key1 => $og_value) {
	    					$finalArr2[] = array(
	    						'social_meta_og_property' 	=> $og_value,
	                        	'social_meta_og_content' 	=> $submit['social_meta_og_content'][$key1],
	                        	'page_name' 				=> 'sub category',
	                            'page_url' 					=> $new_url_slug,
	                            'seo_meta_id' 				=> $insert_seo,
	                        );    
	    				}
					
						$insert_meta_og = $this->db->insert_batch('seo_social_og_info', $finalArr2);
					}
				}

			if($insert){
				$this->session->set_flashdata('cat_success','SubCategory added successfully');
				redirect('category/viewSubCategory');
			}else{
				$this->session->set_flashdata('cat_error','Failed to add data');
				redirect('category/addSubCategory');
			}
		}
		$data["page_title"] 	= "Add Sub Category Information";
        $page["layout_content"] = $this->load->view('pages/category/addSubCategory', $data, true);
        $page["script_files"]	= $this->load->view('scripts/category/category',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function viewSubCategory(){
		$catFormat 				= 	$this->subCatNameDetailsFormat();
		$data['columns']		=	$catFormat['cat_column'];
		$data['subCatData']		=	$catFormat['category_name'];
		$data["page_title"] 	= 	"View Sub Category Information";
        $page["layout_content"] = 	$this->load->view('pages/category/viewSubCategory', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/category/category',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function subCatNameDetailsFormat() {

		$retData['cat_column'] = array("Parent Category","Sub-Category Name","Image","Created Date", "Status","Action");
		$path = VIEW_IMAGE_URL.'categories/';
		$no_image = VIEW_IMAGE_URL.'noimage.png';
        $retData['category_name'] = "[
        	{ 'data' : 'parent_categories_name' },
        	{ 'data' : 'category_name' },
        	{
        		'render': function (data, type, row, meta) {
        			let imgPath = row.category_image;
        			let html = '';
        			if(imgPath) {
        				html += '<img src=';
	        			html += '".$path."';
	        			html += imgPath;
	        			html += ' class=\"img-thumbnail\" width=\"100\" height=\"100\" />';
        			} else {
        				html += '<img src=';
	        			html += '".$no_image."';
	        			html += ' class=\"img-thumbnail\" width=\"100\" height=\"100\" />';
        			}
                    return html;
                }
        	},
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\">Active</span>';
        			} else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Inactive</span>';
        			}
        		}
        	},
        	{ 'defaultContent' : '<div class=\"dropdown\"><a class=\"btn btn-secondary dropdown-toggle\" href=\"#\" role=\"button\" id=\"dropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fs-14 fa fa-bars\"></i></a><ul class=\"dropdown-menu dropdown-menu-right\" aria-labelledby=\"about-us\"><li style=\"text-align: center;\"><a class=\"dropdown-item edit_info\" href=\"javascript:void(0)\" data-toggle=\"modal\" style=\"color: blue;\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;\" aria-hidden=\"true\"></i>Edit</a></li><li style=\"text-align: center;\"><a class=\"dropdown-item delete_info\" href=\"javascript:void(0)\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;\" aria-hidden=\"true\"></i>Delete</a></li></ul></div>'
        	}
    	]";

        return $retData;
	}

	public function subCategoryDetails_ajax() {
		$response = array();
		$response['draw'] = 1;
		$response['recordsTotal'] = 0;
		$response['recordsFiltered'] = 0;
		$response['data'] = array();
		try {
			$data = $this->input->post();
			if(empty($data)) {
				echo json_encode($response); die;
			} else {
				$search = !empty($this->input->post('search'))? $this->input->post('search')['value'] : '';
				$limit  = (!empty($data['length'])?$data['length']:10);
				$offset = (!empty($data['start'])?$data['start']:0);
   				$result = $this->CategoryModel->getAllSubCatDetails($limit,$offset,$search);
   				$response['draw'] = (!empty($data['draw'])?$data['draw']:1);
				$response['recordsTotal'] = (!empty($result['total_count'])?$result['total_count']:0);
				$response['recordsFiltered'] = (!empty($result['total_count'])?$result['total_count']:0);
				$response['data'] = (!empty($result['fetch_data'])?$result['fetch_data']:array());
				echo json_encode($response); die;
			}
		} catch (Exception $e) {
			echo json_encode($response); die;
		}
	} 

	public function editsubCat(){
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('Category/viewSubCategory');
		}
		$data['master_category']= $this->CategoryModel->getCategoryName();
		$res = $this->CategoryModel->getSubCatDetailsById($id);
		$data['fetch_data'] 	= 	$res['fetch_data'][0];
		$data['parent_category']=  $this->CategoryModel->getAllParentCat($data['fetch_data']['parent_cat_id']);
		$data['cat_id'] 		= 	$id;
		$data["page_title"]		=	"Edit Category Details";
        $page["script_files"]	=   $this->load->view('scripts/category/category',$data, true);
        $page["layout_content"]	=	$this->load->view('pages/category/editsubcategory', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function updateSubCategory() {
		$data = $this->input->post();
		$finalArr = array();
		$update_page_name = array();
		$seoArr = array();
		$previous_url_slug = $data['url_slug_prev'];
		$category_name_prv = $data['category_name_prv'];

		$id = !empty($data['cat_id'])?$data['cat_id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('cat_error',"Failed to update");	
			redirect('Category/editsubCat/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('user_error',"Failed to update");	
			redirect('Category/editsubCat/'.$id);
		}			
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('parent_cat_id', 'Parent Category', 'trim|required');
		$this->form_validation->set_rules('related_category[]', 'Related Category name', 'trim');
        $this->form_validation->set_rules('category_name', 'Sub Category Name','trim|required');
        $this->form_validation->set_rules('url_slug', 'Url Name', 'trim|required');
		$this->form_validation->set_rules('category_content', 'Category Content', 'trim');
		$this->form_validation->set_rules('footer_content', 'Footer Content', 'trim');
      	
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('cat_error',validation_errors());
            redirect('Category/editsubCat/'.$id);
        }

       	if(!empty($data['related_category'])){
       			$related_category = implode(',', $data['related_category']);
       	}
    	if(!empty($_FILES['files']['name'])) {
			$upload_folder = 'categories';
	        if (!file_exists(UPLOAD_IMAGE_URL) && !is_dir(UPLOAD_IMAGE_URL)) { 
	            mkdir(UPLOAD_IMAGE_URL, 0777, true);
	        }

	        // check uploaded path folder is present or not other wise create it 
	        if (!empty($upload_folder) && (!file_exists(UPLOAD_IMAGE_URL.$upload_folder))) {
				mkdir(UPLOAD_IMAGE_URL.$upload_folder, 0777, true);
			}

			$config['upload_path'] 	= 	UPLOAD_IMAGE_URL.$upload_folder;
			$config['allowed_types']= 	'jpg|png|jpeg';
			$config['max_size']		=	'5120';
			$config['file_name']	=	strtolower($upload_folder).'_'.rand(10,99999).time();
			
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('files')){
				
				$errors = $this->upload->display_errors();
				$this->session->set_flashdata('cat_error',$errors);
				redirect('category/editsubCat/'.$id);
			}
			$data['category_image'] = $this->upload->data('orig_name');	

			$finalArr = array(
				'parent_cat_id' 	=> $data['parent_cat_id'],
				'category_name' 	=> $data['category_name'],
				'related_category' 	=> $related_category,
				'url_host' 			=> SERVERNAME.'/auto_gorilla/',
				'url_slug' 			=> $data['url_slug'],
				'cat_level'			=> 2,
				'category_content'	=> $data['category_content'],
				'footer_content'	=> $data['footer_content'],
				'category_image' 	=> $data['category_image'],
				'modified_at' 		=> date('Y-m-d H:i:s')
			);
	    }else{ 
	    	$finalArr = array(
				'parent_cat_id' 	=> $data['parent_cat_id'],
				'category_name' 	=> $data['category_name'],
				'related_category' 	=> $related_category,
				'url_host' 			=> SERVERNAME.'/auto_gorilla/',
				'url_slug' 			=> $data['url_slug'],
				'cat_level'			=> 2,
				'category_content'	=> $data['category_content'],
				'footer_content'	=> $data['footer_content'],
				'modified_at' 		=> date('Y-m-d H:i:s')
			);
	    }

     	$response = $this->CategoryModel->updateCatDetails($finalArr,$id);
     	
		$check_seo_data 		= seo_meta_information('sub category',$data['url_slug']);
     	$seo_multi_meta_info 	= seo_multi_meta_information('sub category',$data['url_slug']); 
   		$seo_social_og_info  	= seo_social_ogInfo('sub category',$data['url_slug']); 

     	if(!empty($check_seo_data)){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_meta_info',array('page_name'=>'sub category','page_url'=>$data['url_slug']));
     	}
     	if(!empty($seo_multi_meta_info) && empty($data['meta_name'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_multiple_meta_info',array('page_name'=>'sub category','page_url'=>$data['url_slug']));
     	}else if(empty($data['meta_name'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_multiple_meta_info',array('page_name'=>'sub category','page_url'=>$data['url_slug']));
     	}else if(!empty($seo_multi_meta_info)){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_multiple_meta_info',array('page_name'=>'sub category','page_url'=>$data['url_slug']));
     	}
     	if(!empty($seo_social_og_info)  && empty($data['social_meta_og_property'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_social_og_info',array('page_name'=>'sub category','page_url'=>$data['url_slug']));
     	}else if(empty($data['social_meta_og_property'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_social_og_info',array('page_name'=>'sub category','page_url'=>$data['url_slug']));
     	}else if(!empty($seo_social_og_info)){
			$delete_data =  $this->CategoryModel->hardDelete('seo_social_og_info',array('page_name'=>'sub category','page_url'=>$data['url_slug']));
     	}

 		$seo_arr = array(
            'meta_title' 			=> $data['meta_title'],
            'meta_description' 		=> $data['meta_description'],
            'canonical_url' 		=> $data['canonical_url'],
            'page_name' 			=> 'sub category',
            'page_url' 				=> $data['url_slug'],
        ); 

		$insert_meta =	$this->CategoryModel->insert('seo_meta_info',$seo_arr);

		if(isset($data['meta_name'][0]) && trim($data['meta_name'][0]) !=''){
	     	foreach ($data['meta_name'] as $key => $value) {
				$finalArr_seo[] = array(
	                'meta_name' 	=> $value,
	                'meta_content' 	=> $data['meta_content'][$key],
	                'page_name' 	=> 'sub category',
	                'page_url' 		=> $data['url_slug'],
	                'seo_meta_id' 	=> $insert_meta,
	        	); 
			}
			$insert_meta = $this->db->insert_batch('seo_multiple_meta_info', $finalArr_seo);
		}

		if(isset($data['social_meta_og_property'][0]) && trim($data['social_meta_og_property'][0]) !=''){
			foreach ($data['social_meta_og_property'] as $key1 => $og_value) {
				$finalArr2[] = array(
					'social_meta_og_property' 	=> $og_value,
	            	'social_meta_og_content' 	=> $data['social_meta_og_content'][$key1],
	            	'page_name' 				=> 'sub category',
	                'page_url' 					=> $data['url_slug'],
	                'seo_meta_id' 				=> $insert_meta,
	            );    
			}
			$insert_meta_og = $this->db->insert_batch('seo_social_og_info', $finalArr2);	
     	}

		if($response) {
			$this->session->set_flashdata('cat_success','Update Successfull');
			redirect('category/viewSubCategory');
		} else {
			$this->session->set_flashdata('cat_error','Failed to update. Please try again');
			redirect('category/editsubCat/'.$id);
		}
	}


	public function getParentCategoryById(){
		$data = $this->input->post();
		$json = array();
        $json = $this->CategoryModel->getAllParentCat($data['parent_id']);
        header('Content-Type: application/json');
        echo json_encode($json);
	}

	/*	delete Category Details 	*/
	public function deleteSubCatDetails() {
		$postData = $this->input->post();
		$id = !empty($postData['cat_id'])?$postData['cat_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->CategoryModel->deleteSubCatDetails($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
		
	}
	

 /***************** END OF THE SUB CATEGORY ************************/

 /*****************START MICRO CATEGORY [Level 3] ************************/

	public function addMicroCategory(){
		$data = array();
		$data['fetch_parent_cat'] 		= $this->CategoryModel->getCategoryName();
		$submit = $this->input->post();
		
		if(!empty($submit)) {
			$data =  $submit;
			$finalArr = array();
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('parent_cat_id', 'Parent Category name', 'trim|required');
			$this->form_validation->set_rules('sub_cat_id', 'Parent Category name', 'trim|required');
			$this->form_validation->set_rules('related_parent_id', 'Category name', 'trim');
			$this->form_validation->set_rules('url_slug', 'Url Name', 'trim|required');
			$this->form_validation->set_rules('meta_tag', 'Meta Tag', 'trim');
			$this->form_validation->set_rules('meta_title', 'Meta Title', 'trim');
			$this->form_validation->set_rules('meta_keyword', 'Meta Keyword', 'trim');
			$this->form_validation->set_rules('meta_description', 'Meta Description', 'trim');
			$this->form_validation->set_rules('category_content', 'Category Content', 'trim');
			$this->form_validation->set_rules('footer_content', 'Footer Content', 'trim');

			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('cat_error',validation_errors());
				redirect('category/addMicroCategory');
	        }
	        
	        if(!empty($_FILES['files']['name'])) {
				$upload_folder = 'categories';
		        if (!file_exists(UPLOAD_IMAGE_URL) && !is_dir(UPLOAD_IMAGE_URL)) { 
		            mkdir(UPLOAD_IMAGE_URL, 0777, true);
		        }

		        // check uploaded path folder is present or not other wise create it 
		        if (!empty($upload_folder) && (!file_exists(UPLOAD_IMAGE_URL.$upload_folder))) {
					mkdir(UPLOAD_IMAGE_URL.$upload_folder, 0777, true);
				}

				$config['upload_path'] 	= 	UPLOAD_IMAGE_URL.$upload_folder;
				$config['allowed_types']= 	'jpg|png|jpeg';
				$config['max_size']		=	'5120';
				$config['file_name']	=	strtolower($upload_folder).'_'.rand(10,99999).time();
				
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('files')){
					
					$errors = $this->upload->display_errors();
					$this->session->set_flashdata('cat_error',$errors);
					redirect('category/addMicroCategory');
				}
				$data['category_image'] = $this->upload->data('orig_name');	
	        }

	        if(!empty($data['parent_cat_id'])){
	        		$finalArr = array(
	        			'parent_cat_id' 		=> $data['parent_cat_id'],
	        			'sub_cat_id' 			=> $data['sub_cat_id'],
	        			'related_parent_id' 	=> $data['related_parent_id'],
		        		'related_category' 		=> !empty($data['related_category'])?implode(',',$data['related_category']):'',
		        		'category_name' 		=> $data['category_name'],
		        		'category_image'		=> $this->upload->data('orig_name'),
		        		'url_host'      		=> SERVERNAME.'/auto_gorilla/',
		        		'url_slug' 				=> $data['url_slug'],
		        		'cat_level' 			=>'3',
		        		'category_content' 		=>$data['category_content3'],
		        		'footer_content' 		=>$data['footer_content']
		        	);
	        	}
	        	
		    $insert =	$this->CategoryModel->insert('auto_category',$finalArr);
				if($insert) {
				    $num = str_pad(mt_rand($insert,99999999),8,'0',STR_PAD_LEFT);
					$new_url_slug = $data['url_slug'].'-'.$num;
					$updateArr = 
						array('url_slug' => $new_url_slug, 
					);
					$update= $this->CategoryModel->update('auto_category',$updateArr,array('id'=>$insert));
					
					$seo_arr = array(
                        'meta_title' 			=> $data['meta_title'],
                        'meta_description' 		=> $data['meta_description'],
                        'canonical_url' 		=> $submit['canonical_url'].'-'.$num,
                        'page_name' 			=> 'micro category',
                        'page_url' 				=> $new_url_slug
	            	); 

				    $insert_seo =	$this->CategoryModel->insert('seo_meta_info',$seo_arr);
    				foreach ($data['meta_name'] as $key => $value) {
    					$finalArr_seo[] = array(
                            'meta_name' 	=> $value,
                            'meta_content' 	=> $data['meta_content'][$key],
                            'page_name' 	=> 'micro category',
                            'page_url' 		=> $new_url_slug,
                            'seo_meta_id' 	=> $insert_seo,
    	            	); 
    				}
    				$insert_meta = $this->db->insert_batch('seo_multiple_meta_info', $finalArr_seo);
			
    				foreach ($data['social_meta_og_property'] as $key1 => $og_value) {
    					$finalArr2[] = array(
    						'social_meta_og_property' 	=> $og_value,
                        	'social_meta_og_content' 	=> $data['social_meta_og_content'][$key1],
                        	'page_name' 				=> 'micro category',
                            'page_url' 					=> $new_url_slug,
                            'seo_meta_id' 				=> $insert_seo,
                        );    
    				}
				
				    $insert_meta_og = $this->db->insert_batch('seo_social_og_info', $finalArr2);
				if($insert){
					$this->session->set_flashdata('cat_success','Micro Category added successfully');
					redirect('category/viewMicroCategory');
				} else {
					$this->session->set_flashdata('cat_error','Failed to add data');
					redirect('category/addMicroCategory');
				}
			}
		}
		$data["page_title"] 	= "Add Micro Category Information";
        $page["layout_content"] = $this->load->view('pages/category/addMicroCategory', $data, true);
        $page["script_files"]	= $this->load->view('scripts/category/category',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function getsubCategoryById(){
		$data = $this->input->post();
		$json = array();
        $json = $this->CategoryModel->getAllSubcategoryById($data['parent_id']);
        header('Content-Type: application/json');
        echo json_encode($json);
	}

	public function getParentRelatedCategoryById(){
		$data = $this->input->post();
		$json = array();
        $json = $this->CategoryModel->getAllrelateParenById($data['subCat_id'],$data['parent_cat']);
        header('Content-Type: application/json');
        echo json_encode($json);
	}

	public function getSubRelatedCatById(){
		$data = $this->input->post();
		$json = array();
        $json = $this->CategoryModel->getAllrelateSubCatById($data['cat_id']);
        header('Content-Type: application/json');
        echo json_encode($json);
	}

	public function viewMicroCategory(){
		$catFormat 				= 	$this->MicroCatNameDetailsFormat();
		$data['columns']		=	$catFormat['cat_column'];
		$data['microCatData']	=	$catFormat['category_name'];
		$data["page_title"] 	= 	"View Micro Category Information";
        $page["layout_content"] = 	$this->load->view('pages/category/viewMicroCategory', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/category/category',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function MicroCatNameDetailsFormat() {

		$retData['cat_column'] = array("Parent-Category","Sub-Category","Micro-Category","Micro-Category Image","Created Date", "Status","Action");
		$path = VIEW_IMAGE_URL.'categories/';
		$no_image = VIEW_IMAGE_URL.'noimage.png';
        $retData['category_name'] = "[
        	{
        		'render': function (data, type, row, meta) {
        			let pimgPath = row.p_cat_img;
        			let html = '';
        			let p_categories_name =  row.p_categories_name;
        			if(pimgPath) {
        				html += p_categories_name;
        				html += '<br>';
        				html += '<img src=';
	        			html += '".$path."';
	        			html += pimgPath;
	        			html += ' class=\"img-thumbnail\" width=\"100\" height=\"100\" />';
        			} else {
        				html += p_categories_name;
        				html += '<br>';
        				html += '<img src=';
	        			html += '".$no_image."';
	        			html += ' class=\"img-thumbnail\" width=\"100\" height=\"100\" />';
        			}
                    return html;
                }
        	},

			{'render': function (data, type, row, meta) {
        			let sub_imgPath = row.sub_cat_img;
        			let html = '';
        			let sub_categories_name =  row.sub_categories_name;
        			if(sub_imgPath) {
        				html += sub_categories_name;
        				html += '<br>';
        				html += '<img src=';
	        			html += '".$path."';
	        			html += sub_imgPath;
	        			html += ' class=\"img-thumbnail\" width=\"100\" height=\"100\" />';
        			} else {
        				html += sub_categories_name;
        				html += '<br>';
        				html += '<img src=';
	        			html += '".$no_image."';
	        			html += ' class=\"img-thumbnail\" width=\"100\" height=\"100\" />';
        			}
                    return html;
                }
        	},
        	
        	{ 'data' : 'category_name' },
        	{
        		'render': function (data, type, row, meta) {
        			let imgPath = row.category_image;
        			let html = '';
        			if(imgPath) {
        				html += '<img src=';
	        			html += '".$path."';
	        			html += imgPath;
	        			html += ' class=\"img-thumbnail\" width=\"100\" height=\"100\" />';
        			} else {
        				html += '<img src=';
	        			html += '".$no_image."';
	        			html += ' class=\"img-thumbnail\" width=\"100\" height=\"100\" />';
        			}
                    return html;
                }
        	},
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\">Active</span>';
        			} else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Inactive</span>';
        			}
        		}
        	},
        	{ 'defaultContent' : '<div class=\"dropdown\"><a class=\"btn btn-secondary dropdown-toggle\" href=\"#\" role=\"button\" id=\"dropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fs-14 fa fa-bars\"></i></a><ul class=\"dropdown-menu dropdown-menu-right\" aria-labelledby=\"about-us\"><li style=\"text-align: center;\"><a class=\"dropdown-item edit_info\" href=\"javascript:void(0)\" data-toggle=\"modal\" style=\"color: blue;\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;\" aria-hidden=\"true\"></i>Edit</a></li><li style=\"text-align: center;\"><a class=\"dropdown-item delete_info\" href=\"javascript:void(0)\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;\" aria-hidden=\"true\"></i>Delete</a></li></ul></div>'
        	}
    	]";

        return $retData;
	}

	public function microCategoryDetails_ajax() {
		$response = array();
		$response['draw'] = 1;
		$response['recordsTotal'] = 0;
		$response['recordsFiltered'] = 0;
		$response['data'] = array();
		try {
			$data = $this->input->post();
			if(empty($data)) {
				echo json_encode($response); die;
			} else {
				$search = !empty($this->input->post('search'))? $this->input->post('search')['value'] : '';
				$limit  = (!empty($data['length'])?$data['length']:10);
				$offset = (!empty($data['start'])?$data['start']:0);
   				$result = $this->CategoryModel->getAllMicroCatDetails($limit,$offset,$search);
   				$response['draw'] = (!empty($data['draw'])?$data['draw']:1);
				$response['recordsTotal'] = (!empty($result['total_count'])?$result['total_count']:0);
				$response['recordsFiltered'] = (!empty($result['total_count'])?$result['total_count']:0);
				$response['data'] = (!empty($result['fetch_data'])?$result['fetch_data']:array());
				echo json_encode($response); die;
			}
		} catch (Exception $e) {
			echo json_encode($response); die;
		}
	}

	public function editMicroCat(){
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('Category/viewMicroCategory');
		}
		$data['master_category']				= 	$this->CategoryModel->getCategoryName();
		$res 									= 	$this->CategoryModel->getMicroCatDetailsById($id);
		$data['fetch_data'] 					= 	$res['fetch_data'][0];
		$data['subCat_category']				= 	$this->CategoryModel->getAllSubcategoryById($data['fetch_data']['parent_cat_id']);
		$data['related_parent_category']		= 	$this->CategoryModel->getAllrelateParenById($data['fetch_data']['sub_cat_id'],$data['fetch_data']['parent_cat_id']);
		$data['related_sub_parent_category']	=   $this->CategoryModel->getAllrelateSubCatById($data['fetch_data']['related_parent_id']);;
		$data['cat_id'] 						= 	$id;
		$data["page_title"]						=	"Edit Micro Category Details";
        $page["script_files"]					=   $this->load->view('scripts/category/category',$data, true);
        $page["layout_content"]					=	$this->load->view('pages/category/editMicroCategory', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function updateMicroCategory(){
		$data = $this->input->post();
		$finalArr = array();
		$update_page_name = array();
		$seoArr = array();
		$previous_url_slug = $data['url_slug_prev'];
		//$category_name_prv = $data['category_name_prv'];

		$id = !empty($data['cat_id'])?$data['cat_id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('cat_error',"Failed to update");	
			redirect('Category/editsubCat/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('user_error',"Failed to update");	
			redirect('Category/editsubCat/'.$id);
		}			
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('parent_cat_id', 'Parent Category name', 'trim|required');
			$this->form_validation->set_rules('sub_cat_id', 'Parent Category name', 'trim|required');
			$this->form_validation->set_rules('related_parent_id', 'Category name', 'trim');
			$this->form_validation->set_rules('related_category[]', 'Category name', 'trim');
			$this->form_validation->set_rules('url_slug', 'Url Name', 'trim|required');
			$this->form_validation->set_rules('meta_tag', 'Meta Tag', 'trim');
			$this->form_validation->set_rules('meta_title', 'Meta Title', 'trim');
			$this->form_validation->set_rules('meta_keyword', 'Meta Keyword', 'trim');
			$this->form_validation->set_rules('meta_description', 'Meta Description', 'trim');
			$this->form_validation->set_rules('category_content', 'Category Content', 'trim');
			$this->form_validation->set_rules('footer_content', 'Footer Content', 'trim');
      	
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('cat_error',validation_errors());
            redirect('Category/editMicroCat/'.$id);
        }

       	if(!empty($data['related_category'])){
       			$related_category = implode(',', $data['related_category']);
       	}
    	if(!empty($_FILES['files']['name'])) {
			$upload_folder = 'categories';
	        if (!file_exists(UPLOAD_IMAGE_URL) && !is_dir(UPLOAD_IMAGE_URL)) { 
	            mkdir(UPLOAD_IMAGE_URL, 0777, true);
	        }

	        // check uploaded path folder is present or not other wise create it 
	        if (!empty($upload_folder) && (!file_exists(UPLOAD_IMAGE_URL.$upload_folder))) {
				mkdir(UPLOAD_IMAGE_URL.$upload_folder, 0777, true);
			}

			$config['upload_path'] 	= 	UPLOAD_IMAGE_URL.$upload_folder;
			$config['allowed_types']= 	'jpg|png|jpeg';
			$config['max_size']		=	'5120';
			$config['file_name']	=	strtolower($upload_folder).'_'.rand(10,99999).time();
			
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('files')){
				
				$errors = $this->upload->display_errors();
				$this->session->set_flashdata('cat_error',$errors);
				redirect('category/editMicroCat/'.$id);
			}
			$data['category_image'] = $this->upload->data('orig_name');	

			$finalArr = array(
	        			'parent_cat_id' 		=> $data['parent_cat_id'],
	        			'sub_cat_id' 			=> $data['sub_cat_id'],
	        			'related_parent_id' 	=> !empty($data['related_parent_id'])?$data['related_parent_id']:'',
		        		'related_category' 		=> !empty($data['related_category'])?implode(',',$data['related_category']):'',
		        		'category_name' 		=> $data['category_name'],
		        		'category_image'		=> $data['category_image'],
		        		'url_host'      		=> SERVERNAME.'/auto_gorilla/',
		        		'url_slug' 				=> $data['url_slug'],
		        		'cat_level' 			=>'3',
		        		'category_content' 		=>$data['category_content'],
		        		'footer_content' 		=>$data['footer_content']
		        );
	    }else{ 
	    	$finalArr = array(
				'parent_cat_id' 		=> $data['parent_cat_id'],
    			'sub_cat_id' 			=> $data['sub_cat_id'],
    			'related_parent_id' 	=> !empty($data['related_parent_id'])?$data['related_parent_id']:'',
        		'related_category' 		=> !empty($data['related_category'])?implode(',',$data['related_category']):'',
        		'category_name' 		=> $data['category_name'],
        		'url_host'      		=> SERVERNAME.'/auto_gorilla/',
        		'url_slug' 				=> $data['url_slug'],
        		'cat_level' 			=>'3',
        		'category_content' 		=>$data['category_content'],
        		'footer_content' 		=>$data['footer_content'],
				'modified_at' 			=> date('Y-m-d H:i:s')
			);
	    }
     	$response = $this->CategoryModel->updateCatDetails($finalArr,$id);
     	
		$check_seo_data 		= seo_meta_information('micro category',$data['url_slug']);
     	$seo_multi_meta_info 	= seo_multi_meta_information('micro category',$data['url_slug']); 
   		$seo_social_og_info  	= seo_social_ogInfo('micro category',$data['url_slug']); 

     	if(!empty($check_seo_data)){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_meta_info',array('page_name'=>'micro category','page_url'=>$data['url_slug']));
     	}
     	if(!empty($seo_multi_meta_info) && empty($data['meta_name'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_multiple_meta_info',array('page_name'=>'micro category','page_url'=>$data['url_slug']));
     	}else if(empty($data['meta_name'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_multiple_meta_info',array('page_name'=>'micro category','page_url'=>$data['url_slug']));
     	}else if(!empty($seo_multi_meta_info)){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_multiple_meta_info',array('page_name'=>'micro category','page_url'=>$data['url_slug']));
     	}

     	if(!empty($seo_social_og_info) && empty($data['social_meta_og_property'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_social_og_info',array('page_name'=>'micro category','page_url'=>$data['url_slug']));
     	}else if(empty($data['social_meta_og_property'])){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_social_og_info',array('page_name'=>'micro category','page_url'=>$data['url_slug']));
     	}else if(!empty($seo_social_og_info)){
     		$delete_data =  $this->CategoryModel->hardDelete('seo_social_og_info',array('page_name'=>'micro category','page_url'=>$data['url_slug']));
     	}

 		$seo_arr = array(
            'meta_title' 			=> $data['meta_title'],
            'meta_description' 		=> $data['meta_description'],
            'canonical_url' 		=> $data['canonical_url'],
            'page_name' 			=> 'micro category',
            'page_url' 				=> $data['url_slug'],
        ); 

		$insert_meta =	$this->CategoryModel->insert('seo_meta_info',$seo_arr);
		if(isset($data['meta_name']) && $data['meta_name'] !=''){
	     	foreach ($data['meta_name'] as $key => $value) {
				$finalArr_seo[] = array(
	                'meta_name' 	=> $value,
	                'meta_content' 	=> $data['meta_content'][$key],
	                'page_name' 	=> 'micro category',
	                'page_url' 		=> $data['url_slug'],
	                'seo_meta_id' 	=> $insert_meta,
	        	); 
			}
			$insert_meta = $this->db->insert_batch('seo_multiple_meta_info', $finalArr_seo);
		}
		if(isset($data['social_meta_og_property']) && $data['social_meta_og_property'] !=''){
			foreach ($data['social_meta_og_property'] as $key1 => $og_value) {
				$finalArr2[] = array(
					'social_meta_og_property' 	=> $og_value,
	            	'social_meta_og_content' 	=> $data['social_meta_og_content'][$key1],
	            	'page_name' 				=> 'micro category',
	                'page_url' 					=> $data['url_slug'],
	                'seo_meta_id' 				=> $insert_meta,
	            );    
			}
			$insert_meta_og = $this->db->insert_batch('seo_social_og_info', $finalArr2);	
		}
		if($response) {
			$this->session->set_flashdata('cat_success','Update Successfull');
			redirect('category/viewMicroCategory');
		} else {
			$this->session->set_flashdata('cat_error','Failed to update. Please try again');
			redirect('category/editMicroCat/'.$id);
		}
	}

	/**** end ***/

	public function allCategoryContent(){
		$catFormat 						= 	$this->allCategoryFormat();
		$data['columns']				=	$catFormat['cat_column'];
		$data['categoryContentData']	=	$catFormat['header_content'];
		$data["page_title"] 			= 	"View All Category Content Information";
        $page["layout_content"] 		= 	$this->load->view('pages/category/categoryContent', $data, true);
        $page["script_files"]			=	$this->load->view('scripts/category/category',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function allCategoryFormat() {
		$retData['cat_column'] = array("Category Header Content","Category Footer Content","Status","Action");
		$retData['header_content'] = "[
        	{ 'data' : 'header_content'},
        	{ 'data' : 'footer_content' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\">Active</span>';
        			} else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Inactive</span>';
        			}
        		}
        	},
        	{ 'defaultContent' : '<a class=\"edit_info\" href=\"javascript:void(0)\" title=\"Edit\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 20px;\" aria-hidden=\"true\"></i></a>'
        	}
    	]";

        return $retData;
	}

	public function categoryContentFormat_ajax() {
		$response = array();
		$response['draw'] = 1;
		$response['recordsTotal'] = 0;
		$response['recordsFiltered'] = 0;
		$response['data'] = array();
		try {
			$data = $this->input->post();
			if(empty($data)) {
				echo json_encode($response); die;
			} else {
				$search = !empty($this->input->post('search'))? $this->input->post('search')['value'] : '';
				$limit  = (!empty($data['length'])?$data['length']:10);
				$offset = (!empty($data['start'])?$data['start']:0);
   				$result = $this->CategoryModel->getCategoryContentDetails($limit,$offset,$search);
   				$response['draw'] = (!empty($data['draw'])?$data['draw']:1);
				$response['recordsTotal'] = (!empty($result['total_count'])?$result['total_count']:0);
				$response['recordsFiltered'] = (!empty($result['total_count'])?$result['total_count']:0);
				$response['data'] = (!empty($result['fetch_data'])?$result['fetch_data']:array());
				echo json_encode($response); die;
			}
		} catch (Exception $e) {
			echo json_encode($response); die;
		}
	}

	public function editAllCategoryContent(){
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('Category/allCategoryContent');
		}
		$res 									= 	$this->CategoryModel->getAllCategoryContentDetailsById($id);
		$data['fetch_data']						=  !empty($res)?$res[0]:[];
		$data["page_title"]						=	"Edit All Category Content Details";
        $page["script_files"]					=   $this->load->view('scripts/category/category',$data, true);
        $page["layout_content"]					=	$this->load->view('pages/category/editAllCategoryContent', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function updateAllCategoryContent(){
		$data = $this->input->post();
		$id   = $data['id'];
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('header_content', 'Header Content', 'trim');
		$this->form_validation->set_rules('footer_content', 'Parent Category name', 'trim');
		  if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('cat_error',validation_errors());
            redirect('Category/editAllCategoryContent/'.$id);
        }

        $updateArr = array();
        $updateArr = array(
        	'header_content' =>	$data['header_content'],
        	'footer_content' =>	$data['footer_content'],
        );
        $response = $this->CategoryModel->updatecontentInfo('auto_all_category_content',$updateArr,$id);
       
        if($response){
        	$this->session->set_flashdata('cat_success','Update Successfull');
			redirect('category/allCategoryContent');
        } else {
        	$this->session->set_flashdata('cat_success','Update Successfull');
			 redirect('Category/editAllCategoryContent/'.$id);
        }
	}



	public function categoryscript(){
		$data = array();
		$page["script_files"]					=   $this->load->view('scripts/category/category',$data, true);
        $page["layout_content"]					=	$this->load->view('pages/category/script', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}
	public function uploadData()
    {
        $this->CategoryModel->uploadData();
        //redirect('categoryscript');
    }
    
	public function generateParentSiteMap(){
    	$sitemap = $this->CategoryModel->sitemapforParentmenu();
		$array_break    = array_chunk($sitemap,14000);
    	$sitemappath= $_SERVER['DOCUMENT_ROOT'].'/'; 
		$actual_link 	= "https://www.autogorilla.com/";
		$lastmod = date("Y-m-d");
		$freq    = "daily";
		$priority = "1.0";
		$ch=0;
		foreach($array_break as $key => $value_slug)
		{
			$xmldata = '<?xml version="1.0" encoding="utf-8"?>'; 
			$xmldata .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">';	

			foreach($value_slug as $k => $slug){
				$urlSlug = create_url($slug->url_slug);
				$url = $actual_link.$urlSlug;
				$xmldata .= '<url>';
				$xmldata .= '<loc>'.$url.'</loc>';
				$xmldata .= '<lastmod>'.$lastmod.'</lastmod>';
				$xmldata .= '<changefreq>'.$freq.'</changefreq>';
				$xmldata .= '<priority>'.$priority.'</priority>';
				$xmldata .= '</url>';
			}
			$xmldata .= '</urlset>';
			$keys = $key+1;
			$fileName = 'parent-category-page-'.$keys.'.xml';
			$dom = new DOMDocument;
			$dom->preserveWhiteSpace = FALSE;
			$result = $dom->loadXML($xmldata);
			$dom->save($sitemappath.$fileName);
		}
		redirect('sitemap/sitemap_url');
		
    } 

    public function generate_SubMenuSiteMap(){
    	$sitemap = $this->CategoryModel->sitemapforSubMenu();
		$array_break    = array_chunk($sitemap,14000);
    	$sitemappath = $_SERVER['DOCUMENT_ROOT'].'/'; 
		$actual_link 	= "https://www.autogorilla.com/";
		$lastmod = date("Y-m-d");
		$freq    = "daily";
		$priority = "1.0";
		$ch = 0;
		
		foreach($array_break as $key=> $sub_slug)
		{
			$xmldata  = '<?xml version="1.0" encoding="utf-8"?>'; 
			$xmldata .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">';
				foreach($sub_slug as $k=> $slug){
					$urlSlug  = create_url($slug->url_slug);
					$url 	  = $actual_link.$urlSlug;
					$xmldata .= '<url>';
					$xmldata .= '<loc>'.$url.'</loc>';
					$xmldata .= '<lastmod>'.$lastmod.'</lastmod>';
					$xmldata .= '<changefreq>'.$freq.'</changefreq>';
					$xmldata .= '<priority>'.$priority.'</priority>';
					$xmldata .= '</url>';
				}
			$xmldata .= '</urlset>';
			$keys = $key+1;
			$fileName = 'sub-category-page-'.$keys.'.xml';
			$dom = new DOMDocument;
			$dom->preserveWhiteSpace = FALSE;
			$result = $dom->loadXML($xmldata);
			$dom->save($sitemappath.$fileName);
		}
		redirect('sitemap/sitemap_url');
    }


    public function generate_MicroMenuSiteMap(){
    	$sitemap      = $this->CategoryModel->sitemapforMicroMenu();
		$array_break  = array_chunk($sitemap,14000);
    	$sitemappath  = $_SERVER['DOCUMENT_ROOT'].'/'; 
		$actual_link 	= "https://www.autogorilla.com/";
		$lastmod = date("Y-m-d");
		$freq    = "daily";
		$priority = "1.0";
		$ch=0;
		
		foreach($array_break as $key=>$m_slug)
		{
			$xmldata = '<?xml version="1.0" encoding="utf-8"?>'; 
			$xmldata .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">';
			foreach($m_slug as $k=>$slug){
				$urlSlug = create_url($slug->url_slug);
				$url = $actual_link.$urlSlug;
				$xmldata .= '<url>';
				$xmldata .= '<loc>'.$url.'</loc>';
				$xmldata .= '<lastmod>'.$lastmod.'</lastmod>';
				$xmldata .= '<changefreq>'.$freq.'</changefreq>';
				$xmldata .= '<priority>'.$priority.'</priority>';
				$xmldata .= '</url>';
			}
			$xmldata .= '</urlset>';
			$keys     = $key+1;
			$fileName = 'micro-category-page-'.$keys.'.xml';
			$dom = new DOMDocument;
			$dom->preserveWhiteSpace = FALSE;
			$result = $dom->loadXML($xmldata);
			$dom->save($sitemappath.$fileName);
		}
		redirect('sitemap/sitemap_url');
    }

    public function generateAllSiteMap(){
		$data["page_title"]				=	"All category site map";
        $page["script_files"]			=   $this->load->view('scripts/category/category',$data, true);
        $page["layout_content"]			=	$this->load->view('pages/category/viewSitemapForcategory', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
    }


}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */