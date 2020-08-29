<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	require_once APPPATH.'libraries/Datatable.php';	

	class Tillcode_model extends CI_Model implements DatatableModel {
		
		function __construct(){
			date_default_timezone_set("Asia/Jakarta");
		}
		
		public function appendToSelectStr() {
	        return null;
	    }

		public function fromTableStr() {
	        return 'mst_tillcode';
	    }

	    public function joinArray() {
	        return NULL;
	    }
	    
	    public function whereClauseArray() {
	        return array(
                'is_active' => 1 
                );
	    }

		// modified by jerry@22-Oct-15
		function all_list(){	
			/*
			$sql = "SELECT a.tillcode, a.disc_label, a.created_date, a.article_code, b.brand_desc
					FROM mst_tillcode a JOIN mst_brand b ON(a.brand_code=b.brand_code)
					WHERE a.is_active='1' 
				";
			*/
			
			$sql = "select tillcode, disc_label, to_char(created_date, 'dd Mon yyyy') created_date, article_code, brand_desc, disc1, disc2, disc3, is_sp from mst_tillcode where is_active = 1";
			
			$ambil = $this->db->query($sql);
			if ($ambil->num_rows() > 0){
				foreach ($ambil->result() as $data){
					$hasil[] = $data;
				}
				return $hasil;
			}
			
		}
		
		function all_list_ajx(){	
			$this->db->select('kode_brand, tillcode, description, diskon_supplier, diskon_yogya'); 
			$this->db->from('mst_tillcode'); 

			$ambil = $this->db->get();
			if ($ambil->num_rows() > 0){
				/* foreach ($ambil->result() as $data){
					$hasil[] = $data;
				} */
				return $ambil;
			}
			
		}
		
		function add_new($username){
			$kode = $this->input->post('txt_kode');
			$nama = $this->input->post('txt_nama');
			$active = '1';
			$date = date("Y-m-d H:i:s");
			
			$data = array(
					   'kode' => $kode ,
					   'nama' => $nama,
					   'active' => $active,
					   'create_by' => $username,
					   'create_at' => $date,
					   'update_by' => '',
					   'update_at' => null
					);
			
			$this->db->insert('mst_supplier', $data);
			
		}

		function cek_tillcode($kode){
			$q = $this->db->query("select tillcode from mst_tillcode where tillcode='$kode' ");
			
			if ($q->num_rows() >= 1){
				$ada = 1;
			}else $ada = 0;

			return $ada;
		}

		function show_modal($kode){
			$sql = "SELECT kode, nama, active from mst_supplier WHERE kode='$kode' ";
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0){
				return $query;
			}
		}

		function edit($username){
			$kode = $this->input->post('txt_kode_show');
			$nama = $this->input->post('txt_nama_show');
			$active = $this->input->post('rb_active_show');

			$update_at = date("Y-m-d H:i:s");

			$data = array(
						'nama' => $nama,
						'active' => $active,
						'update_by' => $username,
						'update_at' => $update_at
					);
			$this->db->where('kode', $kode);
			$this->db->update('mst_supplier',$data);
		}

		function get_nextval(){
			$this->db->select("nextval('mst_tillcode_id_seq') as next_value");
			$q = $this->db->get()->row();
			$r = $q->next_value;
			return $r;
		}

		function get_currval(){
			$this->db->select("currval('mst_tillcode_id_seq') as curr_value");
			$q = $this->db->get()->row();
			$r = $q->curr_value;
			return $r;
		}
		
		// added by jerry@22-Oct-15
		function update($tillcode, $disc1, $disc2, $disc3, $issp, $usr, $upd) {
			$params = array($disc1, $disc2, $disc3, $issp, $usr, $upd, $tillcode);
			
			$sql = "update mst_tillcode set disc1 = ?, disc2 = ?, disc3 = ?, is_sp = ?, updated_by = ?, updated_date = ? where tillcode = ?";
			return $this->db->query($sql, $params);
		}


	}
?>