<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Xendit\Xendit;

class Xendit_library {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        \Xendit\Xendit::setApiKey('xnd_public_development_fyNHt0R5chuBH1JQncWKVcl3vZpSuYlzvWJYgDakYvlL2uV9kBdrHCOHXmj9WCl');
    }

    public function create_invoice($payment_data) {
        // Buat invoice pembayaran menggunakan Xendit
        $response = \Xendit\Invoice::create($payment_data);

        return $response;
    }

    public function check_invoice_status($invoice_id) {
        // Cek status invoice pembayaran menggunakan Xendit
        $response = \Xendit\Invoice::retrieve($invoice_id);

        return $response;
    }
}
?>
