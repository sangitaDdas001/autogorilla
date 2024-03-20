<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {
	public function fetchMenuDetails(){
		$parent_sql = "Select parent_id,menu_name,menu_icon,menu_url,segment,id from menu_master where parent_id = 0 AND status=1 AND level = 1 ";
		$query      			= $this->db->query($parent_sql);
		$parent_numrows 		= $query->num_rows();
		$parent_menu 			= $query->result_array();
		$finalArr = [];
		if(!empty($parent_menu)){
			foreach ($parent_menu as $key => $parent_value) {	
				$subsql = "Select parent_id,menu_name,menu_icon,menu_url,segment,id from menu_master where parent_id = ".$parent_value['id']." AND status=1 AND level = 2 ";
				$query      		= $this->db->query($subsql);
				$sub_numrows 		= $query->num_rows();
				$submenu_result 	= $query->result_array();
				array_push($finalArr, $parent_value);
				$finalArr[$key]['submenu'][$parent_value['id']] = $submenu_result;
				
				if($sub_numrows > 0){
					foreach ($submenu_result as $mkey => $sub_value) {
						$micro_sql = "Select parent_id,menu_name,menu_icon,menu_url,segment,id from menu_master where submenu_id = ".$sub_value['id']." AND status=1 AND level = 3 ";
						$query      		= $this->db->query($micro_sql);
						$micro_numrows 		= $query->num_rows();
						$micromenu_result 	= $query->result_array();
						if ($micro_numrows > 0) {
							//$finalArr[$parent_value['id']]['submenu'][$mkey][$sub_value['id']]['micro_menu'] = $micromenu_result;
							$finalArr[$key]['submenu'][$parent_value['id']][$mkey]['micro_menu'][$sub_value['id']] = $micromenu_result;
						}
					}
				}
			}
		}
		
		return $finalArr;
	}

	/**
	 * Insert record based on table name and post data array. 
	*/

	public function insert($table, $post_data=array()){
		$this->db->insert($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}

	public function getAllUserDetails($limit = 10, $offset = 0, $search=''){
		
		$this->db->select("SQL_CALC_FOUND_ROWS id, department_id, name, email, mobile, date_format(created_at,'%d-%m-%y') as created_at, IF(status = 'A','Active','Inactive') AS `status` ",FALSE);
		$this->db->from('auto_admin');
		$this->db->where(array('status !='=>'D','id !='=>1));
		
		if (!empty($search)) {
			$this->db->group_start();
 			$this->db->like('email', $search, 'both');
 			$this->db->or_like('name', $search, 'both');
 			$this->db->group_end();
		}
		$this->db->order_by('id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        if(!empty($data)){
        	foreach ($data['fetch_data']  as $key => $userval) {
        		$department_id			= !empty($userval['department_id']) ? explode(',', $userval['department_id']) : '';
        		$elements 				= []; 
        		$implode_menuName 		= '';
        		$assigned_menu 			= ''; 
        		$submenu_element    	= ''; 
        		$assigned_submenu_menu 	= '';
        		$sub_elements 			= [];
        		$departments 		    = [];

        		if(!empty($department_id)){
	        		foreach ($department_id as $rkey => $d_value) {
	        			$this->db->select('department_name,id');
						$this->db->from('department');
						$this->db->where(array('status'=>1,'id'=>$d_value));
						$query = $this->db->get();
						$data['fetch_department_name'] = $query->result_array();
						if(!empty($data['fetch_department_name'])){
							$departments[] = $data['fetch_department_name'][0]['department_name'];
						}
	        		}
	        		$implodeDepartment =  implode(', ',$departments);
	        		$data['fetch_data'][$key]['depatment_name'] = $implodeDepartment;
	        	}else{
	        		$data['fetch_data'][$key]['depatment_name'] = '';
	        	}

        		$this->db->select("pv_tbl.id, pv_tbl.admin_user_id, pv_tbl.parent_menu_id, pv_tbl.sub_menu,menu_tbl.menu_name");
				$this->db->from('menu_previllage_tbl pv_tbl');
				$this->db->join('menu_master menu_tbl','menu_tbl.id = pv_tbl.parent_menu_id','left');
				$this->db->where(array('pv_tbl.status'=>1,'pv_tbl.admin_user_id' =>$userval['id']));
				$query 		= $this->db->get();
		        $data['fetch_data'][$key]['fetch_menu_data']  = $query->result_array();

		        $this->db->select("c_per.company_id");
				$this->db->from('company_permission_tbl c_per');
				$this->db->where(array('c_per.admin_user_id' => $userval['id'],'c_per.status'=>1));
				$query = $this->db->get();
		        $data['fetch_data'][$key]['numberOfCompany']  = $query->num_rows();

		        $this->db->select("p_u.product_id");
				$this->db->from('product_assign_user p_u');
				$this->db->join('product p', 'p.product_id = p_u.product_id', 'left');
				$this->db->join('vendor_info v', 'v.id = p.vendor_id', 'inner');
				$this->db->where(array('p_u.user_id' => $userval['id'],'p_u.status'=>1, 'p.status' => 1, 'p.approved_status' => '1','v.status'=>'A','v.approved_status'=>1));
				$query_cou = $this->db->get();
				 $data['fetch_data'][$key]['product_count'] = $query_cou->num_rows();


	        	if(!empty($data['fetch_data'][$key]['fetch_menu_data'])){
		        	foreach($data['fetch_data'][$key]['fetch_menu_data'] as $key1=> $menuval){
		        		if(!empty($menuval['sub_menu']) && $menuval['sub_menu']=='sp_1'){
		        			$elements[] = $menuval['menu_name'].' '.'(Specific Supplier)';
		        		}else if(!empty($menuval['sub_menu']) && $menuval['sub_menu']=='all_1'){
		        			$elements[] = $menuval['menu_name'].' '.'(All Supplier)';
		        		}else{
		        			$elements[] = $menuval['menu_name'];
		        		}

		        		if(!empty($menuval['sub_menu'])){
		        			$sub_elements['submenu_element'] = $menuval['sub_menu'];
		        		}

		        	}
		        	
		        	$implode_menuName = implode(', ',$elements);
		       		$data['fetch_data'][$key]['assigned_menu'] = !empty($implode_menuName)?$implode_menuName:'';
		       		$data['fetch_data'][$key]['assigned_submenu_menu'] = !empty($sub_elements['submenu_element'])?$sub_elements['submenu_element']:'';
		       	}else{
		       		$data['fetch_data'][$key]['assigned_menu'] = [];
		       	}
        	}
        }
       
       return $data;
	}

	public function fetchAllUser(){
		$this->db->select("SQL_CALC_FOUND_ROWS id, name ",FALSE);
		$this->db->from('auto_admin');
		$this->db->where(array('status'=>'A','id !='=>1));
		$this->db->order_by('id','DESC');
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function getInactiveUserDetails($limit = 10, $offset = 0, $search=''){
		$this->db->select("SQL_CALC_FOUND_ROWS id, department_id, name, email, mobile, date_format(created_at,'%d-%m-%y') as created_at, IF(status = 'A','Active','Inactive') AS `status` ",FALSE);
		$this->db->from('auto_admin');
		$this->db->where(array('status'=>'I','id !='=>1));
		if (!empty($search)) {
			$this->db->group_start();
 			$this->db->like('email', $search, 'both');
 			$this->db->or_like('name', $search, 'both');
 			$this->db->group_end();
		}
		$this->db->order_by('id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
         if(!empty($data)){
        	foreach ($data['fetch_data']  as $key => $userval) {
        		$elements 				= []; 
        		$implode_menuName 		= '';
        		$assigned_menu 			= ''; 
        		$submenu_element    	= ''; 
        		$assigned_submenu_menu 	= '';
        		$sub_elements 			= [];
        		$department_id			= !empty($userval['department_id']) ? explode(',', $userval['department_id']) : '';
        		$departments 		    = [];

        		if(!empty($department_id)){
	        		foreach ($department_id as $rkey => $d_value) {
	        			$this->db->select('department_name,id');
						$this->db->from('department');
						$this->db->where(array('status'=>1,'id'=>$d_value));
						$query = $this->db->get();
						$data['fetch_department_name'] = $query->result_array();
						if(!empty($data['fetch_department_name'])){
							$departments[] = $data['fetch_department_name'][0]['department_name'];
						}
	        		}
	        		$implodeDepartment =  implode(', ',$departments);
	        		$data['fetch_data'][$key]['depatment_name'] = $implodeDepartment;
	        	}else{
	        		$data['fetch_data'][$key]['depatment_name'] = '';
	        	}

        		$this->db->select("pv_tbl.id, pv_tbl.admin_user_id, pv_tbl.parent_menu_id, pv_tbl.sub_menu,menu_tbl.menu_name");
				$this->db->from('menu_previllage_tbl pv_tbl');
				$this->db->join('menu_master menu_tbl','menu_tbl.id = pv_tbl.parent_menu_id','left');
				$this->db->where(array('pv_tbl.status'=>1,'pv_tbl.admin_user_id' =>$userval['id']));
				$query 		= $this->db->get();
		        $data['fetch_data'][$key]['fetch_menu_data']  = $query->result_array();

		        $this->db->select("c_per.company_id");
				$this->db->from('company_permission_tbl c_per');
				$this->db->where(array('c_per.admin_user_id' => $userval['id']));
				$query = $this->db->get();
		        $data['fetch_data'][$key]['numberOfCompany']  = $query->num_rows();

		        $this->db->select("p_u.product_id");
				$this->db->from('product_assign_user p_u');
				$this->db->join('product p', 'p.product_id = p_u.product_id', 'left');
				$this->db->join('vendor_info v', 'v.id = p.vendor_id', 'inner');
				$this->db->where(array('p_u.user_id' => $userval['id'],'p_u.status'=>1, 'p.status' => 1, 'p.approved_status' => '1','v.status'=>'A','v.approved_status'=>1));
				$query_cou = $this->db->get();
				 $data['fetch_data'][$key]['product_count'] = $query_cou->num_rows();

	        	if(!empty($data['fetch_data'][$key]['fetch_menu_data'])){
		        	foreach($data['fetch_data'][$key]['fetch_menu_data'] as $key1=> $menuval){
		        		if(!empty($menuval['sub_menu']) && $menuval['sub_menu']=='sp_1'){
		        			$elements[] = $menuval['menu_name'].' '.'(Specific Supplier)';
		        		}else if(!empty($menuval['sub_menu']) && $menuval['sub_menu']=='all_1'){
		        			$elements[] = $menuval['menu_name'].' '.'(All Supplier)';
		        		}else{
		        			$elements[] = $menuval['menu_name'];
		        		}

		        		if(!empty($menuval['sub_menu'])){
		        			$sub_elements['submenu_element'] = $menuval['sub_menu'];
		        		}

		        	}
		        	
		        	$implode_menuName = implode(', ',$elements);
		       		$data['fetch_data'][$key]['assigned_menu'] = !empty($implode_menuName)?$implode_menuName:'';
		       		$data['fetch_data'][$key]['assigned_submenu_menu'] = !empty($sub_elements['submenu_element'])?$sub_elements['submenu_element']:'';
		       	}else{
		       		$data['fetch_data'][$key]['assigned_menu'] = [];
		       	}
        	}
        }
        return $data;
	}

	public function getActiveUserDetails($limit = 10, $offset = 0, $search=''){
		$dep_array = ['9','11'];
		$this->db->select("SQL_CALC_FOUND_ROWS id, department_id, name, email, mobile, date_format(created_at,'%d-%m-%y') as created_at, IF(status = 'A','Active','Inactive') AS `status` ",FALSE);
		$this->db->from('auto_admin');
		$this->db->where(array('status'=>'A','id !='=>1));
		$this->db->where_not_in("department_id",$dep_array);
		if (!empty($search)) {
			$this->db->group_start();
 			$this->db->like('email', $search, 'both');
 			$this->db->or_like('name', $search, 'both');
 			$this->db->group_end();
		}
		$this->db->order_by('id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        if(!empty($data)){
        	foreach ($data['fetch_data']  as $key => $userval) {
        		$elements 				= []; 
        		$implode_menuName 		= '';
        		$assigned_menu 			= ''; 
        		$submenu_element    	= ''; 
        		$assigned_submenu_menu 	= '';
        		$sub_elements 			= [];
        		$department_id			= !empty($userval['department_id']) ? explode(',', $userval['department_id']) : '';
        		$departments 		    = [];

        		if(!empty($department_id)){
	        		foreach ($department_id as $rkey => $d_value) {
	        			$this->db->select('department_name,id');
						$this->db->from('department');
						$this->db->where(array('status'=>1,'id'=>$d_value));
						$query = $this->db->get();
						$data['fetch_department_name'] = $query->result_array();
						if(!empty($data['fetch_department_name'])){
							$departments[] = $data['fetch_department_name'][0]['department_name'];
						}
	        		}
	        		$implodeDepartment =  implode(', ',$departments);
	        		$data['fetch_data'][$key]['depatment_name'] = $implodeDepartment;
	        	}else{
	        		$data['fetch_data'][$key]['depatment_name'] = '';
	        	}

        		$this->db->select("c_per.company_id");
				$this->db->from('company_permission_tbl c_per');
				$this->db->where(array('c_per.admin_user_id' => $userval['id']));
				$query = $this->db->get();
		        $data['fetch_data'][$key]['numberOfCompany']  = $query->num_rows();
		       

        		$this->db->select("pv_tbl.id, pv_tbl.admin_user_id, pv_tbl.parent_menu_id, pv_tbl.sub_menu,menu_tbl.menu_name");
				$this->db->from('menu_previllage_tbl pv_tbl');
				$this->db->join('menu_master menu_tbl','menu_tbl.id = pv_tbl.parent_menu_id','left');
				$this->db->where(array('pv_tbl.status'=>1,'pv_tbl.admin_user_id' =>$userval['id']));
				$query 		= $this->db->get();
		        $data['fetch_data'][$key]['fetch_menu_data']  = $query->result_array();

		        $this->db->select("p_u.product_id");
				$this->db->from('product_assign_user p_u');
				$this->db->join('product p', 'p.product_id = p_u.product_id', 'left');
				$this->db->join('vendor_info v', 'v.id = p.vendor_id', 'inner');
				$this->db->where(array('p_u.user_id' => $userval['id'],'p_u.status'=>1, 'p.status' => 1, 'p.approved_status' => '1','v.status'=>'A','v.approved_status'=>1));
				$query_cou = $this->db->get();
				 $data['fetch_data'][$key]['product_count'] = $query_cou->num_rows();

	        	if(!empty($data['fetch_data'][$key]['fetch_menu_data'])){
		        	foreach($data['fetch_data'][$key]['fetch_menu_data'] as $key1=> $menuval){
		        		if(!empty($menuval['sub_menu']) && $menuval['sub_menu']=='sp_1'){
		        			$elements[] = $menuval['menu_name'].' '.'(Specific Supplier)';
		        		}else if(!empty($menuval['sub_menu']) && $menuval['sub_menu']=='all_1'){
		        			$elements[] = $menuval['menu_name'].' '.'(All Supplier)';
		        		}else{
		        			$elements[] = $menuval['menu_name'];
		        		}

		        		if(!empty($menuval['sub_menu'])){
		        			$sub_elements['submenu_element'] = $menuval['sub_menu'];
		        		}

		        	}
		        	
		        	$implode_menuName = implode(', ',$elements);
		       		$data['fetch_data'][$key]['assigned_menu'] = !empty($implode_menuName)?$implode_menuName:'';
		       		$data['fetch_data'][$key]['assigned_submenu_menu'] = !empty($sub_elements['submenu_element'])?$sub_elements['submenu_element']:'';
		       	}else{
		       		$data['fetch_data'][$key]['assigned_menu'] = [];
		       	}


        	}
        }
        return $data;
	}

	public function getMenuDetailsById($id){
		$this->db->select("SQL_CALC_FOUND_ROWS pv_tbl.id, pv_tbl.admin_user_id, pv_tbl.parent_menu_id, pv_tbl.sub_menu,pv_tbl.permissionType",FALSE);
		$this->db->from('menu_previllage_tbl pv_tbl');
		$this->db->where(array('pv_tbl.status'=>1,'pv_tbl.admin_user_id' =>base64_decode($id)));
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function getSubMenuDetailsById($id,$parent_id){
		$this->db->select("SQL_CALC_FOUND_ROWS pv_tbl.id, pv_tbl.admin_user_id, pv_tbl.parent_menu_id, pv_tbl.sub_menu,pv_tbl.permissionType",FALSE);
		$this->db->from('menu_previllage_tbl pv_tbl');
		$this->db->where(array('pv_tbl.status'=>1,'pv_tbl.admin_user_id' =>base64_decode($id),'parent_menu_id'=>$parent_id));
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function existSubMenuDetailsById($id){
		$this->db->select("SQL_CALC_FOUND_ROWS pv_tbl.id, pv_tbl.admin_user_id, pv_tbl.parent_menu_id, pv_tbl.sub_menu,pv_tbl.permissionType",FALSE);
		$this->db->from('menu_previllage_tbl pv_tbl');
		$this->db->where(array('pv_tbl.status'=>1,'pv_tbl.admin_user_id' =>base64_decode($id),'pv_tbl.sub_menu'=>'sp_1'));
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function getCompanyName($subval='',$userId=''){
		if($subval == ''){
			$subval = $userId;
		} else {
			$subval = $subval;
		}

		$this->db->select("SQL_CALC_FOUND_ROWS ad.name,ad.id,ad.state_id",FALSE);
		$this->db->from('auto_admin ad');
		$this->db->where(array('ad.status'=>'A','ad.id'=>base64_decode($subval)));
		$query = $this->db->get();
        $resdata  = $query->result_array();
        
        if(!empty($resdata[0]['state_id'])){
        	$state_id   = []; $implode_state_id='';
        	$test = explode(',', $resdata[0]['state_id']);
        	foreach ($test as $key => $value) {
        		$state_id[] = $value;
        	}
        	$implode_state_id = implode(',',$state_id);

        	$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.company_name,v.id,v.state_id,state.state_name",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('auto_state state','state.id = v.state_id','left');
			$this->db->where(array('v.status'=>'A','v.approved_status'=>1));
			$this->db->where_in('v.state_id',$state_id);
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$query = $this->db->get();
	        $data  = $query->result_array();
	      
	        $finalArr = [];
	        foreach ($data as $key => $com_value) {
	        	$element = []; $implod_user ='';
	        	$this->db->select("c_per.company_id,c_per.admin_user_id,a_admin.name");
				$this->db->from('company_permission_tbl c_per');
				$this->db->join('auto_admin a_admin','a_admin.id = c_per.admin_user_id','left');
				$this->db->where(array('c_per.status'=>1,'a_admin.status'=>'A','c_per.company_id'=>$com_value['id']));
				$query 		= $this->db->get();
		        $result  	= $query->result_array();
		        array_push($finalArr, $com_value);
		        $finalArr[$key]['username'] = $result;
		        if(!empty($finalArr[$key]['username'])){
			        foreach ($finalArr[$key]['username'] as $key1 => $value) {
			        	$element[] = $value['name'];
			        }

		    	}
		       	$implod_user = implode(', ',$element);
		       	$finalArr[$key]['assigned_users'] = $implod_user;
	        }
        } else {
			$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.company_name,v.id",FALSE);
			$this->db->from('vendor_info v');
			$this->db->where(array('v.status'=>'A','v.approved_status'=>1));
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$query = $this->db->get();
	        $data  = $query->result_array();
	        $finalArr =[];
	        foreach ($data as $key => $com_value) {
	        	$element = []; $implod_user ='';
	        	$this->db->select("c_per.company_id,c_per.admin_user_id,a_admin.name");
				$this->db->from('company_permission_tbl c_per');
				$this->db->join('auto_admin a_admin','a_admin.id = c_per.admin_user_id','left');
				$this->db->where(array('c_per.status'=>1,'a_admin.status'=>'A','c_per.company_id'=>$com_value['id']));
				$query 		= $this->db->get();
		        $result  	= $query->result_array();
		        array_push($finalArr, $com_value);
		        $finalArr[$key]['username'] = $result;
		        if(!empty($finalArr[$key]['username'])){
			        foreach ($finalArr[$key]['username'] as $key1 => $value) {
			        	$element[] = $value['name'];
			        }

		    	}
		       	$implod_user = implode(', ',$element);
		       	$finalArr[$key]['assigned_users'] = $implod_user;
	        }
	    }
       
        return $finalArr;
	}

	public function check_email($email){
		$this->db->select("us.email");
		$this->db->from('auto_admin us');
		$this->db->where(array('us.status'=>'A','us.email' =>$email));
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function existCompanyList($id){
		$this->db->select("c_per.company_id,v_i.company_name");
		$this->db->from('company_permission_tbl c_per');
		$this->db->join('vendor_info v_i','v_i.id = c_per.company_id','left');
		$this->db->where(array('c_per.admin_user_id' => base64_decode($id),'c_per.status'=>1));
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function changeStatus($data){
		$sql = "UPDATE auto_admin SET status =  ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['id'])));
		if($query){
			$sql1 = "UPDATE menu_previllage_tbl SET status =  ( CASE WHEN STATUS = '1' THEN '0' ELSE '1' END ) WHERE admin_user_id = ?";
			$query1 = $this->db->query($sql1, array(base64_decode($data['id'])));

			$sql2 = "UPDATE company_permission_tbl SET status =  ( CASE WHEN STATUS = '1' THEN '0' ELSE '1' END ) WHERE admin_user_id = ?";
			$query2 = $this->db->query($sql2, array(base64_decode($data['id'])));
		}
		return $query;
	}

	public function getUserDetailsById($id) {
		$this->db->select("id, first_name, middle_name, last_name, email, mobile, password, password_hint, department_id");
		$this->db->from('auto_admin');
		$this->db->where(array('id'=>base64_decode($id)));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}

	public function updateUserDetails($data,$id){
		$this->db->set($data);
		$this->db->where('id', base64_decode($id));
		$res = $this->db->update('auto_admin');
		return $res;
	}

	public function updateSubmenu($data,$id,$primaryId){
		$this->db->set($data);
		$this->db->where(array('admin_user_id'=>base64_decode($id),'id'=>$primaryId));
		$res = $this->db->update('menu_previllage_tbl');
		return $res;
	}

	public function deleteUserDetails($data){
		$this->db->set('status', 'D');
		$this->db->where('id', base64_decode($data['id']));
		$res = $this->db->update('auto_admin');
		if($res){
			$sql1 = "DELETE FROM menu_previllage_tbl WHERE admin_user_id = ?";
			$query1 = $this->db->query($sql1, array(base64_decode($data['id'])));

			$sql2 = "DELETE FROM company_permission_tbl WHERE admin_user_id = ?";
			$query2 = $this->db->query($sql2, array(base64_decode($data['id'])));
		}
		return $res ;
	}
	
	public function existIsCompanyId(){
		$this->db->select("c_per.company_id,c_per.admin_user_id,a_admin.name");
		$this->db->from('company_permission_tbl c_per');
		$this->db->join('auto_admin a_admin','a_admin.id = c_per.admin_user_id','left');
		$this->db->where(array('c_per.status'=>1,'a_admin.status'=>'A'));
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

	public function getDepartmentLit(){
		$sql = " SELECT `dp`.`id`, `dp`.`department_name` FROM `department` `dp` WHERE `dp`.`department_name` NOT IN('Sales Department','Telecaller Department') AND `dp`.`status` ORDER BY `dp`.`id` DESC";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		return $data;
	}
	
	public function getDepartmentLit_forSales(){
		$sql = " SELECT `dp`.`id`, `dp`.`department_name` FROM `department` `dp` WHERE `dp`.`department_name` IN('Sales Department','Telecaller Department') AND `dp`.`status` ORDER BY `dp`.`id` DESC";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		return $data;
	}

	public function uploadData($table, $post_data=array()){
       	$this->db->insert_batch($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
    }


    public function getAllActiveProductName($limit = 10, $offset = 0, $search = '', $user_id = '') {
    	$this->db->select("c_per.company_id");
		$this->db->from('company_permission_tbl c_per');
		$this->db->where(array('c_per.admin_user_id' => base64_decode($user_id),'c_per.status'=>1));
		$query = $this->db->get();
        $data  = $query->result_array();
       	
        if(!empty($data)){ 
        	$companyIds   = []; $implode_companyId = [];
        	foreach ($data as $key => $company_ids) {
        		$companyIds[] = $company_ids['company_id'];
        	}

        	$this->db->select("SQL_CALC_FOUND_ROWS p.product_id, p.product_name, v.company_name", FALSE);
		    $this->db->from('product p');
		    $this->db->join('product_assign_user p_assign', 'p_assign.product_id = p.product_id', 'left');
			$this->db->join('vendor_info v', 'v.id = p.vendor_id', 'inner');
		    $this->db->where(array('p.status' => 1, 'p.approved_status' => '1'));
		    // Add condition to filter out assigned products
		    $this->db->where('p_assign.product_id IS NULL');
		    if(!empty($data)){
				$this->db->where_in('p.vendor_id', $companyIds);
			}
		    if (!empty($search)) {
		        $this->db->group_start();
		        $this->db->like('p.product_name', $search, 'both');
		        $this->db->or_like('v.company_name', $search, 'both');
				$this->db->or_like('p.product_id', $search, 'both');
		        $this->db->group_end();
		    }
		    $this->db->order_by('p.product_id', 'desc');
		    $this->db->limit($limit, $offset);
		    $query = $this->db->get();

		    $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		    $data['total_count'] = $countQuery->row()->Count;
		    $data['fetch_count'] = $query->num_rows();
		    $data['fetch_data'] = $query->result_array();
		    return $data;
        } else { 
		    $this->db->select("SQL_CALC_FOUND_ROWS p.product_id, p.product_name, v.company_name", FALSE);
		    $this->db->from('product p');
		    $this->db->join('product_assign_user p_assign', 'p_assign.product_id = p.product_id', 'left');
			$this->db->join('vendor_info v', 'v.id = p.vendor_id', 'inner');
		    $this->db->where(array('p.status' => 1, 'p.approved_status' => '1','v.status'=>'A','v.approved_status'=>'1'));
		    // Add condition to filter out assigned products
		    $this->db->where('p_assign.product_id IS NULL');
		    if (!empty($search)) {
		        $this->db->group_start();
		        $this->db->like('p.product_name', $search, 'both');
		        $this->db->or_like('v.company_name', $search, 'both');
				$this->db->or_like('p.product_id', $search, 'both');
		        $this->db->group_end();
		    }
		    $this->db->order_by('p.product_id', 'desc');
		    $this->db->limit($limit, $offset);
		    $query = $this->db->get();
		   // echo $this->db->last_query();
		    $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		    $data['total_count'] = $countQuery->row()->Count;
		    $data['fetch_count'] = $query->num_rows();
		    $data['fetch_data'] = $query->result_array();
		    return $data;
		}
	}

	public function getAllAssignedProductByuserId($user_id){
		$this->db->select("p.product_id, p.product_name, v.company_name,usertbl.id as userId , usertbl.name ,usertbl.email ,usertbl.created_at ");
	    $this->db->from('product_assign_user p_assign');
	    $this->db->join('product p', 'p.product_id = p_assign.product_id', 'left');
	    $this->db->join('auto_admin usertbl', 'usertbl.id = p_assign.user_id', 'left');
		$this->db->join('vendor_info v', 'v.id = p.vendor_id', 'inner');
	    $this->db->where(array('p_assign.user_id' => base64_decode($user_id),'p.status' => 1, 'p.approved_status' => '1','v.status'=>'A','v.approved_status'=>1,'p_assign.status'=>1));
	    $this->db->order_by('p_assign.product_id', 'desc');
	    $query = $this->db->get();
	    $data = $query->result_array();
	    return $data;
	}

	public function deleteAssignProductUserWise($product_id){
		$sql 	= "DELETE FROM `product_assign_user` WHERE product_id = ?";
		$query  = $this->db->query($sql, array($product_id));
		return 1;
	}

    public function checkmenuPermissionby_user($user_id){ 
	    $this->db->select("m_pt.parent_menu_id");
	    $this->db->from('auto_admin a_admin');
	    $this->db->join('menu_previllage_tbl m_pt', 'm_pt.admin_user_id = a_admin.id', 'left');
	    $this->db->where(array(
	        'a_admin.id' => $user_id,
	        'm_pt.parent_menu_id' => 24,
	        'm_pt.status' => 1
	    ));
	    $query = $this->db->get();
	    $data = $query->result_array();
	    return $data;
	}

	public function checkCompanyPermission($postData){
		if($postData['type_check'] == 'company'){
			$this->db->select("c_per.company_id");
		    $this->db->from('company_permission_tbl c_per');
		    $this->db->where(array(
		        'c_per.admin_user_id ' => $postData['admin_user_id'],
		        'c_per.status' => 1
		    ));
		    $query = $this->db->get();
		    $data = $query->result_array();
		    return $data;
		} else {
			$this->db->select("p_a_user.product_id");
		    $this->db->from('product_assign_user p_a_user');
		    $this->db->where(array(
		        'p_a_user.user_id' => $postData['admin_user_id'],
		        'p_a_user.status'  => 1
		    ));
		    $query = $this->db->get();
		    $data  = $query->result_array();
		    return $data;
		}
	}

	public function updateCompanyProductPermission($postdata){
		$sql = "UPDATE company_permission_tbl SET status =  ".$postdata['status']."  WHERE admin_user_id = ?";
		$query = $this->db->query($sql, array($postdata['user_id']));
		if($query){
			$sql1 = "UPDATE product_assign_user SET status =  ".$postdata['status']." WHERE user_id = ? ";
			$query1 = $this->db->query($sql1, array($postdata['user_id']));
		}
		return $query;
	}	

	public function updateProductPermission($postdata){
		$sql1 = "UPDATE product_assign_user SET status =  ".$postdata['status']." WHERE user_id = ? ";
		$query1 = $this->db->query($sql1, array($postdata['user_id']));
		return $query;
	}	

	public function getCompanyIdByproductId($productId){
		$this->db->select("p.vendor_id");
	    $this->db->from('product p');
	    $this->db->where(array(
	        'p.product_id' => $productId,
	        'p.status' => 1
	    ));
	    $query = $this->db->get();
	    $data = $query->result_array();
	    return $data;
	}
}

