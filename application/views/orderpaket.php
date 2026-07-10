<?php $this->load->view('template/head') ?>
<?php $this->load->view('style/paket') ?>
    <style type="text/css">
      
      .custom-popup {
    
    z-index: 2;}
    @media (max-width: 414px) {
        .md {
       
       top: 10px;
       right: 100px;
       width: 385px;
       height:600px;
       bottom: 0;
       z-index: 9998;
       left: 0;
       overflow: auto;
       overflow-y: auto;
    }
        }
          
        /* Media Query for low resolution  Tablets, Ipads */
        @media (min-width: 397px) {
            .md {
       
       top: 10px;
       right: 100px;
       width: 395px;
       height:610px;
       bottom: 0;
       z-index: 9998;
       left: 0;
       overflow: auto;
       overflow-y: auto;
    }
        }
      @media (min-height: 640px) {
        .md {
      
       top: 10px;
       right: 0px;
       width: 350px;
       height:515px;
       bottom: 0;
       z-index: 9998;
       left: 5px;
       overflow: auto;
       overflow-y: auto;
    }
    .mdl {
       
       top: 10px;
       right: 0px;
       width: 340px;
       height:515px;
       bottom: 0;
       z-index: 9998;
       left: 10px;
       overflow: auto;
       overflow-y: auto;
    }

    #imgmodal{
        width: 150px;height: 100px;border-radius: 20px; display: block;margin-left: auto;margin-right: auto;
    }
    #description{
        color: <?= $cn->color ?>;float: left;font-size: 15px;
    }
    /*#product_info{
        color: <?= $cn->color ?>;margin-top: 2px;float: left;font-size: 15px;
    }*/
    #harga{
        color: <?= $cn->color ?>;margin-top: 2px;float: right;font-size: 15px;
    }
    #option{
      float: left;color: <?= $cn->color ?>;font-size: 15px;
    }
    .modalbutton{
      padding-left: 45px;padding-right: 45px;
    }
      }
        @media (min-height: 720px) {
            .md {
       margin-top: 85px;
       top: 10px;
       right: 0px;
       width: 350px;
       height:540px;
       bottom: 0;
       z-index: 9998;
       left: 17px;
       overflow: auto;
       overflow-y: auto;
    }
    .mdl {
       margin-top: 220px;
       top: 10px;
       right: 0px;
       width: 350px;
       height:515px;
       bottom: 0;
       z-index: 9998;
       left: 17px;
       overflow: auto;
       overflow-y: auto;
    }
    #imgmodal{
        width: 170px;height: 120px;border-radius: 20px; display: block;margin-left: auto;margin-right: auto;
    }
    #description{
        color: <?= $cn->color ?>;float: left;font-size: 15px;
    }
    /*#product_info{
        color: <?= $cn->color ?>;margin-top: 2px;float: left;font-size: 15px;
    }*/
    #harga{
        color: <?= $cn->color ?>;margin-top: 2px;float: right;font-size: 15px;
    }
    #option{
      float: left;color: <?= $cn->color ?>;font-size: 15px;
    }
        }
          
        /* Media Query for Tablets Ipads portrait mode */
        @media (min-width: 768px) and (max-width: 1024px){
            

        }
          
        /* Media Query for Laptops and Desktops */
        @media (min-width: 1025px) and (max-width: 1280px){
            
        }
        
      .button {
  background-color: white;
  color: <?= $cn->color ?>;
  
  padding:16px 30px;
  text-align: center;

  text-decoration: none;
  display: inline-block;
  font-size: 12px;
  transition-duration: 0.4s;
}

.active{
   background-color: <?= $cb->color ?>;
  color: <?= $cn->lightcolor ?>;
  
  padding:16px 30px;
  text-align: center;
  border-radius: 10px;
  text-decoration: none;
  display: inline-block;
  font-size: 12px;
  transition-duration: 0.4s;
  background-size: 55%;
  background-position-y: 20%;
  background-position-x: 50%;
}

.button:hover {
  /*background: linear-gradient(to right, <?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);*/ /* Green */
  border-radius: 10px;
  color: <?= $cn->color ?>;
}
.wrapper{
  max-height: 120px;
  display: flex;
  overflow: scroll;
  background-color: white;
  overflow-x: auto;
  white-space: nowrap; 
  margin-top: 10px;
}
.wrapper .item{
  
  text-align: center;
  background-color: #ddd;
  margin-right: 2px;
  display: inline-block; /* Menampilkan elemen "item" dalam satu baris */
    margin-right: 10px;
}

footer{
  text-align: center;
  background-color: white;
  margin-top: 10px;
  border-top-right-radius: 10%;
  border-top-left-radius: 10%;
  /*position: absolute;*/
  bottom: 0;
  width: 100%;
  position: fixed;
  z-index: 200000;
}
#load{
    background: rgba(0,0,0,0.7);
    height: 100vh;
    width: 100%;
    position: fixed;
    z-index: 1000000;
  }

   #loading{
    width: 50px;
    height: 50px;
    border:solid 5px #ccc;
    border-top-color: <?= $cn->color ?>;
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
  }
  .sold{
    
    border-radius: 10px;
    position: fixed;
    z-index: 1000;
  }
  .soldtext{
    position:absolute;top: 30%; left:50%;transform:translate(-50%,-50%);text-align:center;color: red;font-weight: bold;font-size: 30px;
  }
    </style>

<div id="loading"></div>
<div id="load"></div>
<div id="loadingkonek"></div>

    <nav style="z-index: 10000;position: fixed;width: 100%;background: linear-gradient(to right, <?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>)">
  <div class="container">
  <div class="row">
    <div class="col-9"><p style="padding-top: 13px;color: white;">Package Menu</p></div>
    <div class="col-1" style="z-index: 10040000;"><a style="text-align: center;margin-top: 6px;" id="btncartheader" href="#" class=""><svg xmlns="http://www.w3.org/2000/svg" width="25" height="23" color="white" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16" style="margin-right: 10px;margin-top: 12px;margin-left: 10px;">
  <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
</svg></a></div>
<!-- <div class="col-1"><strong><h3 style="color: white;font-size: 10px;margin-top: 6px;background-color: red;border-radius: 40%;text-align: center;"><b id="cart_count"><?= $cart_count ?></b></h3></strong></div> -->
<div class="col-1"><strong><h3 style="color: white;font-size: 10px;margin-top: 6px;background-color: red;border-radius: 40%;text-align: center;"><b id="total_qty_header"><!-- <?= $total_qty;?> --></b></h3></strong></div>
  </div>
  </div>
  <div style="width: 100%; height: 0px; border: 1px #000 solid;">
</div>
</nav>
<br>

<header style="display: flex;width:100%; position: fixed;z-index: 1;margin-top: 30px;background-color: white;border-bottom-right-radius: 5%;
  border-bottom-left-radius: 5%;">

