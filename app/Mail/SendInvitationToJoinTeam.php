<?php

namespace App\Mail;

use App\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvitationToJoinTeam extends Mailable
{
    use Queueable, SerializesModels;
    public $invitation;
    public $user_exist;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation, $user_exist)
    {
        $this->invitation = $invitation;
        $this->user_exist = $user_exist;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->user_exist)
        {
            $url = config('app.client_url').'/settings/teams';
            return $this->markdown('emails.invitations.invite-existing-user')
                ->subject('Invitation to join team '.$this->invitation->team->name)
                ->with(['invitation'=>$this->invitation,'url'=>$url]);
        }else{
            $url = config('app.client_url').'/register?invitation='.$this->invitation->recipient_email;
            return $this->markdown('emails.invitations.invite-new-user')
                ->subject('Invitation to join team '.$this->invitation->team->name)
                ->with(['invitation'=>$this->invitation,'url'=>$url]);
        }

    }
}
