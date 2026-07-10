<?php $this->load->view('template/headmenu') ?>
<style>
body, .main-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    background: linear-gradient(<?= $cn->lightcolor ?>, <?= $cn->color ?>, <?= $cn->darkcolor ?>);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.main {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: rgba(255,255,255,0.9);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

.logo img {
    width: 250px;
    height: auto;
    margin-bottom: 20px;
}

#loadinglanding {
    width: 80px;
    height: 80px;
    border: 8px solid #f3f3f3;
    border-top: 8px solid <?= $cn->color ?>;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

#status-text {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    text-align: center;
}

.food-animation {
    width: 60px;
    height: 60px;
    margin: 10px 0;
    background-size: contain;
    background-repeat: no-repeat;
    animation: float 1.5s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}
</style>

<div class="main-container">
    <div class="main">
        <div class="logo">
            <img src="<?= $logo->image_path ?>" alt="Dear Clio Restaurant" />
        </div>
        <div id="loadinglanding"></div>
        <div class="food-animation"></div>
        <div id="status-text">Your payment is being processed...</div>
        <input type="hidden" id="redirect" value="<?= $linkredirect ?>">
    </div>
</div>

<script>
    // Ambil jumlah reload dari sessionStorage
    let reloadCount = sessionStorage.getItem('reloadCount');

    if (!reloadCount) {
        reloadCount = 0;
    }

    reloadCount++;

    // Simpan kembali
    sessionStorage.setItem('reloadCount', reloadCount);

    if (reloadCount <= 10) {

        setTimeout(() => {
            window.location.reload();
        }, 10000); // 10 detik

    } else {

        alert("Payment verification is taking longer than expected. Please contact the cashier.");

    }
</script>