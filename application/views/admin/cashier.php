<?php $this->load->view('admin/layout/header') ?>

<style>
    /* Hide sidebar and make cashier page full-width */
    .pcoded-navbar { display: none !important; }
    .pcoded-main-container, .pcoded-content { margin-left: 0 !important; padding-left: 12px !important; }
    .pcoded-header { left: 0 !important; }

    /* Adjust column widths for full-width layout */
    @media (min-width: 992px) {
        .col-lg-5 { flex: 0 0 36%; max-width: 36%; }
        .col-lg-7 { flex: 0 0 64%; max-width: 64%; }
    }

    /* existing styles */
    .cashier-order-card {
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
    }
    .cashier-order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.08);
    }
    .cashier-order-card.active {
        border-color: #198754;
        box-shadow: 0 0 0 3px rgba(25,135,84,0.15);
        background: #f8fff9;
    }
    .cashier-detail-card {
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #fff;
        margin-bottom: 12px;
    }
</style>

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Kasir</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('index.php/Admin') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Kasir</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="card shadow-sm" style="border-radius: 14px;">
                    <div class="card-header bg-white border-bottom-0">
                        <h5 class="mb-0">Daftar Pesanan</h5>
                        <small class="text-muted">Klik salah satu pesanan untuk melihat detail item</small>
                    </div>
                    <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                        <?php if (!empty($pesanan_list)): ?>
                            <?php foreach ($pesanan_list as $pesanan): ?>
                                <?php
                                    $statusClass = 'bg-secondary';
                                    if (strtolower($pesanan->status) === 'paid') $statusClass = 'bg-success';
                                    elseif (strtolower($pesanan->status) === 'pending') $statusClass = 'bg-warning text-dark';
                                    elseif (strtolower($pesanan->status) === 'canceled') $statusClass = 'bg-danger';
                                    else $statusClass = 'bg-info';
                                ?>
                                <div class="cashier-order-card" data-id="<?= $pesanan->id ?>" data-customer="<?= htmlspecialchars($pesanan->customer_name, ENT_QUOTES) ?>" data-table="<?= htmlspecialchars($pesanan->table_number, ENT_QUOTES) ?>" data-status="<?= htmlspecialchars($pesanan->status, ENT_QUOTES) ?>" data-total="<?= number_format($pesanan->total, 0, ',', '.') ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">Table <?= $pesanan->table_number ?></h6>
                                            <div class="text-muted small"><?= $pesanan->customer_name ?: 'Customer' ?></div>
                                        </div>
                                        <span class="badge <?= $statusClass ?>"><?= $pesanan->status ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">Total</small>
                                        <strong>Rp <?= number_format($pesanan->total, 0, ',', '.') ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">Belum ada pesanan hari ini.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm" style="border-radius: 14px;">
                    <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Detail Pesanan</h5>
                            <small class="text-muted" id="detailHeaderText">Pilih pesanan di sisi kiri</small>
                        </div>
                        <a href="#" id="btnProcessPayment" class="btn btn-success btn-sm disabled">Proses Pembayaran</a>
                    </div>
                    <div class="card-body" id="cashierDetailContent">
                        <div class="text-center text-muted py-5">Silakan pilih pesanan untuk melihat detail item.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.cashier-order-card');
    const detailContent = document.getElementById('cashierDetailContent');
    const detailHeaderText = document.getElementById('detailHeaderText');
    const paymentButton = document.getElementById('btnProcessPayment');

    function renderDetail(data, orderInfo) {
        if (!data.length) {
            detailContent.innerHTML = '<div class="alert alert-warning mb-0">Tidak ada item dalam pesanan ini.</div>';
            paymentButton.classList.add('disabled');
            paymentButton.removeAttribute('href');
            return;
        }

        let total = 0;
        let html = '';
        html += '<div class="mb-3 p-3 rounded" style="background:#f8fff9; border:1px solid #dff6e8;">';
        html += '<h6 class="mb-1 font-weight-bold">' + orderInfo.customer + '</h6>';
        html += '<div class="text-muted small">Table ' + orderInfo.table + ' • Status ' + orderInfo.status + '</div>';
        html += '</div>';

        data.forEach(function (item) {
            const qty = parseInt(item.qty || 0, 10);
            const unitPrice = parseInt(item.unit_price || 0, 10);
            const subtotal = qty * unitPrice;
            total += subtotal;

            let imgPath = '<?= base_url('assets/images/userkosong.png') ?>';
            if (item.image_path) {
                if (item.image_path.startsWith('data:image')) {
                    imgPath = item.image_path;
                } else {
                    imgPath = 'data:image/jpeg;base64,' + item.image_path;
                }
            }

            html += '<div class="cashier-detail-card p-3">';
            html += '<div class="d-flex align-items-center">';
            html += '<img src="' + imgPath + '" alt="' + item.item_name + '" style="width:70px;height:70px;object-fit:cover;border-radius:10px;margin-right:15px;">';
            html += '<div class="flex-grow-1">';
            html += '<h6 class="mb-1 font-weight-bold">' + item.item_name + '</h6>';
            html += '<div class="text-muted small">Qty: ' + qty + ' • Harga: Rp ' + unitPrice.toLocaleString('id-ID') + '</div>';
            html += '</div>';
            html += '<div class="text-right">';
            html += '<div class="text-muted small">Subtotal</div>';
            html += '<strong>Rp ' + subtotal.toLocaleString('id-ID') + '</strong>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        });

        html += '<div class="mt-3 p-3 rounded" style="background:#fff; border:1px solid #e5e7eb;">';
        html += '<div class="d-flex justify-content-between align-items-center">';
        html += '<span class="font-weight-bold">Total Tagihan</span>';
        html += '<span class="h5 mb-0 text-success">Rp ' + total.toLocaleString('id-ID') + '</span>';
        html += '</div>';
        html += '</div>';

        detailContent.innerHTML = html;
        paymentButton.classList.remove('disabled');
        // update footer payment info
        window.selectedOrderId = orderInfo.id;
        window.selectedOrderTotal = total;
        const footerTotal = document.getElementById('footer_pay_total');
        const footerCashInput = document.getElementById('footer_cash_input');
        const footerChange = document.getElementById('footer_change');
        if (footerTotal) footerTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
        if (footerCashInput) { footerCashInput.value = ''; footerChange.textContent = 'Rp 0'; }
        const footerOrderInfo = document.getElementById('footer_order_info');
        if (footerOrderInfo) footerOrderInfo.textContent = orderInfo.customer + ' • Table ' + orderInfo.table;
        document.getElementById('cashierFooter').classList.remove('d-none');
    }

    function loadOrderDetail(id, orderInfo) {
        detailHeaderText.textContent = 'Memuat detail pesanan...';
        detailContent.innerHTML = '<div class="text-center text-muted py-5">Memuat detail item...</div>';

        const formData = new FormData();
        formData.append('id_trans', id);

        fetch('<?= base_url('index.php/Admin/get_pesanan_detail') ?>', {
            method: 'POST',
            body: formData
        })
        .then(function (response) { return response.text(); })
        .then(function (text) {
            try {
                var data = JSON.parse(text);
            } catch (e) {
                var errText = text.replace(/<[^>]*>?/gm, '').slice(0, 500);
                if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat detail (server response): ' + errText });
                } else { alert('Gagal memuat detail (server response): ' + errText); }
                detailContent.innerHTML = '<div class="alert alert-danger mb-0">Gagal memuat detail pesanan.</div>';
                detailHeaderText.textContent = 'Gagal memuat detail pesanan';
                paymentButton.classList.add('disabled');
                paymentButton.removeAttribute('href');
                return;
            }
            renderDetail(data, orderInfo);
            detailHeaderText.textContent = 'Detail pesanan ' + orderInfo.customer + ' • Table ' + orderInfo.table;
        })
        .catch(function (err) {
            console.error(err);
            detailContent.innerHTML = '<div class="alert alert-danger mb-0">Gagal memuat detail pesanan.</div>';
            detailHeaderText.textContent = 'Gagal memuat detail pesanan';
            paymentButton.classList.add('disabled');
            paymentButton.removeAttribute('href');
        });
    }

    cards.forEach(function (card) {
        card.addEventListener('click', function () {
            cards.forEach(function (item) { item.classList.remove('active'); });
            this.classList.add('active');

            const orderInfo = {
                id: this.getAttribute('data-id'),
                customer: this.getAttribute('data-customer') || 'Customer',
                table: this.getAttribute('data-table') || '-',
                status: this.getAttribute('data-status') || '-'
            };

            loadOrderDetail(orderInfo.id, orderInfo);
        });
    });

    if (cards.length) {
        cards[0].click();
    }
});
</script>

