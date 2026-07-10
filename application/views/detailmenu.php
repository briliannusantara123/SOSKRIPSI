<?php $this->load->view('template/headmenu') ?>
<style type="text/css">
  .desc {
    background-color: <?= $cn->color ?>;
    text-align: center;
    color: white;
    padding: 5px;
    border-radius: 10px;
    font-size: 20px;
  }
  .quantity-control{
    float: right;
    padding-right: 20px;
  }
  input[type="checkbox"]:checked {
        background-color: <?= $cn->color ?>; /* warna hijau */
        border-color: <?= $cn->color ?>;
    }
</style>
<!-- <link href="<?= base_url();?>assets/css/detail.css" rel="stylesheet"> -->
<?php $this->load->view('style/detail') ?>
  <div class="head">
        <header>
            <div style="display: flex; align-items: center;">
                <a href="<?= base_url() ?><?= $link ?>" style="text-decoration: none; color: black;">
                    <i class="bi bi-arrow-left" style="font-size: 30px;margin-left: 10px; text-shadow: 1px 1px 2px black;"></i>
                </a>
                <h2 style="margin: 0; margin-left: 5px;"><strong>Menu Detail</strong></h2>
            </div>
            <div class="profile">
                <!-- <a href="<?= base_url() ?>index.php/Billsementara/home/<?= $nomeja ?>" style="color: black"><i class="bi bi-file-earmark-text" style="font-size: 25px;"></i></a> -->
                <i class="fas fa-user" style="font-size: 20px;"><label style="font-size: 12px;">&nbsp;<?= explode(' ', $this->session->userdata('username'))[0]; ?> ( <?= $this->session->userdata('nomeja') ?> )</label></i>
            </div>
        </header>
    </div>
  <form action="<?=base_url()?><?= $linkform ?>" method="POST">
    <div class="burger-card">
      <?php 
        $img = '';

          if (!empty($item->image_path)) {
              if (strpos($item->image_path, 'assets') !== false) {
                  $img = base_url($item->image_path);
              } else {
                  $img = $item->image_path;
              }
          } else {
              $img = base_url($logo->image_path);
          }
          ?>
       ?>
      <?php if ($item->image_path): ?>
        <img src="<?= $img ?>" class="burger-image">
      <?php else: ?>
        <img src="<?= $logo->image_path ?>" style="width: 100%;">
      <?php endif ?>
      <!-- Rating dan Harga -->
      <div class="d-flex justify-content-between align-items-center">
        <div class="row">
          <div class="col-12">
            <div class="desc"><strong><?= $item->description ?></strong></div>
          </div>
          <div class="col-4">
            <!-- <?php if ($item->harga_weekday == 0): ?>
                <div class="price">Free</div>
              <?php elseif ($item->harga_weekend == 0): ?>
                <div class="price">Free</div>
              <?php elseif ($item->harga_holiday == 0): ?>
                <div class="price">Free</div>
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
                ?>
                <?php if ( $holiday == NULL): ?>
                   <?php  if ($hr == "Saturday" || $hr == "Sunday") :?>
                    <div class="price">Rp&nbsp;<?= number_format($item->harga_weekend) ?></div>
                    <input type="hidden" name="unit_price" value="<?= $item->harga_weekend ?>">
                    <?php else: ?>
                    <div class="price">Rp&nbsp;<?= number_format($item->harga_weekday) ?></div>
                    <input type="hidden" name="unit_price" value="<?= $item->harga_weekday ?>">
                  <?php   endif ?>
                <?php  else: ?>
                  <?php  if ($hr == "Saturday" || $hr == "Sunday") :?>
                    <div class="price">Rp&nbsp;<?= number_format($item->harga_weekend) ?></div>
                    <input type="hidden" name="unit_price" value="<?= $item->harga_weekend ?>">
                <?php elseif ($holiday->tipe == 0) :?>
                    <div class="price">Rp&nbsp;<?= number_format($item->harga_weekend) ?></div>
                    <input type="hidden" name="unit_price" value="<?= $item->harga_weekend ?>">
                <?php elseif ($holiday->tipe == 1 && $time >= $waktu) :?>
                    <div class="price">Rp&nbsp;<?= number_format($item->harga_weekend) ?></div>
                    <input type="hidden" name="unit_price" value="<?= $item->harga_weekend ?>">
                <?php elseif ($holiday->tipe == 1 && $time <= $waktu) :?>
                    <div class="price">Rp&nbsp;<?= number_format($item->harga_weekday) ?></div>
                    <input type="hidden" name="unit_price" value="<?= $item->harga_weekday ?>">
                <?php else: ?>
                  <div class="price">Rp&nbsp;<?= number_format($item->harga_weekday) ?></div>
                  <input type="hidden" name="unit_price" value="<?= $item->harga_weekday ?>">
                <?php endif ?>
                <?php endif ?>
              <?php endif ?> -->
              <input type="hidden" name="" id="stock" value="<?= $item->stock ?>">
              <input type="hidden" name="" id="need_stock" value="<?= $item->need_stock ?>">
              <?php 
                $this->load->model('Item_model');

                $cekpromo       = $this->Item_model->cekpromo($item->sub_category);
                $cekpromoharian = $this->Item_model->cekpromoharian($item->sub_category, $item->no);

                // ===============================
                // HARGA DEFAULT
                // ===============================
                $harga_asli  = $item->harga_weekend;
                $harga_akhir = $harga_asli;
                $promo_value = 0;
                $promo_type  = '';

                // ===============================
                // PRIORITAS PROMO
                // ===============================
                if (!empty($cekpromo)) {

                    $promo_value = (int)$cekpromo->promo_value;
                    $promo_type  = $cekpromo->promo_type;

                } elseif (!empty($cekpromoharian)) {

                    $promo_value = (int)$cekpromoharian->promo_value;
                    $promo_type  = $cekpromoharian->promo_type;
                }

                // ===============================
                // HITUNG HARGA
                // ===============================
                if ($promo_value > 0) {

                    if ($promo_type == 'Percentage') {

                        // Diskon persen
                        $harga_akhir = $harga_asli - ($harga_asli * ($promo_value / 100));

                    } elseif ($promo_type == 'Special Price') {

                        // Harga khusus langsung
                        $harga_akhir = $promo_value;
                    }

                }
                ?>
                
              <?php if ($promo_value != 0): ?>
                <?php if ($item->harga_weekday == 0 || $item->harga_weekend == 0 || $item->harga_holiday == 0): ?>
                    <h6>Free</h6>
                <?php else: ?>
                  <div class="price"><span style="text-decoration: line-through; color: red;">Rp&nbsp;<?= number_format($harga_asli) ?></span></div>
                    <div class="price">Rp&nbsp;<?= number_format($harga_akhir) ?></div>
                    <input type="hidden" name="unit_price" value="<?= $harga_akhir ?>">
                    <input type="hidden" name="unit_price_disc" value="<?= $harga_akhir ?>">
                    <input type="hidden" name="disc" value="<?= $promo_value ?>">
                <?php endif; ?>
              <?php else: ?>
                <?php if ($item->harga_weekend == 0): ?>
                  <div class="price">Free</div>
              <?php else: ?>
                  <div class="price">Rp&nbsp;<?= number_format($item->harga_weekend) ?></div>
                  <input type="hidden" name="unit_price" value="<?= $item->harga_weekend ?>">
                  <input type="hidden" name="unit_price_disc" value="<?= $item->harga_weekend ?>">
                  <input type="hidden" name="disc" value="0">
              <?php endif; ?>
              <?php endif ?>


          </div>
        </div>
        
        <input type="hidden" name="nama" value="<?= $item->description ?>">
        <input type="hidden" name="no" value="<?= $item->no ?>">
        
        
      </div>
      
      <!-- Judul Burger dan Deskripsi -->
      <div class="burger-title"></div>
      <p class="description"><?= $item->product_info ?></p>
      <?php if ($option): ?>
        <?php foreach ($option as $groupName => $opts): ?>
            <div class="option-group">
                <?php foreach ($opts as $o): ?>
                    <div class="row">
                        <div class="col-1">
                            <input type="checkbox" 
                                   name="options[<?= $groupName ?>][]" 
                                   value="<?= $o->description ?>" 
                                   class="custom-checkbox option-checkbox">
                        </div>
                        <div class="col-8"><?= $o->description ?></div>
                        <div class="col-3">FREE</div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <h5>Notes : </h5>
    <div class="row">
        <div class="col-12">
            <textarea name="notes" 
                      class="form-control" 
                      style="height: 150px;" 
                      id="customTextarea" 
                      maxlength="100" 
                      placeholder="Write a note (max 100 characters, letters and numbers only)"></textarea>
            <small id="noteHelp" class="form-text text-muted">Max 100 characters, letters and numbers only</small>
        </div>
    </div>

    <?php if ($addon): ?>
      <h5 class="mt-1">Add Ons</h5>
      <?php foreach ($addon as $add): ?>
          <div class="row">
              <div class="col-1">
                  <?php if ($add->is_sold_out): ?>
                    <input type="checkbox" name="addons[]" value="<?= $add->no ?>" class="custom-checkbox" disabled> 
                  <?php else: ?>  
                    <input type="checkbox" name="addons[]" value="<?= $add->no ?>" class="custom-checkbox">
                  <?php endif ?>
                  
              </div>
              <div class="col-8">
                <?php if ($add->is_sold_out): ?>
                    <span style="text-decoration: line-through; color: red;">
                        <?= $add->description ?>
                    </span>
                <?php else: ?>
                    <?= $add->description ?>
                <?php endif ?>

              </div>
              <div class="col-3">
                  <!-- <?php if ($add->harga_weekday == 0 || $add->harga_weekend == 0 || $add->harga_holiday == 0): ?>
                      <div>Free</div>
                      <input type="hidden" name="unit_price_add" value="0">
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
                      ?>
                      <?php if ($holiday == NULL): ?>
                          <?php if ($hr == "Saturday" || $hr == "Sunday") : ?>
                              <div>+Rp <?= number_format($add->harga_weekend) ?></div>
                              <input type="hidden" name="unit_price_add" value="<?= $add->harga_weekend ?>">
                          <?php else: ?>
                              <div>+Rp <?= number_format($add->harga_weekday) ?></div>
                              <input type="hidden" name="unit_price_add" value="<?= $add->harga_weekday ?>">
                          <?php endif ?>
                      <?php else: ?>
                          <?php if ($hr == "Saturday" || $hr == "Sunday" || $holiday->tipe == 0 || ($holiday->tipe == 1 && $time >= $waktu)) : ?>
                              <div>+Rp <?= number_format($add->harga_weekend) ?></div>
                              <input type="hidden" name="unit_price_add" value="<?= $add->harga_weekend ?>">
                          <?php else: ?>
                              <div>+Rp <?= number_format($add->harga_weekday) ?></div>
                              <input type="hidden" name="unit_price_add" value="<?= $add->harga_weekday ?>">
                          <?php endif ?>
                      <?php endif ?>
                  <?php endif ?> -->
                  <?php if ($add->is_sold_out): ?>
                      <div style="color: red;font-size: 13px;">SOLD OUT</div>
                  <?php else: ?>
                    <?php if ($add->harga_weekend == 0): ?>
                        <div>Free</div>
                        <input type="hidden" name="unit_price_add" value="0">
                    <?php else: ?>
                        <div style="font-size: 13px;">Rp <?= number_format($add->harga_weekend) ?></div>
                        <input type="hidden" name="unit_price_add" value="<?= $add->harga_weekend ?>">
                    <?php endif; ?>
                  <?php endif;?>
                  

              </div>
          </div>
      <?php endforeach; ?>
  <?php endif ?>

      
        
      <!-- <h5 class="mt-1">Additional Notes</h5>
      <div class="row">
        <div class="col-12">
          <textarea name="notes" class="form-control" style="height: 100px;" id="customTextarea"></textarea>
        </div>
      </div>   -->
      
    </div>
  
  <footer>
    <div class="quantity-control" style="display:flex; justify-content:space-between; align-items:center;">
    
        <span style="margin-right: 90px;">Ordered Quantity</span>

        <div style="display:flex; align-items:center; gap:10px;">
            <button type="button" class="btn" style="color:white;height:40px;background-color: <?= $cn->color ?>" id="kurang">-</button>
            
            <span id="hasil">1</span>
            
            <button type="button" class="btn" style="color:white;height:40px;background-color: <?= $cn->color ?>" id="tambah">+</button>
        </div>

        <input type="hidden" name="qty" id="qty" value="1">
    </div>
    <!-- <div class="quantity-control">
      <?php if ($item->sub_category != 'Special Promo'): ?>
        <button type="button" class="btn" style="color: white;height: 40px;background-color: <?= $cn->color ?>" id="kurang">-</button>
        <span id="hasil">1</span>
        
        <button type="button" class="btn" style="color: white;height: 40px;background-color: <?= $cn->color ?>" id="tambah">+</button>
      <?php endif ?>
      <input type="hidden" name="qty" id="qty" value="1">
    </div> -->
    <?php 
      // Cek apakah item ini sudah ada di cart/trans
      $cekSPchart = $this->Item_model->cekSPchart($item->no);
      $cekSPtrans = $this->Item_model->cekSPtrans($item->no);

      // Cek apakah ada item Special Promo lain di cart/trans (tanpa filter item_code)
      $adaSPchart = $this->Item_model->cekSPchart(); 
      $adaSPtrans = $this->Item_model->cekSPtrans(); 
      ?>

      <?php if ($item->sub_category == 'Special Promo'): ?>
          <?php 
          $itemIniAda = ($cekSPchart || $cekSPtrans); // item ini sudah ada
          $sudahAdaSP = ($adaSPchart || $adaSPtrans); // ada SP lain di cart/trans
          ?>

          <?php if (!$sudahAdaSP || $itemIniAda): ?>
              <!-- ✅ Belum ada SP lain, atau ini item SP yang sama → BOLEH Add to Cart -->
              <button type="submit" class="btn add-to-cart-btn" 
                      style="background-color: <?= $cn->color ?>; color: white;">
                  Add to Cart
              </button>
          <?php else: ?>
              <!-- ❌ Ada SP lain dan bukan item ini → DISABLE -->
              <button type="button" class="btn add-to-cart-btn" 
                      style="background-color: #b0b0b0; color: white; cursor: not-allowed;" disabled>
                  You have already ordered another special promo item
              </button>
          <?php endif; ?>
      <?php else: ?>
          <!-- Item non–Special Promo → selalu boleh Add to Cart -->
          <button type="submit" class="btn add-to-cart-btn" 
                  style="background-color: <?= $cn->color ?>; color: white;">
              Add to Cart
          </button>
      <?php endif; ?>


    
  </footer>
