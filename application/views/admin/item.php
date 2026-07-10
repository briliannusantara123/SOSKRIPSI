<?php $this->load->view('admin/layout/header') ?>
<style>
    .image-wrapper {
  display: flex;             /* Menggunakan Flexbox */
  justify-content: center;   /* Memusatkan gambar secara horizontal */
  align-items: center;       /* Memusatkan gambar secara vertikal */
  width: 100%;               /* Agar wrapper bisa mengisi seluruh lebar */
  height: 100%;              /* Agar wrapper bisa mengisi seluruh tinggi */
}

.responsive-image {
  width: 50%;               /* Gambar mengisi lebar kontainer secara responsif */
  max-width: 400px;          /* Batas lebar maksimum gambar */
  height: auto;              /* Tinggi otomatis untuk menjaga rasio aspek */
  border-radius: 20px;       /* Menambahkan border radius */
  display: block;
    margin: 10px auto;
}

</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Bundle (sudah include Popper) -->
<script src="<?= base_url('assets/library/bootstrap-5/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('assets/library/dselect.js'); ?>"></script>
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
                            <li class="breadcrumb-item"><a href="#!">Menu Item</a></li>
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
                                        <h4>Menu Item</h4>
                                        <form method="get" action="<?= base_url('index.php/admin/item'); ?>">
                                            <label>Item Name</label>
                                            <input type="text" name="item_name" class="form-control" value="<?= isset($item_name) ? $item_name : '' ?>" placeholder="Search Item Name">
                                            <label>Sub Category</label>
                                            <select name="sub" class="form-control">
                                                <option value="">All Categories</option>
                                                <?php foreach ($sub as $s): ?>
                                                    <option value="<?= $s->sub_category ?>" <?= $subc == $s->sub_category ? 'selected' : '' ?>><?= $s->sub_category ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <label>By Division</label>
                                            <select name="divisi" class="form-control">
                                                <option value="">All Division</option>
                                                <?php foreach ($divisi as $d): ?>
                                                    <option value="<?= $d->id ?>" <?= $divc == $d->id ? 'selected' : '' ?>><?= $d->description ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <button type="submit" class="btn" style="background-color: #198754;color: white;border-radius: 20px;margin-top: 10px;float: right;">Search</button>
                                        </form>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="<?= base_url() ?>index.php/Admin/tambahitem" class="btn" style="background-color: #198754;color: white;border-radius: 20px;margin-top: 10px;margin-left:20px;float: left;">+ Add New Item</a>
                                            
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#addgambar" class="btn" style="background-color: #198754;color: white;border-radius: 20px;margin-top: 10px;margin-left:20px;float: left;">Add New Image</button>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Image</th>
                                                        <th>Item Code</th>
                                                        <th>Item Name</th>
                                                        <th>Remark</th>
                                                        <th>Sub Category</th>
                                                        <th>Stock Status</th>
                                                        <th>Stock</th>
                                                        <th>Item Status</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $no = 1;
                                                    foreach ($item as $d): ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                             <td>
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#ubahgambar<?= $d->id?>">
                                                                    <img src="<?= !empty($d->image_path) ? base_url($d->image_path) : base_url('assets/noimage.jpg') ?>" 
                                                                         style="width: 90px; height: 90px; border-radius: 20px;">
                                                                </a>
                                                            </td>
                                                            
                                                            <td><?= $d->no ?></td>
                                                            <td><?= $d->description ?></td>
                                                            <td>
                                                                <input type="text" id="remark<?= $d->id ?>" name="remark" class="form-control" value="<?= $d->remarks ?>" oninput="insertRemark(<?= $d->id ?>)">
                                                            </td>
                                                            <td><?= $d->sub_category ?></td>
                                                            <!-- <td id="stockstatus<?= $d->id ?>">
                                                                <?php if ($d->need_stock == 1): ?>
                                                                    <a href="<?= base_url() ?>index.php/Admin/UpdateStockStatus/<?= $d->id ?>/unlimited">
                                                                        <label style="background-color: #198754;border-radius: 20px;color: white;padding: 10px;">Limited</label>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <a href="<?= base_url() ?>index.php/Admin/UpdateStockStatus/<?= $d->id ?>/limited">
                                                                        <label style="background-color: red;border-radius: 20px;color: white;padding: 10px;">Unlimited</label>
                                                                    </a>
                                                                <?php endif ?>
                                                                <input type="hidden" name="needstock" id="flagns<?= $d->id ?>" value="<?= $d->need_stock ?>">
                                                            </td> -->
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
                                                                <a href="<?= base_url() ?>index.php/Admin/edititem/<?= $d->id ?>"><i class="fas fa-pen" style="color: orange;font-size: 20px;"></i></a>
                                                                <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModaledituser<?= $d->id?>"><i class="fas fa-pen" style="color: orange;font-size: 20px;"></i></a> -->
                                                                <a href="<?= base_url() ?>index.php/Admin/deleteitem/<?= $d->id ?>"><i class="fas fa-trash" style="color: red;font-size: 20px;"></i></a>
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

