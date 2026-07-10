<?php $this->load->view('template/head') ?>
<style type="text/css">
  #btn_kiri_order {
              margin-bottom: 10px;
              padding-left: 40px;
              padding-right: 40px;
              padding-top: 5px;
              padding-bottom: 5px;
            }
            #btn_kiri_memanggil {
              margin-bottom: 10px;
              padding-left: 40px;
              padding-right: 40px;
              padding-top: 5px;
              padding-bottom: 5px;
            }
            #btn_kanan_awal {
              margin-bottom: 20px;
              padding-left: 40px;
              padding-right: 40px;
              padding-top: 5px;
              padding-bottom: 5px;
            }
            #btn_kanan_bill {
              margin-bottom: 10px;
              padding-left: 48px;
              padding-right: 48px;
              padding-top: 5px;
              padding-bottom: 5px;
            }
  @media (min-width: 412px){
            #btn_kiri_order {
              margin-bottom: 10px;
              padding-left: 40px;
              padding-right: 40px;
              padding-top: 20px;
              padding-bottom: 20px;
            }
            #btn_kiri_memanggil {
              margin-bottom: 10px;
              padding-left: 40px;
              padding-right: 40px;
              padding-top: 30px;
              padding-bottom: 30px;
            }
            #btn_kanan_awal {
              margin-bottom: 20px;
              padding-left: 40px;
              padding-right: 40px;
              padding-top: 20px;
              padding-bottom: 20px;
            }
            #btn_kanan_bill {
              margin-bottom: 10px;
              padding-left: 48px;
              padding-right: 48px;
              padding-top: 20px;
              padding-bottom: 20px;
            }
            
        }
        @media (min-width: 720px){
            #btn_kiri_order {
              margin-bottom: 10px;
              padding-left: 40px;
              padding-right: 40px;
              padding-top: 20px;
              padding-bottom: 20px;
            }
            #btn_kiri_memanggil {
              margin-bottom: 10px;
              padding-left: 52px;
              padding-right: 52px;
              padding-top: 20px;
              padding-bottom: 20px;
            }
            #btn_kanan_awal {
              margin-bottom: 20px;
              padding-left: 40px;
              padding-right: 40px;
              padding-top: 20px;
              padding-bottom: 20px;
            }
            #btn_kanan_bill {
              margin-bottom: 10px;
              padding-left: 48px;
              padding-right: 48px;
              padding-top: 20px;
              padding-bottom: 20px;
            }
            
        }
  @media (min-width: 768px) and (max-width: 1024px){
            #btn_kiri_order {
              margin-bottom: 10px;
              padding-left: 67px;
              padding-right: 67px;
              padding-top: 40px;
              padding-bottom: 40px;
            }
            #btn_kiri_memanggil {
              margin-bottom: 10px;
              padding-left: 50px;
              padding-right: 50px;
              padding-top: 45px;
              padding-bottom: 45px;
            }
            #btn_kanan_awal {
              margin-bottom: 10px;
              padding-left: 50px;
              padding-right: 50px;
              padding-top: 45px;
              padding-bottom: 45px;
            }
            #btn_kanan_bill {
              margin-bottom: 10px;
              padding-left: 86px;
              padding-right: 86px;
              padding-top: 40px;
              padding-bottom: 40px;
            }
            
        }
        @media (min-width: 1025px) and (max-width: 1280px){
          #btn_kiri_order {
              margin-bottom: 10px;
              padding-left: 67px;
              padding-right: 67px;
              padding-top: 40px;
              padding-bottom: 40px;
            }
            #btn_kiri_memanggil {
              margin-bottom: 10px;
              padding-left: 50px;
              padding-right: 50px;
              padding-top: 45px;
              padding-bottom: 45px;
            }
            #btn_kanan_awal {
              margin-bottom: 10px;
              padding-left: 50px;
              padding-right: 50px;
              padding-top: 45px;
              padding-bottom: 45px;
            }
            #btn_kanan_bill {
              margin-bottom: 10px;
              padding-left: 86px;
              padding-right: 86px;
              padding-top: 40px;
              padding-bottom: 40px;
            }
        }
        footer{
  text-align: center;
  background-color: white;
  margin-top: 10px;
  /*position: absolute;*/
  bottom: 0;
  width: 100%;
  position: fixed;
  z-index: 200000;
}
 </style>
 <div id="loadingkonek"></div>
    <nav style="background-color: #223c77;color: white;">
  <div class="container">
    <p style="text-align: center;padding-top: 13px;color: white;">Bill Preview</p>
  </div>
  <div style="width: 100%; height: 0px; border: 1px #000 solid;margin-bottom: 5px;">
</div>
</nav>
<div class="container">
  <table class="table">
  <tbody>
    <tr>
      <th scope="row">Customer Name</th>
      <td> </td>
      <td> </td>
      <td><?= $this->session->userdata('username') ?></td>
    </tr>
    <tr>
      <th scope="row">Table No</th>
      <td> </td>
      <td> </td>
      <td><?= $nomeja ?></td>
    </tr>
    <tr>
      <th scope="row">Actual Pax</th>
      <td> </td>
      <td> </td>
      <td><?php if ( $order_bill == NULL ): ?>
      -
      <?php else: ?>
      <?= $order_bill->totalpax_actual ?> / <?= $order_bill->totalpax_reservasi ?> 
      <?php endif ?></td>
    </tr>
    <tr>
      <th scope="row">Transaction No</th>
      <td> </td>
      <td> </td>
      <td><?= $notrans ?></td>
    </tr>
    <tr>
      <th scope="row">Package Type</th>
      <td> </td>
      <td> </td>
      <td>Alacarte</td>
    </tr>
  </tbody>
