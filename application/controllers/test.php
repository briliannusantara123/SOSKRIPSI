
public function re_interface()
    {
        $cabangList = array_unique((array)$this->input->post('cabang'));
        $items      = array_values(array_unique((array)$this->input->post('items')));
        if (empty($cabangList)) {
            $this->session->set_flashdata(
                'error',
                'No cabang selected'
            );
            redirect('index.php/Tembakdata/itemwmsviews');
        }
        if (empty($items)) {
            $this->session->set_flashdata(
                'error',
                'No item selected'
            );
            redirect('index.php/Tembakdata/itemwmsviews');
        }
        $user         = $this->session->userdata('username');
        $created_date = date('Y-m-d H:i:s');
        $dbhachi = $this->load->database('dbhachi', TRUE);
        foreach ($cabangList as $cabang) {
            if ($cabang == 28) {
                $db_cabang = $this->load->database('hgdalsut_p', TRUE);
            } elseif ($cabang == 37) {
                $db_cabang = $this->load->database('hgdampera_p', TRUE);
            }
            $cabangInfo = $dbhachi
                ->select('cabang_name, is_Alacarte')
                ->get_where(
                    'sh_m_cabang',
                    ['id' => $cabang]
                )
                ->row();
            $cabangName = $cabangInfo
                ? $cabangInfo->cabang_name
                : 'Unknown';
            $isAlacarte = $cabangInfo
                ? $cabangInfo->is_Alacarte
                : 0;
            $cabangSimple = '';
            if (stripos($cabangName, 'shabu') !== false) {
                $cabangSimple = 'Shabu';

            } elseif (stripos($cabangName, 'hachi') !== false) {
                $cabangSimple = 'Hachi';
            }
            $dataInsertToCabang = [];
            $insertedItems = [];
            foreach ($items as $itemCode) {
                $item = $this->db
                    ->get_where(
                        'item_wms',
                        ['item_code' => $itemCode]
                    )
                    ->row();
                if (!$item) {
                    continue;
                }
                $cek = $db_cabang
                    ->get_where(
                        'sh_m_item',
                        ['no' => $itemCode]
                    )
                    ->row();
                if (!$cek) {
                    if (isset($insertedItems[$itemCode])) {
                        continue;
                    }
                    $dataInsert = [
                        'description'     => $item->item_name,
                        'no'              => $itemCode,
                        'cabang'          => $cabang,
                        'show_list'       => 1,
                        'dine_in_menu'    => 1,
                        'take_away_menu'  => 1,
                        'additional'      => 'True'
                    ];
                    if ($isAlacarte == 1) {
                        $dataInsert['item_package'] = 'Ala Carte';
                    } elseif (in_array($cabangSimple, ['Shabu', 'Hachi'])) {
                        $dataInsert['item_package'] = 'Tambahan';
                    }
                    $dataInsertToCabang[] = $dataInsert;
                    $insertedItems[$itemCode] = true;
                    $this->db->insert('log_autoinsert', [
                        'type'        => 'insert items wms',
                        'cabang_name' => $cabangName,
                        'deskripsi'   =>
                            "Item $item->item_name ($item->item_code) berhasil dikirim ke outlet $cabangName.",
                        'status'      => 'Berhasil',
                        'user_insert' => $user,
                        'tgl_insert'  => $created_date,
                        'is_email'    => 0
                    ]);
                } else {
                    $oldDesc = $cek->description;
                    $newDesc = $item->item_name;
                    $updateData = [
                        'description'     => $newDesc,
                        'show_list'       => 1,
                        'dine_in_menu'    => 1,
                        'take_away_menu'  => 1,
                        'additional'      => 'True'
                    ];
                    if ($isAlacarte == 1) {
                        $updateData['item_package'] = 'Ala Carte';
                    } elseif (in_array($cabangSimple, ['Shabu', 'Hachi'])) {
                        $updateData['item_package'] = 'Tambahan';
                    }
                    $db_cabang->where('no', $itemCode);
                    $db_cabang->update(
                        'sh_m_item',
                        $updateData
                    );
                    if ($oldDesc !== $newDesc) {
                        $deskripsi =
                            "Update item $itemCode | OLD: \"$oldDesc\" | NEW: \"$newDesc\"";
                    } else {
                        $deskripsi =
                            "Item $itemCode sudah ada — hanya update flag.";
                    }
                    $this->db->insert('log_autoinsert', [
                        'type'        => 'update items wms',
                        'cabang_name' => $cabangName,
                        'deskripsi'   => $deskripsi,
                        'status'      => 'Berhasil',
                        'user_insert' => $user,
                        'tgl_insert'  => $created_date,
                        'is_email'    => 0
                    ]);
                }
            }
            if (!empty($dataInsertToCabang)) {
                $db_cabang->insert_batch(
                    'sh_m_item',
                    $dataInsertToCabang
                );
            }

        }
        $this->session->set_flashdata(
            'notif',
            'Successfully Reinterface WMS Item to Selected Outlet(s)',
            300
        );
        redirect('index.php/Tembakdata/itemwmsviews');
    }
