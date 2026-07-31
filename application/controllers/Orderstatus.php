<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orderstatus extends CI_Controller {
public function __construct() {
			parent::__construct();
			if($this->session->userdata('username') == ""){
           		$nomeja = $this->session->userdata('nomeja');
  				redirect('login/logout/'.$nomeja);
        	}
			$this->load->model('Item_model');
			$this->load->model('Orderstatus_model');
			$this->load->model('Admin_model');
			$this->load->model('cekstatus_model');
			$this->load->helper('cookie');
			$session = $this->cekstatus_model->cek();

	  		// if($session['status'] == 'Cleaning'){
	  		// 	$nomeja = $this->session->userdata('nomeja');
	  		// 	redirect('login/logout/'.$nomeja.'/cleaning');
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
	public function index()
	{
		$cs = $this->session->userdata('id');
		$nomeja = $this->session->userdata('nomeja');
		$id_customer = $this->session->userdata('id');
		
		$cabang = $this->db->order_by('id',"desc")
  			->limit(1)
  			->get('sh_m_cabang')
  			->row('id');
  		$notrans = $this->db->order_by('id',"desc")->where('id_customer',$id_customer)
  			->limit(1)
  			->get('sh_t_transactions')
  			->row('id');


  		// echo $cabang; echo "<br>";echo $notrans;exit();
		$uc = $this->session->userdata('username');
		$cart_count = $this->Item_model->cart_count($id_customer,$nomeja)->num_rows();
		if($cart_count > 0){
			$cart = $this->Item_model->cart_count($id_customer,$nomeja)->row();//tambahan	
			$cart_total = $cart->total_qty;
		}else{
			$cart_total = 0;
		}
		$data['sca'] = $this->Item_model->sub_category_awal();
		$data['scm'] = $this->Item_model->sub_category_minuman_awal();
		$data['sub_category'] = "ayam";
		$data['sub_category_minuman'] = "Cold Drink";
		$data['total_qty'] = $cart_total;
		$data['item'] = $this->Item_model->billsementara($id_customer)->result();
		$data['total'] = $this->Item_model->total($uc);
		$data['nomeja'] = $nomeja;
		$data['notrans'] = $notrans;
		$data['order_waiting'] = $this->Orderstatus_model->cekstatusorder($cabang,$notrans,'awaiting');
		$data['order_complete'] = $this->Orderstatus_model->cekstatusorder($cabang,$notrans,'complete');
		$data['order_deliver'] = $this->Orderstatus_model->cekstatusorder($cabang,$notrans,'deliver');
		$data['order_proses'] = $this->Orderstatus_model->cekstatusorder($cabang,$notrans,'proses');
		$data['iconfooter'] = $this->Admin_model->getIcon('footer');
		$data['cn'] = $this->Admin_model->getColorCN();
		$data['ch'] = $this->Admin_model->getColorHD();
		$data['cb'] = $this->Admin_model->getColorBTN();
		$data['icon'] = $this->Admin_model->getIcon('add');
		$data['logo'] = $this->Admin_model->getLogo();
		// var_dump($this->Orderstatus_model->cekstatusorder($cabang,$notrans,'proses'));exit();
		$this->load->view('orderstatus',$data);
	}
	
	public function landing()
	{
		$data = [
			'username' => $this->session->userdata('username'),
			'nomeja' => $this->session->userdata('nomeja'),
			'cn' => $this->Admin_model->getColorCN(),
			'ch' => $this->Admin_model->getColorHD(),
			'cb' => $this->Admin_model->getColorBTN(),
		];
		$this->load->view('landing',$data);
	}
}
