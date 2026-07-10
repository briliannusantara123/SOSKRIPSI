<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orderpaket extends CI_Controller {
function __construct()
		{
			parent::__construct();
			
			$this->load->model('Item_model');
			$this->load->model('Paket_model');
			$this->load->model('Admin_model');
			$this->load->model('cekstatus_model');
			$this->load->helper('cookie');
			$session = $this->cekstatus_model->cek();
			if($this->session->userdata('username') == ""){
           		$nomeja = $this->session->userdata('nomeja');
  				redirect('index.php/login/logout/'.$nomeja);
        	}
        	// if ($session['status'] == 'Payment') {
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('login/logout/'.$nomeja);
	  		// }else 
	  		// if($session['status'] == 'Cleaning'){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('index.php/login/logout/'.$nomeja);
	  		// }
	  		if($session['id_table'] != $this->session->userdata('nomeja')){
	  			$nomeja = $this->session->userdata('nomeja');
	  			redirect('index.php/login/log_out/'.$nomeja);
	  		}

		}
	public function index()
	{
		// $id_customer = $this->session->userdata('id');
		// $data['item'] = $this->Item_model->getDataOrder($id_customer)->result();
		$id_customer = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		//$data['option'] = $this->Item_model->option();
		$data['pc'] = '';
		$data['subp'] = '';
		$data['cekpaket'] = 'ada';
		$data['sub'] = $this->Item_model->sub_category();
		$data['ic'] = $id_customer;
		$data['key'] = '';
		$data['cart_count'] = $this->Item_model->hitungcart($nomeja);
		$data['paket'] = $this->Paket_model->getpaketso();
		$data['nomeja'] = $this->session->userdata('nomeja');
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
		$data['logo'] = $this->Admin_model->getLogo();

		
		$this->load->view('orderpaket',$data);
	}
	public function home($pc=NULL,$subp=NULL,$id=NULL,$menu=NULL)
	{

		if ($id) {
			$cekitem = $this->Paket_model->cekDataPaket($pc);
			$isSoldOut = false;
		    foreach ($cekitem as $ci) {
		        if ($ci['jml'] == $ci['sold_out']) {
		            $isSoldOut = true;
		            break; // Keluar dari loop jika item sold out
		        }
		    }
		    $this->db->select('*');
			$this->db->from('sh_cart a');
			$this->db->join('sh_cart_details b', 'a.id = b.id_cart');
			$this->db->where('b.id', $id);
			$cart = $this->db->get()->row();

			$this->db->select('*');
			$this->db->from('sh_m_item');
			$this->db->where('no', $pc);
			$namapaket = $this->db->get();
			$np = $namapaket->row();

			$this->db->select('*');
			$this->db->from('sh_m_item');
			$this->db->where('parent_id', $pc);
			$this->db->where('is_sold_out', 1);
			$cekdatapaket = $this->db->get();
			$result = $cekdatapaket->row();
			if ($isSoldOut) {
				$data['pc'] = '';
				$data['subp'] = '';
				$dataupdate = [
			        'is_sold_out' => 1
			    ];
			    $this->db->where('no', $np->no);
			    $update = $this->db->update('sh_m_item', $dataupdate);
				$this->db->where('id_cart',$cart->id_cart);
	    		$this->db->delete('sh_cart_details');
	    		$this->db->where('id',$cart->id_cart);
	    		$this->db->delete('sh_cart');
	    		$data['cekpaket'] = 'habis';
				$this->session->set_flashdata('error',"Unfortunately, you cannot order the ".$np->description." package because the ".$result->description." is sold out.");
			}else{
				$data['cekpaket'] = 'habis';
				$data['pc'] = $pc;
				$this->db->where('id_cart',$cart->id_cart);
	    		$this->db->delete('sh_cart_details');
	    		$this->db->where('id',$cart->id_cart);
	    		$this->db->delete('sh_cart');
				$this->session->set_flashdata('error',"Unfortunately, you cannot order the ".$np->description." package because the ".$result->description." is sold out.");
			}
	    	
		}

		$id_customer = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');

		$data['paketdata'] = $this->Item_model->getPaket($pc,$subp);
		
		$data['sub'] = $this->Item_model->sub_category();
		$data['ic'] = $id_customer;
		$data['key'] = '';
		$data['cart_count'] = $this->Item_model->hitungcart($nomeja);
		$data['paket'] = $this->Paket_model->getpaketso();
		$data['nomeja'] = $this->session->userdata('nomeja');
		$cart_count = $this->Item_model->cart_count($id_customer,$nomeja)->num_rows();
		if($cart_count > 0){
			$cart = $this->Item_model->cart_count($id_customer,$nomeja)->row();//tambahan	
			$cart_total = $cart->total_qty;
		}else{
			$cart_total = 0;
		}
		$data['total_qty'] = $cart_total;
		
		$this->load->view('orderpaket',$data);
	}
	public function getsub()
	{
		$item_code = $this->input->post('item_code');
		$getsub = $this->Paket_model->sub_category_paket($item_code);
		echo json_encode($getsub);
	}
	public function menupaket($paket,$sc)
	{
		$sub_category = str_replace('_', ' ', urldecode($sc));
		$id_customer = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$data['item'] = $this->Paket_model->getDataPaket($paket,$sub_category);
		$data['code_item'] = $this->Paket_model->getCodeItem($paket,$sub_category);
		$data['code'] = $this->Paket_model->getCode($paket,$sub_category);

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
		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$data['logo'] = $this->Admin_model->getLogo();

			$this->load->view('menu/paket',$data);
		
	}
	public function orderqty()
	{
		$table = $this->session->userdata('nomeja');
		$uc = $this->session->userdata('username');
		$ic = $this->session->userdata('id');
		$post = $this->input->post();
		$trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $ic))->row();
		if($post['tipe']=='plus' && $post['item_code'] != ''){
			
			$cek_count = $this->Paket_model->get_cart_details($ic,$table,$post['itemcode'],$post['uoi'])->num_rows();

			if($cek_count > 0){
				$cek_cart = $this->Paket_model->get_cart_details($ic,$table,$post['itemcode'],$post['uoi'])->row();
				$update_data = array(
					        'qty' =>$post['qtypaket'],
					    );
					    $this->db->where('id', $cek_cart->id_cart);
					    $update = $this->db->update('sh_cart', $update_data);
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
						
						
					];
				}else{
					$data = [
						'qty' => $aqty,
						
						
					];	
				}
				
				$this->Item_model->save('sh_cart_details',$data, ['item_code'=>$cek_cart->item_code,'user_order_id'=>$cek_cart->user_order_id,'left(entry_date,10)' => date('Y-m-d') ]);
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
					'qty' => $post['qtypaket'],
					'cabang' => $trans->cabang,
					'unit_price' => $post['hargapaket'],
					'description' => $post['paket'],
					'entry_by' => $this->session->userdata('username'),
					'id_table' => $table,
					'extra_notes' => $post['extra_notes'],
					'user_order_id' => $this->session->userdata('user_order_id'),
					'is_paket' => 1,
					];
					$query = $this->db->get_where('sh_cart', array('item_code' => $post['item_code'],'id_customer' => $ic,'id_table' => $table,'user_order_id' => $post['uoi'],'left(entry_date,10)' => date('Y-m-d')));
			        $hqty = $query->row();
			        if (!$hqty) {
			        	$cart_id = $this->Item_model->save('sh_cart',$data);
			        }else{
			        	
			        	$where = "LEFT(entry_date, 10) = '" . date('Y-m-d') . "' AND id_customer = '" . $ic . "' AND paket_code = '" . $post['item_code'] . "' AND user_order_id = '" . $this->session->userdata('user_order_id') . "'";
						$this->db->from('sh_cart_details');
						$this->db->where($where);
						$this->db->limit(1); // Batasan hasil menjadi satu baris
						$this->db->order_by('id', 'asc'); // Mengurutkan berdasarkan id secara ascending
						$query = $this->db->get();
						$idCart = $query->row('id_cart');
						// Mendapatkan satu baris hasil
						$cart_id = $idCart;
						
						
			        }
			        
					$datadetails = [
					'id_cart' => $cart_id,
					'paket_code' => $post['item_code'],
					'sub_category' => $post['subklik'], 
					'item_code' => $post['itemcode'],
					'qty' => 1,
					'description' => $post['description'],
					'extra_notes' => $post['extra_notes'],
					'user_order_id' => $this->session->userdata('user_order_id'),
					'id_table' => $table,
					'id_customer' => $ic,
					'id_trans' => $trans->id,
					];
					$this->Item_model->save('sh_cart_details',$datadetails);
					
					
				
				$cart_count = $this->Item_model->hitungcart($table);

				$carts = $this->Item_model->cart_count($ic,$table)->num_rows();
				if($carts > 0){
					$cart = $this->Item_model->cart_count($ic,$table)->row();	
					$total_qty = $cart->total_qty;
				}else{
					$total_qty = 0;
				}
				
				if($cart_id){
					echo json_encode(array('status'=> True,'new_qty'=> 1,'pesan'=>$pesan,'cart_count'=>(int)$cart_count,'total_qty'=>(int)$total_qty));	
				}

			}
		}else if($post['tipe']=='minus' && $post['itemcode'] != ''){
			$cek_count = $this->Paket_model->get_cart_details($ic,$table,$post['itemcode'],$post['uoi'])->num_rows();
			if($cek_count > 0){
				$cek_cart = $this->Paket_model->get_cart_details($ic,$table,$post['itemcode'],$post['uoi'])->row();
				$cc = $this->Item_model->get_cart($ic,$table,$post['item_code'],$post['uoi'])->row();
		        $this->db->select_sum('qty');
				$this->db->select('id_cart');
				$this->db->where('id_customer', $ic);
				$this->db->where('id_table', $table);
				$this->db->where('user_order_id', $post['uoi']);
				$query = $this->db->get('sh_cart_details');

				$result = $query->row();
				$hqty = $result->qty;

		        if ($hqty == 1) {
		        	$this->db->delete('sh_cart', ['id' => $result->id_cart]);
		        }
		       
				if($cek_cart->qty == 1){
					$this->db->delete('sh_cart_details',['item_code'=>$cek_cart->item_code]);
					$cart_count = $this->Item_model->hitungcart($table);
					$carts = $this->Item_model->cart_count($ic,$table)->num_rows();
					if($carts > 0){
						$cart = $this->Item_model->cart_count($ic,$table)->row();	
						$total_qty = $cart->total_qty;
					}else{
						$total_qty = 0;
					}
					echo json_encode(array('status'=> True,'new_qty'=> 0,'pesan'=>'','cart_count'=>(int)$cart_count,'total_qty'=>(int)$total_qty));
				}else{
					$pesan = $post['extra_notes'];
					$data = [
						'qty' => ($cek_cart->qty-1),
					];
					$this->Item_model->save('sh_cart_details',$data, ['item_code'=>$cek_cart->item_code]);
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
	public function add_cart()
	{
		$id = $this->input->post('id');
		$no = $this->input->post('no'.$id);
		$nama = $this->input->post('nama'.$id);
		$harga = $this->input->post('harga'.$id);
		$qty = $this->input->post('qty'.$id);
		$pesan = $this->input->post('pesan'.$id);
		$uoi = $this->session->userdata('uoi');
		$p = "3 PAHA";
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		// echo $nama;echo $harga;echo $qty;echo $pesan;exit();
		$cekdatacart = $this->Item_model->cekdatacart($no,$uoi)->row();
		$cabang = $this->db->order_by('id',"desc")
				  			->limit(1)
				  			->get('sh_m_cabang')
				  			->row('id');
		$paket = $this->db->order_by('id',"desc")
				  			->limit(1)
				  			->where('no',$this->input->post('paket_code'))
				  			->get('sh_m_item')
				  			->row();
		// var_dump($cekdatacart);exit();
		$data = [
					'item_code' => $this->input->post('paket_code'),
					'id_trans' => $id_trans->id,
					'id_customer' => $id_customer,
					'qty' => $qty,
					'cabang' => $cabang,
					'unit_price' => $paket->harga_weekday,
					'description' => $paket->description,
					'entry_by' => $this->session->userdata('username'),
					'id_table' => $this->session->userdata('nomeja'),
					'extra_notes' => $pesan,
					'user_order_id' => $this->session->userdata('user_order_id'),
					'is_paket' => 1,
					];
					$query = $this->db->get_where('sh_cart', array('item_code' => $this->input->post('paket_code'),'id_customer' => $id_customer,'id_table' => $this->session->userdata('nomeja'),'user_order_id' => $this->session->userdata('user_order_id'),'left(entry_date,10)' => date('Y-m-d')));
			        $hqty = $query->row();
			        if (!$hqty) {
			        	$cart_id = $this->Item_model->save('sh_cart',$data);
			        }else{
			        	
			        	$where = "LEFT(entry_date, 10) = '" . date('Y-m-d') . "' AND id_customer = '" . $id_customer . "' AND paket_code = '" . $this->input->post('paket_code') . "' AND user_order_id = '" . $this->session->userdata('user_order_id') . "'";
						$this->db->from('sh_cart_details');
						$this->db->where($where);
						$this->db->limit(1); // Batasan hasil menjadi satu baris
						$this->db->order_by('id', 'asc'); // Mengurutkan berdasarkan id secara ascending
						$query = $this->db->get();
						$idCart = $query->row('id_cart');
						// Mendapatkan satu baris hasil
						$cart_id = $idCart;
			        }
		$datadetails = [
				// 'item_code' => $this->input->post('no'.$id),
				// 'id_trans' => $id_trans->id,
				// 'id_customer' => $this->session->userdata('id'),
				// 'qty' => $qty,
				// 'cabang' => $cabang,
				// 'unit_price' => $harga,
				// 'description' => $nama,
				// 'entry_by' => $this->session->userdata('username'),
				// 'id_table' => $this->session->userdata('nomeja'),
				// 'extra_notes' => $pesan,
				// 'entry_date' => date('Y-m-d'),
				// 'user_order_id' => $this->session->userdata('user_order_id'),
					'id_cart' => $cart_id,
					'paket_code' => $this->input->post('paket_code'),
					'sub_category' => $this->input->post('sub'.$id), 
					'item_code' => $this->input->post('no'.$id),
					'qty' => $qty,
					'description' => $nama,
					'extra_notes' => $pesan,
					'user_order_id' => $this->session->userdata('user_order_id'),
					'id_table' => $this->session->userdata('nomeja'),
					'id_customer' => $this->session->userdata('id'),
					'id_trans' =>$id_trans->id,
				
			];
			if ($qty == 0) {
				$this->session->set_flashdata('error','Order Gagal! Tambahkan jumlah pesan!');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
			  if ($cekdatacart) {
			  	if ($cekdatacart->extra_notes == $pesan) {
			  		$date = date('Y-m-d');
				  	$ic = $this->session->userdata('id');

				  	$dataedit = [
				  		'qty'=> $cekdatacart->qty + $qty
				  	];

				  	$where = "left(entry_date,10) ='".$date."' and id_customer = '".$ic."' and item_code = '".$this->input->post('no'.$id)."' and user_order_id = '".$this->session->userdata('user_order_id')."'";
				  	$this->db->where($where);
	    			$result = $this->db->update('sh_cart_details',$dataedit);
			  	}else{
			  		$result = $this->db->insert('sh_cart_details',$datadetails);
			  	}
			  }else{
			  	$result = $this->db->insert('sh_cart_details',$datadetails);
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
						'description' => 'Menambahkan item: '.$this->input->post('nama'.$id).' dengan qty: '.$this->input->post('qty'.$id),
						'created_date' => date('Y-m-d'),
					];
					$result = $this->db->insert('sh_event_log',$dataevent);
			  $this->session->set_flashdata('success','Menu Added to Cart');
				redirect($_SERVER['HTTP_REFERER']);
			}
	}
	public function orderqtyp() 
	{
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

	public function delete_data() {
	    // Get the ID from POST data
	    $nama_paket = $this->input->post('nama_paket');
	    $date = date('Y-m-d');
	    $ic = $this->session->userdata('id');
	    $uoi = $this->session->userdata('user_order_id');

	    // Build the query to select the cart item
	    $this->db->select('*');
		$this->db->from('sh_cart');
		$this->db->where('LEFT(entry_date, 10) =', $date);
		$this->db->where('id_customer', $ic);
		$this->db->where('user_order_id', $uoi);
		$this->db->where('description', $nama_paket);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);

		// Get the cart item
		$cart = $this->db->get()->row();

		$this->db->select('*');
		$this->db->from('sh_cart_details');
		$this->db->where('LEFT(entry_date, 10) =', $date);
		$this->db->where('id_cart', $cart->id);
		$this->db->order_by('id', 'desc');
		$cd = $this->db->get()->result();

		$item_codes = array(); // Initialize an array to store item codes
		$sub_category = array();

		foreach ($cd as $c) {
		    $item_codes[] = $c->item_code; // Store each item code in the array
		    $sub_category[] = $c->sub_category;
		}

		$item_codes_string = implode(',', $item_codes);
		$sub_category_string = implode(',', $sub_category);

		$this->session->set_userdata('itemcodepi', $item_codes_string); // Store the concatenated string in the session
		$this->session->set_userdata('subc', $sub_category_string);
	    $this->session->set_userdata('itemcodepaket', $cart->item_code);

	    if ($cart) {
	        // Delete from sh_cart_details first
	        $this->db->where('id_cart', $cart->id);
	        $this->db->delete('sh_cart_details');

	        // Delete from sh_cart
	        $this->db->where('id', $cart->id);
	        if ($this->db->delete('sh_cart')) {
	            // If deletion is successful, return success response
	            echo json_encode(['status' => 'success']);
	        } else {
	            // If deletion fails, return error response
	            echo json_encode(['status' => 'error']);
	        }
	    } else {
	        // If no cart item is found, return error response
	        echo json_encode(['status' => 'error', 'message' => 'Cart item not found']);
	    }
	}
	public function simpanPaket()
	{
	    $data = json_decode(file_get_contents("php://input"), true);
	    $ic = $this->session->userdata('id');
	    $table = $this->session->userdata('nomeja');
	    $uoi = $this->session->userdata('user_order_id');

	    if (empty($data['code']) || empty($data['qty']) || empty($data['qtypaket']) || empty($data['hargapaket'])) {
	        echo json_encode(["status" => "error", "message" => "Code, qty, qtypaket, atau hargapaket tidak ditemukan"]);
	        return;
	    }

	    $cekitem = $this->Paket_model->cekitempaket($data['code']);
	    $trans = $this->db->get_where('sh_t_transactions', ['id_customer' => $ic])->row();
	    if ($data['qtypaket'] != 0) {
	    	$idCart = $this->db->select('id_cart')
	        ->from('sh_cart_details')
	        ->where([
	            'DATE(entry_date)' => date('Y-m-d'),
	            'id_customer' => $ic,
	            'paket_code' => $cekitem->parent_code,
	            'user_order_id' => $uoi
	        ])
	        ->limit(1)
	        ->get()
	        ->row('id_cart');

		    if (!$idCart) {
		        $cartData = [
		            'item_code' => $cekitem->parent_code,
		            'id_trans' => $trans->id,
		            'id_customer' => $ic,
		            'qty' => intval($data['qtypaket']), // Konversi qty ke integer
		            'cabang' => $trans->cabang,
		            'unit_price' => floatval($data['hargapaket']), // Konversi harga ke float
		            'description' => $data['paket'],
		            'entry_by' => $this->session->userdata('username'),
		            'id_table' => $table,
		            'extra_notes' => '',
		            'user_order_id' => $uoi,
		            'is_paket' => 1,
		        ];
		        $idCart = $this->Item_model->save('sh_cart', $cartData);
		    }

		    // **Cek apakah item sudah ada di sh_cart_details**
		    $cekitemdetails = $this->db->from('sh_cart_details')
		        ->where('item_code', $cekitem->item_code)
		        ->where('id_customer', $ic)
		        ->where('id_table', $table)
		        ->where('user_order_id', $uoi)
		        ->where('DATE(entry_date)', date('Y-m-d')) // Perbaikan pengecekan tanggal
		        ->get()
		        ->row();

		    if ($cekitemdetails) {
		        // Jika item sudah ada, cek apakah qty berbeda
		        if ($cekitemdetails->qty != intval($data['qty'])) {
		            // Update qty jika berbeda
		            $this->db->where('id', $cekitemdetails->id);
		            $simpan = $this->db->update('sh_cart_details', ['qty' => intval($data['qty'])]);
		        } else {
		            // Jika qty sama, tidak perlu update
		            $simpan = true;
		        }
		    } else {
		        // Jika item belum ada, insert data baru
		        $datadetails = [
		            'id_cart' => $idCart,
		            'paket_code' => $cekitem->parent_code,
		            'sub_category' => $cekitem->varian_category,
		            'item_code' => $cekitem->item_code,
		            'qty' => intval($data['qty']), // Pastikan qty berupa integer
		            'description' => $cekitem->description,
		            'extra_notes' => '',
		            'user_order_id' => $uoi,
		            'id_table' => $table,
		            'id_customer' => $ic,
		            'id_trans' => $trans->id
		        ];
		        $simpan = $this->Item_model->save('sh_cart_details', $datadetails);
		        $subcategory = $cekitem->varian_category;
		    }
	    }else{
	    	$simpan = true;
	    }

	    if ($simpan) {
	        echo json_encode(["status" => "success", "message" => "Data berhasil disimpan atau diperbarui","subcategory" => $subcategory,"qty" => intval($data['qty'])]);
	    } else {
	        echo json_encode(["status" => "error", "message" => "Gagal menyimpan atau memperbarui data"]);
	    }
	}




	
}
