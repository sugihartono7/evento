<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	date_default_timezone_set('Asia/Jakarta');		

	// get subclass for public controller
	// require(APPPATH.'libraries/Public_Controller.php');	
	
	class MY_Controller extends CI_Controller {
		

		function __construct(){
			parent::__construct();
			$this->load->library('Datatable', array('model' => 'tillcode_model', 'rowIdCol' => 'tillcode'));
		}
		
		function is_logged_in(){
			if(!isset($this->session->userdata['event_logged_in']['username']) || $this->session->userdata['event_logged_in']['username'] != true) {
				redirect("login");
			}
		}
		
		public function to_dMY($date){
			$fmt = date('d M Y', strtotime($date));
			return $fmt;
		}

		public function to_date($date){
			$fmt = date('d', strtotime($date));
			return $fmt;
		}

		public function to_dM($date){
			$fmt = date('d M', strtotime($date));
			return $fmt;
		}
		
		public function my_404(){
			$this->load->view('errors/html/error_test');
		}

		public function my_message($msg){
			$duplicateNumber = $this->session->userdata("duplicateNumber");
			
			switch ($msg) {
				//case '1' : $ret = "<div class='alert alert-success'><a href='#' data-dismiss='alert' class='close'>×</a><span id='alertMessage'>operation success .</span></div>"; break;
				case '1' : $ret = "<div class='alert alert-success'><a href='#' data-dismiss='alert' class='close'>×</a><span style='font-size: 14px;' id='alertMessage'>Data berhasil diduplikasi dengan nomor: " . $duplicateNumber .  ".</span></div>"; break;
				case '2' : $ret = "<div class='alert alert-danger'><a href='#' data-dismiss='alert' class='close'>×</a><span id='alertMessage'>error on operation .</span></div>"; break;
				default  : $ret = "";
			}	
			
			return $ret;
		}
		
	}
?>