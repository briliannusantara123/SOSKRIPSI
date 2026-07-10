<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Xendit\Token;
class Xendit_payment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load library Xendit
        $this->load->library('xendit_library');

    }

    public function index() {
        // Halaman pembayaran
        $data['title'] = 'Pembayaran dengan Xendit';
        // Tampilkan halaman pembayaran
        $this->load->view('payment_page', $data);
        // $this->load->view('xendit_form', $data);
    }
    public function create_promotion() {
        $apiKey = 'xnd_development_MHFonfxW3xEdU1wQTfaMT8epmrJgdZqq0OSO47d91B1CO8LflPMc1cmF6KhphW';
        $encodedApiKey = base64_encode($apiKey . ':');

        $data = array(
            "reference_id" => "BRI_20_JAN",
            "description" => "20% discount applied for all BRI cards",
            "bin_list" => array(
                "400000",
                "460000"
            ),
            "discount_percent" => 20,
            "channel_code" => "BRI",
            "currency" => "IDR",
            "min_original_amount" => 25000,
            "max_discount_amount" => 5000,
            "start_time" => "2024-03-25T00:00:00.000Z", // Waktu mulai promosi dalam format ISO 8601
            "end_time" => "2024-06-25T00:00:00.000Z"   // Waktu berakhir promosi dalam format ISO 8601
        );

        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.xendit.co/promotions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . $encodedApiKey,
        ));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Process response
        if ($http_code == 200) {
            echo "Promotion created successfully!";
        } else {
            echo "Failed to create promotion. HTTP Code: " . $http_code . "\n";
            echo "Response: " . $response;
        }


    }
    public function cekbank($value='')
    {
        $this->load->view('cekbank');
    }

    public function api($value='')
    {
        $apiKey = 'xnd_development_MHFonfxW3xEdU1wQTfaMT8epmrJgdZqq0OSO47d91B1CO8LflPMc1cmF6KhphW';
        $encodedApiKey = base64_encode($apiKey . ':');
        echo $encodedApiKey;exit();
    }       
    public function process_payment() {
        header('Content-Type: application/json');

        $inputData = json_decode(trim(file_get_contents('php://input')), true);

        if (!isset($inputData['token_id']) || !isset($inputData['external_id']) || 
            !isset($inputData['amount']) || !isset($inputData['payer_email']) || 
            !isset($inputData['description'])) {
            echo json_encode(['error_code' => 'VALIDATION_ERROR', 'message' => 'Missing required fields']);
            return;
        }

        $apiKey = 'xnd_development_MHFonfxW3xEdU1wQTfaMT8epmrJgdZqq0OSO47d91B1CO8LflPMc1cmF6KhphW';
        $encodedApiKey = base64_encode($apiKey . ':');

        $paymentData = [
            'token_id' => $inputData['token_id'],
            'external_id' => $inputData['external_id'],
            'amount' => (int)$inputData['amount'],
            'authorized_amount'=> 0,
            'capture' => true,
            'descriptor' => 'My new store',
            'type' => 'CREDIT',
            'business_id' => '5850e55d8d9791bd40096364',
            'description' => $inputData['description'],
            'currency' => 'IDR',
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.xendit.co/credit_card_charges');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . $encodedApiKey,
        ));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo json_encode(['error_code' => 'CURL_ERROR', 'message' => curl_error($ch)]);
        } else {
            $responseData = json_decode($response, true);

            if (isset($responseData['id'])) {
                // Pembayaran berhasil
                echo json_encode(['success' => true, 'data' => $responseData]);
            } elseif (isset($responseData['payer_authentication_url'])) {
                // Pembayaran memerlukan otentikasi 3DS
                echo json_encode([
                    'authentication_url' => $responseData['payer_authentication_url'],
                    'authentication_id' => $responseData['authentication_id'],
                    'token_id' => $inputData['token_id']
                ]);
            } else {
                // Terjadi kesalahan saat pembayaran
                echo json_encode(['error_code' => $responseData['error_code'], 'message' => $responseData['message']]);
            }
        }

        curl_close($ch);
        }


    public function complete_payment() {
    header('Content-Type: application/json');

    $inputData = json_decode(trim(file_get_contents('php://input')), true);

    if (!isset($inputData['token_id']) || !isset($inputData['authentication_id']) || 
        !isset($inputData['external_id']) || !isset($inputData['amount'])) {
        echo json_encode(['error_code' => 'VALIDATION_ERROR', 'message' => 'Missing required fields']);
        return;
    }

    $apiKey = 'xnd_development_MHFonfxW3xEdU1wQTfaMT8epmrJgdZqq0OSO47d91B1CO8LflPMc1cmF6KhphW';
    $encodedApiKey = base64_encode($apiKey . ':');

    $paymentData = [
        'token_id' => $inputData['token_id'],
        'amount' => (int)$inputData['amount'],
        'external_id' => $inputData['external_id'],
        'authentication_id' => $inputData['authentication_id'],
        'capture' => true
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.xendit.co/v2/credit_card_charges');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Basic ' . $encodedApiKey
    ));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(['error_code' => 'CURL_ERROR', 'message' => curl_error($ch)]);
    } else {
        $responseData = json_decode($response, true);

        if (isset($responseData['error_code'])) {
            echo json_encode(['error_code' => $responseData['error_code'], 'message' => $responseData['message']]);
        } else {
            echo json_encode(['success' => true, 'data' => $responseData]);
        }
    }

    curl_close($ch);
}


    // Proses pembayaran langsung menggunakan Xendit API
public function process_payment_direct() {

    $cardData = [
    'card_number' => '4111111111111111',
    'card_exp_month' => '12',
    'card_exp_year' => '2024',
    'card_cvn' => '123',
    'is_single_use' => true // Opsional: Tentukan apakah token hanya bisa digunakan sekali
];

$apiKey = 'xnd_development_MHFonfxW3xEdU1wQTfaMT8epmrJgdZqq0OSO47d91B1CO8LflPMc1cmF6KhphW'; // Ganti dengan kunci API Xendit Anda

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.xendit.co/v2/credit_card_tokens');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($cardData));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($apiKey . ':')
));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    $tokenData = json_decode($response, true);
    var_dump($tokenData);
}

curl_close($ch);
}


    public function check_payment_status($invoice_id) {
        // Cek status pembayaran
        $response = $this->xendit_library->check_invoice_status($invoice_id);

        // Tampilkan respon status pembayaran
        echo json_encode($response);
    }
}
?>
