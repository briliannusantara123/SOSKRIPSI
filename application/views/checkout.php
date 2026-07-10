<?php $this->load->view('template/headmenu'); ?>
<style>
body {
    background: #f5f5f5;
    font-family: -apple-system, BlinkMacSystemFont, sans-serif;
}

.checkout-container {
    padding-bottom: 110px;
}

/* HEADER */
.checkout-header {
    display: flex;
    align-items: center;
    background: #fff;
    padding: 12px;
    font-size: 16px;
    font-weight: 600;
    border-bottom: 1px solid #eee;
}

.back-btn {
    margin-right: 10px;
    text-decoration: none;
    font-size: 20px;
    color: #333;
}

/* CARD */
.checkout-card {
    background: #fff;
    margin: 10px 12px;
    padding: 12px;
    border-radius: 14px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08),
                0 6px 16px rgba(0,0,0,0.04);
}

/* PRODUCT */
.product-row {
    display: flex;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.product-row:last-child {
    border-bottom: none;
}

.product-img {
    width: 64px;
    height: 64px;
    border-radius: 8px;
    object-fit: cover;
}

.product-info {
    flex: 1;
    padding-left: 10px;
}

.product-name {
    font-size: 14px;
    font-weight: 600;
}

.product-variant {
    font-size: 12px;
    color: #888;
}

.product-price {
    margin-top: 6px;
    font-size: 15px;
    font-weight: 700;
    color: <?= $cn->color ?>;
}

.product-qty {
    font-size: 13px;
    color: #666;
}

/* SUMMARY */
.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    margin-bottom: 6px;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    font-weight: 700;
    margin-top: 10px;
}

/* PAYMENT */
.payment-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 10px;
}

.payment-option {
    display: flex;
    align-items: flex-start;
    padding: 12px;
    border: 1px solid #eee;
    border-radius: 12px;
    margin-bottom: 10px;
    cursor: pointer;
    background: #fff;
}

.payment-radio {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    margin-top: 2px;
}

.payment-radio::after {
    content: '';
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: <?= $cn->color ?>;
    transform: scale(0);
    transition: 0.2s ease;
}

.payment-option input {
    display: none;
}

.payment-option input:checked + .payment-radio {
    border-color: <?= $cn->color ?>;
}

.payment-option input:checked + .payment-radio::after {
    transform: scale(1);
}

.payment-content {
    flex: 1;
}

.payment-name {
    font-size: 14px;
    font-weight: 600;
}

.payment-desc {
    font-size: 12px;
    color: #888;
}

.payment-option input:checked ~ .payment-content .payment-name {
    color: <?= $cn->color ?>;
}

/* BANK GRID */
.bank-wrapper {
    margin-top: 12px;
}

.bank-list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.bank-item {
    height: 90px;
    border: 1px solid #000;
    border-radius: 6px;
    background-size: 70%;
    background-repeat: no-repeat;
    background-position: center;
    cursor: pointer;
}

.bank-item.selected {
    border-color: green;
    box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
}

/* FOOTER */
.checkout-footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background: #fff;
    border-top: 1px solid #eee;
    padding: 12px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.footer-label {
    font-size: 12px;
    color: #888;
}

.footer-total {
    font-size: 16px;
    font-weight: 700;
    color: <?= $cn->color ?>;
}

.btn-order {
    background: <?= $cn->color ?>;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 600;
}
</style>

