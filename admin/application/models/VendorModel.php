<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VendorModel extends CI_Model {

	public function getCompanyName(){
		$this->db->select(array('v.id', 'v.company_name'));
        $this->db->from('vendor_info as v');
        $this->db->where(array('v.approved_status'=>'1'));
        $query = $this->db->get();
        return $query->result_array();
	}

}

/* End of file VendorModel.php */
/* Location: ./application/models/VendorModel.php */