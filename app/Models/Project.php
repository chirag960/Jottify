<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $table = 'projects';
    public $timestamps = false;

    protected $fillable = ['title','description','background'];

    public function users(){
        return $this->belongsToMany('App\Models\Project', 'project_has_members', 'project_id', 'member_id');
    }

    public function tasks(){
        return $this->hasMany('App\Models\Task','project_id','id');
    }

    //public function create($id,$description,)

}
