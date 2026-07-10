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
        position: relative;
        z-index: 1050;
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
        display: inline-block; /* 🔥 penting */
    }

    /* ACTIVE TEXT */
    .category-tabs .nav-link.active {
        color: <?= $cn->color ?>;
        font-weight: 800;
    }

    /* DEFAULT (GARIS HILANG) */
    .category-tabs .nav-link::after {
        content: "";
        position: absolute;
        bottom: -5px; /* jarak dari text */
        left: 50%;
        transform: translateX(-50%);
        width: 0; /* 🔥 awalnya tidak terlihat */
        height: 3px;
        background: <?= $cn->color ?>;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    /* SAAT ACTIVE */
    .category-tabs .nav-link.active::after {
        width: 100%; /* 🔥 garis full sesuai text */
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
        z-index: 2000;
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
    .table-card {
        position: relative;
    }

    .table-search-slot {
        position: absolute;
        top: -20px;     /* atur naik turun */
        right: -15px;  /* ⬅️ ini bikin ke kanan */
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
    #searchWrapper {
        transition: all 0.3s ease;
    }
    /* ===== FLOATING AI BUTTON ===== */
    .ai-float-btn {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(<?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);
        box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        z-index: 1100;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* hover effect */
    .ai-float-btn:hover {
        transform: scale(1.1);
    }

    /* saat checkout muncul → naik dikit */
    .checkout-bar.show ~ .ai-float-btn {
        bottom: 120px;
    }
    .ai-float-btn img {
        width: 35px;
        height: 35px;
        object-fit: contain;
        display: block;
    }
    .ai-float-img {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 100px;
        height: 130px;
        cursor: pointer;
        z-index: 1100;

        transition: transform 0.3s ease, filter 0.3s ease;
        animation: floatAI 3s ease-in-out infinite, glowAI 2.5s ease-in-out infinite;
    }
    @keyframes floatAI {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
        100% {
            transform: translateY(0px);
        }
    }
    @keyframes glowAI {
        0% {
            filter: drop-shadow(0 0 0px rgba(0,0,0,0.2));
        }
        50% {
            filter: drop-shadow(0 6px 10px rgba(0,0,0,0.35));
        }
        100% {
            filter: drop-shadow(0 0 0px rgba(0,0,0,0.2));
        }
    }
    .ai-float-img:hover {
        transform: scale(1.1) translateY(-5px);
    }
    /* ===== AI CHAT POPUP ===== */
    .ai-chat-popup {
        position: fixed;
        bottom: 220px;
        right: 20px;
        width: 380px;
        height: 480px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        z-index: 1200;

        opacity: 0;
        transform: translateY(30px);
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .ai-chat-popup.show {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    /* HEADER */
    .ai-chat-header {
        background: <?= $cn->color ?>;
        color: #fff;
        padding: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ai-chat-header button {
        background: transparent;
        border: none;
        color: #fff;
        font-size: 20px;
    }

    /* BODY */
    .ai-chat-body {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
        background: #f7f7f7;
    }

    /* MESSAGE */
    .ai-message {
        padding: 8px 12px;
        border-radius: 12px;
        margin-bottom: 8px;
        max-width: 75%;
        font-size: 13px;
    }

    .ai-message.bot {
        background: #e4e6eb;
    }

    .ai-message.user {
        background: <?= $cn->color ?>;
        color: #fff;
        margin-left: auto;
    }

    /* INPUT */
    .ai-chat-input {
        display: flex;
        border-top: 1px solid #ddd;
    }

    .ai-chat-input input {
        flex: 1;
        border: none;
        padding: 10px;
    }

    .ai-chat-input button {
        border: none;
        background: <?= $cn->color ?>;
        color: #fff;
        padding: 0 15px;
    }
    /* ===== AI MENU CARD ===== */
    .ai-menu-wrapper {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 5px;
    }

    /* CARD */
    .ai-menu-card {
        width: 110px;
        min-width: 110px;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .ai-menu-card:active {
        transform: scale(0.95);
    }

    /* INNER */
    .ai-card-inner {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* IMAGE */
    .ai-menu-img {
        width: 100%;
        height: 80px;
        object-fit: cover;
    }

    /* CONTENT */
    .ai-menu-content {
        padding: 6px;
        text-align: center;
    }

    /* TITLE */
    .ai-menu-title {
        font-size: 12px;
        font-weight: 600;
        line-height: 1.2;

        display: -webkit-box;
        -webkit-line-clamp: 2;   /* max 2 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* PRICE */
    .ai-menu-price {
        font-size: 12px;
        font-weight: bold;
        color: <?= $cn->color ?>;
        margin-top: 3px;
    }
</style>
</head>

<body>

<!-- HEADER -->
<div class="banner position-relative">

    <!-- PANAH KIRI -->
    <!-- <?= base_url() ?>index.php/selforder/home/<?= $nomeja ?> -->
    <a href="#"
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
            <form id="searchForm" action="<?= base_url() ?>index.php/ordermakanan/search/<?= $nomeja ?>" method="POST">
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
            <h6 class="mb-1">Hachi Garden <br> Ampera</h6>
            <small style="text-decoration: none;"><?= $username ?> - <?= $no_telp ?></small>
        </div>

        <!-- Tombol di kanan (dibuat column) -->
        <div class="ms-auto d-flex flex-column gap-1">
            <a href="<?= base_url() ?>index.php/Review/form/<?= $nomeja ?>/<?= $s ?>" 
               class="btn btn-sm btn-outline-custom">
                Feedback & Suggestions
            </a>

            <a href="<?= base_url() ?>index.php/Billsementara/home/<?= $nomeja ?>/<?= $s ?>" 
               class="btn btn-sm btn-outline-custom">
                View Bill
            </a>
        </div>

    </div>

        <div class="card bg-warning bg-opacity-50 p-2 mb-2 shadow-sm table-card">
            <div class="table-row d-flex align-items-center">
                <strong class="table-no">Table No : <?= $nomeja ?></strong>

                <div class="table-search-slot"></div>
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
                                "index.php/ordermakanan/menu/Makanan/" .
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
                            "index.php/ordermakanan/menu/Makanan/" .
                            rawurlencode($i['sub_category'])
                        ) . "#" . $cat_id;
                    ?>
                    <li class="nav-item" style="margin-bottom: 10px;">
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

                <div class="col-6">
                    <div class="card menu-grid-card p-2">
                        <?php 
                            $img = '';

                            if (!empty($i->image_path)) {
                                if (strpos($i->image_path, 'assets') !== false) {
                                    $img = base_url($i->image_path);
                                } else {
                                    $img = $i->image_path;
                                }
                            } else {
                                $img = base_url($logo->image_path);
                            }
                            ?>

                            <?php if ($i->is_sold_out == 0): ?>
                                <img src="<?= $img ?>" class="menu-grid-img">
                            <?php else: ?>
                                <img src="<?= $img ?>" class="menu-grid-img grayscale">
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
                                      <a href="<?= base_url('index.php/ordermakanan/detailmenu/'.$i->id.'/'.str_replace(' ','%20',$i->sub_category)) ?>" class="btn btn-outline-custom btn-sm w-100 mt-2"style="border-radius: 50px;box-shadow: 0px 5px 5px #00000040;">Add</a>
                                    <?php else: ?>
                                        <?php
                                            $itemCode = $i->no;
                                            $cartQty  = $cartMap[$itemCode] ?? 0;
                                        ?>
                                          <div class="order-action mt-2"
                                         data-id="<?= $i->id ?>"
                                         data-price="<?= $harga_akhir ?>"
                                         data-notes="">

                                        <?php
                                            $itemCode = $i->no;
                                            $cartQty  = $cartMap[$itemCode] ?? 0;
                                        ?>

                                        <!-- ADD BUTTON -->
                                        <button type="button"
                                                class="btn btn-outline-custom btn-sm w-100 add-btn <?= $cartQty > 0 ? 'd-none' : '' ?>"
                                                style="border-radius:50px;box-shadow:0px 5px 5px #00000040;">
                                            Add
                                        </button>

                                        <!-- NOTES BUTTON -->
                                        <button type="button"
                                                class="btn btn-outline-custom btn-sm w-100 mt-1 note-btn <?= $cartQty > 0 ? '' : 'd-none' ?>"
                                                style="border-radius:50px;margin-bottom: 5px;box-shadow:0px 3px 3px #00000040;">
                                            Add Notes
                                        </button>

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

<a href="<?= base_url() ?>index.php/Cart/home/<?= $nomeja ?>/Makanan/<?= $s ?>"
   class="checkout-bar d-flex justify-content-between align-items-center d-none"
   id="checkoutBar"
   style="text-decoration:none;color:white;">

    <div>
        <div>Total</div>
        <strong id="checkoutTotal">Rp 0</strong>
    </div>

    <div>
        VIEW CART (<span id="checkoutQty">0</span>)
    </div>

</a>

<img src="<?= base_url('assets/ailogo.png'); ?>" alt="AI" id="aiButton" class="ai-float-img">
<div id="aiChatPopup" class="ai-chat-popup">

    <div class="ai-chat-header">
        <strong>Hachi Assistant</strong>
        <button id="closeAiChat">&times;</button>
    </div>

    <!-- CHAT BODY -->
    <div class="ai-chat-body" id="aiChatBody">
        <div class="ai-message bot">
            👋 Halo! Aku Hachi Assistant 🌿<br><br>

            Aku bisa bantu kamu:
            <ul>
                <li>🍽️ Rekomendasi menu terbaik</li>
                <li>🥗 Menu sesuai kategori</li>
                <li>🛒 Pemesanan cepat</li>
            </ul>

            Kategori tersedia: <b><?= implode(', ', array_column($sub, 'sub_category')); ?></b><br><br>

            👉 Mau aku rekomendasikan atau pilih kategori dulu? 😊
        </div>
    </div>

    <!-- INPUT -->
    <div class="ai-chat-input">
        <input type="text" id="aiInput" placeholder="Type your message...">
        <button id="sendAi">Kirim</button>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        updateCheckoutUI();
    });

    /* =========================
       GLOBAL CART
    ========================= */
    let cartQty   = <?= (int)$cartQtyTotal ?>;
    let cartTotal = <?= (int)$cartPriceTotal ?>;


    /* =========================
       CLICK HANDLER
    ========================= */
    document.addEventListener('click', function (e) {

        /* ===== ADD ===== */
        if (e.target.closest('.add-btn')) {
            const btn     = e.target.closest('.add-btn');
            const wrap    = btn.closest('.order-action');
            const box     = wrap.querySelector('.qty-box');
            const noteBtn = wrap.querySelector('.note-btn');

            const itemId = box.dataset.itemId;
            const price  = parseInt(wrap.dataset.price);

            btn.classList.add('d-none');
            box.classList.remove('d-none');
            if (noteBtn) noteBtn.classList.remove('d-none');

            box.querySelector('.qty-value').innerText = 1;
            box.querySelector('.plus').classList.remove('disabled');

            cartQty++;
            cartTotal += price;

            updateCheckoutUI();
            updateCart(itemId, 1, price, 'plus');
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
                    title: 'Stock Warning',
                    text: 'Stock tidak cukup',
                    icon: 'warning',
                    showConfirmButton: false,
                    timer: 1500
                });
                return;
            }

            qtyEl.innerText = qty;

            if (needStock && qty >= stock) {
                plus.classList.add('disabled');
            }

            cartQty++;
            cartTotal += price;

            updateCheckoutUI();
            updateCart(itemId, qty, price,'plus');
        }

        /* ===== MINUS ===== */
        if (e.target.closest('.qty-btn.minus')) {
            const minus = e.target.closest('.qty-btn.minus');
            const plus = e.target.closest('.qty-btn.plus');
            const box   = minus.closest('.qty-box');
            const wrap  = minus.closest('.order-action');
            const qtyEl = box.querySelector('.qty-value');
            const noteBtn = wrap.querySelector('.note-btn');

            const itemId = box.dataset.itemId;
            const price  = parseInt(wrap.dataset.price);

            let qty = parseInt(qtyEl.innerText) - 1;

            cartQty--;
            cartTotal -= price;

            if (qty <= 0) {
                box.classList.add('d-none');
                wrap.querySelector('.add-btn').classList.remove('d-none');
                if (noteBtn) noteBtn.classList.add('d-none');

                updateCheckoutUI();
                updateCart(itemId, 0, price,'minus');
                return;
            }

            qtyEl.innerText = qty;
            box.querySelector('.minus').classList.remove('disabled');
            box.querySelector('.plus').classList.remove('disabled');

            updateCheckoutUI();
            updateCart(itemId, qty, price,'minus');
        }

        /* ===== NOTES ===== */
        if (e.target.closest('.note-btn')) {
            const wrap   = e.target.closest('.order-action');
            const itemId = wrap.querySelector('.qty-box').dataset.itemId;

            // ambil notes lama dari attribute
            let existingNotes = wrap.dataset.notes || '';

            Swal.fire({
                title: 'Add Notes',
                input: 'textarea',
                inputValue: existingNotes, // ⬅️ isi default
                inputPlaceholder: 'Example: no ice less sugar',
                showCancelButton: true,
                confirmButtonText: 'Save',
                confirmButtonColor: "<?= $cn->color ?>",
                inputAttributes: {
                    maxlength: 200,
                    autocapitalize: 'off',
                    autocorrect: 'off',
                    style: 'resize:none;'
                },
                inputValidator: (value) => {
                    if (!value) return 'Notes cannot be empty';

                    if (value.length > 200) {
                        return 'Maximum 200 characters allowed';
                    }

                    if (!/^[a-zA-Z0-9 ]+$/.test(value)) {
                        return 'Only letters and numbers are allowed (no symbols)';
                    }

                    return null;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const notes = result.value;

                    fetch('<?= base_url("index.php/ordermakanan/save_notes") ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `item_id=${itemId}&notes=${encodeURIComponent(notes)}`
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.status) {

                            // ⬅️ SIMPAN KE DATA ATTRIBUTE (PENTING)
                            wrap.dataset.notes = notes;

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Notes have been successfully updated.',
                                showConfirmButton: false,
                                timer: 1500
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.message || 'Failed to save notes'
                            });
                        }
                    });
                }
            });
        }

    });


    /* =========================
       CHECKOUT UI
    ========================= */
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


    /* =========================
       AJAX CART
    ========================= */
    function updateCart(itemId, qty, price, type) {
    fetch('<?= base_url("index.php/ordermakanan/cart_action") ?>', {
        method: 'POST',
        credentials: 'include', // 🔥 WAJIB (INI FIX UTAMA)
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `item_id=${itemId}&qty=${qty}&price=${price}&type=${type}`
    })
    .then(res => res.text())
    .then(text => {
        try {
            const res = JSON.parse(text);
            if (res.force_logout) {
                window.location.href = res.redirect;
                return;
            }

            if (!res.status) {
                alert(res.msg || 'Terjadi kesalahan');
                return;
            }

            console.log(res);

        } catch (e) {
            console.error('Response bukan JSON:', text);
        }
    })
    .catch(console.error);
}
document.querySelector('.search-btn').addEventListener('click', function () {
    const box = document.querySelector('.search-box');
    const input = document.querySelector('.search-input');

    box.classList.toggle('active');

    if (box.classList.contains('active')) {
        input.focus();
    }
});
document.addEventListener('DOMContentLoaded', function () {

    const tabs = document.querySelectorAll('#categoryTabs .nav-link');

    function setActiveTab() {
        let hash = window.location.hash.replace('#', '');

        tabs.forEach(tab => {
            tab.classList.remove('active');

            if (tab.dataset.cat === hash) {
                tab.classList.add('active');
            }
        });

        // default ke pertama kalau tidak ada hash
        if (!hash && tabs.length > 0) {
            tabs[0].classList.add('active');
        }
    }

    function scrollToActiveTab() {
        const active = document.querySelector('#categoryTabs .nav-link.active');
        if (active) {
            active.scrollIntoView({
                behavior: 'smooth',
                inline: 'center',
                block: 'nearest'
            });
        }
    }

    // 🔥 LOAD AWAL
    setActiveTab();
    scrollToActiveTab();

    // 🔥 SAAT KLIK TAB
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            scrollToActiveTab(); // 🔥 auto scroll pas klik
        });
    });

});
</script>

