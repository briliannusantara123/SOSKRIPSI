<style type="text/css">
  .my-popup-class{
    z-index: 10000001 !important;
  }
  .modal-dialog-scrollable {
          max-height: calc(100vh - 20px); /* Sesuaikan dengan kebutuhan */
        }
      }
      ::-webkit-scrollbar {
        width: 1px; /* Lebar scrollbar */
      }
      ::-webkit-scrollbar {
    height: 1px; /* Tinggi scrollbar */
}

      ::-webkit-scrollbar-thumb {
        background-color: <?= $cn->color ?>; /* Warna thumb scrollbar */
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
<form action="#" method="post"> 
<div class="container text-center" style="margin-top: 125px;">
  <div class="row row-cols-2">
    <?php 
    $countitem = COUNT($item);
    foreach($item as $i){ ?>
      <input type="hidden" id="max_qty_item<?= $i->id ?>" value="<?= $i->max_qty ?>">
      <input type="hidden" id="sum_max_qty<?= $i->id ?>">
      <?php if ($i->is_sold_out == 0): ?>
        <div class="container text-center" style="position:relative;">
          <div>
          <div class="row">
            <div class="col" style="margin-top:10px;">
               
              <?php if ( $i->image_path != "" ): ?>
                <?php if ($i->with_option == 1 || $i->with_option == 2 || $i->with_option == 3): ?>
                <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="font-size: 12px;float: right;background-color: red;border-radius: 20px;padding: 3px;color: white;">Cart Qty 0</h5>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalview<?= $i->id ?>" style="color: <?= $cn->color ?>;font-size: 13px;text-decoration: none;"><img src="<?= $i->image_path ?>" loading="lazy" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;" /><br> <h5 style="font-size: 12px;">Customize</h5></a>
                <?php else: ?>

                  <div class="container">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalview<?= $i->id ?>" style="position: relative; display: inline-block;">
                      <img src="<?= $i->image_path ?>" loading="lazy" alt="Red dot" style="width: 120px; height: 120px; border-radius: 20px;" />
                      <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="position: absolute; top: 10px; right: 10px; font-size: 12px; background-color: red; border-radius: 20px; padding: 3px; color: white;">Cart Qty 0</h5>
                    </a>
                  </div>
                <?php endif ?>
              <?php  else: ?>
                <?php if ($i->with_option == 1 || $i->with_option == 2 || $i->with_option == 3): ?>
                <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="font-size: 12px;float: right;background-color: red;border-radius: 20px;padding: 3px;color: white;">Cart Qty 0</h5>
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalview<?= $i->id ?>" style="color: <?= $cn->color ?>;font-size: 13px;text-decoration: none;"><img src="<?= $logo->image_path ?>" loading="lazy" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;" /><br> <h5 style="font-size: 12px;">Customize</h5></a>
                <?php else: ?>
                    <div class="container">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalview<?= $i->id ?>" style="position: relative; display: inline-block;">
                      <img src="<?= $logo->image_path ?>" loading="lazy" alt="Red dot" style="width: 120px; height: 120px; border-radius: 20px;" />
                      <h5 id="qtycart<?= $i->id ?>" class="qtycart<?= $i->id ?>" style="position: absolute; top: 10px; right: 10px; font-size: 12px; background-color: red; border-radius: 20px; padding: 3px; color: white;">Cart Qty 0</h5>
                    </a>
                  </div>
                <?php endif; ?>
              <?php endif ?>
              <h6 style="color: <?= $cn->color ?>;font-size: 16px;"class="text_<?= str_replace(" ","_", $i->description)?> text"><?= $i->description ?></h6>
              
              <!-- <?php if ($i->harga_weekday == 0): ?>
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
                
              <?php endif ?> -->
              
            </div>
            <div class="col">
              <!-- <?php $cekpesan = $this->Item_model->cekpesan($i->no); ?>
              <?php foreach ($cekpesan as $c): ?>
                <h6 style="color: <?= $cn->color ?>;font-size: 13px;">Ordered Qty : <?= $c->qty ?></h6>
              <?php endforeach ?> -->
              
              <!-- <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                <a href="" style="text-decoration:none;" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><input type="text" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Customizable" style="border:1px solid <?= $cn->color ?>;padding-left: 20%;" disabled></a>
              <?php else: ?>
                <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid <?= $cn->color ?>;">
              <?php endif ?> -->
            <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid <?= $cn->color ?>;">


             
              <div class="">
          <div class="row" style="margin-top: 5px;">
            <div class="col" >
              <?php if ($countitem > 1): ?>
                <button type="button" class="btn kurang " style="padding-left: 9px;padding-right: 9px;background-color: <?= $cn->color ?>;color: white" id="kurang<?= $i->id ?>" onclick="OrderQty('minus','<?= $i->id ?>','<?= $i->no ?>','<?= $i->stock ?>','<?= $i->need_stock ?>','<?= $this->session->userdata('user_order_id') ?>','paket');"> - </button>
              <?php endif ?>
            </div>
            <div class="col">
                <!-- <?php if ($i->id_customer != $ic): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="0"  class="form-control" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:35px; " readonly>
                <?php  elseif ($i->id_customer == NULL): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="NULL"  class="form-control" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:35px; " readonly>
                <?php else: ?> 
                 <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="<?= $i->qty ?>"  class="form-control" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:35px; " readonly>
                <?php endif ?> -->
                <?php if ($countitem > 1): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="jumlah<?= $i->id ?>" value="0"  class="cekjumlah" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:30px;height:37px;border-radius: 10px;text-align: center; " readonly>
                <?php else: ?>
                  <input type="text" name="qty<?= $i->id ?>" id="jumlahsatuitem<?= $i->id ?>" value="0"  class="cekjumlah" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:30px;height:37px;border-radius: 10px;text-align: center; " readonly>
                <?php endif ?>
              
            </div>

            <div class="col">
              <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($countitem > 1): ?>
                <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                  <button type="button" class="btn nambah<?= $i->id ?>"  style="padding-left: 7px;padding-right: 7px;background-color: <?= $cn->color ?>;color: white;">+</button>
                <?php else: ?>
                  <button type="button" class="btn tambah" style="padding-left: 7px;padding-right: 7px;background-color: <?= $cn->color ?>;color: white" id="tambah<?= $i->id ?>" onclick="OrderQty('plus','<?= $i->id ?>','<?= $i->no ?>','<?= $i->stock ?>','<?= $i->need_stock ?>','<?= $this->session->userdata('user_order_id') ?>','paket');">+</button>
                <?php endif ?>
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
                
          <a href="#"><img src="<?= $i->image_path ?>" loading="lazy" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;opacity: 075;" /><div class="soldtext">SOLD OUT</div></a>
                <?php else: ?>

                  
                  <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><img src="<?= $i->image_path ?>" loading="lazy" alt="Red dot" style="width: 120px;height: 120px;border-radius: 20px;opacity: 075;" /><div class="soldtext">SOLD OUT</div></a>
                <?php endif ?>
              <?php  else: ?>
                
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><img src="<?= base_url();?>assets/logo.png" loading="lazy" alt="Red dot" style="width: 160px;height: 100px;border-radius: 20px;" /><div class="soldtext">SOLD OUT</div></a>
              <?php endif ?>
              <h6 style="color: <?= $cn->color ?>;font-size: 16px;"class="text_<?= str_replace(" ","_", $i->description)?> text"><?= $i->description ?></h6>
              
              <!-- <?php if ($i->harga_weekday == 0): ?>
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
                
              <?php endif ?> -->
              
            </div>
            <div class="col">
              <!-- <?php $cekpesan = $this->Item_model->cekpesan($i->no); ?>
              <?php foreach ($cekpesan as $c): ?>
                <h6 style="color: <?= $cn->color ?>;font-size: 13px;">Ordered Qty : <?= $c->qty ?></h6>
              <?php endforeach ?> -->
              
              <!-- <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                <a href="" style="text-decoration:none;" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $i->id ?>"><input type="text" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Customizable" style="border:1px solid <?= $cn->color ?>;padding-left: 20%;" disabled></a>
              <?php else: ?>
                <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid <?= $cn->color ?>;">
              <?php endif ?> -->
            <input type="hidden" name="pesan<?= $i->id ?>" id="pesan<?= $i->id ?>" class="form-control cari" placeholder="Masukan Pesan" style="border:1px solid <?= $cn->color ?>;">
             
              <div class="container text-center">
          <div class="row" style="margin-top: 5px;">
            <div class="col" >
              <button type="button" class="btn btn-success" style="padding-left: 9px;padding-right: 9px;opacity:0.7;background-color: <?= $cn->color ?>;color: white"> - </button>
            </div>
            <div class="col">
                <!-- <?php if ($i->id_customer != $ic): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="0"  class="form-control" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:35px; " readonly>
                <?php  elseif ($i->id_customer == NULL): ?>
                  <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="NULL"  class="form-control" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:35px; " readonly>
                <?php else: ?> 
                 <input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"  value="<?= $i->qty ?>"  class="form-control" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:35px; " readonly>
                <?php endif ?> -->
                <input type="text" value="0"  class="text-center" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:22px;height:37px;border-radius: 7px;text-align: center; " disabled>
              
            </div>

            <div class="col">
              <?php $options = $this->Item_model->option($i->no); ?>
              <?php if ($i->with_option == 1 || $i->with_option == 2): ?>
                <button type="button" class="btn btn-success" style="padding-left: 7px;padding-right: 7px;opacity:0.7;background-color: <?= $cn->color ?>;color: white">+</button>
              <?php else: ?>
                <button type="button" class="btn btn-success" style="padding-left: 7px;padding-right: 7px;opacity:0.7;background-color: <?= $cn->color ?>;color: white">+</button>
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
    <div class="col-7" style="color: <?= $cn->color ?>;">TakeAway?</div>
    <div class="col-5"><input type="checkbox" value="Take Away" onclick="getClick<?= $i->id ?>()" class="ta<?= $i->id ?>"></div>
  </div> -->
</div>
<div class="container text-center" id="tk<?= $i->id ?>" hidden >
  <div class="row">
    <div class="col" >
      <input type="hidden" name="cek[]" class="cek<?= $i->id ?>" id="cek<?= $i->id ?>">
      <button type="button" class="btn btn-success mi<?= $i->id ?>" style="padding-left: 10px;padding-right: 10px;background-color: <?= $cn->color ?>;color: white"> - </button>
    </div>
    <div class="col">
      <input type="text" name="qta[]" id="qta" value="0"  class="form-control nu<?= $i->id ?>" id="cek<?= $i->id ?>" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:35px;" readonly disabled="disabled">
    </div>
    <div class="col">
      <button type="button" class="btn btn-success pl<?= $i->id ?>" id="pls<?= $i->id ?>" style="padding-left: 10px;padding-right: 10px;background-color: <?= $cn->color ?>;color: white">+</button>
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
<!-- <a href="<?php echo base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Makanan/<?= $s ?>#<?= $s ?>" class="btn btn-outline-success" style="padding-top: 20px;padding-bottom: 20px;padding-left: 40px;padding-right: 40px;">Cart<i class="fa fa-cart-plus"></i> <b id="total_qty_header" align="right"><?= $total_qty;?></b></a>
<a href="<?php echo base_url('') ?>index.php/selforder/home/<?= $nomeja ?>" class="btn btn-outline-danger" style="padding-top: 20px;padding-bottom: 20px;padding-left: 40px;padding-right: 40px;">Back</a> -->
</form>
<?php foreach ($item as $i): ?>
  <?php if ($i->with_option) : ?>
  <div class="md modal fade" id="exampleModal<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <?php else: ?>
  <div class="mdl modal fade" id="exampleModal<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<?php endif ?>

  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background-color: <?= $cn->color ?>;color: white;">
        <h5 class="modal-title" style="text-align: center;margin-left: 42%;" id="exampleModalLabel">Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="tutuptab<?= $i->id ?>()" id="close"></button>
      </div>
      <div class="modal-body">
        <?php if ( $i->image_path != "" ): ?>
        <img src="<?= $i->image_path ?>" loading="lazy" alt="Red dot" class="img-fluid" />
        <?php  else: ?>
          <img src="<?= $logo->image_path ?>" loading="lazy" id="imgmodal" alt="Red dot" class="img-fluid" />
      <?php endif ?>
    <form action="#" method="post" id="input<?= $i->id ?>">
      <div class="card mb-3">
        
        <div class="card-body">

          <h5 id="description"><?= $i->description ?></h5><br>  
          <!-- <h6 id="product_info"><?= $i->product_info ?></h6> -->
          <!-- <?php if ($i->harga_weekday == 0): ?>
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
                
              <?php endif ?> -->
          
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
      <input type="hidden" name="paket_codes" id="paket_code_modal<?= $i->id ?>" class="form-control">
      <input type="hidden" name="paket_code" id="paket_code<?= $i->id ?>" class="form-control">
      <input type="hidden" name="sub<?= $i->id ?>" value="<?= $i->sub_category ?>" class="form-control">
      <input type="hidden" name="id" id="id<?= $i->id ?>" value="<?= $i->id ?>" class="form-control">
      <input type="hidden" name="sub_category" id="sub_category<?= $i->id ?>" value="<?= $i->sub_category ?>" class="form-control">
        </div>
      </div>

      <div class="card" style="margin-bottom: 10px;">
        <ul class="list-group list-group-flush">
          <?php $options = $this->Item_model->option($i->no); ?>
          <?php $options2 = $this->Item_model->option2($i->no); ?>
          <?php if ($i->with_option == 1): ?>
            <select name="pesan<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: <?= $cn->color ?>;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>

          <?php elseif($i->with_option == 2): ?>
            <select name="pesan<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: <?= $cn->color ?>;">
              <option value="" selected disabled>Select Option Customize</option>
            <?php foreach ($options as $o ):?>
                <option value="<?= $o->description ?>"><?= $o->description ?></option>
            <?php endforeach ?>
            </select>
            <div style="margin-top:5px;"></div>  
            <select name="pesan<?= $i->id ?>" id="" class="form-control" style="text-align:center;color: <?= $cn->color ?>;">
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
        <div class="col"><button type="button" class="btn btn-success minus<?= $i->id ?>" style="padding-left: 15px;padding-right: 15px;background-color: <?= $cn->color ?>;color: white"> - </button></div>
        <div class="col"><input type="text" name="qty<?= $i->id ?>" id="qty<?= $i->id ?>"   class="form-control num<?= $i->id ?>" value="0" style="border:1px solid <?= $cn->color ?>;margin-bottom: 5px;color: <?= $cn->color ?>; width:43px;text-align: center; "  readonly></div>
        
        <input type="hidden" value="<?= $i->stock?>" id="stock<?= $i->id ?>">
        <input type="hidden" value="<?= $i->need_stock?>" id="ns<?= $i->id ?>">
        <input type="hidden" value="<?= $total_qty ?>" id="tqi<?= $i->id ?>">
        
        <div class="col"><button type="button" class="btn btn-success plus<?= $i->id ?>" style="padding-left: 15px;padding-right: 15px;background-color: <?= $cn->color ?>;color: white">+</button></div>

      </div>
    </div>
    <!-- <button type="button" class="btn btn-success" style="padding-left: 45px;padding-right: 45px;" data-bs-dismiss="modal" aria-label="Close">Tambah Ke Keranjang <a id="total<?= $i->id ?>"></a></button> -->
    <button id="simpan<?= $i->id ?>" type="button" class="btn btn-success" data-bs-dismiss="modal" aria-label="Close"  onclick="simpanalert('<?= $i->need_stock ?>')" style="background-color: <?= $cn->color ?>;color: white">Add to Cart <!-- <a id="total<?= $i->id ?>"></a> --></button>
    <?php endif ?>
      </div>
      
</div>
      </div>
    </div>
  </div>
</div>
</form>

<?php endforeach ?>

<?php foreach ($item as $i): ?>
  <?php if ($i->with_option) : ?>
  <div class="md modal fade" id="exampleModalview<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <?php else: ?>
  <div class="mdl modal fade" id="exampleModalview<?= $i->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<?php endif ?>

  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background-color: <?= $cn->color ?>;color: white;">
        <h5 class="modal-title" style="text-align: center;margin-left: 42%;" id="exampleModalLabel">Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="tutuptab<?= $i->id ?>()" id="close"></button>
      </div>
      <div class="modal-body">
        <?php if ( $i->image_path != "" ): ?>
        <img src="<?= $i->image_path ?>" loading="lazy" alt="Red dot" class="img-fluid" />
        <?php  else: ?>
          <img src="<?= $logo->image_path ?>" loading="lazy" id="imgmodal" alt="Red dot" class="img-fluid" />
      <?php endif ?>
    <form action="#" method="post" id="input<?= $i->id ?>">
      <div class="card mb-3">
        
        <div class="card-body">

          <h5 id="description"><?= $i->description ?></h5><br>  
          <!-- <h6 id="product_info"><?= $i->product_info ?></h6> -->
          <!-- <?php if ($i->harga_weekday == 0): ?>
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
                
              <?php endif ?> -->
          
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
      <input type="hidden" name="paket_codes" id="paket_code_modal" class="form-control">
      <input type="hidden" name="paket_code" id="paket_code" class="form-control">
      <input type="hidden" name="sub<?= $i->id ?>" value="<?= $i->sub_category ?>" class="form-control">
      <input type="hidden" name="id" id="id<?= $i->id ?>" value="<?= $i->id ?>" class="form-control">
        </div>
      </div>

      
      
</div>
      </div>
    </div>
  </div>
</div>
</form>

<?php endforeach ?>
<script type="text/javascript">
  // var kurangButton = document.getElementById('kurangpaket');
  // var tambahButton = document.getElementById('tambahpaket');
  // var jumlahInput = document.getElementById('jumlahpaket');

  // tambahButton.addEventListener('click', function () {
  //     var currentValue = parseInt(jumlahInput.value) || 0;
  //     var pc = document.getElementById('paket_name').value;

  //     // Cek apakah paket sudah dipilih
  //     if (pc == 0) {
  //         Swal.fire({
  //             title: 'Notification!',
  //             customClass: { container: 'my-popup-class' },
  //             text: "Please select a package first.",
  //             icon: 'warning',
  //             confirmButtonColor: "<?= $cn->color ?>",
  //             confirmButtonText: 'OK'
  //         });
  //     } else {
  //         // Tambah jumlah paket
  //         jumlahInput.value = currentValue + 1;
  //         localStorage.setItem('qtypaket', currentValue + 1);
          
  //         var code_item = "<?= $code_item ?>";
  //         var ids = code_item.split(',');

  //         // Loop untuk setiap item ID
  //         ids.forEach(function (itemId) {
  //             var max_qty_item = document.getElementById('max_qty_item' + itemId);
  //             var sum_max_qty = document.getElementById('sum_max_qty' + itemId);

  //             // Pastikan elemen ada sebelum mengaksesnya
  //             if (max_qty_item && sum_max_qty) {
  //                 var maxQty = parseInt(max_qty_item.value) || 0;

  //                 // Hitung total berdasarkan jumlah paket baru
  //                 var jumlahkan = (currentValue + 1) * maxQty;
  //                 sum_max_qty.value = jumlahkan;
  //             } else {
  //                 console.warn('Element not found for itemId:', itemId);
  //             }
  //         });
  //     }
  // });

  // kurangButton.addEventListener('click', function () {
  //     var currentValue = parseInt(jumlahInput.value) || 0;
  //     var qtyadd = document.getElementById('qtyadd');
  //     var cek = document.querySelector('#cek');

  //     if (!cek || !qtyadd) {
  //         console.warn("Element 'cek' or 'qtyadd' not found!");
  //         return;
  //     }

  //     console.log(qtyadd.value);

  //     if (parseInt(cek.value) >= currentValue) {
  //         Swal.fire({
  //             title: 'Notification!',
  //             customClass: { container: 'my-popup-class' },
  //             text: "To reduce the package quantity, remove all items by pressing the minus button on each one.",
  //             icon: 'warning',
  //             confirmButtonColor: "<?= $cn->color ?>",
  //             confirmButtonText: 'OK'
  //         });
  //     } else {
  //         if (currentValue > 0) {
  //             jumlahInput.value = currentValue - 1;
  //             localStorage.setItem('qtypaket', currentValue - 1);

  //             // Panggil hitungJumlahkan setelah jumlah berubah
  //             hitungJumlahkan("<?= $code_item ?>");
  //         }
  //     }
  // });

// Fungsi untuk menghitung ulang jumlah maksimal per item
  function hitungJumlahkan() {
      var jumlahInput = document.getElementById('jumlahpaket');
      var max_qty_sub = document.getElementById('max_qty_sub');
      var code_item = '<?= $code_item ?>';
      var ids = code_item.split(',');

      ids.forEach(function (itemId) {
          var max_qty_item = document.getElementById('max_qty_item' + itemId);
          var sum_max_qty = document.getElementById('sum_max_qty' + itemId);
          var jumlahsatuitem = document.getElementById('jumlahsatuitem' + itemId);

          if (max_qty_item && sum_max_qty) {
              var maxQty = parseInt(max_qty_item.value) || 0;
              var jumlahkan = parseInt(jumlahInput.value) * maxQty;
              sum_max_qty.value = jumlahkan;
              max_qty_sub.value = jumlahkan;
              if (jumlahsatuitem) {
                jumlahsatuitem.value = jumlahkan;
              }
          }
      });
  }


  
</script>
<script type="text/javascript">
  function OrderQty(tipe,id,no,stock,need_stock,uoi,cek) {

    var itemCode = $('#no' + id).val();
    var desc = $('#nama' + id).val();
    var price = $('#harga' + id).val();
    var notes = $('#pesan' + id).val();
    var notesdua = $('#pesandua' + id).val();
    var notestiga = $('#pesantiga' + id).val();
    var qtyadd = document.getElementById('qtyadd');
    var qtylimit = document.getElementById('max_qty_sub');
    var qtypaket = document.getElementById('jumlahpaket');
    var paket = $('#paket').val();
    var hargapaket = $('#harga_paket').val();
    var item_code = $('#item_code').val();
    var subklik = $('#subklik').val();
    var subactive = $('#subactive').val();
    
       if (tipe == 'plus') {
        if (qtylimit.value == 0) {
          var isi = "Please add the package quantity first.";
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
        }else if (qtyadd.value == qtylimit.value) {
             var isi = "Please select max "+qtylimit.value;
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
          $('#tambah' + id).prop('disabled', false);
          $.ajax({
          type:'POST',
          data: {tipe: tipe,id: id,itemcode: itemCode,description: desc,unit_price: price,extra_notes: notes,no: no,stock: stock,need_stock: need_stock,uoi: uoi,notesdua:notesdua,notestiga:notestiga,paket:paket,hargapaket:hargapaket,cek:cek,item_code:item_code,subklik:subklik,qtypaket:qtypaket.value},
          url: '<?= base_url().'index.php/orderpaket/orderqty' ?>',
          dataType:'json',})
          .done(function (hasil){
            if (!hasil.cek) {
                var qtyadd = document.getElementById('qtyadd');
                var qtylimit = document.getElementById('max_qty_sub');
                var subklik = document.getElementById('subklik');
                var subactive = document.getElementById('subactive');
                var item_code = document.getElementById('item_code');
                var cek = document.querySelector('#cek');
                var q = qtyadd.value;
                qtyadd.value= parseInt(q)+1;
                if (localStorage.getItem('cekqty')) {
                  var gc = parseInt(localStorage.getItem('cekqty'));
                  cek.value = parseInt(gc)+1;
                }else{
                  cek.value = parseInt(q)+1;
                }
                localStorage.setItem('cekqty',cek.value);
                  setTimeout(buka,700);
                  // console.log(cek.value);
                  //DEVELOP
                
                localStorage.setItem(item_code.value,qtyadd.value);
                // localStorage.setItem(subklik.value,qtyadd.value);
                localStorage.setItem('limit'+subactive.value,qtylimit.value);
                localStorage.setItem(subactive.value,qtyadd.value);
                localStorage.setItem(no,hasil.new_qty); 
            }
            
            
            if(hasil.status == true){
              $('#jumlah' + id).val(hasil.new_qty);
              $('#qtycart' + id).text("Cart Qty "+localStorage.getItem(no));
              $('#pesan' + id).val(hasil.pesan);
              $('#pesandua' + id).val(hasil.pesandua);
              $('#pesantiga' + id).val(hasil.pesantiga);
              $('#cart_count').text(hasil.cart_count);
              $('#total_qty').text(hasil.total_qty);
              $('#total_qty_header').text(hasil.total_qty);
              if (hasil.cek == true) {
                var isi = hasil.notif;
                Swal.fire({
                title: 'Notification!',
                text: isi,
                icon: 'warning',
                confirmButtonColor: "<?= $cn->color ?>",
                confirmButtonText: 'OK'
                })
              }

            }
          });
        }
        $('#kurang' + id).prop('disabled', false);
        $('#tambah' + id).prop('disabled', true);
        
       }else{
        $('.tambah').prop('disabled', false);
            $.ajax({
          type:'POST',
          data: {tipe: tipe,id: id,itemcode: itemCode,description: desc,unit_price: price,extra_notes: notes,no: no,stock: stock,need_stock: need_stock,uoi: uoi,notesdua:notesdua,notestiga:notestiga,paket:paket,hargapaket:hargapaket,cek:cek,item_code:item_code},
          url: '<?= base_url().'index.php/orderpaket/orderqty' ?>',
          dataType:'json',})
          .done(function (hasil){
            
            
            if(hasil.status == true){
              $('#jumlah' + id).val(hasil.new_qty);
              $('#qtycart' + id).text("Cart Qty "+localStorage.getItem(no));
              $('#pesan' + id).val(hasil.pesan);
              $('#pesandua' + id).val(hasil.pesandua);
              $('#pesantiga' + id).val(hasil.pesantiga);
              $('#cart_count').text(hasil.cart_count);
              $('#total_qty').text(hasil.total_qty);
              $('#total_qty_header').text(hasil.total_qty);
              if (hasil.cek == true) {
                var isi = hasil.notif;
                Swal.fire({
                title: 'Notification!',
                text: isi,
                icon: 'warning',
                confirmButtonColor: "<?= $cn->color ?>",
                confirmButtonText: 'OK'
                })
              }

            }
          });
          $('#kurang' + id).prop('disabled', false);
        $('#tambah' + id).prop('disabled', true);
       }
     
    
  }
</script>
<?php foreach($sub as $i): ?>
<script type="text/javascript">
  var<?= str_replace(" ","_", $i['sub_category']) ?>= document.getElementById('<?= str_replace(" ","_", $i['sub_category']) ?>');
  var all = document.getElementById('all');
  var rekomendasi = document.getElementById('rekomendasi');
  var item<?= str_replace(" ","_", $i['sub_category']) ?> = document.querySelector('.test<?= str_replace(" ","_", $i['sub_category']) ?>');
  var itemall = document.querySelector('.testall');
  var itemrekomendasi = document.querySelector('.testrekomendasi');

    if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/<?= str_replace(" ","%20", $i['sub_category']) ?>#<?= str_replace(" ","_", $i['sub_category']) ?>") {
        $(document).ready(function() {
        item<?= str_replace(" ","_", $i['sub_category']) ?>.classList.add('active');
    });
    }
</script>
<?php endforeach; ?>

<?php foreach ($item as $i ): ?>
  <script type="text/javascript">
    var qtylimit = document.getElementById('qtylimit');
    var sum_max_qty<?= $i->id ?> = document.getElementById('sum_max_qty<?= $i->id ?>');
    var max_qty_item<?= $i->id ?> = document.getElementById('max_qty_item<?= $i->id ?>');

    var nilaiAwal = qtylimit.value;  
//     function hitungJumlahkan(id) {
    
//     var ids = id.split(',');

//     ids.forEach(function(itemId) {
        
//         var qtylimit = document.getElementById('qtylimit'); // Misal idnya 'qtylimit_6'
//         var max_qty_item = document.getElementById('max_qty_item' + itemId); // Misal idnya 'max_qty_item_6'
//         var sum_max_qty = document.getElementById('sum_max_qty' + itemId); // Misal idnya 'sum_max_qty_6'

//         var jumlahkan = parseInt(qtylimit.value);
//         sum_max_qty.value = 'testing';

//     });
// }



// Panggil fungsi hitungJumlahkan setiap kali ada input
if (qtylimit) {
    qtylimit.addEventListener('input', function() {
        hitungJumlahkan();  // Panggil fungsi untuk menghitung
             // Panggil fungsi untuk mengecek perubahan
    });
}





    var qtylimit = document.getElementById('jumlahpaket');
    var cekjumlah = document.querySelector('.cekjumlah');
    if (document.querySelector('.nambah<?= $i->id ?>')) {
      document.querySelector('.nambah<?= $i->id ?>').addEventListener('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal<?= $i->id ?>'));
            var jumlah<?=$i->id?> = document.querySelector('#jumlah<?=$i->id?>');
            if (qtylimit.value == 0) {
              var isi = "Please add the package quantity first";
              Swal.fire({
              title: 'Notification!',
              customClass: {
                container :'my-popup-class'
              },
              text: isi,
              icon: 'warning',
              confirmButtonColor: "<?= $cn->color ?>",
              confirmButtonText: 'OK'
              });
            }else if (cekjumlah.value == qtylimit.value) {
              var isi = "Please select max "+qtylimit.value;
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
              myModal.show();
            }
            if (localStorage.getItem('paket_code')) {
                document.getElementById('paket_code_modal<?= $i->id ?>').value = localStorage.getItem('paket_code');
                document.getElementById('paket_code<?= $i->id ?>').value = localStorage.getItem('item_code');
            } else {
                document.getElementById('paket_code_modal<?= $i->id ?>').value = 0;
            }
        });
    }
    
      $('#simpan<?= $i->id ?>').on('click',function(){
          var input<?= $i->id ?> = $('#input<?= $i->id ?>').serialize();
          var qtya<?= $i->id ?> = document.getElementById("qty<?= $i->id ?>");
          var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
          var tqi<?= $i->id ?> = document.querySelector("#tqi<?= $i->id ?>");
          var stock<?= $i->id ?> = document.querySelector("#stock<?= $i->id ?>");
          var ns<?= $i->id ?> = document.querySelector("#ns<?= $i->id ?>");
          var sub_category<?= $i->id ?> = document.querySelector("#sub_category<?= $i->id ?>");
          

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
                  confirmButtonColor: "<?= $cn->color ?>",
                  confirmButtonText: 'OK'
                  })

                }else{
                  $.ajax({
                  url : '<?=base_url()?>index.php/orderpaket/add_cart',
                  type : 'POST',
                  data: input<?= $i->id ?>,
                  dataType : 'JSON',
                  success : function(data){
                    var qty<?= $i->id ?> = localStorage.getItem('<?=$i->no?>')+qtya<?= $i->id ?>.value;
                    localStorage.setItem(sub_category<?= $i->id ?>.value,qty<?= $i->id ?> + qa);
                    localStorage.setItem('<?= $i->no ?>',qty<?= $i->id ?> + qa);

                   
                  }
                })
                }
            }else{
              $.ajax({
                  url : '<?=base_url()?>index.php/orderpaket/add_cart',
                  type : 'POST',
                  data: input<?= $i->id ?>,
                  dataType : 'JSON',
                  success : function(data){
                    var qty<?= $i->id ?> = localStorage.getItem('<?=$i->no?>')+qtya<?= $i->id ?>.value;
                    
                    localStorage.setItem('<?= $i->no ?>',qty<?= $i->id ?> + qa);
                    
                  }
                })
            }
          var dataArray<?= $i->id ?> = input<?= $i->id ?>.split("&");
          var qtyValue<?= $i->id ?>;
          for (var i = 0; i < dataArray<?= $i->id ?>.length; i++) {
              var pair<?= $i->id ?> = dataArray<?= $i->id ?>[i].split("=");
              if (pair<?= $i->id ?>[0] === "qty"+<?= $i->id ?>) {
                  qtyValue<?= $i->id ?> = pair<?= $i->id ?>[1];
                  break; // Keluar dari loop setelah menemukan nilai qty862
              }
          }

          // Mencetak nilai qty862
          var jumlah<?=$i->id?> = document.querySelector('#jumlah<?=$i->id?>');
          var qtycart<?= $i->id ?> = document.querySelector("#qtycart<?= $i->id ?>");
          qtycart<?= $i->id ?>.innerHTML = "Cart Qty "+ qtyValue<?= $i->id ?>;
          jumlah<?=$i->id?>.value = qtyValue<?= $i->id ?>;
          
      })
  function simpanalert(need_stock) {
      if (need_stock == 0) {
        Swal.fire({
            title: 'Success!',
            text: 'Menu Added to Cart',
            icon: 'success',
            confirmButtonColor: "<?= $cn->color ?>",
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
    var qtyadd = document.getElementById('qtyadd');
    var qtylimit = document.getElementById('max_qty_sub');
    var subklik = document.getElementById('subklik');
    var subactive = document.getElementById('subactive');
    var item_code = document.getElementById('item_code');
    $('.tambah').click(function() {
      var q = qtyadd.value;
      if (qtylimit.value == 0) {
          var isi = "Please add the package quantity first";
          Swal.fire({
          title: 'Notification!',
          customClass: {
            container :'my-popup-class'
          },
          text: isi,
          icon: 'warning',
          confirmButtonColor: "<?= $cn->color ?>",
          confirmButtonText: 'OK'
          });
          $('.tambah').prop('disabled', false);
          $('.kurang').prop('disabled', false);
          localStorage.setItem(subactive.value,qtylimit.value);
      }else if(qtyadd.value == qtylimit.value){
           var isi = "Please select max "+qtylimit.value;
          Swal.fire({
          title: 'Notification!',
          customClass: {
            container :'my-popup-class'
          },
          text: isi,
          icon: 'warning',
          confirmButtonColor: "<?= $cn->color ?>",
          confirmButtonText: 'OK'
          });
          $('.tambah').prop('disabled', false);
          $('.kurang').prop('disabled', false);
          localStorage.setItem(subactive.value,qtylimit.value);
      }else{
        // var cek = document.querySelector('#cek');
        //   qtyadd.value= parseInt(q)+1;
        //   if (localStorage.getItem('cekqty')) {
        //     var gc = parseInt(localStorage.getItem('cekqty'));
        //     cek.value = parseInt(gc)+1;
        //   }else{
        //     cek.value = parseInt(q)+1;
        //   }
        //   localStorage.setItem('cekqty',cek.value);
        //     setTimeout(buka,700);
        //     console.log(cek.value);
        //     //DEVELOP
          
        //   localStorage.setItem(item_code.value,qtyadd.value);
      }
    });
    $('.kurang').click(function() {
      var q = qtyadd.value;
      var cek = document.querySelector('#cek');
      var currentValue = parseInt(cek.value);
          if (localStorage.getItem('cekqty')) {
            var gc = parseInt(localStorage.getItem('cekqty'));
            if (currentValue > 0) {
                cek.value = parseInt(gc)-1;
            }
            
          }else{
            if (currentValue > 0) {
                cek.value = parseInt(q)-1;
            }
          }
          localStorage.setItem('cekqty',cek.value);
      
      qtyadd.value= parseInt(q)-1;
      if (qtyadd.value >= 0) {
        localStorage.setItem(subactive.value,qtyadd.value);
        localStorage.setItem(item_code.value,qtyadd.value);
        setTimeout(buka,700);  
      }else{
        $('.kurang').prop('disabled', false);
        qtyadd.value = 0;
      }
      
    });
  });

  setTimeout(berhenti,2000);


  function berhenti() {
    var jumlahInput = document.getElementById('jumlahpaket');
      var max_qty_sub = document.getElementById('max_qty_sub');
      var code_item = '<?= $code_item ?>';
      var items = code_item.split(','); // Memisahkan string menjadi array
      var hitungitem = items.length; // Menghitung jumlah item
      var max_qty_item = document.getElementById('max_qty_item' + code_item);
      var sum_max_qty = document.getElementById('sum_max_qty' + code_item);
      var jumlahsatuitem = document.getElementById('jumlahsatuitem' + code_item);
      var hargapaket = $('#harga_paket').val();
      var paket = $('#paket').val();

      if (max_qty_item && sum_max_qty) {
          var maxQty = parseInt(max_qty_item.value) || 0;
          var jumlahkan = parseInt(jumlahInput.value) * maxQty;
          jumlahsatuitem.value = jumlahkan;
      }
      if (hitungitem === 1) {
          var data = {
              code: code_item,
              qty: jumlahkan,
              qtypaket : parseInt(jumlahInput.value),
              hargapaket : hargapaket,
              paket : paket
          };

          // Kirim data ke backend CI dengan AJAX jQuery
          $.ajax({
              url: '<?= base_url("index.php/Orderpaket/simpanPaket") ?>',
              type: 'POST',
              data: JSON.stringify(data),  // Pastikan data sudah sesuai dengan format yang dibutuhkan
              contentType: 'application/json',  // Menyatakan bahwa data yang dikirim adalah dalam format JSON
              dataType: 'json',  // Menyatakan bahwa response yang diharapkan dalam format JSON
              success: function(response) {
                  // Cek jika response berhasil
                  if (response.status === "success") {
                      localStorage.setItem(response.subcategory, response.qty);  // Menyimpan qty ke localStorage
                      // console.log('Data berhasil disimpan:', response.message);
                      // alert('Data berhasil disimpan!');
                  }
              },
              error: function(xhr, status, error) {
                  // console.error('Terjadi kesalahan:', error);
                  // alert('Gagal menyimpan data. Silakan coba lagi!');
              }
          });


      }
    loading.style.display = "none";
   $("#loading").fadeOut();
   $("#load").fadeOut();
  }
  function buka() {
    loading.style.display = "none";
      $('.kurang').prop('disabled', false);
      $('.tambah').prop('disabled', false);
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
  var sub_category<?= $i->id ?> = document.querySelector("#sub_category<?= $i->id ?>");
  if (simpan<?= $i->id ?>) {
  simpan<?= $i->id ?>.addEventListener("click", ()=>{
    if (qtya<?= $i->id ?>.value >= stock<?= $i->id ?>.value - tqi<?= $i->id ?>.value +1) {
      }else{
        location.reload();
      }
    
    var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
    var qtypop<?= $i->id ?> = document.getElementById('qty<?= $i->id ?>');
    localStorage.setItem('<?= $i->no ?>', qa+parseInt(qtypop<?= $i->id ?>.value));
    localStorage.setItem(sub_category<?= $i->id ?>.value, qa+parseInt(qtypop<?= $i->id ?>.value));
    // console.log(window.location.toString());
    setTimeout(function() {
            if (window.location.toString() == "<?= base_url() ?>index.php/ordermakanan/menu/Makanan/Salad#Salad") {
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
}else{
  var simpan<?= $i->id ?> = document.getElementById('simpan<?= $i->id ?>');
  var qtya<?= $i->id ?> = document.getElementById("qty<?= $i->id ?>");
  var tqi<?= $i->id ?> = document.querySelector("#tqi<?= $i->id ?>");
  var stock<?= $i->id ?> = document.querySelector("#stock<?= $i->id ?>");
  var ns<?= $i->id ?> = document.querySelector("#ns<?= $i->id ?>");
  var sub_category<?= $i->id ?> = document.querySelector("#sub_category<?= $i->id ?>");

  if (simpan<?= $i->id ?>) {
    simpan<?= $i->id ?>.addEventListener("click", ()=>{
    if (qtya<?= $i->id ?>.value >= stock<?= $i->id ?>.value - tqi<?= $i->id ?>.value +1) {
      }else{
        location.reload();
      }
    
    var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
    var qp<?= $i->id ?> = document.getElementById('qty<?= $i->id ?>');
    localStorage.setItem('<?= $i->no ?>', qp<?= $i->id ?>.value);
    localStorage.setItem(sub_category<?= $i->id ?>.value, qp<?= $i->id ?>.value);
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
  // var total<?= $i->id ?> = document.querySelector("#total<?= $i->id ?>");
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
      var cek =parseInt(localStorage.getItem('angka<?=$i->no?>'))+parseInt(a<?= $i->id ?>); 
      // console.log(cek);
      if (nambah<?= $i->id ?>) {
        nambah<?= $i->id ?>.addEventListener("click", ()=>{
         a<?= $i->id ?>++;
         if (localStorage.getItem('qty<?=$i->no?>')) {
         num<?= $i->id ?>.value = localStorage.getItem('qty<?=$i->no?>');
         }else{
          
              num<?= $i->id ?>.value = 1;
         
          
         }
        
        var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * 1;
        // total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?> ;
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
         qtycart<?= $i->id ?>.innerHTML = "Cart Qty "+ localStorage.getItem('qty<?=$i->no?>');
         tq.innerHTML = localStorage.getItem('qty<?=$i->no?>');
         tqh.innerHTML = localStorage.getItem('qty<?=$i->no?>');
         }else{
          num<?= $i->id ?>.value = 1;
          qtycart<?= $i->id ?>.innerHTML = "Cart Qty "+ 1;
          tq.innerHTML = 1;
          tqh.innerHTML = 1;
         }
         var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; 
         // console.log(cek);
        var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * 1;
        // total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
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
      var qtylimit = document.getElementById('jumlahpaket');
      plus<?= $i->id ?>.addEventListener("click", ()=>{

        var inputValueA = num<?= $i->id ?>.value;
        var inputValueB = stock<?= $i->id?>.value;
        var inputValueC = tqi<?= $i->id?>.value;

        if (ns<?= $i->id ?>.value == 1) {
            if (inputValueA <= inputValueB -1 - inputValueC) {
              if (qtylimit.value == num<?= $i->id ?>.value) {
                var isi = "Food Stocks Are Not Fulfilled";
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
               a<?= $i->id ?>++;
               var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; 
               // console.log(cek); //cek
                num<?= $i->id ?>.value = cek;
                var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
                // total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
                qtycart<?= $i->id ?>.innerHTML ="Cart Qty "+ a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
                tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
                tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
                // localStorage.setItem('<?= $i->no ?>',a<?= $i->id?> + qa);
                 // localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
                 // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
                 // // console.log(hasil); 
              }
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
                        confirmButtonColor: "<?= $cn->color ?>",
                        confirmButtonText: 'OK'
                        })
               }
        }else{
          a<?= $i->id ?>++;
               
              var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; 
              // console.log(cek); //cek
              num<?= $i->id ?>.value = cek;
              var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
              // total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
              qtycart<?= $i->id ?>.innerHTML ="Cart Qty "+ a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
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
      var qtylimit = document.getElementById('jumlahpaket');
      var angka = localStorage.getItem('angka<?=$i->no?>');
      var angka = localStorage.getItem('angka<?=$i->no?>');
      var a<?= $i->id ?> = localStorage.getItem('qty<?=$i->no?>');
      if (plus<?= $i->id ?>) {
        plus<?= $i->id ?>.addEventListener("click", ()=>{
          var inputValueA = num<?= $i->id ?>.value;
          var inputValueB = stock<?= $i->id?>.value;
          var inputValueC = tqi<?= $i->id?>.value;
        if (ns<?= $i->id ?>.value == 1) {
          if (inputValueA <= inputValueB -1 - inputValueC) {
            if (qtylimit.value == num<?= $i->id ?>.value) {
                var isi = "Food Stocks Are Not Fulfilled";
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
                a<?= $i->id ?>++;
                 num<?= $i->id ?>.value = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;
                 var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; 
                 // console.log(cek); //cek
                var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
                // total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;

                qtycart<?= $i->id ?>.innerHTML = "Cart Qty "+ a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
                tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
                tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;

                 // localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
                 // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
                 // // console.log(hasil); 
              }
           
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
                    confirmButtonColor: "<?= $cn->color ?>",
                    confirmButtonText: 'OK'
                    })
           }
        }else{
          var cekjumlah = document.querySelector('.cekjumlah');
          
            if (qtylimit.value == 0) {
            var isi = "Please add the package quantity first.";
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
          }else if (qtylimit.value-cekjumlah.value == num<?= $i->id ?>.value) {
                var isi = "Please select max "+qtylimit.value;
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
                a<?= $i->id ?>++;
               num<?= $i->id ?>.value = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;
               var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; 
               // console.log(cek); //cek
              var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
              // total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;

              qtycart<?= $i->id ?>.innerHTML = "Cart Qty "+ a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
              tq.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;
              tqh.innerHTML = a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1;;

               // localStorage.setItem('H<?= $i->no ?>',hasil<?= $i->id ?>);
               // localStorage.setItem('<?= $i->no ?>',a<?= $i->id ?>);
               // // console.log(hasil); 
              }
          
          
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
   var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; 
   // console.log(cek); //cek
   var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
   // total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
   qtycart<?= $i->id ?>.innerHTML = "Cart Qty "+ a<?= $i->id ?>;
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
   var cek =a<?= $i->id ?>-parseInt(localStorage.getItem('angka<?=$i->no?>'))+1; 
   // console.log(cek); //cek
   var hasil<?= $i->id ?> = harga<?= $i->id ?>.value * cek;
   // total<?= $i->id ?>.innerHTML = hasil<?= $i->id ?>;
   qtycart<?= $i->id ?>.innerHTML = "Cart Qty "+ cek;
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
  var item_code = document.getElementById("item_code");
  var qtycart<?=$i->id?> = "Cart Qty "+ document.querySelector('#qtycart<?=$i->id?>');
  if (localStorage.getItem('<?=$i->no?>')) {
         if (localStorage.getItem(item_code.value) == 0) {
          jumlah<?=$i->id?>.value = 0;
         qtycart<?=$i->id?>.innerHTML = "Cart Qty "+0;
         }else{
         jumlah<?=$i->id?>.value = localStorage.getItem('<?=$i->no?>');
         qtycart<?=$i->id?>.innerHTML = "Cart Qty "+localStorage.getItem('<?=$i->no?>');
          }
  }else{
  }

  
</script>
<?php foreach ($item as $i): ?>
  <script type="text/javascript">
    var qc<?= $i->id ?> = document.querySelector(".qtycart<?= $i->id ?>");
    var qr<?= $i->id ?> = document.getElementById("qty<?= $i->id ?>");
    function tutuptab<?= $i->id ?>(){
    if (qr<?= $i->id ?>) {
      qr<?= $i->id ?>.value = "Cart Qty "+ 0;
    }
      var qa = parseInt(localStorage.getItem('<?=$i->no?>'));
    if (localStorage.getItem('<?=$i->no?>')) {
    qc<?= $i->id ?>.innerHTML = "Cart Qty "+ localStorage.getItem('<?=$i->no?>');
    tq.innerHTML = localStorage.getItem('<?=$i->no?>');
    tqh.innerHTML = localStorage.getItem('<?=$i->no?>')
    // localStorage.setItem('<?= $i->no ?>',qa - 1);
  }else{
    qc<?= $i->id ?>.innerHTML = "Cart Qty "+ 0;
    tq.innerHTML = "Cart Qty "+ 0;
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
    },5000);
    
  });
</script>
