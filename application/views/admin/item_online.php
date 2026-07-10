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
                            <li class="breadcrumb-item"><a href="#!">Online Menu Item Mapping</a></li>
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
                                        <h4>Menu Item Online</h4>
                                        <form method="get" action="<?= base_url('index.php/admin/item_online'); ?>">
                                            <label>Item Name</label>
                                            <input type="text" name="item_name" class="form-control" value="<?= isset($item_name) ? $item_name : '' ?>" placeholder="Search Item Name">
                                            <button type="submit" class="btn" style="background-color: #198754;color: white;border-radius: 20px;margin-top: 10px;float: right;">Search</button>
                                        </form>
                                    </div>
                                    <div class="card-body table-border-style">
                                        <div class="container">
                                            <button class="btn" type="button" style="background-color: #198754;color: white;border-radius: 10px;margin-bottom: 10px;float: left;" data-bs-toggle="modal" data-bs-target="#additemonline">Add Item Online</button>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <!-- <th>Image</th> -->
                                                        <th>Item Code Dine In</th>
                                                        <th>Item Name Dine In</th>
                                                        <th>Item Code Online</th>
                                                        <th>Item Name Online</th>
                                                        <th>Created Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $no = 1;
                                                    foreach ($item as $d): ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td><?= $d->item_code_dinein ?></td>
                                                            <td><?= $d->item_name_dinein ?></td>
                                                            <td><?= $d->item_code_online ?></td>
                                                            <td><?= $d->item_name_online ?></td>
                                                            <td><?= $d->created_date ?></td>
                                                            
                                                            <td>
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModaledituser<?= $d->id?>"><i class="fas fa-pen" style="color: orange;font-size: 20px;"></i></a>
                                                                <a href="<?= base_url() ?>index.php/Admin/deleteitemonline/<?= $d->id ?>"><i class="fas fa-trash" style="color: red;font-size: 20px;"></i></a>
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
<div class="modal fade" id="additemonline" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Add Item Online</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('index.php/admin/insert_item_online/') ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Item Name DineIN <span style="color: red;">*</span></label>
                            <select class="form-select" id="select_box" name="item_dine">
                                <option selected="" disabled="">Select Item</option>
                                <?php foreach ($dataitem as $di): ?>
                                    <option value="<?= $di->no ?>"><?= $di->description ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Item Name Online <span style="color: red;">*</span></label>
                            <select class="form-select" id="select_box2" name="item_online">
                                <option selected="" disabled="">Select Item</option>
                                <?php foreach ($dataitemonline as $di): ?>
                                    <option value="<?= $di->no ?>"><?= $di->description ?></option>
                                <?php endforeach ?>
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
<?php foreach ($item as $o): ?>
<div class="modal fade" id="exampleModaledituser<?= $o->id ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                <h1 class="modal-title fs-5" style="color: white;">Edit Item Online</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="<?= base_url('index.php/admin/update_item_online/'.$o->id) ?>" method="POST">
                <div class="modal-body">

                    <input type="hidden" name="id" value="<?= $o->id ?>">

                    <!-- ✅ SELECT ITEM DINE IN -->
                    <div class="mb-3">
                        <label class="form-label">Item Name Dine In <span style="color:red">*</span></label>
                        <select class="form-select" id="select_box3" name="item_dine" required>
                            <option value="" disabled>Select Item</option>
                            <?php foreach ($dataitem as $di): ?>
                                <option value="<?= $di->no ?>"
                                    <?= ($di->no == $o->item_code_dinein) ? 'selected' : '' ?>>
                                    <?= $di->description ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <!-- ✅ SELECT ITEM ONLINE -->
                    <div class="mb-3">
                        <label class="form-label">Item Name Online <span style="color:red">*</span></label>
                        <select class="form-select" id="select_box4" name="item_online" required>
                            <option value="" disabled>Select Item</option>
                            <?php foreach ($dataitemonline as $dio): ?>
                                <option value="<?= $dio->no ?>"
                                    <?= ($dio->no == $o->item_code_online) ? 'selected' : '' ?>>
                                    <?= $dio->description ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color:#198754;color:white;">
                        Submit
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<?php endforeach ?>


<?php $this->load->view('admin/layout/footer') ?>
<script>
$(document).ready(function(){
    const select_box_element = document.querySelector('#select_box');
    dselect(select_box_element, { search: true });
    const select_box_element2 = document.querySelector('#select_box2');
    dselect(select_box_element2, { search: true });
    const select_box_element3 = document.querySelector('#select_box3');
    dselect(select_box_element3, { search: true });
    const select_box_element4 = document.querySelector('#select_box4');
    dselect(select_box_element4, { search: true });
});
</script>
-->
   
 