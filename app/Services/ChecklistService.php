<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\DB;
use App\ProjectHasMember;
use App\Project;
use App\Checklist;
use App\Task;

class ChecklistService{

    protected $checklist;

    public function __construct(Checklist $checklist){
        $this->checklist = $checklist;
    }

    public function index($project_id, $id){
        $user_id = auth()->id();
        $checklist = Checklist::where('task_id','=',$id)->get();
        return $checklist;
    }

    public function create(Request $request,$project_id,$task_id){
        $checklist = new Checklist;
        $checklist->task_id = $task_id;
        $checklist->item = $request->message;
        $checklist->completed = false;
        $checklist->save();

        $task = Task::find($task_id);
        $count = $task->checklist_item_count;
        $task->checklist_item_count = ++$count;
        $task->save();

        return $checklist;
    }

    public function update($request,$project_id,$task_id,$id){
        $done = ($request->completed == 1)?true:false;
        //dd($true);
        $checklist = Checklist::find($id);
        $checklist->completed = $done;
        $checklist->save();
        //dd($checklist);
        $task = Task::find($task_id);
        $task->checklist_done = $request->checklist_done;
        $task->save();
        
        $response = DB::table('task')
        ->join('checklist','checklist.task_id','=','task.id')
        ->where('checklist.id',$id)
        ->select('task.checklist_item_count','task.checklist_done','checklist.id','checklist.completed')
        ->get();

        return $response;

    }

}