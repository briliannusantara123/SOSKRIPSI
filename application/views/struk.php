<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f5f5;
        }
        .receipt {
            max-width: 380px;
            background: #fff;
            border-radius: 8px;
            font-size: 14px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 1);
        }

        .receipt hr {
            border-top: 1px dashed #ccc;
        }
        .text-small {
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="receipt mx-auto p-3 shadow-sm">

        <!-- Header -->
        <div class="text-center mb-2">
            <!-- <img id="previewImage" src="<?= $logo->image_path ?>" style="width: 220px; height: 90px;"> -->
            <h6 class="fw-bold mb-0"><?= $cabang->cabang_name ?></h6>
            <div class="text-muted text-small"><?= $cabang->alamat ?></div>
            <div class="fw-semibold mt-2">Table <?= sprintf('%02d', $nomeja) ?> <?php if($total_pax > 0):?> / Pax <?= $total_pax ?><?php endif ?></div>
        </div>

        <hr>

        <!-- Info -->
        <div class="row text-small">
            <div class="col-6">
                <div>Date</div>
                <div class="fw-semibold"><?= date('d F Y') ?></div>
            </div>
            <div class="col-6 text-end">
                <div>Transaction Number</div>
                <div class="fw-semibold"><?= $trans->order_no ?></div>
            </div>
        </div>

        <div class="mt-2 text-small">
            <div>Customer name</div>
            <div class="fw-semibold"><?= $customer_name ?></div>
        </div>

        <hr>

        <!-- Items -->
        <div class="text-small">
            <?php foreach ($order_bill_line as $obl ): ?>
                <?php $uc = $obl->unit_price * $obl->qty ?>
                <?php if ($obl->extra_notes): ?>
                    <div class="d-flex justify-content-between fw-semibold">
                        <span><?= $obl->description ?></span>
                        <span>Qty : <?= $obl->qty ?></span>
                    </div>
                    <div class="ms-2" style="float: right;"><strong>Rp<?= number_format($uc ?? 'FREE') ?></strong></div>
                    <div class="text-muted ms-2">*Notes: <?= $obl->extra_notes ?></div>
                <?php else: ?>  
                    <div class="d-flex justify-content-between mt-2">
                        <span><?= $obl->description ?> <?= $obl->qty ?>X</span>
                        <span>Rp<?= number_format($uc ?? 'FREE') ?></span>
                    </div>
                <?php endif ?>
                
            <?php endforeach ?>
        </div>

        <hr>

        <!-- Payment Details -->
        <div class="text-small">
            <div class="fw-semibold mb-1">Payment Details</div>

            <div class="d-flex justify-content-between">
                <span>Subtotal</span>
                <span>Rp<?= number_format($order_bill->total ?? 0) ?></span>
            </div>

            <div class="d-flex justify-content-between">
                <span>Discount</span>
                <span>-Rp0</span>
            </div>

            <!-- <div class="d-flex justify-content-between">
                <span>Service</span>
                <span>Rp<?= number_format($order_bill->sc ?? 0) ?></span>
            </div> -->

            <div class="d-flex justify-content-between">
                <span>PB1 10%</span>
                <span>Rp<?= number_format($order_bill->ppn ?? 0) ?></span>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <span>Total</span>
                <span>Rp<?= number_format(
                                ($order_bill->total ?? 0) +
                                ($order_bill->sc ?? 0) +
                                ($order_bill->ppn ?? 0)
                            ) ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Down Payment</span>
                <span>-Rp<?= number_format($trans->down_payment ?? 0) ?></span>
            </div>
            <hr>    
            <div class="d-flex justify-content-between fw-bold fs-6">
                <span>Grand Total</span>
                <span>Rp<?= number_format(
                                ($order_bill->total ?? 0) +
                                ($order_bill->sc ?? 0) +
                                ($order_bill->ppn ?? 0)
                            ) ?></span>
            </div>
            <hr>    
            <div class="d-flex justify-content-between fw-bold fs-6">
                <?php if ($paymentMethod == 'QR_CODE'): ?>
                    <span>QR <?= $trans->payment_bank_card ?></span>
                <?php else: ?>
                    <span>VA <?= $trans->payment_bank_card ?></span>
                <?php endif ?>
                
                <span>Rp<?= number_format(
                                ($order_bill->total ?? 0) +
                                ($order_bill->sc ?? 0) +
                                ($order_bill->ppn ?? 0)
                            ) ?></span>
            </div>
        </div>

        <hr>

        <!-- Payment Method -->
        <div class="text-small">
            <div class="d-flex justify-content-between">
                <span>Change</span>
                <span>Rp0</span>
            </div>

            <!-- <div class="d-flex justify-content-between mt-1">
                <span>Earn Point</span>
                <span>Rp<?= number_format($point->point_earned ?? 0) ?></span>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <span>Point Balance</span>
                <span>Rp<?= number_format($point->point_balance ?? 0) ?></span>
            </div> -->
        </div>

        <hr>

        <!-- Footer -->
        <div class="text-center text-small">
            <div class="fw-bold">PAID</div>
            <div><?= date('d F Y - H:i', strtotime($trans->payment_date)) ?></div>
            <div class="mt-2 text-muted">Thank you for your order!</div>
        </div>

    </div>
    <?php if ($print == 0): ?>
        <div class="d-flex justify-content-between mt-3">
            <a href="<?= base_url('index.php/selforder/download_struk_pdf') ?>" 
               class="btn btn-warning w-50" style="margin-right:10px;">
                Download PDF
            </a>

            <a href="<?= base_url() ?>index.php/selforder/struk_done" 
               class="btn btn-success w-50">
                DONE
            </a>
        </div>
    <?php endif ?>
    

</div>

</body>
</html>
