<?php $this->load->view('admin/layout/header') ?>

<style>
.card-hover {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2) !important;
    cursor: pointer;
}
</style>

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Orders</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('index.php/Admin') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Orders</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        
        <!-- [ Main Content ] start -->
        <div class="row">
            <?php if (!empty($pesanan_list)): ?>
                <?php foreach ($pesanan_list as $pesanan): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <!-- Card yang dapat diklik memicu modal -->
                        <a href="javascript:void(0)" 
                           data-toggle="modal" data-target="#detailModal" 
                           data-id="<?= $pesanan->id ?>" 
                           data-customer="<?= $pesanan->customer_name ?>" 
                           data-table="<?= $pesanan->table_number ?>" 
                           data-status="<?= $pesanan->status ?>" 
                           data-total="<?= number_format($pesanan->total, 0, ',', '.') ?>" 
                           style="text-decoration: none;" class="btn-detail-pesanan">
                            <div class="card support-bar overflow-hidden card-hover" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                <div class="card-body pb-3 pt-4">
                                    <h2 class="text-c-blue text-center mb-1">Table <?= $pesanan->table_number ?></h2>
                                    <h6 class="text-center text-muted mb-0"><?= $pesanan->customer_name ?></h6>
                                </div>
                                <?php 
                                    // Menentukan warna background footer berdasarkan status
                                    $bg_color = 'bg-primary';
                                    if(strtolower($pesanan->status) == 'paid') $bg_color = 'bg-success';
                                    elseif(strtolower($pesanan->status) == 'pending') $bg_color = 'bg-warning text-dark';
                                    elseif(strtolower($pesanan->status) == 'cooking') $bg_color = 'bg-info';
                                ?>
                                <div class="card-footer <?= $bg_color ?> text-white" style="border-radius: 0 0 10px 10px; padding: 15px 10px;">
                                    <div class="row text-center">
                                        <div class="col">
                                            <h6 class="m-0 text-white" style="<?= strtolower($pesanan->status) == 'pending' ? 'color: #333 !important;' : '' ?>">
                                                Rp <?= number_format($pesanan->total, 0, ',', '.') ?>
                                            </h6>
                                            <span style="<?= strtolower($pesanan->status) == 'pending' ? 'color: #333;' : '' ?>">Total</span>
                                        </div>
                                        <div class="col" style="border-left: 1px solid rgba(255,255,255,0.3);">
                                            <h6 class="m-0 text-white" style="<?= strtolower($pesanan->status) == 'pending' ? 'color: #333 !important;' : '' ?>">
                                                <?= $pesanan->status ?>
                                            </h6>
                                            <span style="<?= strtolower($pesanan->status) == 'pending' ? 'color: #333;' : '' ?>">Status</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">Belum ada data pesanan.</div>
                </div>
            <?php endif; ?>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

<!-- Modal Detail Pesanan -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <h5 class="modal-title" id="detailModalLabel">Detail Pesanan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="background-color: #f4f7fa;">
          <input type="hidden" id="modal_id_pesanan">
          
          <div id="modal_item_list" style="max-height: 60vh; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
            <div class="text-center text-muted"><br>Memuat data...</div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between align-items-center bg-white" style="border-top: 1px solid #e9ecef;">
          <div>
              <small class="text-muted d-block">Total Pembayaran</small>
              <h5 class="m-0 font-weight-bold text-primary" id="modal_total_footer">Rp 0</h5>
          </div>
          <div>
              <a href="#" id="btn_modal_payment" class="btn btn-success d-none">Payment</a>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var detailButtons = document.querySelectorAll('.btn-detail-pesanan');
    detailButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var customer = this.getAttribute('data-customer');
            var table = this.getAttribute('data-table');
            var status = this.getAttribute('data-status');
            var total = this.getAttribute('data-total');

            document.getElementById('modal_id_pesanan').value = id;
            document.getElementById('modal_total_footer').innerHTML = 'Rp ' + total;

            var btnPayment = document.getElementById('btn_modal_payment');
            if (status.toLowerCase() === 'pending') {
                btnPayment.classList.remove('d-none');
                btnPayment.href = '<?= base_url('index.php/Admin/payment_pesanan/') ?>' + id;
            } else {
                btnPayment.classList.add('d-none');
            }

            var itemListContainer = document.getElementById('modal_item_list');
            itemListContainer.innerHTML = '<div class="text-center text-muted"><br>Memuat data item...</div>';

            var formData = new FormData();
            formData.append('id_trans', id);
            
            fetch('<?= base_url('index.php/Admin/get_pesanan_detail') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                var html = '';
                if(data.length > 0) {
                    var groupedData = {
                        'IN PROGRESS': [],
                        'DELIVERED': [],
                        'COMPLETED': []
                    };
                    data.forEach(item => {
                        if (groupedData[item.item_status]) {
                            groupedData[item.item_status].push(item);
                        } else {
                            groupedData['IN PROGRESS'].push(item);
                        }
                    });

                    for (const [statusGroup, items] of Object.entries(groupedData)) {
                        if (items.length > 0) {
                            html += '<div class="mt-3 mb-2 px-1"><h6 class="text-uppercase font-weight-bold" style="border-bottom: 2px solid #ddd; padding-bottom: 5px; color: #555;">' + statusGroup + '</h6></div>';
                            items.forEach(item => {
                                var imgPath = '<?= base_url('assets/images/userkosong.png') ?>';
                                if (item.image_path) {
                                    if (item.image_path.startsWith('data:image')) {
                                        imgPath = item.image_path;
                                    } else {
                                        imgPath = 'data:image/jpeg;base64,' + item.image_path;
                                    }
                                }
                                var unitPrice = parseInt(item.unit_price);
                                var qty = parseInt(item.qty);
                                var subtotal = unitPrice * qty;
                                
                                html += `
                                <div class="card mb-3" style="border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: none;">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <img src="${imgPath}" alt="${item.item_name}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; margin-right: 15px;">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 font-weight-bold">${item.item_name}</h6>
                                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">Harga: Rp ${unitPrice.toLocaleString('id-ID')}</p>
                                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">Qty: <strong class="text-dark">${qty}</strong></p>
                                            </div>
                                            <div class="text-right ml-2">
                                                <small class="text-muted d-block">Subtotal</small>
                                                <h6 class="mb-0 font-weight-bold text-primary">Rp ${subtotal.toLocaleString('id-ID')}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            });
                        }
                    }
                } else {
                    html = '<div class="alert alert-warning text-center">Tidak ada item ditemukan.</div>';
                }
                itemListContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                itemListContainer.innerHTML = '<div class="alert alert-danger text-center">Gagal memuat detail item.</div>';
            });
        });
    });
});
</script>

<?php $this->load->view('admin/layout/footer') ?>