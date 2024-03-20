<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryModel extends CI_Model {

	/**
	 * Insert record based on table name and post data array. 
	*/
	public function insert($table, $post_data=array()){
		$this->db->insert($table,$post_data);
		$insertId = $this->db->insert_id();
		return $insertId;
	}

	/**
	 * Update record based on table name, value to be update in udpateArr array and condition
	 * that will be placed in condition array
	*/
	public function update($table = '', $updateArr = array() ,$condition = array()) {
		if(empty($table) || empty($updateArr) || empty($condition)) {
			return false;
		}
		$update = $this->db->update($table,$updateArr,$condition);
		return $update;
	}

	Public function check_Category_name($cat_name){
		$this->db->select("catN.category_name");
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.category_name'=>$cat_name,'catN.status'=>'A'));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	Public function checkUrlSlug($url_slug){
		$this->db->select("catN.url_slug");
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.url_slug'=>$url_slug));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}



	public function getAllcatDetails($limit = 10, $offset = 0, $search='') {
		
		$this->db->select("SQL_CALC_FOUND_ROWS catN.category_name, catN.category_image, date_format(catN.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, IF(catN.status = 'A','Active','Inactive') AS `status`,catN.id",FALSE);
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.parent_cat_id'=>0,'catN.status !='=>'D','catN.cat_level'=>1));
		if (!empty($search)) {
 			$this->db->like('catN.category_name', $search, 'after');
		}
		$this->db->order_by('catN.id','ASC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

        $countQuery 		 = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        return $data;
	}	

	/*	Change Category status */
	public function changeCatStatus($data) {

		$sql = "UPDATE auto_category SET status =  ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['cat_id'])));
		return $query;
	}

	/*	Change MCategory status */
	public function change_m_CatStatus($data) {

		$sql = "UPDATE auto_category SET status =  ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE id = ?";
		$query = $this->db->query($sql, array(base64_decode($data['cat_id'])));
		if($query){
			$sql2 = "UPDATE vendor_product_mapping SET status =  ( CASE WHEN STATUS = 1 THEN 0 ELSE 1 END ) WHERE autogorila_micro_cat_id = ?";
			$query2 = $this->db->query($sql2, array(base64_decode($data['cat_id'])));
		}
		return $query;
	}

	/*	get category details by id */
	public function getCatDetailsById($id) {
		$this->db->select("catN.category_name, date_format(catN.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, catN.category_image,catN.url_slug, catN.footer_content,catN.category_content");
		$this->db->from('auto_category catN');
		$this->db->where(array('id'=>base64_decode($id)));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}



	public function updateCatDetails($data,$id) {
		$this->db->set($data);
		$this->db->where('id', base64_decode($id));
		$res = $this->db->update('auto_category');
		return $res;
	}

	public function deleteCatDetails($data) {
		$this->db->set('status', 'D');
		$this->db->where('id', base64_decode($data['cat_id']));
		$res = $this->db->update('auto_category');
		if($res){
			$this->db->set('status', 'D');
			$this->db->where('parent_cat_id', base64_decode($data['cat_id']));
			$res = $this->db->update('auto_category');

			$this->db->select("product_id");
			$this->db->from('vendor_product_mapping');
			$this->db->where(array('autogorila_parent_cat_id' => base64_decode($data['cat_id']),'status'=>1));
			$query = $this->db->get();
	        $checkProductMapping = $query->num_rows();
	        if($checkProductMapping > 0){
				// delete Product from mapping table
				$this->db->where('autogorila_parent_cat_id', base64_decode($data['cat_id']));
	    		$this->db->delete('vendor_product_mapping');
	    	}
		}
		return $this->db->affected_rows();
	}
	public function deletemCatDetails($data) {

		$this->db->select("autogorila_micro_cat_id");
		$this->db->from('vendor_product_mapping');
		$this->db->where(array('autogorila_micro_cat_id' => base64_decode($data['cat_id']),'status'=>1));
		$query = $this->db->get();
        $checkProductMapping = $query->num_rows();
       	if($checkProductMapping > 0){
			// delete Product from mapping table
			$this->db->where('autogorila_micro_cat_id', base64_decode($data['cat_id']));
			$this->db->delete('vendor_product_mapping');
			
			$this->db->where('id', base64_decode($data['cat_id']));
			$this->db->delete('auto_category');
    			
	    }else{
	    	$this->db->where('id', base64_decode($data['cat_id']));
			$this->db->delete('auto_category');
	    }
	    return $this->db->affected_rows();

	}

	public function deleteSubCatDetails($data) {
		/*fetch ParentId By subcat Id */
		$this->db->select("catN.parent_cat_id");
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.id'=>base64_decode($data['cat_id']),'catN.status '=>'A'));
		$query = $this->db->get();
        $pcatdata = $query->result_array();

		$this->db->set('status', 'D');
		$this->db->where('id', base64_decode($data['cat_id']));
		$res = $this->db->update('auto_category');
		if($res){
			// Delete micro cat 
			$this->db->set('status', 'D');
			$this->db->where('sub_cat_id', base64_decode($data['cat_id']));
			$res = $this->db->update('auto_category');

			$this->db->select("product_id");
			$this->db->from('vendor_product_mapping');
			$this->db->where(array('autogorila_sub_cat_id' => base64_decode($data['cat_id']),'status'=>1));
			$query = $this->db->get();
	        $checkProductMapping = $query->num_rows();
	        if($checkProductMapping > 0){
				// delete Product from mapping table
				$this->db->where('autogorila_sub_cat_id', base64_decode($data['cat_id']));
	    		$this->db->delete('vendor_product_mapping');
	    	}

		}
		return $this->db->affected_rows();
	}

	public function getDetails($tablename,$condition=array()) {
		$this->db->select("$tablename.*");
		$this->db->from($tablename);
		if (!empty($condition)) {
			$this->db->where($condition);
		}
		$query=$this->db->get();
		$result=$query->result_array();
		return $result;
	}

	/** Sub catgeory details */
	public function getAllSubCatDetails($limit = 10, $offset = 0, $search='') {
		$this->db->select("SQL_CALC_FOUND_ROWS catN.category_name, date_format(catN.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, catN.parent_cat_id, catN.id, catN.category_image ,catN.url_host,catN.url_slug, CONCAT(catN.url_host,catN.url_slug) AS url_name ,IF(catN.status = 'A','Active','Inactive') AS `status`,catN.related_category",FALSE);
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.status !='=>'D','catN.cat_level='=>'2'));
		if (!empty($search)) {
 			$this->db->like('catN.category_name', $search, 'after');
 			//$this->db->or_like('bsc.subcategory_name', $search, 'after');
		}
		$this->db->order_by('catN.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data'] = $query->result_array();
        $finalArr =[];

        foreach ($data['fetch_data'] as $rkey => $value) {
        	$this->db->select('c.category_name,c.id,c.parent_cat_id');
			$this->db->from('auto_category c');
			$this->db->where(array('c.status'=>'A', 'c.cat_level='=>'1','c.id'=>$value['parent_cat_id']));
			$query = $this->db->get();
			$data['fetch_parent_data'] = $query->result_array();
			// echo "<pre>";
			// print_r ($data['fetch_parent_data']);
			// echo "</pre>";
        	$parent_categories = '';
        	$explod_parant_cat = explode(',',$value['related_category']);
        	foreach ($explod_parant_cat as $key => $p_value) {
        		$this->db->select('c.category_name,c.id,c.related_category');
				$this->db->from('auto_category c');
				$this->db->where(array('c.status'=>'A', 'c.cat_level='=>'1','c.id'=>$p_value));
				$query = $this->db->get();
				$data['fetch_related_parent_data'] = $query->result_array();
				if(empty($parent_categories)) {
					$parent_categories = !empty($data['fetch_related_parent_data'][0]['category_name'])?$data['fetch_related_parent_data'][0]['category_name']:0;
				} else {
					$parent_categories = $parent_categories.', '.$data['fetch_related_parent_data'][0]['category_name'];
				}
        	}
        	$data['fetch_data'][$rkey]['related_categories_name'] = $parent_categories;
        	if(!empty($data['fetch_parent_data'][0]['category_name'])){
        		$data['fetch_data'][$rkey]['parent_categories_name']  = $data['fetch_parent_data'][0]['category_name'];
        	}
        }
        return $data;
	}


	public function getCategoryName(){
		$this->db->select("catN.category_name, catN.id",FALSE);
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.parent_cat_id'=>0,'catN.status '=>'A','catN.cat_level '=>'1'));
		$query = $this->db->get();
        $data = $query->result_array();
        return $data;
	}

/*	get sub category details by id */

	public function getSubCatDetailsById($id) {
		$this->db->select("cat.category_name, cat.id, date_format(cat.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, cat.parent_cat_id,cat.category_image, cat.url_host,cat.url_slug, CONCAT(cat.url_host,cat.url_slug) AS url_name,cat.footer_content, cat.category_content,cat.related_category");
		$this->db->from('auto_category cat');
		$this->db->where(array('cat.id'=>base64_decode($id), 'cat.cat_level'=>'2'));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}


	public function fetchSubCategoryName(){
		$this->db->select("catN.category_name, catN.id",FALSE);
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.cat_level'=>2,'catN.status '=>'A'));
		$query = $this->db->get();
        $data = $query->result_array();
        return $data;	
	}

	public function updateSeoInfo($data,$page_name) {
		$this->db->set($data);
		$this->db->where('page_name', $page_name);
		$res = $this->db->update('seo_meta_info');
		return $res;
	}


	public function getAllSubcategoryById($catId){
		$this->db->select("catN.category_name, catN.id as cat_id,catN.parent_cat_id",FALSE);
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.cat_level'=>2,'catN.status '=>'A','catN.parent_cat_id'=>$catId));
		$query = $this->db->get();
        $data = $query->result_array();
        return $data;
	}

	public function getAllParentCat($catId){
		//echo $catId;
		$this->db->select("catN.category_name, catN.id as cat_id,catN.parent_cat_id",FALSE);
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.cat_level'=>1,'catN.parent_cat_id'=>0,'catN.status '=>'A','catN.id !='=>$catId));
		$query = $this->db->get();
        $data = $query->result_array();
        return $data;
	}

	public function getAllrelateParenById($sub_catId,$parent_cat_id){
		$data = array();
		$this->db->select("catN.category_name, catN.id as cat_id,catN.parent_cat_id,catN.related_category, catN.sub_cat_id");
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.cat_level'=>2,'catN.parent_cat_id'=>$parent_cat_id,'catN.status '=>'A','catN.id '=>$sub_catId));
		$query = $this->db->get();
        $data_1 = $query->result_array();
        $related_cat = explode(',', $data_1[0]['related_category']);
        foreach ($related_cat as $key => $value) {
        	$this->db->select("catN.category_name, catN.id as cat_id,catN.parent_cat_id,catN.related_category");
			$this->db->from('auto_category catN');
			$this->db->where(array('catN.id'=>$value,'catN.status '=>'A'));
			$query = $this->db->get();
	        $get_related_cat = $query->result_array();
	        if(!empty($get_related_cat)){
       			$get_cat = array_push($data,$get_related_cat[0]);
       		}
        }
       	  	
        return $data;
	}

	public function getAllrelateSubCatById($cat_id){
		$data = array();
		$this->db->select("catN.category_name, catN.id as cat_id,catN.parent_cat_id,catN.related_category");
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.parent_cat_id'=>$cat_id,'catN.status '=>'A','catN.cat_level'=>2));
		$query = $this->db->get();
        $data = $query->result_array();
        return $data;
	}

	public function getAllMicroCatDetails($limit = 10, $offset = 0, $search=''){
		$this->db->select("SQL_CALC_FOUND_ROWS catN.category_name, date_format(catN.created_at,'%d-%m-%y %h:%i:%s %p') as created_at, catN.parent_cat_id, catN.id, catN.category_image ,catN.url_host,catN.url_slug, CONCAT(catN.url_host,catN.url_slug) AS url_name ,IF(catN.status = 'A','Active','Inactive') AS `status`,catN.related_category, catN.sub_cat_id,catN.related_parent_id,",FALSE);
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.status !='=>'D','catN.cat_level='=>'3'));
		if (!empty($search)) {
			$this->db->group_start();
 			$this->db->like('catN.category_name', $search, 'after');
 			$this->db->group_end();
 			//$this->db->or_like('bsc.subcategory_name', $search, 'after');
		}
		$this->db->order_by('catN.id','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();

        $countQuery = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
        $data['total_count'] = $countQuery->row()->Count;
        $data['fetch_count'] = $query->num_rows();
        $data['fetch_data']  = $query->result_array();
        $finalArr =[];
        foreach ($data['fetch_data'] as $rkey => $value) {
        	$related_category = explode(',', $value['related_category']);
        	$parent_categories = '';
        	foreach ($related_category as $key => $val) {
        		$this->db->select('c.category_name,c.id,c.related_category');
				$this->db->from('auto_category c');
				$this->db->where(array('c.status'=>'A','c.id'=>$val));
				$query = $this->db->get();
				$data['fetch_related_cat_data'] = $query->result_array();
				if(empty($parent_categories)) {
					$parent_categories = !empty($data['fetch_related_cat_data'][0]['category_name'])?$data['fetch_related_cat_data'][0]['category_name']:'';
				} else {
					$parent_categories = $parent_categories.', '.$data['fetch_related_cat_data'][0]['category_name'];
				}
				
        	}
        	/* start fatching Parent category name */ 
        	$this->db->select("id,category_name,url_slug,category_image");
			$this->db->from('auto_category');
			$this->db->where(array('status'=>'A','id'=>$value['parent_cat_id'],'cat_level'=>'1'));
			
			$query = $this->db->get();
		    $fetch_parentCat_data = $query->result_array();
		   	if(!empty($fetch_parentCat_data)){
        		$data['fetch_data'][$rkey]['p_categories_name'] =  html_entity_decode($fetch_parentCat_data[0]['category_name']);
        		$data['fetch_data'][$rkey]['p_cat_img'] 		=  $fetch_parentCat_data[0]['category_image'];
        	}else{
        		$data['fetch_data'][$rkey]['p_categories_name'] =  '';
        	}

        	$this->db->select("id,category_name,url_slug,category_image");
			$this->db->from('auto_category');
			$this->db->where(array('status'=>'A','id'=>$value['sub_cat_id'],'cat_level'=>'2'));
			
			$query = $this->db->get();
			
		    $fetch_subCat_data = $query->result_array();
		   	if(!empty($fetch_subCat_data)){
        		$data['fetch_data'][$rkey]['sub_categories_name'] =  html_entity_decode($fetch_subCat_data[0]['category_name']);
        		$data['fetch_data'][$rkey]['sub_cat_img'] 		  =  $fetch_subCat_data[0]['category_image'];
        	}else{
        		$data['fetch_data'][$rkey]['sub_categories_name'] =  '';
        	}

        	$data['fetch_data'][$rkey]['related_categories_name'] = $parent_categories;
        }
        return $data;
	}

	public function getMicroCatDetailsById($id){
		$this->db->select("cat.category_name, cat.id, cat.created_at,cat.parent_cat_id,cat.category_image, cat.url_host,cat.url_slug, CONCAT(cat.url_host,cat.url_slug) AS url_name,cat.footer_content, cat.category_content,cat.related_category,cat.sub_cat_id,cat.related_parent_id");
		$this->db->from('auto_category cat');
		$this->db->where(array('cat.id'=>base64_decode($id), 'cat.cat_level'=>'3'));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}

	public function fetchSeoDataByUrl($page_name){ 			/*url_slug use here as a page name*/ 
		$this->db->select("seoT.status,seoT.page_name");
		$this->db->from('seo_meta_info seoT');
		$this->db->where(array('seoT.page_name'=>$page_name));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;	
	}

	/*	Change SEO status */
	public function changeSeoCatStatus($page_name) {
		$sql = "UPDATE seo_meta_info SET status =  ( CASE WHEN STATUS = 'A' THEN 'I' ELSE 'A' END ) WHERE page_name = ?";
		$query = $this->db->query($sql, array($page_name));
		return $query;
	}

	public function getCategoryContentDetails(){
		$this->db->select("ac.header_content, ac.footer_content, IF(ac.status = 'A','Active','Inactive') AS `status`,ac.id");
		$this->db->from('auto_all_category_content ac');
		$this->db->where(array('ac.status'=>'A'));
		$query = $this->db->get();
		$data['fetch_data'] = $query->result_array();
		return $data;
	}

	public function getAllCategoryContentDetailsById($id){
		$this->db->select("ac.header_content, ac.footer_content, IF(ac.status = 'A','Active','Inactive') AS `status`,ac.id");
		$this->db->from('auto_all_category_content ac');
		$this->db->where(array('ac.status'=>'A' ,'ac.id'=>base64_decode($id)));
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}


	public function updatecontentInfo($tablename,$data,$id) {
		$this->db->set($data);
		$this->db->where('id', $id);
		$res = $this->db->update($tablename);
		return $res;
	}


	public function uploadData()
    {
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
            	$city_id = $csv_line[0];
               	$area_name = $csv_line[1];
            }
            $data = array(
                'city_id' 			=> !empty($city_id)?$city_id:'',
                'area_name' 		=> !empty($area_name)?$area_name:'',
               );
            $data['crane_features']= $this->db->insert('auto_city_area', $data); 
        }
        echo 'successfully inserted';
    }

    public function exportPCategoryDetails(){
    	$this->db->select("cat.id,cat.category_name");
		$this->db->from('auto_category cat');
		$this->db->where(array('cat.status'=>'A', 'cat.cat_level'=>1 ,'cat.parent_cat_id'=>0));
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
    }
    public function exportSubCategoryDetails(){
    	$this->db->select("cat.id,cat.category_name");
		$this->db->from('auto_category cat');
		$this->db->where(array('cat.status'=>'A', 'cat.cat_level'=>2));
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
    }
    public function exportMicroCategoryDetails(){
    	$this->db->select("cat.id,cat.category_name");
		$this->db->from('auto_category cat');
		$this->db->where(array('cat.status'=>'A', 'cat.cat_level'=>3));
		$query = $this->db->get();
		$data  = $query->result_array();
		return $data;
    }
    
    public function hardDelete($table,$condition=array()){
		$this->db->where($condition);
    	$this->db->delete($table);
	}
    
    public function sitemapforParentmenu(){
		$this->db->select("catN.url_slug");
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.parent_cat_id'=>0,'catN.status '=>'A','catN.cat_level '=>'1','catN.category_type '=>'1'));
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}
	public function sitemapforSubMenu(){
		$this->db->select("catN.url_slug");
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.status '=>'A','catN.cat_level '=>'2','catN.category_type '=>'1'));
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}
	
	public function sitemapforMicroMenu(){
		$this->db->select("catN.url_slug");
		$this->db->from('auto_category catN');
		$this->db->where(array('catN.status '=>'A','catN.cat_level '=>'3','catN.category_type '=>'1'));
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
		$data = $query->result();
		return $data;
	}


}





/* End of file CategoryModel.php */
/* Location: ./application/models/CategoryModel.php */