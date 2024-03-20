<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seo extends CI_Controller {

	public $sessionDetails;
	public function __construct()
	{
		parent::__construct();
		if(!($this->session->userdata('adminsessiondetails'))) {
			redirect('Login');
		}
		$this->sessionDetails = $this->session->userdata('adminsessiondetails');
		$this->load->model(array('SeoModel'));
	}

	public function add_seo(){
		$submit = $this->input->post();
		
		if(!empty($submit)){
			$seo_arr = array(
                'meta_title' 			=> $submit['meta_title'],
                'meta_description' 		=> $submit['meta_description'],
                'canonical_url' 		=> $submit['canonical_url'],
                'page_name' 			=> $submit['page_name'],
                'page_url' 				=> $submit['page_url'],
                'seo_page_type' 		=> 'others'
        	); 

			$insert =	$this->SeoModel->insert('seo_meta_info',$seo_arr);
			foreach ($submit['meta_name'] as $key => $value) {
				$finalArr[] = array(
                    'meta_name' 	=> $value,
                    'meta_content' 	=> $submit['meta_content'][$key],
                    'page_name' 	=> $submit['page_name'],
                	'page_url' 		=> $submit['page_url'],
                    'seo_meta_id' 	=> $insert,
            	); 
			}

			$insert_meta = $this->db->insert_batch('seo_multiple_meta_info', $finalArr);
			foreach ($submit['social_meta_og_property'] as $key1 => $og_value) {
				$finalArr2[] = array(
					'social_meta_og_property' 	=> $og_value,
                	'social_meta_og_content' 	=> $submit['social_meta_og_content'][$key1],
                	'page_name' 				=> $submit['page_name'],
                	'page_url' 					=> $submit['page_url'],
                    'seo_meta_id' 				=> $insert,
                );    
			}
				
			$insert_meta_og = $this->db->insert_batch('seo_social_og_info', $finalArr2);
		}
		$data["page_title"] 	= "Add seo Information";
	    $page["layout_content"] = $this->load->view('pages/seo/addSeoInfo', $data, true);
	    $this->load->view('layouts/dashboard_layout', $page);
	}

	public function viewseo(){
		$seoFormat 				= 	$this->seoNameDetailsFormat();
		$data['columns']		=	$seoFormat['seo_column'];
		$data['seoData']		=	$seoFormat['page_name'];
		$data["page_title"] 	=   "View Seo Information";
        $page["layout_content"] =   $this->load->view('pages/seo/seo_information', $data, true);
        $page["script_files"]	=	$this->load->view('scripts/seo/seo_script',$data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	private function seoNameDetailsFormat() {
		$retData['seo_column'] = array("Page name","Page url","Action");
        $retData['page_name'] = "[
        	{ 'data' : 'page_name' },
        	{ 'data' : 'page_url' },
        	
        	{ 'defaultContent' : '<a class=\"edit_info\" href=\"javascript:void(0)\" title=\"Edit seo\" data-toggle=\"modal\"><i class=\"fa fa-edit\" style=\"margin-right: 3px;font-size: 19px; color:#227676\" aria-hidden=\"true\"></i></a>'
        	}
    	]";

        return $retData;
	}

	public function seoDetails_ajax() {
		$response = array();
		$response['draw'] = 1;
		$response['recordsTotal'] = 0;
		$response['recordsFiltered'] = 0;
		$response['data'] = array();
		try {
			$data = $this->input->post();
			if(empty($data)) {
				echo json_encode($response); die;
			} else {
				$search = !empty($this->input->post('search'))? $this->input->post('search')['value'] : '';
				$limit  = (!empty($data['length'])?$data['length']:10);
				$offset = (!empty($data['start'])?$data['start']:0);
   				$result = $this->SeoModel->getSeoInforation($limit,$offset,$search);
   				$response['draw'] = (!empty($data['draw'])?$data['draw']:1);
				$response['recordsTotal'] = (!empty($result['total_count'])?$result['total_count']:0);
				$response['recordsFiltered'] = (!empty($result['total_count'])?$result['total_count']:0);
				$response['data'] = (!empty($result['fetch_data'])?$result['fetch_data']:array());
				echo json_encode($response); die;
			}
		} catch (Exception $e) {
			echo json_encode($response); die;
		}
	}

	public function edit_seo(){
		$id = $this->uri->segment('3');
		if(empty($id)) {
			redirect('seo/viewseo');
		}
		$res = $this->SeoModel->getSeoDetailsById($id);
		$data['fetch_data'] 	= 	$res;
		$data["page_title"]		=	"Edit seo Details";
        $page["layout_content"]	=	$this->load->view('pages/seo/editSeo', $data, true);
        $this->load->view('layouts/datatable_layout', $page);
	}

	public function updateSeo(){
		$submit = $this->input->post();
		if(!empty($submit)) {
			$check_seo_data 		= seo_meta_information($submit['page_name'],$submit['page_url']);
	     	$seo_multi_meta_info 	= seo_multi_meta_information($submit['page_name'],$submit['page_url']); 
	   		$seo_social_og_info  	= seo_social_ogInfo($submit['page_name'],$submit['page_url']); 

	     	if(!empty($check_seo_data)){
	     		$delete_data =  $this->SeoModel->hardDelete('seo_meta_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}
	     	if(!empty($seo_multi_meta_info) && empty($submit['meta_name'])){
	     		$delete_data =  $this->SeoModel->hardDelete('seo_multiple_meta_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}else if(empty($submit['meta_name'])){
	     		$delete_data =  $this->SeoModel->hardDelete('seo_multiple_meta_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}else {
	     		$delete_data =  $this->SeoModel->hardDelete('seo_multiple_meta_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}

	     	if(!empty($seo_social_og_info) && empty($submit['social_meta_og_property'])){
	     		$delete_data =  $this->SeoModel->hardDelete('seo_social_og_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}else if($submit['social_meta_og_property']){
	     		$delete_data =  $this->SeoModel->hardDelete('seo_social_og_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	} else {
	     		$delete_data =  $this->SeoModel->hardDelete('seo_social_og_info',array('page_name'=>$submit['page_name'],'page_url'=>$submit['page_url']));
	     	}

			$seo_arr = array(
                'meta_title' 			=> $submit['meta_title'],
                'meta_description' 		=> $submit['meta_description'],
                'canonical_url' 		=> $submit['canonical_url'],
                'page_name' 			=> $submit['page_name'],
                'page_url' 				=> $submit['page_url'],
                'seo_page_type' 		=> 'others'
	        ); 

			$insert =	$this->SeoModel->insert('seo_meta_info',$seo_arr);
			if(isset($submit['meta_name'][0]) && trim($submit['meta_name'][0]) !=''){
				foreach ($submit['meta_name'] as $key => $value) {
					$finalArr[] = array(
	                    'meta_name' 	=> $value,
	                    'meta_content' 	=> $submit['meta_content'][$key],
	                    'page_name' 	=> $submit['page_name'],
	                    'page_url' 		=> $submit['page_url'],
	                    'seo_meta_id' 	=> $insert,
	            	); 
				}
				$insert_meta = $this->db->insert_batch('seo_multiple_meta_info', $finalArr);
			}
			if(isset($submit['social_meta_og_property'][0]) && trim($submit['social_meta_og_property'][0]) !=''){
				foreach ($submit['social_meta_og_property'] as $key1 => $og_value) {
					$finalArr2[] = array(
						'social_meta_og_property' 	=> $og_value,
	                	'social_meta_og_content' 	=> $submit['social_meta_og_content'][$key1],
	                	'page_name' 				=> $submit['page_name'],
	                    'page_url' 					=> $submit['page_url'],
	                    'seo_meta_id' 				=> $insert,
	                );    
				}
				
				$insert_meta_og = $this->db->insert_batch('seo_social_og_info', $finalArr2);
			}
			if($insert) {
				$this->session->set_flashdata('seo_success','Data added successfully');
				redirect('seo/viewseo');
			} else {
				$this->session->set_flashdata('seo_error','Failed to add data');
				redirect('seo/edit_seo/'.$submit['id']);
			}
		}
	}

	public function deleteSeoContent(){
		$submit= $this->input->post();
		if(!empty($submit)){
			$delete_data = $this->SeoModel->hardDelete('seo_meta_info',array('id'=>base64_decode($submit['id'])));
			$delete_data2 = $this->SeoModel->hardDelete('seo_multiple_meta_info',array('seo_meta_id'=>base64_decode($submit['id'])));
			$delete_data3 = $this->SeoModel->hardDelete('seo_social_og_info',array('seo_meta_id'=>base64_decode($submit['id'])));

			$this->session->set_flashdata('seo_success','Data delete successfully');
			echo json_encode(array('status'=>TRUE,'message'=>'Successfully deleted'));
			redirect('seo/viewseo');
		}
	}

}

/* End of file Seo.php */
/* Location: ./application/controllers/Seo.php */ ?>