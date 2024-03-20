<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LeadsModel extends CI_Model {

	public function getAllLeads($limit = 10, $offset = 0, $search='', $searchByFromMin='',$searchByToMax=''){
		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		//echo $where;exit;

		$this->db->select("SQL_CALC_FOUND_ROWS led.name as user_name, led.email, led.phone, led.qty, led.leads_for, IF(led.status = 'A','Active','Reject') AS `status`,led.id , ven.name ,ven.company_name, pro.product_name, ven.email as vendor_email ,ven.vendor_catalog_url_slug, led.created_at AS created_at, IF(led.lead_type = 1,'Direct Lead','Buy Lead') AS `lead_type` ",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where('led.status !=', 'D');
		if(!empty($where)){
		$this->db->where($where);
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('ven.company_name', $search, 'after');
			$this->db->or_like('led.id', $search, 'after');
			$this->db->or_like('pro.product_name', $search, 'after');
 			$this->db->or_like('led.name', $search, 'after');
 			$this->db->or_like('led.email', $search, 'after');
 			$this->db->or_like('led.phone', $search, 'after');
 			$this->db->or_like('ven.name', $search, 'after');
 			$this->db->group_end();
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

		//return $this->db->last_query();exit;

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function getActiveLeads($limit = 10, $offset = 0, $search='', $searchByFromMin='',$searchByToMax=''){
		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		$this->db->select("SQL_CALC_FOUND_ROWS led.name as user_name, led.email, led.phone, led.qty, led.leads_for, IF(led.status = 'A','Active','Reject') AS `status`,led.id , ven.name ,ven.company_name, pro.product_name, ven.email as vendor_email ,ven.vendor_catalog_url_slug, led.created_at AS created_at ",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where('led.status !=', 'D');
		$this->db->where('led.status =', 'A');
		if(!empty($where)){
		$this->db->where($where);
		}
		if (!empty($search)) {
			$this->db->group_start();
 			$this->db->like('ven.company_name', $search, 'after');
			$this->db->or_like('led.id', $search, 'after');
			$this->db->or_like('pro.product_name', $search, 'after');
 			$this->db->or_like('led.name', $search, 'after');
 			$this->db->or_like('led.email', $search, 'after');
 			$this->db->or_like('led.phone', $search, 'after');
 			$this->db->or_like('ven.name', $search, 'after');
 			$this->db->group_end();
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}


	public function getRejectLeads($limit = 10, $offset = 0, $search='', $searchByFromMin='',$searchByToMax=''){
		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		$this->db->select("SQL_CALC_FOUND_ROWS led.name as user_name, led.email, led.phone, led.qty, led.leads_for, IF(led.status = 'A','Active','Reject') AS `status`,led.id , ven.name ,ven.company_name, pro.product_name, ven.email as vendor_email ,ven.vendor_catalog_url_slug, led.created_at AS created_at , IF(led.lead_type = 1,'Direct Lead','Buy Lead') AS `lead_type` ",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where('led.status !=', 'D');
		$this->db->where('led.status =', 'I');
		if(!empty($where)){
		$this->db->where($where);
		}
		if (!empty($search)) {
			$this->db->group_start();
 			$this->db->like('ven.company_name', $search, 'after');
			$this->db->or_like('led.id', $search, 'after');
			$this->db->or_like('pro.product_name', $search, 'after');
 			$this->db->or_like('led.name', $search, 'after');
 			$this->db->or_like('led.email', $search, 'after');
 			$this->db->or_like('led.phone', $search, 'after');
 			$this->db->or_like('ven.name', $search, 'after');
 			$this->db->group_end();
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function LeadDetailsById($id){
		$finalArr =[];
		$this->db->select("led.name as user_name, led.email, led.phone, led.qty, led.leads_for, IF(led.status = 'A','Active','Inactive') AS `status`,led.id , ven.name ,ven.company_name ,pro.product_name, ven.email as vendor_email,led.leads_for, led.formsource_path,led.fromsource_name,led.request_submit_form_name,led.email_status,led.created_at,led.genral_request,led.requirement_details,ven.type,led.leads_company_name,ven.vendor_catalog_url_slug,ven.name,led.rejected_reason,ven.phone as vendor_phone,led.looking_suppliers,led.file,led.required_product_name");
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where('led.id', base64_decode($id));
		$query = $this->db->get();
        $data  = $query->result_array();
        if(!empty($data)){
        	$this->db->select("ven.company_name as led_reg_company_as_supplier,ven.vendor_catalog_url_slug, ven.type");
			$this->db->from('vendor_info ven');
			$this->db->where('ven.email',$data[0]['email']);
			$query = $this->db->get();
		    $res  = $query->result_array();
		    array_push($finalArr,$data[0]);
		    $finalArr[0]['led_reg_company'] = $res[0];
        }
       
        return $finalArr[0];
	}

	public function updateLead($data,$id){
		$this->db->set($data);
		$this->db->where('id',$id);
		$res = $this->db->update('leads_master_tbl');
		return $res;
	}

	public function exportAllLeadCsvExport(){
		$searchByFromMin = $_SESSION['searchByFromMin'];
		$searchByToMax = $_SESSION['searchByToMax'];
		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		$this->db->select("SQL_CALC_FOUND_ROWS led.id AS lead_id, ven.company_name, ven.email as vendor_email , pro.product_name, led.name as user_name, led.email, led.phone, IF(led.status = 'A','Active','Reject') AS `status`",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where('led.status !=', 'D');
		if(!empty($where)){
		$this->db->where($where);
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
    }

    public function exportActiveLeadCsvExport(){
    	$searchByFromMin = $_SESSION['searchByFromMin'];
		$searchByToMax = $_SESSION['searchByToMax'];
		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		$this->db->select("SQL_CALC_FOUND_ROWS led.id AS lead_id, ven.company_name, ven.email as vendor_email , pro.product_name, led.name as user_name, led.email, led.phone, IF(led.status = 'A','Active','Reject') AS `status`",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where('led.status !=', 'D');
		$this->db->where('led.status =', 'A');
		if(!empty($where)){
		$this->db->where($where);
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
    }

    public function exportRejectLeadCsvExport(){
    	$searchByFromMin = $_SESSION['searchByFromMin'];
		$searchByToMax = $_SESSION['searchByToMax'];
		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		$this->db->select("SQL_CALC_FOUND_ROWS led.id AS lead_id, ven.company_name, ven.email as vendor_email , pro.product_name, led.name as user_name, led.email, led.phone, IF(led.status = 'A','Active','Reject') AS `status`",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where('led.status !=', 'D');
		$this->db->where('led.status =', 'I');
		if(!empty($where)){
		$this->db->where($where);
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
    }

    public function getAllBuyLeads($limit = 10, $offset = 0, $search='', $searchByFromMin='',$searchByToMax=''){
		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		//echo $where;exit;

		$this->db->select("SQL_CALC_FOUND_ROWS led.name as user_name, led.email, led.phone, led.qty, led.leads_for, IF(led.status = 'A','Active','Reject') AS `status`,led.id , ven.name ,ven.company_name, pro.product_name, ven.email as vendor_email ,ven.vendor_catalog_url_slug, led.created_at AS created_at, IF(led.lead_type = 1,'Direct Lead','Buy Lead') AS `lead_type` ",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where(array('led.status !='=>'D' ,'led.lead_type'=>2));
		if(!empty($where)){
		$this->db->where($where);
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('ven.company_name', $search, 'after');
			$this->db->or_like('led.id', $search, 'after');
			$this->db->or_like('pro.product_name', $search, 'after');
 			$this->db->or_like('led.name', $search, 'after');
 			$this->db->or_like('led.email', $search, 'after');
 			$this->db->or_like('led.phone', $search, 'after');
 			$this->db->or_like('ven.name', $search, 'after');
 			$this->db->group_end();
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

		//return $this->db->last_query();exit;

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function getAllActiveBuyLeads($limit = 10, $offset = 0, $search='', $searchByFromMin='',$searchByToMax=''){

		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		//echo $where;exit;

		$this->db->select("SQL_CALC_FOUND_ROWS led.name as user_name, led.email, led.phone, led.qty, led.leads_for, IF(led.status = 'A','Active','Reject') AS `status`,led.id , ven.name ,ven.company_name, pro.product_name, ven.email as vendor_email ,ven.vendor_catalog_url_slug, led.created_at AS created_at, IF(led.lead_type = 1,'Direct Lead','Buy Lead') AS `lead_type` ",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where(array('led.status'=>'A' ,'led.lead_type'=>2));
		if(!empty($where)){
		$this->db->where($where);
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('ven.company_name', $search, 'after');
			$this->db->or_like('led.id', $search, 'after');
			$this->db->or_like('pro.product_name', $search, 'after');
 			$this->db->or_like('led.name', $search, 'after');
 			$this->db->or_like('led.email', $search, 'after');
 			$this->db->or_like('led.phone', $search, 'after');
 			$this->db->or_like('ven.name', $search, 'after');
 			$this->db->group_end();
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

		//return $this->db->last_query();exit;

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}
	
	public function getAllDirectLeads($limit = 10, $offset = 0, $search='', $searchByFromMin='',$searchByToMax=''){

		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		//echo $where;exit;

		$this->db->select("SQL_CALC_FOUND_ROWS led.name as user_name, led.email, led.phone, led.qty, led.leads_for, IF(led.status = 'A','Active','Reject') AS `status`,led.id , ven.name ,ven.company_name, pro.product_name, ven.email as vendor_email ,ven.vendor_catalog_url_slug, led.created_at AS created_at, IF(led.lead_type = 1,'Direct Lead','Buy Lead') AS `lead_type` ",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where(array('led.status !=' =>'D' ,'led.lead_type'=>1));
		if(!empty($where)){
		$this->db->where($where);
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('ven.company_name', $search, 'after');
			$this->db->or_like('led.id', $search, 'after');
			$this->db->or_like('pro.product_name', $search, 'after');
 			$this->db->or_like('led.name', $search, 'after');
 			$this->db->or_like('led.email', $search, 'after');
 			$this->db->or_like('led.phone', $search, 'after');
 			$this->db->or_like('ven.name', $search, 'after');
 			$this->db->group_end();
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

		//return $this->db->last_query();exit;

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function getActiveDirectLeads($limit = 10, $offset = 0, $search='', $searchByFromMin='',$searchByToMax=''){

		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		//echo $where;exit;

		$this->db->select("SQL_CALC_FOUND_ROWS led.name as user_name, led.email, led.phone, led.qty, led.leads_for, IF(led.status = 'A','Active','Reject') AS `status`,led.id , ven.name ,ven.company_name, pro.product_name, ven.email as vendor_email ,ven.vendor_catalog_url_slug, led.created_at AS created_at, IF(led.lead_type = 1,'Direct Lead','Buy Lead') AS `lead_type` ",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where(array('led.status'=>'A' ,'led.lead_type' => 1));
		if(!empty($where)){
		$this->db->where($where);
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('ven.company_name', $search, 'after');
			$this->db->or_like('led.id', $search, 'after');
			$this->db->or_like('pro.product_name', $search, 'after');
 			$this->db->or_like('led.name', $search, 'after');
 			$this->db->or_like('led.email', $search, 'after');
 			$this->db->or_like('led.phone', $search, 'after');
 			$this->db->or_like('ven.name', $search, 'after');
 			$this->db->group_end();
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

		//return $this->db->last_query();exit;

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function exportActiveDirectLeadCsvExport(){
		$searchByFromMin = $_SESSION['searchByFromMin'];
		$searchByToMax = $_SESSION['searchByToMax'];

		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		$this->db->select("SQL_CALC_FOUND_ROWS led.id AS lead_id, ven.company_name, ven.email as vendor_email , pro.product_name, led.name as user_name, led.email, led.phone, IF(led.status = 'A','Active','Reject') AS `status`",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where(array('led.status'=>'A' ,'led.lead_type' => 1));
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
	}

	public function exportAllDirectLeadCsvExport(){
		$searchByFromMin = $_SESSION['searchByFromMin'];
		$searchByToMax = $_SESSION['searchByToMax'];

		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		$this->db->select("SQL_CALC_FOUND_ROWS led.id AS lead_id, ven.company_name, ven.email as vendor_email , pro.product_name, led.name as user_name, led.email, led.phone, IF(led.status = 'A','Active','Reject') AS `status`",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where(array('led.status !=' =>'D' ,'led.lead_type' => 1));
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
	}

	public function exportActiveBuyLeadCsvExport(){
		$searchByFromMin = $_SESSION['searchByFromMin'];
		$searchByToMax = $_SESSION['searchByToMax'];

		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		$this->db->select("SQL_CALC_FOUND_ROWS led.id AS lead_id, ven.company_name, ven.email as vendor_email , pro.product_name, led.name as user_name, led.email, led.phone, IF(led.status = 'A','Active','Reject') AS `status`",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where(array('led.status' =>'A' ,'led.lead_type' => 2));
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
	}

	public function exportAllBuyLeadCsvExport(){
		$searchByFromMin = $_SESSION['searchByFromMin'];
		$searchByToMax = $_SESSION['searchByToMax'];

		if(!empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) BETWEEN '".$searchByFromMin."' AND '".$searchByToMax."' " ;
		}else if(!empty($searchByFromMin) && empty($searchByToMax)){
			$where = "DATE(led.created_at) >= '".$searchByFromMin."' " ;
		}else if(empty($searchByFromMin) && !empty($searchByToMax)) {
			$where = "DATE(led.created_at) <= '".$searchByToMax."' ";
		}else{
			$where = '';
		}

		$this->db->select("SQL_CALC_FOUND_ROWS led.id AS lead_id, ven.company_name, ven.email as vendor_email , pro.product_name, led.name as user_name, led.email, led.phone, IF(led.status = 'A','Active','Reject') AS `status`",FALSE);
		$this->db->from('leads_master_tbl led');
		$this->db->join('vendor_info ven', 'ven.id = led.vendor_id','left');
		$this->db->join('product pro', 'pro.product_id = led.product_id','left');
		$this->db->where(array('led.status !=' =>'D' ,'led.lead_type' => 2));
		if(!empty($where)){
			$this->db->where($where);
		}
		$this->db->order_by('led.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
	}



}

/* End of file LeadsModel.php */
/* Location: ./application/models/LeadsModel.php */ ?>