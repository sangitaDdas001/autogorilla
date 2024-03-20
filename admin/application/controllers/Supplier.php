<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('SupplierModel','ServiceModel','ProductModel','CategoryModel'));
	}

	public function csvDownload(){
		$file = UPLOAD_IMAGE_URL.'vendor_company_info.csv'; //path to the file on disk
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

	public function pendingDataCsvDownload(){
		$filename = 'pending_supplier_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 
		// get data
		$usersData = $this->SupplierModel->exportPendingVendorDetails();
		// file creation
		$file = fopen('php://output', 'w');
		$header = array("id","Company Name","Vendor name","Created Date and Time");
		fputcsv($file, $header);
		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}
		fclose($file);
		exit;
	}

	public function rejectedDataCsvDownload(){
		$filename = 'rejected_supplier_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 
		// get data
		$usersData = $this->SupplierModel->exportRejectedVendorDetails();
		// file creation
		$file = fopen('php://output', 'w');
		$header = array("id","Company Name","Vendor name","Created Date and Time");
		fputcsv($file, $header);
		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}
		fclose($file);
		exit;
	}

	public function exportAllDataByCsv(){
		$filename = 'supplier_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 


		// get data
		$usersData = $this->SupplierModel->exportVendorDetails();
		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","Company Name","Vendor name","GST");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;
	}

	public function approvedDataCsvDownload(){
		$filename = 'supplier_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 


		// get data
		$usersData = $this->SupplierModel->exportApprovedVendorDetails();
		// file creation
		$file = fopen('php://output', 'w');

		$header = array("id","Company Name","Email","Vendor name","GST","Created Date&Time");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;
	}

	public function uploadCsvFile(){
		$fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
		fgetcsv($fp);
		$uniq_code_no 	    			= rand(10,9999);
		while($csv_line = fgetcsv($fp))
        {
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {

                $sr_no 						= $csv_line[0];
               	$company_name 				= $csv_line[1];
               	$vendor_catalog_url_slug 	= $csv_line[2];
               	$contact_name 				= $csv_line[3];
               	$designation 				= $csv_line[4];
               	$mobile 					= $csv_line[5];
               	$alternate_mobile_no 		= $csv_line[6];
               	$landline_no 				= $csv_line[7];
               	$alternate_landline_no 		= $csv_line[8];
               	$email 						= $csv_line[9];
               	$alternate_email 			= $csv_line[10];
               	$website_url 				= $csv_line[11];
               	$address 					= $csv_line[12];
               	$area_id  					= $csv_line[13];
               	$city_id 					= $csv_line[14];
               	$state_id 					= $csv_line[15];
               	$country_id 				= $csv_line[16];
               	$pincode 					= $csv_line[17];
               	$image 						= $csv_line[18];
               	$company_image 				= $csv_line[19];
               	$company_type 				= $csv_line[20];
               	$year_of_establishment 		= $csv_line[21];
               	$gst 					    = $csv_line[22];
               	$pan 					    = $csv_line[23];
               	$tan 						= $csv_line[24];
               	$cin_llpin 					= $csv_line[25];
               	$business_type 				= $csv_line[26];
               	$ownership_type 			= $csv_line[27];
               	$no_of_employees 			= $csv_line[28];
               	$annual_turn_over 			= $csv_line[29];
               	$about_company 				= $csv_line[30];
               	$decript_password 			= rand(9999,9999999);
               	$password 					= base64_encode($decript_password);
            }

            $url  = create_url($company_name);
            $new_url  = 'company/'.$url;
            $data[] = array(
               	'name' 						=> !empty($contact_name)?$contact_name:'',
               	'vendor_catalog_url_slug' 	=> !empty($vendor_catalog_url_slug)?'company/'.$vendor_catalog_url_slug: $new_url,
               	'designation' 				=> !empty($designation)?$designation:'',
               	'image' 					=> !empty($image)?$image:'',
               	'email' 					=> !empty($email)?$email:'test'.rand(10,999).'@autogorilla.com',
               	'alternate_email' 			=> !empty($alternate_email)?$alternate_email:'',
               	'password' 					=> $password,
               	'decript_password' 			=> $decript_password,
               	'phone' 					=> !empty($mobile)?$mobile:'',
               	'alternate_mobile_no' 		=> !empty($alternate_mobile_no)?$alternate_mobile_no:'',
               	'landline_no' 				=> !empty($landline_no)?$landline_no:'',
               	'alternate_landline_no' 	=> !empty($alternate_landline_no)?$alternate_landline_no:'',
               	'company_name' 				=> !empty($company_name)?$company_name:'',
               	'company_image' 			=> !empty($company_image)?$company_image:'',
               	'company_type' 				=> !empty($company_type)?$company_type:'',
               	'year_of_establishment'     => !empty($year_of_establishment)?$year_of_establishment:'',
               	'company_ceo_name' 			=> !empty($company_ceo_name)?$company_ceo_name:'',
               	'website_url' 				=> !empty($website_url)?$website_url:'',
               	'about_company' 			=> !empty($about_company)?$about_company:'',
               	'building_no_floor' 		=> !empty($building_no_floor)?$building_no_floor:'',
               	'address'  					=> !empty($address)?$address:'',
               	'alternate_address' 		=> !empty($alternate_address)?$alternate_address:'',
               	'landmark'  				=> !empty($landmark)?$landmark:'',
               	'country_id' 				=> !empty($country_id)?$country_id:'101',
               	'state_id' 					=> !empty($state_id)?$state_id:'',
               	'city_id' 					=> !empty($city_id)?$city_id:'',
               	'area_id' 					=> !empty($area_id)?$area_id:'',
               	'pincode' 					=> !empty($pincode)?$pincode:'',
               	'gst' 						=> !empty($gst)?trim($gst):'',
               	'pan' 						=> !empty($pan)?trim($pan):'',
               	'tan' 		 				=> !empty($tan)?trim($tan):'',
               	'cin_llpin' 				=> !empty($cin_llpin)?trim($cin_llpin):'',
               	'business_type'  			=> !empty($business_type)?$business_type:'',
               	'ownership_type'            => !empty($ownership_type)?$ownership_type:'',
               	'no_of_employees'  			=> !empty($no_of_employees)?$no_of_employees:'',
               	'annual_turn_over'  		=> !empty($annual_turn_over)?$annual_turn_over:'',
               	'otp_varify' 				=> 1,
               	'login_on' 					=> 0,
               	'login_from'  				=> 'web',
               	'status'  					=> 1,
               	'approved_status' 			=> 0,
               	'type' 						=> 1,
               	'featured_company'  		=> 0,
               	'profile_check_by'  		=> 'web',
               	'autogorilla_verified'      => 'N',
               	'csv_row_no'      			=> $sr_no,
               );
        	
        	
        }

		// array to store value (like :array to store email ids)
        $duplicate_arr 					= array();
        $email_ids    					= array(); 
        $phone_nos    					= array(); 
        $vendor_catalog_url_slug    	= array(); 
        $alternate_email    			= array(); 
        $alternate_mobile_no    		= array(); 
        $landline_no    				= array(); 
        $alternate_landline_no    		= array(); 
        $gst    						= array(); 
        $tan    						= array(); 
        $pan    						= array(); 
        $website_url    				= array(); 
        

		$unique_array = array(); // array to store unique arrays
		$i = 0 ;
		$countArr = count($data);
		if(!empty($countArr) && $countArr <= 1000){
        	foreach ($data as $key => $value) {
        		$checkEmailExist 		= $this->SupplierModel->checkIsEmailExist($value['email']);
        		$checkPhoneExist 		= $this->SupplierModel->checkIsPhoneExist($value['phone']);
        		$checkCompany_urlExist 	= $this->SupplierModel->checkIsCatelogUrlExist($value['vendor_catalog_url_slug']);
        		$checkCompany_GstExist 	= $this->SupplierModel->checkIsGSTExist($value['gst']);
        		$checkCompany_TanExist 	= $this->SupplierModel->checkIsTANExist($value['tan']);
        		$checkCompany_PanExist 	= $this->SupplierModel->checkIsPANExist($value['pan']);

        		if(in_array($value['email'], $email_ids) && in_array($value['phone'], $phone_nos) && in_array($value['vendor_catalog_url_slug'], $vendor_catalog_url_slug) && empty($value['email']) && empty($value['phone'])) {
        			
                	$value['uniq_code']	= $uniq_code_no;
                	$duplicate_arr[] 	= $value;
                    unset($value[$key]);
                }
				else if (!empty($value['email']) && $checkEmailExist > 0) {
					$value['uniq_code']	= $uniq_code_no;
					$duplicate_arr[] = $value;// if email id is not unique, remove the array
					$duplicate_arr[$i]['rejected_reason'] = 'Email id is already exist in our system';
					unset($value[$key]);
					$i++;
				} else if (!empty($value['email']) && in_array($value['email'], $email_ids)) {
					$value['uniq_code']	= $uniq_code_no;
					$duplicate_arr[] = $value;// if email id is not unique, remove the array
					$duplicate_arr[$i]['rejected_reason'] = 'Email id is already exist';
					unset($value[$key]);
					$i++;
				} else if(!empty($value['phone']) && $checkPhoneExist >0){
                   	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'This mobile no is already exist in our system';
                    unset($value[$key]);
                    $i++;
                } else if(!empty($value['phone']) && in_array($value['phone'], $phone_nos)){
                   	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'This mobile no is already exist';
                    unset($value[$key]);
                    $i++;
                } else if(!empty($value['vendor_catalog_url_slug']) && !empty($checkCompany_urlExist)){
                	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'This catelog url is already exist in our system';
                    unset($value[$key]);
                    $i++;
                } else if(!empty($value['vendor_catalog_url_slug']) && in_array($value['vendor_catalog_url_slug'], $vendor_catalog_url_slug)){
                	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'This catelog url is already exist';
                    unset($value[$key]);
                    $i++;
                } else if(empty($value['company_name'])) {
                	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Company name is required';
                    unset($value[$key]);
                    $i++;
                } else if(!empty($value['gst']) && $checkCompany_GstExist >0) {
                	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Gst no is already exist in our system';
                    unset($value[$key]);
                    $i++;
                }else if(!empty($value['gst']) && in_array($value['gst'], $gst)) {
                	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Gst no is already exist';
                    unset($value[$key]);
                    $i++;
                } else if(!empty($value['tan']) && in_array($value['tan'], $tan)) {
                	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Tan no is already exist';
                    unset($value[$key]);
                    $i++;
                }else if(!empty($value['tan']) && $checkCompany_TanExist > 0) {
                	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Tan no is already exist in our system';
                    unset($value[$key]);
                    $i++;
                } else if(!empty($value['pan']) && $checkCompany_PanExist > 0 ) {
                	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Pan no is already exist in our system';
                    unset($value[$key]);
                    $i++;
                }else if(!empty($value['pan']) && in_array($value['pan'], $pan)) {
                	$value['uniq_code']	= $uniq_code_no;
                    $duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Pan no is already exist';
                    unset($value[$key]);
                    $i++;
                } else if(!empty($value['website_url']) && in_array($value['website_url'], $website_url)){
                	$value['uniq_code']	= $uniq_code_no;
                	$duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Website url is already exist';
                    unset($value[$key]);
                    $i++;
                } else if(!empty($value['alternate_email']) && in_array($value['alternate_email'], $alternate_email)){
                	$value['uniq_code']	= $uniq_code_no;
                	$duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Alternate email id is already exist';
                    unset($value[$key]);
                    $i++;
                } else if(!empty($value['alternate_mobile_no']) && in_array($value['alternate_mobile_no'], $alternate_mobile_no)){
                	$value['uniq_code']	= $uniq_code_no;
                	$duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Alternate phone no is already exist';
                    unset($value[$key]);
                    $i++;
                } else if(!empty($value['landline_no']) && in_array($value['landline_no'], $landline_no)){
                	$value['uniq_code']	= $uniq_code_no;
                	$duplicate_arr[] 	= $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Landline no is already exist';
                    unset($value[$key]);
                    $i++;
                }
                else if(!empty($value['alternate_landline_no']) && in_array($value['alternate_landline_no'], $alternate_landline_no)){
                	$value['uniq_code']	= $uniq_code_no;
                	$duplicate_arr[] = $value;
                    $duplicate_arr[$i]['rejected_reason'] = 'Alternate Landline no is already exist';
                    unset($value[$key]);
                    $i++;
                }
                else {
                	// if value is unique, add it to the email_ids array

					$vendor_catalog_url_slug[] 	= $value['vendor_catalog_url_slug'];
					$email_ids[] 				= $value['email']; 
					$phone_nos[] 				= $value['phone']; 
					$pan[] 						= $value['pan'];
					$tan[] 						= $value['tan'];
					$gst[] 						= $value['vendor_catalog_url_slug'];
					$alternate_email[] 			= $value['alternate_email'];
					$alternate_landline_no[] 	= $value['alternate_landline_no'];
					$alternate_mobile_no[] 	    = $value['alternate_mobile_no'];
					$website_url[] 	    		= $value['website_url'];
					$value['uniq_code'] 	    = $uniq_code_no;

					// add the array to the unique_array
					$unique_array[] 			= $value; 
				}
			}
		 
			if(!empty($unique_array)){
				$insert  = $this->SupplierModel->uploadData('vendor_info',$unique_array);
				if(!empty($insert)){
					$getLastCsvRecord = $this->SupplierModel->getLastCsvRecord($uniq_code_no);
					
					if(!empty($getLastCsvRecord)){
						foreach ($getLastCsvRecord as $key1 => $v_value) { 
							$package_data[] = array(
								'vendor_id' 			=> $v_value['id'],
								'service_package_id' 	=> 1,
								'package_start_date'    => date('Y-m-d h:i:s')

							);
						}
						$insert2 = $this->SupplierModel->uploadData('vendor_service_package_list',$package_data);
					}
				}
			}
			
			if(!empty($duplicate_arr)){
				$insert1 = $this->SupplierModel->uploadData('vendor_rejeted_datalog',$duplicate_arr);
			}
			fclose($fp) or die("can't close file");
			redirect('supplier/company_rejected_list/'.base64_encode($uniq_code_no),'refresh');
		}else{
			fclose($fp) or die("can't close file");
			$this->session->set_flashdata('suppliers_error','Please upload 1000 data only');
			redirect('supplier','refresh');
		}
	}

	public function company_rejected_list(){
		$uri_segment = $this->uri->segment(3);
		$supplierFormat 			= 	$this->companyRejectedDataFormat();
		$data['columns']			=	$supplierFormat['supplier_column'];
		$data['companyData']		=	$supplierFormat['name'];
		$data['uniq_code']			=	$uri_segment;
		$data["page_title"] 		= 	"View Vendor Rejected Information";
		$data['success_csvdata'] 	=   $this->SupplierModel->getSuccessCsvInfo($uri_segment);
		$data['failed_csvdata'] 	=   $this->SupplierModel->getFailedCsvInfo($uri_segment);
		$page["layout_content"] 	= 	$this->load->view('pages/supplier/company_rejected_info', $data, true);
        $page["script_files"]		= 	$this->load->view('scripts/supplier/supplier',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}


	private function companyRejectedDataFormat() {
		$retData['supplier_column'] = array("Excel Row No","Company Name","Email","Phone","Created Date","Rejected Reason");
        $retData['name'] = "[
        	{ 'data' : 'csv_row_no' },
        	{ 'data' : 'company_name' },
        	{ 'data' : 'email' },
        	{ 'data' : 'phone' },
        	{ 'data' : 'created_at' },
        	{ 'data' : 'rejected_reason' },
    	]";

        return $retData;
	}


	public function company_rejected_listAjax() {
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
   				$result = $this->SupplierModel->getRejectedCompanyReport($limit,$offset,$search,$data['uniq_code']);
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

	public function exportFailedCsvData(){
		$postData = $this->input->post();
		$filename = 'failed_company_datalog'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 


		// get data
		$usersData = $this->SupplierModel->exportfailedCompanyData($postData['uniq_code']);
		// file creation
		$file = fopen('php://output', 'w');

		$header = array("Excel Row No","Company Name","Email","Phone","Created Date&Time","Rejected Reason");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;
	}

	public function exportSuccessCsvData(){
		$postData = $this->input->post();
		$filename = 'success_company_datalog'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 


		// get data
		$usersData = $this->SupplierModel->exportSuccessCompanyData($postData['uniq_code']);
		// file creation
		$file = fopen('php://output', 'w');

		$header = array("Company Id","Company Name","Supplier Name","Email","Phone","Created Date&Time");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;
	}

	/*** END OF THE CSV SECTION *****/

	public function index(){
		$data["page_title"] 	= 	"View Vendor Information";
		$page["layout_content"] = 	$this->load->view('pages/supplier/supplierList', $data, true);
        $page["script_files"]	= 	$this->load->view('scripts/supplier/supplier',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function approved_supplier(){
		$supplierFormat 				= 	$this->supplierDetailsFormat();
		$data['columns']				=	$supplierFormat['supplier_column'];
		$data['supplierData']			=	$supplierFormat['name'];
		$data["page_title"] 			= 	"View Vendor Information";
		$data['check_approved_data']  	=   $this->SupplierModel->existApproveData();
		$page["layout_content"] 		= 	$this->load->view('pages/supplier/approved_supplier_list', $data, true);
        $page["script_files"]			= 	$this->load->view('scripts/supplier/supplier',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function reject_supplier(){
		$supplierFormat 			= 	$this->supplierRejectDetailsFormat();
		$data['columns']			=	$supplierFormat['supplier_column'];
		$data['supplierRejectData']	=	$supplierFormat['name'];
		$data["page_title"] 		= 	"View Vendor Information";
		$data['check_reject_data']  =   $this->SupplierModel->existRejectData();
		$page["layout_content"] 	= 	$this->load->view('pages/supplier/reject_supplier_list', $data, true);
        $page["script_files"]		= 	$this->load->view('scripts/supplier/supplier',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function pending_supplier(){
		$supplierFormat 			= 	$this->supplierDetailsFormat();
		$data['columns']			=	$supplierFormat['supplier_column'];
		$data['supplierData']		=	$supplierFormat['name'];
		$data["page_title"] 		= 	"View Vendor Information";
		$data['check_pending_data'] =   $this->SupplierModel->existPendingData();
		$page["layout_content"] 	= 	$this->load->view('pages/supplier/pending_supplier_list', $data, true);
        $page["script_files"]		= 	$this->load->view('scripts/supplier/supplier',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}


	private function supplierRejectDetailsFormat() {
		// for view permission
        /*$this->db->select("m_pv_tbl.sub_menu,m_pv_tbl.permissionType");
		$this->db->from('menu_previllage_tbl m_pv_tbl');
		$this->db->where(array('m_pv_tbl.parent_menu_id'=>19,'m_pv_tbl.admin_user_id' => $_SESSION['adminsessiondetails']['id']));
		$query 		= $this->db->get();
	    $sub_data  	= $query->result_array();

		$menuPermission = fetchParentMenu();
		$menu_arr =[];
		if($_SESSION['adminsessiondetails']['id'] != 1){
			if(!empty($sub_data)){
		        if(!empty($menuPermission)){
			        foreach ($menuPermission as $key => $menu_value) {
			        	if(!empty($menu_value['permissionType'])){
				        	if($menu_value['id'] == 19 && $menu_value['permissionType'] == 1){
				        	  array_push($menu_arr, $menu_value['permissionType']);
				        	}
				        }
			        }
			    }
			}
		}*/

		
			$retData['supplier_column'] = array("Sl No","Company Id","Company Name","Vendor Name","Email","Created Date","Status","Update Status","Feature Company","Action");
	        $retData['name'] = "[
	        	{ 'data' : 'sl_no' },
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
	        	{ 'data' : 'name' },
	        	{ 'data' : 'email' },
	        	{ 'data' : 'created_at' },
	        	{
	        		'render' : function (data, type, row, meta) {
	        			return '<span  style=\"color: #f90d18;\">Rejected</span>';
	        		}
	        	},
	        	{
	        		'render' : function (data, type, row, meta) {
	        			return '<span class=\"badge badge-success approve_status cursor-pointer\">Approve</span>';
	        		}
	        	},
	        	{
	        		'render' : function (data, type, row, meta) {
	        			if(row.featured_company == 0) {
	        				return '<span class=\"badge badge-warning change_feature_company cursor-pointer\" style=\"background-color: #958686e3;\">Feature</span>';
	        			}  else {
	        				return '<span class=\"badge badge-warning change_feature_company cursor-pointer\" style=\"background-color: #0a4c2ed1;\">Featured</span>';
	        			}
	        		}
	        	},
	        	{ 'defaultContent' : '<a class=\"profile_info\" href=\"javascript:void(0)\" title=\"Profile\" data-toggle=\"modal\" style=\"color: #766969;\"><i class=\"fa fa-user\" style=\"margin-right: 8px;font-size: 20px;\" aria-hidden=\"true\"></i></a><a class=\"product_info\" href=\"javascript:void(0)\" title=\"Product List\" data-toggle=\"modal\" style=\"color: #15608d;\"><i class=\"fa fa-product-hunt\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a><a class=\"package_mapping\" href=\"javascript:void(0)\" title=\"Package Mapping\" data-toggle=\"modal\" style=\"color: black;\"><i class=\"fa fa-map\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a>'
	        	}
	    	]";
	    	

        return $retData;
	}

	private function supplierDetailsFormat() {
		/*$menuPermission = fetchParentMenu();
		$menu_arr =[];

		$this->db->select("m_pv_tbl.sub_menu,m_pv_tbl.permissionType");
		$this->db->from('menu_previllage_tbl m_pv_tbl');
		$this->db->where(array('m_pv_tbl.parent_menu_id'=>19,'m_pv_tbl.admin_user_id' => $_SESSION['adminsessiondetails']['id']));
		$query 		= $this->db->get();
	    $sub_data  	= $query->result_array();
	 
		if($_SESSION['adminsessiondetails']['id'] != 1){
			if(!empty($sub_data)){
		        if(!empty($menuPermission)){
			        foreach ($menuPermission as $key => $menu_value) {
			        	if(!empty($menu_value['permissionType'])){
				        	if($menu_value['id'] == 19 && $menu_value['permissionType'] == 1){
				        	  array_push($menu_arr, $menu_value['permissionType']);
				        	}
				        }
			        }
			    }
			}
		}*/
		
		$retData['supplier_column'] = array("Sl No","Company Id","Company Name","Company Vendor Name","Email","Catelog Avg Score","Membership","Verified","Feature","Created Date","Status","Update Status","Action");
        $retData['name'] = "[
        	{ 'data' : 'sl_no' },
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
        	{ 'data' : 'name' },
        	{ 'data' : 'email' },
        	{ 'data' : 'cat_avscore' },
        	{
        		'render' : function (data, type, row, meta) {
        			let html = '';
        			let service_plan = row.service_plan;
        			if(service_plan != null){
	        			html += service_plan;
	        			html += '<span class=\"badge badge-warning membership_listing cursor-pointer\" style=\"background-color: #0a4c2ed1;\">Subcription Details</span>';
	        		}
	        		return html;
        		}
        		
        	},
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
        				return '<span class=\"badge badge-warning change_feature_company cursor-pointer\" style=\"background-color: #958686e3;\">Feature</span>';
        			}  else {
        				return '<span class=\"badge badge-warning change_feature_company cursor-pointer\" style=\"background-color: #0a4c2ed1;\">Featured</span>';
        			}
        		}
        	},
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
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.approved_status == 'Approved') {
        				return '<span class=\"badge badge-warning reject_status cursor-pointer\">Reject</span>';
        			} else if(row.approved_status == 'Reject') {
        				return '<span class=\"badge badge-success approve_status cursor-pointer\">Approve</span>';
        			} else {
        				return '<span class=\"badge badge-success approve_status cursor-pointer\">Approve</span> <span class=\"badge badge-warning reject_status cursor-pointer\">Reject</span>';
        			}
        		}
        	},

        	
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.approved_status == 'Not Approved') {
        				return '<a class=\"profile_info\" href=\"javascript:void(0)\" title=\"Profile\" data-toggle=\"modal\" style=\"color: #766969;\"><i class=\"fa fa-user\" style=\"margin-right: 8px;font-size: 20px;\" aria-hidden=\"true\"></i></a><a class=\"product_info\" href=\"javascript:void(0)\" title=\"Product List\" data-toggle=\"modal\" style=\"color: #15608d;\"><i class=\"fa fa-product-hunt\" style=\"margin-right: 3px;font-size: 16px;margin-left: 6px;\" aria-hidden=\"true\"></i></a>';
        			}  else {
        				let html  = '';
        				let rowid = row.id;
        				html += '<a class=\"profile_info\" href=\"javascript:void(0)\" title=\"Profile\" data-toggle=\"modal\" style=\"color: #766969;\"><i class=\"fa fa-user\" style=\"margin-right: 8px;font-size: 20px;\" aria-hidden=\"true\"></i></a><a class=\"product_info\" href=\"javascript:void(0)\" title=\"Product List\" data-toggle=\"modal\" style=\"color: #15608d;\"><i class=\"fa fa-product-hunt\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a><a class=\"package_mapping\" href=\"javascript:void(0)\" title=\"Package Mapping\" data-toggle=\"modal\" style=\"color: black;\"><i class=\"fa fa-map\" style=\"margin-right: 3px;font-size: 16px;\" aria-hidden=\"true\"></i></a><a class=\"category_info\" href=\"javascript:void(0)\" title=\"Category Info\" style=\"color: #15608d;\" data-id=\"';
        				html += rowid;
	        			html += '\"><i class=\"fa fa-list-alt\" aria-hidden=\"true\"></i><a/>';
	        			return html;
        			}
        		}
        	},
        	
    	]";

		
	
        return $retData;
	}


	public function supplierDetails_ajax() {
		$response = array();
		try {
			$postData = $this->input->post();
			if(empty($postData)) {
				echo json_encode($response); die;
			} else {
   				$result = $this->SupplierModel->getVendorDetails($postData);
   				
				echo json_encode($result); die;
			}
		} catch (Exception $e) {
			echo json_encode($response); die;
		}
		
	}

	public function approved_supplierDetails_ajax() {
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
				$searchByFromMin = !empty($data['searchByFromMin'])?$data['searchByFromMin']:'';
				$searchByFromMax = !empty($data['searchByToMax'])?$data['searchByToMax']:'';
   				$result = $this->SupplierModel->getapprovedVendorDetails($limit,$offset,$search,$searchByFromMin,$searchByFromMax);
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

	public function pending_supplierDetails_ajax() {
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
   				$result = $this->SupplierModel->getpendingVendorDetails($limit,$offset,$search);
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

	public function reject_supplierDetails_ajax() {
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
   				$result = $this->SupplierModel->getRejectVendorDetails($limit,$offset,$search);
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

	public function updateSupplierStatus(){
		$postData = $this->input->post();
		$id = !empty($postData['vendor_id'])?$postData['vendor_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->SupplierModel->changeStatus($postData);
			if ($response) {
				$this->session->set_flashdata('suppliers_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function updateSupplierApprovedStatus(){
		$postData = $this->input->post();
		$id = !empty($postData['vendor_id'])?$postData['vendor_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$checkActiveExitMailId = $this->SupplierModel->isActiveMailId($postData['email']);
			if(!empty($checkActiveExitMailId)){
				echo json_encode(array('status'=>FALSE,'message'=>'Already there is an account with the email id, kindly reject the same for the further activities.'));
			}else{
				$response = $this->SupplierModel->changeApprovedStatus($postData);
				if ($response) {
					$this->session->set_flashdata('cat_success',"Successfully updated");	
					echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
				} else {
					echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
				}
			}
		}
	}

	public function deleteSupplierDetails(){
		$postData = $this->input->post();
		$id = !empty($postData['vendor_id'])?$postData['vendor_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->SupplierModel->deleteVendor($postData);
			if ($response) {
				$this->session->set_flashdata('product_success',"Successfully deleted");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function vendor_packageInfo(){
		$vendor_id = $this->uri->segment(3);
		$data = array();
		if(validateBase64($vendor_id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Vendor not selected'));
		}
		$package_id = 5;
		$data['get_services_details']   			=   $this->ServiceModel->fetchPackageData($vendor_id);
		$data['get_autogorilla_verified_data']   	=   $this->ServiceModel->fetchAuto_verifiedData($vendor_id,$package_id);
		$data['vendor_details']   					= 	$this->ServiceModel->getVendorDetailsById($vendor_id);
		$page["layout_content"]						=	$this->load->view('pages/service/packageInfo', $data, true);
		$page["script_files"]						= 	$this->load->view('scripts/supplier/supplier',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function vendorPackageAdd(){
		$submit = $this->input->post();
		if(!empty($submit)) {
			if(!empty($submit['service_package_id'])){
				$check_vendor_packageId = $this->ServiceModel->checkPackageExist($submit['vendor_id']);
				if(empty($check_vendor_packageId)){
					foreach ($submit['service_package_id'] as $key => $value) {
						if($value !='' && $submit['package_start_date'][$key] !='' && $submit['package_expiry_date'][$key]){
								$date1 = new DateTime($submit['package_start_date'][$key]);
								$date2 = new DateTime($submit['package_expiry_date'][$key]);
								$interval = $date1->diff($date2);
								$calculateTime = $interval->y . " years, " . $interval->m." months, ".$interval->d." days "; 
								// shows the total amount of days (not divided into years, months and days like above)
								$totalCalculatetime = $interval->days . " days ";
							$package_ids[]= array(
				                'service_package_id' 			=> !empty($value)?$value:'',
				                'vendor_id' 					=> $submit['vendor_id'],
				                'package_start_date' 			=> $submit['package_start_date'][$key],
				                'package_expiry_date' 			=> $submit['package_expiry_date'][$key],
				                'total_date_calculate' 			=> $calculateTime,
				                'total_calculate_days' 			=> $totalCalculatetime,
			                );
						}
					}
					
					if(!empty($package_ids)){
			    		$insert_vendor_package = $this->ServiceModel->insertBatch('vendor_service_package_list', $package_ids);
					}
				}else{
					$delete_packageId = $this->ServiceModel->deleteVendorPackage($submit['vendor_id']);
					foreach ($submit['service_package_id'] as $key => $value) {
						if($value !='' && $submit['package_start_date'][$key] !='' && $submit['package_expiry_date'][$key]){
								$date1 = new DateTime($submit['package_start_date'][$key]);
								$date2 = new DateTime($submit['package_expiry_date'][$key]);
								$interval = $date1->diff($date2);
								$calculateTime = $interval->y . " years, " . $interval->m." months, ".$interval->d." days "; 
								// shows the total amount of days (not divided into years, months and days like above)
								$totalCalculatetime = $interval->days . " days ";
							$package_ids[]= array(
				                'service_package_id' 			=> !empty($value)?$value:'',
				                'vendor_id' 					=> $submit['vendor_id'],
				                'package_start_date' 			=> $submit['package_start_date'][$key],
				                'package_expiry_date' 			=> $submit['package_expiry_date'][$key],
				                'total_date_calculate' 			=> $calculateTime,
				                'total_calculate_days' 			=> $totalCalculatetime,
			                );
						}
					}
					if(!empty($package_ids)){
			    		$insert_vendor_package = $this->ServiceModel->insertBatch('vendor_service_package_list', $package_ids);

					}
				}
				if(!empty($insert_vendor_package)){
					$this->session->set_flashdata('service_success','package added successfully');
					redirect('supplier/vendor_packageInfo/'.base64_encode($submit['vendor_id']));
				}else{
					$this->session->set_flashdata('service_error','package not added successfully');
					redirect('supplier/vendor_packageInfo/'.base64_encode($submit['vendor_id']));
				}
			}else{
				$this->session->set_flashdata('service_error','package not selected');
				redirect('supplier/vendor_packageInfo/'.base64_encode($submit['vendor_id']));
			}
			
		}else{
			$this->session->set_flashdata('service_error','package not selected');
			redirect('supplier/vendor_packageInfo/'.base64_encode($submit['vendor_id']));
		}
	}

	public function updateAutogorilla_verified_status(){
		$postData = $this->input->post();
		if(!empty($postData)){
			if(!empty($postData['package_start_date']) && empty($postData['package_expiry_date'])){
				$this->session->set_flashdata('service_error','Please select service expiry date');
				redirect('supplier/vendor_packageInfo/'.base64_encode($postData['vendor_id']));die;
			}

			if(!empty($postData['package_expiry_date']) && empty($postData['package_start_date'])){
				$this->session->set_flashdata('service_error','Please select service expiry date');
				redirect('supplier/vendor_packageInfo/'.base64_encode($postData['vendor_id']));die;
			}

			if($postData['service_package_id'] == 5 ){
				$startDateTime = new DateTime($postData['package_start_date']);
				$endDateTime = new DateTime($postData['package_expiry_date']);
				
				$currentDate = new DateTime();

				if($currentDate > $startDateTime && $currentDate > $endDateTime){
					
					$tab = "vendor_info";
					$update_status = $this->ServiceModel->autogorilla_verify_status($tab,$postData['vendor_id'],'N');
					
				}else{
					$tab = "vendor_info";
					$update_status = $this->ServiceModel->autogorilla_verify_status($tab,$postData['vendor_id'],'Y');
					
				}
			}

			$checkPakageId = $this->ServiceModel->checkPackageId($postData['vendor_id'],$postData['service_package_id']);
			if(!empty($checkPakageId)){
				$date1 = new DateTime($postData['package_start_date']);
				$date2 = new DateTime($postData['package_expiry_date']);
				$interval = $date1->diff($date2);
				$calculateTime = $interval->y . " years, " . $interval->m." months, ".$interval->d." days "; 
					// shows the total amount of days (not divided into years, months and days like above)
				$totalCalculatetime = $interval->days . " days ";
				$packArray = array(
					'package_start_date'  	=> !empty($postData['package_start_date'])?$postData['package_start_date']:'',
					'package_expiry_date' 	=> !empty($postData['package_expiry_date'])?$postData['package_expiry_date']:'',
					'vendor_id' 		  	=> !empty($postData['vendor_id'])?$postData['vendor_id']:'',
					'service_package_id'  	=> !empty($postData['service_package_id'])?$postData['service_package_id']:'',
					'total_date_calculate' 	=> $calculateTime,
				    'total_calculate_days' 	=> $totalCalculatetime,
				);

				$table = "vendor_service_package_list";
				$response = $this->ServiceModel->update_package($packArray,$postData['vendor_id'],$postData['service_package_id'],$table);
				if($response){
					$this->session->set_flashdata('service_success','package added successfully');
					redirect('supplier/vendor_packageInfo/'.base64_encode($postData['vendor_id']));
				}
			}else{
				$date1 = new DateTime($postData['package_start_date']);
				$date2 = new DateTime($postData['package_expiry_date']);
				$interval = $date1->diff($date2);
				$calculateTime = $interval->y . " years, " . $interval->m." months, ".$interval->d." days "; 
					// shows the total amount of days (not divided into years, months and days like above)
				$totalCalculatetime = $interval->days . " days ";
				$packArray = array(
					'package_start_date'  	=> $postData['package_start_date'],
					'package_expiry_date' 	=> $postData['package_start_date'],
					'vendor_id' 		  	=> $postData['vendor_id'],
					'service_package_id'  	=> $postData['service_package_id'],
					'total_date_calculate' 	=> $calculateTime,
				    'total_calculate_days' 	=> $totalCalculatetime,
				);
				$insert_vendor_package = $this->ServiceModel->insert('vendor_service_package_list', $packArray);
				if($insert_vendor_package){
					$tab = "vendor_info";
					$update_status = $this->ServiceModel->autogorilla_verify_status($tab,$postData['vendor_id'],'Y');
					$this->session->set_flashdata('service_success','package added successfully');
					redirect('supplier/vendor_packageInfo/'.base64_encode($postData['vendor_id']));
				}
			}

		}
	}

	public function supplierRejected(){
		$postData = $this->input->post();
		$id = !empty($postData['vendor_id'])?$postData['vendor_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->SupplierModel->rejectStatus($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function rejected_all_pendingSupplier(){
		$response = $this->SupplierModel->rejectedAllPendingSupplier();
		if($response){
			$this->session->set_flashdata('suppliers_success',"Successfully Rejected all suppliers");
			echo json_encode(array('status'=>true,'message'=>'Successfully updated'));
		} else {
			$this->session->set_flashdata('suppliers_error',"Failed to rejected");
			echo json_encode(array('status'=>false,'message'=>'Successfully updated'));
		}
	}

	public function approved_all_supplier(){
		$response = $this->SupplierModel->approvedAllPendingSupplier();
		if($response){
			$this->session->set_flashdata('suppliers_success',"Successfully approved all suppliers");
			echo json_encode(array('status'=>true,'message'=>'Successfully updated'));
			//redirect('supplier/approved_supplier','refresh');
		} else {
			$this->session->set_flashdata('suppliers_error',"Failed to rejected");
			echo json_encode(array('status'=>false,'message'=>'Successfully updated'));
			//redirect('supplier/pending_supplier','refresh');
		} 
		// for pending supplier
	}

	public function rejected_all_ApprovedSupplier(){
		$response = $this->SupplierModel->rejectedAllApprovedSupplier();
		if($response){
			$this->session->set_flashdata('suppliers_success',"Successfully Rejected all suppliers");
			echo json_encode(array('status'=>true, 'message' => 'successfully updated'));
		} else {
			$this->session->set_flashdata('suppliers_error',"Failed to rejected");
			echo json_encode(array('status'=>false, 'message' => 'Failed to update. Please try again'));
		}
	}

	public function approved_all_supplierInfo(){
		$response = $this->SupplierModel->allApprovedSupplierInfo();
		if($response){
			$this->session->set_flashdata('suppliers_success',"Successfully approved all suppliers");
			echo json_encode(array('status'=>true, 'message' => 'successfully updated'));
		} else {
			$this->session->set_flashdata('suppliers_error',"Failed to rejected");
			echo json_encode(array('status'=>false, 'message' => 'Failed to update. Please try again'));
		}
	}
	public function rejected_all_Supplier(){
		$response = $this->SupplierModel->rejectAllSupplierInfo();
		if($response){
			$this->session->set_flashdata('suppliers_success',"Successfully Rejected all suppliers");
			echo json_encode(array('status'=>true, 'message' => 'successfully updated'));
		} else {
			$this->session->set_flashdata('suppliers_error',"Failed to rejected");
			echo json_encode(array('status'=>false, 'message' => 'Failed to update. Please try again'));
		}
	}


	public function approved_all_rejectedSupplier(){
		$response = $this->SupplierModel->approvedAllRejectedSupplier();
		if($response){
			$this->session->set_flashdata('suppliers_success',"Successfully Rejected all suppliers");
			echo json_encode(array('status'=>true, 'message' => 'successfully updated'));
		} else {
			$this->session->set_flashdata('suppliers_error',"Failed to rejected");
			echo json_encode(array('status'=>false, 'message' => 'Failed to update. Please try again'));
		}
	}


	public function subscriptionDelete(){
		$postData = $this->input->post();
		$vendor_id = !empty($postData['vendor_id'])?$postData['vendor_id']:'';
		$subscription_id = !empty($postData['subscriptionId'])?$postData['subscriptionId']:'';
		if(validateBase64($vendor_id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->ServiceModel->deleteSubscriptionByVendorId($postData);
			if ($response) {
				echo json_encode(array('status'=>true,'message'=>'Data deleted successfully'));
			} else {
				echo json_encode(array('status'=>false,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	/***** Supplier Product Part ******/

	public function productInfo(){
		$vendorId = $this->uri->segment(3);
		$supplierProductFormat 	= 	$this->supplierProductDetailsFormat();
		$data['columns']		=	$supplierProductFormat['product_column'];
		$data['productData']	=	$supplierProductFormat['product_name'];
		$data['vendorId']		=	$vendorId;
		$data["page_title"] 	= 	"View Product Information";
		$data['vendor_info'] 	=  $this->ProductModel->getvendorInfoById($vendorId);
		$page["layout_content"] = 	$this->load->view('pages/product/productList', $data, true);
        $page["script_files"]	= 	$this->load->view('scripts/supplier/supplier',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function supplierProductDetailsFormat(){
		$retData['product_column'] = array("Product Name","Created Date","Status","Product Total Score","Action");
		$path     = VIEW_IMAGE_URL.'product/';
		$no_image = VIEW_IMAGE_URL.'noimage.png';
        $retData['product_name'] = "[
        	{ 'data' : 'product_name'},
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
        	{ 'data' : 'total_score'},

        	{
        		'render' : function (data, type, row, meta) {
        			if(row.product_mapped == 1) {
        				return '<a class=\"product_edit\" href=\"javascript:void(0)\" title=\"Product Mapping\" data-toggle=\"modal\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 19px; color:#227676\" aria-hidden=\"true\"></i></a>';
        			} else if(row.approved_status == 'Reject') {
        				return '<a class=\"product_edit\" href=\"javascript:void(0)\" title=\"Product Mapping\" data-toggle=\"modal\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 19px; color:#227676\" aria-hidden=\"true\"></i></a>';
        			} else {
        				return '<a class=\"product_edit\" href=\"javascript:void(0)\" title=\"Product Mapping\" data-toggle=\"modal\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 19px; color:#227676\" aria-hidden=\"true\"></i></a>';
        				
        			}
        		}
        	},
        	
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
   				$result = $this->ProductModel->getProducts($limit,$offset,$search,$data['vendor_id']);
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

	public function supplier_profile_check(){
		$postData = $this->input->post();
		$id = !empty($postData['vendor_id'])?$postData['vendor_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->SupplierModel->profileCheckBy($postData);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function updateCompanyFeatureStatus() {
		$postData = $this->input->post();
		$id = !empty($postData['vendor_id'])?$postData['vendor_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->SupplierModel->changefeatureStatus($postData);
			if ($response) {
				$this->session->set_flashdata('suppliers_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	public function subscriptionList(){
		$postData = $this->input->post();
		$id = !empty($postData['vendor_id'])?$postData['vendor_id']:'';
		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'something went wrong, Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'something went wrong, Please try again'));
		} else {
			$response = $this->SupplierModel->fetchSubscriptionListById($postData);
			$menuPermission = fetchParentMenu();$menu_arr =[];
	        if(!empty($menuPermission)){
	        	foreach ($menuPermission as $key => $menu_value) {
		        	if(!empty($menu_value['permissionType'])){
			        	if($menu_value['id'] == 19 && $menu_value['permissionType'] == 1){
			        	  array_push($menu_arr, $menu_value['permissionType']);
			        	}
			        }
	        	}
	    	}
	        if(in_array('1',$menu_arr)){
		    	$permissionType = 1;
		    } else {
		    	$permissionType  = 0;
		    }
	      
			if ($response) {
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated','data'=>$response , 'permission_access'=>$permissionType));
			} else {
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}

	/**** END **** */

	public function supplierOwnCategory_info(){
		$vendorId = $this->uri->segment(3);
		$data['vendorId']		=	$vendorId;
		$data["page_title"] 	= 	"View Category Information";
		$page["layout_content"] = 	$this->load->view('pages/supplier/categoryList', $data, true);
        $page["script_files"]	= 	$this->load->view('scripts/supplier/supplier',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function supplierOwnCategoryDetails_ajax(){
		$response = array();
		try {
			$postData = $this->input->post();
			if(empty($postData)) {
				echo json_encode($response); die;
			} else {
   				$result = $this->SupplierModel->getSupplierCategoryInfo($postData);
				echo json_encode($result); die;
			}
		} catch (Exception $e) {
			echo json_encode($response); die;
		}
	
	}

	public function exportAllCategoryDataByCsv(){
		$postData = $this->input->post(); 
		$filename = 'supplierOwn_category_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 
		// get data
		$usersData = $this->SupplierModel->exportVendorCategoryDetails($postData['vendor_id']);
		// file creation
		$file = fopen('php://output', 'w');

		$header = array("Category Id","Company Name","Category Name");
		fputcsv($file, $header);

		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}

		fclose($file);
		exit;
	}

	/*** START SITE MAP FOR COMPANY URL **/

	public function supplierSiteMap(){
		$data["page_title"]				=	"Suppliers company site map";
		$count_suppliers 				= 	$this->SupplierModel->countActiveSuppliers();
		$data['count_suppliers']		=   !empty($count_suppliers)?$count_suppliers:0;
       	$page["layout_content"] 		= 	$this->load->view('pages/supplier/supplierSitemap', $data, true);
        $page["script_files"]			= 	$this->load->view('scripts/supplier/supplier',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function generateCompanySiteMap(){
		$sitemap 		= $this->SupplierModel->fetchActiveCompanyUrl();
		$array_break    = array_chunk($sitemap,40000);
    	$sitemappath 	= $_SERVER['DOCUMENT_ROOT'].'/'; 
		$actual_link 	= "http://dev.autogorilla.com/";
		$lastmod 		= date("Y-m-d h:i:sa");
		$freq     = "daily";
		$priority = "1.0";
		$ch =0;
		
		foreach($array_break as $key => $value_slug)
		{
			$xmldata = '<?xml version="1.0" encoding="utf-8"?>'; 
			$xmldata .= '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">';	
			foreach($value_slug as $k => $slug){
				$urlSlug = str_replace('company/','',$slug->vendor_catalog_url_slug);
				$url = $actual_link.'company/'.create_url($urlSlug);
				$xmldata .= '<url>';
				$xmldata .= '<loc>'.$url.'</loc>';
				$xmldata .= '<lastmod>'.$lastmod.'</lastmod>';
				$xmldata .= '<changefreq>'.$freq.'</changefreq>';
				$xmldata .= '<priority>'.$priority.'</priority>';
				$xmldata .= '</url>';
			}
			$keys = $key+1;
			$xmldata .= '</urlset>';
			$fileName = 'company-name-'.$keys.'.xml';
			$dom = new DOMDocument;
			$dom->preserveWhiteSpace = FALSE;
			$result = $dom->loadXML($xmldata);
			$dom->save($sitemappath.$fileName);
		}
		redirect('sitemap/sitemap_url');
	}


	    /*** Duplicate entry list ****/

    public function viewDuplicateSuppliers(){
		$catFormat 				= 	$this->duplicateSuppliersDetailsFormat();
		$data['columns']		=	$catFormat['supplier_column'];
		$data['duplicateSupData']=	$catFormat['name'];
		$data["page_title"] 	=   "View Dupliers Information";
        $page["layout_content"] =   $this->load->view('pages/supplier/duplicateSuppliers', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/supplier/supplier',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function duplicateSuppliersDetailsFormat() {
		$retData['supplier_column'] = array("Company Id","Company Name","Vendor Name","Email","Created Date","Status","Action");
	        $retData['name'] = "[
	        	{ 'data' : 'id' },
	        	{
	        		'render' : function (data, type, row, meta) {
		        		if(row.company_name == null) {
	        				return 'NULL';
	        			}else{
	        				return row.company_name;
	        			} 
	        		}
	        	},
	        	{ 'data' : 'name' },
	        	{ 'data' : 'email' },
	        	{ 'data' : 'created_at' },
	        	{
	        		'render' : function (data, type, row, meta) {
	        			if(row.approved_status == 1) {
	        				return '<span  style=\"color: #008000;\">Approved</span>';
	        			} else if(row.approved_status == 2) {
	        				return '<span  style=\"color: #f90d18;\">Rejected</span>';
	        			} else {
	        				return '<span  style=\"color: #1241ab;\">Pending</span>';
	        			}
	        		}
        		},
	        	{ 'defaultContent' : '<a class=\"delete_duplicate_data\" href=\"javascript:void(0)\" title=\"Delete\" data-toggle=\"modal\" style=\"color:red;\"><i class=\"fa fa-trash\" style=\"margin-right: 8px;font-size: 20px;\" aria-hidden=\"true\"></i></a>'
	        	}
	    	]";
	    	

        return $retData;
	}

	public function getDuplicateSuppliers(){
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
   				$result = $this->SupplierModel->fetchDuplicateSuppliersRecords($limit,$offset,$search);
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

	public function deleteSupplierRecord(){
		$postData = $this->input->post();
		$id = !empty($postData['vendor_id'])?$postData['vendor_id']:'';

		if(validateBase64($id) == false) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		}

		if(empty($postData)) {
			echo json_encode(array('status'=>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$response = $this->SupplierModel->hardDeleteSupplier($postData);
			$this->session->set_flashdata('suppliers_success',"Successfully updated");	
			echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
		}
	}

}

/* End of file Supplier.php */
/* Location: ./application/controllers/Supplier.php */