<form id="checkoutForm" method="POST" action="<?= base_url('index.php/cart/bayar_va/'.$nomeja) ?>">

    <div class="checkout-container">

        <div class="checkout-header">
            <a href="<?= base_url().$log ?>" class="back-btn">
                <i class="bi bi-arrow-left" style="font-size:30px;margin-left:10px;"></i>
            </a>
            <h2 style="margin:0;margin-left:5px;"><strong>Checkout</strong></h2>
        </div>

        <div class="checkout-card">
            <?php foreach ($item as $i): ?>
            <?php $note = $i->extra_notes ?>
            <div class="product-row">
                <img src="<?= $i->image_path ?: $logo->image_path ?>" class="product-img">
                <div class="product-info">
                    <div class="product-name"><?= $i->description ?></div>
                    <?php if ($note): ?>
                        <div class="product-variant"><?= $note ?></div>
                    <?php endif ?>
                    <div class="product-price">Rp <?= number_format($i->unit_price_disc ?: $i->unit_price) ?></div>
                </div>
                <div class="product-qty">x<?= $i->qty ?></div>
            </div>

            <input type="hidden" name="nama[]" value="<?= $i->description ?>">
            <input type="hidden" name="qty[]" id="qty<?= $i->id?>" value="<?= $i->qty ?>">
            <input type="hidden" name="cek[]" value="<?= $i->as_take_away ?>">
            <input type="hidden" name="qta[]" value="<?= $i->qty_take_away ?>">
            <input type="hidden" name="harga[]" value="<?= $i->unit_price ?>">
            <input type="hidden" name="is_paket[]" value="<?= $i->is_paket ?>">
             <!-- <input type="hidden" name="options[]" value="<?= $i->od ?>"> -->
            <!-- <?php if ( $i->notesdua): ?>
            <input type="hidden" name="pesandua[]" value="<?= $i->notesdua ?>">
            <input type="hidden" name="pesantiga[]" value="<?= $i->notesdua ?>">
            <?php   endif ?> -->
            <input type="hidden" name="pesan[]" value="<?= $i->extra_notes ?>">
            <input type="hidden" name="no[]" id="item_code<?= $i->id ?>" value="<?= $i->item_code ?>" class="form-control item_code<?= $i->id ?>">
            <input type="hidden" name="need_stock[]" id="need_stock" value="<?= $i->need_stock ?>" class="form-control need_stock">
            <?php endforeach ?>
        </div>

        <div class="checkout-card">
            <div class="payment-title">Payment Method</div>

            <label class="payment-option">
                <input type="radio" name="payment_type" value="online" checked>
                <span class="payment-radio"></span>

                <div class="payment-content">
                    <div class="payment-name">Pay Now</div>
                    <div class="payment-desc">Virtual Account</div>

                    <div class="bank-wrapper" id="bankWrapper">
                        <div class="bank-list" id="bankList">
                            <?php
                            $banks = ['BCA','BRI','MANDIRI','BNI'];
                            foreach ($banks as $bank): ?>
                            <div class="bank-item <?= $bank=='BCA'?'selected':'' ?>"
                                 data-value="<?= $bank ?>"
                                 style="background-image:url('<?= base_url("assets/BANK/".$bank.".png") ?>')">
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                    <input type="hidden" name="bank_code" id="bank_code" value="BCA">
                </div>
            </label>

            <label class="payment-option">
                <input type="radio" name="payment_type" value="cashier">
                <span class="payment-radio"></span>
                <div class="payment-content">
                    <div class="payment-name">Pay at Cashier</div>
                    <div class="payment-desc">Pay directly at the cashier</div>
                </div>
            </label>
        </div>

        <div class="checkout-card">
            <div class="payment-title">Payment Summary</div>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp <?= number_format($total) ?></span>
            </div>
            <?php if (!empty($hitungbayar)): ?>
            <div class="summary-row">
                <span>Service Charge</span>
                <span>Rp <?= number_format($hitungbayar->sc) ?></span>
            </div>
            <div class="summary-row">
                <span>Tax (PB1)</span>
                <span>Rp <?= number_format($hitungbayar->ppn) ?></span>
            </div>
            <?php endif ?>
            <hr>
            <div class="summary-total">
                <span>Total Payment</span>
                <span>Rp <?= number_format($total + ($hitungbayar->sc ?? 0) + ($hitungbayar->ppn ?? 0)) ?></span>
            </div>
        </div>
    </div>

    <div class="checkout-footer">
        <div>
            <div class="footer-label">Total</div>
            <div class="footer-total">
                Rp <?= number_format($total + ($hitungbayar->sc ?? 0) + ($hitungbayar->ppn ?? 0)) ?>
            </div>
        </div>
        <button class="btn-order" type="submit">Pay Now</button>
    </div>

    <input type="hidden" name="totalbayar" value="<?= $totalbayar->total + $totalbayar->sc + $totalbayar->ppn ?>">
</form>

<script>
const paymentRadios = document.querySelectorAll('input[name="payment_type"]');
const bankList = document.getElementById('bankList');
const btnOrder = document.querySelector('.btn-order');
const formCheckout = document.getElementById('checkoutForm');

const urlVA    = "<?= base_url('index.php/cart/bayar_va/'.$nomeja) ?>";
const urlOrder = "<?= base_url('index.php/cart/order/'.$nomeja.'/PaymentCashier/'.$cek.'/'.$url) ?>";
function toggleBank() {
    const selected = document.querySelector('input[name="payment_type"]:checked').value;

    if (selected === 'online') {
        bankList.style.display = 'grid';
        btnOrder.innerText = 'Pay Now';
        formCheckout.action = urlVA;
    } else {
        bankList.style.display = 'none';
        btnOrder.innerText = 'Order & Pay at Cashier';
        formCheckout.action = urlOrder;
    }
}

paymentRadios.forEach(r => r.addEventListener('change', toggleBank));
toggleBank();

document.querySelectorAll('.bank-item').forEach(item => {
    item.addEventListener('click', function () {
        document.querySelectorAll('.bank-item').forEach(i => i.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('bank_code').value = this.dataset.value;
    });
});
</script>

