<?php $this->load->view('template/head') ?>
    <style type="text/css">
        body {
            background: url('<?= base_url();?>/assets/icon/home/contoh.gif') center center fixed; /* Adjust the path to your image */
      background-size: cover; /* This property ensures that the background image covers the entire body */
      height: 100vh; /* This ensures the background covers the entire viewport height */
      margin: 0;
          height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
          margin: 0;
          
        }
         @media (max-width: 414px) {
            #btn_kiri_order {
                padding-left: 30px;padding-right: 30px;padding-top: 30px;padding-bottom: 30px;margin-top: 20px;
            }
            #btn_kiri_melihat {
                padding-left: 30px;padding-right: 30px;padding-top: 30px;padding-bottom: 30px;margin-top: 20px;
            }
            #btn_kiri_memanggil {
                padding-left: 30px;padding-right: 30px;padding-top: 30px;padding-bottom: 30px;margin-top: 20px;
            }
            #btn_kanan {
                padding-left: 30px;padding-right: 30px;padding-top: 30px;padding-bottom: 30px;margin-top: 20px;
            }
            #btn_kanan_bill {
                padding-left: 30px;padding-right: 30px;padding-top: 30px;padding-bottom: 30px;margin-top: 20px;
            }
            #btn_kanan_meminta {
                padding-left: 42px;padding-right: 42px;padding-top: 30px;padding-bottom: 30px;margin-top: 20px;
            }
        }
          
        /* Media Query for low resolution  Tablets, Ipads */
        @media (min-width: 481px) {
            #btn_kiri_order {
                padding-left: 52px;padding-right: 52px;padding-top: 42px;padding-bottom: 42px;margin-top: 20px;
            }
            #btn_kiri_melihat {
                padding-left: 52px;padding-right: 52px;padding-top: 42px;padding-bottom: 42px;margin-top: 20px;
            }
            #btn_kiri_memanggil {
                padding-left: 52px;padding-right: 52px;padding-top: 42px;padding-bottom: 42px;margin-top: 20px;
            }
            #btn_kanan {
                padding-left: 52px;padding-right: 52px;padding-top: 30px;padding-bottom: 30px;margin-top: 20px;
            }
            #btn_kanan_bill {
                padding-left: 30px;padding-right: 30px;padding-top: 42px;padding-bottom: 42px;margin-top: 20px;
            }
            #btn_kanan_meminta {
                padding-left: 62px;padding-right: 62px;padding-top: 55px;padding-bottom: 55px;margin-top: 20px;
            }
        }
        @media (min-width: 720px) {
            #btn_kiri_order {
                padding-left: 52px;padding-right: 52px;padding-top: 42px;padding-bottom: 42px;margin-top: 20px;
            }
            #btn_kiri_melihat {
                padding-left: 52px;padding-right: 52px;padding-top: 42px;padding-bottom: 42px;margin-top: 20px;
            }
            #btn_kiri_memanggil {
                padding-left: 52px;padding-right: 52px;padding-top: 42px;padding-bottom: 42px;margin-top: 20px;
            }
            #btn_kanan {
                padding-left: 52px;padding-right: 52px;padding-top: 42px;padding-bottom: 42px;margin-top: 20px;
            }
            #btn_kanan_bill {
                padding-left: 30px;padding-right: 30px;padding-top: 42px;padding-bottom: 42px;margin-top: 20px;
            }
            #btn_kanan_meminta {
                padding-left: 62px;padding-right: 62px;padding-top: 55px;padding-bottom: 55px;margin-top: 20px;
            }
        }
          
        /* Media Query for Tablets Ipads portrait mode */
        @media (min-width: 768px) and (max-width: 1024px){
            #btn_kiri_order {
                font-size: 20px;
            }
            #btn_kiri_melihat {
                font-size: 17px;
                padding-bottom: 44px;
                padding-top: 44px;
            }
            #btn_kiri_memanggil {
                font-size: 17px;
                padding-bottom: 54px;
                padding-top: 54px;
            }
            #btn_kanan {
                font-size: 20px;
            }
            #btn_kanan_bill {
                font-size: 20px;
            }
            #btn_kanan_meminta {
                padding-right: 85px;
                padding-left: 85px;
            }

        }
          
        /* Media Query for Laptops and Desktops */
        @media (min-width: 1025px) and (max-width: 1280px){
            #btn_kiri_order {
                font-size: 20px;
                padding-right: 56px;
                padding-left: 56px;
            }
            #btn_kiri_melihat {
                font-size: 17px;
                padding-bottom: 44px;
                padding-top: 44px;
            }
            #btn_kiri_memanggil {
                font-size: 17px;
                padding-bottom: 54px;
                padding-top: 54px;
            }
            #btn_kanan {
                font-size: 20px;
                padding-right: 56px;
                padding-left: 56px;
            }
            #btn_kanan_bill {
                font-size: 20px;
            }
            #btn_kanan_meminta {
                padding-right: 85px;
                padding-left: 85px;
            }

        }
        .image{
            background-color: #198754;
            transition-duration: 0.4s;
        }
        .image:hover {
              background-color: lightgreen; /* Green */
              color: white;
        }
        /* Media Query for Large screens */
    </style>
    <style type="text/css">
  .loadingkonek{
    width: 50px;
    height: 50px;
    border:solid 5px #ccc;
    border-top-color: #198754;
    border-radius: 100%;

    position: fixed;
    left: 0;
    top: 0;
    right:0;
    bottom: 0;
    margin: auto;
    z-index: 10000001;

    animation: putar 1s linear infinite;
  }

  @keyframes putar{
    from{transform: rotate(0deg);}
    to{transform: rotate(360deg);}
  
</style>
<div id="loadingkonek"></div>
    <!-- <nav class="bg-success" style="z-index: 10000;position: fixed;width: 100%;">
  <div class="container">
  <div class="row">
    <div class="col-9"><p style="padding-top: 13px;color: white;">Home Menu</p></div>
    <div class="col-1" style="z-index: 10040000;"></div>
<div class="col-1"><strong></strong></div>

  </div>
</div>
  <div style="width: 100%; height: 0px; border: 1px #000 solid;">
</div>
</nav>
<div class="container " style="z-index: 10000;position: fixed;width: 100%;margin-top: 55px;">
  <div class="row">
     <div class="col bg-success" style="margin-left:30px;border-bottom-left-radius: 20px;padding-top: 10px; ">
      <p style="color: white;"><?= date('d-m-y H:i:s'); ?></p>
    </div>
  
    <div class="col bg-success" style="margin-right:30px;border-bottom-right-radius: 20px;padding-top: 10px;">
    </div>
  </div>
</div> -->
<br>
<div class="container text-center">
  <div class="row">
    <div class="fadeIn first" style="margin-bottom: 30px;">
      <img src="<?= base_url();?>assets/logo.png" style="width: 300px;height: 100px;margin-bottom: 10px;" alt="Dear Clio" />
    </div>
    <div class="col">
      <a class="" href="<?= base_url() ?>index.php/selforder/pilih_pembayaran/2" role="button"  style="text-decoration:none"><img src="<?= base_url();?>/assets/icon/menu/order_makanan.png" style="width: 200px;height: 200px;border-radius: 5%;" alt="Hachi Grill" class="image" /><h2 style="color:#198754;margin-top: 5px; ">Dine In</h2></a>
    </div>
 
    <div class="col">
      <a class="" href="<?= base_url() ?>index.php/selforder/pilih_pembayaran/4" role="button"  style="margin-top: 20px;text-decoration:none"><img src="<?= base_url();?>/assets/icon/menu/takeaway.png" style="width: 200px;height: 200px;border-radius: 5%;" alt="Hachi Grill" class="image" /><h2 style="color:#198754;margin-top: 5px; ">Take Away</h2></a>
    </div>
  </div>

</div>
        
    <?php $this->load->view('template/footer') ?>