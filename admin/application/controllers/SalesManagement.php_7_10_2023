<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesManagement extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('SalesModel','UserModel'));
	}

	public function getCityList(){
		$postData = $this->input->post();
		$getStateWiseList = $this->SalesModel->stateWiseCityList($postData['state']);
		if(!empty($getStateWiseList)){
			echo json_encode(array('status'=>true,'msg'=>'record found','data'=>$getStateWiseList));
		}else{
			echo json_encode(array('status'=>false,'msg'=>'record not found','data'=>''));
		}
	}
	public function getPostList(){
		$postData = $this->input->post();
		$getStateWiseList = $this->SalesModel->departmentWisePostList($postData['department_id']);

		if(!empty($getStateWiseList)){
			echo json_encode(array('status'=>true,'msg'=>'record found','data'=>$getStateWiseList));
		}else{
			echo json_encode(array('status'=>false,'msg'=>'record not found','data'=>''));
		}
	}
	public function getAreaList(){
		$postData = $this->input->post();
		$getCityWiseAreaList = $this->SalesModel->cityWiseAreaList($postData['cities']);
		if(!empty($getCityWiseAreaList)){
			echo json_encode(array('status'=>true,'msg'=>'record found','data'=>$getCityWiseAreaList));
		}else{
			echo json_encode(array('status'=>false,'msg'=>'record not found','data'=>''));
		}
	}

	public function addSalesUser(){
		$submit = $this->input->post(); 
		if(!empty($submit)) {
			$data =  $submit;
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('manager_id','Manager', 'trim');
			$this->form_validation->set_rules('first_name','First Name', 'trim|required');
			$this->form_validation->set_rules('middle_name','Middle Name', 'trim');
			$this->form_validation->set_rules('last_name','Last Name', 'trim|required');
			$this->form_validation->set_rules('email','Email', 'trim|valid_email|xss_clean|required|callback_checkEmail',array('checkEmail' => 'Email id is already exist,Please try again.'));
			$this->form_validation->set_rules('mobile','Mobile', 'trim|required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('password','Password', 'trim|required');
			$this->form_validation->set_rules('department_id','department', 'required');
			$this->form_validation->set_rules('state_id[]','State', 'required');
			$this->form_validation->set_rules('city_id[]','City', 'trim');
			$this->form_validation->set_rules('area_id[]','Area', 'trim');

			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('user_error',validation_errors());
				redirect('SalesManagement/addSalesUser');
	        }
	        
	    
          	$userdata = array(
          		'first_name'  		=> $data['first_name'],
          		'middle_name' 		=> $data['middle_name'],
          		'last_name'   		=> $data['last_name'],
          		'name'   	  		=> $data['first_name'].' '.$data['middle_name'].' '.$data['last_name'],
          		'email'   	  		=> $data['email'],
          		'mobile'   	  		=> $data['mobile'],
          		'password'    		=> password_hash($data['password'],PASSWORD_DEFAULT),
          		'password_hint'    	=> $data['password'],
          		'department_id'   	=> !empty($data['department_id'])?$data['department_id']:'',
          		'manager_id'   		=> !empty($data['manager_id'])?$data['manager_id']:1,
          		'state_id'   		=> implode(',', $data['state_id']),
          		'city_id'   		=> !empty($data['city_id'])?implode(',', $data['city_id']):'',
          		'area_id'   		=> !empty($data['area_id'])?implode(',', $data['area_id']):'',
          		'post'   			=> !empty($data['post_id'])?$data['post_id']:'',
          	);

          	if(!empty($_FILES['image']['name'])) {
				$upload_folder = 'salesuser';
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
					$this->session->set_flashdata('user_error',$errors);
					redirect('salesManagement/add-sales-user');
				}
				$userdata['user_img'] = $this->upload->data('orig_name');	
	        }
        
			$insert =	$this->UserModel->insert('auto_admin',$userdata);
			if($insert) {
				
				$this->session->set_flashdata('user_success','User registered successfully');
				redirect('userManagement/user-menu-permission/'.base64_encode($insert).'/salesuser');
			} else {
				$this->session->set_flashdata('user_error','Failed to add data');
				redirect('salesManagement/add-sales-user');
			}
		}

		$data["page_title"] 			= "Add Sales User Information";
		$data['department_list'] 		= $this->UserModel->getDepartmentLit_forSales();
		$data['state_list'] 			= $this->SalesModel->getStateList();
		$data['get_all_sales_user'] 	= $this->SalesModel->getAllSalesUser();
        $page["layout_content"] 		= $this->load->view('pages/sales/addSalesUser', $data, true);
        $page["script_files"]	    	= $this->load->view('scripts/sales/sales',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function checkEmail($email){
		$checkData = $this->UserModel->check_email(trim($email));
		if(!empty($checkData)){
			return false;die;
		}else{
			return true;
		}
	}

	public function viewSalesUser(){
		$postData = $this->input->post();
		$admin_id = 0;$manager_id = 0;
		$data["page_title"] 	= 	"View Sales User Information";
		$page["layout_content"] = 	$this->load->view('pages/sales/viewSalesUser', $data, true);
		$page["script_files"]	=	$this->load->view('scripts/sales/sales',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function viewTelecallerUser(){
		$data["page_title"] 	= 	"View Telecaller user Information";
		$page["layout_content"] = 	$this->load->view('pages/sales/viewTelecallerUser', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function get_users_list_treeview_ajax(){
		$postData = $this->input->post();
		
		if(!empty($postData)){
			$admin_id = 0;$manager_id = 0;
			$fetchData = $this->SalesModel->get_users_list_treeview($admin_id,$postData['department_id'],$manager_id);
			if(!empty($fetchData)){
				$status_str ='success';  
		        $result["status"] = $status_str;
		        $result["html"]= $fetchData;
		        echo json_encode($result);
		        exit(0);
			}
		}
	}

	public function editSalesUser(){
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('salesManagement/viewSalesUser');
		}
		$data["page_title"] 		= "Edit sales User Information";
		$data['department_list'] 	= $this->UserModel->getDepartmentLit_forSales();
		$data['state_list'] 		= $this->SalesModel->getStateList();
		$data['get_all_sales_user'] = $this->SalesModel->getAllSalesUserWithOutEditId($id);
		$data['fetch_data'] 		= $this->SalesModel->getSalesDetailsById($id);
		$data['city_list'] 			= $this->SalesModel->getCityListStateWise($data['fetch_data'][0]['state_id']);
		$data['area_list'] 			= $this->SalesModel->getAreaListcityWise($data['fetch_data'][0]['city_id']);
		$data['designation_list'] 	= $this->SalesModel->departmentWisePostList($data['fetch_data'][0]['department_id']);
		$data['user_id'] 			= $id;
        $page["layout_content"] 	= $this->load->view('pages/sales/edit_sales_user', $data, true);
        $page["script_files"]	    = $this->load->view('scripts/sales/sales',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function updateSalesUser(){
		$postData = $this->input->post();
		$id = $postData['user_id'];
		if(validateBase64($postData['user_id']) == false) {
			$this->session->set_flashdata('user_error',"Failed to update");	
			redirect('SalesManagement/editSalesUser/'.$postData['user_id']);	
		}		

		$this->form_validation->set_data($postData);
		
        $this->form_validation->set_rules('manager_id', 'Manager','trim|required');
        $this->form_validation->set_rules('first_name', 'First Name','trim|required');
        $this->form_validation->set_rules('middle_name', 'Middle Name','trim');
        $this->form_validation->set_rules('last_name', 'Last Name','trim|required');
        $this->form_validation->set_rules('email', 'Email','trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile','trim|required');
        $this->form_validation->set_rules('password', 'Password','trim|required');
        $this->form_validation->set_rules('department_id', 'Department','trim|required');
        $this->form_validation->set_rules('state_id[]','State', 'required');
		$this->form_validation->set_rules('city_id[]','City', 'trim');
		$this->form_validation->set_rules('area_id[]','Area', 'trim');
		$this->form_validation->set_rules('post','Post', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('user_error',"Failed to update");	
			redirect('SalesManagement/editSalesUser/'.$postData['user_id']);	
        }

        if(!empty($_FILES['image']['name'])) {
			$upload_folder = 'salesuser';
	        if (!file_exists(UPLOAD_IMAGE_URL) && !is_dir(UPLOAD_IMAGE_URL)) { 
	            mkdir(UPLOAD_IMAGE_URL, 0777, true);
	        }

	        // check uploaded path folder is present or not other wise create it 
	        if (!empty($upload_folder) && (!file_exists(UPLOAD_IMAGE_URL.$upload_folder))) {
				mkdir(UPLOAD_IMAGE_URL.$upload_folder, 0777, true);
			}

			$config['upload_path'] 		= 	UPLOAD_IMAGE_URL.$upload_folder;
			$config['allowed_types']	= 	'jpg|png|jpeg';
			$config['max_size']			=	'5120';
			$config['file_name']		=	strtolower($upload_folder).'_'.rand(10,99999).time();
			
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('image')){
				
				$errors = $this->upload->display_errors();
				$this->session->set_flashdata('user_error',$errors);
				redirect('salesManagement/add-sales-user');
			}
			$postData['user_img'] = $this->upload->data('orig_name');	
	   	}

        
		$postData['name']			=  !empty($postData['middle_name'])?($postData['first_name'].''.$postData['middle_name'].''.$postData['last_name']) : ($postData['first_name'].''.$postData['last_name']);
		$postData['password_hint'] 	=  $postData['password'];
		$postData['password'] 		=  password_hash($postData['password'],PASSWORD_DEFAULT);
		$stateArr = []; $cityArr =[]; $areaArr =[];
		foreach($postData['state_id'] as $state){
			$stateArr[] = $state;
		}
		foreach($postData['city_id'] as $city){
			$cityArr[] = $city;
		}
		foreach($postData['area_id'] as $area){
			$areaArr[] = $area;
		}
		$postData['state_id'] 		=  implode(',', $stateArr);
		$postData['city_id'] 		=  implode(',', $cityArr);
		$postData['area_id'] 		=  implode(',', $areaArr);
		$postData['modified_at'] 	=  date('Y-m-d H:i:s');

		unset($postData['user_id']);
		unset($postData['submit']);

		
     	$response = $this->SalesModel->update($postData,$id);
     	if($response){
     		$this->session->set_flashdata('user_success',"successfully updated");	
			redirect('salesManagement/viewSalesUser');	
     	} else {
     		$this->session->set_flashdata('user_error',"Failed to update");	
			redirect('SalesManagement/editSalesUser/'.$postData['user_id']);
     	}
	}

	public function deleteSalesUser(){
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('salesManagement/viewSalesUser');
		}
		$delete = $this->SalesModel->deleteUser($id);
		if($delete){
			$this->session->set_flashdata('user_success','User deleted successfully');
			redirect('salesManagement/viewSalesUser');
		} else {
			$this->session->set_flashdata('user_error','Failed to delete data');
			redirect('salesManagement/viewSalesUser');
		}
	}

	public function assignedCompanyListForUser(){
		$userid = $this->uri->segment('3');
		if(empty($userid)) {
			redirect('salesManagement/viewSalesUser');
		}

		$assignedCompanyFormat 	= 	$this->assignedCompanyDetailsFormat();
		$data['columns']		=	$assignedCompanyFormat['company_column'];
		$data['companyData']	=	$assignedCompanyFormat['company_name'];
		$data['userId']			=	$userid;
		$data["page_title"] 	= 	"View Assigned Company Information";
		$page["layout_content"] = 	$this->load->view('pages/sales/assignedCompanyList', $data, true);
        $page["script_files"]	=   $this->load->view('scripts/sales/sales',$data, true);
       $this->load->view('layouts/datatable_layout', $page);
	}

	private function assignedCompanyDetailsFormat() {
		$retData['company_column'] = array("Company Id","Company Name","State Name","Company Vendor Name","Email","Verified","Feature","Created Date");
        $retData['company_name'] = "[
        	{ 'data' : 'id' },
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let companyName = row.company_name;
        				html += '<a class=\"catlog_info\" href=\"javascript:void(0)\">';
	        			html += companyName;
	        			html += '<a/>';
	        		return html;
        		}
        		
        	},
        	{ 'data' : 'state_name' },
        	{ 'data' : 'name' },
        	{ 'data' : 'email' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.autogorilla_verified == 'Y') {
        				return '<span  style=\"color: #008000;\">Yes</span>';
        			} else {
        				return '<span  style=\"color: #1241ab;\">No</span>';
        			}
        		}
        	},
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.featured_company == 0) {
        				return '<span class=\"badge badge-warning \" style=\"background-color: #958686e3;\">Feature</span>';
        			}  else {
        				return '<span class=\"badge badge-warning \" style=\"background-color: #0a4c2ed1;\">Featured</span>';
        			}
        		}
        	},
        	{ 'data' : 'created_at' },
    	]";
	
        return $retData;
	}

	public function companyDetails_ajax() {
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
   				$result = $this->SalesModel->getCompanyInfoById($limit,$offset,$search,$data['admin_user_id']);
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

	public function exportCsv($userId){
		$filename = 'salesUserCompanyAssignlist_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 


		// get data
		$usersData = $this->SalesModel->exportCsvInfo($userId);
		// file creation
		$file = fopen('php://output', 'w');

		$header = array("Company id","Company name","State Name","User name","Email");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
			fputcsv($file,$line);
		}

		fclose($file);
		exit;
	}
}