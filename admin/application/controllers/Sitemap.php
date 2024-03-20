<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login/index');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
	}
    public function sitemap_url(){
		$sitemappath 					= $_SERVER['DOCUMENT_ROOT'].'/'; 
		$xmlFiles 						= getXmlFilesList($sitemappath);
		$data['xmlFiles'] 				= $xmlFiles;
       	$page["layout_content"] 		= $this->load->view('pages/sitemap_url', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}
}
