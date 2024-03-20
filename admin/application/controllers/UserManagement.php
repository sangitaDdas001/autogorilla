<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserManagement extends CI_Controller {
	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('SupplierModel','UserModel','DepartmentModel'));
	}

	public function addUser(){
		$submit = $this->input->post(); 
		if(!empty($submit)) {
			$data =  $submit;
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('first_name','First Name', 'trim|required');
			$this->form_validation->set_rules('middle_name','Middle Name', 'trim');
			$this->form_validation->set_rules('last_name','Last Name', 'trim|required');
			$this->form_validation->set_rules('email','Email', 'trim|valid_email|xss_clean|required|callback_checkEmail',array('checkEmail' => 'Email id is already exist,Please try again.'));
			$this->form_validation->set_rules('mobile','Mobile', 'trim|required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('password','Password', 'trim|required');
			$this->form_validation->set_rules('department_id[]','department', 'required');

			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('user_error',validation_errors());
				redirect('userManagement/add-user');
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
          		'department_id'   	=> implode(',', $data['department_id']),
          	);

			$insert =	$this->UserModel->insert('auto_admin',$userdata);
			if($insert) {
				$this->session->set_flashdata('user_success','User registered successfully');
				redirect('userManagement/user-menu-permission/'.base64_encode($insert));
			} else {
				$this->session->set_flashdata('user_error','Failed to add data');
				redirect('userManagement/addUser');
			}
		}
		$data['department_list'] 	=   $this->UserModel->getDepartmentLit();
		$data["page_title"] 		=   "User Registration";
        $page["layout_content"] 	=   $this->load->view('pages/usermanagement/add-user', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function viewUserList(){
		$userFormat 			= 	$this->allUserDetailsFormat();
		$data['columns']		=	$userFormat['user_column'];
		$data['userData']	    =	$userFormat['name'];
		$data["page_title"] 	= 	"View User Information";
		$page["layout_content"] = 	$this->load->view('pages/usermanagement/user-list', $data, true);
        $page["script_files"] 	= 	$this->load->view('scripts/users/user_scripts', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function inactiveUserList(){
		$userFormat 			= 	$this->userDetailsFormat();
		$data['columns']		=	$userFormat['user_column'];
		$data['userData']	    =	$userFormat['name'];
		$data["page_title"] 	= 	"View Inactive User Information";
		$page["layout_content"] = 	$this->load->view('pages/usermanagement/inactive-user', $data, true);
        $page["script_files"] 	= 	$this->load->view('scripts/users/user_scripts', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function activeUserList(){
		$userFormat 			= 	$this->userDetailsFormat();
		$data['columns']		=	$userFormat['user_column'];
		$data['userData']	    =	$userFormat['name'];
		$data["page_title"] 	= 	"View Anactive User Information";
		$page["layout_content"] = 	$this->load->view('pages/usermanagement/active-user', $data, true);
        $page["script_files"] 	= 	$this->load->view('scripts/users/user_scripts', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function userDetailsFormat() {
		$retData['user_column'] = array("User Name","Email","Menu Permission","Department Name","Status","Assign Product List","Action");
        $retData['name'] = "[
        	{ 'data' : 'name' },
        	{ 'data' : 'email' },
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let assigned_menu = row.assigned_menu;

        			var foo = String(assigned_menu);
        			var arr = foo.split(',');

        			if (row.assigned_submenu_menu == 'sp_1' && row.status == 'Active' && row.numberOfCompany > 0){
        				for (let i = 0; i < arr.length; i++) {
						  html += '<i class=\"fa fa-dot-circle-o mri-4\" aria-hidden=\"true\"></i>'+ arr[i] + '<br>';
						}
	        			//html += assigned_menu;
        				html += '<span class=\"badge badge-success assign_company cursor-pointer\" >Assigned Company</span>';
	        		} else {
	        			
	        			for (let i = 0; i < arr.length; i++) {
						  html += '<i class=\"fa fa-dot-circle-o mri-4\" aria-hidden=\"true\"></i>'+ arr[i] + '<br>';
						}
						
	        			//html += assigned_menu;
	        		}
	        		return html;
        		}
        		
        	},


        	{ 'data' : 'depatment_name' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\" >Active</span>';
        			}  else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Inactive</span>';
        			}
        		}
        	},
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.product_count > 0) {
        				return '<span class=\"badge badge-warning user_assigned_product_list cursor-pointer\" style=\"margin-left: 10px; background-color:#585653\";>Assigned Product List</span>';
        			}  else {
        				return '<span class=\"badge badge-warning \" style=\"background-color:#c77d0fbf\";>Product not assigned</span>';
        			}
        		}
        	},

        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active' ){
        				return '<a class=\"user_management_info\" href=\"javascript:void(0)\" title=\"User Management\" data-toggle=\"modal\" style=\"color: #766969;\"><i class=\"fa fa-user\" style=\"margin-right: 8px;font-size: 20px;\" aria-hidden=\"true\"></i></a><a class=\"edit_info\" href=\"javascript:void(0)\" title=\"Edit User\" data-toggle=\"modal\" style=\"color: black;\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a><a class=\"delete_info\" href=\"javascript:void(0)\" title=\"Delete User\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a><a class=\"product_info\" href=\"javascript:void(0)\" title=\"Product assign to user\" data-toggle=\"modal\" style=\"color: #08c;\"><i class=\"fa fa-product-hunt\" style=\"margin-right: 3px;font-size: 17px;\" aria-hidden=\"true\"></i></a>';
        			} else {
        				return  '<a class=\"delete_info\" href=\"javascript:void(0)\" title=\"Delete User\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a>';
        			}
        		}
        	},

        	
    	]";

        return $retData;
	}

	private function allUserDetailsFormat() {
		$retData['user_column'] = array("User Name","Email","Menu Permission","Department Name","Status","Assign Product List","Action");
        $retData['name'] = "[
        	{ 'data' : 'name' },
        	{ 'data' : 'email' },
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let assigned_menu = row.assigned_menu;

        			var foo = String(assigned_menu);
        			var arr = foo.split(',');

        			if (row.assigned_submenu_menu == 'sp_1' && row.status == 'Active' && row.numberOfCompany > 0){
        				for (let i = 0; i < arr.length; i++) {
						  html += '<i class=\"fa fa-dot-circle-o mri-4\" aria-hidden=\"true\"></i>'+ arr[i] + '<br>';
						}
	        			//html += assigned_menu;
        				html += '<span class=\"badge badge-success assign_company cursor-pointer\" >Assigned Company</span>';
	        		} else {
	        			
	        			for (let i = 0; i < arr.length; i++) {
						  html += '<i class=\"fa fa-dot-circle-o mri-4\" aria-hidden=\"true\"></i>'+ arr[i] + '<br>';
						}
						
	        			//html += assigned_menu;
	        		}
	        		return html;
        		}
        		
        	},


        	{ 'data' : 'depatment_name' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\" >Active</span>';
        			}  else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Inactive</span>';
        			}
        		}
        	},
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.product_count > 0) {
        				return '<span class=\"badge badge-warning user_assigned_product_list cursor-pointer\" style=\"margin-left: 10px; background-color:#585653\";>Assigned Product List</span>';
        			}  else {
        				return '<span class=\"badge badge-warning \" style=\"background-color:#c77d0fbf\";>Product not assigned</span>';
        			}
        		}
        	},

        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active' ){
        				return '<a class=\"user_management_info\" href=\"javascript:void(0)\" title=\"User Management\" data-toggle=\"modal\" style=\"color: #766969;\"><i class=\"fa fa-user\" style=\"margin-right: 8px;font-size: 20px;\" aria-hidden=\"true\"></i></a><a class=\"edit_info\" href=\"javascript:void(0)\" title=\"Edit User\" data-toggle=\"modal\" style=\"color: black;\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a><a class=\"delete_info\" href=\"javascript:void(0)\" title=\"Delete User\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a>';
        			} else {
        				return  '<a class=\"delete_info\" href=\"javascript:void(0)\" title=\"Delete User\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a>';
        			}
        		}
        	},

        	
    	]";

        return $retData;
	}

	public function allUsers_ajax(){
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
   				$result = $this->UserModel->getAllUserDetails($limit,$offset,$search);
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

	public function index(){
		$data = array();
		$data['user_list']      =   $this->UserModel->fetchAllUser();
		$data['menulist'] 		= 	$this->UserModel->fetchMenuDetails(); 
		$page["layout_content"] = 	$this->load->view('pages/usermanagement/user_menu_permission', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function menuPrevillageUserWise($id){
		$postData = $this->input->post();
		$is_sp_1  = 0; 
		$userArr  = [];
		if(!empty($postData)){
			foreach ($postData['user_menu'] as $pkey => $pvalue) {
				if(!empty($pvalue[0]) && is_array($pvalue)){
					if($pvalue[0] == 'sp_1'){
						$is_sp_1   = 1; 
					}
					$userArr[] = array(
						'admin_user_id' 	=> $postData['admin_user_id'],
						'parent_menu_id' 	=> $pkey,
						'sub_menu' 			=> $pvalue[0],
						'permissionType' 	=> isset($pvalue[1])?$pvalue[1]:0,
					);

					if($pvalue[0] == 'all_1'){
						$company_list      =  $this->UserModel->existCompanyList($postData['admin_user_id']);
						if(!empty($company_list)){
							$deleteData = $this->db->delete('company_permission_tbl', array('admin_user_id' => base64_encode($postData['admin_user_id']),'status'=>1)); 
						}
						/*if(!empty($company_list)){
							foreach ($company_list as $pkey => $cvalue) {
								$companyArr[] = array(
									'admin_user_id'  => $postData['admin_user_id'],
									'company_id'     => $cvalue['id'],
								);
							}
							$insert = $this->db->insert_batch('company_permission_tbl', $companyArr);
						}*/
					}
				} else {
					if(!empty($pvalue[0])){
							$permission = 0;
						}else{
							$permission = 1;
						} 
					$userArr[] = array(
						'admin_user_id' 	=> $postData['admin_user_id'],
						'parent_menu_id' 	=> $pkey,
						'sub_menu' 			=> '',
						'permissionType'    => $permission,
					);
				}
				
			} 
			 /// end of foreach
			
			$insert = $this->db->insert_batch('menu_previllage_tbl', $userArr);
			
			if($insert){
				if($is_sp_1 == 1){
					$this->session->set_flashdata('user_success','Menu permission successfully');
					redirect('userManagement/companyListForPermission/'.base64_encode($postData['admin_user_id']));
				}else{
					$this->session->set_flashdata('user_success','Menu permission successfully');
					redirect('userManagement/user-list');
				}
				
			}else{
				$this->session->set_flashdata('user_error','Data not inserted');
				redirect('userManagement/user-menu-permission');
			}
			
		} else {
			redirect('userManagement/user-menu-permission');
		}

	}

	public function editUserManagement(){
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('userManagement/user-list');
		}
		$data['user_list']      =   $this->UserModel->fetchAllUser();
		$data['menulist'] 		= 	$this->UserModel->fetchMenuDetails(); 
		$data['user_id'] 		= 	$id;
		$data['result'] 		=   $this->UserModel->getMenuDetailsById($id);
		//$data['get_sub_menu'] 	=   $this->UserModel->getSubMenuDetailsById($id);
		$data["page_title"]		=	"Edit user management Details";
        $page["layout_content"]	=	$this->load->view('pages/usermanagement/user_menu_permission', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function updateMenuPermission($id){
		if(!empty($id)){
			$postData = $this->input->post();
			//echo '<pre>';print_r($postData);
			$checkmenuData = $this->UserModel->getMenuDetailsById($id);
			//echo '<pre>';print_r($checkmenuData);
			if(!empty($checkmenuData)){
 				$deleteData = $this->db->delete('menu_previllage_tbl', array('admin_user_id' => base64_decode($id),'status'=>1)); 
 				//echo $this->db->last_query();die;
			}
			$is_sp_1  = 0; 
			$userArr  = [];
			if(!empty($postData['user_menu'])){
				foreach ($postData['user_menu'] as $pkey => $pvalue) { 
					if(!empty($pvalue[0]) && is_array($pvalue)){
						if($pvalue[0] == 'sp_1'){
							$is_sp_1   = 1; 
						}

						$userArr[] = array(
							'admin_user_id' 	=> $postData['admin_user_id'],
							'parent_menu_id' 	=> $pkey,
							'sub_menu' 			=> $pvalue[0],
							'permissionType'    => isset($pvalue[1])?$pvalue[1]:0,
						);
							
						if($pvalue[0] == 'all_1'){
							$company_list      =  $this->UserModel->existCompanyList(base64_encode($postData['admin_user_id']));
							
							if(!empty($company_list)){
								$deleteData = $this->db->delete('company_permission_tbl', array('admin_user_id' => $postData['admin_user_id'],'status'=>1)); 
							}
						}
					} else {
						if(!empty($pvalue[0])){
							$permission = 0;
						}else{
							$permission = 1;
						}

						$userArr[] = array(
							'admin_user_id' 	=> $postData['admin_user_id'],
							'parent_menu_id' 	=> $pkey,
							'sub_menu' 			=> '',
							'permissionType'    => $permission,
						);
						
						
					}
					
				} /// end of foreachdie;
				
				$insert = $this->db->insert_batch('menu_previllage_tbl', $userArr);
				//echo $this->db->last_query();die;
				if($insert){
					if($is_sp_1 == 1){
						$this->session->set_flashdata('user_success','Menu permission successfully');
						redirect('userManagement/companyListForPermission/'.base64_encode($postData['admin_user_id']));
					}else{
						$this->session->set_flashdata('user_success','Menu permission successfully');
						redirect('userManagement/user-list');
					}
					
				}else{
					$this->session->set_flashdata('user_error','Data not inserted');
					redirect('userManagement/activeUserList');
				}
				
			} else {
				$this->session->set_flashdata('user_error','Please select atleast one');
				redirect('userManagement/editUserManagement/'.base64_encode($postData['admin_user_id']));
			}
		}
	}

	public function updateCompanyandProductPermission(){
		$postData = $this->input->post();
		if(!empty($postData)){
			$update = $this->UserModel->updateCompanyProductPermission($postData);
			if($update){
				echo json_encode(array('status'=>true,'msg'=>'updated_successfully'));
			}else{
				echo json_encode(array('status'=>false,'msg'=>'not_updated'));
			}
		}
	}

	public function updateProductPermission(){
		$postData = $this->input->post();
		if(!empty($postData)){
			$update = $this->UserModel->updateProductPermission($postData);
			if($update){
				echo json_encode(array('status'=>true,'msg'=>'updated_successfully'));
			}else{
				echo json_encode(array('status'=>false,'msg'=>'not_updated'));
			}
		}
	}

	public function companyListForPermission(){
		$userId 				   			=  $this->uri->segment(3);
		$data['exist_company_list']     	=  $this->UserModel->existCompanyList($userId);
		$data['company_list']      			=  $this->UserModel->getCompanyName($userId);
        $page["layout_content"]	   		    =  $this->load->view('pages/usermanagement/company-permission', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function companyPermission($id){
		$postData = $this->input->post();
		//echo '<pre>';print_r($postData);
		$userArr  = [];
		if(!empty($postData['company_id'])){
			$check_menu_data = $this->UserModel->getMenuDetailsById($id);
			$parent_menu =[];
			foreach ($check_menu_data as $key => $value) {
				$parent_menu[] = $value['parent_menu_id'];
			}
		
			$checkCompanyData = $this->UserModel->existCompanyList($id);
			if(!empty($checkCompanyData)){
				foreach ($checkCompanyData as $key1 => $co_value) {
					$deleteProduct = $this->db->delete('product_assign_user',array('user_id' => base64_decode($id),'company_id' => $co_value['company_id'],'status'=>1));
				}
				$deleteData = $this->db->delete('company_permission_tbl', array('admin_user_id' => base64_decode($id),'
					status'=>1)); 
			}

			foreach ($postData['company_id'] as $pkey => $cvalue) {
				
				$userArr[] = array(
					'admin_user_id' 	=> $postData['admin_user_id'],
					'company_id' 		=> $cvalue,
				);
			}
			
			$insert = $this->db->insert_batch('company_permission_tbl', $userArr);
			if($insert){
				if(in_array("24", $parent_menu)){
					$this->session->set_flashdata('suppliers_product_success','Company Assigned successfully, According to your menu permission , need to given product permission');
					redirect('userManagement/assignProductlist/'.base64_encode($postData['admin_user_id']));
				}else{
					$this->session->set_flashdata('user_success','Menu permission successfully');
					redirect('userManagement/companyListForPermission/'.base64_encode($postData['admin_user_id']));
				}
			}else{
				$this->session->set_flashdata('user_error','Data not inserted');
				redirect('userManagement/activeUserList');
			}
		} else {
			$this->session->set_flashdata('user_error','Please select least one');
			redirect('userManagement/companyListForPermission/'.base64_encode($postData['admin_user_id']));
		}

	}

	public function allCompanyPermission($id){
		$checkCompanyData = $this->UserModel->existCompanyList($id);
		if(!empty($checkCompanyData)){
			$deleteData = $this->db->delete('company_permission_tbl', array('admin_user_id' => base64_decode($id),'status'=>1)); 
		}
		$checkMenuPermission = $this->UserModel->existSubMenuDetailsById($id);
		if(!empty($checkMenuPermission[0]['sub_menu']) && $checkMenuPermission[0]['sub_menu']=='sp_1'){
			$updateData = array(
				'sub_menu' =>'all_1',
			);
			$updateSub_menu = $this->UserModel->updateSubmenu($updateData,$id,$checkMenuPermission[0]['id']);
		}
		$this->session->set_flashdata('user_success','Permission has been given to all the companies successfully');
		redirect('userManagement/user-list');
	}

	public function checkEmail($email){
		$checkData = $this->UserModel->check_email(trim($email));
		if(!empty($checkData)){
			return false;die;
		}else{
			return true;
		}
	}

	public function updateStatus() {
		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->UserModel->changeStatus($postData);
			if ($response) {
				$this->session->set_flashdata('user_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function editUser() {
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('userManagement/user-list');
		}
		$res = $this->UserModel->getUserDetailsById($id);

		$data['fetch_data'] 		= 	$res['fetch_data'][0];
		$data['id'] 		    	= 	$id;
		$data["page_title"]			=	"Edit User Details";
		$data['department_list'] 	=   $this->UserModel->getDepartmentLit();
        $page["layout_content"]		=	$this->load->view('pages/usermanagement/edit-user', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function editUserDetails(){
		$data = $this->input->post();
		$id = !empty($data['id'])?$data['id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('user_error',"Failed to update");	
			redirect('userManagement/editUser/'.$id);	
		}
		$this->form_validation->set_data($data);
        $this->form_validation->set_rules('first_name','First Name', 'trim|required');
		$this->form_validation->set_rules('middle_name','Middle Name', 'trim');
		$this->form_validation->set_rules('last_name','Last Name', 'trim|required');
		$this->form_validation->set_rules('email','Email', 'trim|valid_email|xss_clean|required');
		$this->form_validation->set_rules('mobile','Mobile', 'trim|required|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('password','Password', 'trim|required');
		$this->form_validation->set_rules('department_id[]','Department', 'required');
      
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('user_error',validation_errors());
            redirect('userManagement/editUser/'.$id);
        }

        unset($data['id']);
        unset($data['submit']);
        $data['modified_at'] 	= date('Y-m-d H:i:s');
        $data['name'] 			= $data['first_name'].' '.$data['last_name'];
        $data['password_hint'] 	= $data['password'];
        $data['password'] 		= password_hash($data['password'],PASSWORD_DEFAULT);
		$data['department_id']  = implode(',', $data['department_id']);
     	$response 				= $this->UserModel->updateUserDetails($data,$id);
		if($response) {
			$this->session->set_flashdata('user_success','Update Successfull');
			redirect('userManagement/user-list');
		} else {
			$this->session->set_flashdata('user_error','Failed to update. Please try again');
			redirect('userManagement/editUser/'.$id);
		}
	}

	public function deleteUserDetails(){
		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->UserModel->deleteUserDetails($postData);
			if ($response) {
				$this->session->set_flashdata('user_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
	}

	public function inactiveUser_ajax(){
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
   				$result = $this->UserModel->getInactiveUserDetails($limit,$offset,$search);
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

	public function activeUser_ajax(){
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
   				$result = $this->UserModel->getActiveUserDetails($limit,$offset,$search);
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

	public function getCompanyListByUserId(){
		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'No record found. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'No record found. Please try again'));
		} else {
			$response = $this->UserModel->existCompanyList($id);
			if ($response) {
				echo json_encode(array('status'=>TRUE,'data'=>$response));
			} else {
				echo json_encode(array('status'=>FALSE));
			}
		}
	}

	public function department(){
		$catFormat 				= 	$this->departmentDetailsFormat();
		$data['columns']		=	$catFormat['cat_column'];
		$data['catData']		=	$catFormat['department_name'];
		$data["page_title"] 	=   "View Department";
        $page["layout_content"] =   $this->load->view('pages/department/view-department', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/department/department',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function departmentDetailsFormat(){
		$retData['cat_column'] = array("Department Name","Date","Status","Action");
        $retData['department_name'] = "[
        	{ 'data' : 'department_name' },
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\" >Active</span>';
        			}  else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Inactive</span>';
        			}
        		}
        	},

        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active' ){
        				return '<a class=\"edit_info\" href=\"javascript:void(0)\" title=\"Edit Department\" data-toggle=\"modal\" style=\"color: black;\"><i class=\"fa fa-edit\" style=\"margin-right: 10px;font-size: 16px;\" aria-hidden=\"true\"></i></a><a class=\"delete_info\" href=\"javascript:void(0)\" title=\"Delete Department\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a>';
        			} else {
        				return  '<a class=\"delete_info\" href=\"javascript:void(0)\" title=\"Delete Department\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a>';
        			}
        		}
        	},

        	
    	]";

        return $retData;
	}

	public function departmentDetails_ajax(){
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
   				$result = $this->DepartmentModel->fetchDepartments($limit,$offset,$search);
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

	public function addDepartment(){
		$submit = $this->input->post();
		if(!empty($submit)) {
			$data = array();
			try {
				$data = validatePostData($submit);
			} catch (Exception $e) {
				$this->session->set_flashdata('department_error','Invalid data');
				redirect('userManagement/addDepartment');
			}

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('department_name', 'Department Name', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
	          	$this->session->set_flashdata('department_error',validation_errors());
				redirect('userManagement/addDepartment');
	        }
	        
			$insert =	$this->DepartmentModel->insert('department',$data);
			if($insert) {
				$this->session->set_flashdata('department_success','Department added successfully');
				redirect('userManagement/department');
			} else {
				$this->session->set_flashdata('department_error','Failed to add data');
				redirect('userManagement/addDepartment');
			}
		}
		$data["page_title"] 	= "Add Department Information";
        $page["layout_content"] = $this->load->view('pages/department/add-department', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function deleteDepartment(){
		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to delete. Please try again'));
		} else {
			$response = $this->DepartmentModel->deleteDepartment($postData);
			if ($response) {
				$this->session->set_flashdata('department_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} else {
				$this->session->set_flashdata('department_error',"Successfully deleted");	
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to delete. Please try again'));
			}
		}
	}

	public function editDepartment(){
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('userManagement/department');
		}
		$res = $this->DepartmentModel->getDepartmentDetailsById($id);
		$data['fetch_data'] 	= 	$res[0];
		$data['id'] 		    = 	$id;  
		$data["page_title"]		=	"Edit Department Details";
        $page["layout_content"]	=	$this->load->view('pages/department/edit-department', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function updateDepartment(){
		$data = $this->input->post();
		$data = validatePostData($data);
		$id = !empty($data['id'])?$data['id']:'';
		if(empty($data) || empty($id)) {
			$this->session->set_flashdata('department_error',"Failed to update");	
			redirect('userManagement/editDepartment/'.$id);	
		}
		
		if(validateBase64($id) == false) {
			$this->session->set_flashdata('department_error',"Failed to update");	
			redirect('userManagement/editDepartment/'.$id);
		}			
		$this->form_validation->set_data($data);
        $this->form_validation->set_rules('department_name', 'Department Name','trim|required');
      
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('department_error',validation_errors());
            redirect('userManagement/editDepartment/'.$id);
        }
      
       
        unset($data['id']);
        $data['update_at'] = date('Y-m-d H:i:s');
     	$response = $this->DepartmentModel->update($data,$id);

		if($response) {
			$this->session->set_flashdata('department_success','Depatment name updated Successfull');
			redirect('userManagement/department');
		} else {
			$this->session->set_flashdata('department_error','Failed to update. Please try again');
			redirect('userManagement/editDepartment/'.$id);
		}
	}

	public function updateDepartmentStatus() {
		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->DepartmentModel->changeStatus($postData);
			if ($response) {
				$this->session->set_flashdata('department_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function downloadAssignList(){
		$file = UPLOAD_IMAGE_URL.'assign_company_list.csv'; //path to the file on disk
		if (file_exists($file)) {
	        //set appropriate headers
	        header('Content-Description: File Transfer');
	        header('Content-Type: application/csv');
	        header('Content-Disposition: attachment; filename='.basename($file));
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate');
	        header('Pragma: public');
	        header('Content-Length: ' . filesize($file));
	        ob_clean();
	        flush();

	        //read the file from disk and output the content.
	        readfile($file);
	        exit;
    	}
	}

	public function importAssignCompanyList(){
		$postData = $this->input->post();
		if(!empty($_FILES['userfile']['tmp_name'])){
			$fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
			fgetcsv($fp);
			$uniq_code_no 	= 'COM_STATE_'.rand(100,9999).'_USER';
			while($csv_line = fgetcsv($fp))
	        {
	            for($i = 0, $j = count($csv_line); $i < $j; $i++)
	            {
	                $sr_no 						= $csv_line[0];
	               	$company_id 				= $csv_line[1];
	            }

	            $data[] = array(
	               	'company_id' 				=> $company_id,
	               	'csv_row_no'      			=> $sr_no,
	            	'admin_user_id' 			=> base64_decode($postData['admin_user_id']),
	               );
	        }
       
			// array to store value (like :array to store email ids)
	        $duplicate_arr 	= array();
	        $admin_user_ids = array();
	        $company_ids 	= array();
			$unique_array   = array(); // array to store unique arrays
			$i = 0 ;
			$countArr = count($data);

			if(!empty($countArr) && $countArr <= 1000){
	        	foreach ($data as $key => $value) {
	        		if(empty($value['admin_user_id'])) {
	        			$value['uniq_code']	= $uniq_code_no;
	        			$duplicate_arr[] 	= $value;
	        			$duplicate_arr[$i]['rejected_reason'] = 'Admin Id is required';
	        			unset($value[$key]);
	        			$i++;
					} else if(empty($value['company_id'])){
						$value['uniq_code']	= $uniq_code_no;
	        			$duplicate_arr[] 	= $value;
	        			$duplicate_arr[$i]['rejected_reason'] = 'Company Id is required';
	        			unset($value[$key]);
	        			$i++;
					} else {
						$admin_user_ids[] 			= $value['admin_user_id'];
						$company_ids[] 				= $value['company_id'];
						$value['uniq_code'] 	    = $uniq_code_no;
						$value['uniq_code'] 	    = $uniq_code_no;
						// add the array to the unique_array
						$unique_array[] 			= $value;
					}
				}
	
				if(!empty($unique_array)){
					$insert  = $this->UserModel->uploadData('company_permission_tbl',$unique_array);
				}
				if(!empty($duplicate_arr)){
					$insert1  = $this->UserModel->uploadData('rejected_comapny_permission_tbl_log',$duplicate_arr);
				}
				fclose($fp) or die("can't close file");
				redirect('userManagement/companyListForPermission/'.$postData['admin_user_id'],'refresh');
			}else{
				fclose($fp) or die("can't close file");
				$this->session->set_flashdata('suppliers_error','Please upload 1000 data only');
				redirect('product/all_product_list','refresh');
			}
		}else{
			$this->session->set_flashdata('product_mapping_error','Please upload a csv');
			redirect('product/all_product_list','refresh');
		}
	}

	/****** _________________________Product Assign Work _____________________****/

	public function assignProductlist(){
		$userId 				   	=   $this->uri->segment(3);
        $userFormat 				= 	$this->productDetailsFormat();
		$data['columns']			=	$userFormat['user_column'];
		$data['userdata']	    	=	$userFormat['product_id'];
		$data['userId']				=	$userId;
		$data["page_title"] 		= 	"View User Information";
		$data['c_assign_product']   =   $this->UserModel->getAllAssignedProductByuserId($userId);
		$page["layout_content"]	    =   $this->load->view('pages/usermanagement/assignProductList', $data, true);
        $page["script_files"] 		= 	$this->load->view('scripts/users/assign_product_script', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function productDetailsFormat() {
		$retData['user_column'] = array("Mark","Product Id","Product Name","Company Name");
        $retData['product_id'] = "[
        	{
        		'render' : function (data, type, row, meta) {
        			return '<span class=\"assignProduct\"><input type=\"checkbox\" id=\"scales\" name=\"product_id[]\"/></span>'
        		}
        	},
        	{ 'data' : 'product_id' },
        	{ 'data' : 'product_name' },
        	{ 'data' : 'company_name' },
        	
    	]";

        return $retData;
	}

	public function productUser_ajax(){
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
   				$result = $this->UserModel->getAllActiveProductName($limit,$offset,$search,$data['userId']);
				
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

	public function assignProductToUser(){
		$postdata = $this->input->post();
		$product_explode_data =explode(',', $postdata['product_id']);
		if(!empty($product_explode_data)){
			$finalArr = []; $com_arr = [];
			foreach ($product_explode_data as $key => $value) {
				$getCompany_name = $this->UserModel->getCompanyIdByproductId($value);
				foreach ($getCompany_name as $keys => $com_ids) {
					array_push($com_arr, $com_ids['vendor_id']);
				}
				$finalArr[]= array(
                    'product_id' 	=> $value,
                    'user_id' 		=> base64_decode($postdata['userId']),
                    'company_id' 	=> $com_arr[$key],
		        );
			}
			
		
			if(!empty($finalArr)){
			    $insert_data = $this->db->insert_batch('product_assign_user', $finalArr);
			    if($insert_data) {
					echo json_encode(array('status'=>true,'msg'=>'added_success'));
				} else {
					echo json_encode(array('status'=>false,'msg'=>'failed'));
				}
			}
		}

	}

	public function assignedProductListByUser(){
		$postData = $this->input->post();
		if(!empty($postData)){
			$fetchData = $this->UserModel->getAllAssignedProductByuserId($postData['user_id']);
			if(!empty($fetchData)){
				echo json_encode(array('status'=>true,'data'=>$fetchData,'msg'=>'data_found'));
			}else{
				echo json_encode(array('status'=>false,'data'=>'','msg'=>'data_not_found'));
			}
		}
	}

	public function deleteAssignedProductByuser(){
		$postData = $this->input->post();
		if(!empty($postData)){
			$delete_data = $this->UserModel->deleteAssignProductUserWise($postData['product_id']);
			if(!empty($delete_data)){
				echo json_encode(array('status'=>true,'msg'=>'delete_data'));
			}else{
				echo json_encode(array('status'=>false,'msg'=>'data_not_delete'));
			}
		}
	}

	public function deleteAllData(){
		$postData = $this->input->post();
		
		foreach ($postData['product_ids'] as $key => $value) {
			$delete_data = $this->UserModel->deleteAssignProductUserWise($value);
		}
		echo json_encode(array('status'=>true,'msg'=>'delete_data'));
	}

	public function menuWiseCompanyPermission(){
		$postData = $this->input->post();
		$check_company_permission = $this->UserModel->checkCompanyPermission($postData);
		if(!empty($check_company_permission)){
			if($postData['type_check'] == 'company'){
				$this->session->set_userdata('supplier','19');
			}
			if($postData['type_check'] == 'product'){
				$this->session->set_userdata('product','24');
			}
			echo json_encode(array('status'=>true,'msg'=>'fetched_success','data'=>$check_company_permission));
		}else{
			echo json_encode(array('status'=>false,'msg'=>'data_not_found'));
		}
	}

	/************* ============ START CSV SECTION ================ **********/

	public function sampleCsvDownload() {
		$file = UPLOAD_IMAGE_URL.'samplecsv/assign_product_to_user_sample_file.csv';
		if (file_exists($file)) {
	        //set appropriate headers
	        header('Content-Description: File Transfer');
	        header('Content-Type: application/csv');
	        header('Content-Disposition: attachment; filename='.basename($file));
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate');
	        header('Pragma: public');
	        header('Content-Length: ' . filesize($file));
	        ob_clean();
	        flush();

	        //read the file from disk and output the content.
	        readfile($file);exit;
    	}else{
    		header("HTTP/1.0 404 Not Found");
        	exit("File not found");
    	}
	}

	public function uploadAssignProductCsv(){

		if(!empty($_FILES['upload_assign_csv']['tmp_name'])){
			$fp = fopen($_FILES['upload_assign_csv']['tmp_name'],'r') or die("can't open file");
			fgetcsv($fp);
			while($csv_line = fgetcsv($fp))
	        {
	            for($i = 0, $j = count($csv_line); $i < $j; $i++)
	            {
					$sr_no 						= $csv_line[0];
	               	$user_id 					= $csv_line[1];
	               	$company_id 				= $csv_line[2];
	               	$product_id  				= $csv_line[3];
	            }
	            $data[] = array(
	            	'user_id' 					=> $user_id,
	            	'company_id' 				=> $company_id,
	               	'product_id' 				=> $product_id,
	               	'status'  					=> 1,
	               	'data_upload_by'      		=> 2,
	            );
	        }
       
			$countArr = count($data);
			if(!empty($countArr) && $countArr <= 1000){
				$insert  = $this->UserModel->uploadData('product_assign_user',$data);
				if(!empty($insert)){
					fclose($fp) or die("can't close file");
					$this->session->set_flashdata('user_success','data uploaded successfully');
					redirect('userManagement/activeUserList','refresh');
				}
			}else{
				fclose($fp) or die("can't close file");
				$this->session->set_flashdata('user_error','Please upload 1000 data only');
				redirect('userManagement/activeUserList','refresh');
			}
		}else{
			$this->session->set_flashdata('user_error','Please upload a csv');
			redirect('userManagement/activeUserList','refresh');
		}
	} 

	public function checkMenuPermission(){
		$postData = $this->input->post();
		if(!empty($postData)){
			$check = $this->UserModel->checkmenuPermissionby_user($postData['user_id']);
			if(!empty($check)){
				echo json_encode(array('status'=>'success','msg'=>'fetch_menu_id'));die;
			}else{
				echo json_encode(array('status'=>false,'msg'=>'empty_menu'));die;
			}
		}
	}

}
?>