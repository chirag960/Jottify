<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $inputs;
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
            ->subject('A new task "'.$this->inputs['title'].'" has been assigned to you in the project "'.$this->inputs['project_title'])
            ->view('emails.assign')
            ->with([
                'params' => $this->inputs
            ]);
    }
}
