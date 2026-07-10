<div class="row">
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
                <?php if ($i->with_option == 1 || $i->with_option == 2 || $i->with_option == 3): ?>
                <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="font-size: 12px;float: right;background-color: red;border-radius: 20px;padding: 3px;color: white;">Cart Qty 0</h5>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>" style="color: #198754;font-size: 13px;text-decoration: none;"><img src="<?= $i->image_path ?>" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;" /><br> <h5 style="font-size: 12px;">Customize</h5></a>
                <?php else: ?>

                  <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="font-size: 12px;float: right;background-color: red;border-radius: 20px;padding: 3px;color: white;">Cart Qty 0</h5>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><img src="<?= $i->image_path ?>" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;" /></a>
                <?php endif ?>
              <?php  else: ?>
                <?php if ($i->with_option == 1 || $i->with_option == 2 || $i->with_option == 3): ?>
                <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="font-size: 12px;float: right;background-color: red;border-radius: 20px;padding: 3px;color: white;">Cart Qty 0</h5>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>" style="color: #198754;font-size: 13px;text-decoration: none;"><img src="<?= base_url();?>assets/logo.png" alt="Red dot" style="width: 160px;height: 120px;border-radius: 20px;" /><br> <h5 style="font-size: 12px;">Customize</h5></a>
                <?php else: ?>
                    <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="font-size: 12px;float: right;background-color: red;border-radius: 20px;padding: 3px;color: white;">Cart Qty 0</h5>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>" style="color: #198754;font-size: 13px;text-decoration: none;"><img src="<?= base_url();?>assets/logo.png" alt="Red dot" style="width: 160px;height: 120px;border-radius: 20px;" /><br> <h5 style="font-size: 12px;"></h5></a>
                <?php endif; ?>
              <?php endif ?>
              <h6 style="color: #198754;font-size: 16px;"class="text_<?= str_replace(" ","_", $i->description)?> text"><?= $i->description ?></h6>
              
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
                <h6 style="color: #198754;font-size: 13px;">Ordered Qty : <?= $c->qty ?></h6>
              <?php endforeach ?>
              
              <!-- <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                <a href="" style="text-decoration:none;" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><input type="text" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Customizable" style="border:1px solid #198754;padding-left: 20%;" disabled></a>
              <?php else: ?>
                <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #198754;">
              <?php endif ?> -->
            <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #198754;">
            <input type="hidden" name="pesandua<?= $i->id ?>" id="pesandua<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #198754;">
            <input type="hidden" name="pesantiga<?= $i->id ?>" id="pesantiga<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #198754;">
             
              <div class="container text-center">
          <div class="row" style="margin-top: 5px;">
            <div class="col" >
              <button type="button" class="btn btn-success kurang<?= $i->id ?>" style="padding-left: 9px;padding-right: 9px;" id="kurang<?= $i->id ?>" onclick="OrderQty('minus','<?= $i->id ?>','<?= $i->no ?>','<?= $i->stock ?>','<?= $i->need_stock ?>','<?= $this->session->userdata('user_order_id') ?>');"> - </button>
            </div>
            <div class="col">
                <!-- <?php if ($i->id_customer != $ic): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="0"  class="form-control" style="border:1px solid #198754;margin-bottom: 5px;color: #198754; width:35px; " readonly>
                <?php  elseif ($i->id_customer == NULL): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="NULL"  class="form-control" style="border:1px solid #198754;margin-bottom: 5px;color: #198754; width:35px; " readonly>
                <?php else: ?> 
                 <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="<?= $i->qty ?>"  class="form-control" style="border:1px solid #198754;margin-bottom: 5px;color: #198754; width:35px; " readonly>
                <?php endif ?> -->
                <input type="text" name="qty<?= $i->id ?>" id="jumlah<?= $i->id ?>" value="0"  class="form-control" style="border:1px solid #198754;margin-bottom: 5px;color: #198754; width:43px;text-align: center; " readonly>
              
            </div>

            <div class="col">
              <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2 || $i->with_option == 3): ?>
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
                <?php if ($i->with_option == 1 || $i->with_option == 2 || $i->with_option == 3): ?>
                
          <a href="#"><img src="<?= $i->image_path ?>" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;opacity: 075;" /><div class="soldtext">SOLD OUT</div></a>
                <?php else: ?>

                  
                  <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><img src="<?= $i->image_path ?>" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;opacity: 075;" /><div class="soldtext">SOLD OUT</div></a>
                <?php endif ?>
              <?php  else: ?>
                
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><img src="<?= base_url();?>assets/logo.png" alt="Red dot" style="width: 160px;height: 120px;border-radius: 20px;" /><div class="soldtext">SOLD OUT</div></a>
              <?php endif ?>
              <h6 style="color: #198754;font-size: 16px;"class="text_<?= str_replace(" ","_", $i->description)?> text"><?= $i->description ?></h6>
              
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
                <h6 style="color: #198754;font-size: 13px;">Ordered Qty : <?= $c->qty ?></h6>
              <?php endforeach ?>
              
              <!-- <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                <a href="" style="text-decoration:none;" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><input type="text" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Customizable" style="border:1px solid #198754;padding-left: 20%;" disabled></a>
              <?php else: ?>
                <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #198754;">
              <?php endif ?> -->
            <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #198754;">
            <input type="hidden" name="pesandua<?= $i->id ?>" id="pesandua<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #198754;">
            <input type="hidden" name="pesantiga<?= $i->id ?>" id="pesantiga<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid #198754;">
             
              <div class="container text-center">
          <div class="row" style="margin-top: 5px;">
            <div class="col" >
              <button type="button" class="btn btn-success" style="padding-left: 9px;padding-right: 9px;opacity:0.7"> - </button>
            </div>
            <div class="col">
                <!-- <?php if ($i->id_customer != $ic): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="0"  class="form-control" style="border:1px solid #198754;margin-bottom: 5px;color: #198754; width:35px; " readonly>
                <?php  elseif ($i->id_customer == NULL): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="NULL"  class="form-control" style="border:1px solid #198754;margin-bottom: 5px;color: #198754; width:35px; " readonly>
                <?php else: ?> 
                 <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="<?= $i->qty ?>"  class="form-control" style="border:1px solid #198754;margin-bottom: 5px;color: #198754; width:35px; " readonly>
                <?php endif ?> -->
                <input type="text" value="0"  class="form-control" style="border:1px solid #198754;margin-bottom: 5px;color: #198754; width:43px;text-align: center;opacity:0.7 " disabled>
              
            </div>

            <div class="col">
              <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2 || $i->with_option == 3): ?>
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
    <div class="col-7" style="color: #198754;">TakeAway?</div>
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
      <input type="text" name="qta[]" id="qta" value="0"  class="form-control nu<?= $i->id ?>" id="cek<?= $i->id ?>" style="border:1px solid #198754;margin-bottom: 5px;color: #198754; width:35px;" readonly disabled="disabled">
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
</footer>
</div>