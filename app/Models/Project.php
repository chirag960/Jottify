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

    public function status(){
        return $this->hasMany('App\Models\Status','project_id','id');
    }


    public function create($title, $description){
        $this->user_id = auth()->id();
        $this->title = $title;
        if(isset($description)){
            $this->description = $description;
        }
        $this->background = "/media/project_background/default.jpg";
        $this->save();
        return $this;
    }

    public function getProjectTitle($project_id){
        return Project::find($project_id)->select('title')->first()->title;
    }

    public function deleteProject($id){
        $this::find($id)->delete();
    }
}
