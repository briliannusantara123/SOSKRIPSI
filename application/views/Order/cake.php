<?php $this->load->view('template/head') ?>
    <style type="text/css">
      .my-popup-class {
        z-index: 10000001;
      }
    
          
        /* Media Query for low resolution  Tablets, Ipads */
        
      
        @media (min-height: 720px) {
            .md {
       margin-top: 85px;
       top: 10px;
       right: 0px;
       width: 350px;
       height:540px;
       bottom: 0;
       z-index: 9998;
       left: 160px;
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
       left: 160px;
       overflow: auto;
       overflow-y: auto;
    }
    #imgmodal{
        width: 170px;height: 120px;border-radius: 20px; display: block;margin-left: auto;margin-right: auto;
    }
    #description{
        color: #198754;float: left;font-size: 15px;
    }
    #product_info{
        color: #198754;margin-top: 2px;float: left;font-size: 15px;
    }
    #harga{
        color: #198754;margin-top: 2px;float: right;font-size: 15px;
    }
    #option{
      float: left;color: #198754;font-size: 15px;
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
  color: #198754;
  
  padding:16px 30px;
  text-align: center;

  text-decoration: none;
  display: inline-block;
  font-size: 12px;
  transition-duration: 0.4s;
}

.active{
   background-color: #198754;
  color: white;
  
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
  /*background-color: #198754;*/ /* Green */
  background-color: #198754;
  border-radius: 10px;
  color: white;
}
.wrapper{
  /*padding-bottom: 60px;*/
  border:1px solid #198754;
  display: flex;
  overflow: scroll; 
}
.wrapper .item{
  
  text-align: center;
  background-color: #ddd;
  margin-right: 2px;
}

.cari::-webkit-input-placeholder{
  color: #198754;
}
 
/*support mozilla*/
.cari:-moz-input-placeholder{
  color: #198754;
}
.text{
  color: #198754;
}
.ENTREMETS{
   background-image: url("<?= base_url() ?>/assets/icon/entremets.png");
  background-repeat: no-repeat;
  background-size: 55%;
  background-position-y: 20%;
  background-position-x: 50%;
}
.BREAD{
   background-image: url("<?= base_url() ?>/assets/icon/bread.png");
  background-repeat: no-repeat;
  background-size: 65%;
  background-position-y: 20%;
  background-position-x: 50%;
}
.ECLAIR{
   background-image: url("<?= base_url() ?>/assets/icon/eclair.png");
  background-repeat: no-repeat;
  background-size: 60%;
  background-position-y: 20%;
  background-position-x: 50%;
}
.CHOUX{
   background-image: url("<?= base_url() ?>/assets/icon/choux.png");
  background-repeat: no-repeat;
  background-size: 60%;
  background-position-y: 20%;
  background-position-x: 50%;
}
.WHOLE_CAKE{
   background-image: url("<?= base_url() ?>/assets/icon/wholecake.png");
  background-repeat: no-repeat;
  background-size: 50%;
  background-position-y: 20%;
  background-position-x: 50%;
}
.SLICE{
   background-image: url("<?= base_url() ?>/assets/icon/slice.png");
  background-repeat: no-repeat;
  background-size: 70%;
  background-position-y: 20%;
  background-position-x: 50%;
}
.PERSONAL{
   background-image: url("<?= base_url() ?>/assets/icon/personal.png");
  background-repeat: no-repeat;
  background-size: 55%;
  background-position-y: 20%;
  background-position-x: 50%;
}
.all{
  background-image: url("<?= base_url() ?>/assets/icon/all.png");
  background-repeat: no-repeat;
  background-size: 80%;
  background-position-y: 20%;
  background-position-x: 50%;
}

