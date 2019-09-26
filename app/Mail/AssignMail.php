<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignMail extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $project_id;
    public $title;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id,$project_id,$title)
    {
        //
        $this->id = $id;
        $this->project_id = $project_id;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("A new task has been assigned to you on Jottify")->markdown('emails.assign');
    }
}
