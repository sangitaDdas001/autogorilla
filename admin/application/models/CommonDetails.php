<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommonDetails extends CI_Model {

	/**
	 * $table = table name
	 * $content = must be array - name, id, extra
	 * $condition = must be array - id, status
	*/
	public function getDetails($table,$content,$condition){

		if(empty($table) || empty($content) || empty($condition) || (!empty($content) && !is_array($content)) || (!empty($condition) && !is_array($condition)) ) {
			return;
		}
		$this->db->select($content);
		$this->db->from($table);
		$this->db->where($condition);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
}

/* End of file CommonDetails.php */
/* Location: ./application/models/CommonDetails.php */