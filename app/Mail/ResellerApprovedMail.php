<?php

namespace App\Mail;

use App\Models\Reseller;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResellerApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reseller $reseller;

    public function __construct(Reseller $reseller)
    {
        $this->reseller = $reseller;
    }

    public function build()
    {
        return $this
            ->subject('Akun Reseller Anda Telah Disetujui')
            ->view('emails.reseller-approved');
    }
}
