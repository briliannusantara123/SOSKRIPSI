<?php $this->load->view('admin/layout/header') ?>

<style>
    .pcoded-navbar { display: none !important; }
    .pcoded-main-container, .pcoded-content { margin-left: 0 !important; padding-left: 12px !important; }
    .pcoded-header { left: 0 !important; }
    .kitchen-card { border-radius: 14px; border: 1px solid #e5e7eb; background: #fff; transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .kitchen-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
    .order-status-chip { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; font-size: 0.85rem; font-weight: 600; }
    .order-status-chip.awaiting { background: #fff3cd; color: #856404; }
    .order-status-chip.proses { background: #d1ecf1; color: #0c5460; }
    .order-status-chip.deliver { background: #d4edda; color: #155724; }
    .order-status-chip.complete { background: #e2e3e5; color: #6c757d; }
    .kitchen-grid { display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 20px; }
    @media (min-width: 768px) { .kitchen-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1200px) { .kitchen-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
</style>

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Kitchen Dashboard</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('index.php/Admin') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Kitchen</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm" style="border-radius: 14px;">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            <div>
                                <h5 class="mb-1">Pesanan Aktif Hari Ini</h5>
                                <p class="mb-0 text-muted">Lihat ringkasan pesanan menurut status untuk proses dapur.</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="order-status-chip awaiting">Awaiting</span>
                                <span class="order-status-chip proses">In Progress</span>
                                <span class="order-status-chip deliver">Deliver</span>
                                <span class="order-status-chip complete">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <?php if (!empty($kitchen_orders)): ?>
                <div class="col-12">
                    <div class="kitchen-grid">
                        <?php foreach ($kitchen_orders as $order): ?>
                            <?php
                                $status = 'awaiting';
                                $statusLabel = 'Awaiting';
                                if ($order->complete_qty > 0 && $order->total_items === $order->complete_qty) {
                                    $status = 'complete';
                                    $statusLabel = 'Completed';
                                } elseif ($order->deliver_qty > 0) {
                                    $status = 'deliver';
                                    $statusLabel = 'Deliver';
                                } elseif ($order->proses_qty > 0) {
                                    $status = 'proses';
                                    $statusLabel = 'In Progress';
                                } elseif ($order->awaiting_qty > 0) {
                                    $status = 'awaiting';
                                    $statusLabel = 'Awaiting';
                                }
                            ?>
                            <div class="kitchen-card p-4 kitchen-order-card" data-order-id="<?= $order->id ?>">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="mb-1">Table <?= htmlspecialchars($order->table_number ?: '-', ENT_QUOTES, 'UTF-8') ?></h5>
                                        <p class="mb-0 text-muted"><?= htmlspecialchars($order->customer_name ?: 'Customer', ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>
                                    <span class="order-status-chip <?= $status ?>"><?= $statusLabel ?></span>
                                </div>

                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Total Item</small>
                                        <div class="h5 mb-0"><?= number_format($order->total_items, 0, ',', '.') ?></div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Awaiting</small>
                                        <div class="h5 mb-0"><?= number_format($order->awaiting_qty, 0, ',', '.') ?></div>
                                    </div>
                                </div>

                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">In Progress</small>
                                        <div class="h5 mb-0"><?= number_format($order->proses_qty, 0, ',', '.') ?></div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Delivered</small>
                                        <div class="h5 mb-0"><?= number_format($order->deliver_qty, 0, ',', '.') ?></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small mb-2">Items</div>
                                    <div class="kitchen-order-items" id="kitchen_items_<?= $order->id ?>">
                                        <div class="text-center text-muted py-3">Klik kartu untuk menampilkan item.</div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-load-items" data-order-id="<?= $order->id ?>">Load Items</button>
                                    <span class="text-muted small">ID: <?= htmlspecialchars($order->id, ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center mb-0">Tidak ada pesanan aktif untuk hari ini.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-load-items').forEach(function(button) {
            button.addEventListener('click', function() {
                var orderId = this.getAttribute('data-order-id');
                var container = document.getElementById('kitchen_items_' + orderId);
                if (!container) return;

                container.innerHTML = '<div class="text-center text-muted py-3">Memuat item...</div>';
                var formData = new FormData();
                formData.append('id_trans', orderId);

                fetch('<?= base_url('index.php/Admin/get_kitchen_items') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) { return response.json(); })
                .then(function(items) {
                    if (!items || items.length === 0) {
                        container.innerHTML = '<div class="alert alert-info mb-0">Tidak ada item untuk pesanan ini.</div>';
                        return;
                    }

                    var html = '';
                    items.forEach(function(item) {
                        var statusLabel = item.item_status || 'Unknown';
                        var statusClass = 'badge-secondary';
                        if (statusLabel.toLowerCase() === 'awaiting') statusClass = 'badge-warning';
                        else if (statusLabel.toLowerCase() === 'in progress') statusClass = 'badge-info';
                        else if (statusLabel.toLowerCase() === 'deliver') statusClass = 'badge-primary';
                        else if (statusLabel.toLowerCase() === 'completed') statusClass = 'badge-success';

                        html += '<div class="card mb-2" style="border-radius: 12px; border: 1px solid #e9ecef;">';
                        html += '  <div class="card-body p-3">';
                        html += '    <div class="d-flex align-items-start gap-3">';
                        html += '      <div class="flex-grow-1">';
                        html += '        <div class="d-flex justify-content-between align-items-start">';
                        html += '          <div><strong>' + (item.item_name || '-') + '</strong><br><small class="text-muted">Qty: ' + item.qty + ' • Rp ' + Number(item.unit_price || 0).toLocaleString('id-ID') + '</small></div>';
                        html += '          <span class="badge ' + statusClass + '">' + statusLabel + '</span>';
                        html += '        </div>';
                        if (item.extra_notes) {
                            html += '        <div class="text-muted small mt-2">Catatan: ' + item.extra_notes + '</div>';
                        }
                        html += '      </div>';
                        var isCompleted = (statusLabel || '').toString().toLowerCase() === 'completed' || (statusLabel || '').toString().toLowerCase() === 'done';

                        html += '      <div class="text-right">';
                        if (!isCompleted) {
                            html += '        <button type="button" class="btn btn-sm btn-outline-primary btn-update-item-status" data-item-id="' + item.id + '" data-current-status="' + statusLabel + '">Update</button>';
                        } else {
                            html += '        <span class="text-success small fw-bold">Completed</span>';
                        }
                        html += '      </div>';
                        html += '    </div>';
                        html += '  </div>';
                        html += '</div>';
                    });

                    container.innerHTML = html;
                    container.querySelectorAll('.btn-update-item-status').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            var itemId = this.getAttribute('data-item-id');
                            var currentStatus = this.getAttribute('data-current-status') || '';
                            var normalizedStatus = currentStatus.toLowerCase();
                            var nextStatus = 'In Progress';

                            if (normalizedStatus === 'awaiting') {
                                nextStatus = 'In Progress';
                            } else if (normalizedStatus === 'in progress' || normalizedStatus === 'proses') {
                                nextStatus = 'Deliver';
                            } else if (normalizedStatus === 'deliver' || normalizedStatus === 'delivered') {
                                nextStatus = 'Completed';
                            } else if (normalizedStatus === 'completed' || normalizedStatus === 'done') {
                                Swal.fire({ icon: 'info', title: 'Selesai', text: 'Item sudah selesai.' });
                                return;
                            }

                            Swal.fire({
                                title: 'Update status item',
                                text: 'Ubah status item menjadi ' + nextStatus + '?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, update',
                                cancelButtonText: 'Batal'
                            }).then(function(result) {
                                if (!result.isConfirmed) return;

                                var postData = new FormData();
                                postData.append('item_id', itemId);
                                postData.append('status', nextStatus);

                                btn.disabled = true;
                                btn.textContent = 'Memproses...';

                                fetch('<?= base_url('index.php/Admin/update_kitchen_item_status') ?>', {
                                    method: 'POST',
                                    body: postData
                                })
                                .then(function(response) { return response.json(); })
                                .then(function(res) {
                                    if (res.status === 'success') {
                                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message });
                                        button.click();
                                    } else {
                                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Tidak dapat memperbarui status.' });
                                        btn.disabled = false;
                                        btn.textContent = 'Update';
                                    }
                                })
                                .catch(function() {
                                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan pada server.' });
                                    btn.disabled = false;
                                    btn.textContent = 'Update';
                                });
                            });
                        });
                    });
                })
                .catch(function() {
                    container.innerHTML = '<div class="alert alert-danger mb-0">Gagal memuat item.</div>';
                });
            });
        });
    });
</script>

<?php $this->load->view('admin/layout/footer') ?>
