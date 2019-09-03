<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $table = 'task';
    public $timestamps = false;
    
    protected $fillable = [
        'status_id','description','due_date','checklist_done','checklist_item_count'
    ];
    
    public function users(){
        return $this->belongsToMany('App\User', 'task_has_member', 'task_id', 'member_id');
    }

    public function project(){
        return $this->belongsTo('App\Project','project_id','id');
    }

    public function attachments(){
        return $this->hasMany('App\Attachment','task_id','id');
    }

    public function comments(){
        return $this->hasMany('App\Comment','task_id','id');
    }

    public function checklist(){
        return $this->hasMany('App\Comment','task_id','id');
    }

}
