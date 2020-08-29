<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Brand_model extends CI_Model {
		
		function __construct(){
			
		}
		
		function all_list(){	
			$this->db->select('*'); 
			$this->db->from('mst_brand'); 
			
			$ambil = $this->db->get();
			if ($ambil->num_rows() > 0){
				foreach ($ambil->result() as $data){
					$hasil[] = $data;
				}
				return $hasil;
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
			
			$this->db->insert('mst_brand', $data);
			
		}

		function cek_kode($kode){
			$q = $this->db->query("select kode from mst_brand where LOWER(kode)='".strtolower($kode)."' ");
			
			if ($q->num_rows() >= 1){
				$ada = 1;
			}else $ada = 0;

			return $ada;
		}

		function show_modal($kode){
			$sql = "SELECT kode, nama, active from mst_brand WHERE kode='$kode' ";
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
			$this->db->update('mst_brand',$data);
		}

		


	}
?>