<div class="container" style="padding-top: 20px;margin-top:5px;background: linear-gradient(to right, <?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);border-radius: 5px 5px 5px 5px;">
  <label style="color: white;">Package Name</label>
  <?php if ($pc): ?>
   <select name="paket" class="form-control" id="paketauto">
    <option value="" disabled>Select Package</option>
    <?php
    $con = mysqli_connect("localhost", "root", "", "db_so");
                $query = "SELECT a.*
                  FROM sh_m_item a
                  INNER JOIN sh_m_varian_option b ON a.no = b.parent_code
                  LEFT JOIN sh_m_item_event e ON a.no = e.item_code
                  WHERE a.is_paket_so = 1 
                        AND a.is_sold_out = 0 
                        AND a.is_active = 1
                        AND (
                              e.item_code IS NULL -- Jika tidak ada event, tampilkan semua data
                              OR (
                                  -- Jika tanggal masih berlaku, filter berdasarkan jam
                                  (CURDATE() BETWEEN e.date_from AND e.date_to AND TIME_FORMAT(CURTIME(), '%H') BETWEEN e.time_from AND e.time_to)
                                  -- Jika tanggal sudah terlewat, abaikan filter jam dan tetap tampilkan data
                                  OR (CURDATE() > e.date_to)
                              )
                        )
                  GROUP BY a.description;
                  b ON a.no = b.parent_code
                                  WHERE a.is_paket_so = 1 
                                        AND a.is_sold_out = 0 
                                        AND a.is_active = 1
                                        GROUP BY a.description;
                ";


                $result = mysqli_query($con, $query);
                $jsArrayPaket = "var prdNamePaket = new Array();\n";

                while ($row = mysqli_fetch_array($result)) {
                    echo '<option name="paket"  value="' . $row['description'] . '">' . $row['description'] . '</option>';
                    $jsArrayPaket .= "prdNamePaket['" . $row['description'] . "'] = {harga_weekend:'" . addslashes($row['harga_weekend']) . "',item_code:'" . addslashes($row['description']) . "',description:'" . addslashes($row['no']) . "'};\n";
                }
    ?>
</select>
<label style="color: white;"> Package Price</label>
  <input type="text" name="harga_paket" id="harga_paket" class="form-control" readonly="" value="<?= $paketdata->harga_weekend ?>">
  <!-- <label style="color: white;">Qty Paket <label style="color: red">*</label></label>
  <input type="text" name="qty_paket" id="qty_paket" class="form-control" required=""> -->
  <input type="hidden" id="item_code" class="form-control" readonly="" value="<?= $paketdata->description ?>">
  <input type="hidden" name="" id="subp" class="form-control" readonly="" value="<?= $subp ?>">
  <input type="hidden" name="item_code" id="paket_name" class="form-control" readonly="" value="<?= $paketdata->no ?>">
  <input type="hidden" name="qtylimit" id="qtylimit" class="form-control" readonly="" value="<?= $paketdata->max_qty ?>">
  <input type="hidden" name="qtyadd" id="qtyadd" class="form-control" readonly="">
  <input type="hidden" name="subklik" id="subklik" class="form-control" readonly="" value="<?= $subp ?>">
  <input type="hidden" name="cek" id="cek" class="form-control" readonly="">
  <div class="wrapper" style="background: linear-gradient(to right, <?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);">
      <div id="sub"> </div>
  </div>
  <label style="color: white;font-size: 10px">*Please click the '+' button to add items to cart.</label>

  <?php else: ?>
  <input type="hidden" name="" value="" id="paketauto">
   <select name="paket" class="form-control" id="paket" onchange='paketValue(this.value)'>
    <option value="" selected disabled>Select Package</option>
    <?php
      $con = mysqli_connect("localhost", "root", "", "db_so");
      if (mysqli_connect_errno()) {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
          exit();
      }

      $query = "SELECT a.*
        FROM sh_m_item a
        INNER JOIN sh_m_varian_option b ON a.no = b.parent_code
        LEFT JOIN sh_m_item_event e ON a.no = e.item_code
        WHERE a.is_paket_so = 1 
              AND a.is_sold_out = 0 
              AND a.is_active = 1
              AND (
                    e.item_code IS NULL -- Jika tidak ada event, tampilkan semua data
                    OR (
                        -- Jika tanggal masih berlaku, filter berdasarkan jam
                        (CURDATE() BETWEEN e.date_from AND e.date_to AND TIME_FORMAT(CURTIME(), '%H') BETWEEN e.time_from AND e.time_to)
                        -- Jika tanggal sudah terlewat, abaikan filter jam dan tetap tampilkan data
                        OR (CURDATE() > e.date_to)
                    )
              )
        GROUP BY a.description;
        ";

      $result = mysqli_query($con, $query);
      $jsArrayPaket = "var prdNamePaket = {};\n";

      while ($row = mysqli_fetch_array($result)) {
          $description = htmlspecialchars($row['description']);
          $cekitem = $this->Paket_model->cekDataPaket($row['no']);
          
          $isSoldOut = false;
          foreach ($cekitem as $ci) {
              if ($ci['jml'] == $ci['sold_out']) {
                  $isSoldOut = true;
                  break; // Keluar dari loop jika item sold out
              }
          }
          
          if ($isSoldOut) {
              $description .= ' (sold out)';
              echo '<option value="" disabled>' . $description . '</option>';
          } else {
              echo '<option name="paket" value="' . $description . '">' . $description . '</option>';
          }
          
          $jsArrayPaket .= "prdNamePaket['" . addslashes($description) . "'] = {
              harga_weekend:'" . addslashes($row['harga_weekend']) . "',
              item_code:'" . addslashes($row['no']) . "',
              qty:'" . addslashes($row['max_qty']) . "',
              description:'" . addslashes($row['description']) . "'
          };\n";
      }

      mysqli_close($con);
      ?>


      </select>
  
  <div class="row">
    <div class="col-6">
      <label style="color: white;"> Package Price</label>
      <h3 id="hargatext" style="color: white;font-size: 25px;"></h3>
      <input type="hidden" name="harga_paket" id="harga_paket" class="form-control" >
    </div>
    <div class="col-6">
      <label style="color: white;"> Package Qty</label>
      <input type="hidden" name="" value="0" id="max_qty_sub">
      <input type="hidden" name="" value="0" id="max_qty_sub_hold">
      <div class="row" >
            <div class="col" >
              <button type="button" class="btn kurangpaket " style="padding-left:10px;padding-right:10px;background-color: white;color: <?= $cn->color ?>; " id="kurangpaket"><span style="font-size: 17px;">-</span></button>
            </div>
            <div class="col">
                <input type="text" name="qty" id="jumlahpaket" value="0"  class="" style="border:1px solid white;margin-bottom: 5px;color: <?= $cn->color ?>; width:30px;height:37px;border-radius: 10px;text-align: center; " readonly>
            </div>
            <div class="col">
                <button type="button" class="btn tambahpaket" style="padding-left: 7px;padding-right: 7px;background-color: white;color: <?= $cn->color ?>;" id="tambahpaket"><span style="font-size: 17px;">+</span></button>
              </div>
            </div>
          </div>
        </div>

  
  <!-- <label style="color: white;">Qty Paket <label style="color: red">*</label></label>
  <input type="text" name="qty_paket" id="qty_paket" class="form-control" required=""> -->
  <input type="hidden" name="item_code"  id="item_code" class="form-control" readonly="">
  <input type="hidden" id="paket_name" class="form-control" readonly="">
  <input type="hidden" name="qtylimit" id="qtylimit" class="form-control" readonly="" value="1">
  <input type="hidden" name="qtyadd" id="qtyadd" class="form-control" readonly="" value="0">
  <input type="hidden" name="subklik" id="subklik" class="form-control" readonly="">
  <input type="hidden" name="cek" id="cek" class="form-control" readonly="">
  <div class="wrapper" style="background: linear-gradient(to right, <?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);">
      <div id="sub"> </div>
      <input type="hidden" name="" id="subtext">
      <input type="hidden" name="" id="subactive">
  </div>
  <label style="color: white;font-size: 10px">*Please click the '+' button to add items to cart.</label> 
  <?php endif ?>
  
  
  