.load{
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
<div class="row">
<header style="display: flex;width:100%; position: fixed;z-index: 100000;margin-top: 8px;background-color: white;border-bottom-right-radius: 5%;
  border-bottom-left-radius: 5%;">

<div class="wrapper" style="background-color: white;">
  <!-- <div class="item">
      <a href="#rekomendasi" id="rekomendasi" class="button rekomendasi testrekomendasi" style="text-decoration:none;padding: 10px 10px;padding-top: 22px;"><p style="margin-top: 60px;margin-bottom: 1px;">recommendation</p></a>
  </div> -->
  <!-- <div class="item">
      <a href="#" id="all" class="button all" style="text-decoration:none"><p style="margin-top: 60px;margin-bottom: 1px;">   ALL   </p></a>
  </div> -->
 <?php foreach($sub as $i){ ?>
    <div class="item">
      <a href="#<?= str_replace(" ","_", $i['sub_category_so']) ?>" id="<?= str_replace(" ","_", $i['sub_category_so']) ?>" class="button <?= str_replace(" ","_", $i['sub_category_so']) ?> test<?= str_replace(" ","_", $i['sub_category_so']) ?> sub"  style="text-decoration:none"><p style="margin-top: 60px;margin-bottom: 1px;"><?= str_replace(" ","", $i['sub_category_so'])?></p></a></div>
 <?php  }  ?>
</div>



<!-- <div class="container" style="display: flex;margin-bottom: 20px; margin-top: 20px;background-color: white;border-radius: 40%;">
  <div class="row row-cols-3">
    <?php foreach($sub as $i){ ?>  
<div class="container text-center">
  <div class="row">
    <div class="col">
      <a href="<?= base_url() ?>ordermakanan/menu/Makanan/<?= $i['description'] ?>/<?= $nomeja ?>" class="button <?= str_replace(" ","_", $i['description']) ?>"><?= str_replace(" ","_", $i['description'])?></a>
      
    </div>
    <div class="col">
      
      <div class="container text-center">
  <div class="row">
    <div class="col">
      
    </div>
  </div>
</div>
    </div>
  </div>
</div>

<?php } ?>
  </div> -->
</div>
</header>
  <div class="container text-center" style="margin-top: 130px;display: flex;width:70%; position: fixed;z-index: 100000; ">
    <input type="text" name="keyword" class="form-control cari"  id="search" placeholder="Find Food..." style="border:1px solid #198754;">
  </div>
<!-- <?= base_url() ?>ordermakanan/addcart/<?= $nomeja ?> -->
<div id="kon">
  
</div>
<div id="konten">
  
</div>

<!-- <button type="submit" class="btn btn-outline-success" style="padding-top: 20px;padding-bottom: 20px;padding-left: 50px;padding-right: 50px;">
  Order 
</button> -->
<!-- <a href="<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Makanan/<?= $s ?>#<?= $s ?>" class="btn btn-outline-success" style="padding-top: 20px;padding-bottom: 20px;padding-left: 40px;padding-right: 40px;">Cart<i class="fa fa-cart-plus"></i> <b id="total_qty" align="right"><?= $total_qty;?></b></a>
<a href="<?php echo base_url('') ?>index.php/selforder/home/<?= $nomeja ?>" class="btn btn-outline-danger" style="padding-top: 20px;padding-bottom: 20px;padding-left: 40px;padding-right: 40px;">Back</a> -->
</form>
<?php foreach ($item as $i): ?>
  <?php if ($i->with_option) : ?>
  <div class="md modal fade" id="exampleModal<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <?php else: ?>
  <div class="mdl modal fade" id="exampleModal<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<?php endif ?>

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #198754;color: white;">
        <h5 class="modal-title" style="text-align: center;margin-left: 42%;" id="exampleModalLabel">Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="tutuptab<?= $i->id ?>()" id="close"></button>
      </div>
      <div class="modal-body">
        <?php if ( $i->image_path != "" ): ?>
        <img src="<?= $i->image_path ?>" alt="Red dot"  id="imgmodal" class="img-fluid" />
        <?php  else: ?>
          <img src="<?= base_url();?>assets/logo.png" id="imgmodal" alt="Red dot" class="img-fluid" />
      <?php endif ?>
    <form action="#" method="post" id="input<?= $i->id ?>">
      <div class="card mb-3">
        
        <div class="card-body">

          <h5 id="description"><?= $i->description ?></h5><br>  
          <h6 id="product_info"><?= $i->product_info ?></h6>
          <?php if ($i->harga_weekday == 0): ?>
                <h6>Free</h6>
              <?php elseif ($i->harga_weekend == 0): ?>
                <h6>Free</h6>
              <?php elseif ($i->harga_holiday == 0): ?>
                <h6>Free</h6>
              <?php else: ?>
                <?php 
                    $hr = date('l');
                    $date = date('Y-m-d');
                    $time = date('H:i:s');  
                    $holiday = $this->Item_model->get_holiday($date);
                    $waktu = $this->db->order_by('id',"desc")
                    ->limit(1)
                    ->get('sh_m_setup')
                    ->row('item_time_check');
                    // var_dump($holiday);exit();
                ?>
                <?php if ( $holiday == NULL): ?>
                   <?php  if ($hr == "Saturday" || $hr == "Sunday") :?>
                    <h6 class="text">Rp <?= number_format($i->harga_weekend) ?></h6>
                    <?php else: ?>
                    <h6 class="text">Rp <?= number_format($i->harga_weekday) ?></h6>
                  <?php   endif ?>
                <?php  else: ?>
                  <?php  if ($hr == "Saturday" || $hr == "Sunday") :?>
                    <h6 class="text">Rp <?= number_format($i->harga_weekend) ?></h6>
                <?php elseif ($holiday->tipe == 0) :?>
                    <h6 class="text">Rp <?= number_format($i->harga_weekend) ?></h6>
                <?php elseif ($holiday->tipe == 1 && $time >= $waktu) :?>
                    <h6 class="text">Rp <?= number_format($i->harga_weekend) ?></h6>
                <?php elseif ($holiday->tipe == 1 && $time <= $waktu) :?>
                    <h6 class="text">Rp <?= number_format($i->harga_weekday) ?></h6>
                <?php else: ?>
                  <h6 class="text">Rp <?= number_format($i->harga_weekday) ?></h6>
                <?php endif ?>
                <?php endif ?>
                
              <?php endif ?>
          
      <input type="hidden" name="nama<?= $i->id ?>" id="nama<?= $i->id ?>" value="<?= $i->description ?>" class="form-control nama">
                <?php 
                    $hr = date('l');
                    $date = date('Y-m-d');
                    $time = date('H:i:s');  
                    $holiday = $this->Item_model->get_holiday($date);
                    $waktu = $this->db->order_by('id',"desc")
                    ->limit(1)
                    ->get('sh_m_setup')
                    ->row('item_time_check');
                    // var_dump($holiday);exit();
                ?>
                <?php if ($holiday == NULL): ?>
                  <?php  if ($hr == "Saturday" || $hr == "Sunday") :?>
                    <input type="hidden" name="harga<?= $i->id ?>" id="harga<?= $i->id ?>" value="<?= $i->harga_weekend ?>" class="form-control harga">
                  <?php else: ?>  
                    <input type="hidden" name="harga<?= $i->id ?>" id="harga<?= $i->id ?>" value="<?= $i->harga_weekday ?>" class="form-control harga">
                  <?php endif ?>
                <?php else: ?>  
                <?php  if ($hr == "Saturday" || $hr == "Sunday") :?>
                    <input type="hidden" name="harga<?= $i->id ?>" id="harga<?= $i->id ?>" value="<?= $i->harga_weekend ?>" class="form-control harga">
                <?php elseif ($holiday->tipe == 0) :?>
                    <input type="hidden" name="harga<?= $i->id ?>" id="harga<?= $i->id ?>" value="<?= $i->harga_weekend ?>" class="form-control harga">
                <?php elseif ($holiday->tipe == 1 && $time >= $waktu) :?>
                    <input type="hidden" name="harga<?= $i->id ?>" id="harga<?= $i->id ?>" value="<?= $i->harga_weekend ?>" class="form-control harga">
                <?php elseif ($holiday->tipe == 0 && $time <= $waktu) :?>
                    <input type="hidden" name="harga<?= $i->id ?>" id="harga<?= $i->id ?>" value="<?= $i->harga_weekday ?>" class="form-control harga">
                <?php else: ?>
                  <input type="hidden" name="harga<?= $i->id ?>" id="harga<?= $i->id ?>" value="<?= $i->harga_weekday ?>" class="form-control harga">
                <?php endif ?>
                <?php endif ?>
                
      
      <input type="hidden" name="no<?= $i->id ?>" id="no<?= $i->id ?>" value="<?= $i->no ?>" class="form-control">
      <input type="hidden" name="id" id="id<?= $i->id ?>" value="<?= $i->id ?>" class="form-control">
        </div>
      </div>

      <div class="card" style="margin-bottom: 10px;">
        <ul class="list-group list-group-flush">
          <?php $options = $this->Item_model->option($i->no); ?>
          <?php $options2 = $this->Item_model->option2($i->no); ?>
          <?php $options3 = $this->Item_model->option3($i->no); ?>
          <?php if ($i->with_option == 1): ?>
            <select name="pesan<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: #198754;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>

          <?php elseif($i->with_option == 2): ?>
            <select name="pesan<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: #198754;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>
            <div style="margin-top:5px;"></div>  
            <select name="pesandua<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: #198754;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options2 as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>
            </select>
          <?php elseif($i->with_option == 3): ?>
            <select name="pesan<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: #198754;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>
            <div style="margin-top:5px;"></div>  
            <select name="pesandua<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: #198754;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options2 as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>
            <div style="margin-top:5px;"></div>  
            <select name="pesantiga<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: #198754;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options3 as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>
          <?php endif ?>
        </ul>
      </div>
      
      
      <div class="container text-center" style="display: flex;justify-content: center;">
      <div class="row row-cols-auto">
        <?php if ($i->with_option == 1 || $i->with_option == 2 || $i->with_option == 3): ?>
        <div class="col"><button type="button" class="btn btn-success minus<?= $i->id ?>" style="padding-left: 15px;padding-right: 15px;"> - </button></div>
        <div class="col"><input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"   class="form-control num<?= $i->id ?>" value="0" style="border:1px solid #198754;margin-bottom: 5px;color: #198754; width:43px;text-align: center; "  readonly></div>
        <input type="hidden" value="<?= $i->stock?>" id="stock<?= $i->id ?>">
        <input type="hidden" value="<?= $i->need_stock?>" id="ns<?= $i->id ?>">
        <input type="hidden" value="<?= $total_qty ?>" id="tqi<?= $i->id ?>">
        
        <div class="col"><button type="button" class="btn btn-success plus<?= $i->id ?>" style="padding-left: 15px;padding-right: 15px;">+</button></div>

      </div>
    </div>
    <!-- <button type="button" class="btn btn-success" style="padding-left: 45px;padding-right: 45px;" data-bs-dismiss="modal" aria-label="Close">Tambah Ke Keranjang <a id="total<?= $i->id ?>"></a></button> -->
    <button id="simpan<?= $i->id ?>" type="button" class="btn btn-success" data-bs-dismiss="modal" aria-label="Close"  onclick="simpanalert('<?= $i->need_stock ?>')">Add to Cart <a id="total<?= $i->id ?>"></a></button>
    <?php endif ?>
      </div>
      
</div>
      </div>
    </div>
  </div>
</div>
</form>

<?php endforeach ?>

<br>
</div>
   
<!-- Modal -->

  <script src="<?= base_url();?>/assets/bootstrap/js/jQuery3.5.1.min.js"></script>

 <script type="text/javascript">
  
  function OrderQty(tipe,id,no,stock,need_stock,uoi) {
    var itemCode = $('#no' + id).val();
    var desc = $('#nama' + id).val();
    var price = $('#harga' + id).val();
    var notes = $('#pesan' + id).val();
    var notesdua = $('#pesandua' + id).val();
    var notestiga = $('#pesantiga' + id).val();
    // $('#kurang' + id).prop('disabled', true);
    // $('#tambah' + id).prop('disabled', true);
    $.ajax({
      type:'POST',
      data: {tipe: tipe,id: id,item_code: itemCode,description: desc,unit_price: price,extra_notes: notes,no: no,stock: stock,need_stock: need_stock,uoi: uoi,notesdua:notesdua,notestiga:notestiga},
      url: '<?= base_url().'index.php/ordermakanan/orderqty' ?>',
      dataType:'json',})
      .done(function (hasil){
        localStorage.setItem(no,hasil.new_qty);
        
        if(hasil.status == true){
          $('#kurang' + id).prop('disabled', false);
          $('#tambah' + id).prop('disabled', false);
          
          $('#jumlah' + id).val(hasil.new_qty);
          $('#qtycart' + id).text("Cart Qty "+localStorage.getItem(no));
          $('#pesan' + id).val(hasil.pesan);
          $('#pesandua' + id).val(hasil.pesandua);
          $('#pesantiga' + id).val(hasil.pesantiga);
          $('#cart_count').text(hasil.cart_count);
          $('#total_qty').text(hasil.total_qty);
          if (hasil.cek == true) {
            var isi = hasil.notif;
            Swal.fire({
            title: 'Notification!',
            text: isi,
            icon: 'warning',
            confirmButtonColor: "#198754",
            confirmButtonText: 'OK'
            })
          }

        }
      });
  }
</script>
<?php foreach($sub as $i): ?>
<script type="text/javascript">
  var<?= str_replace(" ","_", $i['sub_category_so']) ?>= document.getElementById('<?= str_replace(" ","_", $i['sub_category_so']) ?>');
  var all = document.getElementById('all');
  var rekomendasi = document.getElementById('rekomendasi');
  var item<?= str_replace(" ","_", $i['sub_category_so']) ?> = document.querySelector('.test<?= str_replace(" ","_", $i['sub_category_so']) ?>');
  var itemall = document.querySelector('.testall');
  var itemrekomendasi = document.querySelector('.testrekomendasi');

    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/<?= str_replace(" ","%20", $i['sub_category_so']) ?>#<?= str_replace(" ","_", $i['sub_category_so']) ?>") {
        $(document).ready(function() {
        item<?= str_replace(" ","_", $i['sub_category_so']) ?>.classList.add('active');
    });
    }
