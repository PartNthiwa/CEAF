<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation;


class MemberInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invitation $invitation) {}

    public function build()
    {
        $url = url('/register?token='.$this->invitation->token);

        return $this->subject('Your invitation to register - Carolina East Africa Foundation')
            ->view('emails.member-invitation')
            ->with([
                'url' => $url,
                'email' => $this->invitation->email,
                'expiresAt' => $this->invitation->expires_at,
            ]);
    }
}
