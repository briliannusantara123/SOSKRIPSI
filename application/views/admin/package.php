<?php $this->load->view('admin/layout/header') ?>

<!-- jQuery (cukup sekali saja) -->
<!-- Bootstrap CSS -->
<link href="<?= base_url('assets/library/bootstrap-5/bootstrap.min.css'); ?>" rel="stylesheet" />


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Bundle (sudah include Popper) -->
<script src="<?= base_url('assets/library/bootstrap-5/bootstrap.bundle.min.js'); ?>"></script>

<!-- dselect JS -->
<script src="<?= base_url('assets/library/dselect.js'); ?>"></script>
<style>
    .image-wrapper {
  display: flex;             /* Menggunakan Flexbox */
  justify-content: center;   /* Memusatkan gambar secara horizontal */
  align-items: center;       /* Memusatkan gambar secara vertikal */
  width: 100%;               /* Agar wrapper bisa mengisi seluruh lebar */
  height: 100%;              /* Agar wrapper bisa mengisi seluruh tinggi */
}

.responsive-image {
  width: 100%;               /* Gambar mengisi lebar kontainer secara responsif */
  max-width: 400px;          /* Batas lebar maksimum gambar */
  height: auto;              /* Tinggi otomatis untuk menjaga rasio aspek */
  border-radius: 20px;       /* Menambahkan border radius */
}
.swal2-container {
    z-index: 9999 !important; /* SweetAlert muncul di atas modal */
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
                            <h5 class="m-b-10">Master Data</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-file-text"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Menu Package</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header">
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal"></i>
                                </button>
                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                    <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                    <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Menu Package</h4>
                                        <form method="get" action="<?= base_url('index.php/admin/package'); ?>">
                                            <label>Package Name</label>
                                            <input type="text" name="item_name" class="form-control" value="<?= isset($item_name) ? $item_name : '' ?>" placeholder="Search Package Name">
                                            
                                            <button type="submit" class="btn" style="background-color: #198754;color: white;border-radius: 20px;margin-top: 10px;float: right;">Search</button>
                                        </form>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#tambahevent" style="background-color: #198754;border-radius: 20px;color: white;margin-left: 20px;margin-top: 10px;">Add Package</button>
                                        </div>   
                                    </div>
                                    
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="table1">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Package Code</th>
                                                        <th>Package Name</th>
                                                        <th>Price</th>
                                                        <th>Need Stock</th>
                                                        <th>Stock</th>
                                                        <th>Package Status</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="dataTable">
                                                    <?php 
                                                    $no = 1;
                                                    foreach ($item as $d): ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td><?= $d->no ?></td>
                                                            <td><a href="#" data-bs-toggle="modal" data-bs-target="#itempaket<?= $d->id?>"><?= $d->description ?></a></td>
                                                            <td><?= 'Rp ' . number_format($d->harga_weekday, 0, ',', '.') ?></td>
                                                            <td id="stockstatus<?= $d->id ?>">
                                                                <a href="javascript:void(0);" 
                                                                   class="update-stock-status" 
                                                                   data-id="<?= $d->id ?>" 
                                                                   data-status="<?= $d->need_stock ?>">
                                                                    <label style="background-color: <?= $d->need_stock == 1 ? '#198754' : 'red' ?>;
                                                                                   border-radius: 20px; 
                                                                                   color: white; 
                                                                                   padding: 10px;">
                                                                        <?= $d->need_stock == 1 ? 'Limited' : 'Unlimited' ?>
                                                                    </label>
                                                                </a>
                                                                <input type="hidden" name="needstock" id="flagns<?= $d->id ?>" value="<?= $d->need_stock ?>">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="stock" id="stock<?= $d->id ?>" value="<?= $d->stock ?>" class="form-control" style="width: 80px;" oninput="handleInput(<?= $d->id ?>)">
                                                            </td>
                                                            <td id="status<?= $d->id ?>">
                                                                <a href="javascript:void(0);"class="update-status" data-id="<?= $d->id ?>" 
                                                                   data-status="<?= $d->is_sold_out ?>">
                                                                    <label style="background-color: <?= $d->is_sold_out == 0 ? '#198754' : 'red' ?>;
                                                                                   border-radius: 20px; 
                                                                                   color: white; 
                                                                                   padding: 10px;">
                                                                        <?= $d->is_sold_out == 0 ? 'Available' : 'Sold Out' ?>
                                                                    </label>
                                                                </a>
                                                            </td>
                                                            <!-- <td id="status<?= $d->id ?>">
                                                                <?php if ($d->is_sold_out == 0): ?>
                                                                    <a href="<?= base_url() ?>index.php/Admin/UpdateStatusStock/<?= $d->id ?>/sold"><label style="background-color: #198754;border-radius: 20px;color: white;padding: 10px;">Available</label></a>
                                                                <?php else: ?>
                                                                    <a href="<?= base_url() ?>index.php/Admin/UpdateStatusStock/<?= $d->id ?>/available"><label style="background-color: red;border-radius: 20px;color: white;padding: 10px;">Sold Out</label></a>
                                                                <?php endif ?>
                                                            </td> -->
                                                            <td>
                                                                <?php if ($d->is_active == 1): ?>
                                                                    <label style="background-color: #198754;border-radius: 20px;color: white;padding: 10px;">Active</label>
                                                                <?php else: ?>
                                                                    <label style="background-color: red;border-radius: 20px;color: white;padding: 10px;">Inactive</label>
                                                                <?php endif ?>
                                                            </td>
                                                            <td>
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModaledituser<?= $d->id?>"><i class="fas fa-pen" style="color: orange;font-size: 20px;"></i></a>
                                                                <a href="<?= base_url() ?>index.php/Admin/deletepackage/<?= $d->id ?>"><i class="fas fa-trash" style="color: red;font-size: 20px;"></i></a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?= $links2 ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>
</div>
<div class="modal fade" id="tambahevent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Add Package</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('index.php/admin/tambahpackage/') ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Package Name<span style="color: red;">*</span></label>
                            <input type="text" name="description" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Package Image</label>
                            <input type="file"id="imageInput" name="image_path" class="form-control"accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Need Stock <span style="color: red;">*</span></label>
                            <select name="need_stock" class="form-control" required="">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Stock <span style="color: red;">*</span></label>
                            <input type="number" name="stock" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Price <span style="color: red;">*</span></label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color: #198754; color: white;">Submit</button>
                </div>
                    </form>
            </div>
        </div>
</div>
<?php foreach ($item as $i): ?>
    <?php $itempackage = $this->Admin_model->getitempackage($i->no); ?>
    <div class="modal fade" id="itempaket<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754; border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Item Package <?= $i->description ?> </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <form id="formTambahItemPackage" action="<?= base_url('index.php/admin/tambahitempackage/') ?>" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="description" class="form-label">Item Name<span style="color: red;">*</span></label>
                                <input type="hidden" name="edit_id" id="edit_id">
                                <input type="hidden" name="parent_code" id="parent_code" value="<?= $i->no ?>">
                                <select name="item_code" class="form-select" id="select_box" required="">
                                    <option value="">Select Item</option>
                                    <?php foreach ($dataitem as $di): ?>
                                        <option value="<?= $di->no ?>"><?= $di->description ?></option>
                                    <?php endforeach ?>
                                </select>
                                <!-- <select name="item_code" id="edit_item_code" class="form-control" required>
                                    <option value="" selected disabled>Select Item</option>
                                    <?php foreach ($dataitem as $di): ?>
                                        <option value="<?= $di->no ?>"><?= $di->description ?></option>
                                    <?php endforeach ?>
                                </select> -->
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Varian Category <span style="color: red;">*</span></label>
                                <input type="text" name="varian_category" id="edit_varian_category" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Max Qty<span style="color: red;">*</span></label>
                                <input type="number" name="max_qty" id="edit_max_qty" class="form-control" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn" style="background-color: #198754; color: white;">Save</button>
                            </div>
                        </form>

                        <table class="table table-hover" id="table2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item Name</th>
                                    <th>Need Stock</th>
                                    <th>Stock</th>
                                    <th>Item Status</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($itempackage as $d): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($d->description) ?></td>
                                        <td id="stockstatus<?= $d->idi ?>">
                                            <a href="javascript:void(0);" class="update-stock-status" data-id="<?= $d->idi ?>" data-status="<?= $d->need_stock ?>">
                                                <label style="background-color: <?= $d->need_stock == 1 ? '#198754' : 'red' ?>; border-radius: 20px; color: white; padding: 10px;">
                                                    <?= $d->need_stock == 1 ? 'Limited' : 'Unlimited' ?>
                                                </label>
                                            </a>
                                            <input type="hidden" name="needstock" id="flagns<?= $d->idi ?>" value="<?= $d->need_stock ?>">
                                        </td>
                                        <td>
                                            <input type="number" name="stock" id="stock<?= $d->idi ?>" value="<?= $d->stock ?>" class="form-control" style="width: 80px;" oninput="handleInput(<?= $d->idi ?>)">
                                        </td>
                                        <td id="status<?= $d->idi ?>">
                                            <a href="javascript:void(0);" class="update-status" data-id="<?= $d->idi ?>" data-status="<?= $d->is_sold_out ?>">
                                                <label style="background-color: <?= $d->is_sold_out == 0 ? '#198754' : 'red' ?>; border-radius: 20px; color: white; padding: 10px;">
                                                    <?= $d->is_sold_out == 0 ? 'Available' : 'Sold Out' ?>
                                                </label>
                                            </a>
                                        </td>
                                        <td>
                                            <label style="background-color: <?= $d->is_active == 1 ? '#198754' : 'red' ?>; border-radius: 20px; color: white; padding: 10px;">
                                                <?= $d->is_active == 1 ? 'Active' : 'Inactive' ?>
                                            </label>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" 
                                               class="edit-item-package" 
                                               data-id="<?= $d->id ?>" 
                                               data-description="<?= htmlspecialchars($d->description) ?>" 
                                               data-item-code="<?= $d->item_code ?>"
                                               data-varian-category="<?= $d->varian_category ?>"
                                               data-max-qty="<?= $d->max_qty ?>">
                                               <i class="fas fa-pen" style="color: orange; font-size: 20px;"></i>
                                            </a>

                                            <a href="javascript:void(0);" class="delete-item-package" data-id="<?= $d->id ?>">
                                                <i class="fas fa-trash" style="color: red; font-size: 20px;"></i>
                                            </a>

                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
<?php endforeach ?>


<?php foreach ($item as $o): ?>
    <div class="modal fade" id="exampleModaledituser<?= $o->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Edit Package</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('index.php/admin/update_package/'.$o->id) ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Package Name<span style="color: red;">*</span></label>
                            <input type="text" name="description" class="form-control" value="<?= $o->description ?>">
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Package Image</label>
                            <input type="file"id="imageInput" name="image_path" class="form-control"accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Price <span style="color: red;">*</span></label>
                            <input type="number" name="price" class="form-control" value="<?= $o->harga_weekday ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="1" <?= $o->is_active == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $o->is_active == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color: #198754; color: white;">Submit</button>
                </div>
                    </form>
            </div>
        </div>
    </div>
<?php endforeach ?> 
<script>
$(document).ready(function(){
    const select_box_element = document.querySelector('#select_box');
    dselect(select_box_element, { search: true });
});
</script>
<?php $this->load->view('admin/layout/footer') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){
    $(document).on('click', '.edit-item-package', function() {
        let id = $(this).data('id');
        let description = $(this).data('description');
        let item_code = $(this).data('item-code');
        let varian_category = $(this).data('varian-category');
        let max_qty = $(this).data('max-qty');

        // Set value ke form modal edit
        $('#edit_id').val(id);
        $('#edit_description').val(description);
        $('#edit_item_code').val(item_code);
        $('#edit_varian_category').val(varian_category);
        $('#edit_max_qty').val(max_qty);

        // Tampilkan modal edit
        $('#editModal').modal('show');
    });

    // Event Hapus (Gunakan event delegation)
    $(document).on("click", ".delete-item-package", function () {
    let itemId = $(this).data("id");

    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "<?= $cn->color ?>",
        cancelButtonColor: "red",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= base_url('index.php/Admin/deleteitempackage/') ?>" + itemId,
                type: "POST",
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: "Data berhasil dihapus!",
                        showConfirmButton: false,
                        timer: 2000
                    });

                    var parent_code = $("#parent_code").val();
                    loadItemPackage(); // Memuat ulang tabel setelah penghapusan
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Terjadi kesalahan, coba lagi!"
                    });
                }
            });
        }
    });
});
    $("#formTambahItemPackage").submit(function(e){
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(){
                $(".btn-submit").prop("disabled", true);
            },
            success: function(response){
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil disimpan!',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    $("#formTambahItemPackage")[0].reset();
                    $(".btn-submit").prop("disabled", false);
                    $("#itempaket<?= $i->id ?>").modal("hide"); // Tutup modal setelah sukses

                    // Panggil fungsi untuk reload data tabel
                    var parent_code = $("#parent_code").val();
                    loadItemPackage('table2',parent_code);
                });
            },
            error: function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan, coba lagi!',
                });

                $(".btn-submit").prop("disabled", false);
            }
        });
    });

    function loadItemPackage() {
        var parent_code = $("#parent_code").val();
        $.ajax({
            url: "<?= base_url('index.php/admin/load_item_package/') ?>" + parent_code,
            type: "GET",
            success: function(response) {
                console.log("Data loaded:", response);
                $("#table2 tbody").html(response); // Perbarui tabel tanpa refresh
            },
            error: function() {
                console.log("Failed to load data.");
            }
        });
    }

    
});


