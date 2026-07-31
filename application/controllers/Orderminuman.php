<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orderminuman extends CI_Controller {

	function __construct()
		{
			parent::__construct();
			if($this->session->userdata('username') == ""){
           		$nomeja = $this->session->userdata('nomeja');
  				redirect('index.php/login/logout/'.$nomeja);
        	}
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
	  		// 	redirect('login/log_out/'.$nomeja);
	  		// }
	  		// if($session['status'] == 'Available'){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('index.php/login/log_out/'.$nomeja);
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
			$this->load->view('orderminuman',$data);
		
	}
	public function menuv2($tipe, $sub_category) 
	{
	    $this->session->unset_userdata('notfoundminuman');

	    $id_customer = $this->session->userdata('id');
	    $nomeja      = $this->session->userdata('nomeja');

	    $hariIni      = date('N');
	    $current_hour = date('H');
	    $current_date = date('Y-m-d');

	    // Cek apakah hari ini libur
	    $this->db->where('holiday_date', $current_date);
	    $holiday = $this->db->get('sh_m_holiday')->num_rows() > 0;

	    // Tentukan tipe hari
	    if ($holiday || $hariIni >= 6) {
	        $te = 'WEEKEND';
	    } else {
	        $te = 'WEEKDAY';
	    }

	    // Ambil semua data minuman dari model
	    $items_raw = $this->Item_model->getData($tipe, $sub_category);

	    // Filter item
	    $items_filtered = [];
	    foreach ($items_raw as $item) {
	        $show = true;

	        // Kalau item punya event, lakukan filter
	        if (!empty($item->event_item_code)) {
			    $tanggalBerlaku = false;
			    $typeBerlaku    = false;

			    // 🔹 Cek range tanggal
			    if (!empty($item->date_from) && !empty($item->date_to)) {
			        if ($current_date >= $item->date_from && $current_date <= $item->date_to) {
			            $tanggalBerlaku = true;
			        }
			    }

			    // 🔹 Cek type (WEEKDAY/WEEKEND/EVERYDAY)
			    $itemType = strtoupper(trim($item->type));
			    if ($itemType === 'EVERYDAY' || $itemType === $te) {
			        $typeBerlaku = true;
			    }

			    // 🔹 Jika tanggal berlaku & type tidak berlaku → langsung hide
			    if ($tanggalBerlaku && !$typeBerlaku) {
			        $show = false;
			    }
			    // 🔹 Jika tanggal & type berlaku → cek jam
			    else if ($tanggalBerlaku && $typeBerlaku) {
			        if (!empty($item->time_from) && !empty($item->time_to)) {
			            if ($current_hour < $item->time_from || $current_hour > $item->time_to) {
			                $show = false; // hide kalau di luar jam
			            }
			        }
			    }
			    // else → tanggal tidak berlaku, langsung tampil (tidak cek jam)
			}

	        if ($show) {
	            $items_filtered[] = $item;
	        }
	    }

	    // Ambil semua subcategory minuman dari model
	    $subcategories_raw = $this->Item_model->sub_category_minuman();

	    // Filter subcategory berdasarkan weekday/weekend & jam
	    $subcategories_filtered = [];
	    $today = date('l');
	    foreach ($subcategories_raw as $sub) {
	        $show = true;

	        // Cek weekday/weekend
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

	    // Tambahkan Signature jika ada chef_recommended
	    $signature_exists = false;
	    // foreach ($subcategories_filtered as $sf) {
	    //     if (strtolower($sf['sub_category']) === 'signature') {
	    //         $signature_exists = true;
	    //         break;
	    //     }
	    // }

	    // if (!$signature_exists) {
	    //     $has_signature_item = $this->db
	    //         ->where('is_active', 1)
	    //         ->where('chef_recommended', 1)
	    //         ->count_all_results('sh_m_item');

	    //     if ($has_signature_item > 0) {
	    //         $subcategories_filtered[] = [
	    //             'sub_category' => 'Signature',
	    //             'id'           => 0,
	    //             'weekday'      => '',
	    //             'weekend'      => '',
	    //             'time_from'    => '',
	    //             'time_to'      => ''
	    //         ];
	    //     }
	    // }

	    // Pastikan Signature di urutan awal
	    usort($subcategories_filtered, function ($a, $b) {
	        if ($a['sub_category'] === 'Signature') return -1;
	        if ($b['sub_category'] === 'Signature') return 1;
	        if ($a['id'] == $b['id']) return 0;
	        return ($a['id'] < $b['id']) ? -1 : 1;
	    });

	    // Data untuk view
	    $data['item']       = $items_filtered;
	    $data['sub']        = $subcategories_filtered;
	    $data['s']          = $sub_category;
	    $data['ic']         = $id_customer;
	    $data['key']        = '';
	    $data['cart_count'] = $this->Item_model->hitungcart($nomeja);
	    $data['nomeja']     = $nomeja;

	    $cart_count = $this->Item_model->cart_count($id_customer, $nomeja)->num_rows();
	    $data['total_qty'] = $cart_count > 0 ? $this->Item_model->cart_count($id_customer, $nomeja)->row()->total_qty : 0;
	    $data['keyword'] = '';
    	$data['offset']  = 0;
	    $data['iconfooter'] = $this->Admin_model->getIcon('footer');
	    $data['cn']         = $this->Admin_model->getColorCN();
	    $data['ch']         = $this->Admin_model->getColorHD();
	    $data['cb']         = $this->Admin_model->getColorBTN();
	    $data['logo']       = $this->Admin_model->getLogo();
	    $trans = $this->db->order_by('create_date', 'DESC')
                  ->get_where('sh_t_transactions', array('id_customer' => $id_customer))
                  ->row();
        if ($trans->parent_id != 0) {
        	$data['cekpay'] = $this->Item_model->getitem($trans->parent_id,'parent');
        }else{
        	$data['cekpay'] = $this->Item_model->getitem($trans->id,'notparent');
        }

	    $this->load->view('orderminuman', $data);
	}
	public function menu($tipe, $sub_category)
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
	    $subcategories_raw = $this->Item_model->sub_category_minuman();

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
	    // $signature_exists = false;
	    // foreach ($subcategories_filtered as $sf) {
	    //     if (strtolower($sf['sub_category']) === 'signature') {
	    //         $signature_exists = true;
	    //         break;
	    //     }
	    // }

	    // if (!$signature_exists) {
	    //     $has_signature_item = $this->db
	    //         ->where('is_active', 1)
	    //         ->where('chef_recommended', 1)
	    //         ->count_all_results('sh_m_item');

	    //     if ($has_signature_item > 0) {
	    //         $subcategories_filtered[] = [
	    //             'sub_category' => 'Signature',
	    //             'id'           => 0,
	    //             'weekday'      => '',
	    //             'weekend'      => '',
	    //             'time_from'    => '',
	    //             'time_to'      => ''
	    //         ];
	    //     }
	    // }

	    // Pastikan Signature tetap di awal
	    // usort($subcategories_filtered, function ($a, $b) {
	    //     if ($a['sub_category'] === 'Signature') return -1;
	    //     if ($b['sub_category'] === 'Signature') return 1;
	    //     if ($a['id'] == $b['id']) return 0;
	    //     return ($a['id'] < $b['id']) ? -1 : 1;
	    // });
	    $cartItems = $this->db
	    ->select('item_code, qty,unit_price')
	    ->where([
	        'id_customer'   => $this->session->userdata('id'),
	        'id_table'      => $nomeja,
	        'user_order_id' => $this->session->userdata('user_order_id'),
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
        $data['settings'] = $this->Admin_model->getLogo();

	    $this->load->view('orderminumanv3', $data);
	}
	public function searchOLD()
	{
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
	    $items_raw = $this->Item_model->get_keyword_minuman($keyword);

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
	    $data['sub']        = $this->Item_model->sub_category_minuman();
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

	    $this->load->view('orderminuman', $data);
	}
	public function search()
	{
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
	    $items_raw = $this->Item_model->get_keyword_minuman($keyword, 10, 0);
	    $cartItems = $this->db
	    ->select('item_code, qty,unit_price')
	    ->where([
	        'id_customer'   => $this->session->userdata('id'),
	        'id_table'      => $nomeja,
	        'user_order_id' => $this->session->userdata('user_order_id'),
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
	    $data['sub']        = $this->Item_model->sub_category_minuman();
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
        $data['settings'] = $this->Admin_model->getLogo();
	    $this->load->view('orderminumanv3', $data);
	}

	public function load_more_search()
	{
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
		                <a href="'.base_url('index.php/orderminuman/detailmenu/'.$i->id.'/'.str_replace(' ','%20',$i->sub_category)).'"
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
	


	public function menuOLD($tipe,$sub_category)
	{
		$this->session->unset_userdata('notfoundminuman');
		$id_customer = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$data['item'] = $this->Item_model->getData($tipe,$sub_category);
		$data['sub'] = $this->Item_model->sub_category_minuman();
		$data['s'] = $sub_category;
		$data['key'] = '';
		$data['cart_count'] = $this->Item_model->hitungcart($nomeja);
		$data['nomeja'] = $this->session->userdata('nomeja');
		$data['logo'] = $this->Admin_model->getLogo();
		$cart_count = $this->Item_model->cart_count($id_customer,$nomeja)->num_rows();
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
			$this->load->view('orderminuman',$data);
		
	}
	public function detailmenu($id,$sub)
	{
		$sharp = str_replace("%20","_", $sub);
		$url = $sub.'#'.$sharp;
		$item = $this->Item_model->getDatabyID($id);
		$addon = $this->Item_model->getAddOn($item->no);
		$option = $this->Item_model->getOption($item->no);
		$nomeja = $this->session->userdata('nomeja');
		$link = 'index.php/orderminuman/menu/Minuman/'.$url;
		$linkform = 'index.php/Orderminuman/add_cart/'.$url;
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
	public function menuminuman($tipe,$sub_category)
	{
		$id_customer = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$data['item'] = $this->Item_model->getData($tipe,$sub_category);
		$data['sub'] = $this->Item_model->sub_category();
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
			$this->load->view('menu/minuman',$data);
		
	}
	
	public function add_cart($sub)
	{
	    $optionsReq = $this->input->post('options');
	    $op = is_array($optionsReq) ? htmlspecialchars(implode(',', $optionsReq)) : htmlspecialchars($optionsReq);

	    $addons = $this->input->post('addons') ?? [];
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

	    $sharp = str_replace("%20", "_", $sub);
	    $url   = $sub . '#' . $sharp;

	    $no      = $this->input->post('no');
	    $nama    = $this->input->post('nama');
	    $harga   = $this->input->post('unit_price');
	    $qty     = (int)$this->input->post('qty');
	    $up      = $this->input->post('unit_price_disc');
	    $disc    = $this->input->post('disc');
	    $pesan   = $this->input->post('notes');
	    $id_customer = $this->session->userdata('id');

	    if ($qty <= 0) {
	        $this->session->set_flashdata('error', 'Order Gagal! Tambahkan jumlah pesan!');
	        redirect($_SERVER['HTTP_REFERER']);
	    }

	    $unit_price_disc = $up ?: 0;

	    $id_trans = $this->db
	        ->get_where('sh_t_transactions', ['id_customer' => $id_customer])
	        ->row();

	    $cabang = $this->db
	        ->order_by('id', 'desc')
	        ->limit(1)
	        ->get('sh_m_cabang')
	        ->row('id');

	    // CEK ITEM SAMA DI CART (BERDASARKAN item_code + options)
	    $cartSameOption = $this->db
	        ->where([
	            'item_code'    => $no,
	            'id_customer'  => $id_customer,
	            'user_order_id'=> $this->session->userdata('user_order_id'),
	            'options'      => $op
	        ])
	        ->get('sh_cart')
	        ->row();

	    $data = [
	        'item_code'        => $no,
	        'id_trans'         => $id_trans->id,
	        'id_customer'      => $id_customer,
	        'qty'              => $qty,
	        'cabang'           => $cabang,
	        'unit_price'       => $harga,
	        'description'      => $nama,
	        'entry_by'         => $this->session->userdata('username'),
	        'id_table'         => $this->session->userdata('nomeja'),
	        'extra_notes'      => $pesan,
	        'entry_date'       => date('Y-m-d'),
	        'user_order_id'    => $this->session->userdata('user_order_id'),
	        'options'          => $op,
	        'unit_price_disc'  => $unit_price_disc,
	        'disc'             => $disc,
	        'addons'           => 0
	    ];

	    if ($cartSameOption) {
	        // OPTIONS SAMA → UPDATE QTY
	        $this->db->where('id', $cartSameOption->id);
	        $result = $this->db->update('sh_cart', [
	            'qty' => $cartSameOption->qty + $qty
	        ]);
	    } else {
	        // OPTIONS BEDA → INSERT BARU
	        $result = $this->db->insert('sh_cart', $data);
	    }

	    if ($result) {
	        $this->session->set_flashdata('success', 'Menu Added to Cart');
	        redirect('index.php/orderminuman/menu/Minuman/' . $url);
	    }
	}
	public function addcart()
	{
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
	// if ($data == NULL) {
	// 	$this->session->set_flashdata('error','Silahkan Pilih Minuman Yang Akan Di Pesan!');
	// 			redirect($_SERVER['HTTP_REFERER']);
	// }else{
	// $result = $this->db->insert_batch('sh_cart',$data);
	// 		if ($result) {
	// 			$this->session->set_flashdata('success','Order Menu/Paket Berhasil Di Tambahkan Ke Dalam Cart');
	// 			redirect($_SERVER['HTTP_REFERER']);
	// 			// $where = array('qty' => 0);
	// 			// $this->Item_model->hapus_qty($where,'testing');
	// 		}else{
	// 			echo "gagal order";
	// 		}
	// }
	}
	public function updatecart($id){
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
	public function subcreate()
	{
		$uc = $this->session->userdata('username');
		$data['total'] = $this->Item_model->totalSubOrder($uc);
		$data['item'] = $this->Item_model->getDataSubOrder($uc);
		$data['no_meja'] = $this->session->userdata('nomeja');
		
		$this->load->view('orderminuman_view',$data);

	}
	public function batal()
	{
		$ic = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$this->db->where('id_customer',$ic);
    	$this->db->delete('sh_t_sub_transactions');
    	redirect('index.php/orderminuman/menu/Minuman/Cold Drink/'.$nomeja);
	}
	public function create()
	{
		$uc = $this->session->userdata('username');
		$ic = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$qty = $this->input->post('qty');
		$nama = $this->input->post('nama');
		$pesan = $this->input->post('pesan');
		$harga = $this->input->post('harga');
		$item_code = $this->input->post('no');
		
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
				redirect('index.php/orderminuman/subcreate/'.$nomeja);
				// $where = array('qty' => 0);
				// $this->Item_model->hapus_qty($where,'testing');
			}else{
				echo "gagal order";
			}

		
	}
	public function order()
	{
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
		$nomer = 1;
		for ($i = 0; $i < count($qty); $i++) {
			if ($qty[$i] != 0) {
				$n = $nomer++ . "<br>"; 
				$data[] = [
				'id_trans' => $id_trans->id,
				'item_code' => $item_code[$i],
				'qty' => $qty[$i],
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
	
}
