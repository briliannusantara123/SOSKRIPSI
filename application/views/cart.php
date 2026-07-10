<?php $this->load->view('template/headmenu') ?>
<style type="text/css">
    /* Modal agak ke bawah */
    #payment .modal-dialog {
      margin-top: 180px; /* atur sesuai selera: 60px / 80px / 100px */
    }

    .card-total {
        background-color: white;
        border-radius: 20px;
        padding: 20px;
        max-width: 700px;
        margin: auto;
        margin-top: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 170px;
    }
    .cart-container {
        background-color: white;
        border-radius: 20px;
        padding: 20px;
        max-width: 700px;
        margin: auto;
        margin-top: 60px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .cartadd-container {
        background-color: white;
        border-radius: 20px;
        padding: 20px;
        max-width: 700px;
        margin: auto;
        margin-top: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    label {
        color: grey;
        font-size: 18px;
    }
    @keyframes slideDown {
        0% {
            transform: translateY(100%) scale(0.5);
            opacity: 0;
        }
        100% {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    @keyframes slideUp {
        0% {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
        100% {
            transform: translateY(100%) scale(0.5);
            opacity: 0;
        }
    }

    .addorder {
        display: none;
        opacity: 0;
        transform: translateY(100%) scale(0.5);
        transition: transform 0.4s ease, opacity 0.4s ease;
    }

    .addorder.show {
        display: inline-block;
        animation: slideDown 0.4s forwards;
    }

    .addorder.hide {
        animation: slideUp 0.4s forwards;
        pointer-events: none;
    }

</style>

    <div class="head">
        <header>
            <div style="display: flex; align-items: center;">
                <a href="<?= base_url() ?><?= $log ?>" style="text-decoration: none; color: black;">
                    <i class="bi bi-arrow-left" style="font-size: 30px;margin-left: 10px; text-shadow: 1px 1px 2px black;"></i>
                </a>
                <h2 style="margin: 0; margin-left: 5px;"><strong>Review Your Order</strong></h2>
            </div>
            <div class="profile">
                <!-- <a href="<?= base_url() ?>index.php/Billsementara/home/<?= $nomeja ?>" style="color: black"><i class="bi bi-file-earmark-text" style="font-size: 25px;"></i></a> -->
                <i class="fas fa-user" style="font-size: 20px;"><label style="font-size: 12px;">&nbsp;<?= explode(' ', $this->session->userdata('username'))[0]; ?> ( <?= $this->session->userdata('nomeja') ?> )</label></i>
            </div>
        </header>
    </div>
    <!-- <form action="<?= base_url() ?>index.php/cart/validasi_order/<?= $nomeja ?>/PaymentCashier/order/<?= $url ?>" method="POST"> -->
        <form action="<?= base_url() ?>index.php/cart/checkout/<?= $nomeja ?>/<?= $cek ?>/<?= $url ?>" method="POST">
        <?php if ($item): ?>
            <div class="cart-container">
                <div class="row">
                    <div class="col-7">
                        <h5><strong><?= $jumlah ?> items in cart</strong></h5>
                    </div>
                    <div class="col-5" style="text-align: right;">   
                        <a href="<?= base_url() ?>index.php/cart/cancel_order/<?= $nomeja ?>/<?= $cek ?>/<?= $url ?>" class="btn btn-danger" style="font-size: 10px;">Cancel Order</a>
                    </div>
                </div>  
                <?php foreach ($item as $i): ?>
                    <?php 
                    $cekSPtrans = $this->Item_model->cekSPtrans($i->item_code);
                    $cekSPchart = $this->Item_model->cekSPchart($i->item_code);
                    $totalSPchart = $this->Item_model->countSPchart(); // fungsi baru: hitung semua item sub_category = 'Special Promo'
                    ?>
                    <input type="hidden" name="options[]" value="<?= $i->options ?>">
                    <div class="item row align-items-center mb-3">
                        <div class="col-8">
                            <div class="item-details">
                                <?php if ($i->is_sold_out == 1): ?>
                                  <h6 style="font-size: 20px; color: red; position: relative; display: inline-block;">
                                       <span style="position: relative; display: inline-block; text-decoration: line-through; opacity: 0.5;">
                                           <strong><?= $i->description ?>
                                            <?php if ($i->extra_notes): ?>[<?= $i->extra_notes ?>]<?php endif ?>
                                            [<?= $i->qty ?>x]</strong>
                                       </span>
                                   </h6>
                                    <?php if ($i->unit_price_disc != 0 ): ?>
                                        <div class="price">Rp <?= number_format($i->unit_price_disc) ?></div>
                                    <?php else: ?>
                                        <div class="price">Rp <?= number_format($i->unit_price) ?></div>
                                    <?php endif ?>
                                <?php else: ?>  
                                  <h6 style="font-size: 20px;"><strong><?= $i->description ?></strong></h6>
                                    <?php if ($i->unit_price_disc != 0 ): ?>
                                        <div class="price">Rp <?= number_format($i->unit_price_disc) ?></div>
                                    <?php else: ?>
                                        <div class="price">Rp <?= number_format($i->unit_price) ?></div>
                                    <?php endif ?>
                                <?php endif ?>
                                
                                
                                <h6 style="color: grey"><?= $i->options ?></h6>
                                <h6 style="color: grey"><?= $i->notesdua ?></h6>
                                <h6 style="color: grey"><?= $i->extra_notes ?></h6>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <?php 
                                $img = '';

                                  if (!empty($i->image_path)) {
                                      if (strpos($i->image_path, 'assets') !== false) {
                                          $img = base_url($i->image_path);
                                      } else {
                                          $img = $i->image_path;
                                      }
                                  } else {
                                      $img = base_url($logo->image_path);
                                  }
                                  ?>
                            <?php if ($i->image_path): ?>
                                <img src="<?= $img ?>" class="img-fluid" style="width: 100px; height: auto; object-fit: cover;">
                            <?php else: ?>
                                <img src="<?= $logo->image_path ?>" class="img-fluid" style="width: 100px; height: auto; object-fit: cover;">
                            <?php endif ?>
                            <div class="quantity-control d-flex align-items-center mt-2">
                                <?php if ($i->is_sold_out == 1): ?>
                                    <h5 style="color: red;padding-top: 10px;">SOLDOUT</h5>
                                    <button type="button" class="btn kurang-btn" style="color: white;height: 35px; display: none;background-color: <?= $cn->color ?>">-</button>
                                    <?php if ($i->is_paket == 1): ?>
                                        <a href="<?= base_url() ?>index.php/cart/delete/<?= $i->id ?>/<?= $nomeja ?>/paket/<?= $cek ?>/<?= $sub ?>" class="remove-item" style="margin-top: 5px; display: none;">
                                    <?php else: ?>
                                        <a href="<?= base_url() ?>index.php/cart/delete/<?= $i->id ?>/<?= $nomeja ?>/nonpaket/<?= $cek ?>/<?= $sub ?>" class="remove-item" style="margin-top: 5px; display: none;">
                                    <?php endif ?>
                                    
                                        <i class="bi bi-trash" style="font-size: 30px"></i>
                                    </a>
                                    
                                    
                                <?php else: ?>

                                    <?php 
                                        $cekSPtrans = $this->Item_model->cekSPtrans($i->item_code);
                                        $cekSPchart = $this->Item_model->cekSPchart($i->item_code);
                                        $totalSPchart = $this->Item_model->countSPchart(); // fungsi baru: hitung semua item sub_category = 'Special Promo'
                                        ?>

                                        <?php if ($i->sub_category == 'Special Promo'): ?>
                                            <?php if ($cekSPtrans): ?>
                                                <!-- Jika item ini ada di transaksi, maka boleh tambah -->
                                                <button type="button" class="btn kurang-btn" style="color: white;height: 35px; display: none;background-color: <?= $cn->color ?>">-</button>
                                                
                                                <?php if ($i->is_paket == 1): ?>
                                                    <a href="<?= base_url("index.php/cart/delete/{$i->id}/{$nomeja}/paket/{$cek}/{$sub}") ?>" class="remove-item" style="margin-top: 5px; display: none;">
                                                <?php else: ?>
                                                    <a href="<?= base_url("index.php/cart/delete/{$i->id}/{$nomeja}/nonpaket/{$cek}/{$sub}") ?>" class="remove-item" style="margin-top: 5px; display: none;">
                                                <?php endif ?>
                                                    <i class="bi bi-trash" style="font-size: 35px"></i>
                                                </a>
                                                <span class="hasil" style="font-size: 20px; margin: 0 10px;"><?= $i->qty ?></span>
                                                <button type="button" class="btn tambah-btn" style="color: white;height: 35px;background-color: <?= $cn->color ?>">+</button>

                                            <?php else: ?>
                                                <?php if ($totalSPchart >= 2): ?>
                                                    <!-- Jika sudah ada 2 item Special Promo di chart -->
                                                    <h5 style="color: red;padding-top: 10px;font-size: 12px;">Only one Special Promo item can be added</h5>

                                                    <!-- Tampilkan tombol hapus -->
                                                    <?php if ($i->is_paket == 1): ?>
                                                        <a href="<?= base_url("index.php/cart/delete/{$i->id}/{$nomeja}/paket/{$cek}/{$sub}") ?>" class="remove-item" style="margin-top: 5px;">
                                                    <?php else: ?>
                                                        <a href="<?= base_url("index.php/cart/delete/{$i->id}/{$nomeja}/nonpaket/{$cek}/{$sub}") ?>" class="remove-item" style="margin-top: 5px;">
                                                    <?php endif ?>
                                                        <i class="bi bi-trash" style="font-size: 35px"></i>
                                                    </a>

                                                <?php else: ?>
                                                    <!-- Belum mencapai 2 promo, boleh tambah -->
                                                    <button type="button" class="btn kurang-btn" style="color: white;height: 35px; display: none;background-color: <?= $cn->color ?>">-</button>

                                                    <?php if ($i->is_paket == 1): ?>
                                                        <a href="<?= base_url("index.php/cart/delete/{$i->id}/{$nomeja}/paket/{$cek}/{$sub}") ?>" class="remove-item" style="margin-top: 5px; display: none;">
                                                    <?php else: ?>
                                                        <a href="<?= base_url("index.php/cart/delete/{$i->id}/{$nomeja}/nonpaket/{$cek}/{$sub}") ?>" class="remove-item" style="margin-top: 5px; display: none;">
                                                    <?php endif ?>
                                                        <i class="bi bi-trash" style="font-size: 35px"></i>
                                                    </a>

                                                    <span class="hasil" style="font-size: 20px; margin: 0 10px;"><?= $i->qty ?></span>
                                                    <button type="button" class="btn tambah-btn" style="color: white;height: 35px;background-color: <?= $cn->color ?>">+</button>
                                                <?php endif ?>

                                            <?php endif ?>

                                        <?php else: ?>
                                            <!-- Item non Special Promo -->
                                            <button type="button" class="btn kurang-btn" style="color: white;height: 35px; display: none;background-color: <?= $cn->color ?>">-</button>
                                            
                                            <?php if ($i->is_paket == 1): ?>
                                                <a href="<?= base_url("index.php/cart/delete/{$i->id}/{$nomeja}/paket/{$cek}/{$sub}") ?>" class="remove-item" style="margin-top: 5px; display: none;">
                                            <?php else: ?>
                                                <a href="<?= base_url("index.php/cart/delete/{$i->id}/{$nomeja}/nonpaket/{$cek}/{$sub}") ?>" class="remove-item" style="margin-top: 5px; display: none;">
                                            <?php endif ?>
                                            <!-- dev -->
                                                <i class="bi bi-trash" style="font-size: 35px"></i>
                                            <!-- dev -->
                                            </a>
                                            <span class="hasil" style="font-size: 20px; margin: 0 10px;"><?= $i->qty ?></span>
                                            <!-- dev -->
                                            <button type="button" class="btn tambah-btn" style="color: white;height: 35px;background-color: <?= $cn->color ?>">+</button>
                                            <!-- dev -->
                                        <?php endif ?>

                                    <?php endif ?>
                                
                                <input type="hidden" name="" id="stock" value="<?= $i->stock ?>">
                                
                                <input type="hidden" name="qty[]" class="qty" value="<?= $i->qty ?>">
                                <input type="hidden" name="nama[]" value="<?= $i->description ?>">
                                <input type="hidden" name="cek[]" value="<?= $i->as_take_away ?>">
                                <input type="hidden" name="qta[]" value="<?= $i->qty_take_away ?>">
                                <input type="hidden" name="harga[]" value="<?= $i->unit_price ?>">
                                <input type="hidden" name="pesan[]" value="<?= !empty($i->extra_notes) ? $i->extra_notes : $i->options ?>">
                                <input type="hidden" name="notesdua[]" value="<?= $i->notesdua ?>">
                                <!-- <input type="hidden" name="addons[]" value="<?= $i->ad ?>"> -->
                                <input type="hidden" name="id[]" value="<?= $i->id ?>" id="id">
                                <input type="hidden" name="no[]" id="item_code" value="<?= $i->item_code ?>" class="form-control item_code">
                                <input type="hidden" name="need_stock[]" id="need_stock" value="<?= $i->need_stock ?>" class="form-control need_stock">
                                
                                
                            </div>

                        </div>
                        <div class="col-8">
                            <?php $itempaket = $this->Paket_model->getcartpaket($i->id)->result(); ?>
                            <?php if ($itempaket): ?>
                                <h6 style="text-align: center;"><strong>Package Item Details</strong></h6>
                                <?php foreach ($itempaket as $i): ?>
                                    <div class="addon row align-items-center mb-3">
                                        <div class="col-8">
                                            <div class="item-details" style="position: relative; text-align: center;">
                                                <?php if ($i->is_sold_out == 1): ?>
                                                    <h6 style="font-size: 15px; color: red; position: relative; display: inline-block;">
                                                        <span style="position: relative; display: inline-block; text-decoration: line-through; opacity: 0.5;">
                                                            <strong><?= $i->description ?>
                                                            <?php if ($i->extra_notes): ?>[<?= $i->extra_notes ?>]<?php endif ?>
                                                            [<?= $i->qty ?>x]</strong>
                                                        </span>
                                                    </h6>
                                                <?php else: ?>
                                                    <h6 style="font-size: 15px; color: grey;">
                                                        <strong><?= $i->description ?>
                                                        <?php if ($i->extra_notes): ?>[<?= $i->extra_notes ?>]<?php endif ?>
                                                        [<?= $i->qty ?>x]</strong>
                                                    </h6>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="quantity-control d-flex align-items-center mt-2">
                                                <?php if ($i->is_sold_out == 1): ?>
                                                    <h6 style="color: red;margin-left:80px;">SOLDOUT</h6>
                                                    <a href="#" class="btn" style="padding:4px 4px;margin-bottom: 2px;margin-left:10px;background-color: <?= $cn->color ?>;color: white; " data-bs-toggle="modal" data-bs-target="#change<?= $i->id ?>"><i class="fas fa-recycle" style="color: white"></i></a>


                                                <?php endif ?>
                                                
                                                <!-- <button type="button" class="btn kurang-btn-addon" style="color: white;height: 35px; display: none;background-color: <?= $cn->color ?>">-</button>
                                                <a href="<?= base_url() ?>index.php/cart/delete/<?= $i->id ?>/<?= $nomeja ?>/nonpaket/<?= $cek ?>/<?= $sub ?>" class="remove-item-addon" style="margin-top: 5px; display: none;">
                                                    <i class="bi bi-trash" style="font-size: 35px;color: red;"></i>
                                                </a> -->
                                                <!-- <span class="hasiladdon" style="font-size: 20px; margin: 0 10px;"><?= $i->qty ?></span> -->
                                                <input type="hidden" name="qtyip[]" class="qtyip" value="<?= $i->qty ?>">
                                                <input type="hidden" name="is_ip" value="1">
                                                <input type="hidden" name="namaip[]" value="<?= $i->description ?>">
                                                <!-- <input type="hidden" name="hargaaddon[]" value="<?= $i->unit_price ?>"> -->
                                                <input type="hidden" name="idip[]" value="<?= $i->id ?>" id="idip">
                                                <input type="hidden" name="noip[]" id="item_code" value="<?= $i->item_code ?>" class="form-control item_code">
                                                <input type="hidden" name="need_stockip[]" id="need_stockip" value="<?= $i->need_stock ?>" class="form-control need_stock">
                                                <!-- <input type="hidden" name="" id="stockaddon" value="<?= $i->stock ?>"> -->
                                                
                                                <!-- <button type="button" class="btn tambah-btn-addon" style="color: white;height: 35px;background-color: <?= $cn->color ?>;">+</button> -->
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                        <div class="col-8">
                            <?php $addons = $this->Item_model->getADDONS($i->item_code); ?>
                            <?php if ($addons): ?>
                                <h6 style="text-align: center;"><strong>ADD ON</strong></h6>
                                <?php foreach ($addons as $i): ?>
                                    <div class="addon row align-items-center mb-3">
                                        <div class="col-8">
                                            <div class="item-details">
                                                <h6 style="font-size: 15px;color: grey"><strong><?= $i->description ?></strong></h6>
                                                <div class="">Rp <?= number_format($i->unit_price) ?></div>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="quantity-control d-flex align-items-center mt-2">
                                                <button type="button" class="btn kurang-btn-addon" style="color: white;height: 35px; display: none;background-color: <?= $cn->color ?>">-</button>
                                                <a href="<?= base_url() ?>index.php/cart/delete/<?= $i->id ?>/<?= $nomeja ?>/nonpaket/<?= $cek ?>/<?= $sub ?>" class="remove-item-addon" style="margin-top: 5px; display: none;">
                                                    <i class="bi bi-trash" style="font-size: 35px;color: red;"></i>
                                                </a>
                                                <span class="hasiladdon" style="font-size: 20px; margin: 0 10px;"><?= $i->qty ?></span>
                                                <input type="hidden" name="qtyaddon[]" class="qtyaddon" value="<?= $i->qty ?>">
                                                <input type="hidden" name="is_addon" value="1">
                                                <input type="hidden" name="namaaddon[]" value="<?= $i->description ?>">
                                                <input type="hidden" name="hargaaddon[]" value="<?= $i->unit_price ?>">
                                                <input type="hidden" name="idaddon[]" value="<?= $i->id ?>" id="idAddon">
                                                <input type="hidden" name="noaddon[]" id="item_code" value="<?= $i->item_code ?>" class="form-control item_code">
                                                <input type="hidden" name="need_stockaddon[]" id="need_stockaddon" value="<?= $i->need_stock ?>" class="form-control need_stock">
                                                <input type="hidden" name="" id="stockaddon" value="<?= $i->stock ?>">
                                                
                                                <button type="button" class="btn tambah-btn-addon" style="color: white;height: 35px;background-color: <?= $cn->color ?>;">+</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>

            </div>
            <div class="card-total">
                <div class="total">
                    <div class="row">
                        <div class="col-6">
                            <label>Subtotal</label>
                        </div>
                        <div class="col-6">
                            <label class="float-end subtotal-label">Rp <?= number_format($total) ?></label>
                        </div>
                    </div>
                    <?php if ($hitungbayar): ?>
                        <?php if ($hitungbayar->sc != 0): ?>
                            <div class="row">
                                <div class="col-6">
                                    <label>SC 5%</label>
                                </div>
                                <div class="col-6">
                                    <label class="float-end sc-label">Rp <?= number_format($hitungbayar->sc) ?></label>
                                </div>
                            </div>
                        <?php endif ?>
                        
                        <div class="row">
                            <div class="col-6">
                                <label>PB1 10%</label>
                            </div>
                            <div class="col-6">
                                <label class="float-end ppn-label">Rp <?= number_format($hitungbayar->ppn) ?></label>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <strong>Total</strong>
                            </div>
                            <div class="col-6">
                                <strong class="float-end total-label">Rp <?= number_format($total+$hitungbayar->sc+$hitungbayar->ppn) ?></strong>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-6">
                                <strong>Total</strong>
                            </div>
                            <div class="col-6">
                                <strong class="float-end total-label">Rp <?= number_format($total) ?></strong>
                            </div>
                        </div>
                    <?php endif ?>
                    

                </div>
            </div>
        <?php else: ?>
            <div style="text-align: center; margin-top: 200px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" color="green" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16" style="display: block; margin-left: auto; margin-right: auto;">
                    
                </svg>
                <br>
                <h3 style="text-align: center; color: <?= $cn->color ?>;">Your Cart is Still Empty <br> Let’s Order Now!</h3>
            </div>

        <?php endif ?>
        <footer>
            <div class="container addorder" style="display: none;">
                <div class="row">
                    <?php foreach ($icon as $i): ?>
                            <div class="col-6">
                                <?php if ($i->link_type == 'makanan'): ?>
                                    <a href="<?= base_url() ?><?= $i->link ?><?= str_replace(" ","%20", $sca) ?>#<?= str_replace(" ","_", $sca) ?>" class="btn" style="display: flex; flex-direction: column; align-items: center; padding: 5px 10px;background-color: <?= $cn->color ?>">
                                <?php else: ?>
                                    <a href="<?= base_url() ?><?= $i->link ?><?= str_replace(" ","%20", $scm) ?>#<?= str_replace(" ","_", $scm) ?>" class="btn" style="display: flex; flex-direction: column; align-items: center; padding: 5px 10px;background-color: <?= $cn->color ?>">
                                <?php endif ?>
                                    <img src="<?= $i->image_path ?>" style="width: 80px; height: 90px; border-radius: 50%;" alt="Hachi Grill" class="image" />
                                    <span style="margin-top: 5px; margin-bottom: 5px; font-size: 16px; font-weight: bold; color: white;"><?= $i->title ?></span>
                                </a>
                            </div>

                        <?php endforeach ?>

                </div>  
            </div>
            <div class="containerfooter" style="padding: 10px;">
            <?php if ($item): ?>
                <!-- <button type="button" class="btn btn-warning add-btn" style="padding: 15px;font-size: 17px;color: white;"><i class="bi bi-plus-circle"></i> <strong>Add to Order</strong></button> -->
                <a href="<?= base_url() ?><?= $log ?>" class="btn btn-warning add-btn" style="padding: 15px;font-size: 17px;color: white;" ><i class="bi bi-plus-circle"></i> <strong>Add to Order</strong></a>
            <?php endif ?>
                <?php if ($item): ?>
                    <?php if ($totalSPchart >= 2): ?>
                        <button type="submit" class="btn add-btn" disabled
                            style="padding: 15px; font-size: 17px; background-color: darkgrey; color: white; cursor: not-allowed;">
                            <strong>Only one Special Promo item can be added</strong>
                        </button>
                    <?php else: ?>
                        <!-- <button type="button" class="btn add-btn" style="padding: 15px;font-size: 17px;background-color: <?= $cn->color ?>;color: white;" data-bs-toggle="modal" data-bs-target="#payment" id="button"><strong>Order Now</strong></button> -->
                        <!-- <button type="submit" class="btn add-btn" style="padding: 15px;font-size: 17px;background-color: <?= $cn->color ?>;color: white;" id="button"><strong>Order Now</strong></button> -->
                        <button type="button" class="btn add-btn" id="orderNowBtn"
                            style="padding: 15px;font-size: 17px;background-color: <?= $cn->color ?>;color: white;">
                            <strong>Order Now</strong>
                        </button>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </footer>
    </form>
    <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: <?= $cn->color ?>;color: white;">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Choose Payment Type</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
        <table class="table">
            <tbody>
                <tr>
                    <th scope="row">Sub Total</th>
                    <td></td>
                    <td></td>
                    <?php if ($totalbayar == NULL): ?>
                        <td>Rp 0</td>
                    <?php else: ?>
                        <td><label class="float-end subtotal-modal-label">Rp <?= number_format($totalbayar->total) ?></label></td>
                    <?php endif; ?>
                </tr>
                <!-- <tr>
                    <th scope="row">SC</th>
                    <td></td>
                    <td></td>
                    <?php if ($totalbayar == NULL): ?>
                        <td>Rp 0</td>
                    <?php else: ?>
                        <td>Rp <?= number_format($totalbayar->sc) ?></td>
                    <?php endif; ?>
                </tr> -->
                <tr>
                    <th scope="row">PB1</th>
                    <td></td>
                    <td></td>
                    <?php if ($totalbayar == NULL): ?>
                        <td>Rp 0</td>
                    <?php else: ?>
                        <td><label class="float-end ppn-modal-label">Rp <?= number_format($totalbayar->ppn) ?></label></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <th scope="row">Total Payment</th>
                    <td></td>
                    <td></td>
                    <?php if ($totalbayar == NULL): ?>
                        <td>Rp 0</td>
                    <?php else: ?>
                      <td><strong class="float-end total-modal-label">Rp <?= number_format($totalbayar->total + $totalbayar->sc + $totalbayar->ppn) ?></strong></td>
                        
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
    <div class="container text-center">
      <div class="row">
        <!-- <div class="col-6">
          <a href="<?= base_url() ?>index.php/cart/checkout" class="btn btn-outline-primary" style="padding-top: 10px;padding-bottom: 10px;padding-left: 40px;padding-right: 40px;" id="bayarsekarang">Payment Now</a>
        </div> -->
        <div class="col-6">
        <form action="<?= base_url() ?>index.php/cart/validasi_order/<?= $nomeja ?>/PN/<?= $cek ?>/<?= $sub ?>#<?= $sub ?>" method="POST">
          <?php foreach ($item as $i): ?>
            <input type="hidden" name="nama[]" value="<?= $i->description ?>">
            <input type="hidden" name="qty[]" id="qty<?= $i->id?>" value="<?= $i->qty ?>">
            <input type="hidden" name="cek[]" value="<?= $i->as_take_away ?>">
            <input type="hidden" name="qta[]" value="<?= $i->qty_take_away ?>">
            <input type="hidden" name="harga[]" value="<?= $i->unit_price ?>">
            <input type="hidden" name="is_paket[]" value="<?= $i->is_paket ?>">
             <input type="hidden" name="options[]" value="<?= $i->options ?>">
            <!-- <?php if ( $i->notesdua): ?>
            <input type="hidden" name="pesandua[]" value="<?= $i->notesdua ?>">
            <input type="hidden" name="pesantiga[]" value="<?= $i->notesdua ?>">
            <?php   endif ?> -->
            <input type="hidden" name="pesan[]" value="<?= !empty($i->extra_notes) ? $i->extra_notes : $i->options ?>">
            <input type="hidden" name="no[]" id="item_code<?= $i->id ?>" value="<?= $i->item_code ?>" class="form-control item_code<?= $i->id ?>">
            <input type="hidden" name="need_stock[]" id="need_stock" value="<?= $i->need_stock ?>" class="form-control need_stock">
          <?php endforeach ?>
          <input type="hidden" name="totalbayar" class="totalbayarmodalPN" value="<?= $totalbayar->total + $totalbayar->sc + $totalbayar->ppn ?>">
          <div class="center">
            <button type="submit" class="btn" style="padding-top: 10px;padding-bottom: 10px;padding-left: 40px;padding-right: 40px;background-color: <?= $cn->color ?>;color: white;" id="bayarsekarang" onclick="order()">Payment Now</button>
          </div>
        </form>
      </div>
        <div class="col-6">
          <form action="<?= base_url() ?>index.php/cart/validasi_order/<?= $nomeja ?>/PC/<?= $cek ?>/<?= $sub ?>#<?= $sub ?>" method="POST">
          <?php foreach ($item as $i): ?>
            <input type="hidden" name="nama[]" value="<?= $i->description ?>">
            <input type="hidden" name="qty[]" id="qty<?= $i->id?>" value="<?= $i->qty ?>">
            <input type="hidden" name="cek[]" value="<?= $i->as_take_away ?>">
            <input type="hidden" name="qta[]" value="<?= $i->qty_take_away ?>">
            <input type="hidden" name="harga[]" value="<?= $i->unit_price ?>">
            <input type="hidden" name="is_paket[]" value="0">
             <input type="hidden" name="options[]" value="<?= $i->options ?>">
            <!-- <?php if ( $i->notesdua): ?>
            <input type="hidden" name="pesandua[]" value="<?= $i->notesdua ?>">
            <input type="hidden" name="pesantiga[]" value="<?= $i->notesdua ?>">
            <?php   endif ?> -->
            <input type="hidden" name="pesan[]" value="<?= !empty($i->extra_notes) ? $i->extra_notes : $i->options ?>">
            <input type="hidden" name="no[]" id="item_code<?= $i->id ?>" value="<?= $i->item_code ?>" class="form-control item_code<?= $i->id ?>">
            <input type="hidden" name="need_stock[]" id="need_stock" value="<?= $i->need_stock ?>" class="form-control need_stock">
          <?php endforeach ?>
          <input type="hidden" name="totalbayar" class="totalbayarmodalPC" value="<?= $totalbayar->total + $totalbayar->sc + $totalbayar->ppn ?>">
          <div class="center">
            <button type="submit" class="btn"
              id="bayarkasir"
              style="
                background-color: <?= $cn->color ?>;
                color: white;
                padding-top: 10px;
                padding-bottom: 10px;
                padding-left: 40px;
                padding-right: 40px;
              ">
              Pay at the cashier
            </button>

          </div>
        </form>
        </div>
      </div>
    </div>
    
      </div>
    </div>
  </div>
</div>
   <?php foreach ($itempaket as $i): ?>
    <?php $itemtersedia = $this->Paket_model->item_tersedia($i->paket_code, $i->sub_category); ?>
    <?php $count = COUNT($itemtersedia); ?>

    <div class="modal fade" id="change<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: <?= $cn->color ?>; color: white;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Items available in the <?= strtolower($i->sub_category) ?> category
                    </h1>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url() ?>index.php/cart/changeitemmodal" method="POST">
                        <input type="hidden" name="itemcodeOLD" value="<?= $i->item_code ?>">
                        <input type="hidden" class="qtylim" data-id="<?= $i->id ?>" value="<?= $i->qty ?>">
                        <input type="hidden" class="qtyubah" data-id="<?= $i->id ?>" value="0">

                        <?php foreach ($itemtersedia as $it): ?>
                            <div class="container">
                                <div class="row align-items-center mb-3">
                                    <div class="col-4 text-center">
                                        <img src="<?= $it->image_path ? $it->image_path : $logo->image_path ?>" loading="lazy" alt="Item Image" style="width: 90px; height: 90px;" />
                                    </div>
                                    <div class="col-8">
                                        <div class="item-description">
                                            <h5 class="mb-1" style="font-size: 17px;text-align: left;"><?= $it->description ?></h5>
                                            <p style="font-size: 12px; color: gray;"><?= $it->product_info ?></p>

                                            <div class="d-flex align-items-center">
                                                <?php if ($count > 1): ?>
                                                    <button type="button" class="btn btn-sm minus" id="kurangqty<?= $it->id ?>" data-id="<?= $it->id ?>" data-paket="<?= $i->id ?>" style="background-color: <?= $cn->color ?>; color: white;">-</button>
                                                    <input type="text" name="qty[]" class="form-control num text-center mx-2" id="num<?= $it->id ?>" data-paket="<?= $i->id ?>" value="0" readonly style="width: 50px;">
                                                    <button type="button" class="btn btn-sm plus" id="tambahqty<?= $it->id ?>" data-id="<?= $it->id ?>" data-paket="<?= $i->id ?>" style="background-color: <?= $cn->color ?>; color: white;">+</button>
                                                <?php else: ?>  
                                                    <a href="<?= base_url() ?>index.php/cart/changeitem/<?= $i->item_code ?>/<?= $it->no ?>/<?= $i->qty ?>/<?= $i->id_cart ?>" class="btn btn-primary ms-2" style="background-color: <?= $cn->color ?>;">Change Item</a>
                                                <?php endif ?>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="nama[]" value="<?= $it->description ?>">
                            <input type="hidden" name="id[]" value="<?= $it->id ?>">
                            <input type="hidden" name="no[]" value="<?= $it->item_code ?>">
                            <input type="hidden" name="subcategory[]" value="<?= $it->varian_category ?>">
                            
                        <?php endforeach; ?>
                        <input type="hidden" name="paket_code" value="<?= $i->item_code ?>">
                        <input type="hidden" name="id_cart" value="<?= $i->id_cart ?>">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <?php if ($count > 1): ?>
                        <button type="submit" class="btn" style="background-color: <?= $cn->color ?>; color: white;">Change Item</button>
                    <?php endif ?>
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Event handler untuk tombol plus (+)
        $(document).on("click", ".plus", function () {
            var itemId = $(this).data("id");
            var paketId = $(this).data("paket");
            var input = $(this).closest(".row").find(".num"); // Cari input dalam row yang sama
            var value = parseInt(input.val()) || 0;
            var qtylim = parseInt($(".qtylim[data-id='" + paketId + "']").val()) || 0;
            var qtyubah = parseInt($(".qtyubah[data-id='" + paketId + "']").val()) || 0;

            if (qtyubah >= qtylim) {
                Swal.fire({
                    title: 'Notification!',
                    text: 'Please select max ' + qtylim,
                    icon: 'warning',
                    confirmButtonColor: "<?= $cn->color ?>",
                    confirmButtonText: 'OK'
                });
            } else {
                input.val(value + 1);
                updateTotalQty(paketId);
            }
        });

        // Event handler untuk tombol minus (-)
        $(document).on("click", ".minus", function () {
            var itemId = $(this).data("id");
            var paketId = $(this).data("paket");
            var input = $(this).closest(".row").find(".num");
            var value = parseInt(input.val()) || 0;

            if (value > 0) {
                input.val(value - 1);
                updateTotalQty(paketId);
            }
        });

        // Fungsi untuk memperbarui total qtyubah per paket
        function updateTotalQty(paketId) {
            var total = 0;
            $(".num[data-paket='" + paketId + "']").each(function () {
                total += parseInt($(this).val()) || 0; // Pastikan nilai tidak NaN
            });

            $(".qtyubah[data-id='" + paketId + "']").val(total); 
        }
    });
</script>



</body>

<?php $this->load->view('template/footer') ?>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Tombol "Tambah Pesanan"
    const addButton = document.querySelector('.add-btn');
    const addButtonAddon = document.querySelector('.add-btn-addon');
    const addOrderContainer = document.querySelector('.addorder');
    let isToggled = false; // Status toggle

    // Event listener untuk tombol tambah pesanan
    // if (addButton) {
    //     addButton.addEventListener('click', function () {
    //         if (isToggled) {
    //             // Animasi slide up
    //             addOrderContainer.classList.remove('show');
    //             addOrderContainer.classList.add('hide');
    //             setTimeout(() => addOrderContainer.style.display = 'none', 400);
    //         } else {
    //             // Animasi slide down
    //             addOrderContainer.style.display = 'inline-block';
    //             addOrderContainer.classList.remove('hide');
    //             addOrderContainer.classList.add('show');
    //         }
    //         isToggled = !isToggled;
    //     });
    // }

    // Tombol kurang, tambah, dan trash pada setiap item
    document.querySelectorAll('.item').forEach(function (item) {
        const kurangBtn = item.querySelector('.kurang-btn');
        const trashIcon = item.querySelector('.remove-item');
        const qtyField = item.querySelector('.qty');
        const hasilField = item.querySelector('.hasil');
        const stockField = item.querySelector('#stock');
        const needStockField = item.querySelector('#need_stock');
        const tambahBtn = item.querySelector('.tambah-btn');

        // Inisialisasi tombol berdasarkan qty awal
        toggleButtonsVisibility(parseInt(qtyField.value), kurangBtn, trashIcon);

        // Event listener untuk tombol kurang
        kurangBtn.addEventListener('click', function () {
            const currentQty = parseInt(hasilField.innerText);
            if (currentQty > 1) {
                const newQty = currentQty - 1;
                updateQty(item, newQty, kurangBtn, trashIcon);
            }
        });

        // Event listener untuk tombol tambah
        tambahBtn.addEventListener('click', function () {
            const currentQty = parseInt(hasilField.innerText);
            const stock = parseInt(stockField.value);
            const needStock = parseInt(needStockField.value);

            if (needStock === 1 && currentQty >= stock) {
                // Jika stok habis
                Swal.fire({
                    title: 'Notification!',
                    text: stock + ' stock remaining',
                    icon: 'warning',
                    confirmButtonColor: "<?= $cn->color ?>",
                    confirmButtonText: 'OK'
                });
            } else {
                // Tambah qty
                const newQty = currentQty + 1;
                updateQty(item, newQty, kurangBtn, trashIcon);
            }
        });
    });

    document.querySelectorAll('.addon').forEach(function (item) {
        const kurangBtnAddon = item.querySelector('.kurang-btn-addon');
        const trashIconAddon = item.querySelector('.remove-item-addon');
        const qtyFieldAddon = item.querySelector('.qtyaddon');
        const hasilFieldAddon = item.querySelector('.hasiladdon');
        const stockFieldAddon = item.querySelector('#stockaddon');
        const needStockFieldAddon = item.querySelector('#need_stockaddon');
        const tambahBtnAddon = item.querySelector('.tambah-btn-addon');

        // Inisialisasi tombol berdasarkan qty awal
        if (qtyFieldAddon) {
            toggleButtonsVisibilityAddon(parseInt(qtyFieldAddon.value), kurangBtnAddon, trashIconAddon);
    
        }
        
        if (kurangBtnAddon) {
            kurangBtnAddon.addEventListener('click', function () {
                const currentQtyAddon = parseInt(hasilFieldAddon.innerText);
                if (currentQtyAddon > 1) {
                    const newQtyAddon = currentQtyAddon - 1;
                    updateQtyAddon(item, newQtyAddon, kurangBtnAddon, trashIconAddon);
                }
            });

            // Event listener untuk tombol tambah
            tambahBtnAddon.addEventListener('click', function () {
                const currentQtyAddon = parseInt(hasilFieldAddon.innerText);
                const stockAddon = parseInt(stockFieldAddon.value);
                const needStockAddon = parseInt(needStockFieldAddon.value);

                if (needStockAddon === 1 && currentQtyAddon >= stockAddon) {
                    // Jika stok habis
                    Swal.fire({
                        title: 'Notification!',
                        text: stockAddon + ' stock remaining',
                        icon: 'warning',
                        confirmButtonColor: "<?= $cn->color ?>",
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Tambah qty
                    const newQtyAddon = currentQtyAddon + 1;
                    updateQtyAddon(item, newQtyAddon, kurangBtnAddon, trashIconAddon);
                }
            });    
        }
        // Event listener untuk tombol kurang
        
    });
    function updateQtyAddon(item, newQtyAddon, kurangBtnAddon, trashIconAddon) {
        const hasilFieldAddon = item.querySelector('.hasiladdon');
        const qtyFieldAddon = item.querySelector('.qtyaddon');
        hasilFieldAddon.innerText = newQtyAddon;
        qtyFieldAddon.value = newQtyAddon;

        // Toggle tombol kurang dan trash
        toggleButtonsVisibilityAddon(newQtyAddon, kurangBtnAddon, trashIconAddon);

        // Kirim update ke server
        updateCartAddon(item, newQtyAddon);
    }
    function toggleButtonsVisibilityAddon(qtyaddon, kurangBtnAddon, trashIconAddon) {
        if (qtyaddon > 1) {
            kurangBtnAddon.style.display = 'inline-block';
            trashIconAddon.style.display = 'none';
        } else {
            kurangBtnAddon.style.display = 'none';
            trashIconAddon.style.display = 'inline-block';
        }
    }
    function updateCartAddon(parent, qtyaddon) {
        const itemId = parent.querySelector('#idAddon').value;

        fetch('<?= base_url() ?>index.php/cart/update_qty', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: itemId, qty: qtyaddon })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Cart updated successfully');
                updateTotal();
            } else {
                console.error('Failed to update cart:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    // Fungsi untuk memperbarui qty
    function updateQty(item, newQty, kurangBtn, trashIcon) {
        const hasilField = item.querySelector('.hasil');
        const qtyField = item.querySelector('.qty');
        hasilField.innerText = newQty;
        qtyField.value = newQty;

        // Toggle tombol kurang dan trash
        toggleButtonsVisibility(newQty, kurangBtn, trashIcon);

        // Kirim update ke server
        updateCart(item, newQty);
    }

    // Fungsi untuk menampilkan/menghilangkan tombol kurang dan trash
    function toggleButtonsVisibility(qty, kurangBtn, trashIcon) {
        if (qty > 1) {
            kurangBtn.style.display = 'inline-block';
            trashIcon.style.display = 'none';
        } else {
            kurangBtn.style.display = 'none';
            trashIcon.style.display = 'inline-block';
        }
    }

    // Fungsi untuk mengirim update qty ke server
    function updateCart(parent, qty) {
        const itemId = parent.querySelector('#id').value;

        fetch('<?= base_url() ?>index.php/cart/update_qty', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: itemId, qty: qty })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Cart updated successfully');
                updateTotal();
            } else {
                console.error('Failed to update cart:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Fungsi untuk memperbarui total harga
    function updateTotal() {
        fetch('<?= base_url() ?>index.php/cart/get_total', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector('.subtotal-label').innerText = 'Rp ' + data.total_formatted;
                document.querySelector('.subtotal-modal-label').innerText = 'Rp ' + data.total_formatted;
                // document.querySelector('.sc-label').innerText = 'Rp ' + data.sc_formatted;
                document.querySelector('.ppn-label').innerText = 'Rp ' + data.ppn_formatted;
                document.querySelector('.ppn-modal-label').innerText = 'Rp ' + data.ppn_formatted;
                document.querySelector('.total-label').innerText = 'Rp ' + data.grand_total_formatted;
                document.querySelector('.total-modal-label').innerText = 'Rp ' + data.grand_total_formatted;
                const pn = document.querySelector('.totalbayarmodalPN');
                const pc = document.querySelector('.totalbayarmodalPC');

                // bersihkan koma, titik, Rp, spasi, dll
                const totalRaw = data.grand_total_formatted.replace(/[^\d]/g, '');

                if (pn) pn.value = totalRaw;
                if (pc) pc.value = totalRaw;


            } else {
                console.error('Failed to fetch total:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Filter untuk textarea input (opsional jika ada)
    const textarea = document.getElementById('customTextarea');
    if (textarea) {
        textarea.addEventListener('input', function () {
            const value = textarea.value;
            textarea.value = value.replace(/[^\w\s.,]/g, ''); // Hanya karakter alfanumerik, spasi, titik, dan koma
        });
    }
});
var sold = document.querySelector("#sold");
function order() {
    <?php if ($i->is_sold_out == 1): ?>
        sold.value = 1;
        const submitButton = document.querySelector("button[type='submit']");
        submitButton.type = "button";
        Swal.fire({
            title: 'Notification!',
            text: 'There is an item out of stock!',
            icon: 'warning',
            confirmButtonColor: "#223c77",
            confirmButtonText: 'OK'
        });
    <?php else: ?>
        localStorage.clear();
    <?php endif; ?>
}
    const btn = document.getElementById('orderNowBtn');

    btn.addEventListener('click', function () {

        // 🔥 CEGAH SPAM KLIK
        if (btn.disabled) return;
        btn.disabled = true;
        btn.innerHTML = 'Processing...';

        let items = [];

        document.querySelectorAll('.item').forEach(function (item) {
            const name = item.querySelector('h6 strong')?.innerText || '';
            const qty = item.querySelector('.qty')?.value || 0;
            const item_code = item.querySelector('.item_code')?.value || '';

            if (name && qty > 0) {
                items.push({
                    name: name,
                    qty: qty,
                    item_code: item_code
                });
            }
        });

        fetch('<?= base_url() ?>index.php/cart/check_existing_order', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ items: items })
        })
        .then(async res => {
            const text = await res.text();

            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Response bukan JSON:', text);
                throw new Error('Invalid JSON response');
            }
        })
        .then(res => {

            const form = document.querySelector('form');

            if (res.exists) {

                let itemList = res.items.join(' and ');

                Swal.fire({
                    title: 'Confirmation',
                    text: itemList + ' is already being ordered. Do you want to order again?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "<?= $cn->color ?>",
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, order again',
                    cancelButtonText: 'Cancel'
                }).then((result) => {

                    if (result.isConfirmed) {
                        form.action = "<?= base_url() ?>index.php/cart/validasi_order/<?= $nomeja ?>/PaymentCashier/reorder/<?= $url ?>";
                        form.submit();
                    } else {
                        location.reload();
                    }

                });

            } else {
                form.submit();
            }

        })
        .catch(err => {
            console.error(err);
            alert('Terjadi error, coba lagi');

            // 🔥 AKTIFKAN LAGI BUTTON KALAU ERROR
            btn.disabled = false;
            btn.innerHTML = 'Order Now';
        });

    });
</script>
</html>