</script>
<?php endforeach; ?>
<?php foreach ($item as $i ): ?>
  <script type="text/javascript">
      $('#simpan<?= $i->id ?>').on('click',function(){
          var input<?= $i->id ?> = $('#input<?= $i->id ?>').serialize();
          var qtya<?= $i->id ?> = document.getElementById("qty<?= $i->id ?>");
          var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
          var tqi<?= $i->id ?> = document.querySelector("#tqi<?= $i->id ?>");
          var stock<?= $i->id ?> = document.querySelector("#stock<?= $i->id ?>");
          var ns<?= $i->id ?> = document.querySelector("#ns<?= $i->id ?>");

            if (ns<?= $i->id?>.value == 1) {
                if (qtya<?= $i->id ?>.value >= stock<?= $i->id ?>.value - tqi<?= $i->id ?>.value + 1) {
                  var isi = "Food Stocks Are Not Fulfilled";
                  Swal.fire({
                  title: 'Notification!',
                  customClass: {
                    container :'my-popup-class'
                  },
                  text: isi,
                  icon: 'warning',
                  confirmButtonColor: "#198754",
                  confirmButtonText: 'OK'
                  })
                }else{
                  $.ajax({
                  url : '<?=base_url()?>index.php/Ordermakanan/add_cart',
                  type : 'POST',
                  data: input<?= $i->id ?>,
                  dataType : 'JSON',
                  success : function(data){
                    var qty<?= $i->id ?> = localStorage.getItem('<?=$i->no?>')+qtya<?= $i->id ?>.value;
                    console.log(qty<?= $i->id ?>);
                    localStorage.setItem('<?= $i->no ?>',qty<?= $i->id ?> + qa);

                   
                  }
                })
                }
            }else{
              $.ajax({
                  url : '<?=base_url()?>index.php/Ordermakanan/add_cart',
                  type : 'POST',
                  data: input<?= $i->id ?>,
                  dataType : 'JSON',
                  success : function(data){
                    var qty<?= $i->id ?> = localStorage.getItem('<?=$i->no?>')+qtya<?= $i->id ?>.value;
                    console.log(qty<?= $i->id ?>);
                    localStorage.setItem('<?= $i->no ?>',qty<?= $i->id ?> + qa);

                    
                  }
                })
            }
          
          
      })
  function simpanalert(need_stock) {
      if (need_stock == 0) {
        Swal.fire({
            title: 'Success!',
            text: 'Menu Added to Cart',
            icon: 'success',
            confirmButtonColor: "#198754",
            confirmButtonText: 'OK'
            })
      }

    }
  </script>
