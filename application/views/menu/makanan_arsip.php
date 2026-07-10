
<div class="container" style="margin-top: 185px;">
  <div class="row row-cols-2">
    <?php foreach($item as $i){ ?>
<div class="card-group" style="margin-top: 25px;">
  <div class="card">
    <div class="image text-center">
     <?php if ( $i->image_path != "" ): ?>
        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><img src="<?= $i->image_path ?>" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;" /></a>
      <?php  else: ?>
        <img src="<?= base_url();?>/assets/picture.png" alt="Red dot" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>" style="width: 160px;height: 120px;border-radius: 20px;" />
      <?php endif ?>
    </div>
    <div class="card-body" >
      <h5 class="card-title text_<?= str_replace(" ","_", $i->description)?> text"><?= $i->description ?></h5>
      <?php if ($i->harga_weekday == 0): ?>
        <p>Free</p>
      <?php else: ?>
        <p class="text">Rp <?= number_format($i->harga_weekday) ?></p>
      <?php endif ?>
    </div>
    <div class="container text-center">
  <div class="row">
    <div class="col" >
      <button type="button" class="btn btn-success kurang<?= $i->id ?>" style="padding-left: 9px;padding-right: 9px;" id="kurang<?= $i->id ?>" onclick="OrderQty('minus','<?= $i->id ?>','<?= $i->no ?>');"> - </button>
    </div>
    <div class="col">

      <input type="text" name="jumlah<?= $i->id ?>" id="jumlah<?= $i->id ?>"  class="form-control" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:43px;text-align: center; " readonly>
    </div>
    <div class="col">
      <button type="button" class="btn btn-success tambah<?= $i->id ?>" style="padding-left: 7px;padding-right: 7px;" id="tambah<?= $i->id ?>" onclick="OrderQty('plus','<?= $i->id ?>','<?= $i->no ?>');">+</button>
    </div>
  </div>
  </div>
</div>
</div>


      <input type="hidden" name="nama<?= $i->id ?>" id="nama<?= $i->id ?>" value="<?= $i->description ?>" class="form-control nama">
      <input type="hidden" name="harga<?= $i->id ?>" id="harga<?= $i->id ?>" value="<?= $i->harga_weekday ?>" class="form-control harga">
      <input type="hidden" name="no<?= $i->id ?>" id="no<?= $i->id ?>" value="<?= $i->no ?>" class="form-control harga">
   
<?php } ?>

  </div>
</div>   
<br>
<br>  
<br>

<footer>
<div class="container text-center">
<!-- <button type="submit" class="btn btn-outline-success" style="padding-top: 20px;padding-bottom: 20px;padding-left: 50px;padding-right: 50px;">
  Order 