<?php foreach ($item as $o): ?>
    <div class="modal fade" id="exampleModaledituser<?= $o->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Edit Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('index.php/admin/update_item/'.$o->id) ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $o->id ?>">
                         <div class="mb-3">
                            <label for="formFile" class="form-label">Image</label>
                            <div class="row">
                                <div class="col-9">
                                    <br>    
                                    <input type="file" id="imageInput<?= $o->id ?>" name="image_path" class="form-control" accept="image/*">
                                </div>
                                <!-- <div class="col-3">
                                    <?php if ($o->image_path): ?>
                                        <img id="previewImage<?= $o->id ?>" src="<?= $o->image_path ?>" style="width: 100px; height: 100px; border-radius: 20px; float: right;">
                                        <a href="<?= base_url('index.php/admin/remove_image/'.$o->id) ?>" style="color: red">Remove Img</a>
                                    <?php else: ?>    
                                        <img id="previewImage<?= $o->id ?>" src="<?= base_url();?>assets/noimage.jpg" style="width: 90px; height: 90px; border-radius: 20px; float: right;">
                                    <?php endif ?>
                                </div> -->
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Description <span style="color: red;">*</span></label>
                            <input type="text" name="description" class="form-control" value="<?= $o->description ?>" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="formFile" class="form-label">Need Stock?</label>
                            <select class="form-control" name="need_stock">
                                <option value="1" <?= $o->need_stock == 1 ? 'selected' : '' ?>>Yes</option>
                                <option value="0" <?= $o->need_stock == 0 ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Stock <span style="color: red;">*</span></label>
                            <input type="number" name="stock" class="form-control" value="<?= $o->stock ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Stock Status</label>
                            <select class="form-control" name="stock_status">
                                <option value="0" <?= $o->is_sold_out == 0 ? 'selected' : '' ?>>Available</option>
                                <option value="1" <?= $o->is_sold_out == 1 ? 'selected' : '' ?>>Sold Out</option>
                            </select>
                        </div> -->
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

    <!-- <script>
        document.getElementById('imageInput<?= $o->id ?>').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage<?= $o->id ?>').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script> -->
<?php endforeach ?> 
<!-- GAMBAR -->
<?php foreach ($item as $o): ?>
<div class="modal fade" id="ubahgambar<?= $o->id ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px;">

            <div class="modal-header" style="background-color: #198754;">
                <h5 class="modal-title text-white">Edit Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="<?= base_url('index.php/admin/update_image/'.$o->no) ?>" method="POST" enctype="multipart/form-data">
                    
                    <input type="hidden" name="id" value="<?= $o->id ?>">

                    <div class="text-center mb-3">
                        <?php 
                        $img = !empty($o->image_path) 
                            ? base_url($o->image_path) 
                            : base_url('assets/noimage.jpg'); 
                        ?>

                        <img id="previewImageEdit<?= $o->id ?>" 
                             src="<?= $img ?>" 
                             style="width:120px; height:120px; object-fit:cover; border-radius:10px;">
                        
                        <?php if (!empty($o->image_path)): ?>
                            <br>
                            <a href="<?= base_url('index.php/admin/remove_image/'.$o->no) ?>" 
                               style="color:red;">Remove Image</a>
                        <?php endif; ?>
                    </div>

                    <input type="file" 
                           id="imageInputEdit<?= $o->id ?>" 
                           name="image_path" 
                           class="form-control" 
                           accept="image/*">
                    <small class="text-muted">
                        Maksimal upload gambar 100KB dan rekomendasi resolusi 1080x1350
                    </small>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>

                </form>
        </div>
    </div>
</div>
<?php endforeach; ?>
<div class="modal fade" id="addgambar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Add New Image</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('index.php/admin/save_image') ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $o->id ?>">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-12">
                                    <label>Item Name</label>
                                    <select class="form-control" name="item_code" id="select_box">
                                        <?php foreach ($allitem as $i):?>
                                            <option value="<?= $i->no ?>"><?= $i->description ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <br>
                                <div class="col-12">
                                    <img id="previewImage" 
                                     src="<?= base_url();?>assets/noimage.jpg" 
                                     alt="Image" 
                                     class="responsive-image"
                                     style="display:none; width:150px; height:150px; object-fit:cover; border-radius:10px;">
                                </div>
                                <div class="col-12">
                                    <br>    
                                    <input type="file" id="imageInput" name="image_path" class="form-control" accept="image/*">
                                </div>
                                <small class="text-muted">
                                    Maksimal upload gambar 100KB dan rekomendasi resolusi 1080x1350
                                </small>
                            </div>
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
<?php $this->load->view('admin/layout/footer') ?>
<script>
document.querySelectorAll("input[type='file']").forEach(input => {
    input.addEventListener("change", function(event) {
        const file = event.target.files[0];
        const id = this.id.replace('imageInputEdit', '');
        const preview = document.getElementById('previewImageEdit' + id);

        if (file && preview) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
<script>
document.getElementById('imageInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('previewImage');

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block'; // tampilkan gambar
        }

        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none'; // kalau tidak ada file
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
<script>
$(document).ready(function(){
    const select_box_element = document.querySelector('#select_box');
    dselect(select_box_element, { search: true });
});
</script>
-->
   
 