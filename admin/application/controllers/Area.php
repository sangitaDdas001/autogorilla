<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('AreaModel'));
	}

	public function csvExport() {
		$filename = 'area_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 

		// get data
		$usersData = $this->AreaModel->exportAreaDetails();

		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","Area name","city name","city id");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;

	} 


	public function index(){
		$areaFormat 			= 	$this->areaNameDetailsFormat();
		$data['columns']		=	$areaFormat['cat_column'];
		$data['areaData']		=	$areaFormat['area_name'];
		$data["page_title"] 	=   "View Area Information";
        $page["layout_content"] =   $this->load->view('pages/area/area', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/area/area_script',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function areaNameDetailsFormat() {
		$retData['cat_column'] = array("Area","City","Created Date", "Status","Action");
        $retData['area_name'] = "[
        	{ 'data' : 'area_name' },
        	{ 'data' : 'city_name' },
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

	public function areaNameDetails_ajax() {
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
   				$result = $this->AreaModel->getAreaData($limit,$offset,$search);
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

	public function updateAreaStatus() {
		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->AreaModel->changeStatus($postData);
			if ($response) {
				$this->session->set_flashdata('area_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				$this->session->set_flashdata('area_error',"Successfully updated");	
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function editArea() {
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('area');
		}
		$data['city_list'] = $this->AreaModel->fetchCityRecord();
		$res = $this->AreaModel->getAreaDetailsById($id);
		$data['fetch_data'] 	= 	$res[0];
		$data['area_id'] 		= 	$id;
		$data["page_title"]		=	"Edit Area Details";
        $page["layout_content"]	=	$this->load->view('pages/area/editArea', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/area/area_script',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function updateArea() {
		$data = $this->input->post();
		$id = !empty($data['id'])?$data['id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('area_error',"Failed to update");	
			redirect('area/editArea/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('area_error',"Failed to update");	
			redirect('banner/editBanner/'.base64_encode($id));
		}			

		$this->form_validation->set_data($data);
    	$this->form_validation->set_rules('city_id', 'city name', 'trim|required');
    	$this->form_validation->set_rules('area_name', 'Area Name', 'trim|required');
		
    	if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('area_error',validation_errors());
            redirect('banner/editBanner/'.base64_encode($id));
        }
    
        unset($data['id']);
        unset($data['submit']);
        $data['updated_at'] = date('Y-m-d H:i:s');
     	$response = $this->AreaModel->update($data,$id,'auto_city_area');

		if($response) {
			$this->session->set_flashdata('area_success','Update Successfull');
			redirect('area');
		} else {
			$this->session->set_flashdata('area_error','Failed to update. Please try again');
			redirect('banner/editBanner/'.$id);
		}
	}

	public function deleteArea(){
		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->AreaModel->deleteArea($postData);
			if ($response) {
				$this->session->set_flashdata('area_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				$this->session->set_flashdata('area_error',"Successfully deleted");	
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
	}

	public function addArea(){
		$submit = $this->input->post();
		if(!empty($submit)) {
			$data = array();
			try {
				$data = validatePostData($submit);
			} catch (Exception $e) {
				$this->session->set_flashdata('area_error','Invalid data');
				redirect('area/addArea');
			}

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('city_id','City name', 'trim|required');
			$this->form_validation->set_rules('area_name', 'Area Name', 'trim|required');

			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('area_error',validation_errors());
				redirect('area/addArea');
	        }
          	unset($data['submit']);
			$insert =	$this->AreaModel->insert('auto_city_area',$data);
			
			if($insert) {
				$this->session->set_flashdata('area_success','Area added successfully');
				redirect('area');
			} else {
				$this->session->set_flashdata('area_error','Failed to add data');
				redirect('area/addArea');
			}
		}
		$data["page_title"] 	= "Add Area Information";
		$data['city_list'] 		= $this->AreaModel->fetchCityRecord();
        $page["layout_content"] = $this->load->view('pages/area/addArea', $data, true);
        $page["script_files"]	= $this->load->view('scripts/area/area_script',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

}

/* End of file Area.php */
/* Location: ./application/controllers/Area.php */