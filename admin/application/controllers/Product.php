<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('ProductModel','CategoryModel'));
	}

	public function productCsvDownload(){
		$file = UPLOAD_IMAGE_URL.'product.csv';//path to the file on disk
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

	public function productSpecificationCsvDownload(){
		$file = UPLOAD_IMAGE_URL.'product_specification.csv';//path to the file on disk
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

	public function productMappingCsvDownload(){
		$file = UPLOAD_IMAGE_URL.'product_mapping.csv';//path to the file on disk
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

	public function export_all_productData(){
		$filename = 'allProduct_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 

		// get data
		$usersData = $this->ProductModel->exportAllProductDetails();
		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","Product Name","Company name","Product total Score","Created Date&Time");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;
	}

	public function failedCsvDownload(){
		$postData = $this->input->post();
		$filename = 'failesProduct_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 

		// get data
		$usersData = $this->ProductModel->exportRejectProductDetails($postData['uniq_code']);
		// file creation
		$file = fopen('php://output', 'w');

		$header = array("Excel Row No.","Product Name","Company name","Created Date&Time");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;
	}


	public function export_approved_productData(){
		$filename = 'approvec_product_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 

		// get data
		$usersData = $this->ProductModel->exportApprovedProductDetails();
		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","Product Name","Company name","Product total Score","Create Date&Time");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;
	}

	public function index(){
		$supplierProductFormat 	= 	$this->supplierProductDetailsFormat();
		$data['columns']		=	$supplierProductFormat['product_column'];
		$data['productData']	=	$supplierProductFormat['product_name'];
		$data["page_title"] 	= 	"View Product Information";
		$page["layout_content"] = 	$this->load->view('pages/product/productList', $data, true);
        $page["script_files"]	= 	$this->load->view('scripts/product/product',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function reject_list(){
		$supplierProductFormat 		= 	$this->supplierProductDetailsFormat();
		$data['columns']			=	$supplierProductFormat['product_column'];
		$data['rejectProductData']	=	$supplierProductFormat['product_name'];
		$data["page_title"] 		= 	"View Product Information";
		$page["layout_content"] 	= 	$this->load->view('pages/product/rejectProductList', $data, true);
        $page["script_files"]		= 	$this->load->view('scripts/product/product',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function all_product_list(){
		$supplierProductFormat 		= 	$this->supplierProductDetailsFormat();
		$data['columns']			=	$supplierProductFormat['product_column'];
		$data['allProductData']	    =	$supplierProductFormat['product_name'];
		$data["page_title"] 		= 	"View All Product Information";
		$page["layout_content"] 	= 	$this->load->view('pages/product/all-product-list', $data, true);
        $page["script_files"]		= 	$this->load->view('scripts/product/product',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function approved_product_list(){
		$supplierProductFormat 			= 	$this->supplierProductDetailsFormat();
		$data['columns']				=	$supplierProductFormat['product_column'];
		$data['approvedProductData']	=	$supplierProductFormat['product_name'];
		$data["page_title"] 			= 	"View Approved Product Information";
		$page["layout_content"] 		= 	$this->load->view('pages/product/product_approved_list', $data, true);
        $page["script_files"]			= 	$this->load->view('scripts/product/product',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function supplierProductDetailsFormat(){
		$retData['product_column'] = array("Product Id","Product Name","Company Name","Added On","Status","Product Total Score","Update Status","Action");
		$path     = VIEW_IMAGE_URL.'product/';
		$no_image = VIEW_IMAGE_URL.'noimage.png';
        $retData['product_name'] = "[
        	{ 'data' : 'product_id' },
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let companyName = row.product_name;
        				html += '<a class=\"catlog_info\" href=\"javascript:void(0)\">';
	        			html += companyName;
	        			html += '<a/>';
	        		return html;
        		}
        		
        	},
        	{ 'data' : 'company_name' },
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.approved_status == 'Approved') {
        				return '<span  style=\"color: #008000;\">Approved</span>';
        			} else if(row.approved_status == 'Reject') {
        				return '<span  style=\"color: #f90d18;\">Rejected</span>';
        			} else {
        				return '<span  style=\"color: #1241ab;\">Pending</span>';
        			}
        		}
        	},
        	{ 'data' : 'total_score' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.approved_status == 'Approved') {
        				return '<span class=\"badge badge-warning product_reject_status cursor-pointer\">Reject</span>';
        			} else if(row.approved_status == 'Reject') {
        				return '<span class=\"badge badge-success product_approve_status cursor-pointer\">Approve</span>';
        			} else {
        				return '<span class=\"badge badge-success product_approve_status cursor-pointer\">Approve</span> <span class=\"badge badge-warning product_reject_status cursor-pointer\">Reject</span>';
        			}
        		}
        	},

        	{ 'defaultContent' : '<a class=\"product_edit_update\" href=\"javascript:void(0)\" title=\"Product Mapping\" data-toggle=\"modal\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 19px; color:#227676\" aria-hidden=\"true\"></i></a><a class=\"delete_product\" href=\"javascript:void(0)\" title=\"Product Delete\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;font-size: 19px; color:red\" aria-hidden=\"true\"></i></a><a class=\"add_seo_info\" href=\"javascript:void(0)\" title=\"Add Seo Info\"><i class=\"fa fa-clone\" style=\"margin-right: 3px;font-size: 19px;color:#000000e6\" aria-hidden=\"true\"></i></a>'
        	}
        	
    	]";
        return $retData;
	}

	public function productDetails_ajax() {
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
   				$result = $this->ProductModel->getProducts($limit,$offset,$search,"",$data['searchByFromMinDate'],$data['searchByToMaxDate']);
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

	public function allProductDetails_ajax() {
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
   				$result = $this->ProductModel->getAllProducts($limit,$offset,$search,$data['searchByFromMin'],$data['searchByToMax'],$data['searchByFromMinDate'],$data['searchByToMaxDate']);
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

	public function rejectedProductDetails_ajax() {
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
   				$result = $this->ProductModel->rejectedGetProducts($limit,$offset,$search,$data['searchByFromMinDate'],$data['searchByToMaxDate']);
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

	public function approvedProductDetails_ajax() {
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
   				$result = $this->ProductModel->fetchapprovedProduct($limit,$offset,$search,$data['searchByFromMin'],$data['searchByToMax'],$data['searchByFromMinDate'],$data['searchByToMaxDate']);
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

	public function updateProductStatus(){
		$postData = $this->input->post();
		$id = !empty($postData['product_id'])?$postData['product_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->ProductModel->changeProductStatus($postData);
			if ($response) {
				$this->session->set_flashdata('suppliers_product_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function updateProductApprovedStatus(){
		$postData = $this->input->post();
		$id = !empty($postData['product_id'])?$postData['product_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->ProductModel->changeProductApprovedStatus($postData);
			if ($response) {
				$this->session->set_flashdata('suppliers_product_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}


	public function deleteProduct()
	{
		$postData = $this->input->post();
		$id = !empty($postData['product_id'])?$postData['product_id']:'';
		if(validateBase64($id) == false) 
		{
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) 
		{
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} 
		else 
		{
			$response = $this->ProductModel->deleteProduct($postData);
			if($response) 
			{
				$this->session->set_flashdata('suppliers_product_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			} 
			else 
			{
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to deleted. Please try again'));
			}
		}
	}

	public function getProductetails(){
		$postData = $this->input->post();
		$id = !empty($postData['product_id'])?$postData['product_id']:'';
	
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'data mismatch. Please try again'));
		} else {
			$response = $this->ProductModel->fetchProductDetails($postData);
			if ($response) {
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated','data'=>$response));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function productRejected(){
		$postData = $this->input->post();
		$id = !empty($postData['product_id'])?$postData['product_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->ProductModel->productRejectedStatus($postData);
			if ($response) {
				$this->session->set_flashdata('suppliers_product_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function productMapping(){
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('product');
		}
		$data['parent_categories']  =   $this->CategoryModel->getCategoryName();
		$res 						=   $this->ProductModel->fetchProductDetails(array('product_id'=>$id));
		$data['fetch_data'] 		= 	$res[0];
		$data['product_id'] 		= 	$id;
		$data["page_title"]			=	"Product Mapping";
        $page["script_files"]		= 	$this->load->view('scripts/product/product',$data, true);
        $page["layout_content"]		=	$this->load->view('pages/product/productmapping', $data, true);
        $this->load->view('layouts/datatable_layout', $page); 

	}

	public function getMicroCategoryNameBySubCatId(){
		$data = $this->input->post();
		$json = array();
        $json = $this->ProductModel->getMicroCategoryNameBySubCat($data['sub_cat_id']);
        header('Content-Type: application/json');
        echo json_encode($json);  
	}

	public function categoryProductMapping(){
		$submit = $this->input->post();
		if(!empty($submit)) {
			if(!empty($submit['autogorila_micro_cat_id'])){
	        	foreach ($submit['autogorila_micro_cat_id'] as $key => $cat_value) {
		        	$categoryArr[]= array(
		                'autogorila_parent_cat_id' 		=> $submit['autogorila_parent_cat_id'],
		                'autogorila_sub_cat_id' 		=> $submit['autogorila_sub_cat_id'],
		                'autogorila_micro_cat_id' 		=> !empty($cat_value)?$cat_value:'',
		                'product_id' 					=> $submit['product_id'],
		                'vendor_id' 					=> $submit['vendor_id'],
	                );
		        }
		      
		        if(!empty($categoryArr)){
			    	$insert_category = $this->db->insert_batch('vendor_product_mapping', $categoryArr);
		    		if(!empty($insert_category)){
			    		$updateP_mFlag =array(
			    			'product_mapped' => 1
			    		);
			    		$update_product = $this->ProductModel->update($updateP_mFlag,$submit['product_id'],'product');
			    	}
			   }
	        }
			
			if($insert_category) {
				$this->session->set_flashdata('cat_success','Category added successfully');
				redirect('product');
			} else {
				$this->session->set_flashdata('cat_error','Failed to add data');
				redirect('product/productMapping/'.base64_encode($submit['product_id']));
			}
		}
        $this->load->view('layouts/dashboard_layout', $page);
	}

	/**** start  Product Mapping Information ******/

	public function product_mapping_info(){
		$productFormat 				= 	$this->productMappingDetailsFormat();
		$data['columns']			=	$productFormat['product_column'];
		$data['productMappingData']	=	$productFormat['product_name'];
		$data["page_title"] 		= 	"View Product Mapping Information";
		$page["layout_content"] 	= 	$this->load->view('pages/product/product-mapping-information', $data, true);
        $page["script_files"]		= 	$this->load->view('scripts/product/product',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function productMappingDetailsFormat(){
		$retData['product_column'] = array("Company Name","Product Name","Category Name Location","Product Score","Created Date","View On Website","Product Status(Active/Inactive)","Current Status","Update Status","Action");
		$path     = VIEW_IMAGE_URL.'product/';
		$no_image = NO_IMAGE_URL.'no-image.png';
        $retData['product_name'] = "[
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let companyName = row.company_name;
        			if(companyName){
        				html += '<a class=\"catlog_info\" href=\"javascript:void(0)\">';
	        			html += companyName;
	        			html += '<a/>';
	        		} else {
	        			html += '';
	        		}
	        		return html;
        		}
        		
        	},
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let productImgPath = row.product_img_1;
        			let productName = row.product_nm;
	        			html += productName;
	        			html += '<br>';
	        			if(productImgPath){
		        			html += '<img src=';
		        			html += '".$path."';
		        			html += productImgPath;
		        			html += ' class=\"img-thumbnail\" width=\"70\" height=\"50\" />';
	        			} else {
	        				html += '<img src=';
	        				html += '".$no_image."';
	        				html += ' class=\"img-thumbnail\" width=\"70\" height=\"50\" />';
	        			}
	        		return html;
        		}
        		
        	},
        	
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let categories_name = row.categories_name;

        			var foo = String(categories_name);
        			var arr = foo.split(',');

        			if (categories_name){
        				for (let i = 0; i < arr.length; i++) {
						  html += '<i class=\"fa fa-dot-circle-o mri-4\" aria-hidden=\"true\"></i>'+ arr[i] + '<br>';
						}
	        		} else {
	        			html += '';
	        		}
	        		return html;
        		}
        		
        	},
        	{ 'data' : 'total_score' },
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active' && row.product_mapped == 1 && row.approved_status == 'Approved') {
        				return '<span  style=\"color: #008000;\">Yes</span>';
        			} else {
        				return '<span  style=\"color: #f90d18;\">No</span>';
        			}
        		}
        	},

        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span  style=\"color: #008000;\">Active</span>';
        			} else {
        				return '<span  style=\"color: #f90d18;\">Inactive</span>';
        			}
        		}
        	},

        	{
        		'render' : function (data, type, row, meta) {
        			if(row.approved_status == 'Approved') {
        				return '<span  style=\"color: #008000;\">Approved</span>';
        			} else if(row.approved_status == 'Reject') {
        				return '<span  style=\"color: #f90d18;\">Rejected</span>';
        			} else {
        				return '<span  style=\"color: #1241ab;\">Pending</span>';
        			}
        		}
        	},
        	

        	{
        		'render' : function (data, type, row, meta) {
        			if(row.approved_status == 'Approved') {
        				return '<span class=\"badge badge-warning product_reject_status cursor-pointer\">Reject</span>';
        			} else if(row.approved_status == 'Reject') {
        				return '<span class=\"badge badge-success product_approve_status cursor-pointer\">Approve</span>';
        			} else {
        				return '<span class=\"badge badge-success product_approve_status cursor-pointer\">Approve</span> <span class=\"badge badge-warning product_reject_status cursor-pointer\">Reject</span>';
        			}
        		}
        	},

        	{ 'defaultContent' : '<a class=\"product_edit_update\" href=\"javascript:void(0)\" title=\"Product Mapping\" data-toggle=\"modal\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 19px; color:#227676\" aria-hidden=\"true\"></i></a><a class=\"delete_product\" href=\"javascript:void(0)\" title=\"Product Delete\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;font-size: 19px; color:red\" aria-hidden=\"true\"></i></a>'
        	}
        	
    	]";
        return $retData;
	}

	public function fetchProductMapping_ajax(){
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
   				$result = $this->ProductModel->fetchProductMappingInformation($limit,$offset,$search,$data['searchByFromMinDate'],$data['searchByToMaxDate']);
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


	public function all_unmapped_products(){
		$productFormat 				= 	$this->unMappingProductDetailsFormat();
		$data['columns']			=	$productFormat['product_column'];
		$data['productUnMappingData']	=	$productFormat['product_name'];
		$data["page_title"] 		= 	"View Product Mapping Information";
		$page["layout_content"] 	= 	$this->load->view('pages/product/product-un-mapping-information', $data, true);
		$page["script_files"]		= 	$this->load->view('scripts/product/product',$data, true);
		$this->load->view('layouts/datatable_layout', $page);
	}

	public function unMappingProductDetailsFormat(){
		$retData['product_column'] = array("Product Id","Company Name","Product Name","Product Score","Created Date","Product Status(Active/Inactive)","Current Status","Update Status","Action");
		$path     = VIEW_IMAGE_URL.'product/';
		$no_image = NO_IMAGE_URL.'no-image.png';
        $retData['product_name'] = "[
        	{ 'data' : 'product_id' },
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let companyName = row.company_name;
        			if(companyName){
        				html += '<a class=\"catlog_info\" href=\"javascript:void(0)\">';
	        			html += companyName;
	        			html += '<a/>';
	        		} else {
	        			html += '';
	        		}
	        		return html;
        		}
        		
        	},
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let productImgPath = row.product_img_1;
        			let productName = row.product_name;
	        			html += productName;
	        			html += '<br>';
	        			if(productImgPath){
		        			html += '<img src=';
		        			html += '".$path."';
		        			html += productImgPath;
		        			html += ' class=\"img-thumbnail\" width=\"70\" height=\"50\" />';
	        			} else {
	        				html += '<img src=';
	        				html += '".$no_image."';
	        				html += ' class=\"img-thumbnail\" width=\"70\" height=\"50\" />';
	        			}
	        		return html;
        		}
        		
        	},
        	{ 'data' : 'total_score' },
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span  style=\"color: #008000;\">Active</span>';
        			} else {
        				return '<span  style=\"color: #f90d18;\">Inactive</span>';
        			}
        		}
        	},
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.approved_status == 'Approved') {
        				return '<span  style=\"color: #008000;\">Approved</span>';
        			} else if(row.approved_status == 'Reject') {
        				return '<span  style=\"color: #f90d18;\">Rejected</span>';
        			} else {
        				return '<span  style=\"color: #1241ab;\">Pending</span>';
        			}
        		}
        	},
        	
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.approved_status == 'Approved') {
        				return '<span class=\"badge badge-warning product_reject_status cursor-pointer\">Reject</span>';
        			} else if(row.approved_status == 'Reject') {
        				return '<span class=\"badge badge-success product_approve_status cursor-pointer\">Approve</span>';
        			} else {
        				return '<span class=\"badge badge-success product_approve_status cursor-pointer\">Approve</span> <span class=\"badge badge-warning product_reject_status cursor-pointer\">Reject</span>';
        			}
        		}
        	},

        	{ 'defaultContent' : '<a class=\"product_edit_update\" href=\"javascript:void(0)\" title=\"Product Mapping\" data-toggle=\"modal\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 19px; color:#227676\" aria-hidden=\"true\"></i></a><a class=\"delete_product\" href=\"javascript:void(0)\" title=\"Product Delete\" data-toggle=\"modal\" style=\"color: red;\"><i class=\"fa fa-trash\" style=\"margin-right: 3px;font-size: 19px; color:red\" aria-hidden=\"true\"></i></a>'
        	}
    	]";
        return $retData;
	}


	public function getProductUnMapping_ajax(){
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
   				$result = $this->ProductModel->getUnMappingProductList($limit,$offset,$search);
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

		/************ EnD ************/

	/************* Product CSV Upload ***************/

	public function uploadProductCsv(){
		if(!empty($_FILES['userfile']['tmp_name'])){
			$fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
			fgetcsv($fp);
			$uniq_code_no 	= rand(10,9999);
			while($csv_line = fgetcsv($fp))
	        {
	        	
	            for($i = 0, $j = count($csv_line); $i < $j; $i++)
	            {

	                $sr_no 						= $csv_line[0];
	               	$product_name 				= $csv_line[1];
	               	$product_code 				= $csv_line[2];
	               	$category_id 				= $csv_line[3];
	               	$brand_name 				= $csv_line[4];
	               	$hsn_code 					= $csv_line[5];
	               	$gst 						= $csv_line[6];
	               	$selling_price 				= $csv_line[7];
	               	$unit 						= $csv_line[8];
	               	$product_video_1 			= $csv_line[9];
	               	$product_video_2 			= $csv_line[10];
	               	$product_video_3 			= $csv_line[11];
	               	$product_description 		= $csv_line[12];
	               	$company_id 				= $csv_line[13];

	            }

	           	
	            $data[] = array(
	            	'vendor_id' 				=> $company_id,
	               	'product_name' 				=> trim($product_name),
	               	'product_supplier_cat_id' 	=> !empty($category_id)?$category_id:'',
	               	'product_code' 				=> !empty($product_code)?trim($product_code):'',
	               	'brand' 					=> !empty($brand_name)?trim($brand_name):'',
	               	'hsn_code' 					=> !empty($hsn_code)?$hsn_code:'',
	               	'gst' 						=> !empty($gst)?$gst:'',
	               	'product_selling_price' 	=> !empty($selling_price)?$selling_price:'',
	               	'selling_price_currency' 	=> 'inr',
	               	'product_unit' 				=> !empty($unit)?$unit:'',
	               	'product_video_1' 			=> !empty($product_video_1)?$product_video_1:'',
	               	'product_video_2' 			=> !empty($product_video_2)?$product_video_2:'',
	               	'product_video_3' 			=> !empty($product_video_3)?$product_video_3:'',
	               	'product_description' 		=> !empty($product_description)?trim($product_description):'',
	               	'status'  					=> 1,
	               	'approved_status' 			=> 1,
	               	'approved_by' 				=> 'admin',
	               	'csv_row_no'      			=> $sr_no,
	               );
	        }
       
	     
			// array to store value (like :array to store email ids)
	        $duplicate_arr 					= array();
	        $product_codes    				= array(); 
	        $product_names    				= array(); 
	        $company_ids     				= array(); 
			$unique_array = array(); // array to store unique arrays
			$scoreArray = [];
			$i = 0 ;
			$countArr = count($data);

			if(!empty($countArr) && $countArr <= 1000){
	        	foreach ($data as $key => $value) {
	        		$checkProductCodeExist 		= $this->ProductModel->checkIsProductCodeExist($value['product_code']);
	        		if(empty($value['product_name'])) {
	                	$value['uniq_code']	= $uniq_code_no;
	                    $duplicate_arr[] = $value;
	                    $duplicate_arr[$i]['rejected_reason'] = 'Product name is required';
	                    unset($value[$key]);
	                    $i++;
	                } else if(!empty($value['product_code']) && $checkProductCodeExist > 0) {
	                	$value['uniq_code']	= $uniq_code_no;
						$duplicate_arr[] 	= $value;
						$duplicate_arr[$i]['rejected_reason'] = 'Product code is already exist in our system';
						unset($value[$key]);
						$i++;
	                } else if(empty($value['vendor_id'])) {
	                	$value['uniq_code']	= $uniq_code_no;
	                    $duplicate_arr[] = $value;
	                    $duplicate_arr[$i]['rejected_reason'] = 'Company id is required';
	                    unset($value[$key]);
	                    $i++;
	                } else {	
						$product_codes[] 			= $value['product_code'];
						$product_names[] 			= $value['product_name']; 
						$company_ids[] 				= $value['vendor_id']; 
						$value['uniq_code'] 	    = $uniq_code_no;
						// add the array to the unique_array
						$unique_array[] 			= $value; 
					}
				}
		
				if(!empty($unique_array)){
					$insert  = $this->ProductModel->uploadData('product',$unique_array);
					if(!empty($insert)){
						$getLastCsvRecord = $this->ProductModel->getLastCsvRecord($uniq_code_no);
						if(!empty($getLastCsvRecord)){
							 
							foreach ($getLastCsvRecord as $key1 => $p_value) { 
								$total_score = 0; 
								$product_name_score  = 0;
								$product_price_score = 0;
								$product_description_score = 0;
								
								if(!empty($p_value['product_name'])){
									$product_name_score = 10;
								}
								if(!empty($p_value['product_selling_price'])){
									$product_price_score = 10;
								}
								if(!empty($p_value['product_description'])){
									$product_description_score = 25;
								}
								$total_score = $product_name_score + $product_price_score + $product_description_score;

								$scoreArray[] 	= array(
									'vendor_id' 				=> $p_value['vendor_id'],
									'product_id' 				=> $p_value['product_id'],
									'product_name_score' 		=> $product_name_score,
									'product_price_score' 		=> $product_price_score,
									'product_description_score' => $product_description_score,
									'total_score' 				=> $total_score,
								);
							}
							$insert_score  = $this->ProductModel->uploadData('vendor_product_score_table',$scoreArray);
						}
					}
				}
				
				
				if(!empty($duplicate_arr)){
					$insert1 = $this->ProductModel->uploadData('product_rejeted_datalog',$duplicate_arr);
				}
				fclose($fp) or die("can't close file");
				redirect('product/product_rejected_list/'.base64_encode($uniq_code_no),'refresh');
				/*redirect('product/all_product_list','refresh');*/
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

	public function product_rejected_list(){
		$uri_segment 					= 	$this->uri->segment(3);
		$productFormat 					= 	$this->productRejectedDataFormat();
		$data['columns']				=	$productFormat['product_reject_column'];
		$data['rejectedProductData']	=	$productFormat['name'];
		$data['uniq_code']				=	$uri_segment;
		$data["page_title"] 			= 	"View Product Rejected Information";
		$data['success_csvdata'] 		=   $this->ProductModel->getSuccessCsvInfo($uri_segment);
		$data['failed_csvdata'] 		=   $this->ProductModel->getFailedCsvInfo($uri_segment);
		$page["layout_content"] 		= 	$this->load->view('pages/product/product_reject_data_list', $data, true);
        $page["script_files"]			= 	$this->load->view('scripts/product/product',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	} 

	private function productRejectedDataFormat() {
		$retData['product_reject_column'] = array("Excel Row No","Product Name","Company Name","Created Date","Rejected Reason");
        $retData['name'] = "[
        	{ 'data' : 'csv_row_no' },
        	{ 'data' : 'product_name' },
        	{ 'data' : 'company_name' },
        	{ 'data' : 'created_at' },
        	{ 'data' : 'rejected_reason' },
    	]";

        return $retData;
	}

	public function product_rejected_listAjax() {
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
   				$result = $this->ProductModel->getRejectedProductReport($limit,$offset,$search,$data['uniq_code']);
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

	public function successCsvDownload(){
		$postData = $this->input->post();
		if(!empty($postData)){
			$filename = 'successProductData_'.date('Ymd').'.csv';
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=$filename");
			header("Content-Type: application/csv; "); 
			// get data
			$usersData = $this->ProductModel->exportSuccessProductCsv($postData['uniq_code']);
			// file creation
			$file = fopen('php://output', 'w');

			$header = array("Product id","Product Name","Company name");
			fputcsv($file, $header);

			foreach ($usersData as $key=>$line){
			 fputcsv($file,$line);
			}

			fclose($file);
			exit;
		}
	}

	public function uploadProductSpecificationCsv(){
		if(!empty($_FILES['sp_userfile']['name'])){
			$fp = fopen($_FILES['sp_userfile']['tmp_name'],'r') or die("can't open file");
			fgetcsv($fp);
			$uniq_code_no 	= rand(10,9999);
			while($csv_line = fgetcsv($fp))
	        {
	        	
	            for($i = 0, $j = count($csv_line); $i < $j; $i++)
	            {

	               	$product_id 					= $csv_line[0];
	               	$product_spection_title 		= $csv_line[1];
	               	$product_spection_description 	= $csv_line[2];
	            }

	            $data[] = array(
	            	'product_id' 				=> !empty($product_id)?$product_id:'',
	               	'title' 					=> !empty($product_spection_title)?$product_spection_title:'',
	               	'specification_details' 	=> !empty($product_spection_description)?$product_spection_description:'',
	            );
	        }
       

			// array to store value (like :array to store email ids)
	        $duplicate_arr 					= array();
	        $specification_details    		= array(); 
			$unique_array = array(); // array to store unique arrays
			$scoreArray = [];
			$i = 0 ;
			$countArr = count($data);

			if(!empty($countArr) && $countArr <= 1000){
	        	foreach ($data as $key => $value) {
	        		$checkProductCodeExist 		= $this->ProductModel->checkIsProductSpecificationExist($value['product_id']);
	        		if(empty($checkProductCodeExist)){
	        			if(!empty($product_spection_title) && empty($product_spection_description)){
	        				$value['uniq_code']	= $uniq_code_no;
							$duplicate_arr[] 	= $value;
							$duplicate_arr[$i]['rejected_reason'] = 'Please product specification details';
							unset($value[$key]);
							$i++;
	        			} else {
	        				$specification_details[] 	= $value['specification_details'];
	        				$value['uniq_code'] 	    = $uniq_code_no;
							// add the array to the unique_array
							$unique_array[] 			= $value; 
	        			}
	        		}
				}

				if(!empty($unique_array)){
					$insert  = $this->ProductModel->uploadData('product_specification_tbl',$unique_array);
					
					if(!empty($insert)){
						$get_last_csv_record = $this->ProductModel->getLastProductSpecificationRecord($uniq_code_no);
						if(!empty($get_last_csv_record)){

							foreach ($get_last_csv_record as $key1 => $p_value) { 
								$product_score_check = $this->ProductModel->getScoreDetails($p_value['product_id']);
								if(!empty($product_score_check)){
									foreach ($product_score_check as $key2 => $scvalue) {
										$total_score = $scvalue['total_score'] + 25;
										$sql =" UPDATE `vendor_product_score_table` SET `product_specification_score` = 25, `total_score` = ".$total_score." WHERE product_id = ? ";
										$query = $this->db->query($sql, $p_value['product_id']);
									}
									
									
								}

							}
						}
					
					}
				}
				
				
				fclose($fp) or die("can't close file");
				$this->session->set_flashdata('product_mapping_success','Product Specification uploaded successfully');
				redirect('product/all_product_list','refresh');
			}else{
				fclose($fp) or die("can't close file");
				$this->session->set_flashdata('product_mapping_error','Please upload 1000 data only');
				redirect('product/all_product_list','refresh');
			}
		} else {
			$this->session->set_flashdata('product_mapping_error','Please Upload a csv');
			redirect('product/all_product_list','refresh');
		}
	}

	public function uploadMicroCategoryCsv(){
		if(!empty($_FILES['mapp_userfile']['name'])) {
			$fp = fopen($_FILES['mapp_userfile']['tmp_name'],'r') or die("can't open file");
			fgetcsv($fp);
			$uniq_code_no 	= rand(10,9999);
			while($csv_line = fgetcsv($fp))
	        {
	        	
	            for($i = 0, $j = count($csv_line); $i < $j; $i++)
	            {
	               	$product_id 					= $csv_line[0];
	               	$company_id 					= $csv_line[1];
	               	$parent_category_id 			= $csv_line[2];
	               	$sub_category_id 				= $csv_line[3];
	               	$micro_category_id 				= $csv_line[4];
	            }

	            $data[] = array(
	            	'product_id' 				=> !empty($product_id)?$product_id:'',
	               	'vendor_id' 				=> !empty($company_id)?$company_id:'',
	               	'autogorila_parent_cat_id'  => !empty($parent_category_id)?$parent_category_id:'',
	               	'autogorila_sub_cat_id' 	=> !empty($sub_category_id )?$sub_category_id :'',
	               	'autogorila_micro_cat_id' 	=> !empty($micro_category_id )?$micro_category_id :'',
	            );
	        }
	
			$unique_array = array(); // array to store unique arrays
			$countArr = count($data);

			if(!empty($countArr) && $countArr <= 1000){
	        	foreach ($data as $key => $value) {
	        		$value['uniq_code'] 	    = $uniq_code_no;
					$unique_array[] = $value;  // add the array to the unique_array
	        	}

				if(!empty($unique_array)){
					$insert  = $this->ProductModel->uploadData('vendor_product_mapping',$unique_array);
					if($insert){
						$get_last_csv_record = $this->ProductModel->getLastMappingRecord($uniq_code_no);
						if(!empty($get_last_csv_record)){
							foreach ($get_last_csv_record as $key2 => $map_value) {
								$sql =" UPDATE `product` SET `product_mapped` = 1 WHERE product_id = ? ";
										$query = $this->db->query($sql, $map_value['product_id']);
							}
						}
					}
				}
				
				fclose($fp) or die("can't close file");
				$this->session->set_flashdata('product_mapping_success','Product mapping successfully');
				redirect('product/all_product_list','refresh');
			}else{
				fclose($fp) or die("can't close file");
				$this->session->set_flashdata('product_mapping_error','Please upload 1000 data only');
				redirect('product/all_product_list','refresh');
			}
		} else {
			$this->session->set_flashdata('product_mapping_error','Please upload a csv');
			redirect('product/all_product_list','refresh');
		}
	}	

	public function sitemap(){
		$data["page_title"]				=	"Product site map";
		$data['count_products']			=   !empty($count_products)?$count_products:0;
       	$page["layout_content"] 		= 	$this->load->view('pages/product/productSitemap', $data, true);
        $page["script_files"]			= 	$this->load->view('scripts/product/product',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function generateProductSiteMapSitemap(){
		$sitemap 		= $this->ProductModel->siteMapforProduct();
		$array_break    = array_chunk($sitemap,10000);
    	$sitemappath 	= $_SERVER['DOCUMENT_ROOT'].'/'; 
		$actual_link 	= "https://www.autogorilla.com/";
		$lastmod 		= date("Y-m-d");
		$freq     = "daily";
		$priority = "1.0";
		$ch =0;
		
		foreach($array_break as $key => $value_slug)
		{
			$xmldata = '<?xml version="1.0" encoding="utf-8"?>'; 
			$xmldata .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';	
			foreach($value_slug as $k => $slug){
				$urlSlug = create_url($slug->product_url_slug);
				$url = $actual_link.'products/'.$urlSlug;
				$xmldata .= '<url>';
				$xmldata .= '<loc>'.$url.'</loc>';
				$xmldata .= '<lastmod>'.$lastmod.'</lastmod>';
				$xmldata .= '<changefreq>'.$freq.'</changefreq>';
				$xmldata .= '<priority>'.$priority.'</priority>';
				$xmldata .= '</url>';
			}
			$keys = $key+1;
			$xmldata .= '</urlset>';
			$fileName = 'product-details-'.$keys.'.xml';
			$dom = new DOMDocument;
			$dom->preserveWhiteSpace = FALSE;
			$result = $dom->loadXML($xmldata);
			if (!$result) {
				$errors = libxml_get_errors();
				foreach ($errors as $error) {
					// Handle or log XML errors
					// Example: echo "XML Error: {$error->message}\n";
				}
				libxml_clear_errors();
			} else {
				$dom->save($sitemappath . $fileName);
			}
		}
		redirect('sitemap/sitemap_url');
		
	}

	/*end of the section*/

	/***** Start Seo Dynamic section ****/

	public function seo_info(){
		$url_slug 						=   $this->uri->segment(3);
		$res 							=   $this->ProductModel->fetchProductDetailsById($url_slug);
		$data['fetch_data'] 			=	$res[0];		
		$data["page_title"] 			=  "Seo Information";
        $page["layout_content"] 		= 	$this->load->view('pages/product/addSeoInformation', $data, true);
        $page["script_files"]			= 	$this->load->view('scripts/product/product',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function addSeoinfo(){
		$submit = $this->input->post();
		if(!empty($submit)) {
			$check_seo_data 		= seo_meta_information($submit['page_name'],$submit['page_url']);
	     	$seo_multi_meta_info 	= seo_multi_meta_information($submit['page_name'],$submit['page_url']); 
	   		$seo_social_og_info  	= seo_social_ogInfo($submit['page_name'],$submit['page_url']); 

	     	if(!empty($check_seo_data)){
	     		$delete_data =  $this->ProductModel->hardDelete('seo_meta_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}

	     	if(!empty($seo_multi_meta_info) && empty($submit['meta_name'])){
	     		$delete_data =  $this->ProductModel->hardDelete('seo_multiple_meta_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}else if(!empty($seo_multi_meta_info)){
	     		$delete_data =  $this->ProductModel->hardDelete('seo_multiple_meta_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}else if(empty($submit['meta_name'])){
	     		$delete_data =  $this->ProductModel->hardDelete('seo_multiple_meta_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}

	     	if(!empty($seo_social_og_info) && empty($submit['social_meta_og_property'])){
	     		$delete_data =  $this->ProductModel->hardDelete('seo_social_og_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}else if(empty($submit['social_meta_og_property'])){
	     		$delete_data =  $this->ProductModel->hardDelete('seo_social_og_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}else if(!empty($seo_social_og_info)){
	     		$delete_data =  $this->ProductModel->hardDelete('seo_social_og_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}

			$seo_arr = array(
                'meta_title' 			=> $submit['meta_title'],
                'meta_description' 		=> $submit['meta_description'],
                'canonical_url' 		=> $submit['canonical_url'],
                'page_name' 			=> $submit['page_name'],
                'page_url' 				=> $submit['page_url'],
	        ); 

			$insert =	$this->ProductModel->insert('seo_meta_info',$seo_arr);
		
			if(isset($submit['meta_name'][0]) && trim($submit['meta_name'][0]) !=''){ 
				foreach ($submit['meta_name'] as $key => $value) {
					$finalArr[] = array(
	                    'meta_name' 	=> $value,
	                    'meta_content' 	=> $submit['meta_content'][$key],
	                    'page_name' 	=> $submit['page_name'],
	                    'page_url' 		=> $submit['page_url'],
	                    'seo_meta_id' 	=> $insert,
	            	); 
				}
				$insert_meta = $this->db->insert_batch('seo_multiple_meta_info', $finalArr);
			}
			if(isset($submit['social_meta_og_property'][0]) && trim($submit['social_meta_og_property'][0]) !=''){
				foreach ($submit['social_meta_og_property'] as $key1 => $og_value) {
					$finalArr2[] = array(
						'social_meta_og_property' 	=> $og_value,
	                	'social_meta_og_content' 	=> $submit['social_meta_og_content'][$key1],
	                	'page_name' 				=> $submit['page_name'],
	                    'page_url' 					=> $submit['page_url'],
	                    'seo_meta_id' 				=> $insert,
	                );    
				}
					
				$insert_meta_og = $this->db->insert_batch('seo_social_og_info', $finalArr2);
			}
			if($insert) {
				$this->session->set_flashdata('seo_success','Data added successfully');
				redirect('product/seo_info/'.$submit['product_id']);
			} else {
				$this->session->set_flashdata('seo_error','Failed to add data');
				redirect('product/seo_info/'.$submit['product_id']);
			}
		}
	}

}

/* End of file Product.php */
/* Location: ./application/controllers/Product.php */