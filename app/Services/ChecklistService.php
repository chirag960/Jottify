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
        $done = ($request->completed == 1)?true:false;
        $checklist = $this->checklist->create($task_id,$request->message,$done);
        
        (new Task)->incrementCheckListCount($task_id);
        if($done == true){
            (new Task)->incrementCheckListComplete($task_id);
        }

        return response()->json(["message"=>"success","checklist"=>$checklist],201);
    }

    public function update($request,$project_id,$task_id,$id){
        $done = ($request->completed == 1)?true:false;
        
        $this->checklist->setCompletion($id,$done);
        
        if($done == true){
            (new Task)->incrementCheckListComplete($task_id);
        }
        else{
            (new Task)->decrementCheckListComplete($task_id);
        }

        return response()->json(["message"=>"success","checklist_id"=>$id,"completed"=>$done],200);

    }

}