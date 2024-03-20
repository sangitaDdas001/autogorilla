<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class State extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model('StateModel');
	}

	public function csvExport() {
		$filename = 'state_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 

		// get data
		$usersData = $this->StateModel->exportStateDetails();

		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","country_id","state_name");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;

	}

	public function addState() {
		$data   = array();
		$submit = $this->input->post();
		$data['country_list'] = $this->StateModel->getAllCountryName();
		if(!empty($submit)) {
			$data 		= $submit;
			$finalArr 	= [];
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('country_id', 'Country Name', 'trim|required');
			$this->form_validation->set_rules('state_name[]', 'State Name', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('login_error',validation_errors());
				redirect('state/addState');
	        }
	        
	        if(!empty($data['state_name'])){
		        foreach ($data['state_name'] as $key => $value) {
		        	$finalArr[] = array(
		        		'country_id' => $data['country_id'],
		        		'state_name' => $value
		        	);
			    }
			    $insert =	$this->StateModel->insert('auto_state',$finalArr);
			    if($insert) {
					$this->session->set_flashdata('cat_success','State added successfully');
					redirect('state/viewState');
				} else {
					$this->session->set_flashdata('cat_error','Failed to add data');
					redirect('state/addState');
				}
			}
		}
		$data["page_title"] 	= "Add State Information";
        $page["layout_content"] = $this->load->view('pages/state/addState', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function viewState(){
		$stateFormat 		= 	$this->stateDetailsFormat();
		$data['columns']	=	$stateFormat['state_column'];
		$data['stateData']	=	$stateFormat['state_name'];
		$data["page_title"] 	= "View State Information";
        $page["layout_content"] = $this->load->view('pages/state/viewState', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/state/state',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	} 

	private function stateDetailsFormat() {
		$retData['state_column'] = array("Country Name", "State Name","Status", "Action");
        $retData['state_name'] = "[
        	{ 'data' : 'country_name' },
        	{ 'data' : 'state_name' },
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

	public function stateDetails_ajax() {
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
   				$result = $this->StateModel->getAllStateDetails($limit,$offset,$search);
   				
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

	public function updateStatus() {
		$postData = $this->input->post();
		$id = !empty($postData['state_id'])?$postData['state_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->StateModel->changeStatus($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function deleteStateDetails(){
		$postData = $this->input->post();
		$id = !empty($postData['state_id'])?$postData['state_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->StateModel->deleteStateDetails($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
	}

	public function editState() {
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('State/viewState');
		}
		$data['country_list'] 	=   $this->StateModel->getAllCountryName();
		$res = $this->StateModel->getStateDetailsById($id);
		$data['fetch_data'] 	= 	$res['fetch_data'][0];
		$data['state_id'] 		= 	$id;
		$data["page_title"]		=	"Edit State Details";
        $page["layout_content"]	=	$this->load->view('pages/state/editState', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	} 

	public function updateState() {
		$data = $this->input->post();
		$data = validatePostData($data);
		$id = !empty($data['state_id'])?$data['state_id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('cat_error',"Failed to update");	
			redirect('State/editState/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('user_error',"Failed to update");	
			redirect('state/editState/'.$id);
		}			
		$this->form_validation->set_data($data);
        $this->form_validation->set_rules('country_id', 'Country Name','trim|required');
         $this->form_validation->set_rules('state_name', 'State Name','trim|required');
      
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('cat_error',validation_errors());
            redirect('state/editState/'.$id);
        }

        unset($data['state_id']);
        $data['updated_at'] = date('Y-m-d H:i:s');
     	$response = $this->StateModel->updateStateDetails($data,$id);
		if($response) {
			$this->session->set_flashdata('cat_success','Update Successfull');
			redirect('state/viewState');
		} else {
			$this->session->set_flashdata('cat_error','Failed to update. Please try again');
			redirect('state/editState/'.$id);
		}
	}


}

/* End of file State.php */
/* Location: ./application/controllers/State.php */