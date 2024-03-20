<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class City extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('CityModel','StateModel'));
	}

	public function csvExport() {
		$filename = 'city_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 

		// get data
		$usersData = $this->CityModel->exportCityDetails();

		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","City name","State id","Country id","State name","Country name");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;

	} 

	public function getStateNameById(){
		$data = $this->input->post();
		$json = array();
        $json = $this->CityModel->getAllState($data['country_id']);
        header('Content-Type: application/json');
        echo json_encode($json);
	}

	public function addCity() {
		$submit = $this->input->post();
		$data['country_list'] 	= $this->StateModel->getAllCountryName();
		if(!empty($submit)) {
			$data = array();
			try {
				$data = validatePostData($submit);
			} catch (Exception $e) {
				$this->session->set_flashdata('cat_error','Invalid data');
				redirect('city/addCity');
			}

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('country_id', 'Country Name', 'trim|required');
			$this->form_validation->set_rules('state_id', 'State Name', 'trim|required');
			$this->form_validation->set_rules('city_name', 'City Name', 'trim|required');
			$this->form_validation->set_rules('latitude', 'Latitude', 'trim');
			$this->form_validation->set_rules('longitude', 'Longitude', 'trim');

			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('login_error',validation_errors());
				redirect('city/addCity');
	        }
	        if(!empty($_FILES['files']['name'])) {
				$upload_folder = 'cityImage';
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
					redirect('city/addCity');
				}
				$data['city_image'] = $this->upload->data('orig_name');	
	        }

			$insert =	$this->CityModel->insert('auto_city',$data);
			if($insert) {
				$this->session->set_flashdata('cat_success','Category added successfully');
				redirect('city/viewCity');
			} else {
				$this->session->set_flashdata('cat_error','Failed to add data');
				redirect('city/addCity');
			}
		}
		$data["page_title"] 	= "Add City Information";
        $page["layout_content"] = $this->load->view('pages/city/addCity', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function viewCity(){
		$catFormat 				= 	$this->cityDetailsFormat();
		$data['columns']		=	$catFormat['cat_column'];
		$data['catData']		=	$catFormat['city_name'];
		$data["page_title"] 	=   "View City Information";
        $page["layout_content"] =   $this->load->view('pages/city/viewCity', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/city/city',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function cityDetailsFormat() {

		$retData['cat_column'] = array("Country Name","State Name","Image","City Name","Latitude","Longitude","Status","Action");
		$path = VIEW_IMAGE_URL.'cityImage/';
		$no_image = VIEW_IMAGE_URL.'noimage.png';
        $retData['city_name'] = "[
        	{ 'data' : 'country_name' },
        	{ 'data' : 'state_name' },
        	{
        		'render': function (data, type, row, meta) {
        			let imgPath = row.city_image;
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
        	{ 'data' : 'city_name' },
        	{ 'data' : 'latitude' },
        	{ 'data' : 'longitude' },
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

	public function cityDetails_ajax() {
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
   				$result = $this->CityModel->getAllCityDetails($limit,$offset,$search);
   				
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

	public function editCity() {
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('City/viewCity');
		}
		$data['country_list'] 	=   $this->StateModel->getAllCountryName();
		$res = $this->CityModel->getCityDetailsById($id);
		$data['fetch_data'] 	= 	$res['fetch_data'][0];
		$data['state_list']		=   $this->CityModel->getAllState($res['fetch_data'][0]['country_id']);
		$data['city_id'] 		= 	$id;
		$data["page_title"]		=	"Edit City Details";
        $page["layout_content"]	=	$this->load->view('pages/city/editCity', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function updateCity() {
		$data = $this->input->post();
		$data = validatePostData($data);
		$id = !empty($data['city_id'])?$data['city_id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('cat_error',"Failed to update");	
			redirect('City/editCity/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('user_error',"Failed to update");	
			redirect('city/editCity/'.$id);
		}			
		$this->form_validation->set_data($data);
        $this->form_validation->set_rules('country_id', 'Country Name','trim|required');
      	$this->form_validation->set_rules('state_id', 'State Name','trim|required');
      	$this->form_validation->set_rules('city_name', 'City Name','trim|required');
		$this->form_validation->set_rules('latitude', 'Latitude','trim');
		$this->form_validation->set_rules('longitude', 'Longitude','trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('cat_error',validation_errors());
            redirect('city/editCity/'.$id);
        }

    	if(!empty($_FILES['files']['name'])) {

			$upload_folder = 'cityImage';
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
				redirect('City/editCity/'.$id);
			}
			$data['city_image'] = $this->upload->data('orig_name');	
        }
        unset($data['city_id']);
        $data['updated_at'] = date('Y-m-d H:i:s');
     	$response = $this->CityModel->updateCityDetails($data,$id);
		if($response) {
			$this->session->set_flashdata('cat_success','Update Successfull');
			redirect('city/viewCity');
		} else {
			$this->session->set_flashdata('cat_error','Failed to update. Please try again');
			redirect('city/editCat/'.$id);
		}
	}

	/*	delete City Details 	*/
	public function deleteCityDetails() {

		$postData = $this->input->post();
		$id = !empty($postData['city_id'])?$postData['city_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->CityModel->deleteCityDetails($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
	}

	/*	Change City Status 	*/
	public function updateStatus() {

		$postData = $this->input->post();
		$id = !empty($postData['city_id'])?$postData['city_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->CityModel->changeStatus($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */