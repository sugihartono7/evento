<?php
/**
 * Class   :  Excel
 * Author  :  
 * Created :  2016-03-25
 * Desc    :  Bridge for PhpExcel library
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
require_once APPPATH . "/third_party/PHPExcel.php";

class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}
