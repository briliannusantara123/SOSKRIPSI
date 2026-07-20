<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ordermakanan extends CI_Controller {

	function __construct()
		{
			parent::__construct();
			// if($this->session->userdata('username') == ""){
   //         		$nomeja = $this->session->userdata('nomeja');
  	// 			redirect('index.php/login/logout/'.$nomeja);
   //      	}
			$this->load->model('Item_model');
			$this->load->model('Admin_model');
			$this->load->model('cekstatus_model');
			$this->load->helper('cookie');
			$session = $this->cekstatus_model->cek();

	  		// if($session['status'] == 'Cleaning'){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('index.php/login/logout/'.$nomeja.'/cleaning');
	  		// }
	  		// if($session['id_table'] != $this->session->userdata('nomeja')){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('index.php/login/log_out/'.$nomeja);
	  		// }
	  		// if($session['status'] == 'Available'){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('index.php/login/log_out/'.$nomeja);
	  		// }
	  		// if($session['status'] == 'Payment'){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('index.php/login/logoutPayment/'.$nomeja);
	  		// }
	  		// if($session['status'] == 'Billing'){
		  	// 		$nomeja = $this->session->userdata('nomeja');
		  	// 		redirect('index.php/login/logoutback/'.$nomeja);
		  	// }
			
		}
	public function index($nomeja)
	{
		$notif = "";
		$id_customer = $this->session->userdata('id');
	    echo $nomeja;exit();
		//cek paket
		$paket = $this->Item_model->get_paket($nomeja);
		if($paket->tipe_paket == ''){
			$this->session->set_flashdata('error','Anda Belum Menentukan Paket,Silahkan hubungi Waitress Untuk Memilih Paket ');
			redirect('selforder');
		}

		//cek order paket
		$order_paket = $this->Item_model->get_order_paket($nomeja,$id_customer);
		if($order_paket->jml_paket == 0){
			$this->session->set_flashdata('error','Anda Belum Menentukan Paket,Silahkan hubungi Waitress Untuk Memilih Paket ');
			redirect('selforder');
		}

		//cek order kuah
		$order_kuah = $this->Item_model->get_order_kuah($nomeja,$id_customer);
		if(($paket->tipe_paket != '' && $paket->tipe_paket == 'Yakiniku Only') && ($order_kuah->jml_kuah == $order_paket->jml_paket)){
			
		}
		$data['item'] = $this->Item_model->getData();
			$this->load->view('ordermakanan',$data);
		
	}
	public function menuv2($tipe, $sub_category)
	{
	    $this->session->unset_userdata('notfound');

	    $id_customer = $this->session->userdata('id');
	    $nomeja      = $this->session->userdata('nomeja');

	    $hariIni      = date('N'); // 1=Senin, 7=Minggu
	    $current_hour = date('H');
	    $current_date = date('Y-m-d');

	    // Cek apakah hari ini libur
	    $this->db->where('holiday_date', $current_date);
	    $holiday = $this->db->get('sh_m_holiday')->num_rows() > 0;

	    // Tentukan tipe hari
	    $te = ($holiday || $hariIni >= 6) ? 'WEEKEND' : 'WEEKDAY';

	    // Ambil semua data dari model
	    $items_raw = $this->Item_model->getData($tipe, $sub_category);
	    // var_dump($items_raw);exit();
	    $items_filtered = [];
	    foreach ($items_raw as $item) {
	        $show = true;

	        if (!empty($item->event_item_code)) {
	            $itemType = strtoupper(trim($item->type));

	            // ✅ Cek apakah tanggal valid dulu
	            $tanggalBerlaku = (
	                !empty($item->date_from) &&
	                !empty($item->date_to) &&
	                $current_date >= $item->date_from &&
	                $current_date <= $item->date_to
	            );

	            if ($itemType === 'EVERYDAY') {
	                if ($tanggalBerlaku) {
	                    // Kalau tanggal valid → cek jam
	                    if (!empty($item->time_from) && !empty($item->time_to)) {
	                        if ($current_hour >= $item->time_from && $current_hour <= $item->time_to) {
	                            $show = true;  // tanggal & jam valid
	                        } else {
	                            $show = false; // tanggal valid tapi jam tidak valid
	                        }
	                    } else {
	                        $show = true; // tanggal valid tanpa batas jam
	                    }
	                } else {
	                    // Kalau tanggal tidak valid → tetap tampil
	                    $show = true;
	                }
	            } else {
	                // 🔹 Untuk WEEKDAY/WEEKEND
	                if ($tanggalBerlaku && $itemType === $te) {
				        if (!empty($item->time_from) && !empty($item->time_to)) {
				            if ($current_hour >= $item->time_from && $current_hour <= $item->time_to) {
				                $show = true; // semua valid
				            } else {
				                $show = false; // jam tidak valid
				            }
				        } else {
				            $show = true; // tanggal + type valid tanpa jam
				        }
				    } else {
				        // Jangan di-hide total → biarkan tampil default
				        $show = true;
				    }
	            }
	        }

	        if ($show) {
	            $items_filtered[] = $item;
	        }
	    }

	    // Ambil semua subcategory dari model
	    $subcategories_raw = $this->Item_model->sub_category();

	    // Filter subcategory berdasarkan weekday/weekend & jam
	    $subcategories_filtered = [];
	    foreach ($subcategories_raw as $sub) {
	        $show = true;

	        // Cek weekday/weekend
	        $today = date('l');
	        if (!empty($sub['weekday']) && stripos($sub['weekday'], $today) === false) {
	            if (!empty($sub['weekend']) && stripos($sub['weekend'], $today) === false) {
	                $show = false;
	            }
	        }

	        // Cek jam aktif
	        if (!empty($sub['time_from']) && !empty($sub['time_to'])) {
	            if ($current_hour < $sub['time_from'] || $current_hour > $sub['time_to']) {
	                $show = false;
	            }
	        }

	        if ($show) {
	            $subcategories_filtered[] = $sub;
	        }
	    }

	    // === Tambahkan Signature jika ada chef_recommended ===
	    $signature_exists = false;
	    foreach ($subcategories_filtered as $sf) {
	        if (strtolower($sf['sub_category']) === 'signature') {
	            $signature_exists = true;
	            break;
	        }
	    }

	    if (!$signature_exists) {
	        $has_signature_item = $this->db
	            ->where('is_active', 1)
	            ->where('chef_recommended', 1)
	            ->count_all_results('sh_m_item');

	        if ($has_signature_item > 0) {
	            $subcategories_filtered[] = [
	                'sub_category' => 'Signature',
	                'id'           => 0,
	                'weekday'      => '',
	                'weekend'      => '',
	                'time_from'    => '',
	                'time_to'      => ''
	            ];
	        }
	    }

	    // Pastikan Signature tetap di awal
	    usort($subcategories_filtered, function ($a, $b) {
	        if ($a['sub_category'] === 'Signature') return -1;
	        if ($b['sub_category'] === 'Signature') return 1;
	        if ($a['id'] == $b['id']) return 0;
	        return ($a['id'] < $b['id']) ? -1 : 1;
	    });
	    
	    // Kirim data ke view
	    $data['item']       = $items_filtered;
	    $data['sub']        = $subcategories_filtered;
	    $data['sube']       = $this->Item_model->sub_category_event();
	    $data['s']          = $sub_category;
	    $data['ic']         = $id_customer;
	    $data['key']        = '';
	    $data['cart_count'] = $this->Item_model->hitungcart($nomeja);
	    $data['nomeja']     = $nomeja;

	    $cart_count = $this->Item_model->cart_count($id_customer, $nomeja)->num_rows();
	    $data['total_qty'] = $cart_count > 0 ? $this->Item_model->cart_count($id_customer, $nomeja)->row()->total_qty : 0;

	    $data['iconfooter'] = $this->Admin_model->getIcon('footer');
	    $data['cn']         = $this->Admin_model->getColorCN();
	    $data['ch']         = $this->Admin_model->getColorHD();
	    $data['cb']         = $this->Admin_model->getColorBTN();
	    $data['logo']       = $this->Admin_model->getLogo();
	    $data['keyword'] = '';
    	$data['offset']  = 0;
	    $trans = $this->db->order_by('create_date', 'DESC')
                  ->get_where('sh_t_transactions', array('id_customer' => $id_customer))
                  ->row();
        if ($trans->parent_id != 0) {
        	$data['cekpay'] = $this->Item_model->getitem($trans->parent_id,'parent');
        }else{
        	$data['cekpay'] = $this->Item_model->getitem($trans->id,'notparent');
        }

	    $this->load->view('ordermakanan', $data);
	}
	public function menu($tipe, $sub_category)
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    $this->session->unset_userdata('notfound');

	    $id_customer = $this->session->userdata('id');
	    $nomeja      = $this->session->userdata('nomeja');
	    $username = $this->session->userdata('username');
	    $no_telp      = $this->session->userdata('no_telp');

	    $hariIni      = date('N'); // 1=Senin, 7=Minggu
	    $current_hour = date('H');
	    $current_date = date('Y-m-d');

	    // Cek apakah hari ini libur
	    $this->db->where('holiday_date', $current_date);
	    $holiday = $this->db->get('sh_m_holiday')->num_rows() > 0;

	    // Tentukan tipe hari
	    $te = ($holiday || $hariIni >= 6) ? 'WEEKEND' : 'WEEKDAY';

	    // Ambil semua data dari model
	    $items_raw = $this->Item_model->getData($tipe, $sub_category);
	    // var_dump($items_raw);exit();
	    $items_filtered = [];
	    foreach ($items_raw as $item) {
	        $show = true;

	        if (!empty($item->event_item_code)) {
	            $itemType = strtoupper(trim($item->type));

	            // ✅ Cek apakah tanggal valid dulu
	            $tanggalBerlaku = (
	                !empty($item->date_from) &&
	                !empty($item->date_to) &&
	                $current_date >= $item->date_from &&
	                $current_date <= $item->date_to
	            );

	            if ($itemType === 'EVERYDAY') {
	                if ($tanggalBerlaku) {
	                    // Kalau tanggal valid → cek jam
	                    if (!empty($item->time_from) && !empty($item->time_to)) {
	                        if ($current_hour >= $item->time_from && $current_hour <= $item->time_to) {
	                            $show = true;  // tanggal & jam valid
	                        } else {
	                            $show = false; // tanggal valid tapi jam tidak valid
	                        }
	                    } else {
	                        $show = true; // tanggal valid tanpa batas jam
	                    }
	                } else {
	                    // Kalau tanggal tidak valid → tetap tampil
	                    $show = true;
	                }
	            } else {
	                // 🔹 Untuk WEEKDAY/WEEKEND
	                if ($tanggalBerlaku && $itemType === $te) {
				        if (!empty($item->time_from) && !empty($item->time_to)) {
				            if ($current_hour >= $item->time_from && $current_hour <= $item->time_to) {
				                $show = true; // semua valid
				            } else {
				                $show = false; // jam tidak valid
				            }
				        } else {
				            $show = true; // tanggal + type valid tanpa jam
				        }
				    } else {
				        // Jangan di-hide total → biarkan tampil default
				        $show = true;
				    }
	            }
	        }

	        if ($show) {
	            $items_filtered[] = $item;
	        }
	    }

	    // Ambil semua subcategory dari model
	    $subcategories_raw = $this->Item_model->sub_category();

	    // Filter subcategory berdasarkan weekday/weekend & jam
	    $subcategories_filtered = [];
	    foreach ($subcategories_raw as $sub) {
	        $show = true;

	        // Cek weekday/weekend
	        $today = date('l');
	        if (!empty($sub['weekday']) && stripos($sub['weekday'], $today) === false) {
	            if (!empty($sub['weekend']) && stripos($sub['weekend'], $today) === false) {
	                $show = false;
	            }
	        }

	        // Cek jam aktif
	        if (!empty($sub['time_from']) && !empty($sub['time_to'])) {
	            if ($current_hour < $sub['time_from'] || $current_hour > $sub['time_to']) {
	                $show = false;
	            }
	        }

	        if ($show) {
	            $subcategories_filtered[] = $sub;
	        }
	    }

	    // === Tambahkan Signature jika ada chef_recommended ===
	    $signature_exists = false;
	    foreach ($subcategories_filtered as $sf) {
	        if (strtolower($sf['sub_category']) === 'signature') {
	            $signature_exists = true;
	            break;
	        }
	    }

	    if (!$signature_exists) {
	        $has_signature_item = $this->db
	            ->where('is_active', 1)
	            ->where('chef_recommended', 1)
	            ->count_all_results('sh_m_item');

	        if ($has_signature_item > 0) {
	            $subcategories_filtered[] = [
	                'sub_category' => 'Signature',
	                'id'           => 0,
	                'weekday'      => '',
	                'weekend'      => '',
	                'time_from'    => '',
	                'time_to'      => ''
	            ];
	        }
	    }

	    // Pastikan Signature tetap di awal
	    usort($subcategories_filtered, function ($a, $b) {
	        if ($a['sub_category'] === 'Signature') return -1;
	        if ($b['sub_category'] === 'Signature') return 1;
	        if ($a['id'] == $b['id']) return 0;
	        return ($a['id'] < $b['id']) ? -1 : 1;
	    });
	    $cartItems = $this->db
	    ->select('item_code, qty, unit_price')
	    ->where([
	        'id_customer'   => $this->session->userdata('id'),
	        'id_table'      => $nomeja,
	        // 'user_order_id' => $this->session->userdata('user_order_id'),
	    ])
	    ->where('DATE(entry_date)', date('Y-m-d'))
	    ->get('sh_cart')
	    ->result();


		/* ubah jadi map */
		$cartMap = [];
		foreach ($cartItems as $c) {
		    $cartMap[$c->item_code] = $c->qty;
		}

		$data['cartMap'] = $cartMap;


		$cartQtyTotal   = 0;
		$cartPriceTotal = 0;
		foreach ($cartItems as $c) {
		    $cartQtyTotal   += $c->qty;
		    $cartPriceTotal += $c->qty * $c->unit_price;
		}
		// var_dump($cartPriceTotal);exit();
		$data['cartQtyTotal']   = $cartQtyTotal;
		$data['cartPriceTotal'] = $cartPriceTotal;

	    // Kirim data ke view
	    $data['item']       = $items_filtered;
	    $data['sub']        = $subcategories_filtered;
	    $data['sube']       = $this->Item_model->sub_category_event();
	    $data['s']          = $sub_category;
	    $data['ic']         = $id_customer;
	    $data['key']        = '';
	    $data['cart_count'] = $this->Item_model->hitungcart($nomeja);
	    $data['nomeja']     = $nomeja;

	    $cart_count = $this->Item_model->cart_count($id_customer, $nomeja)->num_rows();
	    $data['total_qty'] = $cart_count > 0 ? $this->Item_model->cart_count($id_customer, $nomeja)->row()->total_qty : 0;

	    $data['iconfooter'] = $this->Admin_model->getIcon('footer');
	    $data['cn']         = $this->Admin_model->getColorCN();
	    $data['ch']         = $this->Admin_model->getColorHD();
	    $data['cb']         = $this->Admin_model->getColorBTN();
	    $data['logo']       = $this->Admin_model->getLogo();
	    $data['username']	= $username;
	    $data['no_telp']	= $no_telp;
	    $data['keyword'] = '';
    	$data['offset']  = 0;
	    $trans = $this->db->order_by('create_date', 'DESC')
                  ->get_where('sh_t_transactions', array('id_customer' => $id_customer))
                  ->row();
        $session = $this->db
	        ->select('status')
	        ->from('sh_rel_table')
	        ->where('id_table', $nomeja)
	        ->where('created_date', date('Y-m-d'))
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get()
	        ->row();
        
        $data['status_meja'] = $session->status;
        if ($trans->parent_id != 0) {
        	$data['cekpay'] = $this->Item_model->getitem($trans->parent_id,'parent');
        }else{
        	$data['cekpay'] = $this->Item_model->getitem($trans->id,'notparent');
        }
        $data['settings'] = $this->Admin_model->getLogo();

	    $this->load->view('ordermakananv3', $data);
	}
	public function menuALL($tipe)
{
	$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
    $id_customer = $this->session->userdata('id');
    $nomeja      = $this->session->userdata('nomeja');

    $hariIni      = date('N');
    $current_hour = date('H');
    $current_date = date('Y-m-d');

    // === CEK HOLIDAY ===
    $holiday = $this->db
        ->where('holiday_date', $current_date)
        ->count_all_results('sh_m_holiday') > 0;
    
    $te = ($holiday || $hariIni >= 6) ? 'WEEKEND' : 'WEEKDAY';

    // === AMBIL SEMUA ITEM ===
    $items_raw = $this->Item_model->getDataAll($tipe);

    // === FILTER EVENT / JAM / TANGGAL (LOGIC ASLI DIPERTAHANKAN) ===
    $items_filtered = [];
    foreach ($items_raw as $item) {
        $show = true;

        if (!empty($item->event_item_code)) {
            $tanggalValid =
                $current_date >= $item->date_from &&
                $current_date <= $item->date_to;

            if ($item->type === 'EVERYDAY') {
                if ($tanggalValid && !empty($item->time_from)) {
                    if ($current_hour < $item->time_from || $current_hour > $item->time_to) {
                        $show = false;
                    }
                }
            } else {
                if ($tanggalValid && $item->type === $te) {
                    if (!empty($item->time_from)) {
                        if ($current_hour < $item->time_from || $current_hour > $item->time_to) {
                            $show = false;
                        }
                    }
                }
            }
        }

        if ($show) {
            $items_filtered[] = $item;
        }
    }

    // === GROUP BY SUB CATEGORY ===
    $grouped_items = [];
    foreach ($items_filtered as $i) {
        $grouped_items[$i->sub_category][] = $i;
    }

    $data['grouped_items'] = $grouped_items;
    $data['cn']            = $this->Admin_model->getColorCN();
    $data['logo']          = $this->Admin_model->getLogo();
    $data['nomeja']        = $nomeja;

    $this->load->view('ordermakanan_all', $data);
}
	
	public function search()
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    $id_customer = $this->session->userdata('id');
	    $nomeja      = $this->session->userdata('nomeja');
	    $username    = $this->session->userdata('username');
	    $no_telp     = $this->session->userdata('no_telp');

	    $keyword     = $this->input->post('keyword');

	    $data['s']   = 'Soup';
	    $data['key'] = $keyword;

	    $hariIni      = date('N');
	    $current_hour = date('H');
	    $current_date = date('Y-m-d');

	    // cek holiday
	    $this->db->where('holiday_date', $current_date);
	    $holiday = $this->db->get('sh_m_holiday')->num_rows() > 0;

	    $te = ($holiday || $hariIni >= 6) ? 'WEEKEND' : 'WEEKDAY';

	    // =============================
	    // AMBIL DATA ITEM BERDASARKAN KEYWORD
	    // =============================

	    $items_raw = $this->Item_model->get_keyword($keyword, 10, 0);

	    $items_filtered = [];

	    foreach ($items_raw as $item) {

	        $show = true;

	        if (!empty($item->event_item_code)) {

	            $itemType = strtoupper(trim($item->type));

	            $tanggalBerlaku = (
	                !empty($item->date_from) &&
	                !empty($item->date_to) &&
	                $current_date >= $item->date_from &&
	                $current_date <= $item->date_to
	            );

	            if ($itemType === 'EVERYDAY') {

	                if ($tanggalBerlaku) {

	                    if (!empty($item->time_from) && !empty($item->time_to)) {

	                        $show = ($current_hour >= $item->time_from && $current_hour <= $item->time_to);

	                    } else {

	                        $show = true;

	                    }

	                } else {

	                    $show = true;

	                }

	            } else {

	                if ($tanggalBerlaku && $itemType === $te) {

	                    if (!empty($item->time_from) && !empty($item->time_to)) {

	                        $show = ($current_hour >= $item->time_from && $current_hour <= $item->time_to);

	                    } else {

	                        $show = true;

	                    }

	                } else {

	                    $show = true;

	                }

	            }

	        }

	        if ($show) {
	            $items_filtered[] = $item;
	        }

	    }

	    // =============================
	    // SUB CATEGORY (SAMA SEPERTI MENU)
	    // =============================

	    $subcategories_raw = $this->Item_model->sub_category();

	    $subcategories_filtered = [];

	    foreach ($subcategories_raw as $sub) {

	        $show = true;

	        $today = date('l');

	        if (!empty($sub['weekday']) && stripos($sub['weekday'], $today) === false) {

	            if (!empty($sub['weekend']) && stripos($sub['weekend'], $today) === false) {

	                $show = false;

	            }

	        }

	        if (!empty($sub['time_from']) && !empty($sub['time_to'])) {

	            if ($current_hour < $sub['time_from'] || $current_hour > $sub['time_to']) {

	                $show = false;

	            }

	        }

	        if ($show) {

	            $subcategories_filtered[] = $sub;

	        }

	    }

	    // =============================
	    // TAMBAH SIGNATURE JIKA ADA
	    // =============================

	    $signature_exists = false;

	    foreach ($subcategories_filtered as $sf) {

	        if (strtolower($sf['sub_category']) === 'signature') {

	            $signature_exists = true;
	            break;

	        }

	    }

	    if (!$signature_exists) {

	        $has_signature_item = $this->db
	            ->where('is_active', 1)
	            ->where('chef_recommended', 1)
	            ->count_all_results('sh_m_item');

	        if ($has_signature_item > 0) {

	            $subcategories_filtered[] = [
	                'sub_category' => 'Signature',
	                'id'           => 0,
	                'weekday'      => '',
	                'weekend'      => '',
	                'time_from'    => '',
	                'time_to'      => ''
	            ];

	        }

	    }

	    // urutkan signature tetap di awal

	    usort($subcategories_filtered, function ($a, $b) {

	        if ($a['sub_category'] === 'Signature') return -1;
	        if ($b['sub_category'] === 'Signature') return 1;
	        if ($a['id'] == $b['id']) return 0;

	        return ($a['id'] < $b['id']) ? -1 : 1;

	    });

	    // =============================
	    // CART DATA
	    // =============================

	    $cartItems = $this->db
	        ->select('item_code, qty, unit_price')
	        ->where([
	            'id_customer' => $id_customer,
	            'id_table'    => $nomeja
	        ])
	        ->where('DATE(entry_date)', date('Y-m-d'))
	        ->get('sh_cart')
	        ->result();

	    $cartMap = [];

	    foreach ($cartItems as $c) {

	        $cartMap[$c->item_code] = $c->qty;

	    }

	    $data['cartMap'] = $cartMap;

	    $cartQtyTotal   = 0;
	    $cartPriceTotal = 0;

	    foreach ($cartItems as $c) {

	        $cartQtyTotal   += $c->qty;
	        $cartPriceTotal += $c->qty * $c->unit_price;

	    }

	    $data['cartQtyTotal']   = $cartQtyTotal;
	    $data['cartPriceTotal'] = $cartPriceTotal;

	    // =============================
	    // DATA KE VIEW
	    // =============================

	    $data['item']   = $items_filtered;
	    $data['sub']    = $subcategories_filtered;
	    $data['sube']   = $this->Item_model->sub_category_event();

	    $data['keyword'] = $keyword;
	    $data['offset']  = 7;

	    $data['nomeja']     = $nomeja;
	    $data['logo']       = $this->Admin_model->getLogo();
	    $data['cart_count'] = $this->Item_model->hitungcart($nomeja);

	    $data['username'] = $username;
	    $data['no_telp']  = $no_telp;

	    $cart_count = $this->Item_model->cart_count($id_customer, $nomeja)->num_rows();

	    $data['total_qty'] = $cart_count > 0
	        ? $this->Item_model->cart_count($id_customer, $nomeja)->row()->total_qty
	        : 0;

	    if (empty($items_filtered)) {

	        $this->session->set_flashdata('notfound', 'Not Found');

	    }

	    $data['iconfooter'] = $this->Admin_model->getIcon('footer');
	    $data['cn']         = $this->Admin_model->getColorCN();
	    $data['ch']         = $this->Admin_model->getColorHD();
	    $data['cb']         = $this->Admin_model->getColorBTN();

	    $trans = $this->db
	        ->order_by('create_date', 'DESC')
	        ->get_where('sh_t_transactions', ['id_customer' => $id_customer])
	        ->row();

	    if ($trans->parent_id != 0) {

	        $data['cekpay'] = $this->Item_model->getitem($trans->parent_id, 'parent');

	    } else {

	        $data['cekpay'] = $this->Item_model->getitem($trans->id, 'notparent');

	    }

	    $data['settings'] = $this->Admin_model->getLogo();

	    $this->load->view('ordermakananv3', $data);
	}
	public function searchv2()
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    $id_customer = $this->session->userdata('id');
	    $nomeja      = $this->session->userdata('nomeja');
	    $keyword     = $this->input->post('keyword');
	    $data['s']   = 'Soup';
	    $data['key'] = $keyword;

	    $hariIni      = date('N'); // 1=Senin, 7=Minggu
	    $current_hour = date('H');
	    $current_date = date('Y-m-d');

	    // Cek apakah hari ini libur
	    $this->db->where('holiday_date', $current_date);
	    $holiday = $this->db->get('sh_m_holiday')->num_rows() > 0;
	    
	    // Tentukan tipe hari
	    $te = ($holiday || $hariIni >= 6) ? 'WEEKEND' : 'WEEKDAY';

	    // Ambil data berdasarkan keyword
	    $items_raw = $this->Item_model->get_keyword($keyword, 10, 0);

	    // Filter item sesuai logika menu()
	    $items_filtered = [];
	    foreach ($items_raw as $item) {
	        $show = true;

	        if (!empty($item->event_item_code)) {
	            $itemType = strtoupper(trim($item->type));

	            // ✅ Cek apakah tanggal valid
	            $tanggalBerlaku = (
	                !empty($item->date_from) &&
	                !empty($item->date_to) &&
	                $current_date >= $item->date_from &&
	                $current_date <= $item->date_to
	            );

	            if ($itemType === 'EVERYDAY') {
	                if ($tanggalBerlaku) {
	                    if (!empty($item->time_from) && !empty($item->time_to)) {
	                        $show = ($current_hour >= $item->time_from && $current_hour <= $item->time_to);
	                    } else {
	                        $show = true;
	                    }
	                } else {
	                    $show = true; // tanggal tidak valid → tetap tampil
	                }
	            } else {
	                // 🔹 Untuk WEEKDAY/WEEKEND
	                if ($tanggalBerlaku && $itemType === $te) {
	                    if (!empty($item->time_from) && !empty($item->time_to)) {
	                        $show = ($current_hour >= $item->time_from && $current_hour <= $item->time_to);
	                    } else {
	                        $show = true; // tanggal + type valid tanpa jam
	                    }
	                } else {
	                    // Jangan di-hide total → fallback tampil default
	                    $show = true;
	                }
	            }
	        }

	        if ($show) {
	            $items_filtered[] = $item;
	        }
	    }

	    // Kirim data ke view
	    $data['item']       = $items_filtered;
	    $data['keyword'] = $keyword;
    	$data['offset']  = 7;
	    $data['sub']        = $this->Item_model->sub_category();
	    $data['sube']       = $this->Item_model->sub_category_event();
	    $data['nomeja']     = $nomeja;
	    $data['logo']       = $this->Admin_model->getLogo();
	    $data['cart_count'] = $this->Item_model->hitungcart($nomeja);

	    $cart_count = $this->Item_model->cart_count($id_customer, $nomeja)->num_rows();
	    $data['total_qty'] = $cart_count > 0
	        ? $this->Item_model->cart_count($id_customer, $nomeja)->row()->total_qty
	        : 0;

	    if (empty($items_filtered)) {
	        $this->session->set_flashdata('notfound', 'Not Found');
	    }

	    $data['iconfooter'] = $this->Admin_model->getIcon('footer');
	    $data['cn']         = $this->Admin_model->getColorCN();
	    $data['ch']         = $this->Admin_model->getColorHD();
	    $data['cb']         = $this->Admin_model->getColorBTN();
	    $trans = $this->db->order_by('create_date', 'DESC')
                  ->get_where('sh_t_transactions', array('id_customer' => $id_customer))
                  ->row();
         
        if ($trans->parent_id != 0) {
        	$data['cekpay'] = $this->Item_model->getitem($trans->parent_id,'parent');
        }else{
        	$data['cekpay'] = $this->Item_model->getitem($trans->id,'notparent');
        }

	    $this->load->view('ordermakananv3', $data);
	}
	public function load_more_search()
{
	$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
    $keyword = $this->input->post('keyword');
    $offset  = (int)$this->input->post('offset');

    $hariIni      = date('N');
    $current_hour = date('H');
    $current_date = date('Y-m-d');

    // Holiday check
    $this->db->where('holiday_date', $current_date);
    $holiday = $this->db->get('sh_m_holiday')->num_rows() > 0;
    
    $te = ($holiday || $hariIni >= 6) ? 'WEEKEND' : 'WEEKDAY';

    $items_raw = $this->Item_model->get_keyword($keyword, 10, $offset);

    if (empty($items_raw)) {
        echo json_encode(['status' => 'end']);
        return;
    }

    $html = '';

    foreach ($items_raw as $i) {

        // ================= SOLD OUT =================
        $isSoldOut = ($i->is_sold_out != 0);

        // ================= PROMO =================
        $cekpromo       = $this->Item_model->cekpromo($i->sub_category);
        $cekpromoharian = $this->Item_model->cekpromoharian($i->sub_category, $i->no);

        $harga_asli  = $i->harga_weekend;
        $harga_akhir = $harga_asli;
        $promo_value = 0;

        if (!empty($cekpromo)) {
            $promo_value = (int)$cekpromo->promo_value;
        } elseif (!empty($cekpromoharian)) {
            $promo_value = (int)$cekpromoharian->promo_value;
        }

        if ($promo_value > 0) {
            $harga_akhir = $harga_asli - ($harga_asli * ($promo_value / 100));
        }

        // ================= IMAGE =================
        $img = $i->image_path ?: $this->logo->image_path;

        // ================= CART QTY =================
        $itemCode = $i->no;
        $cartQty  = $this->cartMap[$itemCode] ?? 0;

        // ================= SPECIAL PROMO =================
        $cekSPchart = $this->Item_model->cekSPchart($i->no);
        $cekSPtrans = $this->Item_model->cekSPtrans($i->no);
        $adaSPchart = $this->Item_model->cekSPchart();
        $adaSPtrans = $this->Item_model->cekSPtrans();

        $itemIniAda = ($cekSPchart || $cekSPtrans);
        $sudahAdaSP = ($adaSPchart || $adaSPtrans);

        $option = $this->Item_model->getOption($i->no);

        // ================= HTML =================
        $html .= '<div class="col-6">
            <div class="card menu-grid-card p-2">';

        // IMAGE
        if (!$isSoldOut) {
            $html .= '<img src="'.$img.'" class="menu-grid-img">';
        } else {
            $html .= '<img src="'.$img.'" class="menu-grid-img grayscale">';
        }

        // ORDERED QTY
        foreach ($this->Item_model->cekpesan($i->no) as $c) {
            $html .= '<div class="ordered-qty">Ordered '.$c->qty.'</div>';
        }

        $html .= '<div class="menu-grid-content">
            <div class="menu-info">
                <strong class="menu-title">'.$i->description.'</strong>';

        // PRICE
        if ($i->harga_weekday == 0 || $i->harga_weekend == 0 || $i->harga_holiday == 0) {
            $html .= '<div class="text-muted">Free</div>';
        } else {
            if ($promo_value > 0) {
                $html .= '
                <span class="text-danger text-decoration-line-through">
                    Rp '.number_format($harga_asli).'
                </span><br>';
            }
            $html .= '<div class="text-muted">Rp '.number_format($harga_akhir).'</div>';
        }

        $html .= '</div>'; // menu-info

        // ================= ACTION =================
        if (!$isSoldOut) {

            if ($i->sub_category == 'Special Promo' && $sudahAdaSP && !$itemIniAda) {

                $html .= '<a class="btn btn-outline-custom btn-sm w-100 mt-2"
                            style="border-radius:50px;box-shadow:0px 5px 5px #00000040;">
                            Only one promo item allowed
                          </a>';

            } else {

                if ($option) {

                    $html .= '<a href="'.base_url('index.php/ordermakanan/detailmenu/'.$i->id.'/'.str_replace(" ","%20",$i->sub_category)).'"
                                class="btn btn-outline-custom btn-sm w-100 mt-2"
                                style="border-radius:50px;box-shadow:0px 5px 5px #00000040;">
                                Add
                              </a>';

                } else {

                    $html .= '
                    <div class="order-action mt-2"
                         data-id="'.$i->id.'"
                         data-price="'.$harga_akhir.'">

                        <button type="button"
                                class="btn btn-outline-custom btn-sm w-100 add-btn '.($cartQty > 0 ? 'd-none' : '').'"
                                style="border-radius:50px;box-shadow:0px 5px 5px #00000040;">
                            Add
                        </button>

                        <div class="qty-box '.($cartQty > 0 ? '' : 'd-none').'"
                             data-stock="'.$i->stock.'"
                             data-need-stock="'.$i->need_stock.'"
                             data-item-id="'.$i->id.'">

                            <button type="button" class="qty-btn minus">
                                <i class="bi bi-dash-circle-fill"></i>
                            </button>

                            <span class="qty-value">'.($cartQty > 0 ? $cartQty : 1).'</span>

                            <button type="button"
                                    class="qty-btn plus '.(($i->need_stock == 1 && $cartQty >= $i->stock) ? 'disabled' : '').'">
                                <i class="bi bi-plus-circle-fill"></i>
                            </button>
                        </div>

                    </div>';
                }
            }

        } else {

            $html .= '<a class="btn btn-outline-custom-sold btn-sm w-100 mt-2"
                        style="border-radius:50px;box-shadow:0px 5px 5px #00000040;">
                        Sold out
                      </a>';
        }

        $html .= '</div></div></div>'; // end card & col
    }

    echo json_encode([
        'status' => 'ok',
        'html'   => $html,
        'next'   => $offset + 10
    ]);
}

	public function load_more_searchv2()
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    $keyword = $this->input->post('keyword');
	    $offset  = (int)$this->input->post('offset');

	    $hariIni      = date('N'); // 1=Senin, 7=Minggu
	    $current_hour = date('H');
	    $current_date = date('Y-m-d');

	    // Cek apakah hari ini libur
	    $this->db->where('holiday_date', $current_date);
	    $holiday = $this->db->get('sh_m_holiday')->num_rows() > 0;
	    
	    // Tentukan tipe hari
	    $te = ($holiday || $hariIni >= 6) ? 'WEEKEND' : 'WEEKDAY';

	    // Ambil data berdasarkan keyword
	    $items_raw = $this->Item_model->get_keyword($keyword, 7, $offset);

	    // Filter item sesuai logika menu()
	    $items_filtered = [];
	    foreach ($items_raw as $item) {
	        $show = true;

	        if (!empty($item->event_item_code)) {
	            $itemType = strtoupper(trim($item->type));

	            // ✅ Cek apakah tanggal valid
	            $tanggalBerlaku = (
	                !empty($item->date_from) &&
	                !empty($item->date_to) &&
	                $current_date >= $item->date_from &&
	                $current_date <= $item->date_to
	            );

	            if ($itemType === 'EVERYDAY') {
	                if ($tanggalBerlaku) {
	                    if (!empty($item->time_from) && !empty($item->time_to)) {
	                        $show = ($current_hour >= $item->time_from && $current_hour <= $item->time_to);
	                    } else {
	                        $show = true;
	                    }
	                } else {
	                    $show = true; // tanggal tidak valid → tetap tampil
	                }
	            } else {
	                // 🔹 Untuk WEEKDAY/WEEKEND
	                if ($tanggalBerlaku && $itemType === $te) {
	                    if (!empty($item->time_from) && !empty($item->time_to)) {
	                        $show = ($current_hour >= $item->time_from && $current_hour <= $item->time_to);
	                    } else {
	                        $show = true; // tanggal + type valid tanpa jam
	                    }
	                } else {
	                    // Jangan di-hide total → fallback tampil default
	                    $show = true;
	                }
	            }
	        }

	        if ($show) {
	            $items_filtered[] = $item;
	        }
	    }

	    if (empty($items_filtered)) {
	        echo json_encode(['status' => 'end']);
	        return;
	    }

	    $html = '';

		foreach ($items_filtered as $i) {

		    // ======================
		    // PROMO
		    // ======================
		    $cekpromo       = $this->Item_model->cekpromo($i->sub_category);
		    $cekpromoharian = $this->Item_model->cekpromoharian($i->sub_category, $i->no);

		    $harga_asli  = $i->harga_weekend;
		    $harga_akhir = $harga_asli;
		    $promo_value = 0;

		    if (!empty($cekpromo)) {
		        $promo_value = (int)$cekpromo->promo_value;
		    } elseif (!empty($cekpromoharian)) {
		        $promo_value = (int)$cekpromoharian->promo_value;
		    }

		    if ($promo_value > 0) {
		        $harga_akhir = $harga_asli - ($harga_asli * ($promo_value / 100));
		    }

		    // ======================
		    // SOLD OUT
		    // ======================
		    if ($i->is_sold_out != 0) {
		        continue;
		    }

		    // ======================
		    // ORDERED QTY
		    // ======================
		    $orderedHtml = '';
		    foreach ($this->Item_model->cekpesan($i->no) as $c) {
		        $orderedHtml .= '<div class="ordered-qty">Ordered '.$c->qty.'</div>';
		    }

		    // ======================
		    // PRICE HTML
		    // ======================
		    if ($i->harga_weekday == 0 || $i->harga_weekend == 0 || $i->harga_holiday == 0) {
		        $priceHtml = '<h6>Free</h6>';
		    } else {
		        if ($promo_value > 0) {
		            $priceHtml = '
		                <span class="text-danger text-decoration-line-through">
		                    Rp '.number_format($harga_asli).'
		                </span><br>
		                <strong>Rp '.number_format($harga_akhir).'</strong>
		            ';
		        } else {
		            $priceHtml = '<strong>Rp '.number_format($harga_akhir).'</strong>';
		        }
		    }

		    // ======================
		    // SPECIAL PROMO BUTTON
		    // ======================
		    $cekSPchart = $this->Item_model->cekSPchart($i->no);
		    $cekSPtrans = $this->Item_model->cekSPtrans($i->no);
		    $adaSPchart = $this->Item_model->cekSPchart();
		    $adaSPtrans = $this->Item_model->cekSPtrans();

		    $itemIniAda = ($cekSPchart || $cekSPtrans);
		    $sudahAdaSP = ($adaSPchart || $adaSPtrans);

		    if ($i->sub_category == 'Special Promo' && $sudahAdaSP && !$itemIniAda) {
		        $buttonHtml = '
		            <div class="add-btn-muted" style="opacity:0.5">
		                <i class="bi bi-plus" style="font-size:25px"></i>
		            </div>
		        ';
		    } else {
		        $buttonHtml = '
		            <div class="add-btn">
		                <a href="'.base_url('index.php/ordermakanan/detailmenu/'.$i->id.'/'.str_replace(' ','%20',$i->sub_category)).'"
		                   style="color:white;font-size:25px">
		                    <i class="bi bi-plus"></i>
		                </a>
		            </div>
		        ';
		    }

		    // ======================
		    // IMAGE
		    // ======================
		    $img = !empty($i->image_path) ? $i->image_path : $logo->image_path;

		    // ======================
		    // FINAL HTML
		    // ======================
		    $html .= '
		    <div class="row align-items-center menu-item position-relative"
		         id="'.str_replace(" ","_",$i->sub_category).'">

		        '.$orderedHtml.'

		        <div class="col-3">
		            <img src="'.$img.'">
		        </div>

		        <div class="col-6">
		            <h6>'.$i->description.'</h6>
		            '.$priceHtml.'
		        </div>

		        <div class="col-3 text-end">
		            '.$buttonHtml.'
		        </div>

		    </div>';
		}


	    echo json_encode([
	        'status' => 'ok',
	        'html'   => $html,
	        'next'   => $offset + 10
	    ]);
	}


	
	public function detailmenuOLD($id,$sub)
	{
		$sharp = str_replace("%20","_", $sub);
		$url = $sub.'#'.$sharp;
		$item = $this->Item_model->getDatabyID($id);
		$addon = $this->Item_model->getAddOn($item->no);
		$option = $this->Item_model->getOption($item->no);
		$nomeja = $this->session->userdata('nomeja');
		$link = 'index.php/ordermakanan/menu/Makanan/'.$url;
		$linkform = 'index.php/ordermakanan/add_cart/'.$url;
		$data = [
			'item' => $item,
			'url' => $url,
			'addon' => $addon,
			'option' => $option,
			'nomeja' => $nomeja,
			'link' => $link,
			'linkform' => $linkform,
			'cn' => $this->Admin_model->getColorCN(),
			'ch' => $this->Admin_model->getColorHD(),
			'cb' => $this->Admin_model->getColorBTN(),
			'logo' => $this->Admin_model->getLogo(),
		];
		$this->load->view('detailmenu',$data);
	}
	public function detailmenu($id, $sub)
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    $sharp = str_replace("%20","_", $sub);
	    $url = $sub.'#'.$sharp;

	    $item = $this->Item_model->getDatabyID($id);
	    $addon = $this->Item_model->getAddOn($item->no);
	    $option = $this->Item_model->getOptionGrouped($item->no); // ambil per group
	    $nomeja = $this->session->userdata('nomeja');

	    $link = 'index.php/ordermakanan/menu/Makanan/'.$url;
	    $linkform = 'index.php/ordermakanan/add_cart/'.$url;

	    $data = [
	        'item' => $item,
	        'url' => $url,
	        'addon' => $addon,
	        'option' => $option,   // sudah grouped
	        'nomeja' => $nomeja,
	        'link' => $link,
	        'linkform' => $linkform,
	        'cn' => $this->Admin_model->getColorCN(),
	        'ch' => $this->Admin_model->getColorHD(),
	        'cb' => $this->Admin_model->getColorBTN(),
	        'logo' => $this->Admin_model->getLogo(),
	    ];

	    $this->load->view('detailmenu', $data);
	}
	public function menumakanan($tipe,$sub_category)
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
		
		$id_customer = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$data['item'] = $this->Item_model->getData($tipe,$sub_category);
		$data['sub'] = $this->Item_model->sub_category();
		//$data['option'] = $this->Item_model->option();
		$data['s'] = $sub_category;
		$data['ic'] = $id_customer;
		$data['cart_count'] = $this->Item_model->hitungcart($nomeja);
		$data['nomeja'] = $this->session->userdata('nomeja');
		$cart_count = $this->Item_model->cart_count($id_customer,$nomeja)->num_rows();
		if($cart_count > 0){
			$cart = $this->Item_model->cart_count($id_customer,$nomeja)->row();//tambahan	
			$cart_total = $cart->total_qty;
		}else{
			$cart_total = 0;
		}
		$data['total_qty'] = $cart_total;

			$this->load->view('menu/makanan',$data);
		
	}
	public function option_list($item_code) {
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	$option = $this->Item_model->option($item_code);
	$html = "<select id='item_option' name='item_option'>";
	$html .= "<option value=''>--Option--</option>";
	foreach($option as $o){
		$html .= "<option value='".$o->description."'>".$o->description."</option>";
	}
	$html .= "</select>";
	return $html;
}
	public function subcreate($nomeja,$cek,$sub=NULL)
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
		$uc = $this->session->userdata('username');
		$id_customer = $this->session->userdata('id');
		$cabang = $this->db->order_by('id',"desc")
  			->limit(1)
  			->get('sh_m_cabang')
  			->row('id');
  		
  		$notrans = $this->db->order_by('id',"desc")->where('id_customer',$id_customer)
  			->limit(1)
  			->get('sh_t_transactions')
  			->row('id');
  		
		$data['order_bill'] = $this->Item_model->order_bill_co($cabang,$notrans);
		$data['total'] = $this->Item_model->totalSubOrder($uc);
		$data['item'] = $this->Item_model->getDataSubOrder($uc);
		$data['no_meja'] = $this->session->userdata('nomeja');
		$data['cek'] = $cek;
		$data['sub'] = $sub;
		
		$this->load->view('ordermakanan_view',$data);

	}
	public function batal()
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
		$ic = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$this->db->where('id_customer',$ic);
    	$this->db->delete('sh_t_sub_transactions');
    	
    	redirect('index.php/ordermakanan/menu/Makanan/ayam/'.$nomeja);
	}
	public function searchOLD()
	{
		$id_customer = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$keyword = $this->input->post('keyword');
		$data['s'] = 'Soup';
		$data['key'] = $keyword;
		$data['item'] = $this->Item_model->get_keyword($keyword);
		$data['sub'] = $this->Item_model->sub_category();
		$data['sube'] = $this->Item_model->sub_category_event();
		$data['nomeja'] = $this->session->userdata('nomeja');
		$data['logo'] = $this->Admin_model->getLogo();
		$data['cart_count'] = $this->Item_model->hitungcart($nomeja);
		$cart_count = $this->Item_model->cart_count($id_customer,$nomeja)->num_rows();
		$data_count = $this->Item_model->get_keyword($keyword);
		if ($data_count == NULL) {
			$this->session->set_flashdata('notfound','Not Found');
		}
		
		if($cart_count > 0){
			$cart = $this->Item_model->cart_count($id_customer,$nomeja)->row();//tambahan	
			$cart_total = $cart->total_qty;
		}else{
			$cart_total = 0;
		}
		$data['total_qty'] = $cart_total;
		$data['iconfooter'] = $this->Admin_model->getIcon('footer');
		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$this->load->view('ordermakanan',$data);
	}
	

	public function searchdata($query) {
        $results = $this->Item_model->get_keyword($keyword);
         header('Content-Type: application/json');
        echo json_encode($results);
    }
	public function create()
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
		$uc = $this->session->userdata('username');
		$ic = $this->session->userdata('id');
		$qty = $this->input->post('qty');
		$nama = $this->input->post('nama');
		$pesan = $this->input->post('pesan');
		$harga = $this->input->post('harga');
		$item_code = $this->input->post('no');
		$nomeja = $this->session->userdata('nomeja');
		
		$nomer = 1;
		for ($i = 0; $i < count($qty); $i++) {
			if ($qty[$i] != 0) {
				$n = $nomer++ . "<br>"; 
				$data[] = [
				'qty' => $qty[$i],
				'harga' => $harga[$i],
				'nama' => $nama[$i],
				'pesan' => $pesan[$i],
				'entry_by' => $uc,
				'id_customer' => $ic,
				'item_code' => $item_code[$i],
			];
			}
    
	}
	$result = $this->db->insert_batch('sh_t_sub_transactions',$data);
	
			if ($result) {
				redirect('index.php/ordermakanan/subcreate/'.$nomeja);
				// $where = array('qty' => 0);
				// $this->Item_model->hapus_qty($where,'testing');
			}else{
				echo "gagal order";
			}

		
	}

	public function orderqty() 
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
		$table = $this->session->userdata('nomeja');
		$uc = $this->session->userdata('username');
		$ic = $this->session->userdata('id');
		$post = $this->input->post();
		$trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $ic))->row();
		
		if($post['tipe']=='plus' && $post['item_code'] != ''){
			$cek_count = $this->Item_model->get_cart($ic,$table,$post['item_code'],$post['uoi'])->num_rows();
			if($cek_count > 0){
				$cek_cart = $this->Item_model->get_cart($ic,$table,$post['item_code'],$post['uoi'])->row();
				if ($post['need_stock'] == 1) {
					if ($cek_cart->qty >= $post['stock']) {
						$aqty = $cek_cart->qty;
						$cek = True;
					}else{
						$aqty = $cek_cart->qty+1;
						$cek = False;
					}
				}else{
					$aqty = $cek_cart->qty+1;
					$cek = False;
				}
					
				$pesan = $post['extra_notes'];
				if($pesan != ''){
					$data = [
						'qty' => $aqty,
						'extra_notes' => $post['extra_notes'],
						'user_order_id' => $this->session->userdata('user_order_id'),
					];
				}else{
					$data = [
						'qty' => $aqty,
						'user_order_id' => $this->session->userdata('user_order_id'),
					];	
				}
				
				$this->Item_model->save('sh_cart',$data, ['id'=>$cek_cart->id]);
				$cart_count = $this->Item_model->hitungcart($table);
				$carts = $this->Item_model->cart_count($ic,$table)->num_rows();
				if($carts > 0){
					$cart = $this->Item_model->cart_count($ic,$table)->row();	
					$total_qty = $cart->total_qty;
				}else{
					$total_qty = 0;
				}

				$notif = "Food Stocks Are Not Fulfilled";

				
				echo json_encode(array('status'=> True,'new_qty'=> $aqty,'pesan'=>$pesan,'cart_count'=>(int)$cart_count,'total_qty'=>(int)$total_qty,'notif' => $notif,'cek'=>$cek));
			}else{

				$pesan = $post['extra_notes'];
				$data = [
					'item_code' => $post['item_code'],
					'id_trans' => $trans->id,
					'id_customer' => $ic,
					'qty' => 1,
					'cabang' => $trans->cabang,
					'unit_price' => $post['unit_price'],
					'description' => $post['description'],
					'entry_by' => $this->session->userdata('username'),
					'id_table' => $table,
					'extra_notes' => $post['extra_notes'],
					'entry_date' => date('Y-m-d H:i:s'),
					'user_order_id' => $this->session->userdata('user_order_id'),
				];
				
				$cart_id = $this->Item_model->save('sh_cart',$data);
				$cart_count = $this->Item_model->hitungcart($table);

				$carts = $this->Item_model->cart_count($ic,$table)->num_rows();
				if($carts > 0){
					$cart = $this->Item_model->cart_count($ic,$table)->row();	
					$total_qty = $cart->total_qty;
				}else{
					$total_qty = 0;
				}
				$id_customer = $this->session->userdata('id');
				$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
				
				$cabang = $this->db->order_by('id',"desc")
			  			->limit(1)
			  			->get('sh_m_cabang')
			  			->row('id');
			  	
			  	$ip_address = $this->input->ip_address();
			  	$cust = $this->session->userdata('username');
				$dataevent = [
					'event_type' => 'Update cart SO',
					'cabang' => $cabang,
					'id_trans' => $id_trans->id,
					'id_customer' => $this->session->userdata('id'),
					'event_date' => date('Y-m-d H:i:s'),
					'user_by' => $this->session->userdata('username'),
					'description' => 'Menambahkan item: '.$post['description'].' dengan qty: 1',
					'created_date' => date('Y-m-d'),
				];
				$result = $this->db->insert('sh_event_log',$dataevent);
				
				if($cart_id){
					echo json_encode(array('status'=> True,'new_qty'=> 1,'pesan'=>$pesan,'cart_count'=>(int)$cart_count,'total_qty'=>(int)$total_qty));	
				}
			}
		}else if($post['tipe']=='minus' && $post['item_code'] != ''){
			$cek_count = $this->Item_model->get_cart($ic,$table,$post['item_code'],$post['uoi'])->num_rows();
			if($cek_count > 0){
				$cek_cart = $this->Item_model->get_cart($ic,$table,$post['item_code'],$post['uoi'])->row();
				if($cek_cart->qty == 1){
					$this->db->delete('sh_cart',['id'=>$cek_cart->id]);
					
					$cart_count = $this->Item_model->hitungcart($table);
					$carts = $this->Item_model->cart_count($ic,$table)->num_rows();
					if($carts > 0){
						$cart = $this->Item_model->cart_count($ic,$table)->row();	
						$total_qty = $cart->total_qty;
					}else{
						$total_qty = 0;
					}
					$id_customer = $this->session->userdata('id');
					$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
					$cabang = $this->db->order_by('id',"desc")
				  			->limit(1)
				  			->get('sh_m_cabang')
				  			->row('id');
				  	
				  	$ip_address = $this->input->ip_address();
				  	$cust = $this->session->userdata('username');
					$dataevent = [
						'event_type' => 'Update cart SO',
						'cabang' => $cabang,
						'id_trans' => $id_trans->id,
						'id_customer' => $this->session->userdata('id'),
						'event_date' => date('Y-m-d H:i:s'),
						'user_by' => $this->session->userdata('username'),
						'description' => 'Mengurangi 1 qty item: '.$post['description'],
						'created_date' => date('Y-m-d'),
					];
					$result = $this->db->insert('sh_event_log',$dataevent);
					
					echo json_encode(array('status'=> True,'new_qty'=> 0,'pesan'=>'','cart_count'=>(int)$cart_count,'total_qty'=>(int)$total_qty));
				}else{
					$pesan = $post['extra_notes'];
					$data = [
						'qty' => ($cek_cart->qty-1),
					];
					$this->Item_model->save('sh_cart',$data, ['id'=>$cek_cart->id]);
					$cart_count = $this->Item_model->hitungcart($table);
					$carts = $this->Item_model->cart_count($ic,$table)->num_rows();
					if($carts > 0){
						$cart = $this->Item_model->cart_count($ic,$table)->row();	
						$total_qty = $cart->total_qty;
					}else{
						$total_qty = 0;
					}
					
					echo json_encode(array('status'=> True,'new_qty'=> ($cek_cart->qty-1),'pesan'=>$pesan,'cart_count'=>(int)$cart_count,'total_qty'=>(int)$total_qty));
				}
			}
		}
	}
	public function add_cartOLD($sub)
	{
	    $options = $_POST['options'];
	    foreach ($options as $option) {
	        $op = htmlspecialchars($option);
	    }
	    $addons = $this->input->post('addons');
	    $soldOut = false;

	    // ✅ Cek stok add-on
	    foreach ($addons as $item_code) {
	        $this->db->select('need_stock, stock');
	        $this->db->from('sh_m_item');
	        $this->db->where('no', $item_code);
	        $query = $this->db->get();

	        $item = $query->row();
	        if ($item->need_stock == 1 && $item->stock <= 0) {
	            $soldOut = true;
	            break;
	        }
	    }
	    
	    if ($soldOut) {
	        $this->session->set_flashdata('error', 'The add-on stock has been sold out');
	        redirect($_SERVER['HTTP_REFERER']);
	    }

	    // Data input utama
	    $sharp = str_replace("%20", "_", $sub);
	    $url = $sub . '#' . $sharp;
	    $id = $this->input->post('id');
	    $no = $this->input->post('no');
	    $nama = $this->input->post('nama');
	    $harga = $this->input->post('unit_price');
	    $qty = $this->input->post('qty');
	    $up = $this->input->post('unit_price_disc');
	    $disc = $this->input->post('disc');
	    $pesan = $this->input->post('notes');
	    $uoi = $this->session->userdata('uoi');
	    $id_customer = $this->session->userdata('id');
	    $unit_price_disc = $up ?: 0;

	    // ✅ Ambil data sub_category item yang sedang ditambahkan
	    $itemData = $this->db->get_where('sh_m_item', ['no' => $no])->row();
	    
	    $sub_category = $itemData ? $itemData->sub_category : '';

	    // ✅ Validasi khusus untuk Special Promo
	    if ($sub_category == 'Special Promo') {
		    $cekSPtrans   = $this->Item_model->cekSPtrans($no);   // true jika item SP ini sudah ada di transaksi
		    $cekSPchart   = $this->Item_model->cekSPchart($no);   // true jika item SP ini sudah ada di cart
		    $countSPchart = $this->Item_model->countSPchart();    // jumlah total SP di cart
		    $countSPtrans = $this->Item_model->countSPtrans();    // jumlah total SP di transaksi

		    // Cek apakah sudah ada Special Promo lain di sistem
		    $hasOtherSP = ($countSPchart > 0 || $countSPtrans > 0);

		    // Kasus 1: Belum ada SP di cart/transaksi -> boleh insert
		    if (!$hasOtherSP) {
		        // lanjut ke proses insert
		    }
		    // Kasus 2: Item sama dengan yang sudah ada di cart/transaksi -> boleh insert
		    else if ($cekSPchart || $cekSPtrans) {
		        // lanjut ke proses insert
		    }
		    // Kasus 3: Ada SP lain (beda item) -> tolak
		    else {
		        $this->session->set_flashdata('error', 'You can only add one Special Promo item.');
		        redirect($_SERVER['HTTP_REFERER']);
		    }
		}



	    // ✅ lanjutkan proses existing logic
	    $id_trans = $this->db->get_where('sh_t_transactions', ['id_customer' => $id_customer])->row();
	    $cekdatacart = $this->Item_model->cekdatacart($no, $uoi)->row();

	    $cabang = $this->db->order_by('id', "desc")->limit(1)->get('sh_m_cabang')->row('id');
	    
	    $data = [
	        'item_code' => $no,
	        'id_trans' => $id_trans->id,
	        'id_customer' => $id_customer,
	        'qty' => $qty,
	        'cabang' => $cabang,
	        'unit_price' => $harga,
	        'description' => $nama,
	        'entry_by' => $this->session->userdata('username'),
	        'id_table' => $this->session->userdata('nomeja'),
	        'extra_notes' => $pesan,
	        'entry_date' => date('Y-m-d'),
	        'user_order_id' => $this->session->userdata('user_order_id'),
	        'options' => $op,
	        'unit_price_disc' => $unit_price_disc,
	        'disc' => $disc,
	        'addons' => 0,
	    ];

	    if ($qty == 0) {
	        $this->session->set_flashdata('error', 'Order Gagal! Tambahkan jumlah pesan!');
	        redirect($_SERVER['HTTP_REFERER']);
	    } else {
	        if ($cekdatacart) {
	            if ($cekdatacart->extra_notes == $pesan) {
	                $dataedit = ['qty' => $cekdatacart->qty + $qty];
	                $this->db->where([
	                    'item_code' => $no,
	                    'id_customer' => $id_customer,
	                    // 'user_order_id' => $this->session->userdata('user_order_id')
	                ]);
	                $result = $this->db->update('sh_cart', $dataedit);
	            } else {
	                $result = $this->db->insert('sh_cart', $data);
	            }
	        } else {
	            $result = $this->db->insert('sh_cart', $data);
	        }
	        
	        if ($result) {
	            // (bagian add-on dan log event tetap sama seperti sebelumnya)
	            $this->session->set_flashdata('success', 'Menu Added to Cart');
	            redirect('index.php/ordermakanan/menu/Makanan/' . $url);
	        }
	    }
	}
	public function cart_action()
	{
	    error_reporting(0);
	    ini_set('display_errors', 0);

	    header('Content-Type: application/json');

	    /* ================= CEK STATUS ================= */
	    $session = $this->cekstatus_model->cek();

	    if (isset($session['status']) && $session['status'] == 'Billing') {
	        $nomeja = $this->session->userdata('nomeja');

	        echo json_encode([
	            'status' => false,
	            'force_logout' => true,
	            'redirect' => base_url('index.php/login/logoutback/' . $nomeja),
	            'msg' => 'Session sudah billing'
	        ]);
	        exit;
	    }

	    /* ================= AMBIL DATA ================= */
	    $item_id     = $this->input->post('item_id');
	    $qty         = (int) $this->input->post('qty'); // hanya dipakai untuk delete
	    $price       = (int) $this->input->post('price');
	    $nomeja      = $this->session->userdata('nomeja');
	    $id_customer = $this->session->userdata('id');
	    $username    = $this->session->userdata('username');
	    $type    = $this->input->post('type');
	    $ip_address  = $this->input->ip_address();

	    /* ================= GET ITEM ================= */
	    $item = $this->db
	        ->select('stock, need_stock, no, description')
	        ->where('id', $item_id)
	        ->get('sh_m_item')
	        ->row();

	    if (!$item) {
	        echo json_encode([
	            'status' => false,
	            'msg' => 'Item tidak ditemukan'
	        ]);
	        exit;
	    }

	    $item_code = $item->no;

	    /* ================= GET TRANSACTION ================= */
	    $id_trans = $this->db
	        ->where('id_customer', $id_customer)
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get('sh_t_transactions')
	        ->row();

	    $id_trans_id = $id_trans ? $id_trans->id : null;

	    /* ================= GET CABANG ================= */
	    $cabang = $this->db
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get('sh_m_cabang')
	        ->row('id');

	    /* ================= CEK CART ================= */
	    $cart = $this->db->where([
	        'item_code'   => $item_code,
	        'id_customer' => $id_customer,
	        'id_table'    => $nomeja,
	    ])->get('sh_cart')->row();

	    /* ================= START TRANSACTION ================= */
	    $this->db->trans_start();

	    /* ================= DELETE ================= */
	    if ($qty <= 0) {
	        if ($cart) {

	            // 🔥 BALIKIN STOCK
	           //  if ($item->need_stock == 1) {
	           //      $this->db->set('stock', 'stock + 1', false)
			         // ->where('id', $item_id)
			         // ->update('sh_m_item');
	           //  }

	            $this->db->where('id', $cart->id)->delete('sh_cart');

	            // 🔥 LOG DELETE
	            $this->db->insert('sh_event_log', [
	                'event_type'  => 'Delete item',
	                'cabang'      => $cabang,
	                'id_trans'    => $id_trans_id,
	                'id_customer' => $id_customer,
	                'event_date'  => date('Y-m-d H:i:s'),
	                'user_by'     => $username,
	                'description' => 'Hapus item: '.$item->description.' (Qty: '.$cart->qty.') | Meja: '.$nomeja.' | IP: '.$ip_address.' dihalaman ordermakanan',
	                'created_date'=> date('Y-m-d'),
	            ]);
	        }

	        $this->db->trans_complete();

	        echo json_encode([
	            'status' => true,
	            'action' => 'deleted'
	        ]);
	        exit;
	    }

	    /* ================= UPDATE ================= */
	    if ($cart) {

	        // cek stock cukup untuk +1
	        if ($item->need_stock == 1 && $item->stock < 1) {
	            echo json_encode([
	                'status' => false,
	                'msg' => 'Stock tidak cukup',
	                'stock' => $item->stock
	            ]);
	            exit;
	        }
	        if ($type == 'plus') {
	        	$new_qty = $cart->qty + 1;
	        }else{
	        	$new_qty = $cart->qty - 1;
	        }

	        // 🔥 KURANGI STOCK
	        // if ($item->need_stock == 1) {
	        //     $this->db->set('stock', 'stock - 1', false)
	        //              ->where('id', $item_id)
	        //              ->update('sh_m_item');
	        // }

	        $this->db->where('id', $cart->id)
	                 ->update('sh_cart', ['qty' => $new_qty]);

	        // 🔥 LOG UPDATE
	        $this->db->insert('sh_event_log', [
	            'event_type'  => 'Update Qty',
	            'cabang'      => $cabang,
	            'id_trans'    => $id_trans_id,
	            'id_customer' => $id_customer,
	            'event_date'  => date('Y-m-d H:i:s'),
	            'user_by'     => $username,
	            'description' => 'Update item: '.$item->description.' ('.$cart->qty.' → '.$new_qty.') | Meja: '.$nomeja.' | IP: '.$ip_address.' dihalaman ordermakanan',
	            'created_date'=> date('Y-m-d'),
	        ]);

	        $this->db->trans_complete();

	        echo json_encode([
	            'status' => true,
	            'action' => 'updated',
	            'qty'    => $new_qty
	        ]);
	        exit;
	    }

	    /* ================= INSERT ================= */

	    // cek stock cukup untuk insert pertama
	    if ($item->need_stock == 1 && $item->stock < 1) {
	        echo json_encode([
	            'status' => false,
	            'msg' => 'Stock habis',
	            'stock' => $item->stock
	        ]);
	        exit;
	    }

	    // 🔥 KURANGI STOCK
	    // if ($item->need_stock == 1) {
	    //     $this->db->set('stock', 'stock - 1', false)
	    //              ->where('id', $item_id)
	    //              ->update('sh_m_item');
	    // }

	    $this->db->insert('sh_cart', [
	        'item_code'     => $item_code,
	        'id_trans'      => $id_trans_id,
	        'id_customer'   => $id_customer,
	        'qty'           => 1,
	        'cabang'        => $cabang,
	        'unit_price'    => $price,
	        'description'   => $item->description,
	        'entry_by'      => $username,
	        'id_table'      => $nomeja,
	        'entry_date'    => date('Y-m-d'),
	        'user_order_id' => $this->session->userdata('user_order_id'),
	        'addons'        => 0
	    ]);

	    // 🔥 LOG INSERT
	    $this->db->insert('sh_event_log', [
	        'event_type'  => 'Add To Cart',
	        'cabang'      => $cabang,
	        'id_trans'    => $id_trans_id,
	        'id_customer' => $id_customer,
	        'event_date'  => date('Y-m-d H:i:s'),
	        'user_by'     => $username,
	        'description' => 'Tambah item: '.$item->description.' (Qty: 1) | Meja: '.$nomeja.' | IP: '.$ip_address.' dihalaman ordermakanan',
	        'created_date'=> date('Y-m-d'),
	    ]);

	    $this->db->trans_complete();

	    echo json_encode([
	        'status' => true,
	        'action' => 'inserted',
	        'qty'    => 1
	    ]);
	    exit;
	}
	
	public function cart_actiondev()
	{
	    error_reporting(0);
	    ini_set('display_errors', 0);

	    header('Content-Type: application/json');

	    /* ================= CEK STATUS ================= */
	    $session = $this->cekstatus_model->cek();

	    if (isset($session['status']) && $session['status'] == 'Billing') {
	        $nomeja = $this->session->userdata('nomeja');

	        echo json_encode([
	            'status' => false,
	            'force_logout' => true,
	            'redirect' => base_url('index.php/login/logoutback/' . $nomeja),
	            'msg' => 'Session sudah billing'
	        ]);
	        exit;
	    }

	    /* ================= AMBIL DATA ================= */
	    $item_id     = $this->input->post('item_id');
	    $qty         = (int) $this->input->post('qty');
	    $price       = (int) $this->input->post('price');
	    $nomeja      = $this->session->userdata('nomeja');
	    $id_customer = $this->session->userdata('id');
	    $username    = $this->session->userdata('username');
	    $ip_address  = $this->input->ip_address();

	    /* ================= GET ITEM ================= */
	    $item = $this->db
	        ->select('no, description')
	        ->where('id', $item_id)
	        ->get('sh_m_item')
	        ->row();

	    if (!$item) {
	        echo json_encode([
	            'status' => false,
	            'msg' => 'Item tidak ditemukan'
	        ]);
	        exit;
	    }

	    $item_code = $item->no;

	    /* ================= GET TRANSACTION ================= */
	    $id_trans = $this->db
	        ->where('id_customer', $id_customer)
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get('sh_t_transactions')
	        ->row();

	    $id_trans_id = $id_trans ? $id_trans->id : null;

	    /* ================= GET CABANG ================= */
	    $cabang = $this->db
	        ->order_by('id', 'DESC')
	        ->limit(1)
	        ->get('sh_m_cabang')
	        ->row('id');

	    /* ================= CEK CART ================= */
	    $cart = $this->db->where([
	        'item_code'   => $item_code,
	        'id_customer' => $id_customer,
	        'id_table'    => $nomeja,
	    ])->get('sh_cart')->row();

	    /* ================= START TRANSACTION ================= */
	    $this->db->trans_start();

	    /* ================= DELETE ================= */
	    if ($qty <= 0) {
	        if ($cart) {

	            $this->db->where('id', $cart->id)->delete('sh_cart');

	            // 🔥 LOG DELETE
	            $this->db->insert('sh_event_log', [
	                'event_type'  => 'Delete item',
	                'cabang'      => $cabang,
	                'id_trans'    => $id_trans_id,
	                'id_customer' => $id_customer,
	                'event_date'  => date('Y-m-d H:i:s'),
	                'user_by'     => $username,
	                'description' => 'Hapus item: '.$item->description.' (Qty: '.$cart->qty.') | Meja: '.$nomeja.' | IP: '.$ip_address.' dihalaman ordermakanan',
	                'created_date'=> date('Y-m-d'),
	            ]);
	        }

	        $this->db->trans_complete();

	        echo json_encode([
	            'status' => true,
	            'action' => 'deleted'
	        ]);
	        exit;
	    }

	    /* ================= UPDATE ================= */
	    if ($cart) {

	        $new_qty = $cart->qty + 1;

	        $this->db->where('id', $cart->id)
	                 ->update('sh_cart', ['qty' => $new_qty]);

	        // 🔥 LOG UPDATE
	        $this->db->insert('sh_event_log', [
	            'event_type'  => 'Update Qty',
	            'cabang'      => $cabang,
	            'id_trans'    => $id_trans_id,
	            'id_customer' => $id_customer,
	            'event_date'  => date('Y-m-d H:i:s'),
	            'user_by'     => $username,
	            'description' => 'Update item: '.$item->description.' ('.$cart->qty.' → '.$new_qty.') | Meja: '.$nomeja.' | IP: '.$ip_address.' dihalaman ordermakanan',
	            'created_date'=> date('Y-m-d'),
	        ]);

	        $this->db->trans_complete();

	        echo json_encode([
	            'status' => true,
	            'action' => 'updated',
	            'qty'    => $new_qty
	        ]);
	        exit;
	    }

	    /* ================= INSERT ================= */

	    $this->db->insert('sh_cart', [
	        'item_code'     => $item_code,
	        'id_trans'      => $id_trans_id,
	        'id_customer'   => $id_customer,
	        'qty'           => 1,
	        'cabang'        => $cabang,
	        'unit_price'    => $price,
	        'description'   => $item->description,
	        'entry_by'      => $username,
	        'id_table'      => $nomeja,
	        'entry_date'    => date('Y-m-d'),
	        'user_order_id' => $this->session->userdata('user_order_id'),
	        'addons'        => 0
	    ]);

	    // 🔥 LOG INSERT
	    $this->db->insert('sh_event_log', [
	        'event_type'  => 'Add To Cart',
	        'cabang'      => $cabang,
	        'id_trans'    => $id_trans_id,
	        'id_customer' => $id_customer,
	        'event_date'  => date('Y-m-d H:i:s'),
	        'user_by'     => $username,
	        'description' => 'Tambah item: '.$item->description.' (Qty: 1) | Meja: '.$nomeja.' | IP: '.$ip_address.' dihalaman ordermakanan',
	        'created_date'=> date('Y-m-d'),
	    ]);

	    $this->db->trans_complete();

	    echo json_encode([
	        'status' => true,
	        'action' => 'inserted',
	        'qty'    => 1
	    ]);
	    exit;
	}
	public function add_cartOLDS($sub)
	{
	    // OPTIONS
	    $optionsReq = $this->input->post('options');
	    $op = is_array($optionsReq)
	        ? htmlspecialchars(implode(',', $optionsReq))
	        : htmlspecialchars($optionsReq);

	    $optionaddReq = $this->input->post('option_additional');
	    $opa = is_array($optionaddReq)
	        ? htmlspecialchars(implode(',', $optionaddReq))
	        : htmlspecialchars($optionaddReq);

	    // ADDONS
	    $addons  = $this->input->post('addons') ?? [];
	    $soldOut = false;

	    // CEK STOK ADDON
	    foreach ($addons as $item_code) {
	        $item = $this->db
	            ->select('need_stock, stock')
	            ->where('no', $item_code)
	            ->get('sh_m_item')
	            ->row();

	        if ($item && $item->need_stock == 1 && $item->stock <= 0) {
	            $soldOut = true;
	            break;
	        }
	    }

	    if ($soldOut) {
	        $this->session->set_flashdata('error', 'The add-on stock has been sold out');
	        redirect($_SERVER['HTTP_REFERER']);
	    }

	    // URL
	    $sharp = str_replace("%20", "_", $sub);
	    $url   = $sub . '#' . $sharp;

	    // POST DATA
	    $no            = $this->input->post('no');
	    $nama          = $this->input->post('nama');
	    $harga         = $this->input->post('unit_price');
	    $qty           = (int) $this->input->post('qty');
	    $up            = $this->input->post('unit_price_disc');
	    $disc          = $this->input->post('disc');
	    $pesan         = $this->input->post('notes');
	    $id_customer   = $this->session->userdata('id');
	    $user_order_id = $this->session->userdata('user_order_id');


	    if ($qty <= 0) {
	        $this->session->set_flashdata('error', 'Order Gagal! Tambahkan jumlah pesan!');
	        redirect($_SERVER['HTTP_REFERER']);
	    }

	    $unit_price_disc = $up ?: 0;

	    // TRANSAKSI AKTIF
	    $id_trans = $this->db
	        ->get_where('sh_t_transactions', ['id_customer' => $id_customer])
	        ->row();

	    if (!$id_trans) {
	        $this->session->set_flashdata('error', 'Transaksi belum tersedia');
	        redirect($_SERVER['HTTP_REFERER']);
	    }

	    // CABANG TERAKHIR
	    $cabang = $this->db
	        ->order_by('id', 'desc')
	        ->limit(1)
	        ->get('sh_m_cabang')
	        ->row('id');

	    // CEK ITEM SAMA DI CART (item_code + options)
	    $cartSameOption = $this->db
	        ->where([
	            'item_code'     => $no,
	            'id_customer'   => $id_customer,
	            // 'user_order_id' => $user_order_id,
	            'options'       => $op
	        ])
	        ->get('sh_cart')
	        ->row();

	    // DATA CART
	    $data = [
	        'item_code'       => $no,
	        'id_trans'        => $id_trans->id,
	        'id_customer'     => $id_customer,
	        'qty'             => $qty,
	        'cabang'          => $cabang,
	        'unit_price'      => $harga,
	        'description'     => $nama,
	        'entry_by'        => $this->session->userdata('username'),
	        'id_table'        => $this->session->userdata('nomeja'),
	        'extra_notes'     => $pesan,
	        'entry_date'      => date('Y-m-d'),
	        'user_order_id'   => $user_order_id,
	        'options'         => $op,
	        'notesdua'		  => $opa,
	        'unit_price_disc' => $unit_price_disc,
	        'disc'            => $disc,
	        'addons'          => 0
	    ];

	    // INSERT / UPDATE
	    if ($cartSameOption) {
	        // ITEM + OPTIONS SAMA → UPDATE QTY
	        $result = $this->db
	            ->where('id', $cartSameOption->id)
	            ->update('sh_cart', [
	                'qty' => $cartSameOption->qty + $qty
	            ]);
	    } else {
	        // OPTIONS BEDA → INSERT BARU
	        $result = $this->db->insert('sh_cart', $data);
	    }

	    if ($result) {
	        $this->session->set_flashdata('success', 'Menu Added to Cart');
	        redirect('index.php/ordermakanan/menu/Makanan/' . $url);
	    } else {
	        $this->session->set_flashdata('error', 'Gagal menambahkan ke cart');
	        redirect($_SERVER['HTTP_REFERER']);
	    }
	}

	public function add_cart($sub)
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    // -----------------------
	    // OPTIONS
	    // -----------------------
	    $optionsReq = $this->input->post('options'); // array multidimensi dari view
	    $opArray = [];

	    // flatten array per group
	    if (is_array($optionsReq)) {
	        foreach ($optionsReq as $group => $vals) {
	            if (is_array($vals)) {
	                foreach ($vals as $v) {
	                    $opArray[] = htmlspecialchars($v);
	                }
	            }
	        }
	    }

	    $op = implode(',', $opArray); // string untuk disimpan di DB

	    // -----------------------
	    // ADDONS
	    // -----------------------
	    $addons  = $this->input->post('addons') ?? [];
	    $soldOut = false;

	    // CEK STOK ADDON
	    foreach ($addons as $item_code) {
	        $item = $this->db
	            ->select('need_stock, stock')
	            ->where('no', $item_code)
	            ->get('sh_m_item')
	            ->row();

	        if ($item && $item->need_stock == 1 && $item->stock <= 0) {
	            $soldOut = true;
	            break;
	        }
	    }

	    if ($soldOut) {
	        $this->session->set_flashdata('error', 'The add-on stock has been sold out');
	        redirect($_SERVER['HTTP_REFERER']);
	    }

	    // -----------------------
	    // URL
	    // -----------------------
	    $sharp = str_replace("%20", "_", $sub);
	    $url   = $sub . '#' . $sharp;

	    // -----------------------
	    // POST DATA
	    // -----------------------
	    $no            = $this->input->post('no');
	    $nama          = $this->input->post('nama');
	    $harga         = $this->input->post('unit_price');
	    $qty           = (int) $this->input->post('qty');
	    $up            = $this->input->post('unit_price_disc');
	    $disc          = $this->input->post('disc');
	    $pesan         = $this->input->post('notes');
	    $id_customer   = $this->session->userdata('id');
	    $user_order_id = $this->session->userdata('user_order_id');

	    if ($qty <= 0) {
	        $this->session->set_flashdata('error', 'Order failed! Please add quantity.');
	        redirect($_SERVER['HTTP_REFERER']);
	    }

	    $unit_price_disc = $up ?: 0;

	    // -----------------------
	    // TRANSAKSI AKTIF
	    // -----------------------
	    $id_trans = $this->db
	        ->get_where('sh_t_transactions', ['id_customer' => $id_customer])
	        ->row();

	    if (!$id_trans) {
	        $this->session->set_flashdata('error', 'Transaction not available');
	        redirect($_SERVER['HTTP_REFERER']);
	    }

	    // -----------------------
	    // CABANG TERAKHIR
	    // -----------------------
	    $cabang = $this->db
	        ->order_by('id', 'desc')
	        ->limit(1)
	        ->get('sh_m_cabang')
	        ->row('id');

	    // -----------------------
	    // CEK ITEM SAMA DI CART (item_code + options)
	    // -----------------------
	    $cartSameOption = $this->db
	        ->where([
	            'item_code'   => $no,
	            'id_customer' => $id_customer,
	            'options'     => $op
	        ])
	        ->get('sh_cart')
	        ->row();

	    // -----------------------
	    // DATA CART
	    // -----------------------
	    $data = [
	        'item_code'       => $no,
	        'id_trans'        => $id_trans->id,
	        'id_customer'     => $id_customer,
	        'qty'             => $qty,
	        'cabang'          => $cabang,
	        'unit_price'      => $harga,
	        'description'     => $nama,
	        'entry_by'        => $this->session->userdata('username'),
	        'id_table'        => $this->session->userdata('nomeja'),
	        'extra_notes'     => $pesan,
	        'entry_date'      => date('Y-m-d'),
	        'user_order_id'   => $user_order_id,
	        'options'         => $op,
	        'unit_price_disc' => $unit_price_disc,
	        'disc'            => $disc,
	        'addons'          => 0
	    ];

	    // -----------------------
	    // INSERT / UPDATE
	    // -----------------------
	    if ($cartSameOption) {
	        // ITEM + OPTIONS SAMA → UPDATE QTY
	        $result = $this->db
	            ->where('id', $cartSameOption->id)
	            ->update('sh_cart', [
	                'qty' => $cartSameOption->qty + $qty
	            ]);
	    } else {
	        // OPTIONS BEDA → INSERT BARU
	        $result = $this->db->insert('sh_cart', $data);
	    }

	    if ($result) {
	        $this->session->set_flashdata('success', 'Menu added to cart');
	        redirect('index.php/ordermakanan/menu/Makanan/' . $url);
	    } else {
	        $this->session->set_flashdata('error', 'Failed to add to cart');
	        redirect($_SERVER['HTTP_REFERER']);
	    }
	}



	public function addcart()
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
		$table = $this->session->userdata('nomeja');
		$uc = $this->session->userdata('username');
		$ic = $this->session->userdata('id');
		$qty = $this->input->post('qty');
		$ata = $this->input->post('cek');
		$qta = $this->input->post('qta');
		$nama = $this->input->post('nama');
		$pesan = $this->input->post('pesan');
		$harga = $this->input->post('harga');
		$item_code = $this->input->post('no');
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$cabang = $this->db->order_by('id',"desc")
  			->limit(1)
  			->get('sh_m_cabang')
  			->row('id');
  		
		echo $qty;
		$nomer = 1;
		for ($i = 0; $i < count($qty); $i++) {
			
			if ($qty[$i] != 0) {
				$n = $nomer++ . "<br>"; 
				$data[] = [
				'item_code' => $item_code[$i],
				'id_trans' => $id_trans->id,
				'id_customer' => $id_customer,
				'qty' => $qty[$i],
				'cabang' => $cabang,
				'unit_price' => $harga[$i],
				'description' => $nama[$i],
				'entry_by' => $this->session->userdata('username'),
				'id_table' => $table,
				'extra_notes' => $pesan[$i],
				'entry_date' => date('Y-m-d'),
				'as_take_away' => $ata[$i],
				'qty_take_away' => $qta[$i],
				'user_order_id' => $this->session->userdata('user_order_id'),
			];
			}
			$query = "select R.* from sh_cart R where R.id_table = '$table' and where R.description = '$nama[$i]' and left(R.create_date,10) = left(sysdate(),10) limit 1";
			$sql = $this->db->query("SELECT * FROM sh_cart where description='$nama[$i]' and id_table='$table' and Left(entry_date, 10) = Left(SYSDATE(), 10) limit 1");
			$cek_data = $sql->num_rows();
			if ($cek_data > 0) {
			$this->db->update_batch('sh_cart',$data,'item_code')->where('id_table',$table)->where('id_customer',$id_customer);
			}else{
			$this->db->insert_batch('sh_cart',$data);
			}
    		
	}

	if ($data == NULL) {
		$this->session->set_flashdata('error','Silahkan Pilih Makanan Yang Akan Di Pesan!');
				redirect($_SERVER['HTTP_REFERER']);
	}else{
	$result = $this->db->insert_batch('sh_cart',$data);
	
			if ($result) {
				$this->session->set_flashdata('success','Order Menu/Paket Berhasil Di Tambahkan Ke Dalam Cart');
				redirect($_SERVER['HTTP_REFERER']);
				// $where = array('qty' => 0);
				// $this->Item_model->hapus_qty($where,'testing');
			}else{
				echo "gagal order";
			}
	}
	}
	public function updatecart($id){
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
		$table = $this->session->userdata('nomeja');
		$uc = $this->session->userdata('username');
		$ic = $this->session->userdata('id');
		$qty = $this->input->post('qty');
		$ata = $this->input->post('cek');
		$qta = $this->input->post('qta');
		$nama = $this->input->post('nama');
		$pesan = $this->input->post('pesan');
		$harga = $this->input->post('harga');
		$item_code = $this->input->post('no');
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$cabang = $this->db->order_by('id',"desc")
  			->limit(1)
  			->get('sh_m_cabang')
  			->row('id');
	
		$nomer = 1;
		for ($i = 0; $i < count($qty); $i++) {
			
			if ($qty[$i] != 0) {
				$n = $nomer++ . "<br>"; 
				$data[] = [
				'item_code' => $item_code[$i],
				'qty' => $qty[$i]-1,
				
			];

			
			}
			if ($qty[$i] == 1) {
				$this->db->where('item_code', $item_code[$i]);
				$this->db->where('id_table',$table);
				$this->db->where('id_customer',$id_customer);
				$this->db->where('entry_date',date('Y-m-d'));
				$this->db->delete('sh_cart');
			}else{
			$this->db->update_batch('sh_cart',$data,'item_code')->where('id_table',$table)->where('id_customer',$id_customer)->where('entry_date',date('Y-m-d'));
    		}
    		
	}
	
	}
	public function jmlcart(){
		$id_customer = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$cart_count = $this->Item_model->cart_count($id_customer,$nomeja)->num_rows();
		if($cart_count > 0){
			$cart = $this->Item_model->cart_count($id_customer,$nomeja)->row();//tambahan	
			$cart_total = $cart->total_qty;
		}else{
			$cart_total = 0;
		}
		$result['total'] = $cart_total;
		$result['msg'] = "Berhasil di refresh secara Realtime";
		echo json_encode($result);
	}
	public function order()
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
		$table = $this->session->userdata('nomeja');
		$qty = $this->input->post('qty');
		$nama = $this->input->post('nama');
		$pesan = $this->input->post('pesan');
		$harga = $this->input->post('harga');
		$item_code = $this->input->post('no');
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$id_table = $this->db->get_Where('sh_rel_table', array('id_customer'=> $id_customer))->row();
		
		$st = $id_table->status;
		if ($st == "Dining" || $st == "Order") {
			$order_stat = 1;
		}elseif ($st == "Billing") {
			$order_stat = 2;
		}
		$today = date('Y-m-d');
		$curTime = explode(':', date('H:i:s'));
		$cekWeekEnd = date('D', strtotime($today));
		$check_promo = $this->Item_model->get_promo($today)->num_rows();
		$get_promo = $this->Item_model->get_promo($today)->row_array();
		$discount = 0;
		if($check_promo > 0){
			$item_check = $this->Item_model->get_info_item($request['item_code'],$get_promo)->num_rows();
			if($item_check > 0){
				$item_data = $this->Item_model->get_info_item($request['item_code'],$get_promo)->row_array();
				if($get_promo["promo_type"] == 'Discount'){
					if($get_promo["promo_criteria"] == 'Weekday'){ //Weekday
						if($cekWeekEnd !== "Sat" || $cekWeekEnd !== "Sun" || $cekWeekEnd !== "Sab" || $cekWeekEnd !== "Min"){
							if($curTime[0] >= $get_promo["promo_from"] && $curTime[0] <= $get_promo["promo_to"]){
								$discount = $get_promo["promo_value"];		
							}else{
								$discount = 0;
							}
						}else{
							$discount = 0;
						}	
					}else if($get_promo["promo_criteria"] == 'Weekend'){ //Weekend
						if($cekWeekEnd === "Sat" || $cekWeekEnd === "Sun" || $cekWeekEnd === "Sab" || $cekWeekEnd === "Min"){
							if($curTime[0] >= $get_promo["promo_from"] && $curTime[0] <= $get_promo["promo_to"]){
								$discount = $get_promo["promo_value"];		
							}else{
								$discount = 0;
							}
						}else{
							$discount = 0;
						}	
					}else{ //Full Week
						if($curTime[0] >= $get_promo["promo_from"] && $curTime[0] <= $get_promo["promo_to"]){
							$discount = $get_promo["promo_value"];		
						}else{
							$discount = 0;
						}
					}
				}else{
					$discount = 0;	
				}
			}else{
				$discount = 0;
			}
		}
		$cabang = $this->db->order_by('id',"desc")
  			->limit(1)
  			->get('sh_m_cabang')
  			->row('id');
  			
		$nomer = 1;
		for ($i = 0; $i < count($qty); $i++) {
			if ($qty[$i] != 0) {
				$n = $nomer++ . "<br>"; 
				$data[] = [
				'id_trans' => $id_trans->id,
				'item_code' => $item_code[$i],
				'qty' => $qty[$i],
				'cabang' => $cabang,
				'unit_price' => $harga[$i],
				'description' => $nama[$i],
				'start_time_order' => date('H:i:s'),
				'entry_by' => $this->session->userdata('username'),
				'disc' => $discount,
				'is_cancel' => 0,
				'session_item' => 0,
				'selected_table_no' => $table,
				'seat_id' => 0,
				'sort_id' => $n,
				'as_take_away' => 0,
				'qty_take_away' => 0,
				'extra_notes' => $pesan[$i],
				'checker_printed' => 1,
				'created_date' => date('Y-m-d'),
				'order_type' => $order_stat
			];
			 }
    
	}
	// var_dump($data);exit();
	$result = $this->db->insert_batch('sh_t_transaction_details',$data);
	
			if ($result) {
				$ic = $this->session->userdata('id');
				 $data = ['status' => 'Dining'];
				$this->db->where('id_customer',$ic);
    			$this->db->update('sh_rel_table',$data);
    			$this->db->where('id_customer',$ic);
    			$this->db->delete('sh_t_sub_transactions');
    			
    			$this->session->set_flashdata('success','Order Menu/Paket Berhasil Di Tambahkan');
				redirect('selforder/home/'.$table);
				// $where = array('qty' => 0);
				// $this->Item_model->hapus_qty($where,'testing');
			}else{
				echo "gagal order";
			}
	}
	
	public function save_notes()
	{
		$session = $this->cekstatus_model->cek();
	  		if($session['status'] == 'Billing'){
		  			$nomeja = $this->session->userdata('nomeja');
		  			redirect('index.php/login/logoutback/'.$nomeja);
		  	}
	    // Ambil input
	    $item_id = $this->input->post('item_id', TRUE);
	    $notes   = $this->input->post('notes', TRUE);

	    $notes = trim($notes);

	    // =========================
	    // VALIDASI
	    // =========================
	    if (empty($item_id)) {
	        echo json_encode([
	            'status' => false,
	            'message' => 'Item ID is required'
	        ]);
	        return;
	    }

	    if (empty($notes)) {
	        echo json_encode([
	            'status' => false,
	            'message' => 'Notes cannot be empty'
	        ]);
	        return;
	    }

	    if (strlen($notes) > 200) {
	        echo json_encode([
	            'status' => false,
	            'message' => 'Maximum 200 characters allowed'
	        ]);
	        return;
	    }

	    // hanya huruf, angka, dan spasi
	    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $notes)) {
	        echo json_encode([
	            'status' => false,
	            'message' => 'Only letters and numbers are allowed'
	        ]);
	        return;
	    }

	    // =========================
	    // AMBIL DATA ITEM
	    // =========================
	    $item = $this->db
	        ->where('id', $item_id)
	        ->get('sh_m_item')
	        ->row();

	    if (!$item) {
	        echo json_encode([
	            'status' => false,
	            'message' => 'Item not found'
	        ]);
	        return;
	    }

	    $item_code = $item->no;

	    // =========================
	    // UPDATE KE CART
	    // =========================
	    $this->db->where('item_code', $item_code);

	    // OPTIONAL (RECOMMENDED kalau multi user / meja)
	    $this->db->where('id_table', $this->session->userdata('nomeja'));

	    $update = $this->db->update('sh_cart', [
	        'extra_notes' => $notes
	    ]);

	    if ($update) {
	        echo json_encode([
	            'status' => true,
	            'message' => 'Notes saved successfully'
	        ]);
	    } else {
	        echo json_encode([
	            'status' => false,
	            'message' => 'Failed to save notes'
	        ]);
	    }
	}
	
	// AIzaSyA38CxDrGgPBFdntU_n43p7eGVSQvFAe1I
	
