<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Tillcode_Controller extends MY_Controller {
		
		function __construct(){
			parent::__construct();
			$this->is_logged_in();
			$this->load->model("Tillcode_model");
		}
		
		
		function index() {
			$data['head'] = 'template/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'tillcode/v_content';
			$data['right_menu'] = 'tillcode/v_right_menu';
			$data['footer'] = 'tillcode/v_footer';
			
			$this->load->view('tillcode/v_tillcode', $data);
		}

		public function datatable() {
	        $jsonArray = $this->datatable->datatableJson(array(
	            'tillcode' => 'string',
	            'article_code' => 'string',
	            'disc_label' => 'string',
	            'brand_desc' => 'string',
	            'disc1' => 'string',
	            'disc2' => 'string',
	            'disc3' => 'string',
	            'is_sp' => 'string'
	        ));
	        $this->output->set_header("Pragma: no-cache");
	        $this->output->set_header("Cache-Control: no-store, no-cache");
	        $this->output->set_content_type('application/json')->set_output(json_encode($jsonArray));
	    }
		
		function all_list() {
			$data['menu_active'] = 'dcjq-parent active';
			$data['menu_tillcode_active'] = 'color:#FFF';

			$data['head'] = 'tillcode/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'tillcode/v_all_list';
			$data['right_menu'] = 'tillcode/v_right_menu';
			$data['footer'] = 'template/v_footer';
			
			$data['list'] = $this->Tillcode_model->all_list();
			$this->load->view('tillcode/v_tillcode', $data);
		}
		
		function add() {
			$data['menu_active'] = 'dcjq-parent active';
			$data['menu_tillcode_active'] = 'color:#FFF';

			$data['head'] = 'tillcode/v_head';
			$data['top_menu'] = 'template/v_top_menu';
			$data['left_menu'] = 'template/v_left_menu';
			$data['content'] = 'tillcode/v_add_new';
			$data['right_menu'] = 'tillcode/v_right_menu';
			$data['footer'] = 'template/v_footer';
			
			$this->load->view('tillcode/v_tillcode', $data);
		}
		
		// added by jerry@22-Oct-15
		function update() {
			$usr = $this->session->userdata['event_logged_in']['username'];
			$upd = date("Y-m-d H:i:s");
			
			$inputs = $this->input->post();
			$ret = $this->Tillcode_model->update($inputs["tillcode"], $inputs["disc1"], $inputs["disc2"], $inputs["disc3"], $inputs["issp"], $usr, $upd);
			if ($ret)
				echo "success";
			else
				echo "failed";
		}
		
		
	}

?>