</div>
</header>

<!-- <?= base_url() ?>orderpaket/addcart/<?= $nomeja ?> -->
<form action="#" method="post"> 
<div class="container text-center" style="margin-top: 140px;">
  <div class="row row-cols-2">
    

</div>
<div id="konten">
  
</div>



<div class="containerfooter text-center" >
        <footer>
            <nav>
                <?php foreach ($iconfooter as $i): ?>
                    <a <?php if ($i->title == 'Home'): ?>
                      href="#" id="btnback"
                      <?php elseif($i->title == 'Cart'): ?>
                        href="#" id="btncart"
                      <?php elseif ($i->link_type == 'status'): ?>
                        href="<?= base_url() ?><?= $i->link ?>"
                      <?php else: ?>
                        href="<?= base_url() ?><?= $i->link ?><?= $nomeja ?>/Home/"
                    <?php endif ?> >
                        <img src="<?= $i->image_path ?>" style="width: 25px;height: 25px; filter: grayscale(100%);">
                    <?php if ($i->title == 'Cart'): ?>
                       <span class="badge" id="total_qty" style="position: absolute; top: -10px; right: -10px; background-color: red; color: white; border-radius: 50%; padding: 5px; font-size: 12px;"><?= $total_qty;?></span>
                    <?php endif ?>
                    </i>
                        <span><?= $i->title ?></span>
                    </a>
                <?php endforeach ?>
            </nav>
        </footer>
    </div>
