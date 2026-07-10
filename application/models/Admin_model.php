<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_model {
		
	public function verify($id_customer,$nomeja)
	{
		$q = "select R.*,C.customer_name from sh_rel_table R inner join sh_m_customer C on C.id = R.id_customer where R.id_table = '".$nomeja."' and C.id = '".$id_customer."' and left(C.create_date,10) = left(sysdate(),10) limit 1";
		return $this->db->query($q);
	}

	public function countOption($search = [])
	{
	    $this->db->from('sh_m_item_option o');
	    $this->db->join('sh_m_item i', 'o.item_code = i.no');
	    
	    $this->db->where('o.type', 'option');
	    $this->db->where('i.is_active', 1);

	    if(!empty($search['item_name'])){
	        $this->db->like('i.description', $search['item_name']);
	    }
	    if(!empty($search['type'])){
	        $this->db->like('o.type', $search['type']);
	    }
	    if(!empty($search['option'])){
	        $this->db->like('o.option_name', $search['option']);
	    }
	    if(!empty($search['status'])){
	        $this->db->like('o.status', $search['status']);
	    }

	    return $this->db->count_all_results();
	}

	public function get_option($limit, $start, $search = [])
	{   
	    $this->db->select('o.*, i.description as dsc');
	    $this->db->from('sh_m_item_option o');
	    $this->db->join('sh_m_item i', 'o.item_code = i.no');
	    
	    // Filter default
	    $this->db->where('o.type', 'option');
	    $this->db->where('i.is_active', 1);

	    // Tambahkan filter search jika ada
	    if(!empty($search['item_name'])){
	        $this->db->like('i.description', $search['item_name']);
	    }
	    if(!empty($search['type'])){
	        $this->db->like('o.type', $search['type']);
	    }
	    if(!empty($search['option'])){
	        $this->db->like('o.option_name', $search['option']); // ganti sesuai nama kolom di tabel option
	    }
	    if(!empty($search['status'])){
	        $this->db->like('o.status', $search['status']);
	    }

	    $this->db->limit($limit, $start);
	    $this->db->order_by('o.id','desc');
	    $query = $this->db->get();
	    return $query->result();
	}
	public function countAddon() {
	    return $this->db->where('type', 'addon')->count_all_results('sh_m_item_option');
	}

	public function get_Addon($limit, $start)
	{   
	    $this->db->select('o.*, i1.description as dsc,i2.description as adsc');
	    $this->db->from('sh_m_item_option o');
	    $this->db->join('sh_m_item i1', 'o.item_code = i1.no', 'left');
	    $this->db->join('sh_m_item i2', 'o.description = i2.no', 'left');
	    
	    $this->db->where('o.type', 'addon');
	    $this->db->where('i1.is_active', 1);
	    $this->db->limit($limit, $start);
	    $this->db->order_by('o.id', 'desc');
	    
	    $query = $this->db->get();
	    return $query->result();
	}

	public function get_item()
	{   
        $this->db->select('o.*');
        $this->db->from('sh_m_item o');
        $this->db->where('is_active', 1);
        $this->db->order_by('o.id','asc');
        $query = $this->db->get();
        return $query->result();
	}
	public function getIcon($type)
	{
		$this->db->select('d.*');
		   $this->db->from('sh_m_setup_so d');
		if ($type == 'add') {
			$this->db->limit(2);
		}else{
			$this->db->where('d.type', $type);
		}
		   $this->db->where('d.is_active', 1);
		    
		   $query = $this->db->get()->result();
		   return $query; 
	}
	public function getIconSET($type)
	{
		$this->db->select('d.*');
		   $this->db->from('sh_m_setup_so d');
		if ($type == 'add') {
			$this->db->limit(2);
		}else{
			$this->db->where('d.type', $type);
		}
		    
		   $query = $this->db->get()->result();
		   return $query; 
	}
		
	public function save($data, $where='') {
		if ($where == '') {
			$this->db->insert('sh_kritik_saran', $data);
			return $this->db->insert_id();
		}
		return $this->db->update('sh_kritik_saran', $data, $where);			
	}
	public function getColor()
	{   
        $this->db->select('o.*');
        $this->db->from('sh_m_setup_color_so o');
        $query = $this->db->get();
        return $query->result();
	}
	public function getColorCN()
	{   
        $this->db->select('o.*');
        $this->db->from('sh_m_setup_color_so o');
        $this->db->where('type','navbar');
        $query = $this->db->get();
        return $query->row();
	}
	public function getColorHD()
	{   
        $this->db->select('o.*');
        $this->db->from('sh_m_setup_color_so o');
        $this->db->where('type','header');
        $query = $this->db->get();
        return $query->row();
	}
	public function getColorBTN()
	{   
        $this->db->select('o.*');
        $this->db->from('sh_m_setup_color_so o');
        $this->db->where('type','button');
        $query = $this->db->get();
        return $query->row();
	}
	public function countUsers() {
	    return $this->db->count_all_results('sh_user_so');
	}
	public function get_users($limit, $start)
	{   
        $this->db->select('o.*');
        $this->db->from('sh_user_so o');
        $this->db->limit($limit, $start);
        $this->db->order_by('o.id','desc');
        $query = $this->db->get();
        return $query->result();
	}
	public function getEvent($limit, $start)
	{
		$this->db->select('o.*,c.customer_name,cb.cabang_name,t.id_table,t.status as st,c.id as id_customer');
        $this->db->from('sh_event_log o');
        $this->db->join('sh_m_customer c', 'o.id_customer = c.id', 'left');
        $this->db->join('sh_m_cabang cb', 'o.cabang = cb.id', 'left');
        $this->db->join('sh_rel_table t', 'c.id = t.id_customer' , 'left');
        $this->db->limit($limit, $start);
        $this->db->where('event_type','Login SO');
        $this->db->where('t.status !=','Available');
        $this->db->where('o.created_date',DATE('Y-m-d'));
        $this->db->group_by('o.description');
        $this->db->order_by('o.id','desc');
        $query = $this->db->get();
        return $query->result();
	}
	public function countSignout($table_number = NULL, $customer_name = NULL) 
	{
	    $this->db->select('o.*,c.customer_name,cb.cabang_name,t.id_table,t.status as st,c.id as id_customer');
        $this->db->from('sh_event_log o');
        $this->db->join('sh_m_customer c', 'o.id_customer = c.id', 'left');
        $this->db->join('sh_m_cabang cb', 'o.cabang = cb.id', 'left');
        $this->db->join('sh_rel_table t', 'c.id = t.id_customer' , 'left');
        $this->db->where('event_type','Login SO');
        $this->db->where('t.status !=','Available');
        $this->db->where('o.created_date',DATE('Y-m-d'));
        $this->db->group_by('o.description');
        $this->db->order_by('o.id','desc');

	    if ($table_number) {
	        $this->db->where('t.id_table', $table_number);
	    }
	    if ($customer_name) {
	        $this->db->where('c.customer_name', $customer_name);
	    }
	    $query = $this->db->get();
	    return $query->num_rows();
	}
	public function getSignout($limit = NULL, $start = NULL, $table_number = NULL, $customer_name = NULL) 
	{
		$this->db->select('o.*,c.customer_name,cb.cabang_name,t.id_table,t.status as st,c.id as id_customer');
        $this->db->from('sh_event_log o');
        $this->db->join('sh_m_customer c', 'o.id_customer = c.id', 'left');
        $this->db->join('sh_m_cabang cb', 'o.cabang = cb.id', 'left');
        $this->db->join('sh_rel_table t', 'c.id = t.id_customer' , 'left');
        $this->db->where('event_type','Login SO');
        $this->db->where('t.status !=','Available');
        $this->db->where('o.created_date',DATE('Y-m-d'));
        $this->db->group_by('o.description');
        $this->db->order_by('o.id','desc');
	    
	    // Menambahkan filter berdasarkan item_name
	    if ($table_number) {
	        $this->db->where('t.id_table', $table_number);
	    }
	    if ($customer_name) {
	        $this->db->where('c.customer_name', $customer_name);
	    }

	    $query = $this->db->get()->result();

	    return $query;
	}
	public function countPayment($table_number = NULL, $customer_name = NULL) 
	{
	    $this->db->select('ts.*,c.customer_name,t.id_table,t.status as st,c.id as id_customer');
        $this->db->from('sh_t_transactions ts');
        $this->db->join('sh_m_customer c', 'ts.id_customer = c.id', 'left');
        $this->db->join('sh_rel_table t', 'c.id = t.id_customer' , 'left');
        $this->db->join('sh_payments_so ps', 'ps.external_id = ts.external_id_so' , 'left');
        $this->db->where('ps.external_id IS NULL', null, false);
        // $this->db->where('t.status','Billing');
        $this->db->where('ts.external_id_so !=','');
        $this->db->where("ts.payment_date IS NULL");
        $this->db->where('DATE(ts.create_date)', DATE('Y-m-d'));

	    if ($table_number) {
	        $this->db->where('t.id_table', $table_number);
	    }
	    if ($customer_name) {
	        $this->db->where('c.customer_name', $customer_name);
	    }
	    $query = $this->db->get();
	    return $query->num_rows();
	}
	public function getPayment($limit = NULL, $start = NULL, $table_number = NULL, $customer_name = NULL) 
	{
		$this->db->select('ts.*,c.customer_name,t.id_table,t.status as st,c.id as id_customer');
        $this->db->from('sh_t_transactions ts');
        $this->db->join('sh_m_customer c', 'ts.id_customer = c.id', 'left');
        $this->db->join('sh_rel_table t', 'c.id = t.id_customer' , 'left');
        $this->db->join('sh_payments_so ps', 'ps.external_id = ts.external_id_so' , 'left');
        $this->db->where('ps.external_id IS NULL', null, false);
        // $this->db->where('t.status','Billing');
        $this->db->where('ts.external_id_so !=','');
        $this->db->where("ts.payment_date IS NULL");
        $this->db->where('DATE(ts.create_date)', DATE('Y-m-d'));
	    
	    // Menambahkan filter berdasarkan item_name
	    if ($table_number) {
	        $this->db->where('t.id_table', $table_number);
	    }
	    if ($customer_name) {
	        $this->db->where('c.customer_name', $customer_name);
	    }

	    $query = $this->db->get()->result();

	    return $query;
	}
	public function countEvent()
	{
		return $this->db->where('event_type','Login SO')->where('created_date',DATE('Y-m-d'))->group_by('description')->count_all_results('sh_event_log');
	}
	public function cekpw($po,$usr)
	{
		$psw = md5($po);
		$this->db->select('o.*');
        $this->db->from('sh_user_so o');
        $this->db->where('username',$usr);
        $this->db->where('password',$psw);
        $this->db->order_by('o.id','desc');
        $query = $this->db->get();
        return $query->result();
	}
	public function countItem($sub_category = null, $item_name = null, $div = null)
	{
	    $this->db->from('sh_m_item');
	    if ($sub_category) {
	        $this->db->where('sub_category', $sub_category);
	    }
	    if ($div) {
	        if (is_numeric($div) && $div >= 1 && $div <= 26) {
	            $this->db->where("monitor{$div}", 1);
	        } else {
	            return 0;
	        }
	    }
	    if ($item_name) {
	        $this->db->like('description', $item_name);
	    }
	    $this->db->where('is_active', 1);
	    return $this->db->count_all_results();
	}
	public function countItemOnline($item_name = null)
	{
	    $this->db->from('sh_m_item_online');
	    if ($item_name) {
	        $this->db->like('item_name_dinein', $item_name);
	    }
	    return $this->db->count_all_results();
	}

	public function countItemEvent($sub_category = null, $item_name = null)
	{
	    $this->db->from('sh_m_item i');
		$this->db->join('sh_m_item_event e', 'e.item_code = i.no', 'inner'); 
		$this->db->where('i.is_active', 1);
		if ($sub_category) {
		    $this->db->where('i.sub_category', $sub_category);
		}
		if ($item_name) {
		    $this->db->like('i.description', $item_name);
		}

		return $this->db->count_all_results();

	}

	public function countPackage($item_name = null)
	{
	    $this->db->from('sh_m_item i');
	    $this->db->where('is_paket_so',1);
		if ($item_name) {
		    $this->db->like('i.description', $item_name);
		}

		return $this->db->count_all_results();

	}

	public function get_itemData($limit = null, $start = null, $sub_category = null, $item_name = null, $div = null)
	{
	    if ($limit !== null && $start !== null) {
	        $this->db->limit($limit, $start);
	    }

	    if ($div) {
	        if (is_numeric($div) && $div >= 1 && $div <= 26) {
	            $this->db->where("monitor{$div}", 1);
	        } else {
	            return [];
	        }
	    }

	    if ($sub_category) {
	        $this->db->where('sub_category', $sub_category);
	    }

	    if ($item_name) {
	        $this->db->like('a.description', $item_name);
	    }

	    $this->db->where('a.is_active', 1);

	    // 🔥 SELECT (tambahkan image)
	    $this->db->select('a.*, b.image_path');

	    // 🔥 FROM + JOIN
	    $this->db->from('sh_m_item a');
	    $this->db->join('sh_m_item_image b', 'b.item_code = a.no', 'left');

	    $query = $this->db->get();

	    return $query->result();
	}
	public function get_itemDine()
	{
	    $query = $this->db->where('sub_category !=','Online')->where('is_active',1)->get('sh_m_item');
	    return $query->result();
	}
	public function get_itemOnline()
	{
	    $query = $this->db->where('sub_category','Online')->where('is_active',1)->get('sh_m_item');
	    return $query->result();
	}
	public function get_itemDataOnline($limit = null, $start = null,$item_name = null)
	{
	    if ($limit !== null && $start !== null) {
	        $this->db->limit($limit, $start);
	    }
	    if ($item_name) {
	        $this->db->like('item_name_dinein', $item_name);
	    }
	    // $this->db->where('is_active', 1);
	    $query = $this->db->get('sh_m_item_online');
	    return $query->result();
	}
	public function get_Package($limit = null, $start = null, $item_name = null)
	{
	    if ($limit !== null && $start !== null) {
	        $this->db->limit($limit, $start);
	    }
	    if ($item_name) {
	        $this->db->like('description', $item_name);
	    }
	    $this->db->where('is_paket_so',1);
	    $query = $this->db->get('sh_m_item');
	    return $query->result();
	}
	
	public function getitempackage($ip)
	{
		$this->db->select('i.description,i.no,e.item_code,e.varian_category,e.max_qty,e.is_active,i.need_stock,i.stock,i.is_sold_out,i.id as idi,e.id'); // Ambil kolom dari kedua tabel
		$this->db->from('sh_m_item i');
		$this->db->join('sh_m_varian_option e', 'e.item_code = i.no', 'inner'); 
		$this->db->where('e.parent_code',$ip);
		$this->db->where('i.is_active', 1);
		$this->db->group_by('e.item_code');
		$query = $this->db->get();
		return $query->result();
	}
	public function getitem($cek=null)
	{
		$this->db->select('i.description,i.no'); // Ambil kolom dari kedua tabel
		$this->db->from('sh_m_item i');
		if ($cek == 'package') {
			$this->db->where('is_paket_so',0);
		}
		$this->db->where('i.is_active', 1);
		

		$query = $this->db->get();
		return $query->result();
	}
	public function get_itemEventData($limit = null, $start = null, $sub_category = null, $item_name = null)
	{
	    $this->db->select('i.description,i.no,e.id, e.date_from, e.date_to, e.time_from, e.time_to'); // Ambil kolom dari kedua tabel
		$this->db->from('sh_m_item i');
		$this->db->join('sh_m_item_event e', 'e.item_code = i.no', 'inner'); 
		if ($sub_category) {
		    $this->db->where('i.sub_category', $sub_category);
		}
		if ($item_name) {
		    $this->db->like('i.description', $item_name);
		}
		if ($limit !== null && $start !== null) {
		    $this->db->limit($limit, $start);
		}

		$query = $this->db->get();
		return $query->result();

	}

	public function getLogo()
	{
		$this->db->select('d.*');
		$this->db->from('sh_m_setup_so d');
		$this->db->where('type','logo');
		$this->db->limit(1);

		$query = $this->db->get()->row();
		return $query; 
	}
	public function countAsignCategory($sub_category = NULL, $sub_categoryso = NULL, $item_name = NULL, $signature = NULL) 
	{
	    $this->db->select('i.sub_category_so, i.image_path');
	    $this->db->from('sh_m_item i');
	    $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'left');
	    $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
	    $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
	    // $this->db->where('i.sub_category_so !=', '');

	    // Menambahkan filter berdasarkan sub_category
	    if ($sub_category) {
	        $this->db->where('i.sub_category', $sub_category);
	    }

	    // Menambahkan filter berdasarkan sub_categoryso
	    if ($sub_categoryso) {
	        $this->db->where('i.sub_category_so', $sub_categoryso);
	    }

	    // Menambahkan filter berdasarkan item_name
	    if ($item_name) {
	        $this->db->like('i.description', $item_name);
	    }

	    // Menambahkan filter berdasarkan signature
	    if ($signature !== NULL) {
	    	if ($signature == 'all') {
	    		
	    	}else{
	    		$this->db->where('i.chef_recommended', $signature);
	    	}
	        
	    }

	    // Mengambil hasil query dan menghitung jumlah barisnya
	    $query = $this->db->get();
	    return $query->num_rows();
	}

	public function get_AsignCategory($limit = NULL, $start = NULL, $sub_category = NULL, $sub_categoryso = NULL, $item_name = NULL, $signature = NULL) 
	{
	    $this->db->select('i.*');
	    $this->db->from('sh_m_item i');
	    $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'left');
	    $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
	    $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
	    // $this->db->where('i.sub_category_so !=', '');
	    // Menambahkan filter berdasarkan sub_category
	    if ($sub_category) {
	        $this->db->where('i.sub_category', $sub_category);
	    }

	    // Menambahkan filter berdasarkan sub_categoryso
	    if ($sub_categoryso) {
	        $this->db->where('i.sub_category_so', $sub_categoryso);
	    }

	    // Menambahkan filter berdasarkan item_name
	    if ($item_name) {
	        $this->db->like('i.description', $item_name);
	    }

	    // Menambahkan filter berdasarkan signature
	    if ($signature !== NULL) {
	    	if ($signature == 'all') {
	    		
	    	}else{
	    		$this->db->where('i.chef_recommended', $signature);
	    	}
	        
	    }

	    if ($limit) {
	        $this->db->limit($limit, $start);
	    }

	    $this->db->order_by('i.sub_category_so', 'asc');

	    $query = $this->db->get()->result();

	    return $query;
	}

	public function countCategory() {
		$this->db->select('i.sub_category, i.image_path');
	    $this->db->from('sh_m_item i');
	    $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'left');
	    $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
	    $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
	    $this->db->where_in('i.category', array('SIAP SAJI', 'PROSES'));
	    $this->db->group_by('i.sub_category');
	    $this->db->order_by('s.id', 'asc');
	    
	    // Mengambil hasil query dan menghitung jumlah barisnya
	    $query = $this->db->get();
	    return $query->num_rows();
	}
	public function get_Divisi($limit = NULL, $start = NULL)
	{
	    $excluded_kode = ['C9', 'C11', 'C12', 'C13', 'C14', 'C15', 'C16', 'C17', 'C18'];

	    $this->db->select('i.*');
	    $this->db->from('sh_m_checker i');
	    if ($limit) {
	        $this->db->limit($limit, $start);
	    }
	    $this->db->where_not_in('kode', $excluded_kode);
	    $this->db->order_by('i.id', 'asc');

	    return $this->db->get()->result();
	}

	public function get_Category($limit = NULL,$start = NULL)
	{
		$this->db->select('i.*');
		$this->db->from('sh_m_item i');
		$this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'left');
		// $this->db->where('(s.weekday IS NULL OR s.weekday = "")'); 
		// $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
		$this->db->where('i.is_active', 1);
		$this->db->where('sub_category !=','none');
		$this->db->where('i.sub_category !=','');
		if ($limit) {
			$this->db->limit($limit, $start);
		} 
		$this->db->group_by('i.sub_category');
		$this->db->order_by('i.sub_category', 'asc');

	              
	    $query = $this->db->get()->result();
	    
	    return $query;
	}
	public function get_Category_Signature()
	{
		$this->db->select('*');
		$this->db->from('sh_m_item_sub_category');
		$this->db->where('description','Signature');
	              
	    $query = $this->db->get()->row();
	    
	    return $query;
	}
	public function get_Category_so($limit = NULL,$start = NULL)
	{
		$this->db->select('i.*');
		$this->db->from('sh_m_item_sub_category i');
		$this->db->where('i.is_active',1);
		if ($limit) {
			$this->db->limit($limit, $start);
		}
		$this->db->order_by('i.description', 'asc');

	              
	    $query = $this->db->get()->result();
	    return $query;
	}
	public function cekADDON() {
        // Query the database to get the existing item numbers
        $this->db->select('item_code');
        $query = $this->db->where('type','addon')->get('sh_m_item_option');
        
        // Return the results as an array of item numbers
        return array_map(function($row) {
            return $row['item_code'];
        }, $query->result_array());
    }
    public function cekADDONDESC() {
        // Query the database to get the existing item numbers
        $this->db->select('description');
        $query = $this->db->where('type','addon')->get('sh_m_item_option');
        
        // Return the results as an array of item numbers
        return array_map(function($row) {
            return $row['description'];
        }, $query->result_array());
    }
    public function cekOPTION() {
        // Query the database to get the existing item numbers
        $this->db->select('item_code');
        $query = $this->db->where('type','option')->get('sh_m_item_option');
        
        // Return the results as an array of item numbers
        return array_map(function($row) {
            return $row['item_code'];
        }, $query->result_array());
    }
    public function insert_csv($data) {
        return $this->db->insert_batch('sh_m_item_event', $data);
    }
    public function noterakhir()
        {
            $this->db->select('no');
            $this->db->from('sh_m_item i');
            $this->db->limit(1);
            $this->db->order_by('no','desc');
            $this->db->order_by('id','desc');
            
            
            $query = $this->db->get();
            return $query->row();
        }
    public function getCategory()
        {
            $this->db->select('*');
            $this->db->from('sh_m_item_category');
            // $this->db->where('is_active',1);
            return $this->db->get()->result();
        }
    public function getmonitor()
        {
            $this->db->select('*');
            $this->db->from('sh_m_checker');
            // $this->db->where('is_active',1);
            return $this->db->get()->result();
        }
    public function getSub($tipe=NULL)
        {
            $this->db->select('*');
            $this->db->from('sh_m_item_sub_category');
            // $this->db->where('is_active',1);
            
            return $this->db->get()->result();
        }
        public function getitempaket()
        {
        	$this->db->select('*');
            $this->db->from('sh_m_item_package');
            $this->db->where('is_active',1);
            return $this->db->get()->result();
        }

	public function getPesananHariIni()
	{
		$date = date('Y-m-d');
		// Mengambil data pesanan dari sh_t_transaction_details per hari ini (group per id_trans)
		$query = "SELECT d.id_trans as id, 
		                 MAX(c.customer_name) as customer_name, 
		                 MAX(d.selected_table_no) as table_number, 
		                 SUM(CASE WHEN d.is_cancel = 0 THEN (d.unit_price * d.qty) ELSE 0 END) as total,
		                 CASE 
		                    WHEN MAX(d.is_cancel) = 1 THEN 'Canceled'
		                    WHEN MAX(d.is_paid) = 1 THEN 'Paid'
		                    ELSE 'Pending'
		                 END as status
		          FROM sh_t_transaction_details d
		          JOIN sh_t_transactions t ON t.id = d.id_trans
		          LEFT JOIN sh_m_customer c ON c.id = t.id_customer
		          WHERE LEFT(d.created_date, 10) = '".$date."'
		          GROUP BY d.id_trans
		          ORDER BY d.id_trans DESC";
		return $this->db->query($query)->result();
	}

	public function getPesananDetail($id_trans)
	{
		$query = "SELECT d.qty, d.unit_price, i.description as item_name, i.image_path,
		          CASE 
                    WHEN d.end_time_order IS NOT NULL THEN 'COMPLETED'
                    WHEN d.is_complete = d.qty AND d.runner_by IS NOT NULL THEN 'DELIVERED'
                    ELSE 'IN PROGRESS'
                  END as item_status
		          FROM sh_t_transaction_details d
		          JOIN sh_m_item i ON i.no = d.item_code
		          WHERE d.id_trans = '".$this->db->escape_str($id_trans)."' AND d.is_cancel = 0";
		return $this->db->query($query)->result();
	}

	public function getKitchenOrders()
	{
		$date = date('Y-m-d');
		$query = "SELECT d.id_trans as id, 
		                 MAX(d.selected_table_no) as table_number, 
		                 MAX(c.customer_name) as customer_name, 
		                 SUM(CASE WHEN d.is_cancel = 0 THEN d.qty ELSE 0 END) as total_items,
		                 SUM(CASE WHEN d.is_cancel = 0 AND d.is_paid = 0 THEN d.qty ELSE 0 END) as awaiting_qty,
		                 SUM(CASE WHEN d.is_cancel = 0 AND d.is_paid = 1 AND d.runner_by IS NULL AND d.end_time_order IS NULL THEN d.qty ELSE 0 END) as proses_qty,
		                 SUM(CASE WHEN d.is_cancel = 0 AND d.runner_by IS NOT NULL AND d.end_time_order IS NULL THEN d.qty ELSE 0 END) as deliver_qty,
		                 SUM(CASE WHEN d.is_cancel = 0 AND d.end_time_order IS NOT NULL THEN d.qty ELSE 0 END) as complete_qty
		          FROM sh_t_transaction_details d
		          JOIN sh_t_transactions t ON t.id = d.id_trans
		          LEFT JOIN sh_m_customer c ON c.id = t.id_customer
		          WHERE LEFT(d.created_date, 10) = '".$date."' AND d.is_cancel = 0
		          GROUP BY d.id_trans
		          ORDER BY d.id_trans DESC";
		return $this->db->query($query)->result();
	}

	public function getKitchenOrderItems($id_trans)
	{
		$query = "SELECT d.id, d.item_code, d.qty, d.unit_price, d.extra_notes, d.is_paid, d.runner_by, d.end_time_order,
	                         i.description as item_name, i.image_path,
	                         CASE 
	                            WHEN d.end_time_order IS NOT NULL THEN 'COMPLETED'
	                            WHEN d.runner_by IS NOT NULL AND d.end_time_order IS NULL THEN 'DELIVERED'
	                            WHEN d.is_paid = 1 THEN 'IN PROGRESS'
	                            ELSE 'AWAITING'
	                         END as item_status
	                  FROM sh_t_transaction_details d
	                  JOIN sh_m_item i ON i.no = d.item_code
	                  WHERE d.id_trans = '".$this->db->escape_str($id_trans)."' AND d.is_cancel = 0
	                  ORDER BY d.id ASC";
		return $this->db->query($query)->result();
	}
}