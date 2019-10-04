<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskHasMember extends Model
{
    //
    protected $table = 'task_has_members';
    public $timestamps = false;

    protected $fillable = [
        'role'
    ];

    public function deleteMember($task_ids,$member_id){

        $this->whereIn('task_id',$task_ids)
                                ->where('member_id',$member_id)
                                ->delete();
    }

    public function getMemberIds($task_id){
        return $this->where('task_id',$task_id)->pluck('member_id')->toArray();
    }

    public function deleteMembers($task_id,$remove_members){
        $this->where('task_id',$task_id)->whereIn('member_id',$remove_members)->delete();
    }

    // public function searchTasks($pattern){
    //     return $this->rightJoin('tasks','task_has_members.task_id','=','tasks.id')
    //                 ->where('task_has_members.member_id','=',auth()->id())
    //                 ->where('tasks.title','like','%'.$pattern.'%')
    //                 ->select(['id','project_id','title'])
    //                 ->get();
    // }

    
}