</form>
  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  <script type="text/javascript">
    function onlyOne(checkbox) {
        var checkboxes = document.querySelectorAll('input[name="options[]"]');
        checkboxes.forEach((item) => {
            if (item !== checkbox) item.checked = false;
        });
    }

    function onlyOneADD(checkbox) {
        var addons = document.querySelectorAll('input[name="addons[]"]');
        addons.forEach((item) => {
            if (item !== checkbox) item.checked = false;
        });
    }

    const hasil = document.getElementById('hasil');
    const tambah = document.getElementById('tambah');
    const qty = document.getElementById('qty');
    const kurang = document.getElementById('kurang');
    const stock = document.getElementById('stock');
    const need_stock = document.getElementById('need_stock');

    tambah.addEventListener('click', function () {
        let currentValue = parseInt(hasil.innerText);
        let maxStock = parseInt(stock.value); // Ambil nilai stok

        if (parseInt(need_stock.value) === 1) { // Jika need_stock bernilai 1
            if (currentValue < maxStock) {
                hasil.innerText = currentValue + 1;
                qty.value = currentValue + 1;
            } else {
                var isi = maxStock +' stock remaining';
                Swal.fire({
                title: 'Notification!',
                text: isi,
                icon: 'warning',
                confirmButtonColor: "<?= $cn->color ?>",
                confirmButtonText: 'OK'
                },setTimeout(function(){ 

              }, 3000));
            }
        } else {
            hasil.innerText = currentValue + 1;
            qty.value = currentValue + 1;
        }
    });

    kurang.addEventListener('click', function () {
        let currentValue = parseInt(hasil.innerText);
        if (currentValue > 0) { // Angka tidak boleh kurang dari 0
            hasil.innerText = currentValue - 1;
            qty.value = currentValue - 1;
        }
    });

    // const textarea = document.getElementById('customTextarea');

    // // Event listener untuk memfilter input
    // textarea.addEventListener('input', function(event) {
    //     let value = textarea.value;
    //     // Regex untuk menghapus emoji dan karakter spesial
    //     textarea.value = value.replace(/[^\w\s.,]/g, ''); // Mengizinkan huruf, angka, spasi, dan tanda baca seperti koma, titik
    // });
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }
    function scrollToBottom() {
        window.scrollTo({
            top: document.body.scrollHeight, // Menggulung hingga akhir halaman
            behavior: "smooth" // Efek pengguliran halus
        });
    }
  </script>
 <script>
document.querySelectorAll('.option-group').forEach(group => {
    const checkboxes = group.querySelectorAll('input[type="checkbox"]');

    checkboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            if (this.checked) {
                // uncheck semua checkbox lain di group ini
                checkboxes.forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            }
        });
    });
});
</script>
<script>
const textarea = document.getElementById('customTextarea');

textarea.addEventListener('input', function() {
    // hapus semua karakter yang bukan huruf, angka, atau spasi
    this.value = this.value.replace(/[^a-zA-Z0-9\s]/g, '');
});
</script>
  <!-- <?php if ($option): ?>
    <script>
    document.querySelector('form').addEventListener('submit', function(event) {
        const checkboxes = document.querySelectorAll('.option-checkbox');
        let isChecked = false;
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                isChecked = true;
            }
        });

        if (!isChecked) {
            event.preventDefault();  // Prevent form submission
            var isi = 'Please Select at Least One Options';
            Swal.fire({
            title: 'Notification!',
            text: isi,
            icon: 'warning',
            confirmButtonColor: "<?= $cn->color ?>",
            confirmButtonText: 'OK'
            })
        }
    });
  </script>    
  <?php endif ?> -->
<?php $this->load->view('template/footer') ?>

