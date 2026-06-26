<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaginaInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $inviteCode;

    public $paginaName;

    public $inviteUrl;

    public $inviterName;

    public function __construct($inviteCode, $paginaName, $inviteUrl, $inviterName)
    {
        $this->inviteCode = $inviteCode;
        $this->paginaName = $paginaName;
        $this->inviteUrl = $inviteUrl;
        $this->inviterName = $inviterName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitación a: '.$this->paginaName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pagina_invitation',
        );
    }
}