<?php endforeach ?>
<script type="text/javascript">
  var loading = document.getElementById('loading');
  $(document).ready(function(){
    load = document.querySelector('#load');
    load.classList.add('load');
  });
  setTimeout(berhenti,1000);
  function berhenti() {
    loading.style.display = "none";
   $("#loading").fadeOut();
   $("#load").fadeOut();
  }
</script>
<?php foreach ($item as $i ): ?>
<script type="text/javascript">
  

if (localStorage.getItem('<?=$i->no?>')) {
  var simpan<?= $i->id ?> = document.getElementById('simpan<?= $i->id ?>');
  var qtya<?= $i->id ?> = document.getElementById("qty<?= $i->id ?>");
  var tqi<?= $i->id ?> = document.querySelector("#tqi<?= $i->id ?>");
  var stock<?= $i->id ?> = document.querySelector("#stock<?= $i->id ?>");
  var ns<?= $i->id ?> = document.querySelector("#ns<?= $i->id ?>");
  if (simpan<?= $i->id ?>) {
  simpan<?= $i->id ?>.addEventListener("click", ()=>{
    if (qtya<?= $i->id ?>.value >= stock<?= $i->id ?>.value - tqi<?= $i->id ?>.value +1) {
      }else{
        location.reload();
      }
    
    var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
    var qtypop<?= $i->id ?> = document.getElementById('qty<?= $i->id ?>');
    localStorage.setItem('<?= $i->no ?>', qa+parseInt(qtypop<?= $i->id ?>.value));
    console.log(window.location.toString());
    setTimeout(function() {
            if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Salad#Salad") {
      var h = '<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/Salad#Salad';
          // alert(h);
          $('#konten').load(h);
    }
    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Rice#Rice") {
          var h = '<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/Rice#Rice';
              // alert(h);
              $('#konten').load(h);
        }
    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Package#Package") {
      var h = '<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/Package#Package';
          // alert(h);
          $('#konten').load(h);
    }
    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Western#Western") {
      var h = '<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/Western#Western';
          // alert(h);
          $('#konten').load(h);
    }
        }, 500);
    
    
  });
}
}else{
  var simpan<?= $i->id ?> = document.getElementById('simpan<?= $i->id ?>');
  var qtya<?= $i->id ?> = document.getElementById("qty<?= $i->id ?>");
  var tqi<?= $i->id ?> = document.querySelector("#tqi<?= $i->id ?>");
  var stock<?= $i->id ?> = document.querySelector("#stock<?= $i->id ?>");
  var ns<?= $i->id ?> = document.querySelector("#ns<?= $i->id ?>");

  if (simpan<?= $i->id ?>) {
    simpan<?= $i->id ?>.addEventListener("click", ()=>{
    if (qtya<?= $i->id ?>.value >= stock<?= $i->id ?>.value - tqi<?= $i->id ?>.value +1) {
      }else{
        location.reload();
      }
    
    var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
    var qp<?= $i->id ?> = document.getElementById('qty<?= $i->id ?>');
    localStorage.setItem('<?= $i->no ?>', qp<?= $i->id ?>.value);
    if (localStorage.getItem('sim')) {
        var sim = parseInt(localStorage.getItem('sim'));
        localStorage.setItem('sim', parseInt(qp<?= $i->id ?>.value) + sim);
    }else{
        localStorage.setItem('sim', qp<?= $i->id ?>.value);  
    }
    
  });
  }
  
}
</script>
<?php endforeach; ?>


