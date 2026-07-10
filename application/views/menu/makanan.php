<style type="text/css">
  #loading{
    width: 50px;
    height: 50px;
    border:solid 5px #ccc;
    border-top-color: #223c77;
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
<form action="#" method="post"> 
<div class="container text-center" style="margin-top: 185px;">
  <div class="row row-cols-2">
    <?php foreach($item as $i){ ?>
      <?php if ($i->is_sold_out == 0): ?>
        <div class="container text-center" style="position:relative;">
          <div>
          <div class="row">
            <div class="col" style="margin-top:10px;">
               
              <?php if ( $i->image_path != "" ): ?>
                <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="font-size: 12px;float: right;background-color: red;border-radius: 20px;padding: 3px;color: white;">Cart Qty 0</h5>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>" style="color: #223c77;font-size: 13px;text-decoration: none;"><img src="<?= $i->image_path ?>" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;" /><br> <h5 style="font-size: 12px;">Customize</h5></a>
                <?php else: ?>

                  <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="font-size: 12px;float: right;background-color: red;border-radius: 20px;padding: 3px;color: white;">Cart Qty 0</h5>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><img src="<?= $i->image_path ?>" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;" /></a>
                <?php endif ?>
              <?php  else: ?>
                <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="font-size: 12px;float: right;background-color: red;border-radius: 20px;padding: 3px;color: white;">Cart Qty 0</h5>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><img src="<?= base_url();?>assets/logo.png" alt="Red dot" style="width: 160px;height: 100px;border-radius: 20px;" /></a>
              <?php endif ?>
              <h6 style="color: #223c77;font-size: 16px;"class="text_<?= str_replace(" ","_", $i->description)?> text"><?= $i->description ?></h6>
              
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
              
            </div>
            <div class="col">
              <?php $cekpesan = $this->Item_model->cekpesan($i->no); ?>
              <?php foreach ($cekpesan as $c): ?>
                <h6 style="color: #223c77;font-size: 13px;">Ordered Qty : <?= $c->qty ?></h6>
              <?php endforeach ?>
              
              <!-- <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                <a href="" style="text-decoration:none;" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><input type="text" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Customizable" style="border:1px solid #223c77;padding-left: 20%;" disabled></a>
              <?php else: ?>
                <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #223c77;">
              <?php endif ?> -->
            <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #223c77;">
             
              <div class="container text-center">
          <div class="row" style="margin-top: 5px;">
            <div class="col" >
              <button type="button" class="btn btn-success kurang<?= $i->id ?>" style="padding-left: 9px;padding-right: 9px;" id="kurang<?= $i->id ?>" onclick="OrderQty('minus','<?= $i->id ?>','<?= $i->no ?>','<?= $i->stock ?>','<?= $i->need_stock ?>','<?= $this->session->userdata('user_order_id') ?>');"> - </button>
            </div>
            <div class="col">
                <!-- <?php if ($i->id_customer != $ic): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="0"  class="form-control" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:35px; " readonly>
                <?php  elseif ($i->id_customer == NULL): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="NULL"  class="form-control" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:35px; " readonly>
                <?php else: ?> 
                 <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="<?= $i->qty ?>"  class="form-control" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:35px; " readonly>
                <?php endif ?> -->
                <input type="text" name="qty<?= $i->id ?>" id="jumlah<?= $i->id ?>" value="0"  class="form-control" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:43px;text-align: center; " readonly>
              
            </div>

            <div class="col">
              <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                <button type="button" class="btn btn-success nambah<?= $i->id ?>" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>" style="padding-left: 7px;padding-right: 7px;">+</button>
              <?php else: ?>
                <button type="button" class="btn btn-success tambah<?= $i->id ?>" style="padding-left: 7px;padding-right: 7px;" id="tambah<?= $i->id ?>" onclick="OrderQty('plus','<?= $i->id ?>','<?= $i->no ?>','<?= $i->stock ?>','<?= $i->need_stock ?>','<?= $this->session->userdata('user_order_id') ?>');">+</button>
              <?php endif ?>
              </div>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="container text-center sold" style="position:relative;">
          <div>
          <div class="row">
            <div class="col" style="margin-top:10px;">
               
              <?php if ( $i->image_path != "" ): ?>
                <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                
          <a href="#"><img src="<?= $i->image_path ?>" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;opacity: 075;" /><div class="soldtext">SOLD OUT</div></a>
                <?php else: ?>

                  
                  <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><img src="<?= $i->image_path ?>" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;opacity: 075;" /><div class="soldtext">SOLD OUT</div></a>
                <?php endif ?>
              <?php  else: ?>
                
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><img src="<?= base_url();?>assets/logo.png" alt="Red dot" style="width: 160px;height: 100px;border-radius: 20px;" /><div class="soldtext">SOLD OUT</div></a>
              <?php endif ?>
              <h6 style="color: #223c77;font-size: 16px;"class="text_<?= str_replace(" ","_", $i->description)?> text"><?= $i->description ?></h6>
              
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
              
            </div>
            <div class="col">
              <?php $cekpesan = $this->Item_model->cekpesan($i->no); ?>
              <?php foreach ($cekpesan as $c): ?>
                <h6 style="color: #223c77;font-size: 13px;">Ordered Qty : <?= $c->qty ?></h6>
              <?php endforeach ?>
              
              <!-- <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                <a href="" style="text-decoration:none;" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><input type="text" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Customizable" style="border:1px solid #223c77;padding-left: 20%;" disabled></a>
              <?php else: ?>
                <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #223c77;">
              <?php endif ?> -->
            <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #223c77;">
             
              <div class="container text-center">
          <div class="row" style="margin-top: 5px;">
            <div class="col" >
              <button type="button" class="btn btn-success" style="padding-left: 9px;padding-right: 9px;opacity:0.7"> - </button>
            </div>
            <div class="col">
                <!-- <?php if ($i->id_customer != $ic): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="0"  class="form-control" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:35px; " readonly>
                <?php  elseif ($i->id_customer == NULL): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="NULL"  class="form-control" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:35px; " readonly>
                <?php else: ?> 
                 <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="<?= $i->qty ?>"  class="form-control" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:35px; " readonly>
                <?php endif ?> -->
                <input type="text" value="0"  class="form-control" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:43px;text-align: center;opacity:0.7 " disabled>
              
            </div>

            <div class="col">
              <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                <button type="button" class="btn btn-success" style="padding-left: 7px;padding-right: 7px;opacity:0.7">+</button>
              <?php else: ?>
                <button type="button" class="btn btn-success" style="padding-left: 7px;padding-right: 7px;opacity:0.7">+</button>
              <?php endif ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif ?>

      <div class="container text-center">
  <div class="row">
    <div class="col">
      
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
      <input type="hidden" name="no<?= $i->id ?>" id="no<?= $i->id ?>" value="<?= $i->no ?>" class="form-control harga">
      <input type="hidden" name="need_stock<?= $i->id ?>" id="need_stock<?= $i->id ?>" value="<?= $i->need_stock ?>" class="form-control harga">
      
    </div>
  </div>
</div>
<div class="container text-center">
  <!-- <div class="row">
    <div class="col-7" style="color: #223c77;">TakeAway?</div>
    <div class="col-5"><input type="checkbox" value="Take Away" onclick="getClick<?= $i->id ?>()" class="ta<?= $i->id ?>"></div>
  </div> -->
</div>
<div class="container text-center" id="tk<?= $i->id ?>" hidden >
  <div class="row">
    <div class="col" >
      <input type="hidden" name="cek[]" class="cek<?= $i->id ?>" id="cek<?= $i->id ?>">
      <button type="button" class="btn btn-success mi<?= $i->id ?>" style="padding-left: 10px;padding-right: 10px;"> - </button>
    </div>
    <div class="col">
      <input type="text" name="qta[]" id="qta" value="0"  class="form-control nu<?= $i->id ?>" id="cek<?= $i->id ?>" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:35px;" readonly disabled="disabled">
    </div>
    <div class="col">
      <button type="button" class="btn btn-success pl<?= $i->id ?>" id="pls<?= $i->id ?>" style="padding-left: 10px;padding-right: 10px;">+</button>
    </div>
  </div>
</div>
      
    </div>
  </div>
</div>

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
<a href="<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Makanan/<?= $s ?>#<?= $s ?>" class="btn btn-outline-success" style="padding-top: 20px;padding-bottom: 20px;padding-left: 40px;padding-right: 40px;">Cart<i class="fa fa-cart-plus"></i> <b id="total_qty" align="right"><?= $total_qty;?></b></a>
<a href="<?php echo base_url('') ?>index.php/selforder/home/<?= $nomeja ?>" class="btn btn-outline-danger" style="padding-top: 20px;padding-bottom: 20px;padding-left: 40px;padding-right: 40px;">Back</a>
</form>
<?php foreach ($item as $i): ?>
  <?php if ($i->with_option) : ?>
  <div class="md modal fade" id="exampleModal<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <?php else: ?>
  <div class="mdl modal fade" id="exampleModal<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<?php endif ?>

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #223c77;color: white;">
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
          <?php if ($i->with_option == 1): ?>
            <select name="pesan<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: #223c77;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>

          <?php elseif($i->with_option == 2): ?>
            <select name="pesan<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: #223c77;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>
            <div style="margin-top:5px;"></div>  
            <select name="pesan<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: #223c77;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options2 as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>
            </select>
          <?php endif ?>
        </ul>
      </div>
      
      
      <div class="container text-center" style="display: flex;justify-content: center;">
      <div class="row row-cols-auto">
        <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
        <div class="col"><button type="button" class="btn btn-success minus<?= $i->id ?>" style="padding-left: 15px;padding-right: 15px;"> - </button></div>
        <div class="col"><input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"   class="form-control num<?= $i->id ?>" value="0" style="border:1px solid #223c77;margin-bottom: 5px;color: #223c77; width:43px;text-align: center; "  readonly></div>
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
                  confirmButtonColor: "#223c77",
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
            confirmButtonColor: "#223c77",
            confirmButtonText: 'OK'
            })
      }

    }
  </script>
