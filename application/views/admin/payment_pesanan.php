<?php $this->load->view('admin/layout/header') ?>

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Payment / Kasir</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('index.php/Admin') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('index.php/Admin/pesanan') ?>">Orders</a></li>
                            <li class="breadcrumb-item"><a href="#!">Payment</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        
        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Detail Item</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $subtotal = 0;
                                    foreach($details as $d): 
                                        $sub = $d->unit_price * $d->qty;
                                        $subtotal += $sub;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php
                                                    $imgPath = base_url('assets/images/userkosong.png');
                                                    if (!empty($d->image_path)) {
                                                        if (strpos($d->image_path, 'data:image') === 0) {
                                                            $imgPath = $d->image_path;
                                                        } else {
                                                            $imgPath = 'data:image/jpeg;base64,' . $d->image_path;
                                                        }
                                                    }
                                                ?>
                                                <img src="<?= $imgPath ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; margin-right: 15px;">
                                                <?= $d->item_name ?>
                                            </div>
                                        </td>
                                        <td>Rp <?= number_format($d->unit_price, 0, ',', '.') ?></td>
                                        <td><?= $d->qty ?></td>
                                        <td class="text-right">Rp <?= number_format($sub, 0, ',', '.') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Ringkasan Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                            <span class="text-muted">Customer:</span>
                            <strong><?= $transaction->customer_name ?></strong>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp <?= number_format($payment->total, 0, ',', '.') ?></span>
                        </div>
                        <?php if($payment->sc > 0): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Service Charge:</span>
                            <span>Rp <?= number_format($payment->sc, 0, ',', '.') ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax / PPN:</span>
                            <span>Rp <?= number_format($payment->ppn, 0, ',', '.') ?></span>
                        </div>
                        <hr>
                        <?php $total_bayar_num = $payment->total + $payment->sc + $payment->ppn; ?>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="m-0 font-weight-bold">Total Bayar:</h5>
                            <h5 class="m-0 font-weight-bold text-primary">Rp <?= number_format($total_bayar_num, 0, ',', '.') ?></h5>
                        </div>
                        
                        <form action="<?= base_url('index.php/Admin/proses_payment_pesanan') ?>" method="post">
                            <input type="hidden" name="id_trans" value="<?= $transaction->id ?>">
                            <input type="hidden" name="payment_method" value="Cash">
                            <input type="hidden" id="total_bayar_num" value="<?= $total_bayar_num ?>">
                            
                            <div class="form-group">
                                <label>Metode Pembayaran</label>
                                <input type="text" class="form-control font-weight-bold" value="CASH" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label>Jumlah Uang Diterima (Rp)</label>
                                <input type="number" class="form-control" name="jumlah_bayar" id="jumlah_bayar" min="<?= $total_bayar_num ?>" required placeholder="Masukkan jumlah uang">
                            </div>
                            
                            <div class="form-group">
                                <label>Kembalian (Rp)</label>
                                <input type="text" class="form-control font-weight-bold text-danger" id="kembalian_tampil" value="0" readonly>
                                <input type="hidden" name="kembalian" id="kembalian" value="0">
                            </div>

                            <button type="submit" class="btn btn-success btn-block mt-4" id="btn_proses" onclick="return confirm('Apakah Anda yakin memproses pembayaran ini?')"><i class="fas fa-check-circle mr-2"></i> Proses Pembayaran</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

<?php $this->load->view('admin/layout/footer') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputJumlahBayar = document.getElementById('jumlah_bayar');
        const inputKembalianTampil = document.getElementById('kembalian_tampil');
        const inputKembalian = document.getElementById('kembalian');
        const totalBayar = parseFloat(document.getElementById('total_bayar_num').value);

        inputJumlahBayar.addEventListener('input', function() {
            let jumlahBayar = parseFloat(this.value);
            if (isNaN(jumlahBayar)) jumlahBayar = 0;

            let kembalian = jumlahBayar - totalBayar;
            if (kembalian < 0) {
                kembalian = 0;
            }

            inputKembalian.value = kembalian;
            inputKembalianTampil.value = kembalian.toLocaleString('id-ID');
        });
    });
</script>
