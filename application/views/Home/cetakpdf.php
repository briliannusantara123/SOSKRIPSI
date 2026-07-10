<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link href="<?= base_url();?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url();?>assets/fontawesome/css/all.min.css" rel="stylesheet">
  
</head>
<body>
<div class="container" >
 <div style="text-align: center; font-size: 17px;">
    Hachi Grill <br>
    Japanese Restaurant
 </div>
 =========================
 <div>
     <table class="table">
  <tbody>
    <tr>
      <td scope="row">Nomor</td>
      <td> </td>
      <td> </td>
      <td>: <?= $orderno ?></td>
    </tr>
    <tr>
      <td scope="row">Date</td>
      <td> </td>
      <td> </td>
      <td>: <?= date('d-m-Y') ?></td>
    </tr>
    <tr>
      <td scope="row">Customer Name</td>
      <td> </td>
      <td> </td>
      <td>: <?= $this->session->userdata('username') ?></td>
    </tr>
    <tr>
      <td scope="row">Table Number</td>
      <td> </td>
      <td> </td>
      <td>: <?= $this->session->userdata('nomeja') ?></td>
    </tr>
    <tr>
      <td scope="row">Package Type</td>
      <td> </td>
      <td> </td>
      <td>: Alacarte</td>
    </tr>
  </tbody>
</table>
 </div>
</div>
=========================
<div class="container ">
  <table class="table" style="font-size: 15px;">
  <tbody>
    <?php foreach ($order_bill_line as $i): ?>
    <tr>
      <td><?= $i->qty ?></td>
      <td scope="row"><?= $i->description ?></td>
      <td></td>
      <td></td>
      <?php if ( $i->unit_price == 0): ?>
          <td>Free</td>
          <td>Free</td>
      <?php else: ?>
        <td>Rp <?= number_format($i->unit_price) ?></td>
      <?php endif ?>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>
=========================
  
</div> 
<table class="table" style="margin-left: 50px;">
  <tbody>
    <tr>
      <td scope="row">Sub Total</td>
      <?php if ($order_bill == NULL): ?>
      <td>: Rp 0</td>
      <?php else: ?>
      <td>: Rp <?= number_format($order_bill->total) ?></td>
      <?php endif;?>
    </tr>
    <tr>
      <td scope="row">Serv Charge 5%</td>
      <?php if ($order_bill == NULL): ?>
      <td>: Rp 0</td>
      <?php else: ?>
      <td>: Rp <?= number_format($order_bill->sc) ?></td>
      <?php endif;?>
    </tr>
    <tr>
      <td scope="row">Pajak 10%</td>
      <?php if ($order_bill == NULL): ?>
      <td>: Rp 0</td>
      <?php else: ?>
      <td>: Rp <?= number_format($order_bill->ppn) ?></td>
      <?php endif;?>
    </tr>
    <tr>
      <td scope="row">===========</td>
      <td>===========</td>
    </tr>
    <tr>
      <td scope="row">Total Payment</td>
      <?php if ($order_bill == NULL): ?>
      <td>: Rp 0</td>
      <?php else: ?>
      <td>: Rp <?= number_format($order_bill->total + $order_bill->sc + $order_bill->ppn) ?></td>
      <?php endif;?>
    </tr>
    <tr>
      <td scope="row">===========</td>
      <td>===========</td>
    </tr>
    <tr>
      <?php if ($cekbk): ?>
        <td scope="row">Pay at Cashier</td>
        <td>: Rp <?= number_format($order_bill->total + $order_bill->sc + $order_bill->ppn) ?></td>
      <?php else: ?>
        <td scope="row">CC <?= $datapayment['bank_code'] ?></td>
        <td>: Rp <?= number_format($datapayment['amount']) ?></td>
      <?php endif ?>
    </tr>
    <tr>
      <td scope="row">===========</td>
      <td>===========</td>
    </tr>
    <tr>
      <td scope="row">Kembali</td>
      <td>: 0 </td>
    </tr>
  </tbody>
</table> 
<h1 style="text-align: center;">Thank You</h1>
<h1 style="text-align: center;">T<?= $nomeja ?><?= $cekdata ?></h1>

</body>
</html>