<!-- Fixed footer payment bar (cash only) -->
<div id="cashierFooter" class="d-none" style="position:fixed;right:20px;left:20px;bottom:20px;z-index:1050;">
    <div class="card shadow" style="border-radius:12px;">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-muted">Pesanan</div>
                <div id="footer_order_info" class="font-weight-bold">-</div>
            </div>
            <div>
                <div class="small text-muted">Total</div>
                <div id="footer_pay_total" class="h5 text-success">Rp 0</div>
            </div>
            <div style="min-width:240px;">
                <div class="small text-muted">Tunai (Masukkan jumlah)</div>
                <div class="input-group">
                    <input id="footer_cash_input" type="number" min="0" class="form-control" placeholder="0">
                    <div class="input-group-append">
                        <button id="footer_pay_btn" class="btn btn-success">Bayar</button>
                    </div>
                </div>
                <div class="small text-muted mt-1">Kembalian: <span id="footer_change">Rp 0</span></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cashInput = document.getElementById('footer_cash_input');
    const changeEl = document.getElementById('footer_change');
    const orderInfoEl = document.getElementById('footer_order_info');
    const payBtn = document.getElementById('footer_pay_btn');

    function formatRupiah(num) {
        return 'Rp ' + (num || 0).toLocaleString('id-ID');
    }

    if (cashInput) {
        cashInput.addEventListener('input', function () {
            const cash = parseInt(this.value || 0, 10);
            const total = parseInt(window.selectedOrderTotal || 0, 10);
            const change = Math.max(0, cash - total);
            changeEl.textContent = formatRupiah(change);
        });
    }

    if (payBtn) {
        payBtn.addEventListener('click', function () {
            const idTrans = window.selectedOrderId;
            if (!idTrans) { Swal.fire({ icon: 'info', title: 'Pilih pesanan', text: 'Pilih pesanan terlebih dahulu.' }); return; }
            const cash = parseInt((cashInput && cashInput.value) || 0, 10);
            const total = parseInt(window.selectedOrderTotal || 0, 10);
            if (isNaN(cash) || cash < total) { Swal.fire({ icon: 'warning', title: 'Jumlah tunai tidak mencukupi', text: 'Jumlah tunai harus sama atau lebih besar dari total.' }); return; }

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: 'Proses pembayaran tunai untuk pesanan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, bayar',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754'
            }).then(function(confirmResult){
                if (!confirmResult.isConfirmed) return;
                payBtn.disabled = true; payBtn.textContent = 'Memproses...';

            const formData = new FormData();
            formData.append('id_trans', idTrans);
            formData.append('payment_method', 'cash');
            formData.append('kembalian', (cash - total));

            fetch('<?= base_url('index.php/Admin/proses_payment_pesanan') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(text => {
                let res;
                try { res = JSON.parse(text); } catch (e) {
                    const snippet = text.replace(/<[^>]*>?/gm, '').slice(0, 500);
                    if (window.Swal) Swal.fire({ icon: 'error', title: 'Server error', text: snippet }); else alert('Server error: ' + snippet);
                    payBtn.disabled = false; payBtn.textContent = 'Bayar';
                    return;
                }

            // handle parsed response
            // then(res => {
                if (res.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Pembayaran berhasil', confirmButtonColor: '#198754' }).then(function(){
                    // update UI: mark card as paid
                    const selCard = document.querySelector('.cashier-order-card.active');
                    if (selCard) {
                        selCard.setAttribute('data-status', 'Paid');
                        const badge = selCard.querySelector('.badge');
                        if (badge) { badge.className = 'badge bg-success'; badge.textContent = 'Paid'; }
                    }
                    // hide footer
                    document.getElementById('cashierFooter').classList.add('d-none');
                    // optionally reload details
                    if (selCard) selCard.classList.remove('active');
                    payBtn.disabled = false; payBtn.textContent = 'Bayar';
                    // open receipt in new window and trigger print
                    if (res.id_trans) {
                        const w = window.open('<?= base_url('index.php/Admin/print_receipt/') ?>' + res.id_trans, '_blank');
                        try { w.focus(); } catch(e){}
                    }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Gagal memproses pembayaran' });
                    payBtn.disabled = false; payBtn.textContent = 'Bayar';
                }
            })
            })
            .catch(err => {
                console.error(err);
                if (window.Swal) Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memproses pembayaran' }); else alert('Gagal memproses pembayaran');
                payBtn.disabled = false; payBtn.textContent = 'Bayar';
            });
        });
    }
});
</script>

<?php $this->load->view('admin/layout/footer') ?>