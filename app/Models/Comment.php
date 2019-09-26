<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Comment extends Model
{
    //
    protected $table = 'comments';
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function task(){
        return $this->belongsTo('App\Models\Task');
    }

    public function index($id){
        return $this->rightJoin('users','comments.user_id','=','users.id')
                    ->where('comments.task_id','=',$id)
                    ->select('comments.id','comments.task_id','comments.user_id','users.name','comments.message','comments.created_at')
                    ->orderBy('comments.created_at','DESC')
                    ->get();
    }

    public function create($task_id,$message){
        $this->task_id = $task_id;
        $this->user_id = auth()->id();
        $this->message = $message;
        $this->created_at = Carbon::now()->setTimezone('UTC')->format('Y-m-d H:i:s');
        $this->save();
        return $this;
    }
}