<?php endforeach ?>
<script type="text/javascript">
  var loading = document.getElementById('loading');
  var Salad = document.getElementById('Salad');
  var Soup = document.getElementById('Soup');
  var Meat = document.getElementById('Meat');
  // var Grill = document.getElementById('Grill');
  var Poultry = document.getElementById('Poultry');
  var Noodle = document.getElementById('Noodle');
  var Rice = document.getElementById('Rice');
  var Pasta = document.getElementById('Pasta');
  var Croissant = document.getElementById('Croissant');
  var Light_Bites = document.getElementById('Light_Bites');
  var Dessert = document.getElementById('Dessert');
  var Cakes = document.getElementById('Cakes');
  var all = document.getElementById('all');
  var rekomendasi = document.getElementById('rekomendasi');

  $(document).ready(function(){
    load = document.querySelector('#load');
    load.classList.add('load');
  });

  setTimeout(berhenti,2000);


  function berhenti() {
    loading.style.display = "none";
   $("#loading").fadeOut();
   $("#load").fadeOut();
  }
  
  WHOLE_CAKE.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  SOUP.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  SLICE.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  // Grill.addEventListener("click", ()=>{
  //   loading.style.display = "flex";
  //  $("#loading").fadeIn();
  //  $(".load").fadeIn();
  // });
  SANDWICH_and_BURGER.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  SALAD.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  PIZZA.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
    PERSONAL.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
    PASTA.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  MAIN_COURSE.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  LITE_BITES.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  GRILL.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  CROISSANT.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  BREAKFAST.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  ASIAN.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  APPETIZER.addEventListener("click", ()=>{
    loading.style.display = "flex";
   $("#loading").fadeIn();
   $(".load").fadeIn();
  });
  // all.addEventListener("click", ()=>{
  //   loading.style.display = "flex";
  //  $("#loading").fadeIn();

  // });
  // rekomendasi.addEventListener("click", ()=>{
  //   loading.style.display = "flex";
  //  $("#loading").fadeIn();
  //  $(".load").fadeIn();
  // });
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
      var h = '<?= base_url() ?>ordermakanan/menumakanan/Makanan/Salad#Salad';
          // alert(h);
          $('#konten').load(h);
    }
    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Rice#Rice") {
          var h = '<?= base_url() ?>ordermakanan/menumakanan/Makanan/Rice#Rice';
              // alert(h);
              $('#konten').load(h);
        }
    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Package#Package") {
      var h = '<?= base_url() ?>ordermakanan/menumakanan/Makanan/Package#Package';
          // alert(h);
          $('#konten').load(h);
    }
    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Western#Western") {
      var h = '<?= base_url() ?>index.php/ordermakanan/menumakanan/Makanan/Western#Western';
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
    setTimeout(function() {
            if (window.location.toString() == "<?= base_url() ?>index.php/index.php/ordermakanan/menu/Makanan/Salad#Salad") {
      var h = '<?= base_url() ?>index.php/ordermakanan/menumakanan/Makanan/Salad#Salad';
          // alert(h);
          $('#konten').load(h);
    }
    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Rice#Rice") {
          var h = '<?= base_url() ?>index.php/ordermakanan/menumakanan/Makanan/Rice#Rice';
              // alert(h);
              $('#konten').load(h);
        }
    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Package#Package") {
      var h = '<?= base_url() ?>index.php/ordermakanan/menumakanan/Makanan/Package#Package';
          // alert(h);
          $('#konten').load(h);
    }
    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Western#Western") {
      var h = '<?= base_url() ?>index.php/ordermakanan/menumakanan/Makanan/Western#Western';
          // alert(h);
          $('#konten').load(h);
    }
        }, 500);
    
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
  var tqh = document.querySelector("#total_qty_header");

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
         tq.innerHTML = a<?= $i->id ?>+1;
         tqh.innerHTML = a<?= $i->id ?>+1;
         }else{
          
              num<?= $i->id ?>.value = 1;
          qtycart<?= $i->id ?>.innerHTML = a<?= $i->id ?>;
          tq.innerHTML = a<?= $i->id ?>;
          tqh.innerHTML = a<?= $i->id ?>;
         
          
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
         tq.innerHTML = localStorage.getItem('qty<?=$i->no?>');
         tqh.innerHTML = localStorage.getItem('qty<?=$i->no?>');
         }else{
          num<?= $i->id ?>.value = 1;
          qtycart<?= $i->id ?>.innerHTML = 1;
          tq.innerHTML = 1;
          tqh.innerHTML = 1;
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
              tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
              tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
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
                        confirmButtonColor: "#223c77",
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
              tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
              tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
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
          tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
          tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;

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
                    confirmButtonColor: "#223c77",
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
          tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
          tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;

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
   tq.innerHTML = a<?= $i->id ?>;
   tqh.innerHTML = a<?= $i->id ?>;
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
   tq.innerHTML = cek;
   tqh.innerHTML = cek;
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
         jumlah<?=$i->id?>.value = 0;
         qtycart<?=$i->id?>.innerHTML = "Cart Qty "+localStorage.getItem('<?=$i->no?>');
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
      qr<?= $i->id ?>.value = 0;
    }
      var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
    if (localStorage.getItem('<?=$i->no?>')) {
    qc<?= $i->id ?>.innerHTML = localStorage.getItem('<?=$i->no?>');
    tq.innerHTML = localStorage.getItem('<?=$i->no?>');
    tqh.innerHTML = localStorage.getItem('<?=$i->no?>')
    // localStorage.setItem('<?= $i->no ?>',qa - 1);
  }else{
    qc<?= $i->id ?>.innerHTML = 0;
    tq.innerHTML = 0;
    tqh.innerHTML = 0;
  }
    
  }
  </script>
  
<?php endforeach ?>
<?php endforeach ?>

  <script type="text/javascript">
  
  $(document).ready(function(){
    setInterval(function(){
      $.ajax({
      type:'POST',
      url: '<?= base_url().'index.php/ordermakanan/jmlcart' ?>',
      dataType:'json',
      success:function(data){
          $("#total_qty").html(data.total);
          $("#total_qty_header").html(data.total);
      }
    });
    },3000);
    
  });
</script>