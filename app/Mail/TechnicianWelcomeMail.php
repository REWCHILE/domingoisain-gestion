<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TechnicianWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $setupUrl;
    public $logoBase64;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $setupUrl)
    {
        $this->user = $user;
        $this->setupUrl = $setupUrl;
        $logoPath = public_path('images/instalgaschile-logitpo.png');
        $this->logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('¡Bienvenido a Instalgaschile! Establece tu contraseña de acceso')
                    ->view('emails.welcome_technician');
    }
}
