<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran dengan Kartu Kredit</title>
    <script src="https://js.xendit.co/v1/xendit.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        label, input, button {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Pembayaran dengan Kartu Kredit</h1>
    <form id="payment-form">
        <label for="card_number">Nomor Kartu Kredit:</label>
        <input type="text" id="card_number" value="5200000000002151" placeholder="Masukkan nomor kartu kredit" required><br>

        <label for="card_exp_month">Bulan Kedaluwarsa:</label>
        <input type="text" id="card_exp_month" value="12" required><br>

        <label for="card_exp_year">Tahun Kedaluwarsa:</label>
        <input type="text" id="card_exp_year" value="2026" required><br>

        <label for="card_cvn">CVV:</label>
        <input type="text" id="card_cvn" value="123" required><br>

        <button type="button" id="submit-payment">Kirim Pembayaran</button>
    </form>

    <p id="payment-status"></p>
    <p id="bank-info"></p>

    <script>
        // Set your Xendit Publishable Key
        Xendit.setPublishableKey('xnd_public_development_fyNHt0R5chuBH1JQncWKVcl3vZpSuYlzvWJYgDakYvlL2uV9kBdrHCOHXmj9WCl');

        const binToBankMap = {
            'BCA': ['400036', '400037', '400038', '5379', '5259'],
            'BRI': ['421897', '5221'],
            'Mandiri': ['4341', '5489'],
            'BNI': ['414708', '4302', '4988', '5255'],
            'CIMB Niaga': ['4606', '5134'],
            'Permata': ['400050', '400051', '400052', '400053', '400054'],
            'Danamon': ['400070', '400071', '400072', '400073', '400074'],
            'Mega': ['400090', '400091', '400092', '400093', '400094'],
            'Panin': ['400100', '400101', '400102', '400103', '400104'],
            'Bukopin': ['400110', '400111', '400112', '400113', '400114'],
            'BTN': ['400120', '400121', '400122', '400123', '400124']
        };

        document.getElementById('submit-payment').addEventListener('click', function(event) {
            event.preventDefault();

            const cardNumber = document.getElementById('card_number').value;
            const bin = cardNumber.slice(0, 4);

            const bank = getBankFromBin(bin);
            document.getElementById('bank-info').innerText = 'Bank: ' + (bank ? bank : 'Tidak dikenal');

            if (!bank) {
                alert('Kartu dari bank yang tidak dikenal.');
                return;
            }

            const cardData = {
                card_number: cardNumber,
                card_exp_month: document.getElementById('card_exp_month').value,
                card_exp_year: document.getElementById('card_exp_year').value,
                card_cvn: document.getElementById('card_cvn').value,
                amount: 100000, // Jumlah pembayaran dalam IDR
                should_authenticate: true // Mengaktifkan 3DS jika diperlukan
            };

            Xendit.card.createToken(cardData, function(err, creditCardCharge) {
                if (err) {
                    console.error('Error tokenizing card:', err);
                    document.getElementById('payment-status').innerText = 'Error: ' + err.message;
                } else {
                    console.log('Token ID:', creditCardCharge.id);
                    handle3DS(creditCardCharge);
                }
            });
        });

        function getBankFromBin(bin) {
            for (const bank in binToBankMap) {
                if (binToBankMap[bank].includes(bin)) {
                    return bank;
                }
            }
            return null;
        }

        function handle3DS(creditCardCharge) {
            if (creditCardCharge.payer_authentication_url) {
                // Buka URL otentikasi 3DS dalam popup
                const popup = window.open(creditCardCharge.payer_authentication_url, '3DS Authentication', 'width=600,height=600');

                // Cek status popup setiap detik
                const popupInterval = setInterval(function() {
                    if (popup.closed) {
                        clearInterval(popupInterval);
                        console.log('Popup closed');
                        continuePayment(creditCardCharge.id);
                    }
                }, 1000);
            } else {
                console.log('No 3DS authentication needed, continue processing');
                continuePayment(creditCardCharge.id);
            }
        }

        function continuePayment(tokenId) {
            // Kirim token ke server backend Anda untuk memproses pembayaran
            const paymentData = {
                token_id: tokenId,
                external_id: 'ORDER_123456',
                amount: 100000, // Jumlah pembayaran dalam IDR
                payer_email: 'customer@example.com',
                description: 'Pembayaran Kartu Kredit',
                currency: 'IDR'
            };

            // Gantilah URL ini dengan endpoint server backend Anda
            fetch('https://example.com/your-backend-endpoint', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(paymentData),
            })
            .then(response => response.json())
            .then(data => {
                if (data.error_code) {
                    document.getElementById('payment-status').innerText = 'Payment failed: ' + data.message;
                } else {
                    document.getElementById('payment-status').innerText = 'Payment successful';
                }
            })
            .catch(error => {
                console.error('Error processing payment:', error);
                document.getElementById('payment-status').innerText = 'Payment failed';
            });
        }
    </script>
</body>
</html>
