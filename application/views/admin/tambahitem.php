<?php $this->load->view('admin/layout/header') ?>

<style>
body { background:#f4f6f9; }

/* HEADER */
.modern-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}
.header-title{ font-size:22px; font-weight:600; }
.header-sub{ font-size:13px; color:#888; }
.btn-header{ border-radius:10px; padding:8px 18px; }

/* CARD */
.modern-card{
    border:none;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
}

/* INPUT */
.modern-input{
    border-radius:10px;
    border:1px solid #ddd;
    padding:10px 12px;
    transition:0.2s;
}
.modern-input:focus{
    border-color:#4e73df;
    box-shadow:0 0 0 3px rgba(78,115,223,0.15);
}

label{ font-weight:500; margin-bottom:5px; }

/* SWITCH */
.switch{ position:relative; width:45px; height:22px; }
.switch input{ display:none; }

.slider{
    position:absolute;
    background:#ccc;
    border-radius:34px;
    top:0; left:0; right:0; bottom:0;
}
.slider:before{
    content:"";
    position:absolute;
    height:16px; width:16px;
    left:3px; bottom:3px;
    background:#fff;
    border-radius:50%;
    transition:.3s;
}
input:checked + .slider{ background:#4e73df; }
input:checked + .slider:before{ transform:translateX(22px); }

.btn-modern{
    border-radius:10px;
    padding:10px 25px;
    font-weight:500;
}

.section-title{
    font-size:14px;
    font-weight:600;
    margin-bottom:15px;
    color:#555;
}
</style>

<div class="pcoded-main-container">
<div class="pcoded-content">

<!-- HEADER -->
<div class="modern-header">
    <div>
        <div class="header-title">Tambah Item</div>
        <div class="header-sub">Master Data / Item</div>
    </div>
    <a href="<?= base_url('index.php/Admin/item') ?>" class="btn btn-light btn-header">
        ← Kembali
    </a>
</div>

<!-- CARD -->
<div class="card modern-card">
<div class="card-body">

<form action="<?= base_url('index.php/Admin/simpanitem') ?>" method="POST">

<div class="row">

<!-- ================= LEFT ================= -->
<div class="col-md-8">
<div class="row">

<!-- KODE -->
<div class="col-md-6 mb-3">
    <label>Kode</label>
    <input type="text" class="form-control modern-input" name="no_series"
           value="<?= isset($no)?$no:'' ?>" readonly>
</div>

<!-- ITEM INDUK -->
<div class="col-md-6 mb-3">
    <label>Item Induk</label>
    <select class="form-control modern-input" name="parent_id" disabled="">
        <option value="">Pilih Item</option>
        <?php if(!empty($item)) foreach($item as $i): ?>
            <option value="<?= $i->id ?>"><?= $i->description ?></option>
        <?php endforeach ?>
    </select>
</div>

<!-- DESKRIPSI -->
<div class="col-md-12 mb-3">
    <label>Deskripsi</label>
    <input type="text" class="form-control modern-input" name="description">
</div>

<!-- HARGA -->
<div class="col-md-4 mb-3">
    <label>Harga Weekday</label>
    <input type="number" class="form-control modern-input" name="harga_weekday">
</div>

<div class="col-md-4 mb-3">
    <label>Harga Weekend</label>
    <input type="number" class="form-control modern-input" name="harga_weekend">
</div>

<div class="col-md-4 mb-3">
    <label>Harga Holiday</label>
    <input type="number" class="form-control modern-input" name="harga_holiday">
</div>

<!-- KATEGORI -->
<div class="col-md-6 mb-3">
    <label>Kategori</label>
    <select class="form-control modern-input" name="item_category">
        <option value="">Pilih</option>
        <?php foreach($category as $c): ?>
            <option value="<?= $c->description ?>"><?= $c->description ?></option>
        <?php endforeach ?>
    </select>
</div>

<!-- SUB KATEGORI -->
<div class="col-md-6 mb-3">
    <label>Sub Kategori</label>
    <select class="form-control modern-input" name="item_sub_category">
        <option value="">Pilih</option>
        <?php foreach($sub as $s): ?>
            <option value="<?= $s->description ?>"><?= $s->description ?></option>
        <?php endforeach ?>
    </select>
</div>

<!-- ITEM PAKET -->
<div class="col-md-12 mb-3">
    <label>Item Paket</label>
    <select class="form-control modern-input" name="item_package">
        <option value="">Pilih</option>
        <?php foreach($paket as $p): ?>
            <option value="<?= $p->description ?>"><?= $p->description ?></option>
        <?php endforeach ?>
    </select>
</div>

<!-- PRODUCT INFO -->
<div class="col-md-12 mb-3">
    <label>Product Info</label>
    <textarea class="form-control modern-input" name="product_indo" rows="6"></textarea>
</div>

</div>
</div>

<!-- ================= RIGHT ================= -->
<div class="col-md-4">

<div class="section-title">Pengaturan</div>

<?php
$toggle = function($label,$name,$true='1',$false='0'){
    return "
    <div class='d-flex justify-content-between align-items-center mb-3'>
        <span>$label</span>
        <input type='hidden' name='$name' value='$false'>
        <label class='switch'>
            <input type='checkbox' name='$name' value='$true'>
            <span class='slider'></span>
        </label>
    </div>";
};

echo $toggle('Dine In','dine_in');
echo $toggle('Take Away','take_away');
echo $toggle('Konsinyasi','consignment');
echo $toggle('Additional','additional','true','false');
echo $toggle('Item Group','group_item','true','false');
echo $toggle('Paket Tambahan','additional_package');
?>

<div class="mt-3">
    <label>Status</label>
    <select class="form-control modern-input" name="aktif">
        <option value="1">Aktif</option>
        <option value="0">Tidak Aktif</option>
    </select>
</div>
<div class="section-title mt-4">Monitor</div>

<div class="row">
<?php foreach($monitor as $m): ?>
    
    <div class="col-md-6 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            
            <span><?= $m->description ?></span>

            <div>
                <input type="hidden" name="monitor[<?= $m->id ?>]" value="0">
                <label class="switch mb-0">
                    <input type="checkbox" name="monitor[<?= $m->id ?>]" value="1">
                    <span class="slider"></span>
                </label>
            </div>

        </div>
    </div>

<?php endforeach ?>
</div>

</div>

</div>

<div class="text-right mt-4">
    <button class="btn btn-success btn-modern">Simpan</button>
</div>

</form>

</div>
</div>

</div>
</div>

<?php $this->load->view('admin/layout/footer') ?>