<script>

    const aiBtn    = document.getElementById('aiButton');
    const popup    = document.getElementById('aiChatPopup');
    const closeBtn = document.getElementById('closeAiChat');
    const sendBtn  = document.getElementById('sendAi');
    const input    = document.getElementById('aiInput');
    const body     = document.getElementById('aiChatBody');

    /* =========================================================
       WEBSOCKET
    ========================================================= */
    let socket;

    function connectWebSocket() {

        socket = new WebSocket("ws://localhost:3001");

        socket.onopen = () => {

            console.log("✅ WebSocket Connected");

        };

        socket.onerror = (err) => {

            console.error("❌ WebSocket Error", err);

        };

        socket.onclose = () => {

            console.log("⚠️ WebSocket Disconnected");

            // auto reconnect
            setTimeout(connectWebSocket, 2000);

        };

        socket.onmessage = function(event) {

            const data = JSON.parse(event.data);

            console.log("📩 WS:", data);

            /* =========================================================
               TYPING
            ========================================================= */
            if (data.type === 'typing') {

                if (data.status) {

                    window.aiTypingBubble = appendMessage(
                        '🤖 mengetik...',
                        'bot'
                    );

                } else {

                    if (window.aiTypingBubble) {
                        window.aiTypingBubble.remove();
                        window.aiTypingBubble = null;
                    }

                }

            }

            /* =========================================================
               STREAM CHAT
            ========================================================= */
            if (data.type === 'stream') {

                if (!window.currentStreamBubble) {

                    window.currentStreamBubble = appendMessage('', 'bot');

                }

                window.currentStreamBubble.textContent += data.chunk;   

                body.scrollTop = body.scrollHeight;

            }

            /* =========================================================
               DONE
            ========================================================= */
            if (data.type === 'done') {

                window.currentStreamBubble = null;

                if (data.menus && data.menus.length > 0) {

                    renderMenuCards(data.menus);

                    appendMessage(
                        '👉 Ketuk gambar untuk menambahkan ke keranjang 🛒',
                        'bot'
                    );

                }

            }

            /* =========================================================
               ERROR
            ========================================================= */
            if (data.type === 'error') {

                appendMessage(
                    '⚠️ ' + data.message,
                    'bot'
                );

            }

        };

    }

    connectWebSocket();

    /* =========================================================
       OPEN CHAT
    ========================================================= */
    aiBtn.addEventListener('click', () => {
        popup.classList.toggle('show');
    });

    /* =========================================================
       CLOSE CHAT
    ========================================================= */
    closeBtn.addEventListener('click', () => {
        popup.classList.remove('show');
    });

    /* =========================================================
       SEND EVENT
    ========================================================= */
    sendBtn.addEventListener('click', sendMessage);

    input.addEventListener('keypress', function(e){

        if(e.key === 'Enter') {
            sendMessage();
        }

    });

    /* =========================================================
       SEND MESSAGE
    ========================================================= */
    function sendMessage() {

        const text = input.value.trim();

        if (!text) return;

        appendMessage(text, 'user');

        input.value = '';

        /* =========================================================
           CHECK WS
        ========================================================= */
        if (!socket || socket.readyState !== WebSocket.OPEN) {

            appendMessage(
                '⚠️ WebSocket belum terhubung',
                'bot'
            );

            return;

        }

        /* =========================================================
           SEND WS
        ========================================================= */
        socket.send(JSON.stringify({
            message: text,
            table_id: 1
        }));

    }

    /* =========================================================
       APPEND MESSAGE
    ========================================================= */
    function appendMessage(text, type) {

        const div = document.createElement('div');

        div.className = 'ai-message ' + type;

        div.innerText = text;

        body.appendChild(div);

        body.scrollTop = body.scrollHeight;

        return div;

    }

    /* =========================================================
       RENDER MENU CARDS
    ========================================================= */
    function renderMenuCards(menus) {

        const wrapper = document.createElement('div');

        wrapper.className = 'ai-menu-wrapper';

        menus.forEach(menu => {

            const card = document.createElement('div');

            card.className = 'ai-menu-card';

            card.innerHTML = `
                <img src="${menu.image}" class="ai-menu-img">

                <div class="ai-menu-content">

                    <div class="ai-menu-title">
                        ${menu.name}
                    </div>

                    <div class="ai-menu-price">
                        Rp ${Number(menu.price).toLocaleString('id-ID')}
                    </div>

                </div>
            `;

            /* =========================================================
               CLICK MENU
            ========================================================= */
            card.addEventListener('click', function () {

                addToCartFromAI(menu);

                const text = `+ Menambahkan ${menu.name} ke dalam keranjang`;

                appendMessage(text, 'user');

                setTimeout(() => {

                    const firstReply =
                        `🤖 Aku sudah menambahkan ${menu.name} ke keranjang 😊`;

                    appendMessage(firstReply, 'bot');

                    /* =========================================================
                       CHECK CART
                    ========================================================= */
                    checkCartAndReply(menu.name)
                    .then(nextReply => {

                        setTimeout(() => {

                            appendMessage(nextReply, 'bot');

                        }, 800);

                    });

                }, 400);

            });

            wrapper.appendChild(card);

        });

        body.appendChild(wrapper);

        body.scrollTop = body.scrollHeight;

    }

    /* =========================================================
       CHECK CART SUMMARY
    ========================================================= */
    function checkCartAndReply(menuName) {

        return fetch(
            '<?= base_url("index.php/ordermakanan/get_cart_summary") ?>',
            {
                method: 'GET',
                credentials: 'include'
            }
        )
        .then(res => {

            if (!res.ok) {
                throw new Error('Network error');
            }

            return res.json();

        })
        .then(cart => {

            console.log('CART DEBUG:', cart);

            const hasFood  = cart.has_food;
            const hasDrink = cart.has_drink;

            let reply1 = '';

            if (hasFood && hasDrink) {

                reply1 =
                `Ada lagi yang mau kamu tambahkan? 😊
👉 Ketik "makanan" / "minuman" / "rekomendasi"`;

            }
            else if (hasFood && !hasDrink) {

                reply1 =
                `Mau sekalian tambah minuman juga nggak? 🥤
👉 Ketik "minuman" ya`;

            }
            else if (!hasFood && hasDrink) {

                reply1 =
                `Mau sekalian tambah makanan juga nggak? 🍽️
👉 Ketik "makanan" ya`;

            }
            else {

                reply1 =
                `Mau aku rekomendasikan menu lainnya? 😋
👉 Ketik "rekomendasi" ya`;

            }

            return reply1;

        })
        .catch(err => {

            console.error('CHECK CART ERROR:', err);

            return 'Mau tambah menu lain juga? 😊';

        });

    }

    /* =========================================================
       ADD TO CART
    ========================================================= */
    function addToCartFromAI(menu) {

        fetch(
            '<?= base_url("index.php/ordermakanan/cart_action") ?>',
            {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body:
                    `item_id=${menu.id}` +
                    `&qty=1` +
                    `&price=${menu.price}` +
                    `&type=plus`
            }
        )
        .then(res => res.json())
        .then(res => {

            if (res.force_logout) {

                window.location.href = res.redirect;

                return;

            }

            if (!res.status) {

                Swal.fire(
                    'Error',
                    res.msg || 'Gagal tambah ke cart',
                    'error'
                );

                return;

            }

            /* =========================================================
               UPDATE UI
            ========================================================= */
            cartQty++;

            cartTotal += parseInt(menu.price);

            updateCheckoutUI();

            Swal.fire({
                icon: 'success',
                title: 'Added!',
                text: menu.name + ' added to cart',
                showConfirmButton: false,
                timer: 1200
            });

        })
        .catch(() => {

            Swal.fire(
                'Error',
                'Connection error',
                'error'
            );

        });

    }

    /* =========================================================
       SEARCH BAR SCROLL
    ========================================================= */
    document.addEventListener('DOMContentLoaded', function () {

        const searchWrapper =
            document.getElementById('searchWrapper');

        const tableSlot =
            document.querySelector('.table-search-slot');

        const banner =
            document.querySelector('.banner');

        window.addEventListener('scroll', function () {

            const bannerBottom =
                banner.getBoundingClientRect().bottom;

            if (bannerBottom <= 0) {

                /* =========================================================
                   PINDAH KE TABLE
                ========================================================= */
                if (!tableSlot.contains(searchWrapper)) {

                    tableSlot.appendChild(searchWrapper);

                    searchWrapper.style.position = 'static';

                    searchWrapper.style.margin = '0';

                }

            } else {

                /* =========================================================
                   BALIK KE BANNER
                ========================================================= */
                if (!banner.contains(searchWrapper)) {

                    banner.appendChild(searchWrapper);

                    searchWrapper.style.position = 'absolute';

                    searchWrapper.style.top = '0';

                    searchWrapper.style.right = '0';

                    searchWrapper.style.margin = '1rem';

                }

            }

        });

    });

</script>





<?php $this->load->view('template/footer') ?>
