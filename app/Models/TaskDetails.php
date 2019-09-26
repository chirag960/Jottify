<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDetails extends Model
{
    //
    protected $table = 'task_details';
    public $timestamps = false;

    public function create($task_id, $status_id){
        $this->task_id = $task_id;
        $this->status_id = $status_id;
        $this->save();
    }

}
