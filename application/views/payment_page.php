<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembayaran</title>
    <script src="https://js.xendit.co/v1/xendit.min.js"></script>
</head>
<body>
    <form id="payment-form">
        <label for="card_number">Card Number:</label>
        <input type="text" id="card_number" value="5200000000002151" required><br>

        <label for="card_exp_month">Expiry Month:</label>
        <input type="text" id="card_exp_month" value="12" required><br>

        <label for="card_exp_year">Expiry Year:</label>
        <input type="text" id="card_exp_year" value="2026" required><br>

        <label for="card_cvn">CVN:</label>
        <input type="text" id="card_cvn" value="123" required><br>

        <button type="button" id="submit-payment">Submit Payment</button>
    </form>

    <script>
        Xendit.setPublishableKey('xnd_public_development_fyNHt0R5chuBH1JQncWKVcl3vZpSuYlzvWJYgDakYvlL2uV9kBdrHCOHXmj9WCl');

        document.getElementById('submit-payment').addEventListener('click', function(event) {
    event.preventDefault();

    const cardData = {
        card_number: document.getElementById('card_number').value,
        card_exp_month: document.getElementById('card_exp_month').value,
        card_exp_year: document.getElementById('card_exp_year').value,
        card_cvn: document.getElementById('card_cvn').value,
        amount: 100000,
        should_authenticate: true // Menambahkan verifikasi 3DS
    };

    Xendit.card.createToken(cardData, function(err, creditCardCharge) {
        if (err) {
            console.error('Tokenization error:', err);
            alert('Error creating token: ' + err.message);
        } else {
             console.log('Token ID:', creditCardCharge.id); // Token ID
            console.log('Authentication ID:', creditCardCharge.authentication_id);
            console.log('Credit Card Charge:', creditCardCharge);
            if (creditCardCharge.payer_authentication_url) {
                // Buka URL otentikasi 3DS dalam popup
                const popup = window.open(creditCardCharge.payer_authentication_url + '&callback_url=' + encodeURIComponent('http://localhost:8080/SOxendit/index.php/Xendit_payment/process_payment'), '3DS Authentication', 'width=600,height=600');

                // Cek status popup setiap detik
                const popupInterval = setInterval(function() {
                    if (popup.closed) {
                        clearInterval(popupInterval);
                        console.log('Popup closed');
                        // Lanjutkan proses pembayaran atau lakukan apapun yang diperlukan
                        continuePayment(creditCardCharge.id);
                    } else {
                        // Anda juga dapat mengecek URL dari popup untuk melihat apakah sudah kembali ke URL callback Anda
                        try {
                            if (popup.location.href.indexOf('http://localhost:8080/SOxendit/index.php/Xendit_payment/process_payment') !== -1) {
                                popup.close();
                                clearInterval(popupInterval);
                                console.log('3DS Authentication complete, popup closed');
                                // Lanjutkan proses pembayaran
                                continuePayment(creditCardCharge.id);
                            }
                        } catch (e) {
                            // Error akan terjadi jika mencoba mengakses domain yang berbeda, jadi ini bisa diabaikan
                        }
                    }
                }, 1000);
            } else {
                console.log('No 3DS authentication needed, continue processing');
                continuePayment(creditCardCharge.id);
            }
        }
    });
});

function continuePayment(tokenId) {
    // Persiapkan data yang akan dikirim ke backend, termasuk token
    const paymentData = {
                token_id: tokenId,
                external_id: 'PAYMENT_123456',
                amount: 20000,
                payer_email: 'customer@example.com',
                description: 'Pembayaran menggunakan kartu kredit demo',
                currency: "IDR",
            };

    // Lakukan permintaan AJAX ke endpoint backend Anda untuk memproses pembayaran
    // Anda dapat menggunakan XMLHttpRequest atau fetch API untuk melakukan ini
    // Contoh dengan fetch API:
        fetch('<?= base_url() ?>index.php/Xendit_payment/process_payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(paymentData),
            })
            .then(response => response.json())
            .then(data => {
                if (data.authentication_url) {
                    // Redirect user to the 3DS authentication URL
                    window.location.href = data.authentication_url;
                } else if (data.error_code) {
                    alert('Payment failed: ' + data.message);
                } else {
                    alert('Payment successful');
                }
            })
            // .catch((error) => {
            //     console.error('Error processing payment:', error);
            //     alert('Payment failed');
            // });
}



        // function createAuth(token){
        //     const cardDataauth = {
        //                     amount: 100000,
        //                     external_id: 'T001',
        //                     currency: "IDR",
        //                     token_id: token,
        //                     card_cvn: document.getElementById('card_cvn').value,
        //                     should_authenticate: true // Menambahkan verifikasi 3DS
        //                 };
        //     Xendit.card.createAuthentication(cardDataauth, function(err, creditCardChargeauth) {
        //         if (err) {
        //             console.error('Tokenization error:', err);
        //             alert('Error creating token: ' + err.message);
        //         } else {

        //             // Memeriksa apakah pembayaran memerlukan verifikasi 3DS
        //             if (creditCardChargeauth.should_3ds_redirect) {
        //                 // Redirect pengguna ke URL verifikasi 3DS
        //                 window.location.href = creditCardChargeauth.payer_authentication_url;
        //             } else {
        //                 // Melanjutkan proses pembayaran jika tidak diperlukan verifikasi 3DS
        //                 processPayment(token,creditCardChargeauth.id);
        //             }
        //         }
        //     });
        // }

        // function processPayment(token,tokenAuth) {
        //     console.log(token)
        //     console.log(tokenAuth)
        //     const paymentData = {
        //         token_id: token,
        //         authentication_id : tokenAuth,
        //         external_id: 'PAYMENT_123',
        //         amount: 100000,
        //         payer_email: 'customer@example.com',
        //         description: 'Pembayaran menggunakan kartu kredit demo',
        //         currency: "IDR",
        //     };

        //     fetch('<?= base_url() ?>index.php/Xendit_payment/process_payment', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //         },
        //         body: JSON.stringify(paymentData),
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.authentication_url) {
        //             // Redirect user to the 3DS authentication URL
        //             window.location.href = data.authentication_url;
        //         } else if (data.error_code) {
        //             alert('Payment failed: ' + data.message);
        //         } else {
        //             alert('Payment successful');
        //         }
        //     })
        //     // .catch((error) => {
        //     //     console.error('Error processing payment:', error);
        //     //     alert('Payment failed');
        //     // });
        // }

        // Simulated response from 3DS (this would actually come from your backend)
        // This script assumes that after successful 3DS authentication, the user is redirected back to your site with query parameters or in a different way.
        function completePayment(authenticationId, tokenId) {
            const completeData = {
                token_id: tokenId,
                authentication_id: authenticationId,
                external_id: 'PAYMENT_123',
                amount: 100000
            };

            fetch('<?= base_url() ?>index.php/Xendit_payment/complete_payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(completeData),
            })
            .then(response => response.json())
            .then(data => {
                if (data.error_code) {
                    alert('Payment failed: ' + data.message);
                } else {
                    alert('Payment completed successfully');
                }
            })
            .catch((error) => {
                console.error('Error completing payment:', error);
                alert('Payment failed');
            });
        }
    </script>
</body>
</html>
