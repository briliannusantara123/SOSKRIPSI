<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
        .receipt { width: 320px; margin: 0 auto; }
        .center { text-align: center; }
        .items { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items td { padding: 4px 0; }
        .items .qty { width: 30px; }
        .items .price { text-align: right; }
        .total { font-weight: bold; margin-top: 10px; }
        hr { border: none; border-top: 1px dashed #000; margin: 8px 0; }
    </style>
</head>
<body>
<div class="receipt">
    <div class="center">
        <h3 style="margin:0;"><?= htmlspecialchars($this->config->item('app_name') ?: ($logo->title ?? 'Outlet'), ENT_QUOTES) ?></h3>
        <div style="font-size:11px;">Struk Pembayaran</div>
        <div style="font-size:11px;">Tanggal: <?= date('Y-m-d H:i:s', strtotime($transaction->payment_date ?? $transaction->create_date ?? date('Y-m-d H:i:s'))) ?></div>
        <div style="font-size:11px;">No. Trans: <?= $transaction->id ?></div>
    </div>

    <hr>

    <table class="items">
        <?php foreach ($details as $d):
            $qty = (int)$d->qty;
            $price = (int)$d->unit_price;
            $sub = $qty * $price;
        ?>
        <tr>
            <td class="qty"><?= $qty ?>x</td>
            <td><?= htmlspecialchars($d->item_name, ENT_QUOTES) ?></td>
            <td class="price"><?= number_format($sub,0,',','.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <hr>

    <div class="total">
        <div style="display:flex;justify-content:space-between;"><div>Subtotal</div><div>Rp <?= number_format($payment->total ?? array_sum(array_map(function($i){return ($i->qty*$i->unit_price);}, $details)),0,',','.') ?></div></div>
        <div style="display:flex;justify-content:space-between;"><div>SC</div><div>Rp <?= number_format($payment->sc ?? 0,0,',','.') ?></div></div>
        <div style="display:flex;justify-content:space-between;"><div>PPN</div><div>Rp <?= number_format($payment->ppn ?? 0,0,',','.') ?></div></div>
        <hr>
        <div style="display:flex;justify-content:space-between;"><div>Total</div><div>Rp <?= number_format($transaction->total_amount ?? ($payment->total + ($payment->sc ?? 0) + ($payment->ppn ?? 0)),0,',','.') ?></div></div>
        <div style="display:flex;justify-content:space-between;"><div>Bayar (Tunai)</div><div>Rp <?= number_format($transaction->payment_amount ?? ($transaction->total_amount ?? 0),0,',','.') ?></div></div>
        <div style="display:flex;justify-content:space-between;"><div>Kembalian</div><div>Rp <?= number_format($transaction->kembalian ?? 0,0,',','.') ?></div></div>
    </div>

    <hr>
    <div class="center" style="margin-top:8px;font-size:11px;">Terima kasih. Selamat datang kembali!</div>
</div>

<script>
// Auto print then close window. Use onafterprint when available and fallbacks.
function doPrintAndClose() {
    try {
        window.print();
    } catch (e) {
        // ignore
    }
    // fallback close in case onafterprint is not supported
    setTimeout(function(){ try { window.close(); } catch(e){} }, 1500);
}

window.addEventListener('load', function(){
    // small delay to ensure resources loaded
    setTimeout(function(){
        // try to use onafterprint for reliable close
        if ('onafterprint' in window) {
            window.onafterprint = function() { try { window.close(); } catch(e){} };
            doPrintAndClose();
        } else if (window.matchMedia) {
            // some browsers support matchMedia for print events
            var mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql){ if (!mql.matches) { try { window.close(); } catch(e){} } });
            doPrintAndClose();
        } else {
            doPrintAndClose();
        }
    }, 200);
});
</script>
</body>
</html>