public function ai_chatOLD()
{
    $message = strtolower($this->input->post('message'));
    $logo    = $this->Admin_model->getLogo();

    $responseText = "";
    $menus = [];

    // =========================
    // 🔥 KEEP RAW MESSAGE (IMPORTANT)
    // =========================
    $rawMessage = $message;

    // =========================
    // 🔥 NORMALIZE
    // =========================
    $message = str_replace(
        ['pingin','pengen','mau','order','pesan'],
        'beli',
        $message
    );

    // =========================
    // 🔥 LANGUAGE
    // =========================
    $lang = $this->detectLanguage($message);

    // =========================
    // 🔥 INTENT
    // =========================
    $intent = $this->detectIntent($message);

    // =========================
    // 🔥 QTY FIX (ANTI ERROR)
    // =========================
    preg_match_all('/\d+/', $rawMessage, $qtyMatch);
    $qty = !empty($qtyMatch[0]) ? (int)$qtyMatch[0][0] : 1;

    // =========================
    // 🔥 ORDER (PRIORITY)
    // =========================
    if ($intent == 'order') {

        // 🔥 CLEAN ONLY TEXT, NOT NUMBER
        $clean = preg_replace('/\d+/', '', $rawMessage);
        $clean = trim(str_replace(['beli','pesan','order'], '', $clean));

        $corrected = $this->correctMenuName($clean);

        // 🔥 STRONG MATCHING
        $this->db->group_start();
        $this->db->like('description', $corrected);
        $this->db->or_like('description', $clean);
        $this->db->group_end();

        $this->db->where('is_sold_out', 0);
        $item = $this->db->get('sh_m_item')->row();

        if ($item) {

            // 🔥 FIXED CART INSERT (NO LOOP BUG)
            $_POST['item_id'] = $item->id;
            $_POST['qty']     = $qty;
            $_POST['price']   = $item->harga_weekend;
            $_POST['type']    = 'plus';

            $this->cart_action();

            $responseText = ($lang == 'id')
                ? "✅ {$item->description} x{$qty} berhasil ditambahkan ke cart!"
                : "✅ {$item->description} x{$qty} added to cart!";
        }
        else {
            $responseText = ($lang == 'id')
                ? "❌ Menu tidak ditemukan. Coba ketik ulang ya 😊"
                : "❌ Menu not found. Try again 😊";
        }

        echo json_encode([
            'status' => true,
            'reply'  => $responseText ?: "OK",
            'menus'  => []
        ]);
        return;
    }

    // =========================
    // 🔥 INTENT BASED QUERY
    // =========================
    switch ($intent) {

        case 'diet':
            $this->db->where('is_healthy', 1);
            break;

        case 'bulking':
            $this->db->order_by('harga_weekend', 'DESC');
            break;

        case 'cheap':
            $this->db->order_by('harga_weekend', 'ASC');
            break;

        case 'hungry':
            $this->db->order_by('RAND()');
            break;

        case 'drink':
            $this->db->like('category', 'MINUMAN');
            break;

        case 'recommend':
            $this->db->where('chef_recommended', 1);
            break;

        case 'search':
        case 'info':
            $corrected = $this->correctMenuName($message);
            $this->db->group_start();
            $this->db->like('description', $corrected);
            $this->db->or_like('description', $message);
            $this->db->group_end();
            break;
    }

    $this->db->where('is_sold_out', 0);
    $this->db->where('is_active', 1);
    $this->db->limit(5);

    $result = $this->db->get('sh_m_item')->result();

    foreach ($result as $m) {
        $menus[] = $this->formatMenu($m, $logo);
    }

    // =========================
    // 🔥 AI RESPONSE (SMART MODE)
    // =========================
    if (!empty($menus)) {

        $menuNames = array_column($menus, 'name');

        $prompt = ($lang == 'id')
        ? "Kamu adalah asisten restoran yang pintar, santai, dan natural.

	User: \"$message\"
	Intent: $intent

	Jawab 2-4 kalimat santai, lalu rekomendasikan menu ini:
	" . implode(", ", $menuNames)

	        : "You are a smart restaurant assistant.

	User: \"$message\"
	Intent: $intent

	Reply naturally, then recommend:
	" . implode(", ", $menuNames);

        $ai = $this->askGemini($prompt);

        $responseText = $ai ? "🤖 " . $ai : "🤖 Ini rekomendasi buat kamu 😊";
    }

    // =========================
    // 🔥 FALLBACK CHAT (NO MENU FOUND)
    // =========================
    if (empty($menus) && empty($responseText)) {

        $prompt = ($lang == 'id')
            ? "Kamu adalah asisten restoran yang ramah.\nUser: $message"
            : "You are a friendly restaurant assistant.\nUser: $message";

        $ai = $this->askGemini($prompt);

        $responseText = $ai
            ? "🤖 " . $ai
            : ($lang == 'id'
                ? "Lagi pengen apa? Aku bantu cari menu 😊"
                : "Looking for something? I can help 😊");
    }

    // =========================
    // 🔥 SAFE OUTPUT (ANTI UNDEFINED 100%)
    // =========================
    echo json_encode([
        'status' => true,
        'reply'  => $responseText ?: "🤖 Maaf, aku belum paham maksudnya 😊",
        'menus'  => $menus ?: []
    ]);
}
public function ai_chatOLD2()
{
    $message = strtolower($this->input->post('message'));
    $logo    = $this->Admin_model->getLogo();

    $menus = [];
    $responseText = "";
    $rawMessage = $message;

    // =========================
    // 🔥 STEP 1: AI UNDERSTANDING (TIDAK PAKAI JSON)
    // =========================
    $aiPrompt = "
		Anda adalah asisten restoran di Indonesia.

		Tugas:
		- Pahami maksud user
		- Jawab natural 1–2 kalimat dalam bahasa Indonesia
		- Jika ada makanan, sebutkan secara umum (tanpa harus detail JSON)

		User: \"$rawMessage\"
		";

    $aiRaw = $this->askGemini($aiPrompt);

    // =========================
    // 🔥 SAFETY AI OUTPUT (ANTI KOSONG)
    // =========================
    $replyAI = trim($aiRaw);

    if (empty($replyAI)) {
        $replyAI = "Aku bantu carikan menu terbaik untuk kamu 😊";
    }

    // =========================
    // 🔥 STEP 2: INTENT DETECTION (RULE BASED - STABLE)
    // =========================
    $intent = $this->detectIntent($message);

    // =========================
    // 🔥 STEP 3: ORDER HANDLING
    // =========================
    if ($intent == 'order') {

        preg_match_all('/\d+/', $rawMessage, $qtyMatch);
        $qty = !empty($qtyMatch[0]) ? (int)$qtyMatch[0][0] : 1;
        $qty = max(1, $qty);

        $clean = preg_replace('/\d+/', '', $rawMessage);
        $clean = trim(str_replace(['beli','pesan','order'], '', $clean));

        $this->db->like('description', $clean);
        $this->db->where('is_sold_out', 0);
        $item = $this->db->get('sh_m_item')->row();

        if ($item) {

            $_POST['item_id'] = $item->id;
            $_POST['qty']     = $qty;
            $_POST['price']   = $item->harga_weekend;
            $_POST['type']    = 'plus';

            $this->cart_action();

            $responseText = "🤖 " . $replyAI . "\n\n✅ {$item->description} x{$qty} berhasil ditambahkan ke keranjang";
        } else {
            $responseText = "🤖 " . $replyAI . "\n\n❌ Menu tidak ditemukan";
        }

        echo json_encode([
            'status' => true,
            'reply'  => $responseText,
            'menus'  => []
        ]);
        return;
    }

    // =========================
    // 🔥 STEP 4: MENU QUERY
    // =========================
    switch ($intent) {

        case 'diet':
            $this->db->where('is_healthy', 1);
            break;

        case 'bulking':
            $this->db->order_by('harga_weekend', 'DESC');
            break;

        case 'cheap':
            $this->db->order_by('harga_weekend', 'ASC');
            break;

        case 'drink':
            $this->db->like('category', 'MINUMAN');
            break;

        case 'recommend':
            $this->db->where('chef_recommended', 1);
            break;

        case 'hungry':
        default:
            $this->db->order_by('RAND()');
            break;
    }

    $this->db->where('is_active', 1);
    $this->db->where('is_sold_out', 0);
    $this->db->where('harga_weekend >', 0);
    // $this->db->limit(5);

    $result = $this->db->get('sh_m_item')->result();

    foreach ($result as $m) {
        $menus[] = $this->formatMenu($m, $logo);
    }

    $menuNames = array_column($menus, 'name');

    // =========================
    // 🔥 STEP 5: FINAL RESPONSE (AI + MENU)
    // =========================
    if (!empty($menus)) {

        $finalPrompt = "
		Anda adalah pelayan restoran di Indonesia.

		ATURAN:
		- Gunakan bahasa Indonesia
		- Jawab santai 1–2 kalimat
		- Jangan mengarang di luar menu

		Jawaban AI sebelumnya:
		$replyAI

		Menu yang tersedia:
		" . implode(", ", $menuNames);

        $finalAI = $this->askGemini($finalPrompt);

        $responseText = "🤖 " . (trim($finalAI) ?: $replyAI);

    } else {

        $responseText = "🤖 " . $replyAI;
    }

    // =========================
    // 🔥 SAFE OUTPUT (ANTI BLANK 100%)
    // =========================
    echo json_encode([
        'status' => true,
        'reply'  => $responseText ?: "🤖 Aku siap membantu kamu 😊",
        'menus'  => $menus ?: []
    ]);
}
public function get_cart_summary()
{
    header('Content-Type: application/json'); // 🔥 WAJIB

    $id_customer = $this->session->userdata('id');

    if (!$id_customer) {
        echo json_encode([
            'has_food' => false,
            'has_drink' => false
        ]);
        return;
    }

    $cart = $this->db->where('id_customer', $id_customer)
                     ->get('sh_cart')
                     ->result();

    $has_food = false;
    $has_drink = false;

    foreach ($cart as $c) {

        $item = $this->db->where('no', $c->item_code)
                         ->get('sh_m_item')
                         ->row();

        if (!$item) continue;

        if (stripos($item->category, 'MINUMAN') !== false) {
            $has_drink = true;
        } else {
            $has_food = true;
        }
    }

    echo json_encode([
        'has_food' => $has_food,
        'has_drink' => $has_drink
    ]);
}
	public function ai_chat()
	{
	    header('Content-Type: application/json; charset=utf-8');

	    try {

	        $message = strtolower($this->input->post('message'));

	        $logo = $this->Admin_model->getLogo();

	        $menus = [];

	        $toolResult = null;

	        /* =========================
	           CONTEXT
	        ========================= */
	        $context = $this->session->userdata('chat_context');

	        /* =========================
	           INTENT DETECTION
	        ========================= */
	        $intent = $this->detectIntent($message);
			
	        /* =========================
	           CATEGORY DETECTION
	        ========================= */
	        $category = null;

	        if (preg_match('/minuman|drink|coffee|kopi|teh|juice/', $message)) {
	            $category = 'MINUMAN';
	        }

	        if (preg_match('/makanan|food|nasi|ayam|mie|burger|rice/', $message)) {
	            $category = 'MAKANAN';
	        }

	        /* =========================
	           QTY DETECTION
	        ========================= */
	        $qty = 1;

	        if (preg_match('/\d+/', $message, $m)) {
	            $qty = (int)$m[0];
	        }

	        /* =========================
	           HANDLE INTENT
	        ========================= */
	        switch ($intent) {

	            case 'order':

	                $food = $this->extractOrderItem($message);

	                $food = $this->correctMenuName($food);

	                $this->db->like('description', $food);

	                $this->db->where('is_sold_out', 0);

	                $item = $this->db->get('sh_m_item')->row();

	                if ($item) {

	                    $this->insertToCart(
	                        $item->id,
	                        $qty,
	                        $item->harga_weekend
	                    );

	                    $toolResult =
	                        "Aku sudah menambahkan {$item->description} ke keranjang";

	                    $this->session->set_userdata('chat_context', [

	                        'last_intent'  => 'order',

	                        'last_item_id' => $item->id

	                    ]);

	                } else {

	                    $toolResult =
	                        "Menu tidak ditemukan, coba lagi";

	                }

	                break;

	            case 'recommend':

	                $this->db->where('chef_recommended', 1);

	                break;

	            case 'cheap':

	                $this->db->order_by('harga_weekend', 'ASC');

	                if ($category == 'MINUMAN') {

	                    $this->db->like('category', 'MINUMAN');

	                } elseif ($category == 'MAKANAN') {

	                    $this->db->not_like('category', 'MINUMAN');

	                }

	                break;

	            case 'drink':

	                $this->db->like('category', 'MINUMAN');

	                $this->db->not_like('sub_category', 'COFFEE');

	                break;

	            case 'food':

	                $this->db->where_in('category', [
	                    'SIAP SAJI',
	                    'PROSES'
	                ]);

	                break;

	            case 'diet':

	                $this->db->where('is_healthy', 1);

	                break;

	            /* =========================
	               SEARCH
	            ========================= */
	            case 'search':
	            case 'info':

	                $keyword = $this->extractKeyword($message);
					
	                $words = explode(' ', $keyword);

	                $this->db->group_start();

	                foreach ($words as $w) {

	                    if (!empty($w)) {

	                        $this->db->or_like('description', $w);

	                        $this->db->or_like('sub_category', $w);

	                    }

	                }

	                $this->db->group_end();

	                break;

	            default:
	                break;
	        }

	        /* =========================
	           QUERY MENU
	        ========================= */
	        if ($intent != 'general') {

	            $this->db->where('is_active', 1);

	            $this->db->where('is_sold_out', 0);

	            $this->db->where('harga_weekend >', 0);

	            $this->db->limit(10);

	            $result = $this->db->get('sh_m_item')->result();

	            foreach ($result as $m) {

	                $menus[] = $this->formatMenu($m, $logo);

	            }

	            /* =========================
	               HANDLE EMPTY
	            ========================= */
	            if (empty($menus)) {

	                $responseText =
	                    "Maaf ya, aku belum nemu menu yang kamu maksud, coba kata lain ya";

	                /* =========================
	                   INSERT CHAT HISTORY
	                ========================= */
	                $this->db->insert('sh_chatbot', [
	                	'id_customer'		=> $this->session->userdata('id'),

	                    'table_id'          => $this->input->post('table_id'),

	                    'customer_message'  => $this->removeEmoji($message),

	                    'chatbot_response'  => $this->removeEmoji($responseText),

	                    'intent'            => $intent,

	                    'detected_category' => $category,

	                    'qty'               => $qty,

	                    'response_type'     => 'chatbot'

	                ]);

	                echo json_encode([

	                    'status' => true,

	                    'reply'  => "🤖 " . $responseText,

	                    'menus'  => []

	                ], JSON_UNESCAPED_UNICODE);

	                exit;
	            }

	            $menuNames = array_column($menus, 'name');

	            $responseText = $this->humanizeResponse(
	                $intent,
	                $menuNames,
	                $toolResult
	            );

	        } else {

	            $ai = $this->askGemini(
	                "Kamu adalah asisten restoran ramah. Jawab singkat natural. User: $message"
	            );

	            $responseText =
	                trim($ai) ?: "Aku siap bantu kamu";

	        }

	        /* =========================
	           FINAL RESPONSE
	        ========================= */
	        $finalReply = "🤖 " . $responseText;

	        /* =========================
	           INSERT CHAT HISTORY
	        ========================= */
	        $this->db->insert('sh_chatbot', [

	            'table_id'          => $this->input->post('table_id'),

	            'customer_message'  => $this->removeEmoji($message),

	            'chatbot_response'  => $this->removeEmoji($finalReply),

	            'intent'            => $intent,

	            'detected_category' => $category,

	            'qty'               => $qty,

	            'response_type'     => 'chatbot'

	        ]);

	        /* =========================
	           RETURN JSON
	        ========================= */
	        echo json_encode([

	            'status' => true,

	            'reply'  => $finalReply,

	            'menus'  => $menus

	        ], JSON_UNESCAPED_UNICODE);

	        exit;

	    } catch (Exception $e) {

	        echo json_encode([

	            'status' => false,

	            'reply'  => $e->getMessage(),

	            'menus'  => []

	        ]);

	        exit;
	    }
	}

	/* =========================
	   REMOVE EMOJI FUNCTION
	========================= */
	private function removeEmoji($text)
	{
	    return preg_replace(
	        '/[\x{1F600}-\x{1F64F}'
	        . '\x{1F300}-\x{1F5FF}'
	        . '\x{1F680}-\x{1F6FF}'
	        . '\x{1F700}-\x{1F77F}'
	        . '\x{1F780}-\x{1F7FF}'
	        . '\x{1F800}-\x{1F8FF}'
	        . '\x{1F900}-\x{1F9FF}'
	        . '\x{1FA00}-\x{1FA6F}'
	        . '\x{1FA70}-\x{1FAFF}'
	        . '\x{2600}-\x{26FF}'
	        . '\x{2700}-\x{27BF}]/u',
	        '',
	        $text
	    );
	}
	private function extractOrderItem($message)
	{
	    return trim(str_replace(
	        [
	            'menambahkan',
	            'tambah',
	            'masukkan ke keranjang',
	            'ke dalam keranjang'
	        ],
	        '',
	        $message
	    ));
	}
	private function humanizeResponse($intent, $menuNames = [], $toolResult = null)
	{
	    $rand = rand(1, 3);

	    switch ($intent) {

	        case 'recommend':
	            $texts = [
	                "Aku rekomendasikan ini buat kamu 😊",
	                "Kayaknya ini cocok banget buat kamu 😋",
	                "Ini favorit banyak orang loh 🔥"
	            ];
	            return $texts[$rand-1] . ": " . implode(", ", $menuNames);

	        case 'search':
	        case 'info':
	            $texts = [
	                "Aku nemuin ini buat kamu 👇",
	                "Ini yang tersedia ya 😊",
	                "Coba cek ini deh 👇"
	            ];
	            return $texts[$rand-1] . ": " . implode(", ", $menuNames);

	        case 'cheap':
	            return "Kalau mau hemat, ini pilihan terbaiknya 💸: " . implode(", ", $menuNames);

	        case 'diet':
	            return "Ini menu sehat buat kamu 🥗: " . implode(", ", $menuNames);

	        case 'drink':
	            return "Lagi pengen minum? Ini dia pilihannya 🥤: " . implode(", ", $menuNames);

	        case 'order':
	            return $toolResult ?: "Pesanan kamu lagi diproses ya 😊";

	        default:
	            return "Ini pilihan yang bisa kamu coba 😊: " . implode(", ", $menuNames);
	    }
	}
		private function extractKeyword($message)
	{
	    $stopwords = ['ada','kah','dong','nih','ga','gak','punya','menu','yang'];

	    // 🔥 hapus simbol
	    $message = preg_replace('/[^a-zA-Z0-9\s]/', '', $message);

	    $words = explode(' ', $message);
	    $filtered = array_diff($words, $stopwords);

	    return trim(implode(' ', $filtered));
	}
		private function detectIntent($message)
	{
	    if (strpos($message, 'beli') !== false) return 'order';

	    if (preg_match('/diet|sehat|low calorie/', $message)) return 'diet';

	    if (preg_match('/murah|hemat|budget|cheap/', $message)) return 'cheap';

	    if (preg_match('/minum|drink|coffee|kopi/', $message)) return 'drink';

	    if (preg_match('/makan|makanan|food/', $message)) return 'food';

	    if (preg_match('/rekomendasi|recommend|best|favorit/', $message)) return 'recommend';

	    if (preg_match('/pedas|halal|kalori|ingredient/', $message)) return 'info';

	    if (preg_match('/ada|punya|available/', $message)) return 'search';

	    // 🔥 FIX UTAMA: fallback jadi search
	    if (str_word_count($message) <= 3) {
	        return 'search';
	    }

	    return 'general';
	}
	private function askGemini($message)
	{
		// Gunakan model gemini-1.5-flash sesuai URL cURL Anda yang valid
		$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";

		// PENTING: Jika token AQ.Ab8RN... ini expired, ganti dengan API Key resmi yang diawali "AIzaSy..." dari Google AI Studio
		$apiKey = "Ab8RN6KtMIG81ysF1sjbaHvNT0pR4nOwBBWFuPv_v8qGNmWwQA";

		$data = [
			"contents" => [
				[
					"parts" => [
						["text" => (string)$message]
					]
				]
			]
		];

		$ch = curl_init($url);

		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/json",
				"X-goog-api-key: " . $apiKey // PERBAIKAN: Mengirimkan key lewat Header sesuai perintah cURL Anda
			],
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false
		]);

		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch)) {
			$error_msg = curl_error($ch);
			curl_close($ch);
			return "cURL Error: " . $error_msg;
		}

		curl_close($ch);

		// Buka error dari Google jika HTTP Status bukan 200
		if ($httpCode !== 200) {
			$errResponse = json_decode($response, true);
			if (isset($errResponse['error']['message'])) {
				return "Google API Error (" . $httpCode . "): " . $errResponse['error']['message'];
			}
			return "Gagal dengan HTTP Code: " . $httpCode . ". Respon mentah: " . $response;
		}

		$result = json_decode($response, true);

		return $result['candidates'][0]['content']['parts'][0]['text'] ?? "Format respon tidak sesuai.";
	}
	private function correctMenuName($input)
	{
	    $this->db->select('description');
	    $menus = $this->db->get('sh_m_item')->result();

	    $bestMatch = null;
	    $highestScore = 0;

	    foreach ($menus as $m) {

	        $menuName = strtolower($m->description);

	        similar_text($input, $menuName, $percent);
	        $distance = levenshtein($input, $menuName);

	        $score = $percent - ($distance * 2);

	        if ($score > $highestScore) {
	            $highestScore = $score;
	            $bestMatch = $m->description;
	        }
	    }

	    return ($highestScore > 40) ? $bestMatch : $input;
	}
	private function formatMenu($m, $logo)
	{
	    if (empty($m->image_path)) {
	        $image = base_url('assets/noimage.jpg');
	    }
	    elseif (strpos($m->image_path, 'data:image') !== false) {
	        $image = $m->image_path;
	    }
	    elseif (strpos($m->image_path, 'assets') !== false) {
	        $image = base_url($m->image_path);
	    }
	    else {
	        $image = base_url($logo->image_path);
	    }

	    return [
	        'id'    => $m->id,
	        'name'  => $m->description,
	        'price' => $m->harga_weekend,
	        'image' => $image
	    ];
	}
	private function insertToCart($item_id, $qty, $price)
	{
	    for ($i = 0; $i < $qty; $i++) {
	        $_POST['item_id'] = $item_id;
	        $_POST['qty']     = 1;
	        $_POST['price']   = $price;
	        $_POST['type']    = 'plus';

	        $this->cart_action(); // 🔥 reuse function kamu
	    }
	}

}


