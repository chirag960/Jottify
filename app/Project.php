<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $table = 'projects';
    public $timestamps = false;

    protected $fillable = ['title','description','background'];

    public function users(){
        return $this->belongsToMany('App\Project', 'project_has_member', 'project_id', 'member_id');
    }

    public function tasks(){
        return $this->hasMany('App\Task','project_id','id');
    }

}
