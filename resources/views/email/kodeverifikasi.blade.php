<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Verifikasi Login</h2>
        <p>Halo,</p>
        <p>Kami menerima permintaan login ke akun Anda. Masukkan kode verifikasi berikut untuk melanjutkan:</p>
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; background-color: #f1f1f1; padding: 10px 20px; border-radius: 5px;">{{ $otp }}</span>
        </div>
        <p>Kode ini hanya berlaku selama 10 menit.</p>
        <p>Jika Anda tidak mencoba login, abaikan email ini.</p>
        <p>Terima kasih,<br><strong>Riska Mebel</strong></p>
    </div>
</body>
</html>
