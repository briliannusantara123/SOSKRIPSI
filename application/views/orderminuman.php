<?php $this->load->view('template/headmenu') ?>
<style>
/* ===== SIDEBAR PREMIUM ===== */
.sidebar {
  background: linear-gradient(180deg, #ffffff, #f3f4f6);
  box-shadow: 4px 0 15px rgba(0,0,0,0.15);
}
.sidebar-toggle {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.sidebar-toggle.hide {
  opacity: 0;
  transform: translateX(-10px);
  pointer-events: none;
}

/* ===== MOBILE SLIDE ===== */
@media (max-width: 768px) {
  #sidebarMenu {
    position: fixed;
    top: 0;
    left: -260px;
    width: 260px;
    height: 100vh;
    z-index: 1100;
    transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }

  #sidebarMenu.show {
    left: 0;
  }

  .sidebar-toggle {
    position: fixed;
    top: 50%;
    left: 0;
    transform: translateY(-50%);
    z-index: 1200;

    writing-mode: vertical-rl;
    text-orientation: mixed;

    background: linear-gradient(<?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);
    color: #fff;

    padding: 14px 8px;
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 3px;

    border-radius: 0 10px 10px 0;
    box-shadow: 2px 2px 12px rgba(0,0,0,0.25);
    cursor: pointer;

    animation: floatText 2.5s ease-in-out infinite;
  }

  .sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 1040;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.3s ease;
  }

  .sidebar-overlay.show {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
  }
}

/* Desktop */
@media (min-width: 769px) {
  .sidebar-toggle,
  .sidebar-overlay {
    display: none;
  }
}

.menu-main {
  padding-top: 10px;
}

.ordered-qty {
  position: absolute;
  top: 5px;
  right: 10px;
  background: red;
  padding: 2px 6px;
  font-size: 12px;
  border-radius: 5px;
}

/* ✨ FLOAT ANIMATION */
@keyframes floatText {
  0%,100% { transform: translateY(-50%) translateX(0); }
  50%     { transform: translateY(-50%) translateX(4px); }
}
</style>
<div id="loading"></div>
<div id="load"></div>
<p id="loadingtext">Loading</p>

<div class="user-icon-circle circle1">
    <div class="icon-wrapper">
        <a href="javascript:void(0)" onclick="scrollToTop()">
            <i class="fas fa-chevron-up user-icon"></i>
        </a>
    </div>
</div>
<div class="user-icon-circle circle2">
    <div class="icon-wrapper">
        <a href="javascript:void(0)" onclick="scrollToBottom()">
            <i class="fas fa-chevron-down user-icon"></i> <!-- Ikon Panah Ke Bawah -->
        </a>
    </div>
</div>
  <div class="container">
    <div class="head">
        <header>
            <div style="display: flex; align-items: center;">
                <a href="<?= base_url() ?>index.php/selforder/home/<?= $nomeja ?>" style="text-decoration: none; color: black;">
                    <i class="bi bi-arrow-left" style="font-size: 30px;margin-left: 10px; text-shadow: 1px 1px 2px black;"></i>
                </a>
                <h2 style="margin: 0; margin-left: 5px;"><strong>Beverage</strong></h2>
            </div>
            <div class="profile">
                <a href="<?= base_url() ?>index.php/Billsementara/home/<?= $nomeja ?>" style="color: black"><i class="bi bi-file-earmark-text" style="font-size: 25px;"></i></a>
                <a href="<?= base_url() ?>index.php/user/home/Minuman/<?= $nomeja ?>/<?= $s ?>" style="color: black;"><i class="fas fa-user" style="font-size: 20px;"><label style="font-size: 12px;">&nbsp;<?= $this->session->userdata('username') ?> ( <?= $this->session->userdata('nomeja') ?> )</label></i></a>
            </div>
        </header>
        <div class="search">
            <i class="bi bi-search" style="color: black"></i>
            <form id="searchForm" action="<?= base_url() ?>index.php/orderminuman/search/<?= $nomeja ?>" method="POST">
                <input type="text"
                 id="search"
                 name="keyword"
                 placeholder="Search beverages..."
                 autocomplete="off"
                 value="<?= set_value('keyword') ?>">
            </form>
        </div>
    </div>
    <div id="sidebarToggle" class="sidebar-toggle d-md-none">
      Category
    </div>

    <!-- OVERLAY -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

