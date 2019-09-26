<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Project;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $title;
    public $message;
    //public $invite;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id,$title,$message)
    {
        //
        $this->id = $id;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("You've been added to a new project in Jottify ")->markdown('emails.invite');
    }
}
