<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $table = 'tasks';
    public $timestamps = true;
    
    protected $fillable = [
        'status_id','description','due_date','attachment_count','comment_count','checklist_done','checklist_item_count'
    ];
    
    public function users(){
        return $this->belongsToMany('App\Models\User', 'task_has_members', 'task_id', 'member_id');
    }

    public function project(){
        return $this->belongsTo('App\Models\Project','project_id','id');
    }

    public function attachments(){
        return $this->hasMany('App\Models\Attachment','task_id','id');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment','task_id','id');
    }

    public function checklist(){
        return $this->hasMany('App\Models\Comment','task_id','id');
    }

}