<?php foreach ($item as $i ): ?>
<script type="text/javascript">
  var ns<?= $i->id ?> = document.querySelector("#ns<?= $i->id ?>");
  var stock<?= $i->id ?> = document.querySelector("#stock<?= $i->id ?>");
  var tqi<?= $i->id ?> = document.querySelector("#tqi<?= $i->id ?>");
  var plus<?= $i->id ?> = document.querySelector(".plus<?= $i->id ?>");
  var nambah<?= $i->id ?> = document.querySelector(".nambah<?= $i->id ?>");
  var minus<?= $i->id ?> = document.querySelector(".minus<?= $i->id ?>");
  var num<?= $i->id ?> = document.querySelector(".num<?= $i->id ?>");
  var harga<?= $i->id ?> = document.querySelector("#harga<?= $i->id ?>");
  var total<?= $i->id ?> = document.querySelector("#total<?= $i->id ?>");
  var qtycart<?= $i->id ?> = document.querySelector("#qtycart<?= $i->id ?>");
  var tq = document.querySelector("#total_qty");

  // if (localStorage.getItem('<?= $i->no ?>') != null) {
  //   // num<?= $i->id ?>.value = localStorage.getItem('<?= $i->no ?>');
  //   // total<?= $i->id ?>.innerHTML = localStorage.getItem('H<?= $i->no ?>');
  //   // var a<?= $i->id ?> =localStorage.getItem('<?= $i->no ?>');
  // }else{
    
  // }

  // num<?= $i->id ?>.value = 0;
    var a<?= $i->id ?> = localStorage.getItem('qty<?=$i->no?>');
    
    if (localStorage.getItem('<?=$i->no?>')) {
      var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
      var cek =parseInt(localStorage.getItem('angka<?=$i->no?>'))+parseInt(a<?= $i->id ?>); console.log(cek);
      if (nambah<?= $i->id ?>) {
        nambah<?= $i->id ?>.addEventListener("click", ()=>{
         a<?= $i->id ?>++;
         if (localStorage.getItem('qty<?=$i->no?>')) {
         num<?= $i->id ?>.value = localStorage.getItem('qty<?=$i->no?>');
         qtycart<?= $i->id ?>.innerHTML = a<?= $i->id ?>+1;
         // tq.innerHTML = a<?= $i->id ?>+1;
         // tqh.innerHTML = a<?= $i->id ?>+1;
         }else{
          
              num<?= $i->id ?>.value = 1;
          qtycart<?= $i->id ?>.innerHTML = a<?= $i->id ?>;
          // tq.innerHTML = a<?= $i->id ?>;
          // tqh.innerHTML = a<?= $i->id ?>;
         
          
         }
        
        var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * 1;
        total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?> ;
        // localStorage.setItem('<?= $i->no ?>',a<?= $i->id?> + qa);
        localStorage.setItem('qty<?= $i->no ?>',1);
        localStorage.setItem('angka<?= $i->no ?>',a<?= $i->id?>);
         // localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
         // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
         // // console.log(hasil); 
        });
      }
    }else{
      if (nambah<?= $i->id ?>) {
        nambah<?= $i->id ?>.addEventListener("click", ()=>{
         a<?= $i->id ?>++;
         if (localStorage.getItem('<?=$i->no?>') && localStorage.getItem('qty<?=$i->no?>') && localStorage.getItem('angka<?=$i->no?>')) {
         num<?= $i->id ?>.value = localStorage.getItem('qty<?=$i->no?>');
         qtycart<?= $i->id ?>.innerHTML = localStorage.getItem('qty<?=$i->no?>');
         // tq.innerHTML = localStorage.getItem('qty<?=$i->no?>');
         // tqh.innerHTML = localStorage.getItem('qty<?=$i->no?>');
         }else{
          num<?= $i->id ?>.value = 1;
          qtycart<?= $i->id ?>.innerHTML = 1;
          // tq.innerHTML = 1;
          // tqh.innerHTML = 1;
         }
         var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; console.log(cek);
        var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * 1;
        total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
        localStorage.setItem('qty<?= $i->no ?>',1);
        localStorage.setItem('angka<?= $i->no ?>',a<?= $i->id?>);
         // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
         // // console.log(hasil); 
        });
      }
      
    }
    if (localStorage.getItem('<?=$i->no?>')) {
      var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
      var a<?= $i->id ?> = localStorage.getItem('qty<?=$i->no?>');
      var angka = parseInt(localStorage.getItem('angka<?=$i->no?>'));
      if (plus<?= $i->id ?>) {
      plus<?= $i->id ?>.addEventListener("click", ()=>{

        var inputValueA = num<?= $i->id ?>.value;
        var inputValueB = stock<?= $i->id?>.value;
        var inputValueC = tqi<?= $i->id?>.value;

        if (ns<?= $i->id ?>.value == 1) {
            if (inputValueA <= inputValueB -1 - inputValueC) {
               a<?= $i->id ?>++;
               
              var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; console.log(cek); //cek
              num<?= $i->id ?>.value = cek;
              var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
              total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
              qtycart<?= $i->id ?>.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
              // tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
              // tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
              // localStorage.setItem('<?= $i->no ?>',a<?= $i->id?> + qa);
               // localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
               // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
               // // console.log(hasil); 
               }
               if (inputValueA >= inputValueB - inputValueC ) {
                var isi = "Food Stocks Are Not Fulfilled";
                        Swal.fire({
                        title: 'Notification!',
                        customClass: {
                          container :'my-popup-class'
                        },
                        text: isi,
                        icon: 'warning',
                        confirmButtonColor: "#198754",
                        confirmButtonText: 'OK'
                        })
               }
        }else{
          a<?= $i->id ?>++;
               
              var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; console.log(cek); //cek
              num<?= $i->id ?>.value = cek;
              var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
              total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
              qtycart<?= $i->id ?>.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
              // tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
              // tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
              // localStorage.setItem('<?= $i->no ?>',a<?= $i->id?> + qa);
               // localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
               // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
               // // console.log(hasil); 
        }
        
  });
    }
    }else{
      var angka = localStorage.getItem('angka<?=$i->no?>');
      var a<?= $i->id ?> = localStorage.getItem('qty<?=$i->no?>');
      if (plus<?= $i->id ?>) {
        plus<?= $i->id ?>.addEventListener("click", ()=>{
          var inputValueA = num<?= $i->id ?>.value;
          var inputValueB = stock<?= $i->id?>.value;
          var inputValueC = tqi<?= $i->id?>.value;

        if (ns<?= $i->id ?>.value == 1) {
          if (inputValueA <= inputValueB -1 - inputValueC) {
           a<?= $i->id ?>++;
           num<?= $i->id ?>.value = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;
           var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; console.log(cek); //cek
          var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
          total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;

          qtycart<?= $i->id ?>.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
          // tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
          // tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;

           // localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
           // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
           // // console.log(hasil); 
         }
         if (inputValueA >= inputValueB - inputValueC ) {
            var isi = "Food Stocks Are Not Fulfilled";
                    Swal.fire({
                    title: 'Notification!',
                    customClass: {
                      container :'my-popup-class'
                    },
                    text: isi,
                    icon: 'warning',
                    confirmButtonColor: "#198754",
                    confirmButtonText: 'OK'
                    })
           }
        }else{
          a<?= $i->id ?>++;
           num<?= $i->id ?>.value = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;
           var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; console.log(cek); //cek
          var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
          total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;

          qtycart<?= $i->id ?>.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
          // tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
          // tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;

           // localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
           // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
           // // console.log(hasil); 
        }
        
  });
      }

  
}
if (localStorage.getItem('<?=$i->no?>')) {
      var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
      if (minus<?= $i->id ?>) {
  minus<?= $i->id ?>.addEventListener("click", ()=>{
   
   var inputValueA = num<?= $i->id ?>.value;
        // console.log(inputValue);
        if (inputValueA >= 1) {
          a<?= $i->id ?>--;
   num<?= $i->id ?>.value = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;
   var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; console.log(cek); //cek
   var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
   total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
   qtycart<?= $i->id ?>.innerHTML = a<?= $i->id ?>;
   // tq.innerHTML = a<?= $i->id ?>;
   // tqh.innerHTML = a<?= $i->id ?>;
   // localStorage.setItem('<?= $i->no ?>',qa+a<?= $i->id?>);
   // localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
   // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
   // // console.log(hasil);
   }  
  });
 }
}else{
  if (minus<?= $i->id ?>) {
    minus<?= $i->id ?>.addEventListener("click", ()=>{
   
   var inputValueA = num<?= $i->id ?>.value;
        // console.log(inputValue);
        if (inputValueA >= 1) {
          a<?= $i->id ?>--;
   num<?= $i->id ?>.value = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;
   var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; console.log(cek); //cek
   var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
   total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
   qtycart<?= $i->id ?>.innerHTML = cek;
   // tq.innerHTML = cek;
   // tqh.innerHTML = cek;
   // localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
   // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
   // // console.log(hasil);
   }  
  });
  }
  
}

