<?php $this->load->view('template/headmenu') ?>

<style>
    body {
        background: #f7f7f7;
        padding-bottom: 110px;
    }

    /* ===== HEADER ===== */
    .banner {
        height: 160px;
        background: url("<?= base_url() ?>/assets/banner.png") center/cover no-repeat;
        border-radius: 0 0 20px 20px;
        box-shadow: 0px 5px 5px #00000040;
    }

    /* ===== CATEGORY TAB ===== */
    .category-tabs {
        display: flex;
        flex-wrap: nowrap;
        overflow: visible;
        gap: 1px;
        padding: 12px 10px 10px;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 5px;
        box-shadow: 0px 5px 5px #00000040;
        border-radius: 0 0 10px 10px;
    }
    .category-tabs-scroll {
        display: flex;
        flex-wrap: nowrap;
        scroll-behavior: smooth;
        overflow-x: auto;           /* ⬅ scroll pindah ke wrapper */
        gap: 15px;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    .category-tabs-scroll::-webkit-scrollbar {
        display: none;
    }
    .box-card{
        box-shadow: 0px 5px 5px #00000040;
        border-radius: 0 0 10px 10px;
    }

    .category-tabs::-webkit-scrollbar {
        display: none;
    }

    .category-tabs .nav-link {
        color: black;
        font-weight: 600;
        padding: 6px 0;
        background: transparent;
        position: relative;
    }

    .category-tabs .nav-link.active {
        color: <?= $cn->color ?>;
        font-weight: 800;
    }

    .category-tabs .nav-link.active::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -1px;
        width: 100%;
        height: 3px;
        background: <?= $cn->color ?>;
        border-radius: 10px;
        box-shadow: 0 5px 10px rgba(0,0,0,0.15);
    }

    /* ===== SUBCATEGORY GRID ===== */
    .menu-grid-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        box-shadow: 0 5px 10px rgba(0,0,0,0.15);
    }

    .menu-grid-img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    /* isi card flex */
    .menu-grid-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding-top: 6px;
    }
    .menu-info {
        min-height: 70px; /* ⬅ KUNCI agar tombol sejajar */
    }
    .menu-title {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* maksimal 2 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* button / qty selalu bawah */
    .menu-grid-content button,
    .menu-grid-content .qty-box {
        margin-top: auto;
    }

    /* qty box */
    .qty-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 2px solid <?= $cn->color ?>;
        border-radius: 12px;
        padding: 4px 8px;
    }

    .qty-box button {
        border: none;
        background: transparent;
        font-size: 18px;
    }

    /* ===== CHECKOUT BAR ===== */
    .checkout-bar {
        position: fixed;
        left: 15px;
        right: 15px;
        bottom: 15px;
        background: linear-gradient(<?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);
        color: white;
        padding: 15px 18px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        z-index: 1050;
    }
    .checkout-bar {
        transition: all 0.35s ease;
        transform: translateY(120%);
        opacity: 0;
    }

    .checkout-bar.show {
        transform: translateY(0);
        opacity: 1;
    }
    #subcategory{
        box-shadow: 0 5px 10px rgba(0,0,0,0.15);
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        height: 100%;
    }
    .search-wrapper {
        z-index: 1100;
    }

    .search-box {
        display: flex;
        align-items: center;
        background: #ffffff;          /* ⬅ ini kuncinya */
        padding: 6px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }


    /* input hidden default */
    .search-input {
        width: 0;
        opacity: 0;
        padding: 0;
        border-radius: 30px;
        transition: all 0.3s ease;
        border: none;
        margin-right: 8px;
    }

    /* aktif */
    .search-box.active .search-input {
        width: 220px;
        opacity: 1;
        padding: 6px 14px;
        border: 1px solid #ddd;
    }
    .sticky-header {
        position: sticky;
        top: 0;                       /* menempel di atas */
        z-index: 1040;                /* di atas konten menu */
        background: #f7f7f7;          /* supaya tidak transparan */
        padding-top: 5px;
    }
    .table-search-slot .search-box {
        box-shadow: none;
        background: #fff;
    }

    .table-search-slot .search-input {
        width: 0;
    }

    .table-search-slot .search-box.active .search-input {
        width: 160px;
    }
    .btn-outline-custom {
        color: <?= $cn->color ?>;
        border-color: <?= $cn->color ?>;
    }

    .btn-outline-custom:hover {
        background-color: <?= $cn->color ?>;
        color: #fff;
        border-color: <?= $cn->color ?>;
    }

    .store-card {
        flex-direction: row;
    }

    .store-card .arrow-slot i {
        font-size: 22px;
        background: #fff;
        padding: 4px 8px;
        border-radius: 50%;
        box-shadow: 0px 3px 5px #00000040;
    }
    .sold_text{
        text-align: center;
        margin-top:10px;
        padding: 5px 5px;
        background-color: red;
        color: white;
        box-shadow: 0px 3px 5px #00000040;
    }
    .btn-outline-custom-sold {
        background-color: red;
        color: #fff;
        border-color: red;
    }
    .ordered-qty {
        position: absolute;
        top: 10px;
        right: -10px;          /* ⬅ kunci di kanan */
        background: red;
        padding: 2px 6px;
        font-size: 12px;
        border-radius: 5px;
        color: #fff;
        box-shadow: 0px 3px 5px #00000040;
    }

    .qty-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 2px solid <?= $cn->color ?>;
        border-radius: 50px;
        padding: 4px 10px;
        height: 36px;
        box-shadow: 0px 5px 5px #00000040;
        background: #fff;
    }

    .qty-btn {
        border: none;
        background: transparent;
        font-size: 20px;
        font-weight: bold;
        color: <?= $cn->color ?>;
        width: 30px;
    }

    .qty-value {
        font-size: 14px;
        font-weight: 600;
    }
    .qty-btn.disabled {
        pointer-events: none;
        opacity: 0.4;
    }
    .menu-grid-img.grayscale {
        filter: grayscale(100%);
    }
    /* DARK DROPDOWN */
    .dark-dropdown-menu {
        border-radius: 8px;
        padding: 6px 0;
        min-width: 190px;
        border: none;
        box-shadow: 0 8px 20px rgba(0,0,0,.6);
    }

    /* ITEM */
    .dark-dropdown-item {
        display: block;
        padding: 10px 16px;
        font-size: 13px;
        color: black;
        border-radius: 10px;
        box-shadow: 0 1px 10px rgba(0,0,0,.6);
        text-decoration: none;
    }

    /* HOVER */
    .dark-dropdown-item:hover {
        background: #1f4d3a;
        color: #fff;
    }

    /* SCROLL */
    .dark-dropdown-menu {
        max-height: 300px;
        overflow-y: auto;
    }


