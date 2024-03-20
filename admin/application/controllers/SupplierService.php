<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupplierService extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('SupplierServiceModel'));
	}

	public function index(){
		$supplierFormat 				= 	$this->serviceSupplierDetailsFormat();
		$data['columns']				=	$supplierFormat['supplier_service_column'];
		$data['supplierServiceData']	=	$supplierFormat['service_name'];
		$data["page_title"] 			= 	"View supplier service Information";
		$page["layout_content"] 		= 	$this->load->view('pages/supplierservice/viewSupplierService', $data, true);
        $page["script_files"]			= 	$this->load->view('scripts/supplierservice/supplierservice',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function serviceSupplierDetailsFormat() {
		$retData['supplier_service_column'] = array("Service Id","Service Name","Company Name","Added On","Status","Service Total Score","Update Status","Action");
		
        $retData['service_name'] = "[
        	{ 'data' : 'service_id' },
        	{ 'data' : 'service_name' },
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let companyName = row.company_name;
        				html += '<a class=\"service_catlog_info\" href=\"javascript:void(0)\">';
	        			html += companyName;
	        			html += '<a/>';
	        		return html;
        		}
        		
        	},
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Approved') {
        				return '<span  style=\"color: #008000;\">Approved</span>';
        			} else if(row.status == 'Reject') {
        				return '<span  style=\"color: #f90d18;\">Rejected</span>';
        			} else {
        				return '<span  style=\"color: #1241ab;\">Pending</span>';
        			}
        		}
        	},
        	{ 'data' : 'total_score' },

        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Approved') {
        				return '<span class=\"badge badge-warning service_reject_status cursor-pointer\">Reject</span>';
        			} else if(row.status == 'Reject') {
        				return '<span class=\"badge badge-success service_approve_status cursor-pointer\">Approve</span>';
        			} else {
        				return '<span class=\"badge badge-success service_approve_status cursor-pointer\">Approve</span> <span class=\"badge badge-warning service_reject_status cursor-pointer\">Reject</span>';
        			}
        		}
        	},

        	{ 'defaultContent' : '<a class=\"service_edit_update\" href=\"javascript:void(0)\" title=\"Service Mapping\" data-toggle=\"modal\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 19px; color:#227676\" aria-hidden=\"true\"></i></a>'
        	}
        	
    	]";
    	

        return $retData;
	}

	
	public function supplierServiceDetails_ajax() {
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
   				$result = $this->SupplierServiceModel->allServiceSupplierDetails($limit,$offset,$search);
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

	public function updateServiceApprovedStatus(){
		$postData = $this->input->post();
		$id = !empty($postData['service_id'])?$postData['service_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->SupplierServiceModel->changeServiceApprovedStatus($postData);
			if ($response) {
				$this->session->set_flashdata('suppliers_product_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}
	
	public function updateServiceRejectedStatus(){
		$postData = $this->input->post();
		$id = !empty($postData['service_id'])?$postData['service_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->SupplierServiceModel->changeServiceRejectedStatus($postData);
			if ($response) {
				$this->session->set_flashdata('suppliers_product_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	/* **** Service Edit update ******/

	public function serviceDetailsByAdmin($service_id){
		$uri_segment 				=   $this->uri->segment(3);
		if(!empty($uri_segment)){
			$services 						=   $this->SupplierServiceModel->getSupplierServiceDetailsById(base64_decode($service_id));
			$data['fetch_service']			=   $services[0]; 
			$data['fetch_specification']	=   $this->SupplierServiceModel->getSupplierServiceSpecificationById(base64_decode($service_id));
			$data['miocrocat']				=   $this->SupplierServiceModel->getMicroCategoryNameBySubCat();

			$data['category_info'] 			=   $this->SupplierServiceModel->getSellerMappingcategoryByServiceId(base64_decode($service_id));
			$data['supplier_cat'] 			=   $this->SupplierServiceModel->getSupplierServiceCategory($data['fetch_service']['vendor_id']);
			$company 						=   getCompanyNameByCompanyId(base64_encode($data['fetch_service']['vendor_id']));
			$data['company_name']			=   $company[0]['company_name'];
			$page["layout_content"] 		= 	$this->load->view('pages/supplierservice/supplier_dashboard_service_ediyByAdmin',$data);

        }else{
        	redirect('supplierservice');
        }
	}

	public function updateServiceDetailsByAdmin(){
		$submit = $this->input->post();
		
		if(empty($submit['id'])){
			redirect('supplierservice');
		}
		if(!empty($submit)){
			$data = array();
			$data = $submit;
			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('service_name','Service Name', 'trim|required');
			$this->form_validation->set_rules('service_code','Service code', 'trim');
			$this->form_validation->set_rules('product_supplier_cat_id','Supplier Category', 'trim');
			$this->form_validation->set_rules('hsn_code','HSN Code', 'trim');
			$this->form_validation->set_rules('gst','GST', 'trim');
			$this->form_validation->set_rules('service_price','Selling price', 'trim');
			$this->form_validation->set_rules('selling_price_currency','Selling Price Curreny', 'trim');
			$this->form_validation->set_rules('product_unit','Service Unit', 'trim');
			$this->form_validation->set_rules('unit_type','Unit Type', 'trim');
			$this->form_validation->set_rules('service_sp_title[]','Service Title', 'trim');
			$this->form_validation->set_rules('service_sp_des[]','Service Description', 'trim');
			$this->form_validation->set_rules('autogorila_micro_cat_id[]','Micro category name', 'trim');
			
			if ($this->form_validation->run() == FALSE) {
	            $this->session->set_flashdata('supplier_service_error',validation_errors());
	            redirect('SupplierService/serviceDetailsByAdmin/'.$submit['id']);
	        }
			$serviceData = array();$status='';
			if($data['status'] == 1){
				$status = 1;
			}else{
				$status = 0;
			}
			$serviceData = array(
		        	'service_name' 				=>	addslashes($data['service_name']),
		        	'service_url_slug' 			=>  create_url($data['service_name']).'/'.$submit['id'],
		        	'service_code' 				=>	$data['service_code'],
		        	'service_vendor_cat_id' 	=>	!empty($data['service_vendor_cat_id'])?$data['service_vendor_cat_id']:1,
		        	'hsn_code' 					=>	$data['hsn_code'],
		        	'gst' 						=>	$data['gst'],
		        	'service_price' 			=>	$data['service_price'],
		        	'currency_type' 			=>	$data['currency_type'],
		        	'unit' 						=>	$data['unit'],
		        	'unit_type' 				=>	$data['unit_type'],
		        	'service_description' 		=>	$data['service_description'],
		        	'service_vedio_1' 			=>	!empty($data['service_vedio_1'])?$data['service_vedio_1']:'',
		        	'service_video_2' 			=>	!empty($data['service_video_2'])?$data['service_video_2']:'',
		        	'service_video_3' 			=>	!empty($data['service_video_3'])?$data['service_video_3']:'',
		        	'status' 					=>  $status ,
		        	'modified_at' 				=>	date('Y-m-d H:i:s'),
	    		);

	        if(!empty($_FILES['image_1']['name'])) {
				$upload_folder = 'vendor_service';
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
				if (!$this->upload->do_upload('image_1')){
					$errors = $this->upload->display_errors();
					$this->session->set_flashdata('supplier_service_error',$errors);
					redirect('SupplierService/serviceDetailsByAdmin/'.base64_encode($submit['id']));
				}
				$serviceData['image_1'] = $this->upload->data('file_name');
	    	}

	    	if(!empty($_FILES['image_2']['name'])) {
				$upload_folder = 'vendor_service';
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
				if (!$this->upload->do_upload('image_2')){
					$errors = $this->upload->display_errors();
					$this->session->set_flashdata('supplier_service_error',$errors);
					redirect('SupplierService/serviceDetailsByAdmin/'.base64_encode($submit['id']));
				}
				$serviceData['image_2'] = $this->upload->data('file_name');
	    	}

	    	if(!empty($_FILES['image_3']['name'])) {
				$upload_folder = 'vendor_service';
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
				if (!$this->upload->do_upload('image_3')){
					$errors = $this->upload->display_errors();
					$this->session->set_flashdata('supplier_service_error',$errors);
					redirect('SupplierService/serviceDetailsByAdmin/'.base64_encode($submit['id']));
				}
				$serviceData['image_3'] = $this->upload->data('file_name');
	    	}
			

		    $table1 = "vendor_service";
		    $table2 = "vendor_service_specification";
		    $table3 = "vendor_service_mapping_tbl";
		    $id 	= $data['id'];

        	$update_service = $this->SupplierServiceModel->update($serviceData,$id,$table1);
        		
        	/*check specification tbl data & update new data*/
        	$check_specfication_data_exist = $this->SupplierServiceModel->getSupplierServiceSpecificationById(base64_decode($id));
        	if($check_specfication_data_exist > 0){
        		$delete_service_specfication = $this->SupplierServiceModel->delete_service($id,$table2);

        	}
        	if(!empty($data['service_sp_title'])){
	    		foreach ($data['service_sp_title'] as $key => $value) {
		            if($value !='' && $data['service_sp_des'][$key] !=''){
	                    $finalArr[]= array(
	                        'service_sp_title' 		=> $value,
	                        'service_sp_des' 		=> $data['service_sp_des'][$key],
	                        'service_id' 			=> base64_decode($id),
	                    ); 
			    	}
			  	}
			  	if(!empty($finalArr)){
					$insert_productSpecification = $this->db->insert_batch('vendor_service_specification', $finalArr);
				}
			}
			/*End of the Processing*/

			/*Check Mapping tbl data & new data update*/
			$check_product_mapping_data_exist = $this->SupplierServiceModel->getSellerMappingcategoryByServiceId(base64_decode($id));
			if($check_product_mapping_data_exist > 0){
        		$this->db->where('service_id', base64_decode($id));
    			$this->db->delete('vendor_service_mapping_tbl');
        	}

        	/* Autogorila Category Mapping Entry */
			if(!empty($data['autogorila_micro_cat_id'])){
    			foreach ($data['autogorila_micro_cat_id'] as $key => $cat_value) {
    				$catData = explode('#',$cat_value);
                    	$categoryArr[]= array(
	                        'autogorila_parent_cat_id' 		=> $catData[2],
	                        'autogorila_sub_cat_id' 		=> $catData[1],
	                        'autogorila_micro_cat_id' 		=> $catData[0],
	                        'service_id' 					=> base64_decode($id),
	                       // 'vendor_id' 					=> $this->sessionDetails['supplier_id'],
	                        'vendor_id' 					=> $data['vendor_id'],
                    	);

		    	}
		    	if(!empty($categoryArr)){
		    		$insert_category = $this->db->insert_batch('vendor_service_mapping_tbl', $categoryArr);
		    		if(!empty($insert_category)){
                    	$updateP_mFlag =array(
			    			'service_mapped' => 1
			    		);
			    		$update_service = $this->SupplierServiceModel->update($updateP_mFlag,$id,'vendor_service');
			    	}
		    	}
		    }

		    if($update_service){
		    	$table4					= "vendor_service_score_table";
		    	$fetch_service 			= $this->SupplierServiceModel->getSupplierServiceDetailsById(base64_decode($id));
		    	$fetch_service_score 	= $this->SupplierServiceModel->getServiceScoreById(base64_decode($id),$data['vendor_id']);

		    	$scoreArray = [];
				$service_name_score = 10;

				if(!empty($fetch_service[0]['image_1']) || !empty($fetch_service[0]['image_2']) || !empty($fetch_service[0]['image_3'])){
					$service_image_score = 30;
				}else if(!empty($data['image_1']) || !empty($data['image_2']) || !empty($data['image_3'])){
					$service_image_score = 30;
				}else{
					$service_image_score = 0;
				}


				if(!empty($data['service_price'])){
					$service_price_score = 10;
				}else{
					$service_price_score = 0;
				}

				if(!empty($data['service_description'])){
					$service_description_score = 25;
				}else{
					$service_description_score = 0;
				}
				if(!empty($data['service_sp_title'])){
					if(array_values(array_filter($data['service_sp_title']))){
						$service_specification_score = 25;
					}else{
						$service_specification_score = 0;
					}
				}else{
					$service_specification_score = 0;
				}

				$total_score = $service_name_score + $service_image_score + $service_price_score + $service_specification_score + $service_description_score;

				if($fetch_service_score > 0){
					$scoreArray = array(
						'vendor_id'  					=> $data['vendor_id'],
						'service_name_score' 			=> $service_name_score,
						'service_image_score' 			=> $service_image_score,
						'service_price_score' 			=> $service_price_score,
						'service_description_score' 	=> $service_description_score,
						'service_specification_score' 	=> $service_specification_score,
						'total_score' 					=> $total_score,
					);
					$update_score = $this->SupplierServiceModel->update($scoreArray,$id,$table4);
				}else{
					$insertscoreArray = array(
						'service_id'  					=> base64_decode($id),
						'vendor_id'  					=> $data['vendor_id'],
						'service_name_score' 			=> $service_name_score,
						'service_image_score' 			=> $service_image_score,
						'service_price_score' 			=> $service_price_score,
						'service_description_score' 	=> $service_description_score,
						'service_specification_score' 	=> $service_specification_score,
						'total_score' 					=> $total_score,
					);
					$insert_score = $this->SupplierServiceModel->insert($table4,$insertscoreArray);
				}
				$this->session->set_flashdata('supplier_service_success','successfully updated');
		    	redirect('supplierservice');
		    }else{
		    	$this->session->set_flashdata('supplier_service_error','Failed to update');
		    	redirect('SupplierService/serviceDetailsByAdmin/'.$id);
		    }
		}
	}

}

/* End of file Supplier.php */
/* Location: ./application/controllers/Supplier.php */