</script>
  
<?php endforeach ?>

<?php foreach ($item as $i ): ?>
<script type="text/javascript">
  var jumlah<?=$i->id?> = document.querySelector('#jumlah<?=$i->id?>');
  var qtycart<?=$i->id?> = document.querySelector('#qtycart<?=$i->id?>');
  if (localStorage.getItem('<?=$i->no?>')) {
         // jumlah<?=$i->id?>.value = localStorage.getItem('<?=$i->no?>');
         // jumlah<?=$i->id?>.value = 0;
         // qtycart<?=$i->id?>.innerHTML = "Cart Qty "+localStorage.getItem('<?=$i->no?>');
         }else{
          // jumlah<?=$i->id?>.value = 0;
        }

  
</script>
<?php foreach ($item as $i): ?>
  <script type="text/javascript">
    var qc<?= $i->id ?> = document.querySelector(".qtycart<?= $i->id ?>");
    var qr<?= $i->id ?> = document.getElementById("qty<?= $i->id ?>");
    function tutuptab<?= $i->id ?>(){
    if (qr<?= $i->id ?>) {
      qr<?= $i->id ?>.value = 'Cart Qty '+0;
    }
      var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
    if (localStorage.getItem('<?=$i->no?>')) {
    qc<?= $i->id ?>.innerHTML = localStorage.getItem('<?=$i->no?>');
    // tq.innerHTML = localStorage.getItem('<?=$i->no?>');
    // tqh.innerHTML = localStorage.getItem('<?=$i->no?>')
    // localStorage.setItem('<?= $i->no ?>',qa - 1);
  }else{
     qc<?= $i->id ?>.innerHTML = 'Cart Qty '+0;
    // tq.innerHTML = 0;
    // tqh.innerHTML = 0;
  }
    
  }
  </script>
  
