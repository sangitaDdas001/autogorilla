<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ServiceModel extends CI_Model {

	public function getAllPlan($limit = 10, $offset = 0, $search=''){
		$this->db->select("SQL_CALC_FOUND_ROWS sp.service_plan, date_format(sp.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, sp.plan_price, IF(sp.status = 'A','Active','Inactive') AS `status`,sp.id,sp.logo",FALSE);
		$this->db->from('service_package sp');
		$this->db->where(array('sp.status !='=>'D'));

		if (!empty($search)) {
 			$this->db->like('sp.service_plan', $search, 'after');
		}
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function getServicePlanDetailsById($id){
		$this->db->select("sp.service_plan, sp.id,sp.logo");
		$this->db->from('service_package sp');
		$this->db->where(array('sp.id'=>base64_decode($id)));
		$query = $this->db->get();
        $data = $query->result_array();
        return $data;
	}
	public function update($data,$filedName,$id,$table){
		$this->db->set($data);
		$this->db->where($filedName, base64_decode($id));
		$res = $this->db->update($table);
		return $res;
	}

	public function insert($table, $post_data=array()){
		$this->db->insert($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}

	/*	Change status */
	/*public function changeStatus($data) {
		$sql = "UPDATE service_package SET status = ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['id'])));
		return $query;
	}*/

	/*	Change status */
	public function changeStatus($data) {
		if($data['status']=='Active'){
			$sql = "UPDATE service_package SET status = 'I' WHERE id = ?";
			$query = $this->db->query($sql, array(base64_decode($data['id'])));
			
			$sql2 = "UPDATE vendor_service_package_list SET status ='I' WHERE service_package_id = ?";
			$query = $this->db->query($sql2, array(base64_decode($data['id'])));

		}else{
			$sql = "UPDATE service_package SET status = 'A' WHERE id = ?";
			$query = $this->db->query($sql, array(base64_decode($data['id'])));

			$sql2 = "UPDATE vendor_service_package_list SET status ='A' WHERE service_package_id = ?";
			$query = $this->db->query($sql2, array(base64_decode($data['id'])));
		}
		return $query;
	}

	/*public function delete($table,$data){
		$sql = "UPDATE ".$table." SET status ='D' WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['id'])));
		return $query;
	}*/

	public function delete($table,$data){
		$sql = "UPDATE ".$table." SET status ='D' WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['id'])));
		if($query){
			$sql2 = "UPDATE vendor_service_package_list SET status ='I' WHERE service_package_id = ?";
			$query = $this->db->query($sql2, array(base64_decode($data['id'])));
		}
		return $query;
	}

	public function getServices(){
		$this->db->select("sp.service_plan,sp.id,sp.logo");
		$this->db->from('service_package sp');
		$this->db->where(array('sp.status'=>'A'));
		$query = $this->db->get();
        $data = $query->result_array();
        return $data;
	}

	public function getVendorDetailsById($id){
		$this->db->select("v.name,v.id,v.company_name");
		$this->db->from('vendor_info v');
		$this->db->where(array('v.id'=>base64_decode($id)));
		$query = $this->db->get();
        $data = $query->result_array();
        return $data;
	}
	
	public function checkPackageExist($vendor_id){
		$this->db->select("v_p_l.id");
		$this->db->from('vendor_service_package_list v_p_l');
		$this->db->where(array('v_p_l.vendor_id'=>$vendor_id,'v_p_l.service_package_id !='=>5));
		$query 		= $this->db->get();
        $numrows  	= $query->num_rows();
        return $numrows;
	}

	public function deleteVendorPackage($vendor_id){
		/*$this->db->select("v_p_l.id");
		$this->db->from('vendor_service_package_list v_p_l');
		$this->db->where(array('v_p_l.vendor_id'=>$vendor_id,'v_p_l.service_package_id !='=>5));
		$query 		= $this->db->get();
        $numrows  	= $query->num_rows();
       
        if($numrows >0 ){
			$this->db->where(array('vendor_id'=> $vendor_id,'service_package_id !='=>5));
    		$this->db->delete('vendor_service_package_list');
    	} else {
    		$this->db->where(array('vendor_id'=> $vendor_id));
    		$this->db->delete('vendor_service_package_list');
    	}
    	return $numrows;*/

    	$sql   = "UPDATE `vendor_service_package_list` SET status ='I' WHERE vendor_id = ? AND service_package_id != 5";
		$query = $this->db->query($sql, array($vendor_id));
		return $query;

	}

	public function fetchSubscriptionData(){
	    $sql = "SELECT `sp`.`service_plan`,`sp`.`id` from `service_package` `sp`  WHERE `sp`.`id` NOT IN(5,6,1) AND sp.status = 'A' ORDER BY sp.id ";
	    $query      = $this->db->query($sql);
	    $data 		= $query->result_array();
	    return $data;
	}

	public function fetchPackageData($vendor_id){
	    $sql = "SELECT `sp`.`service_plan`,`sp`.`id`,`vsp`.`id` as `vsp_id`,`vsp`.`vendor_id`,if(vsp.status='A',`vsp`.`package_start_date`,'') as package_start_date, if(vsp.status='A',`vsp`.`package_expiry_date`,'') as package_expiry_date, `vsp`.`service_package_id`,`vsp`.status FROM service_package AS sp LEFT JOIN vendor_service_package_list AS vsp ON vsp.service_package_id = sp.id AND vsp.vendor_id =".base64_decode($vendor_id)." AND vsp.status = 'A' WHERE sp.id != 5  AND sp.status = 'A' ORDER BY sp.id ";
	    $query      = $this->db->query($sql);
	    $data 		= $query->result_array();
	    return $data;
	}

	public function autogorilla_verify_status($table,$vendorId,$status){
		$sql   = "UPDATE ".$table." SET autogorilla_verified ='".$status."' WHERE id = ?";
		$query = $this->db->query($sql, array($vendorId));
		return $query;
	}

	public function update_package($data,$vendor_id,$package_id,$table){
		$this->db->set($data);
		$this->db->where(array('vendor_id'=>$vendor_id,'service_package_id'=>$package_id));
		$res = $this->db->update($table);
		return $res;
	}

	public function checkPackageId($vendor_id,$package_id){
		$this->db->select("v_p_l.id");
		$this->db->from('vendor_service_package_list v_p_l');
		$this->db->where(array('v_p_l.vendor_id'=>$vendor_id,'v_p_l.service_package_id'=>$package_id));
		$query 		= $this->db->get();
        $numrows  	= $query->num_rows();
        return $numrows;
	}

	public function fetchAuto_verifiedData($vendor_id,$package_id){
		$this->db->select("v_p_l.id,v_p_l.package_start_date,v_p_l.package_expiry_date");
		$this->db->from('vendor_service_package_list v_p_l');
		$this->db->where(array('v_p_l.vendor_id'=>base64_decode($vendor_id),'v_p_l.service_package_id'=>$package_id));
		$query 		= $this->db->get();
        $numrows  	= $query->num_rows();
        $data 		= $query->result_array();
        return $data;
	}

	public function deleteSubscriptionByVendorId($data){
			$sql = "DELETE FROM vendor_service_package_list WHERE  vendor_id = ? AND service_package_id = ? ";
			$query = $this->db->query($sql, array(base64_decode($data['vendor_id']),$data['subscriptionId']));
			if($data['subscriptionId'] == 5){
				$sql2 = "UPDATE vendor_info SET autogorilla_verified = 0 WHERE  id = ? ";
				$query = $this->db->query($sql2, array(base64_decode($data['vendor_id'])));
			}
			return $query;
	}

	public function insertBatch($table, $post_data=array()){
		$this->db->insert_batch($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}


}

/* End of file ServiceModel.php */
/* Location: ./application/models/ServiceModel.php */