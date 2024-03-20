<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StateModel extends CI_Model {

	public function insert($table, $post_data=array()){
		$this->db->insert_batch($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}

	public function getAllCountryName(){
		$this->db->select("country.id,country.country_name");
		$this->db->from('auto_country country');
		$this->db->where('country.status =', 'A');
		$query = $this->db->get();
        $data = $query->result_array();
        return $data;
	}

	public function getAllStateDetails($limit = 10, $offset = 0, $search=''){
		$this->db->select("SQL_CALC_FOUND_ROWS state.state_name, state.id AS stateId, country.country_name, country.id AS country_id, IF(state.status = 'A','Active','Inactive') AS `status`",FALSE);
		$this->db->from('auto_state state');
		$this->db->join('auto_country country', 'country.id = state.country_id');
		$this->db->where(array('state.status !='=>'D'));

		if (!empty($search)) {
 			$this->db->like('state.state_name', $search, 'both');
 			$this->db->like('country.country_name', $search, 'both');
		}
		$this->db->order_by('state.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data'] = $query->result_array();
        return $data;
	}

	public function changeStatus($data){
		$sql = "UPDATE auto_state SET status =  ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['state_id'])));
		return $query;
	}
	public function deleteStateDetails($data){
		$this->db->set('status', 'D');
		$this->db->where('id', base64_decode($data['state_id']));
		$res = $this->db->update('auto_state');
		return $this->db->affected_rows();
	}

	public function getStateDetailsById($id){
		$this->db->select("St.state_name, St.id as state_id, country.id as country_id,country.country_name");
		$this->db->from('auto_state St');
		$this->db->join('auto_country country', 'country.id = St.country_id');
		$this->db->where(array('St.id'=>base64_decode($id)));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}

	public function updateStateDetails($data,$id){
		$this->db->set($data);
		$this->db->where('id', base64_decode($id));
		$res = $this->db->update('auto_state');
		return $res;
	}


	public function exportStateDetails(){
		$this->db->select("id,country_id,state_name");
		$this->db->from('auto_state state');
		$this->db->where(array('state.status'=>'A'));
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

}

/* End of file StateModel.php */
/* Location: ./application/models/StateModel.php */