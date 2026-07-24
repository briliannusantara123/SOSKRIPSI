<?php 
	class Item_model extends CI_model {

	public function getData($tipe, $sub_category)
	{
	    $this->db->select("
	        i.sub_category, i.sub_category_so, i.description, COALESCE(im.image_path, i.image_path) AS image_path, i.id, 
	        i.harga_weekday, i.harga_weekend, i.harga_holiday, i.no, i.product_info, i.need_stock, 
	        i.is_sold_out, c.qty as cart_qty, c.id_customer, i.with_option, i.stock, d.qty as trans_qty,
	        e.date_from, e.date_to, e.time_from, e.time_to, e.item_code as event_item_code,e.type
	    ");

	    $this->db->from('sh_m_item i');
	    $this->db->where("i.is_active", 1);
	    $this->db->where("i.harga_weekday >", 10);
	    $this->db->where("i.is_paket_so", 0);
	    $this->db->where("i.sub_category !=", 'Online');
		$this->db->where("i.sub_category !=", 'Buffet');
	   	$this->db->where("i.sub_category !=", 'PAKET');
	    $this->db->where_not_in("i.sub_category", ["FREE"]);

	    // FILTER KATEGORI
	    // if ($tipe == 'Makanan') {
	    //     $this->db->where_in("i.category", ["SIAP SAJI", "PROSES"]);
	    // } elseif ($tipe == 'Minuman') {
	    //     $this->db->where("i.category", "MINUMAN");
	    // } elseif ($tipe == 'CAKE%20DAN%20BAKERY') {
	    //     $this->db->where("i.category", "CAKE DAN BAKERY");
	    // }

	    // Pastikan ada stok
	    $this->db->group_start();
	    for ($m = 1; $m <= 26; $m++) {
	        $this->db->or_where("i.monitor{$m} !=", 0);
	    }
	    $this->db->group_end();

	    // FILTER SIGNATURE / SUB CATEGORY
	    if ($sub_category == "Signature") {
	        $this->db->where("chef_recommended", 1);
	    } else {
	        $this->db->group_start();
	        $this->db->where("LOWER(i.sub_category)", strtolower(urldecode($sub_category)));
	        $this->db->or_where("LOWER(i.sub_category_so)", strtolower(urldecode($sub_category)));
	        $this->db->group_end();
	    }

	    // JOIN tabel event (tanpa filter event, biar nanti filter di controller)
	    $this->db->join("sh_m_item_event e", "i.no = e.item_code", "left");

	    // JOIN tabel cart & transaksi
	    $this->db->join("sh_cart c", "i.no = c.item_code", "left");
	    $this->db->join("sh_t_transaction_details d", "i.no = d.item_code", "left");
	    $this->db->join("sh_m_item_image im", "i.no = im.item_code", "left");

	    // Group dan urutkan
	    $this->db->group_by("i.id");
	    $this->db->order_by("i.description", "asc");

	    return $this->db->get()->result();
	}
	public function getDataAll($tipe)
{
    $this->db->select("
        i.sub_category, i.description, i.image_path,
        i.harga_weekday, i.harga_weekend, i.harga_holiday,
        e.date_from, e.date_to, e.time_from, e.time_to,
        e.item_code as event_item_code, e.type
    ");

    $this->db->from('sh_m_item i');
    $this->db->join('sh_m_item_event e', 'i.no = e.item_code', 'left');

    $this->db->where('i.is_active', 1);
    $this->db->where('i.harga_weekday >', 10);
    $this->db->where_not_in('i.sub_category', ['Online','Buffet','PAKET','FREE']);

    // if ($tipe === 'Makanan') {
    //     $this->db->where_in('i.category', ['SIAP SAJI','PROSES']);
    // } else {
    //     $this->db->where('i.category', 'MINUMAN');
    // }

    return $this->db->get()->result();
}

	public function getDataTEST($tipe, $sub_category)
{
    $this->db->select("
        i.sub_category, i.sub_category_so, i.description, i.image_path, i.id, 
        i.harga_weekday, i.harga_weekend, i.harga_holiday, i.no, i.product_info, i.need_stock, 
        i.is_sold_out, c.qty as cart_qty, c.id_customer, i.with_option, i.stock, d.qty as trans_qty,
        e.date_from, e.date_to, e.time_from, e.time_to, e.item_code as event_item_code
    ");

    $this->db->from('sh_m_item i');
    $this->db->where("i.is_active", 1);
    $this->db->where("i.harga_weekday >", 10);
    $this->db->where("i.is_paket_so", 0);
    $this->db->where_not_in("i.sub_category", ["FREE"]);

    // FILTER KATEGORI
    // if ($tipe == 'Makanan') {
    //     $this->db->where_in("i.category", ["SIAP SAJI", "PROSES"]);
    // } elseif ($tipe == 'Minuman') {
    //     $this->db->where("i.category", "MINUMAN");
    // } elseif ($tipe == 'CAKE%20DAN%20BAKERY') {
    //     $this->db->where("i.category", "CAKE DAN BAKERY");
    // }

    // Pastikan ada stok
    $this->db->group_start();
    for ($m = 1; $m <= 26; $m++) {
        $this->db->or_where("i.monitor{$m} !=", 0);
    }
    $this->db->group_end();

    // FILTER SIGNATURE / SUB CATEGORY
    if ($sub_category == "Signature") {
        $this->db->where("chef_recommended", 1);
    } else {
        $this->db->group_start();
        $this->db->where("LOWER(i.sub_category)", strtolower(urldecode($sub_category)));
        $this->db->or_where("LOWER(i.sub_category_so)", strtolower(urldecode($sub_category)));
        $this->db->group_end();
    }

    // JOIN tabel event (tanpa filter event, biar nanti filter di controller)
    $this->db->join("sh_m_item_event e", "i.no = e.item_code", "left");

    // JOIN tabel cart & transaksi
    $this->db->join("sh_cart c", "i.no = c.item_code", "left");
    $this->db->join("sh_t_transaction_details d", "i.no = d.item_code", "left");

    // Group dan urutkan
    $this->db->group_by("i.id");
    $this->db->order_by("i.description", "asc");

    return $this->db->get()->result();
}


	public function getDatabyID($id)
	{
	    $this->db->select("
	        i.*,
	        COALESCE(im.image_path, i.image_path) as image_path
	    ");

	    $this->db->from('sh_m_item i');
	    $this->db->join('sh_m_item_image im', 'i.no = im.item_code', 'left');

	    $this->db->where('i.id', $id);

	    $query = $this->db->get();
	    return $query->row();
	}
	public function get_stock($cabang, $item_code=null)
	{
		if($cabang !='0'){
			$this->db->where(["i.cabang"=> $cabang]);
		}

		if($item_code !=null) {
			$where = "i.no = '".$item_code."'";
			$this->db->where($where);
		}

		$this->db->select("i.*")
			->from("sh_m_item i")
			->order_by("i.id", "asc");
		return $this->db->get();
	}
	public function getDataC($item_code)
	{
	    $date = date('Y-m-d');
	    $this->db->join('sh_m_item i', 'c.item_code = i.no', 'left');
	    $this->db->select('i.stock, i.need_stock, c.*');
	    $this->db->where("left(c.entry_date, 10) =", $date);  // Fix the comparison
	    $this->db->where_in('c.item_code', $item_code);

	    // Get single row
	    return $this->db->get('sh_cart c')->row();  // Use row() for a single result
	}

	public function getDataCP($item_code)
	{
	    $date = date('Y-m-d');
	    $this->db->join('sh_m_item i', 'c.item_code = i.no', 'left');
	    $this->db->select('i.stock, i.need_stock, c.*');
	    $this->db->where("left(c.entry_date, 10) =", $date);  // Fix the comparison
	    $this->db->where_in('c.item_code', $item_code);

	    // Get single row
	    return $this->db->get('sh_cart_details c')->row();  // Use row() for a single result
	}


	public function getDataCAddon($item_code)
	{
		$date = date('Y-m-d');
		$where ="left(c.entry_date,10) ='".$date."'";
		$this->db->join('sh_m_item i', 'c.item_code = i.no', 'left');
		$this->db->select('i.stock,i.need_stock,c.*');
		$this->db->where($where);
		$this->db->where_in('c.item_code',$item_code);

		return $this->db->get('sh_cart c')->result();
	}
	public function getPromo($sub_category)
	{
		$sub = str_replace('%20', ' ', $sub_category);
		$tanggal = date('Y-m-d');
		$jam = date("H");
		$where = "'".$tanggal."' between p.promo_start_date and p.promo_end_date ";
		$wherejam = "'".$jam."' between p.promo_from and p.promo_to ";
	            $this->db->select('*');
	            $this->db->from('sh_m_promo p');
	            $this->db->like('filter_value', $sub);
	            $this->db->where($where);
	            $this->db->where($wherejam);
	            $query = $this->db->get();

	            return $result = $query->row();
	}

		public function get_holiday($tanggal='all')
	{
		if($tanggal != 'all'){
			$this->db->where(["h.holiday_date"=> $tanggal]);
		}

		$this->db->select("h.*")
			->from("sh_m_holiday h")
			->order_by("h.id", "asc");
		return $this->db->get()->row();
	}
	public function get_promo($ic,$tanggal)
	{
		$this->db->select("sub_category")
			->from("sh_m_item")
			->where('no',$ic);
		$sub = $this->db->get()->row('sub_category');
		if($tanggal !='0'){
			$where = "'".$tanggal."' between p.promo_start_date and p.promo_end_date and p.promo_type='Discount' and filter_value = '".$sub."'";
			$this->db->where($where);
		}
	 
		$this->db->select("p.*")
			->from("sh_m_promo p")
			->order_by("p.id", "asc");
		return $this->db->get();
	}

	//item info promo
	public function get_info_item($item_code, $data)
		{
			$filter = $data["promo_filter"];
			$filter_value = $data["filter_value"];
			$filter_value1 = $data["filter_value1"];
			$filter_value2 = $data["filter_value2"];
			$filter_arr = explode(',',$filter_value);
			$filter_arr1 = explode(',',$filter_value1);
			$filter_arr2 = explode(',',$filter_value2);
			$filter0 = "";
			$filter1 = "";
			$filter2 = "";
			if($filter_value != ""){
				for($f=0; $f < sizeof($filter_arr); $f++){
					if($filter0 == ""){
						$filter0 = "'".$filter_arr[$f]."'";
					}else{
						$filter0 .= ",'".$filter_arr[$f]."'";
					}
				}
			}
			if($filter_value1 != ""){
				for($f1=0; $f1 < sizeof($filter_arr1); $f1++){
					if($filter1 == ""){
						$filter1 = "'".$filter_arr1[$f1]."'";
					}else{
						$filter1 .= ",'".$filter_arr1[$f1]."'";
					}
				}
			}
			if($filter_value2 != ""){
				for($f2=0; $f2 < sizeof($filter_arr2); $f2++){
					if($filter2 == ""){
						$filter2 = "'".$filter_arr2[$f2]."'";
					}else{
						$filter2 .= ",'".$filter_arr2[$f2]."'";
					}
				}
			}
			$where = "i.no = '".$item_code."'";
			if($filter != ''){
				if($filter_value != '' && $filter_value1 != '' && $filter_value2 != ''){
					$where .= " and i.".$filter." in (".$filter0.",".$filter1.",".$filter2.")";
				}else if($filter_value != '' && $filter_value1 != '' && $filter_value2 == ''){
					$where .= " and i.".$filter." in (".$filter0."',".$filter1.")";
				}else if($filter_value != '' && $filter_value1 == '' && $filter_value2 != ''){
					$where .= " and i.".$filter." in (".$filter0.",".$filter2.")";
				}else if($filter_value == '' && $filter_value1 != '' && $filter_value2 != ''){
					$where .= " and i.".$filter." in (".$filter1.",".$filter2.")";
				}else if($filter_value != '' && $filter_value1 == '' && $filter_value2 == ''){
					$where .= " and i.".$filter." in (".$filter0.")";
				}else if($filter_value == '' && $filter_value1 != '' && $filter_value2 == ''){
					$where .= " and i.".$filter." in (".$filter1.")";
				}else if($filter_value == '' && $filter_value1 == '' && $filter_value2 != ''){
					$where .= " and i.".$filter." in (".$filter2.")";
				}
				
			}
			$this->db->where($where);
			$this->db->select("i.*")
				->from("sh_m_item i")
				->order_by("i.id", "asc");
			return $this->db->get();
		}
		public function cekSignature()
		{
			$this->db->select('*');
		    $this->db->from('sh_m_item_sub_category');
		    $this->db->where('description', 'Signature');
		    $this->db->where("LPAD(time_from, 2, '0') <=", date('H'));
			$this->db->where("LPAD(time_to, 2, '0') >=", date('H'));
		    $cek = $this->db->get()->row();
		    return $cek;
		}

		public function sub_categoryOLD()
		{
		    // Query pertama
		    $this->db->select('s.description as sub_category, s.id');
		    $this->db->from('sh_m_item_sub_category s');
		    $this->db->where('s.is_active', 1);
		    // $this->db->where('s.description !=', 'Pendamping');
		    $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
		    $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
		    $this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));
		    $query1 = $this->db->get()->result_array();

		    // Query kedua
		    $this->db->select('i.sub_category_so AS sub_category, i.image_path, s.id');
		    $this->db->from('sh_m_item_sub_category s');
		    $this->db->join('sh_m_item i', 's.description = i.sub_category', 'left');
		    $this->db->where('i.is_active_so', 1);
		    $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
		    $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
		    $this->db->where('i.sub_category_so IS NOT NULL');
		    $this->db->where('i.sub_category_so !=', '');
		    $this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));
		    $this->db->group_by('i.sub_category_so, s.id');
		    $query2 = $this->db->get()->result_array();

		    // Query ketiga (untuk chef_recommended)
		    $this->db->select('"Signature" AS sub_category, i.image_path, s.id');
		    $this->db->from('sh_m_item_sub_category s');
		    $this->db->join('sh_m_item i', 's.description = i.sub_category', 'left');
		    $this->db->where('s.is_active', 1);
		    $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
		    $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
		    $this->db->where('i.sub_category IS NOT NULL');
		    $this->db->where('i.sub_category !=', '');
		    $this->db->limit(1);
		    $this->db->where('i.chef_recommended', 1);
		    $this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));
		    $this->db->group_by('i.sub_category_so, s.id');
		    $query3 = $this->db->get()->result_array();

		    // Gabungkan hasil dengan urutan: query3, query1
		    $result = array_merge($query3, $query1);

		    // Cek kolom 'sort' pada sub_category 'Signature'
		    $this->db->select('*');
		    $this->db->from('sh_m_item_sub_category');
		    $this->db->where('description', 'Signature');
		    $cek = $this->db->get()->row();

		    // Jika `sort` bernilai 0, atur urutan Signature di awal
		    if (isset($cek->sort) && $cek->sort == 0) {
		        usort($result, function ($a, $b) {
		            if ($a['sub_category'] === 'Signature') {
		                return -1; // Signature tetap di awal
		            }
		            if ($b['sub_category'] === 'Signature') {
		                return 1; // Signature tetap di awal
		            }
		            if ($a['id'] < $b['id']) {
		                return -1;
		            } elseif ($a['id'] > $b['id']) {
		                return 1;
		            } else {
		                return 0;
		            }
		        });
		    }

		    return $result;
		}


		public function sub_categoryTEST()
		{
		    $result = [];

		    // Query pertama
		    $this->db->select('s.description as sub_category, s.id');
			$this->db->from('sh_m_item_sub_category s');
			$this->db->where('s.is_active', 1);
			$this->db->where('(s.weekday IS NULL OR s.weekday = "")');
			$this->db->where('(s.weekend IS NULL OR s.weekend = "")');
			$this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));

			$this->db->where('EXISTS (
			    SELECT 1 FROM sh_m_item i 
			    WHERE i.is_active = 1  
			    AND (
			        CASE 
			            WHEN s.description = "Signature" THEN i.chef_recommended = 1
			            ELSE i.sub_category = s.description OR (i.chef_recommended = 1 AND i.sub_category = s.description)
			        END
			    )
			    AND (
			        NOT EXISTS (SELECT 1 FROM sh_m_item_event c WHERE c.item_code = i.no) 
			        OR EXISTS (
			            SELECT 1 FROM sh_m_item_event c 
			            WHERE c.item_code = i.no
			            AND (
			                (c.time_from IS NOT NULL AND c.time_to IS NOT NULL 
			                    AND HOUR(NOW()) BETWEEN c.time_from AND c.time_to) 
			                OR (c.date_from IS NOT NULL AND c.date_to IS NOT NULL 
			                    AND CURDATE() BETWEEN c.date_from AND c.date_to)
			            )
			        )
			    )
			)', null, false);



		    $query1 = $this->db->get()->result_array();

		    // Query kedua (Sub Category dari sh_m_item yang memiliki item aktif)
		    $this->db->select('i.sub_category_so AS sub_category, i.image_path, s.id');
		    $this->db->from('sh_m_item_sub_category s');
		    $this->db->join('sh_m_item i', 's.description = i.sub_category_so', 'left');
		    $this->db->where('i.is_active_so', 1);
		    $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
		    $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
		    $this->db->where('i.sub_category_so IS NOT NULL');
		    $this->db->where('i.sub_category_so !=', '');
		    $this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));
		    $this->db->where('EXISTS (SELECT 1 FROM sh_m_item i2 WHERE i2.sub_category_so = i.sub_category_so AND i2.is_active = 1)', null, false); // Cek item aktif
		    $this->db->group_by('i.sub_category_so, s.id');
		    $query2 = $this->db->get()->result_array();

		    // Query ketiga (Signature)
		    $this->db->select('description AS sub_category');
		    $this->db->from('sh_m_item_sub_category');
		    $this->db->where('description', 'Signature');
		    $this->db->where("LPAD(time_from, 2, '0') <=", date('H'));
		    $this->db->where("LPAD(time_to, 2, '0') >=", date('H'));
		    $query3 = $this->db->get()->result_array();

		    // Query keempat (Event-based sub_category)
		    $this->db->select('i.sub_category, s.weekday, s.weekend, s.time_from, s.time_to, s.id');
		    $this->db->from('sh_m_item i');
		    $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
		    $this->db->like('s.weekday', date('l'));
		    $this->db->where('i.is_active', 1);
		    $this->db->where("LPAD(s.time_from, 2, '0') <=", date('H'));
		    $this->db->where("LPAD(s.time_to, 2, '0') >=", date('H'));
		    $this->db->where_in('LOWER(i.category)', array('siap saji', 'proses'));
		    $this->db->group_by('i.sub_category');
		    $this->db->order_by('s.id', 'asc');
		    $weekday = $this->db->get()->result_array();

		    if ($weekday) {
		        $query4 = $weekday;
		    } else {
		        $this->db->select('i.sub_category, s.weekday, s.weekend, s.time_from, s.time_to, s.id');
		        $this->db->from('sh_m_item i');
		        $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
		        $this->db->like('s.weekend', date('l'));
		        $this->db->where('i.is_active', 1);
		        $this->db->where("LPAD(s.time_from, 2, '0') <=", date('H'));
		        $this->db->where("LPAD(s.time_to, 2, '0') >=", date('H'));
		        $this->db->where_in('LOWER(i.category)', array('siap saji', 'proses'));
		        $this->db->group_by('i.sub_category');
		        $this->db->order_by('s.id', 'asc');
		        $weekend = $this->db->get()->result_array();
		        $query4 = $weekend;
		    }

		    // Gabungkan hasil dengan menghindari duplikasi berdasarkan `sub_category`
		    $mergedResults = array_merge($query3, $query1, $query2, $query4);
		    $uniqueResults = [];

		    foreach ($mergedResults as $item) {
		        $key = strtolower($item['sub_category']); // Normalisasi key untuk menghindari case-sensitive duplikasi
		        if (!isset($uniqueResults[$key])) {
		            $uniqueResults[$key] = $item; // Simpan hanya sekali
		        }
		    }

		    // Ubah kembali ke bentuk array numerik
		    $result = array_values($uniqueResults);

				    // Urutkan hasil selain "Signature" berdasarkan `id`
				    usort($result, function ($a, $b) {
					    if ($a['sub_category'] === 'Signature') {
					        return -1; // Signature tetap di awal
					    }
					    if ($b['sub_category'] === 'Signature') {
					        return 1; // Signature tetap di awal
					    }

					    // Gantilah operator `<=>` dengan perbandingan biasa
					    if ($a['id'] < $b['id']) {
					        return -1;
					    } elseif ($a['id'] > $b['id']) {
					        return 1;
					    }
					    return 0;
					});



				    return $result;
		}

		public function sub_category()
		{
		    $this->db->select('s.description AS sub_category, s.id, s.weekday, s.weekend, s.time_from, s.time_to');
		    $this->db->from('sh_m_item_sub_category s');
		    $this->db->join('sh_m_item i', 'LOWER(i.sub_category) = LOWER(s.description)', 'inner');
		    $this->db->where('s.is_active', 1);
		    // $this->db->where_in('LOWER(s.category)', ['siap saji', 'proses']);
		    $this->db->where('i.is_active', 1);
		    $this->db->group_by(['s.description', 's.id', 's.weekday', 's.weekend', 's.time_from', 's.time_to']);
		    $this->db->having('COUNT(i.id) >', 0);

		    // === Tambahan filter weekday/weekend & jam ===
		    $today = strtolower(date('l')); // contoh: "monday"
		    $hour  = (int) date('H');       // jam sekarang (0-23)

		    // Hari
		    $this->db->group_start();
		        $this->db->like('LOWER(s.weekday)', $today);
		        $this->db->or_like('LOWER(s.weekend)', $today);
		    $this->db->group_end();

		    // Jam
		    $this->db->where("{$hour} BETWEEN s.time_from AND s.time_to");

		    $query = $this->db->get()->result_array();
		    return $query;
		}


		public function sub_category_awal()
		{
			$result = [];

		    // Query pertama
		    $this->db->select('s.description as sub_category, s.id');
			$this->db->from('sh_m_item_sub_category s');
			$this->db->where('s.is_active', 1);
			$this->db->where('(s.weekday IS NULL OR s.weekday = "")');
			$this->db->where('(s.weekend IS NULL OR s.weekend = "")');
			// $this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));

			$this->db->where('EXISTS (
			    SELECT 1 FROM sh_m_item i 
			    WHERE i.is_active = 1  
			    AND (
			        CASE 
			            WHEN s.description = "Signature" THEN i.chef_recommended = 1
			            ELSE i.sub_category = s.description OR (i.chef_recommended = 1 AND i.sub_category = s.description)
			        END
			    )
			    AND (
			        NOT EXISTS (SELECT 1 FROM sh_m_item_event c WHERE c.item_code = i.no) 
			        OR EXISTS (
			            SELECT 1 FROM sh_m_item_event c 
			            WHERE c.item_code = i.no
			            AND (
			                (c.time_from IS NOT NULL AND c.time_to IS NOT NULL 
			                    AND HOUR(NOW()) BETWEEN c.time_from AND c.time_to) 
			                OR (c.date_from IS NOT NULL AND c.date_to IS NOT NULL 
			                    AND CURDATE() BETWEEN c.date_from AND c.date_to)
			            )
			        )
			    )
			)', null, false);

			    $query1 = $this->db->get()->result_array();

			    // Query kedua (Sub Category dari sh_m_item yang memiliki item aktif)
			    $this->db->select('i.sub_category_so AS sub_category, i.image_path, s.id');
			    $this->db->from('sh_m_item_sub_category s');
			    $this->db->join('sh_m_item i', 's.description = i.sub_category_so', 'left');
			    $this->db->where('i.is_active_so', 1);
			    $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
			    $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
			    $this->db->where('i.sub_category_so IS NOT NULL');
			    $this->db->where('i.sub_category_so !=', '');
			    $this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));
			    $this->db->where('EXISTS (SELECT 1 FROM sh_m_item i2 WHERE i2.sub_category_so = i.sub_category_so AND i2.is_active = 1)', null, false); // Cek item aktif
			    $this->db->group_by('i.sub_category_so, s.id');
			    $query2 = $this->db->get()->result_array();

			    // Query ketiga (Signature)
			    $this->db->select('description AS sub_category');
			    $this->db->from('sh_m_item_sub_category');
			    $this->db->where('description', 'Signature');
			    $this->db->where("LPAD(time_from, 2, '0') <=", date('H'));
			    $this->db->where("LPAD(time_to, 2, '0') >=", date('H'));
			    $query3 = $this->db->get()->result_array();

			    // Query keempat (Event-based sub_category)
			    $this->db->select('i.sub_category, s.weekday, s.weekend, s.time_from, s.time_to, s.id');
			    $this->db->from('sh_m_item i');
			    $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
			    $this->db->like('s.weekday', date('l'));
			    $this->db->where('i.is_active', 1);
			    $this->db->where("LPAD(s.time_from, 2, '0') <=", date('H'));
			    $this->db->where("LPAD(s.time_to, 2, '0') >=", date('H'));
			    $this->db->where_in('LOWER(i.category)', array('siap saji', 'proses'));
			    $this->db->group_by('i.sub_category');
			    $this->db->order_by('s.id', 'asc');
			    $weekday = $this->db->get()->result_array();

			    if ($weekday) {
			        $query4 = $weekday;
			    } else {
			        $this->db->select('i.sub_category, s.weekday, s.weekend, s.time_from, s.time_to, s.id');
			        $this->db->from('sh_m_item i');
			        $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
			        $this->db->like('s.weekend', date('l'));
			        $this->db->where('i.is_active', 1);
			        $this->db->where("LPAD(s.time_from, 2, '0') <=", date('H'));
			        $this->db->where("LPAD(s.time_to, 2, '0') >=", date('H'));
			        $this->db->where_in('LOWER(i.category)', array('siap saji', 'proses'));
			        $this->db->group_by('i.sub_category');
			        $this->db->order_by('s.id', 'asc');
			        $weekend = $this->db->get()->result_array();
			        $query4 = $weekend;
			    }

			    // Gabungkan hasil dengan menghindari duplikasi berdasarkan `sub_category`
			    $mergedResults = array_merge($query3, $query1, $query2, $query4);
			    $uniqueResults = [];

			    foreach ($mergedResults as $item) {
			        $key = strtolower($item['sub_category']); // Normalisasi key untuk menghindari case-sensitive duplikasi
			        if (!isset($uniqueResults[$key])) {
			            $uniqueResults[$key] = $item; // Simpan hanya sekali
			        }
			    }

			    // Ubah kembali ke bentuk array numerik
			    $result = array_values($uniqueResults);

			    // Urutkan hasil selain "Signature" berdasarkan `id`
			    // Urutkan hasil selain "Signature" berdasarkan `id`
				// Urutkan hasil selain "Signature" berdasarkan `id`
				usort($result, function ($a, $b) {
				    if ($a['sub_category'] === 'Signature') {
				        return -1; // Signature tetap di awal
				    }
				    if ($b['sub_category'] === 'Signature') {
				        return 1; // Signature tetap di awal
				    }

				    // Perbandingan ID manual karena PHP 5.6 tidak mendukung <=> (spaceship operator)
				    if ($a['id'] < $b['id']) {
				        return -1;
				    } elseif ($a['id'] > $b['id']) {
				        return 1;
				    }
				    return 0;
				});

				// Pastikan hasil tetap array numerik
				$result = array_values($result);

				// Jika ada "Signature", ambil hanya satu
				if (!empty($result) && $result[0]['sub_category'] === 'Signature') {
				    $result = [$result[0]];
				} elseif (!empty($result)) {
				    // Jika tidak ada "Signature", ambil hanya satu data pertama
				    $result = [$result[0]];
				}
				if (!empty($result)) {
					$rslt = $result[0]['sub_category'];
				} else {
				    $rslt = '';
				}

				return $rslt;


		}
		public function sub_category_awal_raw()
		{
		    // Ambil data sub_category seperti biasa
		    $this->db->select('s.description as sub_category, s.id, s.time_from, s.time_to, s.weekday, s.weekend, e.date_from, e.date_to');
		    $this->db->from('sh_m_item_sub_category s');
		    $this->db->join('sh_m_item i', 'i.sub_category = s.description', 'inner');
		    $this->db->join('sh_m_item_event e', 'e.item_code = i.no', 'left');
		    $this->db->where('s.is_active', 1);
		    // $this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));
		    $this->db->group_by('s.description, s.id, s.time_from, s.time_to, s.weekday, s.weekend, e.date_from, e.date_to');
		    $result = $this->db->get()->result_array();

		    // === Tambahkan filter weekday/weekend & time ===
		    $filtered_result = [];
		    $today = strtolower(date('l')); // contoh: monday, tuesday
		    $currentHour = (int) date('H'); // jam dalam 24 jam, misal 14 untuk jam 14:30

		    foreach ($result as $row) {
		        $show = true;

		        // Cek weekday/weekend
		        if (!empty($row['weekday']) || !empty($row['weekend'])) {
		            if (in_array($today, ['saturday', 'sunday'])) {
		                // hari weekend
		                if (empty($row['weekend']) || stripos(strtolower($row['weekend']), $today) === false) {
		                    $show = false;
		                }
		            } else {
		                // hari weekday
		                if (empty($row['weekday']) || stripos(strtolower($row['weekday']), $today) === false) {
		                    $show = false;
		                }
		            }
		        }

		        // Cek jam (time_from - time_to)
		        if ($show && $row['time_from'] !== null && $row['time_to'] !== null) {
		            $timeFrom = (int) $row['time_from'];
		            $timeTo   = (int) $row['time_to'];

		            if ($currentHour < $timeFrom || $currentHour >= $timeTo) {
		                $show = false;
		            }
		        }

		        if ($show) {
		            $filtered_result[] = $row;
		        }
		    }

		    // === Cek ada item chef_recommended = 1 ===
		    $this->db->select('COUNT(*) as cnt');
		    $this->db->from('sh_m_item');
		    $this->db->where('chef_recommended', 1);
		    $count_chef = $this->db->get()->row()->cnt;

		    // Inisialisasi data yang akan dikembalikan
		    $final_data = null;

		    if ($count_chef > 0) {
		        // Cek apakah 'Signature' sudah ada di hasil (case insensitive)
		        foreach ($filtered_result as $row) {
		            if (strtolower(trim($row['sub_category'])) === 'signature') {
		                $final_data = $row; // ambil data signature dari hasil query
		                break;
		            }
		        }

		        // Jika tidak ada signature, buat data manual
		        if (!$final_data) {
		            $final_data = [
		                'sub_category' => 'Signature',
		                'id' => 99999, // id dummy
		                'time_from' => null,
		                'time_to' => null,
		                'weekday' => null,
		                'weekend' => null,
		                'date_from' => null,
		                'date_to' => null,
		            ];
		        }
		    } else {
		        // Jika tidak ada chef_recommended, ambil data pertama yang lolos filter
		        // if (!empty($filtered_result)) {
		        //     $final_data = $filtered_result[0];
		        // }
		        if (!empty($filtered_result)) {

				    // Urutkan berdasarkan ID terkecil (biasanya urutan awal menu)
				    usort($filtered_result, function($a, $b) {
				        return $a['id'] <=> $b['id'];
				    });

				    $final_data = $filtered_result[0];
				}
		    }

		    // Kembalikan data tunggal (bukan array)
		    return $final_data;
		}




		// public function sub_category_awalOLDD()
		// {
		//     $result = [];

		//     // Query pertama
		//     $this->db->select('s.description as sub_category, s.id');
		//     $this->db->from('sh_m_item_sub_category s');
		//     $this->db->where('s.is_active', 1);
		//     $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
		//     $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
		//     $this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));
		//     $query1 = $this->db->get()->result_array();

		//     // Query kedua
		//     $this->db->select('i.sub_category_so AS sub_category, i.image_path, s.id');
		//     $this->db->from('sh_m_item_sub_category s');
		//     $this->db->join('sh_m_item i', 's.description = i.sub_category_so', 'left');
		//     $this->db->where('i.is_active_so', 1);
		//     $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
		//     $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
		//     $this->db->where('i.sub_category_so IS NOT NULL');
		//     $this->db->where('i.sub_category_so !=', '');
		//     $this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));
		//     $this->db->group_by('i.sub_category_so, s.id');
		//     $query2 = $this->db->get()->result_array();
		    
		//     // Query ketiga (untuk chef_recommended)
		//     $this->db->select('"Signature" AS sub_category, i.image_path, s.id');
		//     $this->db->from('sh_m_item_sub_category s');
		//     $this->db->join('sh_m_item i', 's.description = i.sub_category', 'left');
		//     $this->db->where('s.is_active', 1);
		//     $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
		//     $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
		//     $this->db->where('i.sub_category IS NOT NULL');
		//     $this->db->where('i.sub_category !=', '');
		//     $this->db->where('i.chef_recommended', 1);
		//     $this->db->where_in('LOWER(s.category)', array('siap saji', 'proses'));
		//     $this->db->group_by('i.sub_category_so, s.id');
		//     $query3 = $this->db->get()->result_array();

		//     // Query keempat (event-based sub_category)
		//     $this->db->select('i.sub_category, s.weekday, s.weekend, s.time_from, s.time_to, s.id');
		//     $this->db->from('sh_m_item i');
		//     $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
		//     $this->db->like('s.weekday', date('l'));
		//     $this->db->where('i.is_active', 1);
		//     $this->db->where("LPAD(s.time_from, 2, '0') <=", date('H'));
		//     $this->db->where("LPAD(s.time_to, 2, '0') >=", date('H'));
		//     $this->db->where_in('LOWER(i.category)', array('siap saji', 'proses'));
		//     $this->db->group_by('i.sub_category');
		//     $this->db->order_by('s.id', 'asc');
		//     $weekday = $this->db->get()->result_array();

		//     if ($weekday) {
		//         $query4 = $weekday;
		//     } else {
		//         $this->db->select('i.sub_category, s.weekday, s.weekend, s.time_from, s.time_to, s.id');
		//         $this->db->from('sh_m_item i');
		//         $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
		//         $this->db->like('s.weekend', date('l'));
		//         $this->db->where('i.is_active', 1);
		//         $this->db->where("LPAD(s.time_from, 2, '0') <=", date('H'));
		//         $this->db->where("LPAD(s.time_to, 2, '0') >=", date('H'));
		//         $this->db->where_in('LOWER(i.category)', array('siap saji', 'proses'));
		//         $this->db->group_by('i.sub_category');
		//         $this->db->order_by('s.id', 'asc');
		//         $weekend = $this->db->get()->result_array();
		//         $query4 = $weekend;
		//     }
		//     // Gabungkan semua hasil dengan menghindari duplikasi berdasarkan `sub_category`
		//     $mergedResults = array_merge($query3, $query1, $query2, $query4);
		//     $uniqueResults = [];

		//     foreach ($mergedResults as $item) {
		//         $key = strtolower($item['sub_category']); // Normalisasi key untuk menghindari case-sensitive duplikasi
		//         if (!isset($uniqueResults[$key])) {
		//             $uniqueResults[$key] = $item; // Simpan hanya sekali
		//         }
		//     }

		//     // Ubah kembali ke bentuk array numerik
		//     $result = array_values($uniqueResults);

		//     // Urutkan hasil selain "Signature" berdasarkan `id`
		//     usort($result, function ($a, $b) {
		//         if ($a['sub_category'] === 'Signature') {
		//             return -1; // Signature tetap di awal
		//         }
		//         if ($b['sub_category'] === 'Signature') {
		//             return 1; // Signature tetap di awal
		//         }

		//         if ($a['id'] < $b['id']) {
		//             return -1;
		//         } elseif ($a['id'] > $b['id']) {
		//             return 1;
		//         }
		//         return 0;
		//     });
		//     // Ambil data pertama saja
		//     return !empty($result) ? reset($result) : null;
		// }

		// public function sub_category_awalOLD()
		// {
		// 	$this->db->select('i.sub_category,i.image_path');
		// 	$this->db->from('sh_m_item i');
		// 	$this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'left'); 
		// 	$this->db->where('i.is_active_so', 1);
		// 	$this->db->where('i.sub_category !=', '');
		// 	$this->db->where('(s.weekday IS NULL OR s.weekday = "")'); 
		// 	$this->db->where('(s.weekend IS NULL OR s.weekend = "")'); 
		// 	$this->db->where_in('LOWER(i.category)', array('siap saji', 'proses'));
		// 	$this->db->group_by('i.sub_category');
		// 	$this->db->order_by('s.id', 'asc');
	 //        $query = $this->db->get()->row();

		// 	// $this->db->select('i.sub_category,s.weekday,s.weekend,s.time_from,s.time_to');
	 //        $this->db->from('sh_m_item i');
	 //        $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
	 //        $this->db->like('s.weekday',date('l'));
	 //        $this->db->where('i.is_active',1);
	 //        $this->db->where('s.time_from <=',date('H:i'));
	 //        $this->db->where('s.time_to >=',date('H:i'));
	 //        $this->db->where_in('i.category', array('SIAP SAJI','PROSES'));
	 //        $this->db->group_by('i.sub_category');
	 //        $this->db->order_by('s.id','asc');
	 //        $weekday = $this->db->get()->row();

	 //        if ($weekday) {
	 //        	$query = $weekday;
	 //        }else{
	 //        	$this->db->select('i.sub_category,s.weekday,s.weekend,s.time_from,s.time_to');
		//         $this->db->from('sh_m_item i');
		//         $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
		//         $this->db->like('s.weekend',date('l'));
		//         $this->db->where('i.is_active',1);
		//         $this->db->where('s.time_from <=',date('H:i'));
	 //        	$this->db->where('s.time_to >=',date('H:i'));
		//         $this->db->where_in('i.category', array('SIAP SAJI','PROSES'));
		//         $this->db->group_by('i.sub_category');
		//         $this->db->order_by('s.id','asc');
		//         $weekend = $this->db->get()->row();
		//         if ($weekend) {
		//         	$query = $weekend;
		//         }else{
		//         	$this->db->select('i.sub_category');
		// 			$this->db->from('sh_m_item i');
		// 			$this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'left'); 
		// 			$this->db->where('i.is_active', 1);
		// 			$this->db->where('(s.weekday IS NULL OR s.weekday = "")'); 
		// 			$this->db->where('(s.weekend IS NULL OR s.weekend = "")'); 
		// 			$this->db->where_in('LOWER(i.category)', array('siap saji', 'proses'));

		// 			$this->db->limit(1);
		// 			$this->db->group_by('i.sub_category');
		// 			$this->db->order_by('s.id', 'asc');

			              
		// 	        $query = $this->db->get()->row();
		//         }
		        
	 //        }

	 //        return $query;
		// }
		public function sub_category_minuman_awal()
		{
			$result = [];

		    // Query pertama
		    $this->db->select('s.description as sub_category, s.id');
			$this->db->from('sh_m_item_sub_category s');
			$this->db->where('s.is_active', 1);
			$this->db->where('(s.weekday IS NULL OR s.weekday = "")');
			$this->db->where('(s.weekend IS NULL OR s.weekend = "")');
			$this->db->where_in('LOWER(s.category)', array('minuman'));

			$this->db->where('EXISTS (
			    SELECT 1 FROM sh_m_item i 
			    WHERE i.is_active = 1  
			    AND (
			        CASE 
			            WHEN s.description = "Signature" THEN i.chef_recommended = 1
			            ELSE i.sub_category = s.description OR (i.chef_recommended = 1 AND i.sub_category = s.description)
			        END
			    )
			    AND (
			        NOT EXISTS (SELECT 1 FROM sh_m_item_event c WHERE c.item_code = i.no) 
			        OR EXISTS (
			            SELECT 1 FROM sh_m_item_event c 
			            WHERE c.item_code = i.no
			            AND (
			                (c.time_from IS NOT NULL AND c.time_to IS NOT NULL 
			                    AND HOUR(NOW()) BETWEEN c.time_from AND c.time_to) 
			                OR (c.date_from IS NOT NULL AND c.date_to IS NOT NULL 
			                    AND CURDATE() BETWEEN c.date_from AND c.date_to)
			            )
			        )
			    )
			)', null, false);

			    $query1 = $this->db->get()->result_array();

			    // Query kedua (Sub Category dari sh_m_item yang memiliki item aktif)
			    $this->db->select('i.sub_category_so AS sub_category, i.image_path, s.id');
			    $this->db->from('sh_m_item_sub_category s');
			    $this->db->join('sh_m_item i', 's.description = i.sub_category_so', 'left');
			    $this->db->where('i.is_active_so', 1);
			    $this->db->where('(s.weekday IS NULL OR s.weekday = "")');
			    $this->db->where('(s.weekend IS NULL OR s.weekend = "")');
			    $this->db->where('i.sub_category_so IS NOT NULL');
			    $this->db->where('i.sub_category_so !=', '');
			    $this->db->where_in('LOWER(s.category)', array('minuman'));
			    $this->db->where('EXISTS (SELECT 1 FROM sh_m_item i2 WHERE i2.sub_category_so = i.sub_category_so AND i2.is_active = 1)', null, false); // Cek item aktif
			    $this->db->group_by('i.sub_category_so, s.id');
			    $query2 = $this->db->get()->result_array();

			    // Query ketiga (Signature)
			    // Query ketiga (Signature) - hanya tampil jika ada item chef_recommended aktif di kategori minuman
				$this->db->select('description AS sub_category');
				$this->db->from('sh_m_item_sub_category');
				$this->db->where('description', 'Signature');
				$this->db->where("LPAD(time_from, 2, '0') <=", date('H'));
				$this->db->where("LPAD(time_to, 2, '0') >=", date('H'));
				$this->db->where('EXISTS (
				    SELECT 1 FROM sh_m_item 
				    WHERE chef_recommended = 1 
				    AND is_active = 1 
				    AND LOWER(category) = "minuman"
				)', null, false);
				$query3 = $this->db->get()->result_array();

			    // Query keempat (Event-based sub_category)
			    $this->db->select('i.sub_category, s.weekday, s.weekend, s.time_from, s.time_to, s.id');
			    $this->db->from('sh_m_item i');
			    $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
			    $this->db->like('s.weekday', date('l'));
			    $this->db->where('i.is_active', 1);
			    $this->db->where("LPAD(s.time_from, 2, '0') <=", date('H'));
			    $this->db->where("LPAD(s.time_to, 2, '0') >=", date('H'));
			    $this->db->where_in('LOWER(i.category)', array('minuman'));
			    $this->db->group_by('i.sub_category');
			    $this->db->order_by('s.id', 'asc');
			    $weekday = $this->db->get()->result_array();

			    if ($weekday) {
			        $query4 = $weekday;
			    } else {
			        $this->db->select('i.sub_category, s.weekday, s.weekend, s.time_from, s.time_to, s.id');
			        $this->db->from('sh_m_item i');
			        $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
			        $this->db->like('s.weekend', date('l'));
			        $this->db->where('i.is_active', 1);
			        $this->db->where("LPAD(s.time_from, 2, '0') <=", date('H'));
			        $this->db->where("LPAD(s.time_to, 2, '0') >=", date('H'));
			        $this->db->where_in('LOWER(i.category)', array('minuman'));
			        $this->db->group_by('i.sub_category');
			        $this->db->order_by('s.id', 'asc');
			        $weekend = $this->db->get()->result_array();
			        $query4 = $weekend;
			    }

			    // Gabungkan hasil dengan menghindari duplikasi berdasarkan `sub_category`
			    $mergedResults = array_merge($query3, $query1, $query2, $query4);
			    $uniqueResults = [];

			    foreach ($mergedResults as $item) {
			        $key = strtolower($item['sub_category']); // Normalisasi key untuk menghindari case-sensitive duplikasi
			        if (!isset($uniqueResults[$key])) {
			            $uniqueResults[$key] = $item; // Simpan hanya sekali
			        }
			    }

			    // Ubah kembali ke bentuk array numerik
			    $result = array_values($uniqueResults);

				usort($result, function ($a, $b) {
				    if ($a['sub_category'] === 'Signature') {
				        return -1; // Signature tetap di awal
				    }
				    if ($b['sub_category'] === 'Signature') {
				        return 1; // Signature tetap di awal
				    }

				    // Perbandingan ID manual karena PHP 5.6 tidak mendukung <=> (spaceship operator)
				    if ($a['id'] < $b['id']) {
				        return -1;
				    } elseif ($a['id'] > $b['id']) {
				        return 1;
				    }
				    return 0;
				});

				// Pastikan hasil tetap array numerik
				$result = array_values($result);

				// Jika ada "Signature", ambil hanya satu
				if (!empty($result) && $result[0]['sub_category'] === 'Signature') {
				    $result = [$result[0]];
				} elseif (!empty($result)) {
				    // Jika tidak ada "Signature", ambil hanya satu data pertama
				    $result = [$result[0]];
				}
				if (!empty($result)) {
					$rslt = $result[0]['sub_category'];
				} else {
				    $rslt = '';
				}

				return $rslt;


		}
		public function sub_category_minuman_awalOLDD()
		{
			$this->db->select('i.sub_category,s.weekday,s.weekend,s.time_from,s.time_to');
	        $this->db->from('sh_m_item i');
	        $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
	        $this->db->like('s.weekday',date('l'));
	        $this->db->where('i.is_active_so',1);
	        $this->db->where('s.time_from <=',date('H:i'));
	        $this->db->where('s.time_to >=',date('H:i'));
	        $this->db->where_in('LOWER(i.category)', array('minuman'));
	        $this->db->group_by('i.sub_category');
	        $this->db->order_by('s.id','asc');
	        $weekday = $this->db->get()->row();

	        if ($weekday) {
	        	$query = $weekday;
	        }else{
	        	$this->db->select('i.sub_category,s.weekday,s.weekend,s.time_from,s.time_to');
		        $this->db->from('sh_m_item i');
		        $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
		        $this->db->like('s.weekend',date('l'));
		        $this->db->where('i.is_active_so',1);
		        $this->db->where('s.time_from <=',date('H:i'));
	        	$this->db->where('s.time_to >=',date('H:i'));
		        $this->db->where_in('LOWER(i.category)', array('minuman'));
		        $this->db->group_by('i.sub_category');
		        $this->db->order_by('s.id','asc');
		        $weekend = $this->db->get()->row();
		        if ($weekend) {
		        	$query = $weekend;
		        }else{
		        	$this->db->select('i.sub_category');
					$this->db->from('sh_m_item i');
					$this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'left'); 
					$this->db->where('i.is_active_so', 1);
					$this->db->where('(s.weekday IS NULL OR s.weekday = "")'); 
					$this->db->where('(s.weekend IS NULL OR s.weekend = "")'); 
					$this->db->where_in('LOWER(i.category)', array('minuman'));
					$this->db->limit(1);
					$this->db->group_by('i.sub_category');
					$this->db->order_by('s.id', 'asc');

			              
			        $query = $this->db->get()->row();
		        }
		        
	        }

	        return $query;
		}
		public function sub_category_event()
		{
			$this->db->select('i.sub_category,s.weekday,s.weekend,s.time_from,s.time_to');
	        $this->db->from('sh_m_item i');
	        $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
	        $this->db->like('s.weekday',date('l'));
	        $this->db->where('i.is_active',1);
	        $this->db->where('s.time_from <=',date('H:i'));
	        $this->db->where('s.time_to >=',date('H:i'));
	        // $this->db->where_in('LOWER(i.category)', array('siap saji', 'proses'));
	        $this->db->group_by('i.sub_category');
	        $this->db->order_by('s.id','asc');
	        $weekday = $this->db->get()->result_array();
	        if ($weekday) {
	        	$query = $weekday;
	        }else{
	        	$this->db->select('i.sub_category,s.weekday,s.weekend,s.time_from,s.time_to');
		        $this->db->from('sh_m_item i');
		        $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
		        $this->db->like('s.weekend',date('l'));
		        $this->db->where('i.is_active',1);
		        $this->db->where('s.time_from <=',date('H:i'));
	        	$this->db->where('s.time_to >=',date('H:i'));
		        // $this->db->where_in('LOWER(i.category)', array('siap saji', 'proses'));
		        $this->db->group_by('i.sub_category');
		        $this->db->order_by('s.id','asc');
		        $weekend = $this->db->get()->result_array();
		        $query = $weekend;
	        }
	        return $query;
		}
		public function sub_categoryCakeBakery()
		{
			$this->db->select('i.sub_category');
	        $this->db->from('sh_m_item i');
	        $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
	        $this->db->where('s.is_active',1);
	        $this->db->where('i.category','CAKE DAN BAKERY');
	        $this->db->group_by('i.sub_category');
	        $this->db->order_by('s.id','asc');
	              
	        $query = $this->db->get()->result_array();
	        return $query;
		}
		public function sub_category_minumanOLD()
		{
			$this->db->select('i.sub_category');
	        $this->db->from('sh_m_item i');
	        $this->db->join('sh_m_item_sub_category s', 's.description = i.sub_category', 'inner');
	        $this->db->where('i.is_active_so',1);
	        $this->db->where_in('LOWER(i.category)', array('minuman'));
	        $this->db->group_by('i.sub_category');
	        $this->db->order_by('s.id','asc');
	               
	        $query = $this->db->get()->result_array();
	        return $query;
		}
		public function sub_category_minuman()
		{
			$this->db->select('s.description AS sub_category, s.id, s.weekday, s.weekend, s.time_from, s.time_to');
		    $this->db->from('sh_m_item_sub_category s');
		    $this->db->join('sh_m_item i', 'LOWER(i.sub_category) = LOWER(s.description)', 'inner');
		    $this->db->where('s.is_active', 1);
		    $this->db->where_in('LOWER(s.category)', array('minuman'));
		    $this->db->where('i.is_active', 1);
		    $this->db->group_by(['s.description', 's.id', 's.weekday', 's.weekend', 's.time_from', 's.time_to']);
		    $this->db->having('COUNT(i.id) >', 0); // Pastikan ada item aktif di sub_category ini
		    $query = $this->db->get()->result_array();

		    return $query;
		    
		}
		public function getDatatype($type)
		{
			$this->db->select('*');
	        $this->db->from('sh_m_item');
	        $this->db->where('category',$type);
	              
	        $query = $this->db->get()->result_array();
	        return $query;
		}
		public function get_paket($nomeja) {
			$this->db->select('tipe_paket');
			$this->db->where(['id_table'=> $nomeja,'status'=> 'Dining']);
			$this->db->limit(1);
			return $this->db->get('sh_rel_table')->row();
		}
		public function get_order_paket($nomeja,$id_customer) {
			$this->db->select('sum(d.qty) as jml_paket');
			$this->db->join('sh_m_item m', 'm.no = d.item_code', 'inner');
			$this->db->join('sh_t_transactions t', 't.id = d.id_trans', 'inner');
			$where = "t.id_customer = '".$id_customer."' and left(t.create_date,10) = left(sysdate(),10) and m.category in ('DEWASA','SENIOR','ANAK') and d.selected_table_no = '".$nomeja."'";
			$this->db->where($where);
			$this->db->group_by('d.id_trans,d.selected_table_no');
			return $this->db->get('sh_t_transaction_details d')->row();
		}
		public function get_order_kuah($nomeja,$id_customer) {
			$this->db->select('sum(d.qty) as jml_kuah');
			$this->db->join('sh_m_item m', 'm.no = d.item_code', 'inner');
			$this->db->join('sh_t_transactions t', 't.id = d.id_trans', 'inner');
			$where = "t.id_customer = '".$id_customer."' and left(t.create_date,10) = left(sysdate(),10) and m.category in ('SOUP') and d.selected_table_no = '".$nomeja."'";
			$this->db->where($where);
			$this->db->group_by('d.id_trans,d.selected_table_no');
			return $this->db->get('sh_t_transaction_details d')->row();
		}
		public function get_kuah() {
			$this->db->select('*');
			$this->db->where(['category'=> 'SOUP','is_active'=> 1]);
			$this->db->order_by('no asc');
			return $this->db->get('sh_m_item')->result();
		}
		public function get_spesial($nomeja) {
			$q = "select tipe_paket from sh_rel_table where id_table='".$nomeja."' and status='Dining' limit 1";
			$paket = $this->db->query($q)->row();
			$sub = "";
			if($paket->tipe_paket == 'Shabu Only'){
			   $sub = "and sub_category='Shabu' ";
			}else if($paket->tipe_paket == 'Yakiniku Only'){
			   $sub = "and sub_category='Yakiniku' ";
			}

			$this->db->select('*');
			$this->db->where(['category'=> 'SPESIAL','is_active'=> 1]);
			$this->db->order_by('no asc');
			return $this->db->get('sh_m_item')->result();
		}
		public function nomeja($cs)
		{
			return $this->db->get_where('sh_rel_table',['id_customer' => $cs, 'status' => 'Dining'])->row_array();
		}
		public function getDataOrder($id_customer)
		{
			// $this->db->select('*');
	  //       $this->db->from('sh_t_transaction_details');
	  //       $this->db->where('entry_by',$uc);
	  //       $this->db->where('is_paid',0);
	              
	  //       $query = $this->db->get()->result_array();
	  //       return $query;
			/*query history order*/

			$q1 = "select * from sh_t_transactions where id_customer = '".$id_customer."' limit 1";
			$trans = $this->db->query($q1)->row();
			$notrans = $trans->id;

			//order line
			$query = "select * from sh_t_transaction_details where id_trans='".$notrans."' and is_paid = 0 and is_cancel = 0 order by start_time_order,item_code asc";
			return $this->db->query($query);
			
		}
		public function getOrderCustomer()
		{
			$date = date('Y-m-d');
			$query = "select t.id,c.customer_name,d.id_trans,r.id_table,r.status,count(d.id) as jml_item,d.* from sh_t_transactions t 
			inner join sh_t_transaction_details d on d.id_trans = t.id 
			inner join sh_m_customer c on c.id = t.id_customer 
			inner join sh_rel_table r on r.id_customer = c.id 
			inner join sh_m_item i on i.no = d.item_code 
			where d.selforder = 1
			and d.is_cancel = 0
			and d.is_printed_so = 0
			and left(d.created_date,10) ='".$date."'
			group by d.cekdata
			order by d.id desc";
			return $this->db->query($query);
			
		}
		public function getOrderCustomerLine($id,$datake)
		{
			$date = date('Y-m-d');
			$query = "select i.image_path,i.description,d.* from sh_t_transactions t 
			inner join sh_t_transaction_details d on d.id_trans = t.id
			inner join sh_m_item i on i.no = d.item_code 
			where d.selforder = 1
			and d.is_paid = 0 
			and d.is_cancel = 0
			and d.is_printed_so = 0
			and d.id_trans = '".$id."'
			and d.cekdata = '".$datake."'
			and left(d.created_date,10) ='".$date."'
			order by d.item_code desc";
			return $this->db->query($query);
			
		}
		public function getCustomer($id)
		{
			$date = date('Y-m-d');
			$query = "select i.image_path,d.entry_by,d.selected_table_no,i.description,d.* from sh_t_transactions t 
			inner join sh_t_transaction_details d on d.id_trans = t.id
			inner join sh_m_item i on i.no = d.item_code 
			where d.selforder = 1
			and d.is_cancel = 0 
			/*and d.id = '".$id."'*/
			and t.id = '".$id."'
			and left(d.created_date,10) ='".$date."'
			LIMIT 1";
			return $this->db->query($query);
		}

		public function getHistoryprints()
		{
			$date = date('Y-m-d');
			$query = "SELECT t.id, c.customer_name, d.id_trans, r.id_table, r.status, COUNT(d.id) AS jml_item
			FROM sh_t_transactions t 
			INNER JOIN sh_t_transaction_details d ON d.id_trans = t.id 
			INNER JOIN sh_m_customer c ON c.id = t.id_customer 
			INNER JOIN sh_rel_table r ON r.id_customer = c.id 
			INNER JOIN sh_m_item i ON i.no = d.item_code 
			WHERE d.selforder = 1
			    AND d.is_cancel = 0 
			    AND d.is_printed_so = 1
			    AND r.status = 'Dining'
			    AND LEFT(d.created_date, 10) = '".$date."'
			GROUP BY t.id, c.customer_name, d.id_trans, r.id_table, r.status
			ORDER BY d.id_trans DESC;
			";
			return $this->db->query($query);
			
		}
		public function getHistoryprint()
		{
			$date = date('Y-m-d');
			$query = "SELECT d.id, c.customer_name, d.id_trans, r.id_table, r.status, d.*
			FROM sh_t_transactions t 
			INNER JOIN sh_t_transaction_details d ON d.id_trans = t.id 
			INNER JOIN sh_m_customer c ON c.id = t.id_customer 
			INNER JOIN sh_rel_table r ON r.id_customer = c.id 
			INNER JOIN sh_m_item i ON i.no = d.item_code 
			WHERE d.selforder = 1
			    AND d.is_cancel = 0
			    AND d.is_printed_so = 1
			    AND LEFT(d.created_date, 10) = '".$date."'
			GROUP BY d.id, c.customer_name, d.id_trans, r.id_table, r.status, d.selected_table_no
			ORDER BY d.id DESC;
			";

			return $this->db->query($query);
		}
		public function cekprint($id_trans)
		{
			$date = date('Y-m-d');
			$query = "select t.id,c.customer_name,d.id_trans,r.id_table,r.status,d.* from sh_t_transactions t 
			inner join sh_t_transaction_details d on d.id_trans = t.id
			inner join sh_m_customer c on c.id = t.id_customer 
			inner join sh_rel_table r on r.id_customer = c.id 
			inner join sh_m_item i on i.no = d.item_code  
			where d.selforder = 1
			and d.is_paid = 0 
			and d.is_cancel = 0
			and d.is_printed_so = 0
			and d.id_trans = '".$id_trans."'
			and left(d.created_date,10) ='".$date."'
			order by d.item_code desc";
			return $this->db->query($query);
		}
		public function getHistoryprintLine($id_trans,$datake)
		{
			$date = date('Y-m-d');
			$query = "select i.image_path,i.description,d.* from sh_t_transactions t 
			inner join sh_t_transaction_details d on d.id_trans = t.id
			inner join sh_m_item i on i.no = d.item_code 
			where d.selforder = 1
			and d.is_cancel = 0
			and d.is_printed_so = 1
			and d.id_trans = '".$id_trans."'
			and d.cekdata = '".$datake."'
			and left(d.created_date,10) ='".$date."'
			order by d.item_code desc";
			return $this->db->query($query);
			
		}
		public function cekdatatrans($id_customer)
		{
			$date = date('Y-m-d');
			$query = "select * from sh_t_transactions t 
			where t.id_customer = '".$id_customer."'
			and left(t.create_date,10) ='".$date."'";
			return $this->db->query($query);
		}
		public function cekdatacart($no,$uoi)
		{
			$date = date('Y-m-d');
			$ic = $this->session->userdata('id');
			$query = "select * from sh_cart c 
			where c.item_code = '".$no."'
			and left(c.entry_date,10) ='".$date."' and c.id_customer = '".$ic."'";
			return $this->db->query($query);
		}
		public function cekdatatransdetail($id_trans)
		{
		    $date = date('Y-m-d');
		    $query = "SELECT MAX(cekdata) as max_cekdata 
		              FROM sh_t_transaction_details 
		              WHERE id_trans = '".$id_trans."'
		              AND LEFT(created_date,10) = '".$date."'";
		    return $this->db->query($query);
		}
		public function billsementara($id_customer)
		{
			$q = "select * from sh_m_setup ";
			$setup = $this->db->query($q)->row();
			$scPercent = $setup->sc_percent;
			$taxPercent = $setup->tax_percent;

			//trans
			$q1 = "select * from sh_t_transactions where id_customer = '".$id_customer."' limit 1";
			$trans = $this->db->query($q1)->row();
			$notrans = $trans->id;
			$cabang = $trans->cabang;

			//bill header
			$query = "select c.customer_name, a.id_trans, c.total_pax as totalpax_reservasi, (select sum(t.qty) as ttl from (select d.qty from sh_t_transaction_details d inner join sh_m_item m on d.item_code = m.no where d.id_trans = ".$notrans." and m.category in ('DEWASA','SENIOR','ANAK') group by d.selected_table_no,d.seat_id) as t) as totalpax_actual, 
							(select sum(d.unit_price * d.qty) as total from sh_t_transaction_details d where d.is_cancel = 0 and d.id_trans = ".$notrans." group by d.id_trans) as total, ((select sum(d.unit_price * d.qty) as total from sh_t_transaction_details d where d.is_cancel = 0 and d.id_trans = ".$notrans." group by d.id_trans) * (".$scPercent."/100)) as sc, ((((select sum(d.unit_price * d.qty) as total from sh_t_transaction_details d where d.is_cancel = 0 and d.id_trans = ".$notrans." group by d.id_trans) * (".$scPercent."/100)) * (".$taxPercent."/100)) + ((select sum(d.unit_price * d.qty) as total from sh_t_transaction_details d where d.is_cancel = 0 and d.id_trans = ".$notrans." group by d.id_trans) * (".$taxPercent."/100))) as ppn, (select group_concat(xx.id_table) from sh_rel_table xx inner join sh_trans_reltable strx on strx.id_rel_table = xx.id inner join sh_t_transactions tx on tx.id = strx.id_trans where tx.id = ".$notrans.") as no_table, b.bill_printed_count as print_count 
								  from sh_t_transaction_details a inner join sh_t_transactions b on a.id_trans = b.id 
								  inner join sh_m_customer c on c.id = b.id_customer where a.is_cancel = 0 and b.cabang = ".$cabang." and b.id= ".$notrans." and Left(b.create_date, 10) = Left(SYSDATE(), 10) limit 1";
			$header = $this->db->query($query)->row();

			//bill line
			$query1 = "select  a.description, case when a.unit_price > 0 then a.unit_price else 'FREE' end as unit_price, case when sum(a.qty*a.unit_price) > 0 then sum(a.qty*a.unit_price) else 'FREE' end as sub_total 
								  from sh_t_transaction_details a 
								  inner join sh_t_transactions b on a.id_trans = b.id 
								  inner join sh_m_customer c on c.id = b.id_customer where a.is_cancel = 0 and b.cabang = ".$cabang." and b.id= ".$notrans." group by a.item_code,a.id_trans order by a.item_code asc";
			return $this->db->query($query1);
		}
		public function getDataSubOrder($uc)
		{
			$this->db->select('*');
	        $this->db->from('sh_t_sub_transactions');
	        $this->db->where('entry_by',$uc);
	              
	        $query = $this->db->get()->result_array();
	        return $query;
		}
		public function getDataCek($ic)
		{
			$this->db->select('*');
	        $this->db->from('sh_cart');
	        $this->db->where('id_customer',$ic);
	              
	        $query = $this->db->get()->result_array();
	        return $query;
		}
		public function get_Cart($ic,$table,$itemCode,$uoi)
		{
			$this->db->select('*');
	        $this->db->from('sh_cart');
	        $this->db->where(['id_customer'=>$ic,'id_table'=>$table,'item_code'=>$itemCode]);
	        $query = $this->db->get();
	        return $query;
		}
		public function save($table,$data, $where='') {
			if ($where == '') {
				$this->db->insert($table, $data);
				return $this->db->insert_id();
			}
			return $this->db->update($table, $data, $where);			
		}
		public function cart($ic)
		{
		    $date = date('Y-m-d'); // Mengambil tanggal hari ini
		    $uoi = $this->session->userdata('user_order_id'); // Mengambil user_order_id dari session

		    // Menggunakan Query Builder untuk memilih data yang diperlukan
		    // $this->db->select('i.harga_weekday,i.harga_weekend,i.harga_holiday,i.description as ad, o.description as od, m.image_path, m.need_stock, d.*');
		    $this->db->select('COALESCE(im.image_path, m.image_path) as image_path, m.need_stock,m.stock,m.is_sold_out,m.consignment,m.sub_category, d.*');
		    $this->db->from('sh_cart d');
		    $this->db->join('sh_m_item m', 'm.no = d.item_code', 'left'); 
		    $this->db->join('sh_m_item_image im', 'm.no = im.item_code', 'left'); 
		    // $this->db->join('sh_m_item i', 'i.no = d.addons', 'left');

		    // Menggunakan where clause yang lebih aman
		    $this->db->where('DATE(d.entry_date)', $date); // Mengambil entry_date berdasarkan tanggal
		    $this->db->where('d.id_customer', $ic); // Memastikan bahwa id_customer sama dengan parameter yang diberikan
		    // $this->db->where('d.user_order_id', $uoi);
		    $this->db->where('(d.addons = 0 OR d.addons IS NULL)', null, false);

		    // Eksekusi query
		    $query = $this->db->get();
		    return $query; // Mengembalikan hasil sebagai array objek
		}
		public function cartadd($ic)
		{
		    $date = date('Y-m-d'); // Mengambil tanggal hari ini
		    $uoi = $this->session->userdata('user_order_id'); // Mengambil user_order_id dari session

		    // Menggunakan Query Builder untuk memilih data yang diperlukan
		    // $this->db->select('i.harga_weekday,i.harga_weekend,i.harga_holiday,i.description as ad, o.description as od, m.image_path, m.need_stock, d.*');
		    $this->db->select('o.description as od, m.image_path, m.need_stock, d.*');
		    $this->db->from('sh_cart d');
		    $this->db->join('sh_m_item m', 'm.no = d.item_code', 'left'); // Menggunakan 'left' join jika diperlukan
		    $this->db->join('sh_m_item_option o', 'o.id = d.options', 'left');
		    // $this->db->join('sh_m_item i', 'i.no = d.addons', 'left');

		    // Menggunakan where clause yang lebih aman
		    $this->db->where('DATE(d.entry_date)', $date); // Mengambil entry_date berdasarkan tanggal
		    $this->db->where('d.id_customer', $ic); // Memastikan bahwa id_customer sama dengan parameter yang diberikan
		    // $this->db->where('d.user_order_id', $uoi); 
		    $this->db->where('d.addons', 1);
		    // Eksekusi query
		    $query = $this->db->get();
		    return $query; // Mengembalikan hasil sebagai array objek
		}

		public function total($uc){
      		$this->db->select('SUM(unit_price * qty) as total');
			$this->db->from('sh_t_transaction_details');
			$this->db->where('entry_by',$uc);
			return $this->db->get()->row()->total;
   		}
   		public function totalSubOrder($nomeja, $ic, $uoi, $idt)
		{
		    $this->db->select('SUM(CASE WHEN unit_price_disc = 0 THEN unit_price * qty ELSE unit_price_disc * qty END) as total');
		    $this->db->from('sh_cart');
		    $this->db->where('id_table', $nomeja);
		    $this->db->where('id_customer', $ic);
		    // $this->db->where('user_order_id', $uoi);
		    $this->db->where('id_trans', $idt);
		    return $this->db->get()->row()->total;
		}


   		public function log($nomeja)
   		{
   			// print_r($date);die();
   			$sql = "select R.*,C.customer_name from sh_rel_table R inner join sh_m_customer C on C.id = R.id_customer where R.id_table = '$nomeja' and left(C.create_date,10) = left(sysdate(),10) and R.status in ('Order','Dining','Billing') limit 1";
	        return $this->db->query($sql);
   		}
   		public function order_bill($cabang,$notrans)
		{
			//get setup
			$q = "select * from sh_m_setup ";
			$setup = $this->db->query($q)->row();
			$scP = $setup->sc_percent;
			$taxP = $setup->tax_percent;

			$query = "
				SELECT 
				    c.customer_name,
				    a.id_trans,
				    c.total_pax AS totalpax_reservasi,

				    (
				        SELECT COUNT(t.seat_id) 
				        FROM (
				            SELECT d.seat_id 
				            FROM sh_t_transaction_details d  
				            WHERE d.id_trans = $notrans
				              AND d.is_cancel = 0
				              AND d.is_paid = 0
				            GROUP BY d.selected_table_no, d.seat_id
				        ) t
				    ) AS totalpax_actual,

				    (
				        SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100)))
				        FROM sh_t_transaction_details d 
				        WHERE d.is_cancel = 0 
				          AND d.is_paid = 0
				          AND d.id_trans = $notrans
				        GROUP BY d.id_trans
				    ) AS total,

				    (
				        (
				            SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100)))
				            FROM sh_t_transaction_details d  
				            INNER JOIN sh_m_item i ON d.item_code = i.no 
				            WHERE d.is_cancel = 0 
				              AND d.is_paid = 0
				              AND d.id_trans = $notrans 
				              AND i.consignment = 0
				            GROUP BY d.id_trans
				        ) * ($scP/100)
				    ) AS sc,

				    (
				        (
				            (
				                SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100)))
				                FROM sh_t_transaction_details d 
				                INNER JOIN sh_m_item i ON d.item_code = i.no 
				                WHERE d.is_cancel = 0 
				                  AND d.is_paid = 0
				                  AND d.id_trans = $notrans 
				                  AND i.consignment = 0
				                GROUP BY d.id_trans
				            ) * ($scP/100)
				        ) * ($taxP/100)
				        +
				        (
				            (
				                SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100)))
				                FROM sh_t_transaction_details d 
				                INNER JOIN sh_m_item i ON d.item_code = i.no 
				                WHERE d.is_cancel = 0 
				                  AND d.is_paid = 0
				                  AND d.id_trans = $notrans 
				                  AND i.consignment = 0
				                GROUP BY d.id_trans
				            ) * ($taxP/100)
				        )
				    ) AS ppn,

				    (
				        SELECT GROUP_CONCAT(xx.id_table)
				        FROM sh_rel_table xx
				        INNER JOIN sh_trans_reltable strx ON strx.id_rel_table = xx.id
				        INNER JOIN sh_t_transactions tx ON tx.id = strx.id_trans
				        WHERE tx.id = $notrans
				    ) AS no_table,

				    b.bill_printed_count AS print_count

				FROM sh_t_transaction_details a
				INNER JOIN sh_t_transactions b ON a.id_trans = b.id 
				INNER JOIN sh_m_customer c ON c.id = b.id_customer
				WHERE a.is_cancel = 0
				  AND a.is_paid = 0
				  AND b.cabang = $cabang
				  AND b.id = $notrans
				  AND LEFT(b.create_date, 10) = LEFT(SYSDATE(), 10)
				LIMIT 1
				";

			return $this->db->query($query)->row();
		}
		public function order_bill_struk($cabang,$notrans)
		{
			//get setup
			$q = "select * from sh_m_setup ";
			$setup = $this->db->query($q)->row();
			$scP = $setup->sc_percent;
			$taxP = $setup->tax_percent;

			$query = "
				SELECT 
				    c.customer_name,
				    a.id_trans,
				    c.total_pax AS totalpax_reservasi,

				    (
				        SELECT COUNT(t.seat_id) 
				        FROM (
				            SELECT d.seat_id 
				            FROM sh_t_transaction_details d  
				            WHERE d.id_trans = $notrans
				              AND d.is_cancel = 0
				              AND d.is_paid = 1
				            GROUP BY d.selected_table_no, d.seat_id
				        ) t
				    ) AS totalpax_actual,

				    (
				        SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100)))
				        FROM sh_t_transaction_details d 
				        WHERE d.is_cancel = 0 
				          AND d.is_paid = 1
				          AND d.id_trans = $notrans
				        GROUP BY d.id_trans
				    ) AS total,

				    (
				        (
				            SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100)))
				            FROM sh_t_transaction_details d  
				            INNER JOIN sh_m_item i ON d.item_code = i.no 
				            WHERE d.is_cancel = 0 
				              AND d.is_paid = 1
				              AND d.id_trans = $notrans 
				              AND i.consignment = 0
				            GROUP BY d.id_trans
				        ) * ($scP/100)
				    ) AS sc,

				    (
				        (
				            (
				                SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100)))
				                FROM sh_t_transaction_details d 
				                INNER JOIN sh_m_item i ON d.item_code = i.no 
				                WHERE d.is_cancel = 0 
				                  AND d.is_paid = 1
				                  AND d.id_trans = $notrans 
				                  AND i.consignment = 0
				                GROUP BY d.id_trans
				            ) * ($scP/100)
				        ) * ($taxP/100)
				        +
				        (
				            (
				                SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100)))
				                FROM sh_t_transaction_details d 
				                INNER JOIN sh_m_item i ON d.item_code = i.no 
				                WHERE d.is_cancel = 0 
				                  AND d.is_paid = 1
				                  AND d.id_trans = $notrans 
				                  AND i.consignment = 0
				                GROUP BY d.id_trans
				            ) * ($taxP/100)
				        )
				    ) AS ppn,

				    (
				        SELECT GROUP_CONCAT(xx.id_table)
				        FROM sh_rel_table xx
				        INNER JOIN sh_trans_reltable strx ON strx.id_rel_table = xx.id
				        INNER JOIN sh_t_transactions tx ON tx.id = strx.id_trans
				        WHERE tx.id = $notrans
				    ) AS no_table,

				    b.bill_printed_count AS print_count

				FROM sh_t_transaction_details a
				INNER JOIN sh_t_transactions b ON a.id_trans = b.id 
				INNER JOIN sh_m_customer c ON c.id = b.id_customer
				WHERE a.is_cancel = 0
				  AND a.is_paid = 1
				  AND b.cabang = $cabang
				  AND b.id = $notrans
				  AND LEFT(b.create_date, 10) = LEFT(SYSDATE(), 10)
				LIMIT 1
				";

			return $this->db->query($query)->row();
		}
		public function order_bill_co($cabang,$notrans)
		{
			//get setup
			$q = "select * from sh_m_setup ";
			$setup = $this->db->query($q)->row();
			$scP = $setup->sc_percent;
			$taxP = $setup->tax_percent;
			//get setup
			$query = "select c.customer_name, a.id_trans, c.total_pax as totalpax_reservasi, (select count(t.seat_id) as ttl from (select d.seat_id from sh_t_sub_transactions d where d.id_trans = ".$notrans." group by d.selected_table_no,d.seat_id) as t) as totalpax_actual, (select (sum(d.unit_price * d.qty) - sum(d.unit_price * d.qty * (d.disc/100))) as total from sh_t_sub_transactions d where d.is_cancel = 0 and d.id_trans = ".$notrans." group by d.id_trans) as total, ((select (sum(d.unit_price * d.qty) - sum(d.unit_price * d.qty * (d.disc/100))) as total from sh_t_sub_transactions d where d.is_cancel = 0 and d.id_trans = ".$notrans." group by d.id_trans) * (".$scP."/100)) as sc, ((((select (sum(d.unit_price * d.qty) - sum(d.unit_price * d.qty * (d.disc/100))) as total from sh_t_sub_transactions d where d.is_cancel = 0 and d.id_trans = ".$notrans." group by d.id_trans) * (".$scP."/100)) * (".$taxP."/100)) + ((select (sum(d.unit_price * d.qty) - sum(d.unit_price * d.qty * (d.disc/100))) as total from sh_t_sub_transactions d where d.is_cancel = 0 and d.id_trans = ".$notrans." group by d.id_trans) * (".$taxP."/100))) as ppn, (select group_concat(xx.id_table) from sh_rel_table xx inner join sh_trans_reltable strx on strx.id_rel_table = xx.id inner join sh_t_transactions tx on tx.id = strx.id_trans where tx.id = ".$notrans.") as no_table, b.bill_printed_count as print_count 
								  from sh_t_sub_transactions a inner join sh_t_transactions b on a.id_trans = b.id 
								  inner join sh_m_customer c on c.id = b.id_customer where a.is_paid = 0 and a.is_cancel = 0 and b.cabang = ".$cabang." and b.id= ".$notrans." and Left(b.create_date, 10) = Left(SYSDATE(), 10) limit 1";
			return $this->db->query($query)->row();
		}

		public function order_bill_line($cabang,$notrans) 
		{
			$query = "select i.category,b.order_no,a.id,a.item_code,COALESCE(i.image_path, i.image_path) as image_path,sum(a.qty) as qty,a.disc,a.is_paid, a.description, case when a.unit_price > 0 then a.unit_price else 'FREE' end as unit_price, case when (sum(a.qty*a.unit_price) - sum(a.qty*a.unit_price * (a.disc/100))) > 0 then (sum(a.qty*a.unit_price) - sum(a.qty*a.unit_price * (a.disc/100))) else 'FREE' end as sub_total, a.extra_notes 
							  from sh_t_transaction_details a 
							  inner join sh_t_transactions b on a.id_trans = b.id
							  inner join sh_m_item i on a.item_code = i.no    
							  inner join sh_m_customer c on c.id = b.id_customer where a.is_cancel = 0 and b.cabang = ".$cabang." and a.parent_id_package = 0 and b.id= ".$notrans." group by a.item_code,a.id_trans order by a.id asc";
			return $this->db->query($query)->result();
		}
		
		public function totalbayar($notrans)
		{
		    // Get setup data (service charge and tax percentages)
		    $q = "SELECT * FROM sh_m_setup";
		    $setup = $this->db->query($q)->row();
		    $scP = $setup->sc_percent;  // Service charge percentage
		    $taxP = $setup->tax_percent;  // Tax percentage
		    $uoi = $this->session->userdata('user_order_id');

		    // Get the latest branch ID
		    $cabang = $this->db->order_by('id', 'desc')
		                        ->limit(1)
		                        ->get('sh_m_cabang')
		                        ->row('id');

		    $query = "SELECT 
	            ROUND(
	                (SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100))) 
	                 FROM sh_cart d 
	                 WHERE d.id_trans = ? 
	                 GROUP BY d.id_trans)
	            ) AS total, 

	            ROUND(
	                ((SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100))) 
	                  FROM sh_cart d 
	                  INNER JOIN sh_m_item i ON d.item_code = i.no 
	                  WHERE d.id_trans = ?
	                  AND i.consignment = 0 
	                  GROUP BY d.id_trans) * (? / 100))
	            ) AS sc, 

	            ROUND(
	                ((((SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100))) 
	                   FROM sh_cart d 
	                   INNER JOIN sh_m_item i ON d.item_code = i.no 
	                   WHERE d.id_trans = ? 
	                   AND i.consignment = 0 
	                   GROUP BY d.id_trans) * (? / 100)) * (? / 100)) 
	                + ((SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100))) 
	                   FROM sh_cart d 
	                   INNER JOIN sh_m_item i ON d.item_code = i.no 
	                   WHERE d.id_trans = ? 
	                   AND i.consignment = 0 
	                   GROUP BY d.id_trans) * (? / 100)))
	            ) AS ppn, 

	            (SELECT GROUP_CONCAT(xx.id_table) 
	             FROM sh_rel_table xx 
	             INNER JOIN sh_trans_reltable strx ON strx.id_rel_table = xx.id 
	             INNER JOIN sh_t_transactions tx ON tx.id = strx.id_trans 
	             WHERE tx.id = ?) AS no_table, 

	            b.bill_printed_count AS print_count 

	          FROM sh_cart a 
	          INNER JOIN sh_t_transactions b ON a.id_trans = b.id 
	          INNER JOIN sh_m_customer c ON c.id = b.id_customer 
	          WHERE b.cabang = ? 
	          AND b.id = ? 
	          AND LEFT(b.create_date, 10) = LEFT(SYSDATE(), 10) 
	          LIMIT 1";

		    return $this->db->query($query, [
		        $notrans,
		        $notrans, $scP, 
		        $notrans, $scP, $taxP, 
		        $notrans, $taxP, 
		        $notrans, $cabang, $notrans
		    ])->row();
		}
		public function totalbayaradmin($notrans)
		{
		    // Get setup data (service charge and tax percentages)
		    $q = "SELECT * FROM sh_m_setup";
		    $setup = $this->db->query($q)->row();
		    $scP = $setup->sc_percent;  // Service charge percentage
		    $taxP = $setup->tax_percent;  // Tax percentage
		    $uoi = $this->session->userdata('user_order_id');

		    // Get the latest branch ID
		    $cabang = $this->db->order_by('id', 'desc')
		                        ->limit(1)
		                        ->get('sh_m_cabang')
		                        ->row('id');

		    $query = "SELECT 
		                (SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100))) 
		                 FROM sh_t_transaction_details d 
		                 WHERE d.id_trans = ? 
		                 GROUP BY d.id_trans) AS total, 

		                ((SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100))) 
		                  FROM sh_t_transaction_details d 
		                  INNER JOIN sh_m_item i ON d.item_code = i.no 
		                  WHERE d.id_trans = ?
		                  AND i.consignment = 0 
		                  GROUP BY d.id_trans) * (? / 100)) AS sc, 

		                ((((SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100))) 
		                   FROM sh_t_transaction_details d 
		                   INNER JOIN sh_m_item i ON d.item_code = i.no 
		                   WHERE d.id_trans = ? 
		                   AND i.consignment = 0 
		                   GROUP BY d.id_trans) * (? / 100)) * (? / 100)) 
		                + ((SELECT (SUM(d.unit_price * d.qty) - SUM(d.unit_price * d.qty * (d.disc/100))) 
		                   FROM sh_t_transaction_details d 
		                   INNER JOIN sh_m_item i ON d.item_code = i.no 
		                   WHERE d.id_trans = ? 
		                   AND i.consignment = 0 
		                   GROUP BY d.id_trans) * (? / 100))) AS ppn, 

		                (SELECT GROUP_CONCAT(xx.id_table) 
		                 FROM sh_rel_table xx 
		                 INNER JOIN sh_trans_reltable strx ON strx.id_rel_table = xx.id 
		                 INNER JOIN sh_t_transactions tx ON tx.id = strx.id_trans 
		                 WHERE tx.id = ?) AS no_table, 

		                b.bill_printed_count AS print_count 

		              FROM sh_t_transaction_details a 
		              INNER JOIN sh_t_transactions b ON a.id_trans = b.id 
		              INNER JOIN sh_m_customer c ON c.id = b.id_customer 
		              WHERE b.cabang = ? 
		              AND b.id = ? 
		              AND LEFT(b.create_date, 10) = LEFT(SYSDATE(), 10) 
		              LIMIT 1";

		    return $this->db->query($query, [
		        $notrans,
		        $notrans, $scP, 
		        $notrans, $scP, $taxP, 
		        $notrans, $taxP, 
		        $notrans, $cabang, $notrans
		    ])->row();
		}

	public function get_keyword($keyword, $limit = 10, $offset = 0)
	{
	    $this->db->select("
	        i.sub_category, i.sub_category_so, i.description, i.image_path, i.id, 
	        i.harga_weekday, i.harga_weekend, i.harga_holiday, i.no, i.product_info, i.need_stock, 
	        i.is_sold_out, c.qty as cart_qty, c.id_customer, i.with_option, i.stock, d.qty as trans_qty,
	        e.date_from, e.date_to, e.time_from, e.time_to, e.item_code as event_item_code,e.type
	    ");

	    $this->db->from('sh_m_item i');
	    $this->db->where("i.is_active", 1);
	    $this->db->where("i.harga_weekday >", 10);
	    $this->db->where_not_in('LOWER(i.category)', array('minuman'));
	    $this->db->where_not_in("i.sub_category", ['Online','Buffet','PAKET','FREE']);
	    $this->db->where("i.is_paket_so", 0);

	    // stok
	    $this->db->group_start();
	    for ($m = 1; $m <= 26; $m++) {
	        $this->db->or_where("i.monitor{$m} !=", 0);
	    }
	    $this->db->group_end();

	    if (!empty($keyword)) {
	        $this->db->like('i.description', $keyword);
	    }

	    $this->db->join("sh_m_item_event e", "i.no = e.item_code", "left");
	    $this->db->join("sh_cart c", "i.no = c.item_code", "left");
	    $this->db->join("sh_t_transaction_details d", "i.no = d.item_code", "left");

	    $this->db->group_by("i.id");
	    $this->db->order_by("i.description", "asc");
	    $this->db->limit($limit, $offset);

	    return $this->db->get()->result();
	}




	public function get_keyword_minuman($keyword, $limit = 10, $offset = 0)
	{
	    $this->db->select("
	        i.sub_category, i.sub_category_so, i.description, i.image_path, i.id, 
	        i.harga_weekday, i.harga_weekend, i.harga_holiday, i.no, i.product_info, i.need_stock, 
	        i.is_sold_out, c.qty as cart_qty, c.id_customer, i.with_option, i.stock, d.qty as trans_qty,
	        e.date_from, e.date_to, e.time_from, e.time_to, e.item_code as event_item_code,e.type
	    ");

	    $this->db->from('sh_m_item i');
	    $this->db->where("i.is_active", 1);
	    $this->db->where("i.harga_weekday >", 10);
	    $this->db->where_in('LOWER(i.category)', array('minuman'));
	    $this->db->where_not_in("i.sub_category", ['Online','Buffet','PAKET','FREE']);
	    $this->db->where("i.is_paket_so", 0);

	    // stok
	    $this->db->group_start();
	    for ($m = 1; $m <= 26; $m++) {
	        $this->db->or_where("i.monitor{$m} !=", 0);
	    }
	    $this->db->group_end();

	    if (!empty($keyword)) {
	        $this->db->like('i.description', $keyword);
	    }

	    $this->db->join("sh_m_item_event e", "i.no = e.item_code", "left");
	    $this->db->join("sh_cart c", "i.no = c.item_code", "left");
	    $this->db->join("sh_t_transaction_details d", "i.no = d.item_code", "left");

	    $this->db->group_by("i.id");
	    $this->db->order_by("i.description", "asc");
	    $this->db->limit($limit, $offset);

	    return $this->db->get()->result();
	}
	public function hitungcart($nomeja)
			{
				$date = date('Y-m-d');
				$idc = $this->session->userdata('id');
				$this->db->select('*');
				$this->db->from('sh_cart');
	        	$where = "left(entry_date,10) ='".$date."' and id_customer = '".$idc."' and id_table = '".$nomeja."'";
	        	$this->db->where($where);

				return $this->db->count_all_results();
			}
			public function cart_count($ic,$nomeja)
		{
			$date = date('Y-m-d');
			$uoi = $this->session->userdata('user_order_id');
			$this->db->select('sum(d.qty) as total_qty');
	        $this->db->from('sh_cart d');
	        $where = "left(entry_date,10) ='".$date."' and id_customer = '".$ic."' and id_table = '".$nomeja."'";
	        $this->db->where($where);
	        $this->db->group_by('d.id_trans,d.id_table');      
	        $query = $this->db->get();
	        return $query;
		}
		public function count_cart_qty($ic,$nomeja)
		{
			$date = date('Y-m-d');
			$this->db->select('sum(d.qty) as total_qty_cart');
	        $this->db->from('sh_cart d');
	        $where = "left(entry_date,10) ='".$date."' and id_customer = '".$ic."' and id_table = '".$nomeja."'";
	        $this->db->where($where);
	        $this->db->group_by('d.id_trans,d.id_table');      
	        $query = $this->db->get();
	        return $query;
		}

		public function updatecart($where,$data,$table)
		{
			$this->db->where($where);
			$this->db->update($table,$data);
		}
		public function option($item_code)
		{
				$this->db->select('o.*');
				$this->db->from('sh_m_item_option o');
				$this->db->join('sh_m_item m', 'm.no = o.item_code');
				$this->db->where('o.item_code',$item_code);
				$this->db->where('o.option',1);
				return $this->db->get()->result();
		}
		public function option2($item_code)
		{
				$this->db->select('o.*');
				$this->db->from('sh_m_item_option o');
				$this->db->join('sh_m_item m', 'm.no = o.item_code');
				$this->db->where('o.item_code',$item_code);
				$this->db->where('o.option',2);
				return $this->db->get()->result();
		}
		public function option3($item_code)
		{
				$this->db->select('o.*');
				$this->db->from('sh_m_item_option o');
				$this->db->join('sh_m_item m', 'm.no = o.item_code');
				$this->db->where('o.item_code',$item_code);
				$this->db->where('o.option',3);
				return $this->db->get()->result();
		}
		public function cekpesan($item_code)
		{
		  $id_customer = $this->session->userdata('id');
		  $q1 = "select * from sh_t_transactions where id_customer = '".$id_customer."' limit 1";
			$trans = $this->db->query($q1)->row();
			$notrans = $trans->id;
			$cabang = $trans->cabang;
				$query = "select a.item_code,sum(a.qty) as qty, a.description, case when a.unit_price > 0 then a.unit_price else 'FREE' end as unit_price, case when (sum(a.qty*a.unit_price) - sum(a.qty*a.unit_price * (a.disc/100))) > 0 then (sum(a.qty*a.unit_price) - sum(a.qty*a.unit_price * (a.disc/100))) else 'FREE' end as sub_total 
					  from sh_t_transaction_details a 
					  inner join sh_t_transactions b on a.id_trans = b.id 
					  inner join sh_m_customer c on c.id = b.id_customer where a.is_cancel = 0 and b.cabang = ".$cabang." and b.id= ".$notrans." and item_code = '".$item_code."' and id_trans = '".$notrans."' and selforder = 1  and left(created_date,10) = left(sysdate(),10) group by a.item_code,a.id_trans order by a.id asc";
			return $this->db->query($query)->result();
		}
		public function cekcart($item_code)
		{
		  $id_customer = $this->session->userdata('id');
		  $q1 = "select * from sh_cart where id_customer = '".$id_customer."' limit 1";
			$trans = $this->db->query($q1)->row();
			$notrans = $trans->id;
			$cabang = $trans->cabang;
		  // $wh = "item_code = '".$item_code."' and id_trans = '".$notrans."' and left(created_date,10) = left(sysdate(),10)";
		  // $co = $this->db
  		// 	->where($wh)
  		// 	->get('sh_t_transaction_details')
  		// 	->num_rows();
				// $this->db->select('*');
				// $this->db->from('sh_t_transaction_details');
				// $this->db->where($wh);
				// return $this->db->get()->result();

				$query = "select a.item_code,sum(a.qty) as qty, a.description, case when a.unit_price > 0 then a.unit_price else 'FREE' end as unit_price, case when (sum(a.qty*a.unit_price) - sum(a.qty*a.unit_price * (a.disc/100))) > 0 then (sum(a.qty*a.unit_price) - sum(a.qty*a.unit_price * (a.disc/100))) else 'FREE' end as sub_total 
					  from sh_cart a 
					  where a.cabang = ".$cabang." and a.item_code = '".$item_code."' and a.id_trans = '".$notrans."' and left(created_date,10) = left(sysdate(),10) group by a.item_code,a.id_trans order by a.id asc";
			return $this->db->query($query)->result();
		}
		public function kode($nomeja)
		{
			$date = date('Y-m-d');
			$this->db->select('RIGHT(u.user_order_id,2) as code', FALSE);
			$where = "left(u.created_date,10) ='".$date."' and u.id_table = '".$nomeja."'";
			$this->db->where($where);
			// $this->db->order_by('u.user_order_id','DESC');    
			$this->db->limit(1);    
			$query = $this->db->get('sh_log_user u');  //cek dulu apakah ada sudah ada kode di tabel.    
			if($query->num_rows() <> 0){      
				 //cek kode jika telah tersedia    
				 $data = $query->row();      
				 $kode = intval($data->code) + 1; 
			}
			else{      
				 $kode = 1;  //cek jika kode belum terdapat pada table
			}
			// $tgl = date('dmY'); 
			$batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
			$kodetampil = $nomeja.$batas;  //format kode
			return $kodetampil;
		}
		public function getAddOn($no)
		{
			$this->db->select('*');
			$this->db->from('sh_m_item_option o');
			$this->db->join('sh_m_item m', 'm.no = o.description');
			$this->db->where('o.item_code',$no);
			$this->db->where('o.type','addon');
			return $this->db->get()->result();
		}
		public function getOption($item_code)
		{
				$this->db->select('o.*');
				$this->db->from('sh_m_item_option o');
				$this->db->where('o.item_code',$item_code);
				$this->db->where('o.type','option');
				$this->db->where('o.is_active', 1);
				return $this->db->get()->result();

				
		}
		public function getOptionGrouped($item_code) 
		{
		    $this->db->select('o.*');
		    $this->db->from('sh_m_item_option o');
		    $this->db->where('o.item_code', $item_code);
		    $this->db->where('o.type', 'option');
		    $this->db->where('o.is_active',1);
		    $this->db->order_by('o.option'); // urutkan per group
		    $options = $this->db->get()->result();

		    // grupkan per option
		    $groupedOptions = [];
		    foreach ($options as $o) {
		        $groupedOptions[$o->option][] = $o;
		    }

		    return $groupedOptions;
		}
		public function GetItemADD($item_code)
		{
			$this->db->select('o.*');
			$this->db->from('sh_m_item o');
			$this->db->where('o.no',$item_code);
			return $this->db->get()->row();
		}
		public function getADDONS($item_code)
		{
			$date = date('Y-m-d'); 
		    $uoi = $this->session->userdata('user_order_id');
		    $ic = $this->session->userdata('id'); 

		    $this->db->select('m.image_path, m.need_stock,m.stock, d.*');
		    $this->db->from('sh_cart d');
		    $this->db->join('sh_m_item m', 'm.no = d.item_code', 'left');
		    $this->db->where('DATE(d.entry_date)', $date); 
		    $this->db->where('d.id_customer', $ic); 
		    // $this->db->where('d.user_order_id', $uoi);
		    $this->db->where('d.addons', 1);
		    $this->db->where('d.item_code_header', $item_code);
		    
		    $query = $this->db->get()->result();
		    return $query; 
		}
		public function cekpromo($sub)
		{
		    $date = date('Y-m-d');
		    $time = date('H');

		    $this->db->select('promo_value');
		    $this->db->from('sh_m_promo');
		    $this->db->where('promo_start_date <=', $date);
		    $this->db->where('promo_end_date >=', $date); 
		    $this->db->where('promo_filter', 'sub_category'); 
		    $this->db->where('filter_value', $sub);
		    $this->db->where('promo_from <=', $time);
		    $this->db->where('promo_to >=', $time);
		    
		    $query = $this->db->get()->row();
		    return $query;
		}
		//DEV
		public function cekpromoharian($sub,$no)
		{
		    $date = date('Y-m-d');
		    $time = date('H');
		    $day  = date('l'); // Monday, Tuesday, dst

		    $this->db->select('promo_value,promo_type');
		    $this->db->from('sh_m_promo');
		    $this->db->where('promo_start_date <=', $date);
		    $this->db->where('promo_end_date >=', $date); 
		    $this->db->where_in('promo_type', ['Special Price', 'Percentage']);
		    $this->db->where_in('promo_filter', ['Item', 'sub_category']);
		    $this->db->where('filter_value', $no);
		    $this->db->where('promo_from <=', $time);
		    $this->db->where('promo_to >=', $time);

		    // === FILTER HARI ===
		    $this->db->group_start();
		        $this->db->where("FIND_IN_SET('$day', promo_criteria) !=", 0);
		        $this->db->or_where("promo_criteria", 'Everyday');

		        if ($day == 'Saturday' || $day == 'Sunday') {
		            $this->db->or_where("promo_criteria", 'Weekend');
		        }

		        if (!in_array($day, ['Saturday', 'Sunday'])) {
		            $this->db->or_where("promo_criteria", 'Weekday');
		        }
		    $this->db->group_end();

		    return $this->db->get()->row();
		}


		public function cekSPchart($no = NULL)
		{
			$date = date('Y-m-d');
		    $id_customer = $this->session->userdata('id');
		 	
		    $this->db->select('*');
		    $this->db->from('sh_cart c');
		    $this->db->join('sh_m_item i', 'i.no = c.item_code', 'left');
		    if ($no) {
		    	$this->db->where('c.item_code', $no); 
		    }
		    $this->db->where("left(c.entry_date, 10) =", $date);
		    $this->db->where('c.id_customer', $id_customer); 
		    $this->db->where('i.sub_category', 'Special Promo');
		    
		    $query = $this->db->get()->row();
		    return $query;
		}
		public function cekSPtrans($no=NULL)
		{
		    $date = date('Y-m-d');
		    $id_customer = $this->session->userdata('id');

		    $this->db->select('tr.*, d.*, i.*');
		    $this->db->from('sh_t_transactions tr');
		    $this->db->join('sh_t_transaction_details d', 'tr.id = d.id_trans', 'left');
		    $this->db->join('sh_m_item i', 'i.no = d.item_code', 'left');
		    if ($no) {
		    	$this->db->where('d.item_code', $no); 
		    }
		    $this->db->where('DATE(tr.create_date)', $date);
		    $this->db->where('tr.id_customer', $id_customer);
		    $this->db->where('d.is_cancel', 0);
		    $this->db->where('i.sub_category', 'Special Promo');

		    $query = $this->db->get();

		    return $query->row(); // hanya ambil 1 baris
		}
		public function countSPchart($no = null)
		{
		    $date = date('Y-m-d');
		    $id_customer = $this->session->userdata('id');

		    $this->db->from('sh_cart c');
		    $this->db->join('sh_m_item i', 'i.no = c.item_code', 'left');
		    $this->db->where('DATE(c.entry_date)', $date);
		    $this->db->where('c.id_customer', $id_customer);
		    $this->db->where('i.sub_category', 'Special Promo');

		    if ($no) {
		        $this->db->where('c.item_code', $no);
		    }

		    return $this->db->count_all_results();
		}

		public function countSPtrans($no = null)
		{
		    $date = date('Y-m-d');
		    $id_customer = $this->session->userdata('id');

		    $this->db->from('sh_t_transactions tr');
		    $this->db->join('sh_t_transaction_details d', 'tr.id = d.id_trans', 'left');
		    $this->db->join('sh_m_item i', 'i.no = d.item_code', 'left');
		    $this->db->where('DATE(tr.create_date)', $date);
		    $this->db->where('tr.id_customer', $id_customer);
		    $this->db->where('d.is_cancel', 0);
		    $this->db->where('i.sub_category', 'Special Promo');

		    if ($no) {
		        $this->db->where('d.item_code', $no);
		    }

		    return $this->db->count_all_results();
		}
		public function getItemByCode($no)
		{
		    // Pastikan parameter bukan array
		    if (is_array($no)) {
		        $no = reset($no); // ambil elemen pertama kalau array
		    }

		    // Pastikan nilainya ada dan tidak kosong
		    if (empty($no)) {
		        return null;
		    }

		    // Ambil item dari tabel sh_m_item
		    $query = $this->db->get_where('sh_m_item', ['no' => $no]);

		    // Kembalikan satu baris data (objek)
		    return $query->row();
		}

		
		public function getitem($id_trans,$cek)
		{
			if ($cek == 'parent') {
				$sql = "SELECT C.* FROM sh_t_transaction_details C INNER JOIN sh_t_transactions T ON C.id_trans = T.id WHERE T.parent_id = '".$id_trans."' AND C.is_paid = 0 AND LEFT(T.create_date, 10) = LEFT(SYSDATE(), 10)";
			}else{
				$sql = "SELECT C.* FROM sh_t_transaction_details C INNER JOIN sh_t_transactions T ON C.id_trans = T.id WHERE C.id_trans = '".$id_trans."' AND C.is_paid = 0 AND LEFT(T.create_date, 10) = LEFT(SYSDATE(), 10)";
			}
		    $cek = $this->db->query($sql)->result();
		    return $this->db->query($sql)->result();
		}

		public function get_setup($cabang)
		{
			if($cabang !='0'){
				$this->db->where(["s.cabang"=> $cabang]);
			}
			$this->db->select("s.*")
				->from("sh_m_setup s")
				->order_by("s.id", "asc");
			return $this->db->get()->row();
		}
		public function get_member_point($tipe,$id,$interval)
		{
			//Load database
			
			$today = date('Y-m-d');
			if(strtolower($tipe) == 'point'){
				$select = 'P.*';
				$where = " P.id_trans = '".$id."' and P.service_status = 'True' and P.is_void = 0 and left(P.transaction_date,10)='".$today."' and left(TIMEDIFF(sysdate(),P.transaction_date),8) < '".$interval."' order by P.id desc";
				
				return $this->db->select($select)
								->from('sh_point_member AS P')
								->where($where)
								->get();	
			}else if(strtolower($tipe) == 'inquiry'){
				$select = 'P.*';
				$where = " P.member_id = '".$id."'";
				
				return $this->db->select($select)
								->from('sh_inquiry_member AS P')
								->where($where)
								->get();
			}else if(strtolower($tipe) == 'redeem'){
				$query = "select t.id, ((redeem_point_amount/((SUM(dt.qty * dt.unit_price) - SUM(((dt.qty * dt.unit_price)*(dt.disc/100)))) - (((dt.qty * dt.unit_price) - ((dt.qty * dt.unit_price)*(dt.disc/100)))*(t.bill_discount_percent/100))))*100) as percent from sh_t_transactions t inner join sh_t_transaction_details dt on dt.id_trans = t.id where /*left(t.payment_date,10) = '".$interval."' and*/ t.redeem_point_amount > 0 and dt.is_cancel = 0 and dt.is_paid = 1 and t.id='".$id."'";
				return $this->db->query($query);
			}else if(strtolower($tipe) == 'redeempoint'){
				$query = "select t.id, ((redeem_point_amount/((SUM(dt.qty * dt.unit_price) - SUM(((dt.qty * dt.unit_price)*(dt.disc/100)))) - (((dt.qty * dt.unit_price) - ((dt.qty * dt.unit_price)*(dt.disc/100)))*(t.bill_discount_percent/100))))*100) as percent from sh_t_transactions t inner join sh_t_transaction_details dt on dt.id_trans = t.id where dt.is_cancel = 0 and dt.is_paid = 1 and t.id='".$id."'";
				return $this->db->query($query);
			}
		}
		public function api_service($tipe='Login',$data=null)
		{
			//Load database
			
			$setup = $this->db->get_where('sh_m_setup', ['id'=> 1])->row();
			$outlet = $this->db->get_where('sh_m_cabang', ['id'=> $setup->cabang])->row();
			$url = "";
			if($tipe == 'Login') {
				$url = $setup->member_login_url;
			}else if($tipe == 'Check') {
				$url = $setup->member_inquiry_url;
			}else if($tipe == 'Inquiry') {
				$url = $setup->member_inquiry_url;
			}else if($tipe == 'Transaction') {
				$url = $setup->member_transaction_url;
			}else if($tipe == 'TransactionWithPoint') {
				$url = $setup->member_transaction_url.'/complete';
			}else if($tipe == 'VoidTransaction') {
				$url = $setup->member_transaction_url.'/void';
			}else if($tipe == 'BookingTransaction') {
				$url = $setup->member_bookingtransaction_url.'/point';
			}else if($tipe == 'BookingList') {
				$url = $setup->member_bookingtransaction_url.'/list';
			}else if($tipe == 'BookingCancel') {
				$url = $setup->member_bookingtransaction_url.'/cancel';
			}else if($tipe == 'CheckTransaction') {
				$url = $setup->member_inquirytransaction_url;
			}else if($tipe == 'OTPREQUEST') {
				$url = $setup->member_otp_url.'/request';
			}else if($tipe == 'OTPCONFIRM') {
				$url = $setup->member_otp_url.'/confirm';
			}
		    $curl = curl_init();
		    // OPTIONS:
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    	curl_setopt($curl, CURLOPT_ENCODING, "");
	    	curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
	    	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		    if($tipe == 'Check') {
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"identity\": \"".$data['identity']."\"}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }else if($tipe == 'Login') {
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"username\": \"".$data['username']."\",\n  \"password\": \"".$data['password']."\"\n}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Access-Control-Allow-Origin: *'
			    ));
		    }else if($tipe == 'Transaction'){
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST"); 
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"member_id\": ".$data['member_id'].",\n  \"receipt_no\": \"".$data['order_no']."\",\n  \"total_transaction\": ".$data['amount'].",\n  \"promotion_amount\": ".$data['promo_amount']."\n}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }else if($tipe == 'TransactionWithPoint'){
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"booking_transaction_id\": ".$data['trans_id'].",\n  \"member_id\": ".$data['member_id'].",\n  \"receipt_no\": \"".$data['order_no']."\",\n  \"total_remain_payment\": ".$data['amount']."\n}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }else if($tipe == 'VoidTransaction'){
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"transaction_id\": ".$data['trans_id']."\n}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }else if($tipe == 'BookingTransaction'){
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"member_id\": ".$data['member_id'].",\n  \"point_usage\": ".$data['point'].",\n  \"total_transaction\": ".$data['amount'].",\n  \"promotion_amount\": ".$data['promo_amount']."\n}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }else if($tipe == 'BookingList'){
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"page\": ".$data['page'].",\n  \"limit\": ".$data['limit'].",\n  \"member_id\": ".$data['member_id']."\n}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }else if($tipe == 'BookingCancel'){
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"transaction_id\": ".$data['trans_id']."\n}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }else if($tipe == 'OTPREQUEST'){
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"transaction_id\": ".$data['trans_id']."\n}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }else if($tipe == 'OTPCONFIRM'){
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"transaction_id\": ".$data['trans_id'].",\n  \"otp\": \"".$data['otp']."\"\n}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }else if($tipe == 'CheckTransaction'){
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"transaction_id\": ".$data['trans_id']."\n}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }else{
		    	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, "{\n  \"identity\": \"".$data['identity']."\"}");
			    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			      'Content-Type: application/json',
			      'Authorization: Bearer ' . $setup->member_access_token
			    ));
		    }
		    
		    // EXECUTE:
		    $result = curl_exec($curl);
		    //var_dump($result);exit();
		    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		    //var_dump($status);exit();
		    curl_close($curl);
		    if($tipe == 'Check') {
		    	return json_decode($status);
		    }else if($tipe == 'Login') {
		    	return json_decode($result);
		    }else{
		    	return $result;	
		    }
		} 
		public function saveData($tabel,$data, $where='') {
			if ($where == '') {
				$this->db->insert($tabel, $data);
				return $this->db->insert_id();
			}
			return $this->db->update($tabel, $data, $where);			
		}
		
		// public function get_qty()
		// {
		// 	$ic = $this->session->userdata('id');
		// 	$this->db->select('*');
	 //        $this->db->from('sh_cart');
	 //        $this->db->where('id_customer',$ic);
	              
	 //        $query = $this->db->get()->row('qty');
	 //        return $query;
		// }				
	}
 ?>


