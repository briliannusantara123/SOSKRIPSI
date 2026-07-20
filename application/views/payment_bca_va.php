<?php $this->load->view('template/headmenu'); ?>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fb, #eef4ff);
            color: #1f2937;
        }

        .wrap {
            max-width: 460px;
            margin: 0 auto;
            background: #ffffff;
            min-height: 100vh;
            box-shadow: 0 12px 35px rgba(0,0,0,0.08);
        }

        .header {
            background: linear-gradient(90deg, #0d5ea8, #1f7ed6);
            color: white;
            padding: 22px 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .header .sub {
            margin-top: 4px;
            font-size: 13px;
            opacity: 0.9;
        }

        .content {
            padding: 18px 20px 28px;
        }

        .card {
            border: 1px solid #e5ecf7;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 14px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        }

        .label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .amount {
            font-size: 26px;
            font-weight: 800;
            color: #0d5ea8;
        }

        .badge {
            display: inline-block;
            background: #eaf5ff;
            color: #0d5ea8;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .bank-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .bank-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #0d5ea8, #1f7ed6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 15px;
        }

        .va-box {
            background: linear-gradient(90deg, #f8fbff, #eef7ff);
            border: 1px dashed #1f7ed6;
            border-radius: 12px;
            padding: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .va-number {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #0f172a;
        }

        .copy-btn {
            background: #1f7ed6;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
        }

        .continue-btn {
            background: linear-gradient(90deg, #0f766e, #14b8a6);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 14px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            width: 100%;
            margin-top: 10px;
        }

        .note {
            font-size: 12px;
            color: #6b7280;
            margin-top: 8px;
            line-height: 1.5;
        }

        .footer {
            text-align: center;
            padding: 16px 20px 24px;
            color: #6b7280;
            font-size: 12px;
        }

        .form-group {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .form-group input {
            flex: 1;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
        }

        .form-group button {
            background: #0d5ea8;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
        }

        .alert {
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="header">
            <h2>Bayar via Virtual Account</h2>
            <div class="sub">Pembayaran aman, cepat, dan langsung terverifikasi</div>
        </div>

        <div class="content">
            <div class="card">
                <div class="badge">Cek Nomor VA</div>
                <div class="label">Masukkan Nomor VA</div>
                <?= form_open('index.php/payment', array('method' => 'post')) ?>
                    <div class="form-group">
                        <input type="text" name="va_number" value="<?= htmlspecialchars($va_number) ?>" placeholder="Contoh: 880812345678" required>
                        <button type="submit">Cek</button>
                    </div>
                <?= form_close() ?>
            </div>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($payment)) : ?>
                <div class="card">
                    <div class="badge">Pembayaran Ditemukan</div>
                    <div class="label">Currency</div>
                    <div class="amount">IDR</div>
                    <div class="label" style="margin-top:10px;">Total Payment</div>
                    <div class="amount">Rp <?= number_format($payment->amount, 0, ',', '.') ?></div>
                    <div class="note">Nomor Order: <?= htmlspecialchars($payment->external_id ?? '-') ?></div>
                    <button class="continue-btn" type="button" onclick="continuePayment('<?= htmlspecialchars($payment->external_id ?? '', ENT_QUOTES, 'UTF-8') ?>', '<?= (int) $payment->amount ?>')">Continue</button>
                </div>

                <div class="card">
                    <div class="bank-box">
                        <div class="bank-icon">BCA</div>
                        <div>
                            <div style="font-weight:bold;">Bank <?= htmlspecialchars($payment->bank ?? 'BCA') ?></div>
                            <div style="font-size:12px;color:#6b7280;">Pembayaran otomatis terverifikasi</div>
                        </div>
                    </div>

                    <div class="va-box">
                        <div>
                            <div class="label">Nomor Virtual Account</div>
                            <div class="va-number" id="vaNumber"><?= htmlspecialchars($payment->va_number) ?></div>
                        </div>
                        <button class="copy-btn" onclick="copyVA()">Salin</button>
                    </div>
                </div>

                <div class="card">
                    <div class="label">Detail Pembayaran</div>
                    <div class="note">Status: <?= htmlspecialchars($payment->status ?? '-') ?></div>
                    <div class="note">Expired: <?= htmlspecialchars($payment->expired_at ?? '-') ?></div>
                    <div class="note">Customer ID: <?= htmlspecialchars($payment->id_customer ?? '-') ?></div>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            Pembayaran aman dan terhubung dengan sistem order Anda.
        </div>
    </div>

    <script>
        function copyVA() {
            const va = document.getElementById('vaNumber').textContent;
            navigator.clipboard.writeText(va).then(function () {
                alert('Nomor VA berhasil disalin');
            });
        }

        function continuePayment(externalId, amount) {
            if (!externalId) {
                alert('External ID tidak tersedia');
                return;
            }

            const url = '<?= base_url('index.php/cart/simulate_va_payment') ?>/' + encodeURIComponent(externalId) + '/' + encodeURIComponent(amount);
            window.location.href = url;
        }
    </script>
</body>
</html>
<?php $this->load->view('template/footer') ?>