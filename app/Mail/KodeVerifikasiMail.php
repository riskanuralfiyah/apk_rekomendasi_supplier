<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KodeVerifikasiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    /**
     * Create a new message instance.
     *
     * @param $otp
     */
    public function __construct($otp)
    {
        $this->otp = $otp;  // Menyimpan OTP yang dikirimkan ke konstruktor
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Kode Verifikasi Login')  // Menambahkan subjek email
                    ->view('email.kodeverifikasi')  // Menggunakan view email.kodeverifikasi
                    ->with([
                        'otp' => $this->otp  // Mengirimkan data OTP ke view
                    ]);
    }
}