<?php endforeach ?>
<?php endforeach ?>
<?php foreach ($sub as $i ): ?>
<script type="text/javascript">
      $(document).ready(function(){
        
        var h = '<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/<?= str_replace(" ","%20", $subpertama->sub_category_so) ?>';
        $('#konten').load(h);
        item<?= str_replace(" ","_", $subpertama->sub_category_so) ?>.classList.add('active');
        var key = "<?= $key ?>";
        if (key != '') {
          $('#konten').load('<?= base_url() ?>index.php/ordermakanan/search/1');
        }
         if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/<?= str_replace(" ","%20", $i['sub_category_so']) ?>#<?= str_replace(" ","_", $i['sub_category_so']) ?>") {
          $('#konten').load('<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/<?= str_replace(" ","%20", $i['sub_category_so']) ?>#<?= str_replace(" ","_", $i['sub_category_so']) ?>');
         }else if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/rekomendasi#rekomendasi") {
          $('#konten').load('<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/rekomendasi#rekomendasi');
         }
          
        

        $('#<?= str_replace(" ","_", $i['sub_category_so']) ?>').click(function(){
    // mengambil data dari href
    var h = '<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/<?= str_replace(" ","%20", $i['sub_category_so']) ?>';

    // Load content into the specified element
    $('#konten').load(h);

    item<?= str_replace(" ","_", $subpertama->sub_category_so) ?>.classList.remove('active');
        $('.sub').removeClass('active');
          item<?= str_replace(" ","_", $i['sub_category_so']) ?>.classList.add('active');

    console.log(h);
});

        $('#rekomendasi').click(function(){
          // mengambil data dari href
          var h = '<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/rekomendasi';
          // alert(h);
          $('#konten').load(h);
          // konten akan diisi oleh menu yang dipilih sesuai dengan isi dari href yang dipilih
          
        });
        $('#all').click(function(){
          // mengambil data dari href
          var h = '<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/all';
          // alert(h);
          $('#konten').load(h);
          // konten akan diisi oleh menu yang dipilih sesuai dengan isi dari href yang dipilih
          
        });
      });
    </script>
<?php endforeach ?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    var search = document.getElementById("search");
    var konten = document.getElementById("konten");
    var kon = document.getElementById("kon");

    search.addEventListener("input", function() {
      if (search.value) {
        kon.style.display = "block";
        var h = '<?= base_url() ?>index.php/ordermakanan/search/'+search.value;
        konten.style.display = "none";
        $('#kon').load(h);
      } else {
        kon.style.display = "none";
        konten.style.display = "block";
        h = '<?= base_url() ?>index.php/ordermakanan/menumakanancake/cake/<?= $subpertama->sub_category_so ?>';
        $('#konten').load(h);
      }
      
    });
  });
</script>



  <?php $this->load->view('template/footer') ?>
  