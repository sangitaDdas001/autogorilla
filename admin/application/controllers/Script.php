<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Script extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ScriptModel');
	}

	public function index()
	{
		$data = array();
		$page["script_files"]					=   $this->load->view('scripts/category/category',$data, true);
        $page["layout_content"]					=	$this->load->view('pages/scripts', $data, true);
        $this->load->view('layouts/dashboard_layout', $page);
	}
	public function uploadData()
    {
        $this->ScriptModel->uploadData();
        //redirect('categoryscript');
    }

}

/* End of file Script.php */
/* Location: ./application/controllers/Script.php */