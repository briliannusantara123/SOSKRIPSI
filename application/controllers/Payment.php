<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'form'));
        $this->load->model('Item_model');
    }

    public function index()
    {
        $data = array(
            'payment' => null,
            'error' => null,
            'va_number' => ''
        );

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $va_number = trim($this->input->post('va_number', TRUE));
            $data['va_number'] = $va_number;

            if (!empty($va_number)) {
                $payment = $this->db
                    ->where('va_number', $va_number)
                    ->get('sh_payment_va')
                    ->row();

                if ($payment) {
                    $data['payment'] = $payment;
                } else {
                    $data['error'] = 'Nomor VA tidak ditemukan di database.';
                }
            } else {
                $data['error'] = 'Silakan masukkan nomor VA.';
            }
        }

        $this->load->view('payment_bca_va', $data);
    }

    public function submit()
    {
        $this->index();
    }
}
