<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AboutModel extends CI_Model {

	public function getAllAboutDetails($limit = 10, $offset = 0, $search='') {
		$this->db->select("SQL_CALC_FOUND_ROWS aboutU.content,aboutU.id,aboutU.content_for,  date_format(aboutU.modified_at,'%d-%m-%y %h:%i:%s %p') as modified_at, IF(aboutU.status = 'A','Active','Inactive') AS `status`,aboutU.id",FALSE);
		$this->db->from('auto_how_it_works aboutU');
		$this->db->where('aboutU.status !=', 'D');
		if (!empty($search)) {
				$this->db->like('aboutU.heading_1', $search, 'after');
		}
		$this->db->order_by('aboutU.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

	    $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
	    $data['total_count'] = $countQuery->row()->Count;
	    $data['fetch_count'] = $query->num_rows();
	    $data['fetch_data']  = $query->result_array();
	    return $data;
	}	

	/*	Change status */
	public function changeStatus($data) {
		$sql = "UPDATE auto_how_it_works SET status = ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['about_id'])));
		return $query;
	}

	/*	get Author details by id */
	public function getDetailsById($id){
		$this->db->select("aboutU.content,aboutU.id,aboutU.content_for",FALSE);
		$this->db->from('auto_how_it_works aboutU');
		$this->db->where(array('id'=>base64_decode($id)));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}

	public function updateAboutDetails($data,$id) {
		$this->db->set($data);
		$this->db->where('id', base64_decode($id));
		$res = $this->db->update('auto_how_it_works');
		return $this->db->affected_rows();
	}

}

/* End of file AboutModel */
/* Location: ./application/models/AboutModel */