<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    //
    protected $table = 'checklists';
    protected $fillable = ['completed'];
    public $timestamps = false;

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

    public function create($task_id,$item,$completed){
        $this->task_id = $task_id;
        $this->item = $item;
        $this->completed = $completed;
        $this->save();
        return $this;
    }

    public function getCompletion($id){
        return Checklist::find($id)->completed;
    }

    public function setCompletion($id,$done){
        Checklist::find($id)->update(['completed'=>$done]);
    }
}
