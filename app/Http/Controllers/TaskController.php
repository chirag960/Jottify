<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Task;
use App\TaskHasMember;
use App\Services\TaskService;
use App\User;
use App\Jobs\SendEmails;
use Illuminate\Support\Facades\Input;

class TaskController extends Controller
{
    
    protected $taskService;

    public function __construct(TaskService $taskService){
        $this->taskService = $taskService;
    }

    public function index($id){
        $tasks = $this->taskService->index($id);
        return $tasks;
    }

    public function create(Request $request, $id){
        // $id is project id
        $values = $this->taskService->create($request, $id);
        return redirect()->action('TaskController@getTaskDetails',['project_id' => $values['project_id'], 'id' => $values['id']]);
    }

    public function getTaskDetails($project_id,$id){
        $task = $this->taskService->getTaskDetails($project_id,$id);
        return view('task')->with('task',$task);
    }

    public function getTaskMembers($project_id,$id){
        $members = $this->taskService->getTaskMembers($project_id,$id);
        return $members;
    }

    public function createduedate(Request $request, $project_id,$id){
         $date = $this->taskService->createduedate($request,$project_id,$id);
        return $date;
    }

    public function createDescription(Request $request, $project_id,$id){
        $desc = $this->taskService->createDescription($request,$project_id,$id);
       return $desc;
   }

   public function updateStatus(Request $request, $project_id,$id){
        $status = $this->taskService->updateStatus($request,$project_id,$id);
        return $status;
    }

    public function assignTask(Request $request, $project_id,$id){
        $status = $this->taskService->assignTask($request,$project_id,$id);
        return $status;
    }

    public function assignTask2(Request $request, $project_id,$id){
        $task = Task::find($id);
            $user = User::find(Input::get('uid'));
            SendEmails::dispatch($user,"assignToTask",$task);
    }

    public function titlesList(){
        $titleList = $this->taskService->titlesList();
        return $titleList;
    }

}
