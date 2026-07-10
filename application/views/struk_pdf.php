<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk PDF</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .receipt {
            width: 300px;
            margin: 0 auto;
        }

        .center {
            text-align: center;
        }

        .title {
            font-size: 24px;
            font-weight: normal;
            margin-bottom: 6px;
        }

        .subtitle {
            font-weight: bold;
        }

        .small {
            font-size: 10px;
        }

        .line {
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }

        .row {
            width: 100%;
            clear: both;
        }

        .left {
            float: left;
            width: 60%;
        }

        .right {
            float: right;
            width: 40%;
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .spacer {
            margin-top: 6px;
        }

        .spacer-lg {
            margin-top: 14px;
        }
    </style>
</head>
<body>

<div class="receipt">

    <!-- HEADER -->
    <div class="center">
        <!-- <img id="previewImage" src="<?= $logo->image_path ?>" style="width: 220px; height: 90px;"> -->
        <div class="subtitle"><?= $cabang->cabang_name ?></div>
        <?= $cabang->alamat ?>
        <div class="spacer"></div>
        <?= $visit_type ?> / Table <?= sprintf('%02d', $nomeja) ?> <?php if($total_pax > 0):?> / Pax <?= $total_pax ?><?php endif ?>
    </div>

    <div class="line"></div>

    <!-- DATE, CUSTOMER & TRANSACTION -->
    <div class="row">
        <div class="left">
            Date<br>
            <?= date('d F Y') ?>

            <div class="spacer"></div>

            Customer name<br>
            <?= $customer_name ?>
        </div>

        <div class="right">
            Transaction Number<br>
            <?= $trans->order_no ?>
        </div>
    </div>

    <div class="line"></div>
    <div style="margin-top: 70px;"></div>
    <!-- ITEM -->
    <?php foreach ($order_bill_line as $obl): ?>

        <div class="row bold">
            <div class="left">
                <?= $obl->description ?>
            </div>
            <div class="right">
                <?php if ($obl->unit_price === null): ?>
                    FREE
                <?php else: ?>
                    Rp<?= number_format($obl->unit_price) ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($obl->extra_notes)): ?>
            <div class="small" style="margin-left:8px;">
                *Notes: <?= $obl->extra_notes ?>
            </div>
        <?php endif; ?>

        <div class="spacer"></div>

    <?php endforeach; ?>


    <div class="line"></div>

    <!-- PAYMENT DETAILS -->
    <div class="bold">Payment Details</div>

    <div class="row spacer">
        <div class="left">Subtotal</div>
        <div class="right">Rp<?= number_format($order_bill->total ?? 0) ?></div>
    </div>

    <div class="row">
        <div class="left">Discount</div>
        <div class="right">-Rp0</div>
    </div>

    <div class="row">
        <div class="left">PBI 10%</div>
        <div class="right">Rp<?= number_format($order_bill->ppn ?? 0) ?></div>
    </div>

    <div class="spacer"></div>

    <div class="row">
        <div class="left">Total</div>
        <div class="right">Rp<?= number_format(
                                ($order_bill->total ?? 0) +
                                ($order_bill->sc ?? 0) +
                                ($order_bill->ppn ?? 0)
                            ) ?></div>
    </div>

    <div class="row">
        <div class="left">Down Payment</div>
        <div class="right">-Rp<?= number_format($trans->down_payment ?? 0) ?></div>
    </div>

    <div class="line"></div>

    <!-- GRAND TOTAL -->
    <div class="row bold" style="font-size:13px;">
        <div class="left">Grand Total</div>
        <div class="right">Rp<?= number_format(
                                ($order_bill->total ?? 0) +
                                ($order_bill->sc ?? 0) +
                                ($order_bill->ppn ?? 0)
                            ) ?></div>
    </div>

    <div class="spacer-lg"></div>

    <!-- PAYMENT METHOD -->
    <div class="row bold">
        <div class="left">VA <?= $trans->payment_bank_card ?></div>
        <div class="right">Rp<?= number_format(
                                ($order_bill->total ?? 0) +
                                ($order_bill->sc ?? 0) +
                                ($order_bill->ppn ?? 0)
                            ) ?></div>
    </div>

    <div class="spacer"></div>

    <div class="row">
        <div class="left">Change</div>
        <div class="right">Rp0</div>
    </div>

    <!-- <div class="row">
        <div class="left">Earn Point</div>
        <div class="right">Rp<?= number_format($point->point_earned ?? 0) ?></div>
    </div>

    <div class="row">
        <div class="left">Point Balance</div>
        <div class="right">Rp<?= number_format($point->point_balance ?? 0) ?></div>
    </div> -->

    <div class="line"></div>

    <!-- FOOTER -->
    <div class="center">
        <div class="bold">PAID</div>
        <?= date('d F Y - H:i', strtotime($trans->payment_date)) ?>
        <div class="spacer"></div>
        Thank you for your order!
    </div>

</div>

</body>
</html>
