<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupplierModel extends CI_Model {

	public function getVendorDetails($postData){
		$this->db->select("c_per.company_id");
		$this->db->from('company_permission_tbl c_per');
		$this->db->where(array('c_per.admin_user_id' => $_SESSION['adminsessiondetails']['id'],'c_per.status'=>1));
		$query = $this->db->get();
        $data  = $query->result_array();

        $this->db->select("m_pv_tbl.sub_menu,m_pv_tbl.permissionType");
		$this->db->from('menu_previllage_tbl m_pv_tbl');
		$this->db->where(array('m_pv_tbl.parent_menu_id'=>19,'m_pv_tbl.admin_user_id' => $_SESSION['adminsessiondetails']['id'],'m_pv_tbl.status'=>1));
		$query 		= $this->db->get();
	    $sub_data  	= $query->result_array();

        $menuPermission = fetchParentMenu(); 
        $menu_arr = [];

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
		}

	   
        if($_SESSION['adminsessiondetails']['id'] != 1){
        	$response = array();
        	$draw = $postData['draw'];
		    $start = $postData['start'];
		    $rowperpage = $postData['length']; // Rows display per page
		    $columnIndex = $postData['order'][0]['column']; // Column index
		    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
		    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		    $searchValue = $postData['search']['value']; // Search value

		    if (!empty($postData['searchByFromMin']) && empty($postData['searchByToMax'])) {
		    	$where = "v.cat_avg_score >= ".$postData['searchByFromMin']." " ;
		    } else if(empty($postData['searchByFromMin']) && !empty($postData['searchByToMax'])) {
		    	$where = "v.cat_avg_score <= ".$postData['searchByFromMin']." ";
		    } else if (!empty($postData['searchByFromMin']) && !empty($postData['searchByToMax'])) {
		    	$where = "v.cat_avg_score BETWEEN ".$postData['searchByFromMin']." AND ".$postData['searchByToMax']." " ;
		    } else {
		    	$where = '';
		    }

	    	## Total number of record with filtering
    		$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%Y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company,v.autogorilla_verified, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_av_score, group_concat(distinct(se_pack.service_plan)) as service_plan",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
			$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
			$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
			if(!empty($sub_data)){
				if($sub_data[0]['sub_menu'] == 'sp_1' && !empty($data)){
					$this->db->join('company_permission_tbl c_p','c_p.company_id = v.id','left');
					$this->db->where(array('v.status !='=>'D', 'c_p.admin_user_id'=>$_SESSION['adminsessiondetails']['id'],'v.otp_varify'=>1));
				}else{
					$this->db->where(array('v.status !='=>'D', 'v.otp_varify'=>1));
				}
			}
			
			if(!empty($where)){
				$this->db->where($where);
			}
			if(!empty($postData['search']['value'])){
				$this->db->group_start();
				$this->db->like('v.email', $postData['search']['value'], 'both');
				$this->db->or_like('v.company_name', $postData['search']['value'], 'both');
				$this->db->or_like('se_pack.service_plan', $postData['search']['value'], 'both');
				$this->db->group_end();
			}
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$query = $this->db->get();
			$records  = $query->result_array();
	        $totalRecordwithFilter = count($records);
	        $totalRecords = count($records);  ## Total number of records without filtering

        	$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%Y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company,v.autogorilla_verified, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_av_score, group_concat(distinct(se_pack.service_plan)) as service_plan",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
			$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
			$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
			if(!empty($sub_data)){
				if($sub_data[0]['sub_menu'] == 'sp_1' && !empty($data)){
					$this->db->join('company_permission_tbl c_p','c_p.company_id = v.id','left');
					$this->db->where(array('v.status !='=>'D', 'c_p.admin_user_id'=>$_SESSION['adminsessiondetails']['id'],'v.otp_varify'=>1));
				}else{
					$this->db->where(array('v.status !='=>'D','v.otp_varify'=>1));
				}
			}
			if(!empty($where)){
				$this->db->where($where);
			}
			if(!empty($postData['search']['value'])){
				$this->db->group_start();
				$this->db->like('v.email', $postData['search']['value'], 'both');
				$this->db->or_like('v.company_name', $postData['search']['value'], 'both');
				$this->db->or_like('se_pack.service_plan', $postData['search']['value'], 'both');
				$this->db->group_end();
			}
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$this->db->limit($rowperpage, $start);
			$query = $this->db->get();
			//echo $this->db->last_query();
	        $records  = $query->result_array();
	        $data = array();

		    foreach($records as $record ){
		    	$sql='';
		    	$sql = "UPDATE vendor_info SET cat_avg_score =".$record['cat_av_score']." WHERE id = ?";
				$query = $this->db->query($sql,$record['id']);
		    	$satus = '';$updateStatus = '';$action_status = ''; $verified = ''; $featured_company = '';$service_plan='';
		    	if(in_array('1',$menu_arr)){
		    		$action_status = 'Only View Access';
		    	} else {
			    	if($record['approved_status'] == 'Not Approved'){
			    		$action_status = '<a class="profileInfo" href="javascript:void(0)" title="Profile" data-toggle="modal" style="color: #766969;" data-id='.$record['id'].' data-email = "'.$record['email'].'" data-password = "'.$record['password'].'"><i class="fa fa-user" style="margin-right: 8px;font-size: 20px;" aria-hidden="true"></i></a><a class="productInfo" href="javascript:void(0)" title="Product List" data-toggle="modal" style="color: #15608d;" data-id='.$record['id'].'><i class="fa fa-product-hunt" style="margin-right: 3px;font-size: 16px;margin-left: 6px;" aria-hidden="true"></i></a>';
			    	} else {
			    		$action_status = '<a class="profileInfo" href="javascript:void(0)" title="Profile" data-toggle="modal" style="color: #766969;"  data-id='.$record['id'].' data-email = "'.$record['email'].'" data-password = "'.$record['password'].'"><i class="fa fa-user" style="margin-right: 8px;font-size: 20px;" aria-hidden="true"></i></a><a class="productInfo" href="javascript:void(0)" title="Product List" data-toggle="modal" style="color: #15608d;" data-id='.$record['id'].'><i class="fa fa-product-hunt" style="margin-right: 3px;font-size: 16px;" aria-hidden="true"></i></a><a class="packageMapping" href="javascript:void(0)" title="Package Mapping" data-toggle="modal" style="color: black;" data-id='.$record['id'].'><i class="fa fa-map" style="margin-right: 3px;font-size: 16px;" aria-hidden="true" ></i></a>';
			    	}
			    }


		    	if($record['approved_status'] == 'Approved'){
		    		$status = '<span  style="color: #008000;">Approved</span>';
		    	}else if($record['approved_status'] == 'Reject'){
		    		$status = '<span  style="color: #f90d18;">Rejected</span>';
		    	}else{
		    		$status = '<span  style="color: #1241ab;">Pending</span>';
		    	}

		    	if(in_array('1',$menu_arr)){
		    		$updateStatus = 'Only View Access';
		    	} else {
			    	if($record['approved_status'] == 'Approved'){
			    		$updateStatus = '<span class="badge badge-warning rejectStatus cursor-pointer" data-id='.$record['id'].'>Reject</span>';
			    	}else if($record['approved_status'] == 'Reject'){
			    		$updateStatus = '<span class="badge badge-success approveStatus cursor-pointer" data-id='.$record['id'].'  data-email = "'.$record['email'].'">Approve</span>';
			    	}else{
			    		$updateStatus = '<span class="badge badge-success approveStatus cursor-pointer" data-id='.$record['id'].' data-email = "'.$record['email'].'" >Approve</span> <span class="badge badge-warning rejectStatus cursor-pointer" data-id='.$record['id'].'>Reject</span>';
			    	}
			    }

		    	if($record['autogorilla_verified']=='Y'){
		    		$verified = '<span  style="color: #008000;">Yes</span>';
		    	}else{
		    		$verified = '<span  style="color: #1241ab;">No</span>';
		    	}

		    	if(in_array('1',$menu_arr)){
		    		if($record['featured_company']== 0){
			    		$featured_company = '<span style="color: #ff0d0d;">Not Featured</span>';
			    	}else{
			    		$featured_company = '<span style="color: #008000;">Featured</span>';
			    	}
		    	} else {
			    	if($record['featured_company']== 0){
			    		$featured_company = '<span class="badge badge-warning change_feature_companys cursor-pointer" style="background-color: #958686e3;" data-id='.$record['id'].'>Feature</span>';
			    	}else{
			    		$featured_company = '<span class="badge badge-warning change_feature_companys cursor-pointer" style="background-color: #0a4c2ed1;" data-id='.$record['id'].'>Featured</span>';
			    	}
			    }

		    	if(!empty($record['service_plan'])){
		    		$service_plan = '<span>'.$record['service_plan'].'</span><br><span class="badge badge-warning membership_listing_sup cursor-pointer" style="background-color: #0a4c2ed1;" data-id='.$record['id'].' data-companyname= "'.$record['company_name'].'" >Subcription Details</span>';
		    	}


		        $data[] = array( 
		        	"id"				=> !empty($record['id'])?$record['id']:'',
		           "company_name"		=> !empty($record['company_name'])?$record['company_name']:'',
		           "name"				=> !empty($record['name'])?$record['name']:'',
		           "email" 				=> !empty($record['email'])?$record['email']:'',
		           "cat_av_score" 		=> !empty($record['cat_av_score'])?$record['cat_av_score']:'',
		           "service_plan"		=> $service_plan,
		           "verified" 			=> $verified,
		           "featured_company" 	=> $featured_company,
		           "created_at" 		=> $record['created_at'],
		           "status" 			=> $status,
		           "update_status" 		=> $updateStatus,
		           "action_status" 		=> $action_status,
		        ); 
		    }

	        ## Response
		    $response = array(
		        "draw" => intval($draw),
		        "iTotalRecords" => $totalRecords,
		        "iTotalDisplayRecords" => $totalRecordwithFilter,
		        "aaData" => $data
		    );

    		return $response;
        
        } else {
        	$response = array();
        	$draw = $postData['draw'];
		    $start = $postData['start'];
		    $rowperpage = $postData['length']; // Rows display per page
		    $columnIndex = $postData['order'][0]['column']; // Column index
		    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
		    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		    $searchValue = $postData['search']['value']; // Search value

		    if (!empty($postData['searchByFromMin']) && empty($postData['searchByToMax'])) {
		    	$where = "v.cat_avg_score >= ".$postData['searchByFromMin']." " ;
		    } else if(empty($postData['searchByFromMin']) && !empty($postData['searchByToMax'])) {
		    	$where = "v.cat_avg_score <= ".$postData['searchByFromMin']." ";
		    } else if (!empty($postData['searchByFromMin']) && !empty($postData['searchByToMax'])) {
		    	$where = "v.cat_avg_score BETWEEN ".$postData['searchByFromMin']." AND ".$postData['searchByToMax']." " ;
		    } else {
		    	$where = '';
		    }

	    	## Total number of record with filtering
    		$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%Y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company,v.autogorilla_verified, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_av_score, group_concat(distinct(se_pack.service_plan)) as service_plan",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
			$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
			$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
			$this->db->where(array('v.status !='=>'D','v.otp_varify'=>1));
			if(!empty($where)){
				$this->db->where($where);
			}
			if(!empty($postData['search']['value'])){
				$this->db->group_start();
				$this->db->like('v.email', $postData['search']['value'], 'both');
				$this->db->or_like('v.company_name', $postData['search']['value'], 'both');
				$this->db->or_like('se_pack.service_plan', $postData['search']['value'], 'both');
				$this->db->group_end();
			}
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$query = $this->db->get();
			$records  = $query->result_array();
	        $totalRecordwithFilter = count($records);
	        $totalRecords = count($records);  ## Total number of records without filtering

        	$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%Y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company,v.autogorilla_verified, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_av_score, group_concat(distinct(se_pack.service_plan)) as service_plan",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
			$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
			$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
			$this->db->where(array('v.status !='=>'D','v.otp_varify'=>1));
			if(!empty($where)){
				$this->db->where($where);
			}
			if(!empty($postData['search']['value'])){
				$this->db->group_start();
				$this->db->like('v.email', $postData['search']['value'], 'both');
				$this->db->or_like('v.company_name', $postData['search']['value'], 'both');
				$this->db->or_like('se_pack.service_plan', $postData['search']['value'], 'both');
				$this->db->group_end();
			}
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$this->db->limit($rowperpage, $start);
			$query = $this->db->get();
	        $records  = $query->result_array();
	        $data = array();

		    foreach($records as $record ){
		    	$sql='';
		    	$sql = "UPDATE vendor_info SET cat_avg_score =".$record['cat_av_score']." WHERE id = ?";
				$query = $this->db->query($sql,$record['id']);
		    	$satus = '';$updateStatus = '';$action_status = ''; $verified = ''; $featured_company = '';$service_plan='';

		    	if($record['approved_status'] == 'Not Approved'){
		    		$action_status = '<a class="profileInfo" href="javascript:void(0)" title="Profile" data-toggle="modal" style="color: #766969;" data-id='.$record['id'].' data-email = "'.$record['email'].'" data-password = "'.$record['password'].'"><i class="fa fa-user" style="margin-right: 8px;font-size: 20px;" aria-hidden="true"></i></a><a class="productInfo" href="javascript:void(0)" title="Product List" data-toggle="modal" style="color: #15608d;" data-id='.$record['id'].'><i class="fa fa-product-hunt" style="margin-right: 3px;font-size: 16px;margin-left: 6px;" aria-hidden="true"></i></a><a class="category_info" href="javascript:void(0)" title="Category Info" style="color: #15608d;" data-id='.$record['id'].'><i class="fa fa-list-alt" aria-hidden="true"></i></a>';
		    	} else {
		    		$action_status = '<a class="profileInfo" href="javascript:void(0)" title="Profile" data-toggle="modal" style="color: #766969;"  data-id='.$record['id'].' data-email = "'.$record['email'].'" data-password = "'.$record['password'].'"><i class="fa fa-user" style="margin-right: 8px;font-size: 20px;" aria-hidden="true"></i></a><a class="productInfo" href="javascript:void(0)" title="Product List" data-toggle="modal" style="color: #15608d;" data-id='.$record['id'].'><i class="fa fa-product-hunt" style="margin-right: 3px;font-size: 16px;" aria-hidden="true"></i></a><a class="packageMapping" href="javascript:void(0)" title="Package Mapping" data-toggle="modal" style="color: black;" data-id='.$record['id'].'><i class="fa fa-map" style="margin-right: 3px;font-size: 16px;" aria-hidden="true" ></i></a><a class="category_info" href="javascript:void(0)" title="Category Info" style="color: #15608d;" data-id='.$record['id'].'><i class="fa fa-list-alt" aria-hidden="true"></i></a>';
		    	}


		    	if($record['approved_status'] == 'Approved'){
		    		$status = '<span  style="color: #008000;">Approved</span>';
		    	}else if($record['approved_status'] == 'Reject'){
		    		$status = '<span  style="color: #f90d18;">Rejected</span>';
		    	}else{
		    		$status = '<span  style="color: #1241ab;">Pending</span>';
		    	}


		    	if($record['approved_status'] == 'Approved'){
		    		$updateStatus = '<span class="badge badge-warning rejectStatus cursor-pointer" data-id='.$record['id'].'>Reject</span>';
		    	}else if($record['approved_status'] == 'Reject'){
		    		$updateStatus = '<span class="badge badge-success approveStatus cursor-pointer" data-id='.$record['id'].'  data-email = "'.$record['email'].'">Approve</span>';
		    	}else{
		    		$updateStatus = '<span class="badge badge-success approveStatus cursor-pointer" data-id='.$record['id'].' data-email = "'.$record['email'].'" >Approve</span> <span class="badge badge-warning rejectStatus cursor-pointer" data-id='.$record['id'].'>Reject</span>';
		    	}

		    	if($record['autogorilla_verified']=='Y'){
		    		$verified = '<span  style="color: #008000;">Yes</span>';
		    	}else{
		    		$verified = '<span  style="color: #1241ab;">No</span>';
		    	}

		    	if($record['featured_company']== 0){
		    		$featured_company = '<span class="badge badge-warning change_feature_companys cursor-pointer" style="background-color: #958686e3;" data-id='.$record['id'].'>Feature</span>';
		    	}else{
		    		$featured_company = '<span class="badge badge-warning change_feature_companys cursor-pointer" style="background-color: #0a4c2ed1;" data-id='.$record['id'].'>Featured</span>';
		    	}

		    	if(!empty($record['service_plan'])){
		    		$service_plan = '<span>'.$record['service_plan'].'</span><br><span class="badge badge-warning membership_listing_sup cursor-pointer" style="background-color: #0a4c2ed1;" data-id='.$record['id'].' data-companyname= "'.$record['company_name'].'" >Subcription Details</span>';
		    	}


		        $data[] = array( 
		           "id"				=> !empty($record['id'])?$record['id']:'',
		           "company_name"		=> !empty($record['company_name'])?$record['company_name']:'',
		           "name"				=> !empty($record['name'])?$record['name']:'',
		           "email" 				=> !empty($record['email'])?$record['email']:'',
		           "cat_av_score" 		=> !empty($record['cat_av_score'])?$record['cat_av_score']:'',
		           "service_plan"		=> $service_plan,
		           "verified" 			=> $verified,
		           "featured_company" 	=> $featured_company,
		           "created_at" 		=> $record['created_at'],
		           "status" 			=> $status,
		           "update_status" 		=> $updateStatus,
		           "action_status" 		=> $action_status,
		        ); 
		    }

	        ## Response
		    $response = array(
		        "draw" => intval($draw),
		        "iTotalRecords" => $totalRecords,
		        "iTotalDisplayRecords" => $totalRecordwithFilter,
		        "aaData" => $data
		    );

    		return $response;
        }
	}

	public function getapprovedVendorDetails($limit = 10, $offset = 0, $search='',$searchByFromMin='',$searchByFromMax=''){
			if (!empty($searchByFromMin) && empty($searchByFromMax)) {
		    		$where = "v.cat_avg_score >= ".$searchByFromMin." " ;
			    } else if(empty($searchByFromMin) && !empty($searchByFromMax)) {
			    	$where = "v.cat_avg_score <= ".$searchByFromMin." ";
			    } else if (!empty($searchByFromMin) && !empty($searchByFromMax)) {
			    	$where = "v.cat_avg_score BETWEEN ".$searchByFromMin." AND ".$searchByFromMax." " ;
			    } else {
			    	$where = '';
			    }
			    
			$this->db->select("c_per.company_id");
			$this->db->from('company_permission_tbl c_per');
			$this->db->where(array('c_per.admin_user_id' => $_SESSION['adminsessiondetails']['id'],'c_per.status'=>1));
			$query = $this->db->get();
	        $data  = $query->result_array();

	        $this->db->select("m_pv_tbl.sub_menu,m_pv_tbl.permissionType");
			$this->db->from('menu_previllage_tbl m_pv_tbl');
			$this->db->where(array('m_pv_tbl.parent_menu_id'=>19,'m_pv_tbl.admin_user_id' => $_SESSION['adminsessiondetails']['id'],'m_pv_tbl.status'=>1));
			$query 		= $this->db->get();
		    $sub_data  	= $query->result_array();

	        if($_SESSION['adminsessiondetails']['id'] != 1) { 
				$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company, v.autogorilla_verified, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_avscore,group_concat(distinct(se_pack.service_plan)) as service_plan ",FALSE);
				$this->db->from('vendor_info v');
				$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
				$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
				$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
				
				if(!empty($sub_data)){
					if($sub_data[0]['sub_menu'] == 'sp_1' && !empty($data)){
						$this->db->join('company_permission_tbl c_p','c_p.company_id = v.id','left');
						$this->db->where(array('v.status !='=>'D','v.approved_status' =>1, 'c_p.admin_user_id'=>$_SESSION['adminsessiondetails']['id']));
					}else{
						$this->db->where(array('v.status !='=>'D','v.approved_status' =>1));
					}
				}

				if(!empty($where)){
					$this->db->where($where);
				}

				if (!empty($search)) {
		 			if($search == 'Approved' || $search == 'approved'){
						$this->db->like('v.approved_status', 1, 'both');
		 			}else if($search == 'Not Approved' || $search == 'not approved' || $search == 'not'){
		 				$this->db->like('v.approved_status', 0, 'both');
		 			} else {
		 				$this->db->group_start();
		 				$this->db->like('v.email', $search, 'both');
		 				$this->db->or_like('v.company_name', $search, 'both');
		 				$this->db->or_like('se_pack.service_plan', $search, 'both');
		 				$this->db->group_end();
		 			}
				}
				$this->db->group_by('v.id');
				$this->db->order_by('v.id','DESC');
				$this->db->limit($limit,$offset);
				$query = $this->db->get();
				
		        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		        $data['total_count'] = $countQuery->row()->Count;
		        $data['fetch_count'] = $query->num_rows();
		        $data['fetch_data']  = $query->result_array();
		        foreach ($data['fetch_data'] as $key => $value) {
	        		$data['fetch_data'][$key]['sl_no'] = $key+1;
	        		$sql='';
			    	$sql = "UPDATE vendor_info SET cat_avg_score =".$value['cat_avscore']." WHERE id = ?";
					$query = $this->db->query($sql,$value['id']);
	        	}
		        return $data;
	    	} else {

	    		if (!empty($searchByFromMin) && empty($searchByFromMax)) {
		    		$where = "v.cat_avg_score >= ".$searchByFromMin." " ;
			    } else if(empty($searchByFromMin) && !empty($searchByFromMax)) {
			    	$where = "v.cat_avg_score <= ".$searchByFromMin." ";
			    } else if (!empty($searchByFromMin) && !empty($searchByFromMax)) {
			    	$where = "v.cat_avg_score BETWEEN ".$searchByFromMin." AND ".$searchByFromMax." " ;
			    } else {
			    	$where = '';
			    }

	    		$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company, v.autogorilla_verified,v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_avscore,group_concat(distinct(se_pack.service_plan)) as service_plan ",FALSE);
				$this->db->from('vendor_info v');
				$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
				$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
				$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
				$this->db->where(array('v.status !='=>'D' ,'v.approved_status'=>1));
				if(!empty($where)){
					$this->db->where($where);
				}
				if (!empty($search)) {
		 			if($search == 'Approved' || $search == 'approved'){
						$this->db->like('v.approved_status', 1, 'both');
		 			}else if($search == 'Not Approved' || $search == 'not approved' || $search == 'not'){
		 				$this->db->like('v.approved_status', 0, 'both');
		 			} else {
		 				$this->db->group_start();
		 				$this->db->like('v.email', $search, 'both');
		 				$this->db->or_like('v.company_name', $search, 'both');
		 				$this->db->or_like('se_pack.service_plan', $search, 'both');
		 				$this->db->group_end();
		 			}
				}
				$this->db->group_by('v.id');
				$this->db->order_by('v.id','DESC');
				$this->db->limit($limit,$offset);
				$query = $this->db->get();
				
		        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		        $data['total_count'] = $countQuery->row()->Count;
		        $data['fetch_count'] = $query->num_rows();
		        $data['fetch_data']  = $query->result_array();
		        foreach ($data['fetch_data'] as $key => $value) {
	        		$data['fetch_data'][$key]['sl_no'] = $key+1;
	        		$sql='';
			    	$sql = "UPDATE vendor_info SET cat_avg_score =".$value['cat_avscore']." WHERE id = ?";
					$query = $this->db->query($sql,$value['id']);
	        	}
		        return $data;
	    	}
	
	}
	
	public function getRejectVendorDetails($limit = 10, $offset = 0, $search=''){
		$this->db->select("c_per.company_id");
		$this->db->from('company_permission_tbl c_per');
		$this->db->where(array('c_per.admin_user_id' => $_SESSION['adminsessiondetails']['id'],'c_per.status'=>1));
		$query = $this->db->get();
        $data  = $query->result_array();

        $this->db->select("m_pv_tbl.sub_menu,m_pv_tbl.permissionType");
		$this->db->from('menu_previllage_tbl m_pv_tbl');
		$this->db->where(array('m_pv_tbl.parent_menu_id'=>19,'m_pv_tbl.admin_user_id' => $_SESSION['adminsessiondetails']['id'],'m_pv_tbl.status'=>1));
		$query 		= $this->db->get();
		$sub_data  	= $query->result_array();

		if($_SESSION['adminsessiondetails']['id'] != 1){
			$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`, v.featured_company, v.id,IF(v.approved_status = '1','Approved','Not Approved') AS `approved_status`,v.password,v.decript_password,v.autogorilla_verified",FALSE);
			$this->db->from('vendor_info v');

			if(!empty($sub_data)){
				if($sub_data[0]['sub_menu'] == 'sp_1' && !empty($data)){
					$this->db->join('company_permission_tbl c_p','c_p.company_id = v.id','left');
					$this->db->where(array('v.status !='=>'D','v.approved_status'=>2, 'c_p.admin_user_id'=>$_SESSION['adminsessiondetails']['id']));
				}else{
					$this->db->where(array('v.status !='=>'D','v.approved_status'=>2));
				}
			}
			if (!empty($search)) {
				$this->db->group_start();
	 			$this->db->like('v.email', $search, 'both');
	 			$this->db->or_like('v.company_name', $search, 'both');
	 			$this->db->group_end();
			}
			$this->db->order_by('v.id','DESC');
			$this->db->limit($limit,$offset);
			$query = $this->db->get();
		
	        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
	        $data['total_count'] = $countQuery->row()->Count;
	        $data['fetch_count'] = $query->num_rows();
	        $data['fetch_data']  = $query->result_array();
	        foreach ($data['fetch_data'] as $key => $value) {
	        	$data['fetch_data'][$key]['sl_no'] = $key+1;
	        }
	        return $data;
	    } else {
	    	$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%y %h:%i:%s %p') as created_at,v.autogorilla_verified, IF(v.status = 'A','Active','Inactive') AS `status`, v.featured_company, v.id,IF(v.approved_status = '1','Approved','Not Approved') AS `approved_status`,v.password,v.decript_password",FALSE);
			$this->db->from('vendor_info v');
			$this->db->where(array('v.status !='=>'D' ,'v.approved_status'=>2));
			if (!empty($search)) {
				$this->db->group_start();
	 			$this->db->like('v.email', $search, 'both');
	 			$this->db->or_like('v.company_name', $search, 'both');
	 			$this->db->group_end();
			}
			$this->db->order_by('v.id','DESC');
			$this->db->limit($limit,$offset);
			$query = $this->db->get();
		
	        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
	        $data['total_count'] = $countQuery->row()->Count;
	        $data['fetch_count'] = $query->num_rows();
	        $data['fetch_data']  = $query->result_array();
	        foreach ($data['fetch_data'] as $key => $value) {
	        	$data['fetch_data'][$key]['sl_no'] = $key+1;
	        }
	        return $data;
	    }
	}

	public function changeStatus($data) {
		$sql = "UPDATE vendor_info SET status = ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['vendor_id'])));
		return $query;
	}

	public function exportPendingVendorDetails()
    {
    	$this->db->select("v.id,v.company_name,v.name,v.created_at");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.status !='=>'D' ,'v.approved_status'=>0 , 'v.otp_varify'=>1 ));
		$query = $this->db->get();
		$data  = $query->result_array();
        return $data;
    }

 	public function exportRejectedVendorDetails(){
    	$this->db->select("v.id,v.company_name,v.name,v.created_at");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.status !='=>'D','v.approved_status'=>2));
		$query = $this->db->get();
		$data  = $query->result_array();
        return $data;
    }

	public function isActiveMailId($email){
		$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.email' => $email,'v.status'=>'A','v.approved_status' => 1));
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function changeApprovedStatus($data){
		$sql = "UPDATE vendor_info SET approved_status = ( CASE WHEN APPROVED_STATUS = '1' THEN '0' ELSE '1' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['vendor_id'])));
		return $query;
	}

	public function deleteVendor($data){
		$sql = "UPDATE vendor_info SET status ='D' WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['vendor_id'])));
		return $query;
	}

	public function rejectStatus($data){
		$sql = "UPDATE vendor_info SET approved_status = 2 WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['vendor_id'])));
		return $query;
	}

	public function getpendingVendorDetails($limit = 10, $offset = 0, $search=''){
		$this->db->select("c_per.company_id");
		$this->db->from('company_permission_tbl c_per');
		$this->db->where(array('c_per.admin_user_id' => $_SESSION['adminsessiondetails']['id'],'c_per.status'=>1));
		$query = $this->db->get();
        $data  = $query->result_array();

        $this->db->select("m_pv_tbl.sub_menu,m_pv_tbl.permissionType");
		$this->db->from('menu_previllage_tbl m_pv_tbl');
		$this->db->where(array('m_pv_tbl.parent_menu_id'=>19,'m_pv_tbl.admin_user_id' => $_SESSION['adminsessiondetails']['id'],'m_pv_tbl.status'=>1));
		$query 		= $this->db->get();
		$sub_data  	= $query->result_array();


        if($_SESSION['adminsessiondetails']['id'] != 1){
			$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_avscore,group_concat(distinct(se_pack.service_plan)) as service_plan ",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
			$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
			$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');

			if(!empty($sub_data)){
				if($sub_data[0]['sub_menu'] == 'sp_1' && !empty($data)){
					$this->db->join('company_permission_tbl c_p','c_p.company_id = v.id','left');
					$this->db->where(array('v.status !='=>'D','v.approved_status'=>0, 'v.otp_varify'=>1 ,'c_p.admin_user_id'=>$_SESSION['adminsessiondetails']['id']));
				} else {
					$this->db->where(array('v.status !='=>'D','v.approved_status'=>0, 'v.otp_varify'=>1));
				}
			}

			if (!empty($search)) {
	 			if($search == 'Approved' || $search == 'approved'){
					$this->db->like('v.approved_status', 1, 'both');
	 			}else if($search == 'Not Approved' || $search == 'not approved' || $search == 'not'){
	 				$this->db->like('v.approved_status', 0, 'both');
	 			} else {
	 				$this->db->group_start();
	 				$this->db->like('v.email', $search, 'both');
	 				$this->db->or_like('v.company_name', $search, 'both');
	 				$this->db->group_end();
	 			}
			}
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$this->db->limit($limit,$offset);
			$query = $this->db->get();
		
	        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
	        $data['total_count'] = $countQuery->row()->Count;
	        $data['fetch_count'] = $query->num_rows();
	        $data['fetch_data']  = $query->result_array();
	        foreach ($data['fetch_data'] as $key => $value) {
	        	$data['fetch_data'][$key]['sl_no'] = $key+1;
	        }
	        return $data;
	    } else {
	    	$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_avscore,group_concat(distinct(se_pack.service_plan)) as service_plan ",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
			$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
			$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
			$this->db->where(array('v.status !='=>'D' ,'v.approved_status'=>0 , 'v.otp_varify'=>1 ));
			if (!empty($search)) {
	 			
	 			if($search == 'Approved' || $search == 'approved'){
					$this->db->like('v.approved_status', 1, 'both');
	 			}else if($search == 'Not Approved' || $search == 'not approved' || $search == 'not'){
	 				$this->db->like('v.approved_status', 0, 'both');
	 			} else {
	 				$this->db->group_start();
	 				$this->db->like('v.email', $search, 'both');
	 				$this->db->or_like('v.company_name', $search, 'both');
	 				$this->db->group_end();
	 			}
			}
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$this->db->limit($limit,$offset);
			$query = $this->db->get();
		
	        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
	        $data['total_count'] = $countQuery->row()->Count;
	        $data['fetch_count'] = $query->num_rows();
	        $data['fetch_data']  = $query->result_array();
	        foreach ($data['fetch_data'] as $key => $value) {
	        	$data['fetch_data'][$key]['sl_no'] = $key+1;
	        }
	        return $data;
	    }
	}
	
	public function profileCheckBy($data){
		$profile_check = $data['profile_check'];
		$sql = "UPDATE vendor_info SET profile_check_by = '".$profile_check."' WHERE id = ?";
		$query = $this->db->query($sql, array($data['id']));
		return $query;
	}

	public function changefeatureStatus($data){
		$sql = "UPDATE vendor_info SET featured_company = ( CASE WHEN featured_company = '0' THEN 1 ELSE 0 END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['vendor_id'])));
		return $query;
	}

	public function fetchVendorName(){
		$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.id",FALSE);
		$this->db->from('vendor_info v');
		$this->db->where(array('v.status'=>'A'));
		$this->db->group_by('v.id');
		$this->db->order_by('v.name','ASC');
		$query = $this->db->get();
		$data  = $query->result_array();
        return $data;
	}

	public function uploadData($table, $post_data=array()){
        $this->db->insert_batch($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
    }

    public function getRejectedCompanyReport($limit = 10, $offset = 0, $search='',$uniq_code=''){
    	$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, v.phone ,v.rejected_reason, v.featured_company,v.csv_row_no , v.decript_password",FALSE);
		$this->db->from('vendor_rejeted_datalog v');
		$this->db->where(array('v.uniq_code'=>base64_decode($uniq_code)));
		if (!empty($search)) {
 			$this->db->group_start();
 			$this->db->like('v.email', $search, 'both');
 			$this->db->or_like('v.company_name', $search, 'both');
 			$this->db->or_like('v.phone', $search, 'both');
 			$this->db->group_end();
 			
		}
		$this->db->group_by('v.id');
		$this->db->order_by('v.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
	
        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
    }


    public function exportVendorDetails()
    {
    	$this->db->select("v.id,v.company_name,v.name,v.gst");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.status'=>'A'));
		$query = $this->db->get();
		$data  = $query->result_array();
        return $data;
    }

    public function getSuccessCsvInfo($uniq_code){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.uniq_code'=>base64_decode($uniq_code)));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    }
    public function getFailedCsvInfo($uniq_code){
    	$this->db->select("v.id");
		$this->db->from('vendor_rejeted_datalog v');
		$this->db->where(array('v.uniq_code'=>base64_decode($uniq_code)));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    } 
    public function checkIsEmailExist($email){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.email'=>$email,'v.approved_status'=>1));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    }
    public function checkIsPhoneExist($phone){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.phone'=>$phone,'v.approved_status'=>1));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    }
    public function checkIsCatelogUrlExist($catlogeUrl){
    	$this->db->select("v.id,v.vendor_catalog_url_slug");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.vendor_catalog_url_slug' => $catlogeUrl,'v.approved_status'=>1));
		$query = $this->db->get();
		$numrows  = $query->num_rows();
		$res  = $query->result_array();
        return $res;
    } 
    public function checkIsGSTExist($gst){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.gst' => $gst,'v.approved_status'=>1));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    } 
    public function checkIsTANExist($tan){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.tan' => $tan,'v.approved_status'=>1));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    }
    public function checkIsPANExist($pan){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.pan' => $pan,'v.approved_status'=>1));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    }

    public function rejectedAllPendingSupplier(){
    	$sql = "UPDATE vendor_info SET approved_status = 2 WHERE approved_status = ?";
		$query = $this->db->query($sql, 0);
		return $query;
    }  

    public function rejectedAllApprovedSupplier(){
    	$sql = "UPDATE vendor_info SET approved_status = 2 WHERE approved_status = ?";
		$query = $this->db->query($sql, 1);
		return $query;
    } 

    public function approvedAllPendingSupplier(){
    	$sql = "UPDATE vendor_info SET approved_status = 1 WHERE approved_status = ?";
		$query = $this->db->query($sql, 0);
		return $query;
    } 

    public function approvedAllRejectedSupplier(){
    	$sql = "UPDATE vendor_info SET approved_status = 1 WHERE approved_status = ?";
		$query = $this->db->query($sql, 2);
		return $query;
    }

    public function existPendingData(){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.approved_status' => 0));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    }

    public function existRejectData(){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.approved_status' => 2));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    }
    public function existApproveData(){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.approved_status' => 1));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    }

    public function allApprovedSupplierInfo(){
    	$sql = "UPDATE vendor_info SET approved_status = 1 WHERE status = ?";
		$query = $this->db->query($sql, 'A');
		return $query;
    }
    public function rejectAllSupplierInfo(){
    	$sql = "UPDATE vendor_info SET approved_status = 2 WHERE status = ?";
    	$query = $this->db->query($sql, 'A');
		return $query;
    }

    public function fetchSubscriptionListById($data){
    	$this->db->select("v_service_p.id,v_service_p.service_package_id, date_format(v_service_p.package_start_date,'%d-%m-%Y') as package_start_date,v_service_p.vendor_id,date_format(v_service_p.package_expiry_date,'%d-%m-%Y') as package_expiry_date, s_p.service_plan,v_service_p.total_date_calculate,v_service_p.total_calculate_days");
		$this->db->from('vendor_service_package_list v_service_p');
		$this->db->join('service_package s_p','s_p.id = v_service_p.service_package_id','left');
		$this->db->where(array('v_service_p.vendor_id'=>base64_decode($data['vendor_id']),'v_service_p.status' => 'A','s_p.status'=>'A'));
		$this->db->group_by('v_service_p.service_package_id');
		$query = $this->db->get();
		$data  = $query->num_rows();
		$res   = $query->result_array();
        return $res;
    }

    public function getSupplierCategoryInfo($postData){
    	
    	$response = array();
    	$draw = $postData['draw'];
	    $start = $postData['start'];
	    $rowperpage = $postData['length']; // Rows display per page
	    $columnIndex = $postData['order'][0]['column']; // Column index
	    $columnName = $postData['columns'][$columnIndex]['data']; // Column name
	    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
	    $searchValue = $postData['search']['value']; // Search value
	    
    	## Total number of record with filtering
		$this->db->select("SQL_CALC_FOUND_ROWS v_c.category_name, v_c.cat_image_1, IF(v_c.status = 'A','Active','Inactive') AS `status`",FALSE);
		$this->db->from('vendor_category v_c');
		$this->db->where(array('v_c.supplier_login_id'=>base64_decode($postData['vendor_id']),'v_c.status'=>'A'));
		
		if(!empty($postData['search']['value'])){
			$this->db->like('v_c.category_name', $postData['search']['value'], 'both');
		}
		$query = $this->db->get();
		$records  = $query->result_array();
        $totalRecordwithFilter = count($records);
        $totalRecords = count($records);  ## Total number of records without filtering

    	$this->db->select("SQL_CALC_FOUND_ROWS v_c.id, v_c.category_name, v_c.cat_image_1, IF(v_c.status = 'A','Active','Inactive') AS `status`",FALSE);
		$this->db->from('vendor_category v_c');
		$this->db->where(array('v_c.supplier_login_id'=>base64_decode($postData['vendor_id']),'v_c.status'=>'A'));
		
		if(!empty($postData['search']['value'])){
			$this->db->like('v_c.category_name', $postData['search']['value'], 'both');
		}
		$query = $this->db->get();
		$records  = $query->result_array();

        $data = array();

	    foreach($records as $record ){
	    	$satus = '';$cat_image_1 = '';
	    	if($record['status'] == 'Active'){
	    		$satus = '<span style="color:green">Active</span>';
	    	}else{
	    		$satus = '<span style="color:red">Inactive</span>';
	    	}
	    	if(!empty($record['cat_image_1'])){
				$cat_image_1 = '<img src='.VIEW_IMAGE_URL.'supplier_categories/'.$record['cat_image_1'].' style="width: 60px; height: 60px;">';
			} else {
				$cat_image_1 = '<img src='.VIEW_IMAGE_URL.'noimage.png'.' style="width: 60px; height: 60px;">';
			}
	    

	        $data[] = array( 
	        	"id"				=> !empty($record['id'])?$record['id']:'',
	           "category_name"		=> !empty($record['category_name'])?$record['category_name']:'',
	           "category_image"		=> $cat_image_1,
	           "status" 			=> $satus,
	       );
	    }

        ## Response
	    $response = array(
	        "draw" => intval($draw),
	        "iTotalRecords" => $totalRecords,
	        "iTotalDisplayRecords" => $totalRecordwithFilter,
	        "aaData" => $data
	    );

		return $response;
    }

    public function exportVendorCategoryDetails($vendor_id){
    	$this->db->select("v_c.id,v.company_name,v_c.category_name");
		$this->db->from('vendor_category v_c');
		$this->db->join('vendor_info v','v.id = v_c.supplier_login_id','left');
		$this->db->where(array('v_c.status'=>'A','v_c.supplier_login_id'=>base64_decode($vendor_id)));
		$query = $this->db->get();
		$data  = $query->result_array();
        return $data;
    }

    public function getLastCsvRecord($uniq_code){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.status'=>'A','v.uniq_code'=>$uniq_code));
		$query = $this->db->get();
		$data  = $query->result_array();
        return $data;
    }

    public function exportApprovedVendorDetails()
    {
    	$this->db->select("v.id,v.company_name,v.email,v.name,v.gst,v.created_at");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.status'=>'A','v.approved_status'=>1));
		$query = $this->db->get();
		$data  = $query->result_array();
        return $data;
    }

    public function exportfailedCompanyData($uniq_code){
    	$this->db->select("v.csv_row_no,v.company_name,v.email,v.phone,v.created_at,v.rejected_reason");
		$this->db->from('vendor_rejeted_datalog v');
		$this->db->where(array('v.uniq_code'=>base64_decode($uniq_code)));
		$query = $this->db->get();
		$data  = $query->result_array();
        return $data;
    }

    public function exportSuccessCompanyData($uniq_code){
    	$this->db->select("v.id,v.company_name,v.name,v.email,v.phone,v.created_at");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.uniq_code'=>base64_decode($uniq_code)));
		$query = $this->db->get();
		$data  = $query->result_array();
        return $data;
    }

    public function updateCateLogurl($data,$id){
    	$sql = "UPDATE vendor_info SET `vendor_catalog_url_slug` = '".$data['vendor_catalog_url_slug']."' WHERE `id` = ".$id." ";
		$query = $this->db->query($sql);
		return $query;
    }

    public function fetchActiveCompanyUrl(){
    	$this->db->select("v.id,v.vendor_catalog_url_slug");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.approved_status'=>1,'v.status'=>'A'));
		$query = $this->db->get();
		$data  = $query->result();
        return $data;
    }

    public function countActiveSuppliers(){
    	$this->db->select("v.id");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.approved_status'=>1,'v.status'=>'A'));
		$query = $this->db->get();
		$data  = $query->num_rows();
        return $data;
    }

    public function fetchDuplicateSuppliersRecords($limit = 10, $offset = 0, $search=''){
    	$subquery = $this->db->select('id, email, `type` ,`status`,`approved_status`, COUNT(*) as email_count')->from('vendor_info')->where(array('status'=>'A','approved_status'=>1,'type'=>2))->group_by('email')->having('email_count > 1', null, false)->get_compiled_select();
		$this->db->select('SQL_CALC_FOUND_ROWS a.id, a.email, a.name, a.phone, a.company_name,a.status, a.approved_status, a.created_at',FALSE);
		$this->db->from('vendor_info a');
		$this->db->join("($subquery) b", "a.email = b.email AND b.email !='' ", 'inner');
		$this->db->where(array('a.status'=>'A','a.approved_status'=>1,'a.type'=>2));
		if (!empty($search)) {
 			$this->db->group_start();
 			$this->db->like('a.email', $search, 'both');
 			$this->db->or_like('a.company_name', $search, 'both');
 			$this->db->or_like('a.phone', $search, 'both');
 			$this->db->group_end();
		}

		$this->db->order_by('a.email','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
       	return $data;

    }

    public function hardDeleteSupplier($data){
    	$this->db->where('id', base64_decode($data['vendor_id']));
    	$delete_vendor= $this->db->delete('vendor_info');

    	if($delete_vendor){
    		$this->db->where('supplier_login_id', base64_decode($data['vendor_id']));
    	    $this->db->delete('vendor_category');

    	    $this->db->where('vendor_id', base64_decode($data['vendor_id']));
    	    $this->db->delete('vendor_product_mapping');

    	    $this->db->where('vendor_id', base64_decode($data['vendor_id']));
    	    $this->db->delete('vendor_product_mapping');

    	    $this->db->where('vendor_id', base64_decode($data['vendor_id']));
    	    $this->db->delete('vendor_product_score_table');

    	    $this->db->where('vendor_id', base64_decode($data['vendor_id']));
    	    $this->db->delete('vendor_bank_details');

    	    $this->db->where('vendor_id', base64_decode($data['vendor_id']));
    	    $this->db->delete('product'); 

    	    $this->db->where('vendor_id', base64_decode($data['vendor_id']));
    	    $this->db->delete('vendor_cms');
    	}
    }
}

/* End of file SupplierModel.php */
/* Location: ./application/models/SupplierModel.php */