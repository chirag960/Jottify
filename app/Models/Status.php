<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    protected $table = 'statuses';
    public $timestamps = false;

    protected $fillable = ['order','title','archived'];

    public function project(){
        return $this->belongsTo('App\Models\Project','project_id','id');
    }

    public function index($project_id){
        return $this->where('project_id',$project_id)->where('archived',false)->orderBy('order')->get();
    }

    public function create($project_id,$title,$order){
        $this->project_id = $project_id;
        $this->title = $title;
        $this->order = $order;
        $this->save();
        return $this;
    }

    public function createDefaultStatuses($project_id){
        $default_titles = ['Unassigned','Open','Work In Progress', 'Completed'];
        for($i = 0; $i < count($default_titles); $i++){
            $inserts[] = [
                'project_id' => $project_id,
                'title' => $default_titles[$i],
                'order' => $i
            ];
        }
        Status::insert($inserts);
           
    }

    public function incrementSingleOrder($project_id,$order){
       $this->where('project_id',$project_id)
            ->where('order','>=',$order)
            ->increment('order');
    }

    public function decrementOrder($project_id,$start,$end){
        $this->where('project_id',$project_id)
                ->whereBetween('order',array($start,$end))
                ->decrement('order');
    }

    public function incrementOrder($project_id,$start,$end){
        $this->where('project_id',$project_id)
                ->whereBetween('order',array($start,$end))
                ->increment('order');
    }

    public function setOrder($id,$order){
        $this->where('id',$id)->update(['order'=>$order]);
    }

    public function archiveStatus(){
        $this->update(['order'=>null,'archived'=>true]);
    }

}
