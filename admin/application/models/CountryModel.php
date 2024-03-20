<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CountryModel extends CI_Model {

	public function insert($table, $post_data=array()){
		$this->db->insert($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}

	public function getAllCountryDetails($limit = 10, $offset = 0, $search=''){
		$this->db->select("SQL_CALC_FOUND_ROWS catN.country_name, catN.country_code, catN.created_at,catN.country_image, IF(catN.status = 'A','Active','Inactive') AS `status`,catN.id",FALSE);
		$this->db->from('auto_country catN');
		$this->db->where('catN.status !=', 'D');
		if (!empty($search)) {
 			$this->db->like('catN.country_name', $search, 'after');
		}
		$this->db->order_by('catN.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}

	public function getCountryDetailsById($id){
		$this->db->select("c.country_name, c.country_code, c.id, c.created_at,c.country_image");
		$this->db->from('auto_country c');
		$this->db->where(array('id'=>base64_decode($id)));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}

	public function updateCountryDetails($data,$id){
		$this->db->set($data);
		$this->db->where('id', base64_decode($id));
		$res = $this->db->update('auto_country');
		return $res;
	}

	public function changeStatus($data){
		$sql = "UPDATE auto_country SET status =  ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['country_id'])));
		return $query;
	}

	public function deleteCountryDetails($data){
		$this->db->set('status', 'D');
		$this->db->where('id', base64_decode($data['country_id']));
		$res = $this->db->update('auto_country');
		return $this->db->affected_rows();
	}

	public function exportCountryDetails(){
		$this->db->select("id,country_name,country_code");
		$this->db->from('auto_country');
		$this->db->where(array('status'=>'A'));
		$query = $this->db->get();
        $data  = $query->result_array();
        return $data;
	}

}

/* End of file CountryModel.php */
/* Location: ./application/models/CountryModel.php */