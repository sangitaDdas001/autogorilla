<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AboutUs extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model('aboutModel');
	}

	public function index() {
	    $this->viewAboutUs();
	}
	

	/******* About Section with data table****************/
	public function viewAboutUs(){
		$aboutFormat 		    = 	$this->aboutDetailsFormat();
		$data['columns']		=	$aboutFormat['about_column'];
		$data['aboutData']		=	$aboutFormat['aboutDetails'];
		$data["page_title"] 	=   "View About Information";
        $page["layout_content"] =   $this->load->view('pages/aboutUs/viewAboutUs', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/aboutUs/about_scripts',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function aboutDetailsFormat() {

		$retData['about_column'] = array("Overview Heading","Modified Date", "Status","Action");
        $retData['aboutDetails'] = "[
        	{ 'data' : 'content_for' },
        	{ 'data' : 'modified_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\">Active</span>';
        			} else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Inactive</span>';
        			}
        		}
        	},
        	{ 'defaultContent' : '<div class=\"dropdown\"><a class=\"btn btn-secondary dropdown-toggle\" href=\"#\" role=\"button\" id=\"dropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fs-14 fa fa-bars\"></i></a><ul class=\"dropdown-menu dropdown-menu-right\" aria-labelledby=\"about-us\"><li style=\"text-align: center;\"><a class=\"dropdown-item more_info\" href=\"javascript:void(0)\" data-toggle=\"modal\"><i class=\"fa fa-info-circle\" style=\"margin-right: 3px; color: #337ab7;\" aria-hidden=\"true\"></i>View Info</a></li><li style=\"text-align: center;\"><a class=\"dropdown-item edit_info\" href=\"javascript:void(0)\" data-toggle=\"modal\" style=\"color: blue;\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;\" aria-hidden=\"true\"></i>Edit</a></li></ul></div>'
        	}
    	]";
        return $retData;
	}

	public function aboutDetails_ajax() {
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
   				$result = $this->aboutModel->getAllAboutDetails($limit,$offset,$search);
   				
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

	/******* Change Status ****************/
	public function updateAboutStatus() {
		$postData = $this->input->post();
		$id = !empty($postData['about_id'])?$postData['about_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->aboutModel->changeStatus($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
	}

	/*	fetch Book Info */
	public function aboutInfo() {
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('aboutUs/viewAboutUs');
		}
		$res = $this->aboutModel->getDetailsById($id);
		$result['fetch_data'] 	= 	$res['fetch_data'][0];
		$result["page_title"]	=	"About information";
        $page["layout_content"]	=	$this->load->view('pages/aboutUs/aboutInfo', $result, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function editAboutUs(){
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('aboutUs/viewAboutUs');
		}
		$res = $this->aboutModel->getDetailsById($id);
		$data['fetch_data'] 	= 	$res['fetch_data'][0];

		$data['about_id'] 		= 	$id;
		$data["page_title"]		=	"Edit About Details";
	    $page["script_files"]	=	$this->load->view('scripts/aboutUs/about_scripts',$data, true);
	    $page["layout_content"]	=	$this->load->view('pages/aboutUs/editAboutUs', $data, true);
	    $this->load->view('layouts/datatable_layout', $page);
	}

	public function uploadImg() {
		  /***************************************************
		   * Only these origins are allowed to upload images *
		   ***************************************************/
		  $accepted_origins = array(SERVERNAME, base_url());
		   $upload_folder = 'aboutus/';
	       if (!file_exists(UPLOAD_IMAGE_URL) && !is_dir(UPLOAD_IMAGE_URL)) { 
	            mkdir(UPLOAD_IMAGE_URL, 0777, true);
	       }
	      $imageFolder = UPLOAD_IMAGE_URL.'aboutus/';
		  reset ($_FILES);
		  $temp = current($_FILES);
		  if (is_uploaded_file($temp['tmp_name'])){
		    if (isset($_SERVER['HTTP_ORIGIN'])) {
		      // same-origin requests won't set an origin. If the origin is set, it must be valid.
		      if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
		        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
		      } else {
		        die("403 Origin Denied");
		        return;
		      }
		    }

		    // Sanitize input
		    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
		        header("HTTP/1.1 400 Invalid file name.");
		        return;
		    }

		    // Verify extension
		    if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("jpeg", "jpg", "png"))) {
		        die("Invalid extension.");
		        return;
		    }
		    
		    $imageArr   =   explode(".", $temp['name']);
		    // Accept upload if there was no origin, or if it is an accepted origin
		    $filetowrite = $imageFolder ."attachment_img_".date('YmdHis').'.'.end($imageArr);
		    move_uploaded_file($temp['tmp_name'], $filetowrite);
		    $file_path = VIEW_IMAGE_URL."aboutus/attachment_img_".date('YmdHis').'.'.end($imageArr);
		    echo json_encode(array('location' => $file_path));
		  } else {
		    // Notify editor that the upload failed
		    die("HTTP/1.1 500 Server Error");
		  }
	}

	public function updateAboutUs() {
		$data = $this->input->post();
		// $data = validatePostData($data);
		$id = !empty($data['about_id'])?$data['about_id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('error',"Failed to update");	
			redirect('aboutUs/editAboutUs/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('error',"Failed to update");	
			redirect('aboutUs/editAboutUs/'.$id);
		}			
		$this->form_validation->set_data($data);
        $this->form_validation->set_rules('content', 'Overview Content','trim|required');
      
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error',validation_errors());
            redirect('aboutUs/editAboutUs/'.$id);
        }
        if(!empty($_FILES['image']['name'])) {

			$upload_folder = 'aboutus';
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
			if (!$this->upload->do_upload('image')){
				
				$errors = $this->upload->display_errors();
				$this->session->set_flashdata('error',$errors);
				redirect('aboutUs/editAboutUs/'.$id);
			}
			$data['image'] = $this->upload->data('orig_name');	
        }
        unset($data['about_id']);
        unset($data['submit']);
        $data['modified_at'] = date('Y-m-d H:i:s');
     	$response = $this->aboutModel->updateAboutDetails($data,$id);
		if($response) {
			$this->session->set_flashdata('success','Update Successfull');
			redirect('aboutUs/viewAboutUs');
		} else {
			$this->session->set_flashdata('error','Failed to update. Please try again');
			redirect('aboutUs/editAboutUs/'.$id);
		}
	}
}

/* End of file aboutUs.php */
/* Location: ./application/controllers/aboutUs.php */