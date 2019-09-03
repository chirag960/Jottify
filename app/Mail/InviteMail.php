<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Project;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $inputs;

    //public $invite;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inputs)
    {
        //
        $this->inputs = $inputs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('mail@example.com', 'Mailtrap')
            ->subject('Invitation to new project '.$this->inputs['project_title'])
            ->view('emails.invite')
            ->with([
                'params' => $this->inputs
            ]);
    }
}