</script>


<script>
    // Fungsi untuk memperbarui status stok (Limited/Unlimited)
    $(document).on('click', '.update-stock-status', function (e) {
        e.preventDefault();

        let element = $(this); // Elemen yang diklik
        let id = element.data('id'); // ID item
        let currentStatus = element.attr('data-status'); // Status saat ini
        let newStatus = currentStatus == 1 ? 'unlimited' : 'limited'; // Status baru

        $.ajax({
            url: "<?= base_url() ?>index.php/Admin/UpdateStockStatus",
            type: "POST",
            data: {
                id: id,
                status: newStatus
            },
            success: function (response) {
                try {
                    response = JSON.parse(response); // Parse JSON response
                } catch (e) {
                    alert("Invalid server response");
                    return;
                }

                if (response.success) {
                    // Perbarui elemen status stok
                    $('#stock' + id).val(0);
                    let updatedStatus = response.newStatus === 'limited' ? 1 : 0;
                    $('#flagns' + id).val(updatedStatus);
                    // Update data-status dan label
                    element.attr('data-status', updatedStatus); // Perbarui atribut data-status
                    element.data('status', updatedStatus); // Sinkronisasi memori jQuery
                    element.find('label')
                        .text(response.newStatus === 'limited' ? 'Limited' : 'Unlimited')
                        .css('background-color', response.newStatus === 'limited' ? '#198754' : 'red');

                    // Perbarui elemen #status{id} sesuai status baru
                    updateStatusLabel(id, updatedStatus); // Update status label
                } else {
                    alert("Failed to update stock status: " + (response.error || 'Unknown error'));
                }
            },
            error: function () {
                alert("An error occurred while updating stock status.");
            }
        });
    });

    // Fungsi untuk memperbarui status (Available/Sold Out)
    $(document).on('click', '.update-status', function (e) {
        e.preventDefault();

        let element = $(this); // Elemen yang diklik
        let id = element.data('id'); // ID item
        let currentStatus = element.data('status'); // Status saat ini (0 = Available, 1 = Sold Out)
        let newStatus = currentStatus == 1 ? 0 : 1; // Status baru
        let newStock = newStatus == 1 ? 0 : 1; // Stok baru (1 untuk Available, 0 untuk Sold Out)

        $.ajax({
            url: "<?= base_url() ?>index.php/Admin/UpdateStatusStock",
            type: "POST",
            data: {
                id: id,
                status: newStatus,
                stock: newStock,
                flagns: $('#flagns' + id).val()
            },
            success: function (response) {
                try {
                    response = JSON.parse(response); // Parse JSON jika respons berupa teks JSON
                } catch (e) {
                    alert("Invalid server response");
                    return;
                }

                if (response.success) {
                    // Perbarui status dan stok
                    element.attr('data-status', newStatus); // Perbarui atribut data-status
                    
                    // Perbarui label dan warna
                    let label = element.find('label');
                    label.text(newStatus == 1 ? 'Sold Out' : 'Available')
                         .css('background-color', newStatus == 1 ? 'red' : '#198754');

                    // Perbarui nilai stok
                    if ($('#flagns' + id).val() == 0) {
                        $('#stock' + id).val(0);
                    }else{
                        $('#stock' + id).val(newStock);
                    }
                    

                    // Jika status stok berubah menjadi unlimited, set status "Sold Out" menjadi "Available"
                    if (newStatus === 0) {
                        $('#status' + id).html(`
                            <a href="javascript:void(0);" class="update-status" data-id="${id}" data-status="0">
                                <label style="background-color: #198754; border-radius: 20px; color: white; padding: 10px;">
                                    Available
                                </label>
                            </a>
                        `);
                    }
                } else {
                    alert("Failed to update status: " + (response.error || 'Unknown error'));
                }
            },
            error: function () {
                alert("An error occurred while updating status.");
            }
        });
    });

    // Fungsi untuk memperbarui label status berdasarkan status stok
    function updateStatusLabel(id, status) {
        let labelHtml = '';

        if (status === 1) {
            // Jika status 'limited' atau 'sold out'
            labelHtml = `
                <a href="javascript:void(0);" class="update-status" data-id="${id}" data-status="1">
                    <label style="background-color: red; border-radius: 20px; color: white; padding: 10px;">
                        Sold Out
                    </label>
                </a>
            `;
        } else {
            // Jika status 'unlimited' atau 'available'
            labelHtml = `
                <a href="javascript:void(0);" class="update-status" data-id="${id}" data-status="0">
                    <label style="background-color: #198754; border-radius: 20px; color: white; padding: 10px;">
                        Available
                    </label>
                </a>
            `;
        }

        // Perbarui elemen #status{id} dengan label baru
        $('#status' + id).html(labelHtml);
    }




    function handleInput(id) {
        // Ambil elemen input
        const stockInput = document.getElementById('stock' + id);
        const value = stockInput.value;

        // Jika sudah mengetik setengah dari panjang angka (misalnya lebih dari 2 karakter)
        if (value.length >= Math.ceil(value.length / 2)) {
            updateStock(id);
        }
    }
    function updateStock(id) {
        // Ambil elemen input
        const stockInput = document.getElementById('stock' + id);
        const stockValue = parseInt(stockInput.value);
        const flagns = document.getElementById('flagns' + id);
        const flag = parseInt(flagns.value);

        $.ajax({
            url: '<?= base_url('index.php/Admin/UpdateStock') ?>',  // Controller dan method di CodeIgniter
            type: 'POST',
            data: {
                id: id,
                stock: stockValue,
                flag: flag,
            },
            success: function(response) {
                const statusElement = document.getElementById('status' + id);
                const stockstatus = document.getElementById('stockstatus' + id);

                if (stockValue > 0) {
                    // statusElement.innerHTML = `<a href="<?= base_url() ?>index.php/Admin/UpdateStatusStock/${id}/sold">
                    //     <label style="background-color: #198754; border-radius: 20px; color: white; padding: 10px;">Available</label>
                    // </a>`;
                    statusElement.innerHTML = `<a href="javascript:void(0);" class="update-status" data-id="${id}" data-status="0">
                    <label style="background-color: #198754; border-radius: 20px; color: white; padding: 10px;">
                        Available
                    </label>
                </a>`;
                } else {
                    // statusElement.innerHTML = `<a href="<?= base_url() ?>index.php/Admin/UpdateStatusStock/${id}/available">
                    //     <label style="background-color: red; border-radius: 20px; color: white; padding: 10px;">Sold Out</label>
                    // </a>`;
                    statusElement.innerHTML = `<a href="javascript:void(0);" class="update-status" data-id="${id}" data-status="1">
                        <label style="background-color: red; border-radius: 20px; color: white; padding: 10px;">
                            Sold Out
                        </label>
                    </a>`;
                }

                if (stockValue >= 1) {
                    // stockstatus.innerHTML = `<a href="<?= base_url() ?>index.php/Admin/UpdateStockStatus/${id}/limited">
                    //     <label style="background-color: #198754; border-radius: 20px; color: white; padding: 10px;">Limited</label>
                    // </a>
                    // <input type="hidden" name="needstock" id="flagns${id}" value="1">`;
                    stockstatus.innerHTML = `<a href="javascript:void(0);"class="update-stock-status" data-id="${id}" data-status="1"><label style="background-color:#198754;border-radius: 20px;color: white;padding: 10px;">Limited</label></a>
                    <input type="hidden" name="needstock" id="flagns${id}" value="1">`;
                } else {
                    if (flag == 0) {
                    //     stockstatus.innerHTML = `<a href="<?= base_url() ?>index.php/Admin/UpdateStockStatus/${id}/unlimited">
                    //     <label style="background-color: red; border-radius: 20px; color: white; padding: 10px;">Unlimited</label>
                    // </a>
                    // <input type="hidden" name="needstock" id="flagns${id}" value="0">`;
                    stockstatus.innerHTML = `<a href="javascript:void(0);"class="update-stock-status" data-id="${id}" data-status="0"><label style="background-color:red;border-radius: 20px;color: white;padding: 10px;">Unlimited</label></a>
                    <input type="hidden" name="needstock" id="flagns${id}" value="0">`;
                    }
                    
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error); // Tampilkan log error
            }
        });
    }
    function insertRemark(id) {
        // Ambil elemen input
        const remark = document.getElementById('remark' + id);
        const remarkvalue = remark.value;

        $.ajax({
            url: '<?= base_url('index.php/Admin/insertRemark') ?>',  // Controller dan method di CodeIgniter
            type: 'POST',
            data: {
                id: id,
                remark: remarkvalue,
            },
            success: function(response) {
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error); // Tampilkan log error
            }
        });
    }

</script>

-->
   
 