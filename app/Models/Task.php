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

    public function checkTask($task_id, $project_id){
        return $this->where([['id','=',$task_id],['project_id','=',$project_id]])
                    ->first();
    }

    public function index($id){
        $tasks = $this->where('project_id','=',$id)->orderBy('status_id')->get();
        foreach($tasks as $task){
            $members = Task::find($task->id)->users;
            if($members != "[]"){
                $task->members = $members;
            } 
        }
        return $tasks;
    }

    public function create($status_id,$project_id,$title,$description=null,$due_date=null){
        $this->status_id = $status_id;
        $this->project_id = $project_id;
        $this->title = $title;
        if(isset($description)){
            $this->description = $description;
        }
        if(isset($due_date)){
            $this->due_date = $due_date;
        }
        $this->save();
        return $this;
    }

    public function setDueDate($task_id,$timestamp){
        Task::find($task_id)->update(['due_date'=>$timestamp]);
    }

    public function setDescription($task_id,$description){
        Task::find($task_id)->update(['description'=>$description]);
    }

    public function setStatus($task_id,$status_id){
        Task::find($task_id)->update(['status_id'=>$status_id]);
    }

    public function incrementCommentCount($task_id){
        Task::find($task_id)->increment('comment_count');
    }

    public function incrementAttachmentCount($task_id,$count){
        Task::find($task_id)->increment('attachment_count',$count);
    }

    public function decrementAttachmentCount($task_id){
        Task::find($task_id)->decrement('attachment_count');
    }

    public function incrementCheckListCount($task_id){
        $count = Task::find($task_id)->checklist_item_count;
        if(is_null($count)){
            Task::find($task_id)->update(['checklist_item_count'=>1]);    
        }
        else{
            Task::find($task_id)->increment('checklist_item_count');
        }
    }

    public function incrementCheckListComplete($task_id){
        Task::find($task_id)->increment('checklist_done');
    }

    public function decrementCheckListComplete($task_id){
        Task::find($task_id)->decrement('checklist_done');
    }

    public function getCheckListDetails(){}

    public function deleteTask($id){
        Task::find($id)->delete();
    }

    public function deleteByStatus($status_id){
        $this->where('status_id',$status_id)->delete();
    }

        public function searchTasks($pattern,$project_ids){
        return $this->whereIn('project_id',$project_ids)
                    ->where('tasks.title','like','%'.$pattern.'%')
                    ->select(['id','project_id','title'])
                    ->get();
    }

    

}
