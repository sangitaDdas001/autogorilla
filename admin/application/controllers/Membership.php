<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Membership extends CI_Controller {

public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('MemberModel'));
	}

	public function goldSellerInfo(){
		$data["page_title"] 		= 	"View Gold Seller Information";
		$data['gold_member_list'] 	=   $this->MemberModel->fetchMembershipInformation(2);
		$page["layout_content"] 	= 	$this->load->view('pages/membership/gold-seller-info', $data, true);
		$page["script_files"]		= 	$this->load->view('scripts/membership/membership',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function silverSellerInfo(){
		$data["page_title"] 		= 	"View Silver Seller Information";
		$data['silver_member_list'] =   $this->MemberModel->fetchMembershipInformation(3);
		$page["layout_content"] 	= 	$this->load->view('pages/membership/silver-seller-info', $data, true);
		$page["script_files"]		= 	$this->load->view('scripts/membership/membership',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function bronzeSellerInfo(){
		$data["page_title"] 		= 	"View Bronze Seller Information";
		$data['bronze_member_list'] =   $this->MemberModel->fetchMembershipInformation(4);
		$page["layout_content"] 	= 	$this->load->view('pages/membership/bronze-seller-info', $data, true);
		$page["script_files"]		= 	$this->load->view('scripts/membership/membership',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}	

	public function aVerifiedSellerInfo(){
		$data["page_title"] 		= 	"View AVarified Seller Information";
		$data['ag_member_list'] =   $this->MemberModel->fetchMembershipInformation(5);
		$page["layout_content"] 	= 	$this->load->view('pages/membership/averified-seller-info', $data, true);
		$page["script_files"]		= 	$this->load->view('scripts/membership/membership',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function proCatalogSellerInfo(){
		$data["page_title"] 		= 	"View Pro Catelog Service Seller Information";
		$data['proCat_member_list'] =   $this->MemberModel->fetchMembershipInformation(6);
		$page["layout_content"] 	= 	$this->load->view('pages/membership/proCatalog-seller-info', $data, true);
		$page["script_files"]		= 	$this->load->view('scripts/membership/membership',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}
	public function featuredCompanyList(){
		$data["page_title"] 			= 	"View Featured Comapny Information";
		$data['featured_member_list'] 	=   $this->MemberModel->fetchFeaturedCompanyInformation();
		$page["layout_content"] 		= 	$this->load->view('pages/membership/featured_company_list', $data, true);
		$page["script_files"]			= 	$this->load->view('scripts/membership/membership',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}
}

/* End of file Membership.php */
/* Location: ./application/controllers/Membership.php */ ?>