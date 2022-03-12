<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PdfMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $msg;

    public $subjet = "Factura Importaciones Clio Nicaragua";


    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($msg)
    {
        $this->msg = $msg; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        

        return $this->view('email_factura')->attach(public_path('storage\factura.pdf'), [
                'as' => 'sample.pdf',
                'mime' => 'application/pdf',
        ]);;
    }
}
