
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Payment</title>

<style>
body {
    margin: 0;
    background: #f5f5f5;
    font-family: -apple-system, BlinkMacSystemFont, sans-serif;
}

/* HEADER */
.header {
    background: #fff;
    padding: 12px;
    display: flex;
    align-items: center;
    font-weight: 600;
    border-bottom: 1px solid #eee;
}

.header a {
    text-decoration: none;
    font-size: 20px;
    margin-right: 10px;
    color: #333;
}

/* CONTAINER */
.container {
    padding: 12px;
    padding-bottom: 100px;
}

/* CARD */
.card {
    background: #fff;
    border-radius: 14px;
    padding: 14px;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
}

/* TOTAL */
.total-label {
    font-size: 13px;
    color: #666;
}

.total-amount {
    font-size: 15px;
    font-weight: 100;
    color: <?= $cn->color ?>;
    float: right;
}

/* COUNTDOWN */
.countdown {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    margin-top: 10px;
}

.countdown span {
    color: red;
    font-weight: 600;
}

/* BANK */
.bank-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.bank-icon {
    width: 28px;
    height: 28px;
    background: #005BAC;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
}

.bank-name {
    font-weight: 600;
}

/* VA */
.va-box {
    background: #fafafa;
    padding: 10px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.va-number {
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 1px;
}

.copy-btn {
    font-size: 13px;
    color: #1a73e8;
    cursor: pointer;
}

/* GUIDE */
.guide-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.guide-list {
    padding-left: 16px;
    font-size: 13px;
    color: #555;
}

.guide-list li {
    margin-bottom: 6px;
}

/* FOOTER */
.footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background: #fff;
    border-top: 1px solid #eee;
    padding: 12px;
}

.btn-ok {
    width: 95%;
    background: <?= $cn->color ?>;
    border: none;
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    padding: 14px;
    border-radius: 10px;
}
.loadingkonek {
        width: 50px;
        height: 50px;
        border: solid 5px #ccc;
        border-top-color: <?= $cn->color ?>;
        border-radius: 100%;

        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        z-index: 7;

        animation: putar 1s linear infinite;
    }

    .textloading {
        text-align: center;
        color: <?= $cn->color ?>;
        position: fixed;
        font-weight: bold;
        font-size: 25px;
        z-index: 7; /* Pastikan lebih tinggi dari .loadingkonek */
        left: 50%; /* Pusatkan secara horizontal */
        top: calc(50% + 35px); /* Jarak dari loadingkonek, sesuaikan jika perlu */
        transform: translateX(-50%); /* Pusatkan elemen secara horizontal */
    }
    .load{
        background: rgba(0,0,0,0.7);
        height: 100vh;
        width: 100%;
        position: fixed;
        z-index: 6;
      }

       #loading{
        width: 60px;
        height: 60px;
        border: solid 5px #ccc;
        border-top-color: <?= $cn->color ?>;
        border-radius: 100%;

        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        z-index: 7;

        animation: putar 1s linear infinite;
      }

    @keyframes putar{
        from{transform: rotate(0deg);}
        to{transform: rotate(360deg);}
    }

      .preloader{
        background: rgba(0,0,0,0.7);
        height: 100%;
        width: 100%;
        position: fixed;
        z-index: 5;
      }
</style>
</head>
<p id="textloading" hidden="">Reconnect to the network</p>
<div id="preloader"></div>
<div id="loadingkonek"></div>
<body>

<!-- HEADER -->
<div class="header">
    <h2 style="margin: 0; margin-left: 5px;"><strong>Payment</strong></h2>
</div>

