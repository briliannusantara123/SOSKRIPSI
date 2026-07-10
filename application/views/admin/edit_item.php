<?php $this->load->view('admin/layout/header') ?>

<style>
body { background:#f4f6f9; }

.modern-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}
.header-title{ font-size:22px; font-weight:600; }
.header-sub{ font-size:13px; color:#888; }
.btn-header{ border-radius:10px; padding:8px 18px; }

.modern-card{
    border:none;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
}

.modern-input{
    border-radius:10px;
    border:1px solid #ddd;
    padding:10px 12px;
}
.modern-input:focus{
    border-color:#4e73df;
    box-shadow:0 0 0 3px rgba(78,115,223,0.15);
}

label{ font-weight:500; }

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

<div class="modern-header">
    <div>
        <div class="header-title">Edit Item</div>
        <div class="header-sub">Master Data / Item</div>
    </div>
    <a href="<?= base_url('index.php/Admin/item') ?>" class="btn btn-light btn-header">
        ← Kembali
    </a>
</div>

<div class="card modern-card">
<div class="card-body">

<form action="<?= base_url('index.php/Admin/updateitem/'.$item->id) ?>" method="POST">

<div class="row">

<!-- ================= LEFT ================= -->
<div class="col-md-8">
<div class="row">

<!-- KODE -->
<div class="col-md-6 mb-3">
    <label>Kode</label>
    <input type="text" class="form-control modern-input"
           value="<?= $item->no ?>" readonly>
</div>
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
    <input type="text" name="description"
           value="<?= $item->description ?>"
           class="form-control modern-input">
</div>

<!-- HARGA -->
<div class="col-md-4 mb-3">
    <label>Harga Weekday</label>
    <input type="number" name="harga_weekday"
           value="<?= $item->harga_weekday ?>"
           class="form-control modern-input">
</div>

<div class="col-md-4 mb-3">
    <label>Harga Weekend</label>
    <input type="number" name="harga_weekend"
           value="<?= $item->harga_weekend ?>"
           class="form-control modern-input">
</div>

<div class="col-md-4 mb-3">
    <label>Harga Holiday</label>
    <input type="number" name="harga_holiday"
           value="<?= $item->harga_holiday ?>"
           class="form-control modern-input">
</div>

<!-- KATEGORI -->
<div class="col-md-6 mb-3">
    <label>Kategori</label>
    <select name="item_category" class="form-control modern-input">
        <option value="">Pilih</option>
        <?php foreach($category as $c): ?>
            <option value="<?= $c->description ?>"
            <?= $item->category == $c->description ? 'selected' : '' ?>>
                <?= $c->description ?>
            </option>
        <?php endforeach ?>
    </select>
</div>

<!-- SUB -->
<div class="col-md-6 mb-3">
    <label>Sub Kategori</label>
    <select name="item_sub_category" class="form-control modern-input">
        <option value="">Pilih</option>
        <?php foreach($sub as $s): ?>
            <option value="<?= $s->description ?>"
            <?= $item->sub_category == $s->description ? 'selected' : '' ?>>
                <?= $s->description ?>
            </option>
        <?php endforeach ?>
    </select>
</div>

<!-- PAKET -->
<div class="col-md-12 mb-3">
    <label>Item Paket</label>
    <select name="item_package" class="form-control modern-input">
        <option value="">Pilih</option>
        <?php foreach($paket as $p): ?>
            <option value="<?= $p->description ?>"
            <?= $item->item_package == $p->description ? 'selected' : '' ?>>
                <?= $p->description ?>
            </option>
        <?php endforeach ?>
    </select>
</div>

<!-- PRODUCT INFO -->
<div class="col-md-12 mb-3">
    <label>Product Info</label>
    <textarea name="product_info" rows="5"
        class="form-control modern-input"><?= $item->product_info ?></textarea>
</div>

</div>
</div>

<!-- ================= RIGHT ================= -->
<div class="col-md-4">

<div class="section-title">Pengaturan</div>

<?php
function toggle($label,$name,$value){
    return "
    <div class='d-flex justify-content-between align-items-center mb-3'>
        <span>$label</span>
        <input type='hidden' name='$name' value='0'>
        <label class='switch'>
            <input type='checkbox' name='$name' value='1' ".($value==1?'checked':'').">
            <span class='slider'></span>
        </label>
    </div>";
}

echo toggle('Dine In','dine_in',$item->dine_in_menu);
echo toggle('Take Away','take_away',$item->take_away_menu);
echo toggle('Konsinyasi','consignment',$item->consignment);
echo toggle('Additional','additional',$item->additional);
echo toggle('Item Group','group_item',$item->group_item);
echo toggle('Paket Tambahan','additional_package',$item->additional_package);
?>

<div class="mt-3">
    <label>Status</label>
    <select name="aktif" class="form-control modern-input">
        <option value="1" <?= $item->is_active == 1 ? 'selected' : '' ?>>Aktif</option>
        <option value="0" <?= $item->is_active == 0 ? 'selected' : '' ?>>Tidak Aktif</option>
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
                <label class="switch">
                    <input type="checkbox"
                        name="monitor[<?= $m->id ?>]"
                        value="1"
                        <?= $item->{'monitor'.$m->id} == 1 ? 'checked' : '' ?>>
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
    <button class="btn btn-success btn-modern">Update</button>
</div>

</form>

</div>
</div>

</div>
</div>

<?php $this->load->view('admin/layout/footer') ?>