</table>
</div>
<div class="container ">
  <table class="table" style="border:2px;">
  <thead style="background-color: #223c77;color: white;">
    <tr>
      <th scope="col">Order Menu</th>
      <th scope="col">Qty</th>
      <th scope="col">Price</th>
      <th scope="col">Total Price</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($order_bill_line as $i): ?>
    <tr>
      <th scope="row"><?= $i->description ?></th>
      <td><?= $i->qty ?></td>
      <?php if ( $i->unit_price == 0): ?>
          <td>Free</td>
          <td>Free</td>
      <?php else: ?>
      <?php 
        $dis =  $i->unit_price * ($i->disc/100);
        $diskon = ($i->unit_price * $i->disc)/100;
        $tl = $i->unit_price - $diskon;
        $tqty = $tl * $i->qty; 
        $up = $i->unit_price - $dis;
      ?>  
        <td>Rp <br> <?= number_format($i->unit_price) ?></td>
        <td>Rp <br> <?= number_format($tqty) ?></td>
      <?php endif ?>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>
  
</div>  
<div class="container" style="margin-bottom: 200px;">
  <table class="table">
  <tbody>
    <tr>
      <th scope="row">Sub Total</th>
      <td> </td>
      <td> </td>
      <?php if ($order_bill == NULL): ?>
      <td>Rp 0</td>
      <?php else: ?>
      <td>Rp <?= number_format($order_bill->total) ?></td>
      <?php endif;?>
    </tr>
    <tr>
      <th scope="row">SC</th>
      <td> </td>
      <td> </td>
      <?php if ($order_bill == NULL): ?>
      <td>Rp 0</td>
      <?php else: ?>
      <td>Rp <?= number_format($order_bill->sc) ?></td>
      <?php endif;?>
    </tr>
    <tr>
      <th scope="row">PB1</th>
      <td> </td>
      <td> </td>
      <?php if ($order_bill == NULL): ?>
      <td>Rp 0</td>
      <?php else: ?>
      <td>Rp <?= number_format($order_bill->ppn) ?></td>
      <?php endif;?>
    </tr>
    <tr>
      <th scope="row">DISC</th>
      <td> </td>
      <td> </td>
      <?php if ($order_bill == NULL): ?>
      <td>Rp 0</td>
      <?php else: ?>
      <td><?= $disc ?>%</td>
      <?php endif;?>
    </tr>
    <tr>
      <th scope="row">Total Payment</th>
      <td> </td>
      <td> </td>
      <?php if ($order_bill == NULL): ?>
      <td>Rp 0</td>
      <?php else: ?>
      <td>Rp <!-- <?= number_format($order_bill->total + $order_bill->sc + $order_bill->ppn) ?> --><?= number_format($datapayment['paid_amount']) ?></td>
      <?php endif;?>
    </tr>
    <tr>
      <th scope="row">Payment Type</th>
      <td> </td>
      <td> </td>
      <?php if ($cekbk): ?>
        <td>Pay at Cashier</td>
      <?php elseif($datapayment['payment_method'] == 'CREDIT_CARD'): ?>
        <td>Credit Card</td>
      <?php else: ?>
        <td>Virtual Account <?= $datapayment['bank_code'] ?></td>
      <?php endif ?>
      
    </tr>
  </tbody>
</table>
</div>
<br>

<footer>
<div class="container text-center">
  <div class="row">
    <div class="col" id="back_to_home">
      <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-outline-success" id="btn_kiri_order">Add More Order</a> -->
      <a href="<?= base_url() ?>index.php/selforder" class="btn btn-outline-danger" id="btn_kanan_awal" ><i class="fa fa-home"></i> Back to Home</a>
    </div>
    
    <div class="col">
      <!-- <a href="<?php echo base_url() ?>Kasir_waitress/meminta/<?= $nomeja ?>" class="btn btn-outline-success" id="btn_kanan_bill">Meminta Bill</a> -->
      <?php if ($cekbk): ?>
        <a href="<?= base_url() ?>index.php/selforder/cetakpdf/bk" class="btn btn-outline-primary" id="btn_kanan_awal"onclick="showBackToHome()"><i class="fa fa-file"></i> Download Receipt</a>
      <?php else: ?>
        <a href="<?= base_url() ?>index.php/selforder/cetakpdf" class="btn btn-outline-primary" id="btn_kanan_awal"onclick="showBackToHome()"><i class="fa fa-file"></i> Download Receipt</a>
      <?php endif ?>
    </div>
  </div>
</div>
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
  // Menghilangkan tombol "Back to Home" saat halaman dimuat
  $("#back_to_home").hide();
});

function showBackToHome() {
  // Menampilkan kembali tombol "Back to Home" saat tombol "Export PDF" diklik
  $("#back_to_home").show();
}
</script>


<?php $this->load->view('template/footer') ?>
    