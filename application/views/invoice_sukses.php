<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		.text {
			text-align: center;
			color: black;
			margin-top: 100px;
		}
		.table {
			background-color: white;
			color: black;
			border-radius: 20px;
		}
	</style>
	 <link href="<?= base_url();?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url();?>assets/fontawesome/css/all.min.css" rel="stylesheet">
</head>
<body style="background-color: #198754">
 <div class="text">
 	<h1>Payment Successful</h1>
	<h1>Your order number is <br><?= $this->session->userdata('order_no') ?></h1>
	<form id="myForm" action="<?= base_url() ?>index.php/cart/validasi_order/<?= $nomeja ?>/<?= $cek ?>/<?= $sub ?>#<?= $sub ?>" method="POST">
<div class="container" >
    <div class="container ">
  <table class="table" >
  <thead>
    <tr>
      <th scope="col">Menu Order</th>
      <th scope="col">Harga</th>
      <th scope="col">Qty</th>
      <th scope="col">Pesan</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($item as $i): ?>
    <tr>
      <th scope="row"><p><?= $i->description ?></p></th>
      <td><p>Rp <?= number_format($i->unit_price); ?></p></td>
      <td><p style="text-align: left;"><?= $i->qty ?></p></td>
      <?php if ( $i->notesdua): ?>
        <td><p style="text-align: left;"><?= $i->extra_notes ?>,<?= $i->notesdua ?></p></td>
      <?php else: ?>  
        <td><p style="text-align: left;"><?= $i->extra_notes ?></p></td>
      <?php endif ?>
    </tr>
    <input type="hidden" name="nama[]" value="<?= $i->description ?>">
  <input type="hidden" name="qty[]" id="qty<?= $i->id?>" value="<?= $i->qty ?>">
  <input type="hidden" name="cek[]" value="<?= $i->as_take_away ?>">
  <input type="hidden" name="qta[]" value="<?= $i->qty_take_away ?>">
  <input type="hidden" name="harga[]" value="<?= $i->unit_price ?>">
  <input type="hidden" name="pesandua[]" value="<?= $i->notesdua ?>">
  <input type="hidden" name="pesantiga[]" value="<?= $i->notesdua ?>">
  <input type="hidden" name="pesan[]" value="<?= $i->extra_notes ?>">
  <input type="hidden" name="no[]" id="harga" value="<?= $i->item_code ?>" class="form-control harga">
  <input type="hidden" name="need_stock[]" id="need_stock" value="<?= $i->need_stock ?>" class="form-control need_stock">

    <?php endforeach ?>
    <input type="hidden" name="price" value="<?= $price?>">
 </div>
</body>
<script type="text/javascript">
	var myForm = document.getElementById("myForm");
    // Submit formulir otomatis
    myForm.submit();
	// Menunggu 1 menit (60.000 milidetik) sebelum redirect
	setTimeout(function() {
	    // Ganti URL halaman redirect sesuai kebutuhan
	    window.location.href = '<?= base_url()?>index.php/selforder';
	}, 3000);

</script>
</html>