<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MemberModel extends CI_Model {

	public function fetchMembershipInformation($memberId){
		$this->db->select("c_per.company_id");
		$this->db->from('company_permission_tbl c_per');
		$this->db->where(array('c_per.admin_user_id' => $_SESSION['adminsessiondetails']['id']));
		$query = $this->db->get();
        $data  = $query->result_array();
        if(!empty($data) && $_SESSION['adminsessiondetails']['id'] != 1){
			$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%Y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company,v.autogorilla_verified, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_avscore, group_concat(distinct(se_pack.service_plan)) as service_plan",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
			$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
			$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
			$this->db->join('company_permission_tbl c_p','c_p.company_id = v.id','left');
			$this->db->where(array('v.status !='=>'D','v.otp_varify'=>1,'v_service_p.service_package_id'=>$memberId,'v_service_p.status'=>'A','c_p.admin_user_id'=>$_SESSION['adminsessiondetails']['id']));
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$query = $this->db->get();
	        $data = $query->result_array();
	        $finalArr = [];
	        if(!empty($data)){
	        	foreach ($data as $key => $value) {
			        $this->db->select("pro.product_id,pro.status,pro.approved_status");
					$this->db->from('product pro');
					$this->db->where(array('pro.vendor_id'=>$value['id'],'pro.status'=>1));
					$query   = $this->db->get();
	        		$numrows = $query->num_rows();
	        		$res = $query->result_array();
	        		array_push($finalArr, $value);
	        		$finalArr[$key]['product'] = $res;
	        	}
	        }
	        return $finalArr;
	    } else {
	    	$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%Y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company,v.autogorilla_verified, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_avscore, group_concat(distinct(se_pack.service_plan)) as service_plan",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
			$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
			$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
			$this->db->where(array('v.status !='=>'D','v.otp_varify'=>1,'v_service_p.service_package_id'=>$memberId,'v_service_p.status'=>'A'));
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$query = $this->db->get();
	        $data = $query->result_array();
	        $finalArr = [];
	        if(!empty($data)){
	        	foreach ($data as $key => $value) {
			        $this->db->select("pro.product_id,pro.status,pro.approved_status");
					$this->db->from('product pro');
					$this->db->where(array('pro.vendor_id'=>$value['id'],'pro.status'=>1));
					$query   = $this->db->get();
	        		$numrows = $query->num_rows();
	        		$res = $query->result_array();
	        		array_push($finalArr, $value);
	        		$finalArr[$key]['product'] = $res;
	        	}
	        }
	        return $finalArr;
	    }
	}

	public function fetchFeaturedCompanyInformation(){
		$this->db->select("c_per.company_id");
		$this->db->from('company_permission_tbl c_per');
		$this->db->where(array('c_per.admin_user_id' => $_SESSION['adminsessiondetails']['id']));
		$query = $this->db->get();
        $data  = $query->result_array();
        if(!empty($data) && $_SESSION['adminsessiondetails']['id'] != 1){
			$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%Y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company,v.autogorilla_verified, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_avscore, group_concat(distinct(se_pack.service_plan)) as service_plan",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
			$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
			$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
			$this->db->join('company_permission_tbl c_p','c_p.company_id = v.id','left');
			$this->db->where(array('v.status !='=>'D','v.otp_varify'=>1,'v.featured_company'=>1,'c_p.admin_user_id'=>$_SESSION['adminsessiondetails']['id']));
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$query = $this->db->get();
	        $data = $query->result_array();
	        $finalArr = [];
	        if(!empty($data)){
	        	foreach ($data as $key => $value) {
			        $this->db->select("pro.product_id,pro.status,pro.approved_status");
					$this->db->from('product pro');
					$this->db->where(array('pro.vendor_id'=>$value['id'],'pro.status'=>1));
					$query   = $this->db->get();
	        		$numrows = $query->num_rows();
	        		$res = $query->result_array();
	        		array_push($finalArr, $value);
	        		$finalArr[$key]['product'] = $res;
	        	}
	        }
	        return $finalArr;
	    } else {
	    	$this->db->select("SQL_CALC_FOUND_ROWS v.name, v.vendor_catalog_url_slug, v.company_name, v.email, date_format(v.created_at,'%d-%m-%Y %h:%i:%s %p') as created_at, IF(v.status = 'A','Active','Inactive') AS `status`,v.id,CASE WHEN v.approved_status = '1' THEN 'Approved' WHEN v.approved_status = '2' THEN 'Reject' ELSE 'Not Approved' END AS `approved_status`, v.password, v.featured_company,v.autogorilla_verified, v.decript_password,round(AVG(coalesce(v_sc.total_score,0))) as cat_avscore, group_concat(distinct(se_pack.service_plan)) as service_plan",FALSE);
			$this->db->from('vendor_info v');
			$this->db->join('vendor_product_score_table v_sc','v_sc.vendor_id=v.id','left');
			$this->db->join('vendor_service_package_list v_service_p','(v_service_p.vendor_id = v.id AND v_service_p.status = "A")','left');
			$this->db->join('service_package se_pack','se_pack.id = v_service_p.service_package_id','left');
			$this->db->where(array('v.status !='=>'D','v.otp_varify'=>1,'v.featured_company'=>1));
			$this->db->group_by('v.id');
			$this->db->order_by('v.id','DESC');
			$query = $this->db->get();
	        $data = $query->result_array();
	        $finalArr = [];
	        if(!empty($data)){
	        	foreach ($data as $key => $value) {
			        $this->db->select("pro.product_id,pro.status,pro.approved_status");
					$this->db->from('product pro');
					$this->db->where(array('pro.vendor_id'=>$value['id'],'pro.status'=>1));
					$query   = $this->db->get();
	        		$numrows = $query->num_rows();
	        		$res = $query->result_array();
	        		array_push($finalArr, $value);
	        		$finalArr[$key]['product'] = $res;
	        	}
	        }
	        return $finalArr;
	    }
	}
}

/* End of file MemberModel.php */
/* Location: ./application/models/MemberModel.php */?>