<div class="container-fluid">
  <div class="row">

    <!-- SIDEBAR -->
    <nav id="sidebarMenu" class="col-lg-3 col-md-4 sidebar">
  <div class="pt-3">
    <h4 class="px-3" style="text-align: center;background: linear-gradient(<?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);color: white;padding-top:5px;padding-bottom:5px;border-radius: 10px;">Category</h4>

    <ul class="nav flex-column px-2">

      <?php foreach ($sub as $i): ?>
        <?php
          $cat_id   = str_replace(" ","_", $i['sub_category']);
          $cat_url  = base_url() .
            "index.php/orderminuman/menu/Minuman/" .
            str_replace(" ","%20", $i['sub_category']) .
            "#" . $cat_id;
        ?>
        <li class="nav-item">
          <h5><a href="<?= $cat_url ?>"
             id="<?= $cat_id ?>"
             class="nav-link nonactive <?= $cat_id ?> test<?= $cat_id ?>">
            <?= $i['sub_category'] ?>
          </a></h5>
        </li>
      <?php endforeach ?>

    </ul>
  </div>
</nav>


    <!-- MAIN CONTENT -->
    <main class="col-lg-9 col-md-8 px-md-4">
      <div class="container menu-main">

        <?php foreach ($item as $i): ?>

        <?php
          $cekpromo       = $this->Item_model->cekpromo($i->sub_category);
          $cekpromoharian = $this->Item_model->cekpromoharian($i->sub_category, $i->no);

          $harga_asli  = $i->harga_weekend;
          $harga_akhir = $harga_asli;
          $promo_value = 0;

          if (!empty($cekpromo)) {
            $promo_value = (int)$cekpromo->promo_value;
          } elseif (!empty($cekpromoharian)) {
            $promo_value = (int)$cekpromoharian->promo_value;
          }

          if ($promo_value > 0) {
            $harga_akhir = $harga_asli - ($harga_asli * ($promo_value / 100));
          }
        ?>

        <?php if ($i->is_sold_out == 0): ?>
        <div class="row align-items-center menu-item position-relative"
             id="<?= str_replace(' ','_',$i->sub_category) ?>">

          <?php foreach ($this->Item_model->cekpesan($i->no) as $c): ?>
            <div class="ordered-qty">Ordered <?= $c->qty ?></div>
          <?php endforeach ?>

          <div class="col-3">
            <img src="<?= $i->image_path ?: $logo->image_path ?>">
          </div>

          <div class="col-6">
            <h6><?= $i->description ?></h6>

            <?php if ($i->harga_weekday == 0 || $i->harga_weekend == 0 || $i->harga_holiday == 0): ?>
              <h6>Free</h6>
            <?php else: ?>
              <?php if ($promo_value > 0): ?>
                <span class="text-danger text-decoration-line-through">
                  Rp <?= number_format($harga_asli) ?>
                </span><br>
              <?php endif ?>
              <strong>Rp <?= number_format($harga_akhir) ?></strong>
            <?php endif ?>
          </div>

          <div class="col-3 text-end">
            <?php
              $cekSPchart = $this->Item_model->cekSPchart($i->no);
              $cekSPtrans = $this->Item_model->cekSPtrans($i->no);
              $adaSPchart = $this->Item_model->cekSPchart();
              $adaSPtrans = $this->Item_model->cekSPtrans();

              $itemIniAda = ($cekSPchart || $cekSPtrans);
              $sudahAdaSP = ($adaSPchart || $adaSPtrans);
            ?>

            <?php if ($i->sub_category == 'Special Promo' && $sudahAdaSP && !$itemIniAda): ?>
              <div class="add-btn-muted" style="opacity:0.5">
                <i class="bi bi-plus" style="font-size:25px"></i>
              </div>
            <?php else: ?>
              <div class="add-btn">
                <a href="<?= base_url('index.php/orderminuman/detailmenu/'.$i->id.'/'.str_replace(' ','%20',$i->sub_category)) ?>"
                   style="color:white;font-size:25px">
                  <i class="bi bi-plus"></i>
                </a>
              </div>
            <?php endif ?>
          </div>

        </div>
        <?php endif ?>

        <?php endforeach ?>

      </div>
    </main>

  </div>
