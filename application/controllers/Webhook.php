<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller {

    public function index()
{
    $raw  = file_get_contents('php://input');
    $data = json_decode($raw, true);

    // LOG
    file_put_contents(
        APPPATH.'logs/xendit_fva.log',
        date('Y-m-d H:i:s').' '.$raw.PHP_EOL,
        FILE_APPEND
    );

    // VALIDASI PAYLOAD FIXED VA
    if (
        isset($data['external_id']) &&
        isset($data['amount']) &&
        isset($data['account_number'])
    ) {
        $this->db->where('external_id', $data['external_id'])
                 ->update('sh_payment_va', [
                     'status'      => 'PAID',
                     'paid_amount'=> $data['amount'],
                     'va_number'  => $data['account_number'],
                     'bank'  => $data['bank_code'] ?? null,
                     'paid_at'    => date(
                         'Y-m-d H:i:s',
                         strtotime($data['transaction_timestamp'] ?? 'now')
                     )
                 ]);
        
    }

    http_response_code(200);
    echo 'OK';
}

}
