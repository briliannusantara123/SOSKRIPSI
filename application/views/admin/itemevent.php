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
                            <li class="breadcrumb-item"><a href="#!">Menu Item Event</a></li>
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
                                        <h4>Menu Item Event</h4>
                                        <form method="get" action="<?= base_url('index.php/admin/itemevent'); ?>">
                                            <label>Item Name</label>
                                            <input type="text" name="item_name" class="form-control" value="<?= isset($item_name) ? $item_name : '' ?>" placeholder="Search Item Name">
                                            <label>Sub Category</label>
                                            <select name="sub" class="form-control">
                                                <option value="">All Categories</option>
                                                <?php foreach ($sub as $s): ?>
                                                    <option value="<?= $s->sub_category ?>" <?= $subc == $s->sub_category ? 'selected' : '' ?>><?= $s->sub_category ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <!-- <label>By Division</label>
                                            <select name="divisi" class="form-control">
                                                <option value="">All Division</option>
                                                <?php foreach ($divisi as $d): ?>
                                                    <option value="<?= $d->id ?>" <?= $divc == $d->id ? 'selected' : '' ?>><?= $d->description ?></option>
                                                <?php endforeach ?>
                                            </select> -->
                                            <button type="submit" class="btn" style="background-color: #198754;color: white;border-radius: 20px;margin-top: 10px;float: right;">Search</button>
                                        </form>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#tambahevent" style="background-color: #198754;border-radius: 20px;color: white;margin-left: 20px;margin-top: 10px;">Add Item Event</button>
                                            <button type="button" class="btn btn-warning" style="border-radius: 20px;color: black;margin-top: 10px" data-bs-toggle="modal" data-bs-target="#importcsv">
                                                  Import Csv
                                                </button>
                                        </div>   
                                    </div>
                                    
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <!-- <th>Image</th> -->
                                                        <th>Item Code</th>
                                                        <th>Item Name</th>
                                                        <th>Date From</th>
                                                        <th>Date To</th>
                                                        <th>Time From</th>
                                                        <th>Time To</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $no = 1;
                                                    foreach ($item as $d): ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td><?= $d->no ?></td>
                                                            <td><?= $d->description ?></td>
                                                            <td><?= $d->date_from ?></td>
                                                            <td><?= $d->date_to ?></td>
                                                            <td><?= $d->time_from ?></td>
                                                            <td><?= $d->time_to ?></td>
                                                            <td>
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModaledituser<?= $d->id?>"><i class="fas fa-pen" style="color: orange;font-size: 20px;"></i></a>
                                                                <a href="<?= base_url() ?>index.php/Admin/deleteitemevent/<?= $d->id ?>"><i class="fas fa-trash" style="color: red;font-size: 20px;"></i></a>
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
        <div class="modal-dialog">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Add Item Event</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('index.php/admin/tambahitemevent/') ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Item Name<span style="color: red;">*</span></label>
                            <select name="item_code" class="form-control" required="">
                                <option value="" selected="" disabled="">Select Item</option>
                                <?php foreach ($dataitem as $di): ?>
                                    <option value="<?= $di->no ?>"><?= $di->description ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Date From <span style="color: red;">*</span></label>
                            <input type="date" name="date_from" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Date To <span style="color: red;">*</span></label>
                            <input type="date" name="date_to" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Time From <span style="color: red;">*</span></label>
                            <input type="number" name="time_from" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Time To <span style="color: red;">*</span></label>
                            <input type="number" name="time_to" class="form-control" required>
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
<div class="modal fade" id="importcsv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Import Csv Event</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= site_url('admin/import_csv'); ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Select File Csv<span style="color: red;">*</span></label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn" style="background-color: #198754; color: white;">Import</button>
                    </div>
                </form>
            </div>
        </div>
</div>
<?php foreach ($item as $o): ?>
    <div class="modal fade" id="exampleModaledituser<?= $o->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"  style="border-radius: 20px;">
                <div class="modal-header" style="background-color: #198754;border-radius: 20px;">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: white;">Edit Item Event</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('index.php/admin/update_itemEvent/'.$o->id) ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $o->id ?>">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Item Name <span style="color: red;">*</span></label>
                            <select class="form-control" name="item_code" required="">
                                <?php foreach ($dataitem as $i): ?>
                                    <option value="<?= $i->no ?>" <?= $o->no == $i->no ? 'selected' : '' ?>><?= $i->description ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Date From <span style="color: red;">*</span></label>
                            <input type="date" name="date_from" class="form-control" value="<?= $o->date_from ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Date To <span style="color: red;">*</span></label>
                            <input type="date" name="date_to" class="form-control" value="<?= $o->date_to ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Time From <span style="color: red;">*</span></label>
                            <input type="number" name="time_from" class="form-control" value="<?= $o->time_from ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Time To <span style="color: red;">*</span></label>
                            <input type="number" name="time_to" class="form-control" value="<?= $o->time_to ?>" required>
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

<?php $this->load->view('admin/layout/footer') ?>

-->
   
 