</button> -->
<a href="<?php echo base_url() ?>Cart/home/<?= $nomeja ?>/Makanan/Chicken" class="btn btn-outline-success" style="padding-top: 20px;padding-bottom: 20px;padding-left: 40px;padding-right: 40px;">Lihat<i class="fa fa-cart-plus"></i> <b id="total_qty" align="right"><?= $total_qty;?></b></a>
<a href="<?php echo base_url('') ?>selforder/home/<?= $nomeja ?>" class="btn btn-outline-danger" style="padding-top: 20px;padding-bottom: 20px;padding-left: 40px;padding-right: 40px;">Kembali</a>
<?php foreach ($item as $i): ?>
<div class="modal fade" id="exampleModal<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #223c77;color: white;">
        <h5 class="modal-title" style="text-align: center;margin-left: 42%;" id="exampleModalLabel">Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if ( $i->image_path != "" ): ?>
        <img src="<?= $i->image_path ?>" alt="Red dot"  class="img-fluid" style="width: 300px;height: 180px;border-radius: 20px; display: block;margin-left: auto;margin-right: auto;" />
        <?php  else: ?>
          <img src="<?= base_url();?>/assets/picture.png" alt="Red dot" class="img-fluid" style="width: 300px;height: 180px;border-radius: 20px; display: block;margin-left: auto;margin-right: auto;" />
      <?php endif ?>
    <form action="<?=base_url()?>Ordermakanan/add_cart" method="post">
      <div class="card mb-3">
        
        <div class="card-body">

          <h5 style="color: #223c77;float: left;"><?= $i->description ?></h5>
          <h6 style="color: #223c77;margin-top: 2px;float: left;"><?= $i->product_info ?></h6>
          <?php if ($i->harga_weekday == 0): ?>
        <h6 style="color: #223c77;margin-top: 2px;float: right;">Free</h6>
        <input type="hidden" name="" value="0" id="harga<?= $i->id ?>">
      <?php else: ?>
        <h6 style="color: #223c77;margin-top: 2px;float: right;">Rp <?= number_format($i->harga_weekday) ?></h6>
        <input type="hidden" name="" value="<?= $i->harga_weekday ?>" id="harga<?= $i->id ?>">
      <?php endif ?>
      <input type="hidden" name="nama<?= $i->id ?>" id="nama<?= $i->id ?>" value="<?= $i->description ?>" class="form-control nama">
      <input type="hidden" name="harga<?= $i->id ?>" id="harga<?= $i->id ?>" value="<?= $i->harga_weekday ?>" class="form-control harga">
      <input type="hidden" name="no<?= $i->id ?>" id="no<?= $i->id ?>" value="<?= $i->no ?>" class="form-control harga">
      <input type="hidden" name="id" id="id<?= $i->id ?>" value="<?= $i->id ?>" class="form-control harga">
        </div>
      </div>

      <div class="card" style="margin-bottom: 10px;">
        <ul class="list-group list-group-flush">
          <?php $options = $this->Item_model->option($i->no); ?>
          <?php if ($i->with_option == 1): ?>
            <?php foreach ($options as $o ):?>
              <li class="list-group-item"><h7 style="float: left;"><?= $o->description ?></h7><input class="form-check-input" type="radio" name="pesan<?= $i->id ?>" value="<?= $o->description ?>" id="flexRadioDefault1" style="float: right"></li>
            <?php endforeach ?>
          <?php endif ?>
        </ul>
      </div>
      
      
      <div class="container text-center" style="display: flex;justify-content: center;">
      <div class="row row-cols-auto">
        <div class="col"><button type="button" class="btn btn-success minus<?= $i->id ?>" style="padding-left: 15px;padding-right: 15px;"> - </button></div>
        <div class="col"><input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"   class="form-control num<?= $i->id ?>" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:43px;text-align: center; "  readonly></div>
        
        <div class="col"><button type="button" class="btn btn-success plus<?= $i->id ?>" style="padding-left: 15px;padding-right: 15px;">+</button></div>
      </div>
    </div>
    <button type="submit" class="btn btn-success" style="padding-left: 45px;padding-right: 45px;">Tambah Ke Keranjang - <a id="total<?= $i->id ?>"></a></button>
      </div>
      <div class="modal-footer">
       
      </div>
</div>
      </div>
    </div>
  </div>
</div>
</form>
<?php endforeach ?>


<?php foreach ($item as $i ): ?>
<script type="text/javascript">
  var plus<?= $i->id ?> = document.querySelector(".plus<?= $i->id ?>"),
  minus<?= $i->id ?> = document.querySelector(".minus<?= $i->id ?>"),
  num<?= $i->id ?> = document.querySelector(".num<?= $i->id ?>"),
  harga<?= $i->id ?> = document.querySelector("#harga<?= $i->id ?>");
  total<?= $i->id ?> = document.querySelector("#total<?= $i->id ?>");

  // if (localStorage.getItem('<?= $i->no ?>') != null) {
  //   // num<?= $i->id ?>.value = localStorage.getItem('<?= $i->no ?>');
  //   // total<?= $i->id ?>.innerHTML = localStorage.getItem('H<?= $i->no ?>');
  //   // var a<?= $i->id ?> =localStorage.getItem('<?= $i->no ?>');
  // }else{
    
  // }
  
  num<?= $i->id ?>.value = 0;
    var a<?= $i->id ?> = 0;

  plus<?= $i->id ?>.addEventListener("click", ()=>{
   a<?= $i->id ?>++;
   num<?= $i->id ?>.value = a<?= $i->id ?>;
  var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * a<?= $i->id ?>;
  total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
   localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
   localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
   // console.log(hasil); 
  });
  minus<?= $i->id ?>.addEventListener("click", ()=>{
   
   var inputValueA = num<?= $i->id ?>.value;
        // console.log(inputValue);
        if (inputValueA >= 1) {
          a<?= $i->id ?>--;
   num<?= $i->id ?>.value = a<?= $i->id ?>;
   var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * a<?= $i->id ?>;
   total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
   localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
   localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
   // console.log(hasil);
   }  
  });

</script>
  
<?php endforeach ?>
<?php foreach ($item as $i ): ?>
<script type="text/javascript">
  var jumlah<?=$i->id?> = document.querySelector('#jumlah<?=$i->id?>');
  if (localStorage.getItem('<?=$i->no?>')) {
         jumlah<?=$i->id?>.value = localStorage.getItem('<?=$i->no?>');
         }else{
          jumlah<?=$i->id?>.value = 0;
        }

  
</script>
<?php endforeach ?>