</style>
</head>

<body>

<!-- HEADER -->
<div class="banner position-relative">

    <!-- PANAH KIRI -->
    <a href="<?= base_url() ?>index.php/selforder/home/<?= $nomeja ?>"
       id="backArrow"
       class="position-absolute top-0 start-0 m-3"
       style="text-decoration:none; color:black;">
        <i class="bi bi-arrow-left"
           style="font-size:30px; text-shadow:1px 1px 2px black;
                  background:white; padding:5px 10px; border-radius:50%;
                  box-shadow:0px 5px 5px #00000040;"></i>
    </a>

    <!-- SEARCH KANAN -->
    <div class="search-wrapper position-absolute top-0 end-0 m-3" id="searchWrapper">
        <div class="search-box">
            <form id="searchForm" action="<?= base_url() ?>index.php/orderminuman/search/<?= $nomeja ?>" method="POST">
                <input type="text" id="search" name="keyword" class="form-control search-input" placeholder="Search menu..." autocomplete="off"
                 value="<?= set_value('keyword') ?>">
            </form>
            <button class="btn btn-light search-btn rounded-circle">
                <i class="bi bi-search"></i>
            </button>

        </div>
    </div>

</div>


<div class="container mt-2">

    <div class="sticky-header">
        <!-- STORE INFO -->
        <div class="card p-3 mb-2 shadow-sm store-card d-flex align-items-center gap-2">
            <div class="arrow-slot"></div>

            <div>
                <h6 class="mb-1">Dear Clio - Kuningan City</h6>
                <small class="text-muted">Open <?= substr($settings->open, 0, 5) ?> - <?= substr($settings->close, 0, 5) ?></small>
            </div>
        </div>

        <div class="card bg-warning bg-opacity-50 p-2 mb-2 shadow-sm table-card">
            <div class="table-row d-flex align-items-center">
                <strong class="table-no">Table No : <?= $nomeja ?></strong>

                <!-- TEMPAT SEARCH PINDAH -->
                <div class="table-search-slot ms-auto"></div>
            </div>
        </div>

        <!-- CATEGORY -->
        <div class="category-tabs box-card d-flex align-items-center">

            <!-- HAMBURGER (TIDAK IKUT SCROLL) -->
            <div class="category-hamburger dropdown me-2">
                <button class="btn p-1"
                        type="button"
                        data-bs-toggle="dropdown">
                    <i class="bi bi-list" style="font-size:25px;"></i>
                </button>

                <ul class="dropdown-menu dark-dropdown-menu">
                    <?php foreach ($sub as $i): ?>
                        <?php
                            $cat_id  = str_replace(' ', '_', $i['sub_category']);
                            $cat_url = base_url(
                                "index.php/orderminuman/menu/makanan/" .
                                rawurlencode($i['sub_category'])
                            ) . "#" . $cat_id;
                        ?>
                        <li>
                            
                             <a class="dark-dropdown-item"
                               href="<?= $cat_url ?>"
                               data-cat="<?= $cat_id ?>">
                                <?= $i['sub_category'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- TAB SCROLL -->
            <ul class="nav category-tabs-scroll flex-nowrap" id="categoryTabs">
                <?php foreach ($sub as $i): ?>
                    <?php
                        $cat_id  = str_replace(' ', '_', $i['sub_category']);
                        $cat_url = base_url(
                            "index.php/orderminuman/menu/makanan/" .
                            rawurlencode($i['sub_category'])
                        ) . "#" . $cat_id;
                    ?>
                    <li class="nav-item">
                    <a class="nav-link"
                       href="<?= $cat_url ?>"
                       data-cat="<?= $cat_id ?>">
                        <?= $i['sub_category'] ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>

        </div>


    </div>


    

    <!-- SUBCATEGORY -->
    <div id="subcategory" class="mt-3">
        <div class="row g-3">
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
                <div class="col-6">
                    <div class="card menu-grid-card p-2">
                        <?php if ($i->is_sold_out == 0): ?>
                            <img src="<?= $i->image_path ?: $logo->image_path ?>" class="menu-grid-img">
                        <?php else: ?>
                            <img src="<?= $i->image_path ?: $logo->image_path ?>" class="menu-grid-img grayscale">
                        <?php endif; ?>
                        
                        <?php foreach ($this->Item_model->cekpesan($i->no) as $c): ?>
                            <div class="ordered-qty">Ordered <?= $c->qty ?></div>
                        <?php endforeach ?>
                        <div class="menu-grid-content">
                            <div class="menu-info">
                                <strong class="menu-title"><?= $i->description ?></strong>
                                <?php if ($i->harga_weekday == 0 || $i->harga_weekend == 0 || $i->harga_holiday == 0): ?>
                                  <div class="text-muted">Free</div>
                                <?php else: ?>
                                  <?php if ($promo_value > 0): ?>
                                    <span class="text-danger text-decoration-line-through">
                                      Rp <?= number_format($harga_asli) ?>
                                    </span><br>
                                  <?php endif ?>
                                  <div class="text-muted">Rp <?= number_format($harga_akhir) ?></div>
                                <?php endif ?>
                                
                            </div>

                            <?php if ($i->is_sold_out == 0): ?>
                                <?php
                                  $cekSPchart = $this->Item_model->cekSPchart($i->no);
                                  $cekSPtrans = $this->Item_model->cekSPtrans($i->no);
                                  $adaSPchart = $this->Item_model->cekSPchart();
                                  $adaSPtrans = $this->Item_model->cekSPtrans();

                                  $itemIniAda = ($cekSPchart || $cekSPtrans);
                                  $sudahAdaSP = ($adaSPchart || $adaSPtrans);
                                ?>
                              <?php $option = $this->Item_model->getOption($i->no); ?>
                              <?php if ($i->sub_category == 'Special Promo' && $sudahAdaSP && !$itemIniAda): ?>
                                <a class="btn btn-outline-custom btn-sm w-100 mt-2"style="border-radius: 50px;box-shadow: 0px 5px 5px #00000040;">Only one promo item allowed</a>
                              <?php else: ?>
                                    <?php if ($option): ?>
                                      <a href="<?= base_url('index.php/orderminuman/detailmenu/'.$i->id.'/'.str_replace(' ','%20',$i->sub_category)) ?>" class="btn btn-outline-custom btn-sm w-100 mt-2"style="border-radius: 50px;box-shadow: 0px 5px 5px #00000040;">Add</a>
                                  <?php else: ?>
                                        <?php
                                            $itemCode = $i->no;
                                            $cartQty  = $cartMap[$itemCode] ?? 0;
                                        ?>
                                      <div class="order-action mt-2"
                                         data-id="<?= $i->id ?>"
                                         data-price="<?= $harga_akhir ?>">

                                        <!-- ADD BUTTON -->
                                        <?php if ($status_meja == 'Billing'): ?>
                                            <button type="button"
                                                class="btn btn-outline-custom btn-sm w-100 add-btn <?= $cartQty > 0 ? 'd-none' : '' ?> disabled"
                                                style="border-radius:50px;box-shadow:0px 5px 5px #00000040;">
                                                Add
                                            </button>
                                        <?php else: ?>  
                                            <button type="button"
                                                class="btn btn-outline-custom btn-sm w-100 add-btn <?= $cartQty > 0 ? 'd-none' : '' ?>"
                                                style="border-radius:50px;box-shadow:0px 5px 5px #00000040;">
                                                Add
                                            </button>
                                        <?php endif ?>

                                        <!-- QTY BOX -->
                                        <div class="qty-box <?= $cartQty > 0 ? '' : 'd-none' ?>"
                                             data-stock="<?= $i->stock ?>"
                                             data-need-stock="<?= $i->need_stock ?>"
                                             data-item-id="<?= $i->id ?>">

                                            <button type="button" class="qty-btn minus">
                                                <i class="bi bi-dash-circle-fill"></i>
                                            </button>

                                            <span class="qty-value"><?= $cartQty > 0 ? $cartQty : 1 ?></span>

                                            <button type="button"
                                                    class="qty-btn plus <?= ($i->need_stock == 1 && $cartQty >= $i->stock) ? 'disabled' : '' ?>">
                                                <i class="bi bi-plus-circle-fill"></i>
                                            </button>
                                        </div>

                                    </div>

                                  <?php endif ?>
                              <?php endif ?>
                              
                            <?php else: ?>
                                <a class="btn btn-outline-custom-sold btn-sm w-100 mt-2"style="border-radius: 50px;box-shadow: 0px 5px 5px #00000040;">Sold out</a>
                            <?php endif ?>
                            


                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<!-- CHECKOUT -->
<a href="<?= base_url() ?>index.php/Cart/home/<?= $nomeja ?>/makanan/<?= $s ?>"
   class="checkout-bar d-flex justify-content-between align-items-center d-none"
   id="checkoutBar"
   style="text-decoration:none;color:white;">

    <div>
        <div>Total</div>
        <strong id="checkoutTotal">Rp 0</strong>
    </div>

    <div>
        View Cart (<span id="checkoutQty">0</span>)
    </div>

</a>




<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       CATEGORY TAB – AUTO ACTIVE & AUTO SCROLL
    ===================================================== */
    const tabContainer = document.getElementById('categoryTabs');
    if (tabContainer) {
        const hash = window.location.hash.replace('#', '');
        const tabs = tabContainer.querySelectorAll('.nav-link');

        let activeTab = null;

        if (hash) {
            activeTab = tabContainer.querySelector(`.nav-link[data-cat="${hash}"]`);
        }
        if (!activeTab && tabs.length) {
            activeTab = tabs[0];
        }

        if (activeTab) {
            tabs.forEach(t => t.classList.remove('active'));
            activeTab.classList.add('active');

            activeTab.scrollIntoView({
                behavior: 'smooth',
                inline: 'center',
                block: 'nearest'
            });
        }
    }


    /* =====================================================
       SEARCH BOX TOGGLE + AUTO OPEN IF VALUE EXISTS
    ===================================================== */
    const searchBox   = document.querySelector('.search-box');
    const searchBtn   = document.querySelector('.search-btn');
    const searchInput = document.querySelector('.search-input');

    if (searchBox && searchBtn && searchInput) {

        searchBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            searchBox.classList.toggle('active');
            if (searchBox.classList.contains('active')) {
                setTimeout(() => searchInput.focus(), 200);
            }
        });

        searchInput.addEventListener('click', e => e.stopPropagation());
        document.addEventListener('click', () => searchBox.classList.remove('active'));

        if (searchInput.value.trim() !== '') {
            searchBox.classList.add('active');
        }
    }


    /* =====================================================
       MOVE SEARCH & BACK ARROW ON SCROLL
    ===================================================== */
    const banner        = document.querySelector('.banner');
    const searchWrapper = document.getElementById('searchWrapper');
    const tableSlot     = document.querySelector('.table-search-slot');
    const arrow         = document.getElementById('backArrow');
    const arrowSlot     = document.querySelector('.arrow-slot');

    let searchMoved = false;
    let arrowMoved  = false;

    if (banner && searchWrapper && tableSlot && arrow && arrowSlot) {
        window.addEventListener('scroll', function () {
            const bannerBottom = banner.getBoundingClientRect().bottom;

            if (bannerBottom <= 0) {

                if (!searchMoved) {
                    tableSlot.appendChild(searchWrapper);
                    searchWrapper.classList.remove('position-absolute','top-0','end-0','m-3');
                    searchMoved = true;
                }

                if (!arrowMoved) {
                    arrowSlot.appendChild(arrow);
                    arrow.classList.remove('position-absolute','top-0','start-0','m-3');
                    arrowMoved = true;
                }

            } else {

                if (searchMoved) {
                    banner.appendChild(searchWrapper);
                    searchWrapper.classList.add('position-absolute','top-0','end-0','m-3');
                    searchMoved = false;
                }

                if (arrowMoved) {
                    banner.appendChild(arrow);
                    arrow.classList.add('position-absolute','top-0','start-0','m-3');
                    arrowMoved = false;
                }
            }
        });
    }


    /* =====================================================
       AUTO SUBMIT SEARCH (DEBOUNCE)
    ===================================================== */
    let typingTimer;
    const typingDelay = 500;
    const searchField = document.getElementById('search');
    const searchForm  = document.getElementById('searchForm');

    if (searchField && searchForm) {
        searchField.addEventListener('keyup', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => searchForm.submit(), typingDelay);
        });

        searchField.addEventListener('keydown', () => {
            clearTimeout(typingTimer);
        });
    }


    /* =====================================================
       CHECKOUT INIT
    ===================================================== */
    updateCheckoutUI();
});