</div>
<div style="margin-bottom: 50px"></div>

<div class="containerfooter text-center" >
    <footer>
      <?php if ($cekpay): ?>
                <div class="alert alert-danger alert-sm" role="alert">
                    There are unpaid orders, Please<a href="<?= base_url() ?>index.php/Billsementara/home/<?= $nomeja ?>"><br>Check Payment</a>
                </div>
            <?php endif ?>
            <nav>
                <?php foreach ($iconfooter as $i): ?>
                    <?php if ($i->link_type == 'status'): ?>
                      <a href="<?= base_url() ?><?= $i->link ?>">
                    <?php else: ?>  
                      <a href="<?= base_url() ?><?= $i->link ?><?= $nomeja ?>/Minuman/<?= $s ?>">
                    <?php endif ?>
                    
                        <img src="<?= $i->image_path ?>" style="width: 25px;height: 25px; filter: grayscale(100%);">
                    <?php if ($i->title == 'Cart'): ?>
                       <span class="badge" style="position: absolute; top: -10px; right: -10px; background-color: red; color: white; border-radius: 50%; padding: 5px; font-size: 12px;"><?= $total_qty;?></span>
                    <?php endif ?>
                    </i>

                        <span><?= $i->title ?></span>
                    </a>
                <?php endforeach ?>
            </nav>
    </footer>
</div>


</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<?php foreach($sub as $i): ?>
<script type="text/javascript">
  var<?= str_replace(" ","_", $i['sub_category']) ?>= document.getElementById('<?= str_replace(" ","_", $i['sub_category']) ?>');
  var item<?= str_replace(" ","_", $i['sub_category']) ?> = document.querySelector('.test<?= str_replace(" ","_", $i['sub_category']) ?>');

    if (window.location.toString() == "<?= base_url() ?>index.php/orderminuman/menu/Minuman/<?= str_replace(" ","%20", $i['sub_category']) ?>#<?= str_replace(" ","_", $i['sub_category']) ?>") {
        $(document).ready(function() {
        item<?= str_replace(" ","_", $i['sub_category']) ?>.classList.add('active');
        item<?= str_replace(" ","_", $i['sub_category']) ?>.classList.remove('nonactive');
    });
    }
</script>
<?php endforeach; ?>
<script type="text/javascript">
  let typingTimer;                // Variabel untuk timer
  let typingInterval = 500;       // Waktu jeda setelah selesai mengetik (500ms)
  let searchInput = document.getElementById('search');

  // Event listener untuk mendeteksi pengguna mengetik
  searchInput.addEventListener('keyup', () => {
      clearTimeout(typingTimer);   // Hapus timer sebelumnya
      typingTimer = setTimeout(submitForm, typingInterval);  // Set timer baru
  });

  // Event listener untuk membatalkan submit jika masih mengetik
  searchInput.addEventListener('keydown', () => {
      clearTimeout(typingTimer);   // Hapus timer jika masih mengetik
  });

  // Fungsi untuk submit form
  function submitForm() {
      document.getElementById('searchForm').submit();  // Submit form
  }
  

</script>
<script>
const sidebar = document.getElementById('sidebarMenu');
const toggle  = document.getElementById('sidebarToggle');
const overlay = document.getElementById('sidebarOverlay');

function openSidebar() {
  sidebar.classList.add('show');
  overlay.classList.add('show');

  // 🔥 HIDE TEXT
  toggle.classList.add('hide');
}

function closeSidebar() {
  sidebar.classList.remove('show');
  overlay.classList.remove('show');

  // 🔥 SHOW TEXT
  toggle.classList.remove('hide');
}

toggle.addEventListener('click', openSidebar);
overlay.addEventListener('click', closeSidebar);

// Tutup saat klik kategori
document.querySelectorAll('#sidebarMenu a').forEach(link => {
  link.addEventListener('click', closeSidebar);
});
</script>




<?php $this->load->view('template/footer') ?>