<!-- Modal -->

  <script src="<?= base_url();?>/assets/bootstrap/js/jQuery3.5.1.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
    <link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    
    <script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <script type="text/javascript">

  var loading = document.getElementById('loading');
  $(document).ready(function(){
    var btncart = document.getElementById('btncart');
    var btncartheader = document.getElementById('btncartheader');
    var btnback = document.getElementById('btnback');

  if (btnback) {
      btnback.addEventListener("click", () => {
        var subtext = document.getElementById('subtext').value;
        var subtextArray = subtext.split(',');

        var allItemsPresent = true; // Flag to track if all items are present
        var subkosong = ""; // Variable to store missing sub items

        for (var i = 0; i < subtextArray.length; i++) {
            var ceksub = localStorage.getItem(subtextArray[i]);
            if (ceksub === null) {
                // console.log('ada yg kosong');
                allItemsPresent = false; // Set flag to false if any item is missing
                subkosong += subtextArray[i] + ", "; // Append missing sub items
            }
        }
          if (!allItemsPresent) {
            var st = document.getElementById("jumlahpaket").value;
            if (st > 0) {
                subkosong = subkosong.slice(0, -2); // Remove the last comma and space
                Swal.fire({
                      title: 'Notification!',
                      text: 'You have a few pending package items. Do you wish to delete them?ss',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: "<?= $cn->color ?>",
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes',
                      cancelButtonText: 'No'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          Swal.fire({
                          title: 'Notification!',
                          text: 'The package and its items were successfully removed.',
                          icon: 'success',
                          confirmButtonColor: "<?= $cn->color ?>",
                          confirmButtonText: 'OK'
                          }).then(() => {
                              var loading = document.getElementById('loading');                              
                              loading.style.display = "flex";
                              $("#loading").fadeIn();
                              $("#load").fadeIn();
                              $('#jumlahpaket').val('0');
                              $('#qtyadd').val('0');
                              localStorage.setItem('paket_code', pc);
                              localStorage.setItem('qtypaket', 0);
                              localStorage.setItem('cekqty', 0);
                              var cekjumlah = document.querySelector('.cekjumlah');
                              cekjumlah.value = 0;
                              localStorage.clear();
                              window.location.href = "<?php echo base_url('') ?>index.php/selforder/home/<?= $nomeja ?>";
                          });
                          var nama_paket = localStorage.getItem('nama_paket');
                          $.ajax({
                              url: '<?php echo base_url("index.php/Orderpaket/delete_data"); ?>',
                              type: 'POST',
                              data: { nama_paket: nama_paket },
                              success: function(response) {
                                  var data = JSON.parse(response);
                                  if (data.status === 'success') {
                                      var itemcodepi = '<?= $this->session->userdata('itemcodepi') ?>';
                                      var subc = '<?= $this->session->userdata('subc') ?>';
                                      var sub = subc.split(',');
                                      var itemcodepaket = '<?= $this->session->userdata('itemcodepaket') ?>';
                                      var icpi = itemcodepi.split(',');

                                      // for (var i = icpi.length - 1; i >= 0; i--) {
                                      //     localStorage.removeItem(icpi[i]);
                                      // }
                                      // for (var i = sub.length - 1; i >= 0; i--) {
                                      //     localStorage.removeItem(sub[i]);
                                      // }
                                      // localStorage.removeItem(itemcodepaket);
                                      localStorage.clear();
                                  } else {
                                      // alert('Error deleting data');
                                  }
                              },
                              error: function() {
                                  alert('Error deleting data');
                              }
                          });
                      }
                  });
            }else{
              localStorage.clear();
              window.location.href = "<?php echo base_url('') ?>index.php/selforder/home/<?= $nomeja ?>";
            }

          } else {
            var subactive = document.getElementById('subactive');
            var max_qty_sub = document.getElementById('max_qty_sub');
            var addsubactive = parseInt(localStorage.getItem(subactive.value));
            if (addsubactive < parseInt(max_qty_sub.value)) {
              Swal.fire({
                      title: 'Notification!',
                      text: 'You have a few pending package items. Do you wish to delete them?',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: "<?= $cn->color ?>",
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes',
                      cancelButtonText: 'No'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          Swal.fire({
                          title: 'Notification!',
                          text: 'The package and its items were successfully removed.',
                          icon: 'success',
                          confirmButtonColor: "<?= $cn->color ?>",
                          confirmButtonText: 'OK'
                          }).then(() => {
                              var loading = document.getElementById('loading');                              
                              loading.style.display = "flex";
                              $("#loading").fadeIn();
                              $("#load").fadeIn();
                              $('#jumlahpaket').val('0');
                              $('#qtyadd').val('0');
                              localStorage.setItem('paket_code', pc);
                              localStorage.setItem('qtypaket', 0);
                              localStorage.setItem('cekqty', 0);
                              var cekjumlah = document.querySelector('.cekjumlah');
                              cekjumlah.value = 0;
                              localStorage.clear();
                              window.location.href = "<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Orderpaket/";
                          });
                          var nama_paket = localStorage.getItem('nama_paket');
                          $.ajax({
                              url: '<?php echo base_url("index.php/Orderpaket/delete_data"); ?>',
                              type: 'POST',
                              data: { nama_paket: nama_paket },
                              success: function(response) {
                                  var data = JSON.parse(response);
                                  if (data.status === 'success') {
                                      var itemcodepi = '<?= $this->session->userdata('itemcodepi') ?>';
                                      var subc = '<?= $this->session->userdata('subc') ?>';
                                      var sub = subc.split(',');
                                      var itemcodepaket = '<?= $this->session->userdata('itemcodepaket') ?>';
                                      var icpi = itemcodepi.split(',');

                                      // for (var i = icpi.length - 1; i >= 0; i--) {
                                      //     localStorage.removeItem(icpi[i]);
                                      // }
                                      // for (var i = sub.length - 1; i >= 0; i--) {
                                      //     localStorage.removeItem(sub[i]);
                                      // }
                                      // localStorage.removeItem(itemcodepaket);
                                      localStorage.clear();
                                  } else {
                                      // alert('Error deleting data');
                                  }
                              },
                              error: function() {
                                  alert('Error deleting data');
                              }
                          });
                      }
                  });
            }else{
              localStorage.clear();
                window.location.href = "<?php echo base_url('') ?>index.php/selforder/home/<?= $nomeja ?>";
            }
            
          }
      });
    }
  // if (btncart) {
  //   btncart.addEventListener("click", () => {
  //     var subtext = document.getElementById('subtext').value;
  //     var subtextArray = subtext.split(',');
  //     var allItemsPresent = true; // Flag to track if all items are present
  //     var subkosong = ""; // Variable to store missing sub items
  //     var subactive = document.getElementById('subactive');
  //     var max_qty_sub = document.getElementById('max_qty_sub');
  //     var cekaddsub = localStorage.getItem(subactive.value);

  //     for (var i = 0; i < subtextArray.length; i++) {
  //         var ceksub = localStorage.getItem(subtextArray[i]);

  //         if (ceksub === null) {
  //             allItemsPresent = false; // Set flag to false if any item is missing
  //             subkosong += subtextArray[i] + ", "; // Append missing sub items
  //         }
  //     }
  //       if (!allItemsPresent) {
  //           subkosong = subkosong.slice(0, -2); // Remove the last comma and space
  //           var isi = "You haven't selected a " + subkosong + " item.";
  //           Swal.fire({
  //               title: 'Notification!',
  //               customClass: {
  //                   container: 'my-popup-class'
  //               },
  //               text: isi,
  //               icon: 'warning',
  //               confirmButtonColor: "<?= $cn->color ?>",
  //               confirmButtonText: 'OK'
  //           });
  //       } else if(cekaddsub < max_qty_sub.value){
  //           // var isi = "Ensure the maximum quantity of " +max_qty_sub.value+ " is reached in the " +subactive.value.replace(/_/g, ' ')+ " subcategory before proceeding to the cart page.";
  //           // Swal.fire({
  //           //     title: 'Notification!',
  //           //     customClass: {
  //           //         container: 'my-popup-class'
  //           //     },
  //           //     text: isi,
  //           //     icon: 'warning',
  //           //     confirmButtonColor: "<?= $cn->color ?>",
  //           //     confirmButtonText: 'OK'
  //           // });
  //           Swal.fire({
  //                   title: 'Notification!',
  //                   text: 'You have a few pending package items. Do you wish to delete them?',
  //                   icon: 'warning',
  //                   showCancelButton: true,
  //                   confirmButtonColor: "<?= $cn->color ?>",
  //                   cancelButtonColor: '#d33',
  //                   confirmButtonText: 'Yes',
  //                   cancelButtonText: 'No'
  //               }).then((result) => {
  //                   if (result.isConfirmed) {
  //                       Swal.fire({
  //                       title: 'Notification!',
  //                       text: 'The package and its items were successfully removed.',
  //                       icon: 'success',
  //                       confirmButtonColor: "<?= $cn->color ?>",
  //                       confirmButtonText: 'OK'
  //                       }).then(() => {
  //                           var loading = document.getElementById('loading');                              
  //                           loading.style.display = "flex";
  //                           $("#loading").fadeIn();
  //                           $("#load").fadeIn();
  //                           $('#jumlahpaket').val('0');
  //                           $('#qtyadd').val('0');
  //                           localStorage.setItem('paket_code', pc);
  //                           localStorage.setItem('qtypaket', 0);
  //                           localStorage.setItem('cekqty', 0);
  //                           var cekjumlah = document.querySelector('.cekjumlah');
  //                           cekjumlah.value = 0;
  //                            window.location.href = "<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Orderpaket/";
  //                       });
  //                       var nama_paket = localStorage.getItem('nama_paket');
  //                       $.ajax({
  //                           url: '<?php echo base_url("index.php/Orderpaket/delete_data"); ?>',
  //                           type: 'POST',
  //                           data: { nama_paket: nama_paket },
  //                           success: function(response) {
  //                               var data = JSON.parse(response);
  //                               if (data.status === 'success') {
  //                                   var itemcodepi = '<?= $this->session->userdata('itemcodepi') ?>';
  //                                   var subc = '<?= $this->session->userdata('subc') ?>';
  //                                   var sub = subc.split(',');
  //                                   var itemcodepaket = '<?= $this->session->userdata('itemcodepaket') ?>';
  //                                   var icpi = itemcodepi.split(',');

  //                                   // for (var i = icpi.length - 1; i >= 0; i--) {
  //                                   //     localStorage.removeItem(icpi[i]);
  //                                   // }
  //                                   // for (var i = sub.length - 1; i >= 0; i--) {
  //                                   //     localStorage.removeItem(sub[i]);
  //                                   // }
  //                                   // localStorage.removeItem(itemcodepaket);
  //                                   localStorage.clear();
  //                               } else {
  //                                   // alert('Error deleting data');
  //                               }
  //                           },
  //                           error: function() {
  //                               alert('Error deleting data');
  //                           }
  //                       });
  //                   }
  //               });
  //       }else {
  //         localStorage.clear();
  //           window.location.href = "<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Orderpaket/";
  //       }
  //   });

  //   btncartheader.addEventListener("click", () => {
  //     var subtext = document.getElementById('subtext').value;
  //     var subtextArray = subtext.split(',');

  //     var allItemsPresent = true; // Flag to track if all items are present
  //     var subkosong = ""; // Variable to store missing sub items

  //     for (var i = 0; i < subtextArray.length; i++) {
  //         var ceksub = localStorage.getItem(subtextArray[i]);
  //         if (ceksub === null) {
  //             allItemsPresent = true;
  //         }else{
  //           allItemsPresent = false; // Set flag to false if any item is missing
  //           subkosong += subtextArray[i] + ", "; // Append missing sub items
  //         }
  //     }
  //       if (!allItemsPresent) {
  //           subkosong = subkosong.slice(0, -2); // Remove the last comma and space
  //           var isi = "You haven't selected a " + subkosong + " item.";
  //           Swal.fire({
  //               title: 'Notification!',
  //               customClass: {
  //                   container: 'my-popup-class'
  //               },
  //               text: isi,
  //               icon: 'warning',
  //               confirmButtonColor: "<?= $cn->color ?>",
  //               confirmButtonText: 'OK'
  //           });
  //       } else {
  //         localStorage.clear();
  //           window.location.href = "<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Orderpaket/";
  //       }
  //   });
  // }

      if (btncart) {
      btncart.addEventListener("click", () => {
      var subtext = document.getElementById('subtext').value;
      var subtextArray = subtext.split(',');

      var allItemsPresent = true; // Flag to track if all items are present
      var subkosong = ""; // Variable to store missing sub items

      for (var i = 0; i < subtextArray.length; i++) {
          var ceksub = localStorage.getItem(subtextArray[i]);
          if (ceksub === null) {
              // console.log('ada yg kosong');
              allItemsPresent = false; // Set flag to false if any item is missing
              subkosong += subtextArray[i] + ", "; // Append missing sub items
          }
      }

        if (!allItemsPresent) {
          var st = document.getElementById("jumlahpaket").value;
          if (st > 0) {
              subkosong = subkosong.slice(0, -2); // Remove the last comma and space
              Swal.fire({
                    title: 'Notification!',
                    text: 'You have a few pending package items. Do you wish to delete them?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "<?= $cn->color ?>",
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                        title: 'Notification!',
                        text: 'The package and its items were successfully removed.',
                        icon: 'success',
                        confirmButtonColor: "<?= $cn->color ?>",
                        confirmButtonText: 'OK'
                        }).then(() => {
                            var loading = document.getElementById('loading');                              
                            loading.style.display = "flex";
                            $("#loading").fadeIn();
                            $("#load").fadeIn();
                            $('#jumlahpaket').val('0');
                            $('#qtyadd').val('0');
                            localStorage.setItem('paket_code', pc);
                            localStorage.setItem('qtypaket', 0);
                            localStorage.setItem('cekqty', 0);
                            var cekjumlah = document.querySelector('.cekjumlah');
                            cekjumlah.value = 0;
                            localStorage.clear();
                            window.location.href = "<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Orderpaket/";
                        });
                        var nama_paket = localStorage.getItem('nama_paket');
                        $.ajax({
                            url: '<?php echo base_url("index.php/Orderpaket/delete_data"); ?>',
                            type: 'POST',
                            data: { nama_paket: nama_paket },
                            success: function(response) {
                                var data = JSON.parse(response);
                                if (data.status === 'success') {
                                    var itemcodepi = '<?= $this->session->userdata('itemcodepi') ?>';
                                    var subc = '<?= $this->session->userdata('subc') ?>';
                                    var sub = subc.split(',');
                                    var itemcodepaket = '<?= $this->session->userdata('itemcodepaket') ?>';
                                    var icpi = itemcodepi.split(',');

                                    // for (var i = icpi.length - 1; i >= 0; i--) {
                                    //     localStorage.removeItem(icpi[i]);
                                    // }
                                    // for (var i = sub.length - 1; i >= 0; i--) {
                                    //     localStorage.removeItem(sub[i]);
                                    // }
                                    // localStorage.removeItem(itemcodepaket);
                                    localStorage.clear();
                                } else {
                                    // alert('Error deleting data');
                                }
                            },
                            error: function() {
                                alert('Error deleting data');
                            }
                        });
                    }
                });
          }else{
            localStorage.clear();
            window.location.href = "<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Orderpaket/";
          }

        } else {
          var subactive = document.getElementById('subactive');
          var max_qty_sub = document.getElementById('max_qty_sub');
          var addsubactive = parseInt(localStorage.getItem(subactive.value));
          if (addsubactive < parseInt(max_qty_sub.value)) {
            Swal.fire({
                    title: 'Notification!',
                    text: 'You have a few pending package items. Do you wish to delete them?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "<?= $cn->color ?>",
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                        title: 'Notification!',
                        text: 'The package and its items were successfully removed.',
                        icon: 'success',
                        confirmButtonColor: "<?= $cn->color ?>",
                        confirmButtonText: 'OK'
                        }).then(() => {
                            var loading = document.getElementById('loading');                              
                            loading.style.display = "flex";
                            $("#loading").fadeIn();
                            $("#load").fadeIn();
                            $('#jumlahpaket').val('0');
                            $('#qtyadd').val('0');
                            localStorage.setItem('paket_code', pc);
                            localStorage.setItem('qtypaket', 0);
                            localStorage.setItem('cekqty', 0);
                            var cekjumlah = document.querySelector('.cekjumlah');
                            cekjumlah.value = 0;
                            localStorage.clear();
                            window.location.href = "<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Orderpaket/";
                        });
                        var nama_paket = localStorage.getItem('nama_paket');
                        $.ajax({
                            url: '<?php echo base_url("index.php/Orderpaket/delete_data"); ?>',
                            type: 'POST',
                            data: { nama_paket: nama_paket },
                            success: function(response) {
                                var data = JSON.parse(response);
                                if (data.status === 'success') {
                                    var itemcodepi = '<?= $this->session->userdata('itemcodepi') ?>';
                                    var subc = '<?= $this->session->userdata('subc') ?>';
                                    var sub = subc.split(',');
                                    var itemcodepaket = '<?= $this->session->userdata('itemcodepaket') ?>';
                                    var icpi = itemcodepi.split(',');

                                    // for (var i = icpi.length - 1; i >= 0; i--) {
                                    //     localStorage.removeItem(icpi[i]);
                                    // }
                                    // for (var i = sub.length - 1; i >= 0; i--) {
                                    //     localStorage.removeItem(sub[i]);
                                    // }
                                    // localStorage.removeItem(itemcodepaket);
                                    localStorage.clear();
                                } else {
                                    // alert('Error deleting data');
                                }
                            },
                            error: function() {
                                alert('Error deleting data');
                            }
                        });
                    }
                });
          }else{
            localStorage.clear();
            window.location.href = "<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Orderpaket/";
          }
           
        }
    });
}



    <?php if ($cekpaket == 'habis'): ?>
      localStorage.clear();
    <?php endif ?>
    load = document.querySelector('#load');
    load.classList.add('load');

    var kurangButton = document.getElementById('kurangpaket');
    var tambahButton = document.getElementById('tambahpaket');
    var jumlahInput = document.getElementById('jumlahpaket');

    kurangButton.addEventListener('click', function () {
        var currentValue = parseInt(jumlahInput.value);
        var qtyadd = document.getElementById('qtyadd');
        cek = document.querySelector('#cek');
        // console.log(qtyadd.value)
        if (cek.value >= jumlahInput.value) {
          var isi = "To reduce the package quantity, remove all items by pressing the minus button on each one.";
            Swal.fire({
            title: 'Notification!',
            customClass: {
              container :'my-popup-class'
            },
            text: isi,
            icon: 'warning',
            confirmButtonColor: "<?= $cn->color ?>",
            confirmButtonText: 'OK'
            })
        }else{
          if (currentValue > 0) {
              jumlahInput.value = currentValue - 1;
              localStorage.setItem('qtypaket',currentValue - 1);
              hitungJumlahkan();
          }
        }
        
    });

    tambahButton.addEventListener('click', function () {
        var currentValue = parseInt(jumlahInput.value);
        var pc = document.getElementById('paket_name').value;
        if (pc == 0) {
            var isi = "Please select a package first.";
            Swal.fire({
            title: 'Notification!',
            customClass: {
              container :'my-popup-class'
            },
            text: isi,
            icon: 'warning',
            confirmButtonColor: "<?= $cn->color ?>",
            confirmButtonText: 'OK'
            })
            $('#tambah' + id).prop('disabled', false);
        }else{
            jumlahInput.value = currentValue + 1;
            localStorage.setItem('qtypaket',currentValue + 1);
            $('#tambah').prop('disabled', false);
            hitungJumlahkan();
        }
    });
  });
  setTimeout(berhenti,1000);
  function berhenti() {
    loading.style.display = "none";
   $("#loading").fadeOut();
   $("#load").fadeOut();
  }
  var paketauto = document.getElementById('paketauto').value;

        if (paketauto) {
            var pc = document.getElementById('paket_name').value;
            localStorage.setItem('paket_code',pc);
            $('#sub_category').prop('hidden', false);
            
            var item_code = paketauto; // Get the value of the input field

            $.ajax({
                url: '<?php echo base_url("index.php/Orderpaket/getsub"); ?>',
                type: 'POST',
                dataType: "json",
                data: {
                    item_code: item_code,
                },
                success: function(response) {
                    $('#sub').empty();
                    var subtextValues = []; // Array to store subtext values
                    for (var i = 0; i < response.length; i++) {
                        (function(index) {
                            var elementId = response[index].sub_category.replace(/ /g, '_');
                            subtextValues.push(elementId); // Add elementId to the array
                            var option = '<div class="item" style="border-radius: 10px;"><a href="#' + elementId + '" id="' + elementId + '" class="button '+ elementId +' test'+ elementId +'" style="text-decoration:none;border-radius: 10px;"><label >' + response[index].sub_category + '</label></a></div>';
                            
                            $('#sub').append(option);
                            var sb = document.getElementById("paketauto").value;
                            var subauto = document.getElementById("subp").value;
                            var h = '<?php echo base_url(); ?>index.php/orderpaket/menupaket/' + sb.replace(/ /g, '_') + '/' + subauto;
                            $('#konten').load(h);
                            // console.log(subauto);
                            $('#' + subauto).addClass('active');
                                if (localStorage.getItem(subauto)) {
                                    qtyadd.value = parseInt(localStorage.getItem(subklik.value));
                                } else {
                                    qtyadd.value = 0;
                                }

                            $('#' + elementId).click(function() {
                                var maxqtysub = response[index].max_qty;
                                var max_qty_sub = document.getElementById('max_qty_sub');
                                var max_qty_sub_hold = document.getElementById('max_qty_sub_hold');
                                var jumlahpaket = document.getElementById('jumlahpaket');
                                var jumlahPaketValue = parseInt(jumlahpaket.value) || 0; 

                                // Hitung hasilnya
                                max_qty_sub_hold.value = maxqtysub * jumlahPaketValue;


                                $('.item a').removeClass('active');
                                $(this).addClass('active');
                                var loading = document.getElementById('loading');
                                var qtylimit = document.getElementById('qtylimit');
                                var subklik = document.getElementById('subklik');
                                qtylimit.value = response[index].max_qty;
                                loading.style.display = "flex";
                                $("#loading").fadeIn();
                                $("#load").fadeIn();
                                
                                
                                subklik.value = elementId;
                                var subactive = document.getElementById('subactive');
                                subactive.value = elementId;
                                var h = '<?php echo base_url(); ?>index.php/orderpaket/menupaket/' + sb.replace(/ /g, '_') + '/' + elementId;
                                // console.log(h);
                                qtyadd = document.querySelector('#qtyadd');
                                subklik = document.querySelector('#subklik');
                                if (localStorage.getItem(subklik.value)) {
                                    qtyadd.value = parseInt(localStorage.getItem(subklik.value));
                                } else {
                                    qtyadd.value = 0;
                                }
                                $('#konten').load(h);
                            });
                        })(i);
                    }

                    // console.log(response);
                    document.getElementById("subtext").value = subtextValues.join(',');

                },
                error: function(xhr, status, error) {
                    // Handle error
                    // console.log(xhr.responseText);
                }
            });
        } else {
            $('#sub_category').prop('hidden', true);
        }
  $('#paket').change(function() {
    var pc = document.getElementById('paket_name').value;
    var st = document.getElementById("jumlahpaket").value;
    var loading = document.getElementById('loading');                              
    loading.style.display = "flex";
    $("#loading").fadeIn();
    $("#load").fadeIn();
    var subtext = document.getElementById('subtext').value;
        var subtextArray = subtext.split(',');

        var allItemsPresent = true; // Flag to track if all items are present
        var subkosong = ""; // Variable to store missing sub items

        for (var i = 0; i < subtextArray.length; i++) {
            var ceksub = localStorage.getItem(subtextArray[i]);
            if (ceksub === null) {
                // console.log('ada yg kosong');
                allItemsPresent = false; // Set flag to false if any item is missing
                subkosong += subtextArray[i] + ", "; // Append missing sub items
            }
        }
        if (!allItemsPresent) {
            if (st > 0) {
                loading.style.display = "none";
                $("#loading").fadeOut();
                $("#load").fadeOut();
              Swal.fire({
                  title: 'Notification!',
                  text: 'Do you wish to change to another package ?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: "<?= $cn->color ?>",
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes',
                  cancelButtonText: 'No'
              }).then((result) => {
                  if (result.isConfirmed) {
                      Swal.fire({
                      title: 'Notification!',
                      text: 'Package successfully changed, items from the previous package have been removed.',
                      icon: 'success',
                      confirmButtonColor: "<?= $cn->color ?>",
                      confirmButtonText: 'OK'
                      }).then(() => {
                          var loading = document.getElementById('loading');                              
                          loading.style.display = "flex";
                          $("#loading").fadeIn();
                          $("#load").fadeIn();
                          $('#jumlahpaket').val('0');
                          $('#qtyadd').val('0');
                          localStorage.setItem('paket_code', pc);
                          localStorage.setItem('qtypaket', 0);
                          localStorage.setItem('cekqty', 0);
                          var cekjumlah = document.querySelector('.cekjumlah');
                          cekjumlah.value = 0;

                          if ($('#paket').val() != null) {
                              $('#sub_category').prop('hidden', false);
                          } else {
                              $('#sub_category').prop('hidden', true);
                          }

                          var item_code = document.getElementById("item_code");
                          $.ajax({
                              url: '<?php echo base_url("index.php/Orderpaket/getsub"); ?>',
                              type: 'POST',
                              dataType: "json",
                              data: {
                                  item_code: item_code.value,
                              },
                              success: function(response) {
                                  var first = response[0];
                                  var firstItem = first.sub_category.replace(/ /g, '_');
                                  $('#sub').empty();
                                  var subtextValues = []; // Array to store subtext values

                                  for (var i = 0; i < response.length; i++) {        
                                      (function(index) {
                                          var elementId = response[index].sub_category.replace(/ /g, '_');
                                          subtextValues.push(elementId); // Add elementId to the array

                                          var option = '<div class="item" style="border-radius: 10px;"><a href="#' + elementId + '" id="' + elementId + '" class="button '+ elementId +' test'+ elementId +'" style="text-decoration:none;border-radius: 10px;"><label >' + response[index].sub_category + '</label></a></div>';

                                          $('#sub').append(option);
                                          $('#' + firstItem).addClass('active');
                                          var subactive = document.getElementById('subactive');
                                          subactive.value = firstItem;
                                          var subklik = document.getElementById('subklik');
                                          subklik.value = firstItem;
                                          var subactive = document.getElementById('subactive');
                                          subactive.value = firstItem;
                                          var ic = document.getElementById("item_code").value;
                                          var hf = '<?php echo base_url(); ?>index.php/orderpaket/menupaket/' + ic + '/' + firstItem;
                                          $('#konten').load(hf);

                                          $('#' + elementId).click(function() {
                                              var maxqtysub = response[index].max_qty;
                                              var max_qty_sub = document.getElementById('max_qty_sub');
                                              var max_qty_sub_hold = document.getElementById('max_qty_sub_hold');
                                              var jumlahpaket = document.getElementById('jumlahpaket');
                                              var jumlahPaketValue = parseInt(jumlahpaket.value) || 0; 

                                              // Hitung hasilnya
                                              max_qty_sub_hold.value = maxqtysub * jumlahPaketValue;

                                              $('.item a').removeClass('active');
                                              $(this).addClass('active');
                                              var loading = document.getElementById('loading');
                                              var qtylimit = document.getElementById('qtylimit');
                                              var subklik = document.getElementById('subklik');
                                              loading.style.display = "flex";
                                              $("#loading").fadeIn();
                                              $("#load").fadeIn();

                                              var sb = document.getElementById("item_code").value;
                                              subklik.value = elementId;
                                              var subactive = document.getElementById('subactive');
                                              subactive.value = elementId;
                                              var h = '<?php echo base_url(); ?>index.php/orderpaket/menupaket/' + sb + '/' + elementId;
                                              var qtyadd = document.querySelector('#qtyadd');
                                              subklik = document.querySelector('#subklik');
                                              if (localStorage.getItem(subklik.value)) {
                                                  qtyadd.value = parseInt(localStorage.getItem(subklik.value));
                                              } else {
                                                  qtyadd.value = 0;
                                              }
                                              $('#konten').load(h);
                                          });
                                      })(i);
                                  }

                                  // Set the value of subtext to the comma-separated values
                                  document.getElementById("subtext").value = subtextValues.join(',');

                              },
                              error: function(xhr, status, error) {
                                  // Action to take on error
                                  console.error(xhr.responseText);
                              }
                          });
                        var nama_paket = localStorage.getItem('nama_paket');
                        $.ajax({
                            url: '<?php echo base_url("index.php/Orderpaket/delete_data"); ?>',
                            type: 'POST',
                            data: { nama_paket: nama_paket },
                            success: function(response) {
                                var data = JSON.parse(response);
                                if (data.status === 'success') {
                                      var itemcodepi = '<?= $this->session->userdata('itemcodepi') ?>';
                                      var subc = '<?= $this->session->userdata('subc') ?>';
                                      var sub = subc.split(',');
                                      var itemcodepaket = '<?= $this->session->userdata('itemcodepaket') ?>';
                                      var icpi = itemcodepi.split(',');

                                      for (var i = icpi.length - 1; i >= 0; i--) {
                                          localStorage.removeItem(icpi[i]);
                                      }
                                      for (var i = sub.length - 1; i >= 0; i--) {
                                          localStorage.removeItem(sub[i]);
                                      }
                                      localStorage.removeItem(itemcodepaket);
                                } else {
                                    // alert('Error deleting data');
                                }
                            },
                            error: function() {
                                alert('Error deleting data');
                            }
                        });
                      });
                  } else if (result.dismiss === Swal.DismissReason.cancel) {
                      // Swal.fire(
                      //     'Dibatalkan',
                      //     'File Anda aman :)',
                      //     'error'
                      // );
                      $('#paket').val(localStorage.getItem('nama_paket'));
                          var jp = document.getElementById("jumlahpaket").value;
                          if (jp > 0) {
                              document.getElementById('harga_paket').value = localStorage.getItem('harga_paket');
                              document.getElementById('item_code').value = localStorage.getItem('item_code');
                              document.getElementById('paket_name').value = localStorage.getItem('paket_name');
                              const angka = Number(localStorage.getItem('harga_paket'));

                              let formatRupiah = angka.toLocaleString('id-ID', {
                                  style: 'currency',
                                  currency: 'IDR',
                                  minimumFractionDigits: 0
                              });

                              // Mengubah isi elemen <h3> dengan id "hargatext"
                              document.getElementById('hargatext').innerText = formatRupiah;
                          }
                  }
              })
          } else {
              // Jika tidak ada subtext, jalankan logika perubahan paket langsung
              localStorage.setItem('paket_code', pc);

              if ($(this).val() != null) {
                  $('#sub_category').prop('hidden', false);
              } else {
                  $('#sub_category').prop('hidden', true);
              }
              var item_code = document.getElementById("item_code");
              $.ajax({
                  url: '<?php echo base_url("index.php/Orderpaket/getsub"); ?>',
                  type: 'POST',
                  dataType: "json",
                  data: {
                      item_code: item_code.value,
                  },
                  success: function(response) {
                      var first = response[0];
                      var firstItem = first.sub_category.replace(/ /g, '_');
                      $('#sub').empty();
                      var subtextValues = []; // Array to store subtext values

                      for (var i = 0; i < response.length; i++) {        
                          (function(index) {
                              var elementId = response[index].sub_category.replace(/ /g, '_');
                              subtextValues.push(elementId); // Add elementId to the array

                              var option = '<div class="item" style="border-radius: 10px;"><a href="#' + elementId + '" id="' + elementId + '" class="button '+ elementId +' test'+ elementId +'" style="text-decoration:none;border-radius: 10px;"><label >' + response[index].sub_category + '</label></a></div>';

                              $('#sub').append(option);
                              $('#' + firstItem).addClass('active');
                              var subactive = document.getElementById('subactive');
                              subactive.value = firstItem;
                              localStorage.setItem(firstItem, 0);

                              var subklik = document.getElementById('subklik');
                              subklik.value = firstItem;
                              var subactive = document.getElementById('subactive');
                              subactive.value = firstItem;
                              var ic = document.getElementById("item_code").value;
                              var hf = '<?php echo base_url(); ?>index.php/orderpaket/menupaket/' + ic + '/' + firstItem;
                              $('#konten').load(hf);

                              $('#' + elementId).click(function() {
                                  var maxqtysub = response[index].max_qty;
                                  var max_qty_sub = document.getElementById('max_qty_sub');
                                  var max_qty_sub_hold = document.getElementById('max_qty_sub_hold');
                                  var jumlahpaket = document.getElementById('jumlahpaket');
                                  var jumlahPaketValue = parseInt(jumlahpaket.value) || 0; 

                                  // Hitung hasilnya
                                  max_qty_sub_hold.value = maxqtysub * jumlahPaketValue;

                                  $('.item a').removeClass('active');
                                  $(this).addClass('active');
                                  var loading = document.getElementById('loading');
                                  var qtylimit = document.getElementById('qtylimit');
                                  var subklik = document.getElementById('subklik');
                                  loading.style.display = "flex";
                                  $("#loading").fadeIn();
                                  $("#load").fadeIn();

                                  var sb = document.getElementById("item_code").value;
                                  subklik.value = elementId;
                                  var subactive = document.getElementById('subactive');
                                  var cekqtyaddsub = parseInt(localStorage.getItem(subactive.value));

                                  if (cekqtyaddsub >= max_qty_sub.value) {
                                    max_qty_sub.value = max_qty_sub_hold.value;
                                    subactive.value = elementId;
                                    var h = '<?php echo base_url(); ?>index.php/orderpaket/menupaket/' + sb + '/' + elementId;
                                    var qtyadd = document.querySelector('#qtyadd');
                                    subklik = document.querySelector('#subklik');
                                    if (localStorage.getItem(subklik.value)) {
                                        qtyadd.value = parseInt(localStorage.getItem(subklik.value));
                                    } else {
                                        qtyadd.value = 0;
                                    }
                                    $('#konten').load(h);
                                  }else{
                                    var pesan = "Ensure the maximum quantity of " +max_qty_sub.value+ " is reached in the " +subactive.value.replace(/_/g, ' ')+ " subcategory before switching to another subcategory."
                                    Swal.fire({
                                        title: 'Notification!',
                                        customClass: {
                                            container: 'my-popup-class'
                                        },
                                        text: pesan,
                                        icon: 'warning',
                                        confirmButtonColor: "<?= $cn->color ?>",
                                        confirmButtonText: 'OK'
                                    });
                                    var h = '<?php echo base_url(); ?>index.php/orderpaket/menupaket/' + sb + '/' + subactive.value;
                                    var qtyadd = document.querySelector('#qtyadd');
                                    subklik = document.querySelector('#subklik');
                                    if (localStorage.getItem(subactive.value)) {
                                        qtyadd.value = parseInt(localStorage.getItem(subactive.value));
                                    } else {
                                        qtyadd.value = 0;
                                    }
                                    $('.item a').removeClass('active');
                                    $('#'+subactive.value).addClass('active');
                                    $('#konten').load(h);
                                  }
                                  
                              });
                          })(i);
                      }

                      // Set the value of subtext to the comma-separated values
                      document.getElementById("subtext").value = subtextValues.join(',');

                  },
                  error: function(xhr, status, error) {
                      // Action to take on error
                      console.error(xhr.responseText);
                  }
              });
          }
        }else{
          localStorage.setItem('paket_code', pc);

              if ($(this).val() != null) {
                  $('#sub_category').prop('hidden', false);
              } else {
                  $('#sub_category').prop('hidden', true);
              }
              var item_code = document.getElementById("item_code");
              $.ajax({
                  url: '<?php echo base_url("index.php/Orderpaket/getsub"); ?>',
                  type: 'POST',
                  dataType: "json",
                  data: {
                      item_code: item_code.value,
                  },
                  success: function(response) {
                      var first = response[0];
                      var firstItem = first.sub_category.replace(/ /g, '_');
                      $('#sub').empty();
                      var subtextValues = []; // Array to store subtext values

                      for (var i = 0; i < response.length; i++) {        
                          (function(index) {
                              var elementId = response[index].sub_category.replace(/ /g, '_');
                              subtextValues.push(elementId); // Add elementId to the array

                              var option = '<div class="item" style="border-radius: 10px;"><a href="#' + elementId + '" id="' + elementId + '" class="button '+ elementId +' test'+ elementId +'" style="text-decoration:none;border-radius: 10px;"><label >' + response[index].sub_category + '</label></a></div>';

                              $('#sub').append(option);
                              $('#' + firstItem).addClass('active');
                              var subklik = document.getElementById('subklik');
                              subklik.value = firstItem;
                              var subactive = document.getElementById('subactive');
                              subactive.value = firstItem;
                              var ic = document.getElementById("item_code").value;
                              var hf = '<?php echo base_url(); ?>index.php/orderpaket/menupaket/' + ic + '/' + firstItem;
                              $('#konten').load(hf);

                              $('#' + elementId).click(function() {
                                  var maxqtysub = response[index].max_qty;
                                  var max_qty_sub = document.getElementById('max_qty_sub');
                                  var max_qty_sub_hold = document.getElementById('max_qty_sub_hold');
                                  var jumlahpaket = document.getElementById('jumlahpaket');
                                  var jumlahPaketValue = parseInt(jumlahpaket.value) || 0; 

                                  // Hitung hasilnya
                                  max_qty_sub_hold.value = maxqtysub * jumlahPaketValue;

                                  $('.item a').removeClass('active');
                                  $(this).addClass('active');
                                  var loading = document.getElementById('loading');
                                  var qtylimit = document.getElementById('qtylimit');
                                  var subklik = document.getElementById('subklik');
                                  loading.style.display = "flex";
                                  $("#loading").fadeIn();
                                  $("#load").fadeIn();

                                  var sb = document.getElementById("item_code").value;
                                  subklik.value = elementId;
                                  var subactive = document.getElementById('subactive');
                                  subactive.value = elementId;
                                  var h = '<?php echo base_url(); ?>index.php/orderpaket/menupaket/' + sb + '/' + elementId;
                                  var qtyadd = document.querySelector('#qtyadd');
                                  subklik = document.querySelector('#subklik');
                                  if (localStorage.getItem(subklik.value)) {
                                      qtyadd.value = parseInt(localStorage.getItem(subklik.value));
                                  } else {
                                      qtyadd.value = 0;
                                  }
                                  $('#konten').load(h);
                              });
                          })(i);
                      }

                      // Set the value of subtext to the comma-separated values
                      document.getElementById("subtext").value = subtextValues.join(',');

                  },
                  error: function(xhr, status, error) {
                      // Action to take on error
                      console.error(xhr.responseText);
                  }
              });
        }
});

