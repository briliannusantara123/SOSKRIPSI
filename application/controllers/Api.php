<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Item_model');
        $this->load->model('cekstatus_model');
        $this->load->helper('cookie');

        // GANTI encrypt → encryption
        $this->load->library('encryption');

        $session = $this->cekstatus_model->cek();
    }
    public function token()
    {
        $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();
        $token = $this->encryption->decrypt($setup->admin_access_token);
        var_dump($token);exit();
    }
    public function generate_token()
    {
        $query = $this->db->where('id', 1)->get('sh_m_setup')->row();

        if (!$query) {
            echo "Setup tidak ditemukan";
            return;
        }

        $url = $query->admin_login_url;

        $payload = json_encode([
            "username" => $query->admin_login_user,
            "password" => $query->admin_login_password
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30
        ]);

        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($result === false) {
            echo "CURL Error: " . curl_error($curl);
            curl_close($curl);
            return;
        }

        curl_close($curl);

        if ($status == 200) {

            $response = json_decode($result, true);

            if (!isset($response['data']['access_token'])) {
                echo "Access token tidak ditemukan<br>";
                echo $result;
                return;
            }

            $token = $response['data']['access_token'];

            // Enkripsi token
            $encrypted_token = $this->encryption->encrypt($token);

            $this->db->where('id', 1);
            $this->db->update('sh_m_setup', [
                'admin_access_token' => $encrypted_token,
                'admin_last_login'   => date('Y-m-d H:i:s')
            ]);

            echo "<script>
            window.close();
          </script>";
        } else {
            echo "Error API: " . $status . "<br>";
            echo $result;
        }
    }
    public function get_billing()
    {
        $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();
        $token = $this->encryption->decrypt($setup->admin_access_token);

        $url = "https://api.hachigroup.id/transaction/v1/billing?page=1&limit=1000&start_date=2026-03-11&end_date=2026-03-11&status=1&external_id=84583_20260311114303_1773203975";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Authorization: Bearer " . $token
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            echo curl_error($curl);
            curl_close($curl);
            return;
        }

        curl_close($curl);

        $result = json_decode($response);
        var_dump($result);exit();
        if (!$result || !$result->status || empty($result->data)) {
            echo "Tidak ada data";
            return;
        }

        $total_insert = 0;

        foreach ($result->data as $row) {

            $payment_status = $row->data->status ?? null;
            // ✅ FILTER HANYA YANG PAID
            if ($payment_status !== 'PAID') {
                continue;
            }
            $external_id = $row->data->external_id ?? null;

            if (!$external_id) {
                continue;
            }

            // Cek sudah ada atau belum
            $cek = $this->db
                        ->where('external_id', $external_id)
                        ->get('sh_payments_so')
                        ->row();

            if (!$cek) {

                $insert = [
                    'external_id'   => $external_id,
                    'transaction_id'=> $row->transaction_id,
                    'invoice_id'    => $row->external_billing_id,
                    'status'        => $payment_status,
                    'amount'        => $row->amount,
                    'paid_amount'   => $row->data->amount ?? 0,
                    'paid_at'       => $row->paid_at ?? date('Y-m-d H:i:s'),
                    'raw_payload'   => json_encode($row),
                    'created_at'    => $row->created_at
                ];

                $this->db->insert('sh_payments_so', $insert);
                $total_insert++;
            }
        }

        echo "Sync selesai. Total PAID tersimpan: " . $total_insert;
    }
    public function create_billing()
    {
        // ===============================
        // Ambil Setup
        // ===============================
        $setup = $this->db->where('id', 1)->get('sh_m_setup')->row();

        if (!$setup) {
            return [
                'status'  => false,
                'message' => 'Setup tidak ditemukan'
            ];
        }

        // ===============================
        // Decrypt Token
        // ===============================
        $token = $setup->member_access_token;
        
        if (empty($token)) {
            return [
                'status'  => false,
                'message' => 'Token gagal didecrypt atau kosong'
            ];
        }

        // ===============================
        // URL API
        // ===============================
        $url = "https://api.hachigroup.id/transaction/v1/billing";

        // ===============================
        // Payload
        // ===============================
        $payload = [
            "service"     => "SELF_ORDER",
            "member_id"   => 0,
            "email"       => "testing@gmail.com",
            "amount"      => 1110,
            "description" => "TESTING PAYMENT SO"
        ];

        // ===============================
        // CURL INIT
        // ===============================
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FAILONERROR    => false, // biar tetap dapat response walau 400
            CURLOPT_HTTPHEADER     => [
                "Content-Type: application/json",
                "Authorization: Bearer " . trim($token)
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
        ]);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        var_dump($response);
        exit();
        // ===============================
        // Handle CURL Error
        // ===============================
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);

            return [
                'status'  => false,
                'message' => 'Curl Error: ' . $error
            ];
        }

        curl_close($ch);

        // ===============================
        // Decode Response
        // ===============================
        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'status'   => false,
                'httpcode' => $httpcode,
                'message'  => 'Response bukan JSON valid',
                'raw'      => $response
            ];
        }

        // ===============================
        // Final Result
        // ===============================
        return [
            'status'   => ($httpcode >= 200 && $httpcode < 300),
            'httpcode' => $httpcode,
            'data'     => $decoded
        ];
    }
}