<?php

namespace App\Services;


use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectHasMember;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskHasMember;
use App\Models\TaskDetails;
use App\Jobs\SendEmails;
use Carbon\Carbon;

class TaskService{

    protected $task;

    public function __construct(Task $task){
        $this->task = $task;
    }

    public function index($id){
        $tasks = $this->task->index($id);
        return $tasks;
    }

    public function create(Request $request, $id){
        // $id is project id

        $task = $this->task->create($request->status_id,$id,$request->title,$request->description,$request->due_date);
        (new TaskDetails)->create($task->id,$request->status_id);
        
        return response()->json(
            ['message'=>'success','id' => $task->id,'title'=> $request->title, 'status_id'=> $request->status_id]
        ,201);

    }

    public function delete($project_id,$id){
        $this->task->deleteTask($id);
        return response()->json(["message"=>"success","id"=>$id],200);
    }

    public function getTaskDetails($project_id,$id){
        $task = Task::find($id);
        if(isset($task->due_date)){
            $date = new Carbon($task->due_date);
            $task->due_date = $date->toFormattedDateString();
        }
        $role = (new ProjectHasMember)->getRole($project_id);
        $task->role = $role;
        return $task;
    }

    public function getTaskMembers($project_id,$id){
        $projectMembers = (new ProjectHasMember)->getMembersDetails($project_id);
        $members = (new TaskHasMember)->getMemberIds($id);
        //dd($members);
        foreach($projectMembers as $projectMember){
            if(in_array($projectMember->id, $members) ){
                $projectMember->present = true;
            }
            else{
                $projectMember->present = false;
            }
        }

        return $projectMembers;
    }

    public function getOnlyTaskMembers($project_id,$id){
        $members = Task::find($id)->users;
        if(count($members) == 0){
            return response()->json(["message"=>"error","error"=>"no members found"],200);
        }

        return response()->json(["message"=>"success","members"=>$members],200);
    }

    public function updateduedate(Request $request,$project_id,$id){
       
        $format = "M j, Y";
        $timestamp = DateTime::createFromFormat($format,$request->date);

        $this->task->setDueDate($id,$timestamp);
        return response()->json(['message'=>'success','date'=>$request->date]);
    }

    public function updateDescription(Request $request,$project_id,$id){ 
            $this->task->setDescription($id,$request->description);
            return response()->json(["message"=>"success","description"=>$request->description],200);
    }
    
    public function updateStatus(Request $request,$project_id,$id){
        
        $this->task->setStatus($id,$request->status_id);
        (new TaskDetails)->create($id,$request->status_id);
        return response()->json(['message'=>'success','status_id'=>$request->status_id],200);
    }

    public function assignTask(Request $request,$project_id,$id){
        $present_members = (new TaskHasMember)->getMemberIds($id);
        $request_members = array_map('intval', $request->ids);
        $new_members = array_values(array_diff($request_members,$present_members));
        $remove_members = array_values(array_diff($present_members,$request_members));
        
        for($i = 0; $i <count($new_members); $i++){
            $inserts[] = [
                'task_id' => $id,
                'member_id' => $new_members[$i]
            ];
        }
        if(isset($inserts)){
            TaskHasMember::insert($inserts);
            SendEmails::dispatch($new_members,"assignToTask",Task::find($id));
        }
        if(isset($remove_members)){
            (new TaskHasMember)->deleteMembers($id,$remove_members);
        }
        
        return response()->json(["message"=>"success","members"=>$request_members],200);
    }

    public function titlesList(){

        $pattern = Input::get('pattern');

        $id = auth()->id();
        $projects = (new ProjectHasMember)->searchProjects($pattern);
        $tasks = (new TaskHasMember)->searchTasks($pattern);

        if(count($tasks)){
            foreach($tasks as $task){
                $project_title = (new Project)->getProjectTitle($task->project_id);
                $task->project_title = $project_title;
            }
        }

        $response = array();
        if(count($projects)){
        $response['projects'] = $projects;
        }
        if(count($tasks)){
        $response['tasks'] = $tasks;
        }
        return $response;
    }
}

?>