</script>

<script type="text/javascript">
        <?php echo $jsArrayPaket; ?>
        function paketValue(id){
          var jp = document.getElementById("jumlahpaket").value;
          if (jp == 0) {
              localStorage.setItem('harga_paket',prdNamePaket[id].harga_weekend);
              localStorage.setItem('item_code',prdNamePaket[id].item_code);
              localStorage.setItem('paket_name',prdNamePaket[id].description);
              localStorage.setItem('nama_paket',id);
          }
          document.getElementById('harga_paket').value = prdNamePaket[id].harga_weekend;
          document.getElementById('item_code').value = prdNamePaket[id].item_code;
          document.getElementById('paket_name').value = prdNamePaket[id].description;

          const angka = Number(document.getElementById('harga_paket').value);

            let formatRupiah = angka.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            // Mengubah isi elemen <h3> dengan id "hargatext"
            document.getElementById('hargatext').innerText = formatRupiah;

        }
</script>
<!-- <?php foreach ($sub as $i ): ?>
<script type="text/javascript">
      $(document).ready(function(){

        var key = "<?= $key ?>";
        if (key != '') {
          $('#konten').load('<?= base_url() ?>index.php/orderpaket/search/1');
        }
         if (window.location.toString() == "<?= base_url() ?>index.php/orderpaket#<?= str_replace(" ","%20", $i['sub_category']) ?>") {
          // console.log('berhasil');
          $('#konten').load('<?= base_url() ?>index.php/orderpaket/menu/<?= str_replace(" ","%20", $i['sub_category']) ?>#<?= str_replace(" ","_", $i['sub_category']) ?>');
         }else if (window.location.toString() == "<?= base_url() ?>index.php/orderpaket/menu/rekomendasi#rekomendasi") {
          $('#konten').load('<?= base_url() ?>orderpaket/menu/rekomendasi#rekomendasi');
         }
          

        
      });
    </script>
<?php endforeach ?> -->


  <?php $this->load->view('template/footer') ?>
  