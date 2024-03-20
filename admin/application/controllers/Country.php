<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model('CountryModel');
	}

	public function csvExport() {
		$filename = 'country_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 

		// get data
		$usersData = $this->CountryModel->exportCountryDetails();

		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","Country name","Country Code");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;

	}

	public function addCountry() {
		$submit = $this->input->post();
		if(!empty($submit)) {
			$data = array();
			try {
				$data = validatePostData($submit);
			} catch (Exception $e) {
				$this->session->set_flashdata('cat_error','Invalid data');
				redirect('country/addCountry');
			}

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('country_name', 'Country Name', 'trim|required');
			$this->form_validation->set_rules('country_code', 'Country Code', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('login_error',validation_errors());
				redirect('country/addCountry');
	        }
	        if(!empty($_FILES['files']['name'])) {
				$upload_folder = 'countryImage';
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
					redirect('country/addCountry');
				}
				$data['country_image'] = $this->upload->data('orig_name');	
	        }

			$insert =	$this->CountryModel->insert('auto_country',$data);
			if($insert) {
				$this->session->set_flashdata('cat_success','Country added successfully');
				redirect('country/viewCountry');
			} else {
				$this->session->set_flashdata('cat_error','Failed to add data');
				redirect('country/addCountry');
			}
		}
		$data["page_title"] 	= "Add Country Information";
        $page["layout_content"] = $this->load->view('pages/country/addCountry', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function viewCountry(){
		$catFormat 				= 	$this->countryDetailsFormat();
		$data['columns']		=	$catFormat['cat_column'];
		$data['catData']		=	$catFormat['country_name'];
		$data["page_title"] 	=   "View Country Information";
        $page["layout_content"] =   $this->load->view('pages/country/viewCountry', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/country/country',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function countryDetailsFormat() {
		$retData['cat_column'] = array("Country Image","Country Name","Country Code","Created Date", "Status","Action");
		$path     = VIEW_IMAGE_URL.'countryImage/';
		$no_image = VIEW_IMAGE_URL.'noimage.png';
        $retData['country_name'] = "[
        	{
        		'render': function (data, type, row, meta) {
        			let imgPath = row.country_image;
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
        	{ 'data' : 'country_name' },
        	{ 'data' : 'country_code' },
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

	public function countryDetails_ajax() {
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
   				$result = $this->CountryModel->getAllCountryDetails($limit,$offset,$search);
   				
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

	public function editCountry() {
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('country/viewCountry');
		}
		$res = $this->CountryModel->getCountryDetailsById($id);
		$data['fetch_data'] 	= 	$res['fetch_data'][0];
		$data['country_id'] 		= 	$id;
		$data["page_title"]		=	"Edit Country Details";
        $page["layout_content"]	=	$this->load->view('pages/country/editCountry', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function updateCountry() {
		$data = $this->input->post();
		$data = validatePostData($data);
		$id = !empty($data['country_id'])?$data['country_id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('cat_error',"Failed to update");	
			redirect('Country/viewCountry/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('user_error',"Failed to update");	
			redirect('country/viewCountry/'.$id);
		}			
		$this->form_validation->set_data($data);
        $this->form_validation->set_rules('country_name', 'Country Name','trim|required');
         $this->form_validation->set_rules('country_code', 'Country Code','trim|required');
      
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('cat_error',validation_errors());
            redirect('country/editCountry/'.$id);
        }
        if(!empty($_FILES['files']['name'])) {

			$upload_folder = 'countryImage';
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
				redirect('country/editCountry/'.$id);
			}
			$data['country_image'] = $this->upload->data('orig_name');	
        }
    	
        unset($data['country_id']);
        $data['updated_at'] = date('Y-m-d H:i:s');
     	$response = $this->CountryModel->updateCountryDetails($data,$id);
		if($response) {
			$this->session->set_flashdata('cat_success','Update Successfull');
			redirect('country/viewCountry');
		} else {
			$this->session->set_flashdata('cat_error','Failed to update. Please try again');
			redirect('country/editCountry/'.$id);
		}
	}

	/*	delete Category Details 	*/
	public function deleteCountryDetails() {

		$postData = $this->input->post();
		$id = !empty($postData['country_id'])?$postData['country_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->CountryModel->deleteCountryDetails($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
	}

	/*	Change Category Status 	*/
	public function updateStatus() {

		$postData = $this->input->post();
		$id = !empty($postData['country_id'])?$postData['country_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->CountryModel->changeStatus($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

}

/* End of file Country.php */
/* Location: ./application/controllers/Country.php */