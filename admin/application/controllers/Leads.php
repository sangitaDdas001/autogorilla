<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Leads extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('LeadsModel'));
	}

	public function all_leads(){
		$catFormat 				= 	$this->leadsDetailsFormat();
		$data['columns']		=	$catFormat['cat_column'];
		$data['catData']		=	$catFormat['name'];
		$data["page_title"] 	=   "View Leads Information";
        $page["layout_content"] =   $this->load->view('pages/leads/viewLeads', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/leads/leads',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function active_leads(){
		$catFormat 				= 	$this->leadsDetailsFormat();
		$data['columns']		=	$catFormat['cat_column'];
		$data['catData']		=	$catFormat['name'];
		$data["page_title"] 	=   "View Active Leads Information";
        $page["layout_content"] =   $this->load->view('pages/leads/activeViewLeads', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/leads/leads',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function reject_leads(){
		$catFormat 				= 	$this->leadsDetailsFormat();
		$data['columns']		=	$catFormat['cat_column'];
		$data['catData']		=	$catFormat['name'];
		$data["page_title"] 	=   "View Reject Leads Information";
        $page["layout_content"] =   $this->load->view('pages/leads/rejectViewLeads', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/leads/leads',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function leadsDetailsFormat() {

		$retData['cat_column'] = array("Lead ID","Lead Type","Enquiry Company Name","Product name","User Name","User Phone","Created Date","Status","Action");
        $retData['name'] = "[
        	{ 'data' : 'id' },
        	{ 'data' : 'lead_type' },
        	{
        		'render' : function (data, type, row, meta) {
    				let html = '';
    				if(row.vendor_email) {
	    				html += row.company_name;
	    				html += '<br>';
	    				html += '<span style=\"color: red;font-weight: 500;\">(';
	        			html += row.vendor_email;
	        			html += ')</span>';
	        		}else{
	        			html += row.company_name;
	        		}
        			return html;
        		}
        	},
        	{ 'data' : 'product_name' },
        	{
        		'render' : function (data, type, row, meta) {
    				let html = '';
    				if(row.email && row.user_name) {
	    				html += row.user_name;
	    				html += '<br>';
	    				html += '<span style=\"color: #118b20;font-weight: 550;\">(';
	        			html += row.email;
	        			html += ')</span>';
	        		}else if(row.email){
	        			html += '';
	        		}else{
	        			html += row.user_name;
	        		}
        			return html;
        		}
        	},
        	{ 'data' : 'phone' },
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\">Active</span>';
        			} else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Reject</span>';
        			}
        		}
        	},
        	{ 'defaultContent' : '<a class=\"leads_details\" href=\"javascript:void(0)\" title=\"leads view details\" data-toggle=\"modal\" style=\"color: #000;\"><i class=\"fa fa-eye\" style=\"margin-right: 8px;font-size: 20px;\" aria-hidden=\"true\"></i></a>'
	        }
    	]";

        return $retData;
	}

	public function leadsDetails_ajax(){
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

				//$data['searchByFromMin']= isset($data['searchByFromMin']) && $data['searchByFromMin']!="" ? $data['searchByFromMin'] : '';
				//$data['searchByToMax']= isset($data['searchByToMax']) && $data['searchByToMax']!="" ? $data['searchByToMax'] : '';

				if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="" && isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$data['searchByToMax']= $data['searchByToMax'];

					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
				}
				else if(isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByToMax']= $data['searchByToMax'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else
				{
					unset($_SESSION['searchByFromMin']);
					unset($_SESSION['searchByToMax']);
				}

   				$result = $this->LeadsModel->getAllLeads($limit,$offset,$search,$data['searchByFromMin'],$data['searchByToMax']);
   				//echo $_SESSION['searchByFromMin']."==".$_SESSION['searchByToMax'];exit;
   				//echo $result;exit;
   				
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

	public function active_leadsDetails_ajax(){
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

				if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="" && isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$data['searchByToMax']= $data['searchByToMax'];

					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
				}
				else if(isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByToMax']= $data['searchByToMax'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else
				{
					unset($_SESSION['searchByFromMin']);
					unset($_SESSION['searchByToMax']);
				}

   				$result = $this->LeadsModel->getActiveLeads($limit,$offset,$search,$data['searchByFromMin'],$data['searchByToMax']);
   				//echo $data['searchByFromMin']."==".$data['searchByToMax'];exit;
   				
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

	public function reject_leadsDetails_ajax(){
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

				if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="" && isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$data['searchByToMax']= $data['searchByToMax'];

					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
				}
				else if(isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByToMax']= $data['searchByToMax'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else
				{
					unset($_SESSION['searchByFromMin']);
					unset($_SESSION['searchByToMax']);
				}

   				$result = $this->LeadsModel->getRejectLeads($limit,$offset,$search,$data['searchByFromMin'],$data['searchByToMax']);
   				
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

	public function leads_details($id){
		$data["page_title"] 	= 	"Leads Detals";
		$data['lead_details'] 	=   $this->LeadsModel->LeadDetailsById($id);
		$page["layout_content"] =   $this->load->view('pages/leads/leads_view_details', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/leads/leads',$data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}

	public function rejectedReasonUpdate(){
		$postData = $this->input->post();
		try {
			if(empty($postData['id'])){
				echo json_encode(array('status'=>false,'msg'=>'id_not_found','data'=>'not_found'));die;
			}else if(empty($postData['rejected_reason'])){
				echo json_encode(array('status'=>false,'msg'=>'rejected_reason_blank','data'=>'not_found'));die;
			} else {
				$updateData = array();
				$updateData = array(
					'rejected_reason' => $postData['rejected_reason'],
					'status' 		  => 'I'
				);
				$update = $this->LeadsModel->updateLead($updateData,$postData['id']);	
				if($update)	{
					echo json_encode(array('status'=>true,'msg'=>'rejected_reason_updated','data'=>'found'));die;
				} else {
					echo json_encode(array('status'=>false,'msg'=>'not_updated_reason','data'=>'not_found'));die;
				}
			}

		} catch (Exception $e) {
			echo json_encode(array('status'=>false,'msg'=>'rejected_reason_blank','data'=>'not_found'));die;
		}
	}

	public function activeLeads(){
		$postData = $this->input->post();
		$id = !empty($postData['id'])?$postData['id']:'';

		if($id == '') {
			echo json_encode(array('status' =>FALSE, 'message' => 'Failed to update. Please try again'));
		}
		if(empty($postData)) {
			echo json_encode(array('status' =>FALSE, 'message' => 'Failed to update. Please try again'));
		} else {
			$updateStatus = array(
				'rejected_reason' => '',
				'status' 		  => 'A'
			);
			$response = $this->LeadsModel->updateLead($updateStatus,$id);
			if ($response) {
				$this->session->set_flashdata('cat_success',"Successfully updated");	
				echo json_encode(array('status'=>TRUE,'message'=>'Successfully updated'));
			} else {
				$this->session->set_flashdata('cat_error',"Status not updated");	
				echo json_encode(array('status'=>FALSE,'message'=>'Failed to update. Please try again'));
			}
		}
	}


	public function allLeadCsvExport() 
	{
		//echo $_SESSION['searchByFromMin']."==".$_SESSION['searchByToMax'];exit;
		$filename = 'allLead_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 
		// get data
		$usersData = $this->LeadsModel->exportAllLeadCsvExport();
		
		//print_r($usersData);exit;
		// file creation
		$file = fopen('php://output', 'w');
		$header = array("ID","Company name","Company email","Product name","User name","User email","User phone","Status");
		fputcsv($file, $header);
		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}
		fclose($file);
		exit;
	}

	public function activeLeadCsvExport() 
	{
		$filename = 'activeLead_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 
		// get data
		$usersData = $this->LeadsModel->exportActiveLeadCsvExport();
		// file creation
		$file = fopen('php://output', 'w');
		$header = array("ID","Company name","Company email","Product name","User name","User email","User phone","Status");
		fputcsv($file, $header);
		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}
		fclose($file);
		exit;
	}

	public function rejectLeadCsvExport() 
	{
		$filename = 'rejectLead_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 
		// get data
		$usersData = $this->LeadsModel->exportRejectLeadCsvExport();
		// file creation
		$file = fopen('php://output', 'w');
		$header = array("ID","Company name","Company email","Product name","User name","User email","User phone","Status");
		fputcsv($file, $header);
		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}
		fclose($file);
		exit;
	}


	/***** BUY LEAD *****/

	public function buy_leads(){
		$catFormat 				= 	$this->buyleadsDetailsFormat();
		$data['columns']		=	$catFormat['cat_column'];
		$data['buyleadData']	=	$catFormat['name'];
		$data["page_title"] 	=   "View Buy Leads Information";
        $page["layout_content"] =   $this->load->view('pages/leads/viewbuyLeads', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/leads/leads',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function active_buy_leads(){
		$catFormat 					= 	$this->buyleadsDetailsFormat();
		$data['columns']			=	$catFormat['cat_column'];
		$data['activebuyleadData']	=	$catFormat['name'];
		$data["page_title"] 		=   "View Active Leads Information";
        $page["layout_content"] 	=   $this->load->view('pages/leads/viewactivebuyLeads', $data, true);
        $page["script_files"]		=	$this->load->view('scripts/leads/leads',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function buyleadsDetailsFormat() {

		$retData['cat_column'] = array("Lead ID","Lead Type","Enquiry Company Name","Product name","User Name","User Phone","Created Date","Status","Action");
        $retData['name'] = "[
        	{ 'data' : 'id' },
        	{ 'data' : 'lead_type' },
        	{
        		'render' : function (data, type, row, meta) {
    				let html = '';
    				if(row.vendor_email) {
	    				html += row.company_name;
	    				html += '<br>';
	    				html += '<span style=\"color: red;font-weight: 500;\">(';
	        			html += row.vendor_email;
	        			html += ')</span>';
	        		}else{
	        			html += row.company_name;
	        		}
        			return html;
        		}
        	},
        	{ 'data' : 'product_name' },
        	{
        		'render' : function (data, type, row, meta) {
    				let html = '';
    				if(row.email && row.user_name ) {
	    				html += row.user_name;
	    				html += '<br>';
	    				html += '<span style=\"color: #118b20;font-weight: 550;\">(';
	        			html += row.email;
	        			html += ')</span>';
	        		}else if(row.email == ''){
	        			html += '';
	        		}else{
	        			html += row.user_name;
	        		}
        			return html;
        		}
        	},
        	{ 'data' : 'phone' },
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\">Active</span>';
        			} else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Reject</span>';
        			}
        		}
        	},
        	{ 'defaultContent' : '<a class=\"leads_details\" href=\"javascript:void(0)\" title=\"leads view details\" data-toggle=\"modal\" style=\"color: #000;\"><i class=\"fa fa-eye\" style=\"margin-right: 8px;font-size: 20px;\" aria-hidden=\"true\"></i></a>'
	        }
    	]";

        return $retData;
	}

	public function buyleadsDetails_ajax(){
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

				if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="" && isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$data['searchByToMax']= $data['searchByToMax'];

					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
				}
				else if(isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByToMax']= $data['searchByToMax'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else
				{
					unset($_SESSION['searchByFromMin']);
					unset($_SESSION['searchByToMax']);
				}

   				$result = $this->LeadsModel->getAllBuyLeads($limit,$offset,$search,$data['searchByFromMin'],$data['searchByToMax']);
   			
   				
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

	public function activeBuyleadsDetails_ajax(){
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

				if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="" && isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$data['searchByToMax']= $data['searchByToMax'];

					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
				}
				else if(isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByToMax']= $data['searchByToMax'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else
				{
					unset($_SESSION['searchByFromMin']);
					unset($_SESSION['searchByToMax']);
				}

   				$result = $this->LeadsModel->getAllActiveBuyLeads($limit,$offset,$search,$data['searchByFromMin'],$data['searchByToMax']);
   			
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

	public function activeBuyLeadCsvExport() 
	{
		//echo $_SESSION['searchByFromMin']."==".$_SESSION['searchByToMax'];exit;
		$filename = 'active_all_buy_Lead_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 
		// get data
		$usersData = $this->LeadsModel->exportActiveBuyLeadCsvExport();
		
		//print_r($usersData);exit;
		// file creation
		$file = fopen('php://output', 'w');
		$header = array("ID","Company name","Company email","Product name","User name","User email","User phone","Status");
		fputcsv($file, $header);
		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}
		fclose($file);
		exit;
	}

	public function allBuyLeadCsvExport() 
	{
		//echo $_SESSION['searchByFromMin']."==".$_SESSION['searchByToMax'];exit;
		$filename = 'active_all_buy_Lead_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 
		// get data
		$usersData = $this->LeadsModel->exportAllBuyLeadCsvExport();
		
		//print_r($usersData);exit;
		// file creation
		$file = fopen('php://output', 'w');
		$header = array("ID","Company name","Company email","Product name","User name","User email","User phone","Status");
		fputcsv($file, $header);
		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}
		fclose($file);
		exit;
	}

	/***** DIRECT LEADS *****/

	public function direct_leads(){
		$catFormat 				= 	$this->directLeadsDetailsFormat();
		$data['columns']		=	$catFormat['cat_column'];
		$data['directleadData']	=	$catFormat['name'];
		$data["page_title"] 	=   "View Buy Leads Information";
        $page["layout_content"] =   $this->load->view('pages/leads/viewDirectLeads', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/leads/leads',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function active_direct_leads(){
		$catFormat 						= 	$this->directLeadsDetailsFormat();
		$data['columns']				=	$catFormat['cat_column'];
		$data['activedirectleadData']	=	$catFormat['name'];
		$data["page_title"] 			=   "View Buy Leads Information";
        $page["layout_content"] 		=   $this->load->view('pages/leads/viewActiveDirectLeads', $data, true);
        $page["script_files"]			=	$this->load->view('scripts/leads/leads',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function directLeadsDetailsFormat() {

		$retData['cat_column'] = array("Lead ID","Lead Type","Enquiry Company Name","Product name","User Name","User Phone","Created Date","Status","Action");
        $retData['name'] = "[
        	{ 'data' : 'id' },
        	{ 'data' : 'lead_type' },
        	{
        		'render' : function (data, type, row, meta) {
    				let html = '';
    				if(row.vendor_email) {
	    				html += row.company_name;
	    				html += '<br>';
	    				html += '<span style=\"color: red;font-weight: 500;\">(';
	        			html += row.vendor_email;
	        			html += ')</span>';
	        		}else{
	        			html += row.company_name;
	        		}
        			return html;
        		}
        	},
        	{ 'data' : 'product_name' },
        	{
        		'render' : function (data, type, row, meta) {
    				let html = '';
    				if(row.email && row.user_name ) {
	    				html += row.user_name;
	    				html += '<br>';
	    				html += '<span style=\"color: #118b20;font-weight: 550;\">(';
	        			html += row.email;
	        			html += ')</span>';
	        		}else if(row.email == ''){
	        			html += '';
	        		}else{
	        			html += row.user_name;
	        		}
        			return html;
        		}
        	},
        	{ 'data' : 'phone' },
        	{ 'data' : 'created_at' },
        	{
        		'render' : function (data, type, row, meta) {
        			if(row.status == 'Active') {
        				return '<span class=\"badge badge-success change_status cursor-pointer\">Active</span>';
        			} else {
        				return '<span class=\"badge badge-warning change_status cursor-pointer\">Reject</span>';
        			}
        		}
        	},
        	{ 'defaultContent' : '<a class=\"leads_details\" href=\"javascript:void(0)\" title=\"leads view details\" data-toggle=\"modal\" style=\"color: #000;\"><i class=\"fa fa-eye\" style=\"margin-right: 8px;font-size: 20px;\" aria-hidden=\"true\"></i></a>'
	        }
    	]";

        return $retData;
	}

	public function directLeadsDetails_ajax(){
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

				if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="" && isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$data['searchByToMax']= $data['searchByToMax'];

					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
				}
				else if(isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByToMax']= $data['searchByToMax'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else
				{
					unset($_SESSION['searchByFromMin']);
					unset($_SESSION['searchByToMax']);
				}

   				$result = $this->LeadsModel->getAllDirectLeads($limit,$offset,$search,$data['searchByFromMin'],$data['searchByToMax']);
   			
   				
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

	public function activeDirectLeadsDetails_ajax(){
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

				if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="" && isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$data['searchByToMax']= $data['searchByToMax'];

					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else if(isset($data['searchByFromMin']) && $data['searchByFromMin']!="")
				{
					$data['searchByFromMin']= $data['searchByFromMin'];
					$_SESSION['searchByFromMin']=$data['searchByFromMin'];
				}
				else if(isset($data['searchByToMax']) && $data['searchByToMax']!="")
				{
					$data['searchByToMax']= $data['searchByToMax'];
					$_SESSION['searchByToMax']=$data['searchByToMax'];
				}
				else
				{
					unset($_SESSION['searchByFromMin']);
					unset($_SESSION['searchByToMax']);
				}

   				$result = $this->LeadsModel->getActiveDirectLeads($limit,$offset,$search,$data['searchByFromMin'],$data['searchByToMax']);
   			
   				
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

	public function directActiveLeadCsvExport() 
	{
		//echo $_SESSION['searchByFromMin']."==".$_SESSION['searchByToMax'];exit;
		$filename = 'active_all_direct_Lead_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 
		// get data
		$usersData = $this->LeadsModel->exportActiveDirectLeadCsvExport();
		
		//print_r($usersData);exit;
		// file creation
		$file = fopen('php://output', 'w');
		$header = array("ID","Company name","Company email","Product name","User name","User email","User phone","Status");
		fputcsv($file, $header);
		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}
		fclose($file);
		exit;
	}

	public function directAllLeadCsvExport() 
	{
		//echo $_SESSION['searchByFromMin']."==".$_SESSION['searchByToMax'];exit;
		$filename = 'all_direct_Lead_'.date('Ymd').'.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; "); 
		// get data
		$usersData = $this->LeadsModel->exportAllDirectLeadCsvExport();
		
		//print_r($usersData);exit;
		// file creation
		$file = fopen('php://output', 'w');
		$header = array("ID","Company name","Company email","Product name","User name","User email","User phone","Status");
		fputcsv($file, $header);
		foreach ($usersData as $key=>$line){
		 fputcsv($file,$line);
		}
		fclose($file);
		exit;
	}



}

/* End of file Leads.php */
/* Location: ./application/controllers/Leads.php */ ?>