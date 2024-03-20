<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function validatePostData($data=array()) {
    if(!empty($data)) {
        $secureData = $currentData = array();
        foreach ($data as $key => $value) {
            if(!empty($value)) {
                $currentValue       =   strip_tags($value);
               // $currentValue       =   htmlentities($currentValue);
                // $currentValue       =   escapeshellcmd($currentValue);
                $currentData[$key]  =   $currentValue;    
            }
        }
        
        $secureData[]   =   $currentData;

        if(!empty($secureData)) { return current($secureData); }
        else { throw new Exception("Error Processing Request"); }
    } else {
        throw new Exception("Error Processing Request");
    }
}

function validateBase64($str) {
    if (base64_encode(base64_decode($str, true)) === $str){
        return true;
    } else {
        return false;
    }
}

function fetchParentMenu(){
    $CI=& get_instance();
    if($_SESSION['adminsessiondetails']['id'] == 1 ){
        $query = $CI->db->select('parent_id,menu_name,menu_icon,menu_url,segment,id')->from('menu_master')->where(array('parent_id'=>0,'status'=>1,'level'=>1))->order_by("orderby_parent", "asc")->get();
        if ($query->num_rows() > 0) {
                return $query->result_array();
        } else {
            return false;
        }
    }else{
        $CI->db->select("m_tbl.parent_id,m_tbl.menu_name,m_tbl.menu_icon,m_tbl.menu_url,m_tbl.segment,m_tbl.id, pv_tbl.permissionType");
        $CI->db->from('menu_master m_tbl');
        $CI->db->join('menu_previllage_tbl pv_tbl','pv_tbl.parent_menu_id = m_tbl.id','left');
        $CI->db->where(array('m_tbl.parent_id'=>0,'m_tbl.status'=>1,'m_tbl.level'=>1,'pv_tbl.admin_user_id'=>$_SESSION['adminsessiondetails']['id']));
        $CI->db->order_by("orderby_parent", "asc");
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
                return $query->result_array();
        } else {
            return false;
        }
    }
    
}
function fetchSubmenu($parentId){
    $CI=& get_instance();
    $query = $CI->db->select('parent_id,menu_name,menu_icon,menu_url,segment,id')->from('menu_master')->where(array('parent_id'=>$parentId,'status'=>1,'level'=>2))->order_by("orderby_sub", "asc")->get();

    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return false;
    }
}


function fetchMicromenu($submenuId){
    $CI=& get_instance();
    $query = $CI->db->select('parent_id,submenu_id,menu_name,menu_icon,menu_url,segment,id,method_name')->from('menu_master')->where(array('submenu_id'=>$submenuId,'status'=>1 ,'level'=>3))->get();
    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return false;
    }
}

function fetchUserName($uid){
    $CI=& get_instance();
    $query = $CI->db->select('name')->from('auto_admin')->where(array('id'=>$uid,'status'=>'A'))->get();
    if ($query->num_rows() > 0) {
        return $query->result_array();
    } else {
        return false;
    }
}
function create_url($text, string $divider = '-'){

  // replace non letter or digits by divider
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, $divider);

  // remove duplicate divider
  $text = preg_replace('~-+~', $divider, $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

    return $text;
}


function getCompanyNameByCompanyId($company_id){
    $CI=& get_instance();
    $CI->db->select("id,company_name,name");
    $CI->db->from('vendor_info');
    $CI->db->where(array('status'=>'A','id'=>base64_decode($company_id)));
    $query = $CI->db->get();
    $fetch_data = $query->result_array();
    return  $fetch_data;
}

function getSubMenuInfo($id,$parentId){
    $CI=& get_instance();
    $CI->db->select("pv_tbl.id, pv_tbl.admin_user_id, pv_tbl.parent_menu_id, pv_tbl.sub_menu,pv_tbl.permissionType");
    $CI->db->from('menu_previllage_tbl pv_tbl');
    $CI->db->where(array('pv_tbl.status'=>1,'pv_tbl.admin_user_id' =>base64_decode($id),'parent_menu_id'=>$parentId));
    $query = $CI->db->get();
    $data  = $query->result_array();
    return $data;
}

function seo_meta_information($page_name,$page_url){
    $CI=& get_instance();
    $CI->db->select("id,page_name,page_url,canonical_url,meta_title,meta_tag,meta_description,status");
    $CI->db->from('seo_meta_info');
    $CI->db->where(array('status'=>'A','page_name'=>$page_name,'page_url'=>$page_url));
    $query = $CI->db->get();
    $fetch_data = $query->result_array();
    return  $fetch_data;
}

function seo_multi_meta_information($page_name,$page_url){
    $CI=& get_instance();
    $CI->db->select("`id`,`page_name`,`page_url`,`meta_content`,`meta_name`,`status`");
    $CI->db->from('seo_multiple_meta_info');
    $CI->db->where(array('status'=>'1','page_name'=>$page_name,'page_url'=>$page_url));
    $query = $CI->db->get();
    $fetch_data = $query->result_array();
    return  $fetch_data;
}

function seo_social_ogInfo($page_name,$page_url){
    $CI=& get_instance();
    $CI->db->select("`id`,`page_name`,`page_url`,`social_meta_og_property`,`social_meta_og_content`,`status`");
    $CI->db->from('seo_social_og_info');
    $CI->db->where(array('status'=>'1','page_name'=>$page_name,'page_url'=>$page_url));
    $query = $CI->db->get();
    $fetch_data = $query->result_array();
    return  $fetch_data;
}

function getXmlFilesList($folderPath) {
    $xmlFiles = array();
    if (is_dir($folderPath)) {
        if ($dirHandle = opendir($folderPath)) {
            while (false !== ($file = readdir($dirHandle))) {
                $filePath = $folderPath . $file;
                if (is_file($folderPath . $file) && pathinfo($file, PATHINFO_EXTENSION) == 'xml') {
                    $xmlFiles[] = array(
                        'file' => $file,
                        'creation_time' => date('Y-m-d H:i:s', filectime($filePath))     // Creation time
                    );
                }
            }
            closedir($dirHandle);
        }
    }

    return $xmlFiles;
}
?>