<div class="container">

    <!-- TOTAL -->
    <div class="card">
        <div class="total-label">Total Payment
            <div class="total-amount">
                Rp <?= number_format($va->amount) ?>
            </div>
        </div>
        
        <hr>
        <div class="countdown">
            <div>Pay within</div>
            <span id="timer" data-expired="<?= $va->expired_at ?>"></span>
        </div>
    </div>

    <!-- BANK -->
    <div class="card">
        <div class="bank-row">
            <div class="bank-icon"><?= substr($va->bank,0,3) ?></div>
            <div class="bank-name">Bank <?= $va->bank ?></div>
        </div>
        <hr>
        <div class="va-box">
            <div>
                <div style="font-size:12px;color:#666">Virtual Account Number</div>
                <div class="va-number" id="vaNumber"><?= $va->va_number ?></div>
            </div>
            <div class="copy-btn" onclick="copyVA()">Copy</div>
        </div>
    </div>

    <!-- GUIDE -->
    <div class="card">
        <div class="guide-title">Transfer Instructions</div>
        <ol class="guide-list">
            <li>Select transfer to Virtual Account (VA).</li>
            <li>Enter VA number <strong><?= $va->va_number ?></strong>.</li>
            <li>Check the payment details carefully.</li>
            <li>Confirm and complete the payment.</li>
        </ol>
    </div>

</div>
<form id="formOrder" method="post" action="<?= base_url('index.php/cart/order/'.$nomeja.'/PaymentNow') ?>">
<input type="hidden" name="payment_card_bank" value="<?= $va->bank ?>">
<?php foreach ($item as $i): ?>
    <input type="hidden" name="nama[]" value="<?= $i->description ?>">
    <input type="hidden" name="qty[]" id="qty<?= $i->id?>" value="<?= $i->qty ?>">
    <input type="hidden" name="cek[]" value="<?= $i->as_take_away ?>">
    <input type="hidden" name="qta[]" value="<?= $i->qty_take_away ?>">
    <input type="hidden" name="harga[]" value="<?= $i->unit_price ?>">
    <input type="hidden" name="is_paket[]" value="<?= $i->is_paket ?>">
    <!-- <input type="hidden" name="options[]" value="<?= $i->od ?>"> -->
    <input type="hidden" name="pesan[]" value="<?= $i->extra_notes?>">
    <input type="hidden" name="no[]" id="item_code<?= $i->id ?>" value="<?= $i->item_code ?>" class="form-control item_code<?= $i->id ?>">
    <input type="hidden" name="need_stock[]" id="need_stock" value="<?= $i->need_stock ?>" class="form-control need_stock">
<?php endforeach ?>
</form>

<!-- FOOTER -->
<!-- <div class="footer">
    <button class="btn-ok" onclick="location.href='<?= base_url() ?>'">
        OK
    </button>
</div> -->
<?php $this->load->view('template/footer') ?>
<script>
function copyVA() {
    const text = document.getElementById("vaNumber").innerText;
    navigator.clipboard.writeText(text);
    alert("Virtual Account number copied");
}
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const timerEl = document.getElementById("timer");
    const expiredAt = timerEl.getAttribute("data-expired");

    // Convert expired time to timestamp
    const expiredTime = new Date(expiredAt.replace(' ', 'T')).getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        let diff = expiredTime - now;

        if (diff <= 0) {
            timerEl.innerHTML = "Expired";
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        diff %= (1000 * 60 * 60);

        const minutes = Math.floor(diff / (1000 * 60));
        diff %= (1000 * 60);

        const seconds = Math.floor(diff / 1000);

        timerEl.innerHTML =
            hours + " hours " +
            minutes + " minutes " +
            seconds + " seconds";
    }

    // Initial call
    updateCountdown();

    // Update every second
    setInterval(updateCountdown, 1000);
});
</script>
<script>
let cekVAInterval = setInterval(function () {
    fetch("<?= base_url('index.php/cart/cek_status_va_local/'.$va->external_id) ?>")
        .then(res => res.json())
        .then(res => {

            console.log(res.payment_status);

            if (res.payment_status === 'PAID') {

                // STOP CEK LAGI
                clearInterval(cekVAInterval);

                Swal.fire({
                    title: 'Payment Successful!',
                    icon: 'success',
                    confirmButtonColor: "<?= $cn->color ?>",
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('formOrder').submit();
                    }
                });
            }

            if (res.payment_status === 'EXPIRED') {

                // STOP CEK LAGI
                clearInterval(cekVAInterval);

                Swal.fire({
                    title: 'Payment Expired',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            }

        })
        .catch(err => {
            console.error(err);
        });
}, 5000);
</script>



</body>
</html>
