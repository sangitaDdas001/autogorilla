<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model('ServiceModel');
	}

	public function ServicePlan(){
		$supplierProductFormat 	= 	$this->servicePlanFormat();
		$data['columns']		=	$supplierProductFormat['service_column'];
		$data['serviceData']	=	$supplierProductFormat['service_plan'];
		$data["page_title"] 	= 	"View Service Plan Information";
		$page["layout_content"] = 	$this->load->view('pages/service/viewService', $data, true);
        $page["script_files"]	= 	$this->load->view('scripts/service/service_script',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function servicePlanFormat(){
		$retData['service_column'] = array("Service Logo","Service Package Name","Created Date","Status","Action");
        $path     = VIEW_IMAGE_URL.'serviceLogo/';
		$no_image = VIEW_IMAGE_URL.'noimage.png';

        $retData['service_plan'] = "[
        {
        		'render': function (data, type, row, meta) {
        			let imgPath = row.logo;
        			let html = '';
        			if(imgPath) {
        				html += '<img src=';
	        			html += '".$path."';
	        			html += imgPath;
	        			html += ' class=\"img-thumbnail\" width=\"45\" height=\"46\" />';
        			} else {
        				html += '<img src=';
	        			html += '".$no_image."';
	        			html += ' class=\"img-thumbnail\" width=\"45\" height=\"46\" />';
        			}
                    return html;
                }
        	},

        	{ 'data' : 'service_plan'},
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_service_status cursor-pointer\">Active</span>';
        			} else {
        				return '<span class=\"badge badge-warning change_service_status cursor-pointer\">Inactive</span>';
        			}
        		}
        	},

			{ 'defaultContent' : '<div class=\"dropdown\"><a class=\"btn btn-secondary dropdown-toggle\" href=\"#\" role=\"button\" id=\"dropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fs-14 fa fa-bars\"></i></a><ul class=\"dropdown-menu dropdown-menu-right\" aria-labelledby=\"about-us\"><li style=\"text-align: center;\"><a class=\"dropdown-item edit_info\" href=\"javascript:void(0)\" data-toggle=\"modal\" style=\"color: blue;\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;\" aria-hidden=\"true\"></i>Edit</a></li><li style=\"text-align: center;\"><a class=\"dropdown-item delete_info\" href=\"javascript:void(0)\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;\" aria-hidden=\"true\"></i>Delete</a></li></ul></div>'
			}

    	]";
        return $retData;
	}

	public function serviceDetails_ajax() {
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
   				$result = $this->ServiceModel->getAllPlan($limit,$offset,$search);
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

	public function addServicePlan(){
		$submit = $this->input->post();
		if(!empty($submit)) {
			$data = array();
			try {
				$data = validatePostData($submit);
			} catch (Exception $e) {
				$this->session->set_flashdata('service_error','Invalid data');
				redirect('service/addServicePlan');
			}

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('service_plan','Service Plan', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('service_success',validation_errors());
				redirect('service/addServicePlan');
	        }
	        $upload_folder = 'serviceLogo';

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
			if (!$this->upload->do_upload('logo')){ //name of file in html
				
				$errors = $this->upload->display_errors();
				$this->session->set_flashdata('service_error',$errors);
				redirect('service/addServicePlan');
       
			}
			$data['logo'] = $this->upload->data('orig_name');
          	unset($data['submit']);
			$insert =	$this->ServiceModel->insert('service_package',$data);
			
			if($insert) {
				$this->session->set_flashdata('service_success','Service Plan added successfully');
				redirect('service/servicePlan');
			} else {
				$this->session->set_flashdata('service_success','Failed to add data');
				redirect('service/addServicePlan');
			}
		}
		$data["page_title"] 	= "Add Service Plan Information";
        $page["layout_content"] = $this->load->view('pages/service/addServicePlan', $data, true);
        $page["script_files"]	= $this->load->view('scripts/service/service_script',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function editServicePlan() {
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('service/servicePlan');
		}
		$res = $this->ServiceModel->getServicePlanDetailsById($id);
		$data['fetch_data'] 	= 	$res[0];
		$data['id'] 			= 	$id;
		$data["page_title"]		=	"Edit Service Details";
        $page["layout_content"]	=	$this->load->view('pages/service/editServicePlan', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/service/service_script',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function updateServicePlan(){
		$data = $this->input->post();
		$data = validatePostData($data);
		$id = !empty($data['id'])?$data['id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('service_error',"Failed to update");	
			redirect('service/editServicePlan/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('service_error',"Failed to update");	
			redirect('service/editServicePlan/'.$id);
		}			
		$this->form_validation->set_data($data);
        $this->form_validation->set_rules('service_plan', 'Service Plan','trim|required');
      
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('service_error',validation_errors());
            redirect('service/editServicePlan/'.$id);
        }
      
        if(!empty($_FILES['logo']['name'])) {
			$upload_folder = 'serviceLogo';
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
			if (!$this->upload->do_upload('logo')){
				
				$errors = $this->upload->display_errors();
				$this->session->set_flashdata('service_error',$errors);
				redirect('service/editServicePlan/'.$id);
			}
			$data['logo'] = $this->upload->data('orig_name');	
        }

        unset($data['id']);
        $data['update_at'] = date('Y-m-d H:i:s');
        $table = "service_package";
        $filedName = "id";

     	$response = $this->ServiceModel->update($data,$filedName,$id,$table);
		if($response) {
			$this->session->set_flashdata('service_success','Update Successfull');
			redirect('service/servicePlan');
		} else {
			$this->session->set_flashdata('service_error','Failed to update. Please try again');
			redirect('service/editServicePlan/'.$id);
		}
	}

	public function ChangeStatus(){
		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->ServiceModel->changeStatus($postData);
			if ($response) {
				$this->session->set_flashdata('service_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}
	
	public function servicePlanDelete(){
		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}

		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$table="service_package";
			$response = $this->ServiceModel->delete($table,$postData);
			if ($response) {
				$this->session->set_flashdata('service_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
	}
}


/* End of file Service.php */
/* Location: ./application/controllers/Service.php */

?>