/* =====================================================
   CART GLOBAL STATE
===================================================== */
let cartQty   = <?= (int)$cartQtyTotal ?>;
let cartTotal = <?= (int)$cartPriceTotal ?>;


/* =====================================================
   CART CLICK HANDLER
===================================================== */
document.addEventListener('click', function (e) {

    /* ===== ADD ===== */
    if (e.target.closest('.add-btn')) {
        const btn     = e.target.closest('.add-btn');
        const wrap    = btn.closest('.order-action');
        const box     = wrap.querySelector('.qty-box');

        const itemId = box.dataset.itemId;
        const price  = parseInt(wrap.dataset.price);

        btn.classList.add('d-none');
        box.classList.remove('d-none');
        box.querySelector('.qty-value').innerText = 1;
        box.querySelector('.plus').classList.remove('disabled');

        cartQty++;
        cartTotal += price;

        updateCheckoutUI();
        updateCart(itemId, 1, price);
    }

    /* ===== PLUS ===== */
    if (e.target.closest('.qty-btn.plus')) {
        const plus = e.target.closest('.qty-btn.plus');
        if (plus.classList.contains('disabled')) return;

        const box   = plus.closest('.qty-box');
        const wrap  = plus.closest('.order-action');
        const qtyEl = box.querySelector('.qty-value');

        const itemId    = box.dataset.itemId;
        const price     = parseInt(wrap.dataset.price);
        const stock     = parseInt(box.dataset.stock);
        const needStock = box.dataset.needStock == "1";

        let qty = parseInt(qtyEl.innerText) + 1;
        if (needStock && qty > stock) {
            Swal.fire({
                title: 'Notification!',
                text: 'Sorry, the requested quantity exceeds the available stock',
                icon: 'warning',
                confirmButtonColor: "<?= $cn->color ?>",
                confirmButtonText: 'OK'
            })
            return;
        }

        qtyEl.innerText = qty;
        if (needStock && qty >= stock) plus.classList.add('disabled');

        cartQty++;
        cartTotal += price;

        updateCheckoutUI();
        updateCart(itemId, qty, price);
    }

    /* ===== MINUS ===== */
    if (e.target.closest('.qty-btn.minus')) {
        const minus = e.target.closest('.qty-btn.minus');
        const box   = minus.closest('.qty-box');
        const wrap  = minus.closest('.order-action');
        const qtyEl = box.querySelector('.qty-value');

        const itemId = box.dataset.itemId;
        const price  = parseInt(wrap.dataset.price);

        let qty = parseInt(qtyEl.innerText) - 1;

        cartQty--;
        cartTotal -= price;

        if (qty <= 0) {
            box.classList.add('d-none');
            wrap.querySelector('.add-btn').classList.remove('d-none');
            updateCheckoutUI();
            updateCart(itemId, 0, price);
            return;
        }

        qtyEl.innerText = qty;
        box.querySelector('.plus').classList.remove('disabled');

        updateCheckoutUI();
        updateCart(itemId, qty, price);
    }
});


/* =====================================================
   CHECKOUT UI
===================================================== */
function updateCheckoutUI() {
    const bar   = document.getElementById('checkoutBar');
    const total = document.getElementById('checkoutTotal');
    const qty   = document.getElementById('checkoutQty');

    if (!bar) return;

    if (cartQty <= 0) {
        bar.classList.remove('show');
        setTimeout(() => bar.classList.add('d-none'), 300);
        return;
    }

    bar.classList.remove('d-none');
    setTimeout(() => bar.classList.add('show'), 10);

    total.innerText = 'Rp ' + cartTotal.toLocaleString('id-ID');
    qty.innerText   = cartQty;
}


/* =====================================================
   AJAX CART
===================================================== */
function updateCart(itemId, qty, price) {
    fetch('<?= base_url("index.php/ordermakanan/cart_action") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `item_id=${itemId}&qty=${qty}&price=${price}`
    }).catch(console.error);
}
</script>







<?php $this->load->view('template/footer') ?>
