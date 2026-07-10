<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Xendit\Xendit;

class Invoice extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('invoice');
	}

	public function sukses($nomeja,$cek=NULL,$sub=NULL,$no=NULL)
	{

		$id_customer = $this->session->userdata('id');
		$cart = $this->Item_model->cart_count($id_customer,$nomeja)->row();//tambahan	
		if ($cart) {
			$cart_total = $cart->total_qty;
			$price = $cart->total_harga;
		}else{
			$cart_total = 0;
			$price = 0;
		}
		// $data['total'] = $this->Item_model->totalSubOrder($uc);
		$data['item'] = $this->Item_model->cart($id_customer)->result();
		$data['nomeja'] = $nomeja;
		$data['price'] = $price;
		
		if ($cek == 'Makanan') {
			$log = 'index.php/ordermakanan/menu/Makanan/'.$sub.'#'.preg_replace('/%20/', '_', $sub);;
		}elseif ($cek == 'Minuman') {
			$log = 'index.php/orderminuman/menu/Minuman/'.$sub.'#'.preg_replace('/%20/', '_', $sub);
		}else{
			$log = 'index.php/selforder/home/'.$nomeja;
		}
		$id_customer = $this->session->userdata('id');
		$id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
		$cabang = $this->db->order_by('id',"desc")
			  	->limit(1)
			  	->get('sh_m_cabang')
			  	->row('outlet_id');
		$ip_address = $this->input->ip_address();
		$dataevent = [
			'event_type' => 'Akses cart SO',
			'cabang' => $cabang,
			'id_trans' => $id_trans->id,
			'id_customer' => $this->session->userdata('id'),
			'event_date' => date('Y-m-d H:i:s'),
			'user_by' => $this->session->userdata('username'),
			'description' => 'Membuka halaman cart dengan IP: '.$ip_address,
			'created_date' => date('Y-m-d'),
		];
		$result = $this->db->insert('sh_event_log',$dataevent);

		$data['log'] = $log;
		$data['cek'] = $cek;
		$data['sub'] = $sub;
		$data['no'] = $no;
		$this->load->view('invoice_sukses',$data);
	}
	public function submit()
	{
		$extId = $this->input->post("external_id");
		$email = $this->input->post("payer_email");
		$description = $this->input->post("description");
		$amount = $this->input->post("amount");

		// https://github.com/xendit/xendit-php/blob/master/examples/InvoiceExample.php
		Xendit::setApiKey('');

		$params = [
			"external_id" => $extId,
			"payer_email" => $email,
			"description" => $description,
			"amount" => $amount,

			// redirect url if the payment is successful
			"success_redirect_url"=> "http://localhost:8080/SOxendit/index.php/Invoice/sukses/1",

			// redirect url if the payment is failed
			"failure_redirect_url"=> "http://localhost:8080/SOxendit/index.php/Invoice",
		];

		$invoice = \Xendit\Invoice::create($params);

		// this will automatically redirect to invoice url
		header("Location: ".$invoice["invoice_url"]);		
	}

	public function success()
	{
		echo("success");
	}

	public function failure()
	{
		echo("failure");
	}
}
