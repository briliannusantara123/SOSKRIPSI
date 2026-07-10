<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ngrok extends CI_Controller {

    public function get_url()
    {
        // 1. Ambil URL ngrok
        $json = @file_get_contents('http://127.0.0.1:4040/api/tunnels');

        if (!$json) {
            echo 'Ngrok belum berjalan';
            return;
        }

        $data = json_decode($json, true);

        if (empty($data['tunnels'])) {
            echo 'Tunnel ngrok tidak ditemukan';
            return;
        }

        // 2. Ambil HTTPS
        $public_url = null;
        foreach ($data['tunnels'] as $tunnel) {
            if ($tunnel['proto'] === 'https') {
                $public_url = $tunnel['public_url'];
                break;
            }
        }

        if (!$public_url) {
            echo 'HTTPS tunnel tidak ditemukan';
            return;
        }

        // 3. URL webhook
        $webhookUrl = $public_url . '/SOBayarNEW/index.php/Webhook';

        // 4. Simpan / Update ke database
        $this->saveOrUpdateWebhook($webhookUrl);

        echo 'Webhook URL berhasil disimpan:<br>' . $webhookUrl;
    }

    private function saveOrUpdateWebhook($url)
    {
        $type = 'URL_WEBHOOK';

        // cek apakah data sudah ada
        $query = $this->db
            ->where('type', $type)
            ->limit(1)
            ->get('sh_m_setup_so');

        if ($query->num_rows() > 0) {
            // UPDATE
            $this->db
                ->where('type', $type)
                ->update('sh_m_setup_so', array(
                    'link' => $url
                ));
        } else {
            // INSERT
            $this->db->insert('sh_m_setup_so', array(
                'type' => $type,
                'title' => 'Webhook URL',
                'link' => $url,
                'link_type' => 'url',
                'is_active' => 1,
                'is_active_paket' => 1
            ));
        }
    }
}
