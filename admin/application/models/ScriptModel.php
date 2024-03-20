<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScriptModel extends CI_Model {
    public function uploadData()
    {
        $count=0;
        $fp = fopen($_FILES['userfile']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024))
        {
            for($i = 0, $j = count($csv_line); $i < $j; $i++)
            {
               $micro_cat = $csv_line[0];

                $micro_slug = str_replace(' ', '-', strtolower($micro_cat));
            }
            $data = array(
                'parent_cat_id' => 26,
                'sub_cat_id'    => 4747,
                'category_name' => $micro_cat,
                'url_slug'      => $micro_slug,
                'cat_level'     => 3
               );
            $data['crane_features']= $this->db->insert('auto_category', $data); 
        }
        echo 'successfully inserted';
        // fclose($fp) or die("can't close file");
        // $data['success']="success";
        // return $data;
    }
    

}

/* End of file ScriptModel.php */
/* Location: ./application/models/ScriptModel.php */