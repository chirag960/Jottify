<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use App\Models\Project;
use App\Mail\InviteMail;
use App\Mail\AssignMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendEmails
{
    use Dispatchable, Queueable;
    protected $ids;
    protected $type;
    protected $entity;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ids, $type, $entity)
    {
        //
        $this->ids = $ids;
        $this->type = $type;
        $this->entity = $entity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        if($this->type == "inviteToProject"){
           $emails = (new User)->whereIn('id',$this->ids)->pluck('email')->toArray();
           Mail::to($emails)->send(new InviteMail($this->entity->id, $this->entity->title,$this->entity->message));
            
        }
        else if($this->type == "assignToTask"){
            $emails = (new User)->whereIn('id',$this->ids)->pluck('email')->toArray();
            Mail::to($emails)->send(new AssignMail($this->entity->id, $this->entity->project_id,$this->entity->title));
        }
        else if($this->type == "dueTaskToNotify"){
            // $params['task_details'] = $this->entity;
            //Mail::to($this->user->email)->send(new AssignMail($params)); 
        }
    }
}
