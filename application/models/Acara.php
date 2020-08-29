<?php
/**
 * Class   :  Acara
 * Author  :  
 * Created :  2015-08-26
 * Desc    :  Data handler for Acara
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Acara extends CI_Model {
        
        public function __construct() {
        
        }
        
        public function isCrossYear($curYear, $nextYear, $nasJat = "All") {
                if ($nasJat == "Nasional") {
                        $sql = "select count(w.event_id) cnt from vw_event_date w where extract('year' from w.date_start) = ? and extract('year' from w.date_end) = ?
                                and (select u.is_printed from event u where u.id = w.event_id) = 1
                                and (select count(v.store_init) from vw_event_location v where v.event_id = w.event_id and v.store_init not in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0";   
                }
                else if ($nasJat == "Jateng") {
                        $sql = "select count(w.event_id) cnt from vw_event_date w where extract('year' from w.date_start) = ? and extract('year' from w.date_end) = ?
                                and (select u.is_printed from event u where u.id = w.event_id) = 1
                                and (select count(v.store_init) from vw_event_location v where v.event_id = w.event_id and v.store_init in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0";   
                }
                else {
                        $sql = "select count(w.event_id) cnt from vw_event_date w where extract('year' from w.date_start) = ? and extract('year' from w.date_end) = ?
                                and (select u.is_printed from event u where u.id = w.event_id) = 1";        
                }
                $params = array($curYear, $nextYear);
                $query = $this->db->query($sql, $params);
                $ret = $query->row()->cnt;
                
                return $ret > 0 ? true : false;
        }
        
        // added @30-Mar-16
        public function loadAllSpecialEvent($yy, $div = null, $user = null, $arrayMode = false) {
                $fDiv = "";
                if ($div) 
                        $fDiv = " and  x.division_code = ? ";
                $fUser = "";
                if ($user) 
                        $fUser = " and  x.first_signature = ? ";
                
                if ($div && $user)
                        $params = array($yy, $div, $user);
                else if (!$div && $user)
                        $params = array($yy, $user);
                else if ($div && !$user)
                        $params = array($yy, $div);
                else
                        $params = array($yy);
                        
                $sql = "select distinct x.special_event_desc 
                        from event x inner join event_item y on x.id = y.event_id
                        where x.is_special_event = 1 and is_event_in_year(x.id, y.tillcode, ?) > 0 
                        $fDiv
                        $fUser
                        order by x.special_event_desc";
                
                $query = $this->db->query($sql, $params);
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        // prepare data special event for export
        public function loadSpecialEventData($yy, $name, $div = null, $user = null, $arrayMode = false, $nasJat = "All") {
                $fDiv = "";
                if ($div) 
                        $fDiv = " and  x.division_code = ? ";
                $fUser = "";
                if ($user) 
                        $fUser = " and  x.first_signature = ? ";
                
                if ($div && $user)
                        $params = array($nasJat, $nasJat, $yy, $name, $div, $user);
                else if (!$div && $user)
                        $params = array($nasJat, $nasJat, $yy, $name, $user);
                else if ($div && !$user)
                        $params = array($nasJat, $nasJat, $yy, $name, $div);
                else
                        $params = array($nasJat, $nasJat, $yy, $name);
                
                if ($nasJat == "Nasional") {
                        $sql = "select x.id,
                        case when x.updated_date is not null then
                                to_char(x.updated_date, 'dd Mon yy') 
                        else
                                to_char(x.created_date, 'dd Mon yy') 
                        end created_date,
                        x.event_no, x.division_code, x.first_signature, y.tillcode, z.article_code, z.disc_label tillcode_desc, y.notes, 
                        y.supp_code, get_supp_desc(y.supp_code) supp_desc, case when y.is_pkp = 1 then 'PKP' else 'NPKP' end com_contract,
                        case when x.active = 0 then
                                'Cancel'
                        else
                                ''
                        end status,
                        case when y.without_responsibility = 1 then
                            y.tax
                        else
                            y.net_margin
                        end net_margin, 
                        x.is_same_date, x.is_same_location, 
                        case when x.is_same_date = 0 then 
                            get_event_date(x.id, y.tillcode) 
                        else
                            get_event_same_date(x.id) 
                        end period, 
                        case when x.is_same_location = 0 then 
                            get_event_location(x.id, y.tillcode, ?) 
                        else
                            get_event_same_location(x.id, ?) 
                        end  site_group 
                        from event x inner join event_item y on x.id = y.event_id
                        left join mst_tillcode z on z.tillcode = y.tillcode
                        where x.is_special_event = 1 and x.is_printed = 1 and is_event_in_year(x.id, y.tillcode, ?) > 0 and x.special_event_desc = ? 
                        $fDiv
                        $fUser
                        --and case when x.is_same_location = 0 then 
                        --    (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.store_init not in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                        --else
                        --    (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.tillcode = y.tillcode and v.store_init not in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                        --end
                        and (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.store_init not in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                        order by x.updated_date, x.id
                        ";
                }
                else if ($nasJat == "Jateng") {
                        $sql = "select x.id,
                        case when x.updated_date is not null then
                                to_char(x.updated_date, 'dd Mon yy') 
                        else
                                to_char(x.created_date, 'dd Mon yy') 
                        end created_date,
                        x.event_no, x.division_code, x.first_signature, y.tillcode, z.article_code, z.disc_label tillcode_desc, y.notes, 
                        y.supp_code, get_supp_desc(y.supp_code) supp_desc, case when y.is_pkp = 1 then 'PKP' else 'NPKP' end com_contract,
                        case when x.active = 0 then
                                'Cancel'
                        else
                                ''
                        end status,
                        case when y.without_responsibility = 1 then
                            y.tax
                        else
                            y.net_margin
                        end net_margin, 
                        x.is_same_date, x.is_same_location, 
                        case when x.is_same_date = 0 then 
                            get_event_date(x.id, y.tillcode) 
                        else
                            get_event_same_date(x.id) 
                        end period, 
                        case when x.is_same_location = 0 then 
                            get_event_location(x.id, y.tillcode, ?) 
                        else
                            get_event_same_location(x.id, ?) 
                        end  site_group 
                        from event x inner join event_item y on x.id = y.event_id
                        left join mst_tillcode z on z.tillcode = y.tillcode
                        where x.is_special_event = 1 and x.is_printed = 1 and is_event_in_year(x.id, y.tillcode, ?) > 0 and x.special_event_desc = ? 
                        $fDiv
                        $fUser
                        --and case when x.is_same_location = 0 then 
                        --    (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.store_init in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                        --else
                        --    (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.tillcode = y.tillcode and v.store_init in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                        --end
                        and (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.store_init in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                        order by x.updated_date, x.id
                        ";
                }
                else {
                        $sql = "select x.id,
                        case when x.updated_date is not null then
                                to_char(x.updated_date, 'dd Mon yy') 
                        else
                                to_char(x.created_date, 'dd Mon yy') 
                        end created_date,
                        x.event_no, x.division_code, x.first_signature, y.tillcode, z.article_code, z.disc_label tillcode_desc, y.notes, 
                        y.supp_code, get_supp_desc(y.supp_code) supp_desc, case when y.is_pkp = 1 then 'PKP' else 'NPKP' end com_contract,
                        case when x.active = 0 then
                                'Cancel'
                        else
                                ''
                        end status,
                        case when y.without_responsibility = 1 then
                            y.tax
                        else
                            y.net_margin
                        end net_margin, 
                        x.is_same_date, x.is_same_location, 
                        case when x.is_same_date = 0 then 
                            get_event_date(x.id, y.tillcode) 
                        else
                            get_event_same_date(x.id) 
                        end period, 
                        case when x.is_same_location = 0 then 
                            get_event_location(x.id, y.tillcode, ?) 
                        else
                            get_event_same_location(x.id, ?) 
                        end  site_group 
                        from event x inner join event_item y on x.id = y.event_id
                        left join mst_tillcode z on z.tillcode = y.tillcode
                        where x.is_special_event = 1 and x.is_printed = 1 and is_event_in_year(x.id, y.tillcode, ?) > 0 and x.special_event_desc = ? 
                        $fDiv
                        $fUser
                        order by x.updated_date, x.id
                        ";        
                }
                
                $query = $this->db->query($sql, $params);
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        // end @30-Mar-16
        
        // added @16-Mar-16
        // prepare data non special event for export
        public function loadData($yymm, $div = null, $user = null, $arrayMode = false, $nasJat = "All") {
                $fDiv = "";
                if ($div) 
                        $fDiv = " and  x.division_code = ? ";
                $fUser = "";
                if ($user) 
                        $fUser = " and  x.first_signature = ? ";
                
                if ($div && $user)
                        $params = array($yymm, $yymm, $nasJat, $nasJat, $yymm, $div, $user);
                else if (!$div && $user)
                        $params = array($yymm, $yymm, $nasJat, $nasJat, $yymm, $user);
                else if ($div && !$user)
                        $params = array($yymm, $yymm, $nasJat, $nasJat, $yymm, $div);
                else
                        $params = array($yymm, $yymm, $nasJat, $nasJat, $yymm);
                
                if ($nasJat == "Nasional") {
                        $sql = "select x.id,
                                case when x.updated_date is not null then
                                        to_char(x.updated_date, 'dd Mon yy') 
                                else
                                        to_char(x.created_date, 'dd Mon yy') 
                                end created_date,
                                x.event_no, x.division_code, x.first_signature, y.tillcode, z.article_code, z.disc_label tillcode_desc, y.notes, 
                                y.supp_code, get_supp_desc(y.supp_code) supp_desc, case when y.is_pkp = 1 then 'PKP' else 'NPKP' end com_contract,
                                case when x.active = 0 then
                                        'Cancel'
                                else
                                        ''
                                end status,
                                case when y.without_responsibility = 1 then
                                    y.tax
                                else
                                    y.net_margin
                                end net_margin,
                                x.is_same_date, x.is_same_location, 
                                case when x.is_same_date = 0 then 
                                    get_event_date(x.id, y.tillcode, ?) 
                                else
                                    get_event_same_date(x.id, ?) 
                                end period, 
                                case when x.is_same_location = 0 then 
                                    get_event_location(x.id, y.tillcode, ?) 
                                else
                                    get_event_same_location(x.id, ?) 
                                end  site_group 
                                from event x inner join event_item y on x.id = y.event_id
                                left join mst_tillcode z on z.tillcode = y.tillcode
                                where x.is_special_event = 0 and x.is_printed = 1 and is_event_in_period(x.id, y.tillcode, ?) > 0 
                                $fDiv
                                $fUser
                                --and case when x.is_same_location = 0 then 
                                --    (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.store_init not in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                                --else
                                --    (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.tillcode = y.tillcode and v.store_init not in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                                --end
                                and (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.store_init not in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                                order by x.updated_date, x.id
                        ";
                }
                else if ($nasJat == "Jateng") {
                        $sql = "select x.id,
                                case when x.updated_date is not null then
                                        to_char(x.updated_date, 'dd Mon yy') 
                                else
                                        to_char(x.created_date, 'dd Mon yy') 
                                end created_date,
                                x.event_no, x.division_code, x.first_signature, y.tillcode, z.article_code, z.disc_label tillcode_desc, y.notes, 
                                y.supp_code, get_supp_desc(y.supp_code) supp_desc, case when y.is_pkp = 1 then 'PKP' else 'NPKP' end com_contract,
                                case when x.active = 0 then
                                        'Cancel'
                                else
                                        ''
                                end status,
                                case when y.without_responsibility = 1 then
                                    y.tax
                                else
                                    y.net_margin
                                end net_margin,
                                x.is_same_date, x.is_same_location, 
                                case when x.is_same_date = 0 then 
                                    get_event_date(x.id, y.tillcode, ?) 
                                else
                                    get_event_same_date(x.id, ?) 
                                end period, 
                                case when x.is_same_location = 0 then 
                                    get_event_location(x.id, y.tillcode, ?) 
                                else
                                    get_event_same_location(x.id, ?) 
                                end  site_group 
                                from event x inner join event_item y on x.id = y.event_id
                                left join mst_tillcode z on z.tillcode = y.tillcode
                                where x.is_special_event = 0 and x.is_printed = 1 and is_event_in_period(x.id, y.tillcode, ?) > 0 
                                $fDiv
                                $fUser
                                --and case when x.is_same_location = 0 then 
                                --    (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.store_init in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                                --else
                                --    (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.tillcode = y.tillcode and v.store_init in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                                --end
                                and (select count(v.store_init) from vw_event_location v where v.event_id = x.id and v.store_init in ('YPL','YKG', 'YLO', 'YTG', 'YSL', 'YBR', 'YMJ')) > 0
                                order by x.updated_date, x.id
                        ";
                }
                else {
                        $sql = "select x.id,
                                case when x.updated_date is not null then
                                        to_char(x.updated_date, 'dd Mon yy') 
                                else
                                        to_char(x.created_date, 'dd Mon yy') 
                                end created_date,
                                x.event_no, x.division_code, x.first_signature, y.tillcode, z.article_code, z.disc_label tillcode_desc, y.notes, 
                                y.supp_code, get_supp_desc(y.supp_code) supp_desc, case when y.is_pkp = 1 then 'PKP' else 'NPKP' end com_contract,
                                case when x.active = 0 then
                                        'Cancel'
                                else
                                        ''
                                end status,
                                case when y.without_responsibility = 1 then
                                    y.tax
                                else
                                    y.net_margin
                                end net_margin,
                                x.is_same_date, x.is_same_location, 
                                case when x.is_same_date = 0 then 
                                    get_event_date(x.id, y.tillcode, ?) 
                                else
                                    get_event_same_date(x.id, ?) 
                                end period, 
                                case when x.is_same_location = 0 then 
                                    get_event_location(x.id, y.tillcode, ?) 
                                else
                                    get_event_same_location(x.id, ?) 
                                end  site_group 
                                from event x inner join event_item y on x.id = y.event_id
                                left join mst_tillcode z on z.tillcode = y.tillcode
                                where x.is_special_event = 0 and x.is_printed = 1 and is_event_in_period(x.id, y.tillcode, ?) > 0 
                                $fDiv
                                $fUser
                                order by x.updated_date, x.id
                        ";        
                }
                
                $query = $this->db->query($sql, $params);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        // end @16-Mar-16
        
		# ZZZZZZZZZ
		public function loadYear($arrayMode = false) {
			$sql = "SELECT DISTINCT TO_NUMBER(RIGHT(event_no, 4), '9999') AS year
				FROM event
				WHERE TO_NUMBER(RIGHT(event_no, 4), '9999') >= 2000
				ORDER BY TO_NUMBER(RIGHT(event_no, 4), '9999') DESC
			";
			$query = $this->db->query($sql);
			if ($arrayMode) {
				return $query->result_array();
			} else {
				return $query->result();
			}
		}
		
		# ZZZZZZZZZ
		public function loadRekapDataNew($div = null, $year = null, $arrayMode = false) {
			$fDiv = "";
			$params = array();
			if ($div) {
				$fDiv = " AND x.division_code = ? ";
				$params = array($div);
			}
			
			$sql = "SELECT x.id, x.event_no, x.division_code, y.category_code, y.supp_code, get_supp_desc(y.supp_code) supp_desc, 
					y.notes, x.first_signature, get_brand_from_tillcode(y.tillcode) brand, '' tempat_acara, updated_date tgl_approve, 
					CASE WHEN x.is_same_date = 0 THEN get_event_date(x.id, y.tillcode) 
						ELSE get_event_same_date(x.id) 
					END period, 
					CASE WHEN x.is_same_location = 0 THEN get_event_location(x.id, y.tillcode) 
						ELSE get_event_same_location(x.id) 
					END site_group
				FROM event x
					INNER JOIN event_item y ON x.id = y.event_id
				WHERE x.is_printed = 1 
					AND RIGHT(x.event_no,4) = '$year'
					$fDiv
				ORDER BY x.created_date, x.id
			";        
	
			$query = $this->db->query($sql, $params);
			if ($arrayMode) {
				return $query->result_array();
			} else {
				return $query->result();
			}
        }
		
        public function loadRekapData($div = null, $arrayMode = false) {
                $fDiv = "";
                $params = array();
                if ($div) {
                        $fDiv = " and  x.division_code = $div ";
                        $params = array($div);
                }
                
                $sql = "select x.id, x.event_no, x.division_code, y.category_code, y.supp_code, get_supp_desc(y.supp_code) supp_desc, 
                        y.notes, x.first_signature, get_brand_from_tillcode(y.tillcode) brand, '' tempat_acara, updated_date tgl_approve, 
                        case when x.is_same_date = 0 then 
                            get_event_date(x.id, y.tillcode) 
                        else
                            get_event_same_date(x.id) 
                        end period, 
                        case when x.is_same_location = 0 then 
                            get_event_location(x.id, y.tillcode) 
                        else
                            get_event_same_location(x.id) 
                        end  site_group
                        from event x inner join event_item y on x.id = y.event_id
                        where x.is_printed = 1 
                        $fDiv
                        order by x.created_date, x.id
                ";        
        
                $query = $this->db->query($sql, $params);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function getMarginPkp($tillcode) {	
                $params = array($tillcode);
                $sql = "select margin, is_pkp from mst_tillcode where tillcode = ? order by is_pkp desc";
                $query = $this->db->query($sql, $params);
                
                $ret["margin"] = 0;
                $ret["is_pkp"] = 1;
                        
                if ($query->num_rows() > 0) {
                        $row = $query->row();
                        $ret["margin"] = $row->margin;
                        $ret["is_pkp"] = $row->is_pkp;
                }
                return $ret;
        }
        
        public function isArticleHasDiscount($tillcode) {
                $params = array($tillcode);
                $sql = "select count(tillcode) cnt from mst_tillcode where tillcode = ? and disc1 is not null";
                $query = $this->db->query($sql, $params);
                
                if ($query->num_rows() > 0) {
                        $row = $query->row();
                        return ($row->cnt > 0 ? true : false);
                }
                return false;
        }
        
        public function isValidSpArticle($tillcode) {
                $params = array($tillcode);
                $sql = "select count(tillcode) cnt from mst_tillcode where tillcode = ? and is_sp = 1";
                $query = $this->db->query($sql, $params);
                
                if ($query->num_rows() > 0) {
                        $row = $query->row();
                        return ($row->cnt > 0 ? true : false);
                }
                return false;
        }
        
        public function getDivisionName($divisionCode) {	
                $params = array($divisionCode);
                $sql = "select division_desc from mst_division where division_code = ?";
                $query = $this->db->query($sql, $params);
                
                if ($query->num_rows() > 0) {
                        $row = $query->row();
                        return $row->division_desc;
                }
                return "";
        }
        
        public function loadMdByDivision($divisionCode, $arrayMode = false) {
                $params = array($divisionCode);
                $sql = "select distinct name from mst_md where is_active = 1 and div_code = ? order by name";
                $query = $this->db->query($sql, $params);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function loadAllMd($arrayMode = false) {	
                $sql = "select distinct name from mst_md where is_active = 1 order by name";
                $query = $this->db->query($sql);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function loadAllLocation($arrayMode = false) {	
                $sql = "select loc_code, loc_desc from mst_location where is_active = 1 order by loc_desc";
                $query = $this->db->query($sql);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function loadAllStore($arrayMode = false) {	
                $sql = "select store_code, store_init, store_desc from mst_store where is_active = 1 order by store_desc";
                $query = $this->db->query($sql);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function loadAllSupplier($arrayMode = false) {	
                $sql = "select distinct supp_code, supp_desc from mst_supplier where is_active = 1 order by supp_desc";
                $query = $this->db->query($sql);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function loadAllBrand($arrayMode = false) {	
                $sql = "select brand_code, brand_desc from mst_brand where is_active = 1 order by brand_desc";
                $query = $this->db->query($sql);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function loadBrandsBySupplier($supplierCode, $arrayMode = false) {	
                $params = array($supplierCode);
                $sql = "select distinct brand_code, brand_desc from mst_tillcode where is_active = 1 and supp_code = ? order by brand_desc";
                $query = $this->db->query($sql, $params);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function loadTillcodeByDivision($divisionCode, $supplierCode = "", $brandCode = "", $arrayMode = false) {	
                if ($supplierCode == "" && $brandCode == "") {
                        $params = array($divisionCode);
                        $sql = "select tillcode, disc_label, brand_code, brand_desc from mst_tillcode where division_code = ? and is_active = 1 and disc1 is not null order by disc_label";
                        $query = $this->db->query($sql, $params);        
                }
                else if ($supplierCode != "" && $brandCode == "") {
                        $params = array($divisionCode, $supplierCode);
                        $sql = "select tillcode, disc_label, brand_code, brand_desc from mst_tillcode where division_code = ? and supp_code = ? and is_active = 1 and disc1 is not null order by disc_label";
                        $query = $this->db->query($sql, $params);        
                }
                else if ($supplierCode == "" && $brandCode != "") {
                        $params = array($divisionCode, $brandCode);
                        $sql = "select tillcode, disc_label, brand_code, brand_desc from mst_tillcode where division_code = ? and brand_code = ? and is_active = 1 and disc1 is not null order by disc_label";
                        $query = $this->db->query($sql, $params);        
                }
                else {
                        $params = array($divisionCode, $supplierCode, $brandCode);
                        $sql = "select tillcode, disc_label, brand_code, brand_desc from mst_tillcode where division_code = ? and supp_code = ? and brand_code = ? and is_active = 1 and disc1 is not null order by disc_label";
                        $query = $this->db->query($sql, $params);  
                }
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function loadCategoryByDivision($divisionCode, $arrayMode = false) {	
                $params = array($divisionCode);
                $sql = "select category_code, category_desc from mst_category where division_code = ? and is_active = 1 order by category_desc";
                $query = $this->db->query($sql, $params);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function loadAllCategory($arrayMode = false) {	
                $sql = "select category_code, category_desc from mst_category where is_active = 1 order by category_desc";
                $query = $this->db->query($sql);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function loadAllTemplate($arrayMode = false) {	
                $sql = "select tmpl_code, tmpl_name from mst_template where is_active = 1 order by tmpl_name";
                $query = $this->db->query($sql);
                
                if ($arrayMode) 
                        return $query->result_array();
                else
                        return $query->result();
        }
        
        public function load($id, $arrayMode = false) {
                $aResult = array();
                $params = array($id);
                
                $sql = "select tillcode, to_char(date_start, 'dd-mm-yyyy') date_start, to_char(date_end, 'dd-mm-yyyy') date_end, to_char(harga_faktur, '999,999,999.99') harga_faktur_f, harga_faktur, to_char(harga_jual, '999,999,999.99') harga_jual_f, harga_jual from event_date where event_id = ? order by tillcode, date_start";
                $query = $this->db->query($sql, $params);
                if ($arrayMode) 
                        $aResult["event_date"] = $query->result_array();
                else
                        $aResult["event_date"] = $query->result();
                
                $sql = "select x.tillcode, x.store_code, y.store_desc || ' (' || y.store_init || ')' store_desc, x.location_code, z.loc_desc from event_location x inner join mst_store y on y.store_code = x.store_code " .
                        "inner join mst_location z on z.loc_code = x.location_code " .
                        "where x.event_id = ? order by x.tillcode, y.store_desc, z.loc_desc";
                $query = $this->db->query($sql, $params);
                if ($arrayMode) 
                        $aResult["event_location"] = $query->result_array();
                else
                        $aResult["event_location"] = $query->result();
                
                $sql = "select to_char(date_start, 'dd-mm-yyyy') date_start, to_char(date_end, 'dd-mm-yyyy') date_end, to_char(harga_faktur, '999,999,999.99') harga_faktur_f, harga_faktur, to_char(harga_jual, '999,999,999.99') harga_jual_f, harga_jual from event_same_date where event_id = ? order by date_start";
                $query = $this->db->query($sql, $params);
                if ($arrayMode) 
                        $aResult["event_same_date"] = $query->result_array();
                else
                        $aResult["event_same_date"] = $query->result();
                
                $sql = "select x.store_code, y.store_desc || ' (' || y.store_init || ')' store_desc, x.location_code, z.loc_desc from event_same_location x inner join mst_store y on y.store_code = x.store_code " .
                        "inner join mst_location z on z.loc_code = x.location_code " .
                        "where x.event_id = ? order by y.store_desc, z.loc_desc";
                $query = $this->db->query($sql, $params);
                if ($arrayMode) 
                        $aResult["event_same_location"] = $query->result_array();
                else
                        $aResult["event_same_location"] = $query->result();
                
                $sql = "select x.tillcode, x.category_code, y.category_desc, x.notes, x.supp_code, x.yds_responsibility, x.supp_responsibility, x.is_pkp, x.tax, " .
                        "x.same_location, x.same_date, x.is_sp, x.special_price, x.city " .
                        "from event_item x inner join mst_category y on y.category_code = x.category_code where x.event_id = ? order by x.notes, x.tillcode";
                $query = $this->db->query($sql, $params);
                if ($arrayMode) 
                        $aResult["event_item"] = $query->result_array();
                else
                        $aResult["event_item"] = $query->result();
                
                $sql = "select event_no, about, purpose, attach, toward, department, division_code, source, template_code, first_signature, second_signature, 
                        approved_by, approved_date, notes, cc, is_manual_setting, to_char(letter_date, 'dd-mm-yyyy') letter_date, is_same_date, is_same_location,
                        is_special_event, special_event_desc, id_venditore from event where id = ?";
                $query = $this->db->query($sql, $params);
                if ($arrayMode) 
                        $aResult["event"] = $query->result_array();
                else
                        $aResult["event"] = $query->result();
                
                return $aResult;
        }
        
        public function remove($id) {
                # start transaction
                $this->db->trans_start();
                
                $params = array($id);
                $sql = "delete from event_date where event_id = ?";
                $this->db->query($sql, $params);
                $sql = "delete from event_location where event_id = ?";
                $this->db->query($sql, $params); 
                $sql = "delete from event_same_date where event_id = ?";
                $this->db->query($sql, $params); 
                $sql = "delete from event_same_location where event_id = ?";
                $this->db->query($sql, $params);
                $sql = "delete from event_item where event_id = ?";
                $this->db->query($sql, $params);
                $sql = "delete from event where id = ?";
                $this->db->query($sql, $params);
                
                # end transaction
                $this->db->trans_complete();
                
                return $this->db->trans_status();
        }
        
        public function update($id, $eventNo, $about, $purpose, $attach, $toward, $department, $divisionCode, $source, $templateCode, $firstSignature, $secondSignature,
                               $notes, $cc, $isManualSetting, $letterDate, $isSameDate, $isSameLocation, $detailEvent, $detailDate, $detailLocation, $usr, $upd,
                               $isSpecialEvent, $specialEventDesc, $isExc) {
                
                # start transaction
                $this->db->trans_start();
                
                # delete detail first
                $params = array($id);
                $sql = "delete from event_date where event_id = ?";
                $this->db->query($sql, $params);
                $sql = "delete from event_location where event_id = ?";
                $this->db->query($sql, $params); 
                $sql = "delete from event_same_date where event_id = ?";
                $this->db->query($sql, $params); 
                $sql = "delete from event_same_location where event_id = ?";
                $this->db->query($sql, $params);
                $sql = "delete from event_item where event_id = ?";
                $this->db->query($sql, $params); 
                
                # event
                $params = array($eventNo, $about, $purpose, $attach, $toward, $department, $divisionCode, $source, $templateCode, $firstSignature, $secondSignature,
                                $notes, $cc, $isManualSetting, $letterDate, $usr, $upd, $isSameDate, $isSameLocation, $isSpecialEvent, $specialEventDesc, $id);
                
                $sql = "update event set event_no = ?, about = ?, purpose = ?, attach = ?, toward = ?, department = ?, division_code = ?, source = ?, template_code = ?, " .
                                "first_signature = ?, second_signature = ?, notes = ?, cc = ?, is_manual_setting = ?, letter_date = to_date(?, 'dd-mm-yyyy'), updated_by = ?, updated_date = ?, " .
                                "is_same_date = ?, is_same_location = ?, is_special_event = ?, special_event_desc = ? where id = ?";
                $this->db->query($sql, $params);
                
                # items
                for ($i = 0; $i < sizeof($detailEvent); $i++) {
                        $withoutResponsibility = ($detailEvent[$i]["ydsResponsibility"] == 0 && $detailEvent[$i]["suppResponsibility"] == 0 ? 1 : 0);
                        $isSp = ($detailEvent[$i]["sp"] == "" ? 0 : 1);
                         
                        $params = array(
                                        $id,
                                        $detailEvent[$i]["tillcode"],
                                        $detailEvent[$i]["notes"],
                                        $detailEvent[$i]["suppCode"],
                                        $detailEvent[$i]["categoryCode"],
                                        $detailEvent[$i]["ydsResponsibility"],
                                        $detailEvent[$i]["suppResponsibility"],
                                        $detailEvent[$i]["isPkp"],
                                        $detailEvent[$i]["margin"],
                                        #$detailEvent[$i]["bruttoMargin"],
                                        #$detailEvent[$i]["netMargin"],
                                        $isSameDate,
                                        $isSameLocation,
                                        $withoutResponsibility,
                                        $isSp,
                                        $detailEvent[$i]["sp"],
                                        $detailEvent[$i]["kota"],
                                );
                        $sql = "insert into event_item (event_id, tillcode, notes, supp_code, category_code, yds_responsibility, supp_responsibility, is_pkp, tax, " .
                                                        "same_location, same_date, without_responsibility, is_sp, special_price, city) ". 
                                "values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->db->query($sql, $params);    

                        //update toward by gie
                        $sql = "SELECT DISTINCT b.supplier_code, b.name 
                                FROM event_item a JOIN mst_pic b ON(a.supp_code=b.supplier_code) 
                                WHERE a.event_id='$id' AND b.name IS NOT NULL  ";
                        $res = $this->db->query($sql)->result(); 
                        $ret = "";
                        
                        foreach($res as $r) {
                            $ret .= $r->name . ", ";
                        }
                        
                        $to =  rtrim($ret, ", ");

                        $sql = "UPDATE event SET toward='$to' WHERE id='$id' ";
                        $this->db->query($sql);

/*
                        $cek = $this->is_null_toward($id);
                        if ($cek==true){
                            $sql = "UPDATE event SET toward='$to' WHERE id='$id' ";
                            $this->db->query($sql);
                        }*/

                }
                
                # date
                if ($isSameDate)
                        for ($i = 0; $i < sizeof($detailDate); $i++) {
                                
                                if ($isExc == "Y") {
                                        $params = array($id, $detailDate[$i]["dateStart"], $detailDate[$i]["dateEnd"], $detailDate[$i]["hargaFaktur"], $detailDate[$i]["hargaJual"]);
                                        $sql = "insert into event_same_date (event_id, date_start, date_end, harga_faktur, harga_jual) values (?, to_date(?, 'dd-mm-yyyy'), to_date(?, 'dd-mm-yyyy'), ?, ?)";
                                        $this->db->query($sql, $params);    
                                }
                                else {
                                        $params = array($id, $detailDate[$i]["dateStart"], $detailDate[$i]["dateEnd"]);
                                        $sql = "insert into event_same_date (event_id, date_start, date_end) values (?, to_date(?, 'dd-mm-yyyy'), to_date(?, 'dd-mm-yyyy'))";
                                        $this->db->query($sql, $params);            
                                }
                                   
                        }
                else {
                        for ($i = 0; $i < sizeof($detailDate); $i++) {
                                
                                if ($isExc == "Y") {
                                        $params = array($id, $detailDate[$i]["tillcode"], $detailDate[$i]["dateStart"], $detailDate[$i]["dateEnd"], $detailDate[$i]["hargaFaktur"], $detailDate[$i]["hargaJual"]);
                                        $sql = "insert into event_date (event_id, tillcode, date_start, date_end, harga_faktur, harga_jual) values (?, ?, to_date(?, 'dd-mm-yyyy'), to_date(?, 'dd-mm-yyyy'), ?, ?)";
                                        $this->db->query($sql, $params);    
                                }
                                else {
                                        $params = array($id, $detailDate[$i]["tillcode"], $detailDate[$i]["dateStart"], $detailDate[$i]["dateEnd"]);
                                        $sql = "insert into event_date (event_id, tillcode, date_start, date_end) values (?, ?, to_date(?, 'dd-mm-yyyy'), to_date(?, 'dd-mm-yyyy'))";
                                        $this->db->query($sql, $params);           
                                }
                                       
                        }      
                }
                
                # location
                if ($isSameLocation) {
                        for ($i = 0; $i < sizeof($detailLocation); $i++) {
                                $params = array($id, $this->get_store_code($detailLocation[$i]["storeCode"]), $detailLocation[$i]["locationCode"]);
                                $sql = "insert into event_same_location (event_id, store_code, location_code) values (?, ?, ?)";
                                $this->db->query($sql, $params);        
                        }
                }
                else {
                        for ($i = 0; $i < sizeof($detailLocation); $i++) {
                                $params = array($id, $detailLocation[$i]["tillcode"], $this->get_store_code($detailLocation[$i]["storeCode"]), $detailLocation[$i]["locationCode"]);
                                $sql = "insert into event_location (event_id, tillcode, store_code, location_code) values (?, ?, ?, ?)";
                                $this->db->query($sql, $params);        
                        }        
                }
                
                # update to null date field
                $params = array($id);
                
                $sql = "update event_date set date_start = null where event_id = ? and date_start = '0001-01-01 BC'";
                $this->db->query($sql, $params);  
                $sql = "update event_date set date_end = null where event_id = ? and date_end = '0001-01-01 BC'";
                $this->db->query($sql, $params);  
                $sql = "update event_same_date set date_start = null where event_id = ? and date_start = '0001-01-01 BC'";
                $this->db->query($sql, $params);  
                $sql = "update event_same_date set date_end = null where event_id = ? and date_end = '0001-01-01 BC'";
                $this->db->query($sql, $params);
                
                # end transaction
                $this->db->trans_complete();
                
                return $this->db->trans_status();
        }
        
        public function addNew($about, $purpose, $attach, $toward, $department, $divisionCode, $source, $templateCode, $firstSignature, $secondSignature,
                               $notes, $cc, $isManualSetting, $letterDate, $isSameDate, $isSameLocation, $detailEvent, $detailDate, $detailLocation, $usr, $upd,
                               $isSpecialEvent, $specialEventDesc, $isExc) {
                
                # get sequence number
                $seq = 0;
                $sql = "select nextval('event_seq') seq";        
                $query = $this->db->query($sql);
                if ($row = $query->row()) {
                        $seq = $row->seq;        
                }
                
                # create letter number
                $seqLetterNumber = 0;
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
                $eventNo = $seqLetterNumber; 
                
                # start transaction
                $this->db->trans_start();
                
                # event
                $active = 1;
                $params = array($seq, $eventNo, $about, $purpose, $attach, $toward, $department, $divisionCode, $source, $templateCode, $firstSignature, $secondSignature,
                                $notes, $cc, $isManualSetting, $letterDate, $usr, $upd, $isSameDate, $isSameLocation, $active, $isSpecialEvent, $specialEventDesc);
                
                $sql = "insert into event (id, event_no, about, purpose, attach, toward, department, division_code, source, template_code, first_signature, " .
                                          "second_signature, notes, cc, is_manual_setting, letter_date, created_by, created_date, is_same_date, is_same_location, " .
                                          "active, is_special_event, special_event_desc) " .
                        "values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, to_date(?, 'dd-mm-yyyy'), ?, ?, ?, ?, ?, ?, ?)";
                $this->db->query($sql, $params);
                
                //get current serial val by gie
                $currval = $this->get_currval_event();
                
                # items
                for ($i = 0; $i < sizeof($detailEvent); $i++) {
                        
                        $ydsResponsibility = (is_numeric($detailEvent[$i]["ydsResponsibility"]) ? $detailEvent[$i]["ydsResponsibility"] : 0);
                        $suppResponsibility = (is_numeric($detailEvent[$i]["suppResponsibility"]) ? $detailEvent[$i]["suppResponsibility"] : 0);
                        $withoutResponsibility = ($ydsResponsibility == 0 && $suppResponsibility == 0 ? 1 : 0);
                        $isSp = ($detailEvent[$i]["sp"] == "" ? 0 : 1);
                        
                        $params = array(
                                        $seq,
                                        $detailEvent[$i]["tillcode"],
                                        $detailEvent[$i]["notes"],
                                        $detailEvent[$i]["suppCode"],
                                        $detailEvent[$i]["categoryCode"],
                                        $ydsResponsibility,
                                        $suppResponsibility,
                                        (is_numeric($detailEvent[$i]["isPkp"]) ? $detailEvent[$i]["isPkp"] : 0),
                                        (is_numeric($detailEvent[$i]["margin"]) ? $detailEvent[$i]["margin"] : 0),
                                        #$detailEvent[$i]["bruttoMargin"],
                                        #$detailEvent[$i]["netMargin"],
                                        $isSameLocation,
                                        $isSameDate,
                                        $withoutResponsibility,
                                        $isSp,
                                        $detailEvent[$i]["sp"],
                                        $detailEvent[$i]["kota"],
                                );
                        
                        
                        $sql = "insert into event_item (event_id, tillcode, notes, supp_code, category_code, yds_responsibility, supp_responsibility, is_pkp, tax, " .
                                                        "same_location, same_date, without_responsibility, is_sp, special_price, city) ". 
                                "values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->db->query($sql, $params); 
                        
                        //update data pic supplier by gie
                        $sql = "SELECT DISTINCT b.supplier_code, b.name FROM event_item a JOIN mst_pic b ON(a.supp_code=b.supplier_code) WHERE a.event_id='$currval' AND b.name IS NOT NULL ";
                        $res = $this->db->query($sql)->result(); 
                        $ret = "";
                        
                        foreach($res as $r) {
                            $ret .= $r->name . ", ";
                        }
                        
                        $to =  rtrim($ret, ", ");

                        $sql = "UPDATE event SET toward='$to' WHERE id='$currval' ";
                        $this->db->query($sql);

                    /*    $cek = $this->is_null_toward($currval);
                        if ($cek==true){
                            $sql = "UPDATE event SET toward='$to' WHERE id='$currval' ";
                            $this->db->query($sql);
                        }
                        
*/

                }
                
                # date
                if ($isSameDate)
                        for ($i = 0; $i < sizeof($detailDate); $i++) {
                                $params = array($seq, $detailDate[$i]["dateStart"], $detailDate[$i]["dateEnd"]);
                                $sql = "insert into event_same_date (event_id, date_start, date_end) values (?, to_date(?, 'dd-mm-yyyy'), to_date(?, 'dd-mm-yyyy'))";
                                $this->db->query($sql, $params);        
                        }
                else {
                        for ($i = 0; $i < sizeof($detailDate); $i++) {
                                $params = array($seq, $detailDate[$i]["tillcode"], $detailDate[$i]["dateStart"], $detailDate[$i]["dateEnd"]);
                                $sql = "insert into event_date (event_id, tillcode, date_start, date_end) values (?, ?, to_date(?, 'dd-mm-yyyy'), to_date(?, 'dd-mm-yyyy'))";
                                $this->db->query($sql, $params);        
                        }      
                }
                
                # location
                if ($isSameLocation) {
                        for ($i = 0; $i < sizeof($detailLocation); $i++) {
                                $params = array($seq, $this->get_store_code($detailLocation[$i]["storeCode"]), $detailLocation[$i]["locationCode"]);
                                $sql = "insert into event_same_location (event_id, store_code, location_code) values (?, ?, ?)";
                                $this->db->query($sql, $params);        
                        }
                }
                else {
                        for ($i = 0; $i < sizeof($detailLocation); $i++) {
                                $params = array($seq, $detailLocation[$i]["tillcode"], $this->get_store_code($detailLocation[$i]["storeCode"]), $detailLocation[$i]["locationCode"]);
                                $sql = "insert into event_location (event_id, tillcode, store_code, location_code) values (?, ?, ?, ?)";
                                $this->db->query($sql, $params);        
                        }        
                }
                
                # update to null date field
                $params = array($seq);
                
                $sql = "update event_date set date_start = null where event_id = ? and date_start = '0001-01-01 BC'";
                $this->db->query($sql, $params);  
                $sql = "update event_date set date_end = null where event_id = ? and date_end = '0001-01-01 BC'";
                $this->db->query($sql, $params);  
                $sql = "update event_same_date set date_start = null where event_id = ? and date_start = '0001-01-01 BC'";
                $this->db->query($sql, $params);  
                $sql = "update event_same_date set date_end = null where event_id = ? and date_end = '0001-01-01 BC'";
                $this->db->query($sql, $params);  
                
                # end transaction
                $this->db->trans_complete();
                
                #return $this->db->trans_status();
                return $seq;
        }
        
        private function makeLetterNumber($num, $div) {
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
                        case "S":
                                $code = "S";
                                break;
                }
                
                return $div . str_pad($num, 5, "0", STR_PAD_LEFT) . "/SA.YDS/YG." . $code . "/" . date("m") . "/" . date("Y");
        }

        //function by gie
        public function filterStoreByTillcode($tillcode, $arrayMode = false) {
            $params = array($tillcode);
            $sql = "SELECT a.*, b.store_code, b.store_desc
                    FROM tillcode_detail a JOIN mst_store b ON(a.store_init=b.store_init)
                    WHERE tillcode = ? AND b.store_desc NOT ilike '%yomart%'
                    ORDER BY store_init ASC ";
            $query = $this->db->query($sql, $params);
            
            if ($arrayMode) 
                return $query->result_array();
            else
                return $query->result();
        }

        public function filterSupplierByTillcode($tillcode, $arrayMode = false) {
            $params = array($tillcode);
            $sql = "SELECT a.supp_code, a.supp_desc
                    FROM mst_supplier a JOIN mst_tillcode b ON(a.supp_code=b.supp_code)
                    WHERE b.tillcode = ? 
                    GROUP BY a.supp_code, a.supp_desc
                    ";
            $query = $this->db->query($sql, $params);
            
            if ($arrayMode) 
                return $query->result_array();
            else
                return $query->result();
        }

        public function filterBrandByTillcode($tillcode, $arrayMode = false) {
            $params = array($tillcode);
            $sql = "SELECT a.brand_code, a.brand_desc
                    FROM mst_brand a JOIN mst_tillcode b ON(a.brand_code=b.brand_code)
                    WHERE b.tillcode = ? 
                    ";
            $query = $this->db->query($sql, $params);
            
            if ($arrayMode) 
                return $query->result_array();
            else
                return $query->result();
        }

        public function filterKotaBySupplier($supplierCode, $arrayMode = false) {
            $params = array($supplierCode);
            $sql = "SELECT DISTINCT city
                    FROM mst_supplier 
                    WHERE supp_code = ? 
                    ";
            $query = $this->db->query($sql, $params);
            
            if ($arrayMode) 
                return $query->result_array();
            else
                return $query->result();
        }
        
        public function load_pic($supplierCode, $arrayMode = false) {
            $params = array($supplierCode);
            $sql = "SELECT id, name
                    FROM mst_pic
                    WHERE supplier_code = ? 
                    ORDER BY name ASC ";
            $query = $this->db->query($sql, $params);
            
            if ($arrayMode) 
                return $query->result_array();
            else
                return $query->result();
        }


        function get_store_code($p){
            $sql = "SELECT store_code FROM mst_store WHERE store_init='$p' ";
            
            $exe = $this->db->query($sql);
            $ret = $exe->row()->store_code;
            
            return $ret;
        }  

        function is_null_toward($p){
            $sql = "SELECT toward FROM event WHERE id='$p' ";
            
            $exe = $this->db->query($sql);
            $ret = $exe->row()->toward;
            
            if($ret==null or $ret=='')
                return true;
            else 
                return false;
            
            
        }    

        function get_currval_event(){
            $this->db->select("currval('event_seq') as currval");
            $q = $this->db->get()->row();
            $r = $q->currval;
            return $r;
        }

        public function cancel($id){
            $sql = "UPDATE event SET active='0' WHERE id='$id' ";
            
            $r = $this->db->query($sql);
            
            if ($r)
                return 1;
            else
                return 0;
        }
        
        
        
}