<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Event_model extends CI_Model {
		
		var $table = 'event a';
		// column_order harus mengikuti urutan pada view TH
		var $column_order = array(
									'a.id', '', 'a.event_no', 'a.propose_by', 
									'a.propose_brand', 'a.propose_notes',
									'a.about', 'a.created_date', 'a.updated_by'
								);

		var $column_search = array(
									'a.event_no', 'a.about', 'a.toward', 'tanggal',
									'a.propose_by', 'a.propose_notes', 'a.propose_brand',
									'a.created_by', 'a.updated_by'
								); 

		// var $order = array(
		// 					'a.id'
		// 				); 

		/************************************************* ajax *********************************************/
		public function _get_datatables_query($is_printed, $is_count_all, $is_md, $department, $division) {	
			if ($is_printed==1){
				$this->db->where("a.is_printed", "1");
			} 
			else if ($is_printed==0){
				$this->db->group_start(); 
				$this->db->where("a.is_printed", "0");
				$this->db->or_where("a.is_printed IS NULL ", NULL, FALSE);
				$this->db->group_end(); 
				# ZZZZZZ
				/*
				$date = strtotime(date('Y-m-01') .' -6 month');
				$thisYear = date('Y-m-d 00:00:00', $date);
				$this->db->where("a.created_date >= ", $thisYear);
				*/
				# ZZZZZZ
				$this->db->where("a.created_date >= ", "2020-06-01");
				# ZZZZZZ
				// $thisMonth = date('Y-m-d 00:00:00', strtotime(date('Y-m-01')));
				// $this->db->where("a.created_date >= ", $thisMonth);
			} 

			// is md
			$md_name = $this->session->userdata['event_logged_in']['md_name'];
			if ($is_md == 1){
				# ZZZZZZ
				// $this->db->join("event_item b", 'a.id=b.event_id');
				$this->db->join("event_item b", 'a.id = b.event_id', 'left');
				$this->db->where("a.first_signature", $md_name);
			}else {
				$this->db->join("event_item b", 'a.id=b.event_id', 'left');
			}

			// filter state
			if ($department != null){
				$this->db->where("a.department", $department);
			}

			if ($division != null){
				$this->db->where("a.division_code", $division);
			}

			$this->db->select("a.id, a.event_no, a.about, a.toward, a.is_printed,
								TO_CHAR(a.created_date, 'dd Mon yyyy') AS tanggal,
								a.is_same_date, a.is_same_location, 
								a.active, a.id_venditore, a.propose_by, a.propose_notes, a.propose_brand,
								a.created_by, a.updated_by");

			$this->db->from($this->table);
			$this->db->join("mst_template c", 'a.template_code=c.tmpl_code');
			$this->db->where("c.is_active", "1");
			$this->db->group_by("a.id, a.event_no, a.about, a.toward, a.is_printed");
			
			if ($is_count_all == 0) {
				$i = 0;
				foreach ($this->column_search as $item) {
					if($_POST['search']['value']) {
						if($i===0) {
							$this->db->group_start(); 
							$this->db->like("LOWER (".$item.")", strtolower($_POST['search']['value']));
						} else {
							if ($item == 'tanggal') {
	    						if ($_POST['search']['value']==date("d M Y", strtotime($_POST['search']['value']))) {
	    							$where = " TO_CHAR(a.created_date, 'dd Mon yyyy') = '".$_POST['search']['value']."' ";
									$this->db->or_where($where);
	    						}
							} 
							else 
								$this->db->or_like("LOWER (".$item.")", strtolower($_POST['search']['value']));
						}

						if(count($this->column_search) - 1 == $i) 
							$this->db->group_end(); 
					}
					$i++;
				}
				
				if(isset($_POST['order'])) {
					$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
				} 
				else if(isset($this->order)) {
					$order = $this->order;
					$this->db->ordera_by(key($order), $order[key($order)]);
				}
			}

		}

		function get_datatables($is_printed, $is_md, $department, $division) {
			$this->_get_datatables_query($is_printed, $is_count_all = 0, $is_md, $department, $division);
			if($_POST['length'] != -1)
				$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			return $query->result();
		}

		function count_filtered($is_printed, $is_md, $department, $division) {
			$this->_get_datatables_query($is_printed, $is_count_all = 0, $is_md, $department, $division);
			$query = $this->db->get();
			return $query->num_rows();
		}

		public function count_all($is_printed, $is_md, $department, $division) {
			$this->_get_datatables_query($is_printed, $is_count_all = 1, $is_md, $department, $division);
			$query = $this->db->get();
			return $query->num_rows();
		}

		/************************************************* end ajax *********************************************/

		public function get_nextval_event(){
			$this->db->select("nextval('event_seq') as next_value");
			$q = $this->db->get()->row();
			$r = $q->next_value;
			return $r;
		}

		public function get_nextval_tillcode_detail(){
			$this->db->select("nextval('tillcode_detail_id_seq') as next_value");
			$q = $this->db->get()->row();
			$r = $q->next_value;
			return $r;
		}

		function insert_tillcode_detail($dt){
			$this->db->insert('tillcode_detail', $dt); 
		}

		public function get_last_value_event(){
			$this->db->select("last_value as last_val");
			$this->db->from("event_seq");
			$q = $this->db->get()->row();
			$r = $q->last_val;
			return $r;
		}
		
		function all_list($is_printed){
			if ($is_printed==1){
				$where = " AND a.is_printed=1 ";	
				$sort = " DESC ";
			} else if ($is_printed==0){
				$where = " AND (a.is_printed=0 OR a.is_printed IS NULL) ";
				$sort = " ASC ";
			}else {
				$where = "";
				$sort = " DESC ";
			}

			$sql = "SELECT a.id, a.event_no, a.about, a.toward , a.is_printed,
					TO_CHAR(a.created_date, 'dd Mon yyyy') as created_date,
					a.is_same_date, a.is_same_location, a.active, a.id_venditore, a.propose_by, a.propose_brand, a.propose_notes, a.created_by, a.updated_by
					FROM event a 
					LEFT JOIN event_item b ON(a.id=b.event_id) 
					JOIN mst_template c ON(a.template_code=c.tmpl_code)
					WHERE c.is_active='1' $where
					GROUP BY a.id, a.event_no, a.about, a.toward, a.is_printed 
					ORDER BY a.id $sort
				";
			
			$ambil = $this->db->query($sql);
			if ($ambil->num_rows() > 0){
				foreach ($ambil->result() as $data){
					$hasil[] = $data;
				}
				return $hasil;
			}
			
		}

		function filter_list($unit_bisnis, $divisi, $is_printed){	
			if ($is_printed==1){
				$where = " AND a.is_printed=1 ";
				$sort = " DESC ";	
			} else if ($is_printed==0){
				$where = " AND (a.is_printed=0 OR a.is_printed IS NULL) ";
				$sort = " ASC ";
			}else {
				$where = "";
				$sort = " DESC ";
			}

			$sql = "SELECT a.id, a.event_no, a.about, a.toward , a.is_printed,
					TO_CHAR(a.created_date, 'dd Mon yyyy') as created_date,
					a.is_same_date, a.is_same_location, a.active, a.id_venditore, a.propose_by, a.propose_brand, a.propose_notes, a.created_by, a.updated_by
					FROM event a 
					LEFT JOIN event_item b ON(a.id=b.event_id) 
					JOIN mst_template c ON(a.template_code=c.tmpl_code)
					WHERE c.is_active='1'
					AND a.department='$unit_bisnis' AND a.division_code='$divisi' $where
					GROUP BY a.id, a.event_no, a.about, a.toward, a.is_printed 
					ORDER BY a.id $sort
				";
			
			$ambil = $this->db->query($sql);
			if ($ambil->num_rows() > 0){
				foreach ($ambil->result() as $data){
					$hasil[] = $data;
				}
				return $hasil;
			}
			
		}
		
		function all_list_by_md($is_printed){
			if ($is_printed==1){
				$where = " AND a.is_printed=1 ";	
				$sort = " DESC ";
			} else if ($is_printed==0){
				$where = " AND (a.is_printed=0 OR a.is_printed IS NULL) ";
				$sort = " ASC ";
			}else {
				$where = "";
				$sort = " DESC ";
			}

			$md_name = $this->session->userdata['event_logged_in']['md_name'];

			$sql = "SELECT a.id, a.event_no, a.about, a.toward , a.is_printed,
					TO_CHAR(a.created_date, 'dd Mon yyyy') as created_date,
					a.is_same_date, a.is_same_location, a.active, a.id_venditore, a.propose_by, a.propose_brand, a.propose_notes, a.created_by, a.updated_by
					FROM event a 
					JOIN event_item b ON(a.id=b.event_id) 
					JOIN mst_template c ON(a.template_code=c.tmpl_code)
					WHERE c.is_active='1' $where
					AND a.first_signature='$md_name'
					GROUP BY a.id, a.event_no, a.about, a.toward, a.is_printed
					ORDER BY a.id $sort
				"; 
			
			$ambil = $this->db->query($sql);
			if ($ambil->num_rows() > 0){
				foreach ($ambil->result() as $data){
					$hasil[] = $data;
				}
				return $hasil;
			}
			
		}
		
		function update_printed($id, $usr = null, $upd = null){
			$params = array($usr, $upd, $id);
			$sql = "UPDATE event SET is_printed = 1, updated_by = ?, updated_date = ? WHERE id = ?";
			$ambil = $this->db->query($sql, $params);
		}

		function get_preview(){	
			$sql = "SELECT id, event_no, about 
					FROM event 
					ORDER BY id DESC
					LIMIT 10 
				";
			$ambil = $this->db->query($sql);
			return $ambil->result();
		}

		function get_event_data($id){
			$sql = "SELECT * FROM event WHERE id='$id' ";
			
			$exe = $this->db->query($sql);
			$ret = $exe->row();
			$arr = array(
							$ret->event_no, $ret->about, $ret->purpose, $ret->attach,
							$ret->toward, $ret->department, $ret->division_code, $ret->source,
							$ret->template_code, $ret->first_signature, $ret->second_signature, $ret->approved_by,
							$ret->approved_date, $ret->notes, $ret->cc, $ret->created_by,
							$ret->created_date, $ret->updated_by, $ret->updated_date, $ret->is_manual_setting,
							$ret->letter_date, $ret->is_same_date, $ret->is_same_location, $ret->is_printed
						);
			return $arr;
		}
		
		function get_event_item_data($id){	
			$sql = "SELECT * FROM event_item WHERE event_id='$id' ";
			$ambil = $this->db->query($sql);
			return $ambil->result();
		}

		function get_event_same_date_data($id){	
			$sql = "SELECT * FROM event_same_date WHERE event_id='$id' ";
			$ambil = $this->db->query($sql);
			return $ambil->result();
		}

		function get_event_date_data($id){	
			$sql = "SELECT * FROM event_date WHERE event_id='$id' ";
			$ambil = $this->db->query($sql);
			return $ambil->result();
		}

		function get_event_same_location_data($id){	
			$sql = "SELECT * FROM event_same_location WHERE event_id='$id' ";
			$ambil = $this->db->query($sql);
			return $ambil->result();
		}

		function get_event_location_data($id){	
			$sql = "SELECT * FROM event_location WHERE event_id='$id' ";
			$ambil = $this->db->query($sql);
			return $ambil->result();
		}

		function makeLetterNumber($num, $div) {
            #9999/SA.YDS/YG.SB/07/2015
            
            $code = "";
            switch(strtoupper($div)) {
                    case "A":
                            $code = "A";
                            break;
                    case "B":
                            $code = "B";
                            break;
                    case "C":
                            $code = "C";
                            break;
                    case "D":
                            $code = "SB";
                            break;
                    case "E":
                            $code = "E";
                            break;
            }
            
            return $div . str_pad($num, 5, "0", STR_PAD_LEFT) . "/SA.YDS/YG." . $code . "/" . date("m") . "/" . date("Y");
        }

        function create_letter_num($divisionCode){
        	switch(strtoupper($divisionCode)) {
                        case "A":
                                $sql = "select nextval('letter_no_a_seq') seq";
                                $query = $this->db->query($sql);
                                if ($row = $query->row()) {
                                        $seqLetterNumber = $this->makeLetterNumber($row->seq, $divisionCode); 
                                }
                                break;
                        case "B":
                                $sql = "select nextval('letter_no_b_seq') seq";
                                $query = $this->db->query($sql);
                                if ($row = $query->row()) {
                                        $seqLetterNumber = $this->makeLetterNumber($row->seq, $divisionCode);    
                                }
                                break;
                        case "C":
                                $sql = "select nextval('letter_no_c_seq') seq";
                                $query = $this->db->query($sql);
                                if ($row = $query->row()) {
                                        $seqLetterNumber = $this->makeLetterNumber($row->seq, $divisionCode);      
                                }
                                break;
                        case "D":
                                $sql = "select nextval('letter_no_d_seq') seq";
                                $query = $this->db->query($sql);
                                if ($row = $query->row()) {
                                        $seqLetterNumber = $this->makeLetterNumber($row->seq, $divisionCode);     
                                }
                                break;
                        case "E":
                                $sql = "select nextval('letter_no_e_seq') seq";
                                $query = $this->db->query($sql);
                                if ($row = $query->row()) {
                                        $seqLetterNumber = $this->makeLetterNumber($row->seq, $divisionCode);     
                                }
                                break;  
                        case "S":
                                $sql = "select nextval('letter_no_s_seq') seq";
                                $query = $this->db->query($sql);
                                if ($row = $query->row()) {
                                        $seqLetterNumber = $this->makeLetterNumber($row->seq, $divisionCode);     
                                }
                                break;       
                }
                return $seqLetterNumber;
        }

		function duplicate($id){	
			$kode = $this->input->post('txt_kode');
			$nama = $this->input->post('txt_nama');
			$active = '1';
			$date = date("Y-m-d H:i:s");
			
			$now = date("Y-m-d");

			$dt = $this->get_event_data($id);
			$eventNo = $this->create_letter_num($dt[6]);

			$data = array(
					   'id' => $this->get_nextval_event(),
					   'event_no' => $eventNo,
					   'about' => $dt[1],
					   'purpose' => $dt[2],
					   'attach' => $dt[3],
					   'toward' => $dt[4],
					   'department' => $dt[5],
					   'division_code' => $dt[6],
					   'source' => $dt[7],
					   'template_code' => $dt[8],
					   'first_signature' => $dt[9],
					   'second_signature' => $dt[10],
					   'approved_by' => $dt[11],
					   'approved_date' => $dt[12],
					   'notes' => $dt[13],
					   'cc' => $dt[14], 
					   'created_by' => $this->session->userdata['event_logged_in']['username'],
					   'created_date' => $date,
					   'updated_by' => null,
					   'updated_date' => null,
					   'is_manual_setting' => $dt[19],
					   'letter_date' => $now,
					   'is_same_date' => $dt[21],
					   'is_same_location' => $dt[22],
					   'is_printed' => null,
					   'active' => '1'
					);
			
			$this->db->insert('event', $data);
			$new_id = $this->get_last_value_event();

			$res = $this->get_event_item_data($id);
			foreach ($res as $r) {
				$data = array(
						   'event_id' => $new_id,
						   'tillcode' => $r->tillcode,
						   'category_code' => $r->category_code,
						   'notes' => $r->notes,
						   'supp_code' => $r->supp_code,
						   'yds_responsibility' => $r->yds_responsibility,
						   'supp_responsibility' => $r->supp_responsibility,
						   'is_pkp' => $r->is_pkp,
						   'tax' => $r->tax,
						   'brutto_margin' => $r->brutto_margin,
						   'net_margin' => $r->net_margin,
						   'same_location' => $r->same_location,
						   'same_date' => $r->same_date,
						   'is_sp' => $r->is_sp,
						   'special_price' => $r->special_price,
						   'without_responsibility' => $r->without_responsibility
						);
				
				$this->db->insert('event_item', $data);
			}

			//cek date
			if($dt[21]=='1'){
				$res = $this->get_event_same_date_data($id);
				foreach ($res as $r) {
					$data = array(
							   'event_id' => $new_id,
							   'date_start' => $r->date_start,
							   'date_end' => $r->date_end
							);
					
					$this->db->insert('event_same_date', $data);
				}
			} 
			else {
				$res = $this->get_event_date_data($id);
				foreach ($res as $r) {
					$data = array(
							   'event_id' => $new_id,
							   'tillcode' => $r->tillcode,
							   'date_start' => $r->date_start,
							   'date_end' => $r->date_end
							);
					
					$this->db->insert('event_date', $data);
				}
			}

			//cek location
			if($dt[22]=='1'){
				$res = $this->get_event_same_location_data($id);
				foreach ($res as $r) {
					$data = array(
							   'event_id' => $new_id,
							   'store_code' => $r->store_code,
							   'location_code' => $r->location_code,
							   'notes' => $r->notes
							);
					
					$this->db->insert('event_same_location', $data);
				}
			} else {
				$res = $this->get_event_location_data($id);
				foreach ($res as $r) {
					$data = array(
							   'event_id' => $new_id,
							   'tillcode' => $r->tillcode,
							   'store_code' => $r->store_code,
							   'location_code' => $r->location_code,
							   'notes' => $r->notes
							);
					
					$this->db->insert('event_location', $data);
				}	
			}

			// return duplicate number
			return $eventNo;
		}


		function get_template($id){	
			/*$sql = "SELECT DISTINCT a.tmpl_code, a.tmpl_name, a.header, a.footer, a.notes AS template_notes,
					b.*,
					f.supp_desc, f.city, f.fax,
					g.*,
					h.md_title AS jabatan
					
					FROM mst_template a JOIN event b ON(a.tmpl_code=b.template_code) 
					JOIN event_item c ON(b.id=c.event_id)
					JOIN mst_tillcode d ON(c.tillcode=d.tillcode)
					
					JOIN mst_supplier f ON(c.supp_code=f.supp_code)
					JOIN mst_dmm g ON(b.division_code=g.div_code)
					
					JOIN mst_md h ON(b.division_code=h.div_code)
					
					WHERE b.id='$id' AND a.is_active='1' ";*/

			//edit gara2 eror gada tillcode		
			$sql = "SELECT DISTINCT a.tmpl_code, a.tmpl_name, a.header, a.footer, a.notes AS template_notes,
					b.*,
					f.supp_desc, f.city, f.fax, f.supp_code,
					g.*,
					h.md_title AS jabatan
					
					FROM mst_template a JOIN event b ON(a.tmpl_code=b.template_code) 
					JOIN event_item c ON(b.id=c.event_id)
					
					JOIN mst_supplier f ON(c.supp_code=f.supp_code)
					JOIN mst_dmm g ON(b.division_code=g.div_code)

					JOIN mst_md h ON(b.division_code=h.div_code)
					
					WHERE b.id='$id' AND a.is_active='1' ";

			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}
		
		function get_signature1_data($name){
			// get last data emp
			$sql = "SELECT DISTINCT * 
					FROM mst_md 
					WHERE name='$name'  
					";
			
			$exe = $this->db->query($sql);

			if ($exe->num_rows() >0){
				$ret = $exe->row();
				$arr = array(
								$ret->md_title,
								$ret->email
							);
			} else {
				$arr = null;
			}
			
			return $arr;
		}	

		function is_same_location($id){
			$this->db->select('is_same_location');
			$this->db->from('event');
			$this->db->where('id', $id);
			
			$q = $this->db->get()->row();
			$r = $q->is_same_location;
			return $r;		
		}
		
		function is_same_date($id){
			$this->db->select('is_same_date');
			$this->db->from('event');
			$this->db->where('id', $id);
			
			$q = $this->db->get()->row();
			$r = $q->is_same_date;
			return $r;
			
		}
		
		function get_event_same_location($id){	
			$sql = "SELECT loc_desc, b.store_desc 
					FROM event_same_location a JOIN mst_store b ON(a.store_code=b.store_code) 
					JOIN mst_location c ON(c.loc_code=a.location_code)
					WHERE a.event_id='$id' 
					";
					
			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}
		
		function get_event_location($id, $tillcode){	
			$sql = "SELECT loc_desc, b.store_desc 
					FROM event_location a JOIN mst_store b ON(a.store_code=b.store_code) 
					JOIN mst_location c ON(c.loc_code=a.location_code)
					JOIN mst_tillcode d ON(d.tillcode=a.tillcode)
					WHERE a.event_id='$id' AND a.tillcode='$tillcode' 
					";
					
			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}
		
		function get_supplier($id, $tillcode){	
			$sql = "SELECT DISTINCT b.supp_code, b.supp_desc
					FROM event_item a JOIN mst_supplier b ON(a.supp_code=b.supp_code)
					WHERE a.event_id='$id' AND a.tillcode='$tillcode'
					";
					
			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}
		
		function get_supplier_header($id){	
			$sql = "SELECT DISTINCT b.supp_code, b.supp_desc
					FROM event_item a JOIN mst_supplier b ON(a.supp_code=b.supp_code)
					WHERE a.event_id='$id' 
					";
					
			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}

		function get_jml_supplier($id){	
			$sql = "SELECT DISTINCT b.supp_code, b.supp_desc
					FROM event_item a JOIN mst_supplier b ON(a.supp_code=b.supp_code)
					WHERE a.event_id='$id' 
					";
					
			$ambil = $this->db->query($sql);
			
			return $ambil->num_rows();
			
		}

		function get_supplier_data($id){	
			$sql = "SELECT DISTINCT b.supp_code, b.supp_desc
					FROM event_item a JOIN mst_supplier b ON(a.supp_code=b.supp_code)
					WHERE a.event_id='$id' 
					";
					
			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}

		function get_event_date($id, $tillcode){	
			$sql = "SELECT * 
					FROM event_date
					WHERE event_id='$id' AND tillcode='$tillcode' ORDER BY date_start ASC
					";
					
			$ambil = $this->db->query($sql);
			
			return $ambil->result();
			
		}
		
		function get_count_event_date($id, $tillcode){	
			$sql = "SELECT * 
					FROM event_date
					WHERE event_id='$id' AND tillcode='$tillcode'  AND date_end IS null
					";
					
			$ambil = $this->db->query($sql);
			
			return $ambil->num_rows();
			
		}

		function get_count_event_date_all($id, $tillcode){	
			$sql = "SELECT * 
					FROM event_date
					WHERE event_id='$id' AND tillcode='$tillcode'
					";
					
			$ambil = $this->db->query($sql);
			
			return $ambil->num_rows();
			
		}

		function get_event_same_date($id){	
			$sql = "SELECT * 
					FROM event_same_date
					WHERE event_id='$id' ORDER BY date_start ASC
					";
					
			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}
		
		function get_max_datestart_event_same_date($id){
			$sql = "SELECT MAX(date_start) as max_date_start
					FROM event_same_date
					WHERE event_id='$id' 
					";
					
			$ambil = $this->db->query($sql)->row();
			return $ambil->max_date_start;
		}

		function get_max_dateend_event_same_date($id){
			$sql = "SELECT MAX(date_end) as max_date_end
					FROM event_same_date
					WHERE event_id='$id' 
					";
					
			$ambil = $this->db->query($sql)->row();
			return $ambil->max_date_end;
		}

		function get_max_datestart_event_date($id, $tillcode){
			$sql = "SELECT MAX(date_start) as max_date_start
					FROM event_date
					WHERE event_id='$id' AND tillcode='$tillcode'
					";
					
			$ambil = $this->db->query($sql)->row();
			return $ambil->max_date_start;
		}

		function get_max_dateend_event_date($id, $tillcode){
			$sql = "SELECT MAX(date_end) as max_date_end
					FROM event_date
					WHERE event_id='$id' AND tillcode='$tillcode'
					";
					
			$ambil = $this->db->query($sql)->row();
			return $ambil->max_date_end;
		}


		function get_count_event_same_date($id){	
			$sql = "SELECT * 
					FROM event_same_date
					WHERE event_id='$id'  and date_end IS null
					";
					
			$ambil = $this->db->query($sql);
			
			return $ambil->num_rows();
			
		}

		function get_count_event_same_date_all($id){	
			$sql = "SELECT * 
					FROM event_same_date
					WHERE event_id='$id' 
					";
					
			$ambil = $this->db->query($sql);
			
			return $ambil->num_rows();
			
		}

		function get_tillcode($id){	
			$sql = "SELECT a.* 
					FROM mst_tillcode a JOIN event_item b ON(a.tillcode=b.tillcode)
					WHERE b.event_id='$id' 
					ORDER BY a.disc_label ASC
					";
					
			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}

		function update_margin($id, $tillcode, $net_margin){	
			$sql = "UPDATE event_item SET net_margin='$net_margin' WHERE event_id='$id' AND tillcode='$tillcode' ";
			$ambil = $this->db->query($sql);
			
		}

		function get_event_item($id, $notes, $net_margin){	
			/*$sql = "SELECT * 
					FROM event_item 
					WHERE event_id='$id' AND notes='".$this->db->escape_str($notes)."' ";*/

			$sql = "SELECT * 
					FROM event_item 
					WHERE event_id=? AND notes=? ";
					
			$ambil = $this->db->query($sql, array($id, $notes));

			if ($ambil->num_rows() > 0){
				foreach ($ambil->result() as $r){
					$this->update_margin($id, $r->tillcode, $net_margin);
				}
				
			}
		}
		
		function get_same_location_content($id){	
			
			$same_date = $this->is_same_date($id);
			if ($same_date=="0"){
				$join = " JOIN event_date x ON(x.event_id=c.event_id) ";

			} else {
				$join = " JOIN event_same_date x ON(x.event_id=c.event_id) ";
			}

			$sql = "SELECT * FROM 
					(
						SELECT DISTINCT ON(c.notes) c.yds_responsibility, c.supp_responsibility, c.is_pkp, c.tax,
						c.event_id, c.tillcode, c.notes, c.brutto_margin, c.net_margin,
						e.disc_label, e.disc1, e.disc2, c.special_price, e.price, c.is_sp,
						x.date_start, s.supp_code, c.without_responsibility,
						(e.disc1+e.disc2) as jdisc
						
						FROM event_item c JOIN event_same_location d ON(d.event_id=c.event_id)
						JOIN mst_tillcode e ON(c.tillcode=e.tillcode)" . 
						
						$join . 
						
						"
						JOIN mst_supplier s ON(s.supp_code=c.supp_code)

						WHERE c.event_id='$id' 
						
						GROUP BY c.yds_responsibility, c.supp_responsibility, c.is_pkp, c.tax,
						c.event_id, c.tillcode, c.notes, c.brutto_margin, c.net_margin,
						e.disc_label, e.disc1, e.disc2, c.special_price, e.price, c.is_sp,
						x.date_start, s.supp_code, c.without_responsibility

						ORDER BY c.notes

					) AS y
					
					ORDER BY CASE WHEN y.is_sp=1 THEN 1
						ELSE 0
					END, y.supp_code ASC, jdisc ASC, y.date_start ASC


					";
					
			//FROM event b JOIN event_item c ON(b.id=c.event_id)
			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}
		
		function get_diff_location_content($id){	

			$same_date = $this->is_same_date($id);
			if ($same_date=="0"){
				$join = " JOIN event_date x ON(x.event_id=c.event_id) ";

			} else {
				$join = " JOIN event_same_date x ON(x.event_id=c.event_id) ";
			}
			
			
			$sql = "SELECT * FROM 
					(
						SELECT DISTINCT ON(c.notes) c.yds_responsibility, c.supp_responsibility, c.is_pkp, 
						c.event_id, c.tillcode, c.notes, c.tax, c.brutto_margin, c.net_margin,
						e.disc_label, e.disc1, e.disc2, c.special_price, e.price, c.is_sp,
						x.date_start, s.supp_code, c.without_responsibility,
						(e.disc1+e.disc2) as jdisc
						
						FROM event_item c JOIN event_location d ON(d.event_id=c.event_id)
						JOIN mst_tillcode e ON(c.tillcode=e.tillcode) " .

						$join .

						"
						JOIN mst_supplier s ON(s.supp_code=c.supp_code)

						WHERE c.event_id='$id' 

						GROUP BY c.yds_responsibility, c.supp_responsibility, c.is_pkp,
						c.event_id, c.tillcode, c.notes, c.tax, c.brutto_margin, c.net_margin,
						e.disc_label, e.disc1, e.disc2, c.special_price, e.price, c.is_sp,
						x.date_start, s.supp_code, c.without_responsibility

						ORDER BY c.notes

					) AS y

					ORDER BY CASE WHEN y.is_sp=1 THEN 1
						ELSE 0
					END, y.supp_code ASC, jdisc ASC, y.date_start ASC
					";	
			//FROM event b JOIN event_item c ON(b.id=c.event_id)
			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}
		
		//calculate contoh perhitungan
		function get_calculate($id){	

			$sql = "SELECT * FROM
					(
						SELECT DISTINCT ON(a.notes) b.*,
							a.is_pkp, a.tax, a.yds_responsibility, 
							a.special_price AS sp_event_price, a.supp_responsibility, 
							a.is_sp AS sp_event,
							(b.disc1+b.disc2) as jdisc, a.without_responsibility, a.notes
						FROM event_item a JOIN mst_tillcode b ON(a.tillcode=b.tillcode)
						WHERE a.event_id='$id'
					) AS x
					ORDER BY CASE WHEN x.sp_event=1 THEN 1
						ELSE 0
					END, jdisc, x.disc1 ASC, x.disc2 ASC
					";

					
			$ambil = $this->db->query($sql);
			return $ambil->result();
			
		}
		
		function get_event_no($id){
			$this->db->select('event_no');
			$this->db->from('event');
			$this->db->where('id', $id);
			
			$q = $this->db->get()->row();
			$r = $q->event_no;
			return $r;		
		}

		public function is_active($id){
			$this->db->select("active");
			$this->db->from("event");
			$this->db->where("id=", $id);
			$q = $this->db->get()->row();
			$r = $q->active;
			return $r;
		}

		/* upload / import master data */
		function brand_code_is_exists($code){	
			$sql = "SELECT * 
					FROM mst_brand
					WHERE brand_code='$code' ";
					
			$ambil = $this->db->query($sql);
			return $ambil->num_rows();
		}

		function ada_tillcode($tillcode){
			$sql = "SELECT * 
					FROM mst_tillcode
					WHERE tillcode='$tillcode'  ";
			
			$exe = $this->db->query($sql);
			if ($exe->num_rows() >0){
				return 1;//ada
			} else {
				return 0;
			}
		}	

		function edit_tillcode($tillcode, $disc_label, $disc1, $disc2, $special_price, $division_code, $brand_code, $is_sp, $article_code, $price, $cat_code, $article_type, $disc_label_2, $disc3, $supp_code, $brand_desc, $margin, $is_pkp, $updated_by, $updated_date){
			$params = array($disc_label, $disc1, $disc2, $special_price, $division_code, $brand_code, $is_sp, $article_code, $price, $cat_code, $article_type, $disc_label_2, $disc3, $supp_code, $brand_desc, $margin, $is_pkp, $updated_by, $updated_date);
			$sql = "UPDATE mst_tillcode
					SET disc_label = ? , disc1 = ? , disc2 = ? , special_price = ? ,
					division_code = ? , brand_code = ? , is_sp = ? , 
					article_code = ? , price = ? , cat_code = ? , article_type = ? ,
					disc_label_2 = ? , disc3 = ? , supp_code = ? , brand_desc = ? ,
					margin = ? , is_pkp = ? , updated_by = ? , updated_date = ?
					WHERE tillcode = '$tillcode' ";
			$ambil = $this->db->query($sql, $params);
		}

		function add_tillcode($dt){
			$this->db->insert('mst_tillcode', $dt); 
		}

		public function get_nextval_pic_id_seq(){
			$this->db->select("nextval('mst_pic_id_seq') as next_value");
			$q = $this->db->get()->row();
			$r = $q->next_value;
			return $r;
		}

		function ada_supplier_pic($name, $supplier_code){
			$sql = "SELECT * 
					FROM mst_pic
					WHERE name='$name' AND supplier_code='$supplier_code'  ";

			$exe = $this->db->query($sql);
			if ($exe->num_rows() >0){
				return 1;//ada
			} else {
				return 0;
			}
		}

		function insert_pic($dt){
			$this->db->insert('mst_pic', $dt); 
		}

		function ada_tillcode_detail($store_init, $tillcode){
			$sql = "SELECT * 
					FROM tillcode_detail
					WHERE store_init='$store_init' AND tillcode='$tillcode'  ";
			
			$exe = $this->db->query($sql);
			if ($exe->num_rows() >0){
				return 1;//ada
			} else {
				return 0;
			}
		}	

		//get is printed
		function get_is_printed($id){
			$sql = "SELECT is_printed 
					FROM event
					WHERE id='$id'  ";
			
			$exe = $this->db->query($sql)->row();
			return $exe->is_printed;
		}

		/* spesial cardinal pake telor */
		function get_exc_data($event_id){	
			$sql = "SELECT is_exc, is_same_date
					FROM event
					WHERE id='$event_id'  ";
			
			$exe = $this->db->query($sql);
			if ($exe->num_rows() > 0){
				$exe = $this->db->query($sql)->row();
				$arr =  array (
								$exe->is_exc, $exe->is_same_date
						);
			} else {
				$arr =  array (
								null, null
						);
			}
			
			return $arr;
		}

		function get_event_same_date_exc($event_id){	
			$sql = "SELECT harga_faktur, harga_jual
					FROM event_same_date
					WHERE event_id='$event_id' LIMIT 1 ";
			
			$exe = $this->db->query($sql);
			if ($exe->num_rows() > 0){
				$exe = $this->db->query($sql)->row();
				$arr =  array (
								$exe->harga_faktur, $exe->harga_jual
						);
			} else {
				$arr =  array (
								null, null
						);
			}
			
			return $arr;
		}

		function get_event_date_exc($event_id, $tillcode){	
			$sql = "SELECT harga_faktur, harga_jual
					FROM event_date
					WHERE event_id='$event_id' AND tillcode='$tillcode'
					LIMIT 1 ";
			
			$exe = $this->db->query($sql);
			if ($exe->num_rows() > 0){
				$exe = $this->db->query($sql)->row();
				$arr =  array (
								$exe->harga_faktur, $exe->harga_jual
						);
			} else {
				$arr =  array (
								null, null
						);
			}
			
			return $arr;
		}
		/* end spesial cardinal pake telor */

		function update_consignment($event_id, $event_item_notes, $part1, $part2){
			$params = array($part1, $part2, $event_item_notes);
			$sql = "UPDATE consignment
					SET part1 = ? , part2 = ?
					WHERE event_id = '$event_id' AND event_item_notes= ? ";
			$this->db->query($sql, $params);
		}

		function get_consignment($event_id, $event_item_notes){
			// $event_item_notes = addslashes($event_item_notes);
			$params = array($event_item_notes);
			$sql = "SELECT event_id, tillcode, event_item_notes 
					FROM consignment
					WHERE event_id='$event_id' AND event_item_notes= ? ";
			
			$exe = $this->db->query($sql, $params);
			return $exe->result();
		}

	}
?>