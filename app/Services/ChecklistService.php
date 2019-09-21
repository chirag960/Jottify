<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectHasMember;
use App\Models\Project;
use App\Models\Checklist;
use App\Models\Task;

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
        $checklist->completed = ($request->completed == 1)?true:false;
        $checklist->save();

        $task = Task::find($task_id);
        $count = $task->checklist_item_count;
        $task->checklist_item_count = ++$count;
        if($request->completed == 1){
            $count = $task->checklist_done;
            $task->checklist_done = ++$count;    
        }
        $task->save();

        return response()->json(["message"=>"success","checklist"=>$checklist],201);
    }

    public function update($request,$project_id,$task_id,$id){
        $done = ($request->completed == 1)?true:false;
        
        $checklist = Checklist::find($id);
        $checklist->completed = $done;
        $checklist->save();
        
        $task = Task::find($task_id);
        $task->checklist_done = $request->checklist_done;
        $task->save();
        
        $response = DB::table('tasks')
        ->join('checklists','checklists.task_id','=','tasks.id')
        ->where('checklists.id',$id)
        ->select('tasks.checklist_item_count','tasks.checklist_done','checklists.id','checklists.completed')
        ->get();

        return $response;

    }

}