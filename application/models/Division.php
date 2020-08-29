<?php
/**
 * Class   :  Division
 * Author  :  
 * Created :  2015-08-26
 * Desc    :  Data handler for Division
 */

class Division extends CI_Model {
        
        public function __construct() {
        
        }
        
        public function loadAll($arrayMode = false) {	
                $sql = "select division_code, division_desc from mst_division where is_active = 1 order by division_code";
                $query = $this->db->query($sql);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
}