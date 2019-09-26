<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //
    protected $table = 'attachments';
    public $timestamps = false;

    public function tasks(){
        return $this->belongsTo('App\Models\Task');
    }

    public function create($task_id,$name,$type,$location){
        $this->task_id = $task_id;
        $this->user_id = auth()->id();
        $this->name = $name;
        $this->type = $type;
        $this->location = $location;
        $this->save();
        return $this;
    }

    
}
