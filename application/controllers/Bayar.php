<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Xendit\Xendit;

class Bayar extends CI_Controller {
    function __construct()
        {
            parent::__construct();
            // if($this->session->userdata('username') == ""){
   //               $nomeja = $this->session->userdata('nomeja');
    //          redirect('login/logout/'.$nomeja);
   //       }
            $this->load->model('Item_model');
            $this->load->model('cekstatus_model');
            $this->load->helper('cookie');
            $session = $this->cekstatus_model->cek();

        // if ($session['status'] == 'Payment') {
        //  $nomeja = $this->session->userdata('nomeja');
        //  redirect('login/logout/'.$nomeja);
        // }else if($session['status'] == 'Cleaning'){
        //  $nomeja = $this->session->userdata('nomeja');
        //  redirect('login/logout/'.$nomeja);
        // }
        // if($session['id_table'] != $this->session->userdata('nomeja')){
        //  $nomeja = $this->session->userdata('nomeja');
        //  redirect('login/log_out/'.$nomeja);
        // }
            
        }

    public function index()
    {
        $this->load->view('bayar');
    }
    public function sukses($nomeja,$cek=NULL,$sub=NULL,$no=NULL)
    {
        $id_customer = $this->session->userdata('id');
        $notrans = $this->db->order_by('id',"desc")->where('id_customer',$id_customer)
                    ->limit(1)
                    ->get('sh_t_transactions')
                    ->row('id');
        $cabang = $this->db->order_by('id',"desc")
                ->limit(1)
                ->get('sh_m_cabang')
                ->row('id');
        $item = $this->Item_model->order_bill_line($cabang,$notrans);
        $datatbl = ['status' => 'Payment'];
                $this->db->where('id_customer',$id_customer);
                $this->db->update('sh_rel_table',$datatbl);
        $update_data = array();
        foreach ($item as $i) {
            $update_data[] = array(
                'id' => $i->id,
                'is_paid' => 1
            );
        }
        $update = $this->db->update_batch('sh_t_transaction_details', $update_data, 'id');
        if ($update) {
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
                $log = 'index.php/selforder/home/'.$nomeja.'/'.$cek.'/'.$sub;
            }
            $id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
            $cabang = $this->db->order_by('id',"desc")
                    ->limit(1)
                    ->get('sh_m_cabang')
                    ->row('id');
            $ip_address = $this->input->ip_address();
            // $dataevent = [
            //     'event_type' => 'Akses cart SO',
            //     'cabang' => $cabang,
            //     'id_trans' => $id_trans->id,
            //     'id_customer' => $this->session->userdata('id'),
            //     'event_date' => date('Y-m-d H:i:s'),
            //     'user_by' => $this->session->userdata('username'),
            //     'description' => 'Membuka halaman cart dengan IP: '.$ip_address,
            //     'created_date' => date('Y-m-d'),
            // ];
            // $result = $this->db->insert('sh_event_log',$dataevent);

            $data['log'] = $log;
            $data['cek'] = $cek;
            $data['sub'] = $sub;
            $data['no'] = $no;
            $this->session->set_flashdata('success','You have successfully processed the payment');
            redirect($log);
        }else{
            echo "GAGAL";
        }
        
    }
    public function gagal($nomeja,$cek=NULL,$sub=NULL,$no=NULL)
    {
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
                $log = 'index.php/selforder/home/'.$nomeja.'/'.$cek.'/'.$sub;
            }
            $id_trans = $this->db->get_Where('sh_t_transactions', array('id_customer'=> $id_customer))->row();
            $cabang = $this->db->order_by('id',"desc")
                    ->limit(1)
                    ->get('sh_m_cabang')
                    ->row('id');
            $ip_address = $this->input->ip_address();
            // $dataevent = [
            //     'event_type' => 'Akses cart SO',
            //     'cabang' => $cabang,
            //     'id_trans' => $id_trans->id,
            //     'id_customer' => $this->session->userdata('id'),
            //     'event_date' => date('Y-m-d H:i:s'),
            //     'user_by' => $this->session->userdata('username'),
            //     'description' => 'Membuka halaman cart dengan IP: '.$ip_address,
            //     'created_date' => date('Y-m-d'),
            // ];
            // $result = $this->db->insert('sh_event_log',$dataevent);

            $data['log'] = $log;
            $data['cek'] = $cek;
            $data['sub'] = $sub;
            $data['no'] = $no;
            $this->session->set_flashdata('error','Your payment process has failed.');
            redirect($log);
        
    }

    public function submit()
    {
        $id_customer = $this->session->userdata('id');
        $nomeja = $this->session->userdata('nomeja');
        $cabang = $this->db->order_by('id',"desc")
                    ->limit(1)
                    ->get('sh_m_cabang')
                    ->row('id');
        $notrans = $this->db->order_by('id',"desc")->where('id_customer',$id_customer)
                    ->limit(1)
                    ->get('sh_t_transactions')
                    ->row('id');
        $tgl = date('ymd');
        $kode = "SH".$cabang.$notrans.$tgl;
        $extId = $kode;
        $description = "Pembayaran SO";
        $amount = $this->input->post("amount");
        

        // Ganti dengan kunci API Xendit Anda
        Xendit::setApiKey('xnd_development_MHFonfxW3xEdU1wQTfaMT8epmrJgdZqq0OSO47d91B1CO8LflPMc1cmF6KhphW');

        $params = [
            "external_id" => $extId,
            "description" => $description,
            "amount" => $amount,

            "success_redirect_url"=> "http://localhost:8080/SOxendit/index.php/Bayar/sukses/".$nomeja,

            // redirect url if the payment is failed
            "failure_redirect_url"=> "http://localhost:8080/SOxendit/index.php/Bayar/gagal/".$nomeja,
        ];
        // var_dump($amount);exit();

        try {
            $invoice = \Xendit\Invoice::create($params);
            
            redirect($invoice['invoice_url']);
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            echo 'Error: ' . $e->getMessage();
        }
    }
    public function success()
    {
        echo "Pembayaran Berhasil";
    }

    public function failure()
    {
        echo "Pembayaran Gagal";
    }
}
