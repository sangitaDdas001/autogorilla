<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('BannerModel','VendorModel'));
	}

	public function index() {
	    $this->viewBannerList();
	}

	public function addBanner() {
		$submit = $this->input->post();
		if(!empty($submit)) {
			$data = $submit;
			
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('banner_text', 'Banner Title', 'trim');
			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('book_error',validation_errors());
				redirect('banner/addBanner');
	        }

	        $upload_folder = 'bannerImage';
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
			if (!$this->upload->do_upload('files')){ //name of file in html
				
				$errors = $this->upload->display_errors();
				$this->session->set_flashdata('book_error',$errors);
				redirect('bannerList/addBanner');
       
			}
			$data['bannerImage'] = $this->upload->data('orig_name');
			unset($data['submit']);
				//database filled name
			$insert =	$this->BannerModel->insert('auto_banner',$data);
			if($insert) {
				$this->session->set_flashdata('book_success','Banner added successfully');
				redirect('banner/viewBannerList');
			} else {
				$this->session->set_flashdata('book_error','Failed to add data');
				redirect('banner/addBanner');
			}
		}
		$data1["page_title"] 	= "Add Banner Information";
		$conditions 			= array('status'=>'A');
		$data1["script_files"]	= $this->load->view('scripts/banner/banner_script',$data1,true);
        $page["layout_content"] = $this->load->view('pages/bannerList/addBanner', $data1, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function viewBannerList(){
		$bannerFormat 				= 	$this->bannerDetailsFormat();
		$data['columns']			=	$bannerFormat['banner_column'];
		$data['bannerData']			=	$bannerFormat['banner_name'];
		$data["page_title"] 		= 	"View Banner Information";
        $page["layout_content"] 	= 	$this->load->view('pages/bannerList/viewBannerList', $data, true);
        $page["script_files"]		=	$this->load->view('scripts/banner/banner_script',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function bannerDetailsFormat() {
		$retData['banner_column'] = array("Banner Title", "Banner Image", "Status", "Action");
		$path = VIEW_IMAGE_URL.'bannerImage/';
		$no_image = VIEW_IMAGE_URL.'uploads/noimage.png';
        $retData['banner_name'] = "[
        	{ 'data' : 'banner_text'},
        	{
        		'render': function (data, type, row, meta) {
        			let imgPath = row.bannerImage;

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

	public function bannerDetails_ajax() {
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
				$limit = (!empty($data['length'])?$data['length']:10);
				$offset = (!empty($data['start'])?$data['start']:0);
   				$result = $this->BannerModel->getBannerList($limit,$offset,$search);
   				
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

	/*	Edit Banner  */
	public function editBanner() {
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('banner/viewBannerList');
		}
		$res = $this->BannerModel->getBannerDetailsById($id);
		if(empty($res)) {
			redirect('banner/viewBannerList');
		}
		$data['fetch_data'] 	= 	!empty($res['fetch_data'][0])?$res['fetch_data'][0]:array();
		$data['banner_id'] 		= 	$id;
		$data["page_title"]		=	"Edit Banner Details";
		$conditions 			=    array('status'=>'A');
		$data['bannerInfo']     = 	$this->BannerModel->getDetailsByConditions('auto_banner',$conditions);
        $page["layout_content"]	=	$this->load->view('pages/bannerList/editBanner', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/banner/banner_script',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	/*	Update Banner details */
	public function updateBannerDetails() {

		$data = $this->input->post();
		$id = !empty($data['banner_id'])?$data['banner_id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('book_error',"Failed to update");	
			redirect('banner/editBanner/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('book_error',"Failed to update");	
			redirect('banner/editBanner/'.$id);
		}			
		$this->form_validation->set_data($data);
    	$this->form_validation->set_rules('banner_text', 'Banner Title', 'trim');
		
    	if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('book_error',validation_errors());
            redirect('banner/editBanner/'.$id);
        }
    
        if(!empty($_FILES['files']['name'])) {
			$upload_folder = 'bannerImage';
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
				redirect('banner/editBanner/'.$id);
			}
			$data['bannerImage'] = $this->upload->data('orig_name');	
        }
       	
        unset($data['banner_id']);
        unset($data['submit']);
        $data['updated_at'] = date('Y-m-d H:i:s');
     	$response = $this->BannerModel->updateBannerDetails($data,$id);

		if($response) {
			$this->session->set_flashdata('book_success','Update Successfull');
			redirect('banner/viewBannerList');
		} else {
			$this->session->set_flashdata('book_error','Failed to update. Please try again');
			redirect('banner/editBanner/'.$id);
		}
	}


 
	/*	Change banner Status 	*/
	public function updateBannerStatus() {

		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->BannerModel->changeBanerStatus($postData);
			if ($response) {
				$this->session->set_flashdata('book_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
		
	}

	/*	delete Banner Details 	*/
	public function deleteBannerDetails() {

		$postData = $this->input->post();
		$id = !empty($postData['banner_id'])?$postData['banner_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->BannerModel->deleteBanner($postData);
			if ($response) {
				$this->session->set_flashdata('book_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
		
	}

	//*********************** START COMPANY PROMOTION BANNER  ***********************//

	public function addPromotionBanner() {
		$data1 = array();
		$data1['company_list'] = $this->VendorModel->getCompanyName();
		$submit = $this->input->post();
		if(!empty($submit)) {
			$data = $submit;
			
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('vendor_id', 'Company_name', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('book_error',validation_errors());
				redirect('banner/addPromotionBanner');
	        }

	        $upload_folder = 'promotion_banner_Image';
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
			if (!$this->upload->do_upload('files')){ //name of file in html
				
				$errors = $this->upload->display_errors();
				$this->session->set_flashdata('book_error',$errors);
				redirect('bannerList/addPromotionBanner');
       
			}
			$data['bannerImage'] = $this->upload->data('orig_name');
			unset($data['submit']);
				//database filled name
			$insert =	$this->BannerModel->insert('auto_banner',$data);
			if($insert) {
				$this->session->set_flashdata('book_success','Banner added successfully');
				redirect('banner/viewPromotionBanner');
			} else {
				$this->session->set_flashdata('book_error','Failed to add data');
				redirect('banner/addPromotionBanner');
			}
		}
		$data1["page_title"] 	= "Add Promotion Banner Information";
		$conditions 			= array('status'=>'A');
		$data1["script_files"]	= $this->load->view('scripts/banner/banner_script',$data1,true);
        $page["layout_content"] = $this->load->view('pages/bannerList/addPromotionBanner', $data1, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function viewPromotionBanner(){
		$bannerFormat 				= 	$this->promotionBannerDetailsFormat();
		$data['columns']			=	$bannerFormat['banner_column'];
		$data['promoBannerData']	=	$bannerFormat['banner_name'];
		$data["page_title"] 		= 	"View Promotion Banner Information";
        $page["layout_content"] 	= 	$this->load->view('pages/bannerList/viewPromotionBanner', $data, true);
        $page["script_files"]		=	$this->load->view('scripts/banner/banner_script',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function promotionBannerDetailsFormat() {
		$retData['banner_column'] = array("Company Name", "Banner Image", "Status", "Action");
		$path = VIEW_IMAGE_URL.'promotion_banner_Image/';
		$no_image = VIEW_IMAGE_URL.'uploads/noimage.png';
        $retData['banner_name'] = "[
        	{ 'data' : 'company_name'},
        	{
        		'render': function (data, type, row, meta) {
        			let imgPath = row.bannerImage;

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

	public function promotionBannerDetails_ajax() {
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
				$limit = (!empty($data['length'])?$data['length']:10);
				$offset = (!empty($data['start'])?$data['start']:0);
   				$result = $this->BannerModel->getPromotionBannerList($limit,$offset,$search);
   				
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

	/*	Edit Banner  */
	public function editProtionBanner() {
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('banner/viewPromotionBanner');
		}
		$data['company_list'] = $this->VendorModel->getCompanyName();
		$res = $this->BannerModel->getPromoBannerDetailsById($id);
	
		if(empty($res)) {
			redirect('banner/viewPromotionBanner');
		}
		$data['fetch_data'] 	= 	!empty($res['fetch_data'][0])?$res['fetch_data'][0]:array();
		$data['banner_id'] 		= 	$id;
		$data["page_title"]		=	"Edit Promotion Banner Details";
        $page["layout_content"]	=	$this->load->view('pages/bannerList/editPromotionBanner', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/banner/banner_script',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	/*	Update Banner details */
	public function updatePromoBannerDetails() {

		$data = $this->input->post();
		$id = !empty($data['banner_id'])?$data['banner_id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('book_error',"Failed to update");	
			redirect('banner/editProtionBanner/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('book_error',"Failed to update");	
			redirect('banner/editProtionBanner/'.$id);
		}			
		$this->form_validation->set_data($data);
    	$this->form_validation->set_rules('vendor_id', 'Company Name', 'trim|required');
		
    	if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('book_error',validation_errors());
            redirect('banner/editProtionBanner/'.$id);
        }
    
        if(!empty($_FILES['files']['name'])) {
			$upload_folder = 'promotion_banner_Image';
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
				redirect('banner/editProtionBanner/'.$id);
			}
			$data['bannerImage'] = $this->upload->data('orig_name');	
        }
       	
        unset($data['banner_id']);
        unset($data['submit']);
        $data['updated_at'] = date('Y-m-d H:i:s');
     	$response = $this->BannerModel->updateBannerDetails($data,$id);

		if($response) {
			$this->session->set_flashdata('book_success','Update Successfull');
			redirect('banner/viewPromotionBanner');
		} else {
			$this->session->set_flashdata('book_error','Failed to update. Please try again');
			redirect('banner/editProtionBanner/'.$id);
		}
	}

	

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */