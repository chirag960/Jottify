<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use App\Models\Project;
use App\Mail\InviteMail;
use App\Mail\AssignMail;
use Illuminate\Support\Facades\Mail;

class SendEmails
{
    use Dispatchable, Queueable;
    protected $user;
    protected $type;
    protected $entity;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $type, $entity)
    {
        //
        $this->user = $user;
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
        $params = array();
        $params['user_id'] = $this->user->id;
        $params['user_name'] = $this->user->name;
        $params['email'] = $this->user->email;

        if($this->type == "inviteToProject"){
            Mail::to($this->user->email)->send(new InviteMail($this->entity)); 
        }
        else if($this->type == "assignToTask"){
            $params['project_id'] = $this->entity->project_id;
            $project = Project::find($this->entity->project_id);
            $params['project_title'] = $project->title;
            $params['id'] = $this->entity->id;
            $params['title'] = $this->entity->title;
            Mail::to($this->user->email)->send(new AssignMail($params)); 
        }
        else if($this->type == "dueTaskToNotify"){
            $params['task_details'] = $this->entity;
            //Mail::to($this->user->email)->send(new AssignMail($params)); 
        }
    }
}
