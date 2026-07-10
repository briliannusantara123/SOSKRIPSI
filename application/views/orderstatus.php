<?php $this->load->view('template/headmenu') ?>
<style>
    body {
        background-color: #f3f3f3;
        font-family: 'Arial', sans-serif;
        margin: 0;
    }

    header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: linear-gradient(to right, <?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);
        padding: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 4;
    }

    header div {
        display: flex;
        align-items: center;
    }

    header h2 {
        margin-left: 5px;
        font-size: 24px;
        color: white;
    }

    .main-header {
        position: fixed; /* Mengubah posisi menjadi fixed */
        top: 60px; /* Menyesuaikan jarak dari atas, sesuaikan dengan tinggi header */
        left: 0;
        width: 100vw;
        height: 144px;
        background-color: <?= $ch->color ?>;
        padding-left: 20px;
        z-index: 3; /* Z-index lebih tinggi untuk menampilkan di atas bulat */
    }

    .main-header h2 {
        color: white;
        font-size: 32px;
        margin-top: 30px;
        z-index: 4; /* Menjaga z-index lebih tinggi agar tetap di atas bulat */
        position: relative; /* Agar z-index berfungsi dengan baik */
        margin-bottom: 1px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
    }

    .main-header label {
        color: white;
        font-size: 20px;
        z-index: 4; /* Menjaga z-index lebih tinggi agar tetap di atas bulat */
        position: relative; /* Agar z-index berfungsi dengan baik */
    }


    .main {
        position: fixed;
        top: 178px;
        left: 0;
        width: 100vw;
        height: 180px;
        background-color: <?= $ch->color ?>;
        border-bottom-left-radius: 25px;
        border-bottom-right-radius: 25px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        padding-left: 20px;
        padding-right: 20px;
        z-index: 2;
    }
    .main h2 {
        color: white;
        font-size: 22px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
    }
    .main .kanan {
        text-align: right;
    }

    .main-content {
        position: relative;
        top: 60px; /* Menyesuaikan jarak dari atas, sesuaikan dengan tinggi header */
        left: 0;
        width: 100vw;
        height: calc(100% - 195px); 
        border-bottom-left-radius: 25px;
        border-bottom-right-radius: 25px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        padding-left: 20px;
        padding-right: 20px;
        z-index: 3;
        overflow-y: auto; /* Menambahkan scroll jika konten lebih tinggi */
    }

    .main-content .row {
        background-color: #f4f4f4;
        border-radius: 10px;
        width: 100%;
        /* Remove the fixed height */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); 
        margin-bottom: 10px;
        margin-left: 2px;
        /* Let the height adjust automatically based on the content */
        padding: 10px;  /* Optional: Add padding to give some spacing inside the box */
    }


    .main-content .btn {
        margin-top: 20px;
        background-color: #f4f4f4;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        border-radius: 20px;
        display: flex;
        justify-content: center;
        align-items: center; 
        margin-right: 15px;
        height: 170px;
        box-sizing: border-box;
        margin-bottom: 20px;
        color: grey;
    }

    .main-content img {
        width: 100%; 
        height: auto; 
        max-width: 100%; 
        display: block; 
        border-radius: 10px;
        padding-top: 10px;
    }

    .main-content .qty {
        background-color: red;
        font-size: 25px;
        padding: 5px 10px 5px 10px;
        border-radius: 50%;
        color: white;
        margin-top: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
    }

    .main-content .bill {
        background: linear-gradient(to right, <?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);
        position: relative; /* Agar elemen mengikuti alur dokumen */
        z-index: 2; /* Tambahkan z-index agar elemen terlihat jika ada elemen dengan z-index tinggi */
        width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        padding-left: 20px;
        padding-right: 20px;
        margin-top: 20px;
        overflow-y: auto;
        background-color: white; /* Pastikan ada background agar terlihat */
    }

    .main-content .bill h2 {
        padding-top: 10px;
        text-align: center;color: white;
        font-weight: bold;
    }


    .cart-link {
        position: relative;
    }

    .cart-link i {
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        background-color: <?= $ch->color ?>;
        color: white;
        border-radius: 50%;
        padding: 17px;
        z-index: 10;
    }

    .badge {
        position: absolute;
        top: -10px;
        right: -10px;
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 5px;
        font-size: 12px;
    }

    .profile-img {
        width: 70%;
        margin-top: 45px;
        border: 200px solid black; /* Menambahkan border hijau dengan ketebalan 2px */
        border-radius: 10px; /* Menambahkan border-radius jika Anda ingin sudut yang melengkung */
        padding: 5px; /* Opsional: menambahkan padding di dalam border */
    }

    .profile-img-dua {
        width: 70%;margin-top: 35px;
    }
    .bulat {
        background-color: <?= $cb->color ?>;
        width: 360px;
        height: 200px;
        border-radius: 50%;
        transform: translate(-40%, -100%);
        z-index: 3; /* Z-index lebih rendah dari main-header */
        position: absolute; /* Menjadikan elemen bulat dengan posisi absolut */
    }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: white;
        border-top-right-radius: 10px;
        border-top-left-radius: 10px;
        box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.2);
        z-index: 9999;
    }

    footer nav {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    footer nav a {
        padding-top: 10px;
        text-align: center;
        color: grey;
        font-size: 12px; /* Small text size */
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
    }

    footer nav a i {
        font-size: 24px; /* Adjust icon size as needed */
    }

    footer nav a span {
        margin-top: 4px; /* Space between icon and text */
        font-size: 10px; /* Small text size */
    }
    span {
        color: grey;
        font-size: 20px;
    }
    h5 {
        color: grey;
    }
    .qty {
        display: inline-block;
        animation: bounce 1s infinite;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px); /* Mengatur seberapa tinggi loncat */
        }
    }
    @keyframes slideDown {
        0% {
            transform: translateY(100%) scale(0.5);
            opacity: 0;
        }
        100% {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    @keyframes slideUp {
        0% {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
        100% {
            transform: translateY(100%) scale(0.5);
            opacity: 0;
        }
    }

    .addorder {
        display: none;
        opacity: 0;
        transform: translateY(100%) scale(0.5);
        transition: transform 0.4s ease, opacity 0.4s ease;
    }

    .addorder.show {
        display: inline-block;
        animation: slideDown 0.4s forwards;
    }

    .addorder.hide {
        animation: slideUp 0.4s forwards;
        pointer-events: none;
    }
    .circle1 {
        bottom: 150px;
        z-index: 5;
    }
    .circle2 {
        bottom: 100px;
        z-index: 5;
    }
</style>
</head>
<body>
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
    <header>
        <div>
            <a href="<?= base_url() ?>index.php/selforder/home/<?= $nomeja ?>" style="text-decoration: none; color: black;">
                    <i class="bi bi-arrow-left" style="font-size: 30px;margin-left: 10px; text-shadow: 1px 1px 2px black;color: white"></i>
                </a>
                <h2 style="margin: 0; margin-left: 5px;"><strong>Order Status</strong></h2>
        </div>
    </header>
    
    <div class="main-header">

    </div>
    <div class="main">
        <hr style="color: <?= $cn->darkcolor ?>">
    </div>
    
    <div class="main-content">
        <div class="bill"><h2>Waiting Payment</h2></div>
        <?php foreach ($order_waiting as $i): ?>
            <div class="row">
                <div class="col-3">
                    <?php if ($i->image_path): ?>
                        <img src="<?= $i->image_path ?>">
                    <?php else: ?>
                        <img src="<?= $logo->image_path ?>">
                    <?php endif ?>
                </div>
                <div class="col-8">
                    <span class="description"><?= $i->description ?> <?php if ($i->extra_notes): ?>(<?= trim($i->extra_notes) ?>)<?php endif; ?></span><br>
                    <?php  $disc = intval($i->disc) / 100; $hasil = ($i->unit_price * $disc) ?>
                    <?php if ($disc != 0 ): ?>
                         <span class="unit-price">Rp <?= number_format($hasil) ?></span>
                    <?php else: ?>
                        <span class="unit-price">Rp <?= number_format($i->unit_price) ?></span>
                    <?php endif ?>
                    
                </div>
                <div class="col-1">
                    <span class="qty"><?= $i->qty ?></span>
                </div>
            </div>
        <?php endforeach; ?> 
        <div class="bill"><h2>IN PROGRESS</h2></div>
        <?php foreach ($order_proses as $i): ?>
            <div class="row">
                <div class="col-3">
                    <?php if ($i->image_path): ?>
                        <img src="<?= $i->image_path ?>">
                    <?php else: ?>
                        <img src="<?= $logo->image_path ?>">
                    <?php endif ?>
                </div>
                <div class="col-8">
                    <span class="description"><?= $i->description ?> <?php if ($i->extra_notes): ?>(<?= trim($i->extra_notes) ?>)<?php endif; ?></span><br>
                    <?php  $disc = intval($i->disc) / 100; $hasil = ($i->unit_price * $disc) ?>
                    <?php if ($disc != 0 ): ?>
                         <span class="unit-price">Rp <?= number_format($hasil) ?></span>
                    <?php else: ?>
                        <span class="unit-price">Rp <?= number_format($i->unit_price) ?></span>
                    <?php endif ?>
                    
                </div>
                <div class="col-1">
                    <span class="qty"><?= $i->qty ?></span>
                </div>
            </div>
        <?php endforeach; ?> 

        <div class="bill"><h2>DELIVERED</h2></div>
        <?php foreach ($order_deliver as $i): ?>
            <div class="row">
                <div class="col-3">
                    <?php if ($i->image_path): ?>
                        <img src="<?= $i->image_path ?>">
                    <?php else: ?>
                        <img src="<?= $logo->image_path ?>">
                    <?php endif ?>
                </div>
                <div class="col-8">
                    <span class="description"><?= $i->description ?> <?php if ($i->extra_notes): ?>(<?= trim($i->extra_notes) ?>)<?php endif; ?></span><br>
                    <?php  $disc = intval($i->disc) / 100; $hasil = ($i->unit_price * $disc) ?>
                    <?php if ($disc != 0 ): ?>
                         <span class="unit-price">Rp <?= number_format($hasil) ?></span>
                    <?php else: ?>
                        <span class="unit-price">Rp <?= number_format($i->unit_price) ?></span>
                    <?php endif ?>
                    
                </div>
                <div class="col-1">
                    <span class="qty"><?= $i->qty ?></span>
                </div>
            </div>
        <?php endforeach; ?> 

        <div class="bill"><h2>COMPLETED</h2></div>
        <?php foreach ($order_complete as $i): ?>
            <div class="row">
                <div class="col-3">
                    <?php if ($i->image_path): ?>
                        <img src="<?= $i->image_path ?>">
                    <?php else: ?>
                        <img src="<?= $logo->image_path ?>">
                    <?php endif ?>
                </div>
                <div class="col-8">
                    <span class="description"><?= $i->description ?> <?php if ($i->extra_notes): ?>(<?= trim($i->extra_notes) ?>)<?php endif; ?></span><br>
                    <?php  $disc = intval($i->disc) / 100; $hasil = ($i->unit_price * $disc) ?>
                    <?php if ($disc != 0 ): ?>
                         <span class="unit-price">Rp <?= number_format($hasil) ?></span>
                    <?php else: ?>
                        <span class="unit-price">Rp <?= number_format($i->unit_price) ?></span>
                    <?php endif ?>
                    
                </div>
                <div class="col-1">
                    <span class="qty"><?= $i->qty ?></span>
                </div>
            </div>
        <?php endforeach; ?>

         
        
        <div style="margin-bottom: 110px;"></div>

    </div>

    <footer>
        <div class="containerfooter">
            <div class="container addorder" style="display: none;">
                    <div class="row">
                        <?php foreach ($icon as $i): ?>
                            <div class="col-6">
                                <?php if ($i->link_type == 'makanan'): ?>
                                    <a href="<?= base_url() ?><?= $i->link ?><?= str_replace(" ","%20", $sca) ?>#<?= str_replace(" ","_", $sca) ?>" class="btn" style="display: flex; flex-direction: column; align-items: center; padding: 5px 10px;background-color: <?= $cn->color ?>">

                                <?php else: ?>
                                    <a href="<?= base_url() ?><?= $i->link ?><?= str_replace(" ","%20", $scm) ?>#<?= str_replace(" ","_", $scm) ?>" class="btn" style="display: flex; flex-direction: column; align-items: center; padding: 5px 10px;background-color: <?= $cn->color ?>">
                                <?php endif ?>
                                    <img src="<?= $i->image_path ?>" style="width: 80px; height: 90px; border-radius: 50%;" alt="Hachi Grill" class="image" />
                                    <span style="margin-top: 5px; margin-bottom: 5px; font-size: 16px; font-weight: bold; color: white;"><?= $i->title ?></span>
                                </a>
                            </div>

                        <?php endforeach ?>
                    </div>  
            </div>
            <nav>
                <?php foreach ($iconfooter as $i): ?>
                    <?php if ($i->link_type == 'status'): ?>
                        <a href="<?= base_url() ?><?= $i->link ?>">
                            <img src="<?= $i->image_path ?>" style="width: 25px;height: 25px; filter: grayscale(100%);">
                    <?php else: ?>
                        <a href="<?= base_url() ?><?= $i->link ?><?= $nomeja ?>/Home/">
                            <img src="<?= $i->image_path ?>" style="width: 25px;height: 25px; filter: grayscale(100%);">
                        <?php if ($i->title == 'Cart'): ?>
                           <span class="badge" style="position: absolute; top: -10px; right: -10px; background-color: red; color: white; border-radius: 50%; padding: 5px; font-size: 12px;"><?= $total_qty;?></span>
                        <?php endif ?>
                    <?php endif ?>
                    
                    </i>

                        <span><?= $i->title ?></span>
                    </a>
                <?php endforeach ?>
                <a href="#" class="btn-add" >
                    <i class="fas fa-plus-circle" >
                    </i>
                    <span class="textmuncul" style="position: relative; top: 12px;font-size: 17px;color: <?= $cn->color ?>;display: none;">Add Order</span>
                    <span class="texttutup">Add Order</span>
                </a>
            </nav>
        </div>
    </footer>
<?php $this->load->view('template/footer') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addButton = document.querySelector('.btn-add');
        const addOrderContainer = document.querySelector('.addorder');
        const textmuncul = document.querySelector('.textmuncul');
        const texttutup = document.querySelector('.texttutup');
        // Buat variabel untuk melacak status klik
        let isToggled = false;

        // Tambahkan event listener untuk klik tombol
        addButton.addEventListener('click', function () {
            if (isToggled) {
                addButton.classList.remove('cart-link');
                addOrderContainer.classList.remove('show');
                addOrderContainer.classList.add('hide');
                textmuncul.style.display = 'none';
                texttutup.style.display = 'inline-block';
                // Tunggu animasi selesai, lalu sembunyikan elemen
                setTimeout(() => {
                    addOrderContainer.style.display = 'none';
                }, 400); // 400ms sesuai dengan durasi animasi CSS
            } else {
                addButton.classList.add('cart-link');
                textmuncul.style.display = 'inline-block';
                texttutup.style.display = 'none';
                addOrderContainer.style.display = 'inline-block';
                addOrderContainer.classList.remove('hide');
                addOrderContainer.classList.add('show');
            }
            
            // Toggle status
            isToggled = !isToggled;
        });
    });
</script>

